<?php

namespace App\Observers;

use App\Jobs\NotifyAdminsOfNewUser;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        $user->assignRole('User');

        NotifyAdminsOfNewUser::dispatch($user);
    }
}
