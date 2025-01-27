<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Observers;

use App\Mail\ActivateNewStudentMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    public function updated(User $user): void
    {
        if (Auth::guard('manager')->check()) {
            if ($user->wasChanged('active') && $user->active == 1 && !is_null($user->direct_email) && !is_null($user->added_by_type) && !is_null($user->added_by_id)) {
                //send email
                Mail::send(new ActivateNewStudentMail($user->load(['addedBy'])));
            }
        }
    }
}
