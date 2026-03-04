<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Student */
/* @var $user common\models\User */

$this->title = Yii::t('app', 'Update Student') . ': ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->full_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="student-update">
    <div class="card shadow">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            
            <h4 class="mb-0"><i class="fas fa-edit"></i> <?= Html::encode($this->title) ?></h4>

            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to list'), ['index'], [
                'class' => 'btn btn-sm btn-light text-primary fw-bold shadow-sm'
            ]) ?>

        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'user' => $user,
            ]) ?>
        </div>
    </div>
</div>