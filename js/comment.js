  opened = [];
  limit = 4;
  updateReplies = function(e,i,b) {
    $.ajax({
      method: "POST",
      url: "/replies.php",
      data: {commentID: e, update: true, action: false},
      beforeSend: function() {
        $(`#r-${e}`)
        .css('bottom','0')
        .html(`<img src="/dashboard/img/spinner.svg" alt="Posting reply..." style="width:40px!important;"/>`)
        .show();
      },
      success: function(data2) {
        $(`#r-${e}`).hide();
        i.html(data2);}
    })
    opened.push(e);
  }
  updateAllReplies = function() {
    $("button[comment-trigger-id]").each(function() {
        id = $(this).attr("comment-trigger-id");
        res = $("div[reply-msg-id="+id+"]");
        updateReplies(id,res,false);
    })
  }
  showMore = function() {
    $("button[comment-trigger-id]").off();
    $("a.show-more").off();
    $("button[comment-trigger-id]").click(function(){
      $(`#r-${$(this).attr('comment-trigger-id')}`).show();
      var div = document.createElement("div");
      if($("textarea[reply-compose-id="+id+"]").val()=="" || $("textarea[reply-compose-id="+id+"]").val()==null){
        $(`#r-${id}`).hide();
        return;}
      id = $(this).attr("comment-trigger-id");
      msg = $("textarea[reply-compose-id="+id+"]").val().replace(/\n/g, "breaklineee");
      div.innerHTML = msg;
      msg = div.innerText;
      msg = msg.replace(/breaklineee/g,"<br>");
      if(msg.length > 200){
        msg = msg.substr(0,200)+"<span style=\"display:none\">"+msg.substr(200)+" <a href=\"showloess\" onclick=\"event.preventDefault();$(this).parent().next(0).toggle();$(this).parent().toggle()\">show less</a>\
        </span>"+" <a href=\"showmore\" style=\"display: block;\" onclick=\"event.preventDefault();$(this).parent().children(0).toggle()\">see more</a>";
      }
      res = $("div[reply-msg-id="+id+"]");
      $.ajax({
        method: "POST",
        url: "/replies.php",
        data: {commentID: id, message: msg, action: "insert", update: true},
        success: function(data) {
          res.html(data);
        }
      })
      $("textarea[reply-compose-id="+id+"]").val('');
    })
    $("a.show-more").click(function(){
      event.preventDefault();
      limit = $(this).text()==='Hide comments'?limit-4:limit+4;
      updatecomments($(this).parent().parent().attr("ref"),$(this).attr("href"));
    })
    $(".c-link[cidb]").each(function() {
      id = $(this).attr("cidb");
      if(localStorage["open-"+id]=="true"){
        $("div[reply-msg-id="+id+"]").show();
        $("div[reply-id="+id+"]").show();
        updateReplies(id,$("div[reply-msg-id="+id+"]"),false);
      }
    })
  }
  updatecomments = function(e,i) {
    $.ajax({
      method: "POST",
      url: "/comment_template.php",
      data: {cid: e,grab: true,showing: i},
      success: function(data){
        $("#c-"+e).html(data);
        showMore();
      }
    })
  }
  updateAllComments = function(){
    $(".comment-btn").each(function() {
      id = $(this).attr("ref");
      container = $(".comments[ref="+id+"]");
      showing = $("a#showing_"+id).attr("showing");
      updatecomments(id,showing);
    })
  }
   $("div.comments").each(function() {
     id = $(this).attr("ref");
     updatecomments(id,limit);})
   $(".comment-btn").click(function() {
     if($(".comment-msg[comment-msg="+$(this).attr("ref")+"]").val()=="" || $(".comment-msg[comment-msg="+$(this).attr("ref")+"]").val()==null){return;}
     var div = document.createElement("div");
     id=$(this).attr("ref");
    msg = $(".comment-msg[comment-msg="+$(this).attr("ref")+"]").val().replace(/\n/g,"breaklineee");
    div.innerHTML = msg;
    msg = div.innerText;
    msg = msg.replace(/breaklineee/g,"<br>");
    if(msg.length > 200){
      msg = msg.substr(0,200)+"<span style='display:none'>"+msg.substr(200)+" <a href='showloess' onclick='event.preventDefault();$(this).parent().next(0).toggle();$(this).parent().toggle()'>show less</a>\
      </span>"+" <a href='showmore' onclick='event.preventDefault();$(this).parent().children(0).toggle()'>see more</a>";
    }
    name = "<div class='col-100'>"+"<b><?php echo $_SESSION['account']['full_name'] ?></b>"+"</div>";
    msg = "<div class='col-100'><p>"+msg+"</p></div>";
    $(".comment-msg[comment-msg="+$(this).attr("ref")+"]").val("")
    r = $("div[comment="+id+"]");
    $.ajax({
      url: "/comment_template.php",
      method: "POST",
      data: {message: msg,comment: true,grab:true,cid: id},
      async: true,
      beforeSend: function(){
        $(`#p-${id}`)
        .html(`<img src="/dashboard/img/spinner.svg" alt="Posting reply..." style="width:40px!important;"/>`)
        .show();
      },
      success: function(data) {
        $(`#p-${id}`).html('Waiting for approval').fadeIn(3000).delay(2500).fadeOut("slow");

        updatecomments(id,limit)},
      error: function(){
        r.html("<div class='col-100'>Something went wrong. Please try again later.</div>"+r.html());
      }
    })
  })
  $(window).on('beforeunload', function() {
    localStorage.clear();
  });

  // setInterval(function(){
  //   updateAllComments();
  //   updateAllReplies();
  // },5000);)
