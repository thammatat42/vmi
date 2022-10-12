<?php 
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>เเก้ไขรหัสผ่าน | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie3.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">

  <style>
        .text-glow {
            text-shadow: 0 0 80px rgb(192 219 255 / 75%), 0 0 32px rgb(65 120 255 / 24%);
        }

        * {
        border: 0;
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    :root {
        font-size: calc(16px + (24 - 16)*(100vw - 320px)/(1920 - 320));
    }
    button, input {
        font: 1em Hind, sans-serif;
        line-height: 1.5em;
    }
    input {
        color: #171717;
    }
    .search-bar {
        display: flex;
    }
 
    .search-bar input,
    .search-btn, 
    .search-btn:before, 
    .search-btn:after {
        transition: all 0.25s ease-out;
    }
    .search-bar input,
    .search-btn {
        width: 3em;
        height: 3em;
    }
    .search-bar input:invalid:not(:focus),
    .search-btn {
        cursor: pointer;
    }
    .search-bar,
    .search-bar input:focus,
    .search-bar input:valid  {
        width: 100%;
    }
    .search-bar input:focus,
    .search-bar input:not(:focus) + .search-btn:focus {
        outline: transparent;
    }
    .search-bar {
        margin: auto;
        padding: 1.5em;
        justify-content: center;
        max-width: 30em;
    }
    .search-bar input {
        background: transparent;
        border-radius: 1.5em;
        box-shadow: 0 0 0 0.4em #171717 inset;
        padding: 0.75em;
        transform: translate(0.5em,0.5em) scale(0.5);
        transform-origin: 100% 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    .search-bar input::-webkit-search-decoration {
        -webkit-appearance: none;
    }
    .search-bar input:focus,
    .search-bar input:valid {
        background: #fff;
        border-radius: 0.375em 0 0 0.375em;
        box-shadow: 0 0 0 0.1em #d9d9d9 inset;
        transform: scale(1);
    }
    .search-btn {
        background: #171717;
        border-radius: 0 0.75em 0.75em 0 / 0 1.5em 1.5em 0;
        padding: 0.75em;
        position: relative;
        transform: translate(0.25em,0.25em) rotate(45deg) scale(0.25,0.125);
        transform-origin: 0 50%;
    }
    .search-btn:before, 
    .search-btn:after {
        content: "";
        display: block;
        opacity: 0;
        position: absolute;
    }
    .search-btn:before {
        border-radius: 50%;
        box-shadow: 0 0 0 0.2em #f1f1f1 inset;
        top: 0.75em;
        left: 0.75em;
        width: 1.2em;
        height: 1.2em;
    }
    .search-btn:after {
        background: #f1f1f1;
        border-radius: 0 0.25em 0.25em 0;
        top: 51%;
        left: 51%;
        width: 0.75em;
        height: 0.25em;
        transform: translate(0.2em,0) rotate(45deg);
        transform-origin: 0 50%;
    }
    .search-btn span {
        display: inline-block;
        overflow: hidden;
        width: 1px;
        height: 1px;
    }

    /* Active state */
    .search-bar input:focus + .search-btn,
    .search-bar input:valid + .search-btn {
        background: #2762f3;
        border-radius: 0 0.375em 0.375em 0;
        transform: scale(1);
    }
    .search-bar input:focus + .search-btn:before, 
    .search-bar input:focus + .search-btn:after,
    .search-bar input:valid + .search-btn:before, 
    .search-bar input:valid + .search-btn:after {
        opacity: 1;
    }
    .search-bar input:focus + .search-btn:hover,
    .search-bar input:valid + .search-btn:hover,
    .search-bar input:valid:not(:focus) + .search-btn:focus {
        background: #0c48db;
    }
    .search-bar input:focus + .search-btn:active,
    .search-bar input:valid + .search-btn:active {
        transform: translateY(1px);
    }

    @media screen and (prefers-color-scheme: dark) {
        body, input {
            color: #f1f1f1;
        }
        body {
            background: #171717;
        }
        .search-bar input {
            box-shadow: 0 0 0 0.4em #f1f1f1 inset;
        }
        .search-bar input:focus,
        .search-bar input:valid {
            background: #3d3d3d;
            box-shadow: 0 0 0 0.1em #3d3d3d inset;
        }
        .search-btn {
            background: #f1f1f1;
        }
    }
    
    legend {
        display: block;
        width: 100%;
        padding: 0;
        margin-bottom: 20px;
        font-size: 21px;
        line-height: inherit;
        color: #333;
        border: 0;
        border-bottom: 1px solid #e5e5e5;
    }

    p.membership_detail span {
		font-weight: 300;
		margin-left: 15px;
	}
	p.membership_detail {
		font-size: 16px;
		font-weight: 600;
		font-family: 'Montserrat',Helvetica,Arial,Lucida,sans-serif!important;
	    border-bottom: 1px solid #ebeff2;
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
                                    <i class="fas fa-user-cog"></i> 
                                    จัดการรหัสผ่านผู้ใช้งาน
                                </h4>
                                <a href="./" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
                            </div>
                            <!-- Initial field -->
                            <form id="search_submit_form" class="search-bar">
                                <input style="width: 250px" type="text" name="search_text" id="search_text" pattern=".*\S.*" maxlength="10" onKeyPress="if(this.value.length==10) return 10;" placeholder="ไอดีพนักงาน.. ">
                                <button class="search-btn" type="submit">
                                    <span>Search..</span>
                                </button>
                                
                            </form>
                            <div id="result"></div>

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

<script>
    $(document).ready( function () {
        $('#search_submit_form').on('submit', function(e) {
            e.preventDefault();

            const txt = $('#search_text').val();
            const txt_length = $('#search_text').val().length;

            if(txt_length == 8) {
                if(txt == ''){
                    window.toastr.remove()
                    toastr.warning('ไม่พบผู้ใช้คนนี้')
                    console.clear();
                } else {
                    $('#result').html('');
                    $.ajax({
                        type: "POST",
                        url: "../../service/manager/search-uid-reset.php",
                        data:{search:txt},
                        dataType:"text",
                        success:function(data)
                        {
                            $('#result').html(data);
                            const uid = $('#uid_profile').val();
                            window.toastr.remove()
                            toastr.success('ค้นหาสำเร็จ');

                            $(document).on('click', '#btn_reset', function(){ 
                                Swal.fire({
                                    title: "Are you sure to reset?",
                                    icon: 'warning',
                                    input: 'text',
                                    inputAttributes: {
                                        autocapitalize: 'off'
                                    },
                                    showCancelButton: true,
                                    confirmButtonText: 'Confirm!',
                                    confirmButtonColor: '#31DD19',
                                    cancelButtonText: 'Cancle',
                                    showLoaderOnConfirm: true,
                                    preConfirm: (remark) => {
                                        const remark_log = remark;
                                    },
                                    allowOutsideClick: () => !Swal.isLoading()

                                }).then((result) => {
                                    const remark_log = result.value;

                                    if (result.isConfirmed && remark_log != '') {
                                        $.ajax({  
                                            type: "POST",  
                                            url: "../../service/manager/reset-password-user.php",  
                                            data: { uid: uid, remark_log: remark_log }
                                        }).done(function(resp) {
                                            Swal.fire({
                                                text: resp.message,
                                                icon: 'success',
                                                confirmButtonText: 'OK',
                                            }).then((result) => {
                                                location.reload()
                                            })
                                        }).fail(function(resp) {
                                            const check_log = jQuery.parseJSON( resp.responseText );
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error!',
                                                text: check_log.message,
                                                footer: '<p style="color: red;"><u>Please try to again!!</u></p>'
                                            }).then((result) => {
                                                location.reload()
                                            })
                                        })
                                    } else if(remark_log == '') {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Something went wrong!',
                                            footer: '<p style="color: red;"><u>Please input the remark!</u></p>'
                                        })
                                    }
                                })
                            });
                        },
                        error:function(data)
                        {
                            window.toastr.remove()
                            toastr.warning(data.responseText)
                        }

                    });
                }
            } else {
                window.toastr.remove()
                toastr.error('กรุณากรอก digit 8 ตัวเท่านั้น')
            }
            // var txt = $(this).val();
            // console.log(txt,txt_length)
        });

        
    });
</script>

</body>
</html>
