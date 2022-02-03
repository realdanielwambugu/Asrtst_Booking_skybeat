<?php
return [

  'validation' => [

    'constraints' => [

      //user
       'default' => [
         'fullName' => 'required|min:3|max:30',
         'email' => 'email|required|unique:user|min:3|max:255',
         'phone' => 'required|unique:user|min:10|max:10',
         'password' => 'required|min:3',
         'confirmPassword' => 'match:password',
         'messages' => 'default',
       ],

      'auth' => [
        'email' => 'required|verify:user',
        'password' => 'required|verify:user',
        'code' => 'required|verify:user',
        'messages' => 'auth',
        ],

        'userUpdate' => [
          'fullName' => 'required|min:3|max:30',
          'email' => 'email|required|min:3|max:255',
          'phone' => 'required|min:10|max:10',
          'messages' => 'auth',
          ],

          'changePassword' => [
            'password' => 'required',
            'newPassword' => 'required',
            'messages' => 'auth',
          ],

        //artist

        'createArtist' => [
          'name' => 'required|min:3|max:30',
          'category_id' => 'required',
          'country' => 'required',
          'birth_day' => 'required',
          'biography' => 'required',
          'cost_per_hr' => 'required',

          'song1Title' => 'required',
          'song1Album' => 'required',
          'song1RealeseYear' => 'required',

          'song2Title' => 'required',
          'song2Album' => 'required',
          'song2RealeseYear' => 'required',

          'messages' => 'default',
        ],


        'book' => [
          'book_date' => 'required',
          'from_time' => 'required',
          'to_time' => 'required',
          'mpesa_code' => 'required|unique:booking',
          'messages' => 'default',
        ],
    ],

    'messages' => [

      'default' => [

        'required' => ':field is required',
        'min' => ':field must be minimumn of :satisfer characters',
        'max' => ':field must be maximum of :satisfer characters',
        'email' => 'Email address is invalid',
        'alpha' => ':field must contain only letters and numbers',
        'match' => ':satisfers should match',
        'unique' => ':field is alredy taken',
        'file' => 'Selected image file Type is not allowed',
        'exists' => true,

      ],

      'auth' => [
        'required' => ':field is required',
        'verify' => 'You have entered a wrong :field',
        'unique' => ':field is alredy taken',
        'min' => ':field must be minimumn of :satisfer characters',
        'max' => ':field must be maximum of :satisfer characters',
        'email' => 'Email address is invalid',

      ],

    ],


  ],

];
