
    <title>Dashboard | Hotel Admin | Himachal Stay</title>
</head>
<!-- include header -->

<?php 
   require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/header.php");
   require_once($_SERVER['DOCUMENT_ROOT']."/process/ajax.php");

   session_start();
   	if($_SESSION['hotel_admin_id'] == NULL) {
      header("Location: /login-hotel-admin");
      die();
    }
    $hotel_admin_id = $_SESSION['hotel_admin_id'];
    $userDetailsObj = getUserDetails($hotel_admin_id);


?>
<!-- ends here ~ include header -->

<body>

    <!-- include menu -->
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/views/admin_panel/sub_views/navbar.php"); ?>
    <!-- ends here ~ include menu -->

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg">
        <h3>Dashboard</h3>
        <h5>Hotel Admin</h5>
    </div>
    <!-- bradcam_area_end -->

    <div class="col-md-12" style="padding: 50px; text-align: center;">
      <h1>Hi, <?php echo($userDetailsObj['firstname']); ?> <?php echo($userDetailsObj['lastname']); ?> </h1>
      <h1><a href="/admin/hotel-details">Click Here!</a> to Manage Hotels</h1>
    </div>

<!-- include footer files -->
<?php 
  require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/footer.php")
?>
<!-- ends here ~ include footer files -->

</body>
</html>
