<?php
/*
 | Provide all service provider that provide service bindings to be registered
 | with the service container.
 |
*/
return [


   'providers' => [
       \providers\DatabaseServiceProvider::class,
       \providers\ApplicationServiceProvider::class,
       \providers\AuthServiceProvider::class,
       \providers\EventServiceProvider::class,
       \providers\MailServiceProvider::class,
   ],


   'middleware' => [
       'auth' => \middleware\Authenticate::class,
       'guest' => \middleware\Guest::class,
       'admin' => \middleware\Admin::class,
       'age'  =>  \middleware\CheckAge::class,

       'groups' => [
            'test' => [
               \middleware\Authenticate::class,
                \middleware\CheckAge::class,
            ]
       ],
   ],


   'default'=> [
     // 'request' => [
     //     'auth\LoginController',
     //      'verify',
     //     [
     //        'email' => 'ac.ipsum@ametluctusvulputate.org',
     //        'password' => '9796',
     //      ],
     // ],

      'request' => [
          'UserController',
           'index',
           'email'    => 'ac.ipsum@ametluctusvulputate.org',
           'password' => '9796',
      ],


      'paths' => [
        'controllersPath'       => 'controllers\\',
        'modelsPath'            => 'models\\',
        'policyPath'            => 'auth\access\Policies\\',
        'viewsPath'             => 'views/',
        'middlewarePath'        => 'middleware\\',
        'eventsPath'            => 'events\\',
        'listenersPath'         => 'events\listeners\\',
        'eventsSubscribersPath' => 'events\subscribers',
        'mailTemplatePath'      =>  'views/mail/',

        'storagePaths' => [
           'public' => [
              'images' => [
                'user' => 'assets/images/user/',
                'artist' => 'assets/images/artist/',
                'svg' => 'assets/images/svg/',
                'others' => 'assets/others/',
                ],
            ],

           'private' => [
             'logs' => 'storage/logs/',
            ],

          ],

      ],

   ],


];
