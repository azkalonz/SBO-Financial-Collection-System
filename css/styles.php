<link rel="icon" href="<?php echo $images['favicon'] ?>" type="image/png">
<link rel="stylesheet" type="text/css" href="/css/flex.css"/>
<?php if (isset($_SESSION['darkmode'])): ?>
  <?php if ($_SESSION['darkmode']=='true'): ?>
      <link rel="stylesheet" type="text/css" href="/css/light.css"/>
    <?php else: ?>
      <link rel="stylesheet" type="text/css" href="/css/dark.css"/>
  <?php endif; ?>
  <?php else: ?>
    <link rel="stylesheet" type="text/css" href="/css/light.css"/>
<?php endif; ?>

<link rel="stylesheet" type="text/css" href="/css/psits-main-style.css"/>
<link rel="stylesheet" type="text/css" href="/dashboard/fa-old/css/fontawesome.css"/>
