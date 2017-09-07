<?php

namespace lambda\neon\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "app_log".
 *
 * @property string $id
 * @property string $logCode
 * @property string $tableName
 * @property string $recordId
 * @property string $userIdentity
 * @property string $userId
 * @property string $logAction
 * @property integer $isUpdated
 * @property string $recordBefore
 * @property string $recordAfter
 * @property string $logInformation
 * @property string $logTrail
 * @property string $createdAt
 */
class AppLog extends ActiveRecord {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'app_log';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['tableName', 'recordId', 'userIdentity', 'userId', 'logAction'], 'required'],
      [['recordId'], 'integer'],
      [['recordBefore', 'recordAfter', 'logTrail'], 'string'],
      [['createdAt'], 'safe'],
      ['isUpdated', 'boolean'],
      [['logCode', 'tableName', 'userIdentity', 'userId', 'logAction', 'logInformation'], 'string', 'max' => 255],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => Yii::t('app', 'ID'),
      'logCode' => Yii::t('app', 'Log Code'),
      'tableName' => Yii::t('app', 'Table Name'),
      'recordId' => Yii::t('app', 'Record ID'),
      'userIdentity' => Yii::t('app', 'User Identity'),
      'userId' => Yii::t('app', 'User ID'),
      'logAction' => Yii::t('app', 'Log Action'),
      'isUpdated' => Yii::t('app', 'Is Updated'),
      'recordBefore' => Yii::t('app', 'Record Before'),
      'recordAfter' => Yii::t('app', 'Record After'),
      'logInformation' => Yii::t('app', 'Log Information'),
      'logTrail' => Yii::t('app', 'Log Trail'),
      'createdAt' => Yii::t('app', 'Created At'),
    ];
  }

}
