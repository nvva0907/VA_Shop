<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ServeWithVite extends Command
{
    protected $signature = 'serve:with-vite';
    protected $description = 'Chạy Laravel và Vite cùng lúc';

    public function handle()
    {
        // Tự động tạo & cập nhật database
        $this->info("🔄 Tạo và cập nhật database...");
        $migrateProcess = new Process(['php', 'artisan', 'make:auto-migration']);
        $migrateProcess->setTimeout(null);
        $migrateProcess->run(function ($type, $buffer) {
            echo $buffer;
        });
        sleep(5); // Chờ tự động tạo & cập nhật database
        $this->info("Đang khởi chạy Laravel...");

        // Chạy Laravel
        $laravelProcess = new Process(['php', 'artisan', 'serve']);
        $laravelProcess->start();

        sleep(2); // Chờ Laravel chạy ổn định

        $this->info("Đang khởi chạy Vite...");
        
        // Chạy Vite
        $viteProcess = new Process(['npm', 'run', 'dev']);
        $viteProcess->setTimeout(null);
        $viteProcess->run(function ($type, $buffer) {
            echo $buffer;
        });

        return 0;
    }
}
