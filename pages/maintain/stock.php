<?php 
    require_once('../authen.php'); 
    require_once '../../service/connect.php';

    if(isset($_GET['search'])) {
        $search = $_GET['search'];
        
    } else {
        $search = '';
    }

    
    $stmt = $connect->prepare("SELECT * FROM tb_group_master WHERE CATEGORY_TH like '%".$search."%' AND STATUS_CHG = '0'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate" />
  <title>คลังสินค้า | VMI</title>
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
    .bg-secondary {
        background-color: #212325!important;
    }
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }
    body {
        font-family: 'Roboto', sans-serif;
        font-style: normal;
        font-weight: 300;
        background-color: var(--color-one);
        font-size: 0.95em;
        color: #222;
    }

    .container {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .card {
        border: 1px solid var(--color-three);
        margin-bottom: 20px;
        transition: border 0.1s, transform 0.3s;
    }

    .card:hover {
        border: 1px solid var(--color-two);
        -webkit-transform: translateY(-10px);
        transform: translateY(-10px);
        cursor: pointer;
    }

    .card .card-body h2 {
        color: var(--color-two);
    }

    .card img:hover {
    opacity: 0.6;
    }

    .card-p {
        color: var(--color-three);
    }

    .card-p i {
        color: var(--color-two);
        margin-right: 8px;
    }

    .text-glow {
        text-shadow: 0 0 80px rgb(192 219 255 / 75%), 0 0 32px rgb(65 120 255 / 24%);
    }
    .bg-secondary {
        background-color: #212325!important;
    }
    .table {
        background-color: #808080;
    }
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }

    * {
        border: 0;
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    :root {
        font-size: calc(16px + (24 - 16)*(100vw - 320px)/(1920 - 320));
    }
    button, input {
        font: 1em Hind, sans-serif;
        line-height: 1.5em;
    }
    input {
        color: #171717;
    }
    .search-bar {
        display: flex;
    }
 
    .search-bar input,
    .search-btn, 
    .search-btn:before, 
    .search-btn:after {
        transition: all 0.25s ease-out;
    }
    .search-bar input,
    .search-btn {
        width: 3em;
        height: 3em;
    }
    .search-bar input:invalid:not(:focus),
    .search-btn {
        cursor: pointer;
    }
    .search-bar,
    .search-bar input:focus,
    .search-bar input:valid  {
        width: 100%;
    }
    .search-bar input:focus,
    .search-bar input:not(:focus) + .search-btn:focus {
        outline: transparent;
    }
    .search-bar {
        margin: auto;
        padding: 1.5em;
        justify-content: center;
        max-width: 30em;
    }
    .search-bar input {
        background: transparent;
        border-radius: 1.5em;
        box-shadow: 0 0 0 0.4em #171717 inset;
        padding: 0.75em;
        transform: translate(0.5em,0.5em) scale(0.5);
        transform-origin: 100% 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    .search-bar input::-webkit-search-decoration {
        -webkit-appearance: none;
    }
    .search-bar input:focus,
    .search-bar input:valid {
        background: #fff;
        border-radius: 0.375em 0 0 0.375em;
        box-shadow: 0 0 0 0.1em #d9d9d9 inset;
        transform: scale(1);
    }
    .search-btn {
        background: #171717;
        border-radius: 0 0.75em 0.75em 0 / 0 1.5em 1.5em 0;
        padding: 0.75em;
        position: relative;
        transform: translate(0.25em,0.25em) rotate(45deg) scale(0.25,0.125);
        transform-origin: 0 50%;
    }
    .search-btn:before, 
    .search-btn:after {
        content: "";
        display: block;
        opacity: 0;
        position: absolute;
    }
    .search-btn:before {
        border-radius: 50%;
        box-shadow: 0 0 0 0.2em #f1f1f1 inset;
        top: 0.75em;
        left: 0.75em;
        width: 1.2em;
        height: 1.2em;
    }
    .search-btn:after {
        background: #f1f1f1;
        border-radius: 0 0.25em 0.25em 0;
        top: 51%;
        left: 51%;
        width: 0.75em;
        height: 0.25em;
        transform: translate(0.2em,0) rotate(45deg);
        transform-origin: 0 50%;
    }
    .search-btn span {
        display: inline-block;
        overflow: hidden;
        width: 1px;
        height: 1px;
    }

    /* Active state */
    .search-bar input:focus + .search-btn,
    .search-bar input:valid + .search-btn {
        background: #2762f3;
        border-radius: 0 0.375em 0.375em 0;
        transform: scale(1);
    }
    .search-bar input:focus + .search-btn:before, 
    .search-bar input:focus + .search-btn:after,
    .search-bar input:valid + .search-btn:before, 
    .search-bar input:valid + .search-btn:after {
        opacity: 1;
    }
    .search-bar input:focus + .search-btn:hover,
    .search-bar input:valid + .search-btn:hover,
    .search-bar input:valid:not(:focus) + .search-btn:focus {
        background: #0c48db;
    }
    .search-bar input:focus + .search-btn:active,
    .search-bar input:valid + .search-btn:active {
        transform: translateY(1px);
    }

    @media screen and (prefers-color-scheme: dark) {
        body, input {
            color: #f1f1f1;
        }
        body {
            background: #171717;
        }
        .search-bar input {
            box-shadow: 0 0 0 0.4em #f1f1f1 inset;
        }
        .search-bar input:focus,
        .search-bar input:valid {
            background: #3d3d3d;
            box-shadow: 0 0 0 0.1em #3d3d3d inset;
        }
        .search-btn {
            background: #f1f1f1;
        }
    }

    
  </style>
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
                        <div class="shadow">
                            <div class="card-header border-0 pt-4">
                                <h4>
                                    <i class="fas fa-dolly-flatbed"></i> 
                                    หน้าจัดการสต๊อก
                                </h4>
                                <a href="./" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>

                                <button type="button" class="btn btn-warning my-3" data-toggle="modal" data-target="#modal-stock" id="click_group">
                                    <i class="fas fa-newspaper"></i>
                                    คลังสินค้า
                                </button>

                                <!-- <a href="../../service/maintain/export.php" class="btn btn-success my-3 float-sm-right">
                                    <i class="fas fa-arrow-circle-down"></i>
                                    Export
                                </a> -->

                                <button type="submit" class="btn btn-success my-3 float-sm-right" name="export_excel" id="export_excel">
                                    <i class="fas fa-arrow-circle-down"></i>
                                    Export
                                </button>

                                <form action="stock.php" class="search-bar">
                                    <input type="search" name="search" pattern=".*\S.*" placeholder="ค้นหาประเภทสินค้า.." required>
                                    <button class="search-btn" type="submit">
                                        <span>Search..</span>
                                    </button>
                                </form>

                                
                            </div>
                            <!-- Initial field -->
                            <div class="card-body">
                                <div class="row">
                                <?php
                                    foreach($result as $key => $row){ ?>
                                    <div class="col-12 col-sm-3">
                                        <a class="check-stock-modal" data-toggle="modal" data-target="#modal-lg" at="<?php echo $row['GROUP_ID']; ?>">
                                            <div class="card">
                                                <div style="text-align: center;">
                                                    <img class="card-img-top text-center" src="../../assets/images/uniforms/<?php echo $row['IMG']?>" alt="Card image cap" style="width: 120px; height: 120px;"></img>
                                                </div>
                                                <div class="card-body">
                                                    <h1 class="card-text text-center" style="color: black; font-size: 1.2rem;"><?php echo $row['CATEGORY_TH']?></h1>
                                                    <p class="card-text text-center" style="color: #4b4a50; font-size: 1rem;"> Sony Technology (Thailand)</p>
                                                    <p class="card-text text-black pb-2 pt-1 text-center">คลังสินค้า: 
                                                            <?php if($row['C_STOCK'] == 0){?>
                                                            <span class="badge badge-danger" style="font-size: 1rem;"><?php echo $row['C_STOCK']; ?></span></p>
                                                            <?php } else {?>
                                                            <span class="badge badge-success" style="font-size: 1rem;"><?php echo $row['C_STOCK']; ?></span></p>
                                                            <?php } ?>
                                                </div>
                                                <?php if($row['C_STOCK'] <= 3 && $row['C_STOCK'] <> 0){ ?>
                                                    <div class="alert alert-warning text-center">
                                                        <i class="fas fa-exclamation-triangle"></i> เเจ้งเตือน: สินค้าใกล้หมดเเล้ว <i class="fas fa-exclamation"></i>
                                                    </div>
                                                <?php } elseif($row['C_STOCK'] == 0) { ?>
                                                    <div class="alert alert-danger text-center">
                                                        <i class="fas fa-exclamation-triangle"></i> เเจ้งเตือน: สินค้าหมดเเล้ว <i class="fas fa-exclamation"></i>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="alert alert-success text-center">
                                                        <i class="fas fa-exclamation-triangle"></i> เเจ้งเตือน: สินค้าปกติ <i class="fas fa-exclamation"></i>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                                
                                    
                                </div>
                            </div>

                            <!-- Modal -->
                            <form id="form_stock">
                                <div class="modal fade" id="modal-lg">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content bg-secondary">
                                            <div class="modal-header">
                                                <h4 class="text-glow" class="modal-title"><i class="fas fa-dolly-flatbed"></i> หน้าจัดการสต๊อก</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_modal_1">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="result_stock"></div>
                                                <!-- <input id="value_id" type="hidden"> -->
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal" id="close_modal_2">Close</button>
                                                <button type="submit" class="btn btn-success" name="submit_group" id="submit_group"><i class="fas fa-plus"></i> เพิ่มเข้า</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Modal -->
                            <form id="list_stock">
                                <div class="modal fade" id="modal-stock">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content bg-secondary">
                                            <div class="modal-header">
                                                <h4 class="text-glow" class="modal-title"><i class="fas fa-newspaper"></i> ข้อมูลคลังสินค้า</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_modal_1">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <table  id="trans_logs" 
                                                            class="table table-hover" 
                                                            width="100%" style="text-align: center;">
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal" id="close_modal_2">Close</button>
                                                <!-- <button type="submit" class="btn btn-success" name="submit_grop" id="submit_grop">บันทึก</button> -->
                                            </div>
                                        </div>
                                    </div>
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
<script src="../../plugins/select2/js/select2.full.min.js"></script>

<script>
    $(function() {
        $("#modal-lg").modal({
            show: false,
            backdrop: 'static'
        });

        $("#modal-stock").modal({
            show: false,
            backdrop: 'static'
        });

        $(document).on('click', '#export_excel', function(){
            $("#overlay").fadeIn(300);
            window.location.href = "../../service/maintain/export.php";
            $("#overlay").fadeOut(300);
        });

        

        /* ฟังก์ชันเช็ค stock ในหน้า modal */
        $('body').on('click', '.check-stock-modal', function (event) {
            event.preventDefault();
            
            const check_stock = $(this).attr('at');

            $('#result_stock').html('');
            $.ajax({  
                type: "POST",  
                url: "../../service/maintain/stock.php",  
                data: { id: check_stock },
                dataType:"text"
            }).done(function(data) {
                // const stock = jQuery.parseJSON( data );
                
                $('#result_stock').html(data);
                

                $.ajax({  
                    type: "POST",  
                    url: "../../service/maintain/check-stock.php",  
                    data: { id: check_stock },
                    dataType:"json"
                }).done(function(data) {
                    $('#size').empty().trigger('change');
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    });
                    //เพิ่มค่าใหม่
                    $('#size').append('<option value="">กรุณาเลือกไซต์..</option>')
                    $.each(data ,function(index, el) {

                        $('#size').append('<option value="'+el.SIZE+'">'+el.SIZE+'</option>')
                    })
                    
                    
                    /* เปลี่ยน size เเสดงค่า stock ของเเต่ละ size */
                    $(document).on('change', '#size', function(){
                        const size = $('#size').val();
                        if(size == null) {

                        } else {
                            // console.log(size);
                            $.ajax({  
                                type: "POST",  
                                url: "../../service/maintain/change-size.php",  
                                data: { size:size,id:check_stock,function:'size_change' },
                                dataType:"text"
                            }).done(function(data) {
                                const check_result = jQuery.parseJSON( data );
                                $('#return_stock').html(check_result.amount);
                                
                            })
                        }
                        
                    })

                }).fail(function(data) {
                    const check_log = jQuery.parseJSON( data.responseText );
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด..',
                        text: check_log.message,
                        footer: 'โปรดลองใหม่อีกครั้ง!'
                    }).then((result) => {
                        // location.assign('');
                    })
                })

            }).fail(function(data) {
                window.toastr.remove()
                toastr.warning('ไม่พบข้อมูล')
            })
            
            /* บันทึกฟอร์ม */
            $('#form_stock').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData();

                var size = $('#size').val();
                var value_id = $('#value_id').val();
                var input_number = $('#input_number').val();
                var submit = $('#submit_group').val();

                formData.append('check_stock', check_stock); 
                formData.append('size', size); 
                formData.append('value_id', value_id); 
                formData.append('input_number', input_number); 
                formData.append('submit_group', submit); 

                $.ajax({  
                    type: "POST",  
                    url: "../../service/maintain/update-stock.php",  
                    data: formData,
                    contentType: false,
                    processData: false
                }).done(function(resp) {
                    Swal.fire({
                    text: 'อัพเดทข้อมูลเรียบร้อยเเล้ว',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                    backdrop: `
                        rgba(0,0,123,0.4)
                        url("../../assets/images/nyan-cat.gif")
                        left top
                        no-repeat
                    `
                    }).then((result) => {
                        location.assign('');
                        // console.log('test');
                    });
                }).fail(function(resp) {
                    const check_log = jQuery.parseJSON( resp.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด..',
                        text: check_log.message,
                        footer: 'โปรดลองใหม่อีกครั้ง!'
                    }).then((result) => {
                        location.assign('');
                    })
                })
            })
            /* จบฟอร์ม */

            

        });

        $(document).on('click', '#close_modal_1', function(){
            location.reload()
        });

        $(document).on('click', '#close_modal_2', function(){
            location.reload()
        });
        
        

        /* function เพิ่มเเละลด */
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
        /* จบ function */

        /* Start Datatable */
        $.ajax({
            type: "GET",
            url: "../../service/maintain/trans-stock.php"
        }).done(function(data) {
            
            let tableData = []
            data.response.forEach(function (item, index){
                if(item.STATUS == 'OK') {
                    item.STATUS = `<span class="badge bg-success" style="font-size: 0.9rem;">OK</span>`
                } else if(item.STATUS == 'NG') {
                    item.STATUS = `<span class="badge bg-danger" style="font-size: 0.9rem;">NG</span>`
                }
                if(item.STOCK == 0) {
                    item.STOCK = `<span class="badge bg-danger" style="font-size: 0.9rem;">${item.STOCK}</span>`
                } else {
                    item.STOCK = `<span class="badge bg-warning" style="font-size: 0.9rem;">${item.STOCK}</span>`
                }

                if(item.DEMAND > 0) {
                    item.DEMAND = `<span class="badge bg-primary" style="font-size: 0.9rem;">${item.DEMAND}</span>`
                }
                tableData.push([    
                    ++index,
                    item.CATEGORY_TH,
                    `<img src="../../assets/images/uniforms/${item.IMG}" style="width: 100px; height: 100px;" class="product-image" alt="Product Image">`,
                    item.SIZE,
                    item.STOCK,
                    item.DEMAND,
                    `<span class="badge bg-success" style="font-size: 0.9rem;">${item.REMAIN_STOCK}</span>`,
                    item.STATUS
                ])
            })
            initDataTables(tableData)

            
        }).fail(function() {
            // Swal.fire({ 
            //     text: 'ไม่สามารถเรียกดูข้อมูลได้', 
            //     icon: 'error', 
            //     confirmButtonText: 'ตกลง', 
            // }).then(function() {
            //     location.assign('../dashboard')
            // })
        })

        function initDataTables(tableData) {
            var table = $('#trans_logs').DataTable( {
                data: tableData,
                columns: [
                    { title: "ลำดับ" , className: "align-middle"},
                    { title: "ประเภทสินค้า" , className: "align-middle"},
                    { title: "รูปภาพ" , className: "align-middle"},
                    { title: "ไซต์" , className: "align-middle"},
                    { title: "คลังสินค้า" , className: "align-middle"},
                    { title: "ความต้องการ" , className: "align-middle"},
                    { title: "คงเหลือ", className: "align-middle"},
                    { title: "สถานะ", className: "align-middle"}
                ],
                responsive: true,
                // initComplete: function () {
                //     $(document).on('click', '#cancel_order', function(){ 
                //         let id = $(this).data('id')
                //         let index = $(this).data('index')
                //         let size = $(this).data('size')
                //         // console.log(id);
                //         Swal.fire({
                //             text: "คุณแน่ใจหรือไม่..ที่จะยกเลิกรายการนี้?",
                //             icon: 'warning',
                //             showCancelButton: true,
                //             confirmButtonText: 'ใช่!',
                //             cancelButtonText: 'ยกเลิก'
                //         }).then((result) => {
                //             if (result.isConfirmed) {
                //                 $.ajax({  
                //                     type: "POST",  
                //                     url: "../../service/request/cancel-order.php",  
                //                     data: { id: id,index: index, size:size }
                //                 }).done(function(resp) {
                //                     Swal.fire({
                //                         text: 'รายการของคุณถูกยกเลิกเรียบร้อยเเล้ว..',
                //                         icon: 'success',
                //                         confirmButtonText: 'ตกลง',
                //                     }).then((result) => {
                //                         location.reload()
                //                     })
                //                 }).fail(function(resp) {
                //                     const check_log = jQuery.parseJSON( resp.responseText );
                //                     Swal.fire({
                //                         icon: 'error',
                //                         title: 'เกิดข้อผิดพลาด..',
                //                         text: check_log.message,
                //                         footer: 'กรุณายกเลิกใหม่อีกครั้ง!!'
                //                     }).then((result) => {
                //                         location.reload()
                //                     })
                //                 })
                //             }
                //         })
                //     })
                // },
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal( {
                            header: function ( row ) {
                                var data = row.data()
                                return 'รายละเอียด: ' + data[4]
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
        

            
    })
</script>


</body>
</html>
