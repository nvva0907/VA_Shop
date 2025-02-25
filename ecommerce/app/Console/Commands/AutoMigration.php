<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;

class AutoMigration extends Command
{
    protected $signature = 'make:auto-migration';
    protected $description = 'Tự động tạo hoặc cập nhật migration dựa trên Model';

    public function handle()
    {
        $modelsPath = app_path('Models');
        if (!File::exists($modelsPath)) {
            $this->error("Thư mục Models không tồn tại!");
            return;
        }

        $models = collect(File::files($modelsPath))
            ->map(fn($file) => pathinfo($file->getFilename(), PATHINFO_FILENAME));

        foreach ($models as $model) {
            $tableName = Str::snake(Str::pluralStudly($model));
            $modelClass = "App\\Models\\$model";

            if (!class_exists($modelClass))
                continue;

            $modelInstance = new $modelClass;
            $fillable = $modelInstance->getFillable();
            $columnTypes = $this->getColumnTypes($modelClass);

            if (!Schema::hasTable($tableName)) {
                // Tạo mới migration với đầy đủ cột từ Model
                $this->info("🆕 Tạo migration cho bảng '$tableName'...");
                $migrationName = "create_{$tableName}_table";
                $migrationFile = database_path("migrations/" . date('Y_m_d_His') . "_$migrationName.php");
                $migrationContent = $this->generateCreateTableMigration($tableName, $fillable, $columnTypes);
                File::put($migrationFile, $migrationContent);
            } else {
                // Nếu bảng đã tồn tại -> Cập nhật
                $existingColumns = Schema::getColumnListing($tableName);

                // Xác định cột cần thêm
                $newColumns = array_diff($fillable, $existingColumns);
                // Xác định cột cần xóa
                $removedColumns = array_diff($existingColumns, array_merge($fillable, ['id', 'created_at', 'updated_at']));

                if (!empty($newColumns) || !empty($removedColumns)) {
                    $this->info("🔄 Cập nhật migration cho bảng '$tableName'...");
                    $migrationName = "update_{$tableName}_table_" . time();
                    $migrationFile = database_path("migrations/" . date('Y_m_d_His') . "_$migrationName.php");
                    $migrationContent = $this->generateMigrationContent($tableName, $newColumns, $removedColumns, $columnTypes);
                    File::put($migrationFile, $migrationContent);
                }
            }
        }

        $this->info("✅ Quá trình tự động tạo migration hoàn tất.");

        // Chạy migrate ngay sau khi tạo migration
        $this->info("🚀 Chạy migrate...");
        $this->call('migrate');
    }

    /**
     * Lấy kiểu dữ liệu từ annotation @property trong Model.
     */
    protected function getColumnTypes($modelClass)
    {
        $reflection = new ReflectionClass($modelClass);
        $docComment = $reflection->getDocComment();
        $types = [];

        if ($docComment) {
            preg_match_all('/@property (\w+) \$([\w]+)/', $docComment, $matches);
            foreach ($matches[2] as $index => $column) {
                $types[$column] = $matches[1][$index];
            }
        }

        return $types;
    }

    /**
     * Tạo nội dung migration khi tạo bảng mới.
     */
    protected function generateCreateTableMigration($tableName, $columns, $columnTypes)
    {
        $columnsMigration = "";
        $typeMapping = [
            'int' => 'integer',
            'integer' => 'integer',
            'float' => 'decimal',
            'double' => 'decimal',
            'decimal' => 'decimal',
            'bool' => 'boolean',
            'boolean' => 'boolean',
            'text' => 'text',
            'string' => 'string'
        ];

        foreach ($columns as $column) {
            $type = $columnTypes[$column] ?? 'string';
            $mappedType = $typeMapping[$type] ?? 'string';

            // Đảm bảo kiểu decimal có đúng tham số
            if ($mappedType === 'decimal') {
                $columnsMigration .= "            \$table->$mappedType('$column', 10, 2)->nullable();\n";
            } else {
                $columnsMigration .= "            \$table->$mappedType('$column')->nullable();\n";
            }
            
        }

        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('$tableName', function (Blueprint \$table) {
            \$table->id();
$columnsMigration
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('$tableName');
    }
};
PHP;
    }


    /**
     * Tạo nội dung migration để thêm và xóa cột.
     */
    protected function generateMigrationContent($tableName, $newColumns, $removedColumns, $columnTypes)
    {
        $upOperations = '';
        $downOperations = '';

        foreach ($newColumns as $column) {
            $type = $columnTypes[$column] ?? 'string';
            $type = match ($type) {
                'int', 'integer' => 'integer',
                'float', 'double', 'decimal' => 'decimal(10,2)',
                'bool', 'boolean' => 'boolean',
                'text' => 'text',
                default => 'string'
            };
            $upOperations .= "\$table->$type('$column')->nullable();\n";
        }

        foreach ($removedColumns as $column) {
            $downOperations .= "\$table->dropColumn('$column');\n";
        }

        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            $upOperations
        });
    }

    public function down(): void
    {
        Schema::table('$tableName', function (Blueprint \$table) {
            $downOperations
        });
    }
};
PHP;
    }
}
