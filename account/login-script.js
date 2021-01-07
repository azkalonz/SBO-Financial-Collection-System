$(document).ready(function() {
  menu = $("#menu");
  let options = function() {
    x = event.pageX-231;
    y = event.pageY-177;
    if(x>$(".login-container").width()-menu.width()-30){
      x-=menu.width()+30
    }
    if(y>$(".login-container").height()-menu.height()-10){
      y-=menu.height()+10
    }
    menu.css({"top":y,"left":x});
    menu.show();
  }
  $(".login-container").click(function() {
    if(event.target.parentElement.id!="menu")
    menu.hide();
  })
  $(".login-container").contextmenu(function(event){
    event.preventDefault();
    options();
  })
  $animate = function(name, prop, value, state){
    $(name).css("transition",prop+" "+state+"s ease-out");
    $(name).css(prop,value);
  }
    containerColor = getComputedStyle(document.querySelector("#login-trigger-container")).getPropertyValue('background');
  $(".login-trigger").click(function() {
    $(".error").hide();
    $("input").val("");
    container = $("#login-trigger-container");
    form = $("#login-form");
    studentform = $("#student-login-form");
    teacherform = $("#teacher-login-form");
    logincontainer = $(".login-container");
    teacher = $("#teacher-login");
    student = $("#student-login");
    $animate(".slide-right", "transform", "translateX(200px)", 0);
    $animate(".slide-left", "transform", "translateX(-200px)", 0);
    $animate(".fade", "opacity", "0", 0);
    if($("#login-trigger-container").css("left")=="0px"){
      container.css("background-color",containerColor);
      logincontainer.css("border","2px solid #06ace3");
      container.css("width","2600px");
      container.css("left","550px");
      container.css("z-index","132");
      teacher.css("z-index","99999");
      teacherform.hide();
      studentform.show();
      form.css("left","0px");
      container.on("transitionend",function() {
        container.css("left","");
        container.css("right","0");
      });
    }else{
      container.css("background-color","#9f3cb1");
      logincontainer.css("border","2px solid #9f3cb1");
      container.css("width","2600px");
      container.css("right","550px");
      container.css("z-index","132");
      student.css("z-index","99999");
      studentform.hide();
      teacherform.show();
      form.css("left","250px");
      container.on("transitionend",function() {
        container.css("right","");
        container.css("left","0");
      })
    }
    that = $(this);
    container.on("transitionend",function() {
      $animate(".fade", "opacity", "1", .3);
      $animate(".slide-right", "transform", "translateX(0)", .5);
      $animate(".slide-left", "transform", "translateX(0)", .5);
      container.css("width","250px");
      container.css("z-index","0");
      teacher.css("z-index","0");
      student.css("z-index","0");
    })
  });
})
