<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = Auth::id();
        $assignments = Assignment::where('teacher_id', $teacherId)->latest()->paginate(10);

        // Filter submissions if assignment_id is present in URL
        $query = Submission::whereHas('assignment', function($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        });

        if ($request->has('assignment_id')) {
            $query->where('assignment_id', $request->assignment_id);
        }

        $submissions = $query->with(['student', 'assignment'])->latest()->paginate(15);

        return view('teacher.index', compact('assignments', 'submissions'));
    }

    public function new()
    {
        return view('teacher._form', ['assignment' => null]);
    }

    public function newAssignment(Request $request)
    {
        $validated = $this->validateAssignment($request);
        $data = $this->normalizeAssignmentData($validated);
        $data['teacher_id'] = Auth::id();

        Assignment::create($data);

        return redirect()->route('dashboard')->with(['status'=>'success', 'response'=>'Assignment created successfully.']);
    }

    public function edit($id)
    {
        $assignment = Assignment::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        return view('teacher._form', compact('assignment'));
    }

    public function updateAssignment(Request $request, $id)
    {
        $assignment = Assignment::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->firstOrFail();

        $validated = $this->validateAssignment($request);
        $data = $this->normalizeAssignmentData($validated);

        $assignment->update($data);

        return redirect()->route('dashboard')->with(['status'=>'success', 'response'=>'Assignment updated successfully.']);
    }

    public function dropAssignment($id)
    {
        Assignment::where('id', $id)
            ->where('teacher_id', Auth::id())
            ->delete();

        return redirect()->route('dashboard')->with(['status'=>'success','response'=> 'Assignment deleted.']);
    }
    public function reviewSubmission($id)
    {
        // Ensure the teacher only sees submissions for THEIR assignments
        $sub = Submission::with(['student', 'assignment'])
            ->whereHas('assignment', function($q) {
                $q->where('teacher_id', Auth::id());
            })
            ->findOrFail($id);

        return view('teacher.review', compact('sub'));
    }
    private function validateAssignment(Request $request): array
    {
        return $request->validate([
            'type' => 'required|in:short_answer,choices,yes_no,multiple_choice,multiple_response',
            'title' => 'required|string|max:50',
            'description' => 'nullable|string|max:200',
            'question' => 'required|string|max:200',
            'estimated_date' => 'required|date',

            // Conditional Validation
            'choices' => 'required_if:type,choices,multiple_choice,multiple_response|array|min:2',
            'choices.*' => 'required_with:choices|string|max:100',
            'short_answer' => 'nullable|string|max:200',
            'choice_answer' => 'required_if:type,choices|string|max:100',
            'yes_no_answer' => 'required_if:type,yes_no|boolean',
            'multiple_choices_answer' => 'required_if:type,multiple_choice|array|min:1',
            'multiple_response_answer' => 'required_if:type,multiple_response|array|min:1',
        ]);
    }

    private function normalizeAssignmentData(array $data): array
    {
        // Reset all possible fields to null first to ensure clean state
        $normalized = [
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'question' => $data['question'],
            'estimated_date' => $data['estimated_date'],
            'choices' => null,
            'short_answer' => null,
            'choice_answer' => null,
            'yes_no_answer' => null,
            'multiple_choices_answer' => null,
            'multiple_response_answer' => null,
        ];

        // Fill based on type
        if (in_array($data['type'], ['choices', 'multiple_choice', 'multiple_response'])) {
            $normalized['choices'] = json_encode(array_values($data['choices']));
        }

        switch ($data['type']) {
            case 'short_answer':
                $normalized['short_answer'] = $data['short_answer'] ?? null;
                break;
            case 'yes_no':
                $normalized['yes_no_answer'] = (bool)$data['yes_no_answer'];
                break;
            case 'choices':
                $normalized['choice_answer'] = $data['choice_answer'];
                break;
            case 'multiple_choice':
                $normalized['multiple_choices_answer'] = json_encode($data['multiple_choices_answer']);
                break;
            case 'multiple_response':
                $normalized['multiple_response_answer'] = json_encode($data['multiple_response_answer']);
                break;
        }

        return $normalized;
    }
}