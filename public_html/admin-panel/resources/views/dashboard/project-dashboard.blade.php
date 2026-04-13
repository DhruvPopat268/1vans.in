@extends('layouts.admin')
@section('page-title')
    {{__('Project Scheduling')}}
@endsection
@push('script-page')

<style>
        :root {
            /* Light Mode Color System */
            --primary: #000000;
            --primary-subtle: #1A1A1A;
            --accent: #007AFF;
            --accent-subtle: #0A84FF;

            /* Grayscale Palette */
            --white: #FFFFFF;
            --gray-50: #FAFAFA;
            --gray-100: #F5F5F5;
            --gray-200: #E5E5E5;
            --gray-300: #D4D4D4;
            --gray-400: #A3A3A3;
            --gray-500: #737373;
            --gray-600: #525252;
            --gray-700: #404040;
            --gray-800: #262626;
            --gray-900: #171717;
            --black: #000000;

            /* Light Theme Colors */
            --background: #FFFFFF;
            --surface: #FAFAFA;
            --surface-elevated: #FFFFFF;
            --surface-hover: #F5F5F5;
            --surface-active: #E5E5E5;
            --border: #E5E5E5;
            --border-subtle: #F0F0F0;

            /* Text Colors */
            --text-primary: #000000;
            --text-secondary: #525252;
            --text-tertiary: #737373;
            --text-quaternary: #A3A3A3;

            /* Typography Scale */
            --large-title: 34px;
            --title-1: 28px;
            --title-2: 22px;
            --title-3: 20px;
            --headline: 17px;
            --body: 17px;
            --callout: 16px;
            --subhead: 15px;
            --footnote: 13px;
            --caption-1: 12px;
            --caption-2: 11px;

            /* Spacing & Sizing */
            --corner-radius-small: 6px;
            --corner-radius-medium: 10px;
            --corner-radius-large: 14px;
            --corner-radius-extra-large: 20px;

            /* Shadows */
            --shadow-small: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 20px rgba(0, 0, 0, 0.05);
            --shadow-large: 0 8px 40px rgba(0, 0, 0, 0.1);
            --glow-subtle: 0 0 20px rgba(0, 0, 0, 0.05);
            --glow-strong: 0 0 30px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--background);
            color: var(--text-primary);
            line-height: 1.4;
            font-size: var(--body);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(0, 0, 0, 0.02) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 0, 0, 0.02) 0%, transparent 50%);
        }

        .navigation-view {
            min-height: 100vh;
            padding: 20px;
            max-width: 100%;
            margin: 0 auto;
        }

        /* Navigation Bar */
        .navigation-bar {
            background: var(--surface-elevated);
            border-radius: var(--corner-radius-large);
            padding: 16px 24px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-medium);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            position: relative;
        }

        .navigation-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
        }

        .nav-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--black) 0%, var(--gray-800) 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--white);
            box-shadow: var(--glow-subtle);
        }

        .nav-text {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: var(--title-2);
            font-weight: 700;
            color: var(--text-primary);
        }

        .nav-subtitle {
            font-size: var(--footnote);
            color: var(--text-secondary);
            font-weight: 500;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-pill {
            background: var(--black);
            color: var(--white);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: var(--caption-1);
            font-weight: 600;
            box-shadow: var(--glow-subtle);
        }

        /* Stats Grid */
        .stats-section {
            margin-bottom: 24px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .stat-card {
            background: var(--surface-elevated);
            border-radius: var(--corner-radius-medium);
            padding: 16px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-large);
            border-color: var(--gray-300);
            background: var(--surface-hover);
        }

        .stat-value {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: var(--title-1);
            font-weight: 700;
            color: var(--black);
            line-height: 1.1;
        }

        .stat-label {
            font-size: var(--footnote);
            color: var(--text-secondary);
            font-weight: 500;
            margin-top: 2px;
        }

        /* Main Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 16px;
        }

        /* Cards */
        .card {
            background: var(--surface-elevated);
            border-radius: var(--corner-radius-large);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 0, 0, 0.1), transparent);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-large);
            border-color: var(--gray-300);
            background: var(--surface-hover);
        }

        .card:active {
            transform: translateY(0);
            transition: transform 0.1s ease;
        }

        .card-header {
            padding: 20px 20px 0 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--corner-radius-medium);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: var(--gray-200);
            color: var(--black);
            border: 1px solid var(--border);
        }

        .card-title {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: var(--headline);
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-content {
            padding: 20px;
        }

        /* List Styles */
        .list-section {
            background: var(--surface);
            border-radius: var(--corner-radius-large);
            border: 1px solid var(--border-subtle);
            overflow: hidden;
        }

        .list-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border-subtle);
            cursor: pointer;
            transition: all 0.2s ease;
            gap: 12px;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background: var(--surface-hover);
        }

        .list-item:active {
            background: var(--surface-active);
        }

        .list-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--corner-radius-small);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            background: var(--gray-200);
            color: var(--black);
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .list-content {
            flex: 1;
            min-width: 0;
        }

        .list-title {
            font-size: var(--body);
            font-weight: 500;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .list-subtitle {
            font-size: var(--footnote);
            color: var(--text-secondary);
            margin-top: 1px;
        }

        .list-chevron {
            color: var(--text-tertiary);
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Button Styles */
        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 18px;
            border-radius: var(--corner-radius-medium);
            font-size: var(--body);
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            gap: 6px;
            position: relative;
            overflow: hidden;
        }

        .button-primary {
            background: var(--black);
            color: var(--white);
            box-shadow: var(--glow-subtle);
        }

        .button-primary:hover {
            background: var(--gray-900);
            transform: translateY(-1px);
            box-shadow: var(--glow-strong);
        }

        .button-secondary {
            background: var(--surface-elevated);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .button-secondary:hover {
            background: var(--surface-hover);
            border-color: var(--gray-300);
        }

        /* Category Grid */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }

        .category-item {
            background: var(--surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--corner-radius-medium);
            padding: 16px 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            min-height: 80px;
            justify-content: center;
            position: relative;
        }

        .category-item:hover {
            background: var(--surface-hover);
            transform: translateY(-1px);
            border-color: var(--gray-300);
        }

        .category-item:active {
            transform: scale(0.98);
            transition: transform 0.1s ease;
        }

        .category-item-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--corner-radius-small);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            background: var(--gray-200);
            color: var(--black);
            border: 1px solid var(--border);
        }

        .category-item-title {
            font-size: var(--footnote);
            font-weight: 500;
            color: var(--text-primary);
            text-align: center;
            line-height: 1.2;
        }

        /* Specific Hover Effects */
        .analytics:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .crm:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .projects:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .equipment:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .materials:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .reports:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .issues:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .gallery:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .users:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .settings:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navigation-view {
                padding: 16px;
            }

            .content-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .nav-content {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .navigation-bar {
                padding: 12px 16px;
            }

        .card-header,
        .card-content {
                padding: 16px;
            }

            .list-item {
                padding: 10px 16px;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
                animation: none !important;
            }
        }

        /* Focus states */
    .card:focus,
    .list-item:focus,
    .category-item:focus,
    .button:focus {
            outline: 2px solid var(--black);
            outline-offset: 2px;
        }

        /* Ripple Effect */
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.1);
            pointer-events: none;
            animation: ripple 0.6s linear;
        }

      .project-banner {
    position: relative;
    background-size: cover;
    background-position: center;
    border-radius: 12px;
    min-height: 240px;
    margin-bottom: 20px;
    overflow: hidden;
    display: flex;
    align-items: center;
}

