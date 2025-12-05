<?php

use yii\helpers\Html;

$this->title = 'All Courses';
?>

<div class="courses-page">
    <h1 class="mb-4" style="color: rgb(70 63 196);"><i class="fas fa-book"></i> Available Courses</h1>

    <div class="row">
        <?php foreach ($courses as $course): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow stat-card" style="background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);">
                    <div class="card-body">
                        <h5 class="card-title text-primary">
                            <i class="fas fa-book-open"></i>
                            <?= Html::encode($course->name) ?>
                        </h5>
                        
                        <p class="card-text"><?= Html::encode($course->description) ?></p>
                        <hr>
                        <p class="mb-1"><strong>Teacher:</strong> <?= Html::encode($course->teacher->full_name) ?></p>
                        <p class="mb-1"><strong>Duration:</strong> <?= $course->duration ?> months</p>
                        <p class="mb-1"><strong>Price:</strong> <span class="text-success"><?= number_format($course->price, 0) ?> UZS/month</span></p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <?= Html::a(
                            '<i class="fas fa-eye"></i> View Details',
                            ['course-detail', 'id' => $course->id],
                            ['class' => 'btn btn-primary w-100']
                        ) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>