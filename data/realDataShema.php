<?php

$infoShemaData = [
    ["name" => "Empowerment Education English One"],
    ["email" => "info@empowermentacademy.com"],
    ["address" => "36, Siem Reap, Siem Reap"],
    ["phone" => "+855 95 999 997"],
    ["image" => "/src/assets/icon.png"]
];

define('ADMIN_BASE', '/admin');
define('ACCOUNT_BASE', '/account');
define('TEACHER_BASE', '/teacher');

$staticShemaData = [
    [
        "path" => "/",

        // ================= ADMIN =================
        "adminPath" => [
            [
                "path" => ADMIN_BASE,
                "childrenPath" => [

                    // Dashboard
                    [
                        "title"  => "Dashboard",
                        "icon"   => "bi bi-speedometer2",
                        "path"   => ADMIN_BASE . "/dashboard",
                        "active" => false
                    ],

                    // ===== Institute =====
                    [
                        "title" => "Institute",
                        "submenu_id" => "instituteSubmenu",
                        "submenu" => [
                            [
                                "title" => "Employees",
                                "path"  => ADMIN_BASE . "/institute/employees",
                                "active" => false
                            ],
                            [
                                "title" => "Department",
                                "path"  => ADMIN_BASE . "/institute/department",
                                "active" => false
                            ],
                            [
                                "title" => "Teachers",
                                "path"  => ADMIN_BASE . "/institute/teacher",
                                "active" => false
                            ],
                            [
                                "title" => "Students",
                                "path"  => ADMIN_BASE . "/institute/student",
                                "active" => false
                            ],
                        ],
                        "active" => false
                    ],

                    // ===== Enrollment =====
                    [
                        "title" => "Enrollment",
                        "submenu_id" => "enrollmentSubmenu",
                        "submenu" => [
                            [
                                "title" => "Enrollment",
                                "path"  => ADMIN_BASE . "/enrollment/enrollment",
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
                                "path"  => "#"
                            ]
                        ],
                        "active" => false
                    ],

                    // ===== Examination =====
                    [
                        "title" => "Examination",
                        "submenu_id" => "examSubmenu",
                        "submenu" => [
                            ["title" => "Schedule", "path" => "#"],
                            ["title" => "Results", "path" => "#"]
                        ],
                        "active" => false
                    ],

                    // ===== Schedule =====
                    [
                        "title" => "Schedule",
                        "submenu_id" => "scheduleSubmenu",
                        "submenu" => [
                            ["title" => "Schedule", "path" => "#"]
                        ]
                    ],
                ]
            ]
        ],

        // ================= ACCOUNT =================
        "accountPath" => [
            [
                "path" => ACCOUNT_BASE,
                "childrenPath" => [

                    [
                        "title" => "Dashboard",
                        "icon"  => "bi bi-speedometer2",
                        "path"  => ACCOUNT_BASE . "/dashboard",
                        "active" => false
                    ],

                    [
                        "title" => "Institute",
                        "submenu_id" => "accountInstituteSubmenu",
                        "submenu" => [
                            [
                                "title" => "Employees",
                                "path"  => ACCOUNT_BASE . "/institute/employees",
                                "active" => false
                            ],
                            [
                                "title" => "Department",
                                "path"  => ACCOUNT_BASE . "/institute/department",
                                "active" => false
                            ],
                            [
                                "title" => "Teachers",
                                "path"  => ACCOUNT_BASE . "/institute/teacher",
                                "active" => false
                            ],
                            [
                                "title" => "Students",
                                "path"  => ACCOUNT_BASE . "/institute/student",
                                "active" => false
                            ]
                        ],
                        "active" => false
                    ],

                    [
                        "title" => "Enrollment",
                        "submenu_id" => "accountEnrollmentSubmenu",
                        "submenu" => [
                            [
                                "title" => "Enrollment",
                                "path"  => ACCOUNT_BASE . "/enrollment",
                                "active" => false
                            ]
                        ],
                        "active" => false
                    ],
                ]
            ]
        ],

        // ================= Teacher =================
        "teacherPath" => [
            [
                "path" => ACCOUNT_BASE,
                "childrenPath" => [

                    [
                        "title" => "Dashboard",
                        "icon"  => "bi bi-speedometer2",
                        "path"  => ACCOUNT_BASE . "/dashboard",
                        "active" => false
                    ],

                    [
                        "title" => "Institute",
                        "submenu_id" => "accountInstituteSubmenu",
                        "submenu" => [
                            [
                                "title" => "Employees",
                                "path"  => ACCOUNT_BASE . "/institute/employees",
                                "active" => false
                            ],
                            [
                                "title" => "Department",
                                "path"  => ACCOUNT_BASE . "/institute/department",
                                "active" => false
                            ],
                            [
                                "title" => "Teachers",
                                "path"  => ACCOUNT_BASE . "/institute/teacher",
                                "active" => false
                            ],
                            [
                                "title" => "Students",
                                "path"  => ACCOUNT_BASE . "/institute/student",
                                "active" => false
                            ]
                        ],
                        "active" => false
                    ],

                    [
                        "title" => "Enrollment",
                        "submenu_id" => "accountEnrollmentSubmenu",
                        "submenu" => [
                            [
                                "title" => "Enrollment",
                                "path"  => ACCOUNT_BASE . "/enrollment",
                                "active" => false
                            ]
                        ],
                        "active" => false
                    ],
                ]
            ]
        ],
    ],

    // ===== Normal paths =====
    [
        "title" => "Billing",
        "path"  => "#"
    ],
    [
        "title" => "Accounts",
        "path"  => "#"
    ]
];
