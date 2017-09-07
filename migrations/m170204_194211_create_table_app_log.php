<?php

use yii\db\Migration;

class m170204_194211_create_table_app_log extends Migration {

  public function safeUp() {
    $this->createTable('{{%app_log}}', [
      'id' => $this->bigPrimaryKey(),
      'logCode' => $this->string(),
      'tableName' => $this->string()->notNull(),
      'recordId' => $this->bigInteger()->notNull(),
      'userIdentity' => $this->string()->notNull(),
      'userId' => $this->string()->notNull(),
      'logAction' => $this->string()->notNull(),
      'isUpdated' => $this->boolean()->notNull()->defaultValue(TRUE),
      'recordBefore' => $this->text()->null(),
      'recordAfter' => $this->text()->null(),
      'logInformation' => $this->string(),
      'logTrail' => $this->text(),
      'createdAt' => $this->dateTime(),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : NULL);
  }

  public function safeDown() {
    $this->dropTable('{{%app_log}}');
  }

}
