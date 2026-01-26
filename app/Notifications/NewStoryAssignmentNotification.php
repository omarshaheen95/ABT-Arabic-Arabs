<?php

namespace App\Notifications;

use App\Models\Story;
use App\Models\UserStoryAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewStoryAssignmentNotification extends Notification
{
    use Queueable;
    public $userAssignment;
    public $story;
    public $message;

    public function __construct(UserStoryAssignment $userAssignment, Story $story)
    {
        $this->userAssignment = $userAssignment;
        $this->story = $story;
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
            "model_id" => $this->userAssignment->story_assignment_id,
            'model_type' => \App\Models\StoryAssignment::class,
            'title'=>'New Story Assignment',
            'message'=>'('.$this->story->name.')'.' Story Assignment and will expired at '.' [ '.$this->userAssignment->deadline->format('d M Y h:i A').' ] ',
            "other" => [
                "user_assignment_id" => $this->userAssignment->id,
                "story_id" => $this->story->id,
                "story_name" => $this->story->getOriginal('name'),
                "deadline" => $this->userAssignment->deadline,
                'icon_type'=>'story',
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
            "title" => t("New Story Assignment"),
            'details'=>'('.$this->story->name.')'.' Story Assignment and will expired at '.' [ '.$this->userAssignment->deadline->format('d M Y h:i A').' ] ',
        ];
        $this->message['others'] = [
            "model_id" => $this->userAssignment->story_assignment_id,
            'model_type' => \App\Models\StoryAssignment::class,
            "user_assignment_id" => $this->userAssignment->id,
            "story_id" => $this->story->id,
            "story_name" => $this->story->getOriginal('name'),
            "deadline" => $this->userAssignment->deadline,
            'icon_type'=>'story',
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
