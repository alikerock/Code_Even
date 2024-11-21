<?php
$title = "강좌매출통계";
include_once($_SERVER['DOCUMENT_ROOT'] . '/CODE_EVEN/admin/inc/header.php');

 // 'code'가 'A'로 시작하는 category 데이터를 가져오기
 $category_sql = "SELECT * FROM category WHERE code LIKE 'A%' ORDER BY cgid ASC";
 $category_result = $mysqli->query($category_sql);

 while($cate_data = $category_result->fetch_object()){
     $categories[] = $cate_data;
 }

 //분야 카테고리랑 매칭하기
 $category_map = [];
 foreach ($categories as $category) {
     $category_map[$category->cgid] = $category->name;
 }


  //게시글 분류 검색 추가
  $category_filter = isset($_GET['category']) ? $mysqli->real_escape_string($_GET['category']) : '';
  $where_clause = '';
  // 게시글 키워드 검색
  $keywords = isset($_GET['keywords']) ? $mysqli->real_escape_string($_GET['keywords']) : '';
  //강좌유형검색
  $lec_type_filter = isset($_GET['search_lectype']) ? $mysqli->real_escape_string($_GET['search_lectype']) : '';

  // 오름차순 내림차순 추가
  // 정렬 관련 파라미터 처리
  $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'leid'; // 기본 정렬: 강좌번호
  $order = isset($_GET['order']) ? $_GET['order'] : 'asc'; // 기본 오름차순
  $order_next = $order === 'asc' ? 'desc' : 'asc'; // 다음 정렬 상태

  // 현재 GET 파라미터에서 불필요한 파라미터 제거
  $query_params = $_GET;
  unset($query_params['order_by'], $query_params['order']); // 정렬 관련 파라미터 제거




 if ($keywords) {
   $where_clause = "WHERE lecture_sales.lec_title LIKE '%$keywords%' OR lecture_sales.th_name LIKE '%$keywords%'";
 }

 if ($category_filter) {
   $where_clause .= ($where_clause ? ' AND ' : 'WHERE ') . "lecture_sales.lec_cate = '$category_filter'";
 }

 if ($lec_type_filter !== '') {
  $where_clause .= ($where_clause ? ' AND ' : 'WHERE ') . "lecture_sales.lec_type = '$lec_type_filter'";
}

 $page_sql = "SELECT COUNT(*) AS cnt FROM lecture_sales $where_clause";
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


  // SQL 쿼리
  $sql = "SELECT * FROM lecture_sales $where_clause 
  ORDER BY $order_by $order 
  LIMIT $start_num, $list";

  $result = $mysqli->query($sql);
  $dataArr = [];
  if ($result) {
  while ($data = $result->fetch_object()) {
    $dataArr[] = $data;
  }
  }
?>

<div class="container"> 
  <h2 class="page_title">강좌매출통계</h2>

  <form action="#" id="search_form" class="row justify-content-end align-items-center" method="GET">
  <div class="col-lg-3">
    <span>총 강좌수 :  <?= $row_num; ?></span>
    </div>

    <div class="col-lg-3 pt_04">
    <span class="status_tt me-4">강좌유형</span>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="search_lectype" id="inlineRadio3" value="" checked>
        <label class="form-check-label" for="inlineRadio3">전체</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="search_lectype" id="inlineRadio1" value="1" <?= $lec_type_filter === '1' ? 'checked' : ''; ?>>
        <label class="form-check-label" for="inlineRadio1">일반</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="search_lectype" id="inlineRadio2" value="2" <?= $lec_type_filter === '2' ? 'checked' : ''; ?>>
        <label class="form-check-label" for="inlineRadio2">레시피</label>
    </div>
