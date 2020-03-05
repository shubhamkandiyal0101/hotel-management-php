// define all required regex
let spaceRegex = /\s+/g;
// ends here ~ define all required regex

// singup function hotel user
function signupHotelUserFn() {
    let formObject = {}
    $("#hotelUserSignupForm").serializeArray().map(function(x){formObject[x.name] = x.value;})
    formObject['district'] = $('#districtOption li.selected').text()
    formObject['user_verify'] = 0
    formObject['user_type'] = 1
    let ajaxData = formObject
    $.ajax({
         type: "POST",
         url: 'process/ajax.php',
         data: {functionName: 'signupHotelUser();', ...ajaxData},
         success: function (resp) {
            if (resp != null && resp != '') {
               let respData = JSON.parse(resp);
               if (respData.status = 200) {
                  if(respData.messageType == "success") {
                     let userRespData = respData.data;
                     setTimeout(function(){
                     	window.location.href = '/login-hotel-admin';
                     },2000)
                     toastr.success(respData.message);
                  } else if(respData.messageType == "error") {
                     toastr.error(respData.message);
                  }
               }
            }
         }
    });
}
// ends here ~ signup function for hotel user

// login function hotel user
function loginHotelUserFn() {
	let formObject = {}
    $("#hotelUserLoginForm").serializeArray().map(function(x){formObject[x.name] = x.value;})
    let ajaxData = formObject
    $.ajax({
         type: "POST",
         url: 'process/ajax.php',
         data: {functionName: 'loginHotelUser();', ...ajaxData},
         success: function (resp) {
         	console.log(resp)
            if (resp != null && resp != '') {
               let respData = JSON.parse(resp);
               if (respData.status = 200) {
                  if(respData.messageType == "success") {
                     let userRespData = respData.data;
                     setTimeout(function(){
                     	window.location.href = '/admin/dashboard';
                     },2000)
                     toastr.success(respData.message);
                  } else if(respData.messageType == "error") {
                     toastr.error(respData.message);
                  }
               }
            }
         }
    });
}
// ends here ~  login function hotel user

// function for logout hotel admin user
function logoutAdminUser() {
	$.ajax({
         type: "POST",
         url: '/process/ajax.php',
         data: {functionName: 'logoutHotelUser();'},
         success: function (resp) {
            if (resp != null && resp != '') {
             	window.location.href = '/login-hotel-admin';
            }
         }
    });
}
// ends here ~ function for logout hotel admin 

// function for logout hotel admin user
function logoutNormalUserFn() {
    $.ajax({
         type: "POST",
         url: '/process/ajax.php',
         data: {functionName: 'logoutNormalUser();'},
         success: function (resp) {
            if (resp != null && resp != '') {
                 window.location.href = '/login-user';
            }
         }
    });
}
// ends here ~ function for logout hotel admin 


// function for add hotel
function addHotelFn() {
	let formObject = {}
    $("#addHotelForm").serializeArray().map(function(x){formObject[x.name] = x.value;})
    formObject['hotelDescription'] = CKEDITOR.instances.hotelDescription.getData()

	if (formObject['hotelName'] == '') {
		toastr.error('Hotel name is Required');
	} else if (formObject['hotelAddr'] == '') {
		toastr.error('Hotel Address is Required');
	} else if (formObject['hotelCity'] == '') {
		toastr.error('Hotel City is Required');
	} else if (formObject['district'] == '') {
		toastr.error('Hotel District is Required');
	} else if (formObject['hotelDescription'].length < 500) {
		toastr.error('Hotel Discription required minimum 500 Characters');
	} else if (formObject['hotelDescription'].length > 1000) {
		toastr.error('Hotel Discription must not be exceeds 1000 Characters');
	} else if ($('#hotelImage').prop('files').length == 0) {
		toastr.error('Image is Required');
	} else {

		// if form is valid
		let imgType = $('#hotelImage').prop('files')[0].type;

		if(imgType != 'image/png' && imgType != 'image/jpeg' && imgType != 'image/jpg') {
			toastr.error('Error in Image Field. Image Allowed Extenstions: <b>PNG</b>, <b>JPG</b>, <b>JPEG</b>')
		} else {

			// check image validation
			let imgSize = $('#hotelImage').prop('files')[0].size/1000; 
			if(imgSize < 500) {
				// if image is valid ~ code for send data to backend
				let fileData = $('#hotelImage').prop('files')[0];   
			    let formData = new FormData();                  
			    formData.append('file', fileData);
			    formData.append('otherInfo',JSON.stringify(formObject));
			    formData.append('functionName', 'hotelRegistration();');

			    // ajax call for save user info data to db for verification
			    jQuery.ajax({
				    type: "POST",
				    url: '/process/ajax.php',
					   	contentType: false,
					processData: false,
					cache: false,
				    data: formData,
				    success: function (resp) {
				    	let respData = JSON.parse(resp);
				    	if (respData.status = 200) {
				    		if(respData.messageType == "success") {
				    			toastr.success(respData.message);
				    			setTimeout(function(){
			                     	window.location.href = '/admin/hotel-details';
			                     },2000)
				    		} else if(respData.messageType == "error") {
				    			toastr.error(respData.message);
				    		}
				    	} 
		            }
				});
				// ends here ~ ajax call for save user info data to db for verification

				// ends here ~ if image is valid ~ code for send data to backend
			} else {
				toastr.error('Image must be smaller than 500 KB');	
			}
			// ends here ~ if form is valid
		}
		// ends here ~ if form is valid
	}

}
// ends here ~ function for add hotel

