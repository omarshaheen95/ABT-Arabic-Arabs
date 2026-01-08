<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\StoryAssignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public function read($id)
    {
        $notification = $this->user->notifications()->find($id);

        if (!$notification) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
            }
            return redirect()->route('home')
                ->with('message', 'Notification not found')->with('m-type', 'error');
        }

        // Mark as read
        $notification->markAsRead();

        // Get redirect URL from notification data
        $redirectUrl = $notification->data['url'] ?? $this->getRedirectUrl($notification->data['other']['user_assignment_id'], $notification->data['model_type']);

        // If it's an AJAX request, return JSON
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl,
                'message' => 'Notification marked as read'
            ]);
        }

        // If it's a direct navigation, redirect to the notification URL
        return redirect($redirectUrl);
    }

    public function readAll()
    {
        $this->user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    private function getRedirectUrl($model_id, $model_type)
    {
        switch ($model_type) {
            case 'App\Models\LessonAssignment':
                return route('lesson.assignments', ['assignment_id' => $model_id]);
            case 'App\Models\StoryAssignment':
                return route('story.assignments', ['assignment_id' => $model_id]);
            default:
                return null;
        }
    }
}
