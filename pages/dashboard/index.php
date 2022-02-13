<?php 
    require_once('../authen.php'); 
    
    if($_SESSION['AD_STATUS'] == 'Admin'){
        
    } elseif($_SESSION['AD_STATUS'] == 'Vendor') {

    } else {
        header('Location: ../order/');
    }
// echo '<pre>';
// print_r($_SESSION);
// die();

    $stmt = $connect->prepare("SELECT COUNT(QTY) AS REQUEST_ORDER FROM tb_products WHERE STATUS_ID = 0");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    $stmt_success = $connect->prepare("SELECT COUNT(QTY) AS REQUEST_ORDER FROM tb_products WHERE STATUS_ID = 2");
    $stmt_success->execute();
    $result_success = $stmt_success->fetch(PDO::FETCH_OBJ);

    $stmt_cancel = $connect->prepare("SELECT COUNT(QTY) AS REQUEST_ORDER FROM tb_products WHERE STATUS_ID = 3");
    $stmt_cancel->execute();
    $result_cancel = $stmt_cancel->fetch(PDO::FETCH_OBJ);

    $stmt_user = $connect->prepare("SELECT COUNT(*) AS COUNT_USER FROM tb_user WHERE STATUS_LOGIN = 'Active'");
    $stmt_user->execute();
    $result_user = $stmt_user->fetch(PDO::FETCH_OBJ);

    //Cal total
    $stmt_cal_total = $connect->prepare("SELECT SUM(QTY) AS total FROM tb_products WHERE STATUS_ID = 2 AND YEAR(ORDER_DATE) = YEAR(NOW())");
    $stmt_cal_total->execute();
    $result_total = $stmt_cal_total->fetch(PDO::FETCH_OBJ);
    $total_qty= $result_total->total;

    if($total_qty == '') {
        $total_qty = 0;
    }

    //Cal Percentage
    $stmt_cal_per = $connect->prepare("
        select ((this_week - last_week)/last_week*100) as percent
        from
        ((SELECT SUM(QTY) AS LAST_WEEK FROM tb_products
        WHERE date(ORDER_DATE) >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY
        AND date(ORDER_DATE) < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY)
        as last,
        (SELECT SUM(QTY) AS THIS_WEEK FROM tb_products WHERE STATUS_ID = 2 AND WEEK(CURDATE()) = WEEK(ORDER_DATE))
        as this
        )
    ");
    $stmt_cal_per->execute();
    $result_per = $stmt_cal_per->fetch(PDO::FETCH_OBJ);
    $total_per = $result_per->percent;

    if($total_per == '') {
        $total_per = 0;
    }
?>
<!DOCTYPE html>
<html lang="en" class="notranslate" translate="no">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate" />
  <title>หน้าหลัก | VMI</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/hoodie3.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    body {
        font-family: 'Roboto', sans-serif;
        font-style: normal;
        font-weight: 300;
        background-color: var(--color-one);
        font-size: 0.95em;
        color: #222;
    }

    .container {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .info-box {
        border: 1px solid var(--color-three);
        margin-bottom: 20px;
        transition: border 0.1s, transform 0.3s;
    }

    .info-box:hover {
        border: 1px solid var(--color-two);
        -webkit-transform: translateY(-10px);
        transform: translateY(-10px);
        cursor: pointer;
    }

    .info-box .info-box-body h2 {
        color: var(--color-two);
    }

    .info-box img:hover {
    opacity: 0.6;
    }

    .info-box-p {
        color: var(--color-three);
    }

    .info-box-p i {
        color: var(--color-two);
        margin-right: 8px;
    }
    

    #order_list_monthly {
    width: 100%;
    height: 450px;
    }

    #order_list_donut {
    width: 100%;
    height: 450px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="../../assets/images/hoodie3.png" alt="VMILogo" height="60" width="60">
    </div>
    <?php include_once('../includes/sidebar.php') ?>
    <div class="content-wrapper pt-3">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">หน้าเเดชบอร์ด</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">หน้าหลัก</li>
                    <!-- <li class="breadcrumb-item active">หน้าเเดชบอร์ด</li> -->
                    </ol>
                </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            <?php 
                if($_SESSION['AD_STATUS'] == 'Admin'){?>
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        
                        <a href="../request/">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cart-plus"></i></span>
                                <div class="info-box-content text-center">
                                    <span class="info-box-text" style="color: black;">ออเดอร์</span>
                                    <span class="info-box-number" style="color: black;">
                                        <?php echo $result->REQUEST_ORDER ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </a>
                        
                    </div>
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="../request/success.php">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                                <div class="info-box-content text-center">
                                    <span class="info-box-text" style="color: black;">สำเร็จ</span>
                                    <span class="info-box-number" style="color: black;">
                                        <?php echo $result_success->REQUEST_ORDER ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </a>
                    </div>
                    <!-- /.col -->

                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="../request/cancel.php">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-window-close"></i></span>

                                <div class="info-box-content text-center">
                                    <span class="info-box-text" style="color: black;">ยกเลิก</span>
                                    <span class="info-box-number" style="color: black;">
                                        <?php echo $result_cancel->REQUEST_ORDER ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </a>
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="../manager/">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                                <div class="info-box-content text-center">
                                    <span class="info-box-text" style="color: black;">สมาชิก</span>
                                    <span class="info-box-number" style="color: black;">
                                        <?php echo $result_user->COUNT_USER ?>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </a>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">รายงานสรุปยอดการออเดอร์ชุดยูนิฟอร์ม</h3>
                                        <a href="../reports">ดูรายงาน</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <p class="d-flex flex-column">
                                            <span class="text-bold text-lg ">รวมยอดออเดอร์ทั้งหมด: <?php echo $total_qty; ?></span>
                                            <span>จำนวนการออเดอร์ชุดยูนิฟอร์มในเเต่ละเดือน</span>
                                        </p>

                                        <p class="ml-auto d-flex flex-column text-right">
                                            <u class="text">ข้อมูลรายงานปี 2022</u>
                                        </p>
                                    </div>

                                    <div class="position-relative mb-4">
                                        <div id="order_list_monthly"></div>
                                    </div>

                                    <!-- <div class="d-flex flex-row justify-content-end">
                                        <span class="mr-2">
                                            <i class="fas fa-square text-primary"></i> This Week
                                        </span>

                                        <span>
                                            <i class="fas fa-square text-gray"></i> Last Week
                                        </span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">รายงานสรุปยอดการออเดอร์ชุดยูนิฟอร์ม (%)</h3>
                                        <a href="../reports">ดูรายงาน</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <p class="d-flex flex-column">
                                            <span class="text-bold text-lg">กราฟโดนัท (%)</span>
                                            <span>ยอดการออเดอร์ชุดยูนิฟอร์มในเเต่ละเดือน (%)</span>
                                        </p>
                                        <p class="ml-auto d-flex flex-column text-right">

                                            <?php if($total_per >= 0) { ?>
                                                <span class="text-success">
                                                    <i class="fas fa-arrow-up"></i> <?php echo $total_per; ?>%
                                                </span>
                                            <?php } else { ?>
                                                <span class="text-danger mr-1">
                                                    <i class="fas fa-arrow-down"></i> <?php echo $total_per; ?>%
                                                </span>
                                            <?php } ?>
                                            <span class="text-muted">สัปดาห์ที่ผ่านมา</span>
                                        </p>
                                    </div>
                                    <div class="position-relative mb-4">
                                        <div id="order_list_donut"></div>
                                    </div>

                                    <!-- <div class="d-flex flex-row justify-content-end">
                                        <span class="mr-2">
                                            <i class="fas fa-square text-primary"></i> ปีนี้
                                        </span>

                                        <span>
                                            <i class="fas fa-square text-gray"></i> ปีที่ผ่านมา
                                        </span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } elseif($_SESSION['AD_STATUS'] == 'Vendor') { ?>
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        
                        <a href="../request/">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cart-plus"></i></span>
                                <div class="info-box-content text-center">
                                    <span class="info-box-text" style="color: black;">ออเดอร์</span>
                                    <span class="info-box-number" style="color: black;">
                                        <?php echo $result->REQUEST_ORDER ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                        
                    </div>
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                            <div class="info-box-content text-center">
                                <span class="info-box-text" style="color: black;">สำเร็จ</span>
                                <span class="info-box-number" style="color: black;">
                                    <?php echo $result_success->REQUEST_ORDER ?>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-window-close"></i></span>

                            <div class="info-box-content text-center">
                                <span class="info-box-text" style="color: black;">ยกเลิก</span>
                                <span class="info-box-number" style="color: black;">
                                    <?php echo $result_cancel->REQUEST_ORDER ?>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content text-center">
                                <span class="info-box-text" style="color: black;">สมาชิก</span>
                                <span class="info-box-number" style="color: black;">
                                    <?php echo $result_user->COUNT_USER ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">รายงานสรุปยอดการออเดอร์ชุดยูนิฟอร์ม</h3>
                                        <a href="javascript:void(0);">ดูรายงาน</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <p class="d-flex flex-column">
                                            <span class="text-bold text-lg ">รวมยอดออเดอร์ทั้งหมด: <?php echo $total_qty; ?></span>
                                            <span>จำนวนการออเดอร์ชุดยูนิฟอร์มในเเต่ละเดือน</span>
                                        </p>

                                        <p class="ml-auto d-flex flex-column text-right">
                                            <u class="text">ข้อมูลรายงานปี 2022</u>
                                        </p>
                                    </div>

                                    <div class="position-relative mb-4">
                                        <div id="order_list_monthly"></div>
                                    </div>

                                    <!-- <div class="d-flex flex-row justify-content-end">
                                        <span class="mr-2">
                                            <i class="fas fa-square text-primary"></i> This Week
                                        </span>

                                        <span>
                                            <i class="fas fa-square text-gray"></i> Last Week
                                        </span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">รายงานสรุปยอดการออเดอร์ชุดยูนิฟอร์ม (%)</h3>
                                        <a href="javascript:void(0);">ดูรายงาน</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <p class="d-flex flex-column">
                                            <span class="text-bold text-lg">กราฟโดนัท (%)</span>
                                            <span>ยอดการออเดอร์ชุดยูนิฟอร์มในเเต่ละเดือน (%)</span>
                                        </p>
                                        <p class="ml-auto d-flex flex-column text-right">

                                            <?php if($total_per >= 0) { ?>
                                                <span class="text-success">
                                                    <i class="fas fa-arrow-up"></i> <?php echo $total_per; ?>%
                                                </span>
                                            <?php } else { ?>
                                                <span class="text-danger mr-1">
                                                    <i class="fas fa-arrow-down"></i> <?php echo $total_per; ?>%
                                                </span>
                                            <?php } ?>
                                            <span class="text-muted">สัปดาห์ที่ผ่านมา</span>
                                        </p>
                                    </div>
                                    <div class="position-relative mb-4">
                                        <div id="order_list_donut"></div>
                                    </div>

                                    <!-- <div class="d-flex flex-row justify-content-end">
                                        <span class="mr-2">
                                            <i class="fas fa-square text-primary"></i> ปีนี้
                                        </span>

                                        <span>
                                            <i class="fas fa-square text-gray"></i> ปีที่ผ่านมา
                                        </span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <div class="container-fluid">
                <!-- <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info shadow">
                            <div class="inner text-center">
                                <h1 class="py-3">&nbsp;ยังคิดไม่ออก&nbsp;</h1>
                            </div>
                            <a href="#" class="small-box-footer py-3"> คลิกจัดการระบบ <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary shadow">
                            <div class="inner text-center">
                                <h1 class="py-3">จัดการโปรไฟล์</h1>
                            </div>
                            <a href="#" class="small-box-footer py-3"> คลิกจัดการระบบ <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success shadow">
                            <div class="inner text-center">
                                <h1 class="py-3">จัดการเมนู</h1>
                            </div>
                            <a href="#" class="small-box-footer py-3"> คลิกจัดการระบบ <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info shadow">
                            <div class="inner text-center">
                                <h1 class="py-3">จัดการเมนู</h1>
                            </div>
                            <a href="#" class="small-box-footer py-3"> คลิกจัดการระบบ <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="small-box py-3 bg-white shadow">
                            <div class="inner">
                                <h3>9,999 รายการ</h3>
                                <p class="text-danger">Report 1</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="small-box py-3 bg-white shadow">
                            <div class="inner">
                                <h3>9,999 รายการ</h3>
                                <p class="text-danger">Report 2</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="small-box py-3 bg-white shadow">
                            <div class="inner">
                                <h3>9,999 รายการ</h3>
                                <p class="text-danger">Report 3</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="small-box py-3 bg-white shadow">
                            <div class="inner">
                                <h3>9,999 รายการ</h3>
                                <p class="text-danger">Report 4</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="d-flex">
                                    <p class="d-flex flex-column">
                                        <span class="text-bold text-xl">฿9,999</span>
                                        <span class="text-danger">Sales Report</span>
                                    </p>
                                </div>
                                <div class="position-relative">
                                    <canvas id="visitors-chart" height="350"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <?php } ?>
        </div>
    </div>
    <?php include_once('../includes/footer.php') ?>
</div>


<!-- SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>

<!-- OPTIONAL SCRIPTS -->
<!-- <script src="../../plugins/chart.js/Chart.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> -->
<!-- <script src="../../assets/js/pages/dashboard.js"></script> -->
<script src="../../assets/amcharts/index.js"></script>
<script src="../../assets/amcharts/xy.js"></script>
<script src="../../assets/amcharts/percent.js"></script>
<script src="../../assets/amcharts/Animated.js"></script>


<script>
    /* Stack column charts with ajax */
    $.ajax({
        url: "ajax_load_report_monthly.php",
        type: "GET",
        datatype:'json',
        cache: false,
        async: false
    }).done(function(response) {
        am5.ready(function() {

            var root = am5.Root.new("order_list_monthly");


            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "panX",
                wheelY: "zoomX",
                layout: root.verticalLayout
            }));

            chart.set("scrollbarX", am5.Scrollbar.new(root, {
                orientation: "horizontal"
            }));

            var json_data = response;
            var data = json_data;

            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "month",
                renderer: am5xy.AxisRendererX.new(root, {}),
                tooltip: am5.Tooltip.new(root, {})
            }));

            xAxis.data.setAll(data);

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                min: 0,
                renderer: am5xy.AxisRendererY.new(root, {})
            }));

            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.p50,
                x: am5.p50
            }));


            function makeSeries(name, fieldName) {
                var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                    name: name,
                    stacked: true,
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: fieldName,
                    categoryXField: "month"
                }));

                series.columns.template.setAll({
                    tooltipText: "{name}, {categoryX}: {valueY}",
                    tooltipY: am5.percent(10)
                });

                series.data.setAll(data);

                series.appear();

                series.bullets.push(function () {
                    return am5.Bullet.new(root, {
                        sprite: am5.Label.new(root, {
                            text: "{valueY}",
                            fill: root.interfaceColors.get("alternativeText"),
                            centerY: am5.p50,
                            centerX: am5.p50,
                            populateText: true
                        })
                    });
                });

                // legend.data.push(series);
              

            }
            
            makeSeries("เสื้อกั๊กสีฟ้า", "VEST_BLUE");
            makeSeries("เสื้อกั๊กสีเทา", "VEST_GRAY");
            makeSeries("รองเท้า ESD", "ESD_SHOES");
            makeSeries("รองเท้าผ้าใบ", "NANYANG_SHOES");
            makeSeries("ชุดช่าง", "MAINTENANCE_SUIT");
            makeSeries("ชุดคลุมท้องสีฟ้า", "BLUE_MOTERNILY_CLOTHES");
            makeSeries("ชุดคลุมท้องสีเทา", "GRAY_MOTERNILY_CLOTHES");
            makeSeries("หมวก (เขียวอ่อน)", "CAP_GREEN");
            makeSeries("หมวก (เขียวเข้ม)", "CAP_DARK_GREEN");
            makeSeries("หมวก (ขาว)", "CAP_WHITE");
            makeSeries("หมวก (สีเหลือง)", "CAP_YELLOW");
            makeSeries("หมวก (สีชมพู)", "CAP_PINK");
            makeSeries("หมวก (สีน้ำตาล)", "CAP_BROWN");
            makeSeries("หมวก (สีดำ)", "CAP_BLACK");
            makeSeries("หมวก (สีฟ้า)", "CAP_BLUE");


            chart.appear(1000, 100);

        });
    });

    /* Donut charts with ajax */
    $.ajax({
        url: "ajax_load_report_donuts.php",
        type: "GET",
        datatype:'json',
        cache: false,
        async: false
    }).done(function(response) {
        am5.ready(function() {
            var root = am5.Root.new("order_list_donut");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                layout: root.verticalLayout
            }));

            var series = chart.series.push(am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category"
            }));

            var json_data = response;
            var data = json_data;
            
            series.data.setAll(data);

            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50),
                x: am5.percent(50),
                marginTop: 15,
                marginBottom: 15,
                layout: root.verticalLayout,
                height: am5.percent(80),
                verticalScrollbar: am5.Scrollbar.new(root, {
                    orientation: "vertical"
                })
            }));

            // legend.data.setAll(series.dataItems);
            series.appear(1000, 100);

        });
    });

</script>
</body>
</html>
