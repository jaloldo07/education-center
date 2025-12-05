<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Admin Login';
?>

<div class="site-login">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h1 class="card-title text-center text-primary"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center">Please fill out the following fields to login:</p>

                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'username')->textInput([
                            'autofocus' => true,
                            'placeholder' => 'Enter your username',
                            'class' => 'form-control'
                        ]) ?>

                        <?= $form->field($model, 'password')->passwordInput([
                            'placeholder' => 'Enter your password',
                            'class' => 'form-control'
                        ]) ?>

                        <?= $form->field($model, 'rememberMe')->checkbox() ?>

                        <div class="form-group">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg w-100', 'name' => 'login-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>