<?php require '../app/views/layouts/header.php'; ?>
<?php require '../app/views/layouts/sidebar.php'; ?>

<?php
$role      = $_SESSION['user']['role'];
$activeTab = $_GET['tab'] ?? 'topics';
?>

<div class="main-content">

    <!-- ── Page Header ── -->
    <div class="page-header mb-4" data-aos="fade-down">
        <div>
            <h2 class="fw-bold mb-1">Topics &amp; Registrations</h2>
            <p class="mb-0" style="color:var(--text-muted);font-size:13px;">
                <?php if ($role === 'admin'): ?>Manage all topics and registrations in the system
                <?php elseif ($role === 'lecturer'): ?>Topics and registrations related to you
                <?php else: ?>View topics and track your registrations
                <?php endif; ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <?php if (in_array($role, ['admin', 'student'])): ?>
                <a href="index.php?page=topic-create" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    <i class="fa fa-plus me-1"></i>
                    <?= $role === 'admin' ? 'Add Topic' : 'Propose Topic' ?>
                </a>
                </a>
            <?php endif; ?>
            <?php if ($role === 'student'): ?>
                <a href="index.php?page=registration-create" class="btn btn-primary rounded-pill px-4 fw-bold">
                    <i class="fa fa-file-signature me-1"></i> New Registration
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Flash Messages ── -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-circle-xmark me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- ── Main Card ── -->
    <div class="glass-panel" data-aos="fade-up" data-aos-delay="50">

        <!-- Tab Nav -->
        <div class="topic-tab-nav">
            <a class="topic-tab-item <?= $activeTab === 'topics' ? 'active' : '' ?>"
               href="index.php?page=topic-management&tab=topics">
                <i class="fa fa-book me-2"></i>Topics
                <span class="topic-tab-badge"><?= count($topics) ?></span>
            </a>
            <a class="topic-tab-item <?= $activeTab === 'registrations' ? 'active' : '' ?>"
               href="index.php?page=topic-management&tab=registrations">
                <i class="fa fa-file-signature me-2"></i>Registrations
                <span class="topic-tab-badge" style="background:rgba(245,158,11,0.15);color:#fbbf24;border-color:rgba(245,158,11,0.25);">
                    <?= count($registrations) ?>
                </span>
            </a>
        </div>

        <div class="p-4">

            <!-- ════════════════════════════════════
                 TAB 1: TOPICS
            ════════════════════════════════════ -->
            <?php if ($activeTab === 'topics'): ?>

                <!-- Filter bar -->
                <div class="filter-bar mb-4">
                    <form method="GET" action="index.php" class="row g-2 align-items-end">
                        <input type="hidden" name="page" value="topic-management">
                        <input type="hidden" name="tab"  value="topics">

                        <div class="col-md-<?= $role === 'student' ? '7' : '5' ?>">
                            <label class="form-label">SEARCH</label>
                            <input type="text" name="search" class="form-control rounded-pill"
                                   placeholder="Topic title, keyword..."
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">SEMESTER</label>
                            <select name="semester_id" class="form-select rounded-pill">
                                <option value="0">All semesters</option>
                                <?php foreach ($semesters as $sem): ?>
                                    <option value="<?= $sem['id'] ?>"
                                        <?= (int)($_GET['semester_id'] ?? 0) === (int)$sem['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sem['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if ($role !== 'student'): ?>
                        <div class="col-md-2">
                            <label class="form-label">STATUS</label>
                            <select name="status" class="form-select rounded-pill">
                                <option value="">All</option>
                                <option value="pending"  <?= ($_GET['status'] ?? '') === 'pending'  ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= ($_GET['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= ($_GET['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-3 flex-fill fw-bold">
                                <i class="fa fa-search me-1"></i>Filter
                            </button>
                            <a href="index.php?page=topic-management&tab=topics" class="btn btn-secondary rounded-pill px-3">
                                <i class="fa fa-rotate-left"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Topics Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="topicsTable">
                        <thead>
                            <tr>
                                <th class="ps-2">Title</th>
                                <th>Keywords</th>
                                <th>Semester</th>
                                <th>Status</th>
                                <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <th class="text-center">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topics)): foreach ($topics as $t):
                                $ts  = $t['status'];
                                $tlbl = $ts === 'approved' ? 'Approved' : ($ts === 'rejected' ? 'Rejected' : ($ts === 'draft' ? 'Draft' : 'Pending'));
                                $tico = $ts === 'approved' ? 'fa-circle-check' : ($ts === 'rejected' ? 'fa-circle-xmark' : ($ts === 'draft' ? 'fa-pen-ruler' : 'fa-clock'));
                            ?>
                                <tr>
                                    <td class="ps-2">
                                        <div class="fw-semibold" style="color:var(--text-bright);">
                                            <?= htmlspecialchars($t['title']) ?>
                                        </div>
                                        <?php if (!empty($t['description'])): ?>
                                            <div style="max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--text-muted);font-size:12px;margin-top:2px;">
                                                <?= htmlspecialchars($t['description']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($t['keywords'])): ?>
                                            <?php foreach (explode(',', $t['keywords']) as $kw): ?>
                                                <span class="kw-badge"><?= htmlspecialchars(trim($kw)) ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span style="color:var(--text-faint);">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="sem-badge">
                                            <i class="fa fa-calendar-alt me-1" style="opacity:.6;"></i>
                                            <?= htmlspecialchars($t['semester']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $ts ?>">
                                            <i class="fa <?= $tico ?> me-1"></i><?= $tlbl ?>
                                        </span>
                                    </td>
                                    <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 flex-wrap">
                                            <?php if ($role === 'admin'): ?>
                                                <?php if ($ts === 'pending'): ?>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                       class="btn btn-sm btn-success rounded-pill px-3"
                                                       onclick="return confirm('Approve this topic?')">
                                                        <i class="fa fa-check me-1"></i>Approve
                                                    </a>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                       class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                       onclick="return confirm('Reject this topic?')">
                                                        <i class="fa fa-xmark me-1"></i>Reject
                                                    </a>
                                                <?php elseif ($ts === 'approved'): ?>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                       class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                       onclick="return confirm('Revoke approval?')">
                                                        <i class="fa fa-rotate-left me-1"></i>Revoke
                                                    </a>
                                                <?php else: ?>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                       class="btn btn-sm btn-outline-success rounded-pill px-3"
                                                       onclick="return confirm('Re-approve?')">
                                                        <i class="fa fa-rotate-left me-1"></i>Re-approve
                                                    </a>
                                                <?php endif; ?>
                                                <a href="index.php?page=topic-edit&id=<?= $t['id'] ?>"
                                                   class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <a href="index.php?page=topic-delete&id=<?= $t['id'] ?>"
                                                   class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                   onclick="return confirm('Confirm delete this topic?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php elseif ($role === 'lecturer'): ?>
                                                <?php if ($ts === 'pending'): ?>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=approved"
                                                       class="btn btn-sm btn-success rounded-pill px-3"
                                                       onclick="return confirm('Approve this topic?')">
                                                        <i class="fa fa-check me-1"></i>Approve
                                                    </a>
                                                    <a href="index.php?page=topic-status&id=<?= $t['id'] ?>&status=rejected"
                                                       class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                       onclick="return confirm('Reject?')">
                                                        <i class="fa fa-xmark me-1"></i>Reject
                                                    </a>
                                                <?php else: ?>
                                                    <span style="color:var(--text-faint);font-size:12px;">Processed</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="<?= in_array($role, ['admin', 'lecturer']) ? 5 : 4 ?>"
                                        class="text-center py-5">
                                        <i class="fa fa-inbox fa-2x mb-3 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                        <div class="fw-semibold" style="color:var(--text-muted);">No topics found</div>
                                        <div style="font-size:12px;color:var(--text-faint);margin-top:4px;">
                                            <?= $role === 'student' ? 'No approved topics available yet.' : 'Add a new topic to get started.' ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; /* end tab topics */ ?>


            <!-- ════════════════════════════════════
                 TAB 2: REGISTRATIONS
            ════════════════════════════════════ -->
            <?php if ($activeTab === 'registrations'): ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="registrationsTable">
                        <thead>
                            <tr>
                                <th class="ps-2">Topic</th>
                                <th>Semester</th>
                                <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <th>Student</th>
                                <?php endif; ?>
                                <?php if ($role === 'admin'): ?>
                                    <th>Desired Supervisor</th>
                                <?php endif; ?>
                                <th>Status</th>
                                <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <th class="text-center">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($registrations)): foreach ($registrations as $r):
                                $s        = $r['status'];
                                $badgeLbl = $s === 'approved' ? 'Approved' : ($s === 'rejected' ? 'Rejected' : 'Pending');
                                $badgeIcon= $s === 'approved' ? 'fa-circle-check' : ($s === 'rejected' ? 'fa-circle-xmark' : 'fa-clock');
                            ?>
                                <tr>
                                    <!-- Đề tài -->
                                    <td class="ps-2">
                                        <div class="fw-semibold" style="color:var(--text-bright);">
                                            <?= htmlspecialchars($r['topic_title']) ?>
                                        </div>
                                        <?php if (!empty($r['keywords'])): ?>
                                            <div class="mt-1">
                                                <?php foreach (explode(',', $r['keywords']) as $kw): ?>
                                                    <span class="kw-badge"><?= htmlspecialchars(trim($kw)) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Học kỳ -->
                                    <td>
                                        <span class="sem-badge">
                                            <i class="fa fa-calendar-alt me-1" style="opacity:.6;"></i>
                                            <?= htmlspecialchars($r['semester_name']) ?>
                                        </span>
                                    </td>

                                    <!-- Sinh viên -->
                                    <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-icon" style="background:rgba(99,102,241,0.15);color:#a78bfa;">
                                                <i class="fa fa-user-graduate" style="font-size:13px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:13px;color:var(--text-bright);">
                                                    <?= htmlspecialchars($r['student_name'] ?? 'N/A') ?>
                                                </div>
                                                <div style="font-size:10px;color:var(--text-faint);">ID: <?= $r['student_id'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <?php endif; ?>

                                    <!-- GV mong muốn -->
                                    <?php if ($role === 'admin'): ?>
                                    <td>
                                        <?php if (!empty($r['lecturer_name'])): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-icon" style="background:rgba(6,182,212,0.12);color:#38bdf8;">
                                                    <i class="fa fa-chalkboard-teacher" style="font-size:11px;"></i>
                                                </div>
                                                <span style="font-size:13px;color:var(--text);">
                                                    <?= htmlspecialchars($r['lecturer_name']) ?>
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <span style="color:var(--text-faint);font-size:12px;font-style:italic;">Không chỉ định</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>

                                    <!-- Trạng thái -->
                                    <td>
                                        <span class="badge badge-<?= $s ?>">
                                            <i class="fa <?= $badgeIcon ?> me-1"></i><?= $badgeLbl ?>
                                        </span>
                                    </td>

                                    <!-- Thao tác -->
                                    <?php if (in_array($role, ['admin', 'lecturer'])): ?>
                                    <td class="text-center">
                                        <?php if ($s === 'pending'): ?>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=approved"
                                                   class="btn btn-sm btn-success rounded-pill px-3"
                                                   onclick="return confirm('Approve this registration?')">
                                                    <i class="fa fa-check me-1"></i>Approve
                                                </a>
                                                <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=rejected"
                                                   class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                   onclick="return confirm('Reject?')">
                                                    <i class="fa fa-xmark me-1"></i>Reject
                                                </a>
                                            </div>
                                        <?php elseif ($s === 'approved'): ?>
                                            <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=rejected"
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                               onclick="return confirm('Revoke approval?')">
                                                <i class="fa fa-rotate-left me-1"></i>Revoke
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?page=registration-status&id=<?= $r['id'] ?>&status=approved"
                                               class="btn btn-sm btn-outline-success rounded-pill px-3"
                                               onclick="return confirm('Re-approve?')">
                                                <i class="fa fa-rotate-left me-1"></i>Re-approve
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="<?= $role === 'admin' ? 6 : ($role === 'lecturer' ? 5 : 3) ?>"
                                        class="text-center py-5">
                                        <i class="fa fa-folder-open fa-2x mb-3 d-block" style="opacity:.2;color:var(--text-muted);"></i>
                                        <div class="fw-semibold" style="color:var(--text-muted);">No registrations found</div>
                                        <div style="font-size:12px;color:var(--text-faint);margin-top:4px;">
                                            <?= $role === 'student'
                                                ? 'Click "New Registration" to register a topic.'
                                                : 'No students have registered yet.' ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; /* end tab registrations */ ?>

        </div><!-- /.p-4 -->
    </div><!-- /.glass-panel -->

</div><!-- /.main-content -->

<style>
/* ── Page-level styles — dark themed ── */

/* Tab navigation */
.topic-tab-nav {
    display: flex;
    gap: 4px;
    padding: 16px 20px 0;
    border-bottom: 1px solid var(--glass-border);
    background: rgba(0,0,0,0.15);
}

.topic-tab-item {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-muted);
    border-radius: var(--radius-md) var(--radius-md) 0 0;
    transition: var(--transition);
    text-decoration: none !important;
    position: relative;
    border: 1px solid transparent;
    border-bottom: none;
    margin-bottom: -1px;
}

.topic-tab-item:hover {
    color: var(--text-bright);
    background: var(--glass-bg);
}

.topic-tab-item.active {
    color: #a78bfa;
    background: rgba(10, 15, 30, 1);
    border-color: var(--glass-border);
    border-bottom-color: rgba(10,15,30,1);
}

.topic-tab-item.active::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, #7c3aed, #06b6d4);
    border-radius: var(--radius-md) var(--radius-md) 0 0;
}

