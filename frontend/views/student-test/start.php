<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\TestAttempt;

$this->title = Yii::t('app', 'Start Test') . ': ' . $test->title;

// 🔥 URINISHLAR SONINI HISOBLASH
$studentAttemptCount = TestAttempt::find()
    ->where(['test_id' => $test->id, 'student_id' => $student->id])
    ->count();
$maxAttempts = (int)$test->max_attempts;

$attemptsText = $maxAttempts > 0 ? ($studentAttemptCount . ' / ' . $maxAttempts) : ($studentAttemptCount . ' / ∞');
?>

<style>
    /* 1. Page Container */
    .start-test-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Glass Card */
    .glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        overflow: hidden;
    }

    /* 3. Header */
    .glass-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        padding: 30px;
        text-align: center;
        color: white;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .test-title {
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: 5px;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.6);
    }

    /* 4. Info Stats Grid */
    .info-stats-row {
        display: flex;
        justify-content: space-around;
        padding: 30px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        flex-wrap: wrap;
        gap: 20px;
    }

    .stat-box {
        background: rgba(255,255,255,0.05);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        min-width: 130px;
        border: 1px solid rgba(255,255,255,0.05);
        transition: 0.3s;
        flex: 1;
    }
    .stat-box:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,0.08);
        border-color: rgba(255,255,255,0.2);
    }

    .stat-icon { font-size: 1.5rem; margin-bottom: 10px; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: white; display: block; }
    .stat-label { font-size: 0.9rem; color: rgba(255,255,255,0.5); text-transform: uppercase; }

    .text-neon-pink { color: #f72585; text-shadow: 0 0 10px rgba(247, 37, 133, 0.4); }
    .text-neon-blue { color: #4cc9f0; text-shadow: 0 0 10px rgba(76, 201, 240, 0.4); }
    .text-neon-green { color: #4ade80; text-shadow: 0 0 10px rgba(74, 222, 128, 0.4); }
    .text-neon-warning { color: #fbbf24; text-shadow: 0 0 10px rgba(251, 191, 36, 0.4); } /* Yangi rang */

    /* 5. Instructions */
    .instructions-area {
        padding: 30px;
        color: rgba(255,255,255,0.8);
    }
    .instructions-title {
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 15px;
        display: flex; align-items: center; gap: 10px;
    }
    .instructions-list {
        list-style: none;
        padding: 0;
    }
    .instructions-list li {
        padding: 8px 0;
        display: flex; align-items: start; gap: 10px;
        font-size: 0.95rem;
    }
    .check-icon { color: #4ade80; }

    /* 6. Camera Section */
    .camera-wrapper {
        background: rgba(0,0,0,0.3);
        padding: 30px;
        text-align: center;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .camera-frame {
        width: 100%;
        max-width: 400px;
        height: 300px;
        background: #000;
        margin: 0 auto 20px auto;
        border-radius: 16px;
        border: 2px solid #4361ee;
        box-shadow: 0 0 20px rgba(67, 97, 238, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    video, #preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* 7. Buttons */
    .btn-neon-action {
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-camera {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        box-shadow: 0 0 15px rgba(67, 97, 238, 0.4);
    }
    .btn-camera:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(67, 97, 238, 0.6);
        color: white;
    }

    .btn-start {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: #064e3b;
        box-shadow: 0 0 15px rgba(74, 222, 128, 0.4);
        width: 100%;
        font-size: 1.1rem;
        padding: 15px;
    }
    .btn-start:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(74, 222, 128, 0.6);
        color: #064e3b;
    }

    .btn-retake {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .btn-retake:hover { background: white; color: black; }

    .btn-glass-back {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
        padding: 10px 20px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }
    .btn-glass-back:hover { background: white; color: black; }

</style>

<div class="start-test-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="glass-card animate__animated animate__fadeInUp">
                    
                    <div class="glass-header">
                        <h3 class="test-title"><?= Html::encode($test->title) ?></h3>
                        <div class="text-white-50"><?= Yii::t('app', 'Ready to challenge yourself?') ?></div>
                    </div>

                    <div class="info-stats-row">
                        <div class="stat-box">
                            <div class="stat-icon text-neon-warning"><i class="bi bi-arrow-repeat"></i></div>
                            <span class="stat-value"><?= $attemptsText ?></span>
                            <span class="stat-label"><?= Yii::t('app', 'Urinishlar') ?></span>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon text-neon-pink"><i class="bi bi-clock"></i></div>
                            <span class="stat-value"><?= $test->duration ?></span>
                            <span class="stat-label"><?= Yii::t('app', 'Minutes') ?></span>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon text-neon-blue"><i class="bi bi-question-circle"></i></div>
                            <span class="stat-value"><?= $test->total_questions ?></span>
                            <span class="stat-label"><?= Yii::t('app', 'Questions') ?></span>
                        </div>
                        <div class="stat-box">
                            <div class="stat-icon text-neon-green"><i class="bi bi-trophy"></i></div>
                            <span class="stat-value"><?= $test->passing_score ?>%</span>
                            <span class="stat-label"><?= Yii::t('app', 'Pass Score') ?></span>
                        </div>
                    </div>

                    <div class="instructions-area">
                        <div class="instructions-title">
                            <i class="bi bi-info-circle text-info"></i> <?= Yii::t('app', 'Important Instructions') ?>
                        </div>
                        <ul class="instructions-list">
                            <li><i class="bi bi-check-circle-fill check-icon"></i> <?= Yii::t('app', 'You must complete all questions within the time limit.') ?></li>
                            <li><i class="bi bi-check-circle-fill check-icon"></i> <?= Yii::t('app', 'Timer will start automatically once you click the button below.') ?></li>
                            <li><i class="bi bi-check-circle-fill check-icon"></i> <?= Yii::t('app', 'Do not refresh the page or switch tabs during the test.') ?></li>
                            <?php if ($test->require_face_control): ?>
                                <li class="text-warning fw-bold"><i class="bi bi-camera-video-fill me-2"></i> <?= Yii::t('app', 'Face Control is enabled. Your photo will be taken.') ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php if ($test->require_face_control): ?>
                        <div class="camera-wrapper">
                            <h5 class="text-white mb-3"><i class="bi bi-person-bounding-box me-2"></i> <?= Yii::t('app', 'Identity Verification') ?></h5>
                            
                            <div class="camera-frame">
                                <video id="video" autoplay muted playsinline></video>
                                <canvas id="canvas" style="display:none;"></canvas>
                                
                                <div id="photo-preview" style="display:none;">
                                    <img id="preview-img">
                                </div>

                                <div id="camera-error-msg" style="display:none; color: #f87171; text-align: center; padding: 20px;">
                                    <i class="bi bi-camera-video-off fa-2x mb-3"></i><br>
                                    <?= Yii::t('app', 'Camera access failed or blocked.') ?>
                                    <div class="mt-3 text-white-50 small">
                                        <?= Yii::t('app', 'Since camera is not available, please upload a photo manually.') ?>
                                    </div>
                                </div>
                            </div>

                            <div id="camera-buttons">
                                <button type="button" class="btn-neon-action btn-camera" onclick="capturePhoto()">
                                    <i class="bi bi-camera me-2"></i> <?= Yii::t('app', 'Take Photo') ?>
                                </button>
                            </div>

                            <div id="upload-buttons" style="display:none;">
                                <label class="btn-neon-action btn-camera" style="cursor: pointer;">
                                    <i class="bi bi-upload me-2"></i> <?= Yii::t('app', 'Upload Photo') ?>
                                    <input type="file" id="manual-upload" accept="image/*" style="display: none;" onchange="handleFileUpload(this)">
                                </label>
                            </div>

                            <div id="photo-buttons" style="display:none;">
                                <div class="d-flex justify-content-center gap-3 align-items-center">
                                    <button type="button" class="btn-neon-action btn-retake" onclick="retakePhoto()">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i> <?= Yii::t('app', 'Retake') ?>
                                    </button>
                                    
                                    <?= Html::beginForm(['begin', 'id' => $test->id], 'post', ['id' => 'face-form', 'style' => 'display:inline; width: auto;']) ?>
                                        <input type="hidden" name="face_photo" id="face_photo">
                                        <button type="submit" class="btn-neon-action btn-start" style="width: auto; padding: 12px 30px;">
                                            <i class="bi bi-play-circle-fill me-2"></i> <?= Yii::t('app', 'Confirm & Start') ?>
                                        </button>
                                    <?= Html::endForm() ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="padding: 0 30px 40px 30px;">
                            <?= Html::beginForm(['begin', 'id' => $test->id], 'post') ?>
                                <button type="submit" class="btn-neon-action btn-start">
                                    <i class="bi bi-play-circle-fill me-2"></i> <?= Yii::t('app', 'Start Test Now') ?>
                                </button>
                            <?= Html::endForm() ?>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="text-center">
                    <?= Html::a('<i class="bi bi-arrow-left me-2"></i> ' . Yii::t('app', 'Back to Tests'), ['index'], ['class' => 'btn-glass-back']) ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php if ($test->require_face_control): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoPreview = document.getElementById('photo-preview');
        const previewImg = document.getElementById('preview-img');
        
        const cameraButtons = document.getElementById('camera-buttons');
        const photoButtons = document.getElementById('photo-buttons');
        const uploadButtons = document.getElementById('upload-buttons');
        const errorMsg = document.getElementById('camera-error-msg');
        
        let stream = null;
        let isCameraMode = true;

        // Kamerani ishga tushirish
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
                video.srcObject = stream;
                // Hammasi joyida bo'lsa
                errorMsg.style.display = 'none';
                video.style.display = 'block';
                cameraButtons.style.display = 'block';
                uploadButtons.style.display = 'none';
            } catch (err) {
                console.warn('Camera failed:', err);
                enableFallbackMode();
            }
        }

        // Fallback: Kamera o'xshamasa -> Upload rejimi
        function enableFallbackMode() {
            isCameraMode = false;
            if(stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            video.style.display = 'none';
            cameraButtons.style.display = 'none';
            
            errorMsg.style.display = 'block'; // Xabarni chiqarish
            uploadButtons.style.display = 'block'; // Upload tugmasini chiqarish
        }

        // Sahifa yuklanganda kamerani sinab ko'rish
        startCamera();

        // 1. Kameradan rasm olish
        window.capturePhoto = function() {
            if (!stream) { enableFallbackMode(); return; }
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/jpeg', 0.8);
            showPreview(imageData);
        };

        // 2. Fayl yuklash orqali rasm olish (Fallback)
        window.handleFileUpload = function(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    showPreview(e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        };

        // Rasmni ko'rsatish va tugmalarni almashtirish
        function showPreview(imageData) {
            previewImg.src = imageData;
            document.getElementById('face_photo').value = imageData;
            
            video.style.display = 'none';
            errorMsg.style.display = 'none';
            photoPreview.style.display = 'block';
            
            cameraButtons.style.display = 'none';
            uploadButtons.style.display = 'none';
            photoButtons.style.display = 'block';
        }

        // Qaytadan urinish
        window.retakePhoto = function() {
            photoPreview.style.display = 'none';
            photoButtons.style.display = 'none';
            
            if (isCameraMode) {
                video.style.display = 'block';
                cameraButtons.style.display = 'block';
            } else {
                errorMsg.style.display = 'block';
                uploadButtons.style.display = 'block';
            }
        };

        // Form submit bo'lganda oqimni to'xtatish
        document.getElementById('face-form').addEventListener('submit', function() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });
    });
</script>
<?php endif; ?>