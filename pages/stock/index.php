<?php 
    require_once('../authen.php'); 
    require_once '../../service/connect.php';

    
    $group_id = $_GET['id'];
    $stmt = $connect->prepare("SELECT * FROM tb_master where GROUP_ID = :id");
    $stmt->execute(array(':id' => $group_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $stmt2 = $connect->prepare("SELECT DISTINCT CATEGORY_TH,IMG FROM tb_master where GROUP_ID = :id");
    $stmt2->execute(array(':id' => $group_id));
    $result2 = $stmt2->fetch(PDO::FETCH_OBJ);
    // echo "<pre>";
    // print_r($result2);
    // die();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>รายการสินค้า | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie3.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/bootstrap-toggle/bootstrap-toggle.min.css">
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Datatables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.css">
  <link rel="stylesheet" href="../../plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css">

    <style>
    </style>
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
                                    <i class="fas fas fa-box"></i> 
                                    Order
                                </h4>

                            
                                <?php 
                                if($result2) { ?>
                                    <input type="hidden" value="1" id="check_value">
                                    <div class="card-header card-header-warning">
                                        <h4 class="card-title ">รายการสินค้า: <?php echo $result2->CATEGORY_TH;?></h4>
                                        <p class="card-category"></p>
                                    </div>

                                    <div class="card card-solid">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <h3 class="d-inline-block d-sm-none">Sony Technology Thailand</h3>
                                                    <div class="col-md-12">
                                                        <div class="container">
                                                            <img src="../../assets/images/uniforms/<?php echo $result2->IMG; ?>" class="img-fluid" alt="Product Image">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    
                                                    <div class="alert alert-danger" role="alert">
                                                        <h3 class="my-0">STT services</h3>
                                                    </div>

                                                    <h4 class="mt-3">Size <small>กรุณาเลือกไซต์..</small></h4>
                                                    <div class="form-group">
                                                        <select class="form-control select2bs4" name="size" id="size" style="width: 100%;">
                                                            <option value disabled selected>กรุณาเลือกไซต์..</option>
                                                            <?php 
                                                                foreach($result as $row){
                                                                    echo '<option value="'.$row['SIZE'].'">'.$row['SIZE'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <h4 class="mt-3"><small>จำนวนสินค้า</small></h4>
                                                    <div class="mt-2">
                                                        <button class="btn btn-default" id="negative">-</button>
                                                        <input class="btn btn-default" style="width: 80px" type="number" min="1" value="1" id="input_number"/>
                                                        <button class="btn btn-default" id="positive">+</button>
                                                    </div>
                                                    

                                                    <div class="bg-gray py-2 px-3 mt-4">
                                                        <h2 class="mb-0">
                                                            165 Bath
                                                        </h2>
                                                    </div>

                                                    <div class="mt-4">
                                                        <button class="btn btn-primary btn-lg"><i class="fas fa-cart-plus fa-lg mr-2"></i>เพิ่มเข้าตะกร้าสินค้า</button>
                                                        <button class="btn btn-danger btn-lg">สั่งซื้อ</button>
                                                    </div>

                                                

                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <nav class="w-100">
                                                <div class="nav nav-tabs" id="product-tab" role="tablist">
                                                    <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">รายละเอียด</a>
                                                    <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">คอมเม้น</a>
                                                    <a class="nav-item nav-link" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">ระดับคุณภาพ</a>
                                                </div>
                                                </nav>
                                                <div class="tab-content p-3" id="nav-tabContent">
                                                    <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab"> </div>
                                                    <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab"> </div>
                                                    <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab"> </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" value="0" id="check_value">
                                
                                <?php }?>
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
<script src="../../plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="../../plugins/toastr/toastr.min.js"></script>

<!-- datatables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-fixedheader/js/dataTables.fixedHeader.js"></script>
<script src="../../plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="../../plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.js"></script>
<script src="../../plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.min.js"></script>

<script src="../../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../plugins/jquery-validation/additional-methods.min.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>

<script>
    $(function() {
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        $(document).on('click', '#positive', function(){
            var input_number = parseInt($("#input_number").val()); 
            var set_value = $('#input_number').val(input_number + 1); 
        });

        $(document).on('click', '#negative', function(){
            var input_number = parseInt($("#input_number").val()); 
            var set_value = $('#input_number').val(input_number - 1);
            if(input_number < 2){
                set_value = $('#input_number').val(1);

            }
        })

        var check_value = $('#check_value').val();

        if(check_value == 0){
            Swal.fire({
            text: 'ไม่มีข้อมูลใน Master',
            icon: 'error',
            confirmButtonText: 'ตกลง',
            backdrop: `
                rgba(0,0,123,0.4)
                url("../../assets/images/nyan-cat.gif")
                left top
                no-repeat
            `
            }).then((result) => {
                location.assign('./');
                // console.log('test');
            });
        }
        
        
    })
</script>
</body>
</html>
