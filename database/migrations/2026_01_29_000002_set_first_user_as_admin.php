<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Asignar el rol 'admin' al primer usuario (ID 1) si existe
        DB::table('users')
            ->where('id', 1)
            ->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir todos los roles a 'user'
        DB::table('users')->update(['role' => 'user']);
    }
};
