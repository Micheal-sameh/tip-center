<?php

namespace App\Observers;

use App\Models\Audit;
use App\Models\SessionStudent;

class SessionStudentObserver
{
    /**
     * Handle the SessionStudent "created" event.
     */
    public function created(SessionStudent $sessionStudent): void
    {
        //
    }

    /**
     * Handle the SessionStudent "updated" event.
     */
    public function updated(SessionStudent $sessionStudent): void
    {
        $oldData = $sessionStudent->getOriginal();
        $newData = $sessionStudent->toArray();

        Audit::create([
            'table_name' => 'session_students',
            'record_id' => $sessionStudent->id,
            'user_id' => auth()->id(),
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

    /**
     * Handle the SessionStudent "deleted" event.
     */
    public function deleted(SessionStudent $sessionStudent): void
    {
        //
    }

    /**
     * Handle the SessionStudent "restored" event.
     */
    public function restored(SessionStudent $sessionStudent): void
    {
        //
    }

    /**
     * Handle the SessionStudent "force deleted" event.
     */
    public function forceDeleted(SessionStudent $sessionStudent): void
    {
        //
    }
}
