<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Attendance;

$this->title = 'Take Attendance - ' . $group->name;
?>

<div class="attendance-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-clipboard-check"></i> Take Attendance</h1>
            <p class="text-white mb-0">Group: <strong><?= Html::encode($group->name) ?></strong> | Course: <strong><?= Html::encode($group->course->name) ?></strong></p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-history"></i> View History', ['attendance-history', 'id' => $group->id], ['class' => 'btn btn-info']) ?>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back', ['group', 'id' => $group->id], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-calendar"></i> Select Date:</label>
                    <input type="date" id="attendance-date" class="form-control" value="<?= $date ?>" max="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary" onclick="changeDate()">
                        <i class="fas fa-sync"></i> Load Date
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Form -->
    <form id="attendance-form">
        <input type="hidden" name="group_id" value="<?= $group->id ?>">
        <input type="hidden" name="date" id="form-date" value="<?= $date ?>">

        <div class="card shadow">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Students (<?= count($group->students) ?>)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th width="400" class="text-center">Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($group->students)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No students enrolled</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($group->students as $i => $student): ?>
                                    <?php
                                    $attendance = $attendances[$student->id] ?? null;
                                    $currentStatus = $attendance ? $attendance->status : Attendance::STATUS_PRESENT;
                                    ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><strong><?= Html::encode($student->full_name) ?></strong></td>
                                        <td><?= Html::encode($student->email) ?></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check" name="attendance[<?= $student->id ?>]" id="present-<?= $student->id ?>" value="<?= Attendance::STATUS_PRESENT ?>" <?= $currentStatus == Attendance::STATUS_PRESENT ? 'checked' : '' ?>>
                                                <label class="btn btn-outline-success" for="present-<?= $student->id ?>">
                                                    <i class="fas fa-check"></i> Present
                                                </label>

                                                <input type="radio" class="btn-check" name="attendance[<?= $student->id ?>]" id="absent-<?= $student->id ?>" value="<?= Attendance::STATUS_ABSENT ?>" <?= $currentStatus == Attendance::STATUS_ABSENT ? 'checked' : '' ?>>
                                                <label class="btn btn-outline-danger" for="absent-<?= $student->id ?>">
                                                    <i class="fas fa-times"></i> Absent
                                                </label>

                                                <input type="radio" class="btn-check" name="attendance[<?= $student->id ?>]" id="late-<?= $student->id ?>" value="<?= Attendance::STATUS_LATE ?>" <?= $currentStatus == Attendance::STATUS_LATE ? 'checked' : '' ?>>
                                                <label class="btn btn-outline-warning" for="late-<?= $student->id ?>">
                                                    <i class="fas fa-clock"></i> Late
                                                </label>

                                                <input type="radio" class="btn-check" name="attendance[<?= $student->id ?>]" id="excused-<?= $student->id ?>" value="<?= Attendance::STATUS_EXCUSED ?>" <?= $currentStatus == Attendance::STATUS_EXCUSED ? 'checked' : '' ?>>
                                                <label class="btn btn-outline-info" for="excused-<?= $student->id ?>">
                                                    <i class="fas fa-file-medical"></i> Excused
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (!empty($group->students)): ?>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn btn-outline-success" onclick="markAll('present')">
                                <i class="fas fa-check-double"></i> Mark All Present
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="markAll('absent')">
                                <i class="fas fa-times-circle"></i> Mark All Absent
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Attendance
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<script>
    function changeDate() {
        const date = document.getElementById('attendance-date').value;
        window.location.href = '<?= \yii\helpers\Url::to(['teacher/attendance', 'id' => $group->id]) ?>&date=' + date;
    }

    function markAll(status) {
        document.querySelectorAll('input[type="radio"][value="' + status + '"]').forEach(radio => {
            radio.checked = true;
        });
    }

    document.getElementById('attendance-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {
            group_id: formData.get('group_id'),
            date: formData.get('date'),
            attendance: {}
        };

        // Collect attendance data
        formData.forEach((value, key) => {
            if (key.startsWith('attendance[')) {
                const studentId = key.match(/\d+/)[0];
                data.attendance[studentId] = value;
            }
        });

        // Send AJAX request
        fetch('<?= \yii\helpers\Url::to(['teacher/save-attendance']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= Yii::$app->request->csrfToken ?>'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('✅ ' + result.message);
                    location.reload();
                } else {
                    alert('❌ Error: ' + result.message);
                }
            })
            .catch(error => {
                alert('❌ Network error: ' + error);
                console.error(error);
            });
    });
