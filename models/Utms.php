<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "utms".
 *
 * @property int $id
 * @property int|null $invoice_id
 * @property string|null $utm_source
 * @property string|null $utm_medium
 * @property string|null $utm_campaign
 * @property string|null $utm_term
 * @property string|null $utm_content
 * @property string|null $referer
 */
class Utms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id'], 'integer'],
            [['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'referer'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'utm_source' => 'Utm Source',
            'utm_medium' => 'Utm Medium',
            'utm_campaign' => 'Utm Campaign',
            'utm_term' => 'Utm Term',
            'utm_content' => 'Utm Content',
            'referer' => 'Referer',
        ];
    }
}
