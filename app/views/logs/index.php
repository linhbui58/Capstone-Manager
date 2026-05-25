<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">System Logs</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Detailed activity history of all users in the system.</p>
        </div>
        <button onclick="window.location.reload()"
                class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
            <i class="fa-solid fa-arrows-rotate me-1"></i>Refresh
        </button>
    </div>

    <!-- Table -->
    <div class="table-container" data-aos="fade-up" data-aos-delay="50">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0" id="systemLogTable">
                <thead>
                    <tr>
                        <th class="ps-4">Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold" style="font-size:13px;color:var(--text-bright);">
                                        <?= date('H:i:s', strtotime($log['created_at'])) ?>
                                    </div>
                                    <div style="font-size:11px;color:var(--text-faint);">
                                        <?= date('d/m/Y', strtotime($log['created_at'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold" style="color:var(--text-bright);font-size:13px;">
                                        <?= htmlspecialchars($log['email'] ?? 'System') ?>
                                    </div>
                                    <div style="font-size:10px;color:var(--text-faint);text-transform:uppercase;letter-spacing:.5px;">
                                        <?= strtoupper($log['role'] ?? 'N/A') ?>
                                    </div>
                                </td>
                                <td>
                                    <span style="display:inline-flex;align-items:center;padding:3px 10px;font-size:11px;font-weight:600;background:rgba(124,58,237,0.1);color:#a78bfa;border:1px solid rgba(124,58,237,0.2);border-radius:50px;">
                                        <?= htmlspecialchars($log['action']) ?>
                                    </span>
                                </td>
                                <td>
                                    <code style="font-size:12px;color:#60a5fa;background:rgba(59,130,246,0.08);padding:2px 8px;border-radius:6px;">
                                        <?= htmlspecialchars($log['details'] ?? '---') ?>
                                    </code>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fa-solid fa-clock-rotate-left fa-2x mb-2 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                <span style="color:var(--text-faint);">No system logs found.</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'none';
    $('#systemLogTable').DataTable({
        order: [[0, 'desc']],
        language: {
            search: '',
            searchPlaceholder: 'Search logs...',
            lengthMenu: '_MENU_ rows per page',
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>