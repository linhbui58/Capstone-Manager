<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<div class="main-content">

    <!-- Topbar -->
    <div class="topbar d-flex justify-content-between align-items-center p-4 mb-4" data-aos="fade-down">
        <div class="topbar-left">
            <h2 class="fw-bold mb-1">Dashboard Overview</h2>
            <p class="small mb-0">Professional Capstone Management System</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-submitted px-3 py-2" style="font-size:12px;">
                <i class="fa fa-circle me-1" style="font-size:8px;"></i> Live
            </span>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mb-4 d-flex gap-2 flex-wrap" data-aos="fade-up" data-aos-delay="50">
        <?php if($_SESSION['user']['role'] == 'admin'): ?>
            <a href="index.php?page=topic-create" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-plus"></i> New Topic
            </a>
            <a href="index.php?page=students" class="btn btn-success rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-user-graduate"></i> Students
            </a>
            <a href="index.php?page=lecturers" class="btn btn-warning rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-chalkboard-user"></i> Lecturers
            </a>
        <?php endif; ?>

        <?php if($_SESSION['user']['role'] == 'student'): ?>
            <a href="index.php?page=topic-create" class="btn btn-primary rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-paper-plane"></i> Submit Topic
            </a>
            <a href="index.php?page=submissions" class="btn btn-success rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-file-lines"></i> My Submissions
            </a>
        <?php endif; ?>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid-dashboard mb-4">

        <div class="stat-card p-4 d-flex align-items-center gap-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-icon d-flex align-items-center justify-content-center"
                 style="width:56px;height:56px;background:rgba(59,130,246,0.15);color:#60a5fa;border-radius:14px;font-size:22px;border:1px solid rgba(59,130,246,0.2);flex-shrink:0;">
                <i class="fa-solid fa-book"></i>
            </div>
            <div class="stat-info">
                <h5 class="small fw-bold mb-1" style="text-transform:uppercase;letter-spacing:.5px;">Total Topics</h5>
                <h2 id="topic-count" class="fw-bold mb-0" style="font-size:28px;"><?= $totalTopics ?? 0 ?></h2>
            </div>
        </div>

        <div class="stat-card p-4 d-flex align-items-center gap-3" data-aos="fade-up" data-aos-delay="150">
            <div class="stat-icon d-flex align-items-center justify-content-center"
                 style="width:56px;height:56px;background:rgba(16,185,129,0.15);color:#34d399;border-radius:14px;font-size:22px;border:1px solid rgba(16,185,129,0.2);flex-shrink:0;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-info">
                <h5 class="small fw-bold mb-1" style="text-transform:uppercase;letter-spacing:.5px;">Students</h5>
                <h2 class="fw-bold mb-0" style="font-size:28px;"><?= $totalStudents ?? 0 ?></h2>
            </div>
        </div>

        <div class="stat-card p-4 d-flex align-items-center gap-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-icon d-flex align-items-center justify-content-center"
                 style="width:56px;height:56px;background:rgba(245,158,11,0.15);color:#fbbf24;border-radius:14px;font-size:22px;border:1px solid rgba(245,158,11,0.2);flex-shrink:0;">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="stat-info">
                <h5 class="small fw-bold mb-1" style="text-transform:uppercase;letter-spacing:.5px;">Lecturers</h5>
                <h2 class="fw-bold mb-0" style="font-size:28px;"><?= $totalLecturers ?? 0 ?></h2>
            </div>
        </div>

        <div class="stat-card p-4 d-flex align-items-center gap-3" data-aos="fade-up" data-aos-delay="250">
            <div class="stat-icon d-flex align-items-center justify-content-center"
                 style="width:56px;height:56px;background:rgba(239,68,68,0.15);color:#f87171;border-radius:14px;font-size:22px;border:1px solid rgba(239,68,68,0.2);flex-shrink:0;">
                <i class="fa-solid fa-file-import"></i>
            </div>
            <div class="stat-info">
                <h5 class="small fw-bold mb-1" style="text-transform:uppercase;letter-spacing:.5px;">Submissions</h5>
                <h2 class="fw-bold mb-0" style="font-size:28px;"><?= $totalSubmissions ?? 0 ?></h2>
            </div>
        </div>

    </div>

    <!-- Tables Row -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="dashboard-box p-4" data-aos="fade-up" data-aos-delay="300">
                <div class="box-header mb-4 d-flex align-items-center justify-content-between">
                    <h4 class="fw-bold m-0" style="font-size:16px;">
                        <i class="fa-solid fa-list-check me-2" style="color:#a78bfa;"></i>Recent Topics
                    </h4>
                    <a href="index.php?page=topics" class="btn btn-light btn-sm rounded-pill px-3 fw-bold" style="font-size:12px;">
                        View All <i class="fa fa-arrow-right ms-1" style="font-size:10px;"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table datatable table-hover border-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Topic Title</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($recentTopics)): ?>
                            <?php foreach($recentTopics as $topic): ?>
                                <?php $status = $topic['status'] ?? 'pending'; ?>
                                <tr>
                                    <td class="fw-bold" style="color:#6366f1;">#<?= $topic['id']; ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($topic['title']); ?></td>
                                    <td>
                                        <span class="badge badge-<?= htmlspecialchars($status); ?>">
                                            <?= ucfirst(htmlspecialchars($status)); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-4" style="color:var(--text-faint);">No topics found</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-box p-4" data-aos="fade-up" data-aos-delay="350">
                <div class="box-header mb-4 d-flex align-items-center justify-content-between">
                    <h4 class="fw-bold m-0" style="font-size:16px;">
                        <i class="fa-solid fa-paper-plane me-2" style="color:#34d399;"></i>Recent Submissions
                    </h4>
                    <a href="index.php?page=submissions" class="btn btn-light btn-sm rounded-pill px-3 fw-bold" style="font-size:12px;">
                        View All <i class="fa fa-arrow-right ms-1" style="font-size:10px;"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table datatable table-hover border-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($recentSubmissions)): ?>
                            <?php foreach($recentSubmissions as $sub): ?>
                                <?php $status = $sub['status'] ?? 'pending'; ?>
                                <tr>
                                    <td class="fw-bold" style="color:#6366f1;">#<?= $sub['id']; ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($sub['student_name']); ?></td>
                                    <td>
                                        <span class="badge badge-<?= htmlspecialchars($status); ?>">
                                            <?= ucfirst(htmlspecialchars($status)); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-4" style="color:var(--text-faint);">No submissions found</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="dashboard-box p-4" data-aos="fade-up" data-aos-delay="400">
        <div class="box-header mb-4 d-flex align-items-center justify-content-between">
            <h4 class="fw-bold m-0" style="font-size:16px;">
                <i class="fa-solid fa-chart-column me-2" style="color:#60a5fa;"></i>Topic Statistics Overview
            </h4>
        </div>
        <div style="height:320px;position:relative;">
            <canvas id="topicChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'none';
    $('.datatable').DataTable({
        destroy: true,
        retrieve: true,
        pageLength: 5,
        responsive: true,
        dom: 'rt<"d-flex justify-content-between p-3"ip>',
        language: {
            search: "",
            searchPlaceholder: "Quick search...",
            paginate: { previous: "Prev", next: "Next" }
        }
    });
});

