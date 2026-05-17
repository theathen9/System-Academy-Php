INSERT INTO tblTimeSlots (slot_name, start_time, end_time) VALUES
('Morning 08:00-09:00', '08:00:00', '09:00:00'),
('Morning 09:00-10:00', '09:00:00', '10:00:00'),
('Morning 10:00-11:00', '10:00:00', '11:00:00'),
('Afternoon 01:00-02:00', '13:00:00', '14:00:00'),
('Afternoon 02:00-03:00', '14:00:00', '15:00:00'),
('Afternoon 03:00-04:00', '15:00:00', '16:00:00'),
('Evening 04:00-05:00', '16:00:00', '17:00:00'),
('Evening 05:00-06:00', '17:00:00', '18:00:00'),
('Evening 06:00-07:00', '18:00:00', '19:00:00'),
('Evening 07:00-08:00', '19:00:00', '20:00:00');


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


INSERT INTO tblSubjects (subject_code, subject_name)
VALUES 
('ENG', 'English'),
('COM', 'Computer'),
('CH', 'Chinese'),
('DES', 'Design');

INSERT INTO tblCourses (course_name, price, duration)
VALUES
('Basic English Course', 120.00, 40),
('IT Fundamentals', 150.00, 50),
('Graphic Design Master', 200.00, 60),
('Business English', 180.00, 45),
('Math for Beginners', 100.00, 30);

INSERT INTO tblCourseSubjects (course_id, subject_id, level)
VALUES
(1, 1, 1), -- English course → English

(2, 2, 3), -- IT course → Computer

(3, 4, 4), -- Design course → Design
(3, 2, 3), -- Design course → Computer

(4, 1, 5), -- Business English → English
(4, 2, 2); -- Business English → Computer



INSERT INTO tblStudents (
    student_code,
    fst_name, lst_name,
    fst_name_eng, lst_name_eng,
    gender, dob,
    dob_village, dob_commune, dob_district, dob_province,
    curr_addr_village, curr_addr_commune, curr_addr_district, curr_addr_province,
    phone1, phone2, email,
    profile_image,
    created_by, created_at, academic_year,
    guardian1_name, guardian2_name,
    guardian1_relationship, guardian2_relationship,
    guardian_curr_addr_village, guardian_curr_addr_commune,
    guardian_curr_addr_district, guardian_curr_addr_province,
    guardian1_phone, guardian2_phone, guardian_email
) VALUES

('STU001','សុខ','ដារ៉ា','Sok','Dara','Male','2005-01-15',
 'Village1','Commune1','District1','Phnom Penh',
 'VillageA','CommuneA','DistrictA','Phnom Penh',
 '012345678',NULL,'dara1@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Chan Dara','Srey Mom','Father','Mother',
 'VillageA','CommuneA','DistrictA','Phnom Penh',
 '011111111','022222222','parent1@gmail.com'),

('STU002','លី','ស្រីនាង','Ly','Sreynang','Female','2006-02-20',
 'Village2','Commune2','District2','Kandal',
 'VillageB','CommuneB','DistrictB','Kandal',
 '012345679',NULL,'sreynang@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Ly Vann','Chan Srey','Father','Mother',
 'VillageB','CommuneB','DistrictB','Kandal',
 '033333333','044444444','parent2@gmail.com'),

('STU003','ជា','វិសាល','Chea','Visal','Male','2004-03-10',
 'Village3','Commune3','District3','Takeo',
 'VillageC','CommuneC','DistrictC','Takeo',
 '012345680',NULL,'visal@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Chea Sok','Kim Chan','Father','Mother',
 'VillageC','CommuneC','DistrictC','Takeo',
 '055555555','066666666','parent3@gmail.com'),

('STU004','ស៊ុន','ចាន់ដារ៉ា','Sun','Chandara','Male','2005-04-05',
 'Village4','Commune4','District4','Kampot',
 'VillageD','CommuneD','DistrictD','Kampot',
 '012345681',NULL,'chandara@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Sun Vanna','Sok Srey','Father','Mother',
 'VillageD','CommuneD','DistrictD','Kampot',
 '077777777','088888888','parent4@gmail.com'),

