@extends('layouts.app')

@section('content')
<div class="container">
    <div class="assignment-builder-wrap">
        
        {{-- HEADER: Fixed Alignment --}}
        <div class="builder-header">
            <div class="header-content">
                <div class="meta-row">
                    @php $isUrgent = $assign->estimated_date->isToday() || $assign->estimated_date->isPast(); @endphp
                    <span class="badge {{ $isUrgent ? 'badge-urgent' : 'badge-primary' }}">
                        {{ $isUrgent ? '⚠️ Final Deadline:' : '📅 Due Date:' }} {{ $assign->estimated_date->format('d M Y') }}
                    </span>
                </div>
                <h1 class="page-title">{{ $assign->title }}</h1>
            </div>
            
            <div class="header-actions">
                <a href="{{ route('student.dashboard') }}" class="btn-secondary btn-with-icon">
                   <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                   <span>Back to Dashboard</span>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('student.assignments.submit', $assign->id) }}" class="mt-6">
            @csrf
            <input type="hidden" name="type" value="{{ $assign->type }}">

            <div class="builder-grid">
                
                {{-- LEFT COLUMN: THE PROMPT --}}
                <div class="builder-col">
                    <div class="card h-full">
                        <div class="card-step-title">
                            <span class="step-badge">?</span>
                            <div>
                                <h3>Assignment Prompt</h3>
                                <p class="small text-dim">Review instructions before answering.</p>
                            </div>
                        </div>

                        <div class="question-container mt-4">
                            @if($assign->description)
                                <div class="info-block">
                                    <label class="label-muted">Teacher Instructions</label>
                                    <p class="description-text">{{ $assign->description }}</p>
                                </div>
                            @endif

                            <div class="info-block">
                                <label class="label-muted">Question</label>
                                <div class="question-box-styled">
                                    {{ $assign->question }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: THE WORKSPACE --}}
                <div class="builder-col" style="grid-column: span 2;">
                    <div class="card h-full">
                        <div class="card-step-title">
                            <span class="step-badge">✎</span>
                            <div>
                                <h3>{{ isset($submit) ? 'Refine Your Response' : 'Your Workspace' }}</h3>
                                <p class="small text-dim">Answer carefully. You can update this until the deadline.</p>
                            </div>
                        </div>

                        <div class="input-render-zone mt-6">
                            
                            {{-- TYPE: SHORT ANSWER --}}
                            @if($assign->type === 'short_answer')
                                <div class="form-group">
                                    <textarea name="short_answer" class="modern-textarea" placeholder="Start typing your answer here..." required>{{ old('short_answer', $submit->short_answer ?? '') }}</textarea>
                                </div>
                            @endif

                            {{-- TYPE: YES / NO --}}
                            @if($assign->type === 'yes_no')
                                <div class="interactive-options">
                                    <label class="custom-option">
                                        <input type="radio" name="yes_no_answer" value="1" @checked(old('yes_no_answer', $submit->yes_no_answer ?? null) == 1)>
                                        <div class="option-content">
                                            <div class="indicator"></div>
                                            <span>Yes / True</span>
                                        </div>
                                    </label>
                                    <label class="custom-option">
                                        <input type="radio" name="yes_no_answer" value="0" @checked(old('yes_no_answer', $submit->yes_no_answer ?? null) === 0)>
                                        <div class="option-content">
                                            <div class="indicator"></div>
                                            <span>No / False</span>
                                        </div>
                                    </label>
                                </div>
                            @endif

                            {{-- TYPE: SINGLE CHOICE --}}
                            @if($assign->type === 'choices')
                                @php $options = is_array($assign->choices) ? $assign->choices : json_decode($assign->choices ?? '[]', true); @endphp
                                <div class="interactive-options">
                                    @foreach(($options ?? []) as $choice)
                                        <label class="custom-option">
                                            <input type="radio" name="choice_answer" value="{{ $choice }}" @checked(old('choice_answer', $submit->choice_answer ?? '') === $choice) required>
                                            <div class="option-content">
                                                <div class="indicator"></div>
                                                <span>{{ $choice }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            {{-- TYPE: MULTIPLE CHOICE --}}
                            @if($assign->type === 'multiple_choice')
                                @php 
                                    $options = is_array($assign->choices) ? $assign->choices : json_decode($assign->choices ?? '[]', true); 
                                    $selected = is_array($submit?->multiple_choices_answer) ? $submit->multiple_choices_answer : json_decode($submit?->multiple_choices_answer ?? '[]', true);
                                @endphp
                                <div class="interactive-options">
                                    @foreach(($options ?? []) as $choice)
                                        <label class="custom-option">
                                            <input type="checkbox" name="multiple_choices_answer[]" value="{{ $choice }}" 
                                                @checked(in_array($choice, (array)old('multiple_choices_answer', $selected ?? [])))>
                                            <div class="option-content">
                                                <div class="indicator checkbox"></div>
                                                <span>{{ $choice }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            {{-- TYPE: MULTIPLE RESPONSE --}}
                            @if($assign->type === 'multiple_response')
                                <div class="form-group">
                                    @php $responses = is_array($submit?->multiple_response_answer) ? $submit->multiple_response_answer : json_decode($submit?->multiple_response_answer ?? '[]', true); @endphp
                                    <textarea name="multiple_response_answer[]" class="modern-textarea" placeholder="Provide your detailed breakdown...">{{ old('multiple_response_answer.0', $responses[0] ?? '') }}</textarea>
                                </div>
                            @endif
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn-primary btn-lg">
                                {{ isset($submit) ? 'Update My Submission' : 'Confirm & Submit Work' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Header Improvements */
    .builder-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end; /* Keeps items grounded on the same baseline */
        gap: 20px;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .header-content { flex: 1; }
    .page-title { 
        margin: 0.5rem 0 0 0; 
        font-size: 1.875rem; 
        line-height: 1.2; 
        color: #fff; 
    }
    .meta-row { display: flex; align-items: center; gap: 10px; }

    .btn-with-icon {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap; /* Prevents button text from wrapping */
    }

    /* Input Styles */
    .label-muted { color: var(--text-dim); text-transform: uppercase; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; display: block; margin-bottom: 0.5rem; }
    .description-text { color: var(--text-dim); line-height: 1.6; margin-bottom: 1.5rem; }
    
    .question-box-styled { 
        background: rgba(255,255,255,0.03); 
        padding: 1.5rem; 
        border-radius: 12px; 
        border: 1px solid var(--border);
        border-left: 4px solid var(--primary);
        font-size: 1.1rem;
        color: #fff;
    }

    /* Option Styling */
    .interactive-options { display: flex; flex-direction: column; gap: 0.75rem; }
    .custom-option { cursor: pointer; display: block; }
    .custom-option input { display: none; }
    
    .option-content {
        background: var(--bg-surface);
        border: 1px solid var(--border);
        padding: 1.1rem 1.25rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: 0.2s ease;
    }

    .indicator { width: 18px; height: 18px; border: 2px solid var(--border); border-radius: 50%; flex-shrink: 0; position: relative; }
    .indicator.checkbox { border-radius: 4px; }

    .custom-option:hover .option-content { border-color: var(--primary); background: rgba(99, 102, 241, 0.05); }
    .custom-option input:checked + .option-content { border-color: var(--primary); background: rgba(99, 102, 241, 0.1); }
    .custom-option input:checked + .option-content .indicator { border-color: var(--primary); background: var(--primary); }
    .custom-option input:checked + .option-content .indicator::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 6px; height: 6px; background: #000; border-radius: 50%;
    }

    .modern-textarea {
        background: var(--bg-surface); border: 1px solid var(--border); color: #fff;
        padding: 1.25rem; font-size: 1rem; line-height: 1.6; border-radius: 12px;
        width: 100%; min-height: 250px; resize: vertical;
    }

    .form-footer { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; }
</style>
@endpush