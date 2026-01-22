@extends('layouts.app')

@section('content')
<div class="builder-container">
    {{-- HEADER --}}
    <div class="builder-header">
        <div>
            <h1>{{ $assignment ? 'Edit Assignment' : 'Create New Assignment' }}</h1>
            <p>Design interactive questions and set correct answers for automated grading.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-secondary">Back to Dashboard</a>
    </div>

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
        <div class="error-alert">
            <div class="alert-icon">⚠️</div>
            <div class="alert-content">
                <strong>Please correct the following:</strong>
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" 
          action="{{ $assignment ? route('teacher.assignments.update', $assignment->id) : route('teacher.assignments.store') }}">
        @csrf
        @if($assignment) @method('PUT') @endif

        <div class="builder-grid">
            
            {{-- COLUMN 1: CONFIGURATION --}}
            <div class="column">
                <div class="card">
                    <div class="card-header">
                        <span class="step-num">1</span>
                        <h3>General Information</h3>
                    </div>
                    
                    <div class="form-group">
                        <label>Assignment Title</label>
                        <input type="text" name="title" value="{{ old('title', $assignment->title ?? '') }}" placeholder="e.g. Midterm Quiz - Unit 1" required>
                    </div>

                    <div class="form-group">
                        <label>Instructions (Optional)</label>
                        <textarea name="description" rows="2" placeholder="Briefly describe the task...">{{ old('description', $assignment->description ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>The Question</label>
                        <textarea name="question" rows="4" placeholder="What is the question for the student?" required>{{ old('question', $assignment->question ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Due Date</label>
                        <input type="date" name="estimated_date" 
                               value="{{ old('estimated_date', $assignment && $assignment->estimated_date ? \Carbon\Carbon::parse($assignment->estimated_date)->format('Y-m-d') : '') }}" 
                               required>
                    </div>
                </div>
            </div>

            {{-- COLUMN 2: INTERACTION --}}
            <div class="column">
                <div class="card">
                    <div class="card-header">
                        <span class="step-num">2</span>
                        <h3>Answer Logic</h3>
                    </div>

                    <div class="form-group">
                        <label>Interaction Type</label>
                        <select class="modern-select" id="typeSelect" name="type" required>
                            <option value="">-- Select Type --</option>
                            @foreach(['short_answer','yes_no','choices','multiple_choice','multiple_response'] as $t)
                                <option value="{{ $t }}" {{ old('type', $assignment->type ?? '') === $t ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_',' ',$t)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="dynamicArea" class="dynamic-area">
                        {{-- Content injected by JS --}}
                    </div>

                    <div id="recapBox" class="recap-box" style="display:none"></div>
                </div>
            </div>

            {{-- COLUMN 3: PREVIEW --}}
            <div class="column">
                <div class="card preview-card">
                    <div class="card-header">
                        <span class="step-num">3</span>
                        <h3>Student Preview</h3>
                    </div>
                    <div id="previewBox" class="preview-content">
                        <div class="empty-state">Select a type to see how it looks for students.</div>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">
                    {{ $assignment ? 'Update Assignment' : 'Publish Assignment' }}
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    :root {
        --bg: #0f172a;
        --card-bg: #1e293b;
        --border: #334155;
        --text-main: #f8fafc;
        --text-dim: #94a3b8;
        --primary: #3b82f6;
        --accent: #10b981;
        --danger: #ef4444;
    }
    .builder-container {  padding: 2.5rem; background: var(--bg); min-height: 100vh; color: var(--text-main); font-family: 'Inter', system-ui, sans-serif; width: 100%;border-radius:10px;}
    
    /* Header */
    .builder-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; }
    .builder-header h1 { font-size: 1.75rem; font-weight: 700; margin: 0; }
    .builder-header p { color: var(--text-dim); margin: 0.5rem 0 0; }

    /* Layout */
    .builder-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; height: 100%; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .card-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; }
    .step-num { background: var(--primary); color: #fff; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: bold; }
    .card-header h3 { font-size: 1.1rem; margin: 0; font-weight: 600; }

    /* Forms */
    .form-group { margin-bottom: 1.25rem; }
    label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-dim); margin-bottom: 0.5rem; }
    input, textarea, select { 
        width: 100%; background: #0f172a; border: 1px solid var(--border); border-radius: 10px; 
        padding: 0.75rem; color: #fff; font-size: 0.95rem; transition: all 0.2s; box-sizing: border-box;
    }
    input:focus, textarea:focus, select:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); }

    /* Choices UI */
    .choice-row { display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem; background: #1e293b; transition: 0.2s; }
    .choice-row input[type="text"] { flex: 1; }
    .choice-row input[type="checkbox"] { width: 20px; height: 20px; accent-color: var(--accent); cursor: pointer; }
    .btn-remove { background: #334155; border: none; color: var(--text-dim); border-radius: 8px; padding: 0.5rem 0.8rem; cursor: pointer; }
    .btn-remove:hover { background: var(--danger); color: #fff; }
    #addChoice { 
        width: 100%; background: transparent; border: 1px dashed var(--border); color: var(--text-dim); 
        padding: 0.75rem; border-radius: 10px; cursor: pointer; margin-top: 0.5rem; font-weight: 500;
    }
    #addChoice:hover { border-color: var(--primary); color: var(--primary); }

    /* Preview & Recap */
    .preview-content { background: #0f172a; border-radius: 12px; padding: 1.25rem; min-height: 200px; border: 1px solid var(--border); }
    .preview-item { display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem; padding: 0.75rem; background: #1e293b; border-radius: 8px; }
    .recap-box { margin-top: 1.5rem; padding: 1rem; background: rgba(16, 185, 129, 0.1); border: 1px solid var(--accent); border-radius: 12px; color: var(--accent); font-size: 0.9rem; }
    .empty-state { color: var(--text-dim); font-style: italic; text-align: center; margin-top: 3rem; }

    /* Buttons */
    .btn-primary { width: 100%; background: var(--primary); color: #fff; border: none; padding: 1rem; border-radius: 12px; font-weight: 600; font-size: 1rem; cursor: pointer; margin-top: 1.5rem; transition: 0.2s; }
    .btn-primary:hover { background: #2563eb; transform: translateY(-1px); }
    .btn-secondary { background: var(--card-bg); border: 1px solid var(--border); color: var(--text-main); padding: 0.6rem 1.2rem; border-radius: 10px; text-decoration: none; font-size: 0.9rem; }

    /* Alerts */
    .error-alert { display: flex; gap: 1rem; background: #450a0a; border: 1px solid var(--danger); border-radius: 12px; padding: 1rem; margin-bottom: 2rem; color: #fca5a5; }
    .alert-icon { font-size: 1.5rem; }

    @media(max-width: 1200px) { .builder-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const typeSelect = document.getElementById('typeSelect');
    const dynamic = document.getElementById('dynamicArea');
    const preview = document.getElementById('previewBox');
    const recap = document.getElementById('recapBox');

    // FIX: Check if data is already an array (old) or JSON string (database)
    const storedChoices = {!! 
        json_encode(old('choices') ?? ($assignment && $assignment->choices ? (is_array($assignment->choices) ? $assignment->choices : json_decode($assignment->choices, true)) : [])) 
    !!};
    
    const storedSingle = "{!! old('choice_answer', $assignment->choice_answer ?? '') !!}";
    const storedMulti = {!! 
        json_encode(old('multiple_choices_answer') ?? ($assignment && $assignment->multiple_choices_answer ? (is_array($assignment->multiple_choices_answer) ? $assignment->multiple_choices_answer : json_decode($assignment->multiple_choices_answer, true)) : [])) 
    !!};
    const storedResp = {!! 
        json_encode(old('multiple_response_answer') ?? ($assignment && $assignment->multiple_response_answer ? (is_array($assignment->multiple_response_answer) ? $assignment->multiple_response_answer : json_decode($assignment->multiple_response_answer, true)) : [])) 
    !!};

    function build() {
        dynamic.innerHTML = '';
        preview.innerHTML = '';
        recap.style.display = 'none';
        const type = typeSelect.value;

        if (!type) {
            preview.innerHTML = '<div class="empty-state">Select a type to see how it looks for students.</div>';
            return;
        }

        if (type === 'short_answer') {
            dynamic.innerHTML = `
                <div class="form-group">
                    <label>Reference Answer (Optional)</label>
                    <textarea name="short_answer" placeholder="The correct answer for your reference...">{{ old('short_answer', $assignment->short_answer ?? '') }}</textarea>
                </div>`;
            preview.innerHTML = '<div class="form-group"><label>Your Answer</label><textarea disabled placeholder="Student types here..."></textarea></div>';
            return;
        }

        if (type === 'yes_no') {
            const currentVal = "{{ old('yes_no_answer', $assignment->yes_no_answer ?? '1') }}";
            dynamic.innerHTML = `
                <div class="form-group">
                    <label>Correct Answer</label>
                    <select class="modern-select" name="yes_no_answer">
                        <option value="1" ${currentVal == '1' ? 'selected' : ''}>Yes</option>
                        <option value="0" ${currentVal == '0' ? 'selected' : ''}>No</option>
                    </select>
                </div>`;
            preview.innerHTML = `
                <div class="preview-item"><input type="radio" checked disabled> Yes</div>
                <div class="preview-item"><input type="radio" disabled> No</div>`;
            return;
        }

        if (['choices', 'multiple_choice', 'multiple_response'].includes(type)) {
            dynamic.innerHTML = `<div id="choicesBox"></div><button type="button" id="addChoice">+ Add Option</button>`;
            
            let corrects = [];
            if(type === 'choices') corrects = [storedSingle];
            else if(type === 'multiple_choice') corrects = storedMulti;
            else corrects = storedResp;

            if (storedChoices.length > 0) {
                storedChoices.forEach(c => addChoice(c, corrects.includes(c)));
            } else {
                addChoice('Option 1'); addChoice('Option 2'); 
            }
            
            document.getElementById('addChoice').onclick = () => addChoice();
        }
    }

    function addChoice(text = '', checked = false) {
        const box = document.getElementById('choicesBox');
        if (!box) return;

        const type = typeSelect.value;
        const row = document.createElement('div');
        row.className = 'choice-row';
        row.innerHTML = `
            <input type="text" name="choices[]" value="${text}" placeholder="Enter option..." required>
            <input type="checkbox" ${checked ? 'checked' : ''} title="Mark as correct">
            <button type="button" class="btn-remove" title="Remove">&times;</button>
        `;

        const txtInput = row.querySelector('input[type=text]');
        const cb = row.querySelector('input[type=checkbox]');

        txtInput.oninput = updateHiddenAnswers;
        cb.onchange = () => {
            if (type === 'choices') {
                box.querySelectorAll('input[type=checkbox]').forEach(x => { if (x !== cb) x.checked = false; });
            }
            updateHiddenAnswers();
        };

        row.querySelector('button').onclick = () => { row.remove(); updateHiddenAnswers(); };
        box.appendChild(row);
        updateHiddenAnswers();
    }

    function updateHiddenAnswers() {
        preview.innerHTML = '';
        const type = typeSelect.value;
        const checkedValues = [];

        document.querySelectorAll('.dynamic-answer-input').forEach(el => el.remove());

        document.querySelectorAll('#choicesBox .choice-row').forEach(row => {
            const txt = row.querySelector('input[type=text]').value;
            const isChecked = row.querySelector('input[type=checkbox]').checked;

            preview.innerHTML += `
                <div class="preview-item">
                    <input type="${type === 'choices' ? 'radio' : 'checkbox'}" disabled ${isChecked ? 'checked' : ''}>
                    <span>${txt || '...'}</span>
                </div>`;

            if (isChecked && txt.trim() !== "") checkedValues.push(txt);
        });

        checkedValues.forEach(val => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.className = 'dynamic-answer-input';
            input.value = val;
            if (type === 'choices') input.name = 'choice_answer';
            else if (type === 'multiple_choice') input.name = 'multiple_choices_answer[]';
            else if (type === 'multiple_response') input.name = 'multiple_response_answer[]';
            dynamic.appendChild(input);
        });

        if (checkedValues.length > 0) {
            recap.style.display = 'block';
            recap.innerHTML = `<strong>Correct Answer:</strong> ${checkedValues.join(', ')}`;
        } else {
            recap.style.display = 'none';
        }
    }

    typeSelect.onchange = build;
    if (typeSelect.value) build();
});
</script>
@endpush