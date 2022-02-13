<?php 
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ส่งสินค้าย้อนหลัง | VMI</title>
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
  

  <!-- Datatables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.css">
  <link rel="stylesheet" href="../../plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-checkboxes/css/dataTables.checkboxes.css">

  <style>

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
                                    <i class="fas fa-arrow-alt-circle-up"></i> 
                                    รายการส่งสินค้า..
                                </h4>
                                <a href="recover_distri.php" class="btn btn-primary my-3 ">
                                    <i class="fas fa-arrow-circle-left"></i>
                                    กลับหน้าก่อนหน้านี้
                                </a>
                            </div>
                            
                            <!-- Initial field -->
                            <form id="search_submit_form" class="search-bar">
                                <input style="width: 250px" type="text" name="search_text" id="search_text" pattern=".*\S.*" maxlength="10" onKeyPress="if(this.value.length==10) return 10;" placeholder="สเเกน RFID หรือไอดีพนักงาน.. ">
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


<script>
    $(function(){
        // $('#search_text').keypress(function(e){
        //     e.preventDefault();
        //     if (e.which == 13) {
        //         var txt = $(this).val();
        //         if(txt == ''){
        //             window.toastr.remove()
        //             toastr.warning('ไม่พบผู้ใช้คนนี้')
        //             console.clear();
        //         } else {
        //             $('#result').html('');
        //             $.ajax({
        //                 type: "POST",
        //                 url: "../../service/request/rfid-search.php",
        //                 data:{search:txt},
        //                 dataType:"text",
        //                 success:function(data)
        //                 {
        //                     $('#result').html(data);
        //                     const uid = $('#uid_profile').val();
        //                     window.toastr.remove()
        //                     toastr.success('ค้นหาสำเร็จ');
        //                     // console.clear();
        //                     Search_datatables(uid);
        //                 },
        //                 error:function(data)
        //                 {
        //                     window.toastr.remove()
        //                     toastr.warning('ไม่พบผู้ใช้คนนี้')
        //                 }

        //             });
        //         }
        //     }
        // });

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
                        url: "../../service/request/rfid-search.php",
                        data:{search:txt},
                        dataType:"text",
                        success:function(data)
                        {
                            $('#result').html(data);
                            const uid = $('#uid_profile').val();
                            window.toastr.remove()
                            toastr.success('ค้นหาสำเร็จ');
                            // console.clear();
                            Search_datatables(uid);
                        },
                        error:function(data)
                        {
                            window.toastr.remove()
                            toastr.warning('ไม่พบผู้ใช้คนนี้')
                        }

                    });
                }
            } else {
                window.toastr.remove()
                toastr.error('กรุณากรอก digit 8 ตัวเท่านั้น')
            }
            // var txt = $(this).val();
            console.log(txt,txt_length)
        });

        function Search_datatables(uid) {
            $('#search_text').val(uid);
            $.ajax({
                type: "POST",
                url: "../../service/request/order-datatables-rfid-special.php",
                data: {uid:uid}
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
                        // `<input name="select_all[]" value="${item.ORDER_ID}" id="orderEx-select-all" type="checkbox" />`,
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
                        item.RECEIVE_DATE
                        // `<input type="hidden" data-id="${item.ORDER_ID}">`
                    ])
                })
                initDataTables(tableData)
            })

            function initDataTables(tableData) {
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
                        { title: "วันรับสินค้า", className: "align-middle"}
                        // { title: "", className: "align-middle"}
                    ],
                    "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;

                        $( api.column( 3 ).footer() ).html(
                            `<div class="btn-group" role="group">
                                <button style="margin-left: -200px;" type="submit" class="btn btn-success btn-lg" id="order_success">
                                    <i class="fas fa-arrow-alt-circle-up"></i> ส่งสินค้า
                                </button>
                                <button type="button" class="btn btn-danger btn-lg" id="cancel_all_order">
                                    <i class="fas fa-window-close"></i> ยกเลิก
                                </button>
                            </div>`
                        );
                    },
                    responsive: true,
                    initComplete: function () {

                        // $('#orderEx-select-all').on('click', function(){
                        //     var rows = table.rows({ 'search': 'applied' }).nodes();
                        //     $('input[type="checkbox"]', rows).prop('checked', this.checked);
                        // });

                        // $('#orderEx tbody').on('change', 'input[type="checkbox"]', function(){
                        //     if(!this.checked){
                        //         var el = $('#orderEx-select-all').get(0);
                        //         if(el && el.checked && ('indeterminate' in el)){
                        //             el.indeterminate = true;
                        //         }
                        //     }
                        // });
                        // $('#frm-example').on('submit', function(e) {
                        //     const log_alert = "ยืนยันการส่งสินค้าทั้งหมดของผู้ใช้ไอดี: " + uid;
                        //     Swal.fire({
                        //         title: log_alert,
                        //         icon: 'warning',
                        //         showCancelButton: true,
                        //         confirmButtonText: 'ตกลง',
                        //         confirmButtonColor: '#28a745',
                        //         cancelButtonText: 'ยกเลิก'
                        //     }).then((result) => {
                        //         if (result.isConfirmed) {
                        //             var form = this;
                        //             var rows_selected = table.column(0).checkboxes.selected();

                        //             $.each(rows_selected, function(index, rowId){
                        //                 // Create a hidden element 
                        //                 $(form).append(
                        //                     $('<input>')
                        //                         .attr('type', 'hidden')
                        //                         .attr('name', 'id[]')
                        //                         .val(rowId)
                        //                 );
                        //             });
                        //             $.ajax({  
                        //                 type: "POST",  
                        //                 url: "../../service/request/order-success.php",  
                        //                 data: formData,
                        //                 contentType: false,
                        //                 processData: false
                        //             }).done(function(resp) {
                        //                 const log_success = "ทำการส่งสินค้าทั้งหมดของ: " + uid + " เรียบร้อยเเล้ว..";
                        //                 Swal.fire({
                        //                     text: log_success,
                        //                     icon: 'success',
                        //                     confirmButtonText: 'ตกลง',
                        //                 }).then((result) => {
                        //                     location.assign('');
                        //                 })
                        //             }).fail(function(resp) {
                        //                 const check_log = jQuery.parseJSON( resp.responseText );
                        //                 Swal.fire({
                        //                     icon: 'error',
                        //                     title: 'เกิดข้อผิดพลาด..',
                        //                     text: check_log,
                        //                     footer: 'กรุณาตรวจสอบใหม่อีกครั้ง!!'
                        //                 }).then((result) => {
                        //                     location.reload()
                        //                 })
                        //             })
                        //         }
                        //     }) 
                        // }),

                        $(document).on('click', '#order_success', function(){ 
                            const log_alert = "ยืนยันการส่งสินค้าให้กับผู้ใช้ไอดี: " + uid;
                            Swal.fire({
                                title: log_alert,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#28a745',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#overlay").fadeIn(300);
                                    var formData = new FormData();
                                    var rows_selected = table.column(0).checkboxes.selected();

                                    var order_id = [];
                                    $.each(rows_selected, function(index, rowId){
                                        order_id.push(rowId);
                                    })
                                    
                                    formData.append('uid', uid);
                                    formData.append('order_id', order_id);

                                    $.ajax({  
                                        type: "POST",  
                                        url: "../../service/request/order-success.php",  
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
                        }),
                        $(document).on('click', '#cancel_all_order', function(){ 

                            const log_alert = "ยืนยันการยกเลิกรายการของผู้ใช้ไอดี: " + uid;
                            Swal.fire({
                                title: log_alert,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'ตกลง',
                                confirmButtonColor: '#28a745',
                                cancelButtonText: 'ยกเลิก'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#overlay").fadeIn(300);

                                    var formData = new FormData();
                                    var rows_selected = table.column(0).checkboxes.selected();

                                    var order_id = [];
                                    $.each(rows_selected, function(index, rowId){
                                        order_id.push(rowId);
                                    })
                                    
                                    formData.append('uid', uid);
                                    formData.append('order_id', order_id);

                                    $.ajax({  
                                        type: "POST",  
                                        url: "../../service/request/order-cancel.php",  
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
