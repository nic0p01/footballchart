<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "predictions".
 *
 * @property int $id
 * @property int $match_id
 * @property double $prob_HW
 * @property double $prob_D
 * @property double $prob_AW
 * @property double $prob_O
 * @property double $prob_U
 * @property double $prob_bts
 * @property double $prob_ots
 */
class Predictions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'predictions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['match_id', 'prob_HW', 'prob_D', 'prob_AW', 'prob_O', 'prob_U', 'prob_bts', 'prob_ots'], 'required'],
            [['match_id'], 'integer'],
            [['prob_HW', 'prob_D', 'prob_AW', 'prob_O', 'prob_U', 'prob_bts', 'prob_ots'], 'number'],
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
            'prob_HW' => 'Prob Hw',
            'prob_D' => 'Prob D',
            'prob_AW' => 'Prob Aw',
            'prob_O' => 'Prob O',
            'prob_U' => 'Prob U',
            'prob_bts' => 'Prob Bts',
            'prob_ots' => 'Prob Ots',
        ];
    }
}
