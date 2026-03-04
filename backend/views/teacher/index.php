<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Teachers');
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .teacher-index table thead a {
        color: #fff !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    .teacher-index table thead a:hover {
        color: #e8e8e8 !important;
        text-decoration: none !important;
    }
</style>

<div class="teacher-index">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><?= Html::encode($this->title) ?></h1>
                <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Add Teacher'), ['create'], ['class' => 'btn btn-success btn-hover']) ?>
            </div>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'full_name',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Full Name'),
                        'value' => function ($model) {
                            return Html::a(
                                '<i class="fas fa-chalkboard-teacher text-success"></i> ' . Html::encode($model->full_name),
                                ['/teacher/view', 'id' => $model->id],
                                ['style' => 'text-decoration: none; font-weight: bold;']
                            );
                        },
                    ],
                    [
                        'attribute' => 'subject',
                        'label' => Yii::t('app', 'Subject'),
                    ],
                    [
                        'attribute' => 'experience_years',
                        'label' => Yii::t('app', 'Experience (Years)'),
                    ],
                    [
                        'attribute' => 'rating',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Rating'),
                        'value' => function ($model) {
                            $stars = '';
                            for ($i = 0; $i < floor($model->rating); $i++) {
                                $stars .= '<i class="fas fa-star text-warning"></i>';
                            }
                            if ($model->rating - floor($model->rating) >= 0.5) {
                                $stars .= '<i class="fas fa-star-half-alt text-warning"></i>';
                            }
                            return $stars . ' ' . $model->rating;
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:Y-m-d H:i'],
                        'label' => Yii::t('app', 'Created At'),
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'class' => 'btn btn-sm btn-info',
                                    'title' => Yii::t('app', 'View'),
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'class' => 'btn btn-sm btn-primary',
                                    'title' => Yii::t('app', 'Update'),
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this teacher?'),
                                    'data-method' => 'post',
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>