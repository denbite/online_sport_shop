<?php

return [
    '<slug:[\w\-\/]+>/page<page:\d+>/' => 'main/default/index',
    
    //    main pages
    '' => 'main/default/index',
    
    //    admin pages
    'admin' => 'admin/default/index',
    'admin/<controller>' => 'admin/<controller>/index',

];