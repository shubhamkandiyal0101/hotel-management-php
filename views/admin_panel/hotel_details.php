
    <title>Hotel Details | Hotel Admin | Himachal Stay</title>
</head>
<!-- include header -->

<?php 
    require_once($_SERVER['DOCUMENT_ROOT']."/process/ajax.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/header.php");
    session_start();
    if($_SESSION['hotel_admin_id'] == NULL) {
      header("Location: /login-hotel-admin");
      die();
    }
    $hotel_admin_id = $_SESSION['hotel_admin_id'];
    $hotelDetailsArr = getAdminHotelDetails();
?>
<!-- ends here ~ include header -->

<body>

    <!-- include menu -->
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/views/admin_panel/sub_views/navbar.php"); ?>
    <!-- ends here ~ include menu -->

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg">
        <h3>Hotel Details</h3>
        <h5>Hotel Admin</h5>
    </div>
    <!-- bradcam_area_end -->

    <!-- body content -->
    <?php foreach($hotelDetailsArr as $rows): ?>
        <div class="row hotelList">
          <div class="col-md-3 partition">
            <img src="<?php echo('/uploadImg/'.$rows['hotel_image'])?>" style="max-width: 300px;" class="elementCenter">
          </div>
          <div class="col-md-4 partition">
            <h3 class="textCenter">Hotel Detials</h3>
            <ul>
              <li><b>Hotel Name:</b> <?php echo($rows['hotel_name']); ?></li>
              <li><b>Hotel Address:</b> <?php echo($rows['hotel_addr']); ?></li>
              <li><b>Hotel City:</b> <?php echo($rows['hotel_city']); ?></li>
              <li><b>Hotel District:</b> <?php echo($rows['hotel_district']); ?></li>
              <li><b>Hotel State:</b> <?php echo($rows['hotel_state']); ?></li>
            </ul>
          </div>
          <div class="col-md-5 partition textCenter">
            <h3>Actions</h3>

            <!-- @author Rachin - new code -->
            <a class="btn btn-warning" href="<?php echo('/admin/edit-hotel/'.$rows['id'])?>">Edit Details</a>
            <!-- ends here ~ @author Rachin - new code -->
            
            <a class="btn btn-primary" href="<?php echo('/admin/manage-hotel-rooms/'.$rows['id'])?>">Manage Hotel Rooms</a>

            <?php if($rows['rooms_available'] == 1): ?>
              <button class="btn btn-danger" disabled="true">Delete Hotel</button>
            <?php else: ?>
              <button class="btn btn-danger" onclick="deleteHotelFn(<?php echo($rows['id']); ?>)">Delete Hotel</button>
            <?php endif; ?>


            
          </div>
        </div>
    <?php endforeach; ?>
    
    <!-- ends here ~ body content -->


<!-- include footer files -->
<?php 
  require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/footer.php")
?>
<!-- ends here ~ include footer files -->


</body>
</html>
