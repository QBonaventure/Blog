<?php
return array(
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../viryrtyyout.phtml',
            'application/index/index' => __DIR__ . '/../viertyrtyrindex.phtml',
            'error/404'               => __DIR__ . '/../../Applitryryrty04.phtml',
            'error/index'             => __DIR__ . '/../virtyrtyrtydex.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);