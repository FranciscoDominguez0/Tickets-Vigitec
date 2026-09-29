<!-- Toast Notifications -->
<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 9999; top: 20px !important; right: 20px !important;">
    
    @if(session('success'))
        <div class="toast custom-toast toast-success align-items-center show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="toast-body fw-medium">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-progress"><div class="toast-progress-bar"></div></div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast custom-toast toast-error align-items-center show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="toast-body fw-medium">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-progress"><div class="toast-progress-bar"></div></div>
        </div>
    @endif

    @if(session('warning'))
        <div class="toast custom-toast toast-warning align-items-center show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-icon">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
                <div class="toast-body fw-medium text-dark">
                    {{ session('warning') }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-progress"><div class="toast-progress-bar"></div></div>
        </div>
    @endif
</div>

<style>
/* Custom Toast Styles */
.custom-toast {
    background: #18181b;
    color: #fff;
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    overflow: hidden;
    margin-bottom: 12px;
    width: 320px;
    transform: translateX(100%);
    animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.toast-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    font-size: 1.25rem;
}

.toast-success .toast-icon { color: #4ade80; background: rgba(74, 222, 128, 0.1); }
.toast-error .toast-icon { color: #f87171; background: rgba(248, 113, 113, 0.1); }
.toast-warning { background: #fef08a; color: #854d0e; }
.toast-warning .toast-icon { color: #ca8a04; background: rgba(202, 138, 4, 0.1); }

.custom-toast .toast-body {
    padding: 14px 10px;
    font-size: 0.95rem;
    flex-grow: 1;
}

/* Progress bar animation */
.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: rgba(255,255,255,0.1);
}
.toast-warning .toast-progress { background: rgba(0,0,0,0.1); }

.toast-progress-bar {
    height: 100%;
    width: 100%;
    transform-origin: left;
    animation: progressShrink linear forwards;
}

.toast-success .toast-progress-bar { background: #4ade80; animation-duration: 4s; }
.toast-error .toast-progress-bar { background: #f87171; animation-duration: 5s; }
.toast-warning .toast-progress-bar { background: #ca8a04; animation-duration: 5s; }

@keyframes progressShrink {
    0% { transform: scaleX(1); }
    100% { transform: scaleX(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function(toastEl) {
        var toast = new bootstrap.Toast(toastEl, { autohide: true });
        toast.show();
        
        // Ensure element is removed from DOM after hiding to prevent stacking issues
        toastEl.addEventListener('hidden.bs.toast', function () {
            toastEl.remove();
        });
        
        return toast;
    });
});
</script>
