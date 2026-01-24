<?php

namespace App\Notifications;

use App\Channels\PusherChannel;
use App\Models\Lesson;
use App\Models\UserAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLessonAssignmentNotification extends Notification
{
    use Queueable;
    public $userAssignment;
    public $lesson;
    public $message;

    public function __construct(UserAssignment $userAssignment, Lesson $lesson)
    {
        $this->userAssignment = $userAssignment;
        $this->lesson = $lesson;
    }

    public function via($notifiable)
    {
        // Currently only using database, but pusher is ready when needed
        return ['database'];

        // Uncomment to enable pusher notifications:
        // return ['database', PusherChannel::class];
    }

    public function toArray($notifiable)
    {
        return $this->message;
    }

    public function toDatabase($notifiable)
    {
        return [
            "model_id" => $this->userAssignment->lesson_assignment_id,
            'model_type' => \App\Models\LessonAssignment::class,
            'title'=>'New Lesson Assignment',
            'message'=>'('.$this->lesson->getTranslation('name','en').')'.' Lesson Assignment and will expired at '.' [ '.$this->userAssignment->deadline->format('d M Y h:i A').' ] ',
            "other" => [
                "user_assignment_id" => $this->userAssignment->id,
                "lesson_id" => $this->lesson->id,
                "lesson_name" => $this->lesson->getOriginal('name'),
                "deadline" => $this->userAssignment->deadline,
                'icon_type'=>'lesson',
            ],


        ];
    }

    /**
     * Send pusher notification (optional - currently disabled)
     * Uncomment via() method above to enable pusher notifications
     */
    public function toPusher($notifiable)
    {
        // Note: User model needs setLanguage() method for this to work
        // You can add this method to User model when enabling pusher:
        // public function setLanguage() {
        //     $locale = $this->local ?? config("app.fallback_locale");
        //     app()->setLocale($locale);
        //     return true;
        // }

        if (method_exists($notifiable, 'setLanguage')) {
            $notifiable->setLanguage();
        }

        $this->message = [
            "title" => t("New Lesson Assignment"),
            'details'=>'('.$this->lesson->getTranslation('name','en').')'.' Lesson Assignment and will expired at '.' [ '.$this->userAssignment->deadline->format('d M Y h:i A').' ] ',
        ];
        $this->message['others'] = [
            "model_id" => $this->userAssignment->lesson_assignment_id,
            'model_type' => \App\Models\LessonAssignment::class,
            "user_assignment_id" => $this->userAssignment->id,
            "lesson_id" => $this->lesson->id,
            "lesson_name" => $this->lesson->getOriginal('name'),
            "deadline" => $this->userAssignment->deadline,
            'icon_type'=>'lesson',
        ];
        send_push_to_pusher('user_'. $notifiable->id, 'user-notification', [
            'title' => $this->message["title"],
            'body' => $this->message["details"],
            'id' => $this->id,
            'unread_notifications' => $notifiable->unread_notifications
        ]);
    }

    public function toMail($notifiable)
    {
        // Mail notification can be implemented later if needed
    }
}
