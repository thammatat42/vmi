
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Error | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/hoodie3.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- <div class="content-wrapper pt-3"> -->
        <!-- Main content -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div class="error-box">
            <div class="error-body text-center" style="margin-top: 200px">
                <h1 class="error-title text-danger">Error: 405</h1>
                <h3 class="text-uppercase error-subtitle">ไม่พบหน้าเพจที่ต้องการ !</h3>
                <p class="text-muted m-t-30 m-b-30"><u style="color: red;">เนื่องจากระบบปิดปรับปรุงจะเปิดทำการวันที่ 03 มีนาคม 2565 เวลา 05:00 PM</u></p>
                <p class="text-muted m-t-30 m-b-30">ขออภัยในความไม่สะดวก..</p>
                <div id="countdown"></div>
            </div>
        </div>
    <!-- </div> -->
</div>
<!-- SCRIPTS -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="assets/js/adminlte.min.js"></script>

<script>
    var timeleft = 10;
    var downloadTimer = setInterval(function(){
    if(timeleft <= 0){
        clearInterval(downloadTimer);
        // document.getElementById("countdown").innerHTML = "สำเร็จ!";
        window.location.href = "pages/logout.php";
    } else {
        document.getElementById("countdown").innerHTML = "จะกลับหน้าล็อคอินภายใน " + timeleft + " วินาที";
    }
    timeleft -= 1;
    }, 1000);

</script>


</body>
</html>
