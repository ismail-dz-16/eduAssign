@props([
    'status' => session('status'), 
    'response' => session('response')
])

@if($status)
    <div class="toast-wrapper" id="toast-wrapper">
        <div class="toast-card {{ $status }}" id="main-toast">
            <div class="toast-icon">
                <div class="icon-visual">
                    @if($status === 'success')
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    @endif
                </div>
            </div>
            
            <div class="toast-content">
                <p class="toast-label">{{ $status === 'success' ? 'Success' : 'Error' }}</p>
                <p class="toast-msg">{{ $response }}</p>
            </div>

            <button class="toast-close" onclick="closeToast()">✕</button>
            <div class="toast-timer-bar"></div>
        </div>
    </div>

    <script>
        function closeToast() {
            const toast = document.getElementById('main-toast');
            if(toast) {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(() => {
                    const wrapper = document.getElementById('toast-wrapper');
                    if(wrapper) wrapper.remove();
                }, 500);
            }
        }

        // Use a small timeout to ensure the animation triggers after render
        setTimeout(closeToast, 5000);
    </script>
@endif