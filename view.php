<?php
include "header.php";


$bid=$_GET["bid"];
$result = $mysqli->query("select * from board where bid=".$bid) or die("query error => ".$mysqli->error);
$rs = $result->fetch_object();

$query="select * from memo where status=1 and bid=".$rs->bid." order by memoid asc";
$memo_result = $mysqli->query($query) or die("query error => ".$mysqli->error);
while($mrs = $memo_result->fetch_object()){
    $memoArray[]=$mrs;
}

$fquery="select * from file_table where status=1 and bid=".$rs->bid." order by fid asc";
$file_result = $mysqli->query($fquery) or die("query error => ".$mysqli->error);
while($frs = $file_result->fetch_object()){
    $fileArray[]=$frs;
}


$query2="select type,count(*) as cnt from recommend r where bid=".$rs->bid." group by type";
$rec_result = $mysqli->query($query2) or die("query error => ".$mysqli->error);
while($recs = $rec_result->fetch_object()){
  $recommend[$recs->type] = $recs->cnt;
}

?>

<style>
  img {
    max-width:90%;
  }
</style>

      <h3 class="pb-4 mb-4 fst-italic border-bottom" style="text-align:center;">
        - 게시판 보기 -
      </h3>

      <article class="blog-post">
        <h2 class="blog-post-title"><?php echo $rs->subject;?></h2>
        <p class="blog-post-meta"><?php echo $rs->regdate;?> by <a href="#"><?php echo $rs->userid;?></a></p>

        <hr>
        <?php
          foreach($fileArray as $fa){
        ?>
          <p><img src="/data/<?php echo $fa->filename;?>"></p>
        <?php }?>
        <p>
          <?php echo $rs->content;?>
        </p>
       
        <div style="text-align:center;">
          <button type="button" class="btn btn-lg btn-primary" id="like_button">추천&nbsp;<span id="like"><?php echo number_format($recommend['like']);?></span></button>
          <button type="button" class="btn btn-lg btn-warning" id="hate_button">반대&nbsp;<span id="hate"><?php echo number_format($recommend['hate']);?></span></button>
        </div>
        <hr>
      </article>

     

      <nav class="blog-pagination" aria-label="Pagination">
        <a class="btn btn-outline-secondary" href="index.php">목록</a>
        <a class="btn btn-outline-secondary" href="write.php?parent_id=<?php echo $rs->bid;?>">답글</a>
        <a class="btn btn-outline-secondary" href="write.php?bid=<?php echo $rs->bid;?>">수정</a>
        <a class="btn btn-outline-secondary" href="delete.php?bid=<?php echo $rs->bid;?>">삭제</a>
      </nav>

      <div style="margin-top:20px;">
        <form class="row g-3">
          <div class="col-md-10">
            <textarea class="form-control" placeholder="댓글을 입력해주세요." id="memo" style="height: 60px"></textarea>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-secondary" id="memo_button">댓글등록</button>
          </div>
        </form>
      </div>
      <div id="memo_place">
      <?php
        foreach($memoArray as $ma){
      ?>
        <div class="card mb-4" id="memo_<?php echo $ma->memoid?>" style="max-width: 100%;margin-top:20px;">
          <div class="row g-0">
            <div class="col-md-12">
              <div class="card-body">
                <p class="card-text"><?php echo $ma->memo;?></p>
                <p class="card-text"><small class="text-muted"><?php echo $ma->userid;?> / <?php echo $ma->regdate;?></small></p>
                <p class="card-text" style="text-align:right"><a href="javascript:;" onclick="memo_modi(<?php echo $ma->memoid?>)">수정</a> / <a href="javascript:;" onclick="memo_del(<?php echo $ma->memoid?>)">삭제</a></p>
              </div>
            </div>
          </div>
        </div>
      <?php }?>
      </div>

