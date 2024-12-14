<?php

namespace App\Observers;


use App\Models\School;
use App\Models\Supervisor;

class SupervisorObserver
{

    public function created(Supervisor $supervisor): void
    {
        $supervisor->assignRole('Supervisor');
    }
}