('STU005','ម៉ៅ','ស្រីពៅ','Mao','Srey Pov','Female','2007-05-18',
 'Village5','Commune5','District5','Battambang',
 'VillageE','CommuneE','DistrictE','Battambang',
 '012345682',NULL,'sreypov@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Mao Dara','Kim Ly','Father','Mother',
 'VillageE','CommuneE','DistrictE','Battambang',
 '099999999','010101010','parent5@gmail.com'),

('STU006','ហេង','វិជ្ជា','Heng','Vichea','Male','2006-06-25',
 'Village6','Commune6','District6','Siem Reap',
 'VillageF','CommuneF','DistrictF','Siem Reap',
 '012345683',NULL,'vichea@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Heng Sok','Chan Thy','Father','Mother',
 'VillageF','CommuneF','DistrictF','Siem Reap',
 '011212121','022323232','parent6@gmail.com'),

('STU007','កែវ','ស្រីលី','Keo','Srey Ly','Female','2005-07-30',
 'Village7','Commune7','District7','Kampong Cham',
 'VillageG','CommuneG','DistrictG','Kampong Cham',
 '012345684',NULL,'sreyly@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Keo Vann','Srey Pov','Father','Mother',
 'VillageG','CommuneG','DistrictG','Kampong Cham',
 '033434343','044545454','parent7@gmail.com'),

('STU008','ជា','សុវណ្ណ','Chea','Sovann','Male','2004-08-12',
 'Village8','Commune8','District8','Prey Veng',
 'VillageH','CommuneH','DistrictH','Prey Veng',
 '012345685',NULL,'sovann@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Chea Dara','Sok Ly','Father','Mother',
 'VillageH','CommuneH','DistrictH','Prey Veng',
 '055656565','066767676','parent8@gmail.com'),

('STU009','លី','ចាន់ថា','Ly','Chantha','Female','2007-09-14',
 'Village9','Commune9','District9','Svay Rieng',
 'VillageI','CommuneI','DistrictI','Svay Rieng',
 '012345686',NULL,'chantha@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Ly Sok','Chan Srey','Father','Mother',
 'VillageI','CommuneI','DistrictI','Svay Rieng',
 '077878787','088989898','parent9@gmail.com'),

('STU010','ប៊ុន','រតនា','Bun','Rothana','Male','2006-10-22',
 'Village10','Commune10','District10','Banteay Meanchey',
 'VillageJ','CommuneJ','DistrictJ','Banteay Meanchey',
 '012345687',NULL,'rothana@gmail.com',
 NULL,1,'2026-03-01','2025-2026',
 'Bun Dara','Srey Mom','Father','Mother',
 'VillageJ','CommuneJ','DistrictJ','Banteay Meanchey',
 '099000000','010202020','parent10@gmail.com');
-- 👉 COPY THE LAST 10 ROWS
-- 👉 CHANGE NUMBERS UP TO 100
-- 👉 PHONE: 010000011 → 010000100
-- 👉 VILLAGE/COMMUNE/DISTRICT NUMBER ++
;




INSERT INTO tblDepartments
(department_code, department_name, description, status)
VALUES
('HR001', 'Human Resources', 'Handles employee management', 1),
('FIN001', 'Finance', 'Manages company finances', 1),
('IT001', 'IT Department', 'Handles technical support and systems', 1),
('ADM001', 'Administration', 'Administrative operations', 1);



