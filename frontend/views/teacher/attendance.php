<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Attendance;

$this->title = Yii::t('app', 'Take Attendance') . ' - ' . $group->name;
?>

<style>
    /* 1. Page Container */
    .attendance-page {
        padding-top: 40px;
        padding-bottom: 80px;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Header Section */
    .page-header {
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

    .page-title h1 {
        font-weight: 800;
        color: white;
        margin: 0;
        font-size: 2rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.6);
    }

    .group-info {
        color: rgba(255, 255, 255, 0.7);
        margin-top: 5px;
    }

    .group-info strong {
        color: var(--accent-color);
    }

    /* 3. Date Selection Card */
    .date-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .form-glass-control {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 12px;
        padding: 10px 15px;
    }

    .form-glass-control:focus {
        background: rgba(0, 0, 0, 0.5);
        border-color: #4361ee;
        color: white;
        box-shadow: 0 0 10px rgba(67, 97, 238, 0.3);
    }

    /* 4. Student List Card */
    .student-list-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
    }

    .list-header {
        background: rgba(67, 97, 238, 0.2);
        padding: 20px 25px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
    }

    .table-glass {
        width: 100%;
        color: white;
        margin: 0;
    }

    .table-glass th {
        background: rgba(0, 0, 0, 0.3);
        padding: 15px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table-glass td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .table-glass tr:hover td {
        background: rgba(255, 255, 255, 0.05);
    }

    /* 5. Custom Radio Buttons (Neon) */
    .btn-check:checked+.btn-neon-success {
        background: #4ade80 !important;
        color: #064e3b !important;
        box-shadow: 0 0 15px rgba(74, 222, 128, 0.6);
        border-color: #4ade80 !important;
    }

    .btn-neon-success {
        color: #4ade80;
        border: 1px solid #4ade80;
        background: transparent;
    }

    .btn-check:checked+.btn-neon-danger {
        background: #f87171 !important;
        color: #7f1d1d !important;
        box-shadow: 0 0 15px rgba(248, 113, 113, 0.6);
        border-color: #f87171 !important;
    }

    .btn-neon-danger {
        color: #f87171;
        border: 1px solid #f87171;
        background: transparent;
    }

    .btn-check:checked+.btn-neon-warning {
        background: #fbbf24 !important;
        color: #78350f !important;
        box-shadow: 0 0 15px rgba(251, 191, 36, 0.6);
        border-color: #fbbf24 !important;
    }

    .btn-neon-warning {
        color: #fbbf24;
        border: 1px solid #fbbf24;
        background: transparent;
    }

    .btn-check:checked+.btn-neon-info {
        background: #38bdf8 !important;
        color: #0c4a6e !important;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.6);
        border-color: #38bdf8 !important;
    }

    .btn-neon-info {
        color: #38bdf8;
        border: 1px solid #38bdf8;
        background: transparent;
    }

    .btn-group label {
        margin: 0 2px;
        border-radius: 8px !important;
        transition: 0.3s;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* 6. Footer Actions */
    .card-footer-glass {
        background: rgba(0, 0, 0, 0.2);
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-glass-action {
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.05);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        transition: 0.3s;
    }

    .btn-glass-action:hover {
        background: white;
        color: black;
    }

    .btn-save-neon {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        box-shadow: 0 0 20px rgba(67, 97, 238, 0.4);
        transition: 0.3s;
    }

    .btn-save-neon:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 30px rgba(67, 97, 238, 0.6);
    }
</style>

<div class="attendance-page">
    <div class="container">

        <div class="page-header animate__animated animate__fadeInDown">
            <div class="page-title">
                <h1><i class="fas fa-clipboard-check text-success me-2"></i> <?= Yii::t('app', 'Take Attendance') ?></h1>
                <p class="group-info">
                    <?= Yii::t('app', 'Group') ?>: <strong><?= Html::encode($group->name) ?></strong> |
                    <?= Yii::t('app', 'Course') ?>: <strong><?= Html::encode($group->course->name) ?></strong>
                </p>
            </div>
            <div class="d-flex gap-2">
                <?= Html::a('<i class="fas fa-history"></i> ' . Yii::t('app', 'History'), ['attendance-history', 'id' => $group->id], [
                    'class' => 'btn-glass-action',
                    'style' => 'text-decoration: none;' /* <-- Mana shu qator qo'shildi */
                ]) ?>

                <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back'), ['/teacher/dashboard'], [
                    'class' => 'btn-glass-action',
                    'style' => 'text-decoration: none;' /* <-- Dashboardga yo'naltirildi */
                ]) ?>
            </div>
        </div>

        <div class="date-card animate__animated animate__fadeInUp">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="text-white-50 mb-2"><i class="far fa-calendar-alt me-2"></i> <?= Yii::t('app', 'Select Date') ?>:</label>
                    <input type="date" id="attendance-date" class="form-control form-glass-control" value="<?= $date ?>" max="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 text-end mt-3 mt-md-0">
                    <button class="btn btn-outline-light rounded-pill px-4" onclick="changeDate()">
                        <i class="fas fa-sync-alt me-2"></i> <?= Yii::t('app', 'Load Date') ?>
                    </button>
                </div>
            </div>
        </div>

        <form id="attendance-form" class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <input type="hidden" name="group_id" value="<?= $group->id ?>">
            <input type="hidden" name="date" id="form-date" value="<?= $date ?>">

            <div class="student-list-card">
                <div class="list-header">
                    <h5 class="m-0"><i class="fas fa-users me-2"></i> <?= Yii::t('app', 'Students List') ?> (<?= count($group->students) ?>)</h5>
                </div>

                <div class="table-responsive">
                    <table class="table-glass">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th><?= Yii::t('app', 'Student Name') ?></th>
                                <th><?= Yii::t('app', 'Email') ?></th>
                                <th class="text-center" width="450"><?= Yii::t('app', 'Status') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($group->students)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-white-50">
                                        <i class="fas fa-user-slash fa-2x mb-3"></i><br>
                                        <?= Yii::t('app', 'No students enrolled in this group yet.') ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($group->students as $i => $student): ?>
                                    <?php
                                    $attendance = $attendances[$student->id] ?? null;
                                    $currentStatus = $attendance ? $attendance->status : Attendance::STATUS_PRESENT;
                                    ?>
                                    <tr>
                                        <td class="text-white-50"><?= $i + 1 ?></td>
                                        <td><strong class="text-white"><?= Html::encode($student->full_name) ?></strong></td>
                                        <td class="text-white-50"><?= Html::encode($student->email) ?></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">

                                                <input type="radio" class="btn-check" name="attendance[<?= $student->id ?>]" id="present-<?= $student->id ?>" value="<?= Attendance::STATUS_PRESENT ?>" <?= $currentStatus == Attendance::STATUS_PRESENT ? 'checked' : '' ?>>
                                                <label class="btn btn-neon-success" for="present-<?= $student->id ?>" title="Present">
                                                    <i class="fas fa-check"></i>
                                                </label>

                                                <input type="radio" class="btn-check" name="attendance[<?= $student->id ?>]" id="absent-<?= $student->id ?>" value="<?= Attendance::STATUS_ABSENT ?>" <?= $currentStatus == Attendance::STATUS_ABSENT ? 'checked' : '' ?>>
                                                <label class="btn btn-neon-danger" for="absent-<?= $student->id ?>" title="Absent">
                                                    <i class="fas fa-times"></i>
                                                </label>

                                                <input type="radio" class="btn-check" name="attendance[<?= $student->id ?>]" id="late-<?= $student->id ?>" value="<?= Attendance::STATUS_LATE ?>" <?= $currentStatus == Attendance::STATUS_LATE ? 'checked' : '' ?>>
                                                <label class="btn btn-neon-warning" for="late-<?= $student->id ?>" title="Late">
                                                    <i class="fas fa-clock"></i>
                                                </label>

                                                <input type="radio" class="btn-check" name="attendance[<?= $student->id ?>]" id="excused-<?= $student->id ?>" value="<?= Attendance::STATUS_EXCUSED ?>" <?= $currentStatus == Attendance::STATUS_EXCUSED ? 'checked' : '' ?>>
                                                <label class="btn btn-neon-info" for="excused-<?= $student->id ?>" title="Excused">
                                                    <i class="fas fa-file-medical"></i>
                                                </label>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($group->students)): ?>
                    <div class="card-footer-glass">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill" onclick="markAll('present')">
                                    <i class="fas fa-check-double me-1"></i> All Present
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="markAll('absent')">
                                    <i class="fas fa-times-circle me-1"></i> All Absent
                                </button>
                            </div>
                            <button type="submit" class="btn-save-neon">
                                <i class="fas fa-save me-2"></i> <?= Yii::t('app', 'Save Attendance') ?>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </form>

    </div>
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

        formData.forEach((value, key) => {
            if (key.startsWith('attendance[')) {
                const studentId = key.match(/\d+/)[0];
                data.attendance[studentId] = value;
            }
        });

        // Button loading state
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        btn.disabled = true;

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
                    // Custom notification instead of alert
                    alert('✅ ' + result.message);
                    location.reload();
                } else {
                    alert('❌ ' + result.message);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(error => {
                alert('❌ Error occurred');
                console.error(error);
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
    });
</script>