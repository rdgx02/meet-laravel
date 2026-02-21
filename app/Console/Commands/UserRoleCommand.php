<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UserRoleCommand extends Command
{
    /**
     * Nome do comando
     */
    protected $signature = 'user:role {user_id} {role}';

    /**
     * Descrição
     */
    protected $description = 'Define o papel (role) de um usuário: admin, secretary ou user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $role   = strtolower($this->argument('role'));

        // validar role
        if (!in_array($role, ['admin', 'secretary', 'user'])) {
            $this->error('Role inválida. Use: admin, secretary ou user');
            return Command::FAILURE;
        }

        // buscar usuário
        $user = User::find($userId);

        if (!$user) {
            $this->error("Usuário ID {$userId} não encontrado.");
            return Command::FAILURE;
        }

        // atualizar role
        $user->role = $role;
        $user->save();

        $this->info("✅ Usuário {$user->name} agora é {$role}.");

        return Command::SUCCESS;
    }
}