<?php 
header('Content-Type: application/json');
require_once '../connect.php';


if (isset($_POST['function']) && $_POST['function'] == 'size_change') {
    
    $SIZE = $_POST['size'];
    $GROUP_ID = $_POST['id'];

    $stmt = $connect->prepare("SELECT C_STOCK FROM tb_master  WHERE GROUP_ID = :id AND SIZE = :SIZE AND STATUS_CHG = '0'");
    $stmt->execute(array(":id" => $GROUP_ID, ":SIZE" => $SIZE));
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if($result) {
        
        $output = '
            <p class="card-text text-black pb-2 pt-1">คลังเเยกไซต์: <span class="badge badge-warning" style="font-size: 1rem;" at="'.$result->C_STOCK.'" id="stock_value">'.$result->C_STOCK.'</span></p>
        ';

        $response = [
            'amount' => $output,
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