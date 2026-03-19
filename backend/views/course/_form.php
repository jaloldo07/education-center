<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Course; 

/* @var $this yii\web\View */
/* @var $model common\models\Course */
/* @var $form yii\widgets\ActiveForm */
/* @var $teachers array */

?>

<style>
    .help-block, .help-block-error, .invalid-feedback {
        color: #dc3545 !important;
        font-weight: bold;
        font-size: 0.9rem;
        margin-top: 5px;
        display: block;
    }
    .has-error .form-control {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    /* Qulflangan input uchun dizayn */
    .form-control[readonly] {
        background-color: #e9ecef;
        opacity: 1;
        cursor: not-allowed;
    }
</style>

<div class="course-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n<div class=\"col-md-12\">{error}</div>",
            'labelOptions' => ['class' => 'control-label fw-bold mb-1'],
            'errorOptions' => ['class' => 'help-block-error text-danger'], 
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0"><i class="fas fa-info-circle"></i> <?= Yii::t('app', 'Course Information') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => true,
                        'placeholder' => Yii::t('app', 'Enter course name'),
                    ])->label(Yii::t('app', 'Name')) ?>
                </div>
                
                <div class="col-md-4">
                    <?= $form->field($model, 'type')->dropDownList(
                        Course::getTypeOptions(), 
                        [
                            'class' => 'form-select',
                            'id' => 'course-type' // JS uchun ID
                        ]
                    )->label(Yii::t('app', 'Enrollment Type')) ?>
                </div>
            </div>

            <?= $form->field($model, 'description')->textarea([
                'rows' => 6,
                'placeholder' => Yii::t('app', 'Write a detailed description of the course...'),
            ])->label(Yii::t('app', 'Description')) ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0"><i class="fas fa-cogs"></i> <?= Yii::t('app', 'Settings & Pricing') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'duration')->textInput([
                        'type' => 'number',
                        'min' => 1,
                        'placeholder' => Yii::t('app', 'Months'),
                    ])->label(Yii::t('app', 'Duration (Months)')) ?>
                </div>
                
                <div class="col-md-4">
                    <?= $form->field($model, 'price')->textInput([
                        'type' => 'number',
                        'min' => 0,
                        'step' => '1000',
                        'id' => 'course-price', // JS uchun ID
                        'placeholder' => Yii::t('app', 'Price in UZS'),
                    ])->label(Yii::t('app', 'Price (UZS)')) ?>
                </div>
                
                <div class="col-md-4">
                    <?= $form->field($model, 'teacher_id')->dropDownList(
                        $teachers, 
                        [
                            'prompt' => Yii::t('app', 'Select Instructor...'),
                            'class' => 'form-select'
                        ]
                    )->label(Yii::t('app', 'Instructor')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Save Course'), ['class' => 'btn btn-success btn-lg px-4']) ?>
        <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-secondary btn-lg px-4']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// 🔥 YANI QO'SHILDI: Free va Premium mantig'i uchun JavaScript
$this->registerJs("
    function togglePriceInput() {
        var type = $('#course-type').val();
        var priceInput = $('#course-price');
        
        // Agar kurs 'free' bo'lsa
        if (type === 'free') {
            priceInput.val(0); // Narxni 0 qilamiz
            priceInput.prop('readonly', true); // Kiritishni qulflaymiz
        } else {
            // Agar 'premium' yoki boshqa bo'lsa
            priceInput.prop('readonly', false); // Qulfni ochamiz
            if (priceInput.val() == 0) {
                priceInput.val(''); // 0 ni tozalab, kiritishga tayyorlaymiz
            }
        }
    }

    // Turi o'zgarganda ishlashi uchun
    $('#course-type').on('change', togglePriceInput);
    
    // Sahifa yuklanganda bir marta tekshirib qo'yish uchun
    togglePriceInput();
");
?>