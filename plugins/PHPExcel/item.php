<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>STARTUP CHECK</title>

    <!-- <link rel="icon" href="framework/img/SMART_LOGO.png" type="image/png" sizes="16x16">
    <link href="framework/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="framework/css/scrolling-nav.css" rel="stylesheet">
    <script src="framework/js/a076d05399.js"></script> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="framework/css/jquery-ui.css">
    <script src="framework/js/jquery-1.12.4.js"></script>
    <script src="framework/js/jquery.min.js"></script>
    <script src="framework/js/jquery-ui.js"></script>
    <script src="framework/js/sweetalert2@9.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.all.min.js"></script>
    <!-- https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.all.min.js -->
    <style>
        input[type=text],
        input[type=password] {
            width: 15%;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type=button],
        input[type=submit] {
            width: 40%;
            height: 60%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
    </style>

</head>

<body>
        <a href="file/STARTUP_ITEM_TEMPLETE.csv" class="btn btn-dark" style="float : right;">
            <i class='fas fa-download' style='color:white'></i>
        </a>

        <form mehtod="post" id="export_excel">
            <label class="btn btn-success">
                
                <input type="file" name="excel_file" id="excel_file">
                <!-- <input type="text" hidden name="table" id="table">
                <input type="text" hidden name="check_biz" id="check_biz">
                <i class='fas fa-upload' style='color:white'></i> -->
            </label>
        </form>

        <script type="text/javascript">
            $(document).ready(function() {

                $('#excel_file').change(function() {
                    $('#export_excel').submit();
                });

                $('#export_excel').on('submit', function(event) {
                    event.preventDefault();
                    console.log(new FormData(this))
                    $.ajax({
                        url: "export.php",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                            $('#result').html(data);
                            $('#excel_file').val('');
                            if (data == "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'อัพโหลดข้อมูลเสร็จสิ้น',
                                })
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    html: 'เกิดข้อผิดพลาดในไฟล์ที่คุณอัปโหลดบรรทัดที่' + data,
                                })
                            }
                        },
                        error: function() {
                            // alert("Error Upload condition.");
                        }
                    })
                })

                $('#export_excel').on('click', function() {
                    $.ajax({
                        url: 'export.php',
                        type: 'POST',
                        data: {
                            data: 'item'
                        },
                        success: function(data) {
                            $('#table').val('item');
                            $('#check_biz').val('<?php echo $MEMBER_ID; ?>');
                        }
                    });
                });
            });
        </script>
    </section>
    <!-- Main -->

</body>

</html>