<?php
  require '../config/config.php';
  $notes = $con->prepare("SELECT * FROM notes ORDER BY id DESC");
  $notes->execute();
  $notes = $notes->fetchAll(PDO::FETCH_ASSOC);
 ?>
 <style media="screen">
   .notes .card {
     width: 270px;
     padding: 10px;
     height: 257px;
     background: #763ab7;
     margin: 20px;
     color: #fff;
     overflow-y: auto;
     position: relative;
     text-shadow: 0 2px black;
     box-shadow: 0 20px 23px rgba(0,0,0,0.2);
   }
   p {
     background: #fff;
     color: #000;
     padding: 5px;text-shadow:none;
   }
   .notes .delete {
     color: #fff;
   }
 </style>
<div>
  <div class="flex col-100 justify-center">
  </div>
<div class="wrap flex col-100 notes flex-wrap" style="min-height: 90vh;">
  <div id="insert_note" class="card flex col-100 align-content-start justify-center flex-wrap" style="background: <?php echo isset($_SESSION['note']['color'])?$_SESSION['note']['color']:'#763ab7' ?>;">
      <input type="text" class="note_title" placeholder="Title" style="padding: 5px;">
      Content
      <textarea class="note_content" style="width: 100%;height:120px;resize: none;font-family: Century Gothic"></textarea>
    <div class="col-100">
      <button type="button" name="button" class="col-100" onclick="save()">save</button>
      <input type="color" id="color" name="theme_color" list="presetColors" value="<?php echo isset($_SESSION['note']['color'])?$_SESSION['note']['color']:'#763ab7' ?>" style="width:100%;">
     <datalist id="presetColors">
       <option>#763ab7</option>
       <option>#ffa600</option>
     </datalist>

    </div>
    <div id="card-loader" style="display:none;position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(255,255,255,0.6);user-select:none;" class="flex align-center justify-center">
      <img src="./img/spinner.svg" width="30" alt="">
    </div>
  </div>
  <?php foreach ($notes as $key => $value): ?>
    <?php if (!$value['hidden']): ?>
      <?php continue; ?>
    <?php endif; ?>
    <div class="card flex col-100 align-content-start justify-start flex-wrap" style="background: <?php echo $value['color'] ?>">
      <h3 class="col-100" style="position:relative;">
        <?php if ($value['user']==$_SESSION['account']['full_name'] || $_SESSION['account']['access']>30): ?>
          <div style="position:absolute;right:0;top:0;">
            <a class="delete" href="#" onclick="deleteNote($(this))" data-id="<?php echo $value['id'] ?>">&times;</a>
          </div>
        <?php endif; ?>
        <?php echo $value['title'] ?>
      </h3>
      <em style="word-break: break-word;font-size: 0.8em;">
        <?php echo date('F d, Y',strtotime(substr($value['_date'],0,strpos($value['_date'],' ')))) ?>
        <br>
        <?php echo $value['user'] ?>
      </em>
      <br>
      <p class="col-100">
        <?php echo $value['msg'] ?>
      </p>
    </div>
  <?php endforeach; ?>

</div>
</div>
<script type="text/javascript">
  $margin = $('.card').outerWidth()+(parseInt($('.card').css('margin').split('p')))
  $('#color').on('change',()=>{
    $('#insert_note').css('background',$('#color').val());
  })
  deleteNote = (e)=>{
    let a = e.parent().parent().parent();
    event.preventDefault();
    a.css('transform','scale(1)').css('transition','all 0.6s ease-out');
    a.css('transform','scale(0)')
    a.next().css('margin','0px').css('transition','all 0.6s ease-out');
    a.next().css('margin','20px 20px 20px -'+$margin+'px');
    let that = e;
    $.ajax({
      method: 'POST',
      url: '/api/send.php',
      data: {action: 'delete_note', id: that.attr('data-id')}
    })
  }
  save = ()=>{
    data = {
      action: 'note',
      title: $('.note_title').val(),
      color: $('#color').val(),
      content: $('.note_content').val().replace(/(?:\r\n|\r|\n)/g, '<br>')
    }
    $('#card-loader').show();
    $.ajax({
      method: 'POST',
      url: '/api/send.php',
      data: data,
      success: (res)=>{
        res = JSON.parse(res);
        $('#card-loader').hide();
        if(res.error){alert('Could not save note');return;}
        $template = `
        <div class="card flex col-100 align-content-start justify-start flex-wrap" style="background: ${data.color}">
          <h3 class="col-100" style="position:relative;">
          <div style="position:absolute;right:0;top:0;">
            <a class="delete" href="#" onclick="deleteNote($(this))" data-id="${res.id}">&times;</a>
          </div>
            ${data.title}
          </h3>
          <em style="word-break: break-word;font-size: 0.8em;">
            <?php echo $_SESSION['account']['full_name'] ?>
          </em>
          <br>
          <p class="col-100">
            ${data.content}
          </p>
        </div>`;
        $($template).insertAfter('#insert_note')
        $('#insert_note').next().css('margin-left','-'+($margin)+'px').css('opacity','0').css('transition','0.6s ease-out');
        $('#insert_note').next().css('opacity','1');
        setTimeout(()=>{
          $('#insert_note').next().css('margin-left','20px');
        },100);
      }
    })
  }
</script>
