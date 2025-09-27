<?php

namespace App\Observers;

use App\Models\Audit;
use App\Models\Session;

class SessionObserver
{
    /**
     * Handle the Session "created" event.
     */
    public function created(Session $session): void
    {
        //
    }

    /**
     * Handle the Session "updated" event.
     */
    public function updated(Session $session): void
    {
        $oldData = $session->getOriginal();
        $newData = $session->toArray();

        Audit::create([
            'table_name' => 'sessions',
            'record_id' => $session->id,
            'user_id' => auth()->id(),
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

    /**
     * Handle the Session "deleted" event.
     */
    public function deleted(Session $session): void
    {
        //
    }

    /**
     * Handle the Session "restored" event.
     */
    public function restored(Session $session): void
    {
        //
    }

    /**
     * Handle the Session "force deleted" event.
     */
    public function forceDeleted(Session $session): void
    {
        //
    }
}
