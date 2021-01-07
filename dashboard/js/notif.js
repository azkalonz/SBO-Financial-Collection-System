// const Notif = function(x){
//   this.x = x;
//   this.container = x.container;
//   let update = function(){
//     $.ajax({
//       method: "GET",
//       async: true,
//       url: x.url+"?action="+x.get,
//       success: (y)=>{
//         console.log(y);
//         y = y.length>0?JSON.parse(y):0;
//         // if(y==0){$("#notif-container").html('');return;}
//         let anim = false;
//         // let size = y.info.length;
//         if(localStorage['size']==null || localStorage['size']==''){localStorage['size']=size;}
//         if(localStorage['closed']==null){localStorage['closed']="";}
//         console.log(size+" "+localStorage['size']);
//         if(size>parseInt(localStorage['size'])){
//           localStorage['size'] = size;
//           anim = true;
//         } else {anim = false;}
//         if(y.count>0){
//           $("#notif-container").show();
//           $("#notif-container").html('');
//           for(var i=0; i<size; i++){
//             if(localStorage['closed'].indexOf(y.info[i].id)>=0){continue;}
//             if(i>=4){break;}
//             $("#notif-container").html(`
//               <div class='flex justify-start align-start notif-toast flex-wrap'>
//                 <header style="position:absolute;right: 5px;top:2px;">
//                   <span style="font-size: 15px;cursor:pointer;text-align:center;line-height 17px;width: 17px; height: 17px;background-color: #000;color:#fff;box-shadow:0 0 0 2px #000;border-radius: 50%; display: block;" onclick="$(this).parent().parent().hide();removeNotif(${y.info[i].id})">
//                     <a style="font-size:1em;">&times;</a>
//                   </span>
//                 </header>
//                 <div class='icon' style='color: #763ab7;padding-top: 7px;font-size: 27px;'>
//                   ${y.info[i].action.indexOf('collected')>=0?"<i class='fa fa-bell'></i>":"<i class='fa fa-user'></i>"}
//                 </div>
//                 <div class='info'>
//                   <h3>${y.info[i].user}</h3>
//                   <p>${y.info[i].action}
//                   ${y.info[i].action.indexOf('collected')>=0?' from ':' student '}
//                   ${y.info[i].description}</p>
//                 </div>
//               </div>
//               `+$("#notif-container").html());
//           }
//           if(anim){
//             $(".notif-toast").last().addClass("anim");
//             $(".notif-toast").last().css("margin-bottom",(-$(".notif-toast").last().height())+"px");
//             $(".notif-toast").last().animate({marginBottom:"0px"})
//           }
//         } else {
//           // $("#notif-toast").hide();
//         }
//         if(y.count>9){
//           y.count = "9+";
//         }
//         x.container.html(y.count);
//         updateNotifs();}
//     })
//   }
//   removeNotif = (e)=>{
//     localStorage['closed']+=e+", ";
//   }
//   update();
//   setInterval(()=>{
//     update();
//     updateNotifs();
//   },x.interval);
//   updateNotifs = function() {
//     $(".notif").each(function() {
//       if($(this).html()=="")
//       $(this).addClass("h");
//       else
//       $(this).removeClass("h");
//     })
//   }
//   updateNotifs();
// }
// Notif.prototype.refresh = function() {
//     $.ajax({
//       method: "GET",
//       async: true,
//       url: this.x.url+"?action="+this.x.get+"&update",
//       success: (y)=>{
//         y = y.length>0?JSON.parse(y):0;
//         localStorage['size']='';
//         if(y==0){
//           this.x.container.html('');return;}
//         this.x.container.html(y.count)}
//     })
// }
