
    <title>Add Hotel | Hotel Admin | Himachal Stay</title>
</head>
<!-- include header -->

<?php 
   require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/header.php");
   session_start();
   	if($_SESSION['hotel_admin_id'] == NULL) {
      header("Location: /login-hotel-admin");
      die();
    }
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
    <?php require_once($_SERVER['DOCUMENT_ROOT']."/views/admin_panel/sub_views/navbar.php"); ?>
    <!-- ends here ~ include menu -->

    <!-- bradcam_area_start -->
    <div class="bradcam_area breadcam_bg">
        <h3>Add Hotel</h3>
        <h5>Hotel Admin</h5>
    </div>
    <!-- bradcam_area_end -->

    <!-- add hotel form -->
    <!-- registration form-->
    <form autocomplete="off" id="addHotelForm">
        <div class="popup_box centerBlock">
            <div class="popup_inner">
                <form>
                    <div class="row">
                        
                        <div class="col-xl-12">
                            <label>Hotel Name*</label>
                            <input type="text" class="nice-select form-select wide open" placeholder="Hotel Name*" name="hotelName" />
                        </div>
                        <div class="col-xl-12">
                            <label>Address*</label>
                            <input type="text" class="nice-select form-select wide open" placeholder="Address*" name="hotelAddr" />
                        </div>
                        <div class="col-xl-12">
                            <label>City*</label>
                            <input type="text" class="nice-select form-select wide open" placeholder="City*" name="hotelCity" />
                        </div>
                        <div class="col-xl-12">
                            <label>District*</label>
                            <select class="form-select wide" id="default-select" name="district">
                                <?php
                                    foreach($cityList as $cityNames) {
                                        echo "<option value='".$cityNames['district_val']."')'>".$cityNames['district_name']."</option>";
                                    }
                                ?>
                            </select>

                        </div>
                         <div class="col-xl-12">
                            <label>Pin Code*</label>
                            <input type="number" class="nice-select form-select wide open" placeholder="Pin Code*" name="hotelPin" />
                        </div>
                        <div class="col-xl-12">
                            <label>State*</label>
                            <input name="state" type="text" value="Himachal" class="nice-select form-select wide open" disabled />
                        </div>
                        <div class="col-xl-12">
                            <label>Description*</label>
                            <div id="hotelDescription"></div>
                        </div>

                        <div class="col-xl-12" style="margin-top: 40px;">                       
                          <input type='file' id="hotelImage" />
                          <img id="hotelImagePreview" src="" style="max-height: 200px;" />
                        </div>
                        
                        <div class="col-xl-12" style="margin-top: 40px;">
                            <a class="boxed-btn3" onclick="addHotelFn()">Add Hotel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </form>
    <!-- ends here ~ registration form -->
    <!-- ends here ~ add hotel form -->


<!-- include footer files -->
<?php 
  require_once($_SERVER['DOCUMENT_ROOT']."/views/sub-views/footer.php")
?>
<!-- ends here ~ include footer files -->
<script type="text/javascript">
  if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
  CKEDITOR.tools.enableHtml5Elements( document );

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';

var initSample = ( function() {
  var wysiwygareaAvailable = isWysiwygareaAvailable(),
    isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

  return function() {
    var editorElement = CKEDITOR.document.getById( 'hotelDescription' );
    if ( wysiwygareaAvailable ) {
      CKEDITOR.replace( 'hotelDescription' );
    } else {
      editorElement.setAttribute( 'contenteditable', 'true' );
      CKEDITOR.inline( 'hotelDescription' );
    }
  };

  function isWysiwygareaAvailable() {
    if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
      return true;
    }

    return !!CKEDITOR.plugins.get( 'wysiwygarea' );
  }
} )();
initSample();

// for image preview
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#hotelImagePreview').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#hotelImage").change(function() {
  readURL(this);
});
// ends here ~ for preview 

</script>
</body>
</html>
