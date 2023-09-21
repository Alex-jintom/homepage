<?php session_start();
include "dbcon.php";
ini_set( 'display_errors', '0' );


$poid1 = $_POST['poid1'];
$poid2 = $_POST['poid2'];
$option_price1=0;
$option_price2=0;

$query="select * from product_options where poid='".$poid1."'";
$result = $mysqli->query($query) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();
$image_url = $rs->image_url;
$option_price1 = $rs->option_price;
$wcode=$poid1;

if($poid2){
    $query="select * from product_options where poid='".$poid2."'";
    $result = $mysqli->query($query) or die("query error => ".$mysqli->error);
    $rs = $result->fetch_object();
    $option_price2 = $rs->option_price;
    $wcode=$poid1."_".$poid2;
}

    $query2="select cnt from wms where wcode='".$wcode."'";
    $result2 = $mysqli->query($query2) or die("query error => ".$mysqli->error);
    $rs2 = $result2->fetch_object();
    $wmscnt = $rs2->cnt??0;

$data = array("image_url"=>$image_url,"option_price1"=>$option_price1,"option_price2"=>$option_price2,"cnt"=>$wmscnt);
echo json_encode($data);

?>
 

 