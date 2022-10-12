<?php 
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ออเดอร์ | VMI</title>
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

  <!-- daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">   
  

  <!-- Datatables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.css">
  <link rel="stylesheet" href="../../plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css">

  <style>
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
                                    <i class="fas fa-cart-plus"></i> 
                                    รายการสินค้า
                                </h4>
                                <a href="../dashboard" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>

                                <a href="distribute.php" class="btn btn-success my-3 ">
                                    <i class="fas fa-arrow-alt-circle-up"></i>
                                    ส่งสินค้า
                                </a>

                                <a href="recover_distri.php" class="btn btn-danger my-3 ">
                                    <i class="fas fa-redo-alt"></i>
                                    รายการที่ไม่มารับสินค้า
                                </a>

                                <button type="submit" class="btn btn-success my-3 float-sm-right" name="export_excel" id="export_excel">
                                    <i class="fas fa-arrow-circle-down"></i>
                                    Export
                                </button>

                                <div class="row">
                                    <div class="row" style="background: #ebedff;padding: 5px 15px 5px 15px;border-radius: 5px;">
                                        <label for="date_id" class="col-2 col-form-label">Date</label>
                                        <div class="col-6">
                                            <button style="width: 240px;" type="button" class="form-control" id="daterange-btn">
                                                <i class="far fa-calendar-alt" id="get_date"></i>
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                        </div>
                                        <div class="col-4">
                                            <button style="width: 100px;padding-left:10px;margin-left:30px;" class="btn btn-success" type="submit" id="btn_submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Initial field -->
                            <div class="card-body">
                                <table  id="order_logs" 
                                        class="table table-hover" 
                                        width="100%"
                                        style="text-align: center;">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('../includes/footer.php') ?>
</div>
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<!-- SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>
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

<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>


