<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Get teacher submissions (paginated)
     */
    protected function teacherSubmissions(int $perPage = 10)
    {
        return Submission::whereHas('assignment', function ($q) {
            $q->where('teacher_id', Auth::id());
        })->latest()->paginate($perPage);
    }
}
