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
            <label for="category_eng">หมวดหมู่ (ENG)</label>
            <input type="text" class="form-control" id="category_eng" name="category_eng" placeholder="หมวดหมู่" value="'.$result->CATEGORY_ENG.'" disabled>
            
        </div>
        <div class="form-group">
            <label for="category_th">หมวดหมู่ (TH)</label>
            <input type="text" class="form-control" id="category_th" name="category_th" placeholder="นามสกุล" value="'.$result->CATEGORY_TH.'" disabled>
            
        </div>
        <div class="form-group">
            <label for="size">ไซส์</label>
            <input type="text" class="form-control" id="size" name="size" placeholder="ไซส์">
            
        </div>
        <div class="form-group">
            <label for="price">ราคา</label>
            <input type="text" class="form-control" name="price" id="price" placeholder="ราคา">
        </div>
    ';

    $output2 = '

        <div class="form-group">
            <label for="customfile_create">รูปภาพ</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="customfile_create" id="customfile_create" accept="image/*" disabled>
                <input type="hidden" id="namefiles_create" name="namefiles_create" value="'.$result->IMG.'">
                <label class="custom-file-label" for="customfile_create" id="file-name-create">'.$result->IMG.'</label>
            </div>
            
        </div>
        <div class="form-group">
            <div class="img-fluid" style="width: 25%; height: 25%; overflow: hidden; background: #cccccc; margin: 0 auto">
                <img src="../../assets/images/uniforms/'.$result->IMG.'" alt="Image Profile" class="img-fluid p-0" >
            </div>
        </div>
    ';

    $response = [
        'cate' => $output,
        'dept' => $output2,
        'message' => 'success'
    ];
    http_response_code(200);
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(array('status' => false, 'message' => 'Not found this group_id'. $GROUP_ID .''));
}


?>