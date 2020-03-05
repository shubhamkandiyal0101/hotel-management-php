<?php
	
	// include required files
	require_once($_SERVER['DOCUMENT_ROOT']."/classes/route.php");
	// ends here ~ include required files

	// All Routes
	
	// home
	Route::add('/',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/web_home.php';
	});
	// register hotel user
	Route::add('/register-hotel-admin',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/hotel_admin_registration.php';
	});
	// login hotel user
	Route::add('/login-hotel-admin',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/hotel_admin_login.php';
	});
	// register normal user
	Route::add('/register-user',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/normal_user_registration.php';
	});
	// login normal user
	Route::add('/login-user',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/normal_user_login.php';
	});
	// hotel user dashboard user
	Route::add('/admin',function(){
		header("Location: /admin/dashboard");
		die();

	});
	Route::add('/admin/dashboard',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/admin_panel/hotel_dashboard.php';
	});
	// add hotel 
	Route::add('/admin/add-hotel',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/admin_panel/add_hotel.php';
	});
	// hotel details 
	Route::add('/admin/hotel-details',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/admin_panel/hotel_details.php';
	});
	// manage hotel rooms
	Route::add('/admin/manage-hotel-rooms/([0-9]*)',function($hotelId){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/admin_panel/manage_hotel_rooms.php';
	});
	Route::add('/admin/manage-hotel-rooms',function(){
		header("Location: /admin/dashboard");
		die();
	});
	// hotel user dashboard user
	Route::add('/user',function(){
		header("Location: /user/dashboard");
		die();

	});
	Route::add('/user/dashboard',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/user_dashboard/user_dashboard.php';
	});
	// user hotel room booking details
	Route::add('/user/room-booking-details',function(){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/user_dashboard/user_hotel_room_booking_details.php';
	});
	
	// @author Rachin - new code
	Route::add('/admin/edit-hotel/([0-9]*)',function($hotelId){
		require $_SERVER['DOCUMENT_ROOT'] . '/views/admin_panel/edit_hotel_details.php';
	});
	// ends here ~ @author Rachin - new code

	// ends here ~ All Routes

	Route::run('/');
	
?>
