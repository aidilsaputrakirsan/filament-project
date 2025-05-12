<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\User;
use App\Policies\AttendancePolicy;
use App\Policies\CoursePolicy;
use App\Policies\AssignmentPolicy;
use App\Policies\QuizPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Course::class => CoursePolicy::class,
        Assignment::class => AssignmentPolicy::class,
        Quiz::class => QuizPolicy::class,
        Attendance::class => AttendancePolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}