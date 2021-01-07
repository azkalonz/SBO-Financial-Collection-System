<?php
  require('../../config/config.php');
  $expenses = $con->prepare("SELECT * FROM psits_expenses WHERE id NOT IN (SELECT exp_id FROM pending_expenses) AND user_id = {$_SESSION['account']['officer_id']} AND status = 0");
  $expenses->execute();
  $expenses = $expenses->fetchAll(PDO::FETCH_ASSOC);

  $liquidated_expenses = $con->prepare("SELECT * FROM psits_expenses WHERE user_id = {$_SESSION['account']['officer_id']} AND status = 1");
  $liquidated_expenses->execute();
  $liquidated_expenses = $liquidated_expenses->fetchAll(PDO::FETCH_ASSOC);
 ?>
<div class="panel col-100">
  <div class="panel-header">
    Unliquidated expenses
  </div>
  <div class="panel-body">
    <table>
      <thead>
        <tr>
          <th>Description</th>
          <th>Date of withdrawal</th>
          <th>Amount</th>
          <th>Due</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!sizeof($expenses)): ?>
          <td colspan="5">No records found. Please check your pending withdrawals.</td>
        <?php endif; ?>
        <?php foreach ($expenses as $key => $value): ?>
          <?php
            $date1 = date_create(date('Y-m-d',strtotime($value['_date'])));
            $date2 = date_create(date('Y-m-d'));
            $diff = date_diff($date1,$date2);
            $dl = $diff->format("%a");
           ?>
          <tr>
            <td><?php echo $value['title'] ?></td>
            <td><?php echo $value['_date'] ?></td>
            <td>
              <img src="img/peso.svg" alt="" width="10">
              <?php echo $value['received'] ?></td>
            <td><?php echo 7-$dl; ?> Days left</td>
            <td>
              <a href="#" onclick="openForm(this)" data-id="<?php echo $value['id'] ?>">
                Submit Liquidation
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<div id="liquidation-form" style="position:absolute;top:0;right:0;left:0; min-height: 100vh;display:none;">
  <div id="liquidation-table" style="width: 70%;transform:translateY(100vh);transition: all 0.5s ease-in-out;z-index: 23;position:relative;margin: 17px auto;overflow: hidden auto;height:540px;border: 13px solid #fff;">
    <form name="liquidation-form">
      <table>
          <tr>
            <td colspan="100%">
              <div class="flex justify-space-between align-items-center">
                <a onclick="openForm()" style="font-size: 2rem;cursor: pointer;">&times;</a>
              </div>
          </td>
          </tr>
        <tr>
          <td class="table-title" colspan="100%">Expense Period <b id="exp-id"></b></td>
        </tr>
        <tr>
          <td>From</td>
          <td colspan="100%" id="from">
            <img src="img/spinner.svg" width="40" alt="">
          </td>
        </tr>
        <tr>
          <td>To</td>
          <td colspan="100%" id="to">
            <img src="img/spinner.svg" width="40" alt="">
          </td>
        </tr>
        <tr>
          <td class="table-title" colspan="100%">Liable Person</td>
        </tr>
        <tr>
          <td>Name</td>
          <td colspan="100%" id="person">
            <img src="img/spinner.svg" width="40" alt="">
          </td>
        </tr>
        <tr>
          <td class="table-title" colspan="100%">Purpose of Expense</td>
        </tr>
        <tr>
          <td colspan="100%" id="purpose">
            <img src="img/spinner.svg" width="40" alt="">
          </td>
        </tr>
        <tr>
          <td class="table-title" colspan="100%">Cash Received</td>
        </tr>
        <tr>
          <td colspan="100%" id="received">
              <img src="img/spinner.svg" width="40" alt="">
          </td>
        </tr>
        <tr>
          <td class="table-title" colspan="100%">Itemized Expenses</td>
        </tr>
        <tr>
          <td>Particular/Details</td>
          <td>Official Receipt</td>
          <td>Amount</td>
        </tr>
        <tr id="template">
          <td><input type="text" class="detail" value=""></td>
          <td><input type="text" class="receipt" value="N/A"></td>
          <td><input class="amount" type="number" name="amount[]" value="0"></td>
          <input class="event_id" type="hidden">
        </tr>
        <tr id="insert">
          <td colspan="100%">
            <div class="flex justify-space-between">
              <a href="#" id="addmore">Add more row</a>
              <a href="#" onclick="removeRow()">Remove row</a>
            </div>
            </td>
        </tr>
        <tr>
          <td colspan="2">Total</td>
          <td id="total">0</td>
        </tr>
        <tr>
          <td colspan="100%" align="right">
              <button type="button" name="button" onclick="resetForm()">Reset</button>
              <button type="button" name="button" onclick="$('form[name=liquidation-form]').submit()">Submit</button>
          </td>
        </tr>
      </table>
    </form>
  </div>
  <div onclick="openForm()" style="width: 100vw;height:100%;background:rgba(0,0,0,0.4);position:fixed;top:0;left:0;right:0;bottom:0;"></div>
