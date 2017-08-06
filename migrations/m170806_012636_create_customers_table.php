<?php

use Faker\Factory;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `customers`.
 */
class m170806_012636_create_customers_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('customers', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(20),
            'last_name' => $this->string(20),
            'email' => $this->string(),
            'address' => $this->string(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        //auto generate fake data
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $this->db->createCommand()->insert('customers', [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'address' => $faker->address,
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()'),
            ])->execute();
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('customers');
    }
}
