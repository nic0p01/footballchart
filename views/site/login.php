<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="site-login">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div>{error}</div>",
            'labelOptions' => ['class' => 'col-lg-4 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true])->error() ?>

        <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div style=\"text-align: center;\" class=\"col-lg-12\">{input} {label}</div>\n<div>{error}</div>",
        ])->label('Запомнить') ?>
    <div style="text-align: center; margin-bottom: 10px;">
        Если вы забыли пароль, вы можете <?= Html::a('сбросить его', ['/request-password-reset'],['class' => 'rpr']) ?>.
    </div>
        <div class="form-group">
            <div style="text-align: center;" class="col-lg-12">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

        
</div>
