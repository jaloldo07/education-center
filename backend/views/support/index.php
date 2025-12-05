<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Support Tickets';
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

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= $stats['total'] ?></div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['open'] ?></div>
        <div class="stat-label">Open</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['replied'] ?></div>
        <div class="stat-label">Replied</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $stats['closed'] ?></div>
        <div class="stat-label">Closed</div>
    </div>
</div>

<!-- Tickets List -->
<div style="background: white; padding: 20px; border-radius: 10px;">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'user_id',
                'label' => 'User',
                'value' => function ($model) {
                    return $model->user->username ?? 'Unknown';
                }
            ],
            'subject',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    $colors = [
                        'open' => 'warning',
                        'replied' => 'info',
                        'closed' => 'secondary',
                    ];
                    $color = $colors[$model->status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . strtoupper($model->status) . '</span>';
                }
            ],
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {reply} {delete}',
                
                    
                    
                
            ],
        ],
    ]); ?>
</div>