.topic-tab-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    font-size: 10px;
    font-weight: 700;
    background: rgba(124,58,237,0.15);
    color: #a78bfa;
    border: 1px solid rgba(124,58,237,0.25);
    border-radius: 50px;
    margin-left: 8px;
}

/* Filter bar */
.filter-bar {
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-lg);
    padding: 16px 20px;
}

/* Keyword badge */
.kw-badge {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    font-size: 10px;
    font-weight: 600;
    background: rgba(255,255,255,0.06);
    color: var(--text-muted);
    border: 1px solid var(--glass-border);
    border-radius: 50px;
    margin-right: 4px;
    margin-bottom: 2px;
}

/* Semester badge */
.sem-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    background: rgba(59,130,246,0.1);
    color: #60a5fa;
    border: 1px solid rgba(59,130,246,0.2);
    border-radius: 50px;
    white-space: nowrap;
}

/* Avatar icon */
.avatar-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
</style>

<script>
$(document).ready(function () {
    $('[data-bs-toggle="tooltip"]').tooltip({ trigger: 'hover' });

    const dtConfig = {
        retrieve  : true,
        language  : {
            search            : '',
            searchPlaceholder : 'Tìm kiếm...',
            emptyTable        : 'Không có dữ liệu.',
            lengthMenu        : 'Hiển thị _MENU_ dòng',
            info              : 'Hiển thị _START_ – _END_ / _TOTAL_',
            infoEmpty         : 'Không có dữ liệu',
            paginate: {
                previous : '<i class="fa fa-chevron-left"></i>',
                next     : '<i class="fa fa-chevron-right"></i>'
            }
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"<"small"l><"small"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"small"i><"small"p>>'
    };

    $.fn.dataTable.ext.errMode = 'none';

    if ($('#topicsTable').length && !$.fn.DataTable.isDataTable('#topicsTable')) {
        $('#topicsTable').DataTable(dtConfig);
    }
    if ($('#registrationsTable').length && !$.fn.DataTable.isDataTable('#registrationsTable')) {
        $('#registrationsTable').DataTable(dtConfig);
    }
});
</script>

<?php require '../app/views/layouts/footer.php'; ?>
