(function () {
    'use strict';

    var header = document.getElementById('site-header');
    var menuButton = document.getElementById('menu-btn');
    var mobileMenu = document.getElementById('mobile-menu');

    if (header && menuButton && mobileMenu) {
        var openIcon = menuButton.querySelector('.menu-open');
        var closeIcon = menuButton.querySelector('.menu-close');
        var setMenu = function (open) {
            mobileMenu.classList.toggle('hidden', !open);
            if (openIcon) {
                openIcon.classList.toggle('hidden', open);
            }
            if (closeIcon) {
                closeIcon.classList.toggle('hidden', !open);
            }
            menuButton.setAttribute('aria-expanded', String(open));
        };

        menuButton.addEventListener('click', function () {
            setMenu(mobileMenu.classList.contains('hidden'));
        });

        mobileMenu.addEventListener('click', function (event) {
            if (event.target instanceof HTMLElement && event.target.closest('a')) {
                setMenu(false);
            }
        });

        document.addEventListener('click', function (event) {
            if (event.target instanceof Node && !header.contains(event.target)) {
                setMenu(false);
            }
        });

        var onScroll = function () {
            header.classList.toggle('shadow-header', window.scrollY > 40);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    document.querySelectorAll('[data-set-lang]').forEach(function (element) {
        element.addEventListener('click', function () {
            try {
                localStorage.setItem('sc-lang', element.getAttribute('data-set-lang') || 'en');
            } catch (error) {
                return;
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (!(event.target instanceof HTMLElement)) {
            return;
        }

        var tracked = event.target.closest('[data-track]');

        if (!tracked) {
            return;
        }

        var eventName = tracked.getAttribute('data-track');

        if (!eventName) {
            return;
        }

        if (typeof window.gtag === 'function') {
            window.gtag('event', eventName);
        }

        if (typeof window.fbq === 'function') {
            window.fbq('trackCustom', eventName);
        }
    });

    if ('IntersectionObserver' in window) {
        var revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('in-view');
                revealObserver.unobserve(entry.target);
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.reveal').forEach(function (element) {
            revealObserver.observe(element);
        });
    }

    var toBanglaNumber = function (value) {
        return value.replace(/\d/g, function (digit) {
            return '০১২৩৪৫৬৭৮৯'[Number(digit)];
        });
    };

    var animateCounter = function (element) {
        var end = Number(element.getAttribute('data-end'));
        var finalValue = element.getAttribute('data-final') || '';
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!element.getAttribute('data-end') || Number.isNaN(end) || reduceMotion) {
            element.textContent = finalValue;
            return;
        }

        var useBangla = element.getAttribute('data-bn') === '1';
        var duration = 1400;
        var start = performance.now();

        var tick = function (now) {
            var progress = Math.min((now - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var value = String(Math.round(end * eased));
            element.textContent = useBangla ? toBanglaNumber(value) : value;

            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                element.textContent = finalValue;
            }
        };

        requestAnimationFrame(tick);
    };

    var counters = document.querySelectorAll('.counter');

    if (counters.length && 'IntersectionObserver' in window) {
        var counterObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            });
        }, { threshold: 0.4 });

        counters.forEach(function (element) {
            counterObserver.observe(element);
        });
    }

    document.querySelectorAll('[data-tabs]').forEach(function (root) {
        var tabs = root.querySelectorAll('[data-tab]');
        var panels = root.querySelectorAll('[data-panel]');

        var activate = function (id, toggle) {
            panels.forEach(function (panel) {
                var isCurrent = panel.getAttribute('data-panel') === id;
                var shouldCollapse = toggle && isCurrent && !panel.classList.contains('hidden');
                panel.classList.toggle('hidden', shouldCollapse || !isCurrent);
            });

            tabs.forEach(function (tab) {
                var isCurrent = tab.getAttribute('data-tab') === id;
                tab.setAttribute('aria-selected', String(isCurrent));
                tab.tabIndex = isCurrent ? 0 : -1;
                tab.classList.toggle('bg-navy', isCurrent);
                tab.classList.toggle('text-gold-light', isCurrent);
                tab.classList.toggle('bg-white', !isCurrent);
                tab.classList.toggle('text-navy', !isCurrent);
                tab.classList.toggle('border', !isCurrent);
                tab.classList.toggle('border-b-0', !isCurrent);
                tab.classList.toggle('border-line', !isCurrent);
            });
        };

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activate(tab.getAttribute('data-tab'), false);
            });
        });

        root.querySelectorAll('[data-acc]').forEach(function (accordion) {
            accordion.addEventListener('click', function () {
                activate(accordion.getAttribute('data-acc'), true);
            });
        });
    });

    document.querySelectorAll('[data-lm-tabs]').forEach(function (root) {
        var tabs = root.querySelectorAll('[data-lmtab]');
        var panels = root.querySelectorAll('[data-lmpanel]');

        var activate = function (id) {
            panels.forEach(function (panel) {
                panel.classList.toggle('hidden', panel.getAttribute('data-lmpanel') !== id);
            });

            tabs.forEach(function (tab) {
                var isCurrent = tab.getAttribute('data-lmtab') === id;
                tab.setAttribute('aria-selected', String(isCurrent));
                tab.tabIndex = isCurrent ? 0 : -1;
                tab.classList.toggle('bg-navy', isCurrent);
                tab.classList.toggle('text-gold-light', isCurrent);
                tab.classList.toggle('border', !isCurrent);
                tab.classList.toggle('border-line', !isCurrent);
                tab.classList.toggle('bg-white', !isCurrent);
                tab.classList.toggle('text-navy', !isCurrent);
            });
        };

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activate(tab.getAttribute('data-lmtab'));
            });
        });
    });

    var mapShell = document.getElementById('map-shell');

    if (mapShell) {
        var loaded = false;
        var loadMap = function () {
            if (loaded) {
                return;
            }

            loaded = true;

            var iframe = document.createElement('iframe');
            iframe.src = mapShell.getAttribute('data-map-src') || '';
            iframe.title = document.documentElement.getAttribute('data-site-language') === 'bn'
                ? 'সাউথ সিটি প্রকল্পের লোকেশন ম্যাপ'
                : 'South City project location map';
            iframe.loading = 'lazy';
            iframe.referrerPolicy = 'no-referrer-when-downgrade';
            iframe.className = 'absolute inset-0 h-full w-full border-0';
            iframe.setAttribute('allowfullscreen', '');
            mapShell.appendChild(iframe);

            var button = document.getElementById('map-load');

            if (button) {
                button.remove();
            }
        };

        var mapLoadButton = document.getElementById('map-load');

        if (mapLoadButton) {
            mapLoadButton.addEventListener('click', loadMap);
        }

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries, observer) {
                if (entries.some(function (entry) {
                    return entry.isIntersecting;
                })) {
                    loadMap();
                    observer.disconnect();
                }
            }, { rootMargin: '200px' }).observe(mapShell);
        }
    }

    var dialog = document.getElementById('lightbox');

    if (dialog instanceof HTMLDialogElement) {
        var image = document.getElementById('lightbox-img');
        var caption = document.getElementById('lightbox-caption');

        document.querySelectorAll('[data-lightbox]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (!(image instanceof HTMLImageElement) || !caption) {
                    return;
                }

                image.src = button.getAttribute('data-lightbox') || '';
                image.alt = button.getAttribute('data-caption') || '';
                caption.textContent = button.getAttribute('data-caption') || '';
                dialog.showModal();
            });
        });

        var closeButton = document.getElementById('lightbox-close');

        if (closeButton) {
            closeButton.addEventListener('click', function () {
                dialog.close();
            });
        }

        dialog.addEventListener('click', function (event) {
            if (event.target === dialog) {
                dialog.close();
            }
        });
    }

    var leadForm = document.getElementById('lead-form');

    if (leadForm instanceof HTMLFormElement) {
        var phone = document.getElementById('lf-phone');
        var phoneError = document.getElementById('lf-phone-err');
        var errorBox = document.getElementById('lf-error');
        var submitButton = document.getElementById('lf-submit');

        var normalizePhone = function (value) {
            return value
                .replace(/[০-৯]/g, function (digit) {
                    return String('০১২৩৪৫৬৭৮৯'.indexOf(digit));
                })
                .replace(/[\s-]/g, '');
        };

        var isValidPhone = function (value) {
            return /^(?:\+?880|0)1[3-9]\d{8}$/.test(normalizePhone(value));
        };

        leadForm.addEventListener('submit', function (event) {
            if (!(phone instanceof HTMLInputElement)) {
                return;
            }

            var phoneIsValid = isValidPhone(phone.value);

            if (phoneError) {
                phoneError.classList.toggle('hidden', phoneIsValid);
            }

            if (!phoneIsValid) {
                event.preventDefault();

                if (errorBox) {
                    errorBox.classList.remove('hidden');
                }

                if (submitButton instanceof HTMLButtonElement) {
                    submitButton.disabled = false;
                }
            }
        });

        var whatsappButton = document.getElementById('lf-wa');

        if (whatsappButton) {
            whatsappButton.addEventListener('click', function () {
                var language = document.documentElement.getAttribute('data-site-language') || 'en';
                var name = document.getElementById('lf-name');
                var plot = document.getElementById('lf-plot');
                var message = document.getElementById('lf-msg');
                var whatsapp = leadForm.getAttribute('data-wa') || '';
                var nameValue = name instanceof HTMLInputElement ? name.value.trim() : '';
                var plotValue = plot instanceof HTMLSelectElement ? plot.value : '';
                var messageValue = message instanceof HTMLInputElement ? message.value.trim() : '';
                var text = language === 'bn'
                    ? ('আসসালামু আলাইকুম, আমি ' + (nameValue || '') + (plotValue ? ', ' + plotValue + ' প্লটে আগ্রহী। ' : ', সাউথ সিটির প্লটে আগ্রহী। ') + messageValue).trim()
                    : ('Assalamu Alaikum, I am ' + (nameValue || 'interested') + (plotValue ? ', interested in a ' + plotValue + ' plot. ' : ' in South City plots. ') + messageValue).trim();

                window.open('https://wa.me/' + whatsapp + '?text=' + encodeURIComponent(text), '_blank', 'noopener');
            });
        }
    }
})();
