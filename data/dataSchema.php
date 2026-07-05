<?php
include_once __DIR__ . '/../config/app.php';

$infoSchemaData = [
    ["name" => "Empowerment Education English One Centre"],
    ["name_short" => "3E One"],
    ["email" => "info@empowermentacademy.com"],
    ["address" => "36, Siem Reap, Siem Reap"],
    ["phone" => "095555521"],
    ["image" => BASE_URL . "/src/assets/logo.png"]
];


// url root/ Admin
$routeAdmin = [

    // ===== Dashboard =====
    [
        "title"  => "Dashboard",
        "icon"   => "bi bi-speedometer2",
        "link"   => BASE_URL . "/admin/dashboard",
        "active" => true
    ],

    // ===== Institute =====
    [
        "title" => "Institute",
        "submenu_id" => "instituteSubmenu",
        "submenu" => [
            [
                "title"  => "Employees",
                "link"   => BASE_URL . "/admin/institute/employees",
                "active" => false
            ],
            [
                "title"  => "Department",
                "link"   => BASE_URL . "/admin/institute/department",
                "active" => false
            ],
            [
                "title"  => "Teachers",
                "link"   => BASE_URL . "/admin/institute/teacher",
                "active" => false
            ]
        ],
        "active" => false
    ],
    // ===== Institute =====
    [
        "title" => "SIS",
        "submenu_id" => "SISSubmenu",
        "submenu" => [
            [
                "title"  => "Students",
                "link"   => BASE_URL . "/admin/sis/student",
                "active" => false
            ],
            // [
            //     "title"  => "Rooms",
            //     "link"   => BASE_URL . "/admin/sis/rooms",
            //     "active" => false
            // ],
            [
                "title"  => "Courses",
                "link"   => BASE_URL . "/admin/sis/courses",
                "active" => false
            ]
        ],
        "active" => false
    ],

    // ===== Enrollment =====
    [
        "title" => "Enrollment",
        "submenu_id" => "enrollmentSubmenu",
        "submenu" => [
            [
                "title"  => "Dashboard",
                "link"   => BASE_URL . "/admin/enrollment/dashboard",
                "active" => false
            ],
            [
                "title"  => "Add",
                "link"   => BASE_URL . "/admin/enrollment/add",
                "active" => false
            ]
        ],
        "active" => false
    ],

    // ===== Attendance =====
    [
        "title" => "Attendance",
        "submenu_id" => "attendanceSubmenu",
        "submenu" => [
            [
                "title" => "Dashboard",
                "link"  => BASE_URL . "/admin/attendance/dashboard",
                "active" => false
            ],
            [
                "title"  => "Approved",
                "link"   => BASE_URL . "/admin/attendance/student/approved",
                "active" => false
            ]
        ],
        "active" => false
    ],
    // ===== Examination =====
    [
        "title" => "Examination",
        "submenu_id" => "examSubmenu",
        "submenu" => [
            ["title" => "Add", "link" => BASE_URL . "/admin/examination/add"],
            ["title" => "Results", "link" => BASE_URL . "/admin/examination/results"]
        ],
        "active" => false
    ],

    // ===== Schedule =====
    [
        "title" => "Schedule",
        "submenu_id" => "scheduleSubmenu",
        "submenu" => [
            [
                "title" => "Schedule",
                "link" => BASE_URL . "/admin/schedule/index",
                "active" => false
            ]
        ]
    ],

    // ===== Normal Links =====
    [
        "title" => "Register",
        "link"  => BASE_URL . "/admin/register",
        "active" => false

    ],
    [
        "title" => "Report",
        "submenu_id" => "reportSubmenu",
        "submenu" => [
            [
                "title" => "Dashboard",
                "link" => BASE_URL . "/admin/report/index",
                "active" => false
            ]
        ],
        "active" => false
    ]
];

