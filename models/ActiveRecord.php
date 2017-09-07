<?php

namespace lambda\neon\models;

use kartik\grid\SerialColumn;
use lambda\neon\behaviors\LogBehavior;
use lambda\neon\components\ActionColumn;
use lambda\neon\components\Hasher;
use lambda\neon\helpers\MainHelper;
use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord as YiiDbActiveRecord;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

class ActiveRecord extends YiiDbActiveRecord {
  /*
   * for using this base ActiveRecord each table most contain this following attributes
   *
   * @property boolean $isDeleted
   * @property date $createdAt
   * @property integer $createdBy
   * @property date $updatedAt
   * @property integer $updatedBy
   * @property boolean $deletedAt
   * @property date $deletedAt
   */

  const CHANGELOG = TRUE;
  const FEEDBACK_MODE_SAVE = 1;
  const FEEDBACK_MODE_DELETE = 2;

  public $datatablePagesize = 10;
  public $logInformation, $logCode;
  public $keyName = 'hashId';
  public $identityColumn = FALSE;
  public $publicAttributes = [];
  public $translationParams = FALSE;
  public $softDelete = TRUE;
  public $log = TRUE;

  public function transactions() {
    return ['default' => self::OP_ALL];
  }

  public static function tableNameAsString() {
    return (ucwords(preg_replace('~[^\\pL\d]+~u', ' ', self::tableName())));
  }

  public static function findByHashId($hashId, $with = []) {
    $hasRecord = self::find()->where([self::primaryKey()[0] => Hasher::decode($hashId)])->with($with)->one();
    if ($hasRecord) {
      return $hasRecord;
    } else {
      throw new NotFoundHttpException(Yii::t('app', 'Failed while fetching record.'));
    }
  }

  public static function findById($id, $with = []) {
    return self::find()->where([self::primaryKey()[0] => $id])->with($with)->one();
  }

  public function getHashId() {
    return Hasher::encode($this->id);
  }

  public function setLogInformation($logInformation) {
    $this->getBehavior('logBehavior')->_logInformation = $logInformation;
  }

  public function setLogCode($logCode) {
    $this->getBehavior('logBehavior')->_logCode = $logCode;
  }

  public static function getUserId() {
    return !\Yii::$app->user->getIsGuest() ? \Yii::$app->user->id : NULL;
  }

  public function behaviors() {
    $behaviors = [];

    if ($this->translationParams) {
      $options = $this->translationParams;
      $behaviors['translateable'] = [
        'class' => TranslateableBehavior::className(),
        'translationAttributes' => $options['attributes'],
        'translationRelation' => key_exists('relation', $options) ? $options['relation'] : 'translations',
        'translationLanguageAttribute' => key_exists('languageAttribute', $options) ? $options['languageAttribute'] : 'language',
      ];
    }

    $behaviors['dateRecordBehavior'] = [
      'class' => TimestampBehavior::className(),
      'attributes' => [
        YiiDbActiveRecord::EVENT_BEFORE_INSERT => ['createdAt'],
        YiiDbActiveRecord::EVENT_BEFORE_UPDATE => ['updatedAt'],
        YiiDbActiveRecord::EVENT_BEFORE_DELETE => ['deletedAt'],
      ],
      'value' => MainHelper::getCurrentDateTime(),
    ];

    $behaviors['createIdentityBehavior'] = [
      'class' => AttributeBehavior::className(),
      'attributes' => [
        YiiDbActiveRecord::EVENT_BEFORE_INSERT => 'createdBy',
      ],
      'value' => self::getUserId(),
    ];

    $behaviors['updateIdentityBehavior'] = [
      'class' => AttributeBehavior::className(),
      'attributes' => [
        YiiDbActiveRecord::EVENT_BEFORE_UPDATE => 'updatedBy',
      ],
      'value' => self::getUserId(),
    ];

    if ($this->softDelete) {
      $behaviors['softDeleteBehavior'] = [
        'class' => SoftDeleteBehavior::className(),
        'softDeleteAttributeValues' => [
          'isDeleted' => true
        ],
        'replaceRegularDelete' => true
      ];

      $behaviors['deleteIdentityBehavior'] = [
        'class' => AttributeBehavior::className(),
        'attributes' => [
          YiiDbActiveRecord::EVENT_BEFORE_DELETE => 'deletedBy',
        ],
        'value' => self::getUserId(),
      ];
    }

    if ($this->log) {
      $behaviors['logBehavior'] = ['class' => LogBehavior::className()];
    }

    return $behaviors;
  }

  public function loadPost($validate = TRUE) {
    if ($validate) {
      return Yii::$app->request->isPost && static::load(\Yii::$app->request->post()) && $this->validate();
    } else {
      return Yii::$app->request->isPost && static::load(\Yii::$app->request->post());
    }
  }

  public function loadGet() {
    return Yii::$app->request->isGet && static::load(\Yii::$app->request->get());
  }

  /*
   * mode 1 insert/update
   * mode 2 delete/recover
   */

  public function generateFeedback($message = FALSE, $mode = 1, $key = 'feedback-warning') {

    if (!$message) {
      $message = ($mode == self::FEEDBACK_MODE_SAVE) ? Yii::t('neon', 'Record has saved successfully') : Yii::t('neon', 'Record status has changed to {recordStatus}', ['recordStatus' => $this->isDeleted ? Yii::t('neon', 'deleted') : Yii::t('neon', 'restored')]);
    }

    return Yii::$app->session->setFlash($key, $message);
  }

