route = new Route( {
	default: [
		"../app/bootstrap.php",
		" ",
	],
	index: [
		"templates/customer/Auth/join",
	],

	join: [
		"UserController",
		"create",
		'join_form',
		"response",
		"join_btn",
		 {
			 loader:'<i class="fal fa-spinner" aria-hidden="true"></i>',
		 }

	],

	auth: [
		"auth\\LoginController",
		"verify",
		'login_form',
		"response",
		"login_btn",
		 {
			 loader:'<i class="fal fa-spinner" aria-hidden="true"></i>',
		 }

	],


	join: [
		"UserController",
		"create",
		'join_form',
		"response",
		"join_btn",
		 {
			 loader:'<i class="fal fa-spinner" aria-hidden="true"></i>',
		 }
	],

	getCustomers: [
		"UserController",
		"index",
		'',
		"all_users",
		'load_customers',
		 {
			 loader:`<i class="fal fa-spinner fa-2x" aria-hidden="true"></i>`,
		 }
	],

	updateCustomer: [
		"UserController",
		"update",
		'',
		"all_users",
		'status_btn',
		 {
			 loader:`<i class="fal fa-spinner fa-2x" aria-hidden="true"></i>`,
		 }
	],

	updateAccount: [
		"UserController",
		"update",
		'updateAccountForm',
		"response_box",
		'status_btn',
		 {
			 loader:`<i class="fal fa-spinner fa-2x" aria-hidden="true"></i>`,
		 }
	],

	changePassword: [
		"UserController",
		"changePassword",
		'updateAccountForm',
		"pass_response_box",
		'pass_status_btn',
		 {
			 loader:`<i class="fal fa-spinner fa-2x" aria-hidden="true"></i>`,
		 }
	],


	deleteCustomer: [
		"UserController",
		"delete",
		'',
		"all_users",
		'delete_btn',
		 {
			 loader:`<i class="fal fa-spinner" aria-hidden="true"></i>`,
		 }
	],

	createArtist: [
		"ArtistController",
		"create",
		"createArtistForm",
		'createArtist_response',
		"createArtist_btn",
		 {
			 loader:'<i class="fal fa-spinner" aria-hidden="true"></i>',
		 }
	],

  getArtist: [
		"ArtistController",
		"index",
		'',
		"all_artists",
		"load_artists",
		 {
			 loader:'<i class="fal fa-spinner" aria-hidden="true"></i>',
		 }
	],

	deleteArtist: [
		"ArtistController",
		"delete",
		'',
		"all_artists",
		'delete_btn_',
		 {
			 loader:`<i class="fal fa-spinner" aria-hidden="true"></i>`,
		 }
	],

	book: [
		"BookingController",
		"create",
		"booking_form",
		'book_response',
		"confirm",
		 {
			 loader:'<i class="fal fa-spinner" aria-hidden="true"></i>',
		 }
	],

	getBooking: [
		"BookingController",
		"index",
		"",
		'all_booking',
		"booking_load",
		 {
			 loader:'<i class="fal fa-spinner fa-2x" aria-hidden="true"></i>',
		 }
	 ],


	 forgotPassword: [
		 "auth\\forgotPasswordController",
		 "confirmEmail",
		 "confirmEmailForm",
		 'response',
		 "load_btn",
			{
				loader:'<i class="fal fa-spinner " aria-hidden="true"></i>',
			}
		],

		resetCode: [
			"auth\\forgotPasswordController",
			"confirmCode",
			"confirmCodeForm",
			'response',
			"load_btn",
			 {
				 loader:'<i class="fal fa-spinner " aria-hidden="true"></i>',
			 }
		 ],

		 resetPassword: [
			 "auth\\forgotPasswordController",
 			"resetPassword",
 			"resetPasswordForm",
 			'response',
 			"load_btn",
 			 {
 				 loader:'<i class="fal fa-spinner " aria-hidden="true"></i>',
 			 }
			],

} );
