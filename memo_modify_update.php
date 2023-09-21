<?php session_start();
include "dbcon.php";
ini_set( 'display_errors', '0' );

if(!$_SESSION['UID']){
    echo "member";
    exit;
}

$memoid = $_POST['memoid'];
$memo = $_POST['memo'];

$result = $mysqli->query("select * from memo where memoid=".$memoid) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

if($rs->userid!=$_SESSION['UID']){
    echo "my";
    exit;
}

$sql="update memo set memo='".$memo."' where memoid=".$memoid;//status값을 바꿔준다.
$result=$mysqli->query($sql) or die($mysqli->error);


echo "<div class=\"row g-0\">
    <div class=\"col-md-12\">
    <div class=\"card-body\">
      <p class=\"card-text\">".$memo."</p>
      <p class=\"card-text\"><small class=\"text-muted\">".$_SESSION['UID']." / now</small></p>
      <p class=\"card-text\" style=\"text-align:right\"><a href=\"javascript:;\" onclick=\"memo_modi(".$memoid.")\">수정</a> / <a href=\"javascript:;\" onclick=\"memo_del(".$memoid.")\">삭제</a></p>
    </div>
  </div>
</div>";

?>