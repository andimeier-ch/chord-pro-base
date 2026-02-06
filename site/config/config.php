<?php

return [
    'api' => [
        'basicAuth' => true,
    ],
    'debug' => true,
    'cors' => [
        'origin' => ['http://localhost:5173/'],
        'methods' => ['GET', 'POST', 'OPTIONS'],
        'allowHeaders' => ['Authorization', 'Content-Type'],
    ],
];
