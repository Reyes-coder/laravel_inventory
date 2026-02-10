<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetea la base de datos con seeders (cuidado: elimina todos los datos)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Reseteando Base de Datos...');
        $this->warn('⚠️  Esto eliminará TODOS los datos actuales');
        $this->newLine();

        if (!$this->confirm('¿Estás seguro?')) {
            $this->error('❌ Cancelado');
            return 1;
        }

        $this->newLine();
        $this->info('🗑️  Eliminando todas las tablas y ejecutando migraciones...');

        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);

        $this->info('✅ Base de datos reseteada correctamente!');
        $this->newLine();
        $this->line('🔑 <fg=green;options=bold>CREDENCIALES PARA ACCEDER:</fg=green;options=bold>');
        $this->newLine();

        $this->line('<fg=yellow>👑 ADMIN (Samuel Reyes Castro)</>');
        $this->line('   Email: <fg=cyan>samuelreyescastro456@gmail.com</>');
        $this->line('   Contraseña: <fg=cyan>Admin@2026!</>', null);
        $this->newLine();

        $this->line('<fg=yellow>👤 USER 1 (Juan Pérez)</>');
        $this->line('   Email: <fg=cyan>juan.perez@example.com</>');
        $this->line('   Contraseña: <fg=cyan>Juan@Perez123</>', null);
        $this->newLine();

        $this->line('<fg=yellow>👤 USER 2 (María García)</>');
        $this->line('   Email: <fg=cyan>maria.garcia@example.com</>');
        $this->line('   Contraseña: <fg=cyan>Maria@Garcia456</>', null);
        $this->newLine();

        $this->line('<fg=green;options=bold>🚀 Inicia el servidor con: php artisan serve</>');

        return 0;
    }
}
