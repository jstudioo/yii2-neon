<?php

namespace lambda\neon\models;

use Yii;

/**
 * This is the model class for table "app_configuration".
 *
 * @property string $id
 * @property string $key
 * @property string $index
 * @property string $value
 * @property integer $isDeleted
 * @property string $createdAt
 * @property integer $createdBy
 * @property string $updatedAt
 * @property integer $updatedBy
 * @property string $deletedAt
 * @property integer $deletedBy
 */
class AppConfiguration extends ActiveRecord {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'app_configuration';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['key', 'value'], 'required'],
      [['value'], 'string'],
      [['createdAt', 'updatedAt', 'deletedAt'], 'safe'],
      [['isDeleted', 'createdBy', 'updatedBy', 'deletedBy'], 'integer'],
      [['key', 'index'], 'string', 'max' => 255],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return [
      'id' => Yii::t('app', 'ID'),
      'key' => Yii::t('app', 'Key'),
      'index' => Yii::t('app', 'Index'),
      'value' => Yii::t('app', 'Value'),
      'isDeleted' => Yii::t('app', 'Is Deleted'),
      'createdAt' => Yii::t('app', 'Created At'),
      'createdBy' => Yii::t('app', 'Created By'),
      'updatedAt' => Yii::t('app', 'Updated At'),
      'updatedBy' => Yii::t('app', 'Updated By'),
      'deletedAt' => Yii::t('app', 'Deleted At'),
      'deletedBy' => Yii::t('app', 'Deleted By'),
    ];
  }

}
