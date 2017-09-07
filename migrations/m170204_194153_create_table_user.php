<?php

use yii\db\Migration;

class m170204_194153_create_table_user extends Migration {

  public function safeUp() {

    $this->createTable('{{%user}}', [
      'id' => $this->bigPrimaryKey(),
      'avatarId' => $this->bigInteger(),
      'firstName' => $this->string()->notNull(),
      'lastName' => $this->string(),
      'email' => $this->string()->notNull(),
      'address' => $this->text(),
      'countryCode' => $this->smallInteger(),
      'areaCode' => $this->smallInteger(),
      'phoneNumber' => $this->bigInteger(),
      'mobileNumber' => $this->bigInteger(),
      'username' => $this->string()->notNull()->unique(),
      'passwordHash' => $this->string()->notNull(),
      'configLanguage' => $this->string(),
      'configTheme' => $this->string(),
      'status' => $this->integer(),
      'authKey' => $this->string(),
      'accessToken' => $this->string(),
      'passwordResetToken' => $this->string(),
      'isDeleted' => $this->boolean()->notNull()->defaultValue(FALSE),
      'createdAt' => $this->dateTime(),
      'createdBy' => $this->bigInteger(),
      'updatedAt' => $this->dateTime(),
      'updatedBy' => $this->bigInteger(),
      'deletedAt' => $this->dateTime(),
      'deletedBy' => $this->bigInteger(),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : NULL);
  }

  public function safeDown() {
    $this->dropTable('{{%user}}');
  }

}
