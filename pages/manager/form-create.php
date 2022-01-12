<?php 
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>จัดการผู้ดูแลระบบ | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie2.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
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
                                    ลงทะเบียนเข้าสู่ระบบ
                                </h4>
                                <a href="./" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="container p-2">
                                        <div class="input-group">
                                            <input name="search_text" id="search_text" type="text" class="form-control" placeholder="ค้นหา..ใส่เลขประจำตัว 8 ตัว เช่น 2212xxxx">
                                            <span class="input-group-btn">
                                                <button type="submit" value="Search" class="btn btn-danger" type="button"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <form id="formData" method="post" enctype="multipart/form-data">
                                <div id="result"></div>
                            
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
<script src="../../plugins/toastr/toastr.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>

<script>
    $(function() {
        toastr.options = {
            "positionClass": "toast-top-center"
        }
        /* เพิ่มข้อมูลลงระบบ */
        $('#formData').on('submit', function (e) {
            e.preventDefault();
            var username = $('#username').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var password = $('#password').val();
            var email = $('#email').val();
            var status = $('#permission').val();
            var submit = $('#submit').val();
            var formData = new FormData();

            /* Attach file */
            formData.append('customfile', $('input[type=file]')[0].files[0]); 
            formData.append('username', username); 
            formData.append('first_name', first_name); 
            formData.append('last_name', last_name);  
            formData.append('password', password); 
            formData.append('email', email); 
            formData.append('status', status); 
            formData.append('submit', submit); 
            // console.log(formData);
            // console.log(username);
            $.ajax({
                type: 'POST',
                url: '../../service/manager/create.php',
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
                    // console.log('test');
                });
            }).fail(function(resp) {
                // console.log(resp);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด..',
                    text: 'มีบางอย่างผิดปกติ',
                    footer: 'โปรดอัปโหลดไฟล์ใหม่อีกครั้ง!'
                }).then((result) => {
                    location.assign('');
                })
            })
        });

         /* ค้นหา */
        $('#search_text').keyup(function(e){
            e.preventDefault();
            var txt = $(this).val();
            if(txt == ''){
                // console.log('Text');
            } else {
                // console.log('POST');
                
                $('#result').html('');
                $.ajax({
                    url: "../../service/manager/search.php",
                    method:"post",
                    data:{search:txt},
                    dataType:"text",
                    success:function(data)
                    {
                        window.toastr.remove()
                        $('#result').html(data);
                        toastr.success('ค้นหาสำเร็จ')
                        /* เเสดงรูปภาพ */
                        $('#customfile').on("change", previewImages);

                        function previewImages() {
                            var $preview = $('#preview').empty();
                            if (this.files) $.each(this.files, readAndPreview);


                            function readAndPreview(i, file) {
                                if (!/\.(jpe?g|png|gif)$/i.test(file.name)){
                                    return file.name;
                                } // else...



                                var reader = new FileReader();

                                $(reader).on("load", function() {
                                    $preview.append($("<img/>", {src:this.result, height:100,width:200}).addClass("img-thumbnail"));
                                });
                                reader.readAsDataURL(file);
                            }

                        }
                    },
                    error:function(data)
                    {
                        window.toastr.remove()
                        toastr.warning('ไม่พบผู้ใช้คนนี้')
                    }

                });
            }
        });

        $('body').on('change', '#customfile', function (event) {
            // console.log("test");
            $("#file-name").text(this.files[0].name);
            $("#namefiles").val(this.files[0].name);
        });

        
    });

    
    
</script>

</body>
</html>
