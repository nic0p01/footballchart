<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tutorials;

/**
 * TutorialsSearch represents the model behind the search form of `app\models\Tutorials`.
 */
class TutorialsSearch extends Tutorials
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'match_id', 'country_id', 'league_id', 'match_hometeam_score', 'match_awayteam_score', 't1yc', 't2yc', 't1rc', 't2rc', 't1corners', 't2corners', 't1offsides', 't2offsides', 't1fouls', 't2fouls', 't1gk', 't2gk', 'match_hometeam_id', 'match_awayteam_id'], 'integer'],
            [['country_n', 'league_name', 'match_date', 'match_time', 'match_status', 'match_hometeam_name', 'match_awayteam_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $date)
    {
        $query = Tutorials::find()->orderBy(['country_n' => SORT_ASC,'league_name' => SORT_ASC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
//             $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'match_id' => $this->match_id,
            'country_id' => $this->country_id,
            'league_id' => $this->league_id,
            'match_date' => $date,
            'match_hometeam_score' => $this->match_hometeam_score,
            'match_awayteam_score' => $this->match_awayteam_score,
            't1yc' => $this->t1yc,
            't2yc' => $this->t2yc,
            't1rc' => $this->t1rc,
            't2rc' => $this->t2rc,
            't1corners' => $this->t1corners,
            't2corners' => $this->t2corners,
            't1offsides' => $this->t1offsides,
            't2offsides' => $this->t2offsides,
            't1fouls' => $this->t1fouls,
            't2fouls' => $this->t2fouls,
            't1gk' => $this->t1gk,
            't2gk' => $this->t2gk,
            'match_hometeam_id' => $this->match_hometeam_id,
            'match_awayteam_id' => $this->match_awayteam_id,
        ]);

        $query->andFilterWhere(['like', 'country_n', $this->country_n])
            ->andFilterWhere(['like', 'league_name', $this->league_name])
            ->andFilterWhere(['like', 'match_time', $this->match_time])
            ->andFilterWhere(['like', 'match_status', $this->match_status])
            ->andFilterWhere(['like', 'match_hometeam_name', $this->match_hometeam_name])
            ->andFilterWhere(['like', 'match_awayteam_name', $this->match_awayteam_name]);

        return $dataProvider;
    }
}
