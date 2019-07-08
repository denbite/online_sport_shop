<?php

return [
    //    '<slug:[\w\-\/]+>/page<page:\d+>/' => 'main/default/index',
    'category/<slug:[\d]+>' => 'main/category/index',
    'product/<id:[\d]+>' => 'main/product/index',
    'category' => 'main/category/index',
    
    //    auth
    '<_a:(login|signup|logout|confirm-email|request-password-reset|reset-password)>' => 'user/default/<_a>',
    
    //    main pages
    '' => 'main/default/index',
    
    //    admin pages
    'admin' => 'admin/default/index',
    'admin/<controller>' => 'admin/<controller>/index',
    'admin/<_c:[\w\-]+>/<_a:(view|update)>/<id:\d+>' => 'admin/<_c>/<_a>',

];