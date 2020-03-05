
    <title>Home | User Dashboard | Himachal Stay</title>
    <style type="text/css">
      .modal-backdrop {
        z-index: 9 !important;
      }
      .modal-backdrop.show {
        opacity: 0.1 !important;
      }
      .modalFooter {
        padding: 5px;
      }
    </style>
</head>
<!-- include header -->

<?php 
   require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/header.php");
   require_once($_SERVER['DOCUMENT_ROOT']."/process/ajax.php");

   session_start();
   	if($_SESSION['hotel_user_id'] == NULL) {
   		header("Location: /login-user");
		die();
   	}
    $hotel_user_id = $_SESSION['hotel_user_id'];
    require_once($_SERVER['DOCUMENT_ROOT']."/helpers/config.php");

   // get all district of himachal from db
   $GLOBALS['dbconnection'] = $dbconnection;
   $serverResp = $GLOBALS['dbconnection']->query("SELECT * FROM himachal_district");
   $cityList = [];
   while($row = $serverResp->fetch_assoc() ){
      array_push($cityList, $row);
   }
   // ends here ~ get all district of himachal from db

?>
<!-- ends here ~ include header -->

<body>

    <!-- include menu -->
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/views/user_dashboard/sub_views/navbar.php"); ?>
    <!-- ends here ~ include menu -->

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg">
        <h5>User Dashboard</h5>
    </div>
    <!-- bradcam_area_end -->

    <div class="col-md-12" style="padding: 50px; text-align: center;">
      <!-- form for search hotel for booking -->
      <form autocomplete="off" id="searchHotelRoom">
          <div class="row">
              <div class="col-xl-4 col-md-4">
                  <label>Booking Start Date*</label>
                  <input id="bookingStartDate" placeholder="Booking Start Date" name="booking_start_date" readonly="">
              </div>
              <div class="col-xl-4 col-md-4">
                  <label>Booking End Date*</label>
                  <div id="bookingEndDateDiv"></div>
              </div>
              <div class="col-xl-4 col-md-4">
                  <label>District*</label>
                  <select class="form-select wide" id="default-select" name="district">
                      <?php
                          foreach($cityList as $cityNames) {
                              echo "<option value='".$cityNames['district_val']."')'>".$cityNames['district_name']."</option>";
                          }
                      ?>
                  </select>
              </div>
              <div class="col-xl-6 col-md-6">
                  <label>Adult*</label>
                  <select class="form-select wide" id="default-select" name="adult">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                  </select>
              </div>
              <div class="col-xl-6 col-md-6">
                  <label>Children*</label>
                  <select class="form-select wide" id="default-select" name="children">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                  </select>
              </div>

              <!-- check out button -->
              <div class="col-xl-12" style="margin-top: 40px;">
                  <a class="boxed-btn3" onclick="searchHotelRoomsFn()">Search Hotels</a>
              </div>
              <!-- ends here ~ check out button -->

          </div>
      </form>
      <!-- ends here ~ form for search hotel for booking -->

      <!-- list of all hotel rooms -->
      <div class="hotel_rooms_list">
        <table id="hotelRoomsDataTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Hotel City</th>
                    <th>Room Type</th>
                    <th>Hotel Name</th>
                    <th>Room Price</th>
                    <th>More Details</th>
                    <th>Book Room</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Room Number</th>
                    <th>Hotel City</th>
                    <th>Room Type</th>
                    <th>Hotel Name</th>
                    <th>Room Price</th>
                    <th>More Details</th>
                    <th>Book Room</th>
                </tr>
            </tfoot>
        </table>
      </div>
      <!-- ends here ~ list of all hotel rooms -->

    </div>

    <!-- modal for view hotel details -->
    <div class="modal fade" id="viewHotelRoomDetails" role="dialog">
      <div class="modal-dialog">
      
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><font id="roomNumberWithHotelName"></font></h4>
          </div>
          <div class="modal-body">
            <!-- form for add rooms -->
            <div class="row" style="padding: 5px;">
                <table class="table table-striped">
                    <div id="modalHotelDivImg" class="col-md-12" style="margin-bottom: 20px;"></div>
                    <tr>
                      <th>Room Number</th>
                      <td><span id="modalRoomNumber"></span></td>
                    </tr>
                    <tr>
                      <th>Hotel City</th>
                      <td id="modalHotelCity"></td>
                    </tr>
                    <tr>
                      <th>Room Type</th>
                      <td id="modalRoomType"></td>
                    </tr>
                    <tr>
                      <th>Room Price</th>
                      <td id="modalRoomPrice"></td>
                    </tr>
                    <tr>
                      <th>Room Features</th>
                      <td id="modalRoomFeatures"></td>
                    </tr>
                    <tr>
                      <th>Hotel Name</th>
                      <td id="modalHotelName"></td>
                    </tr>
                    <tr>
                      <th>Hotel Description</th>
                      <td id="modalHotelDesc"></td>
                    </tr>
                    <tr>
                      <th>Hotel Address</th>
                      <td><div id="modalHotelAddress"></div></td>
                    </tr>
                    <tr>
                      <th>Hotel Pincode</th>
                      <td id="modalHotelPincode"></td>
                    </tr>
                    <tr>
                      <th>Room Status</th>
                      <td id="modalRoomBookStatus"></td>
                    </tr>
                </table>
            </div>
            <!-- ends here ~ form for add rooms -->
          </div>
          <div class="modal-footer modalFooter">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
        
      </div>
    </div>
    <!-- ends here ~ modal for view hotel details -->