</script>


<style>
    .attendance-page h1 {
        font-weight: 700;
        font-size: 32px;
        color: #6c2ba0ff;
    }

    .attendance-page .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .attendance-page .card-header {
        background: linear-gradient(45deg, #0d6efd, #6610f2);
        padding: 18px 25px;
        font-size: 18px;
    }

    .attendance-page .card-body {
        background: #fafbfc;
    }

    /* Date selector styling */
    #attendance-date {
        height: 48px;
        border-radius: 10px;
        font-size: 16px;
    }

    .attendance-page .btn-primary {
        border-radius: 10px;
        padding: 10px 20px;
    }

    /* Table styling */
    .attendance-page table {
        font-size: 15px;
    }

    .attendance-page thead tr {
        background: #f0f4f8;
    }

    .attendance-page thead th {
        font-weight: 600;
        color: #34495e;
    }

    .attendance-page tbody td {
        vertical-align: middle;
        padding: 14px 12px;
    }

    /* Hover row */
    .attendance-page tbody tr:hover {
        background: #eef6ff;
        transition: 0.2s;
    }

    /* Radio Button Custom Buttons */
    .btn-outline-success,
    .btn-outline-danger,
    .btn-outline-warning,
    .btn-outline-info {
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 600;
    }

    .btn-outline-success:hover {
        background: #28a745;
        color: #fff;
    }

    .btn-outline-danger:hover {
        background: #dc3545;
        color: #fff;
    }

    .btn-outline-warning:hover {
        background: #ffc107;
        color: #000;
    }

    .btn-outline-info:hover {
        background: #0dcaf0;
        color: #fff;
    }

    /* Active selected button */
    .btn-check:checked+.btn-outline-success {
        background: #28a745;
        color: white;
    }

    .btn-check:checked+.btn-outline-danger {
        background: #dc3545;
        color: white;
    }

    .btn-check:checked+.btn-outline-warning {
        background: #ffc107;
        color: black;
    }

    .btn-check:checked+.btn-outline-info {
        background: #0dcaf0;
        color: white;
    }

    /* Card footer buttons */
    .attendance-page .card-footer {
        padding: 20px;
        border-top: 1px solid #e9ecef;
    }

    .attendance-page .btn-outline-success,
    .attendance-page .btn-outline-danger {
        border-radius: 10px;
        padding: 10px 20px;
    }

    /* Save button */
    .attendance-page .btn-primary.btn-lg {
        padding: 12px 25px;
        font-size: 17px;
        border-radius: 12px;
        background: linear-gradient(45deg, #007bff, #4da3ff);
        border: none;
    }

    .attendance-page .btn-primary.btn-lg:hover {
        background: linear-gradient(45deg, #0069d9, #3786e6);
    }

    /* Top buttons */
    .attendance-page .btn-info,
    .attendance-page .btn-secondary {
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
    }

    .attendance-page .btn-info {
        background: #17a2b8;
    }

    .attendance-page .btn-info:hover {
        background: #138496;
    }

    .attendance-page .btn-secondary:hover {
        background: #5a6268;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .attendance-page h1 {
            font-size: 26px;
        }

        .attendance-page table {
            font-size: 13.5px;
        }

        .attendance-page .btn-group label {
            margin-bottom: 6px;
        }
    }
</style>