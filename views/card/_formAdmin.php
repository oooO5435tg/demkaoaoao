<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Status;

/** @var yii\web\View $this */
/** @var app\models\Card $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="card-form">

    <?php $form = ActiveForm::begin(); 
        $status = Status::find()
            ->select(['name'])
            ->indexBy('id')
            ->column();
    ?>

    <?= $form->field($model, 'status_id')->dropDownList($status) ?>

    <?= $form->field($model, 'cancellation_reason')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
