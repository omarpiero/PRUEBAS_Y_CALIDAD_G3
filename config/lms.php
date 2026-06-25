<?php

return [
    'uploads' => [
        'video' => [
            'max_size_kb' => 512000, // 500 MB
            'allowed_extensions' => ['mp4', 'webm'],
            'allowed_mimes' => ['video/mp4', 'video/webm'],
        ],
        'document' => [
            'max_size_kb' => 51200, // 50 MB
            'allowed_extensions' => ['pdf', 'docx', 'doc'],
            'allowed_mimes' => [
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/msword'
            ],
        ],
        'presentation' => [
            'max_size_kb' => 51200, // 50 MB
            'allowed_extensions' => ['pptx', 'ppt', 'pdf'],
            'allowed_mimes' => [
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/vnd.ms-powerpoint',
                'application/pdf'
            ],
        ],
        'resource' => [
            'max_size_kb' => 102400, // 100 MB
            'allowed_extensions' => ['zip', 'rar', 'pdf', 'xlsx', 'docx'],
            'allowed_mimes' => [
                'application/zip',
                'application/x-rar-compressed',
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ],
        ],
        'cover' => [
            'max_size_kb' => 2048, // 2 MB
            'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
            'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp'],
        ],
    ],
    'storage_path_pattern' => 'materials/{course_id}/{module_id}',
];
