<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Teacher Dashboard';
?>


<style>
    /* =============================== */
    /* GLOBAL DASHBOARD STYLE */
    /* =============================== */
    .teacher-dashboard {
        animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Title */
    .teacher-dashboard h1 {
        font-weight: 700;
        color: #414fde;
    }

    .border-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    /* Buttons (Full calendar button) */
    .teacher-dashboard .btn-info {
        background: linear-gradient(135deg, #414fde, #6e7bff);
        border: none;
        color: #fff !important;
        font-weight: 600;
        padding: 10px 20px;
        transition: 0.3s;
    }

    .teacher-dashboard .btn-info:hover {
        opacity: .85;
        transform: translateY(-2px);
    }


    /* =============================== */
    /* STATS CARDS */
    /* =============================== */
    .stat-card {
        border-radius: 18px;
        transition: 0.3s ease;
        background: linear-gradient(135deg, #414fde, #6a6ff6) !important;
        border: none;
    }

    .stat-card .card-body h3 {
        font-size: 32px;
        font-weight: bold;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px rgba(65, 79, 222, 0.3);
    }


    /* =============================== */
    /* COURSE CARDS */
    /* =============================== */
    .teacher-dashboard .card.border-primary {
        border: 2px solid #414fde !important;
        border-radius: 16px;
        transition: 0.3s;
    }

    .teacher-dashboard .card.border-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 22px rgba(65, 79, 222, 0.2);
    }

    /* Course titles */
    .teacher-dashboard .card-title {
        font-weight: 700;
        color: #414fde;
    }

    /* =============================== */
    /* CARD HEADERS */
    /* =============================== */
    .teacher-dashboard .card-header {
        padding: 18px;
        font-weight: bold;
        font-size: 18px;
        border-top-left-radius: 14px !important;
        border-top-right-radius: 14px !important;
    }

    .teacher-dashboard .bg-primary {
        background: linear-gradient(135deg, #414fde, #6e7bff) !important;
    }

    .teacher-dashboard .bg-success {
        background: linear-gradient(135deg, #34c38f, #28a76b) !important;
    }

    /* =============================== */
    /* GROUP TABLE */
    /* =============================== */
    .teacher-dashboard table.table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
    }

    .teacher-dashboard table thead {
        background: #414fde;
        color: #fff;
    }

    .teacher-dashboard table thead th {
        padding: 15px;
        font-size: 14px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .teacher-dashboard table tbody tr:hover {
        background: rgba(65, 79, 222, 0.08);
        cursor: pointer;
    }

    .teacher-dashboard table .badge.bg-info {
        background: linear-gradient(135deg, #6a6ff6, #414fde) !important;
        padding: 6px 10px;
        font-size: 12px;
    }

    /* =============================== */
    /* ACTION BUTTONS */
    /* =============================== */
    .teacher-dashboard .btn-sm {
        padding: 6px 12px;
        font-weight: 600;
        border-radius: 8px;
        transition: 0.25s;
    }

    .teacher-dashboard .btn-sm i {
        margin-right: 3px;
    }

    .teacher-dashboard .btn-sm:hover {
        transform: translateY(-2px);
        opacity: .85;
    }

    /* Primary */
    .teacher-dashboard .btn-sm.btn-primary {
        background: #414fde;
        border: none;
    }

    /* Info */
    .teacher-dashboard .btn-sm.btn-info {
        background: #6a6ff6;
        border: none;
    }

    /* Success */
    .teacher-dashboard .btn-sm.btn-success {
        background: #28a76b;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.05) !important;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.4) !important;
    }
</style>



<div class="teacher-dashboard">
    <!-- Yangi qo'shilgan qism -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">
            <i class="fas fa-chalkboard-teacher text-success"></i>
            Welcome, <?= Html::encode($teacher->full_name) ?>!
        </h1>
        <div>
            <?= Html::a('<i class="fas fa-calendar"></i> Full Calendar', ['calendar'], ['class' => 'btn btn-info btn-lg']) ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <a href="#my-courses" style="text-decoration: none;">
                <div class="card bg-primary text-white shadow stat-card" style="cursor: pointer;">
                    <div class="card-body">
                        <h3><i class="fas fa-book"></i> <?= $stats['totalCourses'] ?></h3>
                        <p class="mb-0">My Courses</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#my-groups" style="text-decoration: none;">
                <div class="card bg-primary text-white shadow stat-card" style="cursor: pointer;">
                    <div class="card-body">
                        <h3><i class="fas fa-users"></i> <?= $stats['totalGroups'] ?></h3>
                        <p class="mb-0">My Groups</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <?= Html::a('
    <div class="card bg-info text-white shadow stat-card" style="cursor: pointer;">
        <div class="card-body">
            <h3><i class="fas fa-user-graduate"></i> ' . $stats['totalStudents'] . '</h3>
            <p class="mb-0">Total Students</p>
        </div>
    </div>', ['my-students'], ['style' => 'text-decoration: none;']) ?>
        </div>
    </div>

    <!-- My Courses -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white" id="my-courses">
            <h2 class="mb-0 animate__animated animate__fadeInUp"><i class="fas fa-book-open"></i> My Courses</h2>
        </div>
        <div class="card-body">
            <?php if (empty($courses)): ?>
                <p class="text-muted">No courses assigned yet.</p>
            <?php else: ?>
                <div class="row">
                    <div class="row">
                        <?php foreach ($courses as $course): ?>
                            <div class="col-md-4 mb-3">
                                <?= Html::a(
                                    '
        <div class="card h-100 border-primary" style="cursor: pointer; transition: all 0.3s;">
            <div class="card-body">
                <h5 class="card-title">' . Html::encode($course->name) . '</h5>
                <p class="card-text">' . Html::encode($course->description) . '</p>
                <hr>
                <p class="mb-1"><strong>Duration:</strong> ' . $course->duration . ' months</p>
                <p class="mb-0"><strong>Price:</strong> ' . number_format($course->price, 0) . ' UZS</p>
            </div>
        </div>',
                                    ['//site/course-detail', 'id' => $course->id],  // ← Link qo'shildi
                                    ['style' => 'text-decoration: none; color: inherit;']
                                ) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>




    <!-- My Groups -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white" id="my-groups">
            <h2 class="mb-0 animate__animated animate__fadeInUp"><i class="fas fa-users"></i> My Groups</h2>
        </div>
        <div class="card-body">
            <?php if (empty($groups)): ?>
                <p class="text-muted">No groups assigned yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Group Name</th>
                                <th>Course</th>
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groups as $group): ?>
                                <tr>
                                    <td><strong><?= Html::encode($group->name) ?></strong></td>
                                    <td><?= Html::encode($group->course->name) ?></td>
                                    <td>
                                        <span class="badge bg-info"><?= count($group->students) ?> students</span>
                                    </td>
                                    <td>
                                        <?= Html::a('<i class="fas fa-eye"></i> View', ['/teacher/group', 'id' => $group->id], ['class' => 'btn btn-sm btn-primary']) ?>
                                        <?= Html::a('<i class="fas fa-calendar-alt"></i> Schedule', ['/teacher/schedule', 'id' => $group->id], ['class' => 'btn btn-sm btn-info']) ?>
                                        <?= Html::a('<i class="fas fa-clipboard-check"></i> Attendance', ['/teacher/attendance', 'id' => $group->id], ['class' => 'btn btn-sm btn-success']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>


        <?= Html::a(
        '<i class="bi bi-clipboard-check"></i> My Tests',
        ['/test/index'],
        [
            'class' => 'btn btn-primary',
            'style' => 'background: linear-gradient(135deg, #414fde, #6b74ff) !important; 
                    border: none; 
                    border-radius: 12px; 
                    padding: 12px 24px; 
                    font-weight: 600; 
                    box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3); 
                    transition: all 0.3s ease;
                    margin-top: 20px;'
        ]
    ) ?>


</div>