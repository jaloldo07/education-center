<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Teacher Dashboard');

// 🔥 Kunlarni chiroyli chiqarish uchun massiv
$daysOfWeek = [
    1 => Yii::t('app', 'Monday'),
    2 => Yii::t('app', 'Tuesday'),
    3 => Yii::t('app', 'Wednesday'),
    4 => Yii::t('app', 'Thursday'),
    5 => Yii::t('app', 'Friday'),
    6 => Yii::t('app', 'Saturday'),
    7 => Yii::t('app', 'Sunday'),
];
?>

<style>
    /* UMUMIY CONTAINER */
    .teacher-dashboard {
        font-family: 'Nunito', sans-serif;
        padding-bottom: 50px;
    }

    /* 1. SECTION HEADERS */
    .section-title-glass {
        font-size: 1.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-shadow: 0 0 10px rgba(67, 97, 238, 0.5);
    }

    /* 2. STATS CARDS (NEON) */
    .stat-glass-box {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-glass-box:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        border-color: rgba(255, 255, 255, 0.2);
    }

    /* Icon Backgrounds */
    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .icon-bg-primary {
        background: linear-gradient(135deg, #4361ee, #3f37c9);
        color: white;
    }

    .icon-bg-success {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: #064e3b;
    }

    .icon-bg-info {
        background: linear-gradient(135deg, #4cc9f0, #4895ef);
        color: white;
    }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        color: white;
        line-height: 1;
    }

    .stat-info p {
        color: rgba(255, 255, 255, 0.6);
        margin: 5px 0 0 0;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* 3. COURSE CARDS */
    .course-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        transition: 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .course-glass-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        border-color: #4cc9f0;
    }

    .card-gradient-top {
        height: 6px;
        background: linear-gradient(90deg, #4361ee, #4cc9f0);
    }

    .course-card-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .course-title {
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .course-desc {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .course-meta {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
    }

    /* 4. GLASS TABLE */
    .glass-table-container {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 20px;
        overflow-x: auto;
    }

    .table-glass {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        color: white;
    }

    .table-glass th {
        padding: 15px;
        text-align: left;
        font-weight: 700;
        color: #4cc9f0;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .table-glass td {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .table-glass tr:last-child td {
        border-bottom: none;
    }

    .table-glass tr:hover td {
        background: rgba(255, 255, 255, 0.03);
    }

    /* 5. BUTTONS */
    .btn-calendar {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-calendar:hover {
        background: #4cc9f0;
        color: white;
        box-shadow: 0 0 15px rgba(76, 201, 240, 0.4);
    }

    .btn-action-glass {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        color: white;
        text-decoration: none;
        margin-right: 5px;
    }

    .btn-primary-glass {
        background: rgba(67, 97, 238, 0.2);
        color: #4361ee;
    }

    .btn-primary-glass:hover {
        background: #4361ee;
        color: white;
    }

    .btn-info-glass {
        background: rgba(76, 201, 240, 0.2);
        color: #4cc9f0;
    }

    .btn-info-glass:hover {
        background: #4cc9f0;
        color: white;
    }

    .btn-success-glass {
        background: rgba(74, 222, 128, 0.2);
        color: #4ade80;
    }

    .btn-success-glass:hover {
        background: #4ade80;
        color: #064e3b;
    }
</style>

<div class="teacher-dashboard">

    <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeInDown">
        <div>
            <h1 class="text-white fw-bold mb-1">
                <?= Yii::t('app', 'Welcome') ?>, <span style="color: #4cc9f0;"><?= Html::encode($teacher->full_name) ?></span>!
            </h1>
            <p class="text-white-50 mb-0">Here is what's happening with your courses today.</p>
        </div>
        <?= Html::a('<i class="fas fa-calendar-alt"></i> ' . Yii::t('app', 'Full Calendar'), ['calendar'], ['class' => 'btn-calendar']) ?>
    </div>

    <div class="row mb-5 animate__animated animate__fadeInUp">
        <div class="col-md-4 mb-3">
            <a href="#my-courses" style="text-decoration: none;">
                <div class="stat-glass-box">
                    <div class="stat-icon-wrapper icon-bg-primary">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['totalCourses'] ?></h3>
                        <p><?= Yii::t('app', 'My Courses') ?></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="#my-groups" style="text-decoration: none;">
                <div class="stat-glass-box">
                    <div class="stat-icon-wrapper icon-bg-success">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['totalGroups'] ?></h3>
                        <p><?= Yii::t('app', 'My Groups') ?></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="<?= Url::to(['my-students']) ?>" style="text-decoration: none;">
                <div class="stat-glass-box">
                    <div class="stat-icon-wrapper icon-bg-info">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $stats['totalStudents'] ?></h3>
                        <p><?= Yii::t('app', 'Total Students') ?></p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div id="my-courses" class="mb-5 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
        <h2 class="section-title-glass">
            <i class="fas fa-book-open text-primary"></i> <?= Yii::t('app', 'My Courses') ?>
        </h2>

        <?php if (empty($courses)): ?>
            <div class="glass-table-container text-center text-white-50 py-5">
                <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                <p><?= Yii::t('app', 'No courses assigned yet.') ?></p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <?php
                    // 🔥 KURS BOSILGANDA GURUHGA O'TISH MANTIG'I
                    $group = $course->getGroups()->one();
                    $targetUrl = $group ? ['/teacher/group', 'id' => $group->id] : ['//site/course-detail', 'id' => $course->id];
                    ?>
                    <div class="col-md-4 mb-4">
                        <?= Html::a(
                            '<div class="course-glass-card">
                                <div class="card-gradient-top"></div>
                                <div class="course-card-body">
                                    <h5 class="course-title">' . Html::encode($course->name) . '</h5>
                                    <p class="course-desc">' . Html::encode(\yii\helpers\StringHelper::truncate($course->description, 80)) . '</p>
                                    
                                    <div class="course-meta">
                                        <span><i class="far fa-clock text-info me-1"></i> ' . $course->duration . ' mo</span>
                                        <span class="text-success fw-bold">' . number_format($course->price, 0) . ' UZS</span>
                                    </div>
                                </div>
                            </div>',
                            $targetUrl, // <-- Dinamik link
                            ['style' => 'text-decoration: none; height: 100%; display: block;']
                        ) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="my-groups" class="mb-5 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <h2 class="section-title-glass">
            <i class="fas fa-users text-success"></i> <?= Yii::t('app', 'My Groups') ?>
        </h2>

        <div class="glass-table-container">
            <?php if (empty($groups)): ?>
                <p class="text-white-50 text-center py-4 mb-0"><?= Yii::t('app', 'No groups assigned yet.') ?></p>
            <?php else: ?>
                <table class="table-glass table-hover">
                    <thead>
                        <tr>
                            <th><?= Yii::t('app', 'Group Name') ?></th>
                            <th><?= Yii::t('app', 'Course') ?></th>
                            <th><?= Yii::t('app', 'Students') ?></th>
                            <th class="text-end"><?= Yii::t('app', 'Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $group): ?>
                            <?php
                            $groupUrl = \yii\helpers\Url::to(['/teacher/group', 'id' => $group->id]);
                            ?>
                            <tr onclick="location.href='<?= $groupUrl ?>'" style="cursor: pointer;">
                                <td>
                                    <strong class="text-white"><?= Html::encode($group->name) ?></strong>
                                </td>
                                <td class="text-white-50"><?= Html::encode($group->course->name) ?></td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25">
                                        <?= count($group->students) ?> <?= Yii::t('app', 'students') ?>
                                    </span>
                                </td>
                                <td class="text-end" onclick="event.stopPropagation()">
                                    <?= Html::a(
                                        '<i class="fas fa-eye"></i>',
                                        ['/teacher/group', 'id' => $group->id],
                                        ['class' => 'btn-action-glass btn-primary-glass', 'title' => Yii::t('app', 'View')]
                                    ) ?>

                                    <?= Html::a(
                                        '<i class="fas fa-calendar-alt"></i>',
                                        ['/teacher/schedule', 'id' => $group->id],
                                        ['class' => 'btn-action-glass btn-info-glass', 'title' => Yii::t('app', 'Schedule')]
                                    ) ?>

                                    <?= Html::a(
                                        '<i class="fas fa-clipboard-check"></i>',
                                        ['/teacher/attendance', 'id' => $group->id],
                                        ['class' => 'btn-action-glass btn-success-glass', 'title' => Yii::t('app', 'Attendance')]
                                    ) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div id="my-schedule" class="animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
        <h2 class="section-title-glass mt-5">
            <i class="fas fa-calendar-week text-warning"></i> <?= Yii::t('app', 'My Schedule') ?>
        </h2>

        <div class="glass-table-container">
            <?php if (empty($schedules)): ?>
                <p class="text-white-50 text-center py-4 mb-0"><?= Yii::t('app', 'No schedule available.') ?></p>
            <?php else: ?>
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th><i class="far fa-calendar-alt me-2"></i> <?= Yii::t('app', 'Day') ?></th>
                            <th><i class="far fa-clock me-2"></i> <?= Yii::t('app', 'Time') ?></th>
                            <th><i class="fas fa-users me-2"></i> <?= Yii::t('app', 'Group') ?></th>
                            <th><i class="fas fa-book me-2"></i> <?= Yii::t('app', 'Course') ?></th>
                            <th><i class="fas fa-map-marker-alt me-2"></i> <?= Yii::t('app', 'Room') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedules as $schedule): ?>
                            <tr>
                                <td>
                                    <strong class="text-warning">
                                        <?= isset($daysOfWeek[$schedule->day_of_week]) ? $daysOfWeek[$schedule->day_of_week] : $schedule->day_of_week ?>
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-dark bg-opacity-50 border border-secondary text-white px-2 py-1 fs-6">
                                        <?= substr($schedule->start_time, 0, 5) ?> - <?= substr($schedule->end_time, 0, 5) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= Html::a(Html::encode($schedule->group->name), ['/teacher/group', 'id' => $schedule->group_id], ['class' => 'text-info fw-bold text-decoration-none']) ?>
                                </td>
                                <td class="text-white-50">
                                    <?= Html::encode($schedule->group->course->name) ?>
                                </td>
                                <td>
                                <td>
                                    <span class="text-white bg-secondary bg-opacity-25 px-2 py-1 rounded">
                                        <?= Html::encode($schedule->group->room ?? '-') ?>
                                    </span>
                                </td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>Html::encode($schedule->room_number)