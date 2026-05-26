<!doctype html>
<html lang="ar"  dir="rtl">
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo e(isset($title) ? $title. " | ":''); ?>منصة لغتي الأولى</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="author" content="">
    <meta name="robots" content="index, follow">

    <meta name="geo.position" content="">
    <meta name="geo.placename" content="">
    <meta name="geo.region" content="">
    <meta name="google" content="notranslate">

    <meta property="og:type" content="" />
    <meta property="og:title" content="<?php echo e(isset($title) ? $title. " | ":''); ?>منصة لغتي الأولى" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="<?php echo e(!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))); ?>"/>
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />

    <meta name="twitter:title" content="<?php echo e(isset($title) ? $title. " | ":''); ?>منصة لغتي الأولى"/>
    <meta name="twitter:description" content=""/>
    <meta name="twitter:image" content="<?php echo e(!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))); ?>"/>
    <meta name="twitter:site" content=""/>
    <meta name="twitter:creator" content=""/>

    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="<?php echo e(!settingCache('logo_min')? asset('logo_min.svg'):asset(settingCache('logo_min'))); ?>" type="image/x-icon">

    <!-- Bootstrap CSS v5.0.2 -->
    <?php echo $__env->yieldContent('p_style'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('cdn_files/bootstrap.rtl.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('web_assets/css/custom.css')); ?>?v=2">
    <link rel="stylesheet" href="<?php echo e(asset('web_assets/css/resposive.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('web_assets/css/container-modern.css')); ?>">

    <?php echo $__env->yieldContent('style'); ?>
    <style>
        .help-block{
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>

</head>
<body <?php if(!Request::is('')): ?> class="student-page" <?php endif; ?>>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Modern Navigation -->
    <nav class="modern-nav" id="mainNav">
        <div class="nav-container">
            <a href="/" class="nav-logo">
                <img src="<?php echo e(asset('web_assets/img/logo.svg')); ?>" alt="Logo">
            </a>

            <div class="mobile-toggle" id="mobileToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="nav-menu" id="navMenu">
                <!-- Sidebar Header (Mobile Only) -->
                <li class="sidebar-header" style="list-style: none;">
                    <a href="/" class="nav-logo">
                        <img src="<?php echo e(asset('web_assets/img/logo.svg')); ?>" alt="Logo" style="height: 45px;">
                    </a>
                    <button class="sidebar-close" id="sidebarClose" aria-label="Close menu">
                        ×
                    </button>
                </li>

                <li class="nav-item">
                    <a class="nav-link-modern" href="/school/login">دخول المدرسة</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link-modern" href="/teacher/login">دخول المعلم</a>
                </li>
                <li class="nav-item">
                    <a class="btn-outline-modern" href="/register">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <line x1="19" y1="8" x2="19" y2="14"></line>
                            <line x1="22" y1="11" x2="16" y2="11"></line>
                        </svg>
                        تسجيل جديد
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn-primary-modern" href="/login">
                        دخول الطالب
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-wrapper" id="home">
    <!-- Start Header -->
    <?php echo $__env->yieldContent('header'); ?>
    <?php echo $__env->yieldContent('content'); ?>
</main>
<!-- End Main -->

    <!-- Modern Footer -->
    <footer class="modern-footer">
        <div class="footer-container">

            <div class="footer-grid">
                <!-- About Section -->
                <div class="footer-section">
                    <a href="/" class="nav-logo" style="margin-bottom: 1.5rem; display: inline-block;">
                        <img src="<?php echo e(asset('web_assets/img/logo.svg')); ?>" alt="Logo" style="height: 60px; filter: brightness(0) invert(1);">
                    </a>
                    <p style="color: rgba(255,255,255,0.7); line-height: 1.8; margin-bottom: 1.5rem;">
                        منصة تعليمية متقدمة لتعليم اللغة العربية للناطقين بها
                    </p>
                    <div class="social-links">
                        <?php if(settingCache('facebook')): ?>
                            <a href="<?php echo e(settingCache('facebook')); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>

                        <?php if(settingCache('twitter')): ?>
                            <a href="<?php echo e(settingCache('twitter')); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>

                        <?php if(settingCache('instagram')): ?>
                            <a href="<?php echo e(settingCache('instagram')); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>

                        <?php if(settingCache('youtube')): ?>
                            <a href="<?php echo e(settingCache('youtube')); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(settingCache('linkedin')): ?>
                            <a href="<?php echo e(settingCache('linkedin')); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h4>روابط سريعة</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo e(Request::is('') ? '#home' : '/#home'); ?>">الرئيسية</a></li>
                        <li><a href="/school/login">دخول المدرسة</a></li>
                        <li><a href="/teacher/login">دخول المعلم</a></li>
                    </ul>
                </div>

                <!-- Portal Access -->
                <div class="footer-section">
                    <h4>الدخول للمنصة</h4>
                    <ul class="footer-links">
                        <li><a href="/login">دخول الطالب</a></li>
                        <li><a href="/teacher/login">دخول المعلم</a></li>
                        <li><a href="/school/login">دخول المدرسة</a></li>
                        <li><a href="/register">تسجيل جديد</a></li>
                    </ul>
                </div>
                <!-- Contact Info -->
                <div class="footer-section">
                    <h4>تواصل معنا</h4>
                    <div class="contact-card">
                        <img src="<?php echo e(asset('web_assets/img/number.svg')); ?>" alt="Phone">
                        <a href="tel:<?php echo e(settingCache('mobile')??'+971503842666'); ?>">
                            <span dir="ltr"><?php echo e(settingCache('mobile')??'+971503842666'); ?></span>
                        </a>
                    </div>
                    <div class="contact-card">
                        <img src="<?php echo e(asset('web_assets/img/email.svg')); ?>" alt="Email">
                        <a href="mailto:<?php echo e(settingCache('email')??'support@abt-assessments.com'); ?>">
                            <span dir="ltr"><?php echo e(settingCache('email')??'support@abt-assessments.com'); ?></span>
                        </a>
                    </div>
                </div>

            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom">
                <div>
                    <a href="#!" style="font-weight: 600;">ABT Assessments</a><span> - جميع الحقوق محفوظة</span> <span>2017 - <?php echo e(date('Y')); ?> © </span>
                </div>
                <div>
                    <a href="<?php echo e(asset('Privacy Policy.pdf')); ?>">سياسة الخصوصية</a>
                </div>
            </div>

        </div>
    </footer>

<!-- Bootstrap JavaScript Libraries -->
<script src="<?php echo e(asset('cdn_files/jquery.min.js')); ?>"></script>

<script src="<?php echo e(asset('cdn_files/bootstrap_5_0.min.js')); ?>"></script>
<script src="<?php echo e(asset('cdn_files/toastify-js.net_npm_toastify-js')); ?>"></script>

<script src="<?php echo e(asset("assets/vendors/general/toastr/build/toastr.min.js")); ?>" type="text/javascript"></script>
<script src="<?php echo e(asset('web_assets/intlTelInput/intlTelInput.min.js')); ?>"></script>




<script src="<?php echo e(asset('web_assets/js/custom.js')); ?>?v=<?php echo e(time()); ?>"></script>
<script>
    $(document).ready(function () {
        'use strict';

        // Sidebar Navigation
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarClose = document.getElementById('sidebarClose');
        const body = document.body;

        // Function to open sidebar
        function openSidebar() {
            navMenu.classList.add('active');
            sidebarOverlay.classList.add('active');
            mobileToggle.classList.add('active');
            body.classList.add('sidebar-open');
        }

        // Function to close sidebar
        function closeSidebar() {
            navMenu.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            mobileToggle.classList.remove('active');
            body.classList.remove('sidebar-open');
        }

        // Toggle sidebar on hamburger click
        if (mobileToggle) {
            mobileToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (navMenu.classList.contains('active')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }

        // Close sidebar on close button click
        if (sidebarClose) {
            sidebarClose.addEventListener('click', function() {
                closeSidebar();
            });
        }

        // Close sidebar on overlay click
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                closeSidebar();
            });
        }

        // Close sidebar when clicking on a navigation link
        if (navMenu) {
            navMenu.querySelectorAll('a:not(.nav-logo)').forEach(link => {
                link.addEventListener('click', function() {
                    closeSidebar();
                });
            });
        }

        // Close sidebar on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                closeSidebar();
            }
        });

        // Navbar scroll effect
        const navbar = document.getElementById('mainNav');
        if (navbar) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        }
        //
        // // تعطيل الزر الأيمن
        // document.addEventListener('contextmenu', function(e) {
        //     e.preventDefault();
        // });
        // document.addEventListener('keydown', function (e) {
        //     // تعطيل مفتاح F12
        //     if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
        //         e.preventDefault();
        //     }
        //     // تعطيل النسخ باستخدام CTRL+C
        //     if (e.ctrlKey && e.key === 'c') {
        //         e.preventDefault();
        //     }
        //     // تعطيل CTRL+U
        //     if (e.ctrlKey && e.key === 'u') {
        //         e.preventDefault();
        //     }
        // });
        //
        // // تعطيل النسخ عن طريق التحديد
        // document.addEventListener('copy', function (e) {
        //     e.preventDefault();
        // });
        //
        // // تعطيل تحديد النص
        // document.addEventListener('selectstart', function (e) {
        //     e.preventDefault();
        // });
    });
</script>
<?php echo $__env->yieldContent('script'); ?>
</body>
</html>
<?php /**PATH /home/omar/websites/arabic-arabs/resources/views/layouts/container_2.blade.php ENDPATH**/ ?>