<div id="addStudent" class="modal">
  <span id="result" class="sm error">
  </span>
  <form id="add" onsubmit="return false">
    <table>
      <tr>
        <td colspan="100%" class="table-title">
          <h3>Add Student</h3>
        </td>
      </tr>
      <tr>
        <td>First Name</td>
        <td>
          <input type="text" name="fname" value=""><br>
        </td>
      </tr>
      <tr>
        <td>Last Name</td>
        <td>
          <input type="text" name="lname" value="">
        </td>
      </tr>
      <tr>
        <td>Student ID</td>
        <td>
          <input type="text" name="id" value="">
        </td>
      </tr>
      <tr>
        <td>Course</td>
        <td>
          <input type="text" name="course" value="">
        </td>
      </tr>
      <tr>
        <td>Year level</td>
        <td>
          <input type="text" name="yearlevel" value="">
        </td>
      </tr>
      <tr>
        <td>Email</td>
        <td>
          <input type="text" name="email" value="">
        </td>
      </tr>
      <tr>
        <td>Semester</td>
        <td>
          <select name="sem">
            <option value="1">1st Semester 2019-2020</option>
            <option value="2">2nd Semester 2019-2020</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="right">
          <button id="add_student">Add</button>
        </td>
      </tr>
    </table>
  </form>
</div>
<div id="registerStudent" class="modal">
  <span id="registerResult" class="sm alert">
  </span>
  <form id="register" onsubmit="return false">
    <table>
      <tr>
        <td colspan="100%" class="table-title">
          <h3>Register Student</h3>
        </td>
      </tr>
      <tr>
        <td>Student ID</td>
        <td>
          <input type="text" name="student_id" id="student_id" value="">
        </td>
      </tr>
      <tr>
        <td>Full Name</td>
        <td>
          <input type="text" name="full_name" id="full_name" value="">
        </td>
      </tr>
      <tr>
        <td>Course</td>
        <td>
          <input type="text" name="course" id="course" value="">
        </td>
      </tr>
      <tr>
        <td>Year</td>
        <td>
          <input type="number" name="year" id="year" value="">
        </td>
      </tr>
      <tr>
        <td>Semester</td>
        <td>
          <input type="number" name="sem2" id="sem2" value="">
          <input type="hidden" name="event_name" id="event_name" value="">
        </td>
      </tr>
      <tr>
        <td colspan="2" align="right">
          <button type="button" id="register-btn">
            Register
          </button>
        </td>
      </tr>
    </table>
  </form>
</div>

<div id="collect" class="modal">
  <span id="collect_result" class="sm error">
  </span>
  <form id="collection" onsubmit="return false">
    <table>
      <tr>
        <td colspan="100%" class="table-title">
          <h3>Collect</h3>
        </td>
      </tr>
      <tr>
        <td>Full Name</td>
        <td>
          <input type="text" name="full_name" value="" readonly><br>
          <input type="hidden" name="sid" value="">
        </td>
      </tr>
      <tr>
        <td>Student ID</td>
        <td>
          <input type="text" name="student_id" value="" readonly>
        </td>
      </tr>
      <tr>
        <td>Email</td>
        <td>
          <input type="text" name="receipt_email" value=""><br>
        </td>
      </tr>
      <tr class="se">
        <td>Size</td>
        <td>
          <select id="size" style="width: 100%;">
            <option id="xsmall">X-Small</option>
            <option id="small">Small</option>
            <option id="medium">Medium</option>
            <option id="large">Large</option>
            <option id="xlarge">X-Large</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Amount</td>
        <td>
          <input type="number" name="amount" value="" readonly>
          <input type="hidden" name="course" value="">
          <input type="hidden" name="year" value="">
          <input type="hidden" name="event" value="">
        </td>
      </tr>
      <tr>
        <td colspan="2" align="right">
          <button id="collect_student">Collect  </button>
        </td>
      </tr>
    </table>
  </form>
</div>

<style media="screen">
  #editEvent table tr td:first-of-type {
    width: 100px;
  }
</style>

