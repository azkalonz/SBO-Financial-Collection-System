<?php
  include('../../config/config.php');
  include('../../api/func.php');
  $pending_exp = $con->prepare("SELECT * FROM psits_expenses WHERE id IN (SELECT exp_id FROM pending_expenses)");
  $pending_exp->execute();
  $pending_exp = $pending_exp->fetchAll(PDO::FETCH_ASSOC);
?>
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready( function () {
  $('#pending_exp').DataTable();
});
</script>

<table id="pending_exp" style="position: relative;">
  <thead>
    <tr>
      <td id="p-loader" style="display: none;position: absolute; left:0; top:0;bottom:0;right:0;text-align:center;background: rgba(255,255,255,0.6)!important">
        <img src="img/spinner.svg" width="50" alt="">
      </td>
    </tr>
    <tr>
      <th>Name</th>
      <th>Title</th>
      <th>Amount</th>
      <th>Date</th>
      <th>Fund</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pending_exp as $key => $value): ?>
      <?php
      $pid = $con->prepare("SELECT id FROM pending_expenses WHERE exp_id = {$value['id']}");
      $pid->execute();
      $pid=$pid->fetch()[0];
       ?>
      <tr>
        <td><?php echo $value['user'] ?></td>
        <td><?php echo $value['title'] ?></td>
        <td><?php echo $value['received'] ?></td>
        <td><?php echo $value['_date'] ?></td>
        <td><?php echo getEventName($value['event_id']) ?></td>
        <td>
          <a href="#" data-ajax2="expenses/action.php" data-exp-id="<?php echo $pid ?>">Accept</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<script type="text/javascript">
  $('a[data-ajax2]').each(function(){
    $(this).on('click',()=>{
      let link = $(this).attr('data-ajax2');
      $.ajax({
        method: 'POST',
        data: {id: $(this).attr('data-exp-id'), action: 'accept'},
        url: link+'?id='+$(this).attr('data-exp-id'),
        beforeSend: function(){
          $('#p-loader').show();
        },
        success: function(data) {
          console.log(data);
          data = JSON.parse(data);
          $('#disp').load('expenses/pending.php',{},function(){
            $('#disp').prepend(`
              <span class="${data.stat} sm">
                ${data.msg}</span>
              `)
            $('#p-loader').hide();
          });
        }
      })
    })
  })
</script>
