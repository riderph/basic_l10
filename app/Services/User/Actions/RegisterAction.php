<?php

namespace App\Services\User\Actions;

use App\Services\Action;
use App\Services\User\Tasks\CreateUserTask;

class RegisterAction extends Action
{

    /**
     * Execute action
     *
     * @param array $data Data
     *
     * @return mixed
     */
    public function run(array $data)
    {
        return resolve(CreateUserTask::class)->run($data);
    }
}
