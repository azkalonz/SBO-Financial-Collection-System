$action = function(data,suc,disp) {
  data["disp"] = disp;
  $.ajax({
    method: "POST",
    url: "../api/send.php",
    data: data,
    beforeSend : function() {
      $(suc).html("Please wait...");
    },
    success: function(res) {
      if(res != "Success"){
        $(suc).html(res);}
      else{
        if(data.action=="add"){
          $(suc).html("Successfully added!");
          if(disp!=0)
          $(disp).load("student_list.php", {start:0});
        } else if (data.action=="register"){
          $(suc).html("Registered!");
          $(disp).html("Registered");
        } else if (data.action=="student-login"){
          $(suc).html(res);
        }
      }
      $(suc).show();
    },
    error: function(data) {
      $(suc).html(data);
    }
  })
}
