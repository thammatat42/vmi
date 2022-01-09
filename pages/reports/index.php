<?php 
    require_once('../authen.php'); 

    $show_date = date("Y-m-d");
    $select_date = date("Y-m-d");

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>รีพอร์ท | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie2.ico">
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
      .col-6 {
        max-width: 55%;
      }
      .col-4 { 
        max-width: 10%;
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
                        <div class="card shadow">
                            <div class="card-header border-0 pt-4">
                                <div class="row justify-content-between">
                                    <h4>
                                        <i class="fas fa-clipboard-list"></i> 
                                        รีพอร์ทโดยรวม
                                    </h4>
                                </div>
                                
                                <a href="../dashboard/" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>

                                <a href="../request/success.php" class="btn btn-success my-3 ">
                                    <i class="fas fa-arrow-circle-down"></i>
                                    ดาวน์โหลดรายงาน
                                </a>
                            
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
                                            <button style="width: 100px;padding-left:10px;margin-left:20px;" class="btn btn-success" type="submit" id="btn_submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Initial field -->
                            <div class="card-body">
                                <div class="form-group">
                                    <table  id="report_logs" 
                                            class="table table-hover" 
                                            width="100%" style="text-align: center;" hidden>

                                        <thead>
                                            <tr>
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
    var startDate;
    var endDate;
    var index = 0;

    $(document).ready(function() {
        $('#daterange-btn').daterangepicker(
            {
                
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
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
                var submit = $('#btn_submit').val();
                var value_date = startDate.format(' YYYY-MM-DD') + ' to ' + endDate.format('YYYY-MM-DD');
                $('#get_date').html(value_date);
                
                loadDataTables()
                
                function numberWithCommas(x) {
                    return x.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
                }

                /* Start Datatables */
                function loadDataTables() {
                    $.ajax({
                        type: "POST",
                        url: "../../service/reports/index.php",
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
                                numberWithCommas(item.TOTAL_PRICE),
                                item.ORDER_DATE,
                                item.DISTRIBUTE_BY,
                                item.DISTRIBUTE_DATE
                            ])
                        })
                        initDataTables(tableData)
                    }).fail(function(data) {
                        const check_log = jQuery.parseJSON( data.responseText );
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด..',
                            text: check_log.message,
                            footer: 'โปรดลองใหม่อีกครั้ง!'
                        }).then((result) => {
                            location.reload();
                        })
                    })
                }

                function initDataTables(tableData) {
                    $("#report_logs").attr("hidden", false);
                    var table = $('#report_logs').DataTable( {
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
                            data = api.column( 8 ).data();
                            total = data.length ?
                                data.reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                } ) :
                                0;
                
                            // ราคารวมเฉพาะหน้านี้
                            data = api.column( 8, { page: 'current'} ).data();
                            pageTotal = data.length ?
                                data.reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                } ) :
                                0;

                            // Update footer
                            $( api.column( 8 ).footer() ).html(
                                `<span class="badge bg-warning" style="font-size: 0.9rem;">${numberWithCommas(pageTotal)} บาท</span>`
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
            }
        });

 });

</script>

</body>
</html>
