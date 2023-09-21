<?php session_start();
include "dbcon.php";
ini_set( 'display_errors', '0' );

if(!$_SESSION['UID']){
    $retun_data = array("result"=>"member");
    echo json_encode($retun_data);
    exit;
}

$imgid = $_POST['imgid'];

$result = $mysqli->query("select * from product_image_table where imgid=".$imgid) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs->userid!=$_SESSION['UID']){
    $retun_data = array("result"=>"my");
    echo json_encode($retun_data);
    exit;
}

$sql="update product_image_table set status=0 where imgid=".$imgid;//status값을 바꿔준다.
$result=$mysqli->query($sql) or die($mysqli->error);
if($result){
   
    //서버에 저장되어 있는 파일 삭제
    $delete_file="/var/www/html/data/".$rs->filename;
    unlink($delete_file);

    $retun_data = array("result"=>"ok");
    echo json_encode($retun_data);
}else{
    $retun_data = array("result"=>"no");
    echo json_encode($retun_data);
}

?>
       