<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use frontend\assets\AppAsset;

AppAsset::register($this);
$this->beginPage()
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Education Center</title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --success-color: #4ade80;
            --danger-color: #f87171;
            --warning-color: #fbbf24;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --gray-color: #64748b;
            --navbar-bg: rgba(67, 97, 238, 0.85);
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: transparent;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        #canvas-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%; z-index: -1;
            background: radial-gradient(circle at center, #1a1f35 0%, #0b0e14 100%);
        }

        .navbar {
            background: var(--navbar-bg) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand { font-weight: 700; font-size: 1.3rem; letter-spacing: -0.5px; color: white !important; }
        .navbar-brand i { margin-right: 8px; animation: pulse 2s infinite; }
        .nav-link { color: rgba(255, 255, 255, 0.9) !important; transition: all 0.3s; }
        .nav-link:hover { color: var(--accent-color) !important; transform: translateY(-2px); }

        .search-container { position: relative; width: 300px; margin: 0 15px; }
        .search-input {
            width: 100%; padding: 10px 20px; border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px; font-size: 14px; transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); color: white;
        }
        .search-input::placeholder { color: rgba(255, 255, 255, 0.6); }
        .search-input:focus {
            outline: none; background: rgba(255, 255, 255, 0.25); border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.3); transform: translateY(-2px); color: white;
        }

        #search-results-frontend {
            position: absolute; top: 100%; left: 0; right: 0; background: rgba(255, 255, 255, 0.95);
            border-radius: 16px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); margin-top: 12px;
            display: none; z-index: 9999; max-height: 400px; overflow-y: auto;
            backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .search-item-frontend {
            padding: 16px 20px; text-decoration: none; color: var(--dark-color);
            border-bottom: 1px solid #f1f5f9; transition: all 0.3s ease; display: flex; align-items: center; gap: 12px;
        }
        .search-item-frontend:hover { background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); color: white; }
        .search-item-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; background: rgba(0, 0, 0, 0.05); }

        .main-content { min-height: calc(100vh - 200px); padding: 40px 0; }

        footer {
            background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(10px); color: white;
            padding: 60px 0 30px; margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        .footer-section h5 { font-weight: 600; margin-bottom: 20px; position: relative; color: var(--accent-color); }
        .footer-section h5::after { content: ''; position: absolute; bottom: -8px; left: 0; width: 50px; height: 3px; background: var(--primary-color); border-radius: 2px; }
        .footer-social a { display: inline-block; width: 45px; height: 45px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; text-align: center; line-height: 45px; color: white; transition: all 0.3s ease; margin-right: 10px; }
        .footer-social a:hover { background: var(--accent-color); transform: translateY(-3px) rotate(360deg); }
        .footer-divider { border: none; height: 1px; background: rgba(255, 255, 255, 0.1); margin: 30px 0; }

        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
        .fade-in { animation: fadeInUp 0.8s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: var(--primary-color); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent-color); }

        .glass-toast {
            position: fixed; bottom: 30px; right: 30px; padding: 15px 25px; border-radius: 12px; color: white;
            font-family: 'Nunito', sans-serif; font-weight: 600; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 99999;
            transform: translateY(100px); opacity: 0; transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex; align-items: center; gap: 10px;
        }
        .glass-toast.show { transform: translateY(0); opacity: 1; }
        .glass-toast.success { background: rgba(16, 185, 129, 0.85); border: 1px solid rgba(16, 185, 129, 0.3); }
        .glass-toast.error { background: rgba(239, 68, 68, 0.85); border: 1px solid rgba(239, 68, 68, 0.3); }
    </style>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <canvas id="canvas-bg"></canvas>

    <?php
    NavBar::begin([
        'brandLabel' => '<i class="fas fa-graduation-cap"></i> ' . Yii::t('app', 'Education Center'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-lg navbar-dark mb-0 sticky-top'],
        'innerContainerOptions' => ['class' => 'container-fluid px-4']
    ]);

    $menuItems = [
        ['label' => '<i class="fas fa-home"></i> ' . Yii::t('app', 'Home'), 'url' => ['/site/index']],
        ['label' => '<i class="fas fa-book"></i> ' . Yii::t('app', 'Courses'), 'url' => ['/site/courses']],
        ['label' => '<i class="fas fa-chalkboard-teacher"></i> ' . Yii::t('app', 'Teachers'), 'url' => ['/site/teachers']],
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '<i class="fas fa-sign-in-alt"></i> ' . Yii::t('app', 'Login'), 'url' => ['/site/login']];
        $menuItems[] = ['label' => '<i class="fas fa-user-plus"></i> ' . Yii::t('app', 'Register'), 'url' => ['/site/signup']];
    } else {
        $role = Yii::$app->user->identity->role;

        if ($role === 'student') {
            $menuItems[] = ['label' => '<i class="fas fa-tachometer-alt"></i> ' . Yii::t('app', 'My Dashboard'), 'url' => ['/student/dashboard']];
            $menuItems[] = ['label' => '<i class="fas fa-book-open"></i> ' . Yii::t('app', 'My Lessons'), 'url' => ['/student-lesson/index']];
        } elseif ($role === 'teacher') {
            $menuItems[] = ['label' => '<i class="fas fa-book-open"></i> ' . Yii::t('app', 'Lessons'), 'url' => ['/lesson/index']];
            $menuItems[] = ['label' => '<i class="fas fa-chalkboard"></i> ' . Yii::t('app', 'My Dashboard'), 'url' => ['/teacher/dashboard']];
        } elseif ($role === 'admin') {
            $menuItems[] = ['label' => '<i class="fas fa-user-shield"></i> ' . Yii::t('app', 'Admin Panel'), 'url' => ['/admin/dashboard/index']];
        }

        $menuItems[] = '<li class="nav-item ms-3">'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
            . Html::submitButton(
                '<i class="fas fa-sign-out-alt"></i> ' . Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-outline-light btn-sm hover-lift', 'style' => 'border-radius: 20px; padding: 5px 15px; margin-top: 5px;']
            )
            . Html::endForm()
            . '</li>';
    }

    $currentLang = Yii::$app->language;
    $langLabel = strtoupper(substr($currentLang, 0, 2));

    $menuItems[] = [
        'label' => '<i class="fas fa-globe"></i> ' . $langLabel,
        'items' => [
            ['label' => '🇺🇿 O\'zbek', 'url' => \yii\helpers\Url::current(['lang' => 'uz-UZ'])],
            ['label' => '🇬🇧 English', 'url' => \yii\helpers\Url::current(['lang' => 'en-US'])],
            ['label' => '🇷🇺 Русский', 'url' => \yii\helpers\Url::current(['lang' => 'ru-RU'])],
        ],
        'options' => ['class' => 'nav-item dropdown'],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);

    echo '<div class="search-container">';
    echo '<input class="search-input" id="frontend-search" type="text" placeholder="' . Yii::t('app', '🔍 Search courses...') . '">';
    echo '<div id="search-results-frontend" class="mt-2"></div>';
    echo '</div>';

    NavBar::end();
    ?>

    <main role="main" class="flex-shrink-0 main-content">
        <div class="container mt-4">
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border: none; background: rgba(220, 252, 231, 0.9);">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= Yii::$app->session->getFlash('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border: none; background: rgba(254, 226, 226, 0.9);">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= Yii::$app->session->getFlash('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="fade-in">
                <?= $content ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-section">
                    <h5><i class="fas fa-graduation-cap me-2"></i><?= Yii::t('app', 'Education Center') ?></h5>
                    <p style="opacity: 0.8;"><?= Yii::t('app', 'Empowering students through quality education since 2020. We believe in excellence and innovation in learning.') ?></p>
                </div>
                <div class="col-md-4 footer-section">
                    <h5><?= Yii::t('app', 'Contact Us') ?></h5>
                    <div class="footer-contact" style="opacity: 0.8;">
                        <p><i class="fas fa-phone text-info"></i> +998 90 123 45 67</p>
                        <p><i class="fas fa-envelope text-info"></i> info@education-center.uz</p>
                        <p><i class="fas fa-map-marker-alt text-info"></i> <?= Yii::t('app', 'Tashkent, Uzbekistan') ?></p>
                    </div>
                </div>
                <div class="col-md-4 footer-section">
                    <h5><?= Yii::t('app', 'Follow Us') ?></h5>
                    <div class="footer-social">
                        <a href="#" class="hover-lift"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="hover-lift"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover-lift"><i class="fab fa-telegram"></i></a>
                        <a href="#" class="hover-lift"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="text-center">
                <p class="mb-0" style="opacity: 0.7;">&copy; <?= date('Y') ?> <?= Yii::t('app', 'Education Center') ?>. <?= Yii::t('app', 'All rights reserved.') ?></p>
            </div>
        </div>
    </footer>

    <?php
    $searchUrl = \yii\helpers\Url::to(['/site/search']);
    $noResultsText = Yii::t('app', 'No results found');

    $this->registerJs("
    const canvas = document.getElementById('canvas-bg');
    const ctx = canvas.getContext('2d');
    let particlesArray;
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    let mouse = { x: null, y: null, radius: (canvas.height/80) * (canvas.width/80) }
    window.addEventListener('mousemove', function(event) { mouse.x = event.x; mouse.y = event.y; });
    class Particle {
        constructor(x, y, directionX, directionY, size, color) { this.x = x; this.y = y; this.directionX = directionX; this.directionY = directionY; this.size = size; this.color = color; }
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false); ctx.fillStyle = 'rgba(255, 255, 255, 0.7)'; ctx.fill(); }
        update() {
            if (this.x > canvas.width || this.x < 0) this.directionX = -this.directionX;
            if (this.y > canvas.height || this.y < 0) this.directionY = -this.directionY;
            let dx = mouse.x - this.x; let dy = mouse.y - this.y; let distance = Math.sqrt(dx*dx + dy*dy);
            if(distance < mouse.radius + this.size){
                if(mouse.x < this.x && this.x < canvas.width - this.size * 10) this.x += 10;
                if(mouse.x > this.x && this.x > this.size * 10) this.x -= 10;
                if(mouse.y < this.y && this.y < canvas.height - this.size * 10) this.y += 10;
                if(mouse.y > this.y && this.y > this.size * 10) this.y -= 10;
            }
            this.x += this.directionX; this.y += this.directionY; this.draw();
        }
    }
    function init() {
        particlesArray = []; let numberOfParticles = (canvas.height * canvas.width) / 9000;
        for (let i = 0; i < numberOfParticles; i++) {
            let size = (Math.random() * 2) + 1; let x = (Math.random() * ((innerWidth - size * 2) - (size * 2)) + size * 2); let y = (Math.random() * ((innerHeight - size * 2) - (size * 2)) + size * 2);
            let directionX = (Math.random() * 0.5) - 0.25; let directionY = (Math.random() * 0.5) - 0.25;
            particlesArray.push(new Particle(x, y, directionX, directionY, size, '#fff'));
        }
    }
    function connect() {
        let opacityValue = 1;
        for (let a = 0; a < particlesArray.length; a++) {
            for (let b = a; b < particlesArray.length; b++) {
                let distance = ((particlesArray[a].x - particlesArray[b].x) * (particlesArray[a].x - particlesArray[b].x)) + ((particlesArray[a].y - particlesArray[b].y) * (particlesArray[a].y - particlesArray[b].y));
                if (distance < (canvas.width/7) * (canvas.height/7)) {
                    opacityValue = 1 - (distance / 20000); ctx.strokeStyle = 'rgba(76, 201, 240,' + opacityValue + ')'; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(particlesArray[a].x, particlesArray[a].y); ctx.lineTo(particlesArray[b].x, particlesArray[b].y); ctx.stroke();
                }
            }
        }
    }
    function animate() { requestAnimationFrame(animate); ctx.clearRect(0, 0, innerWidth, innerHeight); for (let i = 0; i < particlesArray.length; i++) { particlesArray[i].update(); } connect(); }
    window.addEventListener('resize', function() { canvas.width = innerWidth; canvas.height = innerHeight; mouse.radius = ((canvas.height/80) * (canvas.height/80)); init(); });
    window.addEventListener('mouseout', function() { mouse.x = undefined; mouse.y = undefined; });
    init(); animate();

    let searchTimeout;
    jQuery('#frontend-search').on('input', function(e) {
        e.preventDefault(); clearTimeout(searchTimeout); let query = jQuery(this).val().trim();
        if(query.length < 2) { jQuery('#search-results-frontend').fadeOut(); return; }
        searchTimeout = setTimeout(function() {
            jQuery.ajax({
                url: '$searchUrl', method: 'GET', data: {q: query}, dataType: 'json',
                success: function(data) {
                    if(!data.results || data.results.length === 0) { jQuery('#search-results-frontend').html('<div class=\"search-item-frontend text-center text-muted py-4\"><i class=\"fas fa-search me-2\"></i>$noResultsText</div>').fadeIn(); return; }
                    let html = '';
                    jQuery.each(data.results, function(i, item) {
                        html += '<a href=\"' + item.url + '\" class=\"search-item-frontend hover-lift\"> <div class=\"search-item-icon bg-gradient-' + item.color + '\"> <i class=\"fas ' + item.icon + '\"></i> </div> <div class=\"search-item-content\"> <h4>' + item.title + '</h4> <p>' + item.subtitle + '</p> </div> </a>';
                    });
                    jQuery('#search-results-frontend').html(html).fadeIn();
                }
            });
        }, 300);
    });
    jQuery('#frontend-search').closest('form').on('submit', function(e) { e.preventDefault(); return false; });
    jQuery(document).click(function(e) { if(!jQuery(e.target).closest('.search-container').length) { jQuery('#search-results-frontend').fadeOut(); } });
    jQuery(document).on('click', '.search-item-frontend', function() { jQuery('#search-results-frontend').fadeOut(); jQuery('#frontend-search').val(''); });
    jQuery(document).ready(function() { jQuery('[data-bs-toggle=\"tooltip\"]').tooltip(); });
    ", \yii\web\View::POS_END);
    ?>

    <div id="chat-widget">
        <div id="chat-button" style="position:fixed;bottom:20px;right:20px;width:60px;height:60px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;color:white;font-size:24px;z-index:9998;box-shadow:0 4px 20px rgba(102,126,234,0.4);">
            💬
            <span id="total-unread" style="position:absolute;top:-5px;right:-5px;background:#ff4757;color:white;width:20px;height:20px;border-radius:50%;font-size:11px;display:none;align-items:center;justify-content:center;">0</span>
        </div>

        <div id="chat-popup" style="display:none;position:fixed;bottom:90px;right:20px;width:380px;height:550px;background:white;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.15);z-index:9999;overflow:hidden;">
            <div style="background:linear-gradient(135deg,#667eea,#764ba2);padding:15px;display:flex;justify-content:space-between;align-items:center;">
                <div style="display:flex;gap:8px;">
                    <button class="tab-btn active" data-tab="messages" style="background:white;color:#667eea;border:none;padding:8px 16px;border-radius:8px;cursor:pointer;font-size:14px;font-weight:600;">
                        <?= Yii::t('app', 'Messages') ?>
                    </button>
                    <button class="tab-btn" data-tab="support" style="background:rgba(255,255,255,0.2);color:white;border:none;padding:8px 16px;border-radius:8px;cursor:pointer;font-size:14px;">
                        <?= Yii::t('app', 'Support') ?>
                    </button>
                </div>
                <button id="close-chat" style="background:rgba(255,255,255,0.2);border:none;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:20px;">×</button>
            </div>

            <div style="height:calc(100% - 70px);">
                <div class="tab-content active" id="tab-messages" style="display:flex;flex-direction:column;height:100%;">
                    
                    <div id="teacher-toggle" style="display:none;gap:5px;padding:10px;background:#f8f9fa;border-bottom:1px solid #e0e0e0;">
                        <button id="show-students-btn" style="flex:1;padding:8px;border:none;border-radius:8px;cursor:pointer;background:#667eea;color:white;font-size:14px;font-weight:600;">
                            👥 <?= Yii::t('app', 'Students') ?>
                        </button>
                        <button id="show-courses-btn" style="flex:1;padding:8px;border:none;border-radius:8px;cursor:pointer;background:#e0e0e0;color:#666;font-size:14px;">
                            📢 <?= Yii::t('app', 'Courses') ?>
                        </button>
                    </div>

                    <div id="contact-list" style="overflow-y:auto;padding:10px;flex:1;">
                        <div style="text-align:center;padding:20px;"><?= Yii::t('app', 'Loading contacts...') ?></div>
                    </div>

                    <div id="course-list" style="display:none;overflow-y:auto;padding:10px;flex:1;">
                        <div style="text-align:center;padding:20px;"><?= Yii::t('app', 'Loading courses...') ?></div>
                    </div>

                    <div id="chat-window" style="display:none;flex-direction:column;height:100%;position:absolute;top:0;left:0;width:100%;background:white;z-index:5;">
                        <div style="flex:0 0 60px;padding:15px;background:linear-gradient(135deg,#667eea,#764ba2);color:white;display:flex;align-items:center;gap:10px;">
                            <button id="back-to-contacts" style="background:rgba(255,255,255,0.2);border:none;color:white;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:18px;">←</button>
                            <span id="current-contact-name" style="flex:1;font-weight:600;"><?= Yii::t('app', 'Contact') ?></span>
                            <button id="clear-chat-btn" style="background:rgba(255,255,255,0.2);border:none;color:white;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:18px;">🗑️</button>
                        </div>
                        <div id="messages-container" style="flex:1;overflow-y:auto;padding:15px;background:#f5f5f5;"></div>
                        <div style="flex:0 0 64px;display:flex;gap:10px;padding:12px;border-top:1px solid #e0e0e0;">
                            <input type="text" id="message-input" placeholder="<?= Yii::t('app', 'Type a message...') ?>" style="flex:1;padding:10px 15px;border:1px solid #ddd;border-radius:20px;outline:none;">
                            <button id="send-btn" style="width:40px;height:40px;border-radius:50%;border:none;background:#667eea;color:white;cursor:pointer;">➤</button>
                        </div>
                    </div>

                    <div id="course-chat-window" style="display:none;flex-direction:column;height:100%;position:absolute;top:0;left:0;width:100%;background:white;z-index:10;">
                        <div style="flex:0 0 60px;padding:15px;background:linear-gradient(135deg,#ff9800,#ff5722);color:white;display:flex;align-items:center;gap:10px;">
                            <button id="back-to-courses" style="background:rgba(255,255,255,0.2);border:none;color:white;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:18px;">←</button>
                            <div style="flex:1;">
                                <div id="current-course-name" style="font-weight:600;"><?= Yii::t('app', 'Course') ?></div>
                                <small id="course-student-count" style="font-size:12px;opacity:0.8;"><?= Yii::t('app', 'Students') ?></small>
                            </div>
                            <button id="clear-course-chat-btn" style="background:rgba(255,255,255,0.2);border:none;color:white;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:18px;">🗑️</button>
                        </div>
                        <div id="course-messages-container" style="flex:1;overflow-y:auto;padding:15px;background:#f5f5f5;"></div>
                        <div style="flex:0 0 64px;display:flex;gap:10px;padding:12px;border-top:1px solid #e0e0e0;">
                            <input type="text" id="course-message-input" placeholder="<?= Yii::t('app', 'Broadcast to all students...') ?>" style="flex:1;padding:10px 15px;border:1px solid #ddd;border-radius:20px;outline:none;">
                            <button id="course-send-btn" style="width:40px;height:40px;border-radius:50%;border:none;background:#ff9800;color:white;cursor:pointer;">📢</button>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="tab-support" style="display:none;flex-direction:column;height:100%;">
                    <div id="support-list" style="padding:15px;overflow-y:auto;flex:1;">
                        <button id="new-ticket-btn" style="background:linear-gradient(135deg,#667eea,#764ba2);color:white;border:none;padding:12px;border-radius:8px;cursor:pointer;width:100%;margin-bottom:15px;font-weight:600;">
                            + <?= Yii::t('app', 'New Ticket') ?>
                        </button>
                        <div id="tickets-list">
                            <div style="text-align:center;padding:20px;"><?= Yii::t('app', 'Loading tickets...') ?></div>
                        </div>
                    </div>

                    <div id="ticket-window" style="display:none;flex-direction:column;height:100%;position:absolute;top:0;left:0;width:100%;background:white;z-index:10;">
                        <div style="flex:0 0 60px;padding:15px;background:linear-gradient(135deg,#667eea,#764ba2);color:white;display:flex;align-items:center;gap:10px;">
                            <button id="back-to-tickets" style="background:rgba(255,255,255,0.2);border:none;color:white;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:18px;">←</button>
                            <span style="flex:1;font-weight:600;"><?= Yii::t('app', 'Ticket Details') ?></span>
                            <button id="delete-ticket-btn" style="background:rgba(255,100,100,0.3);border:none;color:white;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:18px;">🗑️</button>
                        </div>
                        <div id="ticket-details" style="flex:1;overflow-y:auto;padding:20px;"></div>
                    </div>

                    <div id="ticket-form" style="display:none;padding:15px;overflow-y:auto;flex:1;">
                        <button id="cancel-ticket" style="background:#e0e0e0;border:none;padding:8px 16px;border-radius:8px;cursor:pointer;margin-bottom:15px;">
                            ← <?= Yii::t('app', 'Back') ?>
                        </button>
                        <input type="text" id="ticket-subject" placeholder="<?= Yii::t('app', 'Subject') ?>" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;margin-bottom:10px;box-sizing:border-box;">
                        <textarea id="ticket-message" placeholder="<?= Yii::t('app', 'Describe your issue...') ?>" rows="5" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;margin-bottom:10px;box-sizing:border-box;font-family:inherit;"></textarea>
                        <button id="submit-ticket" style="background:linear-gradient(135deg,#667eea,#764ba2);color:white;border:none;padding:12px;border-radius:8px;cursor:pointer;width:100%;font-weight:600;">
                            <?= Yii::t('app', 'Submit Ticket') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $this->registerCssFile('@web/css/support-style.css?v=' . time());
    $this->registerJsFile('@web/js/support-app.js?v=' . time(), ['position' => \yii\web\View::POS_END]);
    ?>

    <?php $this->endBody() ?>
</body>

<script>
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `glass-toast ${type}`;
        
        const icon = type === 'success' ? '<i class="fas fa-check-circle fs-5"></i>' : '<i class="fas fa-exclamation-circle fs-5"></i>';
        toast.innerHTML = `${icon} <span>${message}</span>`;
        
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 10);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }
</script>

</html>
<?php $this->endPage() ?>