</div>
    <div class="col-lg-2">
      <select class="form-select" name="category" aria-label="대표분류">
        <option value="">-전체분류선택-</option>
        <?php foreach($categories as $category): ?>
          <option value="<?= $category->cgid; ?>">
            <?= $category->name;?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-lg-4">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="유형이나 분류 선택 또는 검색어를 입력하세요." name="keywords" value="<?= htmlspecialchars($keywords); ?>">
        <button type="submit" class="btn btn-secondary">
          <i class="bi bi-search"></i>
        </button>
      </div>
    </div>
    
  </form>
  <!-- //Search-form -->

  <table class="table list_table">
    <thead>
      <tr>
        <th scope="col">번호</th>
        <th scope="col">분류</th>
        <th scope="col">강사명</th>
        <th scope="col">강좌명</th>
        <th scope="col">강좌유형</th>
        <th scope="col">가격</th>
        <th scope="col">
          주문금액
          <a href="?<?= http_build_query(array_merge($query_params, ['order_by' => 'total_order_amount', 'order' => $order_next])); ?>" class="sort-icon">
              <i class="bi bi-arrow-<?= $order_by === 'total_order_amount' && $order === 'asc' ? 'up' : 'down'; ?>-short"></i>
          </a>
        </th>
        <th scope="col">
            건수
            <a href="?<?= http_build_query(array_merge($query_params, ['order_by' => 'order_count', 'order' => $order_next])); ?>" class="sort-icon">
                <i class="bi bi-arrow-<?= $order_by === 'order_count' && $order === 'asc' ? 'up' : 'down'; ?>-short"></i>
            </a>
        </th>
        <th scope="col">
            환불금액
            <a href="?<?= http_build_query(array_merge($query_params, ['order_by' => 'total_refund_amount', 'order' => $order_next])); ?>" class="sort-icon">
                <i class="bi bi-arrow-<?= $order_by === 'total_refund_amount' && $order === 'asc' ? 'up' : 'down'; ?>-short"></i>
            </a>
        </th>
        <th scope="col">
            건수
            <a href="?<?= http_build_query(array_merge($query_params, ['order_by' => 'refund_count', 'order' => $order_next])); ?>" class="sort-icon">
                <i class="bi bi-arrow-<?= $order_by === 'refund_count' && $order === 'asc' ? 'up' : 'down'; ?>-short"></i>
            </a>
        </th>
        <th scope="col">
            총매출금액
            <a href="?<?= http_build_query(array_merge($query_params, ['order_by' => 'final_sales_amount', 'order' => $order_next])); ?>" class="sort-icon">
                <i class="bi bi-arrow-<?= $order_by === 'final_sales_amount' && $order === 'asc' ? 'up' : 'down'; ?>-short"></i>
            </a>
        </th>
       
      </tr>
    </thead>
    <tbody>
    <!-- 
      <tr>
        <th scope="row">1</th>
        <td>웹개발</td>
        <td>김동주</td>
        <td>html, css 쉽게배우자</td>
        <td><span class="badge text-bg-secondary">일반</span></td>
        <td>129,000원</td>
        <td>9,999원 / 10건</td>
        <td>9,999원 / 10건</td>
        <td>119,999원</td>
      </tr>  
      <tr>
        <th scope="row">1</th>
        <td>웹개발</td>
        <td>김동주</td>
        <td>Flex box 파헤치기!</td>
        <td><span class="badge text-bg-red">레시피</span></td>
        <td>129,000원</td>
        <td>9,999원 / 10건</td>
        <td>9,999원 / 10건</td>
        <td>119,999원</td>
      </tr>  
    -->

    <?php
  if (isset($dataArr) && count($dataArr) > 0) {
      foreach ($dataArr as $index => $data) {

  ?>
      <tr>
          <th scope="row"><?= $start_num + $index + 1; ?></th>
          <td>
    <?= $data->lec_cate == 1 ? '웹개발' : ($data->lec_cate == 2 ? '클라우드·DB' : ($data->lec_cate == 3 ? '보안·네트워크' : '기타')); ?>
</td>

          <td><?= htmlspecialchars($data->th_name); ?></td>
          <td>
    <?= htmlspecialchars(mb_strlen($data->lec_title) > 32 ? mb_substr($data->lec_title, 0, 32) . '...' : $data->lec_title); ?>
</td>

          <td>
              <span class="badge <?= $data->lec_type == 1 ? 'text-bg-secondary' : 'text-bg-danger'; ?>">
                  <?= $data->lec_type == 1 ? '일반' : '레시피'; ?>
              </span>
          </td>
          <td>강좌가격</td>
          <td class="group_lefttline"><?= number_format($data->total_order_amount); ?>원 </td>
          <td class="group_rightline"><?= $data->order_count; ?>건</td>
          <td><?= number_format($data->total_refund_amount); ?>원</td>
          <td class="group_rightline"> 0건</td>
          <td><?= number_format($data->final_sales_amount); ?>원</td>
      </tr>
      <?php
      }
  } 
  ?>
    </tbody>
  </table>
  <!-- //table -->

  <div class="list_pagination">
    <ul class="pagination d-flex justify-content-center">
      <?php
        $previous = $block_start - $block_ct;
        if ($previous < 1) $previous = 1;
        if ($block_num > 1) { 
      ?>
      <li class="page-item">
        <a class="page-link" href="user_list.php?page=<?= $previous; ?>" aria-label="Previous">
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
      <li class="page-item <?= $active; ?>"><a class="page-link" href="user_list.php?page=<?= $i; ?>"><?= $i; ?></a></li>
      <?php
        }
        $next = $block_end + 1;
        if($total_block > $block_num){
      ?>
      <li class="page-item">
        <a class="page-link" href="user_list.php?page=<?= $next; ?>" aria-label="Next">
          <i class="bi bi-chevron-right"></i>
        </a>
      </li>
      <?php
        }
      ?>
    </ul>
  </div>
  <!-- //Pagination -->

</div>

<script>
    // 새로고침 시 URL에서 GET 파라미터 제거
    if (window.location.search) {
        const url = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, url);
    }
</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/CODE_EVEN/admin/inc/footer.php');
?>