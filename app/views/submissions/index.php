<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fa-solid fa-file-arrow-up me-2" style="color:var(--primary);"></i>Milestone Submissions
            </h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">
                <?php if ($_SESSION['user']['role'] === 'student'): ?>
                    Bài nộp của bạn theo từng cột mốc đồ án
                <?php else: ?>
                    Tổng hợp bài nộp của tất cả sinh viên
                <?php endif; ?>
            </p>
        </div>
        <?php if ($_SESSION['user']['role'] === 'student'): ?>
            <a href="index.php?page=submission-create" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-upload me-2"></i>Nộp bài mới
            </a>
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
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fa-solid fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Stats Row (admin/lecturer) -->
    <?php if ($_SESSION['user']['role'] !== 'student' && !empty($submissions)): ?>
        <?php
            $total     = count($submissions);
            $submitted = count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'submitted'));
            $late      = count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'late'));
            $revision  = count(array_filter($submissions, fn($s) => ($s['status'] ?? '') === 'revision_required'));
        ?>
        <div class="row g-3 mb-4" data-aos="fade-up">
            <?php foreach ([
                ['label'=>'Tổng bài nộp', 'val'=>$total,    'icon'=>'fa-layer-group',    'color'=>'#60a5fa', 'bg'=>'rgba(59,130,246,0.12)'],
                ['label'=>'Đã nộp',        'val'=>$submitted,'icon'=>'fa-circle-check',   'color'=>'#34d399', 'bg'=>'rgba(16,185,129,0.12)'],
                ['label'=>'Nộp trễ',       'val'=>$late,     'icon'=>'fa-clock',           'color'=>'#fbbf24', 'bg'=>'rgba(245,158,11,0.12)'],
                ['label'=>'Cần sửa lại',   'val'=>$revision, 'icon'=>'fa-rotate-left',     'color'=>'#f87171', 'bg'=>'rgba(239,68,68,0.12)'],
            ] as $stat): ?>
            <div class="col-6 col-md-3">
                <div class="stat-card" style="border-top:2px solid <?= $stat['color'] ?>;">
                    <div class="stat-icon" style="background:<?= $stat['bg'] ?>;color:<?= $stat['color'] ?>;">
                        <i class="fa-solid <?= $stat['icon'] ?>"></i>
                    </div>
                    <div>
                        <div class="stat-label"><?= $stat['label'] ?></div>
                        <div class="stat-value"><?= $stat['val'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="table-container" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0" id="submissionsTable">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Sinh viên</th>
                        <th>Cột mốc</th>
                        <th>File</th>
                        <th>Trạng thái</th>
                        <th>Thời gian nộp</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($submissions)): ?>
                        <?php foreach ($submissions as $sub): ?>
                            <?php
                                $status = $sub['status'] ?? 'submitted';
                                $statusLabel = match($status) {
                                    'submitted'         => 'Đã nộp',
                                    'late'              => 'Nộp trễ',
                                    'revision_required' => 'Cần sửa',
                                    default             => ucfirst($status),
                                };
                                $statusBadge = match($status) {
                                    'submitted'         => 'badge-approved',
                                    'late'              => 'badge-pending',
                                    'revision_required' => 'badge-rejected',
                                    default             => 'badge-pending',
                                };
                                $ext = strtolower(pathinfo($sub['file_path'] ?? '', PATHINFO_EXTENSION));
                                [$fileIcon, $fileColor] = match($ext) {
                                    'pdf'        => ['fa-file-pdf',    '#f87171'],
                                    'doc','docx' => ['fa-file-word',   '#60a5fa'],
                                    'zip','rar'  => ['fa-file-zipper', '#fbbf24'],
                                    default      => ['fa-file',        'var(--text-faint)'],
                                };
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold" style="color:var(--primary);">#<?= $sub['id'] ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-icon" style="background:rgba(99,102,241,0.15);color:#a78bfa;">
                                            <i class="fa-solid fa-user-graduate" style="font-size:13px;"></i>
                                        </div>
                                        <span class="fw-semibold" style="color:var(--text-bright);font-size:13px;">
                                            <?= htmlspecialchars($sub['student_name'] ?? '—') ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold" style="font-size:13px;color:var(--text);">
                                        <?= htmlspecialchars(ucfirst($sub['milestone_title'] ?? '—')) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($sub['file_path'])): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-icon" style="background:rgba(255,255,255,0.05);color:<?= $fileColor ?>;">
                                                <i class="fa-solid <?= $fileIcon ?>"></i>
                                            </div>
                                            <span style="font-size:11px;color:var(--text-muted);max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:inline-block;">
                                                <?= htmlspecialchars(basename($sub['file_path'])) ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span style="color:var(--text-faint);">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $statusBadge ?>">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size:13px;color:var(--text);">
                                        <?= $sub['submitted_at'] ? date('d/m/Y', strtotime($sub['submitted_at'])) : '—' ?>
                                    </div>
                                    <div style="font-size:10px;color:var(--text-faint);">
                                        <?= $sub['submitted_at'] ? date('H:i', strtotime($sub['submitted_at'])) : '' ?>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="index.php?page=submission-show&id=<?= $sub['id'] ?>"
                                           class="icon-btn icon-btn-info" title="Xem chi tiết">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if (!empty($sub['file_path'])): ?>
                                            <a href="assets/uploads/<?= htmlspecialchars($sub['file_path']) ?>"
                                               target="_blank"
                                               class="icon-btn icon-btn-success" title="Tải file">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (in_array($_SESSION['user']['role'], ['admin','lecturer'])): ?>
                                            <?php if ($status !== 'submitted'): ?>
                                                <a href="index.php?page=submission-status&id=<?= $sub['id'] ?>&status=submitted"
                                                   class="icon-btn icon-btn-success" title="Xác nhận đã nộp">
                                                    <i class="fa-solid fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="index.php?page=submission-status&id=<?= $sub['id'] ?>&status=revision_required"
                                               class="icon-btn icon-btn-warning" title="Yêu cầu sửa lại"
                                               onclick="return confirm('Yêu cầu sinh viên sửa lại?')">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </a>
                                            <a href="index.php?page=submission-delete&id=<?= $sub['id'] ?>"
                                               class="icon-btn icon-btn-danger btn-delete" title="Xóa">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa-solid fa-inbox fa-3x mb-3 d-block" style="opacity:.15;color:var(--text-muted);"></i>
                                <div class="fw-semibold" style="color:var(--text-muted);">Chưa có bài nộp nào</div>
                                <?php if ($_SESSION['user']['role'] === 'student'): ?>
                                    <div style="font-size:12px;color:var(--text-faint);margin-top:4px;">Bấm "Nộp bài mới" để bắt đầu</div>
                                <?php endif; ?>
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
    if ($.fn.DataTable.isDataTable('#submissionsTable')) $('#submissionsTable').DataTable().destroy();
    $('#submissionsTable').DataTable({
        pageLength: 10,
        language: {
            search: '', searchPlaceholder: 'Tìm kiếm bài nộp...',
            lengthMenu: '_MENU_ dòng/trang',
            info: 'Hiển thị _START_–_END_ / _TOTAL_',
            paginate: { previous: 'Trước', next: 'Sau' },
            emptyTable: 'Không có dữ liệu'
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3 px-2"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
