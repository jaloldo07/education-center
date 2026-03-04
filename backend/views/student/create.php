<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Student */
/* @var $user common\models\User */ // 🔥

$this->title = Yii::t('app', 'Talaba Qo\'shish');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-create">

    <div class="card shadow">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #198754, #20c997);">
            <h4 class="mb-0"><i class="fas fa-user-plus"></i> <?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'user' => $user, // 🔥 BU MUHIM
            ]) ?>
        </div>
    </div>

</div>