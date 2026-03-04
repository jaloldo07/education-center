<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Create Course');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="course-create">
    <div class="card shadow">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            <h4 class="mb-0"><i class="fas fa-book"></i> <?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body">
            <?= $this->render('_form', ['model' => $model, 'teachers' => $teachers]) ?>
        </div>
    </div>
</div>