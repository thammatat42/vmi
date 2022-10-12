<?php
header('Content-Type: application/json');
require_once '../connect.php';

$GROUP_ID = $_POST['id'];

$stmt = $connect->prepare("SELECT * FROM tb_group_master  WHERE GROUP_ID = :id AND STATUS_CHG = '0'");
$stmt->execute(array(":id" => $GROUP_ID));
$result = $stmt->fetch(PDO::FETCH_OBJ);


if(!empty($result)) {
    $output = '
        <div class="form-group">
            <label for="group_category_ENG_edit">หมวดหมู่อังกฤษ</label>
            <input type="text" class="form-control" name="group_category_ENG_edit" id="group_category_ENG_edit" value="'.$result->CATEGORY_ENG.'" placeholder="หมวดหมู่อังกฤษ">
        </div>

        <div class="form-group">
            <label for="group_category_TH_edit">หมวดหมู่ไทย</label>
            <input type="text" class="form-control" name="group_category_TH_edit" id="group_category_TH_edit" value="'.$result->CATEGORY_TH.'" placeholder="หมวดหมู่ไทย">
        </div>

        <div class="form-group">
            <label for="customFile">รูปภาพ</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="customfile" id="customfile" accept="image/*">
                <input type="hidden" id="namefiles" name="namefiles">
                <label class="custom-file-label" for="customfile" id="file-name">เลือกรูปภาพ</label>
            </div>

        </div>

        <div class="form-group">
            <div class="img-fluid" style="width: 25%; height: 25%; overflow: hidden; background: #cccccc; margin: 0 auto">
                <img src="../../assets/images/uniforms/'.$result->IMG.'" alt="Image Profile" class="img-fluid p-0" >
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