
<header id="main-header" class="col-100">
  <div class="flex flex-wrap align-start">
    <div id="logo" class="col-100">
      <div class="wrap flex justify-center">

        <div class="flex align-center" style="cursor:pointer;" onclick="window.location='/'">
            <div>
              <img src="/img/psits.svg" alt="" width="100">
            </div>
            <div id="logo-title">
              <img src="/img/psits-line.png" width="240" alt="">
            </div>
        </div>

      </div>
    </div>

    <menu class="col-100" style="position: relative;">
      <button id="menu-toggler" onclick="togglemenu()" style="background:transparent;border-radius:50%;padding: 0;position:absolute;right:10px;display:none;">
          <i class="fal fa-chevron-circle-down" style="font-size: 2rem;color:#00adff;"></i>
      </button>

      <div id="menu-links" class="wrap flex justify-space-between">
        <div id="linkz" class="col-50">
            <div id="link-wrapper" class="no-bold">
              <?php if (isset($_SESSION['account'])): ?>
                <a href="/account/index.php">
                  <i class="fal fa-user"></i>
                  My account</a>
                <!-- <a href="/account/payment.php">
                  <i class="fa fa-chart-bar"></i>
                  Payment</a> -->
                <?php if ($_SESSION['account']['access']>=30): ?>
                  <a href="/dashboard">
                    <i class="fal fa-th-list"></i>
                    Dashboard</a>
                <?php endif; ?>
              <?php endif; ?>
              <a href="/events.php">
                <i class="fal fa-calendar"></i>
                Events</a>
           </div>
        </div>
        <div class="no-bold">
          <?php if(isLoggedIn()){ ?>
            <a href="/logout.php">
              <i class="fal fa-sign-out-alt"></i>
              Logout</a>
          <?php } else { ?>
            <a href="/account/login.php">
              <i class="fal fa-sign-in-alt"></i>
              Login</a>
          <?php } ?>
        </div>
      </div>
    </menu>
  </div>
  <script>
      function togglemenu(){
          if($('#linkz').height()==0){
              $('#linkz').css('height',$('#link-wrapper').height());
              return;
          }
          $('#linkz').css('height','0px');
      }
      // $("#main-header").sticky(
      //   {
      //   topSpacing:-($('#main-header').height()-$('header menu').outerHeight())
      // });
      // $('#main-header').on('sticky-start',()=>{
      //   $('#main-header').css('box-shadow','0');
      // })
      // $('#main-header').on('sticky-end',()=>{
      //   $('#main-header').css('box-shadow','0 8px 8px rgba(0,0,0,0.1)');
      // })

  </script>
</header>
