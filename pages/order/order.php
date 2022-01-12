<?php 
    require_once('../authen.php'); 
    require_once '../../service/connect.php';

    
    $id_decode = $_GET['id'];

    //ถอดรหัส id
    $id_decrypt = base64_decode(urldecode($id_decode));
    $id_decrypt = ((($id_decrypt*956783)/5678)/123456789);
    $group_id = round($id_decrypt);
    
    $stmt = $connect->prepare("SELECT * FROM tb_master where GROUP_ID = :id AND C_STOCK <> 0 AND STATUS_CHG = '0'");
    $stmt->execute(array(':id' => $group_id));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $stmt2 = $connect->prepare("SELECT DISTINCT CATEGORY_TH,IMG,UNIT_PRICE FROM tb_master where GROUP_ID = :id AND STATUS_CHG = '0'");
    $stmt2->execute(array(':id' => $group_id));
    $result2 = $stmt2->fetch(PDO::FETCH_OBJ);
    // echo "<pre>";
    // print_r($result2);
    // die();

    $stmt3 = $connect->prepare("SELECT COUNT(*) AS THIS_YEAR FROM tb_order_trans WHERE YEAR(NOW()) = YEAR(ORDER_DATE) AND ORDER_BY = :ORDER_BY AND GROUP_ID = :id and QTY >= 2 AND STATUS_ID <> 3");
    $stmt3->execute(array(':ORDER_BY' => $_SESSION['gid'],':id' => $group_id));
    $result3 = $stmt3->fetch(PDO::FETCH_OBJ);

    $count_check_year = $result3->THIS_YEAR;

    $stmt4 = $connect->prepare("SELECT COUNT(*) AS count_trans FROM tb_order_trans WHERE YEAR(NOW()) = YEAR(ORDER_DATE) AND ORDER_BY = :ORDER_BY AND GROUP_ID = :id AND STATUS_ID <> 3");
    $stmt4->execute(array(':ORDER_BY' => $_SESSION['gid'],':id' => $group_id));
    $result4 = $stmt4->fetch(PDO::FETCH_OBJ);

    $count_check_trans = $result4->count_trans;

    

    // if($result3->THIS_YEAR <= 1 && $result3->STATUS_ID == 0) {
    //     $PRICE = 'FREE';
    // } else {
    //     $PRICE = $result2->UNIT_PRICE.' Bath';
    // }

    // echo '<pre>';
    // print_r($result->UNIT_PRICE);
    // die();
