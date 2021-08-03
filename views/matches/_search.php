<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Tutorials;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\TutorialsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tutorials-search">
    <div class="row">
    <?php $action = 'matches/date/'.$date; ?>
    <?php $form = ActiveForm::begin([
        'action' => [$action],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php 
        $tut = Tutorials::find()->where(['match_date' => $date])->orderBy(['country_n' => SORT_ASC,'league_name' => SORT_ASC])->all();
        $items1 = ArrayHelper::map($tut,'country_n','country_n');
        
        $params1 = [
            'prompt' => 'Выберите страну'
        ];
        
    ?>
        <div class="col-md-6">
    <?= $form->field($model, 'country_n')->dropDownList($items1,$params1)->label(false); ?>



    <?php // echo $form->field($model, 'league_name')->dropDownList($items2,$params2); ?>

    <?php // echo $form->field($model, 'match_date') ?>

    <?php // echo $form->field($model, 'match_time') ?>

    <?php // echo $form->field($model, 'match_status') ?>

    <?php // echo $form->field($model, 'match_hometeam_name') ?>

    <?php // echo $form->field($model, 'match_hometeam_score') ?>

    <?php // echo $form->field($model, 'match_awayteam_name') ?>

    <?php // echo $form->field($model, 'match_awayteam_score') ?>

    <?php // echo $form->field($model, 't1yc') ?>

    <?php // echo $form->field($model, 't2yc') ?>

    <?php // echo $form->field($model, 't1rc') ?>

    <?php // echo $form->field($model, 't2rc') ?>

    <?php // echo $form->field($model, 't1corners') ?>

    <?php // echo $form->field($model, 't2corners') ?>

    <?php // echo $form->field($model, 't1offsides') ?>

    <?php // echo $form->field($model, 't2offsides') ?>

    <?php // echo $form->field($model, 't1fouls') ?>

    <?php // echo $form->field($model, 't2fouls') ?>

    <?php // echo $form->field($model, 't1gk') ?>

    <?php // echo $form->field($model, 't2gk') ?>

    <?php // echo $form->field($model, 'match_hometeam_id') ?>

    <?php // echo $form->field($model, 'match_awayteam_id') ?>
    </div>
    <div class="col-md-6">
    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Очистить фильтры', [$action], ['class' => 'btn btn-outline-secondary']) ?>
        
    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