.banner-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.banner-content {
    position: relative;
    z-index: 2;
    padding: 30px 40px;
    max-width: 60%;
    text-align: left;
}

.project-title {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 12px;
    color: #fff;
}

.project-meta {
    font-size: 14px;
    color: #ddd;
    margin-bottom: 6px;
    line-height: 1.5;
}
    </style>
        <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>

<script>
  window.OneSignalDeferred = window.OneSignalDeferred || [];
  OneSignalDeferred.push(async function(OneSignal) {
    await OneSignal.init({
      appId: "f782d678-ff2c-47d3-93c9-840c8a3b2683",
      serviceWorkerPath: "/OneSignalSDKWorker.js", // 👈 REMOVE `/public`, this must be root-level
    });

    // Wait for permission and get player ID
    const id = await OneSignal.User.PushSubscription.id;

    if (id) {
      // Send to your Laravel backend via AJAX
      fetch("{{ route('save.web.player') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          player_id: id
        })
      }).then(res => res.json())
        .then(data => console.log('Player ID saved:', data))
        .catch(err => console.error('Save failed', err));
    } else {
      console.warn('No player ID found');
    }
  });
</script>

    <script>
        // Enhanced dark mode interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add sophisticated ripple effect for interactions
            const interactiveElements = document.querySelectorAll('.card, .list-item, .category-item, .button');

            interactiveElements.forEach(element => {
                element.addEventListener('click', function(e) {
                    // Create enhanced ripple effect
                    const ripple = document.createElement('div');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.classList.add('ripple');
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';

                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });

                // Add subtle glow on hover for enhanced dark mode experience
                element.addEventListener('mouseenter', function() {
                    this.style.filter = 'brightness(1.05)';
                });

                element.addEventListener('mouseleave', function() {
                    this.style.filter = 'brightness(1)';
                });
            });

            // Handle navigation with smooth transitions
            const listItems = document.querySelectorAll('.list-item');
            listItems.forEach(item => {
                item.addEventListener('click', function() {
                    const title = this.querySelector('.list-title').textContent;
                    console.log(`Navigating to: ${title}`);

                    // Add visual feedback
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 100);
                });
            });

            // Add smooth scroll behavior for better UX
            document.documentElement.style.scrollBehavior = 'smooth';

            // Enhanced button interactions
            const buttons = document.querySelectorAll('.button');
            buttons.forEach(button => {
                button.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.96)';
                });

                button.addEventListener('mouseup', function() {
                    this.style.transform = 'scale(1)';
                });

                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Add subtle parallax effect to background
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const rate = scrolled * -0.5;
                document.body.style.backgroundPosition = `0 ${rate}px`;
            });

            // Dynamic theme adjustments based on time (optional enhancement)
            const hour = new Date().getHours();
            if (hour >= 22 || hour <= 6) {
                // Ultra light mode for late night/early morning
                document.documentElement.style.setProperty('--background', '#FFFFFF');
                document.documentElement.style.setProperty('--surface-elevated', '#FAFAFA');
            }
        });

        // Add custom context menu for professional feel
        document.addEventListener('contextmenu', function(e) {
           // e.preventDefault();
            // Could implement custom context menu here
        });

        // Keyboard navigation support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                // Enhance focus visibility for keyboard navigation
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        document.addEventListener('DOMContentLoaded', function() {
                @if(session('showClientModal'))
                var ClientModal = new bootstrap.Modal(document.getElementById('ClientModal'), {});
                ClientModal.show();
                @endif
            });

            function handleClientChoice(choice) {
                if (choice === 'yes') {
                    window.location.href = "{{ route('clients.index') }}"; // Redirect to the Client index page
                }
            }
    </script>

    <style>
        /* Enhanced keyboard navigation styles */
        .keyboard-navigation .card:focus,
        .keyboard-navigation .list-item:focus,
        .keyboard-navigation .category-item:focus,
        .keyboard-navigation .button:focus {
            outline: 2px solid var(--black);
            outline-offset: 3px;
            box-shadow: 0 0 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Subtle animation for loading state */
        @keyframes shimmer {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .loading {
            animation: shimmer 1.5s ease-in-out infinite;
        }

        /* Custom scrollbar for dark theme */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--surface);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Selection styling */
        ::selection {
            background: rgba(0, 0, 0, 0.2);
            color: var(--black);
        }

        ::-moz-selection {
            background: rgba(0, 0, 0, 0.2);
            color: var(--black);
        }
    </style>
    <style>


        .weather-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .search-section {
            margin-bottom: 30px;
            text-align: center;
        }

        .search-bar {
            display: inline-flex;
            background: white;
            border-radius: 25px;
            padding: 12px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e1e1e1;
            max-width: 400px;
            width: 100%;
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 16px;
            color: #333;
            background: none;
        }

        .search-input::placeholder {
            color: #999;
        }

        .search-btn {
            background: none;
            border: none;
            color: #007AFF;
            font-size: 16px;
            cursor: pointer;
            padding: 0 10px;
            transition: color 0.2s;
        }

        .search-btn:hover {
            color: #0056b3;
        }

        .weather-cards {
    display: grid;
     grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}


        .top-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
    align-items: stretch;   /* ⭐ IMPORTANT */
}

