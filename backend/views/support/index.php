<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Support Tickets');
?>

<style>
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
        font-size: 32px;
        font-weight: bold;
        color: #667eea;
    }

    .stat-label {
        color: #999;
        font-size: 14px;
        text-transform: uppercase;
    }
</style>

<h1><?= Html::encode($this->title) ?></h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= $stats['total'] ?></div>
        <div class="stat-label"><?= Yii::t('app', 'Total') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['open'] ?></div>
        <div class="stat-label"><?= Yii::t('app', 'Open') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['replied'] ?></div>
        <div class="stat-label"><?= Yii::t('app', 'Replied') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['closed'] ?></div>
        <div class="stat-label"><?= Yii::t('app', 'Closed') ?></div>
    </div>
</div>

<div style="color: white; text-decoration: none; background: white; padding: 20px; border-radius: 10px;">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'user_id',
                'label' => Yii::t('app', 'User'),
                'value' => function ($model) {
                    return $model->user->username ?? Yii::t('app', 'Unknown');
                }
            ],
            [
                'attribute' => 'subject',
                'label' => Yii::t('app', 'Subject'),
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'label' => Yii::t('app', 'Status'),
                'value' => function ($model) {
                    $colors = [
                        'open' => 'warning',
                        'replied' => 'info',
                        'closed' => 'secondary',
                    ];
                    $color = $colors[$model->status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . Yii::t('app', strtoupper($model->status)) . '</span>';
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => Yii::t('app', 'Created At'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {reply} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-info',
                            'title' => Yii::t('app', 'View'),
                        ]);
                    },
                    'reply' => function ($url, $model) {
                        return Html::a('<i class="fas fa-reply"></i>', ['view', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-primary',
                            'title' => Yii::t('app', 'Reply'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-danger',
                            'title' => Yii::t('app', 'Delete'),
                            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
</div>