?>
<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate" />
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
                                    <i class="fas fa-shopping-cart"></i> 
                                    Order
                                </h4>
                                <a href="../order/" class="btn btn-primary my-3 ">
                                    <i class="fas fa-arrow-circle-left"></i>
                                    เลือกรายการถัดไป..
                                </a>
                                <!-- ถ้ามีข้อมูลมาถึงจะ show div นี้ -->
                                <?php 
                                if($result2) { ?>
                                    <input type="hidden" value="1" id="check_value">
                                    <input type="hidden" value="<?php echo $group_id;?>" id="send_group_id">
                                    <div class="card-header card-header-warning">
                                        <h4 class="card-title ">รายการสินค้า: <?php echo $result2->CATEGORY_TH;?></h4>
                                        <input type="hidden" value="<?php echo $result2->CATEGORY_TH;?>" id="category_th">
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

                                                    <div class="form-group" id="amount">
                                                    </div>

                                                    <h4 class="mt-3"><small>จำนวนสินค้า</small></h4>
                                                    <div class="mt-2">
                                                        <button class="btn btn-default" id="negative">-</button>
                                                        <input class="btn btn-default" style="width: 80px" type="number" min="1" value="1" onKeyPress="if(this.value.length==4) return false;" id="input_number"/>
                                                        <button class="btn btn-default" id="positive">+</button>
                                                    </div>
                                                    
                                                   
                                                    <div class="alert alert-warning py-2 px-3 mt-4" role="alert" id="show_value_bath" hidden>
                                                        <h2 class="mb-0">
                                                            <?php echo $result2->UNIT_PRICE.' Bath';?>
                                                        </h2>
                                                    </div>
                                                    
                                                    <input type="hidden" id="check_this_year" value="<?php echo $count_check_year; ?>">
                                                    <input type="hidden" id="check_trans" value="<?php echo $count_check_trans; ?>">
                                                    <div class="alert alert-warning py-2 px-3 mt-4" role="alert" id="show_value_for_this_year" hidden>
                                                        <h2 class="mb-0">
                                                            <?php echo $result2->UNIT_PRICE.' Bath';?>
                                                        </h2>
                                                    </div>
                                                                
                                                    <div class="alert alert-success py-2 px-3 mt-4" role="alert" id="show_value_free" hidden>
                                                        <h2 class="mb-0">
                                                            <?php echo 'ฟรี';?>
                                                        </h2>
                                                    </div>

                                                    <div class="mt-4" id="check_order_button">
                                                        <!-- <button type="button" class="btn btn-success btn-lg" id="group_edit" data-id="${item.GROUP_ID}" data-index="${index}" data-toggle="modal" data-target="#modal-edit">
                                                            <i class="fas fa-cart-plus"></i> สั่งซื้อ
                                                        </button> -->
                                                    </div>

                                                

                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <nav class="w-100">
                                                <div class="nav nav-tabs" id="product-tab" role="tablist">
                                                    <a class="nav-item nav-link active" style="background-color: #62ff62; font-size: 1rem; color: black;" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true"><i class="fas fa-cart-plus"></i> ตะกร้าสินค้า</a>
                                                </div>
                                                </nav>
                                                <div class="tab-content p-3" id="nav-tabContent">
                                                    <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <table  id="order_logs" 
                                                        class="table table-hover" 
                                                        width="100%" style="text-align: center;" hidden>

                                                    <thead>
                                                        <tr>
                                                            <th>ออเดอร์</th>
                                                            <th>รูปภาพสินค้า</th>
                                                            <th>หมวดหมู่ไทย</th>
                                                            <th>ขนาดสินค้า</th>
                                                            <th>จำนวนสินค้า</th>
                                                            <th>ราคารวม (บาท)</th>
                                                            <th>ผู้สั่งซื้อสินค้า</th>
                                                            <th>วันที่รับเข้า</th>
                                                            <th>การเปลี่ยนแปลง</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th>รวม</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" value="0" id="check_value">
                                
                                <?php }?>
                            </div>

                            <!-- Modal -->
                            <!-- <form id="alert_notice">
                                <div class="modal fade" id="modal-alert-notice">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="text-glow" class="modal-title"><i class="fas fa-bullhorn"></i> ข้อควรทราบ!</h4>
                                                
                                            </div>
                                            <div class="modal-body">
                                                <h1 style="text-align: center; font-size: 1.5rem">กฏระเบียบการสั่งสินค้า</h1>
                                                <p style="color: red; text-align: center;">1. การสั่งซื้อสินค้า 2 ชิ้นเเรกของเเต่ละประเภทใน 1 ปี ฟรี!&hellip;</p>
                                                <p style="color: red; text-align: center;">2. ถ้าหาก&hellip;</p>
                                                <p style="color: red; text-align: center;">3. การสั่งซื้อสินค้า 2 ชิ้นเเรกของเเต่ละประเภทใน 1 ปี ฟรี!&hellip;</p>

                                                <div id="result"></div>
                                                <input id="value_id" type="hidden">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">รับทราบ</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form> -->
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
        const check_this_year = parseInt($("#check_this_year").val());
        const check_trans = parseInt($("#check_trans").val());

        if (check_this_year > 0){
            $("#show_value_bath").attr("hidden", false);
        } else if(check_trans > 2) {
            $("#show_value_bath").attr("hidden", false);
        } else if(check_trans == 1) {
            $("#show_value_free").attr("hidden", false);
        } else if(check_trans == 2) {
            $("#show_value_bath").attr("hidden", false);
        } else {
            $("#show_value_free").attr("hidden", false);
        }

        // $("#modal-alert-notice").modal({
        //     show: true,
        //     backdrop: 'static'
        // });
        

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        $(document).on('click', '#positive', function(){
            const input_number = parseInt($("#input_number").val()); 
            const check_stock = $("#stock_value").attr('at');

            // limit การสั่ง order
            if (input_number < check_stock){
                const set_value = $('#input_number').val(input_number + 1);
            }

            if (check_this_year > 0){
                // $("#show_value_for_this_year").attr("hidden", true);
                $("#show_value_bath").attr("hidden", false);
                $("#show_value_free").attr("hidden", true);
            } else if (check_trans > 2) {
                $("#show_value_free").attr("hidden", true);
                $("#show_value_bath").attr("hidden", false);
            } else if (check_trans == 1) {
                if (input_number > 0){
                    $("#show_value_bath").attr("hidden", false);
                    $("#show_value_free").attr("hidden", true);
                }
            } else if (check_trans == 2) {
                if (input_number > 0){
                    $("#show_value_bath").attr("hidden", false);
                    $("#show_value_free").attr("hidden", true);
                }
            } else {
                if (input_number > 1){
                    $("#show_value_bath").attr("hidden", false);
                    $("#show_value_free").attr("hidden", true);
                } else {
                    $("#show_value_free").attr("hidden", false);
                    $("#show_value_bath").attr("hidden", true);
                }
            }
        });

        $(document).on('click', '#negative', function(){
            const input_number = parseInt($("#input_number").val()); 
            const set_value = $('#input_number').val(input_number - 1);

            if(input_number < 2){
                set_value = $('#input_number').val(1);
            }

            if (check_this_year > 0){
                // $("#show_value_for_this_year").attr("hidden", true);
                $("#show_value_bath").attr("hidden", false);
                $("#show_value_free").attr("hidden", true);
            } else if (check_trans > 2) {
                $("#show_value_free").attr("hidden", true);
                $("#show_value_bath").attr("hidden", false);
            } else if (check_trans == 1) {
                if (input_number <= 2){
                    $("#show_value_free").attr("hidden", false);
                    $("#show_value_bath").attr("hidden", true);
                }
            } else if (check_trans == 2) {
                if (input_number <= 3){
                    $("#show_value_free").attr("hidden", true);
                    $("#show_value_bath").attr("hidden", false);
                }
            } else {
                if (input_number <= 3){
                    $("#show_value_free").attr("hidden", false);
                    $("#show_value_bath").attr("hidden", true);
                }
            }
        
        })

        $(document).on('change', '#input_number', function(){
            const input_number = parseInt($("#input_number").val());
            const check_stock = $("#stock_value").attr('at');

            // set ค่า max value
            if(input_number > check_stock) {
                const set_value = $('#input_number').val(check_stock);
            }

            if (check_this_year > 0){
                $("#show_value_bath").attr("hidden", false);
                $("#show_value_free").attr("hidden", true);
            } else if (check_trans == 0) {
                if (input_number > 2){
                    $("#show_value_free").attr("hidden", true);
                    $("#show_value_bath").attr("hidden", false);
                } else {
                    $("#show_value_free").attr("hidden", false);
                    $("#show_value_bath").attr("hidden", true);
                }
            } else if (check_trans == 1) {
                if (input_number > 1){
                    $("#show_value_free").attr("hidden", true);
                    $("#show_value_bath").attr("hidden", false);
                } else {
                    $("#show_value_free").attr("hidden", false);
                    $("#show_value_bath").attr("hidden", true);
                }
            } else if (check_trans == 2) {

                if (input_number > 2){
                    $("#show_value_free").attr("hidden", true);
                    $("#show_value_bath").attr("hidden", false);
                } else {
                    $("#show_value_free").attr("hidden", true);
                    $("#show_value_bath").attr("hidden", false);
                }
            } else {
                $("#show_value_free").attr("hidden", false);
                $("#show_value_bath").attr("hidden", true);
            }
        });

        const check_value = $('#check_value').val();

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

        $('#size').change(function() {
            const size = $('#size').val();
            const send_group_id = $('#send_group_id').val();

            $.ajax({  
                type: "POST",  
                url: "../../service/order/change-size.php",  
                data: { size:size,send_group_id:send_group_id,function:'size_change' },
                dataType:"text"
            }).done(function(data) {
                set_value = $('#input_number').val(1);
                const check_result = jQuery.parseJSON( data );
                $('#amount').html(check_result.amount);
                $('#check_order_button').html(check_result.check_stock);

                /* เพิ่มเข้าตะกร้าสินค้า */
                $('#order_submit').click(function(e) {
                    e.preventDefault();
                    const size = $('#size').val();
                    const send_group_id = $('#send_group_id').val();
                    const input_number = $('#input_number').val();
                    // const check_stock = $("#stock_value").attr('at');
                    const category_th = $('#category_th').val();

                    $.ajax({  
                        type: "POST",  
                        url: "../../service/order/order-cart.php",  
                        data: { size:size,send_group_id:send_group_id,input_number:input_number,category_th:category_th,function:'order_submit' },
                        dataType:"text"
                    }).done(function(data) {
                        
                        Swal.fire({ 
                            text: 'เพิ่มเข้าตะกร้าสินค้าเรียบร้อยเเล้ว..', 
                            icon: 'success', 
                            confirmButtonText: 'ตกลง', 
                        }).then(function() {
                            location.assign('')
                        })

                    }).fail(function(data) {
                        window.toastr.remove()
                        toastr.warning('ไม่พบข้อมูล')
                    })
                });
                /* จบ */
                
            }).fail(function(data) {
                window.toastr.remove()
                toastr.warning('ไม่พบข้อมูล')
            })


        });
        // cal comma
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
        }

        loadDataTables()
         /* Start Datatable */
        function loadDataTables() {
            $.ajax({
                type: "GET",
                url: "../../service/order/order-cart-datatable.php"
            }).done(function(data) {
                let tableData = []
                data.response.forEach(function (item, index){
                    tableData.push([    
                        ++index,
                        `<img src="../../assets/images/uniforms/${item.IMG}" style="width: 100px; height: 100px;" class="product-image" alt="Product Image">`,
                        item.CATEGORY_TH,
                        item.SIZE,
                        item.QTY,
                        numberWithCommas(item.PRICE),
                        // `<span class="badge bg-warning" style="font-size: 0.9rem;">${item.PRICE} บาท</span>`,
                        item.ORDER_BY,
                        item.ORDER_DATE,
                        `<div class="btn-group" role="group">
                            
                            <button type="submit" class="btn btn-danger" id="cancel" data-id="${item.ID}">
                                <i class="fas fa-trash"></i> ลบ
                            </button>
                        </div>`
                    ])
                })
                initDataTables(tableData)
            })
        }
        
        
        function initDataTables(tableData) {
            $("#order_logs").attr("hidden", false);
            var table = $('#order_logs').DataTable( {
                data: tableData,
                columns: [
                    { title: "ออเดอร์", tableData: "ออเดอร์", className: "align-middle"},
                    { title: "รูปภาพสินค้า", tableData: "รูปภาพสินค้า" , className: "align-middle"},
                    { title: "หมวดหมู่ไทย", tableData: "หมวดหมู่ไทย" , className: "align-middle"},
                    { title: "ขนาดสินค้า", tableData: "ขนาดสินค้า", className: "align-middle"},
                    { title: "จำนวนสินค้า", tableData: "จำนวนสินค้า", className: "align-middle"},
                    { title: "ราคารวม (บาท)", tableData: "ราคารวม (บาท)", className: "align-middle"},
                    { title: "ผู้สั่งซื้อสินค้า", tableData: "ผู้สั่งซื้อสินค้า", className: "align-middle"},
                    { title: "วันที่รับเข้า", tableData: "วันที่รับเข้า", className: "align-middle"},
                    { title: "การเปลี่ยนแปลง", tableData: "การเปลี่ยนแปลง", className: "align-middle"}
                    
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
                    // console.log(data);

                    // ลบข้อมูลที่เป็น string ออกต้องเป็น integer เท่านั้น
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
        
                    // ราคารวมทุกหน้า
                    data = api.column( 5 ).data();
                    total = data.length ?
                        data.reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                        } ) :
                        0;
        
                    // ราคารวมเฉพาะหน้านี้
                    data = api.column( 5, { page: 'current'} ).data();
                    pageTotal = data.length ?
                        data.reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                        } ) :
                        0;

                    // Update footer
                    $( api.column( 5 ).footer() ).html(
                        `<span class="badge bg-warning" style="font-size: 0.9rem;">${numberWithCommas(pageTotal)} บาท</span>`
                    );


                    $( api.column( 8 ).footer() ).html(
                        `<div class="btn-group" role="group">
                            <button type="submit" class="btn btn-success btn-lg" id="send_order">
                                <i class="fas fa-cart-plus"></i> ออเดอร์
                            </button>
                            <button type="submit" class="btn btn-danger btn-lg" id="cancel_all_order">
                                <i class="fas fa-window-close"></i> ยกเลิก
                            </button>
                        </div>`
                    );
                },
                responsive: true,
                initComplete: function () {
                    $(document).on('click', '#cancel', function(){ 
                        let id = $(this).data('id')
                        const submit = $('#cancel').val();
                        $.ajax({  
                            type: "POST",  
                            url: "../../service/order/cancel-order.php",  
                            data: { id: id,submit:submit }
                        }).done(function(resp) {
                            location.reload()
                            
                        }).fail(function(resp) {
                            const check_log = jQuery.parseJSON( resp.responseText );
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด..',
                                text: check_log.message,
                                footer: 'กรุณายกเลิกใหม่อีกครั้ง!!'
                            }).then((result) => {
                                location.assign('');
                            })
                        })
                    })
                },
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal( {
                            header: function ( row ) {
                                var data = row.data()
                                return 'ผู้ใช้งาน: ' + data[1]
                            }
                        }),
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                            tableClass: 'table'
                        })
                    }
                },
                language: {
                    "lengthMenu": "แสดงข้อมูล _MENU_ แถว",
                    "zeroRecords": "ไม่พบข้อมูลที่ต้องการ",
                    "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
                    "infoEmpty": "ไม่พบข้อมูลที่ต้องการ",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "search": 'ค้นหา'
                }
                
            });
            new $.fn.dataTable.FixedHeader( table );
        }
    
        /* End Datatable */


        /* บันทึกทั้งหมดของออเดอร์ */
        $(document).on('click', '#send_order', function(){ 
            Swal.fire({
                title: "คุณแน่ใจเเล้วหรือไม่..ที่จะสั่งรายการชุดยูนิฟอร์มทั้งหมด?",
                text: "เมื่อสั่งออเดอร์เเล้วจะไม่สามารถยกเลิกรายการได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed == true) {
                    $.ajax({  
                        type: "POST",  
                        url: "../../service/request/send-order.php"
                        // data: {submit: submit},
                    }).done(function(resp) {
                        Swal.fire({
                            text: 'ทำการสั่งรายการทั้งหมดในตะกร้าสินค้าเรียบร้อยเเล้ว..',
                            icon: 'success',
                            confirmButtonText: 'ตกลง',
                        }).then((result) => {
                            location.assign('../order/');
                        })
                    }).fail(function(resp) {
                        const check_log = jQuery.parseJSON( resp.responseText );
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด..',
                            text: check_log.message,
                            footer: 'กรุณายกเลิกทั้งหมดใหม่อีกครั้ง!!'
                        }).then((result) => {
                            location.reload();
                        })
                    })
                } else {
                    location.reload();
                }
                
            })

        });
        /* จบ */

        /* ยกเลิกทั้งหมดของออเดอร์ */
        $(document).on('click', '#cancel_all_order', function(){ 
            const submit = $('#cancel_all_order').val();
            Swal.fire({
                text: "คุณแน่ใจหรือไม่..ที่จะยกเลิกรายการทั้งหมด?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if(result.isConfirmed == true) {
                    $.ajax({  
                        type: "POST",  
                        url: "../../service/order/cancel-order-all.php",
                        data: {submit: submit},
                    }).done(function(resp) {
                        Swal.fire({
                            text: 'รายการของคุณถูกยกเลิกทั้งหมดเรียบร้อยเเล้ว',
                            icon: 'success',
                            confirmButtonText: 'ตกลง',
                        }).then((result) => {
                            location.reload()
                        })
                    }).fail(function(resp) {
                        const check_log = jQuery.parseJSON( resp.responseText );
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด..',
                            text: check_log.message,
                            footer: 'กรุณายกเลิกทั้งหมดใหม่อีกครั้ง!!'
                        }).then((result) => {
                            location.reload();
                        })
                    })
                } else {
                    location.reload();
                }
                
            })
            
        });
        
    })
</script>
</body>
</html>
