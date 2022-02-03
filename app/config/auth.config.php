<?php

return [
  /*
   | model class to be used for authentication .
   |
  */
  'url' => '',

  'auth' => [
      'protect' => [
        'user' => [
           'model' => models\User::class,
           'key' => 'email'
         ],
      ],

    'hash' => [
        'algo' => PASSWORD_BCRYPT,
        'cost' => 10,
    ]
 ],

 /*
  | set email configuration.
  |
 */
  'mail' => [
     'smtp' => true,
     'smtp_auth' => true,
     'smtp_secure' => 'tls',
     'smtp_debug' => 0,
     'host' => 'smtp.gmail.com',
     'username' => 'newspair@gmail.com',
     'password' => 'myadsense',
     'port' => 587,
     'html' => true,
  ]
];
