<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "odds".
 *
 * @property int $id
 * @property string $match_id
 * @property string $odd_bookmakers
 * @property string $odd_1
 * @property string $odd_x
 * @property string $odd_2
 * @property string $bts_yes
 * @property string $bts_no
 */
class Odds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'odds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['match_id', 'odd_bookmakers', 'odd_1', 'odd_x', 'odd_2', 'bts_yes', 'bts_no', 'o25', 'u25'], 'string', 'max' => 255],
            [['odd_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Match ID',
            'odd_bookmakers' => 'Odd Bookmakers',
            'odd_1' => 'Odd 1',
            'odd_x' => 'Odd X',
            'odd_2' => 'Odd 2',
            'bts_yes' => 'Bts Yes',
            'bts_no' => 'Bts No',
        ];
    }
}
