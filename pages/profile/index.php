<?php 
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> ผู้ดูเเลระบบ | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie2.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
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
                                    <i class="fas fa-user-cog"></i> 
                                    โปรไฟล์
                                </h4>
                            </div>
                            <div class="card-body">
                                <table  id="logs" 
                                        class="table table-hover" 
                                        width="100%" style="text-align: center;">
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

<!-- scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>

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
        $.ajax({
            type: "GET",
            url: "../../service/profile/index.php"
        }).done(function(data) {
            let tableData = []
            data.response.forEach(function (item, index){
                tableData.push([    
                    ++index,
                    item.USERNAME,
                    item.FNAME,
                    item.LNAME,
                    item.EMAIL,
                    item.UPDATE_DATE,
                    `<span class="badge badge-primary">${item.STATUS_TYPE}</span>`,
                    `<div class="btn-group" role="group">
                        <a href="form-edit.php?id=${item.UID}" type="button" class="btn btn-warning text-white">
                            <i class="far fa-edit"></i> แก้ไข
                        </a>
                        <button type="button" class="btn btn-danger" id="delete" data-id="${item.UID}" data-index="${index}">
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
                    { title: "ชื่อผู้ใช้งาน" , className: "align-middle"},
                    { title: "ชื่อจริง", className: "align-middle"},
                    { title: "นามสกุล", className: "align-middle"},
                    { title: "อีเมล", className: "align-middle"},
                    { title: "ใช้งานล่าสุด", className: "align-middle"},
                    { title: "สิทธิ์เข้าใช้งาน", className: "align-middle"},
                    { title: "การเปลี่ยนแปลง", className: "align-middle"}
                ],
                responsive: true,
                initComplete: function () {
                    $(document).on('click', '#delete', function(){ 
                        let id = $(this).data('id')
                        let index = $(this).data('index')

                        Swal.fire({
                            title: "คุณแน่ใจหรือไม่...ที่จะลบรายการนี้?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'ใช่! ลบเลย',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({  
                                    type: "POST",  
                                    url: "../../service/profile/delete.php",  
                                    data: { id: id }
                                }).done(function(resp) {
                                    Swal.fire({
                                        text: 'ทำการลบข้อมูลเรียบร้อยเเล้ว..',
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
                                        text: check_log,
                                        footer: 'กรุณาลองใหม่อีกครั้ง!!'
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

    })
</script>
</body>
</html>
