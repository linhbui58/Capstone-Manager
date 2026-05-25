<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Capstone Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">

<div class="auth-wrapper">
    <div class="auth-sidebar d-none d-lg-flex">
        <div class="sidebar-content">
            <div class="brand-logo mb-5">
                <i class="fa-solid fa-layer-group fa-3x"></i>
                <span class="ms-3 fs-2 fw-bold">Capstone</span>
            </div>
            
            <h1 class="display-5 fw-bold mb-4">Start your creative <br><span class="text-info">journey today.</span></h1>
            <p class="lead opacity-75 mb-5">Create an account to join the capstone project management network at IS-VNU.</p>

            <div class="workflow-steps mb-5">
                <div class="step-item mb-4">
                    <div class="step-icon"><i class="fa-solid fa-user-shield"></i></div>
                    <div class="step-info">
                        <h6>Secure Access</h6>
                        <p class="small opacity-75">Role-based access control (RBAC) ensures your data stays safe.</p>
                    </div>
                </div>
                <div class="step-item mb-4">
                    <div class="step-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                    <div class="step-info">
                        <h6>Centralized Storage</h6>
                        <p class="small opacity-75">Submit reports and project documents online with ease.</p>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="step-info">
                        <h6>Progress Tracking</h6>
                        <p class="small opacity-75">Monitor your progress through each milestone in real time.</p>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-box">
                    <h4 class="fw-bold">1500+</h4>
                    <p class="small m-0">Students</p>
                </div>
                <div class="stat-box">
                    <h4 class="fw-bold">85+</h4>
                    <p class="small m-0">Lecturers</p>
                </div>
                <div class="stat-box">
                    <h4 class="fw-bold">500+</h4>
                    <p class="small m-0">Topics</p>
                </div>
            </div>

            <div class="sidebar-footer mt-auto pt-5">
                <p class="small opacity-50">© 2026 International School - VNU (IS-VNU)</p>
            </div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-card-new">
            <div class="auth-tabs mb-4">
                <a href="index.php?page=login" class="tab-item">Sign In</a>
                <a href="index.php?page=register" class="tab-item active">Register</a>
            </div>

            <div class="auth-header-text mb-4">
                <h3 class="fw-bold">Create a new account</h3>
                <p class="text-muted small">Fill in the details below to register for the system.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger border-0 shadow-sm py-2 small">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=register">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-user text-muted"></i></span>
                        <input type="text" name="full_name" class="form-control bg-light border-start-0 shadow-none" placeholder="Enter your full name" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold">Student ID</label>
                        <input type="text" name="student_code" class="form-control bg-light shadow-none" placeholder="2101xxxx">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control bg-light shadow-none" placeholder="09xx...">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-start-0 shadow-none" placeholder="name@vnu.edu.vn" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-start-0 shadow-none" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Role</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-users-gear text-muted"></i></span>
                        <select name="role" class="form-select bg-light border-start-0 shadow-none" required>
                            <option value="" disabled selected>— Select your role —</option>
                            <option value="student">Student</option>
                            <option value="lecturer">Lecturer</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-3 shadow-sm">
                    Create Account <i class="fa-solid fa-user-plus ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted">Already have an account? <a href="index.php?page=login" class="fw-bold text-decoration-none">Sign in now</a></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
