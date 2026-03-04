<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Lessons');
?>

<style>
    /* 1. Page Container */
    .lessons-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Header Gradient */
    .glass-header {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .header-title h2 {
        font-weight: 800;
        color: white;
        margin: 0;
        font-size: 2rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
    }

    .header-subtitle {
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
        font-size: 1rem;
    }

    /* 3. Lesson Cards (Glass) */
    .lesson-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        margin-bottom: 25px;
        overflow: hidden;
        transition: transform 0.3s;
    }

    .lesson-glass-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }

    /* Card Header (Course Title) */
    .card-course-header {
        background: linear-gradient(90deg, #4361ee 0%, #3a0ca3 100%);
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .course-title-text {
        font-weight: 700;
        margin: 0;
        font-size: 1.1rem;
        display: flex; align-items: center; gap: 10px;
    }

    /* Lesson Item */
    .lesson-item-glass {
        padding: 20px 25px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: 0.2s;
    }
    .lesson-item-glass:last-child { border-bottom: none; }
    .lesson-item-glass:hover { background: rgba(255,255,255,0.05); }

    .lesson-number-box {
        width: 40px; height: 40px;
        background: rgba(255,255,255,0.1);
        color: white;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        margin-right: 15px;
        font-size: 1.1rem;
    }

    .lesson-title-text {
        font-weight: 600;
        color: white;
        font-size: 1.05rem;
        margin-bottom: 5px;
    }

    /* 4. Badges */
    .badge-glass {
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-right: 5px;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-video { background: rgba(244, 114, 182, 0.2); color: #f472b6; border: 1px solid rgba(244, 114, 182, 0.3); }
    .badge-text { background: rgba(96, 165, 250, 0.2); color: #60a5fa; border: 1px solid rgba(96, 165, 250, 0.3); }
    .badge-pdf { background: rgba(251, 146, 60, 0.2); color: #fb923c; border: 1px solid rgba(251, 146, 60, 0.3); }
    .badge-image { background: rgba(167, 139, 250, 0.2); color: #a78bfa; border: 1px solid rgba(167, 139, 250, 0.3); }

    .badge-difficulty { background: rgba(255,255,255,0.1); color: #cbd5e1; }
    
    .badge-published { background: rgba(74, 222, 128, 0.2); color: #4ade80; }
    .badge-draft { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }

    /* 5. Buttons */
    .btn-create-neon {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: #064e3b;
        border: none;
        padding: 10px 25px;
        border-radius: 12px;
        font-weight: 700;
        box-shadow: 0 0 15px rgba(74, 222, 128, 0.4);
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-create-neon:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(74, 222, 128, 0.6);
        color: #064e3b;
    }

    .btn-test-neon {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 12px;
        font-weight: 700;
        box-shadow: 0 0 15px rgba(67, 97, 238, 0.4);
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-test-neon:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(67, 97, 238, 0.6);
        color: white;
    }

    .btn-action-glass {
        width: 35px; height: 35px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: 0.3s;
        text-decoration: none;
        margin-left: 5px;
    }
    
    .btn-glass-edit { background: rgba(56, 189, 248, 0.2); color: #38bdf8; }
    .btn-glass-edit:hover { background: #38bdf8; color: white; }

    .btn-glass-del { background: rgba(248, 113, 113, 0.2); color: #f87171; }
    .btn-glass-del:hover { background: #f87171; color: white; }

    .btn-link-test {
        background: rgba(255,255,255,0.2);
        color: white;
        font-size: 0.8rem;
        padding: 5px 12px;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-link-test:hover { background: white; color: #4361ee; }

    /* Empty State */
    .empty-glass {
        text-align: center;
        padding: 60px;
        background: rgba(255,255,255,0.05);
        border-radius: 20px;
        border: 1px dashed rgba(255,255,255,0.2);
    }

</style>

<div class="lessons-page">
    <div class="container">
        
        <div class="glass-header animate__animated animate__fadeInDown">
            <div class="header-title">
                <h2>📚 <?= Html::encode($this->title) ?></h2>
                <div class="header-subtitle"><?= Yii::t('app', 'Manage your course lessons and educational content') ?></div>
            </div>
            <div class="d-flex gap-3">
                <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Create Lesson'), ['create'], ['class' => 'btn-create-neon']) ?>
                <?= Html::a('<i class="fas fa-clipboard-check"></i> ' . Yii::t('app', 'Test'), ['/test/index'], ['class' => 'btn-test-neon']) ?>
            </div>
        </div>

        <?php if (empty($lessons)): ?>
            <div class="empty-glass animate__animated animate__fadeInUp">
                <i class="fas fa-book-open fa-4x mb-3 text-white-50"></i>
                <h4 class="text-white"><?= Yii::t('app', 'No Lessons Yet') ?></h4>
                <p class="text-white-50 mb-4"><?= Yii::t('app', 'Start creating engaging lessons for your students') ?></p>
                <?= Html::a(Yii::t('app', 'Create First Lesson'), ['create'], ['class' => 'btn-create-neon']) ?>
            </div>
        <?php else: ?>
            
            <?php 
            $groupedLessons = [];
            foreach ($lessons as $lesson) {
                $groupedLessons[$lesson->course_id]['course_name'] = $lesson->course->name;
                $groupedLessons[$lesson->course_id]['lessons'][] = $lesson;
            }
            ?>

            <?php foreach ($groupedLessons as $courseId => $group): ?>
                <div class="lesson-glass-card animate__animated animate__fadeInUp">
                    <div class="card-course-header">
                        <div class="course-title-text">
                            <i class="fas fa-graduation-cap"></i> <?= Html::encode($group['course_name']) ?>
                        </div>
                        <?= Html::a('<i class="fas fa-link me-1"></i> ' . Yii::t('app', 'Link Test'), ['link-test', 'course_id' => $courseId], ['class' => 'btn-link-test']) ?>
                    </div>

                    <div class="card-body-glass p-0">
                        <?php foreach ($group['lessons'] as $lesson): ?>
                            <div class="lesson-item-glass">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="lesson-number-box">
                                        <?= $lesson->order_number ?>
                                    </div>
                                    <div>
                                        <div class="lesson-title-text">
                                            <?= Html::encode($lesson->title) ?>
                                        </div>
                                        
                                        <div>
                                            <?php 
                                            $typeIcon = '📚';
                                            $typeClass = 'badge-text';
                                            if($lesson->content_type == 'video') { $typeIcon='🎥'; $typeClass='badge-video'; }
                                            elseif($lesson->content_type == 'pdf') { $typeIcon='📄'; $typeClass='badge-pdf'; }
                                            elseif($lesson->content_type == 'image') { $typeIcon='🖼️'; $typeClass='badge-image'; }
                                            ?>
                                            
                                            <span class="badge-glass <?= $typeClass ?>">
                                                <?= $typeIcon ?> <?= Yii::t('app', ucfirst($lesson->content_type)) ?>
                                            </span>

                                            <span class="badge-glass badge-difficulty">
                                                <?= Yii::t('app', ucfirst($lesson->difficulty_level)) ?>
                                            </span>

                                            <?php if ($lesson->is_published): ?>
                                                <span class="badge-glass badge-published">
                                                    ✓ <?= Yii::t('app', 'Published') ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-glass badge-draft">
                                                    ✎ <?= Yii::t('app', 'Draft') ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id' => $lesson->id], [
                                        'class' => 'btn-action-glass btn-glass-edit',
                                        'title' => Yii::t('app', 'Edit')
                                    ]) ?>
                                    <?= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id' => $lesson->id], [
                                        'class' => 'btn-action-glass btn-glass-del',
                                        'data' => [
                                            'confirm' => Yii::t('app', 'Delete this lesson?'),
                                            'method' => 'post',
                                        ],
                                        'title' => Yii::t('app', 'Delete')
                                    ]) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</div>