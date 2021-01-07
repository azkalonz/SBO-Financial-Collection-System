<?php
  session_start();
  date_default_timezone_set("Asia/Manila");
  header("Content-Type: text/html; charset=ISO-8859-1");
  require __DIR__.'\../vendor/autoload.php';
  $dotenv = Dotenv\Dotenv::create(__DIR__.'\..\variables');
  $dotenv->load();

  define('PORT',getenv('PORT')!=null?getenv('PORT'):80);
  $dbname = getenv('DB_NAME');
  $dbhost = getenv('DB_HOST');
  $dbuser = getenv('DB_USER');
  $dbpass = getenv('DB_PASS');
  // $webhost = sprintf("http://localhost:%s/",PORT);
  try {
    $con = new PDO("mysql:host={$dbhost};dbname={$dbname}",$dbuser,$dbpass);
    if(isset($_SESSION['start_time']) && isset($_SESSION['account'])){
      if(time()-$_SESSION['start_time']>900 && $_SESSION['account']['access']<30)
      header('location: /logout.php');
    }
    $_SESSION['start_time'] = time();
  } catch(Exception $e) {
    echo '<h4 style="font-family: Century Gothic;">Could not establish mysql connection in '.$dbhost.'</h4>';
    exit;
            // <b>DB name:</b> {$dbname}<br>
            // <b>DB host:</b> {$dbhost}<br>
            // <b>DB user:</b> {$dbuser}<br>
            // <b>DB pass:</b> {$dbpass}<br><br>.$e->getMessage();
  }
  // function updateFunds() {
  //   global $con;
  //   $reg = $con->prepare("SELECT * FROM psits_events");
  //   $reg->execute();
  //   $reg = $reg->fetchAll(PDO::FETCH_ASSOC);
  //   // foreach ($reg as $key => $value) {
  //   //   $total_reg = sizeof(explode(", ",$value["student"]))-1;
  //   //   $total_reg *= $value["price"];
  //   //   $query = $con->prepare("UPDATE psits_events SET fund = {$total_reg} WHERE id = {$value['id']}");
  //   //   $query->execute();
  //   // }
  // }
  // updateFunds();
  function secure($options=null){
    if(!isset($_SESSION['account']['password'])){
      header('location: /account/login.php?sec&ref='.str_replace('&','__',$_SERVER['REQUEST_URI']));
    }
    if($options['admin-only']){
      if($_SESSION['account']['access']<30){
      header('location: /account/login.php?sec&ref='.str_replace('&','__',$_SERVER['REQUEST_URI']));
      exit;}
    }
  }
  if(isset($_POST['settings'])){
    $settings = $con->prepare("SELECT * FROM settings");
    $settings->execute();
    $settings = $settings->fetchAll(PDO::FETCH_ASSOC);
    $settings = json_decode(json_encode($settings),true)[0];
    switch($_POST['action']){
      case 'design':
      $colors = json_decode($settings['colors'],true);
      $labels = json_decode($settings['labels'],true);
      $images = json_decode($settings['images'],true);

      $images['logo'] = $_POST['image'][0];
      $images['favicon'] = $_POST['image'][1];
      $labels['text-logo'] = $_POST['label'][0];
      $labels['abbr'] = $_POST['label'][1];
      $colors['nav-bar'] = $_POST['color'][0];
      $colors['text-logo'] = $_POST['color'][1];
      $colors['left-panel'] = $_POST['color'][2];
      $colors['right-panel'] = $_POST['color'][3];
      $colors['page-label'] = $_POST['color'][4];
        $colors = json_encode($colors);
        $labels = json_encode($labels);
        $priv = '';
        $images = json_encode($images);
        $query = $con->prepare("UPDATE settings SET colors = '$colors', labels = '$labels', images = '$images'");
        $query->execute();
        header('location: /dashboard');
      break;
      case 'privs':
        $privileges = json_decode($settings['privileges'],true);
        $privileges[$_POST['type']] = $_POST['priv'];
        $priv = json_encode($privileges);
        $query = $con->prepare("UPDATE settings SET privileges = '$priv'");
        $query->execute();
        header('location: /dashboard');
        break;
      case 'department':
        $department = json_decode($settings['department'],true);
        $department['college'] = $_POST['college'];
        $department['university'] = $_POST['university'];
        $department = json_encode($department);
        $query = $con->prepare("UPDATE settings SET department = '$department'");
        $query->execute();
        header('location: /dashboard');
        break;
      case 'web':
        $web = json_decode($settings['web'],true);
        $web['domain'] = $_POST['domain'];
        $web['title'] = $_POST['webtitle'];
        $web = json_encode($web);
        $query = $con->prepare("UPDATE settings SET web = '$web'");
        $query->execute();
        header('location: /dashboard');
      default: header('location:/dashboard');
    }
  }
  $settings = $con->prepare("SELECT * FROM settings ORDER BY id DESC LIMIT 1");
  $settings->execute();
  $settings = $settings->fetchAll(PDO::FETCH_ASSOC);
  $settings = json_decode(json_encode($settings),true)[0];
  $colors = json_decode($settings['colors'],true);
  $labels = json_decode($settings['labels'],true);
  $privileges = json_decode($settings['privileges'],true);
  $images = json_decode($settings['images'],true);
  $webmail = json_decode($settings['webmail'],true);
  $department = json_decode($settings['department'],true);
  $web = json_decode($settings['web'],true);
  $webtitle = $web['title'];
  $webhost = $web['domain'];
?>