/* Banner Full Height */
.project-banner {
    height: 100%;
}

/* Weather Side Full Height */
.weather-side {
    display: flex;
    flex-direction: column;
    gap: 15px;
    height: 100%;            /* ⭐ IMPORTANT */
}

/* Cards Stretch */
.weather-side .weather-card {
  background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-medium);
    text-align: center;
    min-height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: center;              /* ⭐ IMPORTANT */
}


        .weather-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: capitalize;
        }

        .weather-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .temperature {
            font-size: 48px;
            font-weight: 300;
            color: #1d1d1f;
            line-height: 1;
        }

        .weather-icon {
            font-size: 40px;
            color: #007AFF;
            opacity: 0.8;
        }

        .weather-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #666;
        }

        .detail-item i {
            width: 16px;
            margin-right: 8px;
            color: #999;
        }

        .condition {
            grid-column: 1 / -1;
            font-size: 16px;
            color: #333;
            font-weight: 500;
            margin-top: 8px;
            text-align: center;
        }

        .loading-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 150px;
            color: #999;
        }

        .loading-state i {
            font-size: 24px;
            margin-bottom: 12px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        .na-state {
            font-size: 32px;
            font-weight: 600;
            color: #1d1d1f;
            text-align: center;
            padding: 40px 0;
        }

        .error-state {
            color: #ff3b30;
            text-align: center;
            padding: 20px 0;
        }

        .error-state i {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .hourly-forecast {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e1e1e1;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 20px;
        }

        .hourly-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .hourly-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 8px;
            border-radius: 8px;
            background: #f8f9fa;
            transition: background 0.2s;
        }

        .hourly-item:hover {
            background: #e9ecef;
        }

        .hour-time {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .hour-icon {
            font-size: 20px;
            color: #007AFF;
            margin: 8px 0;
        }

        .hour-temp {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        @media (max-width: 768px) {
            .weather-cards {
                grid-template-columns: 1fr;
            }

            .search-bar {
                margin: 0 20px;
            }

            .hourly-grid {
                grid-template-columns: repeat(6, minmax(70px, 1fr));
            }
              .top-section {
        grid-template-columns: 1fr;
    }

    .weather-side {
        flex-direction: row;
    }
        }

        .refresh-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #007AFF;
            color: white;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);
            transition: all 0.3s ease;
        }

        .refresh-btn:hover {
            background: #0056b3;
            transform: scale(1.1);
        }

        .refresh-btn:active {
            transform: scale(0.95);
        }

        .refresh-btn i {
            transition: transform 0.3s ease;
        }

        .refresh-btn.spinning i {
            animation: spin 1s linear infinite;
        }



        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
<style>
    .gantt-wrapper {
        background: white;
        border-radius: 14px;
        padding: 25px;
        overflow-x: auto;
        position: relative;
    }

    .gantt-wrapper::after {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;

        left: 300px;
        /* 80 + 220 = MAGIC 😎 */

        width: 1px;
        background: #e5e7eb;
    }

    /* Header */
    .gantt-header {
        font-size: 13px;
        font-weight: 600;
        color: #000;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 6px;
    }

    /* GROUP GRID (IMPORTANT) */
    .gantt-group {
        display: grid;
        grid-template-columns:
            80px 220px 90px 90px 90px repeat(12, 1fr);

        margin-bottom: 2px;

    }




    /* Cells */
    .phase-title {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        display: flex;
        align-items: center;
        padding-left: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .gantt-days {
        font-size: 11px;
        font-weight: 600;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Category Column */
    .phase-category {
        writing-mode: vertical-rl;
        transform: rotate(180deg);

        font-size: 16px;
        font-weight: 600;
        color: black;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 6px;
        margin-right: 6px;
        padding: 10px 6px;
    }

    /* Progress */
    .gantt-progress-wrapper {
        position: relative;
        height: 26px;
        background: #dbdcdd;
        border-radius: 6px;
        overflow: hidden;
        margin-top: 8px;
    }

    .gantt-progress-bar {
        height: 100%;
        border-radius: 6px;
        transition: width 0.4s ease;
    }

    .gantt-progress-label {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Divider */
    .category-divider {
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 8px;
    }
    
    #projectBanner {
        position: relative;
        overflow: hidden;
    }

    .slider-track {
        display: flex;
        width: 100%;
        height: 100%;
        transition: transform 1s ease-in-out;

        position: absolute;
        /* ⭐ ADD THIS */
        top: 0;
        left: 0;
    }

    .slide {
        min-width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;

        flex-shrink: 0;
        /* ⭐ IMPORTANT */
    }

</style>


<script>
    // Check if lat/lon are already in the URL
    if (!window.location.search.includes('lat=') && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            // Redirect to same URL with lat/lon parameters
            const baseUrl = window.location.origin + window.location.pathname;
            window.location.href = `${baseUrl}?lat=${lat}&lon=${lon}`;
        }, function(error) {
            console.warn("Geolocation not allowed or failed:", error.message);
        });
    }
</script>
<script>
    let currentCity = 'London';
    let weatherData = {};
    const apiKey = '8ca94961ae104aaab3b112137250606';

    function getWeatherIcon(condition, isDay = true) {
        const iconMap = {
            'clear': isDay ? 'fas fa-sun' : 'fas fa-moon',
            'sunny': 'fas fa-sun',
            'partly cloudy': isDay ? 'fas fa-cloud-sun' : 'fas fa-cloud-moon',
            'cloudy': 'fas fa-cloud',
            'overcast': 'fas fa-cloud',
            'rain': 'fas fa-cloud-rain',
            'light rain': 'fas fa-cloud-rain',
            'drizzle': 'fas fa-cloud-rain-heavy',
            'showers': 'fas fa-cloud-showers-heavy',
            'thunder': 'fas fa-bolt',
            'snow': 'fas fa-snowflake',
            'fog': 'fas fa-smog',
            'mist': 'fas fa-smog'
        };

        const normalized = condition.toLowerCase();
        for (const [key, icon] of Object.entries(iconMap)) {
            if (normalized.includes(key)) return icon;
        }
        return 'fas fa-cloud';
    }

    function getDayName(dateStr) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const date = new Date(dateStr);
        return days[date.getDay()];
    }

    async function fetchWeatherData(city = null, lat = null, lon = null) {
    const apiKey = '8ca94961ae104aaab3b112137250606';
    let url = '';

    if (lat && lon) {
        url = `https://api.weatherapi.com/v1/forecast.json?key=${apiKey}&q=${lat},${lon}&days=3&aqi=no&alerts=no`;
    } else {
        url = `https://api.weatherapi.com/v1/forecast.json?key=${apiKey}&q=${encodeURIComponent(city)}&days=3&aqi=no&alerts=no`;
    }

    const response = await fetch(url);
    if (!response.ok) throw new Error('Failed to fetch weather data');

    const data = await response.json();

    const now = new Date();
    const currentHour = now.getHours();

    // Collect next 12 hours across forecastday[0] and [1] if needed
    const allHours = [...data.forecast.forecastday[0].hour, ...data.forecast.forecastday[1].hour];

    const next12Hours = allHours
        .filter(hour => {
            const hourTime = new Date(hour.time);
            return hourTime > now;
        })
        .slice(0, 12)
        .map(hour => ({
            time: hour.time.split(' ')[1],  // HH:MM
            temp: Math.round(hour.temp_c),
            condition: hour.condition.text
        }));

    const daily = data.forecast.forecastday.map((day, i) => ({
        day: i === 0 ? 'Today' : i === 1 ? 'Tomorrow' : i === 2 ? 'Day After Tomorrow' : getDayName(i),
        temp: Math.round(day.day.avgtemp_c),
        condition: day.day.condition.text,
        humidity: day.day.avghumidity,
        windSpeed: day.day.maxwind_kph,
        pressure: day.hour[12].pressure_mb,
        uvIndex: day.day.uv
    }));

    return {
        city: data.location.name,
        country: data.location.country,
        daily,
        hourly: next12Hours
    };
}



    function updateWeatherCard(cardId, dayData) {
        const card = document.getElementById(cardId);
        const isDay = new Date().getHours() >= 6 && new Date().getHours() <= 18;

        card.innerHTML = `
            <div class="card-header">${dayData.day} Weather</div>
            <div class="weather-main">
                <div class="temperature">${dayData.temp}°C</div>
                <div class="weather-icon">
                    <i class="${getWeatherIcon(dayData.condition, isDay)}"></i>
                </div>
            </div>
            <div class="weather-details">
                <div class="detail-item"><i class="fas fa-tint"></i><span>${dayData.humidity}%</span></div>
                <div class="detail-item"><i class="fas fa-wind"></i><span>${dayData.windSpeed} km/h</span></div>
                <div class="detail-item"><i class="fas fa-thermometer-half"></i><span>${dayData.pressure} mb</span></div>
                <div class="detail-item"><i class="fas fa-sun"></i><span>UV ${dayData.uvIndex}</span></div>
                <div class="condition">${dayData.condition}</div>
            </div>
        `;
    }

    function updateHourlyForecast(hourlyData) {
        const hourlyGrid = document.getElementById('hourlyGrid');
        const hourlyHTML = hourlyData.map(hour => `
            <div class="hourly-item">
                <div class="hour-time">${hour.time}</div>
                <div class="hour-icon">
                    <i class="${getWeatherIcon(hour.condition)}"></i>
                </div>
                <div class="hour-temp">${hour.temp}°</div>
            </div>
        `).join('');
        hourlyGrid.innerHTML = hourlyHTML;
    }

    function showLoadingState() {
        const cards = ['todayCard', 'tomorrowCard'];
        const dayNames = ['Today', 'Tomorrow'];
        cards.forEach((cardId, index) => {
            const card = document.getElementById(cardId);
            card.innerHTML = `
                <div class="card-header">${dayNames[index]} Weather</div>
                <div class="loading-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading...</p>
                </div>
            `;
        });
        document.getElementById('hourlyGrid').innerHTML = `
            <div class="loading-state">
                <i class="fas fa-clock"></i>
                <p>Loading hourly data...</p>
            </div>
        `;
    }

    function showErrorState() {
        const cards = ['todayCard', 'tomorrowCard'];
        const dayNames = ['Today', 'Tomorrow'];
        cards.forEach((cardId, index) => {
            const card = document.getElementById(cardId);
            card.innerHTML = `
                <div class="card-header">${dayNames[index]} Weather</div>
                <div class="error-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Failed to load</p>
                </div>
            `;
        });
    }

    async function searchWeather() {
        const locationInput = document.getElementById('locationInput');
        const city = locationInput.value.trim();
        if (!city) {
            alert('Please enter a city name');
            return;
        }
        currentCity = city;
        await loadWeatherData();
    }

    async function loadWeatherData() {
    try {
        showLoadingState();

        const urlParams = new URLSearchParams(window.location.search);
        const lat = urlParams.get('lat');
        const lon = urlParams.get('lon');

        const data = await fetchWeatherData(currentCity, lat, lon);
        weatherData = data;

        // Update weather cards
        const cardIds = ['todayCard', 'tomorrowCard'];
        data.daily.forEach((dayData, index) => {
            if (index < cardIds.length) {
                updateWeatherCard(cardIds[index], dayData, index);
            }
        });

        // Update hourly forecast
        updateHourlyForecast(data.hourly);
    } catch (error) {
        showErrorState('Failed to load weather data');
        console.error('Error loading weather:', error);
    }
}


    async function refreshWeather() {
        const refreshBtn = document.getElementById('refreshBtn');
        refreshBtn.classList.add('spinning');
        try {
            await loadWeatherData();
        } finally {
            setTimeout(() => refreshBtn.classList.remove('spinning'), 1000);
        }
    }

    document.getElementById('locationInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchWeather();
        }
    });

    setInterval(() => {
        loadWeatherData();
    }, 600000); // 10 minutes

    loadWeatherData(); // Initial load
