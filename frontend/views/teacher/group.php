<?php
use yii\helpers\Html;

$this->title = 'Group: ' . $group->name;
?>


<style>


/* teacher-group-view.css */

/* Main Container */
.teacher-group-view {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

/* Header Section */
.teacher-group-view h1 {
    color: #ffffff !important;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    margin-bottom: 0;
}

.teacher-group-view h1 i {
    margin-right: 10px;
}

/* Back Button */
.teacher-group-view .btn-secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 10px 25px;
    font-weight: 600;
    border-radius: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.teacher-group-view .btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

/* Cards */
.teacher-group-view .card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    transition: transform 0.3s ease;
}

.teacher-group-view .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

/* Card Headers */
.teacher-group-view .card-header {
    padding: 15px 20px;
    border-bottom: 3px solid rgba(255,255,255,0.2);
    
}

.teacher-group-view .card-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.teacher-group-view .card-header.bg-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
}

.teacher-group-view .card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #ffffff;
}

.teacher-group-view .card-header h5 i {
    margin-right: 10px;
}

/* Card Body */
.teacher-group-view .card-body {
    padding: 25px;
    background-color: #ffffff;
    
}

.teacher-group-view .card-body p {
    margin-bottom: 10px;
    font-size: 15px;
    color: #333;
}

.teacher-group-view .card-body strong {
    color: #764ba2;
    font-weight: 600;
    
}

/* Table Styles */
.teacher-group-view .table {
    margin-bottom: 0;
}

.teacher-group-view .table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    
}

.teacher-group-view .table thead th {
    border: none;
    padding: 15px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.teacher-group-view .table tbody tr {
    transition: all 0.3s ease;
}

.teacher-group-view .table tbody tr:hover {
    background-color: #f8f9ff;
    transform: scale(1.01);
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.1);
}

.teacher-group-view .table tbody td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
    color: #555;
}

.teacher-group-view .table tbody td strong {
    color: #764ba2;
    font-weight: 600;
}

/* Empty State */
.teacher-group-view .text-muted {
    color: #999 !important;
    font-style: italic;
    text-align: center;
    padding: 30px;
    font-size: 16px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .teacher-group-view {
        padding: 15px;
    }

    .teacher-group-view h1 {
        font-size: 24px;
    }

    .teacher-group-view .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }

    .teacher-group-view .btn-secondary {
        margin-top: 15px;
        width: 100%;
    }

    .teacher-group-view .card-body {
        padding: 15px;
    }

    .teacher-group-view .table {
        font-size: 14px;
    }

    .teacher-group-view .table thead th,
    .teacher-group-view .table tbody td {
        padding: 10px;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.teacher-group-view .card {
    animation: fadeInUp 0.6s ease;
}

.teacher-group-view .card:nth-child(2) {
    animation-delay: 0.1s;
}

.teacher-group-view .card:nth-child(3) {
    animation-delay: 0.2s;
}


</style>

<div class="teacher-group-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="color: #5831c6ff;"><i class="fas fa-users"></i> <?= Html::encode($group->name) ?></h1>
        <?= Html::a('<i class="fas fa-arrow-left"></i> Back', ['/teacher/dashboard'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <!-- Group Info -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Group Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Course:</strong> <?= Html::encode($group->course->name) ?></p>
                    <p><strong>Duration:</strong> <?= $group->course->duration ?> months</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total Students:</strong> <?= count($group->students) ?></p>
                    <p><strong>Teacher:</strong> <?= Html::encode($teacher->full_name) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-user-graduate"></i> Students in This Group</h5>
        </div>
        <div class="card-body">
            <?php if (empty($group->students)): ?>
                <p class="text-muted">No students enrolled yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Enrolled Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($group->students as $i => $student): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= Html::encode($student->full_name) ?></strong></td>
                                <td><?= Html::encode($student->email) ?></td>
                                <td><?= Html::encode($student->phone) ?></td>
                                <td><?= Yii::$app->formatter->asDate($student->enrolled_date) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


