<?php

namespace lambda\neon\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller as YiiWebController;
use yii\web\ErrorAction;

class Controller extends YiiWebController {

  public $allowedRoles = ['@'];

  public function init() {
    parent::init();
    if (!Yii::$app->user->isGuest) {
      Yii::$app->language = Yii::$app->user->identity->configLanguage ? Yii::$app->user->identity->configLanguage : Yii::$app->sourceLanguage;
    }
  }

  public function behaviors() {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          ['allow' => true, 'roles' => $this->allowedRoles]
        ],
      ],
    ];
  }

  public function actions() {
    $this->getView()->title = Yii::$app->params['appInfo']['name'];

    return [
      'error' => [
        'class' => ErrorAction::className(),
      ],
    ];
  }

}
