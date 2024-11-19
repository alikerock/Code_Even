<?php

  $title = "강좌 등록";

  include_once($_SERVER['DOCUMENT_ROOT'] . '/CODE_EVEN/admin/inc/header.php');

  $leid = isset($_GET['leid']) ? $_GET['leid'] : '';

  // 카테고리 데이터를 불러오기
  $sql_cate = "SELECT * FROM category ORDER BY step, pcode";
  $result_cate = $mysqli->query($sql_cate);

  $categories = [];
  while ($categoryObj = $result_cate->fetch_object()) {
    $categories[] = $categoryObj;
}

  // 선택한 분류가 있으면 그 값 아니면 빈값
  $selected_cate1 = isset($_POST['cate1']) ? $_POST['cate1'] : '';
  $selected_cate2 = isset($_POST['cate2']) ? $_POST['cate2'] : '';
  $selected_cate3 = isset($_POST['cate3']) ? $_POST['cate3'] : '';

  // 강좌 카테고리와 연결된 book 테이블 교재 데이터 불러오기
  $sqlBooks = "SELECT * FROM book WHERE cate1 = '{$selected_cate1}' AND cate2 = '{$selected_cate2}' AND cate3 = '{$selected_cate3}'";
  $resultBooks = $mysqli->query($sqlBooks);

  $books = [];
  while ($bookObj = $resultBooks->fetch_object()) {
      $books[] = $bookObj;
  }

  // 실습 파일 엽로드
  // 요청 방식이 POST 이고, 폼에 사용자가 파일을 업로드 했으며 강사 아이디랑 강의 아이디가 품을 통해 들어왔을 때 실행
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['practice_file']) && isset($_POST['lecture_id']) && isset($_POST['instructor_id'])) {
    // 데이터 받기
    $lectureVideoId = $_POST['lecture_video_id'];
    $instructorId = $_POST['instructor_id'];
    $file = $_FILES['practice_file'];

    // 지원되는 파일 형식
    $fileType = ['application/pdf', 'application/msword', 'application/zip', 'application/x-zip-compressed'];
    
    // 파일이 지원되는 형식인지 체크
    if (in_array($file['type'], $fileType)) {
      $uploadFile = 'uploads/files/';
      $filePath = $uploadFile . basename($file['name']);
      
      // 파일 저장
      if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // 데이터베이스에 삽입
        // 아래와 같이 다른 이름을 사용해도 됩니다.
        $uploadfileQuery = $mysqli->prepare("INSERT INTO lefile (lecdid, lepid, fname, fpath, ftype) VALUES (?, ?, ?, ?, ?)");
        // 쿼리 실행
        $uploadfileQuery->bind_param("iisss", $lectureId, $instructorId, $file['name'], $filePath, $file['type']);
        $uploadfileQuery->execute();

        echo "실습 파일이 업로드되었습니다!";
      } else {
        echo "파일 업로드 실패!";
      }
    } else {
      echo "지원되지 않는 파일 형식입니다!";
    }
  }

  // 강의 저장
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['video_url'], $_POST['lecture_id'], $_POST['instructor_id'], $_POST['video_order'])) {
    $videoUrl = $_POST['video_url'];
    $lectureId = $_POST['lecture_id'];
    $instructorId = $_POST['instructor_id'];
    $videoOrder = $_POST['video_order'];  // 동영상 순서 (optional)

    // URL 유효성 검사 및 저장
    if (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
        // 동영상 정보를 levideo 테이블에 저장
        $uploadvideoQuery = $mysqli->prepare("
            INSERT INTO levideo (lecpid, lepid, video_url, orders)
            VALUES (?, ?, ?, ?)
        ");
        $uploadvideoQuery->bind_param("iisi", $lectureId, $instructorId, $videoUrl, $videoOrder);
        $uploadvideoQuery->execute();

        echo "동영상 URL이 저장되었습니다!";
    } else {
        echo "유효하지 않은 URL입니다.";
    }
  }

  // 임시 저장 클릭 시
  // 임시 저장 버튼 클릭 시 처리
  if (isset($_POST['draft_save'])) {
  // 폼 데이터 수집
  $cate1 = $_POST['cate1'];
  $cate2 = $_POST['cate2'];
  $cate3 = $_POST['cate3'];
  $title = $_POST['title'];
  $name = $_POST['name'];
  $price = $_POST['price'];
  $period = $_POST['period'];
  $isrecipe = isset($_POST['isrecipe']) ? 1 : 0;
  $isgeneral = isset($_POST['isgeneral']) ? 1 : 0;
  $image = $_FILES['image']['name'];  // 이미지 파일 처리 로직 추가 필요

    // lecdraft 테이블에 임시 저장 쿼리
    $sql_draft = "INSERT INTO lecdraft (lecid, cate1, cate2, cate3, title, name, price, period, isrecipe, isgeneral, image, created_at, isfinal)
                  VALUES ('$pid', '$cate1', '$cate2', '$cate3', '$title', '$name', '$price', '$period', '$isrecipe', '$isgeneral', '$image', NOW(), 0)";

    if ($mysqli->query($sql_draft)) {
      // 임시 저장 성공 후, 퀴즈/시험 입력 페이지로 이동
      echo "<script>alert('임시 저장되었습니다. 퀴즈/시험 정보를 입력하세요.');</script>";
      echo "<script>location.href='quiz_test_up.php?draft_id=" . $mysqli->insert_id . "';</script>";
    } else {
      echo "<script>alert('임시 저장에 실패했습니다. 다시 시도해주세요.');</script>";
    }
  }



?>

<div class="container">
  <h2>강좌 등록</h2>
  <div class="content_bar d-flex justify-content-between align-item-center cent">
    <h3>강좌 기본 정보 입력</h3>
    <small>* 분류 설정과 강자명은 필수로 입력해야 임시 저장 가능합니다.</small>
  </div>
  <form action="lecture_up_ok.php" id="lecture_save" enctype="multipart/form-data">
  <input type="hidden" name="leid" value="<?= $leid; ?>">
    <table class="table">
      <tbody>
        <tr>
          <th scope="row">분류 설정 <b>*</b></th>
          <td colspan="2">
            <select name="cate1" id="cate1" class="form-select" aria-label="대분류">
              <option selected>대분류</option>
              <?php foreach ($categories as $category) {
                if ($category->step == 1) { // 대분류만
                  echo "<option value='{$category->code}'>{$category->name}</option>";
                }
              } ?>
            </select>
          </td>
          <td colspan="2">
            <select name="cate2" id="cate2" class="form-select" aria-label="Default select example">
              <option selected value="">중분류</option>
            </select>
          </td>
          <td colspan="2">
            <select name="cate3" id="cate3" class="form-select" aria-label="Default select example">
              <option selected value="">소분류</option>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row">강좌명 <b>*</b></th>
          <td colspan="6">
            <input type="text" name="title" id="title" class="form-control" placeholder="기초부터 확실하게! (페이지의 내용 전달을 위한 HTML, 스타일 설정을 위한 CSS 기초 학습)">
          </td>
        </tr>
        <tr>
          <th scope="row">강사명 <b>*</b></th>
          <td colspan="2">
            <input type="text" name="name" class="form-control" placeholder="admin">
          </td>
          <td name="image" class="box_container" colspan="4" rowspan="5">
            <div class="box">
              <span>강좌 썸네일 이미지를 선택해주세요.</span>
              <div class="image"><img src="" alt=""></div>
            </div>
            <div class="input-group mb-3">
              <input name="image" accept="image/*" type="file" id="image" class="form-control">
            </div>
          </td>
        </tr>
        <tr>
          <th scope="row">수강료 <b>*</b></th>
          <td colspan="2">
            <div class="input-group">
              <input name="price" type="text" class="form-control" aria-label="원" oninput="priceNum(this)">
              <span class="input-group-text">원</span>
            </div>
          </td>
        </tr>
        <tr>
          <th scope="row">교재 선택 <b>*</b></th>
          <td colspan="2">
            <select name="" id="book" class="form-select">
              <option value="0">SELECT</option>
              <option value="1">없음</option>
              <?php if (!empty($books)) {
                foreach ($books as $book) {
                  echo "<option value='{$book->boid}'>{$book->title}</option>";
                }
              } ?>
            </select>
            <small class="text-muted">* 필요한 교재가 있다면 교재 목록에서 우선 등록해 주세요.</small>
          </td>
        </tr>
        <tr>
        <th scope="row">
          <label for="period">교육 기간 <b>*</b></label>
        </th>
          <td colspan="2">
            <select id="period" name="period" class="form-select">
              <option value="30">30일</option>
              <option value="60">60일</option>
              <option value="90">90일</option>
              <option value="120">120일</option>
              <option value="150">150일</option>
              <option value="180">180일</option>
            </select>
            <small class="text-muted">* 교육 기간은 30일 단위로 설정 가능합니다.</small>
          </td>
        </tr>
        <tr>
          <th scope="row">강좌 유형 <b>*</b></th>
          <td colspan="4">
            <div class="d-flex gap-4">
              <div class="form-check">
                <input name="isrecipe" class="form-check-input" type="radio" name="courseType" id="recipeCourse">
                <label class="form-check-label" for="isrecipe">레시피 강좌</label>
              </div>
              <div class="form-check">
                <input name="isgeneral" class="form-check-input" type="radio" name="courseType" id="generalCourse" checked>
                <label class="form-check-label" for="isgeneral">일반 강좌</label>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  
    <!-- 강의 설정 영역 -->
    <div class="content_bar cent">
      <h3>강의 설정</h3>
    </div>
    <div>
      <div class="video d-flex justify-content-between align-items-center bg-light border rounded-3">
        <h5 class="mb-0">1강</h5>
        <i class="bi bi-x"></i>
      </div>
      <table class="table">
        <colgroup>
          <col width="160">  
          <col width="516">  
          <col width="160">
          <col width="516">  
        </colgroup>
        <tbody>
          <tr>
            <th scope="row">강의명 <b>*</b></th>
            <td colspan="3">
              <input type="text" class="form-control" placeholder="강의명을 입력해 주세요.">
            </td>
          </tr>
          <tr>
            <th scope="row">강의 설명 <b>*</b></th>
            <td colspan="3">
              <textarea class="form-control" rows="3" placeholder="강의 설명을 입력해 주세요."></textarea>
            </td>
          </tr>
          <tr>
            <th scope="row">퀴즈 선택 <b>*</b></th>
            <td>
              <select class="form-select">
                <option selected>퀴즈를 선택해 주세요.</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </td>
            <th scope="row">시험 선택 <b>*</b></th>
            <td>
              <select class="form-select">
                <option selected>시험을 선택해 주세요.</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </td>
          </tr>
          <tr>
            <th scope="row">실습 파일 등록 <b>*</b></th>
            <td>
              <input name="practice_file" class="form-control" type="file">
            </td>
            <th scope="row">동영상 주소 <b>*</b></th>
            <td>
              <div class="input-group">
                <span class="input-group-text">https://</span>
                <input type="text" class="form-control" placeholder="www.code_even.com">
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="leplus d-flex justify-content-center align-items-center bg-white border rounded-3 boder-secondary">
        <i class="bi bi-plus"></i>
      </div>
    </div>
    <div class="d-flex justify-content-end gap-2 mt-4 mb-5">
      <a href="" type="button" class="btn btn-secondary">등록</a>
      <a href="" type="button" class="btn btn-secondary">임시 저장</a>
      <a href="" type="button" class="btn btn-danger">취소</a>
    </div>
  </form>
</div>

<script>

  // 카테고리 데이터 변환
  const categories = <?php echo json_encode($categories); ?>;

  // 대분류 선택 -> 중분류 업데이트
  $('#cate1').on('change', function() {
    const cate1 = $(this).val();

    if(cate1) {
      const filterCate2 = categories.filter(category => category.step == 2 && category.pcode == cate1);

      $('#cate2').html('<option value="">중분류</option>');
      filterCate2.forEach(category => {
        $('#cate2').append(`<option value="${category.code}">${category.name}</option>`);
      });
      $('#cate3').html('<option value="">소분류</option>');

    }else{

      $('#cate2').html('<option value="">중분류</option>');
      $('#cate3').html('<option value="">소분류</option>');

    }
  });

  // 중분류 선택 -> 소분류 업데이트
  $('#cate2').on('change', function() {
    const cate2 = $(this).val();

    if(cate2) {
      const filterCate3 = categories.filter(category => category.step == 3 && category.pcode == cate2);

      $('#cate3').html('<option value="">소분류</option>')
      filterCate3.forEach(category => {
        $('#cate3').append(`<option value="${category.code}">${category.name}</option>`);
      });

    }else{

      $('#cate3').html('<option value="">소분류</option>');

    }
  });

  // 수강료 입력 시 1,000 단위 반점
  function priceNum(input) {
    let value = input.value.replace(/[^0-9]/g, ''); // 숫자만 입력 가능하게!
    let priceValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, ','); // 100 단위 반점 추가
    input.value = priceValue; // input에 입력한 값에 세 자릿수마다 반점 추가
  }
  
  // 카테고리 변경 시 교재 목록 업데이트
  function updateBooks() {
    console.log({
        cate1: $('#cate1').val(),
        cate2: $('#cate2').val(),
        cate3: $('#cate3').val(),
        title: $('#title').val()
    }); // 콘솔 확인용

    let formData = new FormData(); // formData 정의
    formData.append('cate1', $('#cate1').val());
    formData.append('cate2', $('#cate2').val());
    formData.append('cate3', $('#cate3').val());
    formData.append('title', $('#title').val());

    if (cate1 && cate2 && cate3) { // 모든 카테고리 선택 시
      $.ajax({
        url: 'bselect_update.php',
        data:formData,
        method: 'POST',
        dataType:'json',
        processData: false,
        contentType: false,
        success: function (data) {
          console.log(data);
          $('#book').html('<option value="">SELECT</option>'); // 기존 옵션 초기화
          data.forEach(book => {
            $('#book').append(`<option value="${book.boid}">${book.book}</option>`);
          });
        },
        error: function () {
          alert('교재 데이터를 가져오는 중 오류가 발생했습니다.');
        }
      });
    } else {
      $('#book').html('<option value="">SELECT</option>');
    }
  }

  // 카테고리 변경 시 교재 목록 업데이트
  $('#cate1, #cate2, #cate3').on('change', updateBooks);

  
  // 썸네일 첨부하면 class image에 출력
  $('#image').on('change', function (event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();

    reader.onload = function (e) {
      $('.image img').attr('src', e.target.result);
      $('.image img').attr('alt', file.name);
      $('.box span').css('display', 'none'); // 텍스트 숨기기
    };

    reader.readAsDataURL(file);
  }
});







</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/code_even/admin/inc/footer.php');
?>