INSERT INTO tblEmployees (
    fst_name, lst_name, fst_name_eng, lst_name_eng, gender, dob,
    dob_vallage, dob_commune, dob_district, dob_province,
    cur_addr_vallage, cur_addr_commune, cur_addr_district, cur_addr_province,
    phone, email, dep_id, status
) VALUES
-- 1
('សុខ','វិរៈ','Sok','Vireak','Male','1995-03-12',
 'ភូមិថ្មី','ឃុំសង្កែ','ស្រុកបាភ្នំ','ខេត្តព្រៃវែង',
 'ភូមិថ្មី','ឃុំសង្កែ','ស្រុកបាភ្នំ','ខេត្តព្រៃវែង',
 '012345678','sok.vireak@gmail.com',1,'active'),

-- 2
('ជា','សុវណ្ណា','Chea','Sovanna','Female','1996-07-22',
 'ភូមិចំការលើ','ឃុំកំពង់ចិន','ស្រុកព្រៃឈរ','ខេត្តកំពង់ចាម',
 'ភូមិចំការលើ','ឃុំកំពង់ចិន','ស្រុកព្រៃឈរ','ខេត្តកំពង់ចាម',
 '011223344','chea.sovanna@gmail.com',1,'active'),

-- 3
('លី','ដារា','Ly','Dara','Male','1994-11-05',
 'ភូមិស្វាយ','ឃុំស្វាយអង្គ','ស្រុកស្វាយជ្រុំ','ខេត្តស្វាយរៀង',
 'ភូមិស្វាយ','ឃុំស្វាយអង្គ','ស្រុកស្វាយជ្រុំ','ខេត្តស្វាយរៀង',
 '015556677','ly.dara@gmail.com',1,'active'),

-- 4
('ហេង','ស្រីនាង','Heng','Sreynang','Female','1997-02-18',
 'ភូមិត្រពាំង','ឃុំត្រពាំងក្រហម','ស្រុកអង្គស្នួល','ខេត្តកណ្តាល',
 'ភូមិត្រពាំង','ឃុំត្រពាំងក្រហម','ស្រុកអង្គស្នួល','ខេត្តកណ្តាល',
 '010998877','heng.sreynang@gmail.com',1,'active'),

-- 5
('គឹម','សុភ័ក្រ','Kim','Sopheap','Male','1993-09-09',
 'ភូមិបឹង','ឃុំបឹងកេងកង','ខណ្ឌចំការមន','ភ្នំពេញ',
 'ភូមិបឹង','ឃុំបឹងកេងកង','ខណ្ឌចំការមន','ភ្នំពេញ',
 '093445566','kim.sopheap@gmail.com',1,'active'),

-- 6
('នួន','ចាន់ថា','Nuon','Chantha','Female','1998-06-06',
 'ភូមិអូរ','ឃុំអូរសារ','ស្រុកសំរោង','ខេត្តតាកែវ',
 'ភូមិអូរ','ឃុំអូរសារ','ស្រុកសំរោង','ខេត្តតាកែវ',
 '081223344','nuon.chantha@gmail.com',1,'active'),

-- 7
('ប៊ុន','សុខា','Bun','Sokha','Male','1992-01-30',
 'ភូមិព្រែក','ឃុំព្រែកថ្មី','ស្រុកល្វាឯម','ខេត្តកណ្តាល',
 'ភូមិព្រែក','ឃុំព្រែកថ្មី','ស្រុកល្វាឯម','ខេត្តកណ្តាល',
 '070111222','bun.sokha@gmail.com',1,'active'),

-- 8
('ម៉េង','វិច្ឆិកា','Meng','Vicheka','Female','1999-11-11',
 'ភូមិកំពង់','ឃុំកំពង់ស្វាយ','ស្រុកកំពង់ស្វាយ','ខេត្តកំពង់ធំ',
 'ភូមិកំពង់','ឃុំកំពង់ស្វាយ','ស្រុកកំពង់ស្វាយ','ខេត្តកំពង់ធំ',
 '012889900','meng.vicheka@gmail.com',1,'Inactive'),