const ctx = document.getElementById('topicChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Draft', 'Pending', 'Approved', 'Rejected'],
        datasets: [{
            label: 'Topics Distribution',
            data: [
                <?= $draftCount ?? 0 ?>,
                <?= $pendingCount ?? 0 ?>,
                <?= $approvedCount ?? 0 ?>,
                <?= $rejectedCount ?? 0 ?>
            ],
            backgroundColor: [
                'rgba(148,163,184,0.7)',
                'rgba(245,158,11,0.7)',
                'rgba(16,185,129,0.7)',
                'rgba(239,68,68,0.7)'
            ],
            borderColor: [
                'rgba(148,163,184,1)',
                'rgba(245,158,11,1)',
                'rgba(16,185,129,1)',
                'rgba(239,68,68,1)'
            ],
            borderWidth: 1,
            borderRadius: 10,
            barThickness: 48
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(13,21,38,0.95)',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                titleColor: '#e2e8f0',
                bodyColor: '#94a3b8',
                padding: 12,
                cornerRadius: 10
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.04)' },
                ticks: { stepSize: 1, color: '#475569', font: { weight: '700', family: 'Plus Jakarta Sans' } },
                border: { color: 'rgba(255,255,255,0.06)' }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#64748b', font: { weight: '700', family: 'Plus Jakarta Sans' } },
                border: { color: 'rgba(255,255,255,0.06)' }
            }
        }
    }
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>