<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Groups');
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .group-index table thead a {
        color: #fff !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    .group-index table thead a:hover {
        color: #e8e8e8 !important;
        text-decoration: none !important;
    }
</style>

<div class="group-index">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-white"><?= Html::encode($this->title) ?></h4>
                <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Add Group'), ['create'], ['class' => 'btn btn-light text-primary fw-bold']) ?>
            </div>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover align-middle'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    // 'id',
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Name'),
                        'value' => function ($model) {
                            return Html::a(
                                '<i class="fas fa-users text-warning me-1"></i> ' . Html::encode($model->name),
                                ['/group/view', 'id' => $model->id],
                                ['class' => 'text-decoration-none fw-bold text-dark']
                            );
                        },
                    ],
                    [
                        'attribute' => 'course_id',
                        'label' => Yii::t('app', 'Course'),
                        'value' => function ($model) {
                            return $model->course->name ?? Yii::t('app', 'N/A');
                        }
                    ],
                    [
                        'attribute' => 'teacher_id',
                        'label' => Yii::t('app', 'Teacher'),
                        'value' => function ($model) {
                            return $model->teacher->full_name ?? Yii::t('app', 'N/A');
                        }
                    ],
                    
                    // 🔥 YANGI USTUNLAR
                    'schedule',
                    'room',
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function($model) {
                            $colors = [
                                'active' => 'success',
                                'pending' => 'warning',
                                'finished' => 'secondary'
                            ];
                            $color = $colors[$model->status] ?? 'primary';
                            return "<span class='badge bg-{$color}'>" . ucfirst($model->status) . "</span>";
                        }
                    ],

                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:Y-m-d'],
                        'label' => Yii::t('app', 'Created'),
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-sm btn-info text-white me-1']);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, ['class' => 'btn btn-sm btn-primary me-1']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data-confirm' => Yii::t('app', 'Delete group?'),
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