<?php

namespace App\Observers;

use App\Models\Visa;
use App\Models\EmailNotification;

class VisaObserver
{
    public function updated(Visa $visa): void
    {
        if ($visa->isDirty(['expiry_date', 'issue_date', 'image'])) {
            EmailNotification::where('student_id', $visa->student_id)
                ->where('notifiable_type', 'visa')
                ->where('notifiable_id', $visa->id)
                ->where('status', 'pending')
                ->update(['status' => 'stopped']);
        }
    }
}
