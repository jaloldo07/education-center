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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            background-image: url('https://avatars.mds.yandex.net/i?id=00c964d41d01f60c77dba299ce0547c6_l-9197384-images-thumbs&n=13');
            background-size: cover;
            background-repeat: repeat-y;
            background-position: center;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.71);
            backdrop-filter: blur(1px);
            z-index: -1;
        }



        .navbar {
            background: var(--navbar-bg) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
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
            width: 350px;
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

        #search-results-frontend {
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

        #search-results-frontend::-webkit-scrollbar {
            width: 8px;
        }

        #search-results-frontend::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        #search-results-frontend::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        #search-results-frontend::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        .search-item-frontend {
            padding: 16px 20px;
            text-decoration: none;
            color: var(--dark-color);
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-item-frontend:last-child {
            border-bottom: none;
        }

        .search-item-frontend:hover {
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

        .teacher-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: scale(1.05);
            transition: all 0.3s;
        }

        .stat-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            min-height: calc(100vh - 200px);
            padding: 40px 0;
        }

        footer {
            background: linear-gradient(135deg, var(--dark-color), #334155);
            color: white;
            padding: 60px 0 30px;
            margin-top: auto;
        }

        .footer-section h5 {
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
        }

        .footer-section h5::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .footer-social a {
            display: inline-block;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 45px;
            color: white;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .footer-social a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .footer-contact i {
            width: 20px;
            margin-right: 10px;
            color: var(--accent-color);
        }

        .footer-divider {
            border: none;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 30px 0;
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

        .fade-in {
            animation: fadeInUp 0.6s ease;
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .search-container {
                width: 200px;
            }

            .footer-section {
                margin-bottom: 30px;
            }

            .main-content {
                padding: 20px 0;
            }
        }
    </style>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <?php
    NavBar::begin([
        'brandLabel' => '<i class="fas fa-graduation-cap"></i> Education Center',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar navbar-expand-lg navbar-dark mb-0'],
        'innerContainerOptions' => ['class' => 'container-fluid px-4']
    ]);

    $menuItems = [
        ['label' => '<i class="fas fa-home"></i> Home', 'url' => ['/site/index']],
        ['label' => '<i class="fas fa-book"></i> Courses', 'url' => ['/site/courses']],
        ['label' => '<i class="fas fa-chalkboard-teacher"></i> Teachers', 'url' => ['/site/teachers']],

    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '<i class="fas fa-sign-in-alt"></i> Login', 'url' => ['/site/login']];
        $menuItems[] = ['label' => '<i class="fas fa-user-plus"></i> Register', 'url' => ['/site/signup']];
    } else {
        $role = Yii::$app->user->identity->role;

        if ($role === 'student') {
            $menuItems[] = ['label' => '<i class="fas fa-tachometer-alt"></i> My Dashboard', 'url' => ['/student/dashboard']];
        } elseif ($role === 'teacher') {
            $menuItems[] = ['label' => '<i class="fas fa-chalkboard"></i> My Dashboard', 'url' => ['/teacher/dashboard']];
        } elseif ($role === 'admin') {
            $menuItems[] = ['label' => '<i class="fas fa-user-shield"></i> Admin Panel', 'url' => ['/admin/dashboard/index']];
        }

        $menuItems[] = '<li class="nav-item ms-3">'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
            . Html::submitButton(
                '<i class="fas fa-sign-out-alt"></i> Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-outline-light btn-sm hover-lift']
            )
            . Html::endForm()
            . '</li>';
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);

    echo '<div class="search-container">';
    echo '<input class="search-input" type="text" placeholder="🔍 Search courses, teachers...">';
    echo '<div id="search-results-frontend" class="mt-2"></div>';
    echo '</div>';

    NavBar::end();
    ?>

    <main role="main" class="flex-shrink-0 main-content">
        <div class="container mt-4">
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border: none;">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= Yii::$app->session->getFlash('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px; border: none;">
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
                    <h5><i class="fas fa-graduation-cap me-2"></i>Education Center</h5>
                    <p>Empowering students through quality education since 2020. We believe in excellence and innovation in learning.</p>
                </div>
                <div class="col-md-4 footer-section">
                    <h5>Contact Us</h5>
                    <div class="footer-contact">
                        <p><i class="fas fa-phone"></i> +998 90 123 45 67</p>
                        <p><i class="fas fa-envelope"></i> info@education-center.uz</p>
                        <p><i class="fas fa-map-marker-alt"></i> Tashkent, Uzbekistan</p>
                    </div>
                </div>
                <div class="col-md-4 footer-section">
                    <h5>Follow Us</h5>
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
                <p class="mb-0">&copy; <?= date('Y') ?> Education Center. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    $searchUrl = \yii\helpers\Url::to(['/site/search']);
    $this->registerJs("
let searchTimeout;

jQuery('#frontend-search').on('input', function(e) {
    e.preventDefault();
    clearTimeout(searchTimeout);
    let query = jQuery(this).val().trim();
    
    if(query.length < 2) {
        jQuery('#search-results-frontend').fadeOut();
        return;
    }
    
    searchTimeout = setTimeout(function() {
        jQuery.ajax({
            url: '$searchUrl',
            method: 'GET',
            data: {q: query},
            dataType: 'json',
            success: function(data) {
                console.log('Search results:', data);
                
                if(!data.results || data.results.length === 0) {
                    jQuery('#search-results-frontend').html('<div class=\"search-item-frontend text-center text-muted py-4\"><i class=\"fas fa-search me-2\"></i>No results found</div>').fadeIn();
                    return;
                }
                
                let html = '';
                jQuery.each(data.results, function(i, item) {
                    html += '<a href=\"' + item.url + '\" class=\"search-item-frontend hover-lift\">';
                    html += '  <div class=\"search-item-icon bg-gradient-' + item.color + '\"> <i class=\"fas ' + item.icon + '\"></i> </div>';
                    html += '  <div class=\"search-item-content\">';
                    html += '    <h4>' + item.title + '</h4>';
                    html += '    <p>' + item.subtitle + '</p>';
                    html += '  </div>';
                    html += '</a>';
                });
                
                jQuery('#search-results-frontend').html(html).fadeIn();
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
            }
        });
    }, 300);
});

// Form submit'ni to'xtatish
jQuery('#frontend-search').closest('form').on('submit', function(e) {
    e.preventDefault();
    return false;
});

// Click outside to close
jQuery(document).click(function(e) {
    if(!jQuery(e.target).closest('.search-container').length) {
        jQuery('#search-results-frontend').fadeOut();
    }
});

// Hover effect
jQuery(document).on('mouseenter', '.search-item-frontend', function() {
    jQuery(this).css('background', 'linear-gradient(90deg, var(--primary-color), var(--accent-color))');
    jQuery(this).find('h4, p').css('color', 'white');
}).on('mouseleave', '.search-item-frontend', function() {
    jQuery(this).css('background', 'white');
    jQuery(this).find('h4, p').css('color', 'var(--dark-color)');
});

// Natijalarni bosganda sahifaga o'tish va searchni yopish
jQuery(document).on('click', '.search-item-frontend', function() {
    jQuery('#search-results-frontend').fadeOut();
    jQuery('#frontend-search').val('');
});

// Initialize tooltips and other effects
jQuery(document).ready(function() {
    jQuery('[data-bs-toggle=\"tooltip\"]').tooltip();
});
", \yii\web\View::POS_READY);
    ?>













    <?php if (!Yii::$app->user->isGuest): ?>
        <!-- ==================== FLOATING CHAT WIDGET ==================== -->
        <div id="chat-widget">
            <!-- Floating Button -->
            <div id="chat-button" class="chat-button" style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; z-index: 9998; box-shadow: 0 4px 20px rgba(102,126,234,0.4);">
                💬
                <span class="badge" id="total-unread" style="position: absolute; top: -5px; right: -5px; background: #ff4757; color: white; width: 20px; height: 20px; border-radius: 50%; font-size: 11px; display: flex; align-items: center; justify-content: center; display: none;">0</span>
            </div>

            <!-- Chat Popup -->
            <div id="chat-popup" class="chat-popup" style="display: none; position: fixed; bottom: 90px; right: 20px; width: 380px; height: 550px; background: white; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); z-index: 9999; overflow: hidden;">
                <div class="chat-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <div class="chat-tabs" style="display: flex; gap: 8px;">
                        <button class="tab-btn active" data-tab="messages" style="background: white; color: #667eea; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 14px;">Messages</button>
                        <button class="tab-btn" data-tab="support" style="background: rgba(255,255,255,0.2); color: white; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 14px;">Support</button>
                    </div>
                    <button id="close-chat" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 20px;">×</button>
                </div>

                <div class="chat-body" style="height: calc(100% - 70px); position: relative;">
                    <!-- Messages Tab -->
                    <div class="tab-content active" id="tab-messages" style="display: flex; flex-direction: column; height: 100%;">
                        <!-- Contact List -->
                        <div class="contact-list" id="contact-list" style="overflow-y: auto; padding: 10px; height: 100%;">
                            <div class="loading" style="text-align: center; padding: 20px; color: #999;">Loading contacts...</div>
                        </div>

                        <!-- Chat Window -->
                        <div class="chat-window" id="chat-window" style="display: none; flex-direction: column; height: 100%; position: absolute; top: 0; left: 0; width: 100%; background: white;">
                            <div class="chat-window-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px; display: flex; align-items: center; gap: 10px; color: white;">
                                <button id="back-to-contacts" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 18px;">←</button>
                                <span id="current-contact-name" style="flex: 1; font-weight: 600;">...</span>
                            </div>
                            <div id="messages-container" style="flex: 1; overflow-y: auto; padding: 15px; background: #f5f5f5;"></div>
                            <div class="message-input" style="display: flex; gap: 10px; padding: 12px; border-top: 1px solid #e0e0e0; background: white;">
                                <input type="text" id="message-input" placeholder="Type a message..." style="flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 14px;">
                                <button id="send-btn" style="width: 40px; height: 40px; border-radius: 50%; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; cursor: pointer; font-size: 18px;">➤</button>
                            </div>
                        </div>
                    </div>

                    <!-- Support Tab -->
                    <div class="tab-content" id="tab-support" style="display: none; flex-direction: column; height: 100%;">
                        <div class="support-list" id="support-list" style="padding: 15px; overflow-y: auto; height: 100%;">
                            <button id="new-ticket-btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 100%; margin-bottom: 15px; font-size: 14px;">+ New Ticket</button>
                            <div id="tickets-list">
                                <div class="loading" style="text-align: center; padding: 20px; color: #999;">Loading tickets...</div>
                            </div>
                        </div>

                        <div class="ticket-window" id="ticket-window" style="display: none; flex-direction: column; height: 100%;">
                            <div class="chat-window-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px; display: flex; align-items: center; gap: 10px; color: white;">
                                <button id="back-to-tickets" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; font-size: 18px;">←</button>
                                <span style="flex: 1; font-weight: 600;">Ticket Details</span>
                            </div>
                            <div id="ticket-details" style="flex: 1; overflow-y: auto; padding: 20px;"></div>
                        </div>

                        <div class="ticket-form" id="ticket-form" style="display: none; padding: 15px; overflow-y: auto; height: 100%;">
                            <div style="margin-bottom: 15px;">
                                <button id="cancel-ticket" style="background: #ccc; border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer;">← Back</button>
                            </div>
                            <input type="text" id="ticket-subject" placeholder="Subject" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 10px; box-sizing: border-box;">
                            <textarea id="ticket-message" placeholder="Describe your issue..." rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 10px; box-sizing: border-box; resize: vertical;"></textarea>
                            <button id="submit-ticket" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; width: 100%;">Submit Ticket</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $this->registerCssFile('@web/css/floating-chat-v2.css?v=' . time());
        $this->registerJsFile('@web/js/floating-chat-v2.js?v=' . time());
        ?>
    <?php endif; ?>






    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
```