<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Mening dars jadvalim');

// Voqealarni tayyorlash
$events = [];
$colors = ['#4361ee', '#f72585', '#4cc9f0', '#7209b7', '#3a0ca3']; // Neon ranglar
$colorIndex = 0;

foreach ($schedules as $schedule) {
    $courseColor = $colors[$colorIndex % count($colors)];
    $colorIndex++;
    
    $startDate = new DateTime();
    $endDate = (new DateTime())->modify('+3 months');
    
    while ($startDate <= $endDate) {
        if ($startDate->format('N') == $schedule->day_of_week) {
            $events[] = [
                'title' => $schedule->course->name,
                'start' => $startDate->format('Y-m-d') . 'T' . $schedule->start_time,
                'end' => $startDate->format('Y-m-d') . 'T' . $schedule->end_time,
                'backgroundColor' => $courseColor, 
                'borderColor' => 'transparent', 
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'courseId' => $schedule->course_id,
                    'room' => $schedule->room ?? 'Belgilanmagan', 
                ],
            ];
        }
        $startDate->modify('+1 day');
    }
}
?>

<style>
    /* ... OLDINGI BARCHA CSS STYLELAR O'ZGARISHSZ QOLADI ... */
    .calendar-page { padding: 40px 0; font-family: 'Nunito', sans-serif; }
    .page-header { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 30px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .page-title h1 { font-weight: 800; color: white; margin: 0; font-size: 2rem; text-shadow: 0 0 15px rgba(67, 97, 238, 0.6); }
    .legend-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 20px; margin-bottom: 30px; }
    .legend-item { background: rgba(255,255,255,0.05); border-radius: 10px; padding: 10px 15px; display: flex; align-items: center; transition: 0.3s; border: 1px solid transparent; }
    .legend-item:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); }
    .color-dot { width: 15px; height: 15px; border-radius: 50%; margin-right: 12px; box-shadow: 0 0 10px currentColor; }
    .calendar-container { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
    .fc-header-toolbar { margin-bottom: 20px !important; }
    .fc-toolbar-title { color: white; font-weight: 800; font-size: 1.5rem; }
    .fc-button { background: rgba(255,255,255,0.1) !important; border: 1px solid rgba(255,255,255,0.2) !important; color: white !important; font-weight: 600 !important; text-transform: uppercase; font-size: 0.8rem !important; }
    .fc-button:hover, .fc-button-active { background: #4361ee !important; border-color: #4361ee !important; box-shadow: 0 0 15px rgba(67, 97, 238, 0.5); }
    .fc-theme-standard td, .fc-theme-standard th { border-color: rgba(255, 255, 255, 0.1) !important; }
    .fc-col-header-cell-cushion { color: #4cc9f0; text-decoration: none !important; padding: 10px 0 !important; }
    .fc-timegrid-slot-label-cushion { color: rgba(255,255,255,0.6) !important; }
    .fc-event { border-radius: 8px !important; border: none !important; box-shadow: 0 4px 10px rgba(0,0,0,0.3); transition: 0.2s; }
    .fc-event:hover { transform: scale(1.02); z-index: 5; }
    .fc-event-main { padding: 4px 8px; color: white !important; font-weight: 600; font-size: 0.85rem; }
    .fc-event-time { font-size: 0.75rem; opacity: 0.8; display: block; margin-bottom: 2px; }
    .fc-day-today { background: rgba(67, 97, 238, 0.05) !important; }
    .btn-glass-back { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 12px; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-glass-back:hover { background: white; color: black; }
</style>

<div class="calendar-page">
    <div class="container">
        
        <div class="page-header animate__animated animate__fadeInDown">
            <div class="page-title">
                <h1><i class="fas fa-calendar-alt text-warning me-2"></i> <?= Yii::t('app', 'Dars jadvali taqvimi') ?></h1>
            </div>
            <div>
                <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Asosiy panelga qaytish'), ['dashboard'], ['class' => 'btn-glass-back']) ?>
            </div>
        </div>

        <div class="legend-card animate__animated animate__fadeInUp">
            <h6 class="text-white mb-3 text-uppercase small fw-bold" style="opacity: 0.7; letter-spacing: 1px;">
                <i class="fas fa-layer-group me-2"></i> <?= Yii::t('app', 'Sizning kurslaringiz') ?>
            </h6>
            <div class="row">
                <?php 
                $colorIndex = 0;
                $courseSchedules = [];
                foreach ($schedules as $schedule) {
                    $courseId = $schedule->course_id;
                    if (!isset($courseSchedules[$courseId])) {
                        $courseSchedules[$courseId] = [
                            'course' => $schedule->course,
                            'color' => $colors[$colorIndex % count($colors)],
                            'count' => 0,
                        ];
                        $colorIndex++;
                    }
                    $courseSchedules[$courseId]['count']++;
                }
                
                foreach ($courseSchedules as $data): ?>
                <div class="col-md-3 mb-3">
                    <div class="legend-item">
                        <div class="color-dot" style="background: <?= $data['color'] ?>; box-shadow: 0 0 10px <?= $data['color'] ?>;"></div>
                        <div>
                            <strong class="text-white d-block" style="line-height: 1.2;"><?= Html::encode($data['course']->name) ?></strong>
                            <small style="color: rgba(255,255,255,0.5); font-size: 0.75rem;"><?= $data['count'] ?> <?= Yii::t('app', 'dars/hafta') ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="calendar-container animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div id="calendar"></div>
        </div>

    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/uz.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales-all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var currentLang = '<?= substr(Yii::$app->language, 0, 2) ?>'; 
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: currentLang,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '22:00:00',
        allDaySlot: false,
        height: 'auto',
        events: <?= json_encode($events) ?>,
        
        eventClick: function(info) {
            var props = info.event.extendedProps;
            alert(
                '📌 <?= Yii::t('app', 'Course') ?>: ' + info.event.title + '\n' +
                '⏰ <?= Yii::t('app', 'Time') ?>: ' + info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + 
                ' - ' + info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) + '\n' +
                '🚪 <?= Yii::t('app', 'Room') ?>: ' + (props.room || 'Onlayn')
            );
        },

        eventContent: function(arg) {
            let time = arg.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: false});
            return {
                html: `
                    <div class="fc-event-main-frame">
                        <div class="fc-event-time"><i class="far fa-clock"></i> ${time}</div>
                        <div class="fc-event-title-container">
                            <div class="fc-event-title fc-sticky">${arg.event.title}</div>
                        </div>
                    </div>
                `
            };
        }
    });
    
    calendar.render();
});
</script>