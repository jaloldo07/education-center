<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */
/* @var $user common\models\User */ // Userni ham tanitib qo'yamiz

$this->title = Yii::t('app', 'Update Teacher') . ': ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teachers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->full_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="teacher-update">
    <div class="card shadow">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            <h4 class="mb-0"><i class="fas fa-edit"></i> <?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'user' => $user, // 🔥 BU QATOR QO'SHILDI
            ]) ?>
        </div>
    </div>
</div>