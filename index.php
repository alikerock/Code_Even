<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/code_even/admin/inc/dbcon.php');

include_once($_SERVER['DOCUMENT_ROOT'] . '/code_even/inc/check_cookie.php');
?>
<style>
/* 모달 배경 */
.cookie-modal {
  display: none; 
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4); /* 배경 어둡게 */
}

/* 모달 콘텐츠 */
.cookie-modal-content {
  background-color: #fff;
  /* margin: 5% auto; */
  padding: 20px;
  margin: 30px;
  border: 5px solid var(--bk900);
  width: 80%;
  max-width: 500px;
  text-align: center;
  border-radius: 5px;
  position: relative;
}

.cookie-close-btn:hover,
.cookie-close-btn:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

#cookieAgreeBtn:hover {
  background-color: #45a049;
}

/* 닫기 버튼 */
.close_txt{
  background: none;
  border: none;
}

</style>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/code_even/admin/css/common.css">
    <link rel="stylesheet" href="http://<?= $_SERVER['HTTP_HOST']; ?>/code_even/admin/css/main.css">
  <title>CodeEven</title>
</head>
<body>
  <div class="mx-auto pt-5" style="width: 320px;">
    <img src="admin/images/sb_logo.png" width="300" height="200" alt="코드이븐로고">
    <div>To be continue...</div>
    <p>코드이븐 클라이언트 페이지는 제작예정입니다</p>
    <p><a href="http://localhost/code_even/admin/login/login.php">어드민(관리자&강사)페이지 바로가기</a></p>
    <ul class="list-group pt-3">
      <li class="list-group-item"><a href="tc_applyform.php" class="link-underline-danger link-offset-2 link-body-emphasis a_underline">강의자신청(임시)</a></li>
      <!-- <li class="list-group-item"><a href="" class="link-underline-danger link-offset-2 link-body-emphasis a_underline">결제페이지(임시)</a></li> -->
    </ul>
  </div>
  <div class="gap-5 align-items-center bgsz ">
    <div class="w-50 container d-flex justify-content-center align-items-center">
      <?php if(!isset($_SESSION['AUID'])){
      ?>
      <div class="w-50 d-flex flex-column align-items-center">
        <h1 class="mt-5">로그인</h1>
        <form action="members/login/login_ok.php" method="POST" class="w-100">
          <label for="inputId" class="form-label mt-3">아이디</label>
          <input type="text" id="inputId" class="form-control" placeholder="아이디를 입력하세요" name="userid" required>
          
          <label for="inputPassword" class="form-label mt-3">비밀번호</label>
          <input type="password" id="inputPassword" class="form-control" placeholder="비밀번호를 입력하세요" name="userpw" required>
          
          <button class="btn btn-primary mt-3 w-100">로그인</button>
          
          <div class="mt-3 d-flex flex-columns justify-content-center gap-3">
            <a href="#" class="link-body-emphasis ">아이디 찾기</a>
            <a href="#" class="link-body-emphasis">비밀번호 찾기</a>
            <a href="members/signup/signup.php" class="link-body-emphasis text-decoration-underline">회원가입</a>
          </div>
        </form>
      </div>
      <?php
        }else{
      ?>
      <a href="http://<?= $_SERVER['HTTP_HOST']; ?>/code_even/members/login/logout.php">로그아웃</a>
      <?php
        } 
      ?>
    </div>
  </div>
  <div id="cookieModal" class="cookie-modal ">
    <div class="cookie-modal-content ">
      <h2 class="d-flex justify-content-center mb-3">CODE EVEN</h2>
      <p>본 웹사이트는 구직용 포트폴리오 웹사이트이며, <br>
      실제로 운영되는 사이트가 아닙니다.</p>
      <hr>
      <div class="text-start">
        <div>
          <span><b>팀원 : </b>홍수진(팀장), 배유나, 조채림, 최은화, 홍은진</span><br>
          <span><b>제작기간</b> : 2024.10.23 - 2024.11.25 </span><br>
          <span><b>개발환경</b> : HTML/CSS, Javascript, J-Query, PHP</span><br>
          <div class="link3">
            <span><b>기획자료 :</b>  <a href="https://www.figma.com/slides/9MsKBvc3jwAm3v1j24QznJ/CODE_EVEN_LMS%EB%94%94%EC%9E%90%EC%9D%B8%EB%B0%9C%ED%91%9C?t=cgmURknfV4lmJRsM-6" target="_blank">figma</a>
            <b> 코드 :</b>  <a href="https://github.com/AppleBee12/Code_Even.git" target="_blank">github</a>
            <b> 관리자 페이지 :</b>  <a href="http://localhost/code_even/admin/index.php" target="_blank">Admin Page</a></p>
          </div>
        </div>
        <hr>
        <div>
          <span><b>업무분장</b></span>
          <p><b>기획 : </b>팀원 전체 <b>디자인 : </b>구현 담당자</p>
        </div>
        <hr>
        <div>
          <span><b>* 구현 완료 페이지 *</b></span><br>
          <span><b>홍수진 : </b>
          <a href="admin/index.php">대시보드,</a> 공통헤더,
          <a href="admin/community/counsel.php">커뮤니티 관리, </a>
          <a href="admin/manual/for_admin.php">관리자 매뉴얼 </a>
        </span><br>
          <span><b>배유나 : </b>
          <a href="admin/student/student_list.php">수강생 관리, </a>
          <a href="admin/inquiry/notice.php">문의 게시판 관리 </a>
          </span><br>
          <span><b>조채림 : </b>
          <a href="members/signup/signup.php">로그인/회원가입, </a>
          <a href="admin/category/category.php">카테고리관리, </a>
          <a href="admin/coupons/coupons.php">쿠폰관리, </a>
           </span><br>
          <span><b>최은화 : </b>
          <a href="admin/lecture/lecture_list.php">강좌관리, </a>
          <a href="admin/book/book_list.php">교재관리 </a>
          </span><br>
          <span><b>홍은진 : </b>
          <a href="admin/teacher/teacher_list.php">강사관리, </a>
          <a href="admin/orders/orders_list.php">결제/배송관리,  </a>
          <a href="admin/sales/lectuer_sales.php">매출통계관리, </a>
          <a href="admin/user/user_list.php">전체회원관리 </a>
          </span><br>
        </div>
        <hr>
        <div>
          <span><b>관리자 아이디 </b>: code_even</span><br>
          <span><b>관리자 비밀번호 </b>: 12345</span>
        </div><hr>
        <div>
          <span><b>강사용 아이디 </b>: even_teacher</span><br>
          <span><b>강사용 비밀번호 </b>: 12345</span>
        </div>
      </div> <hr>
      <div class="d-flex justify-content-start gap-2 mb-3">
        <label class="align-items-end cookie_btn" for="check">오늘 하루 안보기</label>
        <input type="checkbox" id="check">
      </div>
      <button id="cookieCloseBtn" type="button" class="close_txt alarm">
        <img src="admin/images/sb_logo.png" width="50" height="30" alt="코드이븐로고">
        close
      </button>
    </div>
  </div>
</body>

<script>
 $(document).ready(function () {
    const cookieName = 'hideCookieModal'; // 쿠키 이름
    const cookieValue = 'true'; // 쿠키 값
    const cookieExpireDays = 1; // 쿠키 유지 기간 (1일)
    
    // 쿠키 확인 함수
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    // 쿠키 설정 함수
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        document.cookie = `${name}=${value}; expires=${date.toUTCString()}; path=/`;
    }

    // 쿠키 삭제 함수 (테스트용)
    // function deleteCookie(name) {
    //     document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    // }

    // "오늘 하루 안 보기" 클릭 이벤트
    $('#cookieCloseBtn').on('click', function () {
        if ($('#check').is(':checked')) {
            // 체크박스가 체크된 경우 쿠키 설정
            setCookie(cookieName, cookieValue, cookieExpireDays);
        }
        // 모달 닫기
        $('#cookieModal').fadeOut();
    });

    // 페이지 로드 시 쿠키 확인
    if (!getCookie(cookieName)) {
        $('#cookieModal').fadeIn(); // 쿠키가 없으면 모달 표시
    }
});

</script>

</html>
