<?php

namespace lambda\neon\models;

use Yii;
use yii\bootstrap\Html;
use yii\db\Exception;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property integer $avatarId
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $address
 * @property integer $countryCode
 * @property integer $areaCode
 * @property integer $phoneNumber
 * @property integer $mobileNumber
 * @property string $username
 * @property string $passwordHash
 * @property string $configLanguage
 * @property string $configTheme
 * @property integer $status
 * @property integer $isDeleted
 * @property string $createdAt
 * @property integer $createdBy
 * @property string $updatedAt
 * @property integer $updatedBy
 * @property string $deletedAt
 * @property integer $deletedBy
 * @property string $userRoles
 * @property string $password
 * @property string $confirmPassword
 * @property string $authKey
 * @property string $accessToken
 * @property string $passwordResetToken
 */
class User extends ActiveRecord implements IdentityInterface {

  public $password, $confirmPassword;
  public $userRoles;

  const STATUS_ACTIVE = 1;
  const STATUS_INACTIVE = 0;
  const STATUS_TEMPORARILY_BANNED = -1;
  const STATUS_PERMANENTLY_BANNED = -2;

  public static function tableName() {
    return '{{%user}}';
  }

  public function rules() {
    return[
      [['username'], 'unique'],
      ['userRoles', 'required', 'on' => ['userRegistration', 'userUpdateInformation']],
      [['firstName', 'email', 'username'], 'required'],
      [['id', 'avatarId', 'countryCode', 'areaCode', 'phoneNumber', 'mobileNumber', 'status', 'createdBy', 'updatedBy'], 'integer'],
      [['email'], 'email'],
      [['firstName', 'lastName', 'authKey', 'accessToken', 'passwordResetToken', 'username', 'passwordHash',], 'string', 'max' => 255],
      [['address'], 'string'],
      [['createdAt', 'updatedAt', 'deletedAt'], 'safe'],
      [['isDeleted'], 'boolean'],
      [['password', 'confirmPassword'], 'string', 'max' => 255],
      ['confirmPassword', 'compare', 'compareAttribute' => 'password'],
      [['configLanguage', 'configTheme'], 'string', 'max' => 255],
      ['userRoles', 'each', 'rule' => ['string', 'max' => 64]],
      [['password', 'confirmPassword'], 'required', 'on' => 'userRegistration'],
    ];
  }

  public function attributeLabels() {
    return [
      'avatarId' => Yii::t('app', 'Avatar'),
      'firstName' => Yii::t('app', 'First Name'),
      'lastName' => Yii::t('app', 'Last Name'),
      'email' => Yii::t('app', 'Email'),
      'address' => Yii::t('app', 'Address'),
      'countryCode' => Yii::t('app', 'Country Code'),
      'areaCode' => Yii::t('app', 'Area Code'),
      'phoneNumber' => Yii::t('app', 'Phone Number'),
      'mobileNumber' => Yii::t('app', 'Mobile Number'),
      'username' => Yii::t('app', 'Username'),
      'passwordHash' => Yii::t('app', 'Password Hash'),
      'configLanguage' => Yii::t('app', 'Language'),
      'configTheme' => Yii::t('app', 'Theme'),
      'status' => Yii::t('app', 'Status'),
      'isDeleted' => Yii::t('app', 'Is Deleted'),
      'createdAt' => Yii::t('app', 'Created At'),
      'createdBy' => Yii::t('app', 'Created By'),
      'updatedAt' => Yii::t('app', 'Updated At'),
      'updatedBy' => Yii::t('app', 'Updated By'),
      'deletedAt' => Yii::t('app', 'Deleted At'),
      'deletedBy' => Yii::t('app', 'Deleted By'),
      'authKey' => Yii::t('app', 'Auth Key'),
      'accessToken' => Yii::t('app', 'Access Token'),
      'passwordResetToken' => Yii::t('app', 'Password Reset Token'),
      'password' => Yii::t('app', 'Password'),
      'confirmPassword' => Yii::t('app', 'Confirm Password'),
      'fullName' => Yii::t('app', 'Full Name'),
      'userRoles' => Yii::t('app', 'Role'),
      'fullName' => Yii::t('app', 'Fullname')
    ];
  }

  public static function findIdentity($id) {
    return static::findOne(['id' => $id]);
  }

  /**
   * @inheritdoc
   */
  public static function findIdentityByAccessToken($token, $type = null) {
    return static::findOne(['accessToken' => $token]);
  }

