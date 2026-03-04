<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Update Enrollment') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Enrollments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="enrollment-update">
    <div class="card shadow">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            <h4 class="mb-0"><i class="fas fa-edit"></i> <?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body">
            <?= $this->render('_form', ['model' => $model, 'students' => $students, 'groups' => $groups]) ?>
        </div>
    </div>
</div>