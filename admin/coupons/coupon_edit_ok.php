<?php
include_once($_SERVER['DOCUMENT_ROOT']. '/code_even/admin/inc/header.php');
// include_once($_SERVER['DOCUMENT_ROOT']. '/code_even/admin/inc/img_upload_func.php');

if (!isset($_SESSION['AUID'])) {
  echo "<script>
  alert('로그인을 해주세요');
  location.href='../login/login.php';
  </script>";
}

$cpid = $_POST['cpid'];
if (!isset($cpid)) {
  echo "<script>alert('쿠폰정보가 없습니다.'); 
  location.href = 'coupons.php';</script>";
}


  $coupon_name = $_POST['coupon_name'] ?? '';
  $coupon_image = $_FILES['coupon_image'] ??'';
  $coupon_type = $_POST['coupon_type'] ?? '';
  $coupon_price = $_POST['coupon_price'] ?? '0';
  $coupon_ratio = $_POST['coupon_ratio'] ?? '0';
  $status = $_POST['status'] ?? '';
  $max_value = $_POST['max_value'] ?? '0';
  $use_min_price = $_POST['use_min_price'] ?? '0';
  $use_max_date = $_POST['use_max_date'] ?? 'NULL';
  $cp_desc = $_POST['cp_desc'] ?? '';

  $save_dir = $_SERVER['DOCUMENT_ROOT'].'/code_even/admin/upload/coupons/';
  $filename = $coupon_image['name']; //insta.jpg
  $ext = pathinfo($filename,PATHINFO_EXTENSION); //파일명의 확장자를 추출, jpg
  $newFileName = date('YmdHis').substr(rand(), 0, 6);//202410091717123456
  $savefile = $newFileName.'.'.$ext;

  if (isset($_FILES['coupon_image']) && $_FILES['coupon_image']['error'] == UPLOAD_ERR_OK)  {

    if(move_uploaded_file($coupon_image['tmp_name'], $save_dir.$savefile)){
      $coupon_image = '/code_even/admin/upload/coupons/'.$savefile;  
    } else{
      echo "<script>
        alert('이미지를 첨부할 수 없습니다.');
      </script>";
    }
  }

//   $sql = "INSERT INTO coupons 
//   (coupon_name, coupon_image, coupon_type, coupon_price, coupon_ratio, status, userid, max_value, use_min_price) 
//   VALUES
//   ('$coupon_name', '$coupon_image', '$coupon_type', $coupon_price, $coupon_ratio, $status, '{$_SESSION['AUID']}', $max_value, $use_min_price)";
 

//  $result = $mysqli->query($sql); 

$sql = "UPDATE coupons SET 
  coupon_name = '$coupon_name',
  coupon_image = '$coupon_image',
  coupon_type = '$coupon_type',
  coupon_price = $coupon_price,
  coupon_ratio = $coupon_ratio,
  status = $status,
  max_value = $max_value,
  use_min_price = $use_min_price,
  use_max_date = '$use_max_date',
  cp_desc = '$cp_desc'
  ";

// thumbnail 값이 존재할 때만 thumbnail 컬럼을 업데이트

if (isset($_FILES['coupon_image']) && $_FILES['coupon_image']['error'] == UPLOAD_ERR_OK)  {
  $sql .= ", coupon_image = '$coupon_image'";
}

$sql .= " WHERE cpid = $cpid";

$result = $mysqli->query($sql); 


 //입력성공하면 쿠폰등록 완료 경고창 띄우고 쿠폰목록 페이지로 이동
 if($result){
   echo "
     <script>
       alert('쿠폰수정 완료');
       location.href = 'coupons.php';
     </script>
   ";
   $mysqli->commit();//디비에 커밋한다.
  }
 



if(isset($_FILES['coupon_image'])){
  if($coupon_image['size'] > 10240000 ){
    echo "
     <script>
       alert('10MB이하만 첨부할 수 있습니다.');
       history.back();
     </script>
    ";
   }
   
   //파일 포멧 검사
   if(strpos($coupon_image['type'], 'image') === false){
     echo "
     <script>
       alert('이미지만 첨부할 수 있습니다.');
       history.back();
     </script>
    ";
   }
  }


$mysqli->close();

?>