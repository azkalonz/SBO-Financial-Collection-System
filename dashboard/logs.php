<?php
require("../config/config.php");
include("../api/func.php");
secure(['admin-only'=>true]);
  $item = '';
  $prev='';
  $counter=0;
  $c;
  $limit = $_GET['limit'];
  if(isset($_GET["search"])){
    $item = str_replace('_',' ',$_GET["search"]);
    $item = str_replace(',','\,',$item);
  }
  $sql = sprintf("SELECT * FROM psits_logs WHERE description LIKE '%s' OR user LIKE '%s' OR action LIKE '%s' OR _date LIKE '%s' OR _time LIKE '%s' ORDER BY id DESC LIMIT {$limit} ",
"%".$item."%","%".$item."%","%".$item."%","%".$item."%","%".$item."%");

  if(isset($_POST['filter'])){
    $sql = "SELECT * FROM psits_logs WHERE (description LIKE '%$item%' OR user LIKE '%$item%' OR _date LIKE '%$item%' OR _time LIKE '%$item%' OR action LIKE '%$item%') AND ( ";
    foreach ($_POST['filter'] as $key => $value) {
      $sql.= "action LIKE '%$value%'";
      if($key<sizeof($_POST['filter'])-1)
      $sql.= " OR ";
      else
      $sql.= ") ";
    }
    $sql .= "ORDER BY id DESC LIMIT ".$limit;
  }
  $logs = $con->prepare($sql);
  $logs->execute();
  $logs = $logs->fetchAll(PDO::FETCH_ASSOC);
 ?>
 <div class="col-100 text-right">
   Showing: <?php echo sizeof($logs) ?>
 </div>
<?php if(sizeof($logs)<=0){ ?>
  <div class="flex align-center justify-center" style="height: 100%; width: 100%;">
    Sorry, <?php echo $item!=""?$item." was not found.":"nothing was found." ?>
  </div>
<?php } ?>
<?php foreach ($logs as $key => $value): ?>
  <?php $prev = $prev!=$value["user"]?"not":$prev ?>
  <?php $counter += $prev!=$value["user"]?1:0; ?>
  <?php $value["action"] = strtoupper($value["action"]); ?>
<?php if ($value['action']!='CASH OUT'): ?>
  <?php $id = explode("->",$value["description"])[1] ?>
  <?php else: ?>
    <?php $id = $value["description"] ?>
<?php endif; ?>
 <!-- style="position:<?php echo $prev==$value['user']?'absolute':'relative' ?>"  -->
<div class="flex col-100 log-item flex-wrap <?php echo $prev=="not"?"stacked":"child" ?>" id="<?php echo $value["user"] ?>" <?php echo $prev=="not"?'parent=\''.$counter.'\'':'child=\''.$counter.'\'' ?>>
  <?php if ($prev=="not"): ?>
    <div class="total-stack" stack-id="<?php echo $counter ?>" style="display: none;"></div>
  <?php endif; ?>
  <div class="_profile">
    <img src="img/prof.png" alt="" width="100%">
  </div>
  <div class="log-info" action="logs" oid="<?php echo getOID($value['user']) ?>">
    <div style="margin-bottom: 12px;">
      <b><a href="/dashboard/officer/?action=officer&all&id=<?php echo getOID($value['user']) ?>" target="_blank"><?php echo fullName($value["user"]) ?></a></b>
      <span style="opacity: 0.5; font-size: 0.7em; float: right;">
        <i class="fal fa-clock"></i> <?php echo $value["_date"] ?>  <?php echo $value["_time"] ?>
      </div>
    </span>
    <p style="color: #3d3d3d">
       <b style="color: <?php echo ($value['action']=='ADDED')?'#ad54c4':'#2bacfe' ?>">
         <?php echo $value["action"] ?></b>
         <?php if ($value['action']=='CASH OUT'): ?>
           <?php echo 'Php'.$value['description'] ?>
         <?php endif; ?>
         <b><?php echo getFullName($id) ?></b>
         <?php if ($value['action']!='MODIFIED' && $value['action']!='CASH OUT'): ?>
            (<?php echo trim($id) ?>)
         <?php endif; ?>
           Event <?php echo getEventName($value['event_id']) ?>
    </p>
  </div>
</div>
<?php $prev = $value["user"]; ?>
<?php endforeach; ?>
<div class="log-item">
  <button type="button" name="button" onclick="limit+=20;
  $('#search-log').keyup();
  ">Load more</button>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $("div[parent]").each(function() {
      total = function(e){
        c=0;
        $("div[child]").each(function(){
          if(e.attr("parent")==$(this).attr("child")){c++;}
        })
        return c;
      }
      if(total($(this))>0 && total($(this))<9){
        $("div[stack-id="+$(this).attr("parent")+"]").show();
        $("div[stack-id="+$(this).attr("parent")+"]").html(total($(this))+1)
      } else if(total($(this))>8){
        $("div[stack-id="+$(this).attr("parent")+"]").show();
        $("div[stack-id="+$(this).attr("parent")+"]").css("font-size","0.7rem");
        $("div[stack-id="+$(this).attr("parent")+"]").html("9+")
      }
      if($(this).next().attr("child")==null)
        $(this).removeClass("stacked");
    })

    $("div[parent]").click(function(){
      id = $(this).attr("parent");
      $("div[child]").each(function() {
        if($(this).attr("class").indexOf("child")>=0){
          if($(this).attr("child")==id){
            $(this).removeClass("child");
            $(this).addClass("highlight");
            $(this).addClass("indent");
          }else {
            $(this).removeClass("indent");
            $(this).addClass("child");
            $(this).removeClass("highlight");
          }
        } else {
          $(this).removeClass("indent");
          $(this).addClass("child");
          $(this).removeClass("highlight");
        }
      })
    })
  })
</script>
