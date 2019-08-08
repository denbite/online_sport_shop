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
    '<_a:(login|signup|logout|confirm-email|request-password-reset|reset-password)>' => 'user/default/<_a>',
    
    //    main pages
    '' => 'main/default/index',
    'cart' => 'main/cart/index',
    'checkout' => 'main/checkout/index',
    
    //    admin pages
    'admin' => 'admin/default/index',
    'admin/<controller>' => 'admin/<controller>/index',
    'admin/<_c:[\w\-]+>/<_a:(view|update)>/<id:\d+>' => 'admin/<_c>/<_a>',

];