<?php

namespace App\Observers;


use App\Models\School;
use App\Models\Teacher;

class TeacherObserver
{

    public function created(Teacher $teacher): void
    {
        $teacher->assignRole('Teacher');
    }
}
