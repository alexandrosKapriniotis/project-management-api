<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyAdminsOfNewUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newUser;

    /**
     * Create a new job instance.
     *
     * @param User $newUser
     * @return void
     */
    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        User::select('id','email')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Admin');
            })
            ->chunkById(100, function ($admins) {
                foreach ($admins as $admin) {
                    Log::info("Admin {$admin->email} notified about new user: {$this->newUser->email}");
                }
            });
    }
}
