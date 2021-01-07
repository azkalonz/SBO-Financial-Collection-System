<?php require '../config/config.php' ?>
<style media="screen">
  .card {
    width: 270px;
    height: 250px;
    background:#46adfa;
    cursor: pointer;
    margin: 12px;
    font-size: 2em;
    color: #fff;
    position: relative;
  }
  .card::after {
    content: '';
    position: absolute;
    left: 0;right:0;bottom:0;top:0;
    width:100%;height:100%;
    background: <?php echo $colors['nav-bar'] ?>;
    opacity: 0;
    transition: all 0.3s ease-out;
  }
  .card:hover::after {
    opacity: 0.6;
  }
  .card i {
    font-size: 4em;
  }
</style>
<hr>
<h3>STUDENT ORGANIZATION FINANCIAL COLLECTION SYSTEM</h3>
<div class="flex justify-center flex-wrap">
  <a href="javascript:$(`li[link='events.php']`).click()">
    <div class="card flex align-items-center align-center justify-center flex-column">
      <i class="fal fa-calendar-alt"></i>
      Registration
    </div>
  </a>
  <a href="javascript:$(`li[link='collections.php']`).click()">
    <div class="card flex align-items-center align-center justify-center flex-column">
      <i class="fal fa-usd-circle"></i>
      Fees
    </div>
  </a>
  <a href="javascript:$(`li[link='expenses/index.php']`).click()">
    <div class="card flex align-items-center align-center justify-center flex-column">
      <i class="fal fa-chart-bar"></i>
      Expenses
    </div>
  </a>
  <a href="javascript:$(`li[link='reports/index.php']`).click()">
    <div class="card flex align-items-center align-center justify-center flex-column">
      <i class="fal fa-file"></i>
      Reports
    </div>
  </a>
  <a href="javascript:$(`li[link='users.php']`).click()">
    <div class="card flex align-items-center align-center justify-center flex-column">
      <i class="fal fa-users"></i>
      Students
    </div>
  </a>
  <a href="javascript:$(`li[link='./logger.php']`).click()">
    <div class="card flex align-items-center align-center justify-center flex-column">
      <i class="fal fa-file-alt"></i>
      Logs
    </div>
  </a>
</div>
