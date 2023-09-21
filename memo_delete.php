<?php session_start();
include "dbcon.php";
ini_set( 'display_errors', '0' );

if(!$_SESSION['UID']){
    $retun_data = array("result"=>"member");
    echo json_encode($retun_data);
    exit;
}

$memoid = $_POST['memoid'];

$result = $mysqli->query("select * from memo where memoid=".$memoid) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs->userid!=$_SESSION['UID']){
    $retun_data = array("result"=>"my");
    echo json_encode($retun_data);
    exit;
}

$sql="update memo set status=0 where memoid=".$memoid;//status값을 바꿔준다.
$result=$mysqli->query($sql) or die($mysqli->error);
if($result){
    $retun_data = array("result"=>"ok");
    echo json_encode($retun_data);
}else{
    $retun_data = array("result"=>"no");
    echo json_encode($retun_data);
}

?>