<?php $currentPage = $_GET['page'] ?? ''; ?>

<div class="sidebar">
    <div class="sidebar-top">
        <div class="sidebar-header">
            <h2>Capstone Manager</h2>
        </div>

        <div class="sidebar-label">Navigation</div>

        <ul class="sidebar-menu">

            <!-- Dashboard (tất cả role) -->
            <li>
                <a href="index.php?page=dashboard"
                   class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="fa fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- ── ADMIN ── -->
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>

                <div class="sidebar-label">Management</div>

                <!-- Nhóm: Quản lý người dùng -->
                <li class="menu-group <?= in_array($currentPage, ['users', 'students', 'lecturers']) ? 'open' : '' ?>">
                    <div class="menu-group-toggle">
                        <i class="fa fa-users-gear"></i>
                    <span>User Management</span>
                        <i class="fa fa-chevron-right toggle-icon"></i>
                    </div>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?page=users" class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                                <i class="fa fa-users"></i> <span>Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=students" class="<?= $currentPage === 'students' ? 'active' : '' ?>">
                                <i class="fa fa-user-graduate"></i> <span>Students</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=lecturers" class="<?= $currentPage === 'lecturers' ? 'active' : '' ?>">
                                <i class="fa fa-chalkboard-teacher"></i> <span>Lecturers</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Nhóm: Quản lý đề tài -->
                <li class="menu-group <?= in_array($currentPage, ['semesters', 'topic-management', 'topics', 'registrations', 'assignments']) ? 'open' : '' ?>">
                    <div class="menu-group-toggle">
                        <i class="fa fa-book"></i>
                    <span>Topic Management</span>
                        <i class="fa fa-chevron-right toggle-icon"></i>
                    </div>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?page=semesters" class="<?= $currentPage === 'semesters' ? 'active' : '' ?>">
                                <i class="fa fa-calendar"></i> <span>Semesters</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=topic-management" class="<?= in_array($currentPage, ['topic-management', 'topics', 'registrations']) ? 'active' : '' ?>">
                                <i class="fa fa-book-open"></i> <span>Topics & Registrations</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=assignments" class="<?= $currentPage === 'assignments' ? 'active' : '' ?>">
                                <i class="fa fa-user-check"></i> <span>Assignments</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <div class="sidebar-label">Progress</div>

                <!-- Nhóm: Tiến độ & Chấm điểm -->
                <li class="menu-group <?= in_array($currentPage, ['milestones', 'submissions', 'scores']) ? 'open' : '' ?>">
                    <div class="menu-group-toggle">
                        <i class="fa fa-tasks"></i>
                    <span>Progress & Grades</span>
                        <i class="fa fa-chevron-right toggle-icon"></i>
                    </div>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?page=milestones" class="<?= $currentPage === 'milestones' ? 'active' : '' ?>">
                                <i class="fa fa-flag"></i> <span>Milestones</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=submissions" class="<?= $currentPage === 'submissions' ? 'active' : '' ?>">
                                <i class="fa fa-upload"></i> <span>Submissions</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=scores" class="<?= $currentPage === 'scores' ? 'active' : '' ?>">
                                <i class="fa fa-star"></i> <span>Scores</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <div class="sidebar-label">System</div>

                <!-- Nhóm: Hệ thống -->
                <li class="menu-group <?= in_array($currentPage, ['notifications', 'logs']) ? 'open' : '' ?>">
                    <div class="menu-group-toggle">
                        <i class="fa fa-cog"></i>
                    <span>System</span>
                        <i class="fa fa-chevron-right toggle-icon"></i>
                    </div>
                    <ul class="submenu">
                        <li>
                            <a href="index.php?page=notifications" class="<?= $currentPage === 'notifications' ? 'active' : '' ?>">
                                <i class="fa fa-bell"></i> <span>Notifications</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?page=logs" class="<?= $currentPage === 'logs' ? 'active' : '' ?>">
                                <i class="fa fa-clock-rotate-left"></i> <span>System Logs</span>
                            </a>
                        </li>
                    </ul>
                </li>

            <?php endif; ?>

            <!-- ── STUDENT ── -->
            <?php if ($_SESSION['user']['role'] === 'student'): ?>
                <div class="sidebar-label">My Area</div>
                <li>
                    <a href="index.php?page=topic-management"
                       class="<?= in_array($currentPage, ['topic-management', 'topics', 'registrations']) ? 'active' : '' ?>">
                        <i class="fa fa-book-open"></i> <span>Topics & Registrations</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=submissions"
                       class="<?= $currentPage === 'submissions' ? 'active' : '' ?>">
                        <i class="fa fa-upload"></i> <span>My Submissions</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=notifications"
                       class="<?= $currentPage === 'notifications' ? 'active' : '' ?>">
                        <i class="fa fa-bell"></i> <span>Notifications</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- ── LECTURER ── -->
            <?php if ($_SESSION['user']['role'] === 'lecturer'): ?>
                <div class="sidebar-label">My Area</div>
                <li>
                    <a href="index.php?page=topic-management"
                       class="<?= in_array($currentPage, ['topic-management', 'topics', 'registrations']) ? 'active' : '' ?>">
                        <i class="fa fa-book-open"></i> <span>Topics & Registrations</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=submissions"
                       class="<?= $currentPage === 'submissions' ? 'active' : '' ?>">
                        <i class="fa fa-upload"></i> <span>Submissions</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=scores"
                       class="<?= $currentPage === 'scores' ? 'active' : '' ?>">
                        <i class="fa fa-star"></i> <span>Scores</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=notifications"
                       class="<?= $currentPage === 'notifications' ? 'active' : '' ?>">
                        <i class="fa fa-bell"></i> <span>Notifications</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </div>

    <div class="sidebar-bottom">
        <ul class="sidebar-menu">
            <li class="user-profile-item">
                <div class="user-profile-wrapper">
                    <i class="fa fa-circle-user fa-2x"></i>
                    <div class="user-text">
                        <span class="user-name">
                            <?php
                                $email = $_SESSION['user']['email'] ?? 'User';
                                echo htmlspecialchars(explode('@', $email)[0]);
                            ?>
                        </span>
                        <span class="user-role"><?= ucfirst($_SESSION['user']['role'] ?? 'Guest') ?></span>
                    </div>
                </div>
            </li>
            <li>
                <a href="index.php?page=logout" class="logout-link">
                    <i class="fa fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.menu-group-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const menuGroup = this.parentElement;
            menuGroup.classList.toggle('open');
        });
    });
});
</script>
