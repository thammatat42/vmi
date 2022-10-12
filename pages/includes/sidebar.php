<?php 
    function isActive ($data) {
        $array = explode('/', $_SERVER['REQUEST_URI']);
        $key = array_search("pages", $array);
        $name = $array[$key + 1];
        return $name === $data ? 'active' : '' ;
    }

    $show_date = date("Y-m-d");
    // print_r($_SESSION);
    // die();
?>

<style>
    .led {
        animation-name: backgroundColorPalette;
        animation-duration: 5s;
        animation-iteration-count: infinite;
        animation-direction: alternate;
        animation-timing-function: linear;
    }

    @keyframes backgroundColorPalette {
        0% {
            color: #ee6055;
        }
        25% {
            color: #60d394;
        }
        50% {
            color: #aaf683;
        }
        75% {
            color: #ffd97d;
        }
        100% {
            color: #ff9b85;
        }
    }

    #overlay{	
        position: fixed;
        top: 0;
        z-index: 2500;
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
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars fa-2x"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto ">
        <li class="nav-item d-md-none d-block">
        <?php 
            if($_SESSION['AD_STATUS'] == 'Admin'){?>
            <a href="../dashboard/">
                <img src="../../assets/images/hoodie3.png" 
                    alt="hoodie Logo" 
                    width="50px"
                    class="img-circle elevation-3">
                <span class="led">VMI Uniforms</span>
            </a>
        <?php } elseif($_SESSION['AD_STATUS'] == 'Vendor') { ?>
            <a href="../request/">
                <img src="../../assets/images/hoodie3.png" 
                    alt="hoodie Logo" 
                    width="50px"
                    class="img-circle elevation-3">
                <span class="led">VMI Uniforms</span>
            </a>
        <?php } else { ?>
            <a href="../order/">
                <img src="../../assets/images/hoodie3.png" 
                    alt="hoodie Logo" 
                    width="50px"
                    class="img-circle elevation-3">
                <span class="led">VMI Uniforms</span>
            </a>
        <?php } ?>
        </li>
        <li class="nav-item d-md-block d-none">
            <b><i class="fa fa-calendar-alt"></i>
                <?php echo $show_date; ?> <i class="fa fa-clock"></i> <span id="MyClockDisplay" class="clock" onload="showTime()"></span>
            </b>
        </li>
    </ul>
