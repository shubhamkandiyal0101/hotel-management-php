
    <title>Registration | Himachal Stay</title>
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

   // get json file content
   $stateCityListUrl = $_SERVER['DOCUMENT_ROOT']."/assets/json/states-and-districts.json";
   $stateCityTextList = file_get_contents($stateCityListUrl);
   $stateCityList = json_decode($stateCityTextList, true);
   // print_r($stateCityList['states']);
   $stateCityList = $stateCityList['states'];
   // $stateCityList = $stateCityList->states;
   // ends here ~ get json file content
    // foreach($stateCityList as $state){
    //   print_r("Salary: $state<br>");
    // }
   // echo($stateCityList[0]);
   // for ($i = 0; $i < count($stateCityList); $i++) {
   //      print_r($stateCityList[$i]);
   //      echo("--------------------------------------------------------");
   //  }

   // die;

?>
<!-- ends here ~ include header -->

<body>

    <!-- include menu -->
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/navbar.php"); ?>
    <!-- ends here ~ include menu -->

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg">
        <h3>Register Account</h3>
        <h5>for Book Hotel</h5>
    </div>
    <!-- bradcam_area_end -->


    <!-- registration form-->
    <form autocomplete="off" id="normalUserSignupForm">
        <div class="popup_box centerBlock">
            <div class="popup_inner">
                <form>
                    <div class="row">
                        
                        <div class="col-xl-6">
                            <label>First Name*</label>
                            <input type="text" class="nice-select form-select wide open" placeholder="First Name*" name="firstname" />
                        </div>
                        <div class="col-xl-6">
                            <label>Last Name*</label>
                            <input type="text" class="nice-select form-select wide open" placeholder="Last Name*" name="lastname" />
                        </div>
                        <div class="col-xl-12">
                            <label>Email*</label>
                            <input type="email" class="nice-select form-select wide open" placeholder="Email*" name="userEmail" />
                        </div>
                        <div class="col-xl-12">
                            <label>Password*</label>
                            <input type="password" class="nice-select form-select wide open" placeholder="Password*" name="userPassword" />
                        </div>
                        <div class="col-xl-12">
                            <label>Gender*</label>
                            <select class="form-select wide" id="gender-select" name="gender">
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-xl-12">
                            <label>State*</label>
                            <select class="form-select wide" id="default-select" name="state" onchange="changeDistrict(this)">
                                <?php
                                    foreach($stateCityList as $stateNames) {
                                        echo "<option value='".$stateNames['state']."' onchange='changeDistrict('".$stateNames['state']."')'>".$stateNames['state']."</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-xl-12">
                            <label>District*</label>
                            <!-- <select class="form-select wide" id="default-select districtOption"></select> -->
                            <!-- <select id="districtOption"></select> -->
                            <div class="nice-select" tabindex="0"><span class="current"></span><ul class="list districtOption" id="districtOption" style="width: 100%;"></ul></div>

                        </div>

                        <div class="col-xl-12">
                            <label>DOB*</label>
                            <input id="datepickerdob" placeholder="Date of Birth" name="dob">
                        </div>

                        
                        <div class="col-xl-12">
                            <a class="boxed-btn3" onclick="signupNormalUserFn()">Sign Up</a>
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

<script type="text/javascript">
    $('#datepickerdob').datepicker({
        iconsLibrary: 'fontawesome',
        icons: {
         rightIcon: '<span class="fa fa-caret-down"></span>'
     }
    });



    function changeDistrict(stateValPar) {
        let currentStateNameVal = $(stateValPar).find(':selected').text()
        let allStateNames = <?php echo(json_encode($stateCityList)); ?>;
        let districtData = $.grep(allStateNames, function(item) { 
          return item.state == currentStateNameVal;
        });
        districtData = districtData[0]['districts'];
        console.log($("#districtOption").next("div.nice-select"))
        console.log($('#districtOption li.selected').text())
        $("#districtOption").html('');
        for (let i=0; i<districtData.length; i++) {            
            // $("#districtOption").append("<option value='"+districtData[i]+"'>"+districtData[i]+"</option>")
            // console.log("<option value='"+districtData[i]+"'>"+districtData[i]+"</option>")
            $("#districtOption").append(`<li data-value="${districtData[i]}" class="option">${districtData[i]}</li>`)
        }
    }
    

    // $.ajax({
    //      type: "POST",
    //      url: 'controllers/signup-login-controller.php',
    //      data: {functionName: 'signupHotelUser();', ...userFetchInfo},
    //      success: function (resp) {
    //         console.log('resp >> ',resp);
    //      }
    // });

    // $.ajax({
    //     url: "/assets/json/states-and-districts.json",
    //     type: 'GET',
    //     success: function(res) {
    //         <?php $stateCityList = res; ?>
    //     }
    // });
</script>
  
</body>
</html>
