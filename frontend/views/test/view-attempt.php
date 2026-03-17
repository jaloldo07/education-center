<?php
use yii\helpers\Html;
use common\models\TestQuestion;

$this->title = Yii::t('app', 'View Attempt Details');
?>

<style>
    /* 1. Page Container */
    .attempt-view-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Header Gradient */
    .glass-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        padding: 25px 30px;
        color: white;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.4);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title h4 {
        font-weight: 800;
        margin: 0;
        font-size: 1.5rem;
    }

    .header-subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-top: 5px;
    }

    /* 3. Main Glass Card */
    .main-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    /* 4. Student & Test Info Section */
    .info-section {
        background: rgba(255,255,255,0.03);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .info-title {
        color: #4cc9f0;
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 1.1rem;
        display: flex; align-items: center; gap: 10px;
    }

    /* Face Photo */
    .face-photo-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid #4361ee;
        box-shadow: 0 0 20px rgba(67, 97, 238, 0.3);
    }
    .face-photo-wrapper img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .face-label {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: rgba(67, 97, 238, 0.8);
        color: white;
        font-size: 0.7rem;
        text-align: center;
        padding: 3px;
        text-transform: uppercase;
        font-weight: 700;
    }

    /* Info Table */
    .info-table-glass {
        width: 100%;
        color: rgba(255,255,255,0.8);
    }
    .info-table-glass th {
        text-align: left;
        padding: 8px 0;
        color: rgba(255,255,255,0.5);
        font-weight: 600;
        width: 40%;
    }
    .info-table-glass td {
        padding: 8px 0;
        font-weight: 600;
    }
    .info-table-glass tr { border-bottom: 1px solid rgba(255,255,255,0.05); }
    .info-table-glass tr:last-child { border-bottom: none; }

    /* 5. Score Card */
    .score-glass-card {
        border-radius: 16px;
        padding: 30px;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    
    .score-glass-card.passed {
        background: radial-gradient(circle at center, rgba(74, 222, 128, 0.2), rgba(74, 222, 128, 0.05));
        border: 1px solid rgba(74, 222, 128, 0.3);
        box-shadow: 0 0 30px rgba(74, 222, 128, 0.1);
    }
    .score-glass-card.failed {
        background: radial-gradient(circle at center, rgba(248, 113, 113, 0.2), rgba(248, 113, 113, 0.05));
        border: 1px solid rgba(248, 113, 113, 0.3);
        box-shadow: 0 0 30px rgba(248, 113, 113, 0.1);
    }

    .score-val { font-size: 3.5rem; font-weight: 800; line-height: 1; margin-bottom: 10px; }
    .score-status { font-size: 1.2rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; }
    
    .text-passed { color: #4ade80; text-shadow: 0 0 15px rgba(74, 222, 128, 0.5); }
    .text-failed { color: #f87171; text-shadow: 0 0 15px rgba(248, 113, 113, 0.5); }

    .points-badge {
        background: rgba(255,255,255,0.1);
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        color: white;
    }

    /* 6. Question Review */
    .section-header {
        font-size: 1.3rem;
        font-weight: 700;
        color: white;
        margin: 40px 0 20px 0;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex; align-items: center; gap: 10px;
    }

    .q-review-card {
        background: rgba(255,255,255,0.03);
        border-radius: 16px;
        margin-bottom: 20px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .q-review-header {
        padding: 15px 20px;
        display: flex; justify-content: space-between; align-items: center;
        background: rgba(0,0,0,0.2);
    }
    .q-review-header.correct { border-left: 4px solid #4ade80; }
    .q-review-header.incorrect { border-left: 4px solid #f87171; }

    .q-status-icon { font-size: 1.2rem; margin-right: 10px; }
    .text-success-neon { color: #4ade80; }
    .text-danger-neon { color: #f87171; }

    .q-review-body { padding: 20px; color: rgba(255,255,255,0.9); }
    .q-text { font-size: 1.05rem; font-weight: 600; margin-bottom: 20px; }

    /* Answer Boxes */
    .ans-box {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }
    .ans-box.student {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .ans-box.student.correct { border-color: #4ade80; background: rgba(74, 222, 128, 0.1); }
    .ans-box.student.incorrect { border-color: #f87171; background: rgba(248, 113, 113, 0.1); }

    .ans-box.key {
        background: rgba(74, 222, 128, 0.05);
        border: 1px dashed #4ade80;
        color: #4ade80;
    }

    .ans-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        opacity: 0.7;
        display: block;
        margin-bottom: 5px;
    }

    /* Button */
    .btn-glass-back {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
        padding: 8px 20px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn-glass-back:hover { background: white; color: black; }

</style>

<div class="attempt-view-page">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="glass-header animate__animated animate__fadeInDown">
                    <div>
                        <div class="header-title">
                            <h4><i class="bi bi-file-earmark-person me-2"></i> <?= Yii::t('app', 'Attempt Details') ?></h4>
                        </div>
                        <div class="header-subtitle">
                            <?= Yii::t('app', 'Student') ?>: <strong><?= Html::encode($attempt->student->full_name) ?></strong>
                        </div>
                    </div>
                    <?= Html::a('<i class="bi bi-arrow-left me-1"></i> ' . Yii::t('app', 'Back to Results'), ['results', 'id' => $attempt->test_id], ['class' => 'btn-glass-back']) ?>
                </div>

                <div class="main-glass-card animate__animated animate__fadeInUp">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="info-section">
                                <div class="info-title">
                                    <i class="bi bi-clipboard-check"></i> <?= Html::encode($attempt->test->title) ?>
                                </div>
                                
                                <div class="d-flex gap-4 align-items-start">
                                    <?php if (!empty($attempt->face_photo)): ?>
                                        <div class="face-photo-wrapper">
                                            <img src="/frontend/web/uploads/faces/<?= Html::encode($attempt->face_photo) ?>" alt="Face Control">
                                            <div class="face-label"><i class="bi bi-camera me-1"></i> Verified</div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex-grow-1">
                                        <table class="info-table-glass">
                                            <tr>
                                                <th><i class="bi bi-play-circle me-2"></i> <?= Yii::t('app', 'Started') ?></th>
                                                <td><?= Yii::$app->formatter->asDatetime($attempt->started_at) ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="bi bi-stop-circle me-2"></i> <?= Yii::t('app', 'Finished') ?></th>
                                                <td><?= Yii::$app->formatter->asDatetime($attempt->finished_at) ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="bi bi-clock-history me-2"></i> <?= Yii::t('app', 'Duration') ?></th>
                                                <td><?= $attempt->getDuration() ?> mins</td>
                                            </tr>
                                            <tr>
                                                <th><i class="bi bi-geo-alt me-2"></i> <?= Yii::t('app', 'IP Address') ?></th>
                                                <td><code><?= Html::encode($attempt->ip_address) ?></code></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="score-glass-card <?= $attempt->isPassed() ? 'passed' : 'failed' ?>">
                                <div class="score-val <?= $attempt->isPassed() ? 'text-passed' : 'text-failed' ?>">
                                    <?= $attempt->score ?>%
                                </div>
                                <div class="score-status <?= $attempt->isPassed() ? 'text-passed' : 'text-failed' ?>">
                                    <?= $attempt->isPassed() ? Yii::t('app', 'PASSED') : Yii::t('app', 'FAILED') ?>
                                </div>
                                <div class="points-badge">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <?= $attempt->points_earned ?> / <?= $attempt->total_points ?> pts
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-header">
                        <i class="bi bi-list-check text-info"></i> <?= Yii::t('app', 'Answer Breakdown') ?>
                    </div>

                    <?php foreach ($answers as $index => $answer): ?>
                        <?php $question = $answer->question; ?>
                        
                        <div class="q-review-card animate__animated animate__fadeInUp">
                            <div class="q-review-header <?= $answer->is_correct ? 'correct' : 'incorrect' ?>">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-<?= $answer->is_correct ? 'check-circle-fill text-success-neon' : 'x-circle-fill text-danger-neon' ?> q-status-icon"></i>
                                    <span class="text-white fw-bold"><?= Yii::t('app', 'Question') ?> <?= $index + 1 ?></span>
                                </div>
                                <span class="badge bg-white bg-opacity-10 text-white">
                                    <?= $answer->points_awarded ?> / <?= $question->points ?> pts
                                </span>
                            </div>

                            <div class="q-review-body">
                                <div class="q-text"><?= Html::encode($question->question_text) ?></div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="ans-box student <?= $answer->is_correct ? 'correct' : 'incorrect' ?>">
                                            <span class="ans-label <?= $answer->is_correct ? 'text-success-neon' : 'text-danger-neon' ?>">
                                                <i class="bi bi-person me-1"></i> <?= Yii::t('app', 'Student Answer') ?>
                                            </span>
                                            
                                            <?php if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE || $question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE): ?>
                                                <ul class="list-unstyled mb-0">
                                                    <?php if (!empty($answer->answerArray)): ?>
                                                        <?php foreach ($answer->answerArray as $ans): ?>
                                                            <li><i class="bi bi-dot me-1"></i> <?= Html::encode($question->optionsArray[$ans] ?? 'Unknown') ?></li>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <li class="text-white-50">No answer</li>
                                                    <?php endif; ?>
                                                </ul>
                                            <?php else: ?>
                                                <div><?= Html::encode($answer->answerArray[0] ?? 'No answer') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!$answer->is_correct): ?>
                                    <div class="col-md-6">
                                        <div class="ans-box key">
                                            <span class="ans-label"><i class="bi bi-key me-1"></i> <?= Yii::t('app', 'Correct Answer') ?></span>
                                            
                                            <?php if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE || $question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE): ?>
                                                <ul class="list-unstyled mb-0">
                                                    <?php foreach ($question->correctAnswerArray as $correct): ?>
                                                        <li><i class="bi bi-check me-1"></i> <?= Html::encode($question->optionsArray[$correct] ?? 'Unknown') ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <div><?= Html::encode($question->correctAnswerArray[0] ?? 'N/A') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

            </div>
        </div>
    </div>
</div>