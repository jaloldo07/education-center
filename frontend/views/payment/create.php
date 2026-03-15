<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Payment;
use common\models\Setting;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */
/* @var $courses common\models\Course[] */
/* @var $selectedCourse common\models\Course|null */
/* @var $enrollment common\models\Enrollment|null */

$this->title = Yii::t('app', 'To\'lov qilish');

// 🔥 BAZADAN DINAMIK KARTA MA'LUMOTLARINI OLAMIZ
$adminCardUzcard = Setting::getValue('uzcard_number', '8600 **** **** ****');
$adminNameUzcard = Setting::getValue('uzcard_name', 'O\'quv Markazi');

$adminCardHumo = Setting::getValue('humo_number', '9860 **** **** ****');
$adminNameHumo = Setting::getValue('humo_name', 'O\'quv Markazi');
?>

<style>
    /* ... Oldingi style'laringiz o'z holicha qoldi, faqat keraklilarini qo'shdim ... */
    .payment-page { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 0; font-family: 'Nunito', sans-serif; }
    .glass-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5); overflow: hidden; color: white; }
    .payment-header { background: linear-gradient(135deg, #4361ee, #3a0ca3); padding: 25px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .payment-title { font-weight: 800; margin: 0; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px; }
    .info-box { background: rgba(67, 97, 238, 0.1); border: 1px dashed rgba(67, 97, 238, 0.4); padding: 15px; border-radius: 12px; margin-bottom: 25px; font-size: 0.95rem; color: #e2e8f0; }
    .card-details-box { background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(255,255,255,0.01)); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 20px; margin-bottom: 25px; }
    .card-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(0,0,0,0.3); border-radius: 10px; margin-bottom: 10px; }
    .card-number { font-size: 1.2rem; font-weight: 700; letter-spacing: 2px; color: #4ade80; }
    .btn-copy { background: rgba(255,255,255,0.1); border: none; color: white; padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; transition: 0.3s; }
    .btn-copy:hover { background: #4361ee; }
    .form-control-dark, .form-select-dark { background: rgba(0, 0, 0, 0.3) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: white !important; border-radius: 12px; padding: 12px 15px; font-size: 1rem; transition: 0.3s; }
    .form-control-dark:focus, .form-select-dark:focus { background: rgba(0, 0, 0, 0.5) !important; border-color: #4ade80 !important; box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.2) !important; color: white !important; outline: none; }
    .btn-neon-pay { background: linear-gradient(135deg, #10b981, #047857); border: none; padding: 14px; border-radius: 12px; font-weight: 800; width: 100%; color: white; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
    .btn-neon-pay:hover { transform: translateY(-2px); box-shadow: 0 0 25px rgba(16, 185, 129, 0.6); color: white; }
    .course-summary { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 15px; margin-bottom: 20px; border: 1px dashed rgba(255,255,255,0.2); }
    
    /* Yangi qo'shilgan stylelar */
    .method-selector { display: flex; gap: 10px; margin-bottom: 20px; }
    .method-btn { flex: 1; padding: 12px; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color: white; font-weight: bold; transition: 0.3s; cursor: pointer; text-align: center; }
    .method-btn.active { background: #4361ee; border-color: #4361ee; box-shadow: 0 0 15px rgba(67, 97, 238, 0.5); }
    #cash-info { display: none; text-align: center; background: rgba(239, 68, 68, 0.1); border: 1px dashed #ef4444; padding: 20px; border-radius: 16px; margin-bottom: 20px; }
</style>

<div class="payment-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                
                <div class="glass-card animate__animated animate__fadeInUp">
                    <div class="payment-header">
                        <i class="bi bi-wallet2 fa-2x mb-2 text-white-50"></i>
                        <h1 class="payment-title"><?= Yii::t('app', 'To\'lovni amalga oshirish') ?></h1>
                    </div>

                    <div class="card-body p-4">
                        
                        <div class="method-selector">
                            <div class="method-btn active" id="btn-card" onclick="selectMethod('card')">
                                <i class="fas fa-credit-card"></i> Karta orqali
                            </div>
                            <div class="method-btn" id="btn-cash" onclick="selectMethod('cash')">
                                <i class="fas fa-money-bill-wave"></i> Naqd pul
                            </div>
                        </div>

                        <?php $form = ActiveForm::begin(['id' => 'payment-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

                        <input type="hidden" name="Payment[payment_method]" id="payment_method_input" value="card">

                        <div id="card-section">
                            <div class="info-box text-center">
                                <i class="bi bi-info-circle-fill text-info me-2"></i> 
                                <?= Yii::t('app', 'Quyidagi karta raqamlaridan biriga pul o\'tkazing. So\'ngra to\'lov chekini skrinshot qilib pastga yuklang.') ?>
                            </div>

                            <div class="card-details-box">
                                <div class="card-item">
                                    <div>
                                        <div class="text-white-50 small"><?= Html::encode($adminNameUzcard) ?></div>
                                        <div class="card-number" id="uzcard-num"><?= Html::encode($adminCardUzcard) ?></div>
                                    </div>
                                    <button type="button" class="btn-copy" onclick="copyToClipboard('uzcard-num')"><i class="bi bi-copy"></i> Nusxa</button>
                                </div>
                                
                                <div class="card-item mb-0">
                                    <div>
                                        <div class="text-white-50 small"><?= Html::encode($adminNameHumo) ?></div>
                                        <div class="card-number text-warning" id="humo-num"><?= Html::encode($adminCardHumo) ?></div>
                                    </div>
                                    <button type="button" class="btn-copy" onclick="copyToClipboard('humo-num')"><i class="bi bi-copy"></i> Nusxa</button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-white"><i class="bi bi-file-earmark-image text-info me-2"></i><?= Yii::t('app', 'To\'lov cheki (Skrinshot)') ?></label>
                                <?= $form->field($model, 'receipt_file')->fileInput([
                                    'class' => 'form-control form-control-dark', 
                                    'accept' => 'image/*',
                                    'id' => 'receipt_input',
                                    'required' => true
                                ])->label(false) ?>
                                <div class="text-white-50 small mt-1">Faqat rasm fayllari (JPG, PNG, JPEG) yuklang.</div>
                            </div>
                        </div>

                        <div id="cash-info">
                            <i class="fas fa-map-marker-alt fa-3x text-danger mb-3"></i>
                            <h5 class="text-white fw-bold">Ofisga tashrif buyuring</h5>
                            <p class="text-white-50 mb-0">Siz naqd to'lov usulini tanladingiz. Iltimos, arizani yuborganingizdan so'ng markazimizga kelib to'lovni amalga oshiring.</p>
                            <p class="text-warning small mt-2">Manzil: Toshkent shahar, Chilonzor tumani, 1-mavze</p>
                        </div>
                        
                        <?php if ($selectedCourse): ?>
                            <div class="course-summary text-center">
                                <h5 class="fw-bold mb-1 text-white"><?= Html::encode($selectedCourse->name) ?></h5>
                                <div class="mt-2 h4 text-success fw-bold">
                                    <?= number_format($selectedCourse->price, 0, '.', ' ') ?> <small>UZS</small>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3" style="display: none;">
                            <?= $form->field($model, 'course_id')->dropDownList(
                                ArrayHelper::map($courses, 'id', 'name'),
                                [
                                    'class' => 'form-select form-select-dark',
                                    'options' => [($selectedCourse ? $selectedCourse->id : 0) => ['Selected' => true]]
                                ]
                            )->label(false) ?>
                        </div>

                        <div class="mb-3" style="display: none;">
                            <?= $form->field($model, 'payment_type')->dropDownList(
                                [Payment::TYPE_FULL => Yii::t('app', 'Full Course Payment')],
                                ['class' => 'form-select form-select-dark', 'options' => [Payment::TYPE_FULL => ['Selected' => true]]]
                            )->label(false) ?>
                        </div>

                        <?= Html::submitButton('<i class="fas fa-paper-plane me-2"></i> <span id="submit-text">' . Yii::t('app', 'Chekni jo\'natish') . '</span>', [
                            'class' => 'btn-neon-pay',
                            'id' => 'submit-btn'
                        ]) ?>

                        <?php ActiveForm::end(); ?>

                        <div class="text-center mt-4">
                            <small class="text-white-50">
                                <i class="bi bi-headset me-1"></i> <?= Yii::t('app', 'Muammo bormi?') ?> 
                                <a href="/student/support" class="text-info text-decoration-none">Supportga yozing</a>
                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    var text = document.getElementById(elementId).innerText;
    navigator.clipboard.writeText(text).then(function() {
        alert("Karta raqami nusxalandi: " + text);
    }, function(err) {
        console.error('Nusxalashda xatolik: ', err);
    });
}

function selectMethod(method) {
    // Tugmalarni rangini o'zgartirish
    document.getElementById('btn-card').classList.remove('active');
    document.getElementById('btn-cash').classList.remove('active');
    document.getElementById('btn-' + method).classList.add('active');

    // Yashirin input qiymatini o'zgartirish
    document.getElementById('payment_method_input').value = method;

    // Karta va Naqd bloklarini ko'rsatish/yashirish
    if (method === 'card') {
        document.getElementById('card-section').style.display = 'block';
        document.getElementById('cash-info').style.display = 'none';
        document.getElementById('receipt_input').setAttribute('required', 'required');
        document.getElementById('submit-text').innerText = 'Chekni jo\'natish';
    } else {
        document.getElementById('card-section').style.display = 'none';
        document.getElementById('cash-info').style.display = 'block';
        document.getElementById('receipt_input').removeAttribute('required');
        document.getElementById('submit-text').innerText = 'Arizani yuborish';
    }
}
</script>