@extends('layouts.app')

@section('content')
<div class="dashboard">

    {{-- HEADER --}}
    <div class="dashboard-header">
        <div>
            <h1>Teacher Dashboard</h1>
            <p class="subtitle">Manage your assignments and track student submissions</p>
        </div>

        <a href="{{ route('teacher.assignments.create') }}" class="btn-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Create Assignment
        </a>
    </div>

    {{-- ASSIGNMENTS SECTION --}}
    <section class="section">
        <div class="section-head">
            <div class="flex-col">
                <h2>My Assignments</h2>
                <span class="helper-text">💡 Click a card to filter and show only its submissions</span>
            </div>
            <span class="count">{{ $assignments->total() }}</span>
        </div>

        @if($assignments->count())
            <div class="assignment-grid">
                {{-- Reset Filter Card --}}
                @if(request('assignment_id'))
                    <a href="{{ route('dashboard') }}" class="assignment-card reset-card">
                        <div class="card-body text-center">
                            <span style="font-size: 1.5rem;">🔄</span>
                            <h3>Show All Submissions</h3>
                            <p class="small text-dim">Currently filtering results</p>
                        </div>
                    </a>
                @endif

                @foreach($assignments as $assignment)
                    <article class="assignment-card {{ request('assignment_id') == $assignment->id ? 'is-active' : '' }}" 
                             onclick="window.location.href='{{ route('teacher.dashboard', ['assignment_id' => $assignment->id]) }}'" 
                             style="cursor: pointer;">
                        <div class="card-top">
                            <div class="title-area">
                                <h3>{{ $assignment->title }}</h3>
                                <span class="badge badge-outline">
                                    {{ ucfirst(str_replace('_',' ', $assignment->type)) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-stats">
                            <div class="stat">
                                <span class="label">Due Date</span>
                                <span class="value">{{ $assignment->estimated_date->format('d M Y') }}</span>
                            </div>
                            <div class="stat">
                                <span class="label">Submissions</span>
                                <span class="value">{{ $assignment->submissions->count() }}</span>
                            </div>
                        </div>

                        <div class="card-actions" onclick="event.stopPropagation();">
                            <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('teacher.assignments.destroy', $assignment) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn-danger btn-sm" onclick="return confirm('Delete this assignment?')">Delete</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- SUBMISSIONS SECTION --}}
    <section class="section mt-12">
        <div class="section-head">
            <h2>
                {{ request('assignment_id') ? 'Filtered Submissions' : 'Recent Submissions' }}
            </h2>
            <span class="count">{{ $submissions->total() }}</span>
        </div>

        @if($submissions->count())
            <div class="table-card overflow-hidden">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Assignment</th>
                            <th>Status Helper</th>
                            <th>Submitted At</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $sub)
                            @php
                                $isCorrect = false;
                                $type = $sub->assignment->type;
                                // Auto-check logic
                                if($type === 'yes_no') $isCorrect = (bool)$sub->yes_no_answer === (bool)$sub->assignment->yes_no_answer;
                                elseif($type === 'choices') $isCorrect = $sub->choice_answer === $sub->assignment->choice_answer;
                                elseif($type === 'multiple_choice') {
                                    $s = (array)$sub->multiple_choices_answer; $a = (array)$sub->assignment->multiple_choices_answer;
                                    sort($s); sort($a); $isCorrect = ($s === $a);
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="student-info">
                                        <div class="avatar-sm">{{ strtoupper(substr($sub->student->name, 0, 1)) }}</div>
                                        <strong>{{ $sub->student->name }}</strong>
                                    </div>
                                </td>
                                <td><span class="text-dim">{{ $sub->assignment->title }}</span></td>
                                <td>
                                    @if($type === 'short_answer' || $type === 'multiple_response')
                                        <span class="status-tag manual">Manual Review</span>
                                    @else
                                        <span class="status-tag {{ $isCorrect ? 'correct' : 'incorrect' }}">
                                            {{ $isCorrect ? '✅ Correct' : '❌ Incorrect' }}
                                        </span>
                                    @endif
                                </td>
                                <td><span class="date-tag">{{ $sub->created_at->format('d M, H:i') }}</span></td>
                                <td class="text-right">
                                    <a href="{{ route('teacher.submissions.review', $sub->id) }}" class="btn-primary btn-sm">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <p>No submissions found for this selection.</p>
            </div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<style>
    /* New Status Tags */
    .status-tag { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .status-tag.correct { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-tag.incorrect { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    .status-tag.manual { background: rgba(255, 255, 255, 0.05); color: var(--text-dim); border: 1px solid var(--border); }

    /* Filter UI Improvements */
    .helper-text { font-size: 0.85rem; color: var(--primary); font-weight: 500; margin-top: 4px; }
    .assignment-card.is-active { border: 2px solid var(--primary); background: rgba(99, 102, 241, 0.05); }
    .reset-card { border: 1px dashed var(--primary); display: flex; align-items: center; justify-content: center; text-decoration: none; }
    .reset-card:hover { background: rgba(99, 102, 241, 0.05); }

    /* Layout Spacing */
    .flex-col { display: flex; flex-direction: column; }
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table td, .modern-table th { padding: 1rem; border-bottom: 1px solid var(--border); }
    .avatar-sm { width: 30px; height: 30px; border-radius: 50%; background: var(--primary); color: #000; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; }
    .student-info { display: flex; align-items: center; gap: 10px; }
</style>
@endpush