<!-- include footer files -->
<?php 
  require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/footer.php")
?>
<!-- ends here ~ include footer files -->

</body>
<script type="text/javascript">
  let hotelRoomArray = [];
  $('#bookingStartDate').datepicker({
      minDate: new Date(),
      iconsLibrary: 'fontawesome',
      icons: {
       rightIcon: '<span class="fa fa-caret-down"></span>'
      }
  });
  

  function setEndDateFn(datePar) {
      $("#bookingEndDateDiv").html('');
      $("#bookingEndDateDiv").html(`<input id="bookingEndDate" placeholder="Booking End Date" name="booking_end_date" readonly="">`);
      $('#bookingEndDate').datepicker({
          minDate: datePar,
          iconsLibrary: 'fontawesome',
          icons: {
           rightIcon: '<span class="fa fa-caret-down"></span>'
          }
      });
  }
  // next day date
  function nextDayDate(datePar) {
    var tomorrowTemp = datePar;
    tomorrowTemp.setDate(tomorrowTemp.getDate() + 1);
    return tomorrowTemp;
  }
  // ends here ~ next day date
  setEndDateFn(nextDayDate(new Date()));
  $('#bookingStartDate').change(function(){
      let tempStartDate = $('#bookingStartDate').val();
      setEndDateFn(nextDayDate(new Date(tempStartDate)));
  });

  // initialize datatable
  let hotelRoomsTable = $('#hotelRoomsDataTable').dataTable( {
      "columns": [
          { "data": "room_number" },
          { "data": "hotel_city" },
          { "data": "room_type" },
          { "data": "hotel_name" },
          { "data": "room_price" },
          {"defaultContent": "<button class='btn btn-success view-details'>View Details</button>"},
          {"defaultContent": "<button class='btn btn-warning book-room'>Book Now</button>"},
      ],

  })
  // ends here ~  initialize datatable

  // function for display hotel rooms data
  function displayHotelRoomsData(dataResp) {
    if(dataResp == '') {
      hotelRoomsTable.fnClearTable();
    } else {
      hotelRoomArray = dataResp;
      hotelRoomsTable.fnClearTable();
      hotelRoomsTable.fnAddData(dataResp);
    }
  }
  // ends here ~ function for display hotel rooms data

  // button for view details
  $('#hotelRoomsDataTable tbody').on( 'click', 'button.view-details', function () {
      let currentHotelRow = hotelRoomsTable.api().row($(this).parents('tr')).data();
      console.log(currentHotelRow)

      
      // set image into modal
      $("#modalHotelDivImg").html(`<img src="/uploadImg/${currentHotelRow.hotel_image}" style="max-width: 300px;" class="elementCenter">`);
      // ends here ~ set image into modal

      // set value into field via id
      $("#roomNumberWithHotelName").text(currentHotelRow.room_number+' | '+currentHotelRow.hotel_name);
      $("#modalRoomNumber").text(currentHotelRow.room_number);
      $("#modalHotelCity").text(currentHotelRow.hotel_city);
      $("#modalRoomType").text(currentHotelRow.room_type);
      $("#modalRoomPrice").text(currentHotelRow.room_price+' INR');
      $("#modalRoomFeatures").text(currentHotelRow.room_details);
      $("#modalHotelName").text(currentHotelRow.hotel_name);
      $("#modalHotelDesc").html(currentHotelRow.hotel_desc);
      $("#modalHotelAddress").text(currentHotelRow.hotel_addr);
      $("#modalHotelPincode").text(currentHotelRow.hotel_pincode);
      if(currentHotelRow.room_booked_status == null || currentHotelRow.room_booked_status == '' || currentHotelRow.room_booked_status == undefined || currentHotelRow.room_booked_status == 0) {
        $("#modalRoomBookStatus").text('Available for Booking');
      } else {
        $("#modalRoomBookStatus").text('Not Available | Already Booked');
      }
      
      // ends here ~ set value into field via id

      // open popup
      $('#viewHotelRoomDetails').modal('show');
      // ends here ~ open popup

  });
  // ends here ~ button for view details

  // button for book hotel 
  $('#hotelRoomsDataTable tbody').on( 'click', 'button.book-room', function () {
      let currentRoomRow = hotelRoomsTable.api().row($(this).parents('tr')).data();
      bookHotelRoomFn(currentRoomRow);
  });
  // ends here ~ button for book hotel 

</script>
</html>
