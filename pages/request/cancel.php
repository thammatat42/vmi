<?php 
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ออเดอร์ | VMI</title>
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
                                    <i class="fas fa-cart-plus"></i> 
                                    รายการสินค้าที่ยกเลิก..
                                </h4>
                                <a href="../dashboard" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
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


<script>
    $(function(){
        $.ajax({
            type: "GET",
            url: "../../service/request/page-cancel.php"
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
                    item.DISTRIBUTE_BY,
                    item.DISTRIBUTE_DATE
                ])
            })
            initDataTables(tableData)
        })

        function initDataTables(tableData) {
            var table = $('#order_logs').DataTable( {
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
                    { title: "ยกเลิกโดย", className: "align-middle"},
                    { title: "วันที่ยกเลิก", className: "align-middle"},
                ],
                responsive: true,
                initComplete: function () {
                    $(document).on('click', '#delete', function(){ 
                        let id = $(this).data('id')
                        let index = $(this).data('index')
                        console.log(id);
                        Swal.fire({
                            text: "คุณแน่ใจหรือไม่...ที่จะลบรายการนี้?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'ใช่! ลบเลย',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({  
                                    type: "POST",  
                                    url: "../../service/maintain/delete.php",  
                                    data: { id: id }
                                }).done(function(resp) {
                                    Swal.fire({
                                        text: 'รายการของคุณถูกลบเรียบร้อย',
                                        icon: 'success',
                                        confirmButtonText: 'ตกลง',
                                    }).then((result) => {
                                        location.reload()
                                    })
                                }).fail(function(resp) {
                                    // console.log(resp);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด..',
                                        text: 'มีบางอย่างผิดปกติ',
                                        footer: 'กรุณาลบไฟล์ใหม่อีกครั้ง!!'
                                    }).then((result) => {
                                        location.assign('');
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

    });
</script>
</body>
</html>
