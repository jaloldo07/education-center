<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SupportTicket */

$this->title = Yii::t('app', 'Reply to Ticket: ') . $model->subject;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Support Tickets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Reply');
?>

<div class="support-reply">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-reply text-primary"></i> <?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user-circle"></i> <?= Yii::t('app', 'User Message') ?></h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <th style="width: 120px;"><?= Yii::t('app', 'User') ?></th>
                            <td><strong class="text-primary"><?= Html::encode($model->user->username ?? Yii::t('app', 'Unknown')) ?></strong></td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('app', 'Status') ?></th>
                            <td>
                                <span class="badge bg-<?= $model->status === 'open' ? 'warning' : ($model->status === 'closed' ? 'secondary' : 'info') ?>">
                                    <?= Yii::t('app', strtoupper($model->status)) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('app', 'Created At') ?></th>
                            <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <label class="fw-bold text-muted"><?= Yii::t('app', 'Message Content') ?>:</label>
                        <div class="p-3 bg-light rounded border mt-1" style="white-space: pre-wrap; font-size: 15px;">
                            <?= Html::encode($model->message) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-pen"></i> <?= Yii::t('app', 'Write your reply') ?></h5>
                </div>
                <div class="card-body">
                    <?= Html::beginForm(['reply', 'id' => $model->id], 'post') ?>

                        <div class="form-group mb-3">
                            <label class="control-label fw-bold mb-1"><?= Yii::t('app', 'Admin Reply') ?></label>
                            <?= Html::textarea('admin_reply', $model->admin_reply, [
                                'class' => 'form-control', 
                                'rows' => 8, 
                                'required' => true,
                                'placeholder' => Yii::t('app', 'Type your response here...')
                            ]) ?>
                        </div>

                        <div class="form-group mb-4">
                            <label class="control-label fw-bold mb-1"><?= Yii::t('app', 'Update Status') ?></label>
                            <?= Html::dropDownList('status', $model->status === 'open' ? 'replied' : $model->status, [
                                'replied' => Yii::t('app', 'Replied'),
                                'closed' => Yii::t('app', 'Closed'),
                            ], ['class' => 'form-select form-control']) ?>
                        </div>

                        <div class="form-group text-end">
                            <?= Html::submitButton('<i class="fas fa-paper-plane"></i> ' . Yii::t('app', 'Send Reply'), ['class' => 'btn btn-success px-4']) ?>
                        </div>

                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    </div>

</div>