<?php 
    require_once('../authen.php');     
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>รอรับสินค้า | VMI</title>
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
                                        <i class="fas fa-check-circle"></i> 
                                        ตรวจสอบรายการย้อนหลัง
                                    </h4>
                                </div>
                                
                                <a href="../request/" class="btn btn-primary my-3 ">
                                    <i class="fas fa-arrow-circle-left"></i>
                                    กลับหน้าก่อนหน้านี้
                                </a>
                            </div>
                            <!-- Initial field -->
                            <div class="card-body">
                                <div class="form-group">
                                    <table  id="receive_logs" 
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
                                                <th style="width: 100px">วันที่รับสินค้า</th>
                                                <th>จัดการ</th>
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


<script>
    $(function(){
        $.ajax({
            type: "GET",
            url: "../../service/receive/traceback_distri.php"
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
                    // item.RECEIVE_DATE,
                    `<div class="btn-group" role="group">
                        <button style="margin-left: -30px; margin-right: -20px; border-radius: 10rem;" type="submit" class="btn btn-danger btn-lg" id="cancel_order" data-order_id="${item.ORDER_ID}" data-index="${item.CART_TRANS}">
                            <i class="fas fa-window-close"></i> ยกเลิก
                        </button>
                    </div>`
                ])
            })
            initDataTables(tableData)
        })

        function initDataTables(tableData) {
            $("#receive_logs").attr("hidden", false);
            var table = $('#receive_logs').DataTable( {
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
                    { title: "วันที่ต้องมารับ", className: "align-middle"},
                    { title: "จัดการ", className: "align-middle"}
                ],
                responsive: true,
                initComplete: function () {
                    $(document).on('click', '#cancel_order', function(){ 
                        let order_id = $(this).data('order_id')
                        let cart_trans = $(this).data('index')

                        Swal.fire({
                            title: "คุณแน่ใจหรือไม่...ที่จะยกเลิกรายการนี้?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'ใช่',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({  
                                    type: "POST",  
                                    url: "../../service/receive/cancel_order_traceback.php",  
                                    data: { order_id: order_id, cart_trans: cart_trans }
                                }).done(function(resp) {
                                    Swal.fire({
                                        text: resp.message,
                                        icon: 'success',
                                        confirmButtonText: 'ตกลง',
                                    }).then((result) => {
                                        location.reload()
                                    })
                                }).fail(function(resp) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด..',
                                        text: resp.message,
                                        footer: 'ลองใหม่อีกครั้ง!!'
                                    }).then((result) => {
                                        location.reload();
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

        $(document).on('click', '#export_excel', function(resp){
            $("#overlay").fadeIn(300);
            setTimeout((resp) => {
                window.location.href = "../../service/request/export.php";
            }, 1000)
            
            $("#overlay").fadeOut(300);
        });

    });
</script>

</body>
</html>
