<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index()
    {
        $studentId = Auth::id();

        // 1. Get assignments already submitted by the student (including teacher info)
        $completedSubmissions = Submission::where('student_id', $studentId)
            ->with(['assignment.teacher'])
            ->latest()
            ->get();

        $submittedIds = $completedSubmissions->pluck('assignment_id');

        // 2. Get pending assignments (Not submitted and not expired)
        $pendingAssignments = Assignment::with('teacher')
            ->whereDate('estimated_date', '>=', Carbon::now())
            ->whereNotIn('id', $submittedIds)
            ->latest()
            ->paginate(10);

        return view('student.index', compact('pendingAssignments', 'completedSubmissions'));
    }

    public function start($id)
    {
        $assign = Assignment::findOrFail($id);
        $submit = Submission::where('assignment_id', $id)
            ->where('student_id', Auth::id())
            ->first();

        if (Carbon::now()->gt($assign->estimated_date) && !$submit) {
            return redirect()->route('dashboard')
                ->with(['status' => 'error', 'response' => 'This assignment is now closed.']);
        }

        return view('student._form', compact('assign', 'submit'));
    }

    public function saveSubmission(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        if (Carbon::now()->gt($assignment->estimated_date)) {
            return redirect()->route('dashboard')
                ->with(['status' => 'error', 'response' => 'The submission deadline has passed.']);
        }

        $this->validateSubmission($request);

        // Data array - strictly using columns from your migration
        $data = [
            'short_answer'             => $request->short_answer,
            'choice_answer'            => $request->choice_answer, // Matches fixed migration
            'yes_no_answer'            => $request->yes_no_answer,
            'multiple_choices_answer'  => $request->multiple_choices_answer,
            'multiple_response_answer' => $request->multiple_response_answer,
        ];

        Submission::updateOrCreate(
            ['assignment_id' => $id, 'student_id' => Auth::id()],
            $data
        );

        return redirect()->route('dashboard')
            ->with(['status' => 'success', 'response' => 'Your work has been submitted successfully!']);
    }

    private function validateSubmission(Request $request): array
    {
        return $request->validate([
            'type' => 'required|in:short_answer,choices,yes_no,multiple_choice,multiple_response',
            'short_answer' => 'required_if:type,short_answer|nullable|string|max:1000',
            'yes_no_answer' => 'required_if:type,yes_no|nullable|boolean',
            'choice_answer' => 'required_if:type,choices|nullable|string',
            'multiple_choices_answer'   => 'required_if:type,multiple_choice|nullable|array',
            'multiple_response_answer'   => 'required_if:type,multiple_response|nullable|array',
        ]);
    }
}