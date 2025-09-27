<?php

namespace App\Observers;

use App\Models\Audit;
use App\Models\Professor;

class ProfessorObserver
{
    /**
     * Handle the Professor "created" event.
     */
    public function created(Professor $professor): void
    {
        //
    }

    /**
     * Handle the Professor "updated" event.
     */
    public function updated(Professor $professor): void
    {
        $oldData = $professor->getOriginal();
        $newData = $professor->toArray();

        Audit::create([
            'table_name' => 'professors',
            'record_id' => $professor->id,
            'user_id' => auth()->id(),
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

    /**
     * Handle the Professor "deleted" event.
     */
    public function deleted(Professor $professor): void
    {
        //
    }

    /**
     * Handle the Professor "restored" event.
     */
    public function restored(Professor $professor): void
    {
        //
    }

    /**
     * Handle the Professor "force deleted" event.
     */
    public function forceDeleted(Professor $professor): void
    {
        //
    }
}
