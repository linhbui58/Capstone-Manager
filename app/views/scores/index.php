<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">Evaluation Results</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">Track and manage capstone defense scores for students.</p>
        </div>
        <?php if ($_SESSION['user']['role'] === 'lecturer'): ?>
            <a href="index.php?page=score-create" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-star me-2"></i>Add Score
            </a>
        <?php endif; ?>
    </div>

    <!-- Table -->
    <div class="table-container" data-aos="fade-up" data-aos-delay="50">
        <div class="table-responsive p-3">
            <table class="table table-hover mb-0" id="scoreTable">
                <thead>
                    <tr>
                        <th class="ps-4">Student & Topic</th>
                        <th class="text-center">Score</th>
                        <th>Supervisor</th>
                        <th>Feedback</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($scores)): ?>
                        <?php foreach ($scores as $s):
                            $scoreClass = ($s['score'] >= 8) ? ['#34d399','rgba(16,185,129,0.12)'] : (($s['score'] >= 5) ? ['#fbbf24','rgba(245,158,11,0.12)'] : ['#f87171','rgba(239,68,68,0.12)']);
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold" style="color:var(--text-bright);"><?= htmlspecialchars($s['student_name']) ?></div>
                                    <div style="font-size:12px;color:var(--text-muted);max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        <?= htmlspecialchars($s['topic_title']) ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div style="width:45px;height:45px;border-radius:12px;background:<?= $scoreClass[1] ?>;color:<?= $scoreClass[0] ?>;font-weight:800;font-size:1.1rem;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                                        <?= number_format($s['score'], 1) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size:13px;color:var(--text);"><?= htmlspecialchars($s['lecturer_name']) ?></div>
                                    <div style="font-size:11px;color:var(--text-faint);"><?= date('d/m/Y', strtotime($s['graded_at'])) ?></div>
                                </td>
                                <td>
                                    <div style="font-size:12px;color:var(--text-muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        <?= htmlspecialchars($s['feedback'] ?? 'No feedback provided.') ?>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="index.php?page=score-edit&id=<?= $s['id'] ?>"
                                           class="icon-btn icon-btn-warning" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="index.php?page=score-delete&id=<?= $s['id'] ?>"
                                           class="icon-btn icon-btn-danger btn-delete"
                                           onclick="return confirm('Delete this score?')" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fa-solid fa-star fa-2x mb-2 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                <span style="color:var(--text-faint);">No evaluation records found.</span>
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
    if ($.fn.DataTable.isDataTable('#scoreTable')) $('#scoreTable').DataTable().destroy();
    $('#scoreTable').DataTable({
        language: { search: '', searchPlaceholder: 'Search scores...' },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small text-muted"i><"small"p>>'
    });
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>