// url root/ Accountant 
$routeAccount = [

    // ===== Dashboard =====
    [
        "title"  => "Dashboard",
        "icon"   => "bi bi-speedometer2",
        "link"   => BASE_URL . "/account/dashboard",
        "active" => true
    ],

    // ===== Institute =====
    [
        "title" => "Institute",
        "submenu_id" => "instituteSubmenu",
        "submenu" => [
            [
                "title"  => "Employees",
                "link"   => BASE_URL . "/account/institute/employees",
                "active" => false
            ],
            [
                "title"  => "Department",
                "link"   => BASE_URL . "/account/institute/department",
                "active" => false
            ],
            [
                "title"  => "Teachers",
                "link"   => BASE_URL . "/account/institute/teacher",
                "active" => false
            ]
        ],
        "active" => false
    ],
    // ===== Institute =====
    [
        "title" => "SIS",
        "submenu_id" => "SISSubmenu",
        "submenu" => [
            [
                "title"  => "Students",
                "link"   => BASE_URL . "/account/sis/student",
                "active" => false
            ],
            [
                "title"  => "Rooms",
                "link"   => BASE_URL . "/account/sis/rooms",
                "active" => false
            ],
            [
                "title"  => "Courses",
                "link"   => BASE_URL . "/account/sis/courses",
                "active" => false
            ]
        ],
        "active" => false
    ],

    // ===== Enrollment =====
    [
        "title" => "Enrollment",
        "submenu_id" => "enrollmentSubmenu",
        "submenu" => [
            [
                "title"  => "Dashboard",
                "link"   => BASE_URL . "/account/enrollment/dashboard",
                "active" => false
            ],
            [
                "title"  => "Add",
                "link"   => BASE_URL . "/account/enrollment/add",
                "active" => false
            ]
        ],
        "active" => false
    ],

    // ===== Attendance =====
    [
        "title" => "Attendance",
        "submenu_id" => "attendanceSubmenu",
        "submenu" => [
            [
                "title" => "Dashboard",
                "link"  => BASE_URL . "/account/attendance/dashboard",
                "active" => false
            ],
            [
                "title"  => "Approved",
                "link"   => BASE_URL . "/account/attendance/student/approved",
                "active" => false
            ]
        ],
        "active" => false
    ],
    // ===== Examination =====
    [
        "title" => "Examination",
        "submenu_id" => "examSubmenu",
        "submenu" => [
            ["title" => "Add", "link" => BASE_URL . "/account/examination/add"],
            ["title" => "Results", "link" => BASE_URL . "/account/examination/results"]
        ],
        "active" => false
    ],

    // ===== Schedule =====
    [
        "title" => "Schedule",
        "submenu_id" => "scheduleSubmenu",
        "submenu" => [
            [
                "title" => "Schedule",
                "link" => BASE_URL . "/account/schedule/index",
                "active" => false
            ]
        ]
    ],

    // ===== Normal Links =====
    [
        "title" => "Register",
        "link"  => BASE_URL . "/account/register",
        "active" => false

    ],
    [
        "title" => "Report",
        "submenu_id" => "reportSubmenu",
        "submenu" => [
            [
                "title" => "Schedule",
                "link" => BASE_URL . "/account/schedule/index",
                "active" => false
            ],
            [
                "title" => "Attendance",
                "link" => BASE_URL . "/account/attendance/dashboard",
                "active" => false
            ],
            [
                "title" => "Examination",
                "link" => BASE_URL . "/account/examination/results",
                "active" => false
            ]
        ],
        "active" => false
    ]
];

// url teacher
$routeTeacher = [

    // ===== Dashboard =====
    [
        "title"  => "Dashboard",
        "icon"   => "bi bi-speedometer2",
        "link"   => BASE_URL . "/teacher/dashboard",
        "active" => true
    ],

    // ===== Attendance =====
    [
        "title" => "Attendance",
        "submenu_id" => "attendanceSubmenu",
        "submenu" => [
            [
                "title" => "Dashboard",
                "link"  => BASE_URL . "/teacher/attendance/dashboard",
                "active" => false
            ],
            [
                "title"  => "Approved",
                "link"   => BASE_URL . "/teacher/attendance/student/approved",
                "active" => false
            ]
        ],
        "active" => false
    ],
    // ===== Examination =====
    [
        "title" => "Examination",
        "submenu_id" => "examSubmenu",
        "submenu" => [
            ["title" => "Add", "link" => BASE_URL . "/teacher/examination/add", "active" => false],
            ["title" => "Results", "link" => BASE_URL . "/teacher/examination/results", "active" => false]
        ],
        "active" => false
    ],

    // ===== Schedule =====
    [
        "title" => "Schedule",
        "submenu_id" => "scheduleSubmenu",
        "submenu" => [
            ["title" => "Schedule", "link" => "#", "active" => false]
        ]
    ],
    [
        "title" => "Sign Out",
        "link"   => BASE_URL . "/auth/signout",

    ]
];


//  ./data/dataSchema.php
$type = $_GET['type'] ?? null;
$id   = $_GET['id'] ?? null;

$id = (int) $id;
$basePath =BASE_INSTITUTE;
$currentPage = $currentPage ?? '';

$queryString = "?type={$type}&id={$id}";


$staffSchemaData = [
    [
        "title"  => "Detail",
        "link"   => $basePath . "/detail" . $queryString,
        "active" => $currentPage === 'detail'
    ],
    [
        "title"  => "Edit",
        "link"   => $basePath . "/edit" . $queryString,
        "active" => $currentPage === 'edit'
    ],
    [
        "title"  => "Schedule",
        "link"   => $basePath . "/schedule" . $queryString,
        "active" => $currentPage === 'schedule'
    ],
    [
        "title"  => "Attendance",
        "link"   => $basePath . "/attendance" . $queryString,
        "active" => $currentPage === 'attendance'
    ],
    [
        "title"  => "Payroll",
        "link"   => $basePath . "/payroll" . $queryString,
        "active" => $currentPage === 'payroll'
    ],
    [
        "title"  => "Leave",
        "link"   => $basePath . "/leave" . $queryString,
        "active" => $currentPage === 'leave'
    ],
];

$studentSchemaData = [
    [
        "title"  => "Detail",
        "link"   => $basePath . "/detail" . $queryString,
        "active" => $currentPage === 'detail'
    ],
    [
        "title"  => "Edit",
        "link"   => $basePath . "/edit" . $queryString,
        "active" => $currentPage === 'edit'
    ],
    [
        "title"  => "Course",
        "link"   => $basePath . "/course" . $queryString,
        "active" => $currentPage === 'course'
    ],
    [
        "title"  => "Attendance",
        "link"   => $basePath . "/attendance" . $queryString,
        "active" => $currentPage === 'attendance'
    ],
    [
        "title"  => "Invoice",
        "link"   => $basePath . "/invoice" . $queryString,
        "active" => $currentPage === 'invoice'
    ],
    [
        "title"  => "Result",
        "link"   => $basePath . "/result" . $queryString,
        "active" => $currentPage === 'result'
    ],
];