-- 9
('រឿន','បូរី','Ruen','Bory','Male','1991-04-04',
 'ភូមិជ្រោយ','ឃុំជ្រោយចង្វា','ខណ្ឌជ្រោយចង្វា','ភ្នំពេញ',
 'ភូមិជ្រោយ','ឃុំជ្រោយចង្វា','ខណ្ឌជ្រោយចង្វា','ភ្នំពេញ',
 '069556677','ruen.bory@gmail.com',1,'active'),

-- 10
('សាន','មករា','San','Makara','Male','1990-01-01',
 'ភូមិថ្មី','ឃុំថ្មី','ស្រុកថ្មី','ខេត្តបាត់ដំបង',
 'ភូមិថ្មី','ឃុំថ្មី','ស្រុកថ្មី','ខេត្តបាត់ដំបង',
 '097778899','san.makara@gmail.com',1,'Inactive');
 
 
 
 
 -- INSERT 10 sample students
INSERT INTO tblStudents (
    student_code, fst_name, lst_name, fst_name_eng, lst_name_eng, gender, dob,
    dob_village, dob_commune, dob_district, dob_province,
    curr_addr_village, curr_addr_commune, curr_addr_district, curr_addr_province,
    phone1, phone2, email, profile_image, academic_year, registed_at,
    guardian1_name, guardian2_name, guardian1_relationship, guardian2_relationship,
    guardian_curr_addr_village, guardian_curr_addr_commune, guardian_curr_addr_district, guardian_curr_addr_province,
    guardian1_phone, guardian2_phone, guardian_email, created_by
)
VALUES
('STU001', 'សៅ', 'ជា', 'Sao', 'Chea', 'Male', '2005-02-15',
 'Svay', 'Svay Commune', 'Siem Reap', 'Siem Reap',
 'Svay Village', 'Svay Commune', 'Siem Reap', 'Siem Reap',
 '012345678', '098765432', 'sao.chea@example.com', NULL, '2025-2026', '2025-09-01',
 'Chea Dara', 'Sok Rith', 'Father', 'Mother', 'Svay Village', 'Svay Commune', 'Siem Reap', 'Siem Reap',
 '012345678', '098765432', 'guardian@example.com', 1),

('STU002', 'ជា', 'ស៊ី', 'Chea', 'Si', 'Female', '2006-05-20',
 'Kampong', 'Kampong Commune', 'Phnom Penh', 'Phnom Penh',
 'Kampong Village', 'Kampong Commune', 'Phnom Penh', 'Phnom Penh',
 '011223344', NULL, 'chea.si@example.com', NULL, '2025-2026', '2025-09-05',
 'Si Dara', 'Chum Rith', 'Father', 'Mother', 'Kampong Village', 'Kampong Commune', 'Phnom Penh', 'Phnom Penh',
 '011223344', '012334455', 'guardian2@example.com', 2),

('STU003', 'សុខ', 'ស៊ី', 'Sok', 'Si', 'Male', '2005-12-01',
 'Phsar', 'Phsar Commune', 'Battambang', 'Battambang',
 'Phsar Village', 'Phsar Commune', 'Battambang', 'Battambang',
 '012334455', NULL, 'sok.si@example.com', NULL, '2025-2026', '2025-09-10',
 'Si Sok', 'Chou Rith', 'Father', 'Mother', 'Phsar Village', 'Phsar Commune', 'Battambang', 'Battambang',
 '012334455', '012445566', 'guardian3@example.com', 1),

('STU004', 'ឡេង', 'ជា', 'Leng', 'Chea', 'Female', '2006-03-22',
 'Tboung', 'Tboung Commune', 'Kampong Cham', 'Kampong Cham',
 'Tboung Village', 'Tboung Commune', 'Kampong Cham', 'Kampong Cham',
 '013556677', '017889900', 'leng.chea@example.com', NULL, '2025-2026', '2025-09-12',
 'Chea Leng', 'Sok Rith', 'Father', 'Mother', 'Tboung Village', 'Tboung Commune', 'Kampong Cham', 'Kampong Cham',
 '013556677', '017889900', 'guardian4@example.com', 2),

