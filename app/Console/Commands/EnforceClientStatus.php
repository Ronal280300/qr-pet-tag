<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class EnforceClientStatus extends Command
{
    protected $signature = 'clients:enforce-status';
    protected $description = 'Set pending users to inactive after 5 days';

    public function handle(): int
    {
        $now = now();

        $affected = User::where('status', User::STATUS_PENDING)
            ->where(function($q){
                $q->whereNotNull('pending_since')
                  ->orWhereNotNull('status_changed_at');
            })
            ->get()
            ->filter(function($u) use ($now) {
                $start = $u->pending_since ?? $u->status_changed_at ?? $u->created_at;
                return $start && $now->diffInDays($start) >= 5;
            });

        foreach ($affected as $u) {
            $u->status = User::STATUS_INACTIVE;
            $u->status_changed_at = now();
            $u->save();
        }

        $this->info('Users inactivated: '.$affected->count());
        return self::SUCCESS;
    }
}