</script>
<script>
   document.addEventListener('DOMContentLoaded', function () {

    let index = 0;
    let totalSlides = {{ count($sliderImages) }}; // original count
    let track = document.getElementById('sliderTrack');

    function slideImages() {
        index++;
        track.style.transition = "transform 1s ease-in-out";
        track.style.transform = `translateX(-${index * 100}%)`;

        // last duplicate slide pe pohnchya pachi reset
        if (index === totalSlides) {
            setTimeout(() => {
                track.style.transition = "none"; // animation band
                track.style.transform = `translateX(0%)`;
                index = 0;
            }, 1000); // same as transition time
        }
    }

    setInterval(slideImages, 3000);

});

</script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('graph.dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Project Scheduling')}}</li>
@endsection
@section('content')
    <div class="navigation-view">
        <!-- Navigation Bar -->
     @php
      function generateColor($value) {
    $hue = hexdec(substr(md5($value), 0, 2));
    return "hsl($hue, 65%, 55%)";
    }
    
    $statusLabels = [
        'in_progress' => 'In Progress',
        'complete' => 'Complete',
        'canceled' => 'Canceled',
        'on_hold' => 'On Hold',
    ];

    $statusText = isset($project->status) && isset($statusLabels[$project->status])
        ? $statusLabels[$project->status]
        : null;

    $line1 = [];
    $line2 = [];

    if (!empty($project->description)) {
        $line1[] = $project->description;
    }

    if (!empty($project->site_address)) {
        $line1[] = $project->site_address;
    }

    if (!empty($project->budget)) {
        $line2[] = $project->budget . ' INR Budget';
    }

    if (!empty($statusText)) {
        $line2[] = $statusText;
    }

    $backgroundUrl = !empty($project->project_image)
        ? asset('storage/' . $project->project_image)
        : asset('images/default-banner.jpg'); // fallback image
