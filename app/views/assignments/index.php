<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">Assignments</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Manage lecturer-to-topic assignment records.</p>
        </div>
        <a href="index.php?page=assignment-create" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="fa-solid fa-plus me-2"></i>Add Assignment
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

    <!-- Table -->
    <div class="table-container" data-aos="fade-up" data-aos-delay="50">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0" id="assignmentsTable">
                <thead>
                    <tr>
                        <th class="ps-4">Topic</th>
                        <th>Supervisor</th>
                        <th>Assigned Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($assignments)): ?>
                        <?php foreach ($assignments as $a): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold" style="color:var(--text-bright);font-size:13px;">
                                        <?= htmlspecialchars($a['topic_title']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-icon" style="background:rgba(6,182,212,0.12);color:#38bdf8;">
                                            <i class="fa-solid fa-chalkboard-teacher" style="font-size:11px;"></i>
                                        </div>
                                        <span style="font-size:13px;color:var(--text);">
                                            <?= htmlspecialchars($a['lecturer_name']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="sem-badge">
                                        <i class="fa fa-calendar-check me-1" style="opacity:.6;"></i>
                                        <?= date('d/m/Y', strtotime($a['assigned_at'])) ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="index.php?page=assignment-delete&id=<?= $a['id'] ?>"
                                       class="icon-btn icon-btn-danger btn-delete"
                                       onclick="return confirm('Delete this assignment?')" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fa-solid fa-user-check fa-2x mb-2 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                <span style="color:var(--text-faint);">No assignments found.</span>
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
    if ($.fn.DataTable.isDataTable('#assignmentsTable')) $('#assignmentsTable').DataTable().destroy();
    $('#assignmentsTable').DataTable({
        language: { search: '', searchPlaceholder: 'Search assignments...' },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>