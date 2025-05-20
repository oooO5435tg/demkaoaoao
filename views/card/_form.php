<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Condition;
use app\models\Binding;

/** @var yii\web\View $this */
/** @var app\models\Card $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="card-form">

    <?php $form = ActiveForm::begin(); 
        $condition = Condition::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();

        $binding = Binding::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'publication')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'publisher')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year_publication')->textInput() ?>

    <?= $form->field($model, 'publication_status')->radioList([ 'publish' => 'Publish', 'library' => 'Library', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'condition_id')->dropDownList($condition) ?>

    <?= $form->field($model, 'binding_id')->dropDownList($binding) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
