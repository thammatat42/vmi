<?php
header('Content-Type: application/json');
require_once '../connect.php';

// echo "<pre>";
// print_r($_POST);
// die();

$GROUP_ID = $_POST['id'];

// query ดึงชื่อเเละรูปภาพ
$stmt2 = $connect->prepare("SELECT DISTINCT CATEGORY_TH,IMG FROM tb_master where GROUP_ID = :id AND STATUS_CHG = '0'");
$stmt2->execute(array(':id' => $GROUP_ID));
$result2 = $stmt2->fetch(PDO::FETCH_OBJ);

// query เช็คจำนวนสินค้า
$stmt3 = $connect->prepare("SELECT SUM(C_STOCK) AS STOCK FROM tb_master where GROUP_ID = :id AND STATUS_CHG = '0'");
$stmt3->execute(array(':id' => $GROUP_ID));
$result3 = $stmt3->fetch(PDO::FETCH_OBJ);

if($result3->STOCK == 0) {
    $color = 'red';
}else{
    $color = '#10f210';
}


if(!empty($result2)) {
    $output = '
            
        <div class="card-header card-header-warning">
            <h4 class="card-title ">รายการสินค้า: '.$result2->CATEGORY_TH.'</h4>
            <input id="value_id" type="hidden" value="'.$result2->CATEGORY_TH.'">
            <p class="card-category"></p>
        </div>

        <div class="solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <h3 class="d-inline-block d-sm-none">Sony Technology Thailand</h3>
                        <div class="col-md-12">
                            <div style="text-align: center;">
                                <img src="../../assets/images/uniforms/'.$result2->IMG.'" class="img-fluid" alt="Product Image" style="width: 100%; height: 100%;">

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        
                        <div class="alert alert-success" role="alert">
                            <h3 class="my-0">STT Inventory</h3>
                        </div>

                        <h4 class="mt-3">Size <small>กรุณาเลือกไซต์..</small></h4>
                        <div class="form-group">
                            <select class="form-control select2bs4" name="size" id="size" style="width: 100%;" required>
                                <option value disabled selected>กรุณาเลือกไซต์..</option>
                            </select>
                        </div>

                        <h4 class="mt-3"><small>จำนวนสินค้า..</small></h4>
                        <div class="mt-2">
                            <a class="btn btn-default" id="negative">-</a>
                            <input class="btn btn-default" style="width: 80px" type="number" min="1" value="1" onKeyPress="if(this.value.length==4) return false;" name="input_number" id="input_number"/>
                            <a class="btn btn-default" id="positive">+</a>
                        </div>
                        <h5 class="mt-3" style=" color:'.$color.';"><small>คลังรวม :  '.$result3->STOCK.' </small></h5>
                        <div class="mt-3" id="return_stock">
                        </div>

                    </div>
                </div>
            </div>
        </div>
            
    ';
    http_response_code(200);
    echo $output;
} else {
    http_response_code(404);
    // echo "data not found";
    echo json_encode(array('status' => false, 'message' => 'Not found this group_id: '. $GROUP_ID .''));
}


?>