<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">
<head>
  <meta charset="UTF-8">
  <title>VMI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="google" content="notranslate" />
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/hoodie2.ico">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
          <h1 class="text-center text-primary font-weight-bold color"> <u>Sign IN</u></h1>
          <!-- <h4 class="text-center">Web Application</h4>  -->
          <div class="card-body">
            <!-- HTML Form Login --> 
            <form id="formLogin">
              <div class="form-group col-sm-12">
                <div class="alert alert-danger" role="alert" id="alert" hidden>
                  *ไม่สามารถเข้าสู่ระบบได้: รหัสผ่านหมดอายุ
                </div>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text px-2">ชื่อผู้ใช้งาน</div>
                  </div>
                  <input type="text" class="form-control" name="username" placeholder="Employee ID" value="<?php if(isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>">
                </div>
              </div>
              <div class="form-group col-sm-12">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text px-3">รหัสผ่าน</div>
                  </div>
                  <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
              </div>
              <div class="form-grop">
                <button type="submit" class="btn btn-fix btn-block" id="button"> เข้าสู่ระบบ</button>
              </div>
              <!-- <div class="form-group"> -->
                <!-- <div class="row"> -->
                  <!-- <div class="col-md-8 col-mb-4"> -->
                  <div class="d-flex justify-content-between">
                    <div class="icheck-primary">
                      <input type="checkbox" name="remember" id="remember" value="<?php if(isset($_COOKIE["remember"])) { echo $_COOKIE["remember"]; } ?>">
                      <label for="remember">
                        ให้ฉันอยู่ในระบบต่อไป
                      </label>
                      
                    </div>
                    <a style="color: #909090;" href="recover.php" >ลืมรหัสผ่านใช่ไหม??</a>
                  </div>
                  <!-- <div class="col-md-4 col-mb-2">
                    <a href="recover.php" style="color: red">Change Password</a>
                  </div> -->
                <!-- </div> -->
              <!-- </div> -->
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
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<script>

  $(function() {
    toastr.options = {
      "positionClass": "toast-top-center"
    }
    $(document).ajaxSend(function() {
      $("#overlay").fadeIn(300);　
    });
    /** Ajax Submit Login */
    $("#formLogin").submit(function(e){
      e.preventDefault()
      $.ajax({
        type: "POST",
        url: "service/auth/login.php",
        data: $(this).serialize()
      }).done(function(resp) {
        $("#overlay").fadeOut(300);
        setTimeout(() => {
          location.href = 'pages/index.php'
        }, 1000)
      }).fail(function(resp) {
        if(resp.status == 400){
          setTimeout((resp) => {
          $("#overlay").fadeOut(300);
          window.toastr.remove()
          toastr.error('ไม่สามารถเข้าสู่ระบบได้: รหัสผ่านหมดอายุ');
          $("#alert").attr("hidden", false);
        })
        } else {
            setTimeout((resp) => {
              $("#overlay").fadeOut(300);
              window.toastr.remove()
              toastr.error('ไม่สามารถเข้าสู่ระบบได้')
              $("#alert").attr("hidden", true);

          })
        }
        
      })
    });

    $('#formLogin').validate({
      rules: {
        username: {
          required: true,
        },
        password: {
          required: true,
          minlength: 3
        },
      },
      messages: {
        username: {
          required: "กรุณาใส่ชื่อผู้ใช้งาน",
        },
        password: {
          required: "กรุณาใส่รหัสผ่าน",
          minlength: "รหัสผ่านของคุณไม่ผ่านเงื่อนไขตัวอักษร 3 ตัวขึ้นไป"
        },
      },
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  })
</script>
</body>
</html>
