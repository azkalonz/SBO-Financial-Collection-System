<?php
  require '../../config/config.php';
  require '../../api/func.php';
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo fullNameId($_GET['id']) ?></title>
    <link rel="stylesheet" href="/css/flex.css">
    <link rel="stylesheet" href="/css/psits-main-style.css">
    <link rel="icon" href="/img/favicon.png" type="img/png">
    <link rel="stylesheet" href="/dashboard/fa-old/css/fontawesome.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style media="screen">
      table{
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
          <h1><?php echo fullNameId($_GET['id']) ?></h1>
          <br />
          <div class="flex w-100 folder">
            <h3 class="table-head" data-showing="true">Activity</h3>
            <h3 class="table-head">Account Details</h3>
          </div>
          <div id="payment">
            <table style="position: relative;">
              <thead>
                <tr>
                  <td colspan="100%" class="table-title" onclick="$('.c-t').toggle()">
                    <div class="flex justify-space-between align-items-center" style="cursor:pointer;">
                      <h3>Collection history</h3>
                      <i class="fa fa-chevron-square-down" style="font-size: 1.5rem;"></i>
                    </div>
                  </td>
                </tr>
                <tr class="c-t">
                  <th>Event</th>
                  <th>Date</th>
                  <th>Student name</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody id="p-d" class="c-t">
              </tbody>
            </table>
            <table>
              <thead>
                <tr>
                  <td colspan="100%" class="table-title" onclick="$('.r-c').toggle()">
                    <div class="flex justify-space-between align-items-center" style="cursor:pointer;">
                      <h3>Registered students</h3>
                      <i class="fa fa-chevron-square-down" style="font-size: 1.5rem;"></i>
                    </div>
                  </td>
                </tr>
                <tr class="r-c" style="display: none;">
                  <th>Event</th>
                  <th>Date</th>
                  <th>Student Name</th>
                </tr>
              </thead>
              <tbody id="r-h" class="r-c" style="display: none;">
              </tbody>
            </table>
            <table>
              <thead>
                <tr>
                  <td colspan="100%" class="table-title" onclick="$('.c-c').toggle()">
                    <div class="flex justify-space-between align-items-center" style="cursor:pointer;">
                      <h3>Cash out history</h3>
                      <i class="fa fa-chevron-square-down" style="font-size: 1.5rem;"></i>
                    </div>
                  </td>
                </tr>
                <tr class="c-c" style="display: none;">
                  <th>Fund</th>
                  <th>Title</th>
                  <th>Received</th>
                  <th>Spent</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="c-o" class="c-c" style="display: none;">
              </tbody>
            </table>
          </div>

          <form id="updateOfficer" action="./index.php" method="post">
            <table id="account" style="display: none;">
              <thead>
                <tr>
                  <td colspan="100%" class="table-title">
                    <h3>Officer Info</h3>
                  </td>
                </tr>
                <tr>
                  <td>Officer ID</td>
                  <td>
                    <input id="officer_id" type="text" name="" value="">
                  </td>
                </tr>
                <tr>
                  <td>Name</td>
                  <td>
                    <input id="full_name" type="text" name="" value="">
                  </td>
                </tr>
                <tr>
                  <td>Position</td>
                  <td>
                    <input id="position" type="text" name="" value="">
                  </td>
                </tr>
                <tr>
                  <td>Password</td>
                  <td>
                  <input id="password" type="text" name="" value=""> </td>
                </tr>
                <tr>
                  <td>Account type</td>
                  <td>
                    <input id="type" type="text" name="" value="">
                  </td>
                </tr>
                <tr>
                  <td>Access</td>
                  <td>
                    <input id="access" type="text" name="" value="">
                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="right">
                    <button type="button" name="button" class="green" onclick="$('#updateOfficer').submit()">Save</button>
                  </td>
                </tr>
              </thead>
            </table>
          </form>

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
            url: '/dashboard/api?action=collected&id='+e.result[0].officer_id,
            success: function(data){
              e = JSON.parse(data);
              payment = e;
              if(!e.payment.length){
                $('#p-d').html(`
                  <tr>
                  <td colspan="100%">No records found.</td></tr>`);
              }
              for(let i in e.payment){
                $('#p-d').html(`${$('#p-d').html()}
                  <tr>
                  <td>${e.payment[i].event}</td>
                  <td>${e.payment[i]._date}</td>
                  <td>${e.payment[i].student_name}</td>
                  <td>${e.payment[i].payment}</td>
                  </tr>
                  `);
              }
            }
          })
          $.ajax({
            method: 'GET',
            url: '/dashboard/api?action=registered&username='+e.result[0].username+'&id='+e.result[0].officer_id,
            success: function(data){
              e = JSON.parse(data);
              payment = e;
              if(!e.reg){
                $('#r-h').html(`
                  <tr>
                  <td colspan="100%">No records found.</td></tr>`);
              }
              for(let i in e.reg){
                $('#r-h').html(`${$('#r-h').html()}
                  <tr>
                    <td>${e.reg[i].event}</td>
                    <td>${e.reg[i].date}</td>
                    <td>${e.reg[i].student}</td>
                  </tr>
                  `);
              }
            }
          })
          $.ajax({
            method: 'GET',
            url: '/dashboard/api?action=cashout&username='+e.result[0].username+'&id='+e.result[0].officer_id,
            success: function(data){
              e = JSON.parse(data);
              payment = e;
              if(!e.exp.length){
                $('#c-o').html(`
                  <tr>
                  <td colspan="100%">No records found.</td></tr>`);
              }
              for(let i in e.exp){
                amount = e.exp[i].amount || 'on going'
                $('#c-o').html(`${$('#c-o').html()}
                  <tr>
                    <td>${e.exp[i].event}</td>
                    <td>${e.exp[i].title}</td>
                    <td>${e.exp[i].received}</td>
                    <td>${amount}</td>
                    <td>${e.exp[i]._date}</td>
                    <td>
                      <a href="/dashboard/expenses/action.php?notify&user-id=${student.result[0].officer_id}&exp-id=${e.exp[i].id}&id=${student.result[0].officer_id}" target="_BLANK">
                      Request liquidation</a>
                    </td>

                  </tr>
                  `);
              }
            }
          })
        }
      })

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
