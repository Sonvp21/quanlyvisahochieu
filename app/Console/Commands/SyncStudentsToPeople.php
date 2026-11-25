<?php

namespace App\Console\Commands;

use App\Models\Passport;
use App\Models\Person;
use App\Models\Student;
use App\Models\Visa;
use Illuminate\Console\Command;

class SyncStudentsToPeople extends Command
{
    protected $signature = 'people:sync-students';

    protected $description = 'Tạo hồ sơ "người" cho mọi sinh viên quốc tế hiện có và gắn hộ chiếu/visa vào đó (chạy lại nhiều lần vẫn an toàn)';

    public function handle(): int
    {
        $created = 0;

        Student::with('user')->chunkById(100, function ($students) use (&$created) {
            foreach ($students as $student) {
                $person = Person::firstOrCreate(
                    ['student_id' => $student->id],
                    [
                        'person_type'   => 'student_intl',
                        'full_name'     => $student->full_name,
                        'gender'        => $student->gender,
                        'date_of_birth' => $student->date_of_birth,
                        'nationality'   => $student->nationality ?: 'Việt Nam',
                        'student_code'  => $student->student_code,
                        'email'         => $student->user?->email,
                        'phone'         => $student->phone,
                        'user_id'       => $student->user_id,
                        'is_active'     => true,
                    ]
                );

                if ($person->wasRecentlyCreated) {
                    $created++;
                }

                Passport::where('student_id', $student->id)->whereNull('person_id')->update(['person_id' => $person->id]);
                Visa::where('student_id', $student->id)->whereNull('person_id')->update(['person_id' => $person->id]);
            }
        });

        $this->info("Hoàn tất. Tạo mới {$created} hồ sơ; tổng số hồ sơ sinh viên quốc tế: " . Person::where('person_type', 'student_intl')->count());

        return self::SUCCESS;
    }
}
