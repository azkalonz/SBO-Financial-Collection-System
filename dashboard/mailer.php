<?php include('../config/config.php'); ?>
<?php include('../api/func.php');
secure(['admin-only'=>true]);
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/pell.css">
    <style media="screen">
      button.pell-button{color: #222!important;padding:0!important;}
      button.pell-button:hover{color: #fff!important;}
    </style>
  </head>
  <body>
    <div class="panel">
      <div class="panel-header">
        Mailer
      </div>
      <div class="panel-body">
        <table>
          <tr>
            <td style="display:none;" colspan="100%" align="center" id="mail-result">
              <img id="m-img" width='60'/>
            </td>
          </tr>
          <tr>
            <td>Title</td>
            <td><input type="text" name="title"></td>
          </tr>
          <tr>
            <td>Message</td>
            <td>
              <div id="editor" style="white-space: pre-wrap;width: 100%;outline: 0;border-bottom: 4px solid #878587;font-family: Roboto;font-size: 1rem;background:#fff;">
              </div></td>
          </tr>
          <tr>
            <td colspan="100%" align="right">
              <button type="button" id="sendMail">Send to All</button>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <script src="js/pell.js"></script>
    <script type="text/javascript">
      sending = false;
      $("#sendMail").click(function() {
        if(sending){return;}
        let image = new Image();
        data = {};
        data["action"] = "send-all";
        data["title"] = $("input[name=title]").val();
        data["message"] = $(".pell-content").html();
        $("#mail-result").show();
        $.ajax({
          method: "POST",
          data: data,
          url: "../mail.php",
          beforeSend: function(){
            sending = true;
            image.src = 'img/spinner.svg';
            $("#m-img").attr('src',image.src);
          },
          success: function(data){
            sending = false;
            let e = JSON.parse(data);
            if(e.error){
              image.src = 'img/error.png';
              alert(e.error);
            } else {
              image.src = 'img/check.gif';
            }
            $("#m-img").attr('src',image.src);
          }
        })
      })

      var editor = window.pell.init({
        element: document.getElementById('editor'),
        defaultParagraphSeparator: 'p',
        onChange: ()=>{}
      })
    </script>
  </body>
</html>
