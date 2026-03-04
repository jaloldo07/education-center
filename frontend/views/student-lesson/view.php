<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $lesson common\models\Lesson */
/* @var $progress common\models\LessonProgress */

$this->title = $lesson->title;

// --- LOGIKA QISMI ---
$isCompleted = $progress->status === 'completed';
$requiredSeconds = $lesson->min_watch_time ? $lesson->min_watch_time : 0;
$resumeTime = $progress->video_progress ? $progress->video_progress : 0;
// Tugma qulflanishi: Agar hali tugatmagan bo'lsa VA vaqt yetarli bo'lmasa
$isLocked = !$isCompleted && $requiredSeconds > 0 && $resumeTime < $requiredSeconds;
?>

<style>
    /* 1. Asosiy Glass Panel */
    .lesson-glass-card {
        background: rgba(15, 23, 42, 0.75); /* To'q ko'k shaffof fon */
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        margin-bottom: 50px;
        color: #ffffff;
    }

    /* 2. Dars Sarlavhasi */
    .lesson-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 30px;
        background: linear-gradient(90deg, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 20px;
    }

    /* 3. Matnli Dars uchun Blok */
    .text-content-box {
        background: rgba(0, 0, 0, 0.3); /* Ichki to'q fon */
        padding: 30px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: #e2e8f0; /* Oq-ish matn */
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 30px;
    }

    /* 4. Tugmalar uchun Action Bar (Pastki qism) */
    .action-bar {
        display: flex;
        justify-content: space-between; /* Ikki chetga surish */
        align-items: center;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    /* 5. "Yakunlash" Tugmasi (Neon Green) */
    .btn-finish-neon {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-finish-neon:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 30px rgba(16, 185, 129, 0.6);
        color: white;
    }

    /* 6. Disabled (Qulf) holati */
    .btn-finish-neon.disabled {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.4);
        box-shadow: none;
        cursor: not-allowed;
        pointer-events: none; /* Bosib bo'lmaydi */
    }

    /* 7. "Ortga" Tugmasi (Glass White) */
    .btn-back-glass {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 25px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.8);
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-back-glass:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: #fff;
        color: white;
    }

    /* Timer Badge */
    .timer-badge {
        background: rgba(0,0,0,0.5);
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.9em;
    }
</style>

