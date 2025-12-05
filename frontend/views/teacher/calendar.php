<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Teaching Calendar';

// Prepare events for FullCalendar
$events = [];
$colors = ['#3788d8', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14'];
$colorIndex = 0;

foreach ($schedules as $schedule) {
    $groupColor = $colors[$colorIndex % count($colors)];
    $colorIndex++;
    
    // Generate recurring events for next 3 months
    $startDate = new DateTime();
    $endDate = (new DateTime())->modify('+3 months');
    
    while ($startDate <= $endDate) {
        if ($startDate->format('N') == $schedule->day_of_week) {
            $events[] = [
                'title' => $schedule->group->name . ' - ' . $schedule->group->course->name,
                'start' => $startDate->format('Y-m-d') . 'T' . $schedule->start_time,
                'end' => $startDate->format('Y-m-d') . 'T' . $schedule->end_time,
                'backgroundColor' => $groupColor,
                'borderColor' => $groupColor,
                'extendedProps' => [
                    'groupId' => $schedule->group_id,
                    'room' => $schedule->room,
                    'students' => count($schedule->group->students),
                ],
            ];
        }
        $startDate->modify('+1 day');
    }
}
?>

<div class="calendar-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-calendar"></i> My Teaching Calendar</h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back to Dashboard', ['dashboard'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <!-- Legend -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h6 class="mb-3"><i class="fas fa-info-circle"></i> My Groups</h6>
            <div class="row">
                <?php 
                $colorIndex = 0;
                $groupedSchedules = [];
                foreach ($schedules as $schedule) {
                    $groupId = $schedule->group_id;
                    if (!isset($groupedSchedules[$groupId])) {
                        $groupedSchedules[$groupId] = [
                            'group' => $schedule->group,
                            'color' => $colors[$colorIndex % count($colors)],
                            'count' => 0,
                        ];
                        $colorIndex++;
                    }
                    $groupedSchedules[$groupId]['count']++;
                }
                
                foreach ($groupedSchedules as $data): ?>
                <div class="col-md-3 mb-2">
                    <div class="d-flex align-items-center">
                        <div style="width: 20px; height: 20px; background: <?= $data['color'] ?>; border-radius: 3px;" class="me-2"></div>
                        <div>
                            <strong><?= Html::encode($data['group']->name) ?></strong>
                            <br><small class="text-muted"><?= $data['count'] ?> classes/week</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="card shadow">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
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
                'Group: ' + info.event.title + '\n' +
                'Time: ' + info.event.start.toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'}) + 
                ' - ' + info.event.end.toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'}) + '\n' +
                'Room: ' + (props.room || 'Not specified') + '\n' +
                'Students: ' + props.students
            );
        },
        eventContent: function(arg) {
            let time = arg.event.start.toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit', hour12: false});
            return {
                html: '<div class="fc-event-main-frame"><div class="fc-event-time">' + time + '</div><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">' + arg.event.title + '</div></div></div>'
            };
        }
    });
    
    calendar.render();
});
</script>

<style>
/* --- PAGE TITLE --- */
.calendar-page h1 {
    font-size: 2.2rem;
    font-weight: 700;
    background: linear-gradient(45deg, #0d6efd, #6610f2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: flex;
    align-items: center;
}

.calendar-page h1 i {
    font-size: 1.9rem;
    margin-right: 10px;
    background: inherit;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* --- LEGEND CARD STYLE --- */
.calendar-page .card {
    border-radius: 15px;
    overflow: hidden;
}

.calendar-page .card-body {
    padding: 25px;
}

.calendar-page .card h6 {
    font-weight: 700;
    margin-bottom: 15px;
}

/* --- CALENDAR WRAPPER --- */
#calendar {
    padding: 10px;
}

/* --- FULLCALENDAR STYLE TUNING --- */
.fc .fc-toolbar-title {
    font-size: 1.5rem;
    font-weight: 700;
}

.fc .fc-button {
    border-radius: 6px !important;
    font-weight: 500;
}

.fc .fc-col-header-cell-cushion {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Event card inside calendar */
.fc-event {
    border-radius: 8px !important;
    padding: 3px 6px !important;
    font-size: 0.85rem;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}

/* TimeGrid row height more compact */
.fc-timegrid-slot {
    height: 40px !important;
}

/* Scrollbar beautiful */
.fc-scroller {
    scrollbar-width: thin;
}

.fc-scroller::-webkit-scrollbar {
    width: 6px;
}

.fc-scroller::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 20px;
}

/* Hover effect on events */
.fc-event:hover {
    transform: scale(1.02);
    transition: 0.15s ease;
    cursor: pointer;
}

/* Legend small badges */
.calendar-page .d-flex .me-2 {
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
}

/* Responsive fix */
@media(max-width: 768px) {
    .calendar-page h1 {
        font-size: 1.7rem;
    }
}
</style>
