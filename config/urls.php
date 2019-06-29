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
    'admin/<_c:[\w\-]+>/<_a:(view|update)>/<id:\d+>' => 'admin/<_c>/<_a>',

];