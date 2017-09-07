<?php

namespace lambda\neon\behaviors;

use lambda\neon\models\AppLog;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class LogBehavior extends Behavior {

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
    $this->dataBefore = $this->owner->isNewRecord ? Json::encode($this->getAttributes()) : NULL;
  }

  private function getOldAttributes() {
    return $this->owner->getOldAttributes();
  }

  private function getAttributes() {
    return $this->owner->getAttributes();
  }

  private static function getHostName() {
    return gethostname() ? : NULL;
  }

  private static function getHostInfo() {
    return Yii::$app->request->hostInfo ? : NULL;
  }

  private static function getPortRequest() {
    return Yii::$app->request->port ? : NULL;
  }

  private static function getUrl() {
    return \Yii::$app->request->url ? : NULL;
  }

  private static function getUserHost() {
    return Yii::$app->request->userHost ? : NULL;
  }

  private static function getUserIP() {
    return Yii::$app->request->userIP ? : NULL;
  }

  private static function getUserAgent() {
    Yii::$app->request->userAgent ? : NULL;
  }

  private static function getUserIdentityClass() {
    return \Yii::$app->user->identityClass ? : NULL;
  }

  private static function getUserId() {
    return !\Yii::$app->user->isGuest ? \Yii::$app->user->id : NULL;
  }

  public function behaviorRecord($userAction) {
    $recordBefore = Json::encode($this->getOldAttributes());
    $recordAfter = Json::encode($this->getAttributes());

    $logCode = $this->_logCode;

    $logTrail = ['hostName' => self::getHostName(), 'hostInfo' => self::getHostInfo(), 'portRequest' => self::getPortRequest(), 'url' => self::getUrl(), 'userHost' => self::getUserHost(), 'userIP' => self::getUserIP(), 'userAgent' => self::getUserAgent()];

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
      'logTrail' => Json::encode($logTrail),
      'createdAt' => date('Y-m-d H:i:s'),
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
