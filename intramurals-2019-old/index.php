
<?php
  require "../config/config.php";
  require "../api/func.php";
  secure();

  if(isset($_SESSION['account'])){
  $user = isset($_SESSION['account']['officer_id'])?$_SESSION['account']['officer_id']:$_SESSION['account']['student_id'];}else{
    $user = null;
  }

  $ticket = true;
  if(isset($_SESSION['submition'])){
    if($_SESSION['submition']['stat']){
      unset($_SESSION['submition']);
      $ticket = false;}
    else {
      $ticket = true;
    }
  } else if(file_exists("./entries/{$user}.png")){
    $ticket = false;
  }
  $json = file_get_contents('./votes.js');
  $votes_array =  json_decode("{$json}",true);
  $entries = scandir('./entries');
  foreach ($votes_array as $key => $value) {
      if(!isset($pop)){
        $pop = $votes_array[$key]['votes'];
      }
      if($votes_array[$key]['votes']>=$pop){
        $pop = $votes_array[$key]['votes'];
        $popular = $key;
      }
    }


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $webtitle?> - Intramurals 2019</title>
    <?php include('../js/jquery.php') ?>
    <?php include('../css/styles.php') ?>
    <link rel="stylesheet" href="./style.css">
  </head>
  <body>
  <?php include("../core/header.php") ?>

  <div class="fake-body" style="color:#fff;position:relative;padding: 20px 0;padding-bottom:0;">
      <div class="flare" style="pointer-events: none;z-index:0;"></div>
  <div class="flex" id="container" style="position:relative;z-index:3;">
    <div class="flex flex-wrap align-start col-100">
      <div class="cc-cont col-100 flex align-start">
        <div class="cc">
          <h2>Guidelines</h2>
          <ol>
            <li>All entries must be original creation of the contestant</li>
            <li>Create a unique tagline</li>
            <li>Theme color of CCS (purple and golden yellow) must be applied in the design</li>
            <li>CCS logo must be present in the design</li>
            <li>Submission of entries will begin on June 24, 2019 and will end on July 1, 2019</li>
            <li>Submit your entries at <a href="http://psits.com/intramurals-2019/">
            http://psits.com/intramurals-2019/ &nbsp;<i class="fal fa-external-link"></i></a></li>
          </ol>
          <p>Note: Contestants must be a student from CCS department.</p><br>
          <h2>CCS LOGO</h2><br>
          <img src="/img/ccs-logo.png" alt="" width="100%" />
          <!--<div class="flex justify-center">-->
          <!--  <span class="cir" style="background: #ce9d00;">-->
          <!--  </span>-->
          <!--  <span class="cir" style="background: #5f3974;">-->
          <!--  </span>-->
          <!--  <span class="cir" style="background: #fff;">-->
          <!--  </span>-->
          <!--</div>-->
        </div>
        <div class="cc col-100 text-center">
          <h2 class="tagline">Unveil your creativity!👕</h2>
          <img src="/img/intrams-tshirt-contest.png" alt="" width="100%">
        </div>
        <div class="cc">
            <h2>Criteria for judging</h2>
            <ol>
              <li>Number of hearts</li>
              <li>Appropriateness to Theme</li>

            </ol>
  <h3 style="word-break: break-word;">The winner will receive the following:</h3>
            <ol>
              <li>Intrams T-shirt</li>
              <li>Lanyard</li>
              <li>Certificate</li>
            </ol>
            
            <br>

            <?php if ($ticket): ?>
              <!--<span class="alert sm">-->
              <!--  Submitted entry should be in a jpg/png file format-->
              <!--</span>-->
              <div id="upload" style="color:#fff;cursor:pointer;padding:15px 30px;max-width: 100%;border-radius: 10px 10px 0px 0px" onclick="$('input[type=file]').click()">
                Upload Intrams T-shirt Design
              </div>
              <div>
                <form action="/api/upload.php" method="post" enctype="multipart/form-data">
                  <input type="submit" name="submit" value="Submit" class="col-100" style="border-radius: 0px 0px 10px 10px;background-color: #ffd140;color:#fff;border:none;cursor:pointer;position:relative;z-index: 4;">
                  <input type="file" name="entry" accept="image/*" style="visibility:hidden;"/>
                </form>
              </div>
              <?php else: ?>
                <h2>Your entry</h2>
            <img src="./entries/<?php echo $user?>.png" width="100%" style="border-radius: 8px;"/>
            <?php if ($popular == "s$user"): ?>
              <span>MOST HEARTED 😍😍😍</span>
            <?php endif; ?>
            <br>
            <span>You captured <?php echo $votes_array["s$user"]['votes'] ?> hearts</span>
            <a href="./delete-entry.php">Delete</a>

            <br><br>
            <?php endif; ?>
          <a class="button" href="javascript:$(document).scrollTop($('.p_c').offset().top)">GIVE YOUR HEART 💜</a>
        </div>


      </div>
        <div class="col-100 thumbnails">
          <div class="bb col-100">
            <div class="bb-wrap flex justify-center align-start flex-wrap">
              <h2 class="d col-100 text-center">Entries</h2>
              <?php if (sizeof($entries)>2): ?>
                  <?php foreach ($entries as $value): ?>
                    <?php $entry_no = substr($value,0,strpos($value,'-')) ?>
                    <?php if(strlen($value)<3 || !strpos($value,'thumb')){continue;} ?>
                    <div class="p_c flex flex-wrap" style="position:relative;">
                      <div class="col-100 text-center">
                        <a style="font-size:1em;"><?php echo $votes_array["s{$entry_no}"]['name'] ?></a>
                        <img src="<?php echo './entries/'.$value ?>" alt="" class="big_picture">
                      </div>
                      <div class="col-100" style="position:absolute;bottom:0;left:0;right:0;background:#fff;padding: 10px 0;">
                        <a href="./vote.php?id=<?php echo $entry_no ?>" class="ajax-link">
                          <i class="<?php echo in_array($user,$votes_array["s{$entry_no}"]['voters'])?'fas':'fal' ?> fa-heart"></i>
                        </a>
                        <a><?php echo $votes_array["s{$entry_no}"]['votes'] ?></a>
                      </div>
                    </div>
                  <?php endforeach; ?>
                                  <div class="pop col-100 text-center"><h2>Most hearted design</h2><br>
                <div class="col-100 flex justify-space-between" style="background: #5f3974;padding: 10px">
                  <h3 style="color: #ffd140;"><?php echo $votes_array[$popular]['name'] ?>'s<span style="color:#fff"> ENTRY</span></h3>
                  <h2 style="color:#fff"><?php echo $votes_array[$popular]['votes'] ?>
                    <i class="fal fa-heart"></i>s</h2>
                </div>
                <div class="col-100" style="background: #000;position:relative;overflow:hidden;">
                    <div style="background:url('./entries/<?php echo substr($popular,1) ?>-thumb.png') no-repeat center;background-size: 200%;position:absolute;top:0;left:0;right:0;bottom:0;z-index:0;filter:blur(9px)"></div>
                    <img src="./entries/<?php echo substr($popular,1) ?>.png" style="position:relative;z-index:1;max-height:500px"/></div>
                </div>
                  <?php else: ?>
                    <p>No entries yet</p>
              <?php endif; ?>
            </div>
          </div>
        </div>

      <div class="col-100" id="faq">
        <div style="width: 95%;margin:15px auto;">
          <h2>FAQ</h2>
          <style media="screen">
            ul li {margin: 17px 0;}
          </style>
          <ul>
              <li>
                  How many entries can I heart?
                  <br>
                  <b>+ You can give a heart to all entries</b>
              </li>
                        <li>
                What will happen if I delete my entry?
                <br>
                <b>+ Your current hearts and entry will be permanently deleted</b>
            </li>
            <li>How can I submit my entry?
              <br>
              <b>+ You can submit your entry by uploading a jpg/png file <a href="javascript:$(document).scrollTop($('#upload').offset().top)">here</a></b>
            </li>
            <li>I can't login my PSITS account/I don't know my PSITS account
              <br>
              <b>+ PM the site admin on <a href="https://www.facebook.com/average.g0at" target="_blank">Facebook
              <i class="fal fa-external-link"></i></a> for assistance</b>
            </li>

          </ul>
          <b>For questions and inquiries dial +63 956 411 8702</b>
        </div>
      </div>

      <div id="zoom_container" class="flex align-items-center justify-center" style="display:none;background:rgba(0,0,0,0.8);z-index:9999;position:fixed;top:0;bottom:0;left:0;right:0;">
        <div class="flex align-items-center">
          <img src="/dashboard/img/bluecat.svg" width="100%">
        </div>
      </div>
    </div>

  </div>
  </div>
  <script type="text/javascript">
    $('h2').each(function(){
      $(this).attr('data-text',$(this).text())
    })
    $('.ajax-link').each(function(){
      $(this).click(function(e){
        e.preventDefault()
        let that = $(this)
        $.ajax({
          method: 'GET',
          url: $(this).attr('href'),
          success: function(data){
            e = JSON.parse(data)
            if(!e.res){
            alert('You already hearted this entry!');return;}
            let c = parseInt(that.next().text());
            that.next().text(c+1)
            that[0].children[0].classList.add('fas')
            that[0].children[0].classList.remove('fal')
          }
        })
      })
    })
    $('.big_picture').click(function(){
      $('#zoom_container').show()
      let image = new Image()
      let that = $(this)
      $.ajax({
        method: 'GET',
        url: $(this).attr('src').split('-')[0]+'.png',
        success: function(){
          image.src = that.attr('src').split('-')[0]+'.png'
          $('#zoom_container img').attr('src',image.src)
        }
      })
    })
    $('#zoom_container').click(function(){
      $(this).toggle()
      $('#zoom_container img').attr('src',"/dashboard/img/bluecat.svg")
    })
    $('input[type=file]').on('change',function(){
      title = $(this).val().split('\\')[2].split('.')[0]
      title = title.length>24?title.slice(0,24)+'...':title
      $('#upload').text(title)
    })
  </script>
  <?php include('../core/footer.php');?>
  <script type="text/javascript" src="/js/comment.js"></script>
  <?php include('../js/common.php');?>
  </body>
</html>
