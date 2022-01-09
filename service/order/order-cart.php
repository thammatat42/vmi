<?php 
header('Content-Type: application/json');
require_once '../connect.php';


if (isset($_POST['function']) && $_POST['function'] == 'order_submit') {
    
    $SIZE = $_POST['size'];
    $GROUP_ID = $_POST['send_group_id'];
    $QTY = $_POST['input_number'];
    // $CHECK_STOCK = $_POST['check_stock'];
    $CATEGORY_TH = $_POST['category_th'];

    /* Query เช็ครูปภาพ */
    $stmt = $connect->prepare("SELECT DISTINCT IMG FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
    $stmt->execute(array(":id" => $GROUP_ID, ":SIZE" => $SIZE));
    $result = $stmt->fetch(PDO::FETCH_OBJ);


    /* Query เช็คจำนวนที่เคยสั่งซื้อไป */
    $stmt5 = $connect->prepare("SELECT COUNT(*) AS CHECK_QTY FROM tb_order_trans WHERE YEAR(NOW()) = YEAR(ORDER_DATE) AND ORDER_BY = :ORDER_BY AND GROUP_ID = :id and QTY >= 2 AND STATUS_ID <> 3");
    $stmt5->execute(array(':ORDER_BY' => $_SESSION['gid'],':id' => $GROUP_ID));
    $result5 = $stmt5->fetch(PDO::FETCH_OBJ);
    $CHECK_QTY = $result5->CHECK_QTY;
    
    /* Query เช็คจำนวนที่เคยสั่งซื้อไป 1 ครั้ง */
    $stmt6 = $connect->prepare("SELECT COUNT(*) AS CHECK_QTY2 FROM tb_order_trans WHERE YEAR(NOW()) = YEAR(ORDER_DATE) AND ORDER_BY = :ORDER_BY AND GROUP_ID = :id and QTY <= 1 AND STATUS_ID <> 3");
    $stmt6->execute(array(':ORDER_BY' => $_SESSION['gid'],':id' => $GROUP_ID));
    $result6 = $stmt6->fetch(PDO::FETCH_OBJ);
    $CHECK_QTY_2 = $result6->CHECK_QTY2;

    /* Query เช็คจำนวนในการสั่งซื้อต่อปี */
    $stmt3 = $connect->prepare("SELECT COUNT(*) AS THIS_YEAR FROM tb_order_trans WHERE YEAR(NOW()) = YEAR(ORDER_DATE) AND ORDER_BY = :ORDER_BY AND GROUP_ID = :id AND STATUS_ID <> 3");
    $stmt3->execute(array(':ORDER_BY' => $_SESSION['gid'],':id' => $GROUP_ID));
    $result3 = $stmt3->fetch(PDO::FETCH_OBJ);

    if($result) {
        $stmt2 = $connect->prepare("SELECT DISTINCT CATEGORY_TH,IMG,UNIT_PRICE FROM tb_master where GROUP_ID = :id AND STATUS_CHG = '0'");
        $stmt2->execute(array(':id' => $GROUP_ID));
        $result2 = $stmt2->fetch(PDO::FETCH_OBJ);


        if($CHECK_QTY > 0){
            $CAL_PRICE = $result2->UNIT_PRICE * $QTY;
            $sql = "INSERT INTO tb_order_trans (GROUP_ID,STATUS_ID,IMG,CATEGORY_TH,SIZE,QTY,PRICE,ORDER_BY,ORDER_DATE) 
                    VALUES (:id,'0',:IMG,:CATEGORY_TH,:SIZE,:QTY,:PRICE,'".$_SESSION['gid']."','".date("Y-m-d H:i:s")."')";
            $stmt2 = $connect->prepare($sql);
            $ADD_TRANS = $stmt2->execute(array(":id" => $GROUP_ID,":IMG" => $result->IMG,":CATEGORY_TH" => $CATEGORY_TH,":SIZE" => $SIZE,":QTY" => $QTY,":PRICE" => $CAL_PRICE));
        } else {
            if($result3->THIS_YEAR <= 1 && $QTY == 1) {
                $PRICE_QTY = 0;
    
                $sql = "INSERT INTO tb_order_trans (GROUP_ID,STATUS_ID,IMG,CATEGORY_TH,SIZE,QTY,PRICE,ORDER_BY,ORDER_DATE) 
                        VALUES (:id,'0',:IMG,:CATEGORY_TH,:SIZE,:QTY,:PRICE,'".$_SESSION['gid']."','".date("Y-m-d H:i:s")."')";
                $stmt2 = $connect->prepare($sql);
                $ADD_TRANS = $stmt2->execute(array(":id" => $GROUP_ID,":IMG" => $result->IMG,":CATEGORY_TH" => $CATEGORY_TH,":SIZE" => $SIZE,":QTY" => $QTY,":PRICE" => $PRICE_QTY));
            } elseif($result3->THIS_YEAR <= 1 && $QTY >= 2 && $CHECK_QTY_2 > 0) {
                $PRICE_QTY = $result2->UNIT_PRICE;
                $CAL_PRICE = ($result2->UNIT_PRICE * $QTY) - $PRICE_QTY;

                $sql = "INSERT INTO tb_order_trans (GROUP_ID,STATUS_ID,IMG,CATEGORY_TH,SIZE,QTY,PRICE,ORDER_BY,ORDER_DATE) 
                        VALUES (:id,'0',:IMG,:CATEGORY_TH,:SIZE,:QTY,:PRICE,'".$_SESSION['gid']."','".date("Y-m-d H:i:s")."')";
                $stmt2 = $connect->prepare($sql);
                $ADD_TRANS = $stmt2->execute(array(":id" => $GROUP_ID,":IMG" => $result->IMG,":CATEGORY_TH" => $CATEGORY_TH,":SIZE" => $SIZE,":QTY" => $QTY,":PRICE" => $CAL_PRICE));
            } elseif($result3->THIS_YEAR <= 1 && $QTY == 2) {
                $PRICE_QTY = 0;
    
                $sql = "INSERT INTO tb_order_trans (GROUP_ID,STATUS_ID,IMG,CATEGORY_TH,SIZE,QTY,PRICE,ORDER_BY,ORDER_DATE) 
                        VALUES (:id,'0',:IMG,:CATEGORY_TH,:SIZE,:QTY,:PRICE,'".$_SESSION['gid']."','".date("Y-m-d H:i:s")."')";
                $stmt2 = $connect->prepare($sql);
                $ADD_TRANS = $stmt2->execute(array(":id" => $GROUP_ID,":IMG" => $result->IMG,":CATEGORY_TH" => $CATEGORY_TH,":SIZE" => $SIZE,":QTY" => $QTY,":PRICE" => $PRICE_QTY));
            } elseif($result3->THIS_YEAR <= 1 && $QTY > 2) {
                $PRICE_QTY = $result2->UNIT_PRICE * 2;
                $CAL_PRICE = ($result2->UNIT_PRICE * $QTY) - $PRICE_QTY;
                    
                $sql = "INSERT INTO tb_order_trans (GROUP_ID,STATUS_ID,IMG,CATEGORY_TH,SIZE,QTY,PRICE,ORDER_BY,ORDER_DATE) 
                        VALUES (:id,'0',:IMG,:CATEGORY_TH,:SIZE,:QTY,:PRICE,'".$_SESSION['gid']."','".date("Y-m-d H:i:s")."')";
                $stmt2 = $connect->prepare($sql);
                $ADD_TRANS = $stmt2->execute(array(":id" => $GROUP_ID,":IMG" => $result->IMG,":CATEGORY_TH" => $CATEGORY_TH,":SIZE" => $SIZE,":QTY" => $QTY,":PRICE" => $CAL_PRICE));
            } else {
                // $PRICE_QTY = $result2->UNIT_PRICE * 2;
                $CAL_PRICE = $result2->UNIT_PRICE * $QTY;
    
                $sql = "INSERT INTO tb_order_trans (GROUP_ID,STATUS_ID,IMG,CATEGORY_TH,SIZE,QTY,PRICE,ORDER_BY,ORDER_DATE) 
                        VALUES (:id,'0',:IMG,:CATEGORY_TH,:SIZE,:QTY,:PRICE,'".$_SESSION['gid']."','".date("Y-m-d H:i:s")."')";
                $stmt2 = $connect->prepare($sql);
                $ADD_TRANS = $stmt2->execute(array(":id" => $GROUP_ID,":IMG" => $result->IMG,":CATEGORY_TH" => $CATEGORY_TH,":SIZE" => $SIZE,":QTY" => $QTY,":PRICE" => $CAL_PRICE));
            }
        }

        if($ADD_TRANS) {
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'เพิ่มเข้าตะกร้าสินค้าสำเร็จ'));
        } else {
            http_response_code(404);
            echo json_encode(array('status' => false, 'message' => 'เกิดบางอย่างผิดปกติ..ไม่สามารถเพิ่มเข้าตะกร้าสินค้าได้!'));
        }
    }

    
    

}

?>