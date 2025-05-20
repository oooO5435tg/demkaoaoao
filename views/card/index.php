<?php

use app\models\Card;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin()) {
        echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'author',
            'publication',
            'publisher',
            //'year_publication',
            //'publication_status',
            //'condition_id',
            'status_id',
            //'binding_id',
            'cancellation_reason',
            //'user_id',
            'isDelete',
            [
                'class' => ActionColumn::className(),
                'template' => '{update}',
                'urlCreator' => function ($action, Card $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'buttons' => [
                    'update' => function($url, $model)
                    {
                        if ($model->status_id == 1 && $model->isDelete == 0)
                        {
                            return Html::a('<span class="btn btn-primary" type="button">Ответить на заявку</span>', $url);
                        }
                    }
                ]
            ],
        ],
        ]);
    } else if (!Yii::$app->user->isGuest && !Yii::$app->user->identity->isAdmin())
    {
        echo Html::a('Подать заявку', ['create'], ['class' => 'btn btn-success']);
        echo ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'row g-4'],
        'itemOptions' => ['class' => 'col-md-4'],
        'itemView' => function ($model, $key, $index, $widget) {
            $delete = ($model->isDelete == 0) ? Html::button('Удалить карточку', ['delete', 'id' => $model->id]) : "";
            return '
            <div class="card shadow-sm">
                <p>Название: </p>' . $model->name .'
                <p>Автор: </p>' . $model->author .'
                <p>Статус: </p>' . $model->status_id .'
                <p>Удалено: </p>' . $model->isDelete .'
                '. $delete .'
            </div>
            ';
        }
        // 'columns' => [
        //     ['class' => 'yii\grid\SerialColumn'],

        //     'id',
        //     'name',
        //     'author',
        //     'publication',
        //     'publisher',
        //     //'year_publication',
        //     //'publication_status',
        //     //'condition_id',
        //     //'status_id',
        //     //'binding_id',
        //     //'cancellation_reason',
        //     //'user_id',
        //     'isDelete',
        //     [
        //         'class' => ActionColumn::className(),
        //         'template' => '{delete}',
        //         'urlCreator' => function ($action, Card $model, $key, $index, $column) {
        //             return Url::toRoute([$action, 'id' => $model->id]);
        //         },
        //         'buttons' => [
        //             'delete' => function($url, $model)
        //             {
        //                 if ($model->isDelete == 0)
        //                 {
        //                     return Html::a('<span class="btn btn-primary" type="button">Удалить карточку</span>', $url);
        //                 }
        //             }
        //         ]
        //     ],
        // ],
        ]);
    }

    ?>


</div>