</div>
<script type="text/javascript">
  resetForm = function(){
    $('#liquidation-form input').each(function(){
      $(this).val('');
    });
    $('.amount').each(function(){
      $(this).val(0);
    });
    $('#total').text(0);
  }
  openForm = function(e=null){
    $('.amount').off();
    $('.amount').on('keyup',function(){
        updateTotal();
      })
    if(e!=null){
      $.ajax({
        method: 'GET',
        url: '/dashboard/api/expenses.php?getInfo&id='+e.getAttribute('data-id'),
        success: function(data){
          data = JSON.parse(data)[0];
          $('#from').text(data._date);
          $('#to').text('<?php echo date('F d, Y') ?>');
          $('#person').text(data.user);
          $('#purpose').text(data.title);
          $('#received').text(data.received);
          $('#exp-id').text(data.id);
          $('.event_id').val(data.event_id);
          console.log(data);
        }
      })
    }
    let height = $('#liquidation-table').height();
    if($('#liquidation-form').css('display')=='none'){
      $('#liquidation-form').show();
      $('#liquidation-table').css('transform','translateY(0px)');
    } else {
      $('#liquidation-table').css('transform','translateY(-'+height+'px)');
      $('#liquidation-table').on('transitionend',function(){
        $('#liquidation-form').hide();
        $('#liquidation-table').css('transform','translateY('+height+'px)');
        $('#liquidation-table').off();
      })
    }
  }
  updateTotal = function(){
      let total=0;
      $('.amount').each(function(){
        total+=eval($(this).val());
      });
      $('#total').text(total);
    }
  $('#addmore').click(()=>{
    let tr = document.createElement('tr');
    tr.innerHTML = $('#template').html();
    $(tr).insertBefore($('#insert'));
    $('#liquidation-table').scrollTop($('#liquidation-table').height());
    $('.amount').off();
    $('.amount').on('keyup',function(){
        updateTotal();
      })
  })
  removeRow = function(){
    let insert = document.querySelector('#insert');
    let previous = insert.previousElementSibling;
    if(previous.getAttribute('id')!='template')
    insert.parentNode.removeChild(previous);

    updateTotal();

  }
</script>
<script type="text/javascript">
  $('form[name=liquidation-form]').on('submit', function(event){
    event.preventDefault();
    let error = false;
    if($('#total').text()=="NaN" || $('#total').text()=="0"){return;}
    $('form[name=liquidation-form] input').each(function(){
      if($(this).val()==''){
        $(this).css('border-color','red');
        $(this).attr('placeholder','This field cannot be empty!');
        $(this).off();
        $(this).on('keydown',function(){
          $(this).css('border-color','grey');
        })
        error = true;
      }
    })
    if(error){return;}

    [data,data.details,data.receipts,data.amount] = [[],[],[],[]];
    data.event_id = $('.event_id').val();
    $('.detail').each(function(){
      data.details.push($(this).val());
    })
    $('.receipt').each(function(){
      data.receipts.push($(this).val());
    })
    $('.amount').each(function(){
      data.amount.push($(this).val());
    })
    $.ajax({
      method: 'POST',
      url: '/dashboard/api/expenses.php',
      data: {
        sendInfo: true,
        id: $('#exp-id').text(),
        details: data.details,
        receipts: data.receipts,
        amount: data.amount,
        date: $('#from').text(),
        event_id: data.event_id,
        total: $('#total').text()},
      success: function(error){
        console.log(error);
        error = JSON.parse(error);
        if(!!error){
          openForm();
          $('#liquidation-table').on('transitionend',function(){
            $("#dashboard-view").load("myaccount",function(){
              $("#loading-content").fadeOut();
              $("#dashboard-view").fadeIn();
            })
          });
        }
      }
    })
  })
</script>
