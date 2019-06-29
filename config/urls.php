<?php

return [
    //    '<slug:[\w\-\/]+>/page<page:\d+>/' => 'main/default/index',
    
    //    auth
    '<_a:(login|signup|logout|confirm-email|request-password-reset|reset-password)>' => 'user/default/<_a>',
    
    //    main pages
    '' => 'main/default/index',
    
    //    admin pages
    'admin' => 'admin/default/index',
    'admin/<controller>' => 'admin/<controller>/index',
    'user/<_c:[\w\-]+>/<id:\d+>' => 'user/<_c>/view',
    'user/<_c:[\w\-]+>/update/<id:\d+>' => 'user/<_c>/update',

];