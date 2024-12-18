<?php
include_once($_SERVER['DOCUMENT_ROOT']. '/CODE_EVEN/admin/inc/header.php');

// session_start();

$sql = "SELECT * FROM category WHERE step=1";
$result = $mysqli->query($sql);
while($data = $result->fetch_object()){
  $cate1[] = $data;
}
// print_r($cate1);

if (!isset($_SESSION['AUID'])) {
  echo "<script>
  alert('로그인을 해주세요');
  location.href='../login/login.php';
  </script>";
}

// if($_SESSION['AULEVEL'] !== 100){
//   echo "
//     <script>
//       alert('권한이 없습니다.');
//       location.href = '../index.php';
//     </script>
//   ";
// }

$mysqli->close();
?>



<div class="container ">
  <h2>카테고리 관리</h2>
  
  <div class="row justify-content-between mt-5">
    <div class="col-md-4">
      <div class="bd d-flex justify-content-center"> 대분류</div>
      <select class="form-select mt-4" id="cate1" aria-label="대분류 선택">
        <option selected>대분류를 선택하세요</option>
        <?php
          foreach($cate1 as $c1){
        ?>
        <option value="<?= $c1-> code; ?>"><?= $c1->name; ?>
        <?php
          }
        ?>
      </select>
      <!-- Button trigger modal -->
      <div class="btns d-flex justify-content-center mt-4">
        <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cate1_modal">
          대분류 등록
        </button>
      </div>
      

    </div>
    
    
    <div class="col-md-4">
    <div class="bd d-flex justify-content-center"> 중분류</div>
      <select class="form-select mt-4" id="cate2" aria-label="Default select example">
        <option selected>대분류를 먼저 선택하세요</option>
      </select>
            <!-- Button trigger modal -->
      <div class="btns d-flex justify-content-center mt-4 ">
        <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cate2_modal">
          중분류 등록
        </button>
      </div>
    </div>

    <div class="col-md-4">
    <div class="bd d-flex justify-content-center"> 소분류</div>
      <select class="form-select mt-4" id="cate3" aria-label="Default select example">
        <option selected>중분류를 먼저 선택하세요</option>
      </select>
            <!-- Button trigger modal -->
      <div class="btns d-flex justify-content-center mt-4">
        <button type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cate3_modal">
          소분류 등록
        </button>
      </div>
    </div>
  </div>
</div>

<!-- 모달 창 -->
<!-- Modal 1-->
<div class="modal fade" id="cate1_modal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="#" class="modal-content"  method="post" data-step="1">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel1">대분류 등록</h1>
        <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body row">
        <div class="col-md-6">
          <input class="form-control" type="text"  id="code1" name="code1"  placeholder="분류코드명을 입력하세요" aria-label="default input example" pattern="A\d{4}" placeholder="코드명을 입력하세요" pattern="A\d{4}" title="A로 시작하고 뒤에 네 자리 숫자가 와야 합니다. 예: A0001, A1234"  required>
        </div>
        <div class="col-md-6">
          <input class="form-control" type="text"  id="name1" name="name1"  placeholder="카테고리명을 입력하세요" aria-label="default input example" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">취소</button>
        <button type="submit" class="btn btn-secondary">등록</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal 2-->
<div class="modal fade" id="cate2_modal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <form action="#" class="modal-content"  method="post" data-step="2">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel2">중분류 등록</h1>
        <button type="submit" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <select class="form-select mb-3" name="pcode2" id="pcode2" aria-label="대분류 선택">
          <option selected>대분류를 선택하세요</option>
          <?php
            foreach($cate1 as $c1){
          ?>
          <option value="<?= $c1-> code; ?>"><?= $c1->name; ?>
          <!-- <h6><span class="badge text-bg-secondary">수정</span></h6></option> -->
          <?php
            }
          ?>
        </select>
          <div class="row">
            <div class="col-md-6">
              <input class="form-control" type="text"  id="code2" name="code2"  placeholder="코드명을 입력하세요" pattern="B\d{4}" title="B로 시작하고 뒤에 네 자리 숫자가 와야 합니다. 예: B0001, B1234" aria-label="default input example" pattern="B\d{4}" required>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text"  id="name2" name="name2"  placeholder="카테고리명을 입력하세요" aria-label="default input example"  required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">취소</button>
          <button type="submit" class="btn btn-secondary">등록</button>
        </div>
      </form>
    </div>
  </div>