@endphp

<!--<div class="project-banner" style="background-image: url('{{ $backgroundUrl }}');">-->
<!--    <div class="banner-overlay"></div>-->
<!--    <div class="banner-content" style="display: flex; align-items: center; gap: 15px;">-->

        <!-- Project Logo -->
<!--        <div class="project-logo">-->
<!--            {{-- <img src="{{ $project->project_image ? asset('storage/' . $project->project_image) : asset('default-logo.png') }}"-->
<!--                 alt="Project Logo"-->
<!--                 class="rounded-circle border border-white"-->
<!--                 style="width: 100px; height: 100px; object-fit: cover;"> --}}-->

<!--            <img src="{{ isset($project) && $project->project_image ? asset('storage/' . $project->project_image) : asset('default-logo.png') }}"-->
<!--     alt="Project Logo"-->
<!--     class="rounded-circle border border-white"-->
<!--     style="width: 100px; height: 100px; object-fit: cover;">-->

<!--        </div>-->

        <!-- Project Info -->
<!--        <div class="project-info">-->
<!--            <h1 class="project-title mb-1">{{ $project->project_name ?? 'Project Name' }}</h1>-->

<!--        @if(count($line1))-->
<!--                <p class="project-meta mb-1">{!! implode(' | ', $line1) !!}</p>-->
<!--        @endif-->

<!--        @if(count($line2))-->
<!--                <p class="project-meta" style="margin-top:-8px">{!! implode(' | ', $line2) !!}</p>-->
<!--        @endif-->
<!--        </div>-->

<!--    </div>-->
<!--</div>-->
<div class="top-section">

    <!-- LEFT → Project Banner -->
    <!--<div class="project-banner" style="background-image: url('{{ $backgroundUrl }}');">-->
    <div class="project-banner" id="projectBanner">
         <div class="slider-track" id="sliderTrack">
                @foreach($sliderImages as $img)
        <div class="slide" style="background-image: url('{{ $img }}')"></div>
    @endforeach

    <!-- ⭐ duplicate first image -->
    @if(count($sliderImages) > 0)
        <div class="slide" style="background-image: url('{{ $sliderImages[0] }}')"></div>
    @endif
            </div>
            
        <div class="banner-overlay"></div>

        <div class="banner-content">
            <div style="display:flex; align-items:center; gap:15px;">

                <div class="project-logo">
                    <img src="{{ isset($project) && $project->project_image ? asset('storage/' . $project->project_image) : asset('default-logo.png') }}"
                         class="rounded-circle border border-white"
                         style="width:100px; height:100px; object-fit:cover;">
                </div>

                <div class="project-info">
                    <h1 class="project-title mb-1">{{ $project->project_name ?? 'Project Name' }}</h1>

                    @if(count($line1))
                        <p class="project-meta mb-1">{!! implode(' | ', $line1) !!}</p>
                    @endif

                    @if(count($line2))
                        <p class="project-meta">{!! implode(' | ', $line2) !!}</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- RIGHT → Weather -->
    <div class="weather-side">
            
                <!-- Search (Banner) -->
  
        <div class="search-bar" style="display:none;">
            <input type="text"
                   class="search-input"
                   id="locationInput"
                   placeholder="Search for a city">

            <button class="search-btn" onclick="searchWeather('banner')">
                <i class="fas fa-search"></i>
            </button>
        </div>
    
            
        <div class="weather-card" id="todayCard">
            <div class="card-header">Today Weather</div>
            <div class="na-state">N/A</div>
        </div>

        <div class="weather-card" id="tomorrowCard">
            <div class="card-header">Tomorrow Weather</div>
            <div class="na-state">N/A</div>
        </div>

    </div>

</div>

        <!-- Stats Section -->
      @php
    use Carbon\Carbon;
@endphp