<script>

  $("#like_button").click(function () {

    if(!confirm('추천하시겠습니까?')){
      return false;
    }
     
      var data = {
          type : 'like' ,
          bid : <?php echo $bid;?>
      };
          $.ajax({
              async : false ,
              type : 'post' ,
              url : 'like_hate.php' ,
              data  : data ,
              dataType : 'json' ,
              error : function() {} ,
              success : function(return_data) {
                if(return_data.result=="member"){
                  alert('로그인 하십시오.');
                  return;
                }else if(return_data.result=="check"){
                  alert('이미 추천이나 반대를 하셨습니다.');
                  return;
                }else if(return_data.result=="no"){
                  alert('다시한번 시도해주십시오.');
                  return;
                }else{
                  $("#like").text(return_data.cnt);
                }
              }
          });
  });

  $("#hate_button").click(function () {

    if(!confirm('반대하시겠습니까?')){
      return false;
    }
     
      var data = {
          type : 'hate' ,
          bid : <?php echo $bid;?>
      };
          $.ajax({
              async : false ,
              type : 'post' ,
              url : 'like_hate.php' ,
              data  : data ,
              dataType : 'json' ,
              error : function() {} ,
              success : function(return_data) {
                if(return_data.result=="member"){
                  alert('로그인 하십시오.');
                  return;
                }else if(return_data.result=="check"){
                  alert('이미 추천이나 반대를 하셨습니다.');
                  return;
                }else if(return_data.result=="no"){
                  alert('다시한번 시도해주십시오.');
                  return;
                }else{
                  $("#hate").text(return_data.cnt);
                }
              }
          });
  });


  $("#memo_button").click(function () {
   
        var data = {
            memo : $('#memo').val() ,
            bid : <?php echo $bid;?>
        };
            $.ajax({
                async : false ,
                type : 'post' ,
                url : 'memo_write.php' ,
                data  : data ,
                dataType : 'html' ,
                error : function() {} ,
                success : function(return_data) {
                  if(return_data=="member"){
                    alert('로그인 하십시오.');
                    return;
                  }else{
                    $("#memo_place").append(return_data);
                  }
                }
        });
    });

  function memo_del(memoid){

    if(!confirm('삭제하시겠습니까?')){
      return false;
    }
   
    var data = {
        memoid : memoid
    };
        $.ajax({
            async : false ,
            type : 'post' ,
            url : 'memo_delete.php' ,
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
                $("#memo_"+memoid).hide();
              }
            }
    });

  }

  function memo_modi(memoid){

    var data = {
        memoid : memoid
    };
   
    $.ajax({
          async : false ,
          type : 'post' ,
          url : 'memo_modify.php' ,
          data  : data ,
          dataType : 'html' ,
          error : function() {} ,
          success : function(return_data) {
            if(return_data=="member"){
              alert('로그인 하십시오.');
              return;
            }else if(return_data=="my"){
              alert('본인이 작성한 글만 수정할 수 있습니다.');
              return;
            }else if(return_data=="no"){
              alert('수정하지 못했습니다. 관리자에게 문의하십시오.');
              return;
            }else{
              $("#memo_"+memoid).html(return_data);
            }
          }
    });

  }

  function memo_modify(memoid){

    var data = {
        memoid : memoid,
        memo : $('#memo_text_'+memoid).val()
    };

    $.ajax({
          async : false ,
          type : 'post' ,
          url : 'memo_modify_update.php' ,
          data  : data ,
          dataType : 'html' ,
          error : function() {} ,
          success : function(return_data) {
            if(return_data=="member"){
              alert('로그인 하십시오.');
              return;
            }else if(return_data=="my"){
              alert('본인이 작성한 글만 수정할 수 있습니다.');
              return;
            }else if(return_data=="no"){
              alert('수정하지 못했습니다. 관리자에게 문의하십시오.');
              return;
            }else{
              $("#memo_"+memoid).html(return_data);
            }
          }
    });

    }

</script>


      <?php
include "footer.php";
?>