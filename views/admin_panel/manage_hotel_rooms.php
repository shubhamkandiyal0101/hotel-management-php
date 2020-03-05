
    <title>Manage Hotel Rooms | Hotel Admin | Himachal Stay</title>
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
    require_once($_SERVER['DOCUMENT_ROOT']."/process/ajax.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/header.php");
    session_start();
    $hotel_admin_id = $_SESSION['hotel_admin_id'];
    if($_SESSION['hotel_admin_id'] == NULL) {
      header("Location: /login-hotel-admin");
      die();
    }
    $currentHotelData = getSingleHotelDetails($hotelId,$hotel_admin_id);
    if($currentHotelData == NULL) {
      header("Location: /admin/dashboard");
      die();
    }
    $allRooms = hotelAllRoomsDetails($hotelId);
    

    // print_r(count($allRooms));
?>
<!-- ends here ~ include header -->

<body>

    <!-- include menu -->
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/views/admin_panel/sub_views/navbar.php"); ?>
    <!-- ends here ~ include menu -->

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg">
        <h3>Manage Hotel Rooms</h3>
        <h5>Hotel Admin</h5>
    </div>
    <!-- bradcam_area_end -->

    <!-- body content -->
    <?php if(count($allRooms) == 0): ?>
      <div class="col-md-12" style="padding: 50px; text-align: center;">
        <h1>We are not able to Found any Rooms for <font style="color: orange;"><?php echo($currentHotelData['hotel_name']);?></font> Hotel </h1>
        <h1><button type="button" class="btn btn-warning" onclick="openAddRoomPopup('create')">Click Here!</button> to Add Room </h1>
      </div>
    <?php else: ?>
        <!-- add room button -->
        <button type="button" class="btn btn-warning" onclick="openAddRoomPopup('create')" style="position: fixed;z-index: 99;right: 5px;">Add Room</button>
        <!-- ends here ~ add room button -->

        <?php foreach($allRooms as $rows): ?>
            <div class="row hotelList">
              <div class="col-md-4 partition">
                <h3 class="textCenter">Basic Room Details</h3>
                <ul>
                  <li><b>Room Number:</b> <?php echo($rows['room_number']); ?></li>
                  <li><b>Room Type:</b> <?php echo($rows['room_type']); ?></li>
                  <li><b>Room Price:</b> <?php echo($rows['room_price']); ?> Rs.</li>
                </ul>
              </div>
              <div class="col-md-3 partition textCenter">
                <h3 class="textCenter">Basic Room Details</h3>
                <?php if($rows['room_booked_status'] == 1): ?>
                  <button class="btn btn-warning">Booked</button>
                <?php else: ?>
                  <button class="btn btn-danger">Not Booked</button>
                <?php endif; ?>
              </div>
              <div class="col-md-5 partition textCenter">
                <h3>Actions</h3>
                

                <?php if($rows['room_booked_status'] == 1): ?>
                  <button class="btn btn-primary" onclick="openAddRoomPopup('edit','<?php echo($rows['room_number']); ?>','<?php echo($rows['room_type']); ?>','<?php echo($rows['room_price']); ?>','<?php echo($rows['id']); ?>','<?php echo($rows['room_details']); ?>')" disabled="true">Edit Details</button>
                  <button class="btn btn-danger" onclick="deleteHotelRoomFn('<?php echo($rows['id']); ?>','<?php echo($hotelId); ?>')" disabled="true">Delete Room</button>
                <?php else: ?>
                  <button class="btn btn-primary" onclick="openAddRoomPopup('edit','<?php echo($rows['room_number']); ?>','<?php echo($rows['room_type']); ?>','<?php echo($rows['room_price']); ?>','<?php echo($rows['id']); ?>','<?php echo($rows['room_details']); ?>')">Edit Details</button>
                  <button class="btn btn-danger" onclick="deleteHotelRoomFn('<?php echo($rows['id']); ?>','<?php echo($hotelId); ?>')">Delete Room</button>
                <?php endif; ?>

              </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>


    
    
    <!-- ends here ~ body content -->

    <!-- popup for add hotel rooms -->
    <div class="modal fade" id="addHotelRoomModal" role="dialog">
      <div class="modal-dialog">
      
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><font id="addHotelRoomModalTitle"></font></h4>
          </div>
          <div class="modal-body">
            <!-- form for add rooms -->
              <form autocomplete="off" id="addHotelRoomForm">
                  <div class="popup_inner">
                      <form>
                          <div class="row">
                              <input name="hotelRoomId" style="display: none;" />
                              <div class="col-xl-12">
                                  <label>Room Number*</label>
                                  <input type="text" class="nice-select form-select wide open" placeholder="123*" name="hotelRoomNumber" />
                              </div>
                              <div class="col-xl-12">
                                  <label>Room Type*</label>
                                  <input type="text" class="nice-select form-select wide open" placeholder="Luxury OR Delux Etc." name="roomType" />
                              </div>
                              <div class="col-xl-12">
                                  <label>Room Price*</label>
                                  <input type="number" class="nice-select form-select wide open" placeholder="Room Price (IN INR)*" name="roomPrice" />
                              </div>
                              <div class="col-xl-12">
                                  <label>Room Details/Features* (Seperate By Comma(,))(Max Features Allowed: 7)</label>
                                  <input type="text" name="roomDetails">

                              </div>
                          </div>
                      </form>
                  </div>
              </form>
            <!-- ends here ~ form for add rooms -->
          </div>
          <div class="modal-footer modalFooter">
            <button type="button" class="btn btn-warning" onclick="saveRoomDetails(<?php echo($hotelId); ?>, 'create')" id="saveRoomDetailsBtnId">Save</button>
            <button type="button" class="btn btn-warning" onclick="saveRoomDetails(<?php echo($hotelId); ?>, 'edit')" id="editRoomDetailsBtnId">Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
        
      </div>
    </div>
    <!-- ends here ~ popup for add hotel rooms -->

<!-- include footer files -->
<?php 
  require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/footer.php")
?>
<!-- ends here ~ include footer files -->

<script type="text/javascript">
  // on load tagify js
  let emailTagsInput = document.querySelector('input[name=roomDetails]');
  let emailTagifyData = new Tagify(emailTagsInput,{
    pattern: /^([a-zA-Z0-9 _-]+)$/
  });
  emailTagifyData.settings.maxTags=7;
  // ends here ~ on load tagify js 

  function openAddRoomPopup(mode, room_number_par, room_type_par, room_price_par, room_id_par, room_details_par) {
      $("[name='hotelRoomNumber']").val('');
      $("[name='roomType']").val('');
      $("[name='roomPrice']").val('');
      emailTagifyData.removeAllTags()

    if(mode == 'create') {
      $("#addHotelRoomModalTitle").html('Add Room');
      $("#saveRoomDetailsBtnId")[0].style.display = 'block';
      $("#editRoomDetailsBtnId")[0].style.display = 'none';
    } else if(mode == 'edit') {
      $("#addHotelRoomModalTitle").html('Edit Room');
      $("#saveRoomDetailsBtnId")[0].style.display = 'none';
      $("#editRoomDetailsBtnId")[0].style.display = 'block';

      $("[name='hotelRoomNumber']").val(room_number_par);
      $("[name='roomType']").val(room_type_par);
      $("[name='roomPrice']").val(parseInt(room_price_par));
      $("[name='hotelRoomId']").val(parseInt(room_id_par));
      
      emailTagifyData.addTags(room_details_par.split(','))
    }
    $('#addHotelRoomModal').modal('show');

  }

</script>

</body>
</html>
