<?php 
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <html lang="en" class="notranslate" translate="no">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate" />
  <title>ข้อมูล master | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie3.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/bootstrap-toggle/bootstrap-toggle.min.css">
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
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
                                    <i class="fas fas fa-book"></i> 
                                    Maintenance
                                </h4>
                                
                                <a href="form-create.php" class="btn btn-success mt-3">
                                    <i class="fas fa-plus"></i>
                                    เพิ่มข้อมูลชุดยูนิฟอร์ม
                                </a>
                                

                                <a href="stock.php" class="btn btn-warning mt-3">
                                    <i class="fas fa-dolly-flatbed"></i>
                                    เช็คสต็อค
                                </a>

                                <a href="stock-master.php" class="btn btn-secondary mt-3">
                                    <i class="fas fa-wrench"></i>
                                    จัดการมาสเตอร์สต็อค
                                </a>
                            </div>
                            <!-- Initial field -->
                            <div class="card-body">
                                <table  id="logs" 
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


<script>

    $(function() {


        toastr.options = {
            "positionClass": "toast-top-center"
        }
        $.ajax({
            type: "GET",
            url: "../../service/maintain/index.php"
        }).done(function(data) {
            let tableData = []
            data.response.forEach(function (item, index){
                tableData.push([    
                    ++index,
                    `<img src="../../assets/images/uniforms/${item.IMG}" style="width: 100px; height: 100px;" class="product-image" alt="Product Image">`,
                    item.CATEGORY_ENG,
                    item.CATEGORY_TH,
                    item.SIZE,
                    item.UNIT_PRICE,
                    item.EMP_TYPE,
                    item.EMP_LEVEL,
                    item.DEPARTMENT,
                    `<div class="btn-group" role="group">
                        <a href="form-edit-master.php?id=${item.ITEM}" type="button" class="btn btn-warning text-white">
                            <i class="fas fa-wrench"></i> แก้ไข
                        </a>

                        <button type="button" class="btn btn-danger" id="delete" data-id="${item.ITEM}" data-index="${item.GROUP_ID}" data-size="${item.SIZE}">
                            <i class="far fa-trash-alt"></i> ลบ
                        </button>
                        
                    </div>`
                ])
            })
            initDataTables(tableData)
        }).fail(function() {
            Swal.fire({ 
                text: 'ไม่สามารถเรียกดูข้อมูลได้', 
                icon: 'error', 
                confirmButtonText: 'ตกลง', 
            }).then(function() {
                location.assign('../dashboard')
            })
        })

        function initDataTables(tableData) {
            var table = $('#logs').DataTable( {
                data: tableData,
                columns: [
                    { title: "ลำดับ" , className: "align-middle"},
                    { title: "รูปภาพ" , className: "align-middle"},
                    { title: "ประเภท (ENG)" , className: "align-middle"},
                    { title: "ประเภท (TH)" , className: "align-middle"},
                    { title: "ไซต์", className: "align-middle"},
                    { title: "ราคา", className: "align-middle"},
                    { title: "ประเภทพนักงาน", className: "align-middle"},
                    { title: "ระดับพนักงาน", className: "align-middle"},
                    { title: "เเผนก", className: "align-middle"},
                    { title: "จัดการ", className: "align-middle"}
                ],
                responsive: true,
                initComplete: function () {
                    $(document).on('click', '#delete', function(){ 
                        let id = $(this).data('id')
                        let index = $(this).data('index')
                        let size = $(this).data('size')
                        // let group = $(this).data('group')

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
                                    data: { id: id,index:index,size:size }
                                }).done(function(resp) {
                                    Swal.fire({
                                        text: 'รายการของคุณถูกลบเรียบร้อย',
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
                                        footer: 'กรุณาตรวจสอบใหม่อีกครั้ง!!'
                                    }).then((result) => {
                                        location.assign('');
                                    })
                                })
                            }
                        })
                    }),

                   $(document).on('change', '.toggle-event', function(){
                        window.toastr.remove()
                        toastr.success('อัพเดทข้อมูลเสร็จเรียบร้อย')
                    })
                },
                fnDrawCallback: function() {
                    $('.toggle-event').bootstrapToggle();
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
        

        $('.product-image-thumb').on('click', function () {
            var $image_element = $(this).find('img')
            $('.product-image').prop('src', $image_element.attr('src'))
            $('.product-image-thumb.active').removeClass('active')
            $(this).addClass('active')
        });
        

    })
</script>
</body>
</html>
