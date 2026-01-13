<?php
$infoShemaData=[
    ["name"=>"Empowerment Education English One"],
    ["email"=>"info@empowermentacademy.com"],
    ["address"=>"36, Siem Reap, Siem Reap"],
    ["phone"=>"095355521"],
    ["image"=>"/src/assets/icon.png"]
];

$staticShemaData = [

    // ===== Dashboard =====
    [
        "title"  => "Dashboard",
        "icon"   => "bi bi-speedometer2",
        "link"   => "/admin/dashboard",
        "active" => true
    ],

    // ===== Institute =====
    [
        "title" => "Institute",
        "submenu_id" => "instituteSubmenu",
        "submenu" => [
            [
                "title"  => "Employees",
                "link"   => "./admin/institute/employees",
                "active" => false
            ],
            [
                "title"  => "Department",
                "link"   => "./admin/institute/department",
                "active" => false
            ],
            [
                "title"  => "Teachers",
                "link"   => "./admin/institute/teacher",
                "active" => false
            ],
            [
                "title"  => "Students",
                "link"   => "./admin/institute/student",
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
                "title"  => "Enrollment",
                "link"   => "./admin/enrollment/enrollment",
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
                "title" => "Attendance",
                "link"  => "#"
            ],
        ],
        "active" => false
    ],
    // ===== Examination =====
    [
        "title" => "Examination",
        "submenu_id" => "examSubmenu",
        "submenu" => [
            ["title" => "Schedule", "link" => "#"],
            ["title" => "Results", "link" => "#"]
        ],
        "active" => false
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
