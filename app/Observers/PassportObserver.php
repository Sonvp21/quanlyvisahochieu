<?php

namespace App\Observers;

use App\Models\Passport;
use App\Models\EmailNotification;

class PassportObserver
{
    public function updated(Passport $passport): void
    {
        if ($passport->isDirty(['expiry_date', 'issue_date', 'image'])) {
            EmailNotification::where('student_id', $passport->student_id)
                ->where('notifiable_type', 'passport')
                ->where('notifiable_id', $passport->id)
                ->where('status', 'pending')
                ->update(['status' => 'stopped']);
        }
    }
}
