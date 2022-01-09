<?php 
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Upload | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie2.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
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
                                    เพิ่มข้อมูลผู้ดูเเลเเบบไฟล์
                                </h4>
                                <a href="./" class="btn btn-primary my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
                            </div>
                            <!-- Initial field -->

            
                            <!-- Card body -->
                            <div class="card-body">
                                <div id="actions" class="row">
                                    <div class="col-lg-6">
                                        <div class="btn-group w-100">
                                        <span class="btn btn-success col fileinput-button">
                                            <i class="fas fa-plus"></i>
                                            <span>เพิ่มไฟล์</span>
                                        </span>
                                        <button type="submit" class="btn btn-primary col start">
                                            <i class="fas fa-upload"></i>
                                            <span>เริ่มการอัปโหลดทั้งหมด</span>
                                        </button>
                                        <button type="reset" class="btn btn-warning col cancel">
                                            <i class="fas fa-times-circle"></i>
                                            <span>ยกเลิกการอัปโหลดทั้งหมด</span>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-flex align-items-center">
                                        <div class="fileupload-process w-100">
                                        <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="table table-striped files" id="previews">
                                    <div id="template" class="row mt-2">
                                        <div class="col-auto">
                                            <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                                        </div>
                                        <div class="col d-flex align-items-center">
                                            <p class="mb-0">
                                            <span class="lead" data-dz-name></span>
                                            (<span data-dz-size></span>)
                                            </p>
                                            <strong class="error text-danger" data-dz-errormessage></strong>
                                        </div>
                                        <div class="col-4 d-flex align-items-center">
                                            <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                            </div>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary start" id="upload_one">
                                            <i class="fas fa-upload"></i>
                                            <span>เริ่มการอัปโหลด</span>
                                            </button>
                                            <button data-dz-remove class="btn btn-warning cancel" id="remove_one">
                                            <i class="fas fa-times-circle"></i>
                                            <span>ยกเลิกการอัปโหลด</span>
                                            </button>
                                            <button data-dz-remove class="btn btn-danger delete" id="delete_one">
                                            <i class="fas fa-trash"></i>
                                            <span>ลบไฟล์ข้อมูล</span>
                                            </button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End card body -->
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
<!-- dropzonejs -->
<script src="../../plugins/dropzone/min/dropzone.min.js"></script>

<script>

  Dropzone.autoDiscover = false

    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: "/service/upload/upload_one.php",
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    })

    myDropzone.on("addedfile", function(file) {
        // Hookup the start button
        file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
    })

    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
        document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function(file) {
        // Show the total progress bar when upload starts
        document.querySelector("#total-progress").style.opacity = "1"
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        document.querySelector("#total-progress").style.opacity = "0"
    })

    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions .start").onclick = function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function() {
        myDropzone.removeAllFiles(true)
    }

    $(document).on('click', '#upload_one', function(e){ 
        e.preventDefault();
        myDropzone.processQueue();
    });

</script>


</body>
</html>
