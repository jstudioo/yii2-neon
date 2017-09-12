<?php

use yii\widgets\Breadcrumbs;
use lambda\neon\assets\Asset;
use yii\bootstrap\Html;
use xj\bootbox\BootboxAsset;
use kartik\alert\Alert;
use yii\bootstrap\Modal;

Asset::register($this);

BootboxAsset::registerWithOverride($this);
$userIdentity = Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity;

$this->beginPage();
echo "<!DOCTYPE html>";
echo Html::beginTag('html', ['lang' => Yii::$app->language]);

echo Html::beginTag('head', ['class' => 'logo-env']);
echo Html::tag('meta', FALSE, ['charset' => Yii::$app->charset]);
echo Html::tag('meta', FALSE, ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);

$this->registerMetaTag(['charset' => Yii::$app->charset]);
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0, user-scalable=yes']);
$this->registerLinkTag(['rel' => 'icon', 'href' => Yii::$app->urlManager->baseUrl . '/favicon.ico']);
echo Html::csrfMetaTags();
echo Html::tag('title', Html::encode($this->title));

$this->head();
echo Html::endTag('head');

echo Html::beginTag('body', ['class' => 'page-body loaded']);
$this->beginBody();

echo Html::tag('div', FALSE, [
  'class' => 'loader loader-default is-active',
  'data-text' => 'Loading Page..Please Wait!!!',
  'data-blink' => '',
]);


echo Html::beginTag('div', ['class' => 'page-container horizontal-menu sidebar-collapsed']);
echo Html::beginTag('header', ['class' => 'navbar navbar-fixed-top']);
echo Yii::$app->getView()->render('_menu');
echo Html::endTag('header');

echo Html::beginTag('div', ['class' => 'main-content']);

$infoFeedbacks = Yii::$app->session->getFlash('feedback-info');
$defaultFeedbacks = Yii::$app->session->getFlash('feedback-default');
$primaryFeedbacks = Yii::$app->session->getFlash('feedback-primary');
$successFeedbacks = Yii::$app->session->getFlash('feedback-success');
$warningFeedbacks = Yii::$app->session->getFlash('feedback-warning');
$dangerFeedbacks = Yii::$app->session->getFlash('feedback-danger');
echo $infoFeedbacks ? Alert::widget(['type' => Alert::TYPE_INFO, 'body' => Html::ul($infoFeedbacks)]) : FALSE;
echo $defaultFeedbacks ? Alert::widget(['type' => Alert::TYPE_DEFAULT, 'body' => Html::ul($defaultFeedbacks)]) : FALSE;
echo $primaryFeedbacks ? Alert::widget(['type' => Alert::TYPE_PRIMARY, 'body' => Html::ul($primaryFeedbacks)]) : FALSE;
echo $successFeedbacks ? Alert::widget(['type' => Alert::TYPE_SUCCESS, 'body' => Html::ul($successFeedbacks)]) : FALSE;
echo $warningFeedbacks ? Alert::widget(['type' => Alert::TYPE_WARNING, 'body' => Html::ul($warningFeedbacks)]) : FALSE;
echo $dangerFeedbacks ? Alert::widget(['type' => Alert::TYPE_DANGER, 'body' => Html::ul($dangerFeedbacks)]) : FALSE;


echo Breadcrumbs::widget([
  'tag' => 'ol',
//  'homeLink' => ['site/index'],
  'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
  'options' => ['class' => 'breadcrumb bc-2']
]);


echo Html::beginTag('div', ['class' => 'row']);
echo Html::beginTag('div', ['class' => 'col-sm-12']);
echo $content;
echo Html::endTag('div');

Modal::begin([
  'headerOptions' => ['id' => 'modalHeader'],
  'closeButton' => [
    'label' => '&times;',
    'tag' => 'button'
  ],
  'id' => 'modal',
  'size' => 'modal-lg',
  //keeps from closing modal with esc key or by clicking out of the modal.
  // user must click cancel or X to close
  'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE]
]);
echo Html::tag('div', FALSE, ['id' => 'modalContent']);
Modal::end();

echo Html::endTag('div');

echo Html::beginTag('footer', ['class' => 'main']);
echo Yii::t('app', '&copy; {year} {appInfo} by {company}', [
  'year' => date('Y'),
  'appInfo' => Html::tag('strong', Yii::$app->params['appInfo']['name']),
  'company' => Html::tag('strong', Html::a(Yii::$app->params['appInfo']['company'], ['site/dashboard'], ['target' => '_blank']))
]);

echo Html::endTag('footer');
echo Html::endTag('div');

$this->endBody();
echo Html::endTag('body');
echo Html::endTag('html');
$this->endPage();

