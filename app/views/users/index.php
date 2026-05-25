<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">Users Management</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Quản lý tài khoản người dùng trong hệ thống</p>
        </div>
        <a href="index.php?page=user-create" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="fa-solid fa-plus me-2"></i>Thêm người dùng
        </a>
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
            <table class="table table-hover mb-0 datatable" id="userTable">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold" style="color:var(--primary);">#<?= $u['id'] ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-letter">
                                            <?= strtoupper(substr($u['email'], 0, 1)) ?>
                                        </div>
                                        <span class="fw-semibold" style="color:var(--text-bright);">
                                            <?= htmlspecialchars($u['email']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge role-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span>
                                </td>
                                <td>
                                    <span class="badge <?= ($u['status'] ?? 'active') === 'active' ? 'badge-approved' : 'badge-rejected' ?>">
                                        <i class="fa <?= ($u['status'] ?? 'active') === 'active' ? 'fa-circle-check' : 'fa-lock' ?> me-1"></i>
                                        <?= ucfirst($u['status'] ?? 'active') ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="index.php?page=user-show&id=<?= $u['id'] ?>"
                                           class="icon-btn icon-btn-info" title="Xem">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <?php if ($u['role'] !== 'admin'): ?>
                                            <a href="index.php?page=user-edit&id=<?= $u['id'] ?>"
                                               class="icon-btn icon-btn-warning" title="Sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <?php if (($u['status'] ?? 'active') === 'active'): ?>
                                                <a href="index.php?page=user-lock&id=<?= $u['id'] ?>"
                                                   class="icon-btn icon-btn-danger" title="Khóa">
                                                    <i class="fa-solid fa-lock"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="index.php?page=user-unlock&id=<?= $u['id'] ?>"
                                                   class="icon-btn icon-btn-success" title="Mở khóa">
                                                    <i class="fa-solid fa-lock-open"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="index.php?page=user-delete&id=<?= $u['id'] ?>"
                                               class="icon-btn icon-btn-danger btn-delete" title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fa-solid fa-users fa-2x mb-2 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                <span style="color:var(--text-faint);">Chưa có người dùng nào.</span>
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
    if ($.fn.DataTable.isDataTable('#userTable')) $('#userTable').DataTable().destroy();
    $('#userTable').DataTable({
        language: { search: '', searchPlaceholder: 'Tìm kiếm người dùng...' },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
