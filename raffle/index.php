<?php
  function getName($id){
    global $con;
    $query = $con->prepare("SELECT full_name FROM ccs_students WHERE student_id = $id");
    $query->execute();
    return $query->fetch()[0];
  }
  $students = $con->prepare("SELECT student FROM psits_events WHERE id = 2");
  $students->execute();
  $students = explode(', ',$students->fetch()[0]);
  $student = '[';
  foreach ($students as $key => $value) {
    foreach (explode('->',$value) as $key2 => $value2) {
      if($key2 == 0){
        $student .= '"'.str_replace('�','Ñ',getName(explode('->',$value)[0])).'"';
        if($key<sizeof($students)-1){
          $student .= ', ';
        }
      }
    }
  }
  $student .= ']';
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <style media="screen">
      .blink {
        animation: blink 0.2s linear infinite;
      }
      @keyframes blink {
        from {color: #19b907;}
        to {color: red;}
      }
    </style>
  </head>
  <body>

    <div class="flex col-100 justify-center flex-wrap" style="align-content: center;min-height: 20vh;">
      <div class="col-100 text-center">
        <b>
          <h1 id="result"></h1>
        </b>
      </div>
      <div class="col-100 align-center flex">
        <button type="button" name="button" class="col-100" onclick="start()">PICK A WINNER</button>
      </div>
    </div>
    <script type="text/javascript">
    let student = <?php echo $student ?>;
    let winner;
    let speed = 50;
    let shuffling;

    function start(){
       winner = Math.floor(Math.random()*student.length)
    display()
    }
    function display(){
      shuffler();
       $(window).on('keyup',function(){
      switch(event.key) {
         case 'ArrowLeft': slower();break;
          case 'ArrowRight': faster();break;
          case 'ArrowUp': showWinner();break; }
       })
  }
  function shuffler(){
     shuffling = setInterval(()=>{
       $('#result').html(`${student[Math.floor(Math.random()*student.length)]}!`)
     },speed)
    }
    function faster(){
        speed -= 10
        clearInterval(shuffling)
        shuffling  = setInterval(()=>{
          $('#result').html(`${student[Math.floor(Math.random()*student.length)]}`)
        },speed)
    }
   function showWinner(){
      setTimeout(()=>{ clearInterval(shuffling)
        student[winner] = student[winner].toUpperCase()
        $('#result').html(`CLAIM YOUR PRIZE <a class="blink" style="color: #19b907">${student[winner]}</a>!!!`)
      },Math.floor((Math.random()*10000)+6000))
    }

    function slower(){
      speed += 10
      clearInterval(shuffling)
      shuffling = setInterval(()=>{
        $('#result').html(`${student[Math.floor(Math.random()*student.length)]}!!!`)
      },speed)
    }

    </script>
  </body>
</html>