<!-- Modal 3-->
<div class="modal fade" id="cate3_modal" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
  <div class="modal-dialog">
    <form action="#" class="modal-content"  method="post" data-step="3">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel3">소분류 등록</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <select class="form-select mb-3" name="pcode3" id="pcode3" aria-label="대분류 선택">
              <option selected>대분류를 선택하세요</option>
              <?php
                foreach($cate1 as $c1){
              ?>
              <option value="<?= $c1-> code; ?>"><?= $c1->name; ?>
              <!-- <h6><span class="badge text-bg-secondary">수정</span></h6></option> -->
              <?php
                }
              ?>
            </select>
          </div>
          <div class="col-md-6">
            <select class="form-select" name="pcode4" id="pcode4"  aria-label="Default select example">
              <option selected>대분류를 먼저 선택하세요</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <input class="form-control" type="text"  id="code3" name="code3"  placeholder="코드명을 입력하세요" pattern="C\d{4}" title="C로 시작하고 뒤에 네 자리 숫자가 와야 합니다. 예: C0001, C1234" aria-label="default input example" pattern="C\d{4}" required>
          </div>
          <div class="col-md-6">
            <input class="form-control" type="text"  id="name3" name="name3"  placeholder="카테고리명을 입력하세요" aria-label="default input example" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">취소</button>
        <button type="submit" class="btn btn-secondary">등록</button>
      </div>
    </form>
  </div>
</div>


<script>
  // 대->중->소 출력
$('#cate1').change(function(){
  makeOption($(this), 2, '중분류', $('#cate2'));
})
$('#cate2').change(function(){
  makeOption($(this), 3, '소분류', $('#cate3'));
})
$('#pcode3').change(function(){
  makeOption($(this), 2, '중분류', $('#pcode4'));
})

  function makeOption(e, step, category, target){
    let cate = e.val();
    // console.log(cate, step, category, target);
    let data = {
      cate:cate,
      step:step,
      category:category
    }
    console.log(data);
    
    $.ajax({
      async:false,
      data:data,
      dataType:'html',
      type:'post',
      url: "printOption.php", 

      success: function(result){
        console.log(result);
      target.html(result);
      },
      error: function(error){
        console.log(error);
        }
    });
  }
  
    $('.modal-content').submit(function(e){
      e.preventDefault();
    let step = Number($(this).attr('data-step'));
    let pcode = $(`#pcode${step}`).val();
    let pcode1 = $(`#pcode${step+1}`).val();
    let code = $(`#code${step}`).val();
    let name = $(`#name${step}`).val();
    category_save(step, pcode, code, name);

    if(step > 1 && !pcode){
      alert('대분류를 선택하세요');
      return;
    }
    if(step > 2 && !pcode1){
      alert('중분류를 선택하세요');
      return;
    }
    if(pcode1){
      pcode = pcode1;
    }
  });

  function category_save(step, pcode, code, name){
    let data = {
      name:name,
      pcode:pcode || '',
      code:code,
      step:step
    }
    console.log(data);
     $.ajax({
      async:false,
      url:'save_category.php',
      data:data,
      type : 'post',
      dataType:'json',
      success:function(returned_data){
        console.log(returned_data);
        if(returned_data.result == 1){
          alert('등록을 완료하였습니다.');
          location.reload();
        }else if(returned_data.result == -1){
          alert('이미 존재하는 코드명 또는 분류명입니다.');
          location.reload();
        }else{
          alert('등록을 실패하였습니다.');
        }
      }
    });
  }



</script>

<?php
include_once($_SERVER['DOCUMENT_ROOT']. '/code_even/admin/inc/footer.php');
?>