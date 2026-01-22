@extends('layouts.app')

@section('content')
<div class="dashboard">

    <div class="dashboard-header">
        <div>
            <h1>Student Dashboard</h1>
            <p class="subtitle">Managing your academic progress and tasks.</p>
        </div>
    </div>

    {{-- TABS FOR NAVIGATION --}}
    <div class="dashboard-tabs">
        <button class="tab-btn active" onclick="switchTab(event, 'pending')">
            Pending Tasks <span class="tab-count">{{ $pendingAssignments->total() }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab(event, 'completed')">
            My Submissions <span class="tab-count">{{ $completedSubmissions->count() }}</span>
        </button>
    </div>

    {{-- PENDING SECTION --}}
    <section id="pending" class="tab-content active">
        @if($pendingAssignments->count())
            <div class="assignment-grid">
                @foreach($pendingAssignments as $assignment)
                    @php $isUrgent = $assignment->estimated_date->isToday(); @endphp
                    <article class="assignment-card">
                        <div class="card-top">
                            <span class="badge {{ $isUrgent ? 'badge-urgent' : 'badge-primary' }}">
                                {{ $isUrgent ? '🔥 Due Today' : '📅 ' . $assignment->estimated_date->format('d M Y') }}
                            </span>
                            <span class="teacher-tag">👨‍🏫 {{ $assignment->teacher->name }}</span>
                        </div>

                        <div class="card-body">
                            <h3>{{ $assignment->title }}</h3>
                            <p class="description">{{ Str::limit($assignment->description, 80) }}</p>
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn-primary btn-full">
                                Start Assignment
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="pagination-wrapper mt-4">
                {{ $pendingAssignments->links() }}
            </div>
        @else
            <div class="empty-state">
                <p>No pending tasks. You're all caught up!</p>
            </div>
        @endif
    </section>

    {{-- COMPLETED SECTION --}}
    <section id="completed" class="tab-content">
        @if($completedSubmissions->count())
            <div class="assignment-grid">
                @foreach($completedSubmissions as $sub)
                    @php 
                        $assignment = $sub->assignment;
                        $canEdit = $assignment->estimated_date->isFuture() || $assignment->estimated_date->isToday();
                    @endphp
                    <article class="assignment-card submitted-card">
                        <div class="card-top">
                            <span class="badge badge-success">✅ Submitted</span>
                            <span class="teacher-tag">👨‍🏫 {{ $assignment->teacher->name }}</span>
                        </div>

                        <div class="card-body">
                            <h3>{{ $assignment->title }}</h3>
                            <p class="small-text">Submitted on: {{ $sub->created_at->format('d M, H:i') }}</p>
                        </div>

                        <div class="card-footer">
                            @if($canEdit)
                                <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn-secondary btn-full">
                                    Edit My Response
                                </a>
                            @else
                                <div class="locked-status">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    Deadline Passed - Locked
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>You haven't submitted any work yet.</p>
            </div>
        @endif
    </section>

</div>
<style>
    /* Dashboard Tabs */
.dashboard-tabs { display: flex; gap: 1rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border); }
.tab-btn { 
    background: none; border: none; color: var(--text-dim); padding: 1rem 0.5rem; 
    cursor: pointer; font-weight: 600; position: relative; font-size: 1rem;
}
.tab-btn.active { color: var(--primary); }
.tab-btn.active::after { 
    content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background: var(--primary); 
}
.tab-count { 
    background: var(--bg-surface); font-size: 0.75rem; padding: 2px 8px; border-radius: 10px; margin-left: 5px; border: 1px solid var(--border); 
}

/* Tab Display */
.tab-content { display: none; }
.tab-content.active { display: block; animation: fadeIn 0.3s ease; }

/* Teacher Tag */
.teacher-tag { font-size: 0.8rem; color: var(--text-dim); font-weight: 500; }

/* Card Variations */
.submitted-card { border-top: 3px solid #10b981; }
.locked-status { 
    display: flex; align-items: center; justify-content: center; gap: 8px; 
    color: var(--text-dim); font-size: 0.9rem; padding: 10px; background: rgba(255,255,255,0.03); border-radius: 8px; width: 100%;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
<script>
    function switchTab(evt, tabName) {
        let content = document.getElementsByClassName("tab-content");
        for (let i = 0; i < content.length; i++) content[i].classList.remove("active");
        
        let tabs = document.getElementsByClassName("tab-btn");
        for (let i = 0; i < tabs.length; i++) tabs[i].classList.remove("active");
        
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }
</script>
@endsection