<script>

    $(document).on('click', '#export_excel', function(){
        $("#overlay").fadeIn(300);
        window.location.href = "../../service/request/export-order.php";
        $("#overlay").fadeOut(300);
    });
    var startDate;
    var endDate;
    var index = 0;
    $(function(){
        $('#daterange-btn').daterangepicker(
            {
                
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    '1st period': [moment().startOf('month'), moment("16/MM/YYYY", "DD/MM/YYYY")],
                    '2nd period': [moment("16/MM/YYYY", "DD/MM/YYYY"), moment("01/MM/YYYY", "DD/MM/YYYY").add(1, 'month')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                opens: 'left',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'DD/MM/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function(start, end) {
                // console.log("Callback has been called!");
                index = 1;
                $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                startDate = start;
                endDate = end;    
            }
        );
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().subtract('days', 29).format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
        var id_order = index;
        load_datatable_order(id_order)


        $('#btn_submit').click(function(){
            if(index == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด..',
                    text: 'โปรดเลือกวันที่ที่ต้องการจะตรวจสอบ!!',
                    footer: 'โปรดลองใหม่อีกครั้ง!'
                }).then((result) => {
                    location.reload();
                })
            } else {
                id_order = 1;

                var submit = $('#btn_submit').val();
                var value_date = startDate.format(' YYYY-MM-DD') + ' to ' + endDate.format('YYYY-MM-DD');
                $('#get_date').html(value_date);
                load_datatable_order(id_order, value_date, submit)
            }
        });

        //create a function
        function load_datatable_order(id_order,value_date,submit) {
            if(id_order == 0) {
                $.ajax({
                    type: "GET",
                    url: "../../service/request/index.php"
                }).done(function(data) {
                    
                    let tableData = []
                    data.response.forEach(function (item, index){
                        if(item.STATUS_ID == 0) {
                            item.STATUS_ID = `<span class="badge bg-warning" style="font-size: 0.9rem;">รอรับสินค้า</span>`
                        } else if(item.STATUS_ID == 2) {
                            item.STATUS_ID = `<span class="badge bg-success" style="font-size: 0.9rem;">สำเร็จ</span>`
                            item.hide = `hidden`
                        } else if(item.STATUS_ID == 3) {
                            item.STATUS_ID = `<span class="badge bg-danger" style="font-size: 0.9rem;">ยกเลิก</span>`
                            item.hide = `hidden`
                        }

                        if(item.TOTAL_PRICE == 0) {
                            item.TOTAL_PRICE = `<span class="badge bg-info" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`;
                        } else {
                            item.TOTAL_PRICE = `<span class="badge bg-danger" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`;
                        }

                        tableData.push([    
                            ++index,
                            item.STATUS_ID,
                            `<button type="button" class="btn btn-primary copy-text" id="click_copy" at="${item.CART_TRANS}">${item.CART_TRANS}</button>`,
                            `<img src="../../assets/images/uniforms/${item.IMG}" style="width: 100px; height: 100px;" class="product-image" alt="Product Image">`,
                            item.CATEGORY_TH,
                            item.SIZE,
                            `<span class="badge bg-success" style="font-size: 0.9rem;">${item.QTY} ชิ้น</span>`,
                            item.ORDER_BY,
                            // `<span class="badge bg-info" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`,
                            item.TOTAL_PRICE,
                            item.ORDER_DATE,
                            `<span class="badge bg-success" style="font-size: 0.9rem;">${item.RECEIVE_DATE}</span>`,
                            // `<div class="btn-group" role="group" ${item.hide}>
                            //     <button type="button" class="btn btn-success text-white" name="order_success" id="order_success" data-id="${item.ORDER_ID}" data-index="${item.CART_TRANS}">
                            //         <i class="fas fa-arrow-alt-circle-up"></i> ส่งสินค้า
                            //     </button>
                            // </div>`
                        ])
                    })
                    initDataTables(tableData)
                }).fail(function(data) {
                    const check_log = jQuery.parseJSON( data.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด..',
                        text: check_log.message,
                        footer: 'โปรดลองใหม่อีกครั้ง!'
                    }).then((result) => {
                        location.assign('');
                    })
                })

            } else {
                $.ajax({
                    type: "POST",
                    url: "../../service/request/index.php",
                    data: {date:value_date, submit:submit}
                }).done(function(data) {
                    
                    let tableData = []
                    data.response.forEach(function (item, index){
                        if(item.STATUS_ID == 0) {
                            item.STATUS_ID = `<span class="badge bg-warning" style="font-size: 0.9rem;">รอรับสินค้า</span>`
                        } else if(item.STATUS_ID == 2) {
                            item.STATUS_ID = `<span class="badge bg-success" style="font-size: 0.9rem;">สำเร็จ</span>`
                            item.hide = `hidden`
                        } else if(item.STATUS_ID == 3) {
                            item.STATUS_ID = `<span class="badge bg-danger" style="font-size: 0.9rem;">ยกเลิก</span>`
                            item.hide = `hidden`
                        }

                        if(item.TOTAL_PRICE == 0) {
                            item.TOTAL_PRICE = `<span class="badge bg-info" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`;
                        } else {
                            item.TOTAL_PRICE = `<span class="badge bg-danger" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`;
                        }

                        tableData.push([    
                            ++index,
                            item.STATUS_ID,
                            `<button type="button" class="btn btn-primary copy-text" id="click_copy" at="${item.CART_TRANS}">${item.CART_TRANS}</button>`,
                            `<img src="../../assets/images/uniforms/${item.IMG}" style="width: 100px; height: 100px;" class="product-image" alt="Product Image">`,
                            item.CATEGORY_TH,
                            item.SIZE,
                            `<span class="badge bg-success" style="font-size: 0.9rem;">${item.QTY} ชิ้น</span>`,
                            item.ORDER_BY,
                            // `<span class="badge bg-info" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`,
                            item.TOTAL_PRICE,
                            item.ORDER_DATE,
                            `<span class="badge bg-success" style="font-size: 0.9rem;">${item.RECEIVE_DATE}</span>`,
                            // `<div class="btn-group" role="group" ${item.hide}>
                            //     <button type="button" class="btn btn-success text-white" name="order_success" id="order_success" data-id="${item.ORDER_ID}" data-index="${item.CART_TRANS}">
                            //         <i class="fas fa-arrow-alt-circle-up"></i> ส่งสินค้า
                            //     </button>
                            // </div>`
                        ])
                    })
                    initDataTables(tableData)
                }).fail(function(data) {
                    const check_log = jQuery.parseJSON( data.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด..',
                        text: check_log.message,
                        footer: '<p style="color: red;"><u>จะทำการรีโหลดข้อมูลทั้งหมดอีกครั้ง!</u></p>'
                    }).then((result) => {
                        location.assign('');
                    })
                })
            }
        }

        function initDataTables(tableData) {
            var table = $('#order_logs').DataTable( {
                "bDestroy": true,
                data: tableData,
                columns: [
                    { title: "ลำดับ" , className: "align-middle"},
                    { title: "สถานะ" , className: "align-middle"},
                    { title: "รหัสสั่งซื้อ" , className: "align-middle"},
                    { title: "รูปภาพ" , className: "align-middle"},
                    { title: "ประเภทสินค้า" , className: "align-middle"},
                    { title: "ไซต์" , className: "align-middle"},
                    { title: "จำนวน" , className: "align-middle"},
                    { title: "ผู้สั่งซื้อ", className: "align-middle"},
                    { title: "ราคารวม (บาท)", className: "align-middle"},
                    { title: "วันที่สั่งซื้อ", className: "align-middle"},
                    { title: "วันทีรับสินค้า", className: "align-middle"}
                    // { title: "จัดการ", className: "align-middle"}
                ],
                responsive: true,
                // initComplete: function () {
                //     $(document).on('click', '#order_success', function(){ 
                //         let id = $(this).data('id')
                //         let index = $(this).data('index')
                        
                //         const log_alert = "ยืนยันการส่งสินค้าของรหัสสินค้า: " + index;
                //         Swal.fire({
                //             title: log_alert,
                //             icon: 'warning',
                //             showCancelButton: true,
                //             confirmButtonText: 'ตกลง',
                //             confirmButtonColor: '#28a745',
                //             cancelButtonText: 'ยกเลิก'
                //         }).then((result) => {
                //             if (result.isConfirmed) {
                //                 $.ajax({  
                //                     type: "POST",  
                //                     url: "../../service/request/order-success.php",  
                //                     data: { id: id }
                //                 }).done(function(resp) {
                //                     const log_success = "รหัสสินค้า: " + index + " ส่งสินค้าเรียบร้อยเเล้ว..";
                //                     Swal.fire({
                //                         text: log_success,
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
                //                         text: check_log,
                //                         footer: 'กรุณาตรวจสอบใหม่อีกครั้ง!!'
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

        $(document).on('click', '#click_copy', function(){ 
            const copy_text = $(this).attr('at');
            navigator.clipboard.writeText(copy_text);
            window.toastr.remove()
            toastr.success('Copy เรียบร้อยเเล้ว..')
        });

    });
</script>
</body>
</html>
