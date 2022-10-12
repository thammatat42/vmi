<?php
header('Content-Type: application/json');
require_once '../connect.php';

$search = $_POST['search'];

$stmt = $connect->prepare("SELECT B.CARD_ID,A.UID,A.FNAME,A.LNAME,A.IMG,A.EMAIL,B.POSITION,B.DEPARTMENT,B.LOCATION FROM tb_user AS A INNER JOIN tb_all_user AS B ON A.UID = B.UID WHERE B.CARD_ID = :search OR A.UID = :search");
$stmt->execute(array(":search" => $search));
$result = $stmt->fetch(PDO::FETCH_OBJ);


if(!empty($result)) {
    $UID_CHG = $result->UID;

    $output = '
    <div class="card-body">
        <div class="row">
            <div class="card col px-1 px-md-5"></br>
                <legend style="font-size: 1.5rem">&nbsp;<i class="fas fa-clipboard-list"></i> ข้อมูลพนักงาน</legend>
                <div class="col-sm-6">
                    <div class="user_info">
                        <div class="col-sm-12">
                            <p class="membership_detail">รหัสพนักงาน: <span>'.$result->UID.'</span></p>
                            <p class="membership_detail">ชื่อ: <span>'.$result->FNAME.'</span></p>
                            <p class="membership_detail">นามสกุล: <span>'.$result->LNAME.'</span></p>
                            <p class="membership_detail">เลเวล: <span>'.$result->POSITION.'</span></p>
                            <p class="membership_detail">เเผนก: <span>'.$result->DEPARTMENT.'</span></p>
                            <p class="membership_detail">สถานที่ที่ทำงาน: <span>'.$result->LOCATION.'</span></p>
                            <p class="membership_detail">รูปภาพ: <img src="../../assets/upload/'.$result->IMG.'" style="width: 100px; height: 100px;" alt="profile_img"></p>
                        </div>
                    </div>	
                </div>
            </div>
        </div>
        <input type="hidden" id="uid_profile" value="'.$result->UID.'">
        <table  id="order_logs" class="table table-hover" width="100%" style="text-align: center;">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th></th>
                    <th>สถานะ</th>
                    <th>รหัสสั่งซื้อ</th>
                    <th>รูปภาพ</th>
                    <th>ประเภทสินค้า</th>
                    <th>ไซต์</th>
                    <th>จำนวน</th>
                    <th>ผู้สั่งซื้อ</th>
                    <th>ราคารวม (บาท)</th>
                    <th style="width: 100px">วันที่สั่งซื้อ</th>
                    <th style="width: 100px">วันที่รับสินค้า</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
';
    http_response_code(200);
    echo $output;
    // echo $output;
} else {
    http_response_code(404);
    // echo "data not found";
    echo json_encode(array('status' => false, 'message' => 'Not found '. $search .' of employee this ID!'));
}

?>