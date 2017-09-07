<?php

use yii\db\Migration;

class m170204_194140_init_languages extends Migration {

  public function safeUp() {
    Yii::$app->runAction('migrate', ['migrationPath' => '@yii/i18n/migrations/']);
  }

  public function safeDown() {
    echo "m170729_094305_init_dual_language cannot be reverted.\n";

    return false;
  }

  /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
    echo "m170729_094305_init_dual_language cannot be reverted.\n";

    return false;
    }
   */
}