  /**
   * Finds user by username
   *
   * @param string $username
   * @return static|null
   */
  public static function findByUsername($username) {
    return static::findOne([
          'username' => $username,
          'status' => self::STATUS_ACTIVE,
          'isDeleted' => FALSE
    ]);
  }

  /**
   * Finds user by password reset token
   *
   * @param string $token password reset token
   * @return static|null
   */
  public static function findByPasswordResetToken($token) {
    if (!static::isPasswordResetTokenValid($token)) {
      return null;
    }

    return static::findOne([
          'passwordResetToken' => $token
    ]);
  }

  /**
   * Finds out if password reset token is valid
   *
   * @param string $token password reset token
   * @return boolean
   */
  public static function isPasswordResetTokenValid($token) {
    if (empty($token)) {
      return false;
    }
    $expire = Yii::$app->params['siteSetting']['passwordResetTokenExpire'];
    $parts = explode('_', $token);
    $timestamp = (int) end($parts);
    return $timestamp + $expire >= time();
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return $this->getPrimaryKey();
  }

  /**
   * @inheritdoc
   */
  public function getAuthKey() {
    return $this->authKey;
  }

  /**
   * @inheritdoc
   */
  public function validateAuthKey($authKey) {
    return $this->getAuthKey() === $authKey;
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return boolean if password provided is valid for current user
   */
  public function validatePassword($password) {
    return Yii::$app->security->validatePassword($password, $this->passwordHash);
  }

  /**
   * Generates password hash from password and sets it to the model
   *
   * @param string $password
   */
  public function setPassword($password) {
    $this->passwordHash = Yii::$app->security->generatePasswordHash($password);
  }

  /**
   * Generates "remember me" authentication key
   */
  public function generateAuthKey() {
    $this->authKey = Yii::$app->security->generateRandomString();
  }

  /**
   * Generates new password reset token
   */
  public function generatePasswordResetToken() {
    $this->passwordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
  }

  /**
   * Removes password reset token
   */
  public function removePasswordResetToken() {
    $this->passwordResetToken = NULL;
  }

  public static function userStatuses() {
    return [
      User::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
      User::STATUS_ACTIVE => Yii::t('app', 'Active'),
      User::STATUS_TEMPORARILY_BANNED => Yii::t('app', 'Temporarily Banned'),
      User::STATUS_PERMANENTLY_BANNED => Yii::t('app', 'Banned'),
    ];
  }

  public function getAvatar() {
    return $this->hasOne(AppFile::className(), ['id' => 'avatarId']);
  }

  public function getAvatarSource() {
    return $this->avatar ? $this->avatar->source : Yii::$app->request->baseUrl . Yii::$app->params['default']['avatar'];
  }

  public function getAvatarImage($options = []) {
    return Html::img($this->avatarSource, $options);
  }

  public function getRoles() {
    return ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($this->id), 'name');
  }

  public function getRegionAssignments() {
    return $this->hasMany(RegionAssignment::className(), ['userId' => 'id'])->inverseOf('user');
  }

  public function assignRoles() {
    $authManager = Yii::$app->authManager;
    $authManager->revokeAll($this->id);

    if ($this->userRoles) {
      foreach ($this->userRoles as $userRole) {
        $authRole = $authManager->getRole($userRole);
        if ($authRole && Yii::$app->user->can($userRole)) {
          $authManager->assign($authRole, $this->id);
        } else {
          $this->addError('userRoles', Yii::t('app', "You can't assign user with role above your authorized access"));
        }
      }
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function getFullname() {
    return $this->lastName ? $this->firstName . ' ' . $this->lastName : $this->firstName;
  }

  public function getUserStatus() {
    if ($this->status == self::STATUS_ACTIVE) {
      $userStatus = Yii::t('app', 'Active');
    } elseif ($this->status == self::STATUS_TEMPORARILY_BANNED) {
      $userStatus = Yii::t('app', 'Temporarily Banned');
    } elseif ($this->status == self::STATUS_PERMANENTLY_BANNED) {
      $userStatus = Yii::t('app', 'Permanently Banned');
    } else {
      $userStatus = Yii::t('app', 'Inactive');
    }
    return $userStatus;
  }

  public function register() {
    $this->setPassword($this->password);
    $pTransaction = self::getDb()->beginTransaction();
    try {
      $isSuccess = parent::saveRecord();
      $isSuccess ? $pTransaction->commit() && $this->sendRegistrationInformation() : $pTransaction->rollBack();
    } catch (Exception $eXDC) {
      $pTransaction->rollBack();
      $isSuccess = FALSE;
    }

    return $isSuccess;
  }

}
