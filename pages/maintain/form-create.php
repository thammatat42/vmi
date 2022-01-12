<?php 
    require_once('../authen.php');
    require_once '../../service/connect.php';

    $stmt = $connect->prepare("SELECT MAX(GROUP_ID) + 1 AS NEW_GROUP FROM tb_group_master");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    $group_id = $connect->query("SELECT CATEGORY_TH, CATEGORY_ENG, IMG, GROUP_ID FROM tb_group_master WHERE STATUS_CHG = '0'");

    $result_dept = $connect->query("SELECT DISTINCT DEPARTMENT_FULL_NAME FROM tb_master_department WHERE STATUS_CHG = 0 ORDER BY 1");
    $result_type = $connect->query("SELECT DISTINCT GROUP_ID,EMP_TYPE FROM tb_emp_group_master WHERE STATUS_CHG = '0' ORDER BY 1");

?>
<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate" />
  <title>จัดการผู้ดูแลระบบ | VMI</title>
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
                                    <i class="fas fa-user-cog"></i> 
                                    เพิ่มข้อมูลชุดยูนิฟอร์ม
                                </h4>
                                <a href="./" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
                                <button type="button" class="btn btn-warning my-3" data-toggle="modal" data-target="#modal-lg" id="click_group">
                                    <i class="fas fa-plus"></i>
                                    เพิ่มกรุ๊ป
                                </button>
                                
                            </div>
                            
                            <!-- Modal -->
                            <form id="new_group">
                                <div class="modal fade" id="modal-lg">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content bg-secondary">
                                            <div class="modal-header">
                                                <h4 class="text-glow" class="modal-title"><i class="fas fa-plus"></i> เพิ่มกรุ๊ปใหม่</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <table  id="group_logs" 
                                                            class="table table-hover" 
                                                            width="100%" style="text-align: center;">
                                                    </table>
                                                </div>
                                                <p style="color: red;">เเนะนำ: ใส่ชื่อหมวดหมู่ที่ต้องการเพิ่ม&hellip;</p>

                                                <div class="form-group">
                                                    <label for="group">กรุ๊ป</label>
                                                    <input type="text" class="form-control" name="group" id="group" placeholder="กรุ๊ป" value="<?php echo $result->NEW_GROUP; ?>" disabled>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="group_category_ENG">หมวดหมู่อังกฤษ</label>
                                                    <input type="text" class="form-control" name="group_category_ENG" id="group_category_ENG" placeholder="หมวดหมู่อังกฤษ">
                                                </div>

                                                <div class="form-group">
                                                    <label for="group_category_TH">หมวดหมู่ไทย</label>
                                                    <input type="text" class="form-control" name="group_category_TH" id="group_category_TH" placeholder="หมวดหมู่ไทย">
                                                </div>
                                                
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success" name="submit_grop" id="submit_grop">บันทึก</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Modal Edit -->
                            <form id="new_group_edit">
                                <div class="modal fade" id="modal-edit">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content bg-secondary">
                                            <div class="modal-header">
                                                <h4 class="text-glow" class="modal-title"><i class="fas fa-edit"></i> เเก้ไขข้อมูล</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p style="color: red;">เเนะนำ: ควรตรวจทานข้อมูลทุกครั้งหลังกรอกข้อมูลเสร็จเเล้ว&hellip;</p>

                                                <div id="result"></div>
                                                <input id="value_id" type="hidden">
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success" name="submit_edit" id="submit_edit">บันทึก</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <form id="formData" method="post" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 px-1 px-md-5">
                                            <div class="form-group">
                                                <label for="group_id">กรุ๊ป</label>
                                                <select class="form-control select2bs4" name="group_id" id="group_id" style="width: 100%;">
                                                    <option value disabled selected>กรุณาเลือกกรุ๊ป</option>
                                                <?php 
                                                    foreach($group_id as $row){
                                                        echo '<option value="'.$row['GROUP_ID'].'">'.$row['GROUP_ID'].'</option>';
                                                    }
                                                ?>
                                                </select>
                                            </div>

                                            <div id="result_cate"></div>

                                        </div>
                                        <div class="col-md-6 px-1 px-md-5">


                                            <div class="form-group" id="type_hidden" hidden>
                                                <label for="type_emp">ประเภทพนักงาน</label>
                                                <select class="form-control select2bs4" name="type_emp" id="type_emp" style="width: 100%;">
                                                    <option value disabled selected>กรุณาเลือกประเภทพนักงาน</option>
                                                    <option value="0">All</option>
                                                <?php 
                                                    foreach($result_type as $row){
                                                        echo '<option value="'.$row['GROUP_ID'].'">'.$row['EMP_TYPE'].'</option>';
                                                    }
                                                ?>
                                                </select>
                                            </div>


                                            <div class="form-group" id="level_hidden" hidden>
                                                <label for="level_emp">ระดับพนักงาน</label>
                                                <select class="form-control select2bs4" name="level_emp" id="level_emp" style="width: 100%;">
                                                    <option value disabled selected>กรุณาเลือกระดับพนักงาน</option>    
                                                </select>
                                            </div>

                                            <div class="form-group" id="dept" hidden>
                                                <label for="department">เเผนก</label>
                                                <select class="form-control select2bs4" name="department" id="department" style="width: 100%;">
                                                <option disabled selected>กรุณาเลือกเเผนก</option>    
                                                <option value="All">All</option>
                                                <option value="DIRECT">DIRECT</option>
                                                <option value="INDIRECT">INDIRECT</option>
                                                <option value="Process Eng./AM/WSS">Process Eng./AM/WSS</option>
                                                    <?php 
                                                        foreach($result_dept as $row){
                                                            echo '<option value="'.$row['DEPARTMENT_FULL_NAME'].'">'.$row['DEPARTMENT_FULL_NAME'].'</option>';
                                                        }
                                                    ?>
                                                </select>
                                                
                                            </div>
                                            <div id="result_dept"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit" id="submit">บันทึกข้อมูล</button>
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
        /* ประกาศตัวแปร */
        
        $('body').on('change', '#group_id', function (event) {
            var group_id = $('#group_id').val();
            // console.log(group_id);

            $('#result_cate').html('');
            $('#result_dept').html('');
            $.ajax({  
                type: "POST",  
                url: "../../service/maintain/select-group.php",  
                data: { id: group_id },
                dataType:"text"
            }).done(function(data) {
                const cate = jQuery.parseJSON( data );
                // console.log(cate.cate);
              
                $('#result_cate').html(cate.cate);
                $('#result_dept').html(cate.dept);

                $("#dept").attr("hidden", false);
                $("#type_hidden").attr("hidden", false);
                $("#level_hidden").attr("hidden", true);

                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

                
            }).fail(function(data) {
                window.toastr.remove()
                toastr.warning('ไม่พบข้อมูล')

                $("#dept").attr("hidden", true);
                $("#type_hidden").attr("hidden", true);
                $("#level_hidden").attr("hidden", true);
            })

        });

        $('#type_emp').change(function() {
            var value_type_emp = $('#type_emp').val();
            $('#type_emp').removeClass('is-invalid');

            $.ajax({  
                type: "POST",  
                url: "../../service/maintain/change-type.php",  
                data: { id:value_type_emp,function:'emp_type' },
                dataType:"text"
            }).done(function(data) {
                $("#level_hidden").attr("hidden", false);
                $('#level_emp').html(data);
                
            }).fail(function(data) {
                window.toastr.remove()
                toastr.warning('ไม่พบข้อมูล')
            })

        });

        $('#level_emp').change(function() {
            $('#level_emp').removeClass('is-invalid');

        });

        $('#department').change(function() {
            $('#department').removeClass('is-invalid');

        });


        $('.select2').select2()

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        $("#modal-lg").modal({
            show: false,
            backdrop: 'static'
        });

        $("#modal-edit").modal({
            show: false,
            backdrop: 'static'
        });

        /* Start Datatable */
        $.ajax({
            type: "GET",
            url: "../../service/maintain/check-group.php"
        }).done(function(data) {
            let tableData = []
            data.response.forEach(function (item, index){
                tableData.push([    
                    // ++index,
                    item.GROUP_ID,
                    `<img src="../../assets/images/uniforms/${item.IMG}" style="width: 100px; height: 100px;" class="product-image" alt="Product Image">`,
                    item.CATEGORY_TH,
                    item.CATEGORY_ENG,
                    `<div class="btn-group" role="group">
                        <button type="button" class="btn btn-warning text-white" id="group_edit" data-id="${item.GROUP_ID}" data-index="${index}" data-toggle="modal" data-target="#modal-edit">
                            <i class="far fa-edit"></i> แก้ไข
                        </button>
                        <button type="button" class="btn btn-danger" id="delete" data-id="${item.GROUP_ID}" data-index="${index}">
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
            var table = $('#group_logs').DataTable( {
                data: tableData,
                columns: [
                    { title: "กรุ๊ป" , className: "align-middle"},
                    { title: "รูปภาพ" , className: "align-middle"},
                    { title: "หมวดหมู่ไทย" , className: "align-middle"},
                    { title: "หมวดหมู่อังกฤษ", className: "align-middle"},
                    { title: "การเปลี่ยนแปลง", className: "align-middle"}
                ],
                responsive: true,
                pageLength : 3,
                lengthMenu: [[3, 10, 100, -1], [3, 10, 100, 'ทั้งหมด']],
                initComplete: function () {
                    $(document).on('click', '#delete', function(){ 
                        let id = $(this).data('id')
                        let index = $(this).data('index')
                        // console.log(id);
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
                                    url: "../../service/maintain/group-delete.php",  
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

                    $(document).on('click', '#group_edit', function(){ 
                        let id = $(this).data('id')
                        // let value_id = $(this).data('value_id')
                        let index = $(this).data('index')

                        var check_id = $('#value_id').val(id);
                        // console.log(id);

                        $('#result').html('');
                        $.ajax({  
                            type: "POST",  
                            url: "../../service/maintain/group-search.php",  
                            data: { id: id },
                            dataType:"text"
                        }).done(function(data) {
                            $('#result').html(data);
                            
                        }).fail(function(data) {
                            window.toastr.remove()
                            toastr.warning('ไม่พบข้อมูล')
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

        /* เพิ่มข้อมูลกรุ๊ป */
        $('#new_group').validate({
            rules: {
                group_category_ENG: {
                required: true
                },
                group_category_TH: {
                required: true
                // minlength: 3
                },
            },
            messages: {
                group_category_ENG: {
                required: "กรุณาใส่ชื่อหมวดหมู่เป็นภาษาอังกฤษ"
                },
                group_category_TH: {
                required: "กรุณาใส่ชื่อหมวดหมู่เป็นภาษาไทย"
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });


        $('#new_group').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData();
            var group = $('#group').val();
            var group_category_ENG = $('#group_category_ENG').val();
            var group_category_TH = $('#group_category_TH').val();
            var submit_grop = $('#submit_grop').val();

            formData.append('group', group); 
            formData.append('group_category_ENG', group_category_ENG); 
            formData.append('group_category_TH', group_category_TH);  
            formData.append('submit_grop', submit_grop);  


            $.ajax({
                type: 'POST',
                url: '../../service/maintain/create.php',
                data: formData,
                contentType: false,
                processData: false
                // data: $(this).serialize()
            }).done(function(resp) {
                Swal.fire({
                    text: 'เพิ่มข้อมูลเรียบร้อย',
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
                });
            }).fail(function(resp) {
                const check_log = jQuery.parseJSON( resp.responseText );
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด..',
                    text: check_log.message,
                    footer: 'โปรดลองใหม่อีกครั้ง!'
                }).then((result) => {
                    // location.assign('');
                })
            })
        });


        /* End ข้อมูลเพิ่มกรุ๊ป */
        
        
        /* Edit */
        $('#new_group_edit').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData();
            var group_category_ENG_edit = $('#group_category_ENG_edit').val();
            var group_category_TH_edit = $('#group_category_TH_edit').val();
            var submit = $('#submit_edit').val();

            var check_id = $('#value_id').val();

            formData.append('customfile', $('input[type=file]')[0].files[0]); 
            formData.append('group_category_ENG_edit', group_category_ENG_edit); 
            formData.append('group_category_TH_edit', group_category_TH_edit); 
            formData.append('submit_edit', submit);
            formData.append('value_id', check_id);

            $.ajax({
                type: 'POST',
                url: '../../service/maintain/group-update.php',
                data: formData,
                contentType: false,
                processData: false
                // data: $(this).serialize()
            }).done(function(resp) {
                Swal.fire({
                    text: 'เเก้ไขข้อมูลเรียบร้อยเเล้ว',
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
                const check_log = jQuery.parseJSON( resp.responseText );
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด..',
                    text: check_log.message,
                    footer: 'โปรดอัปโหลดไฟล์ใหม่อีกครั้ง!'
                }).then((result) => {
                    location.assign('');
                })
            })
        });
        

        /* เพิ่มข้อมูลลงระบบ */

        $('#formData').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData();
            var group_id = $('#group_id').val();
            var category_eng = $('#category_eng').val();
            var category_th = $('#category_th').val();
            var size = $('#size').val();
            var price = $('#price').val();
            var type_emp = $('#type_emp').val();
            var level_emp = $('#level_emp').val();
            var department = $('#department').val();
            var submit = $('#submit').val();
            var namefiles = $("#namefiles_create").val();

            

            /* Attach file */
            formData.append('customfile', $('input[type=file]')[0].files[0]); 
            
            formData.append('group_id', group_id); 
            formData.append('category_eng', category_eng); 
            formData.append('category_th', category_th);  
            formData.append('size', size); 
            formData.append('price', price); 
            formData.append('type_emp', type_emp); 
            formData.append('level_emp', level_emp); 
            formData.append('department', department); 
            formData.append('submit', submit); 
            formData.append('namefiles', namefiles); 


            $.ajax({
                type: 'POST',
                url: '../../service/maintain/master-create.php',
                data: formData,
                contentType: false,
                processData: false
                // data: $(this).serialize()
            }).done(function(resp) {
                Swal.fire({
                    text: 'เพิ่มข้อมูลเรียบร้อย',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                    backdrop: `
                        rgba(0,0,123,0.4)
                        url("../../assets/images/nyan-cat.gif")
                        left top
                        no-repeat
                    `
                }).then((result) => {
                    location.assign('./');
                });
            }).fail(function(resp) {
                /* รับค่า response จาก ajax เเละดึงค่ามาใช้เป็น message */
                const check_log = jQuery.parseJSON( resp.responseText );
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด..',
                    text: check_log.message,
                    footer: 'กรุณาลองใหม่อีกครั้ง!'
                }).then((result) => {
                    // location.assign('');
                })
            })

        });

        $('#formData').validate({
            rules: {
                size: {
                required: true
                },
                price: {
                required: true
                },
                type_emp: {
                required: true
                },
                level_emp: {
                required: true
                },
                department: {
                required: true
                },
            },
            messages: {
                size: {
                required: "กรุณากรอกข้อมูล",
                },
                price: {
                required: "กรุณากรอกข้อมูล",
                },
                type_emp: {
                required: "กรุณาเลือกประเภทพนักงาน",
                },
                level_emp: {
                required: "กรุณาเลือกระดับพนักงาน",
                },
                department: {
                required: "กรุณาเลือกเเผนก",
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        /* End ข้อมูลเพิ่มเข้าระบบ */

        
        /* เเสดงชื่อไฟล์ ทั้งกรุ๊ปเเละในหน้า master */
        $('body').on('change', '#customfile', function (event) {
            // console.log("test");
            $("#file-name").text(this.files[0].name);
            $("#namefiles").val(this.files[0].name);
        });

        $('body').on('change', '#customfile_create', function (event) {
            $("#file-name-create").text(this.files[0].name);
            $("#namefiles_create").val(this.files[0].name);
        });

        

    });


</script>


</body>
</html>
