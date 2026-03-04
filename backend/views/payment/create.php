<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */
/* @var $students array */
/* @var $courses array */

$this->title = Yii::t('app', 'Record Payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payment-create">
    <div class="card shadow">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            <h4 class="mb-0"><i class="fas fa-money-bill-wave"></i> <?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model, 
                'students' => $students, 
                'courses' => $courses
            ]) ?>
        </div>
    </div>
</div>