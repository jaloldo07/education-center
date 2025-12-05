<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\SupportTicket $model */

$this->title = 'Ticket #' . $model->id . ': ' . $model->subject;
$this->params['breadcrumbs'][] = ['label' => 'Support Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="support-ticket-view">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0"><?= Html::encode($this->title) ?></h3>
                    <div>
                        <?php if ($model->status !== 'closed'): ?>
                            <?= Html::a('Close Ticket', ['update-status', 'id' => $model->id, 'status' => 'closed'], [
                                'class' => 'btn btn-warning',
                                'data' => [
                                    'confirm' => 'Are you sure you want to close this ticket?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this ticket?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Ticket Status Badge -->
                    <div class="mb-3">
                        <?php
                        $statusClass = [
                            'open' => 'badge bg-warning',
                            'replied' => 'badge bg-success',
                            'closed' => 'badge bg-secondary',
                        ];
                        ?>
                        <span class="<?= $statusClass[$model->status] ?? 'badge bg-secondary' ?>">
                            <?= strtoupper($model->status) ?>
                        </span>
                    </div>

                    <!-- Ticket Details -->
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'user_id',
                                'value' => $model->user->username ?? 'Unknown',
                                'label' => 'User',
                            ],
                            'subject',
                            [
                                'attribute' => 'message',
                                'format' => 'ntext',
                                'label' => 'User Message',
                            ],
                            'created_at:datetime',
                        ],
                    ]) ?>

                    <!-- Admin Reply Section -->
                    <?php if ($model->admin_reply): ?>
                        <div class="alert alert-info mt-4">
                            <h5><i class="bi bi-reply"></i> Admin Reply:</h5>
                            <p><?= nl2br(Html::encode($model->admin_reply)) ?></p>
                            <small class="text-muted">Replied at: <?= Yii::$app->formatter->asDatetime($model->admin_replied_at) ?></small>
                        </div>
                    <?php endif; ?>

                    <!-- Reply Form -->
                    <div class="mt-4">
                        <h4><?= $model->admin_reply ? 'Update Reply' : 'Reply to Ticket' ?></h4>
                        <?php $form = \yii\widgets\ActiveForm::begin([
                            'action' => ['reply', 'id' => $model->id],
                            'method' => 'post',
                        ]); ?>

                        <div class="mb-3">
                            <label class="form-label">Reply Message</label>
                            <textarea name="admin_reply" class="form-control" rows="5" required><?= Html::encode($model->admin_reply) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="replied" <?= $model->status === 'replied' ? 'selected' : '' ?>>Replied</option>
                                <option value="closed" <?= $model->status === 'closed' ? 'selected' : '' ?>>Closed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <?= Html::submitButton('Send Reply', ['class' => 'btn btn-primary']) ?>
                        </div>

                        <?php \yii\widgets\ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border-radius: 10px;
}
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 20px;
}
.badge {
    font-size: 14px;
    padding: 8px 15px;
}
</style>