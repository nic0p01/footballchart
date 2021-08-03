<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
mihaildev\elfinder\Assets::noConflict($this);

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'content')->widget(CKEditor::className(), [
                    'editorOptions' => ElFinder::ckeditorOptions(['elfinder', 'path' => 'some/sub/path'],[]),
                ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'image')->fileInput()->label('Основное фото') ?>
                            <img class="img-preview" src="/web/upload/<?= $model->image ?>" alt="">
        </div>
    </div>

    

    

    

   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
