<?php
use yii\helpers\Html;

$this->title = 'My Notifications';
?>

<div class="notifications-page">
    <h1><i class="fas fa-bell"></i> <?= Html::encode($this->title) ?></h1>

    <?php if (empty($notifications)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-bell-slash fa-3x mb-3"></i>
            <h4>No notifications yet</h4>
        </div>
    <?php else: ?>
        <?php foreach ($notifications as $notification): ?>
        <div class="card mb-3 shadow-sm <?= $notification->is_read ? 'bg-light' : '' ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="flex-grow-1">
                        <h5 class="card-title">
                            <i class="fas fa-<?= ['info' => 'info-circle', 'success' => 'check-circle', 'warning' => 'exclamation-triangle', 'danger' => 'times-circle'][$notification->type] ?> text-<?= $notification->type ?>"></i>
                            <?= Html::encode($notification->title) ?>
                        </h5>
                        <p class="card-text"><?= Html::encode($notification->message) ?></p>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> <?= Yii::$app->formatter->asDatetime($notification->created_at) ?>
                        </small>
                    </div>
                    <?php if (!$notification->is_read): ?>
                        <div>
                            <span class="badge bg-primary">New</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>