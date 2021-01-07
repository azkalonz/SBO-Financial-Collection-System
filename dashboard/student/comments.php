<?php include('../../config/config.php') ?>
<?php include('../../api/func.php') ?>
<?php
  secure(['admin-only'=>true]);
  $comments = $con->prepare('SELECT * FROM comments ORDER BY id DESC');
  $comments->execute();
  $comments = $comments->fetchAll(PDO::FETCH_ASSOC);
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
$(document).ready( function () {
  $('#c').DataTable({
    columnDefs: [{ "targets": [0,6,7], "searchable": false, "orderable": false, "visible": true }]
  });
});
</script>
<style media="screen">
  table tr td {
    vertical-align: top;
  }
</style>
  </head>
  <body>
    <div class="panel">
      <div class="panel-header">
        Comments
      </div>
      <div class="panel-body">
        <form onsubmit="return false();" method="post">
          <table id="c" class="w-100" style="position:relative;">
            <thead>
              <tr style="z-index: 999999;position: relative;">
                <td id="spinner" style="z-index: 999999;position: relative;display: none;position: absolute;top:0;left:0;right:0;bottom:0;background: rgba(255,255,255,0.8)!important;" class="flex justify-center align-items-center">
                  <img id="s-img" width="50" alt="">
                </td>
              </tr>
              <th>
                <a href="#" onclick="selectAll()">
                All</a>
              </th>
              <th>ID</th>
              <th>Name</th>
              <th>Date</th>
              <th>Event</th>
              <th>Comment</th>
              <th>
                <a href="#" onclick="acceptAll()">
                Show</a>
              </th>
              <th>
                <a href="#" onclick="ignoreAll()">
                Hide</a>
              </th>
            </thead>
            <tbody>
              <?php foreach ($comments as $key => $value): ?>
                <tr style="opacity: <?php echo $value['stat']==1?'1':'0.2'; ?>;">
                  <td>
                    <input type="checkbox" name="item[]" value="<?php echo $value['id'] ?>">
                  </td>
                  <td><?php echo $value['student_id'] ?></td>
                  <td><?php echo $value['name'] ?></td>
                  <td><?php echo $value['_date'] ?></td>
                  <td><?php echo getEventName($value['event_id']) ?></td>
                  <td style="white-space:pre-wrap; word-break:break-word;"><?php echo $value['comment'] ?></td>
                  <td>
                    <a href="#" onclick="event.preventDefault();acceptOne(this)">
                      <i class="fa fa-check"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" onclick="event.preventDefault();ignoreOne(this)">
                      <i class="fa fa-times"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
      </form>
      </div>
    </div>

    <script type="text/javascript">
       selectAll = function(){
        if($(this).attr('select')==undefined){
          $(this).attr('select',true);
        }
        if($(this).attr('select')){
          $('#c input[type=checkbox]').each(function(){
            $(this).prop('checked',true);
          })
          $(this).attr('select',false);
        } else {
          $('#c input[type=checkbox]').each(function(){
            $(this).prop('checked',false);
          })
          $(this).attr('select',true);
        }
      }
      acceptAll = function(){
        let image = new Image();
        let items = [];
        let data = {};
        data.action = 'accept';
        $('#c input[type=checkbox]').each(function(){
        	if($(this).prop('checked'))
             items.push($(this).val());
        });
        data.items = items;
        $.ajax({
          method: 'POST',
          url: 'student/action.php',
          data: data,
          beforeSend: function(){
            image.src = '/dashboard/img/spinner.svg';
            $('#s-img').attr('src',image.src);
            $('#spinner').show();
          },
          success: function(data){
            image.src = '/dashboard/img/check.gif';
            $('#s-img').attr('src',image.src);
            setTimeout(function(){
              $('#spinner').hide();
              $('#s-img').attr('src',image.src);
              $('#dashboard-view').load('student/comments.php');
            },2000);
          }
        })
      }
      ignoreAll = function(){
        let image = new Image();
        let items = [];
        let data = {};
        data.action = 'ignore';
        $('#c input[type=checkbox]').each(function(){
        	if($(this).prop('checked'))
             items.push($(this).val());
        });
        data.items = items;
        $.ajax({
          method: 'POST',
          url: 'student/action.php',
          data: data,
          beforeSend: function(){
            image.src = '/dashboard/img/spinner.svg';
            $('#s-img').attr('src',image.src);
            $('#spinner').show();
          },
          success: function(data){
            image.src = '/dashboard/img/check.gif';
            $('#s-img').attr('src',image.src);
            setTimeout(function(){
              $('#spinner').hide();
              $('#s-img').attr('src',image.src);
              $('#dashboard-view').load('student/comments.php');
            },2400);
          }
        })
      }
      acceptOne = function(e){
        $('#c input[type=checkbox]').each(function(){
          $(this).prop('checked',false);
        })
        console.log(e.parentNode.parentNode.children[0].children[0].checked=true);
        acceptAll();
      }
      ignoreOne = function(e){
        $('#c input[type=checkbox]').each(function(){
          $(this).prop('checked',false);
        })
        console.log(e.parentNode.parentNode.children[0].children[0].checked=true);
        ignoreAll();
      }
    </script>
  </body>
</html>
