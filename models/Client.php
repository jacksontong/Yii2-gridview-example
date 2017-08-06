<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "clients".
 *
 * @property integer $id
 * @property string $company_name
 * @property string $website
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer[] $customers
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @var int
     */
    public $search_customer_count;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notes'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['company_name', 'website'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_name' => 'Company Name',
            'website' => 'Website',
            'notes' => 'Notes',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'search_customer_count' => 'Number Of Customers'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['client_id' => 'id']);
    }
}
