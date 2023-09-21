<?php session_start();
include "dbcon.php";//dbcon.php 안에는 session_start()가 없기때문에 위에 따로 선언해준다.


if(!$_SESSION['UID']){
  echo "<script>alert('회원 전용 게시판입니다.');location.href='twocolumn2.php';</script>";
  exit;
}

// echo "<pre>";
// print_r($_FILES);
// exit;


$subject=$_POST["subject"];
$content=$_POST["content"];
$bid=$_POST["bid"];//bid값이 있으면 수정이고 아니면 등록이다.
$parent_id=$_POST["parent_id"];//parent_id가 있으면 답글이다.
$userid=$_SESSION['UID'];//userid는 세션값으로 넣어준다.
$status=1;//status는 1이면 true, 0이면 false이다.

if($bid){//bid값이 있으면 수정이고 아니면 등록이다.
  $result = $mysqli->query("select * from board where bid=".$bid) or die("query error => ".$mysqli->error);
  $rs = $result->fetch_object();

  if($rs->userid!=$_SESSION['UID']){
      echo "<script>alert('본인 글이 아니면 수정할 수 없습니다.');location.href='/';</script>";
      exit;
  }

  $sql="update board set subject='".$subject."', content='".$content."', modifydate=now() where bid=".$bid;

}else{

  if($parent_id){//답글인 경우 쿼리를 수정해서 parent_id를 넣어준다.
      $sql="insert into board (userid,subject,content,parent_id) values ('".$userid."','".$subject."','".$content."','".$parent_id."')";
  }else{
      $sql="insert into board (userid,subject,content) values ('".$userid."','".$subject."','".$content."')";
  }
 
}
$result=$mysqli->query($sql) or die($mysqli->error);
if(!$bid)$bid = $mysqli -> insert_id;

if(count($_FILES["upfile"]["name"])>0){//첨부한 파일이 있으면

  for($k=0;$k<count($_FILES["upfile"]["name"]);$k++){

      if($_FILES['upfile']['size'][$k]>10240000){//10메가
          echo "<script>alert('10메가 이하만 첨부할 수 있습니다.');history.back();</script>";
          exit;
      }

      if($_FILES['upfile']['type'][$k]!='image/jpeg' and $_FILES['upfile']['type'][$k]!='image/gif' and $_FILES['upfile']['type'][$k]!='image/png'){//이미지가 아니면, 다른 type은 and로 추가
          echo "<script>alert('이미지만 첨부할 수 있습니다.');history.back();</script>";
          exit;
      }

      $save_dir = "/var/www/html/real-bright93/data/";//파일을 업로드할 디렉토리
      $filename = $_FILES["upfile"]["name"][$k];
      $ext = pathinfo($filename,PATHINFO_EXTENSION);//확장자 구하기
      $newfilename = date("YmdHis").substr(rand(),0,6);
      $upfile = $newfilename.".".$ext;//새로운 파일이름과 확장자를 합친다
     
      if(move_uploaded_file($_FILES["upfile"]["tmp_name"][$k], $save_dir.$upfile)){//파일 등록에 성공하면 디비에 등록해준다.
          $sql="INSERT INTO jin.file_table
          (bid, userid, filename)
          VALUES(".$bid.", '".$_SESSION['UID']."', '".$upfile."')";
          $result=$mysqli->query($sql) or die($mysqli->error);
      }

  }

}


if($result){
  echo "<script>location.href='twocolumn2.php';</script>";
  exit;
}else{
  echo "<script>alert('글등록에 실패했습니다.');history.back();</script>";
  exit;
}

?>