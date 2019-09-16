<?php

return [
    // products pages
    [
        'pattern' => '<_a:(category|product|catalog)>/<slug:[\d]+>',
        'route' => 'main/products/<_a>',
        'defaults' => [
            'slug' => false,
        ],
    ],
    
    //    auth
    '<_a:(login|signup|logout|confirm-email|reset-password|reset-password-request)>' => 'user/default/<_a>',
    
    //    main pages
    '' => 'main/default/index',
    
    '<_c:(cart|checkout|profile)>' => 'main/<_c>/index',
    '<_a:(delivery|payment|warranty|sizes|contacts|about)>' => 'main/default/<_a>',
    
    //    admin pages
    'admin' => 'admin/default/index',
    'admin/<controller>' => 'admin/<controller>/index',
    'admin/<_c:[\w\-]+>/<_a:(view|update)>/<id:\d+>' => 'admin/<_c>/<_a>',

];