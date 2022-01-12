<?php 
    require_once('../authen.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Upload | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie2.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include_once('../includes/sidebar.php') ?>
    <div class="content-wrapper pt-3">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header border-0 pt-4">
                                <h4>
                                    <i class="far fa-file-excel"></i> 
                                    เพิ่มข้อมูลผู้ใช้งานเเบบไฟล์
                                </h4>
                                <a href="./" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>

                                <a href="../../assets/template/master_info.csv" class="btn btn-success my-3 float-sm-right" download>
                                    <i class="fas fa-file-excel"></i>
                                    ดาวน์โหลดไฟล์ master
                                </a>
                            </div>
                            <!-- Initial field -->

            
                            <!-- Card body -->
                            <div class="card-body">
                                <form id="submit_form">
                                    <div class="row">
                                        <div class="col-md-6 m-auto border shadow">
                                            <label style="font-size: 1rem"> เพิ่มข้อมูลเเบบไฟล์ <label style="color: red;">* (.csv UTF-8 เท่านั้น)</label></label>
                                                <div class="form-group">
                                                    <div class="custom-file">					
                                                        <input type="file" name="customfile" class="form-group" id="customfile" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success" name="upload_form" id="upload_form"> เพิ่มข้อมูล </button>
                                                </div>				
                                        </div>		
                                    </div>
                                </form>
                            </div>
                            <!-- End card body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('../includes/footer.php') ?>
</div>
<!-- SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>


<script>
    $('#submit_form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData();
        var upload_form = $('#upload_form').val();

        formData.append('customfile', $('input[type=file]')[0].files[0]); 
        formData.append('upload_form', upload_form);
        $("#overlay").fadeIn(300);
        $.ajax({  
            type: "POST",  
            url: "../../service/upload/upload_one.php",
            data: formData,
            contentType: false,
            processData: false
        }).done(function(resp) {
            $("#overlay").fadeOut(300);
            Swal.fire({
                text: 'เพิ่มข้อมูลเรียบร้อย',
                icon: 'success',
                confirmButtonText: 'ตกลง',
                backdrop: `
                    rgba(0,0,123,0.4)
                    url("../../assets/images/nyan-cat.gif")
                    left top
                    no-repeat
                `
            }).then((result) => {
                location.assign('./');
            });
            
        }).fail(function(resp) {
            $("#overlay").fadeOut(300);
            const check_log = jQuery.parseJSON( resp.responseText );
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด..',
                text: check_log.message,
                footer: 'โปรดลองใหม่อีกครั้ง!'
            }).then((result) => {
                location.reload()
            })
        })
    });
</script>


</body>
</html>