<div id="editEvent" class="modal" style="max-width: 1000px;">
  <span id="editResult" class="sm error" style="display: none;">
  </span>
  <form id="edit_event" onsubmit="return false">
    <table>
      <tr>
        <td colspan="100%" class="table-title">
          <h3>Edit Event</h3>
        </td>
      </tr>
      <tr>
        <td>Event Name</td>
        <td>
          <input type="text" name="event_name" value=""><br>
          <input type="hidden" name="event_id" value=""><br>
        </td>
      </tr>
      <tr>
        <td>Payment Amount</td>
        <td>
          <input type="number" name="payment_amount" value="" step="any">
        </td>
      </tr>
      <tr>
        <td>Theme color</td>
        <td>
          <input type="color" name="theme_color" list="presetColors" value="#00adff" style="height: 30px; width: 100%;">
           <datalist id="presetColors">
             <option>#8f39c1</option>
             <option>#00adff</option>
             <!-- <option>#b1b1b1</option>/>
             <option>#ff9800</option>
             <option>#f44336</option>
             <option>#8bc34a</option> -->
           </datalist>
         </td>
      </tr>
      <!-- <tr>
        <td>Rate</td>
        <td>
          <input type="number" name="rate" value="" step="any">
        </td>
      </tr> -->
      <tr>
        <td>Start date</td>
        <td>
          <input type="date" name="start_date" value="" style="width: 100%;">
        </td>
      </tr>
      <tr>
        <td>End date</td>
        <td>
          <input type="date" name="end_date" value="" style="width: 100%;">
        </td>
      </tr>
      <tr>
        <td>Start time</td>
        <td>
          <input type="text" name="start_time" value="" style="width: 100%;">
          <br>
          <input type="checkbox" name="all_day" id="allday" value="true">
          <label for="allday">All day event</label>
        </td>
      </tr>
      <tr>
        <td>Enable registration</td>
        <td>
          <select name="reg-btn">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Short description</td>
        <td>
          <textarea name="short_desc" style="font-family: Roboto;font-size:1rem;resize: none;width:100%;height: 50px;"></textarea>
        </td>
      </tr>
      <tr>
        <td>Long description</td>
        <td style="max-width: 710px;">
          <div id="ldesc" style="background: #fff;font-family: Roboto;font-size:1rem;resize: none;width:100%;"></div>
        </td>
      </tr>

      <tr>
        <td colspan="2" align="right">
          <button id="_editEvent">Update Event</button>
        </td>
      </tr>
    </table>
  </form>
</div>


<div id="addEvent" class="modal" style="max-width: 590px;">
  <span id="addResult" class="sm error" style="display: none;">
  </span>
  <form id="add_event" onsubmit="return false">
    <table>
      <tr>
        <td colspan="100%" class="table-title">
          <h3>Add Event</h3>
        </td>
      </tr>
      <tr>
        <td>Event Name</td>
        <td>
          <input type="text" name="event_name" value=""><br>
          <input type="hidden" name="event_id" value=""><br>
        </td>
      </tr>
      <tr>
        <td>Description</td>
        <td>
          <textarea name="desc" rows="8" cols="50" style="font-family: Roboto;resize: vertical;"></textarea>
        </td>
      </tr>
      <tr>
        <td>Semester</td>
        <td>
          <select name="sem" style="width: 100%;">
            <option value="1">1</option>
            <option value="2">2</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Payment Amount</td>
        <td>
          <input type="number" name="payment_amount" value="" step="any">
        </td>
      </tr>
      <tr>
        <td>Date</td>
        <td>
          <input type="text" name="date" value="">
        </td>
      </tr>
        <td colspan="2" align="right">
          <button id="_addEvent">Add Event</button>
        </td>
      </tr>
    </table>
  </form>
</div>

<div id="fullreport" class="modal" style="max-width: 700px!important;">
</div>

<script src="/dashboard/js/pell.js"></script>

<script type="text/javascript">
  $("#register-btn").click(function() {
    $("#registerResult").show();
  })
  $("#_editEvent").click(function() {
    data={};
    data['action'] = "edit_event";
    $("form#edit_event input").each(function(){
      data[$(this).attr("name")] = $(this).val();
    });
    $("form#edit_event select").each(function(){
      data[$(this).attr("name")] = $(this).val();
    });

    $("form#edit_event textarea").each(function(){
      data[$(this).attr("name")] = $(this).val().replace(/(?:\r|\n|\r\n)/g, '<br>');
    });
    data['long_desc'] = $("#ldesc .pell-content").html();


    if($('input[name=all_day]').prop('checked')){
      data.all_day = 1
    } else {
      data.all_day = 0
    }
    $('.jquery-modal').scrollTop(0);
    $action(data,"#editResult","");
  })
  $("#_addEvent").click(function() {
    data={};
    data['action'] = "add_event";
    $("form#add_event input").each(function(){
      data[$(this).attr("name")] = $(this).val();
    });
    $("form#add_event select").each(function(){
      data[$(this).attr("name")] = $(this).val();
    });
    $("form#add_event textarea").each(function(){
      data[$(this).attr("name")] = $(this).val();
    });
    $action(data,"#addResult","");
  })
  $("#collect_student").click(function(){
    data = {};
    data['action'] = "collect";
    $("form#collection input").each(function(){
      data[$(this).attr("name")] = $(this).val();
    })
    data['date'] = "<?php echo date('F d, Y') ?>";
    $action(data,"#collect_result","");
  })

  $("#add_student").click(function(){
    $cat = $("#cat").val();
    $sem = $("#sem").val();
    $showing = $("#showing").val();
    $("#student_list").load("student_list.php?sem="+$sem+"&cat="+$cat+"&showing="+$showing, {start:0});
  });
  var editor = window.pell.init({
    element: document.getElementById('ldesc'),
    defaultParagraphSeparator: 'p',
    onChange: ()=>{}
  })
</script>
