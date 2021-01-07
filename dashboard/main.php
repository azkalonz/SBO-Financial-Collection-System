<div class="flex col-100 justify-space-between">
  <div class="left-pane" style="background-color: <?php echo $colors['left-panel'] ?>">
    <div class="wrap flex flex-wrap justify-center">
      <div class="logo logo-height flex align-items-center justify-center">
        <a href="../" style="color: #e0e0e0;" class="flex align-items-center">
          <img src="<?php echo $images['logo'] ?>" width="70" alt="">
          <h1 class="lg-color" style="background-image: linear-gradient(<?php echo $colors['text-logo'] ?>,<?php echo $colors['text-logo'] ?>,#808080);"><?php echo $labels['text-logo'] ?></h1>
          <!-- <img src="img/logo.svg" alt="" width="110"> -->
        </a>
      </div>
      <!-- <div class="user flex justify-space-between align-center">
        <div class="profile flex justify-center">
          <i class="fal fa-user" style="font-size: 2rem;color: #fff;"></i>
        </div>
        <div class="user-info">
          <a><?php echo $_SESSION["account"]["full_name"] ?></a><br>
          <a><?php echo $_SESSION["account"]["position"] ?></a>
        </div>
      </div> -->
      <div class="links">
        <ul>
          <li link="welcome.php" class="tab selected" notification="activity">
            <span>
              <i class="fal fa-th-list"></i>
            </span>
            <span>
              <a>Dashboard</a>
            </span>
            <span>
            </span>
          </li>
          <?php if ($privileges[$_SESSION['account']['type']][0]): ?>
          <li link="events.php" class="tab" notification="event">
            <span>
              <i class="fal fa-calendar-alt"></i>
            </span>
            <span>
              <a>Register</a>
            </span>
            <span>
            </span>
          </li>
          <?php endif; ?>
          <?php if ($privileges[$_SESSION['account']['type']][1]): ?>
          <li link="collections.php" class="tab" notification="collection">
            <span>
              <i class="fal fa-usd-circle"></i>
            </span>
            <span>
              <a>Fees</a>
            </span>
            <span>
            </span>
          </li>
          <?php endif; ?>
          <?php if ($privileges[$_SESSION['account']['type']][2]): ?>
          <li link="expenses/index.php" class="tab">
            <span>
              <i class="fal fa-chart-bar"></i>
            </span>
            <span>Expenses</span>
            <span>
            </span>
          </li>
          <?php endif; ?>
          <?php if ($privileges[$_SESSION['account']['type']][3]): ?>
          <li link="overview.php" class="tab selected" notification="activity">
            <span>
              <i class="fal fa-th-list"></i>
            </span>
            <span>
              <a>Events</a>
            </span>
            <span>
            </span>
          </li>
          <?php endif; ?>
          <?php if ($privileges[$_SESSION['account']['type']][4]): ?>
          <li link="./logger.php" class="tab">
            <span>
              <i class="fal fa-chart-bar"></i>
            </span>
            <span>Logs</span>
            <span>
            </span>
          </li>
          <?php endif; ?>
          <?php if ($privileges[$_SESSION['account']['type']][7]): ?>
          <li link="reports/index.php" class="tab">
            <span>
              <i class="fal fa-file-alt"></i>
            </span>
            <span>Reports</span>
            <span>
            </span>
          </li>
          <?php endif; ?>
          <?php if ($privileges[$_SESSION['account']['type']][5]): ?>
          <li link="./note.php" class="tab">
            <span>
              <i class="fal fa-pencil"></i>
            </span>
            <span>Notes</span>
            <span>
            </span>
          </li>
          <?php endif; ?>
          <?php if ($privileges[$_SESSION['account']['type']][6]): ?>
          <li link="student/comments.php" class="tab">
            <span>
              <i class="fal fa-comment"></i>
            </span>
            <span>Comments</span>
            <span>
            </span>
          </li>
          <?php endif; ?>
          <?php if ($privileges[$_SESSION['account']['type']][8]): ?>
            <li link="mailer.php" class="tab">
              <span>
                <i class="fal fa-envelope"></i>
              </span>
              <span>Compose</span>
              <span>
              </span>
            </li>
          <?php endif; ?>
          <li link="myaccount/index.php" class="tab">
            <span>
              <i class="fal fa-user"></i>
            </span>
            <span>My Account</span>
            <span>
            </span>
          </li>
          <?php if ($privileges[$_SESSION['account']['type']][6]): ?>
          <li link="users.php" class="tab">
            <span>
              <i class="fal fa-users"></i>
            </span>
            <span>Everyone</span>
            <span>
              <!-- <a style="font-size:0.7rem;opacity:0.6;white-space:pre;margin-left:-30px;">(in progress)</a> -->
            </span>
          </li>
          <?php endif; ?>
          <?php if ($_SESSION['account']['access']==40): ?>
            <li link="settings.php" class="tab">
              <span>
                <i class="fal fa-cog"></i>
              </span>
              <span>Settings</span>
              <span>
                <!-- <a style="font-size:0.7rem;opacity:0.6;white-space:pre;margin-left:-30px;">(in progress)</a> -->
              </span>
            </li>
        <?php endif; ?>
          <li style="cursor: pointer;" onclick="window.location='/logout.php'">
            <span>
              <i class="fal fa-sign-out-alt"></i>
            </span>
            <span>Logout</span>
            <span>
            </span>
          </li>
          <!-- <li link="events.php" class="tab">
            <span>
              <i class="fal fa-file"></i>
              <sup id="report-notif" class="notif"></sup>
            </span>
            <span>Report</span>
            <span>
            </span>
          </li> -->
          <!-- <li link="events.php" class="tab">
            <span>
              <i class="fal fa-users"></i>
              <sup class="notif">23</sup>
            </span>
            <span>Users</span>
            <span>
            </span>
          </li> -->
        </ul>
      </div>
    </div>
  </div>
  <div class="right-pane" style="position:relative;background-color: <?php echo $colors['right-panel'] ?>">
    <div class="col-100 right-panel-header flex justify-space-between align-center" style="background-color: <?php echo $colors['nav-bar'] ?>;flex-wrap:nowrap;padding: 0 20px;">
      <div class="b">
        <nav id="main">
          <button onclick="toggleLeftPane()">Menu</button>
        </nav>
      </div>
      <div class="">
        <h1 id="page-label" style="color: <?php echo $colors['page-label'] ?>">Dashboard</h1>
      </div>
      <div class="d user flex justify-space-between align-center">
        <div class="user-info">
          <a style="white-space:pre;"><?php echo $_SESSION["account"]["full_name"] ?> (<a href="/logout.php">Logout</a>)
          </a>
        </div>
      </div>
    </div>
    <div id="loading-content" class="flex align-center justify-center" style="display:none;display:flex;background:rgba(255,255,255,0.8);position:absolute;top:0;right:0;bottom:0;left:0;z-index: 999;width: 100%;height:100%;">
      <img src="img/load.svg" alt="" width="100">
    </div>
    <div id="dashboard-view"></div>
  </div>
</div>
