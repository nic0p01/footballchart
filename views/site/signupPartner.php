<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 
$this->title = 'Регистрация';
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="text-center">
        <p>Пожалуйста заполните поля для дальнейшей авторизации на сайте.</p>
    </div>
    
    
    <div class="sform">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
        <p>Используя Footballcharts вы соглашаетесь с <a class="under-line" href="/contract">условиями обслуживания</a></p>
                <div class="form-group">
                    <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
    </div>
            
 
        
</div>