('STU005', 'ពៅ', 'ស៊ី', 'Pao', 'Si', 'Male', '2005-07-18',
 'Kandal', 'Kandal Commune', 'Kandal', 'Kandal',
 'Kandal Village', 'Kandal Commune', 'Kandal', 'Kandal',
 '015667788', NULL, 'pao.si@example.com', NULL, '2025-2026', '2025-09-15',
 'Si Pao', 'Chum Dara', 'Father', 'Mother', 'Kandal Village', 'Kandal Commune', 'Kandal', 'Kandal',
 '015667788', '016778899', 'guardian5@example.com', 1),

('STU006', 'ណារិន', 'ជា', 'Narin', 'Chea', 'Male', '2006-11-10',
 'Prey', 'Prey Commune', 'Takeo', 'Takeo',
 'Prey Village', 'Prey Commune', 'Takeo', 'Takeo',
 '016889900', '018990011', 'narin.chea@example.com', NULL, '2025-2026', '2025-09-18',
 'Chea Narin', 'Sok Dara', 'Father', 'Mother', 'Prey Village', 'Prey Commune', 'Takeo', 'Takeo',
 '016889900', '018990011', 'guardian6@example.com', 2),

('STU007', 'ឡោ', 'ស៊ី', 'Lao', 'Si', 'Female', '2005-09-05',
 'Siem', 'Siem Commune', 'Siem Reap', 'Siem Reap',
 'Siem Village', 'Siem Commune', 'Siem Reap', 'Siem Reap',
 '019001122', NULL, 'lao.si@example.com', NULL, '2025-2026', '2025-09-20',
 'Si Lao', 'Chum Dara', 'Father', 'Mother', 'Siem Village', 'Siem Commune', 'Siem Reap', 'Siem Reap',
 '019001122', '019112233', 'guardian7@example.com', 1),

('STU008', 'សុខា', 'ជា', 'Sokha', 'Chea', 'Female', '2006-01-12',
 'Kep', 'Kep Commune', 'Kep', 'Kep',
 'Kep Village', 'Kep Commune', 'Kep', 'Kep',
 '019223344', NULL, 'sokha.chea@example.com', NULL, '2025-2026', '2025-09-22',
 'Chea Sokha', 'Sok Rith', 'Father', 'Mother', 'Kep Village', 'Kep Commune', 'Kep', 'Kep',
 '019223344', '019334455', 'guardian8@example.com', 2),

('STU009', 'ប៉ែន', 'ស៊ី', 'Pan', 'Si', 'Male', '2005-04-28',
 'Preah', 'Preah Commune', 'Banteay Meanchey', 'Banteay Meanchey',
 'Preah Village', 'Preah Commune', 'Banteay Meanchey', 'Banteay Meanchey',
 '019445566', NULL, 'pan.si@example.com', NULL, '2025-2026', '2025-09-25',
 'Si Pan', 'Chum Dara', 'Father', 'Mother', 'Preah Village', 'Preah Commune', 'Banteay Meanchey', 'Banteay Meanchey',
 '019445566', '019556677', 'guardian9@example.com', 1),

('STU010', 'មន្រ្តី', 'ជា', 'Montri', 'Chea', 'Female', '2006-08-30',
 'Koh', 'Koh Commune', 'Kampot', 'Kampot',
 'Koh Village', 'Koh Commune', 'Kampot', 'Kampot',
 '019667788', '019778899', 'montri.chea@example.com', NULL, '2025-2026', '2025-09-28',
 'Chea Montri', 'Sok Dara', 'Father', 'Mother', 'Koh Village', 'Koh Commune', 'Kampot', 'Kampot',
 '019667788', '019778899', 'guardian10@example.com', 2);
 
 
 
 INSERT INTO tblRoom (room_name, capacity, status) VALUES
('Room A', 20, 'Available'),
('Room B', 25, 'Available'),
('Room D', 35, 'Available'),
('Room F', 20, 'Available'),
('Room G', 25, 'Available');


 
 
 INSERT INTO tblCourse (course_name, teacher_id, room_id) VALUES
