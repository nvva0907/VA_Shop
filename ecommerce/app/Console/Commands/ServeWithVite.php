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
