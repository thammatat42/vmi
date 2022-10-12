<?php 
    require_once('../authen.php'); 

    $date = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate" />
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
  <link rel="stylesheet" href="../../plugins/datatables-checkboxes/css/dataTables.checkboxes.css">

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
                                    <i class="fas fa-check-square"></i> 
                                    รายการสินค้าที่สำเร็จเเล้ว..
                                </h4>
                                <a href="../dashboard" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>

                                <div class="row justify-content-between">
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

                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-warning" name="export_excel" id="export_excel">
                                            <i class="fas fa-arrow-circle-down"></i>
                                            Payment
                                        </button> &nbsp;

                                        <button type="submit" class="btn btn-success" name="export_excel_detail" id="export_excel_detail">
                                            <i class="fas fa-arrow-circle-down"></i>
                                            Export
                                        </button>
                                    
                                    </div>
                                </div>
                                
                            </div>
                            <!-- Initial field -->
                            <!-- <div class="card-body">
                                <table  id="order_logs" 
                                        class="table table-hover" 
                                        width="100%"
                                        style="text-align: center;">
                                </table>
                            </div> -->
                            <input type="hidden" value="<?php echo $date; ?>" id="date_id">

                            <?php 
                                
                                $filename = '../../service/request/excel/report_payment_' .$date. '.csv';
                                if (file_exists($filename)) {
                                    $checkfiles = 0;
                                } else {
                                    $checkfiles = 1;
                                }
                               
                            ?>
                            <input type="hidden" value="<?php echo $checkfiles; ?>" id="file_id">

                            <div class="card-body">
                                <div class="form-group">
                                    <table  id="order_logs" 
                                            class="table table-hover" 
                                            width="100%" style="text-align: center;" hidden>

                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>ลำดับ</th>
                                                <th>สถานะ</th>
                                                <th>รหัสสั่งซื้อ</th>
                                                <th>รูปภาพ</th>
                                                <th>ประเภทสินค้า</th>
                                                <th>ไซต์</th>
                                                <th>จำนวน</th>
                                                <th>ผู้สั่งซื้อ</th>
                                                <th>ราคารวม (บาท)</th>
                                                <th style="width: 100px">วันที่สั่งซื้อ</th>
                                                <th>ผู้จ่ายสินค้า</th>
                                                <th style="width: 100px">วันที่จ่าย</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>รวม</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
<script src="../../plugins/datatables-checkboxes/js/dataTables.checkboxes.min.js"></script>

<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>



