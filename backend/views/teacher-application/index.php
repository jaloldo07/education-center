<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\TeacherApplication;

$this->title = Yii::t('app', 'Teacher Applications');
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
                        'label' => Yii::t('app', 'Full Name'),
                        'value' => function($model) {
                            return Html::a(
                                '<i class="fas fa-user text-primary"></i> ' . Html::encode($model->full_name),
                                ['view', 'id' => $model->id],
                                ['style' => 'text-decoration: none; font-weight: bold;']
                            );
                        },
                    ],
                    
                    'email:email',
                    [
                        'attribute' => 'subject',
                        'label' => Yii::t('app', 'Subject'),
                    ],
                    [
                        'attribute' => 'experience_years',
                        'label' => Yii::t('app', 'Experience (Years)'),
                    ],
                    
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Status'),
                        'value' => function ($model) {
                            $class = TeacherApplication::getStatusBadgeClass($model->status);
                            // Statuslarni tarjima qilish kerak bo'lsa, bu yerda arraydan olish mumkin
                            // Lekin modelda getStatusOptions() borligini hisobga olib:
                            $text = TeacherApplication::getStatusOptions()[$model->status]; 
                            return '<span class="badge bg-' . $class . '">' . Yii::t('app', $text) . '</span>';
                        },
                        'filter' => TeacherApplication::getStatusOptions(),
                    ],
                    
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:Y-m-d H:i'],
                        'label' => Yii::t('app', 'Applied At'),
                    ],
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'class' => 'btn btn-sm btn-info',
                                    'title' => Yii::t('app', 'View'),
                                ]);
                            },
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('app', 'Are you sure?'),
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