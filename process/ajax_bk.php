<?php


   require_once($_SERVER['DOCUMENT_ROOT']."/helpers/config.php");
   require_once($_SERVER['DOCUMENT_ROOT']."/helpers/encryption_decryption.php");

	$GLOBALS['dbconnection'] = $dbconnection;
	$GLOBALS['encryption'] = new Encryption;
	session_start();

	//############################################
	// common functions ~ Starts Here 
	//############################################

	// function for reset hotel rooms booking details
	function resetRoomBookingDetails($roomDataPar) {
		$todayDate = date('Y-m-d');
		$isBookingExpired = ($todayDate > $roomDataPar['check_out_date']);
		if($isBookingExpired == 1) {
			// update hotel rooms booking details
			$query = "UPDATE hotel_rooms SET room_booked_status=0, check_in_date=null, check_out_date=null, reserved_user_id=null, room_booking_table_id=null WHERE id='".$roomDataPar['id']."'";
			$serverResp = $GLOBALS['dbconnection']->query($query);
			// ends here ~ update hotel rooms booking details

			// update object and return data
			$roomDataPar['room_booked_status']=0;
			$roomDataPar['check_in_date']=null;
			$roomDataPar['check_out_date']=null;
			$roomDataPar['reserved_user_id']=null;
			$roomDataPar['room_booking_table_id']=null;

			return $roomDataPar;
			// ends here ~ update object and return data
		}
	}
	// ends here ~ function for reset hotel rooms booking details

	//############################################
	// common functions ~ Ends Here
	//############################################


   // signup hotel user
	function signupHotelUser() {

		$password = $GLOBALS['encryption']->encode($_POST['userPassword']);
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$userEmail = $_POST['userEmail'];
		$gender = $_POST['gender'];
		$state = $_POST['state'];
		$dob = $_POST['dob'];
		$district = $_POST['district'];
		$user_verify = $_POST['user_verify'];
		$user_type = $_POST['user_type'];

		// check user already register or not
		$checkRegUser = $GLOBALS['dbconnection']->query("SELECT id FROM users WHERE email='".$userEmail."'");
		$checkRegUser = $checkRegUser->fetch_assoc();
		// ends here ~ check user already register or not

		if ($checkRegUser == NULL) {
			// if user not exists

			// insert data to db
			$query = "INSERT INTO users (firstname, lastname, email, password, mobile, address, gender, district, state, country, user_type, user_verify, dob) VALUES ('".$firstname."', '". $lastname."', '". $userEmail."', '". $password."', '', '', '". $gender."', '". $district."', '". $state."', 'India', '". $user_type."', '". $user_verify."', '". $dob."')";
			$serverResp = $GLOBALS['dbconnection']->query($query);
			// ends here ~ insert data to db

			// send response back to AJAX according to $serverResp
			if($serverResp == true) {
				$respArray = array("status" => 200,"messageType" => "success", "data" => "null","responseType" => "success","message" => "User is Register successfully.");
				echo json_encode($respArray);
			} else {
				$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Error while User Registration");
				echo json_encode($respArray);
			}
			// ends here ~ send response back to AJAX according to $serverResp

			// ends here ~ if user not exists
		} else {
			// show error if user exists
			$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Please make sure You are Entering Correct Email Address.. ".$userEmail." email is already exists in our records");
			echo json_encode($respArray);
			// ends here ~ show error if user exists
		}

	}
	// ends here ~ signup hotel user

	// login hotel user
	function loginHotelUser() {
		$encryptPass = $GLOBALS['encryption']->encode($_POST['userPassword']);
		$userEmail = $_POST['userEmail'];

		// query for login user
		$serverResp = $GLOBALS['dbconnection']->query("SELECT id FROM users WHERE email='".$userEmail."' AND password='".$encryptPass."' AND user_type=1");
		$serverResp = $serverResp->fetch_assoc();
		// ends here ~ query for login user

		// send response back to AJAX according to $serverResp
		if ($serverResp == NULL) {
			$userArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Email / Password is Invalid");
			echo json_encode($userArray);
		} else {
			$userArray = array("status" => 200,"messageType" => "success", "data" => "null","responseType" => "success","message" => "Login Successfully");
			$_SESSION['hotel_admin_id'] = $serverResp['id'];
			echo json_encode($userArray);
		}
		// ends here ~ send response back to AJAX according to $serverResp
	}
	// ends here ~ login hotel user

	// function for logout hotel user
	function logoutHotelUser() {
		session_unset();
		session_destroy();
		header("Location: /login-hotel-admin");
		die();
	}
	// ends here ~ function for logout hotel user

	// function for logout normal user
	function logoutNormalUser() {
		session_unset();
		session_destroy();
		header("Location: /login-user");
		die();
	}
	// ends here ~ function for logout normal user

	// function for hotel registration
	function hotelRegistration() {
		$target_dir = $_SERVER['DOCUMENT_ROOT']."/uploadImg/";
		$formData = json_decode($_POST['otherInfo']);

		$hotelName = $formData->hotelName;
		$hotelAddr = $formData->hotelAddr;
		$hotelCity = $formData->hotelCity;
		$hotelDistrict = $formData->district;
		$hotelPin = $formData->hotelPin;
		$hotelDescription = $formData->hotelDescription;
		$hotelState = 'Himachal Pradesh';
		$hotelAdminId = $_SESSION['hotel_admin_id'];

		// check hotel already exists or not already 
		$isHotelExists = $GLOBALS['dbconnection']->query("SELECT id FROM hotels WHERE hotel_name='".$hotelName."' AND hotel_city='".$hotelCity."'");
		$isHotelExists = $isHotelExists->fetch_assoc();
		// ends here ~ check hotel already exists or not already

		if($isHotelExists == NULL) {
			$tmpExt = explode('.', $_FILES['file']['name']);
			$imgExt = end($tmpExt);
			$randomNum = mt_rand(100000, 999999);
			$milliseconds = round(microtime(true) * 1000);
			$imgName = $randomNum.$milliseconds.".".$imgExt;
			$target_file = $target_dir . $imgName;

			if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file) == true) {
				$query = "INSERT INTO hotels (hotel_name, hotel_addr, hotel_city, hotel_district, hotel_pincode, hotel_state, hotel_status, hotel_desc, hotel_image, user_id) VALUES ('".$hotelName."', '". $hotelAddr."', '". $hotelCity."', '". $hotelDistrict."', '". $hotelPin."','".$hotelState."',1, '". $hotelDescription."', '". $imgName."', '". $hotelAdminId."')";
				$serverResp = $GLOBALS['dbconnection']->query($query);

				// send response back to AJAX according to $serverResp
				if($serverResp == true) {
					$respArray = array("status" => 200,"messageType" => "success", "data" => "null","responseType" => "success","message" => "Hotel is Added Successfully");
					echo json_encode($respArray);
				} else {
					$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Error while registration of Hotel");
					echo json_encode($respArray);
				}
				// ends here ~ send response back to AJAX according to $serverResp
			}

		} else {
			$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Hotel already exists with ".$hotelName." name and ".$hotelCity." City in our records");
			echo json_encode($respArray);
		}

	}
	// ends here ~ function for hotel registration

	// get hotel details by hotel admin id
	function getAdminHotelDetails() {
		$hotelAdminId = $_SESSION['hotel_admin_id'];
		$hotelDetailsFilter = $GLOBALS['dbconnection']->query("SELECT * FROM hotels WHERE user_id='".$hotelAdminId."'");
		$hotelArr = [];
	    while($row = $hotelDetailsFilter->fetch_assoc() ){

	    // alter row details
	    $roomFilterData = $GLOBALS['dbconnection']->query("SELECT * FROM hotel_rooms WHERE hotel_id='".$row[id]."'");

	    if(mysqli_num_rows($roomFilterData) == 0) {
	    	$row['rooms_available'] = 0;
	    } else {
	    	$row['rooms_available'] = 1;
	    }
	    // ends here ~ alter row details

	    array_push($hotelArr, $row);
	    }
		return $hotelArr;
	}
	// ends here ~ get hotel details by hotel admin id

	// get current user login details
	function getUserDetails($userId) {
		$userFilter = $GLOBALS['dbconnection']->query("SELECT * FROM users WHERE id='".$userId."'");
		$userArr = [];
	    while($row = $userFilter->fetch_assoc() ){
	    	unset($row['password']);
	    	array_push($userArr, $row);
	    }
	    $arrayObj = $userArr[0];
		return $arrayObj;
	}
	// ends here ~ get current user login details

	// get all rooms according to hotel id
	function hotelAllRoomsDetails($hotelId) {
		$roomsFilterData = $GLOBALS['dbconnection']->query("SELECT * FROM hotel_rooms WHERE hotel_id='".$hotelId."'");
		$roomsArr = [];
		if($roomsFilterData != NULL) {
			while($row = $roomsFilterData->fetch_assoc() ){
				// reset room book details
				if($row['check_out_date'] != null) {
					$row = resetRoomBookingDetails($row);
				}
				// ends here ~ reset room book details
		    	array_push($roomsArr, $row);
		    }	
		}
	    return $roomsArr;
	}
	// ends here ~ get all rooms according to hotel id
	
	// get single hotel details on the basis of hotel id and hotel admin id
	function getSingleHotelDetails($hotelId, $hotelAdminId) {
		$singleHotelFilterData = $GLOBALS['dbconnection']->query("SELECT * FROM hotels WHERE id='".$hotelId."' AND user_id='".$hotelAdminId."'");
		$singleHotelArr = [];
	    while($row = $singleHotelFilterData->fetch_assoc() ){
	       array_push($singleHotelArr, $row);
	    }
	    $singleHotelObj = $singleHotelArr[0];
		return $singleHotelObj;
	}
	// ends here ~ get single hotel details on the basis of hotel id and hotel admin id

	// function for save hotel rooms according to hotel id
	function saveHotelRooms() {
		// set value into all variables
		$hotelRoomNumber = $_POST['hotelRoomNumber'];
		$roomType = $_POST['roomType'];
		$roomPrice = $_POST['roomPrice'];
		$hotel_id = $_POST['hotel_id'];
		$roomDetails = $_POST['roomDetails'];
		// ends here ~ set value into all variables

		// check hotel room already exists or not already 
		$isRoomExists = $GLOBALS['dbconnection']->query("SELECT id FROM hotel_rooms WHERE hotel_id='".$hotel_id."' AND room_number='".$hotelRoomNumber."'");
		$isRoomExists = $isRoomExists->fetch_assoc();
		// ends here ~ check hotel room already exists or not already

		if($isRoomExists == NULL) {
			$query = "INSERT INTO hotel_rooms (room_number, room_type, room_price, hotel_id, room_details) VALUES ('".$hotelRoomNumber."', '". $roomType."', '". $roomPrice."', '". $hotel_id."', '". $roomDetails."')";
			$serverResp = $GLOBALS['dbconnection']->query($query);

			// send response back to AJAX according to $serverResp
			if($serverResp == true) {
				$respArray = array("status" => 200,"messageType" => "success", "data" => "null","responseType" => "success","message" => "Hotel Room is Added Successfully");
				echo json_encode($respArray);
			} else {
				$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Error while Adding Hotel Room. Please Try Again.");
				echo json_encode($respArray);
			}
			// ends here ~ send response back to AJAX according to $serverResp

		} else {
			$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Room Number ".$hotelRoomNumber."  already exists with Current Hotel");
			echo json_encode($respArray);
		}

	}
	// ends here ~ function for save hotel rooms according to hotel id

	// function for update hotel rooms according to room id
	function updateHotelRoomDetails () {
		// store post request data into variables
		$hotelRoomId = $_POST['hotelRoomId'];
		$hotelRoomNumber = $_POST['hotelRoomNumber'];
		$roomType = $_POST['roomType'];
		$roomPrice = $_POST['roomPrice'];
		$roomDetails = $_POST['roomDetails'];
		$hotel_id = $_POST['hotel_id'];
		// ends here ~ store post request data into variables

		// check hotel room already exists or not already 
		$isRoomExists = $GLOBALS['dbconnection']->query("SELECT id FROM hotel_rooms WHERE id!='".$hotelRoomId."' AND  room_number='".$hotelRoomNumber."'");
		$isRoomExists = $isRoomExists->fetch_assoc();
		// ends here ~ check hotel room already exists or not already

		if($isRoomExists == NULL) {
			$query = "UPDATE hotel_rooms SET room_number='".$hotelRoomNumber."', room_type='". $roomType."', room_price='". $roomPrice."', room_details='". $roomDetails."' WHERE id='".$hotelRoomId."'";
			$serverResp = $GLOBALS['dbconnection']->query($query);

			// send response back to AJAX according to $serverResp
			if($serverResp == true) {
				$respArray = array("status" => 200,"messageType" => "success", "data" => "null","responseType" => "success","message" => "Hotel Room is Updated Successfully");
				echo json_encode($respArray);
			} else {
				$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Error while Updating Hotel Room. Please Try Again.");
				echo json_encode($respArray);
			}
			// ends here ~ send response back to AJAX according to $serverResp

		} else {
			$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Room Number ".$hotelRoomNumber."  already exists with Current Hotel");
			echo json_encode($respArray);
		}
	}
	// ends here ~ function for update hotel room according to room id

	// function for delete hotel room according to room id
	function deleteHotelRoom() {
		$hotelRoomId = $_POST['hotelRoomId'];

		// query for delete hotel room
		$query = "DELETE FROM hotel_rooms WHERE id='".$hotelRoomId."'";
		$serverResp = $GLOBALS['dbconnection']->query($query);
		// ends here ~ query for delete hotel room

		// send response back to AJAX according to $serverResp
		if($serverResp == true) {
			$respArray = array("status" => 200,"messageType" => "success", "data" => "null","responseType" => "success","message" => "Hotel Room is Successfully Delete");
			echo json_encode($respArray);
		} else {
			$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Error while Deleting Hotel Room. Please Try Again.");
			echo json_encode($respArray);
		}
		// ends here ~ query for delete hotel room
	}
	// ends here ~ function for delete hotel room according to room id

	// signup hotel user
	function signupNormalUser() {

		$password = $GLOBALS['encryption']->encode($_POST['userPassword']);
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$userEmail = $_POST['userEmail'];
		$gender = $_POST['gender'];
		$state = $_POST['state'];
		$dob = $_POST['dob'];
		$district = $_POST['district'];
		$user_verify = $_POST['user_verify'];
		$user_type = $_POST['user_type'];

		// check user already register or not
		$checkRegUser = $GLOBALS['dbconnection']->query("SELECT id FROM users WHERE email='".$userEmail."'");
		$checkRegUser = $checkRegUser->fetch_assoc();
		// ends here ~ check user already register or not

		if ($checkRegUser == NULL) {
			// if user not exists

			// insert data to db
			$query = "INSERT INTO users (firstname, lastname, email, password, mobile, address, gender, district, state, country, user_type, user_verify, dob) VALUES ('".$firstname."', '". $lastname."', '". $userEmail."', '". $password."', '', '', '". $gender."', '". $district."', '". $state."', 'India', '". $user_type."', '". $user_verify."', '". $dob."')";
			$serverResp = $GLOBALS['dbconnection']->query($query);
			// ends here ~ insert data to db

			// send response back to AJAX according to $serverResp
			if($serverResp == true) {
				$respArray = array("status" => 200,"messageType" => "success", "data" => "null","responseType" => "success","message" => "User is Register successfully.");
				echo json_encode($respArray);
			} else {
				$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Error while User Registration");
				echo json_encode($respArray);
			}
			// ends here ~ send response back to AJAX according to $serverResp

			// ends here ~ if user not exists
		} else {
			// show error if user exists
			$respArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Please make sure You are Entering Correct Email Address.. ".$userEmail." email is already exists in our records");
			echo json_encode($respArray);
			// ends here ~ show error if user exists
		}

	}
	// ends here ~ signup hotel user

	// login hotel user
	function loginNormalUser() {
		$encryptPass = $GLOBALS['encryption']->encode($_POST['userPassword']);
		$userEmail = $_POST['userEmail'];

		// query for login user
		$serverResp = $GLOBALS['dbconnection']->query("SELECT id FROM users WHERE email='".$userEmail."' AND password='".$encryptPass."' AND user_type=0");
		$serverResp = $serverResp->fetch_assoc();
		// ends here ~ query for login user

		// send response back to AJAX according to $serverResp
		if ($serverResp == NULL) {
			$userArray = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "Email / Password is Invalid");
			echo json_encode($userArray);
		} else {
			$userArray = array("status" => 200,"messageType" => "success", "data" => "null","responseType" => "success","message" => "Login Successfully");
			$_SESSION['hotel_user_id'] = $serverResp['id'];
			echo json_encode($userArray);
		}
		// ends here ~ send response back to AJAX according to $serverResp
	}
	// ends here ~ login hotel user

	// function for search hotel
	function searchHotelRooms() {
		$booking_start_date = $_POST['booking_start_date'];
		$booking_end_date = $_POST['booking_end_date'];
		$district = $_POST['district'];
		$adult = $_POST['adult'];
		$children = $_POST['children'];

		$hotelRoomsArr = [];

		// query for get all hotels according to district
		$hotelFilterData = $GLOBALS['dbconnection']->query("SELECT * FROM hotels WHERE hotel_district='".$district."'");
		// $hotelData = $hotelFilterData->fetch_assoc();
		// ends here ~ query for get all hotels according to district
		// $hotelRespData = $hotelFilterData;
		// $serverResp = $hotelRespData->fetch_assoc();

		if(mysqli_num_rows($hotelFilterData) == 0) {
			$ajaxReturn = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "No Hotels is Available in ".$district." District");
			echo json_encode($ajaxReturn);
		} else {
			$hotelArr = [];
			while($row = $hotelFilterData->fetch_assoc() ){
		    	array_push($hotelArr, $row);
		    }	
		    // for loop on filter hotel data
			for($hotel_index=0; count($hotelArr)>$hotel_index; $hotel_index++) {
				$currentHotelId = $hotelArr[$hotel_index]['id'];
				// query for get rooms on basis of hotel
				$room_filter_data = $GLOBALS['dbconnection']->query("SELECT * FROM hotel_rooms WHERE hotel_id='".$currentHotelId."'");
				// ends here ~ query for get rooms on basis of hotel
				if(mysqli_num_rows($room_filter_data) != 0) {
					// push data into hotel array 
					while($row = $room_filter_data->fetch_assoc() ){

						// reset room book details
						if($row['check_out_date'] != null) {
							$row = resetRoomBookingDetails($row);
						}
						// ends here ~ reset room book details

				    	$row['hotel_name'] = $hotelArr[$hotel_index]['hotel_name'];
				    	$row['hotel_addr'] = $hotelArr[$hotel_index]['hotel_addr'];
				    	$row['hotel_city'] = $hotelArr[$hotel_index]['hotel_city'];
				    	$row['district'] = $hotelArr[$hotel_index]['hotel_district'];
				    	$row['hotel_pincode'] = $hotelArr[$hotel_index]['hotel_pincode'];
				    	$row['hotel_state'] = $hotelArr[$hotel_index]['hotel_state'];
				    	$row['hotel_desc'] = $hotelArr[$hotel_index]['hotel_desc'];
				    	$row['hotel_image'] = $hotelArr[$hotel_index]['hotel_image'];
				    	array_push($hotelRoomsArr, $row);
				    }
				    // ends here ~ push data into hotel array	
				}
			}
			// ends here ~ for loop on filter hotel data

			// return hotel rooms array
			$ajaxReturn = array("status" => 200,"messageType" => "success", "data" => $hotelRoomsArr,"responseType" => "success","message" => "Hotel Searching Process is Completed Now");
			echo json_encode($ajaxReturn);
			// ends here ~ return hotel rooms array
		}

	}
	// ends here ~ function for search hotel

	// function for book hotels
	function bookHotelRoom() {
		$loggedInUserId = $_SESSION['hotel_user_id'];
		$booking_start_date = $_POST['booking_start_date'];
		$booking_end_date = $_POST['booking_end_date'];
		$location = $_POST['district'];
		$adult = $_POST['adult'];
		$children = $_POST['children'];
		$hotel_room_id = $_POST['hotel_room_id'];

		$check_in_timestamp = strtotime($booking_start_date);
		$check_in_final_date = date("Y-m-d H:i:s", $check_in_timestamp);

		$check_out_timestamp = strtotime($booking_end_date);
		$check_out_final_date = date("Y-m-d H:i:s", $check_out_timestamp);

		// check hotel room is already booked or not
		$isRoomBooked = $GLOBALS['dbconnection']->query("SELECT id FROM hotel_rooms WHERE room_booked_status=1 AND id='".$hotel_room_id."'");
		$isRoomBooked = $isRoomBooked->fetch_assoc();
		// ends here ~ check hotel room is already booked or not

		if($isRoomBooked == NULL) {
			// query for insert data to book hotel room
			$query = "INSERT INTO room_booking_details (hotel_room_id, check_in_date, check_out_date, reserved_user_id, totalAdult, totalChildren, hotel_location, room_booked_status) VALUES ('".$hotel_room_id."', '". $check_in_final_date."', '". $check_out_final_date."', '". $loggedInUserId."', '". $adult."', '". $children."', '". $location."', 1)";
			$serverResp = $GLOBALS['dbconnection']->query($query);
			// ends here ~ query for insert data to book hotel room
			if($serverResp == 1) {
				// get last book room id 
				$query = "SELECT id FROM room_booking_details ORDER BY ID DESC LIMIT 1";
				$lastRoomBookId = $GLOBALS['dbconnection']->query($query);
				$lastRoomBookId = $lastRoomBookId->fetch_assoc();
				$lastRoomBookId = $lastRoomBookId['id'];
				// ends here ~ get last book room id 

				// update hotel rooms booking details
				$query = "UPDATE hotel_rooms SET room_booked_status=1, check_in_date='". $check_in_final_date."', check_out_date='". $check_out_final_date."', reserved_user_id='". $loggedInUserId."', room_booking_table_id='". $lastRoomBookId."' WHERE id='".$hotel_room_id."'";
				$serverResp = $GLOBALS['dbconnection']->query($query);
				// ends here ~ update hotel rooms booking details

				// return response to ajax on success
				$ajaxReturn = array("status" => 200,"messageType" => "success", "data" => '',"responseType" => "success","message" => "Hotel Room Successfully Book");
				echo json_encode($ajaxReturn);
				// ends here ~ return response to ajax on success
			} else {
				// return response to ajax on success
				$ajaxReturn = array("status" => 200,"messageType" => "error", "data" => '',"responseType" => "success","message" => "Sorry! This Hotel Room is Already Booked");
				echo json_encode($ajaxReturn);
				// ends here ~ return response to ajax on success
			}
		} else {
			// return response to ajax on success
			$ajaxReturn = array("status" => 200,"messageType" => "error", "data" => '',"responseType" => "success","message" => "Sorry! This Hotel Room is Already Booked");
			echo json_encode($ajaxReturn);
			// ends here ~ return response to ajax on success
		}

	}
	// ends here ~ function for book hotels

	// function for get booked hotel room
	function getBookedHotelRoom() {
		$hotelUserId = $_SESSION['hotel_user_id'];

		// query for get booked hotel details from hotel_rooms table
		$query = "SELECT * FROM hotel_rooms WHERE reserved_user_id='".$hotelUserId."'";
		$filterBookedHotelRooms = $GLOBALS['dbconnection']->query($query);
		// ends here ~ query for get booked hotel details from hotel_rooms table

		if(mysqli_num_rows($filterBookedHotelRooms) == 0) {
			$ajaxReturn = array("status" => 200,"messageType" => "error", "data" => "null","responseType" => "success","message" => "No Booking Found with Current User Id");
			echo json_encode($ajaxReturn);
		} else {
			// if database have booking with current user id
			// ##################################################

			$finalRoomBookingDetails = [];

			while($row = $filterBookedHotelRooms->fetch_assoc() ){
				$currentRoomId = $row['id'];
				// query for get booked hotel details from room-booking_details table
				$query = "SELECT * FROM room_booking_details WHERE hotel_room_id='".$currentRoomId."'";
				$filterRoomBooking = $GLOBALS['dbconnection']->query($query);
				$filterRoomBooking = $filterRoomBooking->fetch_assoc();
				// ends here ~ query for get booked hotel details from room_booking_details table

				// set values into required variable
				$row['payment_id'] = $filterRoomBooking['payment_id'];
				$row['payment_status'] = $filterRoomBooking['payment_status'];
				$row['totalAdult'] = $filterRoomBooking['totalAdult'];
				$row['totalChildren'] = $filterRoomBooking['totalChildren'];
				$row['hotel_location'] = $filterRoomBooking['hotel_location'];
				// ends here ~ set values into required variable

				// push data to array
				array_push($finalRoomBookingDetails, $row);
				// ends here ~ push data to array

			}

			// return hotel rooms array
			$ajaxReturn = array("status" => 200,"messageType" => "success", "data" => $finalRoomBookingDetails,"responseType" => "success","message" => "Booked Hotel Room Searching Process is Completed Now");
			echo json_encode($ajaxReturn);
			// ends here ~ return hotel rooms array

			// ##################################################
			// ends here ~ if database have booking with current user id
		}

	}
	// ends here ~ function for get booked hotel room

	// function for delete hotel
	function deleteHotel() {
		$hotel_id = $_POST['hotel_id'];

		// query for delete hotel 
		$query = "DELETE FROM hotels WHERE id='".$hotel_id."'";
		$serverResp = $GLOBALS['dbconnection']->query($query);
		// ends here ~ query for delete hotel

		if($serverResp == 1) {
			// return hotel rooms array
			$ajaxReturn = array("status" => 200,"messageType" => "success", "data" => null,"responseType" => "success","message" => "Delete Hotel Data is Successfully");
			echo json_encode($ajaxReturn);
			// ends here ~ return hotel rooms array	
		} else {
			// return hotel rooms array
			$ajaxReturn = array("status" => 200,"messageType" => "error", "data" => null,"responseType" => "success","message" => "Error while Deleting Hotel");
			echo json_encode($ajaxReturn);
			// ends here ~ return hotel rooms array
		}

		
	}
	// ends here ~ function for delete hotel

	// code for eval function and data
	if(isset($_POST['functionName']) && $_POST['functionName'] != '') {
		$functionName = ($_POST['functionName']);
		eval($functionName);	
	}
	// ends here ~ code for eval function and data
?>