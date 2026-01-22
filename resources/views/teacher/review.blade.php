@extends('layouts.app')

@section('content')
<div class="container">
    {{-- TOP HEADER: Clean & Spaced --}}
    <div class="builder-header mb-10" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 2rem;">
        <div>
            <div class="meta-row mb-2" style="display: flex; gap: 12px; align-items: center;">
                <span class="badge badge-primary">Reviewing Submission</span>
                <span class="text-dim">ID: #SUB-{{ $sub->id }}</span>
            </div>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: #fff; margin: 0;">{{ $sub->assignment->title }}</h1>
            <p style="color: var(--text-dim); margin-top: 5px; font-size: 1.1rem;">
                Student: <strong style="color: #fff;">{{ $sub->student->name }}</strong>
            </p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-secondary">Back to Dashboard</a>
    </div>

    {{-- MAIN GRID: High Spacing --}}
    <div class="builder-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
        
        {{-- LEFT COLUMN: REFERENCE --}}
        <div class="review-column">
            <h3 class="label-muted mb-4">Assignment Prompt</h3>
            <div class="card" style="background: rgba(255,255,255,0.02); border: 1px dashed var(--border); padding: 2rem; border-radius: 16px;">
                <div class="info-block mb-6">
                    <label class="label-muted">The Question</label>
                    <div style="font-size: 1.2rem; color: #fff; line-height: 1.6;">{{ $sub->assignment->question }}</div>
                </div>

                @php 
                    $options = is_array($sub->assignment->choices) ? $sub->assignment->choices : json_decode($sub->assignment->choices ?? '[]', true); 
                @endphp

                @if(!empty($options))
                    <div class="info-block">
                        <label class="label-muted">Valid Options</label>
                        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">
                            @foreach($options as $opt)
                                <div style="padding: 10px; background: rgba(255,255,255,0.05); border-radius: 8px; color: var(--text-dim);">
                                    • {{ $opt }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT COLUMN: THE VERDICT --}}
        <div class="review-column">
            <h3 class="label-muted mb-4">Student Response</h3>
            <div class="card" style="background: var(--bg-surface); border: 1px solid var(--border); padding: 2rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                
                <div class="response-content">
                    @php 
                        $isCorrect = false; 
                        $type = $sub->assignment->type;
                    @endphp

                    {{-- Logic: Compare Answer vs Submission --}}
                    @if($type === 'short_answer')
                        <div class="answer-bubble mb-6">{{ $sub->short_answer }}</div>
                        <p class="text-dim small italic">Note: Short answers require manual grading.</p>

                    @elseif($type === 'yes_no')
                        @php $isCorrect = (bool)$sub->yes_no_answer === (bool)$sub->assignment->yes_no_answer; @endphp
                        <div class="answer-pill {{ $sub->yes_no_answer ? 'pill-success' : 'pill-danger' }} mb-4">
                             Student Answered: {{ $sub->yes_no_answer ? 'Yes / True' : 'No / False' }}
                        </div>

                    @elseif($type === 'choices')
                        @php $isCorrect = $sub->choice_answer === $sub->assignment->choice_answer; @endphp
                        <div class="answer-pill pill-primary mb-4">
                            Student Selected: {{ $sub->choice_answer }}
                        </div>

                    @elseif($type === 'multiple_choice')
                        @php 
                            $studentArr = is_array($sub->multiple_choices_answer) ? $sub->multiple_choices_answer : [];
                            $correctArr = is_array($sub->assignment->multiple_choices_answer) ? $sub->assignment->multiple_choices_answer : [];
                            sort($studentArr); sort($correctArr);
                            $isCorrect = ($studentArr === $correctArr);
                        @endphp
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($studentArr as $c) <span class="answer-pill pill-primary">{{ $c }}</span> @endforeach
                        </div>
                    @endif
                </div>

                {{-- CORRECT / INCORRECT HELPER BADGE --}}
                @if($type !== 'short_answer' && $type !== 'multiple_response')
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border);">
                        <div class="verdict-badge {{ $isCorrect ? 'is-correct' : 'is-wrong' }}">
                            <span class="icon">{{ $isCorrect ? '✅' : '❌' }}</span>
                            <div class="text">
                                <strong>{{ $isCorrect ? 'Correct Match' : 'Incorrect Match' }}</strong>
                                <p>{{ $isCorrect ? 'This matches your pre-set answer.' : 'Does not match your records.' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .label-muted { color: var(--text-dim); text-transform: uppercase; font-size: 0.8rem; font-weight: 700; letter-spacing: 1.5px; }
    .answer-bubble { background: var(--bg-dark); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border); color: #fff; line-height: 1.7; font-size: 1.1rem; }
    
    .answer-pill { display: inline-flex; padding: 10px 20px; border-radius: 30px; font-weight: 700; }
    .pill-primary { background: rgba(99, 102, 241, 0.15); color: var(--primary); border: 1px solid var(--primary); }
    .pill-success { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid #10b981; }
    .pill-danger { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid #ef4444; }

    /* Verdict Helper */
    .verdict-badge { display: flex; align-items: center; gap: 15px; padding: 1.25rem; border-radius: 12px; }
    .verdict-badge.is-correct { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; }
    .verdict-badge.is-wrong { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; }
    .verdict-badge .icon { font-size: 1.5rem; }
    .verdict-badge p { margin: 0; font-size: 0.85rem; opacity: 0.8; }
</style>
@endpush