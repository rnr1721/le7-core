<?php

return [
    'assets' => [
        'scripts' => [
            'bootstrap5' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',
            'jquery3' => 'https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js',
            'axios' => 'https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js',
            'vuejs' => 'https://cdn.jsdelivr.net/npm/vue@3.3.4/dist/vue.global.min.js'
        ],
        'styles' => [
            'bootstrap5' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css',
            'fontawesome6' => 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/fontawesome.min.css'
        ]
    ],
    'collections' => [
        'standard' => [
            'scripts_header' => [
                'bootstrap5'
            ],
            'scripts_footer' => [
            ],
            'styles' => [
                'bootstrap5', 'fontawesome6'
            ]
        ]
    ],
    'jsEnvironment' => []
];
