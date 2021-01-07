<div class="flex flex-wrap" id="sidebar">
  <?php if (!isset($hideQuickies) && isset($_SESSION['account'])): ?>
  <div class="col-100 s-section">
    <h3>Quickies</h3>
    <div class="r-container w-100">
      <ul class="normal-ul">
        <li>
          <a href="/">
            <i class="fal fa-home"></i>
            Home</a>
        </li>
        <li>
          <a href="/account/?tab=account-settings">
            <i class="fal fa-lock-alt"></i>
            Change password</a>
        </li>
        <li>
          <a href="/account/?tab=comment-settings">
            <i class="fal fa-comment"></i>
            My comments</a>
        </li>
        <li>
          <a href="/account/?tab=payment">
            <i class="fal fa-credit-card"></i>
            My payments</a>
        </li>
      </ul>
    </div>
  </div>
  <?php endif; ?>


  <div class="col-100 s-section newsletter">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/module/subscribe.php" ?>
  </div>

  <div class="col-100 s-section">
    <h3>Connect with us</h3><br>
    <table class="normal light col-100" style="background: none;">
      <tr>
        <td><i class="fab fa-facebook"></i></td>
        <td>
          <b>
              Facebook group
            <a href="https://www.facebook.com/groups/1670759663218592/" target="_blank">
              <i class="fal fa-link"></i>
              </a>
            </b>
          </td>
        </tr>
      <tr>
        <td><i class="fal fa-envelope"></i></td>
        <td>
          <b>psits.uc@gmail.com</b>
          <a href="mailto:psits.uc@gmail.com" target="_blank">
            <i class="fal fa-link"></i>
            </a>
        </td>
      </tr>
      <tr>
        <td><i class="fal fa-phone"></i></td>
        <td>
          <b>+63 9123456789</b></td>
      </tr>
    </table>
  </div>


  <div class="col-100 s-section">
    <div class="sidebar flex justify-space-between align-items-center w-100">
      <span>Dark theme </span>
      <label class="switch <?php echo !isset($_SESSION['darkmode'])?'':$_SESSION['darkmode']!=='true'?'on':'' ?>" id="darkmode" onclick="location.reload()">
        <input type="checkbox" onclick="darkmode(this.parentElement)">
        <span class="slider round"></span>
      </label>
    </div>
  </div>
</div>