  public function saveRecord($feedback = TRUE, $validation = TRUE, $logInformation = FALSE, $logCode = FALSE) {

    if ($this->log) {
      $this->setlogCode($logCode ? : time());
      $this->setLogInformation($logInformation ? : 'save to `' . self::tableNameAsString() . '`');
    }

    $isSuccess = $validation ? $this->validate() && $this->save() : $this->save();

    $feedbackClass = $isSuccess ? 'feedback-success' : 'feedback-danger';
    $this->generateFeedback($feedback, self::FEEDBACK_MODE_SAVE, $feedbackClass);

    return $isSuccess;
  }

  public function softDelete($feedback = FALSE, $logInformation = FALSE, $logCode = FALSE, $isPostRequest = TRUE) {

    if ($isPostRequest && !Yii::$app->request->isPost) {
      throw new MethodNotAllowedHttpException(Yii::t('app', 'POST request needed to process this action.'));
    }


    $noChildren = TRUE;

//    foreach ($this->hasMany as $recordRelation) {
//      if (count($this->$recordRelation)) {
//        $hasRelatedRecords = TRUE;
//        break;
//      }
//    }

    if ($this->log) {
      $this->setlogCode($logCode ? : time());
      $this->setLogInformation($logInformation ? : ($this->isDeleted ? 'restore from `' . self::tableNameAsString() . '`' : 'delete to `' . self::tableNameAsString() . '`'));
    }

    if ($this->softDelete) {
      $isSuccess = $noChildren && $this->isDeleted ? $this->restore() : $this->softDelete();
    } else {
      $isSuccess = $noChildren && $this->delete();
    }

    $feedbackClass = $isSuccess ? 'feedback-success' : 'feedback-danger';
    $this->generateFeedback($feedback, self::FEEDBACK_MODE_DELETE, $feedbackClass);

    return $isSuccess;
  }

  public function viewAttributes() {
    return [];
  }

  public function search($query = FALSE, $joinWith = []) {

    $dataProvider = new ActiveDataProvider([
      'query' => $query ? : ($joinWith ? self::find()->joinWith($joinWith) : self::find()),
      'pagination' => [
        'pageSize' => $this->datatablePagesize,
      ],
      'key' => $this->keyName
    ]);

    $this->load(\Yii::$app->request->queryParams);

    if (!$this->validate()) {
      return $dataProvider;
    }

    foreach ($this->viewAttributes() as $attributeKey => $attributeValue) {

      if (is_int($attributeKey)) {
        $dataProvider->sort->attributes[] = $attributeValue;

        $likeAttribute = $attributeValue;
        $columnName = $this->tableName() . '.' . $attributeValue;
      } else {
        $dataProvider->sort->attributes[$attributeKey] = [
          'asc' => [$attributeValue => SORT_ASC],
          'desc' => [$attributeValue => SORT_DESC],
        ];

        $likeAttribute = $attributeKey;
        $columnName = $attributeValue;
      }

      $query->andFilterWhere(['like', $columnName, $this->$likeAttribute]);
    }

    return $dataProvider;
  }

  public function serialColumn() {
    return ['class' => SerialColumn::className()];
  }

  public function actionColumn() {
    return ['class' => ActionColumn::className(), 'keyName' => $this->keyName];
  }

  public function getGridColumns($serial = TRUE, $action = TRUE) {

    $columns = [];
    if ($serial) {
      $columns[] = $this->serialColumn();
    }

    foreach ($this->viewAttributes() as $attributeKey => $attributeValue) {
      if (is_int($attributeKey)) {
        $columns[] = [
          'attribute' => $attributeValue,
          'format' => 'text',
          'filterInputOptions' => [
            'class' => 'form-control',
            'placeholder' => \Yii::t('app', '{attributeLabel}', [
              'attributeLabel' => $this->getAttributeLabel($attributeValue),
            ]),
          ]
        ];
      } else {
        $columns[] = [
          'attribute' => $attributeKey,
          'value' => $attributeValue,
          'format' => 'text',
          'filterInputOptions' => [
            'class' => 'form-control',
            'placeholder' => \Yii::t('app', '{attributeLabel}', [
              'attributeLabel' => $this->getAttributeLabel($attributeValue),
            ]),
          ]
        ];
      }
    }

    if ($action) {
      $columns[] = $this->actionColumn();
    }

    return $columns;
  }

  public function getPublicData() {
    $publicData = [];
    foreach ($this->publicAttributes as $attributeKey => $attributeValue) {
      if (is_int($attributeKey)) {
        $attributeKey = $attributeValue;
      }

      if (strpos($attributeValue, ".") === FALSE) {
        $attributeData = $this->$attributeValue;
      } else {
        $modelRelations = explode('.', $attributeValue);
        $attributeData = FALSE;
        foreach ($modelRelations as $modelRelation) {
          $attributeData = $this->$modelRelation ? : NULL;
          if (!$attributeData) {
            break;
          }
        }

        if (is_string($attributeData)) {
          $attributeData = NULL;
        }
      }

      $publicData[$attributeKey] = $attributeData;
    }
    return $publicData;
  }

  public function getCreator() {
    return $this->hasOne(User::className(), ['id' => 'createdBy']);
  }

  public static function findActive() {
    return parent::find()->where(['isDeleted' => FALSE]);
  }

}
