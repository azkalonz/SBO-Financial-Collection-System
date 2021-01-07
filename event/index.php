<?php
 include('../config/config.php');
 include('../api/func.php');
 secure();
 if(isset($_GET['id'])){
   $id = $_GET['id'];
   $event = $con->prepare("SELECT * FROM psits_events WHERE id = {$id}");
   $event->execute();
   $event = $event->fetchAll(PDO::FETCH_ASSOC)[0];

   $date = explode('-',date('d-M-l',strtotime($event['date'])));
   $day = $date[0];
   $month = $date[1];
   $week = $date[2];
   $time = date("g:i A", strtotime($event['startTime']));
 }
 ?>
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta property="og:description"
       content="<?php echo $event['_desc'] ?>" />
     <meta property="og:locale" content="en_US" />
     <meta property="og:site_name" content="PSITS - UCLM" />
     <meta property="og:type" content="website" />
     <meta name="keywords" content="PSITS, UCLM, University of Cebu, Philippines Society of Information Technology Students">
     <meta name="author" content="College of Computer Studies - UCLM">
     <meta property="og:image" content="https://i.imgur.com/FwCBMtk.jpg" />
     <title><?php echo $webtitle?> - <?php echo getEventName($id) ?></title>
     <script type="text/javascript" src="/js/mkj-api.js"></script>
     <?php include('../js/jquery.php') ?>
     <?php include('../css/styles.php') ?>
     <script type="text/javascript" src="/js/progressbar.js"></script>
     <style media="screen">

       #at4-share{
         display: none;
       }
     </style>
   </head>
   <body>
   <?php include("../core/header.php") ?>
   <div class="fake-body">
     <div class="wrap flex" id="container">
       <div class="col-100">
           <span id="registerResult" class="sm alert" style="display:none"></span>
         <div class="event-container flex align-start" style="line-height:32px;padding: 18px;position:relative;border: .5px solid <?php echo $event['theme_color'] ?>">

           <div class="col-100">
             <div class="flex col-100 align-center justify-space-between">
               <div>
                   <h1><?php echo $event['name'] ?></h1>
                   <a style="color: <?php echo $event['theme_color'];?>">
                     <i class="fa fa-calendar"></i>
                     <?php echo $week ?>
                   </a>
                   <a style="color: <?php echo $event['theme_color'];?>">
                     <i class="fa fa-clock"></i>
                     <?php echo $time ?>
                   </a>
               </div>
               <div class="calendar flex flex-wrap text-right">
                 <div class="col-100 day" style="background-color:  <?php echo $event['theme_color']?>">
                   <b class="d2" style="color: <?php echo $event['theme_color'];?>"><?php echo $month ?></b>
                   <b class="d"><?php echo $day ?></b>
                   <span class="d2" style="background-color:  <?php echo $event['theme_color']?>;padding: 0 10px;border-radius: 4px;"><b><?php echo $day ?></b></span>
                 </div>
                 <div class="col-100 month d" style="color: <?php echo $event['theme_color'];?>">
                   <b><?php echo $month ?></b>
                 </div>
               </div>

             </div>
             <hr>
             <p>
               <?php echo $event['_desc'] ?>
             </p>
             <div class="col-100">
               <?php if ($event['registration']): ?>
                 <br>
                 <a id="reg-btn" class="button">
                   Register
                 </a>
                 <br><br>
               <?php endif; ?>
             </div>

             <!-- Go to www.addthis.com/dashboard to customize your tools -->
             <div class="addthis_inline_share_toolbox"></div>

             <p>
               <?php echo $event['long_desc'] ?>
             </p>
           </div>

         </div>
         <h2>Comments</h2>
         <br>
         <div style="border: .5px solid <?php echo $event['theme_color'] ?>;padding-right: 1px;">
           <?php
            $cid = $id;
            include '../module/comment.php'; ?>
        </div>

       </div>



       <?php include('../core/sidebar.php') ?>
     </div>
   </div>
   <?php include('../core/footer.php');?>
   <!-- Go to www.addthis.com/dashboard to customize your tools -->
   <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5bcfdf19bd0d981c"></script>
   <script type="text/javascript">
    checkerror = function(){}
    $('#reg-btn').click(function(){
      let image = new Image();
      let data = {
        action: 'register',
        student_id: <?php echo $_SESSION['account']['user-id'] ?>,
        full_name: '<?php echo $_SESSION['account']['full_name'] ?>',
        course: '',
        year: '',
        sem2: <?php echo $event['sem'] ?>,
        event_name: <?php echo $event['id'] ?>,
        disp: ''
      }
      if($(this).text().trim()!=='Register'){return;}
      that = $(this)
      $.ajax({
        method: 'POST',
        data: data,
        url: '/api/send.php',
        beforeSend: function(){
          that
          .css('display','inline-block')
          .css('padding','0')
          .css('border-radius','20px')
          .css('background-color','#7ac142')
          .css('width',that.width()+'px')
          .css('transition','all 0.3s ease-out')
          .css('width','30px')
          .css('height','30px')
          that.html(`
             <img src="/dashboard/img/balls.svg" style="width:30px!important;padding:5px;"/>
             `)
        },
        success: function(data){
          $('#registerResult').show();
          $('#registerResult').html(data);
         that.html(`
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" style="width: 30px;height:30px;"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>
          `)
          setTimeout(function(){
            that
            .css('transition','all 0.6s ease-out')
            .css('display','inline-block')
            .css('padding','10px 15px')
            .css('border-radius','5px')
            .css('background-color','#ffc800')
            .css('width','initial')
            .css('height','initial')
            .css('line-height','initial')
            that.html('Register');
          },3000);
        }
      })
    })
   </script>


   <script type="text/javascript" src="/js/comment.js"></script>

   <?php include('../js/common.php');?>
   </body>
 </html>
