
    <title>Booking Details  | User Dashboard | Himachal Stay</title>
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
   // ends here ~ get all district of himachal from db

?>
<!-- ends here ~ include header -->

<body>

    <!-- include menu -->
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/views/user_dashboard/sub_views/navbar.php"); ?>
    <!-- ends here ~ include menu -->

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg">
        <h3>User Dashboard</h3>
        <h5>Hotel Room Booking Details</h5>
    </div>
    <!-- bradcam_area_end -->

    <div class="col-md-12" style="padding: 50px; text-align: center;">
      <!-- list of all hotel rooms -->
      <div class="hotel_rooms_list">
        <table id="hotelRoomBookingDetails" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Hotel City</th>
                    <th>Room Type</th>
                    <th>Room Price</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Total Adult</th>
                    <th>Total Children</th>
                  </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Room Number</th>
                    <th>Hotel City</th>
                    <th>Room Type</th>
                    <th>Room Price</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Total Adult</th>
                    <th>Total Children</th>
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


  // initialize datatable
  let hotelRoomsTable = $('#hotelRoomBookingDetails').dataTable( {
      "columns": [
          { "data": "room_number" },
          { "data": "hotel_location" },
          { "data": "room_type" },
          { "data": "room_price" },
          { "data": "check_in_date" },
          { "data": "check_out_date" },
          { "data": "totalAdult" },
          { "data": "totalChildren" }
      ]
  })
  // ends here ~  initialize datatable

  // function for display hotel rooms data
  function displayBookedHotelRoomsData(dataResp) {
    if(dataResp == '' || dataResp == 'null') {
      hotelRoomsTable.fnClearTable();
    } else {
      hotelRoomArray = dataResp;
      hotelRoomsTable.fnClearTable();
      hotelRoomsTable.fnAddData(dataResp);
    }
  }
  // ends here ~ function for display hotel rooms data

  // get booked hotel rooms details
  function getBookedHotelRoomFn() {
      // ajax call for book hotel room
      $.ajax({
          type: "POST",
          url: '/process/ajax.php',
          data: {functionName: 'getBookedHotelRoom();'},
          success: function (resp) {
            console.log('resp >> ',resp)
              if (resp != null && resp != '') {
                 let respData = JSON.parse(resp);
                 if (respData.status = 200) {
                    if(respData.messageType == "success") {
                       toastr.success(respData.message);
                       displayBookedHotelRoomsData(respData.data);
                    } else if(respData.messageType == "error") {
                       toastr.error(respData.message);
                    }
                 }
              }
          }
      });
  }
  getBookedHotelRoomFn();
  // ends here ~ get booked hotel details

</script>
</html>
