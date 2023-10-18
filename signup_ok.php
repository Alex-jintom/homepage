<?php
include "dbcon.php";

$userid=$_POST["userid"];
$username=$_POST["username"];
$email=$_POST["email"];
$passwd=$_POST["passwd"];
$passwd=hash('sha512',$passwd);

$sql="INSERT INTO members
        (userid, email, username, passwd)
        VALUES('".$userid."', '".$email."', '".$username."', '".$passwd."')";
$result=$mysqli->query($sql) or die($mysqli->error);


if($result){
    user_coupon($userid, 1, "회원가입");
    echo "<script>alert('가입을 환영합니다. );location.href='twocolumn2.php';</script>";
    exit;
}else{
    echo "<script>alert('회원가입에 실패했습니다.');history.back();</script>";
    exit;
}

function user_coupon($userid, $cid, $reason){
    global $mysqli;
    $query="select * from coupons where cid=".$cid;
    $result2 = $mysqli->query($query) or die("query error => ".$mysqli->error);
    $rs2 = $result2->fetch_object();

    $last_date = date("Y-m-d 23:59:59", strtotime("+30 days")); //30일이 지나면 못쓴다.
    $sql="INSERT INTO user_coupons
    (couponid, userid, status, use_max_date, regdate, reason)
    VALUES(".$rs2->cid.", '".$userid."', 1, '".$last_date."', now(), '".$reason."')";
    $ins=$mysqli->query($sql) or die($mysqli->error);
}


?>