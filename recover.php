<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>VMI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/hoodie3.ico">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>

    .text-primary { 
      color: black!important;
    }

    .bg{
      background: mediumpurple;
    }
    .btn-fix{
      background-color: black;
      border-color: white;
      color: white;
    }

    
    #overlay{	
      position: fixed;
      top: 0;
      z-index: 100;
      width: 100%;
      height:100%;
      display: none;
      background: rgba(0,0,0,0.6);
    }
    .cv-spinner {
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;  
    }
    .spinner {
      width: 40px;
      height: 40px;
      border: 4px #ddd solid;
      border-top: 4px #2e93e6 solid;
      border-radius: 50%;
      animation: sp-anime 0.8s infinite linear;
    }
    @keyframes sp-anime {
      100% { 
        transform: rotate(360deg); 
      }
    }
    .is-hide{
      display:none;
    }

    .bg-img {
      /* The image used */
      background-image: url("assets/images/vmi-bg.jpg");

      min-height: 380px;

      /* Center and scale the image nicely */
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      position: relative;
    }
    .bg-blur {
      /* The image used */
      background-image: url("assets/images/vmi-bg.jpg");
      min-height: 380px;
      backdrop-filter: blur(5px);
      /* Center and scale the image nicely */
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      position: relative;
    }
    .blur {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
    }
    .color {
      color: white !important;
    }
  </style>
</head>
<body>
<header class="bg"></header>
<section class="d-flex align-items-center min-vh-100 bg-img">
  <div class="container">
    <div class="row justify-content-right">
      <section class="col-lg-6">
        <div class="p-3 p-md-4 text-center">
          <h1 class="text-center text-primary font-weight-bold color"> VMI SYSTEM</h1>
          <h4 class="color">Vendor Management Inventory System</h4> 
          <img style="width: 200px; height: 200px;" src="assets/images/vmi3.png" alt="Preview">
        </div>
      </section>
      <section class="col-lg-6">
        <div class="card shadow p-3 p-md-4 blur">
          <h1 class="text-center text-primary font-weight-bold color"><u>Change Password</u></h1>
          <!-- <h4 class="text-center">Web Application</h4>  -->
          <div class="card-body">
            <!-- HTML Form Recover --> 
            <form id="formRecover">
                <div class="input-group mb-3">
                    <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text px-2">ชื่อผู้ใช้งาน</div>
                    </div>
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password_1" placeholder="รหัสผ่านเดิม" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password_2" placeholder="รหัสผ่านใหม่" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-fix btn-block" id="button">Change password</button>
                        </div>
                    </div>
                    <a href="login.php" style="color: black">เข้าสู่ระบบ</a>
            </form>
          </div>
        </div>
      </section>
    </div>
    <footer class="container-fluid footer">
      <div class="row color">
        <div class="col-12 col-sm-12"><i class="fas fa-circle" style="font-size: 8px;"></i>&nbsp; Request Uniform with criteria per year</div>
        <div class="col-12 col-sm-12"><i class="fas fa-circle" style="font-size: 8px;"></i>&nbsp; Uniform ordering: Dedection with salary automatically</div>
        <div class="col-12 col-sm-12"><i class="fas fa-circle" style="font-size: 8px;"></i>&nbsp; Stock control and manage by outsource</div>
      </div>
    </footer>
  </div>
</section>
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<!-- script -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/toastr/toastr.min.js"></script>
<script>

  $(function() {
    toastr.options = {
      "positionClass": "toast-top-center"
    }
    $(document).ajaxSend(function() {
      $("#overlay").fadeIn(300);　
    });
    /** Ajax Submit Login */
    $("#formRecover").submit(function(e){
      e.preventDefault()
      $.ajax({
        type: "POST",
        url: "service/auth/recover.php",
        data: $(this).serialize()
      }).done(function(resp) {
        // console.log('test');
        window.toastr.remove()
        $("#overlay").fadeOut(300);
        setTimeout(() => {
          toastr.success('เปลี่ยนพาสเวิร์ดเรียบร้อยเเล้ว..')
        }, 1000)
        
        setTimeout(() => {
          location.href = 'login.php'
        }, 1500)
      }).fail(function(resp) {
        setTimeout((resp) => {
          $("#overlay").fadeOut(300);
          window.toastr.remove()
          toastr.error('ไม่สามารถเปลี่ยนพาสเวิร์ดได้..')
        //   setTimeout(() => {
        //     location.assign('');
        //   }, 1500);
        })
      })
    })
  })
</script>
</body>
</html>
