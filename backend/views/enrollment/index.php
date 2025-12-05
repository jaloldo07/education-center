<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Enrollments';
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .enrollment-index table thead a {
        color: #fff !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    .enrollment-index table thead a:hover {
        color: #e8e8e8 !important;
        text-decoration: none !important;
    }
</style>


<div class="enrollment-index">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><?= Html::encode($this->title) ?></h1>
                <?= Html::a('<i class="fas fa-plus"></i> New Enrollment', ['create'], ['class' => 'btn btn-success btn-hover']) ?>
            </div>
        </div>
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',

                    // ✅ Student - Clickable
                    [
                        'attribute' => 'student_id',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(
                                '<i class="fas fa-user-graduate text-primary"></i> ' . Html::encode($model->student->full_name),
                                ['view', 'id' => $model->id],
                                ['style' => 'text-decoration: none; font-weight: bold;']
                            );
                        },
                    ],

                    // ✅ Group - Clickable
                    [
                        'attribute' => 'group_id',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(
                                '<i class="fas fa-users text-success"></i> ' . Html::encode($model->group->name),
                                ['view', 'id' => $model->id],
                                ['style' => 'text-decoration: none;']
                            );
                        },
                    ],

                    [
                        'attribute' => 'enrolled_on',
                        'format' => ['date', 'php:Y-m-d'],
                    ],

                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $class = $model->status == 'active' ? 'success' : 'secondary';
                            return '<span class="badge bg-' . $class . '">' . ucfirst($model->status) . '</span>';
                        },
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-sm btn-info']);
                            },
                            'update' => function ($url) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, ['class' => 'btn btn-sm btn-primary']);
                            },
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data-confirm' => 'Are you sure?',
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