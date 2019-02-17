<?php

use yii\db\Migration;

/**
 * Handles the creation for table `{{%profile}}`.
 */
class m190214_093229_create_table_profile extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%profile}}', [

            'id' => $this->primaryKey()->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'avatar' => $this->string(255),
            'first_name' => $this->string(32),
            'second_name' => $this->string(32),
            'middle_name' => $this->string(32),
            'birthday' => $this->integer(11),
            'gender' => $this->smallInteger(6),

        ]);
 
        // creates index for column `user_id`
        $this->createIndex(
            'profile_ibfk_1',
            '{{%profile}}',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'profile_ibfk_1',
            '{{%profile}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'profile_ibfk_1',
            '{{%profile}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'profile_ibfk_1',
            '{{%profile}}'
        );

        $this->dropTable('{{%profile}}');
    }
}
