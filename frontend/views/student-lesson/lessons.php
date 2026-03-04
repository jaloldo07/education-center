<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $course common\models\Course */
/* @var $lessons common\models\Lesson[] */

$this->title = $course->name . ' - ' . Yii::t('app', 'Lessons');
?>

<style>
    /* 1. Page Container */
    .lessons-page {
        padding-bottom: 60px;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Hero Section (Glass) */
    .course-hero {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }

    /* Neon Glow behind Hero - Z-INDEX TUZATILDI */
    .course-hero::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(67, 97, 238, 0.4) 0%, transparent 70%);
        filter: blur(40px);
        z-index: 0; /* Orqa fonga o'tkazildi */
        pointer-events: none; /* Sichqoncha hodisalarini o'tkazib yuboradi */
    }

    /* Hero ichidagi kontent uchun wrapper (Tugmalar ishlashi uchun) */
    .hero-content {
        position: relative;
        z-index: 2; /* Kontentni oldinga chiqarish */
    }

    /* Breadcrumbs */
    .breadcrumb-custom {
        margin-bottom: 20px;
        font-size: 0.9rem;
    }
    .breadcrumb-custom a {
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        transition: 0.3s;
    }
    .breadcrumb-custom a:hover { color: var(--accent-color); }
    .breadcrumb-custom .active { color: white; font-weight: 600; }
    .breadcrumb-separator { margin: 0 10px; color: rgba(255,255,255,0.3); }

    .course-title {
        font-weight: 800;
        font-size: 2.2rem;
        margin-bottom: 10px;
        background: linear-gradient(90deg, #fff, #a5b4fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .course-description {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
        max-width: 800px;
    }

    /* 3. Lesson Cards (Glass List) */
    .section-title {
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 25px;
        display: flex; align-items: center; gap: 10px;
        text-shadow: 0 0 10px rgba(76, 201, 240, 0.3);
    }

    .lesson-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
    }

    .lesson-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        border-color: rgba(255,255,255,0.2);
    }

    /* Active/Completed States */
    .lesson-card.active {
        border-color: var(--primary-color);
        box-shadow: 0 0 15px rgba(67, 97, 238, 0.2);
    }
    
    .lesson-card.completed {
        border-color: var(--success-color);
    }

    .lesson-card.locked {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Lesson Number Box */
    .lesson-number {
        width: 45px; height: 45px;
        background: rgba(255,255,255,0.1);
        color: white;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        margin-right: 15px;
        font-size: 1.1rem;
    }

    .completed .lesson-number {
        background: rgba(74, 222, 128, 0.2);
        color: var(--success-color);
    }

    .active .lesson-number {
        background: var(--primary-color);
        box-shadow: 0 0 10px var(--primary-color);
    }

    /* Badges */
    .difficulty-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .difficulty-easy { background: rgba(74, 222, 128, 0.2); color: #4ade80; }
    .difficulty-medium { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }
    .difficulty-hard { background: rgba(248, 113, 113, 0.2); color: #f87171; }

    /* Buttons */
    .btn-start {
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn-start:hover {
        box-shadow: 0 0 15px var(--primary-color);
        color: white;
    }

    .btn-review {
        background: transparent;
        border: 1px solid var(--success-color);
        color: var(--success-color);
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .btn-review:hover {
        background: var(--success-color);
        color: #0f172a;
    }

    /* Test Card */
    .test-card {
        background: rgba(251, 191, 36, 0.1); /* Yellow tint */
        border: 1px solid rgba(251, 191, 36, 0.3);
        border-radius: 16px;
        padding: 25px;
        transition: 0.3s;
    }
    .test-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(251, 191, 36, 0.2);
    }
    
    .btn-test {
        background: var(--warning-color);
        color: #0f172a;
        font-weight: 700;
        border: none;
        padding: 10px 25px;
        border-radius: 50px;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-test:hover {
        background: #f59e0b;
        color: white;
        box-shadow: 0 0 15px var(--warning-color);
    }

    /* Score Badge */
    .score-badge {
        background: rgba(0,0,0,0.3);
        padding: 8px 15px;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        border: 1px solid rgba(255,255,255,0.1);
    }

</style>

<div class="lessons-page">
    <div class="container pt-4">
        
        <div class="course-hero animate__animated animate__fadeInDown">
            <div class="hero-content">
                <div class="breadcrumb-custom">
                    <a href="<?= \yii\helpers\Url::to(['index']) ?>"><?= Yii::t('app', 'My Lessons') ?></a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="active"><?= Html::encode($course->name) ?></span>
                </div>

                <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
                    <div>
                        <h2 class="course-title">📚 <?= Html::encode($course->name) ?></h2>
                        <p class="course-description mb-0"><?= Html::encode($course->description) ?></p>
                    </div>
                    <?= Html::a('<i class="fas fa-arrow-left me-2"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-outline-light rounded-pill px-4']) ?>
                </div>
            </div>
        </div>

        <h4 class="section-title"><i class="fas fa-list-ul"></i> <?= Yii::t('app', 'Course Content') ?></h4>
        
        <div class="row">
            <?php if (empty($lessons)): ?>
                <div class="col-12">
                    <div class="text-center py-5 glass-panel" style="background: rgba(255,255,255,0.05); border-radius: 20px;">
                        <i class="fas fa-book-open fa-4x text-white-50 mb-3"></i>
                        <h5 class="text-white"><?= Yii::t('app', 'No Lessons Available') ?></h5>
                        <p class="text-white-50"><?= Yii::t('app', 'Check back later!') ?></p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($lessons as $lesson):
                    $progress = $progressData[$lesson->id] ?? null;
                    $isCompleted = $progress && $progress->status === 'completed';
                    $isLocked = $lockedStatus[$lesson->id];

                    $typeIcons = ['video' => '🎥', 'text' => '📝', 'pdf' => '📄', 'image' => '🖼️'];
                    $icon = $typeIcons[$lesson->content_type] ?? '📚';
                    
                    $cardClass = $isCompleted ? 'completed' : ($isLocked ? 'locked' : 'active');
                    $difficultyClass = 'difficulty-' . $lesson->difficulty_level;
                ?>
                    <div class="col-md-6 mb-4">
                        <div class="lesson-card <?= $cardClass ?>">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="lesson-number"><?= $lesson->order_number ?></div>
                                    <div>
                                        <h6 class="mb-1 text-white fw-bold">
                                            <span class="me-2"><?= $icon ?></span><?= Html::encode($lesson->title) ?>
                                        </h6>
                                        <span class="difficulty-badge <?= $difficultyClass ?>">
                                            <?= Yii::t('app', ucfirst($lesson->difficulty_level)) ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <?php if ($isCompleted): ?>
                                    <i class="fas fa-check-circle text-success fs-4"></i>
                                <?php elseif ($isLocked): ?>
                                    <i class="fas fa-lock text-secondary fs-4"></i>
                                <?php endif; ?>
                            </div>

                            <p class="text-white-50 small mb-4" style="min-height: 40px;">
                                <?= Html::encode(\yii\helpers\StringHelper::truncate($lesson->description, 100)) ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($lesson->duration_minutes): ?>
                                    <small class="text-white-50">
                                        <i class="far fa-clock me-1"></i> <?= $lesson->duration_minutes ?> <?= Yii::t('app', 'min') ?>
                                    </small>
                                <?php else: ?>
                                    <span></span>
                                <?php endif; ?>

                                <?php if (!$isLocked): ?>
                                    <?= Html::a(
                                        $isCompleted ? '<i class="far fa-eye me-1"></i> ' . Yii::t('app', 'Review') : '<i class="fas fa-play me-1"></i> ' . Yii::t('app', 'Start'),
                                        ['view', 'id' => $lesson->id],
                                        ['class' => $isCompleted ? 'btn-review' : 'btn-start']
                                    ) ?>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" disabled>
                                        <i class="fas fa-lock me-1"></i> <?= Yii::t('app', 'Locked') ?>
                                    </button>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($courseTests)): ?>
            <h4 class="section-title mt-5"><i class="fas fa-tasks"></i> <?= Yii::t('app', 'Quizzes & Tests') ?></h4>
            <div class="row">
                <?php foreach ($courseTests as $courseTest):
                    $test = $courseTest->test;
                    // Logic ...
                    $allCompleted = true;
                    foreach ($lessons as $lesson) {
                        $progress = $progressData[$lesson->id] ?? null;
                        if (!$progress || $progress->status !== 'completed') { $allCompleted = false; break; }
                    }
                    $lastAttempt = \common\models\TestAttempt::find()->where(['student_id' => $student->id, 'test_id' => $test->id, 'status' => 'completed'])->orderBy(['created_at' => SORT_DESC])->one();
                ?>
                    <div class="col-md-6 mb-4">
                        <div class="test-card">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="text-white fw-bold mb-1">
                                        <?= Html::encode($test->title) ?>
                                        <?php if ($courseTest->is_final_test): ?>
                                            <span class="badge bg-danger ms-2">🏆 <?= Yii::t('app', 'FINAL') ?></span>
                                        <?php endif; ?>
                                    </h5>
                                    <p class="text-white-50 small mb-0"><?= Html::encode($test->description) ?></p>
                                </div>
                                <div class="fs-2">📝</div>
                            </div>

                            <?php if ($lastAttempt): ?>
                                <div class="score-badge d-flex justify-content-between align-items-center mb-3">
                                    <span><?= Yii::t('app', 'Last Score') ?></span>
                                    <span class="fs-5 <?= $lastAttempt->isPassed() ? 'text-success' : 'text-danger' ?>">
                                        <?= $lastAttempt->score ?>%
                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="text-end">
                                <?php if ($allCompleted): ?>
                                    
                                    <?php if ($test->isAvailable()): ?>
                                        <?= Html::a(
                                            $lastAttempt ? '<i class="fas fa-redo me-1"></i> ' . Yii::t('app', 'Retake') : '<i class="fas fa-play me-1"></i> ' . Yii::t('app', 'Start Test'),
                                            ['/student-test/start', 'id' => $test->id],
                                            ['class' => 'btn-test']
                                        ) ?>
                                    <?php else: ?>
                                        <button class="btn btn-secondary rounded-pill w-100 py-2" disabled>
                                            <i class="fas fa-lock me-2"></i> <?= Yii::t('app', 'Test yopiq') ?>
                                        </button>
                                        <div class="small text-danger mt-1 text-center"><?= Yii::t('app', 'Muddat tugagan') ?></div>
                                    <?php endif; ?>
                                    
                                <?php else: ?>
                                    <button class="btn btn-secondary rounded-pill w-100 py-2" disabled>
                                        <i class="fas fa-lock me-2"></i> <?= Yii::t('app', 'Complete lessons first') ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>