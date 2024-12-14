<?php

namespace App\Observers;


use App\Models\School;

class SchoolObserver
{

    public function created(School $school): void
    {
      $school->assignRole('School');
    }
}