('English Level 1', 9, 1),
('English Level 2', 9, 1),
('English Level 3', 9, 2),
('English Grammar Basic', 9, 2),
('English Grammar Advanced', 8, 3),
('English Speaking Level 1', 8, 3),
('English Speaking Level 2', 8, 4),
('English Listening', 8, 4),
('English Writing', 8, 5),
('English IELTS Preparation', 8, 5),


('Computer Basic', 10, 2),
('Computer Advanced', 10, 2),
('Microsoft Word', 10, 3),
('Microsoft Excel', 10, 3),
('Microsoft PowerPoint', 10, 4),
('Graphic Design Basic', 10, 4),


('Chinese Level 1', 3, 1),
('Chinese Level 2', 3, 1),
('Chinese Level 3', 3, 2),
('Chinese Speaking', 3, 3),
('Chinese Writing', 6, 4),
('Chinese Listening', 6, 4),
('Chinese HSK Preparation', 6, 5);


-- Insert 10 classes with placeholder course, teacher, and room IDs
INSERT INTO tblClasses (class_name, start_date, end_date, course_id, teacher_id, room_id, academic_year, status) VALUES
('English Class A', '2026-04-01', '2026-06-30', 1, 1, 1, '2026-2027', 'Open'),
('English Class B', '2026-04-01', '2026-06-30', 1, 1, 1, '2026-2027', 'Open'),
('Computer Class A', '2026-04-01', '2026-06-30', 2, 1, 1, '2026-2027', 'Open'),
('Computer Class B', '2026-04-01', '2026-06-30', 2, 1, 1, '2026-2027', 'Open'),
('Chiness Class A', '2026-04-01', '2026-06-30', 3, 1, 1, '2026-2027', 'Open'),
('Chiness Class B', '2026-04-01', '2026-06-30', 3, 1, 1, '2026-2027', 'Open'),
('Design Class A', '2026-04-01', '2026-06-30', 4, 1, 1, '2026-2027', 'Open'),
('Design Class B', '2026-04-01', '2026-06-30', 4, 1, 1, '2026-2027', 'Open'),
('Mixed Class 1', '2026-04-01', '2026-06-30', 1, 1, 1, '2026-2027', 'Open'),
('Mixed Class 2', '2026-04-01', '2026-06-30', 2, 1, 1, '2026-2027', 'Open');



INSERT INTO tblPaymentMethods (method_name) VALUES
('Cash'),
('ABA Pay'),
('Bank Transfer');

INSERT INTO tblEnrollments (student_id, class_id, created_by)
VALUES 
(1, 1, 4),
(2, 2, 4),
(3, 3, 4),
(4, 4, 4),
(5, 5, 4),
(6, 6, 4),
(7, 7, 4),
(8, 8, 4),
(9, 9, 4),
(10, 10, 4);



-- INSERT 20 invoices
INSERT INTO tblInvoices 
(student_id, enrollment_id, invoice_date, total_amount, created_by, status)
VALUES
(1,1,'2026-01-01',120,1,'Paid'),
(2,2,'2026-01-02',150,1,'Partial'),
(3,3,'2026-01-03',200,2,'Unpaid'),
(4,4,'2026-01-04',180,2,'Paid'),
(5,5,'2026-01-05',220,3,'Partial'),
(6,6,'2026-01-06',130,1,'Paid'),
(7,7,'2026-01-07',170,2,'Unpaid'),
(8,8,'2026-01-08',190,3,'Paid'),
(9,9,'2026-01-09',210,1,'Partial'),
(10,10,'2026-01-10',160,2,'Paid'),