// function for save hotel room details
function saveRoomDetails(hotelId, mode) {
	let formObject = {}
    $("#addHotelRoomForm").serializeArray().map(function(x){formObject[x.name] = x.value;})

    
    if (formObject['hotelRoomNumber'].trim() == '') {
		toastr.error('Hotel Room Number is Required');	
    } else if (spaceRegex.test(formObject['hotelRoomNumber'])) {
		toastr.error('Space Not Allowed in Room Number');	
    } else if (formObject['roomDetails'].trim() == '') {
        toastr.error('Hotel Details is Required');    
    } else if (formObject['roomPrice'].trim() == '') {
        toastr.error('Hotel Price is Required');    
    } else if (formObject['roomType'].trim() == '') {
        toastr.error('Hotel Type is Required');    
    } else {
        // if all validation passed
        formObject['hotel_id'] =  hotelId;
        let dynamicFunctionName = '';
        // ends here ~ if all validation passed

        // set dynamic php function name
        if(mode == 'create') {
            dynamicFunctionName = 'saveHotelRooms();';
        } else if(mode == 'edit') {
            dynamicFunctionName = 'updateHotelRoomDetails();';
        }
        // ends here ~ set dynamic php function name

        // room details string
        let roomDetailsArr = JSON.parse(formObject['roomDetails']);
        let roomDetailsArrStr = ''
        for(let i=0; i<roomDetailsArr.length; i++) {
            let featureVal = roomDetailsArr[i]['value'];
            if(i !=  roomDetailsArr.length-1) {
                roomDetailsArrStr+=featureVal.toString()+',';    
            } else {
                roomDetailsArrStr+=featureVal.toString();
            }
        }
        // ends here ~ room details string

        formObject['roomDetails'] = roomDetailsArrStr;
    
        let ajaxData = formObject;
        // ajax request
        $.ajax({
             type: "POST",
             url: '/process/ajax.php',
             data: {functionName: dynamicFunctionName, ...ajaxData},
             success: function (resp) {
                if (resp != null && resp != '') {
                   let respData = JSON.parse(resp);
                   if (respData.status = 200) {
                      if(respData.messageType == "success") {
                         let userRespData = respData.data;
                         setTimeout(function(){
                             let newLocation = '/admin/manage-hotel-rooms/'+hotelId.toString();
                             window.location.href = newLocation;
                         },2000)
                         toastr.success(respData.message);
                      } else if(respData.messageType == "error") {
                         toastr.error(respData.message);
                      }
                   }
                }
             }
        });
        // ends here ~ ajax request

    }

}
// ends here ~ function for save hotel room details