<!-- Stats Section -->
<div class="row mb-4 gy-3">
 <div class="weather-container">
        <!--<div class="search-section">-->
        <!--    <div class="search-bar" style="display:none;">-->
        <!--        <input type="text" class="search-input" id="locationInput" placeholder="Search for a city or location">-->
        <!--        <button class="search-btn" onclick="searchWeather()">-->
        <!--            <i class="fas fa-search"></i>-->
        <!--        </button>-->
        <!--    </div>-->
        <!--</div>-->

        <!--<div class="weather-cards">-->
        <!--    <div class="weather-card" id="todayCardS">-->
        <!--        <div class="card-header">Today Weather</div>-->
        <!--        <div class="na-state">N/A</div>-->
        <!--    </div>-->

        <!--    <div class="weather-card" id="tomorrowCardS">-->
        <!--        <div class="card-header">Tomorrow Weather</div>-->
        <!--        <div class="na-state">N/A</div>-->
        <!--    </div>-->

        <!--    <div class="weather-card" id="dayAfterCard">-->
        <!--        <div class="card-header">Day After Tomorrow</div>-->
        <!--        <div class="na-state">N/A</div>-->
        <!--    </div>-->

        <!--    <div class="weather-card" id="nextDayCard">-->
        <!--        <div class="card-header">Next to Next Day</div>-->
        <!--        <div class="na-state">N/A</div>-->
        <!--    </div>-->
        <!--</div>-->

        <div class="hourly-forecast" style="display:none;">
            <div class="section-title">Hourly Forecast</div>
            <div class="hourly-grid" id="hourlyGrid">
                <div class="loading-state">
                    <i class="fas fa-clock"></i>
                    <p>Loading hourly data...</p>
                </div>
            </div>
        </div>
    </div>

    <!--<button class="refresh-btn" id="refreshBtn" onclick="refreshWeather()">-->
    <!--    <i class="fas fa-sync-alt"></i>-->
    <!--</button>-->

