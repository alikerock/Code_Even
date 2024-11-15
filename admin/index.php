<?php
$chart_js = "<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";
$main_js = "";
include_once($_SERVER['DOCUMENT_ROOT'] . '/CODE_EVEN/admin/inc/header.php');

if (!isset($_SESSION['AUID'])) {
  echo "<script>
  alert('로그인을 해주세요');
  location.href='admin/login/login.php';
  </script>";
}
?>

<div class="container">
  <div class="top_wrapper d-flex justify-content-between">
    <div>
      <h3>10월 수익</h3>
      <p>7,123,000<span class="top_text"> 원</span></p>
    </div>
    <div>
      <h3>직전달 대비 수익률</h3>
      <p>3%</p>
    </div>
    <div>
      <h3>과정 개설 현황</h3>
      <p><span class="top_text">대기 </span>12 <span class="top_text">/ 개설 </span>1,234</p>
    </div>
    <div>
      <h3>판매 강좌수</h3>
      <p>1,234<span class="top_text"> 개</span></p>
    </div>
    <div>
      <h3>오늘 접속자 수</h3>
      <p>53<span class="top_text"> 명</span></p>
    </div>
  </div>
  <div class="bottom_wrapper d-flex justify-content-between">
    <div class="bott_left d-flex flex-column justify-content-between ">
      <h3>최근 6개월 수익률</h3>
      <canvas id="current_six_returns" width="500" height="500"></canvas>
    </div>
    <div class="bott_right d-flex flex-column justify-content-between">
      <div class="sellcost_best_table">
        <h3>판매 금액 BEST 강좌</h3>
      </div>
      <div class="d-flex">
        <div>
          <div class="d-flex justify-content-between">
            <h3>신규 가입자 현황</h3>
            <p class="">11월 현황</p>
          </div>
          <canvas id="current_six_news" width="340" height="250"></canvas>
        </div>
        <div>
          <h3>카테고리별 매출 금액</h3>
          <p>5,412<span class="top_text"> 명</span></p><canvas id="cate_one_return" width="200" height="250"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  const barchart = document.getElementById('current_six_returns');
  const cateDatas = [250, 390, 580, 610, 910, 783]

  new Chart(barchart, {
    type: 'bar',
    data: {
      labels: ['6월', '7월', '8월', '9월', '10월', '11월'],
      datasets: [{
        label: '(만원)',
        data: cateDatas,
        borderColor: '#D25353',
        backgroundColor: '#c93333',
        borderWidth: 1,
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<script>
  const lineCtx = document.getElementById('current_six_news').getContext('2d');
  const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: ['6월', '7월', '8월', '9월', '10월', '11월'],
      datasets: [{
        label: '신규 가입자 수',
        data: [2000, 2800, 3200, 3800, 4000, 3765],
        borderColor: '#D25353',
        backgroundColor: '#c93333',
        borderWidth: 1,
        fill: false,
        pointRadius: 3
      }, {
        label: '방문자',
        data: [8000, 6700, 6900, 8500, 9215, 7265],
        borderColor: '#7987FF',
        backgroundColor: '#5e62f1',
        borderWidth: 1,
        fill: false,
        pointRadius: 3
      }],
      options: {
        scales: {
          x: {
            beginAtZero: true
          },
          y: {
            beginAtZero: true
          }
        }
      }
    }
  });
</script>
<script>
  const doughnutCtx = document.getElementById('cate_one_return');
  const doughnutChart = new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
      labels: ['웹 개발', '클라우드DB', '보안 네트워크'],
      datasets: [{
        label: '카테고리',
        data: [3561500, 2493050, 1068450],
        borderColor: ['#D25353'],
        backgroundColor: ['#c93333', '#E76969', '#E8A9A9'],
        borderWidth: 1,
        fill: false,
        pointRadius: 3
      }],
      options: {
        scales: {
          x: {
            beginAtZero: true
          },
          y: {
            beginAtZero: true
          }
        }
      }
    }
  });
</script>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/CODE_EVEN/admin/inc/footer.php');
?>