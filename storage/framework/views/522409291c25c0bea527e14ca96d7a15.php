<head>
    <meta charset="UTF-8" />

    <!-- Theme Detection - MUST run before ANY CSS loads -->
    <script>
        (function () {
            var theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>

    <!-- Critical CSS for theme switching - inline to prevent flash -->
    <style>
        .navbar__logo .logo-dark,
        .footer__logo .logo-dark {
            display: none !important;
        }

        .navbar__logo .logo-light,
        .footer__logo .logo-light {
            display: block !important;
        }

        .dark-mode .navbar__logo .logo-dark,
        .dark-mode .footer__logo .logo-dark {
            display: block !important;
        }

        .dark-mode .navbar__logo .logo-light,
        .dark-mode .footer__logo .logo-light {
            display: none !important;
        }

        .theme-toggle-btn .theme-icon-dark {
            display: none !important;
        }

        .theme-toggle-btn .theme-icon-light {
            display: inline-block !important;
        }

        .dark-mode .theme-toggle-btn .theme-icon-dark {
            display: inline-block !important;
        }

        .dark-mode .theme-toggle-btn .theme-icon-light {
            display: none !important;
        }
    </style>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <meta name="robots" content="index, follow" />

    <title><?php echo $__env->yieldContent('title', config_get('site_name')); ?> - <?php echo e(config_get('site_name')); ?></title>

    <!-- Primary Meta Tags -->
    <meta name="description" content="<?php echo e(config_get('site_description')); ?>" />
    <meta name="keywords" content="<?php echo e(config_get('site_keywords')); ?>" />
    <meta name="author" content="<?php echo e(config_get('site_name')); ?>" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo e(url()->current()); ?>" />
    <meta property="og:site_name" content="<?php echo e(config_get('site_name')); ?>" />
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', config_get('site_name')); ?> - <?php echo e(config_get('site_name')); ?>" />
    <meta property="og:description" content="<?php echo e(config_get('site_description')); ?>" />
    <meta property="og:image" content="<?php echo e(config_get('site_share_image', config_get('site_logo'))); ?>" />
    <meta property="og:image:alt" content="<?php echo e(config_get('site_name')); ?>" />
    <meta property="og:locale" content="vi_VN" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="<?php echo e(url()->current()); ?>" />
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('title', config_get('site_name')); ?> - <?php echo e(config_get('site_name')); ?>" />
    <meta name="twitter:description" content="<?php echo e(config_get('site_description')); ?>" />
    <meta name="twitter:image" content="<?php echo e(config_get('site_share_image', config_get('site_logo'))); ?>" />
    <meta name="twitter:image:alt" content="<?php echo e(config_get('site_name')); ?>" />

    <!-- Favicon chuẩn cho mọi trình duyệt -->
    <link rel="icon" href="<?php echo e(config_get('site_favicon')); ?>" type="image/png">

    <!-- Apple Touch Icon (iOS) - tùy chọn -->
    
    <link rel="apple-touch-icon" href="<?php echo e(config_get('site_favicon')); ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo e(url()->current()); ?>" />

    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com" />
    <link rel="dns-prefetch" href="//fonts.googleapis.com" />

    <!-- Preload Important Resources -->
    
    <link rel="preload" href="<?php echo e(asset('assets/css/global.css')); ?>" as="style" />

    <?php $css_ver = '1.7'; ?>

    <!-- Stylesheets -->
    
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/reset.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/global.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/home.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/register.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/profile.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/deposit.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/category.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/detail-account.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/service.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/wheel.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <!-- Random Accounts CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/random-accounts.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/random-account-detail.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/random-categories.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <!-- Game Accounts CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/game-accounts.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/game-account-detail.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <!-- Services CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/service-cards.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/hacks.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <!-- Lightbox CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/libs/simplelightbox.min.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/lightbox-custom.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <!-- Responsive Fixes -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/responsive-fixes.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/mobile-menu.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/header-responsive.css')); ?>?v=<?php echo e($css_ver); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/apps.css')); ?>?v=<?php echo e($css_ver); ?>" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- BoxIcons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Google Fonts - Be Vietnam Pro (Perfect for Vietnamese) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">


    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif !important;
            font-weight: 400;
            color: var(--text-color);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 600;
            color: var(--dark);
        }

        button,
        a {
            font-weight: 500;
        }
    </style>

    <?php if(request()->is('/')): ?>
        <script type="application/ld+json">
                                                    {
                                                        "@context": "https://schema.org",
                                                        "@type": "Organization",
                                                        "name": "<?php echo e(config_get('site_name')); ?>",
                                                        "url": "<?php echo e(url('/')); ?>",
                                                        "logo": "<?php echo e(config_get('site_logo')); ?>",
                                                        "contactPoint": {
                                                            "@type": "ContactPoint",
                                                            "telephone": "<?php echo e(config_get('phone')); ?>",
                                                            "contactType": "customer service",
                                                            "availableLanguage": "Vietnamese"
                                                        },
                                                        "sameAs": [
                                                            "<?php echo e(config_get('facebook')); ?>",
                                                            "<?php echo e(config_get('youtube')); ?>"
                                                        ]
                                                    }
                                                </script>
    <?php endif; ?>

    <!-- Page-specific CSS -->
    <?php echo $__env->yieldPushContent('css'); ?>
</head><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/layouts/user/head.blade.php ENDPATH**/ ?>