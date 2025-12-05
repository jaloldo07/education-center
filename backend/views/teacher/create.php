<?php

use yii\helpers\Html;

$this->title = 'Create Teacher';
$this->params['breadcrumbs'][] = ['label' => 'Teachers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="teacher-create">
    <div class="card">
        <div class="card-header" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>