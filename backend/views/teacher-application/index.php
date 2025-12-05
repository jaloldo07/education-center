<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\TeacherApplication;

$this->title = 'Teacher Applications';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.teacher-application-index table thead a {
    color: #fff !important;
    font-weight: 600;
    text-decoration: none !important;
}
.teacher-application-index table thead a:hover {
    color: #e8e8e8 !important;
    text-decoration: none !important;
}
</style>

<div class="teacher-application-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-file-alt"></i> <?= Html::encode($this->title) ?></h1>
    </div>
    
    <div class="card shadow">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    
                    'id',
                    
                    // ✅ Full Name - Clickable
                    [
                        'attribute' => 'full_name',
                        'format' => 'raw',
                        'value' => function($model) {
                            return Html::a(
                                '<i class="fas fa-user text-primary"></i> ' . Html::encode($model->full_name),
                                ['view', 'id' => $model->id],
                                ['style' => 'text-decoration: none; font-weight: bold;']
                            );
                        },
                    ],
                    
                    'email:email',
                    'subject',
                    'experience_years',
                    
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $class = TeacherApplication::getStatusBadgeClass($model->status);
                            $text = TeacherApplication::getStatusOptions()[$model->status];
                            return '<span class="badge bg-' . $class . '">' . $text . '</span>';
                        },
                        'filter' => TeacherApplication::getStatusOptions(),
                    ],
                    
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:Y-m-d H:i'],
                        'label' => 'Applied At',
                    ],
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'class' => 'btn btn-sm btn-info',
                                    'title' => 'View',
                                ]);
                            },
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => 'Delete',
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