<?php

namespace lambda\neon\models;

use Yii;

/**
 * This is the model class for table "user_log".
 *
 * @property string $id
 * @property string $userId
 * @property string $activity
 * @property string $userIP
 * @property string $userHost
 * @property string $portRequest
 * @property string $hostName
 * @property string $userAgent
 * @property string $createdAt
 *
 * @property User $user
 */
class UserLog extends \app\models\Base {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'user_log';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['userId', 'activity', 'createdAt'], 'required'],
      [['userId'], 'integer'],
      [['createdAt'], 'safe'],
      [['activity', 'userIP', 'userHost', 'portRequest', 'hostName', 'userAgent'], 'string', 'max' => 255],
      [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => Yii::t('app', 'ID'),
      'userId' => Yii::t('app', 'User ID'),
      'activity' => Yii::t('app', 'Activity'),
      'userIP' => Yii::t('app', 'User Ip'),
      'userHost' => Yii::t('app', 'User Host'),
      'portRequest' => Yii::t('app', 'Port Request'),
      'hostName' => Yii::t('app', 'Host Name'),
      'userAgent' => Yii::t('app', 'User Agent'),
      'createdAt' => Yii::t('app', 'Created At'),
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser() {
    return $this->hasOne(User::className(), ['id' => 'userId'])->inverseOf('userLogs');
  }

}
