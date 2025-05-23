<?php
/*
Dev Omar Shaheen
Devomar095@gmail.com
WhatsApp +972592554320
*/

namespace App\Providers;

use App\Interfaces\LessonAssignmentRepositoryInterface;
use App\Interfaces\LessonTestRepositoryInterface;
use App\Interfaces\MotivationalCertificateRepositoryInterface;
use App\Interfaces\StoryAssignmentRepositoryInterface;
use App\Interfaces\StoryRecordRepositoryInterface;
use App\Interfaces\StoryTestRepositoryInterface;
use App\Interfaces\SupervisorRepositoryInterface;
use App\Interfaces\TeacherRepositoryInterface;
use App\Interfaces\UserLessonAssignmentRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserStoryAssignmentRepositoryInterface;
use App\Repositories\LessonAssignmentRepository;
use App\Repositories\LessonTestRepository;
use App\Repositories\MotivationalCertificateRepository;
use App\Repositories\StoryAssignmentRepository;
use App\Repositories\StoryRecordRepository;
use App\Repositories\StoryTestRepository;
use App\Repositories\SupervisorRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserLessonAssignmentRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserStoryAssignmentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SupervisorRepositoryInterface::class, SupervisorRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(LessonTestRepositoryInterface::class, LessonTestRepository::class);
        $this->app->bind(StoryTestRepositoryInterface::class, StoryTestRepository::class);
        $this->app->bind(StoryRecordRepositoryInterface::class, StoryRecordRepository::class);
        $this->app->bind(LessonAssignmentRepositoryInterface::class, LessonAssignmentRepository::class);
        $this->app->bind(UserLessonAssignmentRepositoryInterface::class, UserLessonAssignmentRepository::class);
        $this->app->bind(StoryAssignmentRepositoryInterface::class, StoryAssignmentRepository::class);
        $this->app->bind(UserStoryAssignmentRepositoryInterface::class, UserStoryAssignmentRepository::class);
        $this->app->bind(MotivationalCertificateRepositoryInterface::class, MotivationalCertificateRepository::class);

    }

    public function boot(): void
    {
    }
}
