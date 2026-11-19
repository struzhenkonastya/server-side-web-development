<?php
return [
    ['pattern' => '~^/$~u', 'method' => 'index'],
    ['pattern' => '~^/hello/(?P<name>[^/]+)$~u', 'method' => 'sayHello'],
    ['pattern' => '~^/bye/(?P<name>[^/]+)$~u', 'method' => 'sayBye'],
];
