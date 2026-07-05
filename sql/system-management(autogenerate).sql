CREATE TEMPORARY TABLE seq_100 (n INT);

INSERT INTO seq_100 (n)
SELECT a.N + b.N * 10 + 1
FROM 
(SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
(SELECT 0 N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b
WHERE a.N + b.N * 10 < 100;


--- this not have rooms, tblTimeSlots, tblPaymentMethods

INSERT INTO tblRoles (role_name) VALUES
('Admin'),
('Accountant'),
('Teacher'),
('Student');

INSERT INTO tblPermissions (permission_name) VALUES
('create'),
('edit'),
('view'),
('delete');



INSERT INTO tblGrade (
    grade_name,
    min_average,
    max_average,
    remark
)
VALUES
('A', 90, 100, 'Excellent'),
('B', 80, 89.99, 'Very Good'),
('C', 70, 79.99, 'Good'),
('D', 60, 69.99, 'Average'),
('E', 50, 59.99, 'Poor'),
('F', 0, 49.99, 'Fail');



INSERT INTO tblScoreTypes
(score_type_name, percentage)
VALUES
('Speaking', 20),
('Listening', 20),
('Reading', 20),
('Grammar', 20),
('Writing', 20);

INSERT INTO tblExamTypes (exam_type_name) VALUES
('Test'),
('Monthly'),
('Midterm'),
('Final'),
('Certificate'),
('Scholarship');



INSERT INTO tblUsers 
(reference_id, reference_type, username, email, password, role_id, status, last_login)
VALUES
(1, 'Employee', 'admin1', 'admin1@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 1, 1, NOW()),
(2, 'Employee', 'teacher1', 'teacher1@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 2, 1, NOW()),
(3, 'Employee', 'teacher2', 'teacher2@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 2, 1, NOW()),
(4, 'Employee', 'account1', 'account1@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 3, 1, NOW()),
(5, 'Employee', 'admin2', 'admin2@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 1, 1, NOW()),

(1, 'Student', 'student1', 'student1@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 4, 1, NOW()),
(2, 'Student', 'student2', 'student2@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 4, 1, NOW()),
(3, 'Student', 'student3', 'student3@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 4, 1, NOW()),
(4, 'Student', 'student4', 'student4@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 4, 1, NOW()),
(5, 'Student', 'student5', 'student5@gmail.com', '$2y$10$e0NRaQmG6Hk5Hq7H9qzGGe3k5Wf1p2NUEW9Z7Cjv8uQYqf5Qb.3Ga', 4, 1, NOW());

-- Admin role = full access
INSERT INTO tblRolePermissions
SELECT 1, permission_id FROM tblpermissions;

-- account role = limited access
INSERT INTO tblRolePermissions VALUES
(2, 1), -- create 
(2, 2), -- edit 
(2, 3); -- view

-- Teacher role = limited access
INSERT INTO tblRolePermissions VALUES
(3, 1), -- create 
(3, 3); -- view

-- Student role = only view
INSERT INTO tblRolePermissions VALUES
(4, 4); -- view




INSERT INTO tblRooms (room_name, capacity, status)
VALUES
('Room A', 50, 'Active'),
('Room B', 50, 'Active'),
('Room C', 50, 'Inactive'),
('Room D', 50, 'Active'),
('Room E', 50, 'Active'),
('Room F', 50, 'Inactive'),
('Room G', 50, 'Active'),
('Room H', 50, 'Active'),
('Room I', 50, 'Inactive'),
('Room J', 50, 'Active');


INSERT INTO tblDays (day_code, day_name, sort_order) VALUES
('Mon', 'Monday', 1),
('Tue', 'Tuesday', 2),
('Wed', 'Wednesday', 3),
('Thu', 'Thursday', 4),
('Fri', 'Friday', 5),
('Sat', 'Saturday', 6),
('Sun', 'Sunday', 7);


INSERT INTO tblLevels (level_name, level_number) VALUES
('Level 1', 1),
('Level 2', 2),
('Level 3', 3),
('Level 4', 4),
('Level 5', 5),
('Level 6', 6),
('Level 7', 7),
('Level 8', 8),
('Level 9', 9);


INSERT INTO tblTimeSlots (slot_name, start_time, end_time) VALUES
('08:00-09:00', '08:00:00', '09:00:00'),
('09:00-10:00', '09:00:00', '10:00:00'),
('10:00-11:00', '10:00:00', '11:00:00'),
('01:00-02:00', '13:00:00', '14:00:00'),
('02:00-03:00', '14:00:00', '15:00:00'),
('03:00-04:00', '15:00:00', '16:00:00'),
('04:00-05:00', '16:00:00', '17:00:00'),
('05:00-06:00', '17:00:00', '18:00:00'),
('06:00-07:00', '18:00:00', '19:00:00'),
('07:00-08:00', '19:00:00', '20:00:00');


INSERT INTO tblPaymentMethods (method_name) VALUES
('Cash'),
('ABA QR'),
('ACE QR'),
('Bank Transfer');


INSERT INTO tblSubjects (subject_code, subject_name)
VALUES 
('ENG', 'English'),
('COM', 'Computer'),
('CH', 'Chinese'),
('DES', 'Design');



INSERT INTO tblCourses (course_code, course_name, price, duration)
VALUES
('CH101', 'Beginner Chinese', 20.00, '3 months'),
('CH102', 'Intermediate Chinese', 50.00, '4 months'),
('CH103', 'Pre-Intermediate Chinese', 30.00, '3 months'),
('CH104', 'Advanced Chinese', 20.00, '5 months'),

('COM101', 'Beginner Computer', 20.00, '3 months'),
('COM102', 'Intermediate Computer', 50.00, '4 months'),
('COM103', 'Pre-Intermediate Computer', 30.00, '3 months'),
('COM104', 'Advanced Computer', 20.00, '5 months'),

('DES101', 'Beginner Design', 20.00, '3 months'),
('DES102', 'Intermediate Design', 50.00, '4 months'),
('DES103', 'Pre-Intermediate Design', 30.00, '3 months'),
('DES104', 'Advanced Design', 20.00, '5 months');


INSERT INTO tblCourseSubjects (course_id, subject_id, level_id)
SELECT 
    c.course_id,
    s.subject_id,
    CASE
        WHEN c.course_code LIKE '%101' THEN 1
        WHEN c.course_code LIKE '%102' THEN 2
        WHEN c.course_code LIKE '%103' THEN 3
        WHEN c.course_code LIKE '%104' THEN 4
    END AS level_id
FROM tblCourses c
JOIN tblSubjects s
    ON c.course_code LIKE CONCAT(s.subject_code, '%')
WHERE 
    c.course_code REGEXP '101$|102$|103$|104$';




INSERT INTO tblClasses 
(class_name, class_code, course_subject_id, teacher_id, room_id, slot_id, academic_year, max_students, current_students, status)
VALUES
('Beginner Chinese A', 'CH101-01', 1, 1, 1, 1, '2025-2026', 50, 0, 'Active'),

('Intermediate Chinese A', 'CH102-02', 2, 2, 2, 2, '2025-2026', 50, 0, 'Active'),

('Beginner Computer A', 'COM101-03', 5, 3, 3, 3, '2025-2026', 50, 0, 'Active'),

('Beginner Design A', 'DES101-04', 9, 4, 4, 4, '2025-2026', 50, 0, 'Active');

INSERT INTO tblTimetables (class_id, day_id)
VALUES
(1, 1),  -- Class 1 on Monday
(1, 2),  -- Class 1 on Tuesday
(1, 3),  -- Class 1 on Wednesday
(1, 4),  -- Class 1 on Thursday
(1, 5);  -- Class 1 on friday

INSERT INTO tblTimetables (class_id, day_id)
VALUES
(2, 1),  -- Class 1 on Monday
(2, 2),  -- Class 1 on Tuesday
(2, 3),  -- Class 1 on Wednesday
(2, 4),  -- Class 1 on Thursday
(2, 5);  -- Class 1 on friday

INSERT INTO tblTimetables (class_id, day_id)
VALUES
(3, 6),  -- Class 1 on sat
(3, 7);  -- Class 1 on sun





INSERT INTO tblDepartments
(department_code, department_name, description, status)
VALUES
('PR001', 'Club President', 'Leads the club', 1),
('ADM002', 'Administration', 'Handles administrative operations', 1),
('FIN003', 'Finance', 'Manages company finances', 1),
('ACC004', 'Accountant', 'Handles accounting operations', 1),
('TH005', 'Teacher Department', 'Handles teaching activities', 1);


INSERT INTO tblEmployees (
    department_id,
    first_name_kh,
    last_name_kh,
    first_name_en,
    last_name_en,
    gender,
    dob,
    birth_village,
    birth_commune,
    birth_district,
    birth_province,
    curr_addr_village,
    curr_addr_commune,
    curr_addr_district,
    curr_addr_province,
    phone1,
    phone2,
    email,
    profile_image,
    hired_at,
    status
) VALUES

(1, 'សុផា', 'ចាន់', 'Sopha', 'Chan', 'Male', '1990-05-10',
 'Phnom Penh', 'Dangkao', 'Dangkao', 'Phnom Penh',
 'Phnom Penh', 'Toul Kork', 'Toul Kork', 'Phnom Penh',
 '010123456', '011123456', 'sopha.chan@gmail.com', NULL, '2022-01-10', 'Active'),

(1, 'ចាន់ណា', 'សុខ', 'Channa', 'Sok', 'Female', '1992-08-21',
 'Kandal', 'Takhmao', 'Takhmao', 'Kandal',
 'Phnom Penh', 'Sen Sok', 'Sen Sok', 'Phnom Penh',
 '012234567', NULL, 'channa.sok@gmail.com', NULL, '2022-02-15', 'Active'),

(2, 'វិរៈ', 'ហេង', 'Vireak', 'Heng', 'Male', '1988-11-02',
 'Takeo', 'Bati', 'Bati', 'Takeo',
 'Phnom Penh', 'Chbar Ampov', 'Chbar Ampov', 'Phnom Penh',
 '010998877', NULL, 'vireak.heng@gmail.com', NULL, '2021-03-20', 'Active'),

(2, 'មុនី', 'លី', 'Mony', 'Ly', 'Female', '1995-03-15',
 'Kampong Cham', 'Kampong Cham', 'Kampong Cham', 'Kampong Cham',
 'Phnom Penh', 'Mean Chey', 'Mean Chey', 'Phnom Penh',
 '012667788', NULL, 'mony.ly@gmail.com', NULL, '2023-06-01', 'Active'),

(3, 'បូរ៉ា', 'គង់', 'Bora', 'Kong', 'Male', '1991-09-12',
 'Battambang', 'Battambang', 'Battambang', 'Battambang',
 'Phnom Penh', '7 Makara', '7 Makara', 'Phnom Penh',
 '010445566', '011445566', 'bora.kong@gmail.com', NULL, '2020-09-10', 'Active'),

(3, 'ស្រីណា', 'អ៊ុក', 'Sreyna', 'Ouk', 'Female', '1993-12-05',
 'Siem Reap', 'Siem Reap', 'Siem Reap', 'Siem Reap',
 'Phnom Penh', 'Daun Penh', 'Daun Penh', 'Phnom Penh',
 '012778899', NULL, 'sreyna.ouk@gmail.com', NULL, '2021-11-11', 'Active'),

(4, 'ដារ៉ា', 'ហ៊ុយ', 'Dara', 'Huy', 'Male', '1987-07-18',
 'Kampot', 'Kampot', 'Kampot', 'Kampot',
 'Phnom Penh', 'Russey Keo', 'Russey Keo', 'Phnom Penh',
 '010556677', NULL, 'dara.huy@gmail.com', NULL, '2019-05-01', 'Active'),

(4, 'លីណា', 'ស៊ុយ', 'Lina', 'Suy', 'Female', '1996-02-28',
 'Prey Veng', 'Prey Veng', 'Prey Veng', 'Prey Veng',
 'Phnom Penh', 'Chamkarmon', 'Chamkarmon', 'Phnom Penh',
 '012112233', NULL, 'lina.suy@gmail.com', NULL, '2023-01-15', 'Active'),

(5, 'សារី', 'នូ', 'Sary', 'Nu', 'Male', '1994-06-30',
 'Kampong Speu', 'Kampong Speu', 'Kampong Speu', 'Kampong Speu',
 'Phnom Penh', 'Por Sen Chey', 'Por Sen Chey', 'Phnom Penh',
 '010889900', NULL, 'sary.nu@gmail.com', NULL, '2022-08-08', 'Active'),

(5, 'កញ្ញា', 'ម៉ៅ', 'Kanha', 'Mao', 'Female', '1997-10-10',
 'Kratie', 'Kratie', 'Kratie', 'Kratie',
 'Phnom Penh', 'Chroy Changvar', 'Chroy Changvar', 'Phnom Penh',
 '012334455', NULL, 'kanha.mao@gmail.com', NULL, '2024-01-01', 'Active');



INSERT INTO tblEmployeeSubjects (employee_id, subject_id)
VALUES
(2, 1), -- English
(2, 2); -- Computer


-- Assign subjects to teacher with employee_id = 3
INSERT INTO tblEmployeeSubjects (employee_id, subject_id)
VALUES
(3, 3), -- Chinese
(3, 4); -- Design

INSERT INTO tblStudents (
    student_code,
    first_name_kh,
    last_name_kh,
    first_name_en,
    last_name_en,
    gender,
    dob,

    birth_village,
    birth_commune,
    birth_district,
    birth_province,

    curr_addr_village,
    curr_addr_commune,
    curr_addr_district,
    curr_addr_province,

    phone1,
    phone2,
    email,
    profile_image,
    academic_year,
    register_at,

    guardian1_name,
    guardian2_name,
    guardian1_relationship,
    guardian2_relationship,

    guardian_curr_addr_village,
    guardian_curr_addr_commune,
    guardian_curr_addr_district,
    guardian_curr_addr_province,

    guardian1_phone,
    guardian2_phone,
    guardian_email,

    created_by,
    status
) VALUES

('STU-2026-0001','សុខ','ចាន់','Sok','Chan','Male','2008-05-10',
'Phnom Penh','Dangkao','Dangkao','Phnom Penh',
'Phnom Penh','Sen Sok','Sen Sok','Phnom Penh',
'010111111',NULL,'sok.chan@gmail.com',NULL,'2026','2026-01-10',
'Chan Dara','Sok Lina','Father','Mother',
'Phnom Penh','Sen Sok','Sen Sok','Phnom Penh',
'012111111','011111111','guardian1@gmail.com',
1,'active'),

('STU-2026-0002','លី','ណារី','Ly','Nary','Female','2007-08-15',
'Kandal','Takhmao','Takhmao','Kandal',
'Phnom Penh','Meanchey','Meanchey','Phnom Penh',
'010222222',NULL,'nary.ly@gmail.com',NULL,'2026','2026-01-12',
'Ly Heng','Nary Srey','Father','Mother',
'Kandal','Takhmao','Takhmao','Kandal',
'012222222',NULL,'guardian2@gmail.com',
1,'active'),

('STU-2026-0003','គង់','មុនី','Kong','Mony','Male','2008-01-20',
'Battambang','Battambang','Battambang','Battambang',
'Phnom Penh','Toul Kork','Toul Kork','Phnom Penh',
'010333333',NULL,'mony.kong@gmail.com',NULL,'2026','2026-01-15',
'Kong Dara','Mony Sok','Father','Mother',
'Battambang','Battambang','Battambang','Battambang',
'012333333',NULL,'guardian3@gmail.com',
2,'active'),

('STU-2026-0004','ស្រី','ដាវី','Srey','Davy','Female','2007-11-05',
'Siem Reap','Siem Reap','Siem Reap','Siem Reap',
'Phnom Penh','Chamkarmon','Chamkarmon','Phnom Penh',
'010444444',NULL,'davy.srey@gmail.com',NULL,'2026','2026-01-18',
'Srey Vuth','Davy Chan','Father','Mother',
'Siem Reap','Siem Reap','Siem Reap','Siem Reap',
'012444444','011444444','guardian4@gmail.com',
2,'active'),

('STU-2026-0005','ហេង','វិសាល','Heng','Visal','Male','2008-09-09',
'Takeo','Bati','Bati','Takeo',
'Phnom Penh','Russey Keo','Russey Keo','Phnom Penh',
'010555555',NULL,'visal.heng@gmail.com',NULL,'2026','2026-01-20',
'Heng Sok','Visal Dara','Father','Mother',
'Takeo','Bati','Bati','Takeo',
'012555555',NULL,'guardian5@gmail.com',
3,'active'),

('STU-2026-0006','ចាន់','លីណា','Chan','Lina','Female','2007-03-03',
'Kampot','Kampot','Kampot','Kampot',
'Phnom Penh','Daun Penh','Daun Penh','Phnom Penh',
'010666666',NULL,'lina.chan@gmail.com',NULL,'2026','2026-01-22',
'Chan Vann','Lina Srey','Father','Mother',
'Kampot','Kampot','Kampot','Kampot',
'012666666','011666666','guardian6@gmail.com',
3,'active'),

('STU-2026-0007','បូរ៉ា','សុភា','Bora','Sopha','Male','2008-12-12',
'Kampong Cham','Kampong Cham','Kampong Cham','Kampong Cham',
'Phnom Penh','Sen Sok','Sen Sok','Phnom Penh',
'010777777',NULL,'sopha.bora@gmail.com',NULL,'2026','2026-01-25',
'Bora Heng','Sopha Chan','Father','Mother',
'Kampong Cham','Kampong Cham','Kampong Cham','Kampong Cham',
'012777777',NULL,'guardian7@gmail.com',
1,'active'),

('STU-2026-0008','មាលី','សុភ័ក្រ','Maly','Sopheak','Female','2007-06-06',
'Prey Veng','Prey Veng','Prey Veng','Prey Veng',
'Phnom Penh','Mean Chey','Mean Chey','Phnom Penh',
'010888888',NULL,'sopheak.maly@gmail.com',NULL,'2026','2026-01-27',
'Maly Sok','Sopheak Dara','Father','Mother',
'Prey Veng','Prey Veng','Prey Veng','Prey Veng',
'012888888',NULL,'guardian8@gmail.com',
1,'active'),

('STU-2026-0009','សុខា','រតនា','Sokha','Rathana','Male','2008-04-04',
'Kratie','Kratie','Kratie','Kratie',
'Phnom Penh','Chroy Changvar','Chroy Changvar','Phnom Penh',
'010999999',NULL,'rathana.sokha@gmail.com',NULL,'2026','2026-01-28',
'Sokha Dara','Rathana Srey','Father','Mother',
'Kratie','Kratie','Kratie','Kratie',
'012999999',NULL,'guardian9@gmail.com',
2,'active'),

('STU-2026-0010','ណារី','ចំរើន','Nary','Chamroeun','Female','2007-10-10',
'Kandal','Kandal','Kandal','Kandal',
'Phnom Penh','Toul Kork','Toul Kork','Phnom Penh',
'010101010',NULL,'chamroeun.nary@gmail.com',NULL,'2026','2026-01-30',
'Nary Heng','Chamroeun Sok','Father','Mother',
'Kandal','Kandal','Kandal','Kandal',
'012101010','011101010','guardian10@gmail.com',
1,'active');



INSERT INTO tblClasses (
    class_name, class_code, course_id, teacher_id, room_id, academic_year,
    max_students,
    status
)
SELECT
    'Computer Beginner' AS class_name,
    CONCAT(
        'COM101',
        '-2025-',
        LPAD(n, 2, '0')
    ) AS class_code,
    1 AS course_id,
    11 AS teacher_id,
    1 AS room_id,

    '2025-2026' AS academic_year,
    50 AS max_students,
    'Active' AS status
FROM seq_100
LIMIT 20;


INSERT INTO tblTimetables (
    class_id, subject_id, teacher_id, room_id, slot_id, day_of_week
)
SELECT
   1,
    1,       -- Random subject_id (assumes 1–4 exist)
   7,       -- Random teacher_id (assumes 1–15 exist)
    FLOOR(1 + RAND()*5),        -- Random room_id (assumes 1–5 exist)
    FLOOR(1 + RAND()*8),        -- Random slot_id (assumes 1–8 time slots)
    ELT(FLOOR(1 + RAND()*7), 'Mon','Tue','Wed','Thu','Fri','Sat','Sun')
FROM seq_100
LIMIT 50;


INSERT INTO tblEnrollments (
    student_id,
    class_id,
    price,
    discount,
    created_by
)
VALUES
(1, 1, 120.00, 10.00, 1),
(2, 2, 150.00, 5.00, 1),
(3, 3, 130.00, 0.00, 2),
(4, 4, 110.00, 15.00, 2),
(5, 1, 140.00, 20.00, 3),
(6, 2, 125.00, 0.00, 3),
(7, 3, 135.00, 8.00, 4),
(8, 3, 160.00, 12.00, 4),
(9, 3, 145.00, 0.00, 5),
(10, 1, 155.00, 18.00, 5);


INSERT INTO tblInvoices (
    student_id, enrollment_id,
    invoice_date, total_amount,
    created_by, status
)
SELECT
    student_id,
    enrollment_id,
    CURDATE(),
    FLOOR(100 + RAND()*200),
    created_by,
    'Unpaid'
FROM tblenrollments;



INSERT INTO tblInvoiceItems (invoice_id, description, amount)
SELECT
    invoice_id,
    'Tuition Fee',
    total_amount * 0.8
FROM tblinvoices;



INSERT INTO tblInvoiceItems (invoice_id, description, amount)
SELECT
    invoice_id,
    'Material Fee',
    total_amount * 0.2
FROM tblinvoices;


INSERT INTO tblPayments (
    invoice_id, payment_date, amount,
    payment_method_id, created_by
)
SELECT
    invoice_id,
    CURDATE(),
    FLOOR(total_amount * RAND()),
    FLOOR(1 + RAND()*4),
    FLOOR(1 + RAND()*5)
FROM tblInvoices
WHERE RAND() > 0.3;


INSERT INTO tblAttendances (enrollment_id, attendance_date, status)
SELECT 
    FLOOR(1 + RAND()*1),
    DATE_ADD('2026-05-01', INTERVAL n DAY),
    'present'
FROM seq_100;








SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE tblPayments;
TRUNCATE tblInvoiceItems;
TRUNCATE tblInvoices;
TRUNCATE tblEnrollments;
TRUNCATE tblStudents;

SET FOREIGN_KEY_CHECKS = 1;
