<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Admin Giriş</title>
  </head>
  <body>

    <div class="main">
      <div class="px-4 pt-5 my-5 text-center text-dark">
        <img src="https://i.hizliresim.com/7ewxbe7.png" class="img-fluid mb-5">
        <div class="col-lg-6 mx-auto">
          <p class="lead mb-1">Problem çözen, yenilikçi ve Dünya'nın <strong>en gelişmiş</strong> yazılımı ile rahata kavuşun. </p>
          <p class="lead mb-4"><i>Cümleye biz başladık, noktayı biz koyduk.</i></p>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-6 mx-auto">
                              <?php if( isset($success) ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
             <form id="yw0" action="" method="post">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" name="username" id="AdminUsers_login">
                <label for="floatingInput">Kullanıcı Adı</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="AdminUsers_passwd">
                <label for="floatingPassword">Şifre</label>
              </div>
              <?php if($settings['recaptcha'] == 2){ ?>
                 <?php if(  $session->get("recaptcha")): ?>
            <div class="form-group">
              <div class="g-recaptcha" data-sitekey="<?php echo $settings["recaptcha_key"] ?>"></div>
            </div>
          <?php endif; ?>
          <?php } ?>

              <button class="w-100 btn btn-lg btn-primary bg-gradient" type="submit">Giriş Yap</button>
              <hr class="my-4">
              <div class="text-center">
                <small class="text-muted text-center">"Başarılı girişimcileri başarısız girişimcilerden ayıran şey sadece azimdir."</small><br>
                <small class="text-muted text-center">-Steve Jobs</small>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src='https://www.google.com/recaptcha/api.js?hl=en'></script>
  </body>
  </html>