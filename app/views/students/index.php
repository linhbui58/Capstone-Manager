<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">Students Management</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Quản lý danh sách sinh viên trong hệ thống</p>
        </div>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="index.php?page=student-create" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-plus me-2"></i>Thêm sinh viên
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

    <!-- Table Card -->
    <div class="table-container" data-aos="fade-up" data-aos-delay="50">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0" id="studentTable">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Sinh viên</th>
                        <th>Mã SV</th>
                        <th>Số điện thoại</th>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <th class="text-end pe-4">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $s): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold" style="color:var(--primary);">#<?= $s['id'] ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-letter">
                                            <?= strtoupper(mb_substr($s['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="color:var(--text-bright);font-size:13px;">
                                                <?= htmlspecialchars($s['full_name']) ?>
                                            </div>
                                            <div style="font-size:11px;color:var(--text-muted);">
                                                <?= htmlspecialchars($s['email']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold" style="font-size:13px;color:var(--text);font-family:monospace;">
                                        <?= htmlspecialchars($s['student_code'] ?? '—') ?>
                                    </span>
                                </td>
                                <td style="color:var(--text-muted);font-size:13px;">
                                    <?= htmlspecialchars($s['phone'] ?? '—') ?>
                                </td>
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="index.php?page=student-show&id=<?= $s['id'] ?>"
                                               class="icon-btn icon-btn-info" title="Xem">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="index.php?page=student-edit&id=<?= $s['id'] ?>"
                                               class="icon-btn icon-btn-warning" title="Sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="index.php?page=student-delete&id=<?= $s['id'] ?>"
                                               class="icon-btn icon-btn-danger btn-delete" title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fa-solid fa-user-graduate fa-2x mb-2 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                <span style="color:var(--text-faint);">Chưa có sinh viên nào.</span>
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
    if ($.fn.DataTable.isDataTable('#studentTable')) $('#studentTable').DataTable().destroy();
    $('#studentTable').DataTable({
        language: { search: '', searchPlaceholder: 'Tìm kiếm sinh viên...' },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