<script>
    const date = $('#date_id').val();
    const files = $('#file_id').val();

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

        // cal comma
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
        }

        function load_datatable_order(id_order,value_date,submit) {
            if(id_order == 0) {
                $.ajax({
                    type: "GET",
                    url: "../../service/request/page-success.php"
                }).done(function(data) {
                    
                    let tableData = []
                    data.response.forEach(function (item, index){
                        if(item.STATUS_ID == 0) {
                            item.STATUS_ID = `<span class="badge bg-warning" style="font-size: 0.9rem;">รอรับสินค้า</span>`
                        } else if(item.STATUS_ID == 2) {
                            item.STATUS_ID = `<span class="badge bg-success" style="font-size: 0.9rem;">สำเร็จ</span>`
                        } else if(item.STATUS_ID == 3) {
                            item.STATUS_ID = `<span class="badge bg-danger" style="font-size: 0.9rem;">ยกเลิก</span>`
                            item.hide = `hidden`
                        }

                        // if(item.TOTAL_PRICE == 0) {
                        //     item.TOTAL_PRICE = `<span class="badge bg-info" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`;
                        // } else {
                        //     item.TOTAL_PRICE = `<span class="badge bg-danger" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`;
                        // }
                        tableData.push([    
                            item.ORDER_ID,
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
                            item.DISTRIBUTE_BY,
                            item.DISTRIBUTE_DATE
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
                    url: "../../service/request/page-success.php",
                    data: {date:value_date, submit:submit}
                }).done(function(data) {
                    
                    let tableData = []
                    data.response.forEach(function (item, index){
                        if(item.STATUS_ID == 0) {
                            item.STATUS_ID = `<span class="badge bg-warning" style="font-size: 0.9rem;">รอรับสินค้า</span>`
                        } else if(item.STATUS_ID == 2) {
                            item.STATUS_ID = `<span class="badge bg-success" style="font-size: 0.9rem;">สำเร็จ</span>`
                        } else if(item.STATUS_ID == 3) {
                            item.STATUS_ID = `<span class="badge bg-danger" style="font-size: 0.9rem;">ยกเลิก</span>`
                            item.hide = `hidden`
                        }

                        // if(item.TOTAL_PRICE == 0) {
                        //     item.TOTAL_PRICE = `<span class="badge bg-info" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`;
                        // } else {
                        //     item.TOTAL_PRICE = `<span class="badge bg-danger" style="font-size: 0.9rem;">${item.TOTAL_PRICE} บาท</span>`;
                        // }
                        tableData.push([    
                            item.ORDER_ID,
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
                            item.DISTRIBUTE_BY,
                            item.DISTRIBUTE_DATE
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

            }
        }


        function initDataTables(tableData) {
            $("#order_logs").attr("hidden", false);
            var table = $('#order_logs').DataTable( {
                "bDestroy": true,
                'columnDefs': [{
                    'targets': 0,
                    'searchable':false,
                    'orderable':false,
                    'className': 'dt-body-center',
                    'checkboxes': {
                        'selectRow': true
                    }
                }],
                'order': [1, 'asc'],
                data: tableData,
                columns: [
                    { title: "", className: "align-middle"},
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
                    { title: "ผู้จ่ายสินค้า", className: "align-middle"},
                    { title: "วันที่จ่าย", className: "align-middle"}
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
                    data = api.column( 9 ).data();
                    total = data.length ?
                        data.reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                        } ) :
                        0;
        
                    // ราคารวมเฉพาะหน้านี้
                    data = api.column( 9, { page: 'current'} ).data();
                    pageTotal = data.length ?
                        data.reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                        } ) :
                        0;

                    // Update footer
                    $( api.column( 9 ).footer() ).html(
                        `<span class="badge bg-warning" style="font-size: 0.9rem;">${numberWithCommas(total)} บาท</span>`
                    );

                    $( api.column( 2 ).footer() ).html(
                        `<div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger btn-lg" id="cancel_all_order">
                                <i class="fas fa-window-close"></i> ยกเลิก
                            </button>
                        </div>`
                    );
                },
                responsive: true,
                initComplete: function () {
                    $(document).on('click', '#cancel_all_order', function(){ 

                        const log_alert = "ยืนยันการยกเลิกรายการที่รับสินค้าเเล้วใช่หรือไม่";
                        Swal.fire({
                            title: log_alert,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'ใช่',
                            confirmButtonColor: '#28a745',
                            cancelButtonText: 'ไม่'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#overlay").fadeIn(300);

                                var formData = new FormData();
                                var rows_selected = table.column(0).checkboxes.selected();

                                var order_id = [];
                                $.each(rows_selected, function(index, rowId){
                                    order_id.push(rowId);
                                })
                                
                                formData.append('order_id', order_id);

                                $.ajax({  
                                    type: "POST",  
                                    url: "../../service/request/order-cancel-traceback.php",  
                                    data: formData,
                                    contentType: false,
                                    processData: false
                                }).done(function(resp) {
                                    $("#overlay").fadeOut(300);
                                    Swal.fire({
                                        text: resp.message,
                                        icon: 'success',
                                        confirmButtonText: 'ตกลง',
                                    }).then((result) => {
                                        location.assign('');
                                    })
                                }).fail(function(resp) {
                                    $("#overlay").fadeOut(300);
                                    const check_log = jQuery.parseJSON( resp.responseText );
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด..',
                                        text: check_log.message,
                                        footer: '<p style="color: red;"><u>กรุณาติ๊กเลือกข้อมูลในช่อง Check box!!</u></p>'
                                    }).then((result) => {
                                        location.reload()
                                    })
                                })
                            }
                        })
                    })

                },
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal( {
                            header: function ( row ) {
                                var data = row.data()
                                return 'รายละเอียด: ' + data[3]
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

        $(document).on('click', '#export_excel', function(resp){
            const date_1 = '../../service/request/excel/report_payment_' + date + '.csv';
            // console.log(date_1);
            $("#overlay").fadeIn(300);

            if(files == '0') {
                window.location.href = date_1;
            } else {
                if(files == '1') {
                    window.location.href = "../../service/request/export.php"
                }
                
            }
            $("#overlay").fadeOut(300);
        });

        $(document).on('click', '#export_excel_detail', function(){
            $("#overlay").fadeIn(300);
            window.location.href = "../../service/request/export-order-success.php";
            $("#overlay").fadeOut(300);
        });

    });
</script>
</body>
</html>
