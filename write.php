<?php
include "header.php";

if(!$_SESSION['UID']){
    echo "<script>alert('회원 전용 게시판입니다.');history.back();</script>";
    exit;
}

$bid=$_GET["bid"];//get으로 넘겼으니 get으로 받는다.
$parent_id=$_GET["parent_id"];

if($bid){//bid가 있다는건 수정이라는 의미다.

    $result = $mysqli->query("select * from board where bid=".$bid) or die("query error => ".$mysqli->error);
    $rs = $result->fetch_object();

    if($rs->userid!=$_SESSION['UID']){
        echo "<script>alert('본인 글이 아니면 수정할 수 없습니다.');history.back();</script>";
        exit;
    }

    $fquery="select * from file_table where status=1 and bid=".$rs->bid." order by fid asc";
    $file_result = $mysqli->query($fquery) or die("query error => ".$mysqli->error);
    while($frs = $file_result->fetch_object()){
        $fileArray[]=$frs;
    }

}

if($parent_id){//parent_id가 있다는건 답글이라는 의미다.

    $result = $mysqli->query("select * from board where bid=".$parent_id) or die("query error => ".$mysqli->error);
    $rs = $result->fetch_object();
    $rs->subject = "[RE]".$rs->subject;
}


?>
        <form method="post" action="write_ok.php" enctype="multipart/form-data">
            <input type="hidden" name="bid" value="<?php echo $bid;?>">

            <input type="hidden" name="parent_id" value="<?php echo $parent_id;?>">
            <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">제목</label>
                <input type="text" name="subject" class="form-control" id="exampleFormControlInput1" placeholder="제목을 입력하세요." value="<?php echo $rs->subject;?>">
            </div>
            <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">내용</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" name="content" rows="3"><?php echo $rs->content;?></textarea>
            </div>
            <div class="mb-3">
                <input type="file" name="upfile[]" multiple>
            </div>
            <!-- 첨부된 이미지 표시 -->
            <div class="row row-cols-1 row-cols-md-6 g-4">
                <?php
                    foreach($fileArray as $fa){
                ?>
                <div class="col" id="f_<?php echo $fa->fid;?>">
                    <div class="card h-100">
                        <img src="/data/<?php echo $fa->filename?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <button type="button" class="btn btn-warning" onclick="file_del(<?php echo $fa->fid;?>)">삭제</button>
                    </div>
                    </div>
                </div>
                <?php }?>
               
            </div>
            <!-- 첨부된 이미지 -->
            <br />
            <button type="submit" class="btn btn-primary">등록</button>
        </form>

<script>
    function file_del(fid){

        if(!confirm('삭제하시겠습니까?')){
        return false;
        }
           
        var data = {
            fid : fid
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'file_delete.php' ,
                data  : data ,
                dataType : 'json' ,
                error : function() {} ,
                success : function(return_data) {
                if(return_data.result=="member"){
                    alert('로그인 하십시오.');
                    return;
                }else if(return_data.result=="my"){
                    alert('본인이 작성한 글만 삭제할 수 있습니다.');
                    return;
                }else if(return_data.result=="no"){
                    alert('삭제하지 못했습니다. 관리자에게 문의하십시오.');
                    return;
                }else{
                    $("#f_"+fid).hide();
                }
                }
        });

    }
</script>

        <?php
include "footer.php";
?>
 