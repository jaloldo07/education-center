<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\TestQuestion;

$this->title = Yii::t('app', 'Manage Questions') . ': ' . $test->title;
?>

<style>
    /* 1. Page Container */
    .manage-questions-page {
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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .header-title h4 {
        font-weight: 800;
        color: white;
        margin: 0;
        font-size: 1.5rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
    }

    .header-subtitle {
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
        font-size: 1rem;
    }

    /* 3. Stats Section */
    .stats-glass-box {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-badge {
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-badge.info {
        background: rgba(56, 189, 248, 0.2);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.3);
    }

    .stat-badge.success {
        background: rgba(74, 222, 128, 0.2);
        color: #4ade80;
        border: 1px solid rgba(74, 222, 128, 0.3);
    }

    /* 4. Question Cards */
    .question-glass-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        transition: 0.3s;
    }

    .question-glass-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.15);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .q-header {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .q-number {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 0 15px rgba(67, 97, 238, 0.4);
    }

    .q-body {
        flex-grow: 1;
    }

    .q-text {
        font-weight: 700;
        color: white;
        font-size: 1.1rem;
        margin-bottom: 15px;
        line-height: 1.5;
    }

    .q-meta {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .meta-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .meta-type {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.8);
    }

    .meta-points {
        background: rgba(74, 222, 128, 0.15);
        color: #4ade80;
    }

    /* Options */
    .options-glass-list {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        padding: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .option-item {
        padding: 8px 12px;
        border-radius: 8px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95rem;
    }

    .option-item:last-child {
        margin-bottom: 0;
    }

    .option-item.correct {
        background: rgba(74, 222, 128, 0.15);
        color: #4ade80;
        border: 1px solid rgba(74, 222, 128, 0.2);
        font-weight: 600;
    }

    .option-letter {
        margin-right: 10px;
        font-weight: 700;
        opacity: 0.7;
    }

    /* Actions */
    .q-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-left: 20px;
    }

    .btn-icon-glass {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        text-decoration: none;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
    }

    .btn-edit {
        color: #fbbf24;
    }

    .btn-edit:hover {
        background: #fbbf24;
        color: black;
    }

    .btn-delete {
        color: #f87171;
    }

    .btn-delete:hover {
        background: #f87171;
        color: white;
    }

    /* Buttons */
    .btn-create-neon {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: #064e3b;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(74, 222, 128, 0.4);
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-create-neon:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 30px rgba(74, 222, 128, 0.6);
        color: #064e3b;
    }

    .btn-glass-back {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 8px 20px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-glass-back:hover {
        background: white;
        color: black;
    }

    /* Empty State */
    .empty-glass {
        text-align: center;
        padding: 60px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        border: 1px dashed rgba(255, 255, 255, 0.2);
    }
</style>


<div class="manage-questions-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="glass-header animate__animated animate__fadeInDown">
                    <div>
                        <div class="header-title">
                            <h4><i class="bi bi-list-check me-2"></i> <?= Yii::t('app', 'Manage Questions') ?></h4>
                        </div>
                        <div class="header-subtitle"><?= Html::encode($test->title) ?></div>
                    </div>
                    <?= Html::a('<i class="bi bi-arrow-left me-1"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn-glass-back']) ?>
                </div>

                <div class="stats-glass-box animate__animated animate__fadeInUp">
                    <div class="d-flex gap-3">
                        <div class="stat-badge info">
                            <i class="bi bi-question-circle"></i> <?= Yii::t('app', 'Questions') ?>: <?= count($questions) ?>
                        </div>
                        <div class="stat-badge success">
                            <i class="bi bi-trophy"></i> <?= Yii::t('app', 'Total Points') ?>: <?= $test->getTotalPoints() ?>
                        </div>
                    </div>
                    <?= Html::a('<i class="bi bi-plus-circle"></i> ' . Yii::t('app', 'Add Question'), ['add-question', 'test_id' => $test->id], ['class' => 'btn-create-neon']) ?>
                </div>

                <?php if (empty($questions)): ?>
                    <div class="empty-glass animate__animated animate__fadeInUp">
                        <i class="bi bi-inbox fa-3x mb-3 text-white-50"></i>
                        <h5 class="text-white"><?= Yii::t('app', 'No questions yet') ?></h5>
                        <p class="text-white-50 mb-4"><?= Yii::t('app', 'Add your first question to get started') ?></p>
                        <?= Html::a('<i class="bi bi-plus-circle me-2"></i> ' . Yii::t('app', 'Add Question'), ['add-question', 'test_id' => $test->id], ['class' => 'btn-create-neon']) ?>
                    </div>
                <?php else: ?>

                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question-glass-card animate__animated animate__fadeInUp">
                            <div class="d-flex">
                                <div class="q-number"><?= $index + 1 ?></div>

                                <div class="q-body ps-3">
                                    <div class="q-text">
                                        <?= Html::encode($question->question_text) ?>
                                    </div>

                                    <div class="q-meta">
                                        <span class="meta-badge meta-type">
                                            <?php
                                            $rawLabel = TestQuestion::getTypeOptions()[$question->question_type] ?? $question->question_type;
                                            echo Yii::t('app', $rawLabel);
                                            ?>
                                        </span>
                                        <span class="meta-badge meta-points">
                                            <i class="bi bi-star-fill me-1"></i> <?= $question->points ?> <?= Yii::t('app', 'pts') ?>
                                        </span>
                                    </div>

                                    <?php if ($question->question_type !== 'text_answer'): // Constant o'rniga string ishlatildi xatolik bo'lmasligi uchun 
                                    ?>
                                        <div class="options-glass-list">
                                            <?php foreach ($question->optionsArray as $optIndex => $option): ?>
                                                <div class="option-item <?= in_array($optIndex, $question->correctAnswerArray) ? 'correct' : '' ?>">
                                                    <div>
                                                        <span class="option-letter"><?= chr(65 + $optIndex) ?>.</span>
                                                        <?= Html::encode($option) ?>
                                                    </div>
                                                    <?php if (in_array($optIndex, $question->correctAnswerArray)): ?>
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="options-glass-list">
                                            <div class="option-item correct">
                                                <div>
                                                    <i class="bi bi-keyboard me-2"></i>
                                                    <strong><?= Yii::t('app', 'Expected Answer:') ?></strong>
                                                    <span class="ms-2 text-white"><?= Html::encode($question->correctAnswerArray[0] ?? '') ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="q-actions">
                                    <?= Html::a('<i class="bi bi-pencil"></i>', ['edit-question', 'id' => $question->id], [
                                        'class' => 'btn-icon-glass btn-edit',
                                        'title' => Yii::t('app', 'Edit')
                                    ]) ?>
                                    <?= Html::a('<i class="bi bi-trash"></i>', ['delete-question', 'id' => $question->id], [
                                        'class' => 'btn-icon-glass btn-delete',
                                        'data-method' => 'post',
                                        'data-confirm' => Yii::t('app', 'Delete this question?'),
                                        'title' => Yii::t('app', 'Delete')
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>