<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="group-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-users"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => 'Are you sure?',
                'data-method' => 'post',
            ]) ?>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    [
                        'attribute' => 'course_id',
                        'value' => $model->course->name,
                    ],
                    [
                        'attribute' => 'teacher_id',
                        'value' => $model->teacher->full_name,
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-user-graduate"></i> Enrolled Students</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($students)): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $i => $student): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= Html::encode($student->full_name) ?></td>
                            <td><?= Html::encode($student->email) ?></td>
                            <td><?= Html::encode($student->phone) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No students enrolled yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>