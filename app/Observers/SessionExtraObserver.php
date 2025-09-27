<?php

namespace App\Observers;

use App\Models\Audit;
use App\Models\SessionExtra;

class SessionExtraObserver
{
    /**
     * Handle the SessionExtra "created" event.
     */
    public function created(SessionExtra $sessionExtra): void
    {
        //
    }

    /**
     * Handle the SessionExtra "updated" event.
     */
    public function updated(SessionExtra $sessionExtra): void
    {
        $oldData = $sessionExtra->getOriginal();
        $newData = $sessionExtra->toArray();

        Audit::create([
            'table_name' => 'session_extras',
            'record_id' => $sessionExtra->id,
            'user_id' => auth()->id(),
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

    /**
     * Handle the SessionExtra "deleted" event.
     */
    public function deleted(SessionExtra $sessionExtra): void
    {
        //
    }

    /**
     * Handle the SessionExtra "restored" event.
     */
    public function restored(SessionExtra $sessionExtra): void
    {
        //
    }

    /**
     * Handle the SessionExtra "force deleted" event.
     */
    public function forceDeleted(SessionExtra $sessionExtra): void
    {
        //
    }
}
