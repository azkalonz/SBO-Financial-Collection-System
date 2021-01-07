<?php
  require('../../config/config.php');
  require('../../api/func.php');
  secure(['admin-only'=>true]);
  $liquidated_expenses = $con->prepare("SELECT id FROM notifications WHERE exp_id IN(SELECT id FROM psits_expenses WHERE status = 0) AND receiver_id = {$_SESSION['account']['user-id']} GROUP BY exp_id");
  $liquidated_expenses->execute();
  $liquidated_expenses = $liquidated_expenses->fetchAll(PDO::FETCH_ASSOC);
  $liquidated_expenses = sizeof($liquidated_expenses);
 ?>
<style media="screen">
  ul.l-nav {
    list-style: none;
    margin: 0;
    min-height: 100vh;
  }
  ul.l-nav li {
    position: relative;
    display: none;
    z-index: 1;
    padding: 0;
    margin: 0;
    background: rgba(255,255,255,0.8);
    padding: 14px 8px;
    margin-left: -40px;
  }
  ul.l-nav li a {
    display: block;
    padding: 14px 8px;
    color: #000!important;
    white-space: nowrap;
    text-transform: uppercase;
    width: 100%;
    transition: all 0.2s ease-out;
  }
  ul.l-nav li a:hover {
    transform: translateX(4px);
  }
  @media all and (max-width: 1000px){
    ul.l-nav {
      position: relative;
    }
  }
</style>
<div class="flex col-100" style="background-color: #fff;">
  <div style="width: 300px;">
    <ul class="l-nav">
      <li>
        <a href="#" data-ajax="myaccount/settings.php">Account settings</a>
      </li>
      <li>
        <a href="#" data-ajax="myaccount/liquidated.php">Liquidated expenses</a>
      </li>
      <li>
        <a href="#" data-ajax="myaccount/unliquidated.php">Unliquidated expenses</a>
      </li>
      <li>
        <a href="#" data-ajax="myaccount/pending.php">Pending withdrawals</a>
      </li>
      <li>
        <a href="#" data-ajax="myaccount/notification.php">Notifications [<?php echo $liquidated_expenses ?>]</a>
      </li>
    </ul>
  </div>
  <div id="account-view" class="flex col-100" style="position: relative;z-index: 999;">
  </div>
</div>

<script type="text/javascript">
  setTimeout(function(){
    $(".l-nav li").animate({width:'toggle'},200);
  },200);
  $('#account-view').load('myaccount/settings.php');
  $('a[data-ajax]').each(function(){
    $(this).on('click',function(){
      let link = $(this).attr('data-ajax');
      $.ajax({
        method: 'GET',
        async: true,
        beforeSend: function(){
          let loading = `<div class="col-100 flex align-center justify-center" style="background: #fff;">            <img src="img/spinner.svg" width="50"></div>`
          $('#account-view').html(loading);
        },
        url: link,
        success: function(data) {
          $('#account-view').html(data);
        }
      })
    })
  })
</script>
