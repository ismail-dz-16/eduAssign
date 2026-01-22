<nav class="navbar">
    <div class="nav-container">

        <a href="@auth{{ route('dashboard') }}@else {{'/'}}@endauth" class="nav-logo">
            Edu<span>Assign</span>
        </a>

        <ul class="nav-links">
            @auth
                @if(auth()->user()->role === 'teacher')
                    <li><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('teacher.assignments.create') }}">New Assignment</a></li>
                @else
                    <li><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                @endif

                <li><a href="{{ route('profile.edit') }}">Profile</a></li>

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-link">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endauth
        </ul>

    </div>
</nav>
