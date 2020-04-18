<?php
namespace app\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `inf_points`.
 */
class m200416_182107_create_news_table extends Migration
{
    const TABLE_NAME = 'inf_points';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey()->comment('Идентификатор'),
            'name' => $this->string()->notNull()->comment('Название'),
            'description' => $this->text()->comment('Описание'),
            'type' => $this->string()->notNull()->comment('Тип'),
            'lon' => $this->double()->notNull()->comment('Долгота'),
            'lat' => $this->double()->notNull()->comment('Широта'),
        ]);
        $this->batchInsert(
            self::TABLE_NAME,['id', 'name', 'description', 'type', 'lat', 'lon'],
            [
                [
                    '1',
                    'Местро фитнес',
                    'Хорошее место',
                    'Спортивный зал',
                    '57.985275',
                    '56.210899',
                ],        
                [
                    '2',
                    'Статуя Ленина',
                    'Хорошее место',
                    'Памятник',
                    '58.014525',
                    '56.247243',
                ],        
                [
                    '3',
                    'Колизей',
                    'Хорошее место',
                    'Торговый центр',
                    '58.008937',
                    '56.236225',
                ],
                [
                    '4',
                    'X-fit',
                    'Хорошее место',
                    'Спортивный зал',
                    '58.001822',
                    '56.253891',
                ],        
                [
                    '5',
                    'Пермяк соленые уши',
                    'Хорошее место',
                    'Памятник',
                    '58.009719',
                    '56.239786',
                ],        
                [
                    '6',
                    'ТРК Семья',
                    'Хорошее место',
                    'Торговый центр',
                    '58.007336',
                    '56.261162',
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}

