<?php
header('Content-Type: application/json');
require_once '../connect.php';

$search = $_POST['search'];

$stmt = $connect->prepare("SELECT * FROM tb_all_user  Where UID = :search");
$stmt->execute(array(":search" => $search));
$result = $stmt->fetch(PDO::FETCH_OBJ);

$UID_CHG = $result->UID;


if(!empty($result)) {
    $output = '
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 px-1 px-md-5">
                            <div class="form-group">
                                <label for="first_name">ชื่อจริง</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="ชื่อจริง" value="'.$result->FNAME.'" disabled>
                                
                            </div>
                            <div class="form-group">
                                <label for="last_name">นามสกุล</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="นามสกุล" value="'.$result->LNAME.'" disabled>
                                
                            </div>
                            <div class="form-group">
                                <label for="username">ชื่อผู้ใช้งาน</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="ชื่อผู้ใช้งาน" value="'.$UID_CHG.'" disabled>
                                
                            </div>
                            <div class="form-group">
                                <label for="password">รหัสผ่าน</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="รหัสผ่าน" value="'.$UID_CHG.'" disabled>
                            </div>

                        </div>
                        <div class="col-md-6 px-1 px-md-5">

                            <div class="form-group">
                                <label for="email">อีเมลล์</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="อีเมลล์" value="'.$result->EMAIL.'" disabled>
                            </div>

                            <div class="form-group">
                                <label for="permission">สิทธิ์การใช้งาน</label>
                                <select class="form-control" name="status" id="permission" required>
                                    <option value disabled selected>กำหนดสิทธิ์</option>
                                    <option value="Admin">Admin</option>
                                    <option value="User">User</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="customfile">รูปโปรไฟล์</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="customfile" id="customfile" accept="image/*" required>
                                    <input type="hidden" id="namefiles" name="namefiles">
                                    <label class="custom-file-label" for="customfile" id="file-name">เลือกรูปภาพ</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div id="preview" class="img-fluid" style="width: 20%; height: 20%; overflow: hidden; background: #cccccc; margin: 0 auto;"></div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit" id="submit">บันทึกข้อมูล</button>
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