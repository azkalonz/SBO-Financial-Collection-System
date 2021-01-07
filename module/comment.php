<div class="flex flex-wrap col-100 comments-container" style="box-shadow: 0 6.6px 6.9px rgba(0,0,0,0.04)">
  <div comment="<?php echo $cid ?>" class="flex flex-wrap col-100">
  </div>

  <div id="c-<?php echo $cid ?>" class="comments" ref="<?php echo $cid ?>">
    <div class="flex justify-center flex-wrap" style="text-align:center;">
      <div class="col-100">
        <img src="/dashboard/img/spinner.svg" alt="Loading comments..." style="width:50px!important;">
      </div>
      <div class="col-100">
        Loading comments...
      </div>
    </div>
  </div>

  <div class="compose-comment col-100">
    <form onsubmit="return false;">
      <table>
        <tr>
          <td>
            <div class="c-cont">
              <textarea class="comment-msg" placeholder="Write a comment..." comment-msg="<?php echo $cid ?>"></textarea>
              <div class="flex align-center justify-space-between" style="padding: 0 10px;">

                <div>
                  <div id="<?php echo "p-{$cid}" ?>" style="display: none;">
                    <img src="/dashboard/img/spinner.svg" alt="Posting comment..." style="width:40px!important;"/>
                  </div>
                </div>

                <div class="flex align-center">
                  <div style="padding: 10px;font-size: 1.5em;">
                    <a href="https://www.facebook.com/groups/1670759663218592/" target="_blank" title="Facebook group">
                      <i class="fab fa-facebook"></i>
                    </a>
                    <a href="mailto:psits.uc@gmail.com" title="Email us">
                      <i class="fal fa-envelope"></i>
                    </a>
                    <a href="/account/?tab=comment-settings" title="My pending comments">
                      <i class="fal fa-comment"></i>
                    </a>
                  </div>
                  <div class="">
                    <button type="button" class="comment-btn h" ref="<?php echo $cid ?>" <?php echo !isset($_SESSION['account'])?"onclick=\"window.location='/event/?id=".$cid."'\"":'' ?>>
                      <b>Post</b></button>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
