<?php
// Все 3 задания работают в одном приложении.
return [
    ['pattern' => '~^/$~u', 'method' => 'index'],
    ['pattern' => '~^/hello/(?P<name>[^/]+)$~u', 'method' => 'sayHello'],
    ['pattern' => '~^/bye/(?P<name>[^/]+)$~u', 'method' => 'sayBye'],
    ['pattern' => '~^/articles/(?P<id>[1-9][0-9]*)$~u', 'controller' => 'articles', 'method' => 'show'],
];
