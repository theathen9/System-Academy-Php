<?php
require_once "./config/db.php";
require_once "./data/dbShemaData.php";
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$response = ['success'=>false, 'message'=>'Invalid request'];

if($action == 'add'){
    $data = $_POST;
    if(addDepartment($conn, $data)) $response = ['success'=>true,'message'=>'Department added'];
    else $response = ['success'=>false,'message'=>$conn->error];

} elseif($action == 'edit'){
    $id = intval($_POST['department_id']);
    $data = $_POST;
    if(updateDepartment($conn, $id, $data)) $response = ['success'=>true,'message'=>'Department updated'];
    else $response = ['success'=>false,'message'=>$conn->error];

} elseif($action == 'delete'){
    $id = intval($_POST['department_id']);
    if(deleteDepartment($conn, $id)) $response = ['success'=>true,'message'=>'Department deleted'];
    else $response = ['success'=>false,'message'=>$conn->error];

} elseif($action == 'fetch'){
    $res = $conn->query("SELECT * FROM tblDepartment ORDER BY department_id DESC");
    $data = [];
    while($row = $res->fetch_assoc()) $data[] = $row;
    $response = ['success'=>true, 'data'=>$data];

} elseif($action == 'get'){
    $id = intval($_POST['id']);
    $res = $conn->query("SELECT * FROM tblDepartment WHERE department_id=$id");
    $data = $res->fetch_assoc();
    $response = ['success'=>true,'data'=>$data];
}

echo json_encode($response);
