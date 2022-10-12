<?php 
header('Content-Type: application/json');
require_once '../connect.php';

if (isset($_POST['function']) && $_POST['function'] == 'size_change') {
    
    $SIZE = $_POST['size'];
    $GROUP_ID = $_POST['send_group_id'];

    $stmt = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
    $stmt->execute(array(":id" => $GROUP_ID, ":SIZE" => $SIZE));
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if($result) {
        
        $output = '
            <label for="stock_value" style="color: red;">ข้อควรทราบ: สิทธิ์ในการเบิกจำนวน 2 ชิ้นต่อปี สำหรับชิ้นถัดไปจะเป็นการสั่งซื้อ!</label>
            <p class="card-text text-black pb-2 pt-1">จำนวนสินค้าทั้งหมด: <span class="badge badge-warning" style="font-size: 1rem;" at="'.$result->C_STOCK.'" id="stock_value">'.$result->C_STOCK.'</span></p>
        ';
        
        // $output2 = '
        //     <button type="submit" class="btn btn-primary btn-lg" name="order_submit" id="order_submit"><i class="fas fa-cart-plus fa-lg mr-2"></i>เพิ่มเข้าตะกร้าสินค้า</button>
        // ';

        // เช็ค stock
        if($result->C_STOCK == 0) {
            $output2 = '
                <button type="button" class="btn btn-primary btn-lg disabled"><i class="fas fa-cart-plus fa-lg mr-2"></i>เพิ่มเข้าตะกร้าสินค้า</button>
            ';
        } else {
            $stmt = $connect->prepare("SELECT COUNT(*) AS CHECK_INPUT FROM tb_order_trans  WHERE GROUP_ID = :id AND ORDER_BY = :ORDER_BY AND STATUS_ID = '0' AND STATUS_CHG = '0'");
            $stmt->bindParam(":id", $GROUP_ID);
            $stmt->bindParam(":ORDER_BY", $_SESSION['gid']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if($result) {
                $CHECK_INPUT = $result->CHECK_INPUT;
                if($CHECK_INPUT > 0) {
                    $output2 = '
                        <button type="button" class="btn btn-primary btn-lg disabled"><i class="fas fa-cart-plus fa-lg mr-2"></i>เพิ่มเข้าตะกร้าสินค้า</button>
                    ';
                } else {

                    $stmt = $connect->prepare("SELECT COUNT(*) AS CHECK_INPUT FROM tb_products  WHERE GROUP_ID = :id AND ORDER_BY = :ORDER_BY AND STATUS_ID = '0' AND STATUS_CHG = '0'");
                    $stmt->bindParam(":id", $GROUP_ID);
                    $stmt->bindParam(":ORDER_BY", $_SESSION['gid']);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_OBJ);

                    if($result) {
                        $CHECK_INPUT = $result->CHECK_INPUT;
                        
                        if($CHECK_INPUT > 0) {
                            $output2 = '
                                <button type="button" class="btn btn-primary btn-lg disabled"><i class="fas fa-cart-plus fa-lg mr-2"></i>เพิ่มเข้าตะกร้าสินค้า</button>
                            ';
                        } else {
                            $output2 = '
                                <button type="submit" class="btn btn-primary btn-lg" name="order_submit" id="order_submit"><i class="fas fa-cart-plus fa-lg mr-2"></i>เพิ่มเข้าตะกร้าสินค้า</button>
                            ';
                        }
                    }
                    
                }
            }
            
        }

        $response = [
            'amount' => $output,
            'check_stock' => $output2,
            'message' => 'success'
        ];
        http_response_code(200);
        echo json_encode($response);
    } else {
        http_response_code(404);
    }
    exit();
}

?>