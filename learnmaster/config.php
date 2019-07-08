<?php
/**
 * @copyright © 2017 by Solazu Co.,LTD
 * @project Learn Master Plugin
 * @since 1.0
 *
 */
/**
 * Version of LEMA
 */

/**
 * Name of learn master plugin
 */
defined( 'LEMA_NAME' ) or define( 'LEMA_NAME', 'learnmaster' );
/**
 * Namespace of learn master plugin
 */
defined('LEMA_NAMESPACE') or define('LEMA_NAMESPACE', 'lema');

define('LEMA_HOME', 'http://learnmaster.rubikthemes.com/');
define('LEMA_SUPPORT_EMAIL', 'cs@rubikthemes.com'); //cs@rubikthemes.com



define('NAME_PLUGIN','learnmaster');
define('LEMA_PATH_PLUGIN', plugins_url() . DIRECTORY_SEPARATOR . NAME_PLUGIN);

defined('LEMA_PATH') or define('LEMA_PATH', ABSPATH . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' .DIRECTORY_SEPARATOR.NAME_PLUGIN);
defined('PUBLIC_PATH') or define('PUBLIC_PATH', dirname(LEMA_PATH) . DIRECTORY_SEPARATOR . 'public');
defined('LEMA_DEBUG') or define('LEMA_DEBUG', defined('WP_DEBUG') ? WP_DEBUG : true);
defined('LEMA_WR_DIR') or  define('LEMA_WR_DIR', ABSPATH . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'lema');
define('LEMA_PATH_TEMPLATE',ABSPATH.'/wp-content/plugins/'.NAME_PLUGIN.'/admin/templates/');
return [
    'lazyShortcode' => false,
    'roleManager' => [
        'roles' => [
            \lema\core\components\RoleManager::LEMA_ROLE_DEFAULT => [
                'label' => 'Learn master default role',
                'capabilities' => [\lema\core\components\RoleManager::LEMA_CAP_DEFAULT]
            ],
            'lema_instructor' => [
                'label' => __( 'Learnmaster Instructor', 'lema'),
                'capabilities' => array(
                    'read'         => true,  // true allows this capability
                    'edit_posts'   => true,
                    'delete_posts' => false, // Use false to explicitly deny
                    'upload_files' => true,
                    'publish_posts' => false,
                )
            ],
            'lema_student' => [
                'label' => __( 'Learnmaster Student', 'lema'),
                'capabilities' => array(
                    'read'         => true
                )
            ]
        ],
        'capabilities' => [
            \lema\core\components\RoleManager::LEMA_CAP_DEFAULT ,
            \lema\core\components\RoleManager::LEMA_CAP_COURSE,
            \lema\core\components\RoleManager::LEMA_CAP_SETTING,
            \lema\core\components\RoleManager::LEMA_CAP_ORDER,
        ],
    ],
    'resource' => [
        'handler' => '',
        'assets' => [
            'scripts' => [
                [
                    'id' => 'lema',
                    'url' => plugins_url('learnmaster/assets/scripts/lema.js'),
                    'dependencies' => [
                        'backbone' ,'underscore'
                    ],
                ],
                [
                    'id' => 'lema.shortcode',
                    'url' => plugins_url('learnmaster/assets/scripts/lema.shortcode.js')
                ],
                [
                    'id' => 'lema.ui',
                    'url' => plugins_url('learnmaster/assets/scripts/lema.ui.js'),
                    'dependencies' => [
                        'select2'
                    ],
                ],
                [
                    'id' => 'select2',
                    'url' => plugins_url('learnmaster/assets/libs/select2/js/select2.min.js')
                ]
            ],
            'styles' => [
                [
                    'id' => 'font-awesome',
                    'isInline' => false,
                    'url' => plugins_url('learnmaster/assets/libs/font-awesome/css/font-awesome.css'),
                ],
                [
                    'id' => 'lema-style',
                    'url'   => plugins_url('learnmaster/assets/styles/lema.css'),
                    'dependencies' => ['font-awesome']
                ],
                [
                    'id' => 'select2',
                    'url'   => plugins_url('learnmaster/assets/libs/select2/css/select2.min.css'),
                    'dependencies' => []
                ]

            ]

        ]
    ],
    'pages' => [
        'lema_search' => [
            'slug' => 'lema-search',
            'label' => __('Search page', 'lema')
        ],
        'lema_cart' => [
            'slug' => 'lema-cart',
            'label' => __('Cart page', 'lema')
        ],
        'lema_checkout' => [
            'slug' => 'lema-checkout',
            'label' => __('Checkout page', 'lame')
        ],
        'lema_dashboard' => [
            'slug' => 'dashboard',
            'label' => __('Course dashboard page')
        ],
        'lema_learning' => [
            'slug' => 'learning',
            'label' => 'Learning page'
        ],
        'lema_user_profile'  => [
            'slug' => 'lema-user-profile',
            'label' => 'User page'
        ] ,
        'lema_user_edit_profile'   => [
            'slug' => 'lema-user-edit-profile' ,
            'label' => 'User page'
        ],
        'lema_instructor_edit_profile'   => [
            'slug' => 'lema-instructor-edit-profile' ,
            'label' => 'User page'
        ],
        'lema_login'   => [
            'slug' => 'lema-login' ,
            'label' => 'Login page'
        ],
        'lema_register'   => [
            'slug' => 'lema-register' ,
            'label' => 'Register page'
        ]
    ]
];