(11,11,'2026-01-11',120,1,'Paid'),
(12,12,'2026-01-12',150,1,'Partial'),
(13,13,'2026-01-13',200,2,'Unpaid'),
(14,14,'2026-01-14',180,2,'Paid'),
(15,15,'2026-01-15',220,3,'Partial'),
(16,16,'2026-01-16',130,1,'Paid'),
(17,17,'2026-01-17',170,2,'Unpaid'),
(18,18,'2026-01-18',190,3,'Paid'),
(19,19,'2026-01-19',210,1,'Partial'),
(20,20,'2026-01-20',160,2,'Paid');


INSERT INTO tblInvoiceItems (invoice_id, description, amount) VALUES
(1,'Tuition Fee',100),(1,'Book Fee',20),
(2,'Tuition Fee',120),(2,'Book Fee',30),
(3,'Tuition Fee',150),(3,'Book Fee',50),
(4,'Tuition Fee',150),(4,'Book Fee',30),
(5,'Tuition Fee',200),(5,'Book Fee',20),
(6,'Tuition Fee',100),(6,'Book Fee',30),
(7,'Tuition Fee',150),(7,'Book Fee',20),
(8,'Tuition Fee',170),(8,'Book Fee',20),
(9,'Tuition Fee',200),(9,'Book Fee',10),
(10,'Tuition Fee',140),(10,'Book Fee',20);

-- INSERT payments for Paid invoices only
INSERT INTO tblPayments
(invoice_id, payment_date, amount, payment_method_id, reference_no, created_by)
VALUES
(1,'2026-01-02',120,1,'',1),
(2,'2026-01-03',50,2,'TRX001',1),
(2,'2026-01-05',100,2,'TRX002',2),
(4,'2026-01-06',180,1,'',1),
(5,'2026-01-07',100,3,'ABA001',2),
(6,'2026-01-08',130,1,'',1),
(8,'2026-01-09',190,3,'CARD001',3),
(9,'2026-01-10',100,3,'ABA002',2),
(9,'2026-01-11',110,3,'ABA003',2),
(10,'2026-01-12',160,1,'',1);

INSERT INTO tblattendances (enrollment_id, attendance_date, status) VALUES
(1, '2026-03-01', 'វត្តមាន'),
(2, '2026-03-01', 'អវត្តមាន'),
(3, '2026-03-01', 'វត្តមាន'),
(4, '2026-03-01', 'ច្បាប់'),
(5, '2026-03-02', 'វត្តមាន'),
(6, '2026-03-02', 'អវត្តមាន'),
(7, '2026-03-02', 'វត្តមាន'),
(8, '2026-03-02', 'ច្បាប់'),
(9, '2026-03-03', 'វត្តមាន'),
(10, '2026-03-03', 'អវត្តមាន');

INSERT INTO tblAttendances (enrollment_id, attendance_date, status) VALUES
(1, '2026-03-20', 'present'),
(1, '2026-03-21', 'late'),
(2, '2026-03-20', 'absent'),
(2, '2026-03-21', 'present'),
(3, '2026-03-20', 'present'),
(3, '2026-03-21', 'present'),
(4, '2026-03-20', 'late'),
(4, '2026-03-21', 'absent'),
(5, '2026-03-20', 'present'),
(5, '2026-03-21', 'late');



INSERT INTO tblUsers (employee_id, name_khmer, name_english, email, password, role)
SELECT 
    emp.employees_id,
    CONCAT(emp.fst_name,' ',emp.lst_name) AS name_khmer,
    CONCAT(emp.fst_name_eng,' ',emp.lst_name_eng) AS name_english,
    emp.email,
    'default_password_here',  -- Replace with hashed password
    'Teacher'                 -- or 'Admin' if admin
FROM tblemployees emp;




INSERT INTO tblUsers (student_id, name_khmer, name_english, email, password, role)
SELECT
    stu.student_id,
    CONCAT(stu.fst_name,' ',stu.lst_name) AS name_khmer,
    CONCAT(stu.fst_name_eng,' ',stu.lst_name_eng) AS name_english,
    stu.email,
    'default_password_here',  -- Replace with hashed password
    'Student'
FROM tblstudent stu;

