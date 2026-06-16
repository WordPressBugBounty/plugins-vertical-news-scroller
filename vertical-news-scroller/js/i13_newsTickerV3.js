/*
  Vertical News Ticker v3 - i13 Web Solution
  Lightweight, dependency-free (besides jQuery) vertical scroller.
  Designed to be a reliable fallback when vTicker (v1) / vTickerV2 (v2)
  run into edge cases on certain themes/browsers.

  Usage:
    jQuery('#news-container').vtickerv3({
        speed: 1700,      // animation duration in ms
        pause: 5000,      // delay between ticks in ms
        height: 200,      // fixed height of the viewport (0 = auto, uses first item height)
        direction: 'up',  // 'up' or 'down'
        mousePause: true  // pause scrolling while mouse is over the widget
    });

  Public API (via data attribute):
    jQuery('#news-container').data('vtickerv3').stop();
    jQuery('#news-container').data('vtickerv3').start();
    jQuery('#news-container').data('vtickerv3').setDirection('down');
*/
(function ($) {

    $.fn.vtickerv3 = function (options) {

        var settings = $.extend({
            speed: 1700,
            pause: 5000,
            height: 0,
            direction: 'up',
            mousePause: true,
            mode: 'tick',
            scrollamount: 1,
            delay: 60
        }, options);

        return this.each(function () {

            var $wrap  = $(this);
            var $list  = $wrap.children('ul').first();

            if (!$list.length) {
                return;
            }

            var $items = $list.children('li');

            if ($items.length < 1) {
                $wrap.css('visibility', 'visible');
                return;
            }

            // ----------------------------------------------------------
            // MODE: continuous (marquee replacement)
            // Smooth pixel-by-pixel scroll, loops seamlessly, pauses on
            // hover. Use this for s_type="classic" instead of <marquee>.
            // ----------------------------------------------------------
            if (settings.mode === 'continuous') {

                var wrapHeight = settings.height > 0
                    ? settings.height
                    : $items.first().outerHeight(true);

                if (!wrapHeight || wrapHeight <= 0) {
                    wrapHeight = 150;
                }

                $wrap.css({
                    overflow: 'hidden',
                    position: 'relative',
                    height: wrapHeight + 'px'
                });

                $list.css({
                    margin: 0,
                    padding: 0,
                    position: 'relative',
                    top: 0
                });

                // Duplicate items once so the scroll can loop seamlessly.
                if (!$list.data('vtickerv3-cloned')) {
                    $items.each(function () {
                        $list.append($(this).clone(true));
                    });
                    $list.data('vtickerv3-cloned', true);
                }

                var contentHeight = $list.outerHeight() / 2;

                // Mirror <marquee>'s scrolldelay/scrollamount semantics:
                // every `delay` ms, move `scrollamount` px. Convert that
                // into a px-per-millisecond rate for smooth rAF animation.
                var delayMs       = Math.max(parseFloat(settings.delay) || 60, 1);
                var amountPx      = Math.max(parseFloat(settings.scrollamount) || 1, 0.2);
                var pxPerMs       = amountPx / delayMs;

                var paused        = false;
                var pos           = 0;
                var dir           = (settings.direction === 'down') ? 'down' : 'up';
                var rafId         = null;
                var lastTs        = null;

                function frame(ts) {
                    if (lastTs === null) {
                        lastTs = ts;
                    }
                    var elapsed = ts - lastTs;
                    lastTs = ts;

                    if (!paused) {
                        var delta = pxPerMs * elapsed;

                        if (dir === 'up') {
                            pos -= delta;
                            if (Math.abs(pos) >= contentHeight) {
                                pos += contentHeight;
                            }
                        } else {
                            pos += delta;
                            if (pos >= contentHeight) {
                                pos -= contentHeight;
                            }
                        }
                        $list.css('transform', 'translateY(' + pos + 'px)');
                    }
                    rafId = requestAnimationFrame(frame);
                }

                if (settings.mousePause) {
                    $wrap.on('mouseenter.vtickerv3', function () {
                        paused = true;
                    }).on('mouseleave.vtickerv3', function () {
                        paused = false;
                    });
                }

                $wrap.css('visibility', 'visible');

                // Start at the bottom edge if scrolling "down" so content
                // enters from above, matching marquee direction="down".
                if (dir === 'down') {
                    pos = -contentHeight;
                    $list.css('transform', 'translateY(' + pos + 'px)');
                }

                rafId = requestAnimationFrame(frame);

                $wrap.data('vtickerv3', {
                    start: function () { paused = false; },
                    stop: function () {
                        paused = true;
                        if (rafId) { cancelAnimationFrame(rafId); }
                    },
                    setDirection: function (d) {
                        dir = (d === 'down') ? 'down' : 'up';
                    }
                });

                return;
            }

            // ----------------------------------------------------------
            // MODE: tick (default) - one item slides at a time
            // ----------------------------------------------------------
            if ($items.length < 2) {
                $wrap.css('visibility', 'visible');
                return;
            }

            // "height" is the overall visible box height (may show multiple
            // items at once, like a normal vTicker). If not provided, fall
            // back to a single item's natural height.
            var naturalItemHeight = $items.first().outerHeight(true) || 50;
            var boxHeight = settings.height > 0 ? settings.height : naturalItemHeight;

            // Lock layout: container clips to boxHeight, items keep their
            // natural height and stack normally inside it.
            $wrap.css({
                overflow: 'hidden',
                position: 'relative',
                height: boxHeight + 'px'
            });

            $list.css({
                margin: 0,
                padding: 0,
                position: 'relative',
                top: 0
            });

            $items.css({
                margin: 0,
                boxSizing: 'border-box'
            });

            var timer    = null;
            var paused   = false;
            var animating = false;
            var direction = (settings.direction === 'down') ? 'down' : 'up';

            function tick() {

                if (paused || animating) {
                    return;
                }

                animating = true;

                if (direction === 'down') {

                    var $last     = $list.children('li').last();
                    var lastH     = $last.outerHeight(true);
                    var $clone    = $last.clone(true);

                    $list.prepend($clone);
                    $list.css('margin-top', '-' + lastH + 'px');

                    $list.animate({ marginTop: 0 }, settings.speed, function () {
                        $list.children('li').last().remove();
                        animating = false;
                    });

                } else {

                    var firstH = $list.children('li').first().outerHeight(true);

                    $list.animate({ marginTop: '-' + firstH + 'px' }, settings.speed, function () {
                        $list.children('li').first().appendTo($list);
                        $list.css('margin-top', 0);
                        animating = false;
                    });
                }
            }

            function start() {
                stop();
                timer = setInterval(tick, settings.pause);
            }

            function stop() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            if (settings.mousePause) {
                $wrap.on('mouseenter.vtickerv3', function () {
                    paused = true;
                }).on('mouseleave.vtickerv3', function () {
                    paused = false;
                });
            }

            // Make visible (the PHP template hides the container until JS is ready)
            $wrap.css('visibility', 'visible');

            start();

            $wrap.data('vtickerv3', {
                start: start,
                stop: stop,
                setDirection: function (dir) {
                    direction = (dir === 'down') ? 'down' : 'up';
                }
            });
        });
    };

})(jQuery);
