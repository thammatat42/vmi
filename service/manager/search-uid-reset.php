<?php
header('Content-Type: application/json');
require_once '../connect.php';

$search = $_POST['search'];
$stmt = $connect->prepare("SELECT * FROM (
    SELECT B.CARD_ID, B.GID, A.UID, A.FNAME, A.LNAME, B.POSITION, B.DEPARTMENT, B.LOCATION, B.EMAIL, A.IMG 
    FROM `tb_user` as A
    INNER JOIN tb_all_user AS B
    ON A.UID = B.UID
) AS list_user WHERE UID = :search");
$stmt->bindParam(':search', $search);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_OBJ);

if(!empty($result)) {
    $response = [
        'status' => true,
        'ResultAuthen' => $result,
        'result' => 'PASS'
    ];

    $data = json_encode($response);

    $array = json_decode($data, true);

    if($array['result'] == "PASS") {

        $IMG = "../../assets/upload/".$array['ResultAuthen']['IMG']."";
    
        $output = '
        <div class="card-body">
            <div class="row">
                <div class="card col px-1 px-md-5" style="align-items: center;"></br>
                    <legend style="font-size: 1.5rem">&nbsp;<i class="fas fa-clipboard-list"></i> ข้อมูลพนักงาน <label style="color: red; font-size: 1rem;">(ข้อควรทราบ: ทุกครั้งที่ทำการ reset password ตัวระบบจะ gen password default เสมอ!)</label></legend>
                    <div class="col-sm-6">
                        <div class="user_info">
                            <div class="col-sm-12">
                                <p class="membership_detail">รหัสพนักงาน: <span>'.$array['ResultAuthen']['UID'].'</span></p>
                                <p class="membership_detail">ชื่อ: <span>'.$array['ResultAuthen']['FNAME'].'</span></p>
                                <p class="membership_detail">นามสกุล: <span>'.$array['ResultAuthen']['LNAME'].'</span></p>
                                <p class="membership_detail">เลเวล: <span>'.$array['ResultAuthen']['POSITION'].'</span></p>
                                <p class="membership_detail">เเผนก: <span>'.$array['ResultAuthen']['DEPARTMENT'].'</span></p>
                                <p class="membership_detail">สถานที่ที่ทำงาน: <span>'.$array['ResultAuthen']['LOCATION'].'</span></p>
                                <p class="membership_detail">รูปภาพ: <img src="'.$IMG.'" style="width: 100px; height: 100px;" alt="profile_img"></p>
                                <p class="membership_detail"><button type="button" id="btn_reset" class="btn btn-danger" style="border: dashed;">รีเซทรหัสผ่าน</button></p>
                            </div>
                        </div>	
                    </div>
                </div>
            </div>
            <input type="hidden" id="uid_profile" value="'.$result->UID.'">
            
        </div>
    ';
        http_response_code(200);
        echo $output;
        // echo $output;
    }
} else {
    http_response_code(404);
    // echo "data not found";
    echo 'User ID: '.$search.' not found!';
}

?>