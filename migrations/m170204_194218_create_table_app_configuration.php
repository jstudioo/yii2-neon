<?php

use yii\db\Migration;

class m170204_194218_create_table_app_configuration extends Migration {

  public function safeUp() {
    $this->createTable('{{%app_configuration}}', [
      'id' => $this->bigPrimaryKey(),
      'key' => $this->string()->notNull(),
      'index' => $this->string(),
      'value' => $this->text()->notNull(),
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
    $this->dropTable('{{%app_configuration}}');
  }

}
