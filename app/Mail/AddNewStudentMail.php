<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddNewStudentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build(): self
    {

        return $this
            ->to([
                'support@abt-assessments.com',
            ])
            ->cc([
                'it@abt-assessments.com',
                'operations@abt-assessments.com',
                'communication@abt-assessments.com',
            ])
            //New Student Added To My First Lang Platform
            ->subject('إضافة طالب جديد إلى منصة لغتي الأولى')
            ->view('mail.new_student', ['user' => $this->user]);
    }
}
