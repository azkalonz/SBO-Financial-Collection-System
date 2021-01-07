<?php require '../config/config.php' ?>
<style media="screen">
  table tr td, table tr th {
    border: none!important;
    word-wrap: break-word;
    vertical-align: center;
    word-break: break-all;
    white-space: pre-wrap;
  }

</style>
<fieldset>
  <legend>Labels & Design</legend>
  <form id="design" method="post">
  <div class="panel">
    <div class="panel-header">
      Labels
    </div>
    <div class="panel-body">
      <table>
        <tr>
          <td>Text logo</td>
          <td align="top"><input type="text" name="label[]" value="<?php echo $labels['text-logo'] ?>"></td>
        </tr>
        <tr>
          <td>Definition</td>
          <td align="top"><input type="text" name="label[]" value="<?php echo $labels['abbr'] ?>"></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="panel">
    <div class="panel-header">
      Design
    </div>
    <div class="panel-body">
        <input type="hidden" name="action" value="design">
        <input type="hidden" name="settings" value="true">
        <table>
          <tr>
            <td>Logo</td>
            <td><input type="text" name="image[]" value="<?php echo $images['logo'] ?>"></td>
          </tr>
          <tr>
            <td>Favicon</td>
            <td><input type="text" name="image[]" value="<?php echo $images['favicon'] ?>"></td>
          </tr>
          <tr>
            <td>Navbar background</td>
            <td><input type="text" name="color[]" value="<?php echo $colors['nav-bar'] ?>"></td>
          </tr>
          <tr>
            <td>Text logo color</td>
            <td><input type="text" name="color[]" value="<?php echo $colors['text-logo'] ?>"></td>
          </tr>
          <tr>
            <td>Left panel background</td>
            <td><input type="text" name="color[]" value="<?php echo $colors['left-panel'] ?>"></td>
          </tr>
          <tr>
            <td>Right panel background</td>
            <td><input type="text" name="color[]" value="<?php echo $colors['right-panel'] ?>"></td>
          </tr>
          <tr>
            <td>Page label</td>
            <td><input type="text" name="color[]" value="<?php echo $colors['page-label'] ?>"></td>
          </tr>

        </table>
    </div>
  </div>
  </form>
  <br>
  <div class="col-100 flex justify-end">
    <button type="button" class="green" name="button" onclick="$('#design').submit()">Save</button>
  </div>
</fieldset>
<fieldset>
  <legend>User Privileges</legend>
  <div class="panel">
    <div class="panel-header">
      Privileges
    </div>
    <div class="panel-body">
      <form id="privs" action="/dashboard/index.php" method="post" onsubmit="$(document).scrollTop(0);$('input[name^=priv]').each(function(){$(this).prop('checked',true)});return true;">
        <input type="hidden" name="action" value="privs">
        <input type="hidden" name="settings" value="true">
      <table>
        <tr>
          <td>Account type</td>
          <td><select id="priv" name="type">
              <option value="a">A</option>
              <option value="b">B</option>
              <option value="c">C</option></select></td>
        </tr>
        <tr>
          <td>Registration</td>
          <td><input type="checkbox" name="priv[0]" value="1"> </td>
        </tr>
        <tr>
          <td>Collection</td>
          <td><input type="checkbox" name="priv[]" value="1"> </td>
        </tr>
        <tr>
          <td>View expenses</td>
          <td><input type="checkbox" name="priv[]" value="1"> </td>
        </tr>
        <tr>
          <td>Manage events</td>
          <td><input type="checkbox" name="priv[]" value="1"> </td>
        </tr>
        <tr>
          <td>View logs</td>
          <td><input type="checkbox" name="priv[]" value="1"> </td>
        </tr>
        <tr>
          <td>View notes</td>
          <td><input type="checkbox" name="priv[]" value="1"> </td>
        </tr>
        <tr>
          <td>Manage comments</td>
          <td><input type="checkbox" name="priv[]" value="1"> </td>
        </tr>
        <tr>
          <td>View Reports</td>
          <td><input type="checkbox" name="priv[]" value="1"> </td>
        </tr>
        <tr>
          <td>Compose email</td>
          <td><input type="checkbox" name="priv[]" value="1"> </td>
        </tr>
      </table>
      </form>
    </div>
  </div>
  <br>
  <div class="col-100 flex justify-end">
    <button type="button" class="green" name="button" onclick="$('#privs').submit()">Save</button>
  </div>