// function for delete hotel room
function deleteHotelRoomFn(hotelRoomId,hotelId) {
    let ajaxData = {'hotelRoomId': hotelRoomId};
    // ajax request
    $.ajax({
         type: "POST",
         url: '/process/ajax.php',
         data: {functionName: 'deleteHotelRoom();', ...ajaxData},
         success: function (resp) {
            if (resp != null && resp != '') {
               let respData = JSON.parse(resp);
               if (respData.status = 200) {
                  if(respData.messageType == "success") {
                     let userRespData = respData.data;
                     setTimeout(function(){
                         let newLocation = '/admin/manage-hotel-rooms/'+hotelId.toString();
                         window.location.href = newLocation;
                     },2000)
                     toastr.success(respData.message);
                  } else if(respData.messageType == "error") {
                     toastr.error(respData.message);
                  }
               }
            }
         }
    });
    // ends here ~ ajax request
}
// ends here ~ function for delete hotel room

// singup function hotel user
function signupNormalUserFn() {
    let formObject = {}
    $("#normalUserSignupForm").serializeArray().map(function(x){formObject[x.name] = x.value;})
    formObject['district'] = $('#districtOption li.selected').text()
    formObject['user_verify'] = 0
    formObject['user_type'] = 0
    let ajaxData = formObject
    $.ajax({
         type: "POST",
         url: '/process/ajax.php',
         data: {functionName: 'signupNormalUser();', ...ajaxData},
         success: function (resp) {
            if (resp != null && resp != '') {
               let respData = JSON.parse(resp);
               if (respData.status = 200) {
                  if(respData.messageType == "success") {
                     let userRespData = respData.data;
                     setTimeout(function(){
                         window.location.href = '/login-user';
                     },2000)
                     toastr.success(respData.message);
                  } else if(respData.messageType == "error") {
                     toastr.error(respData.message);
                  }
               }
            }
         }
    });
}
// ends here ~ signup function for hotel user

// login function hotel user
function normalHotelUserFn() {
    let formObject = {}
    $("#normalUserLoginForm").serializeArray().map(function(x){formObject[x.name] = x.value;})
    let ajaxData = formObject
    $.ajax({
         type: "POST",
         url: '/process/ajax.php',
         data: {functionName: 'loginNormalUser();', ...ajaxData},
         success: function (resp) {
             console.log(resp)
            if (resp != null && resp != '') {
               let respData = JSON.parse(resp);
               if (respData.status = 200) {
                  if(respData.messageType == "success") {
                     let userRespData = respData.data;
                     setTimeout(function(){
                         window.location.href = '/user/dashboard';
                     },2000)
                     toastr.success(respData.message);
                  } else if(respData.messageType == "error") {
                     toastr.error(respData.message);
                  }
               }
            }
         }
    });
}
// ends here ~  login function hotel user

// function for search hotel rooms
var globalRoomSearchFormData = [];
function searchHotelRoomsFn() {
    let formObject = {}
    $("#searchHotelRoom").serializeArray().map(function(x){formObject[x.name] = x.value;})

    if (formObject['booking_start_date'] == '') {
        toastr.error('Booking Start Date is Required');    
    } else if (formObject['booking_end_date'] == '') {
        toastr.error('Booking End Date is Required');    
    } else {
       let ajaxData = formObject;

       //ajax call
       $.ajax({
         type: "POST",
         url: '/process/ajax.php',
         data: {functionName: 'searchHotelRooms();', ...ajaxData},
         success: function (resp) {
            if (resp != null && resp != '') {
               let respData = JSON.parse(resp);
               if (respData.status = 200) {
                  if(respData.messageType == "success") {
                     let hotelRespData = respData.data;
                     globalRoomSearchFormData = ajaxData;
                     toastr.success(respData.message);
                     displayHotelRoomsData(hotelRespData);
                  } else if(respData.messageType == "error") {
                     displayHotelRoomsData('');
                     toastr.error(respData.message);
                  }
               }
            }
         }
        });
       // end shere ~ ajax call 
    }
}
// ends here ~ function for search hotel rooms

