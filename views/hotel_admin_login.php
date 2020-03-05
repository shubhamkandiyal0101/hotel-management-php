
    <title>Login | Himachal Stay</title>
</head>
<!-- include header -->

<?php 
   require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/header.php");
   require_once($_SERVER['DOCUMENT_ROOT']."/controllers/signup-login-controller.php");
   session_start();
    if($_SESSION['hotel_admin_id'] != NULL) {
        header("Location: /admin/dashboard");
        die();
    } else if($_SESSION['hotel_user_id'] != NULL) {
        header("Location: /user/dashboard");
        die();
    }

?>
<!-- ends here ~ include header -->

<body>

    <!-- include menu -->
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/navbar.php"); ?>
    <!-- ends here ~ include menu -->

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg">
        <h3>Login Account</h3>
        <h5>Hotel Admin</h5>
    </div>
    <!-- bradcam_area_end -->


    <!-- registration form-->
    <form autocomplete="off" id="hotelUserLoginForm">
        <div class="popup_box centerBlock">
            <div class="popup_inner">
                <form>
                    <div class="row">
                        
                        <div class="col-xl-12">
                            <label>Email*</label>
                            <input type="email" class="nice-select form-select wide open" placeholder="Email*" name="userEmail" />
                        </div>
                        <div class="col-xl-12">
                            <label>Password*</label>
                            <input type="password" class="nice-select form-select wide open" placeholder="Password*" name="userPassword" />
                        </div>
                        
                        <div class="col-xl-12">
                            <a class="boxed-btn3" onclick="loginHotelUserFn()">Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </form>
    <!-- ends here ~ registration form -->


<!-- include footer files -->
<?php 
  require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/footer.php")
?>
<!-- ends here ~ include footer files -->

</body>
</html>
