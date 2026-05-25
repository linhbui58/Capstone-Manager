<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">Milestone Management</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Set up submission stages and deadlines for each semester.</p>
        </div>
        <a href="index.php?page=milestone-create" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="fa-solid fa-calendar-plus me-2"></i>Add Milestone
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
            <table class="table table-hover align-middle mb-0" id="milestonesTable">
                <thead>
                    <tr>
                        <th class="ps-4">Milestone</th>
                        <th>Semester</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($milestones)): foreach ($milestones as $m):
                        $isOverdue = strtotime(date('Y-m-d H:i:s')) > strtotime($m['deadline']);
                    ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-icon" style="background:rgba(99,102,241,0.12);color:#a78bfa;">
                                        <i class="fa-solid fa-flag-checkered" style="font-size:13px;"></i>
                                    </div>
                                    <span class="fw-bold" style="color:var(--text-bright);">
                                        <?= htmlspecialchars($m['title']) ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="sem-badge">
                                    <i class="fa fa-calendar-alt me-1" style="opacity:.6;"></i>
                                    <?= htmlspecialchars($m['semester_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <span class="<?= $isOverdue ? 'fw-bold' : '' ?>"
                                      style="color:<?= $isOverdue ? '#f87171' : '#34d399' ?>;font-size:13px;">
                                    <i class="fa fa-clock me-1" style="opacity:.7;"></i>
                                    <?= date('H:i | d/m/Y', strtotime($m['deadline'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($isOverdue): ?>
                                    <span class="badge badge-rejected">Expired</span>
                                <?php else: ?>
                                    <span class="badge badge-approved">Open</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="index.php?page=milestone-edit&id=<?= $m['id'] ?>"
                                       class="icon-btn icon-btn-warning" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="index.php?page=milestone-delete&id=<?= $m['id'] ?>"
                                       class="icon-btn icon-btn-danger btn-delete"
                                       onclick="return confirm('Delete this milestone?')" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fa-solid fa-inbox fa-2x mb-3 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                <div class="fw-semibold" style="color:var(--text-muted);">No milestones configured yet.</div>
                                <div style="font-size:12px;color:var(--text-faint);margin-top:4px;">Add a milestone to get started.</div>
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
    if ($.fn.DataTable.isDataTable('#milestonesTable')) $('#milestonesTable').DataTable().destroy();
    $('#milestonesTable').DataTable({
        language: { search: '', searchPlaceholder: 'Search milestones...' },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>