</nav>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php 
        if($_SESSION['AD_STATUS'] == 'Admin'){?>
        <a href="../dashboard/" class="brand-link">
            <img src="../../assets/images/hoodie3.png" 
                alt="Admin Logo" 
                class="brand-image img-circle elevation-3">
            <span class="led">VMI Uniforms</span>
        </a>
    <?php } elseif($_SESSION['AD_STATUS'] == 'Vendor') { ?>
        <a href="../request/" class="brand-link">
            <img src="../../assets/images/hoodie3.png" 
                alt="Admin Logo" 
                class="brand-image img-circle elevation-3">
            <span class="led">VMI Uniforms</span>
        </a>
    <?php }else { ?>
        <a href="../order/" class="brand-link">
            <img src="../../assets/images/hoodie3.png" 
                alt="Admin Logo" 
                class="brand-image img-circle elevation-3">
            <span class="led">VMI Uniforms</span>
        </a>
    <?php } ?>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../assets/upload/<?php echo $_SESSION['AD_IMAGE'];?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="../profile/" class="d-block"> <?php echo $_SESSION['AD_FIRSTNAME'].' '.$_SESSION['AD_LASTNAME'] ?> </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php 
                if($_SESSION['AD_STATUS'] == 'Admin'){?>    
                <li class="nav-item">
                    <a href="../dashboard/" class="nav-link <?php echo isActive('dashboard') ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>หน้าหลัก</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../manager/" class="nav-link <?php echo isActive('manager') ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>ผู้ดูแลระบบ</p>
                    </a>
                </li>
                <li class="nav-item <?php if(isActive('maintain') == true) {echo 'menu-open';} elseif (isActive('request') == true) {echo 'menu-open';} elseif (isActive('reports') == true) {echo 'menu-open';} ?>">
                    <a href="#" class="nav-link <?php if(isActive('maintain') == true) {echo isActive('maintain');} elseif (isActive('request') == true) {echo isActive('request');} elseif (isActive('reports') == true) {echo isActive('reports');}?>">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        จัดการระบบ
                        <i class="fas fa-angle-left right"></i>
                    </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../maintain/" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/VMI/pages/maintain/index.php" ? "active" : "" || $_SERVER['PHP_SELF'] == "/vmi/pages/maintain/index.php" ? "active" : ""); ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p>จัดการมาสเตอร์</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../maintain/stock.php" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/VMI/pages/maintain/stock.php" ? "active" : "" || $_SERVER['PHP_SELF'] == "/vmi/pages/maintain/stock.php" ? "active" : ""); ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p>คลังสินค้า</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../request/" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/VMI/pages/request/index.php" ? "active" : "" || $_SERVER['PHP_SELF'] == "/vmi/pages/request/index.php" ? "active" : ""); ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p>ออเดอร์</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../reports/" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/VMI/pages/reports/index.php" ? "active" : "" || $_SERVER['PHP_SELF'] == "/vmi/pages/reports/index.php" ? "active" : ""); ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p>รีพอร์ท</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="../order/" class="nav-link <?php echo isActive('order') ?>">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>รายการสินค้า</p>
                    </a>
                </li>
                <li class="nav-item">
                        <a href="../receive/" class="nav-link <?php echo isActive('receive') ?>">
                            <i class="nav-icon fas fa-check-circle"></i>
                            <p>รอรับสินค้า</p>
                        </a>
                    </li>
                <?php
                } elseif($_SESSION['AD_STATUS'] == 'Vendor') { ?>
                    <li class="nav-item">
                        <a href="../dashboard/" class="nav-link <?php echo isActive('dashboard') ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>หน้าหลัก</p>
                        </a>
                    </li>
                    <li class="nav-item <?php if(isActive('maintain') == true) {echo 'menu-open';} elseif (isActive('request') == true) {echo 'menu-open';} elseif (isActive('reports') == true) {echo 'menu-open';} ?>">
                        <a href="#" class="nav-link <?php if(isActive('maintain') == true) {echo isActive('maintain');} elseif (isActive('request') == true) {echo isActive('request');} elseif (isActive('reports') == true) {echo isActive('reports');}?>">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            จัดการระบบ
                            <i class="fas fa-angle-left right"></i>
                        </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="../maintain/stock.php" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/VMI/pages/maintain/stock.php" ? "active" : "" || $_SERVER['PHP_SELF'] == "/vmi/pages/maintain/stock.php" ? "active" : ""); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>คลังสินค้า</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../request/" class="nav-link <?php echo ($_SERVER['PHP_SELF'] == "/VMI/pages/request/index.php" ? "active" : "" || $_SERVER['PHP_SELF'] == "/vmi/pages/request/index.php" ? "active" : ""); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ออเดอร์</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } else {?>
                    <li class="nav-item">
                        <a href="../order/" class="nav-link <?php echo isActive('order') ?>">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>รายการสินค้า</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../receive/" class="nav-link <?php echo isActive('receive') ?>">
                            <i class="nav-icon fas fa-check-circle"></i>
                            <p>รอรับสินค้า</p>
                        </a>
                    </li>
                <?php } ?>
                    
                <!-- <li class="nav-item">
                    <a href="../blankpage/" class="nav-link <?php echo isActive('blankpage') ?>">
                        <i class="nav-icon fab fa-artstation"></i>
                        <p>Blank Page</p>
                    </a>
                </li> -->
                
                <li class="nav-header">บัญชีของเรา</li>
                <li class="nav-item">
                    <a href="../profile/" id="profile" class="nav-link <?php echo isActive('profile') ?>">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>โปรไฟล์</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../logout.php" id="logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>ออกจากระบบ</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>

<script>
    function showTime() {
        var date = new Date();
        var h = date.getHours(); // 0 - 23
        var m = date.getMinutes(); // 0 - 59
        var s = date.getSeconds(); // 0 - 59
        var session = "AM";

        if (h == 0) {
            h = 12;
        }

        if (h > 12) {
            h = h - 12;
            session = "PM";
        }

        h = (h < 10) ? "0" + h : h;
        m = (m < 10) ? "0" + m : m;
        s = (s < 10) ? "0" + s : s;

        var time = h + ":" + m + ":" + s + " " + session;
        document.getElementById("MyClockDisplay").innerText = time;
        document.getElementById("MyClockDisplay").textContent = time;

        setTimeout(showTime, 1000);

    }

    showTime();
</script>