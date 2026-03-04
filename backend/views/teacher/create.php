<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */
/* @var $user common\models\User */ // Userni ham tanitib qo'yamiz

$this->title = Yii::t('app', 'Create Teacher');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teachers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="teacher-create">
    <div class="card">
        <div class="card-header" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            <h1 class="text-white m-0"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'user' => $user, // 🔥 BU QATOR QO'SHILDI
            ]) ?>
        </div>
    </div>
</div>