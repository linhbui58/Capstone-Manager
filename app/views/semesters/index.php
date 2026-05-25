<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">Semesters Management</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Manage academic periods and deadlines.</p>
        </div>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="index.php?page=semester-create" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-plus me-2"></i>Add Semester
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

    <!-- Table -->
    <div class="table-container" data-aos="fade-up" data-aos-delay="50">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0" id="semesterTable">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Semester Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <th class="text-end pe-4">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($semesters)): ?>
                        <?php foreach ($semesters as $s): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold" style="color:var(--primary);">#<?= $s['id'] ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-icon" style="background:rgba(99,102,241,0.12);color:#a78bfa;">
                                            <i class="fa-solid fa-calendar-days" style="font-size:13px;"></i>
                                        </div>
                                        <span class="fw-bold" style="color:var(--text-bright);">
                                            <?= htmlspecialchars($s['name']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span style="display:inline-flex;align-items:center;padding:4px 12px;font-size:11px;font-weight:600;background:rgba(59,130,246,0.1);color:#60a5fa;border:1px solid rgba(59,130,246,0.2);border-radius:50px;">
                                        <?= date('d/m/Y', strtotime($s['start_date'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="display:inline-flex;align-items:center;padding:4px 12px;font-size:11px;font-weight:600;background:rgba(16,185,129,0.1);color:#34d399;border:1px solid rgba(16,185,129,0.2);border-radius:50px;">
                                        <?= date('d/m/Y', strtotime($s['end_date'])) ?>
                                    </span>
                                </td>
                                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="index.php?page=semester-edit&id=<?= $s['id'] ?>"
                                               class="icon-btn icon-btn-warning" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="index.php?page=semester-delete&id=<?= $s['id'] ?>"
                                               class="icon-btn icon-btn-danger btn-delete"
                                               onclick="return confirm('Delete this semester?')" title="Delete">
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
                                <i class="fa-solid fa-calendar fa-2x mb-2 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                <span style="color:var(--text-faint);">No semesters found.</span>
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
    if ($.fn.DataTable.isDataTable('#semesterTable')) $('#semesterTable').DataTable().destroy();
    $('#semesterTable').DataTable({
        language: { search: '', searchPlaceholder: 'Search semester...' },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>