</div>


        <!-- Main Content Grid -->
        <div class="content-grid">

        <div class="card h-100">

            <div class="card-header d-flex justify-content-between align-items-center py-2">

                <h6 class="mb-0 d-flex align-items-center gap-2">
                    <i class="ti ti-calendar"></i>
                    {{ __('Project Milestone Timeline (Gantt)') }}
                </h6>


                <form method="GET" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="lat" value="{{ request('lat') }}">
                    <input type="hidden" name="lon" value="{{ request('lon') }}">

                    <label class="small mb-0">Year</label>

                    <select name="year" class="form-select form-select-sm" style="width: 120px;" onchange="this.form.submit()">

                        @php
                        $currentYear = request('year', date('Y'));
                        @endphp

                        @for($y = date('Y') - 2; $y <= date('Y') + 8; $y++) <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>
                            {{ $y }}
                            </option>
                            @endfor

                    </select>

                    {{-- ✅ TYPES OF WORK FILTER --}}
                    <label class="small mb-0">Type</label>

                    <select name="work_type" class="form-select form-select-sm" style="width: 160px;" onchange="this.form.submit()">

                        <option value="">All Types</option>

                        @foreach($home_data['work_types'] as $type)
                        <option value="{{ $type->id }}" {{ request('work_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>

                </form>

                {{-- <div class="d-flex gap-3 small">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:10px;height:10px;background:#22c55e;border-radius:3px;"></div> Done
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:10px;height:10px;background:#0ea5e9;border-radius:3px;"></div> In Progress
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:10px;height:10px;background:#94a3b8;border-radius:3px;"></div> Planned
                    </div>
                </div> --}}
            </div>


            <div class="gantt-wrapper">

                {{-- HEADER --}}
                <div class="gantt-group gantt-header">
                    <div>TYPES OF WORK</div>
                    <div>PHASE ({{ $home_data['selected_year'] }})</div>
                    <div>PLANNED</div>
                    <div>ACTUAL</div>
                    <div>DELAY</div>

                    <div class="text-center">JAN</div>
                    <div class="text-center">FEB</div>
                    <div class="text-center">MAR</div>
                    <div class="text-center">APR</div>
                    <div class="text-center">MAY</div>
                    <div class="text-center">JUN</div>
                    <div class="text-center">JUL</div>
                    <div class="text-center">AUG</div>
                    <div class="text-center">SEP</div>
                    <div class="text-center">OCT</div>
                    <div class="text-center">NOV</div>
                    <div class="text-center">DEC</div>
                </div>

                {{-- BODY --}}
                @foreach($home_data['gantt'] as $group)

                @php
                $works = $group['works'];
                $totalRows = count($works);
                @endphp

                <div class="gantt-group" style="grid-template-rows: repeat({{ $totalRows }}, 42px);">

                    @foreach($works as $index => $work)

                    @php
                    $currentCategory = optional($work->mainCategory)->name;
                    $row = $index + 1;
                    @endphp

                    {{-- ✅ CATEGORY CELL (MERGED) --}}
                    @if($index === 0)
                    <div class="phase-category" style="
                            background: {{ generateColor($currentCategory) }};
                            grid-row: 1 / span {{ max(2, $totalRows) }};
                         ">
                        {{ $currentCategory }}
                    </div>
                    @endif

                    {{-- WORK NAME --}}
                    <div class="phase-title" style="grid-row: {{ $row }}">
                        {{ $work->name }}
                    </div>

                    {{-- PLANNED --}}
                    <div class="gantt-days" style="grid-row: {{ $row }}">
                        {{ $work->planned_days }} Day
                    </div>

                    {{-- ACTUAL --}}
                    <div class="gantt-days" style="grid-row: {{ $row }}">
                        {{ $work->actual_days }} Day
                    </div>

                    {{-- DELAY --}}
                    <div class="gantt-days" style="grid-row: {{ $row }}">
                        {{ $work->delay_days }} Day
                    </div>

                    {{-- PROGRESS BAR --}}
                    <div style="
                        grid-row: {{ $row }};
                        grid-column: {{ $work->startMonth + 5 }} / span {{ $work->span }};
                     ">

                        <div class="gantt-progress-wrapper">

                            <div class="gantt-progress-bar" style="
                                width: {{ $work->progress_percent }}%;
                                background: {{ generateColor($currentCategory) }};
                             ">
                            </div>

                            <span class="gantt-progress-label">

                                {{ $work->progress_percent }}% –

                                @if($work->progress_percent >= 100)
                                Done
                                @elseif($work->progress_percent > 0)
                                In Progress
                                @else
                                Planned
                                @endif

                            </span>

                        </div>
                    </div>

                    @endforeach

                </div>

                <div class="category-divider"></div>

                @endforeach

            </div>

        </div>
    </div>

<!--<div class="content-grid">-->
            <!-- Analytics Dashboard -->
<!--            <div class="card analytics">-->
<!--                <div class="card-header">-->
<!--                    <div class="card-icon">📊</div>-->
<!--                    <div class="card-title">Analytics</div>-->
<!--                </div>-->

<!--                <div class="card-content">-->
<!--                    <a href="{{ route('graph.dashboard') }}" class="card-content text-decoration-none text-dark">-->

<!--                    <div class="list-item">-->
<!--                        <div class="list-icon">📈</div>-->
<!--                        <div class="list-content">-->
<!--                            <div class="list-title">Performance Metrics</div>-->
<!--                            <div class="list-subtitle">Real-time insights</div>-->
<!--                        </div>-->
<!--                        <div class="list-chevron">›</div>-->
<!--                    </div>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->

            <!-- CRM System -->
<!--            <div class="card crm">-->
<!--                <div class="card-header">-->
<!--                    <div class="card-icon">👥</div>-->
<!--                    <div class="card-title">Project Document And Report</div>-->
<!--                </div>-->
<!--                <div class="card-content">-->
<!--    <div class="category-list d-flex flex-column gap-3">-->

<!--        {{-- Leads --}}-->
<!--        <a href="{{ route('project.document.index') }}" class="category-item text-decoration-none text-dark">-->
<!--            <div class="category-item-icon">🎯</div>-->
<!--            <div class="category-item-title">Project Documents</div>-->
<!--        </a>-->

<!--        {{-- Deals --}}-->
<!--        <a href="{{ route('material-testing-reports.index') }}" class="category-item text-decoration-none text-dark">-->
<!--            <div class="category-item-icon">🤝</div>-->
<!--            <div class="category-item-title">Material Testing Reports</div>-->
<!--        </a>-->

<!--    </div>-->
<!--</div>-->

<!--            </div>-->

            <!-- Project Management -->
<!--            <div class="card projects">-->
<!--    <div class="card-header">-->
<!--        <div class="card-icon">🏗️</div>-->
<!--        <div class="card-title">Projects</div>-->
<!--    </div>-->
<!--    <div class="card-content">-->
<!--        <div class="list-section">-->

<!--            {{-- Project Overview --}}-->
<!--            <a href="{{ route('projects.index') }}" class="list-item text-decoration-none text-dark">-->
<!--                <div class="list-icon">📋</div>-->
<!--                <div class="list-content">-->
<!--                    <div class="list-title">Project Overview</div>-->
<!--                </div>-->
<!--                <div class="list-chevron">›</div>-->
<!--            </a>-->

<!--            {{-- Technical Drawings --}}-->
<!--            <a href="{{ route('drawings.index') }}" class="list-item text-decoration-none text-dark">-->
<!--                <div class="list-icon">📐</div>-->
<!--                <div class="list-content">-->
<!--                    <div class="list-title">Work Drawings</div>-->
<!--                </div>-->
<!--                <div class="list-chevron">›</div>-->
<!--            </a>-->

<!--            {{-- Cost Estimation --}}-->
<!--            <a href="{{ route('billOfQuantity.index') }}" class="list-item text-decoration-none text-dark">-->
<!--                <div class="list-icon">💰</div>-->
<!--                <div class="list-content">-->
<!--                    <div class="list-title">Bill Of Quantity</div>-->
<!--                </div>-->
<!--                <div class="list-chevron">›</div>-->
<!--            </a>-->

<!--        </div>-->
<!--    </div>-->
<!--</div>-->


            <!-- Equipment Management -->
<!--            <div class="card equipment">-->
<!--    <div class="card-header">-->
<!--        <div class="card-icon">🚜</div>-->
<!--        <div class="card-title">Equipment</div>-->
<!--    </div>-->
<!--    <div class="card-content">-->
<!--        <div class="category-grid">-->

<!--            {{-- Fleet --}}-->
<!--            <a href="{{ route('equipment.index') }}" class="category-item text-decoration-none text-dark">-->
<!--                <div class="category-item-icon">🔧</div>-->
<!--                <div class="category-item-title">Add Equipment</div>-->
<!--            </a>-->

<!--            {{-- Usage --}}-->
<!--            <a href="{{ route('equipment.history.index') }}" class="category-item text-decoration-none text-dark">-->
<!--                <div class="category-item-icon">📊</div>-->
<!--                <div class="category-item-title">Usage</div>-->
<!--            </a>-->

<!--            {{-- Schedule --}}-->
<!--            <a href="{{ route('equipment.report.index') }}" class="category-item text-decoration-none text-dark">-->
<!--                <div class="category-item-icon">📅</div>-->
<!--                <div class="category-item-title">Schedule</div>-->
<!--            </a>-->

<!--        </div>-->

<!--        {{-- View Equipment Button --}}-->
<!--        <a href="{{ route('equipment.history.index') }}" class="button button-primary mt-3 d-block text-center" style="width: 100%; background:radial-gradient(at top center, rgba(51, 51, 127, 1) 0%, rgba(2, 2, 79, 1) 100%);">-->
<!--            View Equipment-->
<!--        </a>-->
<!--    </div>-->
<!--</div>-->

            <!-- Materials -->
<!--            <div class="card materials">-->
<!--    <div class="card-header">-->
<!--        <div class="card-icon">📦</div>-->
<!--        <div class="card-title">Materials</div>-->
<!--    </div>-->
<!--    <div class="card-content">-->
<!--        <div class="list-section">-->

<!--            {{-- Attribute --}}-->
<!--            <a href="{{ route('attribute.index') }}" class="list-item text-decoration-none text-dark">-->
<!--                <div class="list-icon">🆕</div>-->
<!--                <div class="list-content">-->
<!--                    <div class="list-title">Add Materials</div>-->
<!--                </div>-->
<!--                <div class="list-chevron">›</div>-->
<!--            </a>-->

<!--            {{-- Specifications --}}-->
<!--            <a href="{{ route('material-category.index') }}" class="list-item text-decoration-none text-dark">-->
<!--                <div class="list-icon">🏷️</div>-->
<!--                <div class="list-content">-->
<!--                    <div class="list-title">Specifications</div>-->
<!--                    <div class="list-subtitle">Material database</div>-->
<!--                </div>-->
<!--                <div class="list-chevron">›</div>-->
<!--            </a>-->

<!--            {{-- Inventory --}}-->
<!--            <a href="{{ route('material-analysis.index') }}" class="list-item text-decoration-none text-dark">-->
<!--                <div class="list-icon">📋</div>-->
<!--                <div class="list-content">-->
<!--                    <div class="list-title">Inventory</div>-->
<!--                    <div class="list-subtitle">Stock management</div>-->
<!--                </div>-->
<!--                <div class="list-chevron">›</div>-->
<!--            </a>-->

<!--        </div>-->
<!--    </div>-->
<!--</div>-->


            <!-- Reports -->
<!--            <div class="card reports">-->
<!--    <div class="card-header">-->
<!--        <div class="card-icon">📊</div>-->
<!--        <div class="card-title">Reports</div>-->
<!--    </div>-->
<!--    <div class="card-content">-->
<!--        <div class="category-grid">-->

<!--            {{-- Daily --}}-->
<!--            <a href="{{ route('daily-report.index') }}" class="category-item text-decoration-none text-dark">-->
<!--                <div class="category-item-icon">📁</div>-->
<!--                <div class="category-item-title">Daily</div>-->
<!--            </a>-->

<!--            {{-- Site (unchanged for now) --}}-->
<!--            <div class="category-item">-->
<!--                <div class="category-item-icon">🏢</div>-->
<!--                <div class="category-item-title">Site</div>-->
<!--            </div>-->

<!--            {{-- Workforce --}}-->
<!--            <a href="{{ route('man-power.index') }}" class="category-item text-decoration-none text-dark">-->
<!--                <div class="category-item-icon">👷</div>-->
<!--                <div class="category-item-title">Workforce</div>-->
<!--            </a>-->

<!--            {{-- Progress --}}-->
<!--            <a href="{{ route('all-report.index') }}" class="category-item text-decoration-none text-dark">-->
<!--                <div class="category-item-icon">📏</div>-->
<!--                <div class="category-item-title">Progress</div>-->
<!--            </a>-->

<!--        </div>-->
<!--    </div>-->
<!--</div>-->


            <!-- Issues -->
<!--            <div class="card issues">-->
<!--    <div class="card-header">-->
<!--        <div class="card-icon">⚠️</div>-->
<!--        <div class="card-title">Issues</div>-->
<!--    </div>-->
<!--    <div class="card-content">-->

<!--        <a href="{{ route('work-issue.index') }}" class="list-item text-decoration-none text-dark">-->
<!--            <div class="list-icon">🚨</div>-->
<!--            <div class="list-content">-->
<!--                <div class="list-title">Work Issues</div>-->
<!--            </div>-->
<!--            <div class="list-chevron">›</div>-->
<!--        </a>-->

<!--    </div>-->
<!--</div>-->


            <!-- Gallery -->
<!--            <div class="card gallery">-->
<!--                <div class="card-header">-->
<!--                    <div class="card-icon">📸</div>-->
<!--                    <div class="card-title">Gallery</div>-->
<!--                </div>-->
<!--                <div class="card-content">-->
<!--        <a href="{{ route('site-gallery.index') }}" class="list-item text-decoration-none text-dark">-->
<!--                        <div class="list-icon">🖼️</div>-->
<!--                        <div class="list-content">-->
<!--                            <div class="list-title">Project Photos</div>-->
<!--                            <div class="list-subtitle">Visual documentation</div>-->
<!--                        </div>-->
<!--                        <div class="list-chevron">›</div>-->
<!--        </a>-->
<!--                    </div>-->
<!--            </div>-->

            <!-- Team Management -->
<!--            <div class="card users">-->
<!--    <div class="card-header">-->
<!--        <div class="card-icon">👤</div>-->
<!--        <div class="card-title">Team</div>-->
<!--    </div>-->
<!--    <div class="card-content">-->
<!--        <div class="category-grid">-->

<!--            {{-- Engineers --}}-->
<!--            <a href="{{ route('users.index') }}" class="category-item text-decoration-none text-dark">-->
<!--                <div class="category-item-icon">🔧</div>-->
<!--                <div class="category-item-title">Engineers</div>-->
<!--            </a>-->

<!--            {{-- Clients --}}-->
<!--            <a href="{{ route('clients.index') }}" class="category-item text-decoration-none text-dark">-->
<!--                <div class="category-item-icon">🏢</div>-->
<!--                <div class="category-item-title">Clients</div>-->
<!--            </a>-->

<!--        </div>-->
<!--    </div>-->
<!--</div>-->


            <!-- Settings -->
<!--            <div class="card settings">-->
<!--                <div class="card-header">-->
<!--                    <div class="card-icon">⚙️</div>-->
<!--                    <div class="card-title">Settings</div>-->
<!--                </div>-->
<!--                <div class="card-content">-->
<!--        <a href="{{ route('plans.index') }}" class="list-item text-decoration-none text-dark">-->
<!--                        <div class="list-icon">💳</div>-->
<!--                        <div class="list-content">-->
<!--                            <div class="list-title">Subscription</div>-->
<!--                            <div class="list-subtitle">Manage billing</div>-->
<!--                        </div>-->
<!--                        <div class="list-chevron">›</div>-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

    </div>

    <div class="modal fade" id="ClientModal" tabindex="-1" aria-labelledby="ClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ClientModalLabel">{{ __('Add Client') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Would you like to add a Client now?') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('No') }}</button>
                    <button type="button" class="btn btn-primary" onclick="handleClientChoice('yes')">{{ __('Yes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
