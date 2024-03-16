<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

/**
 * Class RecordOwnerObserver
 */
class RecordOwnerObserver
{
    /**
     * Handle the User "created" event.
     *
     * @return void
     */
    public function creating($record)
    {
        $createdBy = $record->getAddedByColumn();
        $record->$createdBy = Auth::check() ? Auth::id() : null;
    }

    /**
     * Handle the User "updated" event.
     *
     * @return void
     */
    public function updating($record)
    {
        $updatedBy = $record->getUpdatedByColumn();
        $record->$updatedBy = Auth::check() ? Auth::id() : null;
    }
}
