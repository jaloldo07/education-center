<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
            // 'tempPath' => '@frontend/runtime/upload-temp',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend',
                                 'httpOnly' => true,
                                 'path' => '/',
                                ],   
        ],
        'session' => [
            'name' => 'advanced-frontend',
            'cookieParams' => [
            'path' => '/',  
        ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'logout' => 'site/logout',
                'courses' => 'site/courses',
                'student-portal' => 'site/student-portal',
                'teachers' => 'site/teachers',
                'teacher-login' => 'site/teacher-login',
                'teacher-register' => 'site/teacher-register',


                'student/dashboard' => 'student/dashboard',

                'teacher/dashboard' => 'teacher/dashboard',
                'teacher/group/<id:\d+>' => 'teacher/group',
                'teacher/attendance' => 'teacher/attendance',
                'teacher/save-attendance' => 'teacher/save-attendance',

                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'teacher-detail/<id:\d+>' => 'site/teacher-detail',

                'support-api/get-tickets' => 'support-api/get-tickets',
                'support-api/get-ticket/<id:\d+>' => 'support-api/get-ticket',
                'support-api/create' => 'support-api/create',
                'support-api/delete-ticket' => 'support-api/delete-ticket',

                'teacher/my-students' => 'teacher/my-students',
                'teacher/attendance-history/<id:\d+>' => 'teacher/attendance-history',
                'teacher/schedule/<id:\d+>' => 'teacher/schedule',
                'teacher/create-schedule/<id:\d+>' => 'teacher/create-schedule',
                'teacher/delete-schedule/<id:\d+>' => 'teacher/delete-schedule',
                'teacher/calendar' => 'teacher/calendar',
            ],
        ],
    ],
    'params' => $params,
];
