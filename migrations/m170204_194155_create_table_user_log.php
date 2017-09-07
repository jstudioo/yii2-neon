<?php

use yii\db\Migration;

class m170204_194155_create_table_user_log extends Migration {

  public function safeUp() {
    $this->createTable('{{%user_log}}', [
      'id' => $this->bigPrimaryKey(),
      'userId' => $this->bigInteger()->notNull(),
      'activity' => $this->string()->notNull(),
      'userIP' => $this->string(),
      'userHost' => $this->string(),
      'portRequest' => $this->string(),
      'hostName' => $this->string(),
      'userAgent' => $this->string(),
      'createdAt' => $this->dateTime()->notNull(),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : NULL);

    $this->addForeignKey('userLogCredential', 'user_log', 'userId', 'user', 'id');
  }

  public function safeDown() {
    $this->dropTable('{{%user_log}}');
  }

}