// function for book hotel room
function bookHotelRoomFn(dataVal) {
    // if(dataVal.room_booked_status == 1) {
    //     toastr.error("This Hotel Room is not Available for Booking. Hotel Room is Already Booked.");
    //     return false;
    // }
    let formData = globalRoomSearchFormData;
    formData['hotel_room_id'] = dataVal.id;

    let ajaxData = formData;

    // ajax call for book hotel room
    $.ajax({
        type: "POST",
        url: '/process/ajax.php',
        data: {functionName: 'bookHotelRoom();', ...ajaxData},
        success: function (resp) {
          console.log(resp);
            if (resp != null && resp != '') {
               let respData = JSON.parse(resp);
               if (respData.status = 200) {
                  if(respData.messageType == "success") {
                     toastr.success(respData.message);
                  } else if(respData.messageType == "error") {
                     toastr.error(respData.message);
                  }
               }
            }
        }
    });
    // ends here ~ ajax call for book hotel room 
}
// ends here ~ function for book hotel room

// function for delete hotel 
function deleteHotelFn(hotelId) {
    let formData = {'hotel_id':hotelId};
    let ajaxData = formData;

    // ajax call for book hotel room
    $.ajax({
        type: "POST",
        url: '/process/ajax.php',
        data: {functionName: 'deleteHotel();', ...ajaxData},
        success: function (resp) {
            if (resp != null && resp != '') {
               let respData = JSON.parse(resp);
               if (respData.status = 200) {
                  if(respData.messageType == "success") {
                     toastr.success(respData.message);
                     window.location.href = '/admin/hotel-details';
                  } else if(respData.messageType == "error") {
                     toastr.error(respData.message);
                  }
               }
            }
        }
    });
    // ends here ~ ajax call for book hotel room 
}
// ends here ~ function for delete hotel


// function for edit hotel
function editHotelFn() {
  let formObject = {}
    $("#addHotelForm").serializeArray().map(function(x){formObject[x.name] = x.value;})
    formObject['hotelDescription'] = CKEDITOR.instances.hotelDescription.getData()
  if (formObject['hotelName'] == '') {
    toastr.error('Hotel name is Required');
  } else if (formObject['hotelAddr'] == '') {
    toastr.error('Hotel Address is Required');
  } else if (formObject['hotelCity'] == '') {
    toastr.error('Hotel City is Required');
  } else if (formObject['district'] == '') {
    toastr.error('Hotel District is Required');
  } else if (formObject['hotelDescription'].length < 500) {
    toastr.error('Hotel Discription required minimum 500 Characters');
  } else if (formObject['hotelDescription'].length > 1000) {
    toastr.error('Hotel Discription must not be exceeds 1000 Characters');
  } else {

    // if form is valid
    let imgType = '';
    if($('#hotelImage').prop('files').length != 0) {
      let imgType = $('#hotelImage').prop('files')[0].type;
        if(imgType != 'image/png' && imgType != 'image/jpeg' && imgType != 'image/jpg') {
          toastr.error('Error in Image Field. Image Allowed Extenstions: <b>PNG</b>, <b>JPG</b>, <b>JPEG</b>')
        }
    } 
          let formData = new FormData();   
          let fileData = ''               

          if($('#hotelImage').prop('files').length != 0) {

            let imgSize = $('#hotelImage').prop('files')[0].size/1000; 
            if(imgSize < 500) {
              // if image is valid ~ code for send data to backend
              let fileData = $('#hotelImage').prop('files')[0];
              formData.append('file', fileData);   
            }  else {
              toastr.error('Image must be smaller than 500 KB');
              return false;  
            }
            
          }

          formData.append('otherInfo',JSON.stringify(formObject));
          formData.append('functionName', 'editHotelRegistration();');

          // ajax call for save user info data to db for verification
          jQuery.ajax({
            type: "POST",
            url: '/process/ajax.php',
               contentType: false,
          processData: false,
          cache: false,
            data: formData,
            success: function (resp) {
              let respData = JSON.parse(resp);
              if (respData.status = 200) {
                if(respData.messageType == "success") {
                  toastr.success(respData.message);
                  setTimeout(function(){
                             window.location.href = '/admin/hotel-details';
                           },2000)
                } else if(respData.messageType == "error") {
                  toastr.error(respData.message);
                }
              } 
                }
        });
        // ends here ~ ajax call for save user info data to db for verification

        // ends here ~ if image is valid ~ code for send data to backend
      }
      // ends here ~ if form is valid
    // ends here ~ if form is valid

}
// ends here ~ function for edit hotel