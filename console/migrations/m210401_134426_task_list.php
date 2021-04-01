<?php

use yii\db\Migration;

/**
 * Class m210401_134426_task_list
 */
class m210401_134426_task_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210401_134426_task_list cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210401_134426_task_list cannot be reverted.\n";

        return false;
    }
    */
}
