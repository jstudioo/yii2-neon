<?php

use yii\db\Migration;

class m170204_194354_create_table_app_file extends Migration {

  public function safeUp() {
    $this->createTable('{{%app_file}}', [
      'id' => $this->bigPrimaryKey(),
      'originalName' => $this->string()->notNull(),
      'fileName' => $this->string()->notNull(),
      'thumbnailFileName' => $this->string()->null(),
      'pathAlias' => $this->string()->notNull(),
      'fileUri' => $this->text(),
      'fileSize' => $this->double()->notNull(),
      'fileExtension' => $this->string()->notNull(),
      'mimeType' => $this->string()->notNull(),
      'additionalInformation' => $this->text(),
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
    $this->dropTable('{{%app_file}}');
  }

}
