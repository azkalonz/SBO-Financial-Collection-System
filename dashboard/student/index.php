<?php
  require '../../config/config.php';
  require '../../api/func.php';
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo studentFullNameId($_GET['id']) ?></title>
    <link rel="stylesheet" href="/css/flex.css">
    <link rel="stylesheet" href="/css/psits-main-style.css">
    <link rel="icon" href="/img/favicon.png" type="img/png">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style media="screen">
      table {
        margin: 0;
      }
      .folder {
        height: 35px;
      }
      .table-head {
        padding: 7px 20px;
        transition: all 0.3s ease-out;
        display: inline-block;
        cursor: pointer;
        width: 245px;
        color: #ffeb3b;
        text-shadow: 0 1px black;
        background: url('../img/thead.png') no-repeat!important;
        background-size: 300px!important;
        background-position: top;
        margin-left: -120px;
        opacity: 0.5;
      }
      .table-head:first-of-type {
        opacity: 1;
        z-index: 99;
        margin: 0;
      }
      .table-head:hover {
        opacity: 1!important;
      }
    </style>
  </head>
  <body>

    <div class="w-100">
      <span id="error" style="display: none;" class="alert sm">
        <div id="denied">
        </div>
      </span>
    </div>
    <div id="result" style="display: none;">
      <div class="flex w-100" style="background: #fff;height: 100vh;padding-top:12px;margin-top:12px;">
        <div class="wrap">
          <h1><?php echo studentFullNameId($_GET['id']) ?></h1>
          <br />
          <div class="flex w-100 folder">
            <h3 class="table-head" data-showing="true">Activity</h3>
            <h3 class="table-head">Account Details</h3>
          </div>
          <div id="payment">
            <table style="position: relative;">
              <thead>
                <tr>
                  <td id="spinner" style="display: none;position: absolute;top:0;left:0;right:0;bottom:0;background: #fff!important;" class="flex justify-center align-items-center">
                    <img id="s-img" width="50" alt="">
                  </td>
                </tr>
                <tr>
                  <td colspan="100%" class="table-title">
                    <h3>Payment history</h3>
                  </td>
                </tr>
                <tr>
                  <th>Event</th>
                  <th>Date</th>
                  <th>Encoded by</th>
                  <th>Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="p-d">
              </tbody>
            </table>
            <table>
              <thead>
                <tr>
                  <td colspan="100%" class="table-title">
                    <h3>Registered events</h3>
                  </td>
                </tr>
                <tr>
                  <th>Event</th>
                  <th>Date</th>
                  <th>Registrant</th>
                </tr>
              </thead>
              <tbody id="r-h">
              </tbody>
            </table>
          </div>

          <table id="account" style="display: none;">
            <thead>
              <tr>
                <td colspan="100%" class="table-title">
                  <h3>Student Info</h3>
                </td>
              </tr>
              <tr>
                <td>Student ID</td>
                <td>
                  <input type="text" id="student_id">
                </td>
              </tr>
              <tr>
                <td>Name</td>
                <td>
                  <input type="text" id="full_name">
                </td>
              </tr>
              <tr>
                <td>Course</td>
                <td>
                  <input type="text" id="course">
                </td>
              </tr>
              <tr>
                <td>Year level</td>
                <td>
                  <input type="text" id="_year">
                </td>
              </tr>
              <tr>
                <td>Email</td>
                <td>
                  <input type="text" id="email">
                </td>
              </tr>
              <tr>
                <td colspan="2" align="right">
                  <button type="button" name="button">Update</button>
                </td>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      let data;
      let student;
      let payment;
      $.ajax({
        method: 'GET',
        url: '/dashboard/api?'+window.location.href.split('?')[1],
        success: function(e){
          e = JSON.parse(e);
          student = e;
          if(e.error) {
            $('#error').text(e.error);
            $('#error').show();
            throw e.error;}
          $('#result').show();
          data = e.result;
          // console.log(JSON.stringify(e, undefined,2));
          for(let i in data[0]){
            if(document.querySelector('#'+i)==null){continue;}
            document.querySelector('#'+i).value = data[0][i];
          }
          $.ajax({
            method: 'GET',
            url: '/dashboard/api?action=payment&id='+e.result[0].student_id,
            success: function(data){
              e = JSON.parse(data);
              payment = e;
              if(!e.payment){
                $('#r-h').html(`
                  <tr>
                  <td colspan="100%">No records found.</td></tr>`);
              }
              for(let i in e.payment){
                $('#p-d').html(`${$('#p-d').html()}
                  <tr>
                  <td>${e.payment[i].event}</td>
                  <td>${e.payment[i]._date}</td>
                  <td>${e.payment[i].encoded_by}</td>
                  <td>${e.payment[i].payment}</td>
                  <td>
                    <a href="#"
                    event="${e.payment[i].event}"
                    year="${e.payment[i].year_}"
                    amount="${e.payment[i].payment}"
                    cid="${e.payment[i].id}" onclick="resend($(this))">
                    Resend receipt</a>
                  </td>
                  </tr>
                  `);
              }
            }
          })
          $.ajax({
            method: 'GET',
            url: '/dashboard/api?action=register&id='+e.result[0].student_id,
            success: function(data){
              e = JSON.parse(data);
              payment = e;
              if(!e.reg){
                $('#r-h').html(`
                  <tr>
                  <td colspan="100%">No records found.</td></tr>`);
              }
              for(let i in e.reg){
                e.reg[i].registrant = e.reg[i].registrant!=null?e.reg[i].registrant:'Self';
                $('#r-h').html(`${$('#r-h').html()}
                  <tr>
                    <td>${e.reg[i].event}</td>
                    <td>${e.reg[i].date}</td>
                    <td>${e.reg[i].registrant}</td>
                  </tr>
                  `);
              }
            }
          })
        }
      })
      function resend(e){
        let d = {};
        let image = new Image();
        image.src = '/dashboard/img/spinner.svg';
        d.student = [];
        d.action = 'send-receipt';
        d.title = `We received your payment with thanks (STUDENT_NO ${student.result[0].student_id} ${e.attr('year')})`;
        d.message = 'Collection';
        d.email = student.result[0].email;
        d.amount = e.attr('amount');
        d.date = e.attr('year');
        d.student = `{
          name: student.result[0].last_name+', '+student.result[0].first_name,
          course: student.result[0].course,
          year: student.result[0]._year,
          email: student.result[0].email
        }`;
        d.size = '';
        d.student_id = student.result[0].student_id;
        d.name = student.result[0].last_name+', '+student.result[0].first_name;
        d.event = e.attr('event');
        d.collection_id = e.attr('cid');

        let that = this;
        $.ajax({
          method: "POST",
          data: d,
          beforeSend: function(){
            $('#spinner').show();
            $('#s-img').attr('src',image.src);
          },
          url: "../../mail.php",
          success: function(data){
            e = JSON.parse(data);
            if(e.error){
              image.src = '/dashboard/img/error.png';
              $('#s-img').attr('src',image.src);
              setTimeout(function(){
                $('#spinner').hide();
                $('#s-img').attr('src',image.src);
              },2100)
            }
            else {
              image.src = '/dashboard/img/check.gif';
              $('#s-img').attr('src',image.src);
              setTimeout(function(){
                $('#spinner').hide();
                $('#s-img').attr('src',image.src);
              },2100)
            }
          }
        })

      }
      $('.table-head').each(function(){
        $(this).on('mouseover',()=>{
          if($(this).attr('data-showing')!='true'){
          $(this).css('z-index',9999);}
        })
        $(this).on('mouseout',()=>{
          if($(this).attr('data-showing')!='true')
          $(this).css('z-index',9);
        })
        $(this).on('click',()=>{
          if($(this).attr('data-showing')=='true'){return;}
          $('*[data-showing]').attr('data-showing','false');
          $('.table-head').css('z-index',99);
          $(this).css('z-index',999);
          $('#payment').toggle();
          $('#account').toggle();
          $(this).attr('data-showing','true');
          $('.table-head').each(function(){
            if($(this).attr('data-showing')=='false')
              $(this).css('opacity','0.5');
            else
              $(this).css('opacity','1');
          })
        })
      })
    </script>
  </body>
</html>
