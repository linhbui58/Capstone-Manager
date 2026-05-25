-- ============================================================
--  CAPSTONE MANAGER — DATABASE SCHEMA + SEED DATA
--  Database: capstone_manager
--  Charset : utf8mb4
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+07:00';
SET foreign_key_checks = 0;

DROP DATABASE IF EXISTS capstone_manager;
CREATE DATABASE capstone_manager
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE capstone_manager;

-- ============================================================
--  TABLE: users
-- ============================================================
CREATE TABLE users (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','lecturer','student') NOT NULL DEFAULT 'student',
  status     ENUM('active','locked') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: students
-- ============================================================
CREATE TABLE students (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id      INT UNSIGNED NOT NULL UNIQUE,
  student_code VARCHAR(20)  NOT NULL UNIQUE,
  full_name    VARCHAR(150) NOT NULL,
  phone        VARCHAR(20)  DEFAULT NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: lecturers
-- ============================================================
CREATE TABLE lecturers (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id   INT UNSIGNED NOT NULL UNIQUE,
  full_name VARCHAR(150) NOT NULL,
  expertise VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: semesters
-- ============================================================
CREATE TABLE semesters (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  start_date DATE         NOT NULL,
  end_date   DATE         NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: topics
-- ============================================================
CREATE TABLE topics (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255) NOT NULL,
  description TEXT         DEFAULT NULL,
  keywords    VARCHAR(255) DEFAULT NULL,
  semester_id INT UNSIGNED NOT NULL,
  created_by  INT UNSIGNED DEFAULT NULL,
  status      ENUM('draft','pending','approved','rejected') NOT NULL DEFAULT 'pending',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (semester_id) REFERENCES semesters(id),
  FOREIGN KEY (created_by)  REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: topic_registrations
-- ============================================================
CREATE TABLE topic_registrations (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id          INT UNSIGNED NOT NULL,
  topic_id            INT UNSIGNED NOT NULL,
  semester_id         INT UNSIGNED NOT NULL,
  desired_lecturer_id INT UNSIGNED DEFAULT NULL,
  keywords            VARCHAR(255) DEFAULT NULL,
  status              ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id)          REFERENCES students(id)  ON DELETE CASCADE,
  FOREIGN KEY (topic_id)            REFERENCES topics(id)    ON DELETE CASCADE,
  FOREIGN KEY (semester_id)         REFERENCES semesters(id),
  FOREIGN KEY (desired_lecturer_id) REFERENCES lecturers(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: topic_assignments
-- ============================================================
CREATE TABLE topic_assignments (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  topic_id    INT UNSIGNED NOT NULL,
  lecturer_id INT UNSIGNED NOT NULL,
  assigned_at DATETIME     NOT NULL,
  UNIQUE KEY uq_topic_lecturer (topic_id, lecturer_id),
  FOREIGN KEY (topic_id)    REFERENCES topics(id)    ON DELETE CASCADE,
  FOREIGN KEY (lecturer_id) REFERENCES lecturers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: milestones
-- ============================================================
CREATE TABLE milestones (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(150) NOT NULL,
  deadline    DATETIME     NOT NULL,
  semester_id INT UNSIGNED NOT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (semester_id) REFERENCES semesters(id)
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: submissions
-- ============================================================
CREATE TABLE submissions (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id   INT UNSIGNED NOT NULL,
  topic_id     INT UNSIGNED DEFAULT NULL,
  milestone_id INT UNSIGNED NOT NULL,
  file_path    VARCHAR(500) NOT NULL,
  status       ENUM('submitted','late','revision_required') NOT NULL DEFAULT 'submitted',
  submitted_at DATETIME     NOT NULL,
  FOREIGN KEY (student_id)   REFERENCES students(id)   ON DELETE CASCADE,
  FOREIGN KEY (topic_id)     REFERENCES topics(id)     ON DELETE SET NULL,
  FOREIGN KEY (milestone_id) REFERENCES milestones(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: evaluation_scores
-- ============================================================
CREATE TABLE evaluation_scores (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  submission_id INT UNSIGNED   NOT NULL,
  lecturer_id   INT UNSIGNED   NOT NULL,
  score         DECIMAL(5,2)   NOT NULL,
  feedback      TEXT           DEFAULT NULL,
  graded_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (submission_id) REFERENCES submissions(id)  ON DELETE CASCADE,
  FOREIGN KEY (lecturer_id)   REFERENCES lecturers(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: notifications
-- ============================================================
CREATE TABLE notifications (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NOT NULL,
  content    TEXT         NOT NULL,
  type       VARCHAR(50)  NOT NULL DEFAULT 'info',
  is_read    TINYINT(1)   NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
--  TABLE: system_logs
-- ============================================================
CREATE TABLE system_logs (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED DEFAULT NULL,
  action     VARCHAR(100) NOT NULL,
  details    TEXT         DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================================
--  SEED DATA
--  Mật khẩu mặc định tất cả: 123456
--  Hash bcrypt của "123456"
-- ============================================================

-- ── 1. USERS ─────────────────────────────────────────────
INSERT INTO users (id, email, password, role, status) VALUES
-- Admin
(1,  'admin@capstone.edu.vn',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',    'active'),
-- Lecturers (6 giảng viên)
(2,  'nguyen.vana@capstone.edu.vn',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'active'),
(3,  'tran.thib@capstone.edu.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'active'),
(4,  'le.vanc@capstone.edu.vn',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'active'),
(5,  'pham.thid@capstone.edu.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'active'),
(6,  'hoang.vane@capstone.edu.vn',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'active'),
(7,  'do.thi.lan@capstone.edu.vn',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lecturer', 'active'),
-- Students (15 sinh viên)
(8,  'sv001@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(9,  'sv002@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(10, 'sv003@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(11, 'sv004@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(12, 'sv005@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(13, 'sv006@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(14, 'sv007@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(15, 'sv008@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(16, 'sv009@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(17, 'sv010@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(18, 'sv011@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(19, 'sv012@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(20, 'sv013@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active'),
(21, 'sv014@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'locked'),
(22, 'sv015@student.capstone.vn',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',  'active');

-- ── 2. LECTURERS ─────────────────────────────────────────
INSERT INTO lecturers (id, user_id, full_name, expertise) VALUES
(1, 2, 'Nguyễn Văn An',   'Trí tuệ nhân tạo, Machine Learning, Deep Learning'),
(2, 3, 'Trần Thị Bình',   'Kỹ thuật phần mềm, Kiểm thử phần mềm, Agile/Scrum'),
(3, 4, 'Lê Văn Cường',    'Cơ sở dữ liệu, Big Data, Data Warehouse'),
(4, 5, 'Phạm Thị Dung',   'Mạng máy tính, An toàn thông tin, IoT'),
(5, 6, 'Hoàng Văn Em',    'Lập trình Web, Cloud Computing, Microservices'),
(6, 7, 'Đỗ Thị Lan',      'Xử lý ảnh, Computer Vision, Robotics');

-- ── 3. STUDENTS ──────────────────────────────────────────
INSERT INTO students (id, user_id, student_code, full_name, phone) VALUES
(1,  8,  'SV21001', 'Nguyễn Minh Tuấn',    '0901234561'),
(2,  9,  'SV21002', 'Trần Thị Hương',       '0901234562'),
(3,  10, 'SV21003', 'Lê Quốc Bảo',          '0901234563'),
(4,  11, 'SV21004', 'Phạm Thị Mai',          '0901234564'),
(5,  12, 'SV21005', 'Hoàng Đức Long',        '0901234565'),
(6,  13, 'SV21006', 'Đỗ Thị Kim Anh',        '0901234566'),
(7,  14, 'SV21007', 'Vũ Văn Hùng',           '0901234567'),
(8,  15, 'SV21008', 'Ngô Thị Lan',           '0901234568'),
(9,  16, 'SV21009', 'Đinh Công Sơn',         '0901234569'),
(10, 17, 'SV21010', 'Bùi Thị Ngọc',          '0901234570'),
(11, 18, 'SV22001', 'Trương Văn Khoa',        '0901234571'),
(12, 19, 'SV22002', 'Lý Thị Hoa',             '0901234572'),
(13, 20, 'SV22003', 'Phan Minh Nhật',         '0901234573'),
(14, 21, 'SV22004', 'Cao Thị Thu Hiền',       '0901234574'),
(15, 22, 'SV22005', 'Dương Quốc Việt',        '0901234575');

-- ── 4. SEMESTERS ─────────────────────────────────────────
INSERT INTO semesters (id, name, start_date, end_date) VALUES
(1, 'Học kỳ 1 — 2023-2024', '2023-09-04', '2024-01-14'),
(2, 'Học kỳ 2 — 2023-2024', '2024-01-22', '2024-06-02'),
(3, 'Học kỳ 1 — 2024-2025', '2024-09-02', '2025-01-12'),
(4, 'Học kỳ 2 — 2024-2025', '2025-01-20', '2025-05-25');

-- ── 5. TOPICS ────────────────────────────────────────────
INSERT INTO topics (id, title, description, keywords, semester_id, created_by, status) VALUES
-- HK1 2023-2024 (semester 1)
(1,  'Hệ thống nhận diện khuôn mặt sử dụng Deep Learning',
     'Xây dựng hệ thống nhận diện khuôn mặt theo thời gian thực sử dụng mạng CNN, ứng dụng điểm danh sinh viên tự động trong giảng đường.',
     'deep learning, face recognition, CNN, attendance system', 1, 2, 'approved'),
(2,  'Ứng dụng quản lý bán hàng trực tuyến tích hợp AI gợi ý sản phẩm',
     'Phát triển nền tảng thương mại điện tử có tích hợp hệ thống gợi ý sản phẩm dựa trên hành vi người dùng sử dụng Collaborative Filtering.',
     'e-commerce, recommendation system, machine learning, Laravel', 1, 5, 'approved'),
(3,  'Phát hiện gian lận giao dịch ngân hàng bằng Machine Learning',
     'Nghiên cứu và xây dựng mô hình phát hiện giao dịch bất thường trong hệ thống ngân hàng sử dụng các thuật toán Random Forest và XGBoost.',
     'fraud detection, machine learning, banking, anomaly detection', 1, 2, 'approved'),
(4,  'Nền tảng học trực tuyến thông minh với chatbot hỗ trợ',
     'Thiết kế và triển khai hệ thống học tập trực tuyến tích hợp chatbot AI để hỗ trợ sinh viên 24/7, bao gồm quản lý khóa học và theo dõi tiến độ.',
     'e-learning, chatbot, NLP, education technology', 1, 3, 'approved'),

-- HK2 2023-2024 (semester 2)
(5,  'Hệ thống IoT giám sát môi trường nông nghiệp thông minh',
     'Xây dựng hệ thống cảm biến IoT để giám sát nhiệt độ, độ ẩm, ánh sáng trong nhà kính nông nghiệp, kết hợp dashboard web và cảnh báo tự động.',
     'IoT, smart agriculture, sensors, dashboard, MQTT', 2, 4, 'approved'),
(6,  'Ứng dụng nhận dạng bệnh lá cây sử dụng Transfer Learning',
     'Phát triển ứng dụng mobile sử dụng mô hình Transfer Learning (MobileNet, ResNet) để nhận dạng và phân loại bệnh trên lá cây nông nghiệp.',
     'transfer learning, plant disease, mobile app, computer vision', 2, 6, 'approved'),
(7,  'Hệ thống quản lý kho thông minh với RFID và Web App',
     'Thiết kế hệ thống quản lý kho hàng sử dụng công nghệ RFID kết hợp ứng dụng web để theo dõi tồn kho, nhập xuất hàng theo thời gian thực.',
     'RFID, warehouse management, inventory, web app', 2, 3, 'approved'),
(8,  'Phân tích cảm xúc bình luận mạng xã hội tiếng Việt',
     'Nghiên cứu và xây dựng mô hình phân tích cảm xúc (Sentiment Analysis) cho văn bản tiếng Việt trên mạng xã hội sử dụng BERT và PhoBERT.',
     'sentiment analysis, NLP, Vietnamese, BERT, PhoBERT', 2, 2, 'approved'),

-- HK1 2024-2025 (semester 3)
(9,  'Nền tảng quản lý đồ án tốt nghiệp trực tuyến',
     'Xây dựng hệ thống web quản lý đồ án tốt nghiệp cho trường đại học, bao gồm quản lý đề tài, phân công giảng viên, theo dõi tiến độ và chấm điểm.',
     'web application, capstone management, PHP, MVC, MySQL', 3, 5, 'approved'),
(10, 'Ứng dụng đặt lịch khám bệnh trực tuyến',
     'Phát triển hệ thống đặt lịch khám bệnh trực tuyến kết nối bệnh nhân với bác sĩ, tích hợp thông báo SMS, lịch sử khám bệnh và tư vấn từ xa.',
     'healthcare, appointment booking, web app, notification', 3, 4, 'approved'),
(11, 'Hệ thống phát hiện bất thường mạng sử dụng AI',
     'Xây dựng hệ thống phát hiện tấn công mạng và bất thường lưu lượng sử dụng các mô hình Deep Learning (LSTM, Autoencoder) trên tập dữ liệu KDD Cup.',
     'network security, anomaly detection, deep learning, LSTM', 3, 2, 'pending'),
(12, 'Ứng dụng theo dõi sức khỏe cá nhân với wearable',
     'Phát triển ứng dụng mobile kết hợp thiết bị đeo tay để theo dõi sức khỏe cá nhân, phân tích dữ liệu sinh lý và đưa ra khuyến nghị sức khỏe.',
     'health tracking, wearable, mobile app, data analysis', 3, 6, 'approved'),
(13, 'Chatbot tư vấn pháp luật sử dụng Large Language Model',
     'Xây dựng chatbot tư vấn pháp luật Việt Nam dựa trên mô hình ngôn ngữ lớn (LLM) được fine-tune với dữ liệu luật Việt Nam.',
     'chatbot, LLM, legal advisory, fine-tuning, Vietnamese law', 3, 5, 'approved'),
(14, 'Nền tảng học lập trình trực tuyến gamification',
     'Phát triển nền tảng học lập trình trực tuyến áp dụng gamification (điểm thưởng, huy hiệu, bảng xếp hạng) để tăng động lực học tập.',
     'gamification, coding platform, education, React, Node.js', 3, 3, 'draft'),

-- HK2 2024-2025 (semester 4)
(15, 'Hệ thống gợi ý việc làm IT thông minh',
     'Xây dựng nền tảng kết nối nhà tuyển dụng và ứng viên IT, sử dụng AI để gợi ý công việc phù hợp dựa trên kỹ năng và kinh nghiệm cá nhân.',
     'job recommendation, AI, IT recruitment, matching algorithm', 4, 5, 'approved'),
(16, 'Ứng dụng nhận dạng chữ viết tay tiếng Việt',
     'Nghiên cứu và xây dựng mô hình nhận dạng chữ viết tay tiếng Việt sử dụng mạng nơ-ron tích chập, ứng dụng trong số hóa tài liệu.',
     'handwriting recognition, OCR, CNN, Vietnamese text, digitization', 4, 6, 'pending'),
(17, 'Hệ thống quản lý tòa nhà thông minh (Smart Building)',
     'Thiết kế và triển khai hệ thống quản lý tòa nhà thông minh tích hợp IoT, điều khiển thiết bị tự động, giám sát năng lượng và bảo mật.',
     'smart building, IoT, BMS, energy management, automation', 4, 4, 'approved'),
(18, 'Phân tích dữ liệu thị trường chứng khoán Việt Nam',
     'Xây dựng hệ thống thu thập, phân tích và dự đoán xu hướng thị trường chứng khoán Việt Nam sử dụng Time Series Analysis và LSTM.',
     'stock market, time series, LSTM, prediction, data analysis', 4, 2, 'rejected');

-- ── 6. TOPIC ASSIGNMENTS ─────────────────────────────────
INSERT INTO topic_assignments (id, topic_id, lecturer_id, assigned_at) VALUES
(1,  1,  1, '2023-09-15 08:00:00'),
(2,  2,  5, '2023-09-15 09:00:00'),
(3,  3,  1, '2023-09-16 08:00:00'),
(4,  4,  2, '2023-09-16 09:00:00'),
(5,  5,  4, '2024-02-01 08:00:00'),
(6,  6,  6, '2024-02-01 09:00:00'),
(7,  7,  3, '2024-02-02 08:00:00'),
(8,  8,  1, '2024-02-02 09:00:00'),
(9,  9,  5, '2024-09-10 08:00:00'),
(10, 10, 4, '2024-09-10 09:00:00'),
(11, 12, 6, '2024-09-11 08:00:00'),
(12, 13, 5, '2024-09-11 09:00:00'),
(13, 15, 5, '2025-01-25 08:00:00'),
(14, 17, 4, '2025-01-25 09:00:00');

-- ── 7. MILESTONES ────────────────────────────────────────
INSERT INTO milestones (id, title, deadline, semester_id) VALUES
-- HK1 2023-2024
(1,  'Báo cáo đề xuất đề tài',           '2023-09-25 23:59:00', 1),
(2,  'Nộp báo cáo tiến độ lần 1 (25%)',  '2023-10-30 23:59:00', 1),
(3,  'Nộp báo cáo tiến độ lần 2 (50%)',  '2023-11-27 23:59:00', 1),
(4,  'Nộp báo cáo hoàn chỉnh (100%)',    '2024-01-05 23:59:00', 1),
-- HK2 2023-2024
(5,  'Báo cáo đề xuất đề tài',           '2024-02-05 23:59:00', 2),
(6,  'Nộp báo cáo tiến độ lần 1 (25%)',  '2024-03-11 23:59:00', 2),
(7,  'Nộp báo cáo tiến độ lần 2 (50%)',  '2024-04-08 23:59:00', 2),
(8,  'Nộp báo cáo hoàn chỉnh (100%)',    '2024-05-20 23:59:00', 2),
-- HK1 2024-2025
(9,  'Báo cáo đề xuất đề tài',           '2024-09-16 23:59:00', 3),
(10, 'Nộp báo cáo tiến độ lần 1 (25%)',  '2024-10-21 23:59:00', 3),
(11, 'Nộp báo cáo tiến độ lần 2 (50%)',  '2024-11-18 23:59:00', 3),
(12, 'Nộp báo cáo hoàn chỉnh (100%)',    '2024-12-30 23:59:00', 3),
-- HK2 2024-2025
(13, 'Báo cáo đề xuất đề tài',           '2025-02-03 23:59:00', 4),
(14, 'Nộp báo cáo tiến độ lần 1 (25%)',  '2025-03-10 23:59:00', 4),
(15, 'Nộp báo cáo tiến độ lần 2 (50%)',  '2025-04-07 23:59:00', 4),
(16, 'Nộp báo cáo hoàn chỉnh (100%)',    '2025-05-12 23:59:00', 4);

-- ── 8. TOPIC REGISTRATIONS ───────────────────────────────
INSERT INTO topic_registrations (id, student_id, topic_id, semester_id, desired_lecturer_id, keywords, status, created_at) VALUES
-- HK1 2023-2024
(1,  1,  1,  1, 1, 'deep learning, face recognition',          'approved', '2023-09-20 10:00:00'),
(2,  2,  2,  1, 5, 'e-commerce, recommendation, machine learning', 'approved', '2023-09-20 11:00:00'),
(3,  3,  3,  1, 1, 'fraud detection, banking security',          'approved', '2023-09-21 09:00:00'),
(4,  4,  4,  1, 2, 'e-learning, chatbot, education',             'approved', '2023-09-21 10:00:00'),
-- HK2 2023-2024
(5,  5,  5,  2, 4, 'IoT, smart agriculture, sensors',            'approved', '2024-01-28 10:00:00'),
(6,  6,  6,  2, 6, 'transfer learning, plant disease, mobile',   'approved', '2024-01-28 11:00:00'),
(7,  7,  7,  2, 3, 'RFID, warehouse, inventory',                 'approved', '2024-01-29 09:00:00'),
(8,  8,  8,  2, 1, 'sentiment analysis, NLP, Vietnamese',        'approved', '2024-01-29 10:00:00'),
-- HK1 2024-2025
(9,  9,  9,  3, 5, 'web app, capstone management',               'approved', '2024-09-06 10:00:00'),
(10, 10, 10, 3, 4, 'healthcare, appointment booking',            'approved', '2024-09-06 11:00:00'),
(11, 11, 12, 3, 6, 'health tracking, wearable, mobile',          'approved', '2024-09-07 09:00:00'),
(12, 12, 13, 3, 5, 'chatbot, LLM, legal advisory',               'approved', '2024-09-07 10:00:00'),
-- HK2 2024-2025
(13, 13, 15, 4, 5, 'job recommendation, AI, recruitment',        'pending',  '2025-01-27 10:00:00'),
(14, 14, 17, 4, 4, 'smart building, IoT, automation',            'pending',  '2025-01-27 11:00:00'),
(15, 15, 15, 4, 5, 'job matching, IT skills',                    'pending',  '2025-01-28 09:00:00');

-- ── 9. SUBMISSIONS ───────────────────────────────────────
INSERT INTO submissions (id, student_id, topic_id, milestone_id, file_path, status, submitted_at) VALUES
-- SV1 - Topic 1
(1,  1, 1,  1,  'uploads/sv001_ms1_de_xuat.pdf',          'submitted',          '2023-09-24 20:30:00'),
(2,  1, 1,  2,  'uploads/sv001_ms2_tiendo_25.pdf',        'submitted',          '2023-10-29 21:00:00'),
(3,  1, 1,  3,  'uploads/sv001_ms3_tiendo_50.pdf',        'submitted',          '2023-11-26 22:00:00'),
(4,  1, 1,  4,  'uploads/sv001_ms4_final.pdf',            'submitted',          '2024-01-04 23:00:00'),
-- SV2 - Topic 2
(5,  2, 2,  1,  'uploads/sv002_ms1_de_xuat.pdf',          'submitted',          '2023-09-24 19:00:00'),
(6,  2, 2,  2,  'uploads/sv002_ms2_tiendo_25.pdf',        'submitted',          '2023-10-30 20:00:00'),
(7,  2, 2,  3,  'uploads/sv002_ms3_tiendo_50.pdf',        'revision_required',  '2023-11-28 21:30:00'),
(8,  2, 2,  4,  'uploads/sv002_ms4_final_v2.pdf',         'submitted',          '2024-01-06 22:00:00'),
-- SV3 - Topic 3
(9,  3, 3,  1,  'uploads/sv003_ms1_de_xuat.pdf',          'submitted',          '2023-09-25 18:00:00'),
(10, 3, 3,  2,  'uploads/sv003_ms2_tiendo_25.pdf',        'late',               '2023-11-02 10:00:00'),
(11, 3, 3,  3,  'uploads/sv003_ms3_tiendo_50.pdf',        'submitted',          '2023-11-27 23:30:00'),
(12, 3, 3,  4,  'uploads/sv003_ms4_final.pdf',            'submitted',          '2024-01-05 22:00:00'),
-- SV4 - Topic 4
(13, 4, 4,  1,  'uploads/sv004_ms1_de_xuat.pdf',          'submitted',          '2023-09-22 15:00:00'),
(14, 4, 4,  2,  'uploads/sv004_ms2_tiendo_25.pdf',        'submitted',          '2023-10-28 20:00:00'),
(15, 4, 4,  3,  'uploads/sv004_ms3_tiendo_50.pdf',        'submitted',          '2023-11-25 21:00:00'),
(16, 4, 4,  4,  'uploads/sv004_ms4_final.pdf',            'submitted',          '2024-01-04 20:00:00'),
-- HK2 — SV5..8
(17, 5, 5,  5,  'uploads/sv005_ms5_de_xuat.pdf',          'submitted',          '2024-02-04 20:00:00'),
(18, 5, 5,  6,  'uploads/sv005_ms6_tiendo_25.pdf',        'submitted',          '2024-03-10 21:00:00'),
(19, 5, 5,  7,  'uploads/sv005_ms7_tiendo_50.pdf',        'submitted',          '2024-04-07 22:00:00'),
(20, 5, 5,  8,  'uploads/sv005_ms8_final.pdf',            'submitted',          '2024-05-19 23:00:00'),
(21, 6, 6,  5,  'uploads/sv006_ms5_de_xuat.pdf',          'submitted',          '2024-02-04 19:00:00'),
(22, 6, 6,  6,  'uploads/sv006_ms6_tiendo_25.pdf',        'submitted',          '2024-03-10 20:00:00'),
(23, 6, 6,  7,  'uploads/sv006_ms7_tiendo_50.pdf',        'revision_required',  '2024-04-09 10:00:00'),
(24, 6, 6,  8,  'uploads/sv006_ms8_final_v2.pdf',         'submitted',          '2024-05-20 21:00:00'),
(25, 7, 7,  5,  'uploads/sv007_ms5_de_xuat.pdf',          'submitted',          '2024-02-03 18:00:00'),
(26, 7, 7,  6,  'uploads/sv007_ms6_tiendo_25.pdf',        'late',               '2024-03-13 09:00:00'),
(27, 7, 7,  7,  'uploads/sv007_ms7_tiendo_50.pdf',        'submitted',          '2024-04-07 20:00:00'),
(28, 7, 7,  8,  'uploads/sv007_ms8_final.pdf',            'submitted',          '2024-05-18 22:00:00'),
(29, 8, 8,  5,  'uploads/sv008_ms5_de_xuat.pdf',          'submitted',          '2024-02-04 21:00:00'),
(30, 8, 8,  6,  'uploads/sv008_ms6_tiendo_25.pdf',        'submitted',          '2024-03-11 20:00:00'),
(31, 8, 8,  7,  'uploads/sv008_ms7_tiendo_50.pdf',        'submitted',          '2024-04-07 21:00:00'),
(32, 8, 8,  8,  'uploads/sv008_ms8_final.pdf',            'submitted',          '2024-05-20 22:00:00'),
-- HK1 2024-2025
(33, 9,  9,  9,  'uploads/sv009_ms9_de_xuat.pdf',         'submitted',          '2024-09-15 20:00:00'),
(34, 9,  9,  10, 'uploads/sv009_ms10_tiendo_25.pdf',      'submitted',          '2024-10-20 21:00:00'),
(35, 9,  9,  11, 'uploads/sv009_ms11_tiendo_50.pdf',      'submitted',          '2024-11-17 22:00:00'),
(36, 9,  9,  12, 'uploads/sv009_ms12_final.pdf',          'submitted',          '2024-12-29 23:00:00'),
(37, 10, 10, 9,  'uploads/sv010_ms9_de_xuat.pdf',         'submitted',          '2024-09-15 19:00:00'),
(38, 10, 10, 10, 'uploads/sv010_ms10_tiendo_25.pdf',      'submitted',          '2024-10-21 20:00:00'),
(39, 10, 10, 11, 'uploads/sv010_ms11_tiendo_50.pdf',      'revision_required',  '2024-11-20 10:00:00'),
(40, 10, 10, 12, 'uploads/sv010_ms12_final_v2.pdf',       'submitted',          '2024-12-30 22:00:00'),
(41, 11, 12, 9,  'uploads/sv011_ms9_de_xuat.pdf',         'submitted',          '2024-09-14 20:00:00'),
(42, 11, 12, 10, 'uploads/sv011_ms10_tiendo_25.pdf',      'late',               '2024-10-23 08:00:00'),
(43, 11, 12, 11, 'uploads/sv011_ms11_tiendo_50.pdf',      'submitted',          '2024-11-18 21:00:00'),
(44, 12, 13, 9,  'uploads/sv012_ms9_de_xuat.pdf',         'submitted',          '2024-09-15 18:00:00'),
(45, 12, 13, 10, 'uploads/sv012_ms10_tiendo_25.pdf',      'submitted',          '2024-10-20 20:00:00'),
-- HK2 2024-2025
(46, 13, 15, 13, 'uploads/sv013_ms13_de_xuat.pdf',        'submitted',          '2025-02-02 20:00:00'),
(47, 14, 17, 13, 'uploads/sv014_ms13_de_xuat.pdf',        'submitted',          '2025-02-03 19:00:00');

-- ── 10. EVALUATION SCORES ────────────────────────────────
INSERT INTO evaluation_scores (id, submission_id, lecturer_id, score, feedback, graded_at) VALUES
-- SV1 – Lecturer 1 chấm
(1,  1,  1, 9.0,  'Đề xuất rõ ràng, mục tiêu cụ thể, phương pháp khả thi.', '2023-09-26 10:00:00'),
(2,  2,  1, 8.5,  'Tiến độ tốt, cần bổ sung thêm kết quả thực nghiệm.', '2023-11-01 10:00:00'),
(3,  3,  1, 8.8,  'Đã cải thiện, kết quả nhận diện đạt 94%. Cần tối ưu tốc độ.', '2023-11-28 11:00:00'),
(4,  4,  1, 9.2,  'Hệ thống hoàn chỉnh, độ chính xác cao, giao diện đẹp.', '2024-01-06 10:00:00'),
-- SV2 – Lecturer 5
(5,  5,  5, 8.5,  'Ý tưởng tốt, kế hoạch thực hiện chi tiết.', '2023-09-26 14:00:00'),
(6,  6,  5, 7.5,  'Tiến độ chậm hơn kế hoạch, cần đẩy nhanh phần backend.', '2023-11-01 14:00:00'),
(7,  7,  5, 6.0,  'Cần bổ sung thuật toán gợi ý, kết quả chưa đánh giá đủ.', '2023-11-30 14:00:00'),
(8,  8,  5, 8.0,  'Đã sửa đủ, hệ thống gợi ý hoạt động tốt với precision 0.82.', '2024-01-08 14:00:00'),
-- SV3 – Lecturer 1
(9,  9,  1, 8.0,  'Đề xuất khả thi, dữ liệu huấn luyện phong phú.', '2023-09-26 15:00:00'),
(10, 11, 1, 7.5,  'Nộp muộn, nội dung ổn.', '2023-11-28 16:00:00'),
(11, 12, 1, 8.5,  'Mô hình XGBoost đạt F1-score 0.91, kết quả tốt.', '2024-01-07 11:00:00'),
-- SV4 – Lecturer 2
(12, 13, 2, 9.5,  'Đề xuất xuất sắc, hệ thống có thiết kế kiến trúc rõ ràng.', '2023-09-23 10:00:00'),
(13, 14, 2, 9.0,  'Chatbot tích hợp tốt, cần mở rộng thêm câu hỏi ngữ cảnh.', '2023-10-29 10:00:00'),
(14, 15, 2, 9.0,  'Giao diện học tập thân thiện, chatbot phản hồi chính xác.', '2023-11-26 10:00:00'),
(15, 16, 2, 9.5,  'Đề tài hoàn thiện xuất sắc, đề xuất đăng báo khoa học.', '2024-01-05 10:00:00'),
-- HK2 submissions — Lecturers chấm
(16, 17, 4, 8.5,  'Thiết kế hệ thống IoT hợp lý, kế hoạch khả thi.', '2024-02-06 10:00:00'),
(17, 18, 4, 8.0,  'Cảm biến hoạt động ổn định, cần cải thiện dashboard.', '2024-03-12 10:00:00'),
(18, 19, 4, 8.5,  'Dashboard đẹp, dữ liệu cảm biến chính xác.', '2024-04-09 10:00:00'),
(19, 20, 4, 9.0,  'Hệ thống IoT hoàn chỉnh, kết quả thực nghiệm ấn tượng.', '2024-05-22 10:00:00'),
(20, 21, 6, 8.0,  'Ý tưởng sáng tạo, mô hình lựa chọn phù hợp.', '2024-02-06 14:00:00'),
(21, 22, 6, 8.5,  'Độ chính xác nhận diện đạt 87%, cần tăng tập dữ liệu.', '2024-03-12 14:00:00'),
(22, 24, 6, 8.8,  'Đã cải thiện đáng kể, mô hình đạt 92% accuracy.', '2024-05-22 14:00:00'),
-- HK1 2024-2025
(23, 33, 5, 9.0,  'Đề xuất chi tiết, giải pháp kỹ thuật phù hợp.', '2024-09-17 10:00:00'),
(24, 34, 5, 8.5,  'Tiến độ tốt, giao diện sạch sẽ.', '2024-10-22 10:00:00'),
(25, 35, 5, 9.0,  'Tính năng phân công giảng viên hoạt động tốt.', '2024-11-19 10:00:00'),
(26, 36, 5, 9.5,  'Hệ thống hoàn chỉnh, đủ tính năng, UX tốt.', '2024-12-31 10:00:00'),
(27, 37, 4, 8.0,  'Thiết kế DB khoa học, luồng đặt lịch rõ ràng.', '2024-09-17 14:00:00'),
(28, 38, 4, 8.5,  'Thông báo SMS tích hợp thành công.', '2024-10-22 14:00:00'),
(29, 41, 6, 8.5,  'Ý tưởng thiết thực, thiết kế UI thân thiện.', '2024-09-16 10:00:00');

-- ── 11. NOTIFICATIONS ────────────────────────────────────
INSERT INTO notifications (id, user_id, content, type, is_read, created_at) VALUES
(1,  8,  'Đề tài của bạn "Hệ thống nhận diện khuôn mặt sử dụng Deep Learning" đã được duyệt.', 'success',  1, '2023-09-16 09:00:00'),
(2,  8,  'Giảng viên hướng dẫn đã chấm điểm milestone 1. Điểm: 9.0/10.', 'info',     1, '2023-09-26 10:30:00'),
(3,  8,  'Nhắc nhở: Deadline nộp báo cáo tiến độ 50% còn 3 ngày.', 'warning',  1, '2023-11-24 08:00:00'),
(4,  8,  'Chúc mừng! Bạn đã hoàn thành tất cả milestone. Điểm tổng kết: 9.2/10.', 'success', 0, '2024-01-06 11:00:00'),
(5,  9,  'Đề tài "Ứng dụng quản lý bán hàng" đã được phê duyệt.', 'success',  1, '2023-09-16 10:00:00'),
(6,  9,  'Giảng viên yêu cầu chỉnh sửa báo cáo milestone 3. Vui lòng xem phản hồi.', 'warning',  1, '2023-11-30 15:00:00'),
(7,  9,  'Bạn đã nộp lại báo cáo thành công. Đang chờ giảng viên xét duyệt.', 'info',    0, '2024-01-06 22:30:00'),
(8,  10, 'Bạn đã nộp muộn báo cáo milestone 2. Vui lòng liên hệ giảng viên.', 'warning',  1, '2023-11-02 12:00:00'),
(9,  11, 'Học kỳ 1 2023-2024 đã bắt đầu. Vui lòng đăng ký đề tài trước 25/09/2023.', 'info',    1, '2023-09-05 08:00:00'),
(10, 12, 'Đề tài của bạn đã được chấp thuận. Giảng viên: TS. Hoàng Văn Em.', 'success',  1, '2023-09-16 10:30:00'),
(11, 2,  'Sinh viên Nguyễn Minh Tuấn đã nộp báo cáo milestone 1.', 'info',    1, '2023-09-24 20:35:00'),
(12, 2,  'Sinh viên Lê Quốc Bảo đã nộp muộn báo cáo milestone 2.', 'warning',  1, '2023-11-02 10:05:00'),
(13, 5,  'Bạn được phân công hướng dẫn đề tài: "Ứng dụng quản lý bán hàng".', 'info',    1, '2023-09-15 09:30:00'),
(14, 1,  'Có 3 đề tài mới đang chờ phê duyệt.', 'warning',  1, '2024-09-12 08:00:00'),
(15, 1,  'Hệ thống đã cập nhật: thêm tính năng phân công giảng viên hàng loạt.', 'info',    0, '2024-10-01 09:00:00'),
(16, 16, 'Học kỳ 1 2024-2025 đã bắt đầu. Vui lòng đăng ký đề tài trước 16/09/2024.', 'info',    1, '2024-09-02 08:00:00'),
(17, 17, 'Học kỳ 1 2024-2025 đã bắt đầu. Vui lòng đăng ký đề tài trước 16/09/2024.', 'info',    0, '2024-09-02 08:00:00'),
(18, 18, 'Đề tài của bạn đã được phê duyệt. Chúc bạn học tốt!', 'success',  1, '2024-09-12 09:00:00'),
(19, 20, 'Đăng ký của bạn đang chờ admin phê duyệt.', 'info',    0, '2025-01-27 10:30:00'),
(20, 3,  'Giảng viên đã chấm điểm: milestone 2 — 7.5/10.', 'info',    0, '2023-11-28 16:30:00');

-- ── 12. SYSTEM LOGS ──────────────────────────────────────
INSERT INTO system_logs (id, user_id, action, details, created_at) VALUES
(1,  1,  'LOGIN',           'Admin đăng nhập từ IP 192.168.1.100',                       '2023-09-04 08:00:00'),
(2,  1,  'CREATE_SEMESTER', 'Tạo học kỳ: Học kỳ 1 — 2023-2024',                          '2023-09-04 08:30:00'),
(3,  2,  'LOGIN',           'Giảng viên Nguyễn Văn An đăng nhập',                         '2023-09-15 07:55:00'),
(4,  2,  'CREATE_TOPIC',    'Tạo đề tài ID=1: Hệ thống nhận diện khuôn mặt',              '2023-09-15 08:10:00'),
(5,  1,  'APPROVE_TOPIC',   'Phê duyệt đề tài ID=1',                                       '2023-09-16 08:00:00'),
(6,  1,  'APPROVE_TOPIC',   'Phê duyệt đề tài ID=2',                                       '2023-09-16 08:05:00'),
(7,  8,  'LOGIN',           'Sinh viên SV21001 đăng nhập',                                 '2023-09-20 09:00:00'),
(8,  8,  'REGISTER_TOPIC',  'Sinh viên SV21001 đăng ký đề tài ID=1',                       '2023-09-20 10:05:00'),
(9,  1,  'APPROVE_REG',     'Phê duyệt đăng ký đề tài: student_id=1, topic_id=1',          '2023-09-21 09:00:00'),
(10, 8,  'SUBMIT',          'Sinh viên SV21001 nộp báo cáo milestone 1',                   '2023-09-24 20:35:00'),
(11, 2,  'GRADE',           'Giảng viên An chấm điểm submission_id=1: 9.0',               '2023-09-26 10:05:00'),
(12, 1,  'CREATE_SEMESTER', 'Tạo học kỳ: Học kỳ 2 — 2023-2024',                          '2024-01-20 08:00:00'),
(13, 9,  'SUBMIT',          'Sinh viên SV21002 nộp lại báo cáo milestone 3 (revision)',    '2024-01-06 22:05:00'),
(14, 1,  'CREATE_SEMESTER', 'Tạo học kỳ: Học kỳ 1 — 2024-2025',                          '2024-08-30 08:00:00'),
(15, 1,  'ASSIGN_LECTURER', 'Phân công GV ID=5 hướng dẫn đề tài ID=9',                    '2024-09-10 08:05:00'),
(16, 1,  'LOCK_USER',       'Khóa tài khoản user_id=21 (SV22004)',                         '2024-09-30 10:00:00'),
(17, 5,  'LOGIN',           'Giảng viên Hoàng Văn Em đăng nhập',                           '2024-10-15 07:50:00'),
(18, 16, 'SUBMIT',          'Sinh viên SV21009 nộp báo cáo milestone 10',                  '2024-10-20 21:05:00'),
(19, 1,  'CREATE_SEMESTER', 'Tạo học kỳ: Học kỳ 2 — 2024-2025',                          '2025-01-20 08:00:00'),
(20, 1,  'REJECT_TOPIC',    'Từ chối đề tài ID=18: không đủ điều kiện nghiên cứu',         '2025-01-25 10:00:00');

SET foreign_key_checks = 1;

-- ============================================================
--  ACCOUNT SUMMARY (mật khẩu đều là: 123456)
-- ============================================================
-- Admin   : admin@capstone.edu.vn
-- Lecturer: nguyen.vana@capstone.edu.vn  (AI & Deep Learning)
--           tran.thib@capstone.edu.vn    (Software Engineering)
--           le.vanc@capstone.edu.vn      (Database & Big Data)
--           pham.thid@capstone.edu.vn    (Networking & IoT)
--           hoang.vane@capstone.edu.vn   (Web & Cloud)
--           do.thi.lan@capstone.edu.vn   (Computer Vision)
-- Student : sv001@student.capstone.vn  → sv015@student.capstone.vn
-- ============================================================
