<?php 
header('Content-Type: application/json');
require_once '../connect.php';


if (isset($_POST['function']) && $_POST['function'] == 'size_change') {
    
    $SIZE = $_POST['size'];
    $GROUP_ID = $_POST['id'];

    $stmt = $connect->prepare("SELECT GROUP_ID,CATEGORY_TH,CATEGORY_ENG,IMG,C_STOCK,TOTAL_MAX,TOTAL_MIN,CREATE_BY,CREATE_DATE,UPDATE_BY,UPDATE_DATE,STATUS_CHG FROM (
        SELECT A.*,SUM(B.MAX_STOCK) AS TOTAL_MAX, SUM(B.MIN_STOCK) AS TOTAL_MIN FROM tb_master AS A INNER JOIN tb_stock_master AS B ON A.GROUP_ID = B.GROUP_ID  AND A.SIZE = B.SIZE
        WHERE B.GROUP_ID = :GROUP_ID AND B.SIZE = :SIZE AND A.STATUS_CHG = '0' AND B.STATUS_CHG = 0 GROUP BY B.GROUP_ID
    ) GROUP_MASTER");
    $stmt->bindParam(':GROUP_ID', $GROUP_ID);
    $stmt->bindParam(':SIZE', $SIZE);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if($result) {

        $output = '
                <input type="hidden" id="fix_max_stock_id" value="'.$result->TOTAL_MAX.'" />
                <div class="row text-center">
                    <div class="col-4">
                        <div class="form-group">
                            <p class="card-text text-black pb-2 pt-1">คลังไซต์ '.$SIZE.' : <span class="badge badge-warning" style="font-size: 1rem;" at="'.$result->C_STOCK.'" id="stock_value">'.$result->C_STOCK.'</span></p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                        <p class="card-text text-black pb-2 pt-1">Max Stock : <span class="badge badge-warning" style="font-size: 1rem;" at="'.$result->TOTAL_MAX.'" id="max_stock_value">'.$result->TOTAL_MAX.'</span></p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                        <p class="card-text text-black pb-2 pt-1">Min Stock : <span class="badge badge-warning" style="font-size: 1rem;" at="'.$result->TOTAL_MIN.'" id="min_stock_value">'.$result->TOTAL_MIN.'</span></p>
                        </div>
                    </div>
                </div>
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