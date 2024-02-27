<?php

namespace App\Services\User\Tasks;

use App\Models\User;
use App\Services\Task;
use Exception;

class CreateUserTask extends Task
{

    /**
     * Execute task
     *
     * @param array $data Data login
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function run(array $data)
    {
        $data['password'] = bcrypt($data['password'] ?? '');
        return User::create($data);
    }
}
