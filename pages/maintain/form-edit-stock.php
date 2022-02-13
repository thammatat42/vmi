<?php 
    require_once('../authen.php'); 
    require_once '../../service/connect.php';

    /* ดึงข้อมูลมาเเสดง */
    $ID = $_GET['id'];

    $stmt = $connect->prepare("SELECT A.*,B.IMG,B.CATEGORY_ENG FROM `tb_stock_master` AS A INNER JOIN tb_master AS B ON A.GROUP_ID = B.GROUP_ID AND A.SIZE = B.SIZE
    WHERE A.STATUS_CHG = 0 AND B.STATUS_CHG = 0 AND A.ID = :id");
    $stmt->execute(array(":id" => $ID));
    $result = $stmt->fetch(PDO::FETCH_OBJ);


?>
<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate" />
  <title>เเก้ไขสต็อค | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie3.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
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
                                    <i class="fas fa-wrench"></i> 
                                    เเก้ไขมาสเตอร์สต็อค
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
                                                <label for="group_id">กรุ๊ป</label>
                                                <select class="form-control select2bs4" name="group_id" id="group_id" style="width: 100%;" disabled>
                                                    <option value="<?php echo $result->GROUP_ID; ?>" selected><?php echo $result->GROUP_ID; ?></option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="category_eng">หมวดหมู่ (ENG)</label>
                                                <input type="text" class="form-control" id="category_eng" name="category_eng" placeholder="หมวดหมู่" value="<?php echo $result->CATEGORY_ENG; ?>" disabled>
                                                <input type="hidden" id="ID" value="<?php echo $result->ID; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="category_th">หมวดหมู่ (TH)</label>
                                                <input type="text" class="form-control" id="category_th" name="category_th" placeholder="นามสกุล" value="<?php echo $result->CATEGORY_TH; ?>" disabled>
                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="size">ไซส์</label>
                                                <input type="text" class="form-control" id="size" name="size" placeholder="ไซส์" value="<?php echo $result->SIZE; ?>" disabled>
                                                
                                            </div>
                                            

                                        </div>
                                        <div class="col-md-6 px-1 px-md-5">

                                            <div class="form-group">
                                                <label for="max_stock">ปริมาณสูงสุด (Max-Stock)</label>
                                                <input type="text" class="form-control" name="max_stock" id="max_stock" placeholder="กรุณาใส่ค่า max" value="<?php echo $result->MAX_STOCK; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="min_stock">ปริมาณตํ่าสุด (Min-Stock)</label>
                                                <input type="text" class="form-control" name="min_stock" id="min_stock" placeholder="กรุณาใส่ค่า max" value="<?php echo $result->MIN_STOCK; ?>" required>
                                            </div>


                                            <div class="form-group">
                                                <label for="customfile">รูปภาพ</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="customfile" id="customfile" accept="image/*" disabled>
                                                    <input type="hidden" id="namefiles" name="namefiles" value="<?php echo $result->IMG?>">
                                                    <label class="custom-file-label" for="customfile" id="file-name"><?php echo $result->IMG?></label>
                                                </div>
                                                
                                            </div>
                                            <div class="form-group">
                                                <div class="img-fluid" style="width: 25%; height: 25%; overflow: hidden; background: #cccccc; margin: 0 auto">
                                                    <img src="../../assets/images/uniforms/<?php echo $result->IMG?>" alt="Image Profile" class="img-fluid p-0" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit" id="submit">บันทึกข้อมูล</button>
                                </div>
                            </form>
                        </div>
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
<script src="../../plugins/toastr/toastr.min.js"></script>




<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>

<script>
     $(function() {
        $('.select2').select2()

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })


        $('#formData').submit(function (e) {
            e.preventDefault();
            var formData = new FormData();
            var ID = $('#ID').val();
            var max_stock = $('#max_stock').val();
            var min_stock = $('#min_stock').val();
            var submit = $('#submit').val();
            

            // formData.append('customfile', $('input[type=file]')[0].files[0]); 
            formData.append('ID', ID);  
            formData.append('max_stock', max_stock);  
            formData.append('min_stock', min_stock);  
            formData.append('submit', submit);  
            $.ajax({
                type: 'POST',
                url: '../../service/maintain/update-stock-master.php',
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
                const check_log = jQuery.parseJSON( resp.responseText );
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด..',
                    text: check_log.message,
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
