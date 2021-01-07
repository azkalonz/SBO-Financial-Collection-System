<?php include '../../config/config.php' ?>
<div class="panel col-100">
  <div class="panel-header">
    Account setting
  </div>
  <div class="panel-body">
    <table>
      <thead>
        <tr>
          <td colspan="100%">
            <span id="u-stat" class="success sm" style="display: none;">
              Success
            </span>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Username</td>
          <td>
            <input type="text" id="username" value="<?php echo $_SESSION['account']['username'] ?>">
          </td>
        </tr>
        <tr>
          <td>Password</td>
          <td>
            <input type="password" id="password" value="<?php echo $_SESSION['account']['password'] ?>">
          </td>
        </tr>
        <tr>
          <td colspan="100%" align="right">
            <button class="green" onclick="updateOfficer()" type="button" name="button">Update</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  updateOfficer = function(){
    $.ajax({
      method: 'POST',
      data: {action: 'update_officer', username: $('#username').val(), password: $('#password').val()},
      url: '/api/send.php',
      success: function(data){
        let e = JSON.parse(data);
        $('#u-stat').show();
        if(e.error){
          $('#u-stat').attr('class','error sm');
          $('#u-stat').text(e.error);
        } else {
          $('#u-stat').attr('class','success sm');
          $('#u-stat').text('Success');
        }
      }
    })
  }
</script>
