<?php 
    require_once('../authen.php'); 
    require_once '../../service/connect.php';

    /* ดึงข้อมูลมาเเสดง */
    $ID = $_GET['id'];

    $stmt = $connect->prepare("SELECT * FROM tb_user WHERE UID = :id");
    $stmt->execute(array(":id" => $ID));
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    /* Debug check API */
    // if(!empty($result)) {
    //     $response = [
    //         'status' => true,
    //         'response' => $result,
    //         'message' => 'success'
    //     ];
    //     http_response_code(200);
    //     echo json_encode($response);
    // } else {
    //     $response = [
    //         'status' => true,
    //         'message' => 'error'
    //     ];
    //     http_response_code(404);
    //     echo json_encode($response);
    // }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>จัดการผู้ดูแลระบบ | VMI</title>
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
                                    <i class="fas fa-user-cog"></i> 
                                    แก้ไขข้อมูลผู้ใช้งาน
                                </h4>
                                <a href="./" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
                            </div>
                            <form id="formData">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 px-1 px-md-5">

                                            <div class="form-group">
                                                <label for="first_name">ชื่อจริง</label>
                                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="ชื่อจริง" value="<?php echo $result->FNAME; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="last_name">นามสกุล</label>
                                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="นามสกุล" value="<?php echo $result->LNAME ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="username">ชื่อผู้ใช้งาน</label>
                                                <input type="text" class="form-control" id="username" name="username" placeholder="ชื่อผู้ใช้งาน" value="<?php echo $result->USERNAME ?>" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">รหัสผ่าน</label>
                                                <p class="bg-light p-2 shadow-sm">******</p>
                                            </div>

                                        </div>
                                        <div class="col-md-6 px-1 px-md-5">
                                        <?php
                                            if($_SESSION['AD_STATUS'] == 'Admin'){?>
                                            <div class="form-group">
                                                <label for="permission">สิทธิ์การใช้งาน</label>
                                                <select class="form-control" name="status" id="permission" required>
                                                    <option value disabled selected>กำหนดสิทธิ์</option>
                                                    <option value="Admin">Admin</option>
                                                    <option value="Vendor">Vendor</option>
                                                    <option value="User">User</option>
                                                </select>
                                            </div>
                                            <?php } else {?>
                                                <div class="form-group">
                                                <label for="permission">สิทธิ์การใช้งาน</label>
                                                <select class="form-control" name="status" id="permission" required>
                                                    <option value disabled selected>กำหนดสิทธิ์</option>
                                                    <option value="User">User</option>
                                                </select>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group">
                                                <label for="customFile">รูปโปรไฟล์</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="customfile" id="customfile" accept="image/*">
                                                    <input type="hidden" id="namefiles" name="namefiles">
                                                    <label class="custom-file-label" for="customfile" id="file-name">เลือกรูปภาพ</label>
                                                </div>

                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="img-fluid" style="width: 25%; height: 25%; overflow: hidden; background: #cccccc; margin: 0 auto">
                                                    <img src="../../assets/upload/<?php echo $result->IMG?>" alt="Image Profile" class="img-fluid p-0" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
                                </div>
                            </form>
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
     $(function() {
        $('#formData').submit(function (e) {
            e.preventDefault();
            var username = $('#username').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var status = $('#permission').val();
            var submit = $('#submit').val();
            var formData = new FormData();

            formData.append('customfile', $('input[type=file]')[0].files[0]); 
            formData.append('username', username); 
            formData.append('first_name', first_name); 
            formData.append('last_name', last_name);
            formData.append('status', status); 
            formData.append('submit', submit); 
            $.ajax({
                type: 'POST',
                url: '../../service/manager/update.php',
                // data: $('#formData').serialize()
                data: formData,
                contentType: false,
                processData: false
            }).done(function(resp) {
                Swal.fire({
                    text: 'อัพเดทข้อมูลเรียบร้อย',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    location.assign('./');
                });
            }).fail(function(resp) {
                // console.log(resp);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด..',
                    text: 'มีบางอย่างผิดปกติ',
                    footer: 'โปรดอัปโหลดไฟล์ใหม่อีกครั้ง!'
                }).then((result) => {
                    location.assign('');
                })
            })
        });

        $('body').on('change', '#customfile', function (event) {
            // console.log("test");
            $("#file-name").text(this.files[0].name);
            $("#namefiles").val(this.files[0].name);
        });
    });
</script>

</body>
</html>
