<div class="rz-action-report">
    <div class="rz--report rz-text-center">
        <a href="#" class="rz-link" data-modal="<?php echo is_user_logged_in() ? 'report' : 'signin'; ?>">
            <i class="fas fa-bell rz-mr-1"></i>
            {{ $strings->report }}
        </a>
    </div>
</div>