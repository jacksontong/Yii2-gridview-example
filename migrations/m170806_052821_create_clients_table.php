<?php

use yii\db\Migration;
use Faker\Factory;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Handles the creation of table `clients`.
 */
class m170806_052821_create_clients_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $db = $this->db;
        $this->createTable('clients', [
            'id' => $this->primaryKey(),
            'company_name' => $this->string(),
            'website' => $this->string(),
            'notes' => $this->text(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        //auto generate fake data
        $faker = Factory::create();
        for ($i = 0; $i < 50; $i++) {
            $db->createCommand()->insert('clients', [
                'company_name' => $faker->company,
                'website' => $faker->domainName,
                'notes' => $faker->text(),
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()'),
            ])->execute();
        }

        $this->addColumn('customers', 'client_id', $this->integer());
        $this->addForeignKey('fk_customers_client', 'customers', 'client_id', 'clients', 'id', 'CASCADE', 'CASCADE');

        //generate customer client_id
        $clients = (new Query())->select('id')
            ->from('clients')
            ->all();
        $clientIds = ArrayHelper::map($clients, 'id', 'id');
        $customers = (new Query())->select('id')
            ->from('customers')
            ->all();

        foreach ($customers as $customer) {
            $clientId = $clientIds[array_rand($clientIds)];//get random value from client ids
            //UPDATE customers SET client_id = $clientId WHERE id = $customer['id']
            $db->createCommand()->update('customers', [
                'client_id' => $clientId,
            ], [
                'id' => $customer['id']
            ])->execute();
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_customers_client', 'customers');
        $this->dropColumn('customers', 'client_id');
        $this->dropTable('clients');
    }
}
