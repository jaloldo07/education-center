<?php
use yii\helpers\Html;

$this->title = 'Our Teachers';
?>

<div class="teachers-page">
    <h1 class="mb-4" style="color: rgb(70 63 196);"><i class="fas fa-chalkboard-teacher"></i> Meet Our Expert Teachers</h1>
    
    <div class="row">
        <?php foreach ($teachers as $teacher): ?>
<div class="col-md-3 mb-4">
    <?= Html::a(
        '<div class="card text-center h-100 shadow teacher-card" style="cursor: pointer;">
            <div class="card-body" style="background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);">
                <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; line-height: 80px; border-radius: 50%; font-size: 2rem;">' . 
                    strtoupper(substr($teacher->full_name, 0, 1)) . 
                '</div>
                <h5 class="card-title">' . Html::encode($teacher->full_name) . '</h5>
                <p class="text-muted small">' . Html::encode($teacher->subject) . '</p>
                <p class="mb-2">' . 
                    str_repeat('<i class="fas fa-star text-warning"></i>', floor($teacher->rating)) . 
                    '<small class="text-muted ms-1">' . $teacher->rating . '</small>
                </p>
                <small class="text-muted">' . $teacher->experience_years . ' years experience</small>
            </div>
        </div>',
        ['teacher-detail', 'id' => $teacher->id],
        ['style' => 'text-decoration: none; color: inherit;']
    ) ?>
</div>
<?php endforeach; ?>
    </div>
</div>