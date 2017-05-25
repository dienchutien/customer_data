<?php
return [    
    'all_category'=>[
        'UserController'=>[
            'Danh sách user'=>'getAllUser',
            'Thêm mới user'=>'EditUser'
        ],
        'ProjectsController'=>[
            'Danh sách dự án'=>'getAllProject',
            'Thêm mới dự án'=>'ListProjects'
        ],
        'SupplierController'=>[
            'Danh sách nhà cung cấp' => 'getAllSupplier',
            'Thêm mới nhà cung cấp'=>'addEditSupplier'            
        ],
        'ChannelController'=>[
            'Danh sách kênh' => 'getAllChannel',
            'Thêm mới kênh'=>'addEditChannel'
        ],
        'BranchController'=>[// VI PHAM THANG
            'Danh sách chi nhánh' => 'getAllBranch',
            'Thêm mới chi nhánh' => 'addEditBranch'
        ],
        'JobController'=>[
            'Danh sách tác vụ'=>'getAllJob',
            'Thêm mới tác vụ'=>'addEditJob',
            'Thống kê tác vụ'=>'jobStatistics',
            'export Thống kê'=>'exportJobStatistics',
            'export tác vụ'=>'exportJob'
            
        ],
        'RoleController'=>[
            'Danh sách nhóm quyền'=>'ListRoleGroup',
            'Sửa nhóm quyền'=>'editRoleGroup',
            'Thêm mới nhóm quyền'=>'insertRoleGroup'
        ]
    ],
    'name_controller'=>[
        'UserController'=>'Quản lý user',
        'ProjectsController'=>'Quản lý dự án',
        'SupplierController'=>'Quản lý nhà cung cấp',
        'ChannelController'=>'Quản lý kênh',
        'BranchController'=>'Quản lý chi nhánh',
        'JobController'=>'Quản lý tác vụ',
        'RoleController'=>'Quản lý phân quyền'
    ]
    
];

