<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int|null $inv_id
 * @property int|null $user_id
 * @property string|null $date
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inv_id', 'user_id'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inv_id' => 'Inv ID',
            'user_id' => 'User ID',
            'date' => 'Date',
        ];
    }
}
