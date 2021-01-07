<?php include("../../config/config.php");?>
<?php include("../../api/func.php");?>
<?php $events  = $con->prepare("SELECT * FROM psits_events");
  $events->execute();
  $events = $events->fetchAll(PDO::FETCH_ASSOC);?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <?php include('../../js/jquery.php') ?>
  </head>
  <body>

    <table id="add-form">
      <tr>
        <td colspan="100%" class="table-title">CASH OUT FORM</td>
      </tr>
      <tr>
        <td colspan="2">
          <div id="res">
          </div>
          <div style="float:right;font-weight:bold;font-size: 40px;">
              Php<a id="f" style="color: green;"></a>
          </div>
        </td>
      </tr>
      <tr>
        <td>Title</td>
        <td>
          <input type="text" name="title" value="">
        </td>
      </tr>
      <tr>
        <td>Amount</td>
        <td>
          <input type="number" name="amount" value="" id="amount_cash" onkeyup="checkFund()">
        </td>
      </tr>
      <tr>
        <td>Fund</td>
        <td>
          <select id="fund" name="event">
            <?php foreach ($events as $key => $value): ?>
              <?php $total_amount=0; $total_cash=0; ?>
              <?php $expense = $con->prepare("SELECT * FROM psits_expenses WHERE id NOT IN (SELECT exp_id FROM pending_expenses) AND event_id = {$value['id']}");
                $expense->execute();?>
              <?php foreach ($expense->fetchAll(PDO::FETCH_ASSOC) as $key => $value2): ?>
                <?php $total_amount+=$value2['amount']; $total_cash+=$value2['received']; ?>
              <?php endforeach; ?>
              <?php $money_left = getMoneyLeft($value['id']) ?>
              <?php $currentFund = (getFund($value['id'])-$total_cash)+$money_left;?>
              <option value="<?php echo $value["id"] ?>" sem="<?php echo $value["sem"]?>" fund="<?php echo $currentFund ?>">
                <?php
                printf("%s Php %.2f",$value['name'],$currentFund); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </td>
      </tr>
      <tr>
        <td></td>
        <td align="left">
          <button type="button" id="add">Proceed</button>
        </td>
      </tr>
    </table>
    <script type="text/javascript">
       readyToProceed = true;
        (checkFund = function() {
          amount = eval($("#fund option:selected").attr("fund")-$("#amount_cash").val());
          $("#f").html(amount.toFixed(2));
          if(amount<=0)
          $("#f").css("color","red");
          else
          $("#f").css("color","green");
        })()
        $("#fund").on("change",function(){
          checkFund();
        })
        action = function(form,location,action,res){
          readyToProceed = false;
          data = {};
          data["action"] = action;
          $(form+" input").each(function(){data[$(this).attr("name")] = $(this).val();})
          $(form+" select").each(function(){data[$(this).attr("name")] = $(this).val();})
          $(form+" option").each(function(){
            if(data.event==$(this).attr("value"))
            data["sem"] = $(this).attr("sem")
            if(data.event==$(this).attr("value"))
            data["exp"] = $(this).attr("last")
          })
          $.ajax({
            method: "POST",
            url: location,
            data: data,
            beforeSend: function(){
              $(res).html("<div class='col-100 flex align-center justify-center' style='background: #fff;'>\
              <img src='img/spinner.svg' width='50'/></div>");
            },
            success: function(data2) {
              readyToProceed = true;
              $(res).html(data2);
            },
          })
        }
        $("#add").click(function(){
          if(readyToProceed)
          action("#add-form","expenses/action.php","add","#res");
        })
    </script>
  </body>
</html>
