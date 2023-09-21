<?php
include "header.php";
?>

<body class="text-center">
   
        <main class="form-signin">
        <form style="padding:200px;" method="post" action="login_ok.php">
            <img class="mb-4" src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">jin 관리자 페이지</h1>

            <div class="form-floating" style="margin-bottom:10px;">
            <input type="text" name="userid" class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">아이디를 입력하세요</label>
            </div>
            <div class="form-floating">
            <input type="password" name="passwd" class="form-control" id="floatingPassword" placeholder="Password" required>
            <label for="floatingPassword">비밀번호를 입력하세요.</label>
            </div>

           
            <button class="w-100 btn btn-lg btn-primary" style="margin-top:20px;" type="submit">로그인</button>

        </form>
        </main>

</body>        

<?php
include "footer.php";
?>