<?php

namespace App\Jobs;

use App\Events\MembershipHasExpired;
use App\Models\Membership;
use Illuminate\Bus\Batchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckMembershipStatus implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels, Batchable;

    public $timeout = 120;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = now()->toDateString();

        // Deactivate memberships that have expired (end_date before today)
        Membership::where('active', true)
            ->where('end_date', '<', $today)
            ->chunk(100, function ($memberships) {
                foreach ($memberships as $membership) {
                    $membership->update(['active' => false]);

                    event(new MembershipHasExpired($membership));
                }
            });

        // Reactivate memberships that were extended (end_date is today or in the future)
        Membership::where('active', false)
            ->where('end_date', '>=', $today)
            ->chunk(100, function ($memberships) {
                foreach ($memberships as $membership) {
                    $membership->update(['active' => true]);
                }
            });
    }
}