</fieldset>
<fieldset>
  <legend>Department</legend>
  <form id="department" action="/dashboard/index.php" method="post">
    <input type="hidden" name="action" value="department">
    <input type="hidden" name="settings" value="true">
    <div class="panel">
      <div class="panel-header">
        School
      </div>
      <div class="panel-body">
        <table>
          <tr>
            <td>University</td>
            <td><input type="text" name="university" value="<?php echo $department['university'] ?>"></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="panel">
      <div class="panel-header">
        School
      </div>
      <div class="panel-body">
        <table>
          <tr>
            <td>College</td>
            <td><input type="text" name="college" value="<?php echo $department['college'] ?>"></td>
          </tr>
        </table>
      </div>
    </div>
  </form>
  <br>
  <div class="col-100 flex justify-end">
    <button type="button" class="green" name="button" onclick="$('#department').submit()">Save</button>
  </div>
</fieldset>
<a href="#" id="show" onclick="event.preventDefault();$('#advance').toggle();$('#show').toggle()">Advanced</a>
<div id="advance" style="display: none;">
  <fieldset>
    <legend>Advance</legend>
    <div class="panel">
        <div class="panel-header">
          Database
        </div>
        <div class="panel-body">
          <table>
            <tr>
              <td>DB Host</td>
              <td><?php echo $dbhost ?></td>
            </tr>
            <tr>
              <td>DB user</td>
              <td><?php echo $dbuser ?></td>
            </tr>
            <tr>
              <td>DB name</td>
              <td><?php echo $dbname ?></td>
            </tr>
          </table>
        </div>
    </div>
    <form id="web" action="/dashboard/index.php" method="post">
      <input type="hidden" name="action" value="web">
      <input type="hidden" name="settings" value="true">
      <div class="panel">
          <div class="panel-header">
            Website
          </div>
          <div class="panel-body">
            <table>
              <tr>
                <td>Domain</td>
                <td><input type="text" name="domain" value="<?php echo $web['domain'] ?>"></td>
              </tr>
              <tr>
                <td>Title</td>
                <td><input type="text" name="webtitle" value="<?php echo $web['title'] ?>"> </td>
              </tr>
            </table>
            <br>
            <div class="flex justify-end col-100">
              <button class="green" type="button" name="button" onclick="$('#web').submit()">Save</button>
            </div>
          </div>
      </div>
    </form>
    <div class="panel">
        <div class="panel-header">
          PHP
        </div>
        <div class="panel-body">
          <table>
            <?php foreach ($_SERVER as $key => $value): ?>
              <?php if ($key=='PATH'){break;} ?>

              <tr>
                <td><?php echo $key ?></td>
                <td><?php echo $value ?></td>

              </tr>
            <?php endforeach; ?>
          </table>
        </div>
    </div>
  </fieldset>
  <a href="#" id="show" onclick="event.preventDefault();$('#advance').toggle();$('#show').toggle()">Hide</a>
</div>
<script type="text/javascript">
  sets = {
    a: JSON.parse('<?php echo json_encode($privileges) ?>').a,
    b: JSON.parse('<?php echo json_encode($privileges) ?>').b,
    c: JSON.parse('<?php echo json_encode($privileges) ?>').c
  }
  userTypes = (e)=>{
    let c=0;
    let p=e;
    $('input[name^=priv]').each(function(){
      $(this).attr('value',sets[p][c]);
      $(this).attr('readonly');
      let d = sets[p][c]==1?true:false;
      $(this).prop('checked',d);
      c++;
    });
  }
  $('#priv').on('change',function(){
    userTypes($(this).val());
  })
  userTypes('a');
  $('input[name^=priv]').each(function(){
    $(this).attr('onchange',"update($(this))");
  })
  update = (e)=>{
    e.attr('value')=='1'?e.attr('value','0'):e.attr('value','1')
  }

</script>
