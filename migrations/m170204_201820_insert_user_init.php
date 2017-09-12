<?php

use yii\db\Migration;

class m170204_201820_insert_user_init extends Migration {

  private $superUserId = 1;
  private $superUserRole = 'SuperUser';
  private $superUserDecription = 'Super User';
  private $administratorId = 2;
  private $administratorRole = 'Administrator';
  private $administratorDecription = 'Administrator';
  private $userId = 3;
  private $userRole = 'User';
  private $userDecription = 'User';

  public function safeUp() {
    $this->insert('{{%user}}', [
      'id' => $this->superUserId,
      'firstName' => 'Mohammad',
      'lastName' => 'Nadzif',
      'email' => 'nadzif.global@gmail.com',
      'address' => '-',
      'countryCode' => '62',
      'areaCode' => '341',
      'phoneNumber' => NULL,
      'mobileNumber' => '85749360666',
      'username' => 'superuser',
      'status' => 1,
      'passwordHash' => Yii::$app->security->generatePasswordHash('lambdaTesseract'),
      'configLanguage' => 'en',
      'configTheme' => 'default',
      'createdAt' => date('Y-m-d H:i:s'),
    ]);

    $this->insert('{{%user}}', [
      'id' => $this->administratorId,
      'firstName' => 'Bambang',
      'lastName' => 'Saputro',
      'email' => 'my.bambangsaputro@gmail.com',
      'address' => '-',
      'countryCode' => '62',
      'areaCode' => '341',
      'phoneNumber' => NULL,
      'mobileNumber' => '81333330600',
      'username' => 'administrator',
      'status' => 1,
      'passwordHash' => Yii::$app->security->generatePasswordHash('lambdaTesseract'),
      'configLanguage' => 'en',
      'configTheme' => 'default',
      'createdAt' => date('Y-m-d H:i:s'),
    ]);

    $this->insert('{{%user}}', [
      'id' => $this->userId,
      'firstName' => 'Yusuf',
      'lastName' => 'Wardata',
      'email' => 'wardata.lambda@gmail.com',
      'address' => '-',
      'countryCode' => '62',
      'areaCode' => '341',
      'phoneNumber' => NULL,
      'mobileNumber' => '85731639726',
      'username' => 'user',
      'status' => 1,
      'passwordHash' => Yii::$app->security->generatePasswordHash('lambdaTesseract'),
      'configLanguage' => 'en',
      'configTheme' => 'default',
      'createdAt' => date('Y-m-d H:i:s'),
    ]);

    Yii::$app->runAction('migrate', ['migrationPath' => '@yii/rbac/migrations/']);

    $authManager = Yii::$app->authManager;
    $superUserRole = $authManager->createRole($this->superUserRole);
    $superUserRole->description = $this->superUserDecription;
    $authManager->add($superUserRole);
    $authManager->assign($superUserRole, $this->superUserId);

    $administratorRole = $authManager->createRole($this->administratorRole);
    $administratorRole->description = $this->administratorDecription;
    $authManager->add($administratorRole);
    $authManager->assign($administratorRole, $this->administratorId);

    $authManager->addChild($superUserRole, $administratorRole);

    $userRole = $authManager->createRole($this->userRole);
    $userRole->description = $this->userDecription;
    $authManager->add($userRole);
    $authManager->assign($userRole, $this->userId);

    $authManager->addChild($administratorRole, $userRole);
  }

  public function safeDown() {

    $pTransaction = $this->getDb()->beginTransaction();
    try {
      $authManager = Yii::$app->authManager;
      $superUser = $authManager->getRole($this->superUserRole);
      $this->delete('{{%user}}', ['id' => $this->superUserId]);
      $authManager->revoke($superUser, $this->superUserId);
      $authManager->remove($superUser);
    } catch (Exception $eXDC) {
      $pTransaction->rollBack();
      echo $eXDC->getTraceAsString();
    }

    $pTransaction->commit();
  }

}
