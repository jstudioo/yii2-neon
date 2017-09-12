<?php

/**
 * @var string $content
 * @var View $this
 */
use lambda\neon\assets\LoginAsset;
use kartik\form\ActiveForm;
use xj\bootbox\BootboxAsset;
use yii\helpers\Html;
use yii\web\View;

LoginAsset::register($this);
BootboxAsset::registerWithOverride($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <?php
    $this->registerMetaTag(['charset' => Yii::$app->charset]);
    $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
    $this->registerLinkTag(['rel' => 'icon', 'href' => Yii::$app->request->baseUrl . '/favicon.png']);
    ?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <?php $this->beginBody(); ?>
    <div class="container" id="login-block">
      <div class="row">
        <div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
          <h3 class="animated bounceInDown"></h3>
          <div class="login-box clearfix animated flipInY">
            <div class="login-logo">
              <?= Html::img('@web/images/pertamina_logo.png'); ?>
            </div>
            <hr />
            <div class="login-form text-center">

              <?php
              $form = ActiveForm::begin();
              echo $form->field($model, 'username')->textInput(['placeholder' => Yii::t('app', 'Username'), 'autofocus' => true])->label(FALSE);
              echo $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Password')])->label(FALSE);
              echo Html::submitButton('Login', ['class' => 'btn btn-default submit', 'name' => 'login-button']);
              kartik\form\ActiveForm::end();
              ?>

              <div class="login-links">
                <?= Html::a(Yii::t('app', 'Forgot Password'), ['site/request-reset-token']) ?>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
    <!-- End Login box -->
    <footer class="container">
      <p id="footer-text"><small>&copy;<?= date('Y'); ?> <?= Yii::$app->name; ?> </small></p>
    </footer>

    <?php $this->endBody(); ?>
  </body>
</html>
<?php $this->endPage(); ?>
