<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Start Test: ' . $test->title;
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header text-white text-center">
                    <h3 class="mb-0"><?= Html::encode($test->title) ?></h3>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <h5><i class="bi bi-info-circle"></i> Test Instructions</h5>
                        <ul class="mb-0">
                            <li>Duration: <strong><?= $test->duration ?> minutes</strong></li>
                            <li>Total Questions: <strong><?= $test->total_questions ?></strong></li>
                            <li>Passing Score: <strong><?= $test->passing_score ?>%</strong></li>
                            <li>You must complete all questions</li>
                            <li>Timer will start automatically</li>
                        </ul>
                    </div>

                    <?php if ($test->require_face_control): ?>
                        <div class="text-center my-4">
                            <h5><i class="bi bi-camera"></i> Face Control Required</h5>
                            <p class="text-muted">Please take a selfie to verify your identity</p>
                            
                            <div id="camera-container" class="mb-3">
                                <video id="video" width="400" height="300" autoplay class="border rounded"></video>
                                <canvas id="canvas" width="400" height="300" style="display:none;"></canvas>
                                <div id="photo-preview" style="display:none;">
                                    <img id="preview-img" class="border rounded" width="400" height="300">
                                </div>
                            </div>

                            <div id="camera-buttons">
                                <button type="button" class="btn btn-primary btn-lg" onclick="capturePhoto()">
                                    <i class="bi bi-camera"></i> Take Photo
                                </button>
                            </div>

                            <div id="photo-buttons" style="display:none;">
                                <button type="button" class="btn btn-success btn-lg me-2" onclick="submitAndStart()">
                                    <i class="bi bi-check-circle"></i> Confirm & Start Test
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="retakePhoto()">
                                    <i class="bi bi-arrow-repeat"></i> Retake
                                </button>
                            </div>
                        </div>

                        <?= Html::beginForm(['begin', 'id' => $test->id], 'post', ['id' => 'face-form']) ?>
                            <input type="hidden" name="face_photo" id="face_photo">
                        <?= Html::endForm() ?>
                    <?php else: ?>
                        <div class="text-center my-4">
                            <?= Html::beginForm(['begin', 'id' => $test->id], 'post') ?>
                                <?= Html::submitButton('<i class="bi bi-play-circle-fill"></i> Start Test Now', [
                                    'class' => 'btn btn-success btn-lg'
                                ]) ?>
                            <?= Html::endForm() ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <?= Html::a('<i class="bi bi-arrow-left"></i> Back to Tests', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($test->require_face_control): ?>
<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const photoPreview = document.getElementById('photo-preview');
const previewImg = document.getElementById('preview-img');
let stream = null;

// Start camera
navigator.mediaDevices.getUserMedia({ video: true })
    .then(function(mediaStream) {
        stream = mediaStream;
        video.srcObject = stream;
    })
    .catch(function(err) {
        alert('Camera access denied. Please enable camera to take the test.');
        console.error('Camera error:', err);
    });

function capturePhoto() {
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, 400, 300);
    
    const imageData = canvas.toDataURL('image/png');
    previewImg.src = imageData;
    document.getElementById('face_photo').value = imageData;
    
    // Hide video, show preview
    video.style.display = 'none';
    photoPreview.style.display = 'block';
    document.getElementById('camera-buttons').style.display = 'none';
    document.getElementById('photo-buttons').style.display = 'block';
}

function retakePhoto() {
    video.style.display = 'block';
    photoPreview.style.display = 'none';
    document.getElementById('camera-buttons').style.display = 'block';
    document.getElementById('photo-buttons').style.display = 'none';
}

function submitAndStart() {
    // Stop camera
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    
    document.getElementById('face-form').submit();
}

// Stop camera when leaving page
window.addEventListener('beforeunload', function() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
});
</script>
<?php endif; ?>




<style>
    body {
        background: #f8f9ff;
    }

    /* 🔥 Header */
    .page-header {
        background: linear-gradient(135deg, #4caf50, #45a049);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.2);
    }

    /* 📝 Card */
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.1);
        overflow: hidden;
    }

    .card-header, .card-header-custom {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        padding: 2rem;
    }

    .card-body-custom {
        padding: 2rem;
    }

    /* 📸 Face Control */
    #video, #canvas {
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    #photo-preview img {
        border-radius: 16px;
        border: 3px solid  #414fde;
    }

    /* ⏰ Timer */
    .timer-display {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-size: 1.5rem;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
    }

    /* 📊 Progress */
    .progress {
        height: 12px;
        border-radius: 10px;
        background: #f8f9ff;
    }

    .progress-bar {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        border-radius: 10px;
    }

    /* 🔘 Buttons */
    .btn {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
    }

    .btn-success {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-3px) scale(1.05);
    }

    /* 🏅 Result Score */
    .score-display {
        font-size: 5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #414fde, #6b74ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ✅ Question Cards */
    .question-result-card {
        border-radius: 16px;
        border: 3px solid;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .question-result-card.correct {
        border-color: #414fde;
    }

    .question-result-card.incorrect {
        border-color: #f44336;
    }

    .question-result-header {
        padding: 1rem 1.5rem;
        color: white;
        font-weight: 700;
    }

    .question-result-header.correct {
        background: linear-gradient(135deg, #414fde, #6b74ff);
    }

    .question-result-header.incorrect {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }
</style>