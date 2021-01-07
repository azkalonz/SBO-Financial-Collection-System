  const content = $("#contextmenu");
  menu = function(e,i){
    let x = event.pageX, y = event.pageY;
    content.css({"top":y,"left":x});
    if(i=="expenses"){
      event.preventDefault();
      content.html("<ul>\
      <li><a href='#editEvent' rel='modal:open'><i class='fal fa-pencil-alt'></i>Edit event</a></li>\
      <li><a href='#addEvent' rel='modal:open'><i class='fal fa-plus-circle'></i>Add event</a></li></ul>");
      $("#editEvent input[name=event_name]").val(e.getAttribute("event-name"));
      $("#editEvent input[name=event_id]").val(e.getAttribute("event-id"));
      // $("input[name=rate]").val(e.getAttribute("rate"));
      if(e.getAttribute('s-price')=="true"){
        $("#editEvent input[name=payment_amount]").attr('type','text');
        $("#editEvent input[name=payment_amount]").attr('placeholder','x-small, small, medium, large, x-large');
      } else {
        $("#editEvent input[name=payment_amount]").attr('placeholder','');
        $("#editEvent input[name=payment_amount]").attr('type','number');
      }
      $("#editEvent input[name=payment_amount]").val(e.getAttribute("amount"));
      $("#editEvent input[name=start_date]").val(e.getAttribute("date"));
      $("#editEvent input[name=end_date]").val(e.getAttribute("date"));
      $("#editEvent input[name=start_time]").val(e.getAttribute("start-time"));
      $("#editEvent select[name=reg-btn]").prop('selectedIndex',e.getAttribute("reg-btn"));
      $("#editEvent input[name=theme_color]").val(e.getAttribute("theme"));
      $("#editEvent input[name=theme_color]").val(e.getAttribute("theme"));
      $.ajax({
        method: 'GET',
        url: '/api/send.php?action=desc&id='+e.getAttribute('event-id'),
        success: function(data){
          let e = JSON.parse(data);
          $("#ldesc .pell-content").html(e.long_desc);
          $("#editEvent textarea[name=short_desc]").val(e._desc);
        }
      })
      checked = e.getAttribute("allday")==1?true:false;
      $("#editEvent input[name=all_day]").prop('checked',checked);
      content.show();
    }
    if(e.getAttribute("action")=="logs"){
      event.preventDefault();
      content.html(`<ul>\
      <li><a href='/dashboard/officer/?action=officer&all&id=${e.getAttribute('oid')}' target="_blank"><i class='fa fa-star'></i>View Officer</a></li>
      </ul>`);
      content.show();
    }
  }
  $(document).click(function(){
    if(!$(".blocker").is(":visible")){
      $(".error").hide();
      $(".alert").hide();
      $(".success").hide();
    }
  })
  closeContext = function(){
    try {
    if(event.target.parentElement.parentElement.parentElement.id!="contextmenu")
    content.hide();} catch(e){
    }}
  $(".right-pane").click(function(){closeContext();})
  $(".left-pane").click(function(){closeContext();})
  $(document).contextmenu(function(event){
    let el = event.target.parentElement;
    if(el.getAttribute("role")=="row" || el.getAttribute("action")!=null)
    menu(el,el.getAttribute("action"));
  })
  // $notif = new Notif({
  //   container: $("#overview-notif"),
  //   interval: 3000,
  //   get: "activity",
  //   url: "notif/overview.php",
  // })
  // $liquidation = new Notif({
  //   container: $("#liquidation-notif"),
  //   interval: 3000,
  //   get: "liquidation",
  //   url: "notif/overview.php",
  // })
  // $events = new Notif({
  //   container: $("#event-notif"),
  //   interval: 3000,
  //   get: "event",
  //   url: "notif/overview.php",
  // })
  container = $("#dashboard-view");
  container.fadeOut();
  container.load("welcome.php",function(){
    $("#loading-content").fadeOut();
    container.fadeIn();
  });
  $("#loading-content").show();
  // $(".tab").on("click", function() {
  //   switch($(this).attr("notification")){
  //     case "activity" : $notif.refresh();break;
  //     case "liquidation": $liquidation.refresh();break;
  //   }
  //   event.preventDefault();
  // })
  $(".tab").click(function() {
    link = $(this).attr("link");
    container.hide();
    $("#loading-content").show();
    $(".tab").removeClass("selected");
    $(this).addClass("selected");
      container.load(link,{},function(){
        $("#loading-content").hide();
        container.show();
      });
    $('#page-label').text($(this).text());
  })
  $('.tab').first().click();
  $(window).on('click',function(){
    if($('.jquery-modal.blocker.current').length>1){
      for(var i=1; i<$('.jquery-modal.blocker.current').length; i++){
        $('.jquery-modal.blocker.current')[i].remove();
      }
    }
  })
  options = function(d){
    let settings = {duration: 200}
    if($(d).css('display')=='none'){
      $(d).slideDown(settings);
      return;
    }
    $(d).slideUp(settings);
  }
