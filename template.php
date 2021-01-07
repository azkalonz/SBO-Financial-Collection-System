<?php include('config/config.php') ?>
<?php
  foreach ($_GET as $key => $value) {
    $_GET[$key] = str_replace('_',' ',$value);
  }
 ?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Receipt for [Product Name]</title>
    <!--
    The style block is collapsed on page load to save you some scrolling.
    Postmark automatically inlines all CSS properties for maximum email client
    compatibility. You can just update styles here, and Postmark does the rest.
    -->
    <style type="text/css" rel="stylesheet" media="all">
    /* Base ------------------------------ */

    *:not(br):not(tr):not(html) {
      font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
      box-sizing: border-box;
    }

    body {
      width: 100% !important;
      height: 100%;
      margin: 0;
      line-height: 1.4;
      background-color: #F2F4F6;
      color: #74787E;
      -webkit-text-size-adjust: none;
    }

    p,
    ul,
    ol,
    blockquote {
      line-height: 1.4;
      text-align: left;
    }

    a {
      color: #3869D4;
    }

    a img {
      border: none;
    }

    td {
      word-break: break-word;
    }
    /* Layout ------------------------------ */

    .email-wrapper {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #F2F4F6;
    }

    .email-content {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    /* Masthead ----------------------- */

    .email-masthead {
      padding: 25px 0;
      text-align: center;
    }

    .email-masthead_logo {
      width: 94px;
    }

    .email-masthead_name {
      font-size: 16px;
      font-weight: bold;
      color: #bbbfc3;
      text-decoration: none;
      text-shadow: 0 1px 0 white;
    }
    /* Body ------------------------------ */

    .email-body {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      border-top: 1px solid #EDEFF2;
      border-bottom: 1px solid #EDEFF2;
      background-color: #FFFFFF;
    }

    .email-body_inner {
      width: 570px;
      margin: 0 auto;
      padding: 0;
      -premailer-width: 570px;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #FFFFFF;
    }

    .email-footer {
      width: 570px;
      margin: 0 auto;
      padding: 0;
      -premailer-width: 570px;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      text-align: center;
    }



    .body-action {
      width: 100%;
      margin: 30px auto;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      text-align: center;
    }

    .body-sub {
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid #EDEFF2;
    }

    .content-cell {
      padding: 35px;
    }

    .preheader {
      display: none !important;
      visibility: hidden;
      mso-hide: all;
      font-size: 1px;
      line-height: 1px;
      max-height: 0;
      max-width: 0;
      opacity: 0;
      overflow: hidden;
    }
    /* Attribute list ------------------------------ */

    .attributes {
      margin: 0 0 21px;
    }

    .attributes_content {
      background-color: #EDEFF2;
      padding: 16px;
    }

    .attributes_item {
      padding: 0;
    }
    /* Related Items ------------------------------ */

    .related {
      width: 100%;
      margin: 0;
      padding: 25px 0 0 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }

    .related_item {
      padding: 10px 0;
      color: #74787E;
      font-size: 15px;
      line-height: 18px;
    }

    .related_item-title {
      display: block;
      margin: .5em 0 0;
    }

    .related_item-thumb {
      display: block;
      padding-bottom: 10px;
    }

    .related_heading {
      border-top: 1px solid #EDEFF2;
      text-align: center;
      padding: 25px 0 10px;
    }
    /* Discount Code ------------------------------ */

    .discount {
      width: 100%;
      margin: 0;
      padding: 24px;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #EDEFF2;
      border: 2px dashed #9BA2AB;
    }

    .discount_heading {
      text-align: center;
    }

    .discount_body {
      text-align: center;
      font-size: 15px;
    }
    /* Social Icons ------------------------------ */

    .social {
      width: auto;
    }

    .social td {
      padding: 0;
      width: auto;
    }

    .social_icon {
      height: 20px;
      margin: 0 8px 10px 8px;
      padding: 0;
    }
    /* Data table ------------------------------ */

    .purchase {
      width: 100%;
      margin: 0;
      padding: 35px 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }

    .purchase_content {
      width: 100%;
      margin: 0;
      padding: 25px 0 0 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }

    .purchase_item {
      padding: 10px 0;
      color: #74787E;
      font-size: 15px;
      line-height: 18px;
    }

    .purchase_heading {
      padding-bottom: 8px;
      border-bottom: 1px solid #EDEFF2;
    }

    .purchase_heading p {
      margin: 0;
      color: #9BA2AB;
      font-size: 12px;
    }

    .purchase_footer {
      padding-top: 15px;
      border-top: 1px solid #EDEFF2;
    }

    .purchase_total {
      margin: 0;
      text-align: right;
      font-weight: bold;
      color: #2F3133;
    }

    .purchase_total--label {
      padding: 0 15px 0 0;
    }
    /* Utilities ------------------------------ */

    .align-right {
      text-align: right;
    }

    .align-left {
      text-align: left;
    }

    .align-center {
      text-align: center;
    }
    /*Media Queries ------------------------------ */

    @media only screen and (max-width: 600px) {
      .email-body_inner,
      .email-footer {
        width: 100% !important;
      }
    }

    @media only screen and (max-width: 500px) {
      .button {
        width: 100% !important;
      }
    }
    /* Buttons ------------------------------ */

    .button {
      background-color: #3869D4;
      border-top: 10px solid #3869D4;
      border-right: 18px solid #3869D4;
      border-bottom: 10px solid #3869D4;
      border-left: 18px solid #3869D4;
      display: inline-block;
      color: #FFF;
      text-decoration: none;
      border-radius: 3px;
      box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
      -webkit-text-size-adjust: none;
    }

    .button--green {
      background-color: #22BC66;
      border-top: 10px solid #22BC66;
      border-right: 18px solid #22BC66;
      border-bottom: 10px solid #22BC66;
      border-left: 18px solid #22BC66;
    }

    .button--red {
      background-color: #FF6136;
      border-top: 10px solid #FF6136;
      border-right: 18px solid #FF6136;
      border-bottom: 10px solid #FF6136;
      border-left: 18px solid #FF6136;
    }
    /* Type ------------------------------ */

    h1 {
      margin-top: 0;
      color: #2F3133;
      font-size: 19px;
      font-weight: bold;
      text-align: left;
    }

    h2 {
      margin-top: 0;
      color: #2F3133;
      font-size: 16px;
      font-weight: bold;
      text-align: left;
    }

    h3 {
      margin-top: 0;
      color: #2F3133;
      font-size: 14px;
      font-weight: bold;
      text-align: left;
    }

    p {
      margin-top: 0;
      color: #74787E;
      font-size: 16px;
      line-height: 1.5em;
      text-align: left;
    }

    p.sub {
      font-size: 12px;
    }

    p.center {
      text-align: center;
    }
    .logo1 h1,.logo2 h1 {
      margin: 0;
    }
    .logo1 h1{
      font-size: 1.3em;
    }
    .logo2 h1 {
      font-size: 0.77em;
    }
    .logo1 h1 > a,.logo2 h1 > a {
      text-decoration: none;
      color: #fff;
    }
    </style>
  </head>
  <body>
    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
      <tbody><tr>
        <td align="center">
          <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
            <tbody><tr style="background: <?php echo $colors['nav-bar'] ?>;">
              <td class="email-masthead" style="padding: 4px 0;">
                <table class="logo" width="500" style="margin: 0 auto;">
                  <tr>
                    <td rowspan="2" width="85">
                      <img src="<?php echo $webhost.$images['logo'] ?>" width="85">
                    </td>
                    <td style="vertical-align: center;" class="logo1">
                      <h1><a href=""><?php echo $labels['abbr'] ?></a></h1>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <!-- Email Body -->
            <tr>
              <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
                <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0">
                  <!-- Body content -->
                  <tbody><tr>
                    <td class="content-cell">
                      <?php if (isset($_GET['collection_id'])): ?>
                      <h1>Hi <?php echo explode(', ',$_GET['name'])[1] ?>,</h1>
                      <p>Thanks for participating the event.</p>
                      <!-- This email will serves as p for your purchase that you <b><?php echo $_GET['sid'] ?></b> made on <b><?php echo date('jS \of F Y h:i:s A') ?></b>. -->
                      <table class="discount" align="center" width="100%" cellpadding="0" cellspacing="0">
                        <tbody><tr>
                          <td align="center">
                            <h1 class="discount_heading">Validate your payment!</h1>
                            <h2 style="margin:0;text-align:center;">Total</h2>
                            Php <?php printf('%.2f',$_GET['amount']) ?>
                            <h2 style="margin:0; text-align:center;">Date</h2>
                            <span>
                              <?php echo date('F d, Y') ?>
                            </span>
                            <p class="discount_body">
                              Open the link below to confirm whether your payment went through our records or not.</p>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tbody><tr>
                                <td align="center">
                                  <table border="0" cellspacing="0" cellpadding="0">
                                    <tbody><tr>
                                      <td>
                                        <a href="<?php echo $webhost ?>account/?tab=payment&confirm&id=<?php echo $_GET['collection_id'] ?>" class="button button--green" target="_blank" style="color: #fff;text-align:center;">Confirm</a>
                                      </td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
                      <table class="purchase" width="100%" cellpadding="0" cellspacing="0" style="padding-bottom: 0;">
                        <tbody><tr>
                          <td>
                            <?php
                            $id = 100000000+(int)$_GET['collection_id'];
                            $id = (string)$id;
                            $id = '#'.substr($id,1);
                             ?>
                            <h3><?php echo $id ?></h3></td>
                          <td>
                            <!-- <h3 class="align-right">
                              <?php echo date('d-m-Y') ?>
                            </h3> -->
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">
                            Event     : <?php echo $_GET['event'] ?>
                          </td>
                        </tr>
                          <?php if(isset($_GET['size'])): ?>
                            <tr>
                              <td colspan="2">
                                Size     : <?php echo $_GET['size'] ?>
                              </td>
                            </tr>
                          <?php endif; ?>
                          <tr>
                            <td colspan="2">
                              Issued by : <?php echo $_GET['user'] ?>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2">
                              Issued to : <?php echo $_GET['name'] ?>
                            </td>
                          </tr>

                      </tbody></table>
                      <table class="purchase_content" width="100%" cellpadding="0" cellspacing="0" style="padding-bottom: 40px;">
                      <tbody><tr>
                        <th class="purchase_heading">
                          <p>Description</p>
                        </th>
                        <th class="purchase_heading">
                          <p class="align-right">Amount</p>
                        </th>
                      </tr><tr>
                        <td width="80%" class="purchase_item">
                          <?php echo $_GET['event'] ?>
                        </td>
                        <td class="align-right" width="20%">
                           <?php printf('%.2f',$_GET['amount']) ?>
                        </td>
                      </tr><tr>
                        <td width="80%" class="purchase_footer" valign="top">
                          <p class="purchase_total purchase_total--label">Total</p>
                        </td>
                        <td class="purchase_footer" valign="top" style="white-space: nowrap;">
                          <p class="purchase_total">
                            Php  <?php printf('%.2f',$_GET['amount'
                            ]) ?>

                          </p>
                        </td>
                      </tr>
                    </tbody></table>
                      <?php else: ?>
                        <h1>Hi!</h1>
                        <p><?php echo $_POST['message'] ?></p>
                      <?php endif; ?>
                      <p>Cheers,
                        <br><?php echo $labels['text-logo'] ?></p>
                    </td>
                  </tr>
                </tbody></table>
              </td>
            </tr>
            <tr>
              <td>
                <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
                  <tbody><tr>
                    <td class="content-cell" align="center">
                      <p class="sub align-center">&copy; 2019 <?php echo $labels['text-logo'] ?>. All rights reserved.</p>
                    </td>
                  </tr>
                </tbody></table>
              </td>
            </tr>
          </tbody></table>
        </td>
      </tr>
    </tbody></table>

</body></html>
