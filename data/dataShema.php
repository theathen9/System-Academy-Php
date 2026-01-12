<?php
$infoShemaData=[
    ["name"=>"Empowerment Education English One"],
    ["email"=>"info@empowermentacademy.com"],
    ["address"=>"36, Siem Reap, Siem Reap"],
    ["phone"=>"095355521"],
    ["image"=>"/system-management/src/assets/icon.png"]
];
$staticShemaData=[
    // ===== Dashboard =====
    [
        "title"  => "Dashboard",              // Menu title
        "icon"   => "bi bi-speedometer2",     // Bootstrap icon
        "link"   => "/system-management/admin/dashboard.php",           // Page link
        "active" => true                      // Mark as active
    ],

    // ===== Institute (Has submenu) =====
    [
        "title" => "Institute",
        "submenu_id" => "instituteSubmenu",   // Bootstrap collapse ID
        "submenu" => [

            // Submenu item
            [
                "title"  => "Employees",
                "link"   => "/system-management/admin/institute/employees.php",
                "active" => false              // Active submenu item
            ],
            [
                "title" => "Teachers",
                "link"  => "/system-management/admin/institute/teacher.php",
                "active" => false              // Active submenu item

            ],
            [
                "title" => "Students",
                "link"  => "/system-management/admin/institute/student.php",
                "active" => false              // Active submenu item
            ]
        ],
        "active" => false                      // Mark as active
    ],
 // ===== Enrollment (Has submenu) =====
    [
        "title" => "Enrollment",
        "submenu_id" => "enrollmentSubmenu",   // Bootstrap collapse ID
        "submenu" => [

            // Submenu item
            [
                "title"  => "Enrollment",
                "link"   => "/system-management/admin/enrollment/enrollment.php",
                "active" => false              // Active submenu item
            ]
        ],
        "active" => false                      // Mark as active
    ],

    // ===== Attendance =====
    [
        "title" => "Attendance",
        "submenu_id" => "attendanceSubmenu",
        "submenu" => [
            ["title" => "Dashboard", "link" => "./Dashboard.php"],
            ["title" => "Attendance", "link" => "#"]
        ]
    ],

    // ===== Examination =====
    [
        "title" => "Examination",
        "submenu_id" => "examSubmenu",
        "submenu" => [
            ["title" => "Schedule", "link" => "#"],
            ["title" => "Results", "link" => "#"]
        ]
    ],

    // ===== Schedule =====
    [
        "title" => "Schedule",
        "submenu_id" => "scheduleSubmenu",
        "submenu" => [
            ["title" => "Schedule", "link" => "#"]
        ]
    ],

    // ===== Normal Links =====
    [
        "title" => "Billing",
        "link"  => "#"
    ],
    [
        "title" => "Accounts",
        "link"  => "#"
    ]
];