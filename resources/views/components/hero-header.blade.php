<!-- Modern & Premium Hero Header -->
<style>
    .hero-section {
        position: relative;
        padding: 20px 20px;
        background: #ffffff;
        text-align: center;
        overflow: hidden;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 20px;
    }

    /* Subtle Geometric Background Background */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(#0e3eda 0.5px, transparent 0.5px);
        background-size: 24px 24px;
        opacity: 0.03;
        pointer-events: none;
    }

    .hero-container {
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    /* Category/Label above title */
    .hero-label {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #0E3EDA;
        background: rgba(14, 62, 218, 0.08);
        padding: 6px 16px;
        border-radius: 99px;
        margin-bottom: 16px;
        animation: heroSlideDown 0.6s ease-out forwards;
    }

    .hero-title {
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 12px;
        line-height: 1.2;
        letter-spacing: -1px;
        text-transform: uppercase;
        animation: heroFadeUp 0.7s ease-out forwards;
    }

    .hero-title span {
        color: #0E3EDA;
    }

    .hero-description {
        font-size: 15px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
        font-weight: 500;
        animation: heroFadeUp 0.8s ease-out 0.1s forwards;
        opacity: 0;
    }

    /* Decorative Accent */
    .hero-accent {
        width: 40px;
        height: 4px;
        background: #0E3EDA;
        margin: 20px auto 0;
        border-radius: 2px;
        animation: heroExpand 0.8s ease-out 0.2s forwards;
        opacity: 0;
    }

    @keyframes heroFadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes heroSlideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes heroExpand {
        from {
            opacity: 0;
            width: 0;
        }
        to {
            opacity: 1;
            width: 40px;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 15px 15px;
        }
        .hero-title {
            font-size: 24px;
        }
        .hero-description {
            font-size: 13px;
        }
    }
</style>

<section class="hero-section">
    <div class="hero-container">
        @if(isset($label))
            <span class="hero-label">{{ $label }}</span>
        @else
            <span class="hero-label">ACCONE.VN</span>
        @endif
        
        <h1 class="hero-title">{{ $title }}</h1>
        
        @if (isset($description) && $description)
            <p class="hero-description">{{ $description }}</p>
        @endif
        
        <div class="hero-accent"></div>
    </div>
</section>
