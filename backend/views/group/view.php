<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// 🔥 Agar Controllerdan studentlar kelmasa, Model orqali olamiz
$studentList = $students ?? $model->students; 
?>

<div class="group-view">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="fas fa-users text-primary"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this group?'),
                'data-method' => 'post',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><?= Yii::t('app', 'Group Details') ?></h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                            [
                                'attribute' => 'course_id',
                                // 🔥 Tarjimaga o'tkazildi
                                'value' => $model->course->name ?? Yii::t('app', 'N/A'),
                            ],
                            [
                                'attribute' => 'teacher_id',
                                // 🔥 Tarjimaga o'tkazildi
                                'value' => $model->teacher->full_name ?? Yii::t('app', 'N/A'),
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function($model) {
                                    $colors = ['active' => 'success', 'pending' => 'warning', 'finished' => 'secondary'];
                                    $c = $colors[$model->status] ?? 'primary';
                                    // 🔥 Status yozuvlari ham tarjima bo'ladi (Active, Pending, Finished)
                                    return "<span class='badge bg-{$c}'>". Yii::t('app', ucfirst($model->status)) ."</span>";
                                }
                            ],
                            'schedule',
                            'room',
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user-graduate"></i> <?= Yii::t('app', 'Enrolled Students') ?></h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($studentList)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th><?= Yii::t('app', 'Name') ?></th>
                                        <th><?= Yii::t('app', 'Phone') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentList as $i => $student): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td>
                                            <i class="fas fa-user-circle text-secondary"></i> 
                                            <?= Html::encode($student->full_name) ?>
                                        </td>
                                        <td><?= Html::encode($student->phone) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                            <?= Yii::t('app', 'No students enrolled yet.') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>