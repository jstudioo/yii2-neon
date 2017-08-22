<?php

namespace lambda\neon\behaviors;

use lambda\neon\helpers\Hasher;
use lambda\neon\helpers\Main;
use app\models\AppLog;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class LogBehavior extends Behavior {

  const IS_RECORDED = TRUE;

  private $dataBefore;
  public $_logInformation;
  public $_logCode = NULL;

  public function events() {
    return [
      ActiveRecord::EVENT_AFTER_INSERT => 'createRecord',
      ActiveRecord::EVENT_BEFORE_UPDATE => 'updateRecord',
      ActiveRecord::EVENT_BEFORE_DELETE => 'deleteRecord',
    ];
  }

  public function setOldAttributes() {
    $this->dataBefore = Json::encode($this->getAttributes());
  }

  private function getCurrentDate() {
    return Main::getCurrentDate();
  }

  private function getOldAttributes() {
    return $this->owner->getOldAttributes();
  }

  private function getAttributes() {
    return $this->owner->getAttributes();
  }

  private static function getHostName() {
    return gethostname() ? : FALSE;
  }

  private static function getHostInfo() {
    return Yii::$app->request->hostInfo ? : FALSE;
  }

  private static function getPortRequest() {
    return Yii::$app->request->port ? : FALSE;
  }

  private static function getUrl() {
    return \Yii::$app->request->url ? : FALSE;
  }

  private static function getUserHost() {
    return Yii::$app->request->userHost ? : FALSE;
  }

  private static function getUserIP() {
    return Yii::$app->request->userIP ? : FALSE;
  }

  private static function getUserAgent() {
    Yii::$app->request->userAgent ? : FALSE;
  }

  private static function getUserIdentityClass() {
    return \Yii::$app->user->identityClass ? : FALSE;
  }

  private static function getUserId() {

    return \Yii::$app->user->isGuest ? FALSE : \Yii::$app->user->id;
  }

  private static function getLogTrail() {
    return [
      'hostName' => self::getHostName(),
      'hostInfi' => self::getHostInfo(),
      'portRequest' => self::getPortRequest(),
      'url' => self::getUrl(),
      'userHost' => self::getUserHost(),
      'userIP' => self::getUserIP(),
      'userAgent' => self::getUserAgent()
    ];
  }

  public function behaviorRecord($userAction) {
    $recordBefore = Json::encode($this->getOldAttributes());
    $recordAfter = Json::encode($this->getAttributes());

    $logCode = $this->_logCode ? : Hasher::encode($this->owner->id) . '-' . time();
    $appLog = new AppLog([
      'logCode' => $logCode,
      'tableName' => $this->owner->tableName(),
      'recordId' => $this->owner->id,
      'userIdentity' => self::getUserIdentityClass(),
      'userId' => self::getUserId(),
      'logAction' => $userAction,
      'isUpdated' => $recordBefore == $recordAfter,
      'recordBefore' => $recordBefore,
      'recordAfter' => $recordAfter,
      'logInformation' => $this->_logInformation,
      'logTrail' => Json::encode(self::getLogTrail()),
      'createdAt' => MainHelper::getCurrentDateTime(),
    ]);

    return $appLog->save();
  }

  public function createRecord() {
    return $this->behaviorRecord('insert');
  }

  public function updateRecord() {
    return $this->behaviorRecord('update');
  }

  public function deleteRecord() {
    return $this->behaviorRecord('delete');
  }

}
