<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">Lecturers Management</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Quản lý danh sách giảng viên trong hệ thống</p>
        </div>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="index.php?page=lecturer-create" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-plus me-2"></i>Thêm giảng viên
            </a>
        <?php endif; ?>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4" data-aos="fade-up">
        <div class="col-md-4">
            <div class="stat-card" style="border-top:2px solid #7c3aed;">
                <div class="stat-icon" style="background:rgba(124,58,237,0.15);color:#a78bfa;">
                    <i class="fa-solid fa-user-tie fa-lg"></i>
                </div>
                <div>
                    <div class="stat-label">Tổng giảng viên</div>
                    <div class="stat-value"><?= count($lecturers ?? []) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="table-container" data-aos="fade-up" data-aos-delay="50">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0" id="lecturerTable">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Giảng viên</th>
                        <th>Chuyên môn</th>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <th class="text-end pe-4">Thao tác</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lecturers)): ?>
                        <?php foreach ($lecturers as $lecturer): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold" style="color:var(--primary);">#<?= $lecturer['id'] ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-letter" style="background:linear-gradient(135deg,#7c3aed,#a855f7);">
                                            <?= strtoupper(substr($lecturer['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="color:var(--text-bright);font-size:13px;">
                                                <?= htmlspecialchars($lecturer['full_name']) ?>
                                            </div>
                                            <div style="font-size:11px;color:var(--text-muted);font-family:monospace;">
                                                <?= htmlspecialchars($lecturer['email']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width:280px;">
                                        <?php
                                        $expertises = explode(',', $lecturer['expertise'] ?? '');
                                        foreach ($expertises as $exp):
                                            $exp = trim($exp);
                                            if ($exp):
                                        ?>
                                            <span style="display:inline-flex;align-items:center;padding:2px 10px;margin:2px 2px 2px 0;font-size:10px;font-weight:600;background:rgba(124,58,237,0.1);color:#a78bfa;border:1px solid rgba(124,58,237,0.2);border-radius:50px;">
                                                <?= htmlspecialchars($exp) ?>
                                            </span>
                                        <?php endif; endforeach; ?>
                                    </div>
                                </td>
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="index.php?page=lecturer-show&id=<?= $lecturer['id'] ?>"
                                               class="icon-btn icon-btn-info" title="Xem">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="index.php?page=lecturer-edit&id=<?= $lecturer['id'] ?>"
                                               class="icon-btn icon-btn-warning" title="Sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="index.php?page=lecturer-delete&id=<?= $lecturer['id'] ?>"
                                               class="icon-btn icon-btn-danger btn-delete"
                                               onclick="return confirm('Xóa giảng viên này?')" title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fa-solid fa-user-tie fa-2x mb-2 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                <span style="color:var(--text-faint);">Chưa có giảng viên nào.</span>
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
    if ($.fn.DataTable.isDataTable('#lecturerTable')) $('#lecturerTable').DataTable().destroy();
    $('#lecturerTable').DataTable({
        language: {
            search: '', searchPlaceholder: 'Tìm kiếm giảng viên...',
            paginate: { next: '<i class="fa-solid fa-chevron-right"></i>', previous: '<i class="fa-solid fa-chevron-left"></i>' }
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>