document.addEventListener('DOMContentLoaded', function () {
    var navItems = document.querySelectorAll('.nav-item');
    var joinBtn = document.getElementById('joinBtn');

    // Bottom navigation active state
    navItems.forEach(function (item) {
        item.addEventListener('click', function () {
            navItems.forEach(function (el) {
                el.classList.remove('active');
            });
            item.classList.add('active');

            var target = item.getAttribute('data-target');
            if (target === 'masuk' && joinBtn) {
                window.location.href = joinBtn.dataset.href || '#';
            }
        });
    });

    // Daftar / Masuk button
    if (joinBtn) {
        joinBtn.addEventListener('click', function () {
            window.location.href = joinBtn.dataset.href || '/login';
        });
    }

    // Reveal-on-scroll animation for sections
    var revealTargets = document.querySelectorAll(
        '.why-section, .app-showcase, .join-section, .quote-section'
    );

    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15 }
        );

        revealTargets.forEach(function (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(16px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(el);
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener(
        'transitionend',
        function (e) {
            if (e.target.classList && e.target.classList.contains('is-visible')) {
                e.target.style.opacity = '1';
                e.target.style.transform = 'none';
            }
        },
        true
    );
});

// Apply visible state via class toggle (separated for clarity)
var style = document.createElement('style');
style.textContent = '.is-visible { opacity: 1 !important; transform: none !important; }';
document.head.appendChild(style);