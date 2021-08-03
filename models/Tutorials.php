<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tutorials".
 *
 * @property int $id
 * @property int $match_id
 * @property int $country_id
 * @property string $country_n
 * @property int $league_id
 * @property string $league_name
 * @property string $match_date
 * @property string $match_time
 * @property string $match_status
 * @property string $match_hometeam_name
 * @property int $match_hometeam_score
 * @property string $match_awayteam_name
 * @property int $match_awayteam_score
 * @property int $t1yc
 * @property int $t2yc
 * @property int $t1rc
 * @property int $t2rc
 * @property int $t1corners
 * @property int $t2corners
 * @property int $t1offsides
 * @property int $t2offsides
 * @property int $t1fouls
 * @property int $t2fouls
 * @property int $t1gk
 * @property int $t2gk
 * @property int $match_hometeam_id
 * @property int $match_awayteam_id
 */
class Tutorials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutorials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'match_id', 'country_id', 'league_id', 'league_name', 'match_status'], 'required'],
            [['id', 'match_id', 'country_id', 'league_id', 'match_hometeam_score', 'match_awayteam_score', 't1yc', 't2yc', 't1rc', 't2rc', 't1corners', 't2corners', 't1offsides', 't2offsides', 't1fouls', 't2fouls', 't1gk', 't2gk', 'match_hometeam_id', 'match_awayteam_id'], 'integer'],
            [['match_date'], 'safe'],
            [['country_n', 'league_name', 'match_status'], 'string', 'max' => 100],
            [['match_time'], 'string', 'max' => 50],
            [['match_hometeam_name', 'match_awayteam_name'], 'string', 'max' => 255],
        ];
    }
    
    public function getPred()
    {
        return $this->hasOne(Predictions::className(), ['match_id' => 'match_id']);
            
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'match_id' => 'Match ID',
            'country_id' => 'Country ID',
            'country_n' => 'Country N',
            'league_id' => 'League ID',
            'league_name' => 'League Name',
            'match_date' => 'Match Date',
            'match_time' => 'Match Time',
            'match_status' => 'Match Status',
            'match_hometeam_name' => 'Match Hometeam Name',
            'match_hometeam_score' => 'Match Hometeam Score',
            'match_awayteam_name' => 'Match Awayteam Name',
            'match_awayteam_score' => 'Match Awayteam Score',
            't1yc' => 'T1yc',
            't2yc' => 'T2yc',
            't1rc' => 'T1rc',
            't2rc' => 'T2rc',
            't1corners' => 'T1corners',
            't2corners' => 'T2corners',
            't1offsides' => 'T1offsides',
            't2offsides' => 'T2offsides',
            't1fouls' => 'T1fouls',
            't2fouls' => 'T2fouls',
            't1gk' => 'T1gk',
            't2gk' => 'T2gk',
            'match_hometeam_id' => 'Match Hometeam ID',
            'match_awayteam_id' => 'Match Awayteam ID',
        ];
    }
}