<div class="lesson-view-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="lesson-glass-card">
                    
                    <h1 class="lesson-title">
                        <i class="fas fa-book-open me-2 text-primary opacity-75"></i> 
                        <?= Html::encode($lesson->title) ?>
                    </h1>

                    <div class="lesson-body">
                        
                        <?php if ($lesson->content_type === 'video'): ?>
                            <div class="ratio ratio-16x9 mb-4 rounded-4 overflow-hidden shadow">
                                <?php 
                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $lesson->video_url, $matches)) { 
                                    $videoId = $matches[1]; ?>
                                    <iframe id="youtube-player" 
                                        src="https://www.youtube.com/embed/<?= $videoId ?>?enablejsapi=1&rel=0&modestbranding=1" 
                                        allowfullscreen></iframe>
                                <?php } else { ?>
                                    <video id="html5-player" controls>
                                        <source src="<?= $lesson->video_url ?>" type="video/mp4">
                                    </video>
                                <?php } ?>
                            </div>

                            <?php if ($requiredSeconds > 0): ?>
                                <div class="d-flex align-items-center gap-3 text-info bg-dark bg-opacity-50 p-3 rounded-3 mb-4 border border-info border-opacity-25">
                                    <i class="fas fa-clock fs-4"></i>
                                    <div>
                                        <div class="small text-uppercase text-white-50"><?= Yii::t('app', 'Davomiylik talabi') ?></div>
                                        <div>
                                            <?= Yii::t('app', 'Ko\'rilgan:') ?> <strong class="text-white"><?= gmdate("i:s", $resumeTime) ?></strong> / 
                                            <?= Yii::t('app', 'Jami:') ?> <strong class="text-white"><?= gmdate("i:s", $requiredSeconds) ?></strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php elseif ($lesson->content_type === 'text'): ?>
                            <div class="text-content-box reading-mode">
                                <?= nl2br(Html::encode($lesson->content)) ?>
                            </div>

                        <?php elseif ($lesson->content_type === 'pdf'): ?>
                            <embed src="<?= $lesson->file_path ?>" type="application/pdf" width="100%" height="600px" class="rounded-3 border border-secondary mb-4">
                        <?php endif; ?>

                    </div>

                    <div class="action-bar">
                        
                        <div>
                            <?php if (!$isCompleted): ?>
                                <a href="<?= Url::to(['complete', 'id' => $lesson->id]) ?>" 
                                   class="btn-finish-neon <?= $isLocked ? 'disabled' : '' ?>" 
                                   id="complete-btn"
                                   data-method="post"
                                   data-confirm="<?= Yii::t('app', 'Darsni tugatganingizni tasdiqlaysizmi?') ?>">
                                   
                                    <span id="btn-icon">
                                        <?php if ($isLocked): ?>
                                            <i class="fas fa-lock"></i>
                                        <?php else: ?>
                                            <i class="fas fa-check-circle"></i>
                                        <?php endif; ?>
                                    </span>
                                    
                                    <span id="btn-text">
                                        <?= $isLocked ? Yii::t('app', 'Kuting...') : Yii::t('app', 'Darsni Yakunlash') ?>
                                    </span>
                                    
                                    <?php if ($isLocked): ?>
                                        <span id="timer-badge" class="timer-badge ms-2">
                                            <?php 
                                                $rem = max(0, $requiredSeconds - $resumeTime);
                                                echo gmdate("i:s", $rem);
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php else: ?>
                                <div class="text-success fw-bold d-flex align-items-center gap-2 fs-5">
                                    <i class="fas fa-check-circle fs-3"></i> 
                                    <?= Yii::t('app', 'Dars yakunlandi! ✅') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Ortga'), ['course', 'course_id' => $lesson->course_id], ['class' => 'btn-back-glass']) ?>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!$isCompleted && $requiredSeconds > 0): ?>
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requiredSeconds = <?= $requiredSeconds ?>;
        let currentProgress = <?= $resumeTime ?>; 
        
        const completeBtn = document.getElementById('complete-btn');
        const timerBadge = document.getElementById('timer-badge');
        const btnText = document.getElementById('btn-text');
        const btnIcon = document.getElementById('btn-icon');
        
        const updateUrl = "<?= Url::to(['update-progress', 'id' => $lesson->id]) ?>";
        // CSRF Tokenni xavfsiz olish
        const csrfParam = document.querySelector('meta[name="csrf-param"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        function formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }

        function unlockButton() {
            if (completeBtn && completeBtn.classList.contains('disabled')) {
                completeBtn.classList.remove('disabled');
                if(timerBadge) timerBadge.style.display = 'none';
                btnText.innerText = "<?= Yii::t('app', 'Darsni Yakunlash') ?>";
                btnIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
            }
        }

        function updateUI(currentTime) {
            currentProgress = currentTime;
            if (currentProgress >= requiredSeconds) {
                unlockButton();
            } else {
                let remaining = requiredSeconds - Math.floor(currentProgress);
                if(remaining < 0) remaining = 0;
                if(timerBadge) timerBadge.innerText = formatTime(remaining);
            }
        }

        function sendProgressToServer(currentTime) {
            if (currentTime > <?= $resumeTime ?>) { 
                const formData = new FormData();
                formData.append('video_progress', Math.floor(currentTime));
                formData.append('time_spent', 10);
                // CSRF Tokenni qo'shish
                if(csrfParam && csrfToken) {
                    formData.append(csrfParam.getAttribute('content'), csrfToken.getAttribute('content'));
                }
                
                fetch(updateUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(res => res.json())
                  .then(data => {
                      if(data.success && data.progress.status === 'completed') {
                          unlockButton();
                      }
                  }).catch(err => console.error(err));
            }
        }

        setInterval(() => {
            if (currentProgress < requiredSeconds) {
                sendProgressToServer(currentProgress);
            }
        }, 10000);

        // --- PLAYER LISTENERS ---
        const html5Video = document.getElementById('html5-player');
        if (html5Video) {
            html5Video.currentTime = currentProgress;
            html5Video.addEventListener('timeupdate', function() {
                if (this.currentTime > currentProgress) {
                    updateUI(this.currentTime);
                }
            });
        }

        window.onYouTubeIframeAPIReady = function() {
            const player = new YT.Player('youtube-player', {
                events: {
                    'onReady': function(event) {
                        if (currentProgress > 0) {
                            event.target.seekTo(currentProgress);
                        }
                    },
                    'onStateChange': onPlayerStateChange
                }
            });

            let ytInterval;
            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.PLAYING) {
                    if (ytInterval) clearInterval(ytInterval);
                    ytInterval = setInterval(() => {
                        let currentTime = player.getCurrentTime();
                        if (currentTime > currentProgress) {
                            updateUI(currentTime);
                        }
                    }, 1000);
                } else {
                    if (ytInterval) clearInterval(ytInterval);
                }
            }
        };
    });
</script>
<?php endif; ?>