<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fa-solid fa-bell me-2" style="color:var(--primary);"></i>Notifications
            </h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Stay updated with project activities and progress.</p>
        </div>
        <?php
            $unreadCount = count(array_filter($notifications ?? [], fn($n) => !$n['is_read']));
        ?>
        <?php if ($unreadCount > 0): ?>
            <div class="d-flex align-items-center gap-2">
                <span class="badge" style="background:rgba(99,102,241,0.15);color:#a78bfa;border:1px solid rgba(99,102,241,0.25);font-size:12px;padding:6px 12px;">
                    <?= $unreadCount ?> unread
                </span>
                <a href="index.php?page=notification-read-all"
                   class="btn btn-outline-secondary rounded-pill px-3 fw-bold" style="font-size:13px;">
                    <i class="fa-solid fa-check-double me-1"></i>Mark all as read
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Flash -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Filter Tabs -->
    <?php if (!empty($notifications)): ?>
    <div class="d-flex gap-2 mb-4 flex-wrap" id="filterTabs" data-aos="fade-up">
        <button class="notif-filter active" data-filter="all">
            All <span class="ms-1 opacity-60">(<?= count($notifications) ?>)</span>
        </button>
        <button class="notif-filter" data-filter="unread">
            Unread <span class="ms-1 opacity-60">(<?= $unreadCount ?>)</span>
        </button>
        <?php
            $types = array_unique(array_column($notifications, 'type'));
            foreach ($types as $t):
        ?>
        <button class="notif-filter" data-filter="<?= htmlspecialchars($t) ?>">
            <?= htmlspecialchars(ucfirst($t)) ?>
        </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Notification List -->
    <?php if (!empty($notifications)): ?>
        <div class="d-flex flex-column gap-3" id="notifList" data-aos="fade-up" data-aos-delay="50">
            <?php foreach ($notifications as $n):
                $isRead  = (bool)$n['is_read'];
                $type    = $n['type'] ?? 'system';
                $typeConfig = match($type) {
                    'system'       => ['color'=>'#60a5fa', 'bg'=>'rgba(59,130,246,0.12)', 'icon'=>'fa-gear'],
                    'registration' => ['color'=>'#34d399', 'bg'=>'rgba(16,185,129,0.12)', 'icon'=>'fa-file-signature'],
                    'score'        => ['color'=>'#fbbf24', 'bg'=>'rgba(245,158,11,0.12)',  'icon'=>'fa-star'],
                    'submission'   => ['color'=>'#a78bfa', 'bg'=>'rgba(124,58,237,0.12)', 'icon'=>'fa-upload'],
                    'milestone'    => ['color'=>'#f87171', 'bg'=>'rgba(239,68,68,0.12)',  'icon'=>'fa-flag'],
                    default        => ['color'=>'var(--text-muted)', 'bg'=>'var(--glass-bg)', 'icon'=>'fa-bell'],
                };
            ?>
                <div class="notif-item <?= $isRead ? 'notif-read' : 'notif-unread' ?>"
                     data-type="<?= htmlspecialchars($type) ?>"
                     data-read="<?= $isRead ? '1' : '0' ?>">
                    <div class="d-flex align-items-start gap-3">
                        <!-- Icon -->
                        <div class="avatar-icon" style="width:44px;height:44px;border-radius:14px;background:<?= $typeConfig['bg'] ?>;color:<?= $typeConfig['color'] ?>;font-size:1.1rem;">
                            <i class="fa-solid <?= $typeConfig['icon'] ?>"></i>
                        </div>
                        <!-- Content -->
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <?php if (!$isRead): ?>
                                    <span style="width:8px;height:8px;border-radius:50%;background:#a78bfa;display:inline-block;flex-shrink:0;"></span>
                                <?php endif; ?>
                                <span style="font-size:10px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:2px 8px;border-radius:50px;background:<?= $typeConfig['bg'] ?>;color:<?= $typeConfig['color'] ?>;">
                                    <?= htmlspecialchars(strtoupper($type)) ?>
                                </span>
                                <?php if (!empty($n['email'])): ?>
                                    <span style="font-size:11px;color:var(--text-faint);">
                                        <i class="fa-solid fa-user me-1"></i><?= htmlspecialchars($n['email']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-0 <?= $isRead ? '' : 'fw-semibold' ?>"
                               style="color:<?= $isRead ? 'var(--text-muted)' : 'var(--text-bright)' ?>;font-size:14px;line-height:1.6;">
                                <?= htmlspecialchars($n['content']) ?>
                            </p>
                        </div>
                        <!-- Actions -->
                        <div class="d-flex gap-1 flex-shrink-0">
                            <?php if (!$isRead): ?>
                                <a href="index.php?page=notification-read&id=<?= $n['id'] ?>"
                                   class="icon-btn icon-btn-success" title="Mark as read">
                                    <i class="fa-solid fa-check"></i>
                                </a>
                            <?php endif; ?>
                            <a href="index.php?page=notification-delete&id=<?= $n['id'] ?>"
                               class="icon-btn icon-btn-danger btn-delete" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="text-center py-5 mt-3" data-aos="fade-up">
            <div class="avatar-icon mx-auto mb-4" style="width:80px;height:80px;border-radius:24px;background:rgba(124,58,237,0.1);color:#a78bfa;font-size:2rem;">
                <i class="fa-solid fa-bell-slash"></i>
            </div>
            <h5 class="fw-bold" style="color:var(--text-bright);">No notifications yet</h5>
            <p style="color:var(--text-muted);font-size:13px;">When there are new updates, they'll appear here.</p>
        </div>
    <?php endif; ?>

</div>

<style>
.notif-item {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-lg);
    padding: 20px;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.notif-item:hover {
    background: var(--glass-bg-hover);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}
.notif-item.notif-unread::before {
    content: '';
    position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #7c3aed, #06b6d4);
    border-radius: 4px 0 0 4px;
}
.notif-filter {
    font-size: 12px;
    font-weight: 600;
    border-radius: 50px;
    padding: 6px 16px;
    border: 1px solid var(--glass-border);
    background: var(--glass-bg);
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
}
.notif-filter.active, .notif-filter:hover {
    background: rgba(124,58,237,0.15);
    color: #a78bfa;
    border-color: rgba(124,58,237,0.3);
}
</style>

<script>
document.querySelectorAll('.notif-filter').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.notif-filter').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.filter;
        document.querySelectorAll('#notifList .notif-item').forEach(card => {
            if (filter === 'all') {
                card.style.display = '';
            } else if (filter === 'unread') {
                card.style.display = card.dataset.read === '0' ? '' : 'none';
            } else {
                card.style.display = card.dataset.type === filter ? '' : 'none';
            }
        });
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
