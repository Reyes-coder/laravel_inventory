<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign-role {user_id} {role : admin|user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asignar un rol a un usuario (admin o user)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $userId = $this->argument('user_id');
        $role = $this->argument('role');

        // Validar que el rol sea válido
        if (!in_array($role, ['admin', 'user'])) {
            $this->error('El rol debe ser "admin" o "user"');
            return 1;
        }

        $user = User::find($userId);

        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado");
            return 1;
        }

        $oldRole = $user->role;
        $user->update(['role' => $role]);

        $this->info("✓ Usuario '{$user->name}' actualizado");
        $this->line("  Rol anterior: <fg=yellow>{$oldRole}</>");
        $this->line("  Rol nuevo:   <fg=green>{$role}</>");

        return 0;
    }
}
