<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/code_even/admin/inc/dbcon.php');
session_start(); // 세션 시작

// 세션에서 사용자 정보 가져오기
$session_userid = $_SESSION['AUID'] ?? null; // 세션에서 userid (AUID)
if (!$session_userid) {
    echo "<script>alert('로그인 정보가 없습니다. 다시 로그인해 주세요.');</script>";
    echo "<script>location.href='/CODE_EVEN/admin/login.php';</script>";
    exit;
}

// 사용자 정보 가져오기
$sql_user = "SELECT uid, username FROM user WHERE userid = ?";
if ($stmt_user = $mysqli->prepare($sql_user)) {
    $stmt_user->bind_param("s", $session_userid);
    $stmt_user->execute();
    $stmt_user->bind_result($uid, $username);
    $stmt_user->fetch();
    $stmt_user->close();
} else {
    echo "<script>alert('사용자 정보를 가져오는 데 실패했습니다.');</script>";
    exit;
}

// 퀴즈와 시험 데이터 가져오기 (AJAX 요청 처리)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_quiz_test') {
    $cate1 = $_POST['cate1'];
    $cate2 = $_POST['cate2'];
    $cate3 = $_POST['cate3'];
    $title = $_POST['title'];

    // 데이터 검증
    if (empty($cate1) || empty($cate2) || empty($cate3) || empty($title)) {
        echo json_encode(['error' => '카테고리와 강좌명을 모두 입력해주세요.']);
        exit;
    }

    // quiz 데이터 가져오기
    $sql_quiz = "SELECT exid, tt FROM quiz WHERE cate1 = ? AND cate2 = ? AND cate3 = ? AND title = ?";
    $stmt_quiz = $mysqli->prepare($sql_quiz);
    $stmt_quiz->bind_param("ssss", $cate1, $cate2, $cate3, $title);
    $stmt_quiz->execute();
    $result_quiz = $stmt_quiz->get_result();
    $quiz_data = [];
    while ($row = $result_quiz->fetch_object()) {
        $quiz_data[] = $row;
    }

    // test 데이터 가져오기
    $sql_test = "SELECT exid, tt FROM test WHERE cate1 = ? AND cate2 = ? AND cate3 = ? AND title = ?";
    $stmt_test = $mysqli->prepare($sql_test);
    $stmt_test->bind_param("ssss", $cate1, $cate2, $cate3, $title);
    $stmt_test->execute();
    $result_test = $stmt_test->get_result();
    $test_data = [];
    while ($row = $result_test->fetch_object()) {
        $test_data[] = $row;
    }

    // JSON으로 반환
    echo json_encode(['quiz' => $quiz_data, 'test' => $test_data]);
    exit;
}

// 버튼 동작 구분 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null; // 버튼의 action 값 확인

    // 공통 데이터 수집
    $cate1 = $_POST['cate1'] ?? null;
    $cate2 = $_POST['cate2'] ?? null;
    $cate3 = $_POST['cate3'] ?? null;
    $title = $_POST['title'] ?? null;
    $price = $_POST['price'] ?? 0;
    $period = $_POST['period'] ?? 30;
    $isrecipe = $_POST['isrecipe'] ?? 0;
    $isgeneral = $_POST['isgeneral'] ?? 1;
    $imagePath = '/uploads/images/default.png';

    // 이미지 업로드 처리
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadedFile = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
            $imagePath = '/uploads/images/' . basename($_FILES['image']['name']);
        }
    }

    // 데이터 유효성 검사
    if (empty($cate1) || empty($cate2) || empty($cate3) || empty($title)) {
        echo "<script>alert('필수 항목을 모두 입력해주세요.');</script>";
        exit;
    }

    // 임시 저장 로직
    if ($action === 'draft_save') {
        $state = 0; // 임시 저장 상태
        $message = '강좌가 임시 저장되었습니다.';
    }
    // 최종 등록 로직
    elseif ($action === 'final_save') {
        $state = 1; // 최종 등록 상태
        $message = '강좌가 최종 저장되었습니다.';
    } else {
        echo "<script>alert('잘못된 요청입니다.');</script>";
        exit;
    }

    // 강좌 데이터 저장 쿼리
    $sql = "INSERT INTO lecture 
            (lecid, cate1, cate2, cate3, title, name, price, period, isrecipe, isgeneral, image, date, state) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param(
        "issssssiiisi",
        $uid, $cate1, $cate2, $cate3, $title, $username, $price, $period, $isrecipe, $isgeneral, $imagePath, $state
    );

    if ($stmt->execute()) {
        echo "<script>alert('$message');</script>";
        echo "<script>location.href='lecture_list.php';</script>";
    } else {
        echo "<script>alert('저장 실패: " . $stmt->error . "');</script>";
    }
}
?>
