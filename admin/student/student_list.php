<?php
$title = "수강생 관리";
include_once($_SERVER['DOCUMENT_ROOT'] . '/code_even/admin/inc/header.php');

// 게시글 개수 구하기
$keywords = isset($_GET['keywords']) ? $mysqli->real_escape_string($_GET['keywords']) : '';
$where_clause = '';

if ($keywords) {
  $where_clause = "WHERE user.username LIKE '%$keywords%' OR user.userid LIKE '%$keywords%'";
}

$page_sql = "SELECT COUNT(*) AS cnt FROM class_data JOIN user ON class_data.uid = user.uid $where_clause";
$page_result = $mysqli->query($page_sql);
$page_data = $page_result->fetch_assoc();
$row_num = $page_data['cnt'];

// 페이지네이션
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$list = 10;
$start_num = ($page - 1) * $list;
$block_ct = 5;
$block_num = ceil($page / $block_ct);
$block_start = (($block_num - 1) * $block_ct) + 1;
$block_end = $block_start + $block_ct - 1;

$total_page = ceil($row_num / $list);
$total_block = ceil($total_page / $block_ct);
if ($block_end > $total_page) {
  $block_end = $total_page;
}

$sql = "SELECT class_data.*, user.username, user.userid, lecture.title, lecture.date, lecture.period 
        FROM class_data 
        JOIN user ON class_data.uid = user.uid 
        JOIN lecture ON class_data.leid = lecture.leid 
        $where_clause 
        ORDER BY class_data.cdid DESC 
        LIMIT $start_num, $list";
$result = $mysqli->query($sql);

$dataArr = [];
while ($data = $result->fetch_object()) {
  $dataArr[] = $data;
}

?>
<div class="container">
  <h2>수강생목록</h2>
  <form class="row justify-content-end">
    <div class="col-lg-4">
      <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="검색어를 입력하세요." name="keywords"
          value="<?= htmlspecialchars($keywords); ?>">
        <button type="submit" class="btn btn-secondary">
          <i class="bi bi-search"></i>
        </button>
      </div>
    </div>
  </form>


  <table class="table list_table">
    <thead>
      <tr>
        <th scope="col">
          <input class="form-check-input" type="checkbox" value="" id="allCheck">
        </th>
        <th scope="col">번호</th>
        <th scope="col">아이디</th>
        <th scope="col">이름</th>
        <th scope="col">강좌명</th>
        <th scope="col">진도율</th>
        <th scope="col">수강이수</th>
        <th scope="col">학습기간</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($dataArr) {
        foreach ($dataArr as $no) {
          ?>
          <tr>
            <th scope="row">
              <input class="form-check-input itemCheckbox" type="checkbox" value="" id="flexCheckDefault">
            </th>
            <td>1</td>
            <td><a href="student_details.php?cdid=<?= $no->cdid; ?>" class="underline"><?= $no->userid ?></a></td>
            <td><a href="student_details.php?cdid=<?= $no->cdid; ?>" class="underline"><?= $no->username ?></a></td>
            <td><?= $no->title ?></td>
            <td></td>
            <td>
              <button type="button" id="printButton">
                <span class="badge text-bg-dark">이수증</span>
              </button>
            </td>
            <!-- <td>2024.10.19 ~ 2024.10.31</td> -->
            <td>
              <?php
              $set_date = date('Y-m-d', strtotime($no->date));
              $start_date = new DateTime($no->date); // DateTime 객체 생성
              $start_date->modify("+{$no->period} days"); // 기간을 더함
              $end_date = $start_date->format('Y-m-d'); // 종료 날짜 포맷팅
              ?>

              <?= $set_date ?> ~ <?= $end_date ?>
            </td>
          </tr>
        </tbody>
        <?php
        }
      } else {
        echo "<tr><td colspan='8'>검색 결과가 없습니다.</td></tr>";
      }
      ?>
  </table>

  <div class="d-flex justify-content-end">
    <button type="button" data-bs-toggle="modal" data-bs-target="#send_email" class="btn btn-outline-secondary">이메일
      전송</button>
  </div>

  <!-- //Pagination -->
  <div class="list_pagination" aria-label="Page navigation example">
    <ul class="pagination d-flex justify-content-center">
      <?php
      $previous = $block_start - $block_ct;
      if ($previous < 1)
        $previous = 1;
      if ($block_num > 1) {
        ?>
        <li class="page-item">
          <a class="page-link" href="class_data.php?page=<?= $previous; ?>" aria-label="Previous">
            <i class="bi bi-chevron-left"></i>
          </a>
        </li>
        <?php
      }
      ?>
      <?php
      for ($i = $block_start; $i <= $block_end; $i++) {
        $active = ($page == $i) ? 'active' : '';
        ?>
        <li class="page-item <?= $active; ?>"><a class="page-link" href="class_data.php?page=<?= $i; ?>"><?= $i; ?></a>
        </li>
        <?php
      }
      $next = $block_end + 1;
      if ($total_block > $block_num) {
        ?>
        <li class="page-item">
          <a class="page-link" href="class_data.php?page=<?= $next; ?>" aria-label="Next">
            <i class="bi bi-chevron-right"></i>
          </a>
        </li>
        <?php
      }
      ?>
    </ul>
  </div>

  <!-- //email 모달창 -->
  <div class="modal modal-lg" id="send_email" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">이메일 전송</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="">
            <table class="table">
              <colgroup>
                <col style="width:110px">
                <col style="width:250px">
              </colgroup>
              <thead class="thead-hidden">
                <tr>
                  <th scope="col">구분</th>
                  <th scope="col">내용</th>
                </tr>
              </thead>
              <tbody>
                <tr class="none">
                  <th scope="row">이름(아이디)</th>
                  <td>홍길동</td>
                  <th scope="row">이메일</th>
                  <td>hong1234@hong.com</td>
                </tr>
                <tr class="none">
                  <th scope="row">제목 <b>*</b></th>
                  <td colspan="3"><input type="text" class="form-control"></td>
                </tr>
                <tr class="none">
                  <th scope="row">내용 <b>*</b></th>
                  <td colspan="3"><textarea name="" id="" class="form-control"></textarea></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">취소</button>
          <button type="button" class="btn btn-secondary">등록</button>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  // 인쇄 버튼
  function printPage() {
    const fileUrl = "../../images/certificate of completion.pdf";

    // PDF를 iframe으로 페이지에 삽입
    const iframe = document.createElement("iframe");
    iframe.style.position = "absolute";
    iframe.style.width = "0px";
    iframe.style.height = "0px";
    iframe.style.border = "none";
    iframe.src = fileUrl;

    // iframe을 body에 추가
    document.body.appendChild(iframe);

    // PDF 파일이 로드된 후 인쇄
    iframe.onload = function () {
      iframe.contentWindow.print();  // iframe 내에서 print() 호출
    };
  }

  document.getElementById("printButton").addEventListener("click", printPage);

  // "전체 선택" 체크박스를 가져옴
  const checkAll = document.getElementById('allCheck');
  // 각 항목 체크박스를 모두 가져옴
  const itemCheckboxes = document.querySelectorAll('.itemCheckbox');

  // "전체 선택" 체크박스 클릭 이벤트 리스너 추가
  checkAll.addEventListener('change', function () {
    itemCheckboxes.forEach((checkbox) => {
      checkbox.checked = checkAll.checked;
    });
  });
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/code_even/admin/inc/footer.php');
?>