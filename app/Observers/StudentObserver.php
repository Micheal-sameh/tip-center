<?php

namespace App\Observers;

use App\Models\Audit;
use App\Models\Student;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        $oldData = $student->getOriginal();
        $newData = $student->toArray();

        Audit::create([
            'table_name' => 'students',
            'record_id' => $student->id,
            'user_id' => auth()->id(),
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        Audit::create([
            'table_name' => 'students',
            'record_id' => $student->id,
            'user_id' => auth()->id(),
            'old_data' => $student->toArray(),
            'new_data' => null,
        ]);
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
