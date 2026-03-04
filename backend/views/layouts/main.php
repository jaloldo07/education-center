<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> - Admin Panel</title>
    <?php $this->head() ?>

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
            --sidebar-bg: linear-gradient(135deg, #707db6ff 0%, #9f8eb1ff 100%);
            --navbar-bg: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #94add6ff 0%, #5886cbff 100%);
            min-height: 100vh;
            color: var(--dark-color);
        }

        /* --- NAVBAR TUZATILDI (Z-INDEX) --- */
        .navbar {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: relative;
            /* Qo'shildi */
            z-index: 1050;
            /* Qo'shildi: Dropdown eng ustda turishi uchun */
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .navbar-brand i {
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        .search-container {
            position: relative;
            width: 400px;
            margin: 0 15px;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.3);
            transform: translateY(-2px);
        }

        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            margin-top: 8px;
            display: none;
            z-index: 9999;
            max-height: 400px;
            overflow-y: auto;
            backdrop-filter: blur(10px);
        }

        #search-results::-webkit-scrollbar {
            width: 8px;
        }

        #search-results::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        #search-results::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        #search-results::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        .search-item {
            padding: 16px 20px;
            text-decoration: none;
            color: var(--dark-color);
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-item:last-child {
            border-bottom: none;
        }

        .search-item:hover {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            color: white;
            transform: translateX(8px);
        }

        .search-item-content h4 {
            margin: 0 0 4px 0;
            font-weight: 600;
            font-size: 15px;
        }

        .search-item-content p {
            margin: 0;
            font-size: 13px;
            opacity: 0.7;
        }

        .search-item-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .sidebar {
            min-height: calc(100vh - 70px);
            background: var(--sidebar-bg);
            color: white;
            position: sticky;
            top: 70px;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(4px);
        }

        .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .content-wrapper {
            padding: 30px 20px;
            animation: fadeInUp 0.6s ease;
        }

        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #e6e3ecff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        .stat-label {
            color: var(--gray-color);
            font-size: 14px;
            font-weight: 500;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
        }

        .alert {
            border-radius: 12px;
            border: none;
            backdrop-filter: blur(10px);
            animation: slideIn 0.5s ease;
        }

        /* --- JADVAL DIZAYNI TUZATILDI --- */
        .table {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table th {
            background: linear-gradient(90deg, #5e72cc, #59558f);
            color: white;
            font-weight: 600;
            border: none;
        }

        /* Table Header ichidagi linklarni (sortirovka) oq rang qilish */
        .table th a {
            color: #fff !important;
            text-decoration: none !important;
            display: block;
            /* Butun katakni bosiladigan qilish uchun */
        }

        .table th a:hover {
            color: #e2e8f0 !important;
            text-decoration: none !important;
        }

        .table td {
            border-top: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        /* --- */

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        @media (max-width: 768px) {
            .search-container {
                width: 200px;
            }

            .sidebar {
                position: relative;
                top: 0;
            }

            .content-wrapper {
                padding: 20px 15px;
            }
        }

        .badge {
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 500;
            color: #59558f;
        }

        .fade-in {
            animation: fadeInUp 0.6s ease;
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <?php $this->beginBody() ?>

    <?php
    NavBar::begin([
        'brandLabel' => '<i class="fas fa-graduation-cap"></i> ' . Yii::t('app', 'Admin Panel'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-lg navbar-dark mb-0'],
        'innerContainerOptions' => ['class' => 'container-fluid px-4']
    ]);

    // Search Input
    echo '<div class="search-container">';
    echo '<input class="search-input" id="backend-search" type="text" placeholder="' . Yii::t('app', '🔍 Search students, teachers, courses...') . '">';
    echo '<div id="search-results" class="mt-2"></div>';
    echo '</div>';

    $menuItems = [];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li class="nav-item ms-3">'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
            . Html::submitButton(
                '<i class="fas fa-sign-out-alt"></i> ' . Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-outline-light btn-sm']
            )
            . Html::endForm()
            . '</li>';
    }

    // Language Menu
    $langLabel = strtoupper(substr(Yii::$app->language, 0, 2));
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

    NavBar::end();
    ?>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar px-0">
                <div class="position-sticky pt-4 pb-4">
                    <ul class="nav flex-column px-3">
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-tachometer-alt"></i> ' . Yii::t('app', 'Dashboard'), ['/dashboard/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-file-alt"></i> ' . Yii::t('app', 'Teacher Apps'), ['/teacher-application/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-file-alt"></i> ' . Yii::t('app', 'Enroll Apps'), ['/enrollment-application/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-user-plus"></i> ' . Yii::t('app', 'Enrollments'), ['/enrollment/index'], ['class' => 'nav-link']) ?>
                        </li>                        
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-chalkboard-teacher"></i> ' . Yii::t('app', 'Teachers'), ['/teacher/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-users"></i> ' . Yii::t('app', 'Students'), ['/student/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-book"></i> ' . Yii::t('app', 'Courses'), ['/course/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-layer-group"></i> ' . Yii::t('app', 'Groups'), ['/group/index'], ['class' => 'nav-link']) ?>
                        </li>

                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-money-bill-wave"></i> ' . Yii::t('app', 'Payments'), ['/payment/index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item mb-1">
                            <?= Html::a('<i class="fas fa-headset"></i> ' . Yii::t('app', 'Support Tickets'), ['/support/index'], ['class' => 'nav-link']) ?>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="content-wrapper">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= Yii::$app->session->getFlash('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= Yii::$app->session->getFlash('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?= $content ?>
                </div>
            </main>
        </div>
    </div>

    <?php
    $searchUrl = \yii\helpers\Url::to(['/site/search']);
    $noResultsText = Yii::t('app', 'No results found');

    $this->registerJs("
(function() {
    'use strict';
    
    try {
        let searchTimeout;
        
        // Search input mavjudligini tekshirish
        const searchInput = $('.search-input');
        const searchResults = $('#search-results');
        
        if (!searchInput.length || !searchResults.length) {
            console.warn('Search elements not found');
            return;
        }
        
        // Search functionality
        searchInput.on('keyup', function() {
            try {
                clearTimeout(searchTimeout);
                let query = $(this).val();
                
                if(query.length < 2) {
                    searchResults.fadeOut();
                    return;
                }
                
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: '$searchUrl',
                        data: {q: query},
                        success: function(data) {
                            try {
                                if(data.results.length === 0) {
                                    searchResults.html('<div class=\"search-item text-center text-muted py-4\"><i class=\"fas fa-search me-2\"></i>$noResultsText</div>').fadeIn();
                                    return;
                                }
                                
                                let html = '';
                                $.each(data.results, function(i, item) {
                                    html += '<a href=\"' + item.url + '\" class=\"search-item hover-lift\">';
                                    html += '  <div class=\"search-item-icon bg-gradient-' + item.color + '\"> <i class=\"fas ' + item.icon + '\"></i> </div>';
                                    html += '  <div class=\"search-item-content\">';
                                    html += '    <h4>' + item.title + '</h4>';
                                    html += '    <p>' + item.subtitle + '</p>';
                                    html += '    <span class=\"badge bg-' + item.color + ' badge-sm\">' + item.type + '</span>';
                                    html += '  </div>';
                                    html += '</a>';
                                });
                                
                                searchResults.html(html).fadeIn();
                            } catch (e) {
                                console.error('Search results processing error:', e);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Search AJAX error:', error);
                        }
                    });
                }, 300);
            } catch (e) {
                console.error('Search keyup error:', e);
            }
        });

        // Click outside to close
        $(document).click(function(e) {
            try {
                if(!$(e.target).closest('.search-container').length) {
                    searchResults.fadeOut();
                }
            } catch (e) {
                console.error('Click outside error:', e);
            }
        });

        // Hover effects
        $(document).on('mouseenter', '.search-item', function() {
            try {
                $(this).css('background', 'linear-gradient(90deg, var(--primary-color), var(--accent-color))');
                $(this).find('h4, p').css('color', 'white');
            } catch (e) {
                console.error('Hover error:', e);
            }
        }).on('mouseleave', '.search-item', function() {
            try {
                $(this).css('background', 'white');
                $(this).find('h4, p').css('color', 'var(--dark-color)');
            } catch (e) {
                console.error('Hover leave error:', e);
            }
        });
        
        console.log('✅ Global Search initialized successfully');
        
    } catch (error) {
        console.error('❌ Global Search initialization failed:', error);
    }
})();
", \yii\web\View::POS_READY);

    ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>