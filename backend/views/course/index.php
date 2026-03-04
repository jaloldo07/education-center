<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Courses');
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .course-index table thead a {
        color: #fff !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    .course-index table thead a:hover {
        color: #e8e8e8 !important;
        text-decoration: none !important;
    }
</style>


<div class="course-index">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><?= Html::encode($this->title) ?></h1>
                <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Add Course'), ['create'], ['class' => 'btn btn-success btn-hover']) ?>
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
                        'attribute' => 'name',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Name'),
                        'value' => function ($model) {
                            return Html::a(
                                '<i class="fas fa-book text-info"></i> ' . Html::encode($model->name),
                                ['/course/view', 'id' => $model->id],
                                ['style' => 'text-decoration: none; font-weight: bold;']
                            );
                        },
                    ],
                    [
                        'attribute' => 'duration',
                        'label' => Yii::t('app', 'Duration'),
                        'value' => function ($model) {
                            return $model->duration . ' ' . Yii::t('app', 'months');
                        }
                    ],
                    [
                        'attribute' => 'type',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Type'),
                        'value' => function ($model) {
                            $badge = $model->type === 'free' ? 'success' : 'warning';
                            return '<span class="badge bg-' . $badge . '">' . strtoupper($model->type) . '</span>';
                        },
                        'filter' => \common\models\Course::getTypeOptions(),
                    ],
                    [
                        'attribute' => 'price',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Price'),
                        'value' => function ($model) {
                            return number_format($model->price, 0) . ' ' . Yii::t('app', 'UZS');
                        }
                    ],
                    [
                        'attribute' => 'teacher_id',
                        'label' => Yii::t('app', 'Teacher'),
                        'value' => function ($model) {
                            return $model->teacher->full_name ?? Yii::t('app', 'N/A');
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:Y-m-d'],
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
                                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this course?'),
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