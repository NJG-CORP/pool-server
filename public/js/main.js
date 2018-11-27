/**
 * jquery-match-height master by @liabru
 * http://brm.io/jquery-match-height/
 * License: MIT
 */
var map;
var markers = [];

function initMap() {
    map = new google.maps.Map(document.getElementById('map1'), {
        zoom: 12,
        center: {lat: 45.040458, lng: 38.981979}
    });

    $.ajax({
        url: "/clubs/markers",
        method: "POST",
        success: function (response) {
            var image = '/img/interface/balloon.png';
            var centerCoords = {
                lat: 0,
                lng: 0
            };
            var count = 0;
            $.each(response, function (index,marker) {
                centerCoords['lat'] += parseFloat(marker['lat']);
                centerCoords['lng'] += parseFloat(marker['lng']);
                var markerGoogle = new google.maps.Marker({
                    position: {lat: parseFloat(marker['lat']), lng: parseFloat(marker['lng'])},
                    icon: image,
                    map: map
                });
                markers.push(markerGoogle);
                count++;
                markerGoogle.addListener('click', function(){
                    window.location = marker['url'];
                });
            });

            centerCoords['lat'] = centerCoords['lat']/count;
            centerCoords['lng'] = centerCoords['lng']/count;

            map.setCenter(centerCoords);
        },
        dataType: "JSON"
    });
}

;(function (factory) { // eslint-disable-line no-extra-semi
    'use strict';
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery'], factory);
    } else if (typeof module !== 'undefined' && module.exports) {
        // CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Global
        factory(jQuery);
    }
})(function ($) {
    /*
    *  internal
    */

    var _previousResizeWidth = -1,
        _updateTimeout = -1;

    /*
    *  _parse
    *  value parse utility function
    */

    var _parse = function (value) {
        // parse value and convert NaN to 0
        return parseFloat(value) || 0;
    };

    /*
    *  _rows
    *  utility function returns array of jQuery selections representing each row
    *  (as displayed after float wrapping applied by browser)
    */

    var _rows = function (elements) {
        var tolerance = 1,
            $elements = $(elements),
            lastTop = null,
            rows = [];

        // group elements by their top position
        $elements.each(function () {
            var $that = $(this),
                top = $that.offset().top - _parse($that.css('margin-top')),
                lastRow = rows.length > 0 ? rows[rows.length - 1] : null;

            if (lastRow === null) {
                // first item on the row, so just push it
                rows.push($that);
            } else {
                // if the row top is the same, add to the row group
                if (Math.floor(Math.abs(lastTop - top)) <= tolerance) {
                    rows[rows.length - 1] = lastRow.add($that);
                } else {
                    // otherwise start a new row group
                    rows.push($that);
                }
            }

            // keep track of the last row top
            lastTop = top;
        });

        return rows;
    };

    /*
    *  _parseOptions
    *  handle plugin options
    */

    var _parseOptions = function (options) {
        var opts = {
            byRow: true,
            property: 'height',
            target: null,
            remove: false
        };

        if (typeof options === 'object') {
            return $.extend(opts, options);
        }

        if (typeof options === 'boolean') {
            opts.byRow = options;
        } else if (options === 'remove') {
            opts.remove = true;
        }

        return opts;
    };

    /*
    *  matchHeight
    *  plugin definition
    */

    var matchHeight = $.fn.matchHeight = function (options) {
        var opts = _parseOptions(options);

        // handle remove
        if (opts.remove) {
            var that = this;

            // remove fixed height from all selected elements
            this.css(opts.property, '');

            // remove selected elements from all groups
            $.each(matchHeight._groups, function (key, group) {
                group.elements = group.elements.not(that);
            });

            // TODO: cleanup empty groups

            return this;
        }

        if (this.length <= 1 && !opts.target) {
            return this;
        }

        // keep track of this group so we can re-apply later on load and resize events
        matchHeight._groups.push({
            elements: this,
            options: opts
        });

        // match each element's height to the tallest element in the selection
        matchHeight._apply(this, opts);

        return this;
    };

    /*
    *  plugin global options
    */

    matchHeight.version = 'master';
    matchHeight._groups = [];
    matchHeight._throttle = 80;
    matchHeight._maintainScroll = false;
    matchHeight._beforeUpdate = null;
    matchHeight._afterUpdate = null;
    matchHeight._rows = _rows;
    matchHeight._parse = _parse;
    matchHeight._parseOptions = _parseOptions;

    /*
    *  matchHeight._apply
    *  apply matchHeight to given elements
    */

    matchHeight._apply = function (elements, options) {
        var opts = _parseOptions(options),
            $elements = $(elements),
            rows = [$elements];

        // take note of scroll position
        var scrollTop = $(window).scrollTop(),
            htmlHeight = $('html').outerHeight(true);

        // get hidden parents
        var $hiddenParents = $elements.parents().filter(':hidden');

        // cache the original inline style
        $hiddenParents.each(function () {
            var $that = $(this);
            $that.data('style-cache', $that.attr('style'));
        });

        // temporarily must force hidden parents visible
        $hiddenParents.css('display', 'block');

        // get rows if using byRow, otherwise assume one row
        if (opts.byRow && !opts.target) {

            // must first force an arbitrary equal height so floating elements break evenly
            $elements.each(function () {
                var $that = $(this),
                    display = $that.css('display');

                // temporarily force a usable display value
                if (display !== 'inline-block' && display !== 'flex' && display !== 'inline-flex') {
                    display = 'block';
                }

                // cache the original inline style
                $that.data('style-cache', $that.attr('style'));

                $that.css({
                    'display': display,
                    'padding-top': '0',
                    'padding-bottom': '0',
                    'margin-top': '0',
                    'margin-bottom': '0',
                    'border-top-width': '0',
                    'border-bottom-width': '0',
                    'height': '100px',
                    'overflow': 'hidden'
                });
            });

            // get the array of rows (based on element top position)
            rows = _rows($elements);

            // revert original inline styles
            $elements.each(function () {
                var $that = $(this);
                $that.attr('style', $that.data('style-cache') || '');
            });
        }

        $.each(rows, function (key, row) {
            var $row = $(row),
                targetHeight = 0;

            if (!opts.target) {
                // skip apply to rows with only one item
                if (opts.byRow && $row.length <= 1) {
                    $row.css(opts.property, '');
                    return;
                }

                // iterate the row and find the max height
                $row.each(function () {
                    var $that = $(this),
                        style = $that.attr('style'),
                        display = $that.css('display');

                    // temporarily force a usable display value
                    if (display !== 'inline-block' && display !== 'flex' && display !== 'inline-flex') {
                        display = 'block';
                    }

                    // ensure we get the correct actual height (and not a previously set height value)
                    var css = {'display': display};
                    css[opts.property] = '';
                    $that.css(css);

                    // find the max height (including padding, but not margin)
                    if ($that.outerHeight(false) > targetHeight) {
                        targetHeight = $that.outerHeight(false);
                    }

                    // revert styles
                    if (style) {
                        $that.attr('style', style);
                    } else {
                        $that.css('display', '');
                    }
                });
            } else {
                // if target set, use the height of the target element
                targetHeight = opts.target.outerHeight(false);
            }

            // iterate the row and apply the height to all elements
            $row.each(function () {
                var $that = $(this),
                    verticalPadding = 0;

                // don't apply to a target
                if (opts.target && $that.is(opts.target)) {
                    return;
                }

                // handle padding and border correctly (required when not using border-box)
                if ($that.css('box-sizing') !== 'border-box') {
                    verticalPadding += _parse($that.css('border-top-width')) + _parse($that.css('border-bottom-width'));
                    verticalPadding += _parse($that.css('padding-top')) + _parse($that.css('padding-bottom'));
                }

                // set the height (accounting for padding and border)
                $that.css(opts.property, (targetHeight - verticalPadding) + 'px');
            });
        });

        // revert hidden parents
        $hiddenParents.each(function () {
            var $that = $(this);
            $that.attr('style', $that.data('style-cache') || null);
        });

        // restore scroll position if enabled
        if (matchHeight._maintainScroll) {
            $(window).scrollTop((scrollTop / htmlHeight) * $('html').outerHeight(true));
        }

        return this;
    };

    /*
    *  matchHeight._applyDataApi
    *  applies matchHeight to all elements with a data-match-height attribute
    */

    matchHeight._applyDataApi = function () {
        var groups = {};

        // generate groups by their groupId set by elements using data-match-height
        $('[data-match-height], [data-mh]').each(function () {
            var $this = $(this),
                groupId = $this.attr('data-mh') || $this.attr('data-match-height');

            if (groupId in groups) {
                groups[groupId] = groups[groupId].add($this);
            } else {
                groups[groupId] = $this;
            }
        });

        // apply matchHeight to each group
        $.each(groups, function () {
            this.matchHeight(true);
        });
    };

    /*
    *  matchHeight._update
    *  updates matchHeight on all current groups with their correct options
    */

    var _update = function (event) {
        if (matchHeight._beforeUpdate) {
            matchHeight._beforeUpdate(event, matchHeight._groups);
        }

        $.each(matchHeight._groups, function () {
            matchHeight._apply(this.elements, this.options);
        });

        if (matchHeight._afterUpdate) {
            matchHeight._afterUpdate(event, matchHeight._groups);
        }
    };

    matchHeight._update = function (throttle, event) {
        // prevent update if fired from a resize event
        // where the viewport width hasn't actually changed
        // fixes an event looping bug in IE8
        if (event && event.type === 'resize') {
            var windowWidth = $(window).width();
            if (windowWidth === _previousResizeWidth) {
                return;
            }
            _previousResizeWidth = windowWidth;
        }

        // throttle updates
        if (!throttle) {
            _update(event);
        } else if (_updateTimeout === -1) {
            _updateTimeout = setTimeout(function () {
                _update(event);
                _updateTimeout = -1;
            }, matchHeight._throttle);
        }
    };

    /*
    *  bind events
    */

    // apply on DOM ready event
    $(matchHeight._applyDataApi);

    // use on or bind where supported
    var on = $.fn.on ? 'on' : 'bind';

    // update heights on load and resize events
    $(window)[on]('load', function (event) {
        matchHeight._update(false, event);
    });

    // throttled update heights on resize events
    $(window)[on]('resize orientationchange', function (event) {
        matchHeight._update(true, event);
    });

});


/*!
 * fancyBox - jQuery Plugin
 * version: 2.1.5 (Fri, 14 Jun 2013)
 * @requires jQuery v1.6 or later
 *
 * Examples at http://fancyapps.com/fancybox/
 * License: www.fancyapps.com/fancybox/#license
 *
 * Copyright 2012 Janis Skarnelis - janis@fancyapps.com
 *
 */

(function (window, document, $, undefined) {
    "use strict";

    var H = $("html"),
        W = $(window),
        D = $(document),
        F = $.fancybox = function () {
            F.open.apply(this, arguments);
        },
        IE = navigator.userAgent.match(/msie/i),
        didUpdate = null,
        isTouch = document.createTouch !== undefined,

        isQuery = function (obj) {
            return obj && obj.hasOwnProperty && obj instanceof $;
        },
        isString = function (str) {
            return str && $.type(str) === "string";
        },
        isPercentage = function (str) {
            return isString(str) && str.indexOf('%') > 0;
        },
        isScrollable = function (el) {
            return (el && !(el.style.overflow && el.style.overflow === 'hidden') && ((el.clientWidth && el.scrollWidth > el.clientWidth) || (el.clientHeight && el.scrollHeight > el.clientHeight)));
        },
        getScalar = function (orig, dim) {
            var value = parseInt(orig, 10) || 0;

            if (dim && isPercentage(orig)) {
                value = F.getViewport()[dim] / 100 * value;
            }

            return Math.ceil(value);
        },
        getValue = function (value, dim) {
            return getScalar(value, dim) + 'px';
        };

    $.extend(F, {
        // The current version of fancyBox
        version: '2.1.5',

        defaults: {
            padding: 15,
            margin: 20,

            width: 800,
            height: 600,
            minWidth: 100,
            minHeight: 100,
            maxWidth: 9999,
            maxHeight: 9999,
            pixelRatio: 1, // Set to 2 for retina display support

            autoSize: true,
            autoHeight: false,
            autoWidth: false,

            autoResize: true,
            autoCenter: !isTouch,
            fitToView: true,
            aspectRatio: false,
            topRatio: 0.5,
            leftRatio: 0.5,

            scrolling: 'auto', // 'auto', 'yes' or 'no'
            wrapCSS: '',

            arrows: true,
            closeBtn: true,
            closeClick: false,
            nextClick: false,
            mouseWheel: true,
            autoPlay: false,
            playSpeed: 3000,
            preload: 3,
            modal: false,
            loop: true,

            ajax: {
                dataType: 'html',
                headers: {'X-fancyBox': true}
            },
            iframe: {
                scrolling: 'auto',
                preload: true
            },
            swf: {
                wmode: 'transparent',
                allowfullscreen: 'true',
                allowscriptaccess: 'always'
            },

            keys: {
                next: {
                    13: 'left', // enter
                    34: 'up',   // page down
                    39: 'left', // right arrow
                    40: 'up'    // down arrow
                },
                prev: {
                    8: 'right',  // backspace
                    33: 'down',   // page up
                    37: 'right',  // left arrow
                    38: 'down'    // up arrow
                },
                close: [27], // escape key
                play: [32], // space - start/stop slideshow
                toggle: [70]  // letter "f" - toggle fullscreen
            },

            direction: {
                next: 'left',
                prev: 'right'
            },

            scrollOutside: true,

            // Override some properties
            index: 0,
            type: null,
            href: null,
            content: null,
            title: null,

            // HTML templates
            tpl: {
                wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
                image: '<img class="fancybox-image" src="{href}" alt="" />',
                iframe: '<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen' + (IE ? ' allowtransparency="true"' : '') + '></iframe>',
                error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
                closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
                next: '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
                prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
            },

            // Properties for each animation type
            // Opening fancyBox
            openEffect: 'fade', // 'elastic', 'fade' or 'none'
            openSpeed: 250,
            openEasing: 'swing',
            openOpacity: true,
            openMethod: 'zoomIn',

            // Closing fancyBox
            closeEffect: 'fade', // 'elastic', 'fade' or 'none'
            closeSpeed: 250,
            closeEasing: 'swing',
            closeOpacity: true,
            closeMethod: 'zoomOut',

            // Changing next gallery item
            nextEffect: 'elastic', // 'elastic', 'fade' or 'none'
            nextSpeed: 250,
            nextEasing: 'swing',
            nextMethod: 'changeIn',

            // Changing previous gallery item
            prevEffect: 'elastic', // 'elastic', 'fade' or 'none'
            prevSpeed: 250,
            prevEasing: 'swing',
            prevMethod: 'changeOut',

            // Enable default helpers
            helpers: {
                overlay: true,
                title: true
            },

            // Callbacks
            onCancel: $.noop, // If canceling
            beforeLoad: $.noop, // Before loading
            afterLoad: $.noop, // After loading
            beforeShow: $.noop, // Before changing in current item
            afterShow: $.noop, // After opening
            beforeChange: $.noop, // Before changing gallery item
            beforeClose: $.noop, // Before closing
            afterClose: $.noop  // After closing
        },

        //Current state
        group: {}, // Selected group
        opts: {}, // Group options
        previous: null,  // Previous element
        coming: null,  // Element being loaded
        current: null,  // Currently loaded element
        isActive: false, // Is activated
        isOpen: false, // Is currently open
        isOpened: false, // Have been fully opened at least once

        wrap: null,
        skin: null,
        outer: null,
        inner: null,

        player: {
            timer: null,
            isActive: false
        },

        // Loaders
        ajaxLoad: null,
        imgPreload: null,

        // Some collections
        transitions: {},
        helpers: {},

        /*
		 *	Static methods
		 */

        open: function (group, opts) {
            if (!group) {
                return;
            }

            if (!$.isPlainObject(opts)) {
                opts = {};
            }

            // Close if already active
            if (false === F.close(true)) {
                return;
            }

            // Normalize group
            if (!$.isArray(group)) {
                group = isQuery(group) ? $(group).get() : [group];
            }

            // Recheck if the type of each element is `object` and set content type (image, ajax, etc)
            $.each(group, function (i, element) {
                var obj = {},
                    href,
                    title,
                    content,
                    type,
                    rez,
                    hrefParts,
                    selector;

                if ($.type(element) === "object") {
                    // Check if is DOM element
                    if (element.nodeType) {
                        element = $(element);
                    }

                    if (isQuery(element)) {
                        obj = {
                            href: element.data('fancybox-href') || element.attr('href'),
                            title: element.data('fancybox-title') || element.attr('title'),
                            isDom: true,
                            element: element
                        };

                        if ($.metadata) {
                            $.extend(true, obj, element.metadata());
                        }

                    } else {
                        obj = element;
                    }
                }

                href = opts.href || obj.href || (isString(element) ? element : null);
                title = opts.title !== undefined ? opts.title : obj.title || '';

                content = opts.content || obj.content;
                type = content ? 'html' : (opts.type || obj.type);

                if (!type && obj.isDom) {
                    type = element.data('fancybox-type');

                    if (!type) {
                        rez = element.prop('class').match(/fancybox\.(\w+)/);
                        type = rez ? rez[1] : null;
                    }
                }

                if (isString(href)) {
                    // Try to guess the content type
                    if (!type) {
                        if (F.isImage(href)) {
                            type = 'image';

                        } else if (F.isSWF(href)) {
                            type = 'swf';

                        } else if (href.charAt(0) === '#') {
                            type = 'inline';

                        } else if (isString(element)) {
                            type = 'html';
                            content = element;
                        }
                    }

                    // Split url into two pieces with source url and content selector, e.g,
                    // "/mypage.html #my_id" will load "/mypage.html" and display element having id "my_id"
                    if (type === 'ajax') {
                        hrefParts = href.split(/\s+/, 2);
                        href = hrefParts.shift();
                        selector = hrefParts.shift();
                    }
                }

                if (!content) {
                    if (type === 'inline') {
                        if (href) {
                            content = $(isString(href) ? href.replace(/.*(?=#[^\s]+$)/, '') : href); //strip for ie7

                        } else if (obj.isDom) {
                            content = element;
                        }

                    } else if (type === 'html') {
                        content = href;

                    } else if (!type && !href && obj.isDom) {
                        type = 'inline';
                        content = element;
                    }
                }

                $.extend(obj, {
                    href: href,
                    type: type,
                    content: content,
                    title: title,
                    selector: selector
                });

                group[i] = obj;
            });

            // Extend the defaults
            F.opts = $.extend(true, {}, F.defaults, opts);

            // All options are merged recursive except keys
            if (opts.keys !== undefined) {
                F.opts.keys = opts.keys ? $.extend({}, F.defaults.keys, opts.keys) : false;
            }

            F.group = group;

            return F._start(F.opts.index);
        },

        // Cancel image loading or abort ajax request
        cancel: function () {
            var coming = F.coming;

            if (!coming || false === F.trigger('onCancel')) {
                return;
            }

            F.hideLoading();

            if (F.ajaxLoad) {
                F.ajaxLoad.abort();
            }

            F.ajaxLoad = null;

            if (F.imgPreload) {
                F.imgPreload.onload = F.imgPreload.onerror = null;
            }

            if (coming.wrap) {
                coming.wrap.stop(true, true).trigger('onReset').remove();
            }

            F.coming = null;

            // If the first item has been canceled, then clear everything
            if (!F.current) {
                F._afterZoomOut(coming);
            }
        },

        // Start closing animation if is open; remove immediately if opening/closing
        close: function (event) {
            F.cancel();

            if (false === F.trigger('beforeClose')) {
                return;
            }

            F.unbindEvents();

            if (!F.isActive) {
                return;
            }

            if (!F.isOpen || event === true) {
                $('.fancybox-wrap').stop(true).trigger('onReset').remove();

                F._afterZoomOut();

            } else {
                F.isOpen = F.isOpened = false;
                F.isClosing = true;

                $('.fancybox-item, .fancybox-nav').remove();

                F.wrap.stop(true, true).removeClass('fancybox-opened');

                F.transitions[F.current.closeMethod]();
            }
        },

        // Manage slideshow:
        //   $.fancybox.play(); - toggle slideshow
        //   $.fancybox.play( true ); - start
        //   $.fancybox.play( false ); - stop
        play: function (action) {
            var clear = function () {
                    clearTimeout(F.player.timer);
                },
                set = function () {
                    clear();

                    if (F.current && F.player.isActive) {
                        F.player.timer = setTimeout(F.next, F.current.playSpeed);
                    }
                },
                stop = function () {
                    clear();

                    D.unbind('.player');

                    F.player.isActive = false;

                    F.trigger('onPlayEnd');
                },
                start = function () {
                    if (F.current && (F.current.loop || F.current.index < F.group.length - 1)) {
                        F.player.isActive = true;

                        D.bind({
                            'onCancel.player beforeClose.player': stop,
                            'onUpdate.player': set,
                            'beforeLoad.player': clear
                        });

                        set();

                        F.trigger('onPlayStart');
                    }
                };

            if (action === true || (!F.player.isActive && action !== false)) {
                start();
            } else {
                stop();
            }
        },

        // Navigate to next gallery item
        next: function (direction) {
            var current = F.current;

            if (current) {
                if (!isString(direction)) {
                    direction = current.direction.next;
                }

                F.jumpto(current.index + 1, direction, 'next');
            }
        },

        // Navigate to previous gallery item
        prev: function (direction) {
            var current = F.current;

            if (current) {
                if (!isString(direction)) {
                    direction = current.direction.prev;
                }

                F.jumpto(current.index - 1, direction, 'prev');
            }
        },

        // Navigate to gallery item by index
        jumpto: function (index, direction, router) {
            var current = F.current;

            if (!current) {
                return;
            }

            index = getScalar(index);

            F.direction = direction || current.direction[(index >= current.index ? 'next' : 'prev')];
            F.router = router || 'jumpto';

            if (current.loop) {
                if (index < 0) {
                    index = current.group.length + (index % current.group.length);
                }

                index = index % current.group.length;
            }

            if (current.group[index] !== undefined) {
                F.cancel();

                F._start(index);
            }
        },

        // Center inside viewport and toggle position type to fixed or absolute if needed
        reposition: function (e, onlyAbsolute) {
            var current = F.current,
                wrap = current ? current.wrap : null,
                pos;

            if (wrap) {
                pos = F._getPosition(onlyAbsolute);

                if (e && e.type === 'scroll') {
                    delete pos.position;

                    wrap.stop(true, true).animate(pos, 200);

                } else {
                    wrap.css(pos);

                    current.pos = $.extend({}, current.dim, pos);
                }
            }
        },

        update: function (e) {
            var type = (e && e.type),
                anyway = !type || type === 'orientationchange';

            if (anyway) {
                clearTimeout(didUpdate);

                didUpdate = null;
            }

            if (!F.isOpen || didUpdate) {
                return;
            }

            didUpdate = setTimeout(function () {
                var current = F.current;

                if (!current || F.isClosing) {
                    return;
                }

                F.wrap.removeClass('fancybox-tmp');

                if (anyway || type === 'load' || (type === 'resize' && current.autoResize)) {
                    F._setDimension();
                }

                if (!(type === 'scroll' && current.canShrink)) {
                    F.reposition(e);
                }

                F.trigger('onUpdate');

                didUpdate = null;

            }, (anyway && !isTouch ? 0 : 300));
        },

        // Shrink content to fit inside viewport or restore if resized
        toggle: function (action) {
            if (F.isOpen) {
                F.current.fitToView = $.type(action) === "boolean" ? action : !F.current.fitToView;

                // Help browser to restore document dimensions
                if (isTouch) {
                    F.wrap.removeAttr('style').addClass('fancybox-tmp');

                    F.trigger('onUpdate');
                }

                F.update();
            }
        },

        hideLoading: function () {
            D.unbind('.loading');

            $('#fancybox-loading').remove();
        },

        showLoading: function () {
            var el, viewport;

            F.hideLoading();

            el = $('<div id="fancybox-loading"><div></div></div>').click(F.cancel).appendTo('body');

            // If user will press the escape-button, the request will be canceled
            D.bind('keydown.loading', function (e) {
                if ((e.which || e.keyCode) === 27) {
                    e.preventDefault();

                    F.cancel();
                }
            });

            if (!F.defaults.fixed) {
                viewport = F.getViewport();

                el.css({
                    position: 'absolute',
                    top: (viewport.h * 0.5) + viewport.y,
                    left: (viewport.w * 0.5) + viewport.x
                });
            }
        },

        getViewport: function () {
            var locked = (F.current && F.current.locked) || false,
                rez = {
                    x: W.scrollLeft(),
                    y: W.scrollTop()
                };

            if (locked) {
                rez.w = locked[0].clientWidth;
                rez.h = locked[0].clientHeight;

            } else {
                // See http://bugs.jquery.com/ticket/6724
                rez.w = isTouch && window.innerWidth ? window.innerWidth : W.width();
                rez.h = isTouch && window.innerHeight ? window.innerHeight : W.height();
            }

            return rez;
        },

        // Unbind the keyboard / clicking actions
        unbindEvents: function () {
            if (F.wrap && isQuery(F.wrap)) {
                F.wrap.unbind('.fb');
            }

            D.unbind('.fb');
            W.unbind('.fb');
        },

        bindEvents: function () {
            var current = F.current,
                keys;

            if (!current) {
                return;
            }

            // Changing document height on iOS devices triggers a 'resize' event,
            // that can change document height... repeating infinitely
            W.bind('orientationchange.fb' + (isTouch ? '' : ' resize.fb') + (current.autoCenter && !current.locked ? ' scroll.fb' : ''), F.update);

            keys = current.keys;

            if (keys) {
                D.bind('keydown.fb', function (e) {
                    var code = e.which || e.keyCode,
                        target = e.target || e.srcElement;

                    // Skip esc key if loading, because showLoading will cancel preloading
                    if (code === 27 && F.coming) {
                        return false;
                    }

                    // Ignore key combinations and key events within form elements
                    if (!e.ctrlKey && !e.altKey && !e.shiftKey && !e.metaKey && !(target && (target.type || $(target).is('[contenteditable]')))) {
                        $.each(keys, function (i, val) {
                            if (current.group.length > 1 && val[code] !== undefined) {
                                F[i](val[code]);

                                e.preventDefault();
                                return false;
                            }

                            if ($.inArray(code, val) > -1) {
                                F[i]();

                                e.preventDefault();
                                return false;
                            }
                        });
                    }
                });
            }

            if ($.fn.mousewheel && current.mouseWheel) {
                F.wrap.bind('mousewheel.fb', function (e, delta, deltaX, deltaY) {
                    var target = e.target || null,
                        parent = $(target),
                        canScroll = false;

                    while (parent.length) {
                        if (canScroll || parent.is('.fancybox-skin') || parent.is('.fancybox-wrap')) {
                            break;
                        }

                        canScroll = isScrollable(parent[0]);
                        parent = $(parent).parent();
                    }

                    if (delta !== 0 && !canScroll) {
                        if (F.group.length > 1 && !current.canShrink) {
                            if (deltaY > 0 || deltaX > 0) {
                                F.prev(deltaY > 0 ? 'down' : 'left');

                            } else if (deltaY < 0 || deltaX < 0) {
                                F.next(deltaY < 0 ? 'up' : 'right');
                            }

                            e.preventDefault();
                        }
                    }
                });
            }
        },

        trigger: function (event, o) {
            var ret, obj = o || F.coming || F.current;

            if (!obj) {
                return;
            }

            if ($.isFunction(obj[event])) {
                ret = obj[event].apply(obj, Array.prototype.slice.call(arguments, 1));
            }

            if (ret === false) {
                return false;
            }

            if (obj.helpers) {
                $.each(obj.helpers, function (helper, opts) {
                    if (opts && F.helpers[helper] && $.isFunction(F.helpers[helper][event])) {
                        F.helpers[helper][event]($.extend(true, {}, F.helpers[helper].defaults, opts), obj);
                    }
                });
            }

            D.trigger(event);
        },

        isImage: function (str) {
            return isString(str) && str.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg)((\?|#).*)?$)/i);
        },

        isSWF: function (str) {
            return isString(str) && str.match(/\.(swf)((\?|#).*)?$/i);
        },

        _start: function (index) {
            var coming = {},
                obj,
                href,
                type,
                margin,
                padding;

            index = getScalar(index);
            obj = F.group[index] || null;

            if (!obj) {
                return false;
            }

            coming = $.extend(true, {}, F.opts, obj);

            // Convert margin and padding properties to array - top, right, bottom, left
            margin = coming.margin;
            padding = coming.padding;

            if ($.type(margin) === 'number') {
                coming.margin = [margin, margin, margin, margin];
            }

            if ($.type(padding) === 'number') {
                coming.padding = [padding, padding, padding, padding];
            }

            // 'modal' propery is just a shortcut
            if (coming.modal) {
                $.extend(true, coming, {
                    closeBtn: false,
                    closeClick: false,
                    nextClick: false,
                    arrows: false,
                    mouseWheel: false,
                    keys: null,
                    helpers: {
                        overlay: {
                            closeClick: false
                        }
                    }
                });
            }

            // 'autoSize' property is a shortcut, too
            if (coming.autoSize) {
                coming.autoWidth = coming.autoHeight = true;
            }

            if (coming.width === 'auto') {
                coming.autoWidth = true;
            }

            if (coming.height === 'auto') {
                coming.autoHeight = true;
            }

            /*
			 * Add reference to the group, so it`s possible to access from callbacks, example:
			 * afterLoad : function() {
			 *     this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
			 * }
			 */

            coming.group = F.group;
            coming.index = index;

            // Give a chance for callback or helpers to update coming item (type, title, etc)
            F.coming = coming;

            if (false === F.trigger('beforeLoad')) {
                F.coming = null;

                return;
            }

            type = coming.type;
            href = coming.href;

            if (!type) {
                F.coming = null;

                //If we can not determine content type then drop silently or display next/prev item if looping through gallery
                if (F.current && F.router && F.router !== 'jumpto') {
                    F.current.index = index;

                    return F[F.router](F.direction);
                }

                return false;
            }

            F.isActive = true;

            if (type === 'image' || type === 'swf') {
                coming.autoHeight = coming.autoWidth = false;
                coming.scrolling = 'visible';
            }

            if (type === 'image') {
                coming.aspectRatio = true;
            }

            if (type === 'iframe' && isTouch) {
                coming.scrolling = 'scroll';
            }

            // Build the neccessary markup
            coming.wrap = $(coming.tpl.wrap).addClass('fancybox-' + (isTouch ? 'mobile' : 'desktop') + ' fancybox-type-' + type + ' fancybox-tmp ' + coming.wrapCSS).appendTo(coming.parent || 'body');

            $.extend(coming, {
                skin: $('.fancybox-skin', coming.wrap),
                outer: $('.fancybox-outer', coming.wrap),
                inner: $('.fancybox-inner', coming.wrap)
            });

            $.each(["Top", "Right", "Bottom", "Left"], function (i, v) {
                coming.skin.css('padding' + v, getValue(coming.padding[i]));
            });

            F.trigger('onReady');

            // Check before try to load; 'inline' and 'html' types need content, others - href
            if (type === 'inline' || type === 'html') {
                if (!coming.content || !coming.content.length) {
                    return F._error('content');
                }

            } else if (!href) {
                return F._error('href');
            }

            if (type === 'image') {
                F._loadImage();

            } else if (type === 'ajax') {
                F._loadAjax();

            } else if (type === 'iframe') {
                F._loadIframe();

            } else {
                F._afterLoad();
            }
        },

        _error: function (type) {
            $.extend(F.coming, {
                type: 'html',
                autoWidth: true,
                autoHeight: true,
                minWidth: 0,
                minHeight: 0,
                scrolling: 'no',
                hasError: type,
                content: F.coming.tpl.error
            });

            F._afterLoad();
        },

        _loadImage: function () {
            // Reset preload image so it is later possible to check "complete" property
            var img = F.imgPreload = new Image();

            img.onload = function () {
                this.onload = this.onerror = null;

                F.coming.width = this.width / F.opts.pixelRatio;
                F.coming.height = this.height / F.opts.pixelRatio;

                F._afterLoad();
            };

            img.onerror = function () {
                this.onload = this.onerror = null;

                F._error('image');
            };

            img.src = F.coming.href;

            if (img.complete !== true) {
                F.showLoading();
            }
        },

        _loadAjax: function () {
            var coming = F.coming;

            F.showLoading();

            F.ajaxLoad = $.ajax($.extend({}, coming.ajax, {
                url: coming.href,
                error: function (jqXHR, textStatus) {
                    if (F.coming && textStatus !== 'abort') {
                        F._error('ajax', jqXHR);

                    } else {
                        F.hideLoading();
                    }
                },
                success: function (data, textStatus) {
                    if (textStatus === 'success') {
                        coming.content = data;

                        F._afterLoad();
                    }
                }
            }));
        },

        _loadIframe: function () {
            var coming = F.coming,
                iframe = $(coming.tpl.iframe.replace(/\{rnd\}/g, new Date().getTime()))
                    .attr('scrolling', isTouch ? 'auto' : coming.iframe.scrolling)
                    .attr('src', coming.href);

            // This helps IE
            $(coming.wrap).bind('onReset', function () {
                try {
                    $(this).find('iframe').hide().attr('src', '//about:blank').end().empty();
                } catch (e) {
                }
            });

            if (coming.iframe.preload) {
                F.showLoading();

                iframe.one('load', function () {
                    $(this).data('ready', 1);

                    // iOS will lose scrolling if we resize
                    if (!isTouch) {
                        $(this).bind('load.fb', F.update);
                    }

                    // Without this trick:
                    //   - iframe won't scroll on iOS devices
                    //   - IE7 sometimes displays empty iframe
                    $(this).parents('.fancybox-wrap').width('100%').removeClass('fancybox-tmp').show();

                    F._afterLoad();
                });
            }

            coming.content = iframe.appendTo(coming.inner);

            if (!coming.iframe.preload) {
                F._afterLoad();
            }
        },

        _preloadImages: function () {
            var group = F.group,
                current = F.current,
                len = group.length,
                cnt = current.preload ? Math.min(current.preload, len - 1) : 0,
                item,
                i;

            for (i = 1; i <= cnt; i += 1) {
                item = group[(current.index + i) % len];

                if (item.type === 'image' && item.href) {
                    new Image().src = item.href;
                }
            }
        },

        _afterLoad: function () {
            var coming = F.coming,
                previous = F.current,
                placeholder = 'fancybox-placeholder',
                current,
                content,
                type,
                scrolling,
                href,
                embed;

            F.hideLoading();

            if (!coming || F.isActive === false) {
                return;
            }

            if (false === F.trigger('afterLoad', coming, previous)) {
                coming.wrap.stop(true).trigger('onReset').remove();

                F.coming = null;

                return;
            }

            if (previous) {
                F.trigger('beforeChange', previous);

                previous.wrap.stop(true).removeClass('fancybox-opened')
                    .find('.fancybox-item, .fancybox-nav')
                    .remove();
            }

            F.unbindEvents();

            current = coming;
            content = coming.content;
            type = coming.type;
            scrolling = coming.scrolling;

            $.extend(F, {
                wrap: current.wrap,
                skin: current.skin,
                outer: current.outer,
                inner: current.inner,
                current: current,
                previous: previous
            });

            href = current.href;

            switch (type) {
                case 'inline':
                case 'ajax':
                case 'html':
                    if (current.selector) {
                        content = $('<div>').html(content).find(current.selector);

                    } else if (isQuery(content)) {
                        if (!content.data(placeholder)) {
                            content.data(placeholder, $('<div class="' + placeholder + '"></div>').insertAfter(content).hide());
                        }

                        content = content.show().detach();

                        current.wrap.bind('onReset', function () {
                            if ($(this).find(content).length) {
                                content.hide().replaceAll(content.data(placeholder)).data(placeholder, false);
                            }
                        });
                    }
                    break;

                case 'image':
                    content = current.tpl.image.replace('{href}', href);
                    break;

                case 'swf':
                    content = '<object id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="' + href + '"></param>';
                    embed = '';

                    $.each(current.swf, function (name, val) {
                        content += '<param name="' + name + '" value="' + val + '"></param>';
                        embed += ' ' + name + '="' + val + '"';
                    });

                    content += '<embed src="' + href + '" type="application/x-shockwave-flash" width="100%" height="100%"' + embed + '></embed></object>';
                    break;
            }

            if (!(isQuery(content) && content.parent().is(current.inner))) {
                current.inner.append(content);
            }

            // Give a chance for helpers or callbacks to update elements
            F.trigger('beforeShow');

            // Set scrolling before calculating dimensions
            current.inner.css('overflow', scrolling === 'yes' ? 'scroll' : (scrolling === 'no' ? 'hidden' : scrolling));

            // Set initial dimensions and start position
            F._setDimension();

            F.reposition();

            F.isOpen = false;
            F.coming = null;

            F.bindEvents();

            if (!F.isOpened) {
                $('.fancybox-wrap').not(current.wrap).stop(true).trigger('onReset').remove();

            } else if (previous.prevMethod) {
                F.transitions[previous.prevMethod]();
            }

            F.transitions[F.isOpened ? current.nextMethod : current.openMethod]();

            F._preloadImages();
        },

        _setDimension: function () {
            var viewport = F.getViewport(),
                steps = 0,
                canShrink = false,
                canExpand = false,
                wrap = F.wrap,
                skin = F.skin,
                inner = F.inner,
                current = F.current,
                width = current.width,
                height = current.height,
                minWidth = current.minWidth,
                minHeight = current.minHeight,
                maxWidth = current.maxWidth,
                maxHeight = current.maxHeight,
                scrolling = current.scrolling,
                scrollOut = current.scrollOutside ? current.scrollbarWidth : 0,
                margin = current.margin,
                wMargin = getScalar(margin[1] + margin[3]),
                hMargin = getScalar(margin[0] + margin[2]),
                wPadding,
                hPadding,
                wSpace,
                hSpace,
                origWidth,
                origHeight,
                origMaxWidth,
                origMaxHeight,
                ratio,
                width_,
                height_,
                maxWidth_,
                maxHeight_,
                iframe,
                body;

            // Reset dimensions so we could re-check actual size
            wrap.add(skin).add(inner).width('auto').height('auto').removeClass('fancybox-tmp');

            wPadding = getScalar(skin.outerWidth(true) - skin.width());
            hPadding = getScalar(skin.outerHeight(true) - skin.height());

            // Any space between content and viewport (margin, padding, border, title)
            wSpace = wMargin + wPadding;
            hSpace = hMargin + hPadding;

            origWidth = isPercentage(width) ? (viewport.w - wSpace) * getScalar(width) / 100 : width;
            origHeight = isPercentage(height) ? (viewport.h - hSpace) * getScalar(height) / 100 : height;

            if (current.type === 'iframe') {
                iframe = current.content;

                if (current.autoHeight && iframe.data('ready') === 1) {
                    try {
                        if (iframe[0].contentWindow.document.location) {
                            inner.width(origWidth).height(9999);

                            body = iframe.contents().find('body');

                            if (scrollOut) {
                                body.css('overflow-x', 'hidden');
                            }

                            origHeight = body.outerHeight(true);
                        }

                    } catch (e) {
                    }
                }

            } else if (current.autoWidth || current.autoHeight) {
                inner.addClass('fancybox-tmp');

                // Set width or height in case we need to calculate only one dimension
                if (!current.autoWidth) {
                    inner.width(origWidth);
                }

                if (!current.autoHeight) {
                    inner.height(origHeight);
                }

                if (current.autoWidth) {
                    origWidth = inner.width();
                }

                if (current.autoHeight) {
                    origHeight = inner.height();
                }

                inner.removeClass('fancybox-tmp');
            }

            width = getScalar(origWidth);
            height = getScalar(origHeight);

            ratio = origWidth / origHeight;

            // Calculations for the content
            minWidth = getScalar(isPercentage(minWidth) ? getScalar(minWidth, 'w') - wSpace : minWidth);
            maxWidth = getScalar(isPercentage(maxWidth) ? getScalar(maxWidth, 'w') - wSpace : maxWidth);

            minHeight = getScalar(isPercentage(minHeight) ? getScalar(minHeight, 'h') - hSpace : minHeight);
            maxHeight = getScalar(isPercentage(maxHeight) ? getScalar(maxHeight, 'h') - hSpace : maxHeight);

            // These will be used to determine if wrap can fit in the viewport
            origMaxWidth = maxWidth;
            origMaxHeight = maxHeight;

            if (current.fitToView) {
                maxWidth = Math.min(viewport.w - wSpace, maxWidth);
                maxHeight = Math.min(viewport.h - hSpace, maxHeight);
            }

            maxWidth_ = viewport.w - wMargin;
            maxHeight_ = viewport.h - hMargin;

            if (current.aspectRatio) {
                if (width > maxWidth) {
                    width = maxWidth;
                    height = getScalar(width / ratio);
                }

                if (height > maxHeight) {
                    height = maxHeight;
                    width = getScalar(height * ratio);
                }

                if (width < minWidth) {
                    width = minWidth;
                    height = getScalar(width / ratio);
                }

                if (height < minHeight) {
                    height = minHeight;
                    width = getScalar(height * ratio);
                }

            } else {
                width = Math.max(minWidth, Math.min(width, maxWidth));

                if (current.autoHeight && current.type !== 'iframe') {
                    inner.width(width);

                    height = inner.height();
                }

                height = Math.max(minHeight, Math.min(height, maxHeight));
            }

            // Try to fit inside viewport (including the title)
            if (current.fitToView) {
                inner.width(width).height(height);

                wrap.width(width + wPadding);

                // Real wrap dimensions
                width_ = wrap.width();
                height_ = wrap.height();

                if (current.aspectRatio) {
                    while ((width_ > maxWidth_ || height_ > maxHeight_) && width > minWidth && height > minHeight) {
                        if (steps++ > 19) {
                            break;
                        }

                        height = Math.max(minHeight, Math.min(maxHeight, height - 10));
                        width = getScalar(height * ratio);

                        if (width < minWidth) {
                            width = minWidth;
                            height = getScalar(width / ratio);
                        }

                        if (width > maxWidth) {
                            width = maxWidth;
                            height = getScalar(width / ratio);
                        }

                        inner.width(width).height(height);

                        wrap.width(width + wPadding);

                        width_ = wrap.width();
                        height_ = wrap.height();
                    }

                } else {
                    width = Math.max(minWidth, Math.min(width, width - (width_ - maxWidth_)));
                    height = Math.max(minHeight, Math.min(height, height - (height_ - maxHeight_)));
                }
            }

            if (scrollOut && scrolling === 'auto' && height < origHeight && (width + wPadding + scrollOut) < maxWidth_) {
                width += scrollOut;
            }

            inner.width(width).height(height);

            wrap.width(width + wPadding);

            width_ = wrap.width();
            height_ = wrap.height();

            canShrink = (width_ > maxWidth_ || height_ > maxHeight_) && width > minWidth && height > minHeight;
            canExpand = current.aspectRatio ? (width < origMaxWidth && height < origMaxHeight && width < origWidth && height < origHeight) : ((width < origMaxWidth || height < origMaxHeight) && (width < origWidth || height < origHeight));

            $.extend(current, {
                dim: {
                    width: getValue(width_),
                    height: getValue(height_)
                },
                origWidth: origWidth,
                origHeight: origHeight,
                canShrink: canShrink,
                canExpand: canExpand,
                wPadding: wPadding,
                hPadding: hPadding,
                wrapSpace: height_ - skin.outerHeight(true),
                skinSpace: skin.height() - height
            });

            if (!iframe && current.autoHeight && height > minHeight && height < maxHeight && !canExpand) {
                inner.height('auto');
            }
        },

        _getPosition: function (onlyAbsolute) {
            var current = F.current,
                viewport = F.getViewport(),
                margin = current.margin,
                width = F.wrap.width() + margin[1] + margin[3],
                height = F.wrap.height() + margin[0] + margin[2],
                rez = {
                    position: 'absolute',
                    top: margin[0],
                    left: margin[3]
                };

            if (current.autoCenter && current.fixed && !onlyAbsolute && height <= viewport.h && width <= viewport.w) {
                rez.position = 'fixed';

            } else if (!current.locked) {
                rez.top += viewport.y;
                rez.left += viewport.x;
            }

            rez.top = getValue(Math.max(rez.top, rez.top + ((viewport.h - height) * current.topRatio)));
            rez.left = getValue(Math.max(rez.left, rez.left + ((viewport.w - width) * current.leftRatio)));

            return rez;
        },

        _afterZoomIn: function () {
            var current = F.current;

            if (!current) {
                return;
            }

            F.isOpen = F.isOpened = true;

            F.wrap.css('overflow', 'visible').addClass('fancybox-opened');

            F.update();

            // Assign a click event
            if (current.closeClick || (current.nextClick && F.group.length > 1)) {
                F.inner.css('cursor', 'pointer').bind('click.fb', function (e) {
                    if (!$(e.target).is('a') && !$(e.target).parent().is('a')) {
                        e.preventDefault();

                        F[current.closeClick ? 'close' : 'next']();
                    }
                });
            }

            // Create a close button
            if (current.closeBtn) {
                $(current.tpl.closeBtn).appendTo(F.skin).bind('click.fb', function (e) {
                    e.preventDefault();

                    F.close();
                });
            }

            // Create navigation arrows
            if (current.arrows && F.group.length > 1) {
                if (current.loop || current.index > 0) {
                    $(current.tpl.prev).appendTo(F.outer).bind('click.fb', F.prev);
                }

                if (current.loop || current.index < F.group.length - 1) {
                    $(current.tpl.next).appendTo(F.outer).bind('click.fb', F.next);
                }
            }

            F.trigger('afterShow');

            // Stop the slideshow if this is the last item
            if (!current.loop && current.index === current.group.length - 1) {
                F.play(false);

            } else if (F.opts.autoPlay && !F.player.isActive) {
                F.opts.autoPlay = false;

                F.play();
            }
        },

        _afterZoomOut: function (obj) {
            obj = obj || F.current;

            $('.fancybox-wrap').trigger('onReset').remove();

            $.extend(F, {
                group: {},
                opts: {},
                router: false,
                current: null,
                isActive: false,
                isOpened: false,
                isOpen: false,
                isClosing: false,
                wrap: null,
                skin: null,
                outer: null,
                inner: null
            });

            F.trigger('afterClose', obj);
        }
    });

    /*
	 *	Default transitions
	 */

    F.transitions = {
        getOrigPosition: function () {
            var current = F.current,
                element = current.element,
                orig = current.orig,
                pos = {},
                width = 50,
                height = 50,
                hPadding = current.hPadding,
                wPadding = current.wPadding,
                viewport = F.getViewport();

            if (!orig && current.isDom && element.is(':visible')) {
                orig = element.find('img:first');

                if (!orig.length) {
                    orig = element;
                }
            }

            if (isQuery(orig)) {
                pos = orig.offset();

                if (orig.is('img')) {
                    width = orig.outerWidth();
                    height = orig.outerHeight();
                }

            } else {
                pos.top = viewport.y + (viewport.h - height) * current.topRatio;
                pos.left = viewport.x + (viewport.w - width) * current.leftRatio;
            }

            if (F.wrap.css('position') === 'fixed' || current.locked) {
                pos.top -= viewport.y;
                pos.left -= viewport.x;
            }

            pos = {
                top: getValue(pos.top - hPadding * current.topRatio),
                left: getValue(pos.left - wPadding * current.leftRatio),
                width: getValue(width + wPadding),
                height: getValue(height + hPadding)
            };

            return pos;
        },

        step: function (now, fx) {
            var ratio,
                padding,
                value,
                prop = fx.prop,
                current = F.current,
                wrapSpace = current.wrapSpace,
                skinSpace = current.skinSpace;

            if (prop === 'width' || prop === 'height') {
                ratio = fx.end === fx.start ? 1 : (now - fx.start) / (fx.end - fx.start);

                if (F.isClosing) {
                    ratio = 1 - ratio;
                }

                padding = prop === 'width' ? current.wPadding : current.hPadding;
                value = now - padding;

                F.skin[prop](getScalar(prop === 'width' ? value : value - (wrapSpace * ratio)));
                F.inner[prop](getScalar(prop === 'width' ? value : value - (wrapSpace * ratio) - (skinSpace * ratio)));
            }
        },

        zoomIn: function () {
            var current = F.current,
                startPos = current.pos,
                effect = current.openEffect,
                elastic = effect === 'elastic',
                endPos = $.extend({opacity: 1}, startPos);

            // Remove "position" property that breaks older IE
            delete endPos.position;

            if (elastic) {
                startPos = this.getOrigPosition();

                if (current.openOpacity) {
                    startPos.opacity = 0.1;
                }

            } else if (effect === 'fade') {
                startPos.opacity = 0.1;
            }

            F.wrap.css(startPos).animate(endPos, {
                duration: effect === 'none' ? 0 : current.openSpeed,
                easing: current.openEasing,
                step: elastic ? this.step : null,
                complete: F._afterZoomIn
            });
        },

        zoomOut: function () {
            var current = F.current,
                effect = current.closeEffect,
                elastic = effect === 'elastic',
                endPos = {opacity: 0.1};

            if (elastic) {
                endPos = this.getOrigPosition();

                if (current.closeOpacity) {
                    endPos.opacity = 0.1;
                }
            }

            F.wrap.animate(endPos, {
                duration: effect === 'none' ? 0 : current.closeSpeed,
                easing: current.closeEasing,
                step: elastic ? this.step : null,
                complete: F._afterZoomOut
            });
        },

        changeIn: function () {
            var current = F.current,
                effect = current.nextEffect,
                startPos = current.pos,
                endPos = {opacity: 1},
                direction = F.direction,
                distance = 200,
                field;

            startPos.opacity = 0.1;

            if (effect === 'elastic') {
                field = direction === 'down' || direction === 'up' ? 'top' : 'left';

                if (direction === 'down' || direction === 'right') {
                    startPos[field] = getValue(getScalar(startPos[field]) - distance);
                    endPos[field] = '+=' + distance + 'px';

                } else {
                    startPos[field] = getValue(getScalar(startPos[field]) + distance);
                    endPos[field] = '-=' + distance + 'px';
                }
            }

            // Workaround for http://bugs.jquery.com/ticket/12273
            if (effect === 'none') {
                F._afterZoomIn();

            } else {
                F.wrap.css(startPos).animate(endPos, {
                    duration: current.nextSpeed,
                    easing: current.nextEasing,
                    complete: F._afterZoomIn
                });
            }
        },

        changeOut: function () {
            var previous = F.previous,
                effect = previous.prevEffect,
                endPos = {opacity: 0.1},
                direction = F.direction,
                distance = 200;

            if (effect === 'elastic') {
                endPos[direction === 'down' || direction === 'up' ? 'top' : 'left'] = (direction === 'up' || direction === 'left' ? '-' : '+') + '=' + distance + 'px';
            }

            previous.wrap.animate(endPos, {
                duration: effect === 'none' ? 0 : previous.prevSpeed,
                easing: previous.prevEasing,
                complete: function () {
                    $(this).trigger('onReset').remove();
                }
            });
        }
    };

    /*
	 *	Overlay helper
	 */

    F.helpers.overlay = {
        defaults: {
            closeClick: true,      // if true, fancyBox will be closed when user clicks on the overlay
            speedOut: 200,       // duration of fadeOut animation
            showEarly: true,      // indicates if should be opened immediately or wait until the content is ready
            css: {},        // custom CSS properties
            locked: !isTouch,  // if true, the content will be locked into overlay
            fixed: true       // if false, the overlay CSS position property will not be set to "fixed"
        },

        overlay: null,      // current handle
        fixed: false,     // indicates if the overlay has position "fixed"
        el: $('html'), // element that contains "the lock"

        // Public methods
        create: function (opts) {
            opts = $.extend({}, this.defaults, opts);

            if (this.overlay) {
                this.close();
            }

            this.overlay = $('<div class="fancybox-overlay"></div>').appendTo(F.coming ? F.coming.parent : opts.parent);
            this.fixed = false;

            if (opts.fixed && F.defaults.fixed) {
                this.overlay.addClass('fancybox-overlay-fixed');

                this.fixed = true;
            }
        },

        open: function (opts) {
            var that = this;

            opts = $.extend({}, this.defaults, opts);

            if (this.overlay) {
                this.overlay.unbind('.overlay').width('auto').height('auto');

            } else {
                this.create(opts);
            }

            if (!this.fixed) {
                W.bind('resize.overlay', $.proxy(this.update, this));

                this.update();
            }

            if (opts.closeClick) {
                this.overlay.bind('click.overlay', function (e) {
                    if ($(e.target).hasClass('fancybox-overlay')) {
                        if (F.isActive) {
                            F.close();
                        } else {
                            that.close();
                        }

                        return false;
                    }
                });
            }

            this.overlay.css(opts.css).show();
        },

        close: function () {
            var scrollV, scrollH;

            W.unbind('resize.overlay');

            if (this.el.hasClass('fancybox-lock')) {
                $('.fancybox-margin').removeClass('fancybox-margin');

                scrollV = W.scrollTop();
                scrollH = W.scrollLeft();

                this.el.removeClass('fancybox-lock');

                W.scrollTop(scrollV).scrollLeft(scrollH);
            }

            $('.fancybox-overlay').remove().hide();

            $.extend(this, {
                overlay: null,
                fixed: false
            });
        },

        // Private, callbacks

        update: function () {
            var width = '100%', offsetWidth;

            // Reset width/height so it will not mess
            this.overlay.width(width).height('100%');

            // jQuery does not return reliable result for IE
            if (IE) {
                offsetWidth = Math.max(document.documentElement.offsetWidth, document.body.offsetWidth);

                if (D.width() > offsetWidth) {
                    width = D.width();
                }

            } else if (D.width() > W.width()) {
                width = D.width();
            }

            this.overlay.width(width).height(D.height());
        },

        // This is where we can manipulate DOM, because later it would cause iframes to reload
        onReady: function (opts, obj) {
            var overlay = this.overlay;

            $('.fancybox-overlay').stop(true, true);

            if (!overlay) {
                this.create(opts);
            }

            if (opts.locked && this.fixed && obj.fixed) {
                if (!overlay) {
                    this.margin = D.height() > W.height() ? $('html').css('margin-right').replace("px", "") : false;
                }

                obj.locked = this.overlay.append(obj.wrap);
                obj.fixed = false;
            }

            if (opts.showEarly === true) {
                this.beforeShow.apply(this, arguments);
            }
        },

        beforeShow: function (opts, obj) {
            var scrollV, scrollH;

            if (obj.locked) {
                if (this.margin !== false) {
                    $('*').filter(function () {
                        return ($(this).css('position') === 'fixed' && !$(this).hasClass("fancybox-overlay") && !$(this).hasClass("fancybox-wrap"));
                    }).addClass('fancybox-margin');

                    this.el.addClass('fancybox-margin');
                }

                scrollV = W.scrollTop();
                scrollH = W.scrollLeft();

                this.el.addClass('fancybox-lock');

                W.scrollTop(scrollV).scrollLeft(scrollH);
            }

            this.open(opts);
        },

        onUpdate: function () {
            if (!this.fixed) {
                this.update();
            }
        },

        afterClose: function (opts) {
            // Remove overlay if exists and fancyBox is not opening
            // (e.g., it is not being open using afterClose callback)
            //if (this.overlay && !F.isActive) {
            if (this.overlay && !F.coming) {
                this.overlay.fadeOut(opts.speedOut, $.proxy(this.close, this));
            }
        }
    };

    /*
	 *	Title helper
	 */

    F.helpers.title = {
        defaults: {
            type: 'float', // 'float', 'inside', 'outside' or 'over',
            position: 'bottom' // 'top' or 'bottom'
        },

        beforeShow: function (opts) {
            var current = F.current,
                text = current.title,
                type = opts.type,
                title,
                target;

            if ($.isFunction(text)) {
                text = text.call(current.element, current);
            }

            if (!isString(text) || $.trim(text) === '') {
                return;
            }

            title = $('<div class="fancybox-title fancybox-title-' + type + '-wrap">' + text + '</div>');

            switch (type) {
                case 'inside':
                    target = F.skin;
                    break;

                case 'outside':
                    target = F.wrap;
                    break;

                case 'over':
                    target = F.inner;
                    break;

                default: // 'float'
                    target = F.skin;

                    title.appendTo('body');

                    if (IE) {
                        title.width(title.width());
                    }

                    title.wrapInner('<span class="child"></span>');

                    //Increase bottom margin so this title will also fit into viewport
                    F.current.margin[2] += Math.abs(getScalar(title.css('margin-bottom')));
                    break;
            }

            title[(opts.position === 'top' ? 'prependTo' : 'appendTo')](target);
        }
    };

    // jQuery plugin initialization
    $.fn.fancybox = function (options) {
        var index,
            that = $(this),
            selector = this.selector || '',
            run = function (e) {
                var what = $(this).blur(), idx = index, relType, relVal;

                if (!(e.ctrlKey || e.altKey || e.shiftKey || e.metaKey) && !what.is('.fancybox-wrap')) {
                    relType = options.groupAttr || 'data-fancybox-group';
                    relVal = what.attr(relType);

                    if (!relVal) {
                        relType = 'rel';
                        relVal = what.get(0)[relType];
                    }

                    if (relVal && relVal !== '' && relVal !== 'nofollow') {
                        what = selector.length ? $(selector) : that;
                        what = what.filter('[' + relType + '="' + relVal + '"]');
                        idx = what.index(this);
                    }

                    options.index = idx;

                    // Stop an event from bubbling if everything is fine
                    if (F.open(what, options) !== false) {
                        e.preventDefault();
                    }
                }
            };

        options = options || {};
        index = options.index || 0;

        if (!selector || options.live === false) {
            that.unbind('click.fb-start').bind('click.fb-start', run);

        } else {
            D.undelegate(selector, 'click.fb-start').delegate(selector + ":not('.fancybox-item, .fancybox-nav')", 'click.fb-start', run);
        }

        this.filter('[data-fancybox-start=1]').trigger('click');

        return this;
    };

    // Tests that need a body at doc ready
    D.ready(function () {
        var w1, w2;

        if ($.scrollbarWidth === undefined) {
            // http://benalman.com/projects/jquery-misc-plugins/#scrollbarwidth
            $.scrollbarWidth = function () {
                var parent = $('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body'),
                    child = parent.children(),
                    width = child.innerWidth() - child.height(99).innerWidth();

                parent.remove();

                return width;
            };
        }

        if ($.support.fixedPosition === undefined) {
            $.support.fixedPosition = (function () {
                var elem = $('<div style="position:fixed;top:20px;"></div>').appendTo('body'),
                    fixed = (elem[0].offsetTop === 20 || elem[0].offsetTop === 15);

                elem.remove();

                return fixed;
            }());
        }

        $.extend(F.defaults, {
            scrollbarWidth: $.scrollbarWidth(),
            fixed: $.support.fixedPosition,
            parent: $('body')
        });

        //Get real width of page scroll-bar
        w1 = $(window).width();

        H.addClass('fancybox-lock-test');

        w2 = $(window).width();

        H.removeClass('fancybox-lock-test');

        $("<style type='text/css'>.fancybox-margin{margin-right:" + (w2 - w1) + "px;}</style>").appendTo("head");
    });

}(window, document, jQuery));


/*-----*/


/*
 * jQuery FlexSlider v2.1
 * Copyright 2012 WooThemes
 * Contributing Author: Tyler Smith
 */
;(function (d) {
    d.flexslider = function (i, k) {
        var a = d(i), c = d.extend({}, d.flexslider.defaults, k), e = c.namespace,
            p = "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch,
            t = p ? "touchend" : "click", l = "vertical" === c.direction, m = c.reverse, h = 0 < c.itemWidth,
            r = "fade" === c.animation, s = "" !== c.asNavFor, f = {};
        d.data(i, "flexslider", a);
        f = {
            init: function () {
                a.animating = !1;
                a.currentSlide = c.startAt;
                a.animatingTo = a.currentSlide;
                a.atEnd = 0 === a.currentSlide || a.currentSlide === a.last;
                a.containerSelector = c.selector.substr(0,
                    c.selector.search(" "));
                a.slides = d(c.selector, a);
                a.container = d(a.containerSelector, a);
                a.count = a.slides.length;
                a.syncExists = 0 < d(c.sync).length;
                "slide" === c.animation && (c.animation = "swing");
                a.prop = l ? "top" : "marginLeft";
                a.args = {};
                a.manualPause = !1;
                var b = a, g;
                if (g = !c.video) if (g = !r) if (g = c.useCSS) a:{
                    g = document.createElement("div");
                    var n = ["perspectiveProperty", "WebkitPerspective", "MozPerspective", "OPerspective", "msPerspective"],
                        e;
                    for (e in n) if (void 0 !== g.style[n[e]]) {
                        a.pfx = n[e].replace("Perspective", "").toLowerCase();
                        a.prop = "-" + a.pfx + "-transform";
                        g = !0;
                        break a
                    }
                    g = !1
                }
                b.transitions = g;
                "" !== c.controlsContainer && (a.controlsContainer = 0 < d(c.controlsContainer).length && d(c.controlsContainer));
                "" !== c.manualControls && (a.manualControls = 0 < d(c.manualControls).length && d(c.manualControls));
                c.randomize && (a.slides.sort(function () {
                    return Math.round(Math.random()) - 0.5
                }), a.container.empty().append(a.slides));
                a.doMath();
                s && f.asNav.setup();
                a.setup("init");
                c.controlNav && f.controlNav.setup();
                c.directionNav && f.directionNav.setup();
                c.keyboard &&
                (1 === d(a.containerSelector).length || c.multipleKeyboard) && d(document).bind("keyup", function (b) {
                    b = b.keyCode;
                    if (!a.animating && (39 === b || 37 === b)) b = 39 === b ? a.getTarget("next") : 37 === b ? a.getTarget("prev") : !1, a.flexAnimate(b, c.pauseOnAction)
                });
                c.mousewheel && a.bind("mousewheel", function (b, g) {
                    b.preventDefault();
                    var d = 0 > g ? a.getTarget("next") : a.getTarget("prev");
                    a.flexAnimate(d, c.pauseOnAction)
                });
                c.pausePlay && f.pausePlay.setup();
                c.slideshow && (c.pauseOnHover && a.hover(function () {
                        !a.manualPlay && !a.manualPause && a.pause()
                    },
                    function () {
                        !a.manualPause && !a.manualPlay && a.play()
                    }), 0 < c.initDelay ? setTimeout(a.play, c.initDelay) : a.play());
                p && c.touch && f.touch();
                (!r || r && c.smoothHeight) && d(window).bind("resize focus", f.resize);
                setTimeout(function () {
                    c.start(a)
                }, 200)
            }, asNav: {
                setup: function () {
                    a.asNav = !0;
                    a.animatingTo = Math.floor(a.currentSlide / a.move);
                    a.currentItem = a.currentSlide;
                    a.slides.removeClass(e + "active-slide").eq(a.currentItem).addClass(e + "active-slide");
                    a.slides.click(function (b) {
                        b.preventDefault();
                        var b = d(this), g = b.index();
                        !d(c.asNavFor).data("flexslider").animating && !b.hasClass("active") && (a.direction = a.currentItem < g ? "next" : "prev", a.flexAnimate(g, c.pauseOnAction, !1, !0, !0))
                    })
                }
            }, controlNav: {
                setup: function () {
                    a.manualControls ? f.controlNav.setupManual() : f.controlNav.setupPaging()
                }, setupPaging: function () {
                    var b = 1, g;
                    a.controlNavScaffold = d('<ol class="' + e + "control-nav " + e + ("thumbnails" === c.controlNav ? "control-thumbs" : "control-paging") + '"></ol>');
                    if (1 < a.pagingCount) for (var n = 0; n < a.pagingCount; n++) g = "thumbnails" === c.controlNav ?
                        '<img src="' + a.slides.eq(n).attr("data-thumb") + '"/>' : "<a>" + b + "</a>", a.controlNavScaffold.append("<li>" + g + "</li>"), b++;
                    a.controlsContainer ? d(a.controlsContainer).append(a.controlNavScaffold) : a.append(a.controlNavScaffold);
                    f.controlNav.set();
                    f.controlNav.active();
                    a.controlNavScaffold.delegate("a, img", t, function (b) {
                        b.preventDefault();
                        var b = d(this), g = a.controlNav.index(b);
                        b.hasClass(e + "active") || (a.direction = g > a.currentSlide ? "next" : "prev", a.flexAnimate(g, c.pauseOnAction))
                    });
                    p && a.controlNavScaffold.delegate("a",
                        "click touchstart", function (a) {
                            a.preventDefault()
                        })
                }, setupManual: function () {
                    a.controlNav = a.manualControls;
                    f.controlNav.active();
                    a.controlNav.live(t, function (b) {
                        b.preventDefault();
                        var b = d(this), g = a.controlNav.index(b);
                        b.hasClass(e + "active") || (g > a.currentSlide ? a.direction = "next" : a.direction = "prev", a.flexAnimate(g, c.pauseOnAction))
                    });
                    p && a.controlNav.live("click touchstart", function (a) {
                        a.preventDefault()
                    })
                }, set: function () {
                    a.controlNav = d("." + e + "control-nav li " + ("thumbnails" === c.controlNav ? "img" : "a"),
                        a.controlsContainer ? a.controlsContainer : a)
                }, active: function () {
                    a.controlNav.removeClass(e + "active").eq(a.animatingTo).addClass(e + "active")
                }, update: function (b, c) {
                    1 < a.pagingCount && "add" === b ? a.controlNavScaffold.append(d("<li><a>" + a.count + "</a></li>")) : 1 === a.pagingCount ? a.controlNavScaffold.find("li").remove() : a.controlNav.eq(c).closest("li").remove();
                    f.controlNav.set();
                    1 < a.pagingCount && a.pagingCount !== a.controlNav.length ? a.update(c, b) : f.controlNav.active()
                }
            }, directionNav: {
                setup: function () {
                    var b = d('<ul class="' +
                        e + 'direction-nav"><li><a class="' + e + 'prev" href="#">' + c.prevText + '</a></li><li><a class="' + e + 'next" href="#">' + c.nextText + "</a></li></ul>");
                    a.controlsContainer ? (d(a.controlsContainer).append(b), a.directionNav = d("." + e + "direction-nav li a", a.controlsContainer)) : (a.append(b), a.directionNav = d("." + e + "direction-nav li a", a));
                    f.directionNav.update();
                    a.directionNav.bind(t, function (b) {
                        b.preventDefault();
                        b = d(this).hasClass(e + "next") ? a.getTarget("next") : a.getTarget("prev");
                        a.flexAnimate(b, c.pauseOnAction)
                    });
                    p && a.directionNav.bind("click touchstart", function (a) {
                        a.preventDefault()
                    })
                }, update: function () {
                    var b = e + "disabled";
                    1 === a.pagingCount ? a.directionNav.addClass(b) : c.animationLoop ? a.directionNav.removeClass(b) : 0 === a.animatingTo ? a.directionNav.removeClass(b).filter("." + e + "prev").addClass(b) : a.animatingTo === a.last ? a.directionNav.removeClass(b).filter("." + e + "next").addClass(b) : a.directionNav.removeClass(b)
                }
            }, pausePlay: {
                setup: function () {
                    var b = d('<div class="' + e + 'pauseplay"><a></a></div>');
                    a.controlsContainer ?
                        (a.controlsContainer.append(b), a.pausePlay = d("." + e + "pauseplay a", a.controlsContainer)) : (a.append(b), a.pausePlay = d("." + e + "pauseplay a", a));
                    f.pausePlay.update(c.slideshow ? e + "pause" : e + "play");
                    a.pausePlay.bind(t, function (b) {
                        b.preventDefault();
                        d(this).hasClass(e + "pause") ? (a.manualPause = !0, a.manualPlay = !1, a.pause()) : (a.manualPause = !1, a.manualPlay = !0, a.play())
                    });
                    p && a.pausePlay.bind("click touchstart", function (a) {
                        a.preventDefault()
                    })
                }, update: function (b) {
                    "play" === b ? a.pausePlay.removeClass(e + "pause").addClass(e +
                        "play").text(c.playText) : a.pausePlay.removeClass(e + "play").addClass(e + "pause").text(c.pauseText)
                }
            }, touch: function () {
                function b(b) {
                    j = l ? d - b.touches[0].pageY : d - b.touches[0].pageX;
                    p = l ? Math.abs(j) < Math.abs(b.touches[0].pageX - e) : Math.abs(j) < Math.abs(b.touches[0].pageY - e);
                    if (!p || 500 < Number(new Date) - k) b.preventDefault(), !r && a.transitions && (c.animationLoop || (j /= 0 === a.currentSlide && 0 > j || a.currentSlide === a.last && 0 < j ? Math.abs(j) / q + 2 : 1), a.setProps(f + j, "setTouch"))
                }

                function g() {
                    i.removeEventListener("touchmove",
                        b, !1);
                    if (a.animatingTo === a.currentSlide && !p && null !== j) {
                        var h = m ? -j : j, l = 0 < h ? a.getTarget("next") : a.getTarget("prev");
                        a.canAdvance(l) && (550 > Number(new Date) - k && 50 < Math.abs(h) || Math.abs(h) > q / 2) ? a.flexAnimate(l, c.pauseOnAction) : r || a.flexAnimate(a.currentSlide, c.pauseOnAction, !0)
                    }
                    i.removeEventListener("touchend", g, !1);
                    f = j = e = d = null
                }

                var d, e, f, q, j, k, p = !1;
                i.addEventListener("touchstart", function (j) {
                    a.animating ? j.preventDefault() : 1 === j.touches.length && (a.pause(), q = l ? a.h : a.w, k = Number(new Date), f = h && m && a.animatingTo ===
                    a.last ? 0 : h && m ? a.limit - (a.itemW + c.itemMargin) * a.move * a.animatingTo : h && a.currentSlide === a.last ? a.limit : h ? (a.itemW + c.itemMargin) * a.move * a.currentSlide : m ? (a.last - a.currentSlide + a.cloneOffset) * q : (a.currentSlide + a.cloneOffset) * q, d = l ? j.touches[0].pageY : j.touches[0].pageX, e = l ? j.touches[0].pageX : j.touches[0].pageY, i.addEventListener("touchmove", b, !1), i.addEventListener("touchend", g, !1))
                }, !1)
            }, resize: function () {
                !a.animating && a.is(":visible") && (h || a.doMath(), r ? f.smoothHeight() : h ? (a.slides.width(a.computedW),
                    a.update(a.pagingCount), a.setProps()) : l ? (a.viewport.height(a.h), a.setProps(a.h, "setTotal")) : (c.smoothHeight && f.smoothHeight(), a.newSlides.width(a.computedW), a.setProps(a.computedW, "setTotal")))
            }, smoothHeight: function (b) {
                if (!l || r) {
                    var c = r ? a : a.viewport;
                    b ? c.animate({height: a.slides.eq(a.animatingTo).height()}, b) : c.height(a.slides.eq(a.animatingTo).height())
                }
            }, sync: function (b) {
                var g = d(c.sync).data("flexslider"), e = a.animatingTo;
                switch (b) {
                    case "animate":
                        g.flexAnimate(e, c.pauseOnAction, !1, !0);
                        break;
                    case "play":
                        !g.playing &&
                        !g.asNav && g.play();
                        break;
                    case "pause":
                        g.pause()
                }
            }
        };
        a.flexAnimate = function (b, g, n, i, k) {
            s && 1 === a.pagingCount && (a.direction = a.currentItem < b ? "next" : "prev");
            if (!a.animating && (a.canAdvance(b, k) || n) && a.is(":visible")) {
                if (s && i) if (n = d(c.asNavFor).data("flexslider"), a.atEnd = 0 === b || b === a.count - 1, n.flexAnimate(b, !0, !1, !0, k), a.direction = a.currentItem < b ? "next" : "prev", n.direction = a.direction, Math.ceil((b + 1) / a.visible) - 1 !== a.currentSlide && 0 !== b) a.currentItem = b, a.slides.removeClass(e + "active-slide").eq(b).addClass(e +
                    "active-slide"), b = Math.floor(b / a.visible); else return a.currentItem = b, a.slides.removeClass(e + "active-slide").eq(b).addClass(e + "active-slide"), !1;
                a.animating = !0;
                a.animatingTo = b;
                c.before(a);
                g && a.pause();
                a.syncExists && !k && f.sync("animate");
                c.controlNav && f.controlNav.active();
                h || a.slides.removeClass(e + "active-slide").eq(b).addClass(e + "active-slide");
                a.atEnd = 0 === b || b === a.last;
                c.directionNav && f.directionNav.update();
                b === a.last && (c.end(a), c.animationLoop || a.pause());
                if (r) p ? (a.slides.eq(a.currentSlide).css({
                    opacity: 0,
                    zIndex: 1
                }), a.slides.eq(b).css({
                    opacity: 1,
                    zIndex: 2
                }), a.slides.unbind("webkitTransitionEnd transitionend"), a.slides.eq(a.currentSlide).bind("webkitTransitionEnd transitionend", function () {
                    c.after(a)
                }), a.animating = !1, a.currentSlide = a.animatingTo) : (a.slides.eq(a.currentSlide).fadeOut(c.animationSpeed, c.easing), a.slides.eq(b).fadeIn(c.animationSpeed, c.easing, a.wrapup)); else {
                    var q = l ? a.slides.filter(":first").height() : a.computedW;
                    h ? (b = c.itemWidth > a.w ? 2 * c.itemMargin : c.itemMargin, b = (a.itemW + b) * a.move * a.animatingTo,
                        b = b > a.limit && 1 !== a.visible ? a.limit : b) : b = 0 === a.currentSlide && b === a.count - 1 && c.animationLoop && "next" !== a.direction ? m ? (a.count + a.cloneOffset) * q : 0 : a.currentSlide === a.last && 0 === b && c.animationLoop && "prev" !== a.direction ? m ? 0 : (a.count + 1) * q : m ? (a.count - 1 - b + a.cloneOffset) * q : (b + a.cloneOffset) * q;
                    a.setProps(b, "", c.animationSpeed);
                    if (a.transitions) {
                        if (!c.animationLoop || !a.atEnd) a.animating = !1, a.currentSlide = a.animatingTo;
                        a.container.unbind("webkitTransitionEnd transitionend");
                        a.container.bind("webkitTransitionEnd transitionend",
                            function () {
                                a.wrapup(q)
                            })
                    } else a.container.animate(a.args, c.animationSpeed, c.easing, function () {
                        a.wrapup(q)
                    })
                }
                c.smoothHeight && f.smoothHeight(c.animationSpeed)
            }
        };
        a.wrapup = function (b) {
            !r && !h && (0 === a.currentSlide && a.animatingTo === a.last && c.animationLoop ? a.setProps(b, "jumpEnd") : a.currentSlide === a.last && (0 === a.animatingTo && c.animationLoop) && a.setProps(b, "jumpStart"));
            a.animating = !1;
            a.currentSlide = a.animatingTo;
            c.after(a)
        };
        a.animateSlides = function () {
            a.animating || a.flexAnimate(a.getTarget("next"))
        };
        a.pause =
            function () {
                clearInterval(a.animatedSlides);
                a.playing = !1;
                c.pausePlay && f.pausePlay.update("play");
                a.syncExists && f.sync("pause")
            };
        a.play = function () {
            a.animatedSlides = setInterval(a.animateSlides, c.slideshowSpeed);
            a.playing = !0;
            c.pausePlay && f.pausePlay.update("pause");
            a.syncExists && f.sync("play")
        };
        a.canAdvance = function (b, g) {
            var d = s ? a.pagingCount - 1 : a.last;
            return g ? !0 : s && a.currentItem === a.count - 1 && 0 === b && "prev" === a.direction ? !0 : s && 0 === a.currentItem && b === a.pagingCount - 1 && "next" !== a.direction ? !1 : b === a.currentSlide &&
            !s ? !1 : c.animationLoop ? !0 : a.atEnd && 0 === a.currentSlide && b === d && "next" !== a.direction ? !1 : a.atEnd && a.currentSlide === d && 0 === b && "next" === a.direction ? !1 : !0
        };
        a.getTarget = function (b) {
            a.direction = b;
            return "next" === b ? a.currentSlide === a.last ? 0 : a.currentSlide + 1 : 0 === a.currentSlide ? a.last : a.currentSlide - 1
        };
        a.setProps = function (b, g, d) {
            var e, f = b ? b : (a.itemW + c.itemMargin) * a.move * a.animatingTo;
            e = -1 * function () {
                if (h) return "setTouch" === g ? b : m && a.animatingTo === a.last ? 0 : m ? a.limit - (a.itemW + c.itemMargin) * a.move * a.animatingTo : a.animatingTo ===
                a.last ? a.limit : f;
                switch (g) {
                    case "setTotal":
                        return m ? (a.count - 1 - a.currentSlide + a.cloneOffset) * b : (a.currentSlide + a.cloneOffset) * b;
                    case "setTouch":
                        return b;
                    case "jumpEnd":
                        return m ? b : a.count * b;
                    case "jumpStart":
                        return m ? a.count * b : b;
                    default:
                        return b
                }
            }() + "px";
            a.transitions && (e = l ? "translate3d(0," + e + ",0)" : "translate3d(" + e + ",0,0)", d = void 0 !== d ? d / 1E3 + "s" : "0s", a.container.css("-" + a.pfx + "-transition-duration", d));
            a.args[a.prop] = e;
            (a.transitions || void 0 === d) && a.container.css(a.args)
        };
        a.setup = function (b) {
            if (r) a.slides.css({
                width: "100%",
                "float": "left", marginRight: "-100%", position: "relative"
            }), "init" === b && (p ? a.slides.css({
                opacity: 0,
                display: "block",
                webkitTransition: "opacity " + c.animationSpeed / 1E3 + "s ease",
                zIndex: 1
            }).eq(a.currentSlide).css({
                opacity: 1,
                zIndex: 2
            }) : a.slides.eq(a.currentSlide).fadeIn(c.animationSpeed, c.easing)), c.smoothHeight && f.smoothHeight(); else {
                var g, n;
                "init" === b && (a.viewport = d('<div class="' + e + 'viewport"></div>').css({
                    overflow: "hidden",
                    position: "relative"
                }).appendTo(a).append(a.container), a.cloneCount = 0, a.cloneOffset =
                    0, m && (n = d.makeArray(a.slides).reverse(), a.slides = d(n), a.container.empty().append(a.slides)));
                c.animationLoop && !h && (a.cloneCount = 2, a.cloneOffset = 1, "init" !== b && a.container.find(".clone").remove(), a.container.append(a.slides.first().clone().addClass("clone")).prepend(a.slides.last().clone().addClass("clone")));
                a.newSlides = d(c.selector, a);
                g = m ? a.count - 1 - a.currentSlide + a.cloneOffset : a.currentSlide + a.cloneOffset;
                l && !h ? (a.container.height(200 * (a.count + a.cloneCount) + "%").css("position", "absolute").width("100%"),
                    setTimeout(function () {
                        a.newSlides.css({display: "block"});
                        a.doMath();
                        a.viewport.height(a.h);
                        a.setProps(g * a.h, "init")
                    }, "init" === b ? 100 : 0)) : (a.container.width(200 * (a.count + a.cloneCount) + "%"), a.setProps(g * a.computedW, "init"), setTimeout(function () {
                    a.doMath();
                    a.newSlides.css({width: a.computedW, "float": "left", display: "block"});
                    c.smoothHeight && f.smoothHeight()
                }, "init" === b ? 100 : 0))
            }
            h || a.slides.removeClass(e + "active-slide").eq(a.currentSlide).addClass(e + "active-slide")
        };
        a.doMath = function () {
            var b = a.slides.first(),
                d = c.itemMargin, e = c.minItems, f = c.maxItems;
            a.w = a.width();
            a.h = b.height();
            a.boxPadding = b.outerWidth() - b.width();
            h ? (a.itemT = c.itemWidth + d, a.minW = e ? e * a.itemT : a.w, a.maxW = f ? f * a.itemT : a.w, a.itemW = a.minW > a.w ? (a.w - d * e) / e : a.maxW < a.w ? (a.w - d * f) / f : c.itemWidth > a.w ? a.w : c.itemWidth, a.visible = Math.floor(a.w / (a.itemW + d)), a.move = 0 < c.move && c.move < a.visible ? c.move : a.visible, a.pagingCount = Math.ceil((a.count - a.visible) / a.move + 1), a.last = a.pagingCount - 1, a.limit = 1 === a.pagingCount ? 0 : c.itemWidth > a.w ? (a.itemW + 2 * d) * a.count - a.w -
                d : (a.itemW + d) * a.count - a.w - d) : (a.itemW = a.w, a.pagingCount = a.count, a.last = a.count - 1);
            a.computedW = a.itemW - a.boxPadding
        };
        a.update = function (b, d) {
            a.doMath();
            h || (b < a.currentSlide ? a.currentSlide += 1 : b <= a.currentSlide && 0 !== b && (a.currentSlide -= 1), a.animatingTo = a.currentSlide);
            if (c.controlNav && !a.manualControls) if ("add" === d && !h || a.pagingCount > a.controlNav.length) f.controlNav.update("add"); else if ("remove" === d && !h || a.pagingCount < a.controlNav.length) h && a.currentSlide > a.last && (a.currentSlide -= 1, a.animatingTo -= 1),
                f.controlNav.update("remove", a.last);
            c.directionNav && f.directionNav.update()
        };
        a.addSlide = function (b, e) {
            var f = d(b);
            a.count += 1;
            a.last = a.count - 1;
            l && m ? void 0 !== e ? a.slides.eq(a.count - e).after(f) : a.container.prepend(f) : void 0 !== e ? a.slides.eq(e).before(f) : a.container.append(f);
            a.update(e, "add");
            a.slides = d(c.selector + ":not(.clone)", a);
            a.setup();
            c.added(a)
        };
        a.removeSlide = function (b) {
            var e = isNaN(b) ? a.slides.index(d(b)) : b;
            a.count -= 1;
            a.last = a.count - 1;
            isNaN(b) ? d(b, a.slides).remove() : l && m ? a.slides.eq(a.last).remove() :
                a.slides.eq(b).remove();
            a.doMath();
            a.update(e, "remove");
            a.slides = d(c.selector + ":not(.clone)", a);
            a.setup();
            c.removed(a)
        };
        f.init()
    };
    d.flexslider.defaults = {
        namespace: "flex-",
        selector: ".slides > li",
        animation: "fade",
        easing: "swing",
        direction: "horizontal",
        reverse: !1,
        animationLoop: !0,
        smoothHeight: !1,
        startAt: 0,
        slideshow: !0,
        slideshowSpeed: 7E3,
        animationSpeed: 600,
        initDelay: 0,
        randomize: !1,
        pauseOnAction: !0,
        pauseOnHover: !1,
        useCSS: !0,
        touch: !0,
        video: !1,
        controlNav: !0,
        directionNav: !0,
        prevText: "Previous",
        nextText: "Next",
        keyboard: !0,
        multipleKeyboard: !1,
        mousewheel: !1,
        pausePlay: !1,
        pauseText: "Pause",
        playText: "Play",
        controlsContainer: "",
        manualControls: "",
        sync: "",
        asNavFor: "",
        itemWidth: 0,
        itemMargin: 0,
        minItems: 0,
        maxItems: 0,
        move: 0,
        start: function () {
        },
        before: function () {
        },
        after: function () {
        },
        end: function () {
        },
        added: function () {
        },
        removed: function () {
        }
    };
    d.fn.flexslider = function (i) {
        void 0 === i && (i = {});
        if ("object" === typeof i) return this.each(function () {
            var a = d(this), c = a.find(i.selector ? i.selector : ".slides > li");
            1 === c.length ? (c.fadeIn(400),
            i.start && i.start(a)) : void 0 == a.data("flexslider") && new d.flexslider(this, i)
        });
        var k = d(this).data("flexslider");
        switch (i) {
            case "play":
                k.play();
                break;
            case "pause":
                k.pause();
                break;
            case "next":
                k.flexAnimate(k.getTarget("next"), !0);
                break;
            case "prev":
            case "previous":
                k.flexAnimate(k.getTarget("prev"), !0);
                break;
            default:
                "number" === typeof i && k.flexAnimate(i, !0)
        }
    }
})(jQuery);


/*-----*/


/*!
 * jQuery Validation Plugin 1.11.1
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright 2013 Jörn Zaefferer
 * Released under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */

(function ($) {

    $.extend($.fn, {
        // http://docs.jquery.com/Plugins/Validation/validate
        validate: function (options) {

            // if nothing is selected, return nothing; can't chain anyway
            if (!this.length) {
                if (options && options.debug && window.console) {
                    console.warn("Nothing selected, can't validate, returning nothing.");
                }
                return;
            }

            // check if a validator for this form was already created
            var validator = $.data(this[0], "validator");
            if (validator) {
                return validator;
            }

            // Add novalidate tag if HTML5.
            this.attr("novalidate", "novalidate");

            validator = new $.validator(options, this[0]);
            $.data(this[0], "validator", validator);

            if (validator.settings.onsubmit) {

                this.validateDelegate(":submit", "click", function (event) {
                    if (validator.settings.submitHandler) {
                        validator.submitButton = event.target;
                    }
                    // allow suppressing validation by adding a cancel class to the submit button
                    if ($(event.target).hasClass("cancel")) {
                        validator.cancelSubmit = true;
                    }

                    // allow suppressing validation by adding the html5 formnovalidate attribute to the submit button
                    if ($(event.target).attr("formnovalidate") !== undefined) {
                        validator.cancelSubmit = true;
                    }
                });

                // validate the form on submit
                this.submit(function (event) {
                    if (validator.settings.debug) {
                        // prevent form submit to be able to see console output
                        event.preventDefault();
                    }

                    function handle() {
                        var hidden;
                        if (validator.settings.submitHandler) {
                            if (validator.submitButton) {
                                // insert a hidden input as a replacement for the missing submit button
                                hidden = $("<input type='hidden'/>").attr("name", validator.submitButton.name).val($(validator.submitButton).val()).appendTo(validator.currentForm);
                            }
                            validator.settings.submitHandler.call(validator, validator.currentForm, event);
                            if (validator.submitButton) {
                                // and clean up afterwards; thanks to no-block-scope, hidden can be referenced
                                hidden.remove();
                            }
                            return false;
                        }
                        return true;
                    }

                    // prevent submit for invalid forms or custom submit handlers
                    if (validator.cancelSubmit) {
                        validator.cancelSubmit = false;
                        return handle();
                    }
                    if (validator.form()) {
                        if (validator.pendingRequest) {
                            validator.formSubmitted = true;
                            return false;
                        }
                        return handle();
                    } else {
                        validator.focusInvalid();
                        return false;
                    }
                });
            }

            return validator;
        },
        // http://docs.jquery.com/Plugins/Validation/valid
        valid: function () {
            if ($(this[0]).is("form")) {
                return this.validate().form();
            } else {
                var valid = true;
                var validator = $(this[0].form).validate();
                this.each(function () {
                    valid = valid && validator.element(this);
                });
                return valid;
            }
        },
        // attributes: space seperated list of attributes to retrieve and remove
        removeAttrs: function (attributes) {
            var result = {},
                $element = this;
            $.each(attributes.split(/\s/), function (index, value) {
                result[value] = $element.attr(value);
                $element.removeAttr(value);
            });
            return result;
        },
        // http://docs.jquery.com/Plugins/Validation/rules
        rules: function (command, argument) {
            var element = this[0];

            if (command) {
                var settings = $.data(element.form, "validator").settings;
                var staticRules = settings.rules;
                var existingRules = $.validator.staticRules(element);
                switch (command) {
                    case "add":
                        $.extend(existingRules, $.validator.normalizeRule(argument));
                        // remove messages from rules, but allow them to be set separetely
                        delete existingRules.messages;
                        staticRules[element.name] = existingRules;
                        if (argument.messages) {
                            settings.messages[element.name] = $.extend(settings.messages[element.name], argument.messages);
                        }
                        break;
                    case "remove":
                        if (!argument) {
                            delete staticRules[element.name];
                            return existingRules;
                        }
                        var filtered = {};
                        $.each(argument.split(/\s/), function (index, method) {
                            filtered[method] = existingRules[method];
                            delete existingRules[method];
                        });
                        return filtered;
                }
            }

            var data = $.validator.normalizeRules(
                $.extend(
                    {},
                    $.validator.classRules(element),
                    $.validator.attributeRules(element),
                    $.validator.dataRules(element),
                    $.validator.staticRules(element)
                ), element);

            // make sure required is at front
            if (data.required) {
                var param = data.required;
                delete data.required;
                data = $.extend({required: param}, data);
            }

            return data;
        }
    });

// Custom selectors
    $.extend($.expr[":"], {
        // http://docs.jquery.com/Plugins/Validation/blank
        blank: function (a) {
            return !$.trim("" + $(a).val());
        },
        // http://docs.jquery.com/Plugins/Validation/filled
        filled: function (a) {
            return !!$.trim("" + $(a).val());
        },
        // http://docs.jquery.com/Plugins/Validation/unchecked
        unchecked: function (a) {
            return !$(a).prop("checked");
        }
    });

// constructor for validator
    $.validator = function (options, form) {
        this.settings = $.extend(true, {}, $.validator.defaults, options);
        this.currentForm = form;
        this.init();
    };

    $.validator.format = function (source, params) {
        if (arguments.length === 1) {
            return function () {
                var args = $.makeArray(arguments);
                args.unshift(source);
                return $.validator.format.apply(this, args);
            };
        }
        if (arguments.length > 2 && params.constructor !== Array) {
            params = $.makeArray(arguments).slice(1);
        }
        if (params.constructor !== Array) {
            params = [params];
        }
        $.each(params, function (i, n) {
            source = source.replace(new RegExp("\\{" + i + "\\}", "g"), function () {
                return n;
            });
        });
        return source;
    };

    $.extend($.validator, {

        defaults: {
            messages: {},
            groups: {},
            rules: {},
            errorClass: "error",
            validClass: "valid",
            errorElement: "label",
            focusInvalid: true,
            errorContainer: $([]),
            errorLabelContainer: $([]),
            onsubmit: true,
            ignore: ":hidden",
            ignoreTitle: false,
            onfocusin: function (element, event) {
                this.lastActive = element;

                // hide error label and remove error class on focus if enabled
                if (this.settings.focusCleanup && !this.blockFocusCleanup) {
                    if (this.settings.unhighlight) {
                        this.settings.unhighlight.call(this, element, this.settings.errorClass, this.settings.validClass);
                    }
                    this.addWrapper(this.errorsFor(element)).hide();
                }
            },
            onfocusout: function (element, event) {
                if (!this.checkable(element) && (element.name in this.submitted || !this.optional(element))) {
                    this.element(element);
                }
            },
            onkeyup: function (element, event) {
                if (event.which === 9 && this.elementValue(element) === "") {
                    return;
                } else if (element.name in this.submitted || element === this.lastElement) {
                    this.element(element);
                }
            },
            onclick: function (element, event) {
                // click on selects, radiobuttons and checkboxes
                if (element.name in this.submitted) {
                    this.element(element);
                }
                // or option elements, check parent select in that case
                else if (element.parentNode.name in this.submitted) {
                    this.element(element.parentNode);
                }
            },
            highlight: function (element, errorClass, validClass) {
                if (element.type === "radio") {
                    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                } else {
                    $(element).addClass(errorClass).removeClass(validClass);
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                if (element.type === "radio") {
                    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                } else {
                    $(element).removeClass(errorClass).addClass(validClass);
                }
            }
        },

        // http://docs.jquery.com/Plugins/Validation/Validator/setDefaults
        setDefaults: function (settings) {
            $.extend($.validator.defaults, settings);
        },

        messages: {
            required: "This field is required.",
            remote: "Please fix this field.",
            email: "Please enter a valid email address.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date (ISO).",
            number: "Please enter a valid number.",
            digits: "Please enter only digits.",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Please enter the same value again.",
            maxlength: $.validator.format("Please enter no more than {0} characters."),
            minlength: $.validator.format("Please enter at least {0} characters."),
            rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
            range: $.validator.format("Please enter a value between {0} and {1}."),
            max: $.validator.format("Please enter a value less than or equal to {0}."),
            min: $.validator.format("Please enter a value greater than or equal to {0}.")
        },

        autoCreateRanges: false,

        prototype: {

            init: function () {
                this.labelContainer = $(this.settings.errorLabelContainer);
                this.errorContext = this.labelContainer.length && this.labelContainer || $(this.currentForm);
                this.containers = $(this.settings.errorContainer).add(this.settings.errorLabelContainer);
                this.submitted = {};
                this.valueCache = {};
                this.pendingRequest = 0;
                this.pending = {};
                this.invalid = {};
                this.reset();

                var groups = (this.groups = {});
                $.each(this.settings.groups, function (key, value) {
                    if (typeof value === "string") {
                        value = value.split(/\s/);
                    }
                    $.each(value, function (index, name) {
                        groups[name] = key;
                    });
                });
                var rules = this.settings.rules;
                $.each(rules, function (key, value) {
                    rules[key] = $.validator.normalizeRule(value);
                });

                function delegate(event) {
                    var validator = $.data(this[0].form, "validator"),
                        eventType = "on" + event.type.replace(/^validate/, "");
                    if (validator.settings[eventType]) {
                        validator.settings[eventType].call(validator, this[0], event);
                    }
                }

                $(this.currentForm)
                    .validateDelegate(":text, [type='password'], [type='file'], select, textarea, " +
                        "[type='number'], [type='search'] ,[type='tel'], [type='url'], " +
                        "[type='email'], [type='datetime'], [type='date'], [type='month'], " +
                        "[type='week'], [type='time'], [type='datetime-local'], " +
                        "[type='range'], [type='color'] ",
                        "focusin focusout keyup", delegate)
                    .validateDelegate("[type='radio'], [type='checkbox'], select, option", "click", delegate);

                if (this.settings.invalidHandler) {
                    $(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler);
                }
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/form
            form: function () {
                this.checkForm();
                $.extend(this.submitted, this.errorMap);
                this.invalid = $.extend({}, this.errorMap);
                if (!this.valid()) {
                    $(this.currentForm).triggerHandler("invalid-form", [this]);
                }
                this.showErrors();
                return this.valid();
            },

            checkForm: function () {
                this.prepareForm();
                for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
                    this.check(elements[i]);
                }
                return this.valid();
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/element
            element: function (element) {
                element = this.validationTargetFor(this.clean(element));
                this.lastElement = element;
                this.prepareElement(element);
                this.currentElements = $(element);
                var result = this.check(element) !== false;
                if (result) {
                    delete this.invalid[element.name];
                } else {
                    this.invalid[element.name] = true;
                }
                if (!this.numberOfInvalids()) {
                    // Hide error containers on last error
                    this.toHide = this.toHide.add(this.containers);
                }
                this.showErrors();
                return result;
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/showErrors
            showErrors: function (errors) {
                if (errors) {
                    // add items to error list and map
                    $.extend(this.errorMap, errors);
                    this.errorList = [];
                    for (var name in errors) {
                        this.errorList.push({
                            message: errors[name],
                            element: this.findByName(name)[0]
                        });
                    }
                    // remove items from success list
                    this.successList = $.grep(this.successList, function (element) {
                        return !(element.name in errors);
                    });
                }
                if (this.settings.showErrors) {
                    this.settings.showErrors.call(this, this.errorMap, this.errorList);
                } else {
                    this.defaultShowErrors();
                }
            },

            // http://docs.jquery.com/Plugins/Validation/Validator/resetForm
            resetForm: function () {
                if ($.fn.resetForm) {
                    $(this.currentForm).resetForm();
                }
                this.submitted = {};
                this.lastElement = null;
                this.prepareForm();
                this.hideErrors();
                this.elements().removeClass(this.settings.errorClass).removeData("previousValue");
            },

            numberOfInvalids: function () {
                return this.objectLength(this.invalid);
            },

            objectLength: function (obj) {
                var count = 0;
                for (var i in obj) {
                    count++;
                }
                return count;
            },

            hideErrors: function () {
                this.addWrapper(this.toHide).hide();
            },

            valid: function () {
                return this.size() === 0;
            },

            size: function () {
                return this.errorList.length;
            },

            focusInvalid: function () {
                if (this.settings.focusInvalid) {
                    try {
                        $(this.findLastActive() || this.errorList.length && this.errorList[0].element || [])
                            .filter(":visible")
                            .focus()
                            // manually trigger focusin event; without it, focusin handler isn't called, findLastActive won't have anything to find
                            .trigger("focusin");
                    } catch (e) {
                        // ignore IE throwing errors when focusing hidden elements
                    }
                }
            },

            findLastActive: function () {
                var lastActive = this.lastActive;
                return lastActive && $.grep(this.errorList, function (n) {
                    return n.element.name === lastActive.name;
                }).length === 1 && lastActive;
            },

            elements: function () {
                var validator = this,
                    rulesCache = {};

                // select all valid inputs inside the form (no submit or reset buttons)
                return $(this.currentForm)
                    .find("input, select, textarea")
                    .not(":submit, :reset, :image, [disabled]")
                    .not(this.settings.ignore)
                    .filter(function () {
                        if (!this.name && validator.settings.debug && window.console) {
                            console.error("%o has no name assigned", this);
                        }

                        // select only the first element for each name, and only those with rules specified
                        if (this.name in rulesCache || !validator.objectLength($(this).rules())) {
                            return false;
                        }

                        rulesCache[this.name] = true;
                        return true;
                    });
            },

            clean: function (selector) {
                return $(selector)[0];
            },

            errors: function () {
                var errorClass = this.settings.errorClass.replace(" ", ".");
                return $(this.settings.errorElement + "." + errorClass, this.errorContext);
            },

            reset: function () {
                this.successList = [];
                this.errorList = [];
                this.errorMap = {};
                this.toShow = $([]);
                this.toHide = $([]);
                this.currentElements = $([]);
            },

            prepareForm: function () {
                this.reset();
                this.toHide = this.errors().add(this.containers);
            },

            prepareElement: function (element) {
                this.reset();
                this.toHide = this.errorsFor(element);
            },

            elementValue: function (element) {
                var type = $(element).attr("type"),
                    val = $(element).val();

                if (type === "radio" || type === "checkbox") {
                    return $("input[name='" + $(element).attr("name") + "']:checked").val();
                }

                if (typeof val === "string") {
                    return val.replace(/\r/g, "");
                }
                return val;
            },

            check: function (element) {
                element = this.validationTargetFor(this.clean(element));

                var rules = $(element).rules();
                var dependencyMismatch = false;
                var val = this.elementValue(element);
                var result;

                for (var method in rules) {
                    var rule = {method: method, parameters: rules[method]};
                    try {

                        result = $.validator.methods[method].call(this, val, element, rule.parameters);

                        // if a method indicates that the field is optional and therefore valid,
                        // don't mark it as valid when there are no other rules
                        if (result === "dependency-mismatch") {
                            dependencyMismatch = true;
                            continue;
                        }
                        dependencyMismatch = false;

                        if (result === "pending") {
                            this.toHide = this.toHide.not(this.errorsFor(element));
                            return;
                        }

                        if (!result) {
                            this.formatAndAdd(element, rule);
                            return false;
                        }
                    } catch (e) {
                        if (this.settings.debug && window.console) {
                            console.log("Exception occurred when checking element " + element.id + ", check the '" + rule.method + "' method.", e);
                        }
                        throw e;
                    }
                }
                if (dependencyMismatch) {
                    return;
                }
                if (this.objectLength(rules)) {
                    this.successList.push(element);
                }
                return true;
            },

            // return the custom message for the given element and validation method
            // specified in the element's HTML5 data attribute
            customDataMessage: function (element, method) {
                return $(element).data("msg-" + method.toLowerCase()) || (element.attributes && $(element).attr("data-msg-" + method.toLowerCase()));
            },

            // return the custom message for the given element name and validation method
            customMessage: function (name, method) {
                var m = this.settings.messages[name];
                return m && (m.constructor === String ? m : m[method]);
            },

            // return the first defined argument, allowing empty strings
            findDefined: function () {
                for (var i = 0; i < arguments.length; i++) {
                    if (arguments[i] !== undefined) {
                        return arguments[i];
                    }
                }
                return undefined;
            },

            defaultMessage: function (element, method) {
                return this.findDefined(
                    this.customMessage(element.name, method),
                    this.customDataMessage(element, method),
                    // title is never undefined, so handle empty string as undefined
                    !this.settings.ignoreTitle && element.title || undefined,
                    $.validator.messages[method],
                    "<strong>Warning: No message defined for " + element.name + "</strong>"
                );
            },

            formatAndAdd: function (element, rule) {
                var message = this.defaultMessage(element, rule.method),
                    theregex = /\$?\{(\d+)\}/g;
                if (typeof message === "function") {
                    message = message.call(this, rule.parameters, element);
                } else if (theregex.test(message)) {
                    message = $.validator.format(message.replace(theregex, "{$1}"), rule.parameters);
                }
                this.errorList.push({
                    message: message,
                    element: element
                });

                this.errorMap[element.name] = message;
                this.submitted[element.name] = message;
            },

            addWrapper: function (toToggle) {
                if (this.settings.wrapper) {
                    toToggle = toToggle.add(toToggle.parent(this.settings.wrapper));
                }
                return toToggle;
            },

            defaultShowErrors: function () {
                var i, elements;
                for (i = 0; this.errorList[i]; i++) {
                    var error = this.errorList[i];
                    if (this.settings.highlight) {
                        this.settings.highlight.call(this, error.element, this.settings.errorClass, this.settings.validClass);
                    }
                    this.showLabel(error.element, error.message);
                }
                if (this.errorList.length) {
                    this.toShow = this.toShow.add(this.containers);
                }
                if (this.settings.success) {
                    for (i = 0; this.successList[i]; i++) {
                        this.showLabel(this.successList[i]);
                    }
                }
                if (this.settings.unhighlight) {
                    for (i = 0, elements = this.validElements(); elements[i]; i++) {
                        this.settings.unhighlight.call(this, elements[i], this.settings.errorClass, this.settings.validClass);
                    }
                }
                this.toHide = this.toHide.not(this.toShow);
                this.hideErrors();
                this.addWrapper(this.toShow).show();
            },

            validElements: function () {
                return this.currentElements.not(this.invalidElements());
            },

            invalidElements: function () {
                return $(this.errorList).map(function () {
                    return this.element;
                });
            },

            showLabel: function (element, message) {
                var label = this.errorsFor(element);
                if (label.length) {
                    // refresh error/success class
                    label.removeClass(this.settings.validClass).addClass(this.settings.errorClass);
                    // replace message on existing label
                    label.html(message);
                } else {
                    // create label
                    label = $("<" + this.settings.errorElement + ">")
                        .attr("for", this.idOrName(element))
                        .addClass(this.settings.errorClass)
                        .html(message || "");
                    if (this.settings.wrapper) {
                        // make sure the element is visible, even in IE
                        // actually showing the wrapped element is handled elsewhere
                        label = label.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
                    }
                    if (!this.labelContainer.append(label).length) {
                        if (this.settings.errorPlacement) {
                            this.settings.errorPlacement(label, $(element));
                        } else {
                            label.insertAfter(element);
                        }
                    }
                }
                if (!message && this.settings.success) {
                    label.text("");
                    if (typeof this.settings.success === "string") {
                        label.addClass(this.settings.success);
                    } else {
                        this.settings.success(label, element);
                    }
                }
                this.toShow = this.toShow.add(label);
            },

            errorsFor: function (element) {
                var name = this.idOrName(element);
                return this.errors().filter(function () {
                    return $(this).attr("for") === name;
                });
            },

            idOrName: function (element) {
                return this.groups[element.name] || (this.checkable(element) ? element.name : element.id || element.name);
            },

            validationTargetFor: function (element) {
                // if radio/checkbox, validate first element in group instead
                if (this.checkable(element)) {
                    element = this.findByName(element.name).not(this.settings.ignore)[0];
                }
                return element;
            },

            checkable: function (element) {
                return (/radio|checkbox/i).test(element.type);
            },

            findByName: function (name) {
                return $(this.currentForm).find("[name='" + name + "']");
            },

            getLength: function (value, element) {
                switch (element.nodeName.toLowerCase()) {
                    case "select":
                        return $("option:selected", element).length;
                    case "input":
                        if (this.checkable(element)) {
                            return this.findByName(element.name).filter(":checked").length;
                        }
                }
                return value.length;
            },

            depend: function (param, element) {
                return this.dependTypes[typeof param] ? this.dependTypes[typeof param](param, element) : true;
            },

            dependTypes: {
                "boolean": function (param, element) {
                    return param;
                },
                "string": function (param, element) {
                    return !!$(param, element.form).length;
                },
                "function": function (param, element) {
                    return param(element);
                }
            },

            optional: function (element) {
                var val = this.elementValue(element);
                return !$.validator.methods.required.call(this, val, element) && "dependency-mismatch";
            },

            startRequest: function (element) {
                if (!this.pending[element.name]) {
                    this.pendingRequest++;
                    this.pending[element.name] = true;
                }
            },

            stopRequest: function (element, valid) {
                this.pendingRequest--;
                // sometimes synchronization fails, make sure pendingRequest is never < 0
                if (this.pendingRequest < 0) {
                    this.pendingRequest = 0;
                }
                delete this.pending[element.name];
                if (valid && this.pendingRequest === 0 && this.formSubmitted && this.form()) {
                    $(this.currentForm).submit();
                    this.formSubmitted = false;
                } else if (!valid && this.pendingRequest === 0 && this.formSubmitted) {
                    $(this.currentForm).triggerHandler("invalid-form", [this]);
                    this.formSubmitted = false;
                }
            },

            previousValue: function (element) {
                return $.data(element, "previousValue") || $.data(element, "previousValue", {
                    old: null,
                    valid: true,
                    message: this.defaultMessage(element, "remote")
                });
            }

        },

        classRuleSettings: {
            required: {required: true},
            email: {email: true},
            url: {url: true},
            date: {date: true},
            dateISO: {dateISO: true},
            number: {number: true},
            digits: {digits: true},
            creditcard: {creditcard: true}
        },

        addClassRules: function (className, rules) {
            if (className.constructor === String) {
                this.classRuleSettings[className] = rules;
            } else {
                $.extend(this.classRuleSettings, className);
            }
        },

        classRules: function (element) {
            var rules = {};
            var classes = $(element).attr("class");
            if (classes) {
                $.each(classes.split(" "), function () {
                    if (this in $.validator.classRuleSettings) {
                        $.extend(rules, $.validator.classRuleSettings[this]);
                    }
                });
            }
            return rules;
        },

        attributeRules: function (element) {
            var rules = {};
            var $element = $(element);
            var type = $element[0].getAttribute("type");

            for (var method in $.validator.methods) {
                var value;

                // support for <input required> in both html5 and older browsers
                if (method === "required") {
                    value = $element.get(0).getAttribute(method);
                    // Some browsers return an empty string for the required attribute
                    // and non-HTML5 browsers might have required="" markup
                    if (value === "") {
                        value = true;
                    }
                    // force non-HTML5 browsers to return bool
                    value = !!value;
                } else {
                    value = $element.attr(method);
                }

                // convert the value to a number for number inputs, and for text for backwards compability
                // allows type="date" and others to be compared as strings
                if (/min|max/.test(method) && (type === null || /number|range|text/.test(type))) {
                    value = Number(value);
                }

                if (value) {
                    rules[method] = value;
                } else if (type === method && type !== 'range') {
                    // exception: the jquery validate 'range' method
                    // does not test for the html5 'range' type
                    rules[method] = true;
                }
            }

            // maxlength may be returned as -1, 2147483647 (IE) and 524288 (safari) for text inputs
            if (rules.maxlength && /-1|2147483647|524288/.test(rules.maxlength)) {
                delete rules.maxlength;
            }

            return rules;
        },

        dataRules: function (element) {
            var method, value,
                rules = {}, $element = $(element);
            for (method in $.validator.methods) {
                value = $element.data("rule-" + method.toLowerCase());
                if (value !== undefined) {
                    rules[method] = value;
                }
            }
            return rules;
        },

        staticRules: function (element) {
            var rules = {};
            var validator = $.data(element.form, "validator");
            if (validator.settings.rules) {
                rules = $.validator.normalizeRule(validator.settings.rules[element.name]) || {};
            }
            return rules;
        },

        normalizeRules: function (rules, element) {
            // handle dependency check
            $.each(rules, function (prop, val) {
                // ignore rule when param is explicitly false, eg. required:false
                if (val === false) {
                    delete rules[prop];
                    return;
                }
                if (val.param || val.depends) {
                    var keepRule = true;
                    switch (typeof val.depends) {
                        case "string":
                            keepRule = !!$(val.depends, element.form).length;
                            break;
                        case "function":
                            keepRule = val.depends.call(element, element);
                            break;
                    }
                    if (keepRule) {
                        rules[prop] = val.param !== undefined ? val.param : true;
                    } else {
                        delete rules[prop];
                    }
                }
            });

            // evaluate parameters
            $.each(rules, function (rule, parameter) {
                rules[rule] = $.isFunction(parameter) ? parameter(element) : parameter;
            });

            // clean number parameters
            $.each(['minlength', 'maxlength'], function () {
                if (rules[this]) {
                    rules[this] = Number(rules[this]);
                }
            });
            $.each(['rangelength', 'range'], function () {
                var parts;
                if (rules[this]) {
                    if ($.isArray(rules[this])) {
                        rules[this] = [Number(rules[this][0]), Number(rules[this][1])];
                    } else if (typeof rules[this] === "string") {
                        parts = rules[this].split(/[\s,]+/);
                        rules[this] = [Number(parts[0]), Number(parts[1])];
                    }
                }
            });

            if ($.validator.autoCreateRanges) {
                // auto-create ranges
                if (rules.min && rules.max) {
                    rules.range = [rules.min, rules.max];
                    delete rules.min;
                    delete rules.max;
                }
                if (rules.minlength && rules.maxlength) {
                    rules.rangelength = [rules.minlength, rules.maxlength];
                    delete rules.minlength;
                    delete rules.maxlength;
                }
            }

            return rules;
        },

        // Converts a simple string to a {string: true} rule, e.g., "required" to {required:true}
        normalizeRule: function (data) {
            if (typeof data === "string") {
                var transformed = {};
                $.each(data.split(/\s/), function () {
                    transformed[this] = true;
                });
                data = transformed;
            }
            return data;
        },

        // http://docs.jquery.com/Plugins/Validation/Validator/addMethod
        addMethod: function (name, method, message) {
            $.validator.methods[name] = method;
            $.validator.messages[name] = message !== undefined ? message : $.validator.messages[name];
            if (method.length < 3) {
                $.validator.addClassRules(name, $.validator.normalizeRule(name));
            }
        },

        methods: {

            // http://docs.jquery.com/Plugins/Validation/Methods/required
            required: function (value, element, param) {
                // check if dependency is met
                if (!this.depend(param, element)) {
                    return "dependency-mismatch";
                }
                if (element.nodeName.toLowerCase() === "select") {
                    // could be an array for select-multiple or a string, both are fine this way
                    var val = $(element).val();
                    return val && val.length > 0;
                }
                if (this.checkable(element)) {
                    return this.getLength(value, element) > 0;
                }
                return $.trim(value).length > 0;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/email
            email: function (value, element) {
                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
                return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/url
            url: function (value, element) {
                // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
                return this.optional(element) || /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/date
            date: function (value, element) {
                return this.optional(element) || !/Invalid|NaN/.test(new Date(value).toString());
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/dateISO
            dateISO: function (value, element) {
                return this.optional(element) || /^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/number
            number: function (value, element) {
                return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/digits
            digits: function (value, element) {
                return this.optional(element) || /^\d+$/.test(value);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/creditcard
            // based on http://en.wikipedia.org/wiki/Luhn
            creditcard: function (value, element) {
                if (this.optional(element)) {
                    return "dependency-mismatch";
                }
                // accept only spaces, digits and dashes
                if (/[^0-9 \-]+/.test(value)) {
                    return false;
                }
                var nCheck = 0,
                    nDigit = 0,
                    bEven = false;

                value = value.replace(/\D/g, "");

                for (var n = value.length - 1; n >= 0; n--) {
                    var cDigit = value.charAt(n);
                    nDigit = parseInt(cDigit, 10);
                    if (bEven) {
                        if ((nDigit *= 2) > 9) {
                            nDigit -= 9;
                        }
                    }
                    nCheck += nDigit;
                    bEven = !bEven;
                }

                return (nCheck % 10) === 0;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/minlength
            minlength: function (value, element, param) {
                var length = $.isArray(value) ? value.length : this.getLength($.trim(value), element);
                return this.optional(element) || length >= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/maxlength
            maxlength: function (value, element, param) {
                var length = $.isArray(value) ? value.length : this.getLength($.trim(value), element);
                return this.optional(element) || length <= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/rangelength
            rangelength: function (value, element, param) {
                var length = $.isArray(value) ? value.length : this.getLength($.trim(value), element);
                return this.optional(element) || (length >= param[0] && length <= param[1]);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/min
            min: function (value, element, param) {
                return this.optional(element) || value >= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/max
            max: function (value, element, param) {
                return this.optional(element) || value <= param;
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/range
            range: function (value, element, param) {
                return this.optional(element) || (value >= param[0] && value <= param[1]);
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/equalTo
            equalTo: function (value, element, param) {
                // bind to the blur event of the target in order to revalidate whenever the target field is updated
                // TODO find a way to bind the event just once, avoiding the unbind-rebind overhead
                var target = $(param);
                if (this.settings.onfocusout) {
                    target.unbind(".validate-equalTo").bind("blur.validate-equalTo", function () {
                        $(element).valid();
                    });
                }
                return value === target.val();
                0
            },

            // http://docs.jquery.com/Plugins/Validation/Methods/remote
            remote: function (value, element, param) {
                if (this.optional(element)) {
                    return "dependency-mismatch";
                }

                var previous = this.previousValue(element);
                if (!this.settings.messages[element.name]) {
                    this.settings.messages[element.name] = {};
                }
                previous.originalMessage = this.settings.messages[element.name].remote;
                this.settings.messages[element.name].remote = previous.message;

                param = typeof param === "string" && {url: param} || param;

                if (previous.old === value) {
                    return previous.valid;
                }

                previous.old = value;
                var validator = this;
                this.startRequest(element);
                var data = {};
                data[element.name] = value;
                $.ajax($.extend(true, {
                    url: param,
                    mode: "abort",
                    port: "validate" + element.name,
                    dataType: "json",
                    data: data,
                    success: function (response) {
                        validator.settings.messages[element.name].remote = previous.originalMessage;
                        var valid = response === true || response === "true";
                        if (valid) {
                            var submitted = validator.formSubmitted;
                            validator.prepareElement(element);
                            validator.formSubmitted = submitted;
                            validator.successList.push(element);
                            delete validator.invalid[element.name];
                            validator.showErrors();
                        } else {
                            var errors = {};
                            var message = response || validator.defaultMessage(element, "remote");
                            errors[element.name] = previous.message = $.isFunction(message) ? message(value) : message;
                            validator.invalid[element.name] = true;
                            validator.showErrors(errors);
                        }
                        previous.valid = valid;
                        validator.stopRequest(element, valid);
                    }
                }, param));
                return "pending";
            }

        }

    });

// deprecated, use $.validator.format instead
    $.format = $.validator.format;

}(jQuery));

// ajax mode: abort
// usage: $.ajax({ mode: "abort"[, port: "uniqueport"]});
// if mode:"abort" is used, the previous request on that port (port can be undefined) is aborted via XMLHttpRequest.abort()
(function ($) {
    var pendingRequests = {};
    // Use a prefilter if available (1.5+)
    if ($.ajaxPrefilter) {
        $.ajaxPrefilter(function (settings, _, xhr) {
            var port = settings.port;
            if (settings.mode === "abort") {
                if (pendingRequests[port]) {
                    pendingRequests[port].abort();
                }
                pendingRequests[port] = xhr;
            }
        });
    } else {
        // Proxy ajax
        var ajax = $.ajax;
        $.ajax = function (settings) {
            var mode = ("mode" in settings ? settings : $.ajaxSettings).mode,
                port = ("port" in settings ? settings : $.ajaxSettings).port;
            if (mode === "abort") {
                if (pendingRequests[port]) {
                    pendingRequests[port].abort();
                }
                pendingRequests[port] = ajax.apply(this, arguments);
                return pendingRequests[port];
            }
            return ajax.apply(this, arguments);
        };
    }
}(jQuery));

// provides delegate(type: String, delegate: Selector, handler: Callback) plugin for easier event delegation
// handler is only called when $(event.target).is(delegate), in the scope of the jquery-object for event.target
(function ($) {
    $.extend($.fn, {
        validateDelegate: function (delegate, type, handler) {
            return this.bind(type, function (event) {
                var target = $(event.target);
                if (target.is(delegate)) {
                    return handler.apply(target, arguments);
                }
            });
        }
    });
}(jQuery));


/*-----*/


/**
 * scriptjava.js
 * save time
 * scriptjava.ru
 * scriptjava.net
 * scriptjava.org
 */

var _$$ = {
    version: 20120603,
    $: function () {
        var obj;
        switch (arguments.length) {
            case 0:
                return document;
                break;
            case 2:
                _$$.$(arguments[0]).innerHTML = arguments[1];
                break;
            case 3:
                _$$.$(arguments[0]).style[arguments[1]] = arguments[2];
                break;
        }
        if (arguments.length > 0) {
            if (typeof arguments[0] == 'string') {
                obj = _$$.$().getElementById(arguments[0]);
            }
            else {
                obj = arguments[0];
            }
        }
        if (obj == null) {
            return false;
        }
        obj.$$ = function () {
            switch (arguments.length) {
                case 0:
                    this.$$ = function () {
                        return _$$.$.apply(null, arguments);
                    }
                    break;
                case 1:
                    obj.innerHTML = arguments[0];
                    break;
                case 2:
                    obj.style[arguments[0]] = arguments[1];
                    break;
            }
            return obj;
        }
        obj.$$after = function (el) {
            var elm = this;
            var th = _$$.$(el);
            do {
                th = th.nextSibling;
            } while (th && th.nodeType != 1);
            if (th != null) {
                if (th != elm) {
                    th.parentNode.insertBefore(elm, th);
                }
            }
            else {
                if (el != elm) {
                    el.parentNode.appendChild(elm);
                }
            }
            return _$$.$(elm);
        }
        obj.$$before = function (el) {
            var elm = this;
            var th = _$$.$(el);
            do {
                th = th.previousSibling;
            } while (th && th.nodeType != 1);
            var nth = th;
            if (nth != null) {
                do {
                    nth = nth.nextSibling;
                } while (nth && nth.nodeType != 1);
                if (th != elm) {
                    th.parentNode.insertBefore(elm, nth);
                }
            }
            else {
                if (el != elm) {
                    el.parentNode.insertBefore(elm, el);
                }
            }
            return _$$.$(elm);
        }
        obj.$$prev = function () {
            var elm = this;
            do {
                elm = elm.previousSibling;
            } while (elm && elm.nodeType != 1);
            if (elm != null) {
                return _$$.$(elm);
            }
            else {
                return elm;
            }
        }
        obj.$$next = function () {
            var elm = this;
            do {
                elm = elm.nextSibling;
            } while (elm && elm.nodeType != 1);
            if (elm != null) {
                return _$$.$(elm);
            }
            else {
                return elm;
            }
        }
        obj.$$first = function () {
            var elm = this;
            elm = elm.firstChild;
            elm = elm && elm.nodeType != 1 ? elm.nextSibling : elm;
            if (elm != null) {
                return _$$.$(elm);
            }
            else {
                return elm;
            }
        }
        obj.$$last = function () {
            var elm = this;
            elm = elm.lastChild;
            elm = elm && elm.nodeType != 1 ? elm.previousSibling : elm;
            if (elm != null) {
                return _$$.$(elm);
            }
            else {
                return elm;
            }
        }
        obj.$$parent = function (num) {
            var elm = this;
            num = num || 1;
            for (var i = 0; i < num; i++)
                if (elm != null) elm = elm.parentNode;
            if (elm != null) {
                return _$$.$(elm);
            }
            else {
                return elm;
            }
        }
        obj.$$html = function () {
            var elm = this;
            return _$$.$(elm).innerHTML;
        }
        return obj;
    },
    $a: function (arg) {
        arg = arg || {};

        var default_arg = {
            type: 'get',
            url: '',
            data: {},
            response: 'text',
            header: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=windows-1251',
                'Referer': location.href
            },
            async: true,
            username: '',
            password: '',
            errrep: false,
            error: function (num) {
                var arr = ['Your browser does not support Ajax',
                    'Request failed',
                    'Address does not exist',
                    'The waiting time left'];

                alert(arr[num]);
            },
            status: function (number) {

            },
            endstatus: function (number) {

            },
            success: function (data) {

            },
            timeout: 0
        }

        for (var index in default_arg) {
            if (typeof arg[index] == "undefined") arg[index] = default_arg[index];
        }


        var type = arg['type'];
        var url = arg['url'];
        var data = arg['data'];
        var response = arg['response'];
        var header = arg['header'];
        var async = arg['async'];
        var username = arg['username'];
        var password = arg['password'];
        var errrep = arg['errrep'];
        var error = arg['error'];
        var status = arg['status'];
        var endstatus = arg['endstatus'];
        var success = arg['success'];
        var timeout = arg['timeout'];


        var obj = function () {
            var req = null;
            if (window.XMLHttpRequest) {
                try {
                    req = new XMLHttpRequest();
                }
                catch (e) {
                }
            }
            else if (window.ActiveXObject) {
                try {
                    req = new ActiveXObject('Msxml2.XMLHTTP');
                }
                catch (e) {
                    try {
                        req = new ActiveXObject('Microsoft.XMLHTTP');
                    }
                    catch (e) {
                    }
                }
            }
            return req;
        }


        var encurl = function (d) {
            var query = [];
            if (d instanceof Object) {
                for (var k in d) {
                    query.push(encodeURIComponent(k) + '=' + encodeURIComponent(d[k]));
                }
                return query.join('&');
            }
            else {
                return encodeURIComponent(d);
            }
        }


        var parsehead = function (headertxt) {
            var headers = {};
            var ls = /^\s*/;
            var ts = /\s*$/;
            var lines = headertxt.split("\n");
            for (var i = 0; i < lines.length; i++) {
                var line = lines[i];
                if (line.length == 0) continue;
                var pos = line.indexOf(':');
                var name = line.substring(0, pos).replace(ls, "").replace(ts, "");
                var value = line.substring(pos + 1).replace(ls, "").replace(ts, "");
                headers[name] = value;
            }
            return headers;
        }


        var req = obj();

        if (req !== null) {

            var fullurl = url + '?' + encurl(data);

            if (type == 'get') {
                (username == '' && password == '') ? req.open("GET", fullurl, async) : req.open("GET", fullurl, async, username, password);
            }
            else if (type == 'post') {
                (username == '' && password == '') ? req.open("POST", url, async) : req.open("POST", url, async, username, password);
            }
            else if (type == 'head') {
                (username == '' && password == '') ? req.open("HEAD", fullurl, async) : req.open("HEAD", fullurl, async, username, password);
            }

            if (header instanceof Object) {
                for (var key in header) {
                    req.setRequestHeader(key, header[key]);
                }
            }


            if (!async) {

                if (type == 'get' || type == 'head') {
                    req.send(null);
                }
                else if (type == 'post') {
                    req.send(encurl(data));
                }


                endstatus(req.status);

                if (req.status == 200) {

                    if (type == 'get' || type == 'post') {
                        if (response == 'text') {
                            success(req.responseText);
                        }
                        else if (response == 'xml') {
                            success(req.responseXML);
                        }
                    }
                    else if (type == 'head') {
                        success(parsehead(req.getAllResponseHeaders()));
                    }

                }
                else if (req.status == 404) {
                    if (errrep) {
                        error(2);
                    }
                }
                else {
                    if (errrep) {
                        error(1);
                    }
                }


            }
            else {

                req.onreadystatechange = function () {

                    status(req.readyState);

                    if (req.readyState == 4) {

                        if (reqtime) {
                            clearTimeout(reqtime);
                        }

                        endstatus(req.status);

                        if (req.status == 200) {

                            if (type == 'get' || type == 'post') {
                                if (response == 'text') {
                                    success(req.responseText);
                                }
                                else if (response == 'xml') {
                                    success(req.responseXML);
                                }
                            }
                            else if (type == 'head') {
                                success(parsehead(req.getAllResponseHeaders()));
                            }

                        }
                        else if (req.status == 404) {
                            if (errrep) {
                                error(2);
                            }
                        }
                        else {
                            if (errrep) {
                                error(1);
                            }
                        }
                    }

                }

                if (type == 'get' || type == 'head') {
                    req.send(null);
                }
                else if (type == 'post') {
                    req.send(encurl(data));
                }


                if (timeout > 0) {
                    var reqtime = setTimeout(function () {
                        if (req.readyState != 4) {
                            req.abort();
                            if (errrep) {
                                error(3);
                            }
                        }
                    }, timeout);
                }

            }


        }
        else {
            if (errrep) {
                error(0);
            }
        }

    },
    $c: {
        set: function (cookie_name, cookie_value, cookie_expires, cookie_path, cookie_domain, cookie_secure) {
            if (cookie_name !== undefined) {
                cookie_expires = cookie_expires || 0;
                var expire_date = new Date;
                expire_date.setTime(expire_date.getTime() + (cookie_expires * 1000));
                _$$.$().cookie = cookie_name + "=" + encodeURIComponent(cookie_value) + '; ' +
                    ((cookie_expires === undefined) ? '' : 'expires=' + expire_date.toGMTString() + '; ') +
                    ((cookie_path === undefined) ? 'path=/;' : 'path=' + cookie_path + '; ') +
                    ((cookie_domain === undefined) ? '' : 'domain=' + cookie_domain + '; ') +
                    ((cookie_secure === true) ? 'secure; ' : '');
            }
        },
        get: function (cookie_name) {
            var cookie = _$$.$().cookie, length = cookie.length;
            if (length) {
                var cookie_start = cookie.indexOf(cookie_name + '=');
                if (cookie_start != -1) {
                    var cookie_end = cookie.indexOf(';', cookie_start);
                    if (cookie_end == -1) {
                        cookie_end = length;
                    }
                    cookie_start += cookie_name.length + 1;
                    return decodeURIComponent(cookie.substring(cookie_start, cookie_end));
                }
            }
        },
        erase: function (cookie_name) {
            this.set(cookie_name, '', -1);
        },
        test: function () {
            this.set('test_cookie', 'test', 10);
            var work = (this.get('test_cookie') === 'test') ? true : false;
            this.erase('test_cookie');
            return work;
        }
    },
    $e: {
        add: function (elem, type, handler) {
            if (elem.event_list == undefined) elem.event_list = {};
            if (elem.event_list[type] == undefined) {
                elem.event_list[type] = [];
                var func = function (event) {
                    var event = event || window.event;
                    var list = elem.event_list[type];
                    for (var i in list) {
                        list[i](event);
                    }
                }
                if (elem.addEventListener) {
                    elem.addEventListener(type, func, false);
                }
                else if (elem.attachEvent) {
                    elem.attachEvent('on' + type, func);
                }
                else {
                    elem['on' + type] = func;
                }
            }
            var list = elem.event_list[type];
            var exist = false;
            for (var i in list) {
                if (list[i] == handler) exist = true;
            }
            if (!exist) {
                elem.event_list[type].push(handler);
            }
        },
        remove: function (elem, type, handler) {
            if (elem.event_list == undefined) return false;
            if (elem.event_list[type] == undefined) return false;
            var list = elem.event_list[type];
            for (var i in list) {
                if (list[i] == handler) {
                    list = list.splice(i, 1);
                    return true;
                }
            }
            return false;
        }
    },
    $f: function (arg) {
        arg = arg || {};
        var default_arg = {
            formid: '',
            url: '',
            onstart: function () {

            },
            onsend: function () {

            }
        }

        for (var index in default_arg) {
            if (typeof arg[index] == "undefined") arg[index] = default_arg[index];
        }

        var formid = arg['formid'];
        var url = arg['url'];
        var onsend = arg['onsend'];
        var onstart = arg['onstart'];

        var id = 'f' + _$$.$s.randnum(0, 1000000);

        var div = _$$.$i({
            create: 'div',
            attribute: {},
            insert: _$$.$().body
        });


        div.innerHTML = '<iframe style="width:250px;height:200px;" src="about:blank" id="' + id + '" name="' + id + '" onload="if(this.onsendcomplete) {if(this.contentDocument) {var d = this.contentDocument;}else if(this.contentWindow) {var d = this.contentWindow.document;}else {var d = window.frames[this.id].document;}if(d.location.href != \'about:blank\') {this.onsendcomplete();}}"></iframe>';
        div.style.display = 'none';

        _$$.$(id).onsendcomplete = function () {
            setTimeout(function () {
                _$$.$().body.removeChild(div);
            }, 60000);
            onsend();
        };

        _$$.$(formid).setAttribute('target', id);
        _$$.$(formid).setAttribute('action', url);
        _$$.$(formid).submit();
        onstart();

        return div;
    },
    $i: function (arg) {
        arg = arg || {};
        var default_arg = {
            create: 'script',
            attribute: {
                'type': 'text/javascript'
            },
            insert: _$$.$().body,
            onready: function () {

            }
        }

        for (var index in default_arg) {
            if (typeof arg[index] == "undefined") arg[index] = default_arg[index];
        }

        var create = arg['create'];
        var attribute = arg['attribute'];
        var insert = arg['insert'];
        var onready = arg['onready'];

        var s = _$$.$().createElement(create);

        for (var key in attribute) {
            s.setAttribute(key, attribute[key]);
        }

        if (create == 'script' && (typeof attribute.src != "undefined")) {
            if (s.readyState) {
                s.onreadystatechange = function () {
                    if (s.readyState == "loaded" || s.readyState == "complete") {
                        s.onreadystatechange = null;
                        onready();
                    }
                }
            }
            else {
                s.onload = function () {
                    onready();
                }
            }
        }

        insert.appendChild(s);

        if (create == 'script' && (typeof attribute.src == "undefined")) {
            s.$$ = function () {
                s.text = arguments[0];
            }
        }
        else if (create == 'style') {
            s.$$ = function () {
                if (s.styleSheet) {
                    s.styleSheet.cssText = _$$.$().createTextNode(arguments[0]).nodeValue;
                }
                else {
                    s.appendChild(_$$.$().createTextNode(arguments[0]));
                }
            }
        }
        else {
            return _$$.$(s);
        }

        return s;
    },
    $r: {
        rl: [],
        or: function (handler) {

            if (!_$$.$r.rl.length) {
                _$$.$r.br(function () {
                    for (var i = 0; i < _$$.$r.rl.length; i++) {
                        _$$.$r.rl[i]();
                    }
                });
            }

            _$$.$r.rl.push(handler);
        },
        br: function (handler) {

            var called = false;

            var ready = function () {
                if (called) return;
                called = true;
                handler();
            }

            if (document.addEventListener) {
                document.addEventListener("DOMContentLoaded", function () {
                    document.removeEventListener("DOMContentLoaded", arguments.callee, false);
                    ready();
                }, false);
            }
            else if (document.attachEvent) {
                if (document.documentElement.doScroll && window == window.top) {
                    var tryScroll = function () {
                        if (called) {
                            return;
                        }
                        try {
                            document.documentElement.doScroll("left");
                            ready();
                        }
                        catch (e) {
                            setTimeout(tryScroll, 10);
                        }
                    }
                    tryScroll();
                }

                document.attachEvent("onreadystatechange", function () {
                    if (document.readyState === "complete") {
                        document.detachEvent("onreadystatechange", arguments.callee);
                        ready();
                    }
                });
            }


            if (window.addEventListener) {
                window.addEventListener('load', ready, false);
            }
            else if (window.attachEvent) {
                window.attachEvent('onload', ready);
            }
            else {
                window.onload = ready;
            }
        }

    },
    $s: {
        screensize: function () {
            return {"w": screen.width, "h": screen.height};
        },
        windowpos: function () {
            if (window.screenLeft || window.screenTop) {
                return {"l": window.screenLeft, "t": window.screenTop};
            }
            else if (window.screenX || window.screenY) {
                return {"l": window.screenX, "t": window.screenY};
            }
            else {
                return {"l": 0, "t": 0};
            }
        },
        clientsize: function () {
            if (window.innerWidth || window.innerHeight) {
                return {"w": window.innerWidth, "h": window.innerHeight};
            }
            else if (_$$.$().documentElement && (_$$.$().documentElement.clientWidth || _$$.$().documentElement.clientHeight)) {
                return {"w": _$$.$().documentElement.clientWidth, "h": _$$.$().documentElement.clientHeight};
            }
            else if (_$$.$().body.clientWidth || _$$.$().body.clientHeight) {
                return {"w": _$$.$().body.clientWidth, "h": _$$.$().body.clientHeight};
            }
            else {
                return {"w": 0, "h": 0};
            }
        },
        scrollpos: function () {
            if (window.innerWidth || window.innerHeight) {
                return {"l": window.pageXOffset, "t": window.pageYOffset};
            }
            else if (_$$.$().documentElement && (_$$.$().documentElement.clientWidth || _$$.$().documentElement.clientHeight)) {
                return {"l": _$$.$().documentElement.scrollLeft, "t": _$$.$().documentElement.scrollTop};
            }
            else if (_$$.$().body.clientWidth || _$$.$().body.clientHeight) {
                return {"l": _$$.$().body.scrollLeft, "t": _$$.$().body.scrollTop};
            }
            else {
                return {"l": 0, "t": 0};
            }
        },
        scrollsize: function () {
            if (_$$.$().documentElement && (_$$.$().documentElement.scrollWidth || _$$.$().documentElement.scrollHeight)) {
                return {"w": _$$.$().documentElement.scrollWidth, "h": _$$.$().documentElement.scrollHeight};
            }
            else if (_$$.$().body.scrollWidth || _$$.$().body.scrollHeight) {
                return {"w": _$$.$().body.scrollWidth, "h": _$$.$().body.scrollHeight};
            }
            else {
                return {"w": 0, "h": 0};
            }
        },
        mousepos: function (e) {
            var x = 0, y = 0;
            if (!e) e = window.event;
            if (e.pageX || e.pageY) {
                x = e.pageX;
                y = e.pageY;
            }
            else if (e.clientX || e.clientY) {
                x = e.clientX + this.scrollpos().l - _$$.$().documentElement.clientLeft;
                y = e.clientY + this.scrollpos().t - _$$.$().documentElement.clientTop;
            }
            return {"x": x, "y": y};
        },
        mouseelpos: function (e) {
            var x = 0, y = 0;
            if (!e) e = window.event;
            if (e.layerX || e.layerY) {
                x = e.layerX;
                y = e.layerY;
            }
            else if (e.offsetX || e.offsetY) {
                x = e.offsetX;
                y = e.offsetY;
            }
            return {"x": x, "y": y};
        },
        elementpos: function (el) {
            var l = 0, t = 0;
            while (el) {
                l += el.offsetLeft;
                t += el.offsetTop;
                el = el.offsetParent;
            }
            return {"l": l, "t": t};
        },
        getelbyclass: function (n, t, el) {
            t = t || '*';
            el = el || _$$.$();
            var elms = [], relm = [];
            elms = el.getElementsByTagName(t);
            for (var i = 0; i < elms.length; i++) {
                if (elms[i].className == n) {
                    relm.push(elms[i]);
                }
            }
            return relm;
        },
        getelbytag: function (t, el) {
            t = t || '*';
            el = el || _$$.$();
            var elms = [], relm = [];
            elms = el.getElementsByTagName(t);
            for (var i = 0; i < elms.length; i++) {
                relm.push(elms[i]);
            }
            return relm;
        },
        getelbyname: function (n, t, el) {
            t = t || '*';
            el = el || _$$.$();
            var elms = [], relm = [];
            elms = el.getElementsByTagName(t);
            for (var i = 0; i < elms.length; i++) {
                if (elms[i].getAttribute('name') == n) {
                    relm.push(elms[i]);
                }
            }
            return relm;
        },
        geteventtype: function (e) {
            if (!e) e = window.event;
            return e.type;
        },
        mousebutton: function (e) {
            if (e.which == null) {
                return (e.button < 2) ? "left" : ((e.button == 4) ? "middle" : "right");
            }
            else {
                return (e.which < 2) ? "left" : ((e.which == 2) ? "middle" : "right");
            }
        },
        randnum: function (rmin, rmax) {
            return Math.floor(Math.random() * (rmax - rmin + 1)) + rmin;
        },
        browsername: function () {
            var useragent = navigator.userAgent;
            var navigatorname;
            if (useragent.indexOf('MSIE') != -1) {
                navigatorname = "MSIE";
            }
            else if (useragent.indexOf('Safari') != -1) {
                if (useragent.indexOf('Chrome') != -1) {
                    navigatorname = "Chrome";
                }
                else {
                    navigatorname = "Safari";
                }
            }
            else if (useragent.indexOf('Gecko') != -1) {
                if (useragent.indexOf('Chrome') != -1) {
                    navigatorname = "Chrome";
                }
                else {
                    navigatorname = "Mozilla";
                }
            }
            else if (useragent.indexOf('Mozilla') != -1) {
                navigatorname = "Old Netscape or Mozilla";
            }
            else if (useragent.indexOf('Opera') != -1) {
                navigatorname = "Opera";
            }
            return navigatorname;
        }
    }
};

var $ver = _$$.version;
var $$ = _$$.$;
var $$a = _$$.$a;
var $$c = _$$.$c;
var $$e = _$$.$e;
var $$f = _$$.$f;
var $$i = _$$.$i;
var $$r = _$$.$r.or;
var $$s = _$$.$s;


/*-----*/


/**
 * ezMark (Minified) - A Simple Checkbox and Radio button Styling plugin. This plugin allows you to use a custom Image for
 * Checkbox or Radio button. Its very simple, small and easy to use.
 *
 * Copyright (c) Abdullah Rubiyath <http://www.itsalif.info/>.
 * Released under MIT License
 *
 * @author Abdullah Rubiyath
 * @version 1.0
 * @date June 27, 2010
 */
(function ($) {
    $.fn.ezMark = function (options) {
        options = options || {};
        var defaultOpt = {
            checkboxCls: options.checkboxCls || 'ez-checkbox',
            radioCls: options.radioCls || 'ez-radio',
            checkedCls: options.checkedCls || 'ez-checked',
            selectedCls: options.selectedCls || 'ez-selected',
            hideCls: 'ez-hide'
        };
        return this.each(function () {
            var $this = $(this);
            var wrapTag = $this.attr('type') == 'checkbox' ? '<div class="' + defaultOpt.checkboxCls + '">' : '<div class="' + defaultOpt.radioCls + '">';
            if ($this.attr('type') == 'checkbox') {
                $this.addClass(defaultOpt.hideCls).wrap(wrapTag).change(function () {
                    if ($(this).is(':checked')) {
                        $(this).parent().addClass(defaultOpt.checkedCls);
                    }
                    else {
                        $(this).parent().removeClass(defaultOpt.checkedCls);
                    }
                });
                if ($this.is(':checked')) {
                    $this.parent().addClass(defaultOpt.checkedCls);
                }
            }
            else if ($this.attr('type') == 'radio') {
                $this.addClass(defaultOpt.hideCls).wrap(wrapTag).change(function () {
                    $('input[name="' + $(this).attr('name') + '"]').each(function () {
                        if ($(this).is(':checked')) {
                            $(this).parent().addClass(defaultOpt.selectedCls);
                        } else {
                            $(this).parent().removeClass(defaultOpt.selectedCls);
                        }
                    });
                });
                if ($this.is(':checked')) {
                    $this.parent().addClass(defaultOpt.selectedCls);
                }
            }
        });
    }
})(jQuery);


/*-----*/


/*
 * jQuery selectBox - A cosmetic, styleable replacement for SELECT elements
 *
 * Licensed under the MIT license: http://opensource.org/licenses/MIT
 *
 * v1.2.0
 *
 * https://github.com/marcj/jquery-selectBox
 */
;(function ($) {

    /**
     * SelectBox class.
     *
     * @param {HTMLElement|jQuery} select If it's a jQuery object, we use the first element.
     * @param {Object}             options
     * @constructor
     */
    var SelectBox = this.SelectBox = function (select, options) {
        if (select instanceof jQuery) {
            if (select.length > 0) {
                select = select[0];
            } else {
                return;
            }
        }

        this.typeTimer = null;
        this.typeSearch = '';
        this.isMac = navigator.platform.match(/mac/i);
        options = 'object' === typeof options ? options : {};
        this.selectElement = select;

        // Disable for iOS devices (their native controls are more suitable for a touch device)
        if (!options.mobile && navigator.userAgent.match(/iPad|iPhone|Android|IEMobile|BlackBerry/i)) {
            return false;
        }

        // Element must be a select control
        if ('select' !== select.tagName.toLowerCase()) {
            return false;
        }

        this.init(options);
    };

    /**
     * @type {String}
     */
    SelectBox.prototype.version = '1.2.0';

    /**
     * @param {Object} options
     *
     * @returns {Boolean}
     */
    SelectBox.prototype.init = function (options) {
        var select = $(this.selectElement);
        if (select.data('selectBox-control')) {
            return false;
        }

        var control = $('<a class="selectBox" />')
            , inline = select.attr('multiple') || parseInt(select.attr('size')) > 1
            , settings = options || {}
            , tabIndex = parseInt(select.prop('tabindex')) || 0
            , self = this;

        control
            .width(select.outerWidth())
            .addClass(select.attr('class'))
            .attr('title', select.attr('title') || '')
            .attr('tabindex', tabIndex)
            .css('display', 'inline-block')
            .bind('focus.selectBox', function () {
                if (this !== document.activeElement && document.body !== document.activeElement) {
                    $(document.activeElement).blur();
                }
                if (control.hasClass('selectBox-active')) {
                    return;
                }
                control.addClass('selectBox-active');
                select.trigger('focus');
            })
            .bind('blur.selectBox', function () {
                if (!control.hasClass('selectBox-active')) {
                    return;
                }
                control.removeClass('selectBox-active');
                select.trigger('blur');
            });

        if (!$(window).data('selectBox-bindings')) {
            $(window)
                .data('selectBox-bindings', true)
                .bind('scroll.selectBox', (settings.hideOnWindowScroll) ? this.hideMenus : $.noop)
                .bind('resize.selectBox', this.hideMenus);
        }

        if (select.attr('disabled')) {
            control.addClass('selectBox-disabled');
        }

        // Focus on control when label is clicked
        select.bind('click.selectBox', function (event) {
            control.focus();
            event.preventDefault();
        });

        // Generate control
        if (inline) {
            // Inline controls
            options = this.getOptions('inline');

            control
                .append(options)
                .data('selectBox-options', options).addClass('selectBox-inline selectBox-menuShowing')
                .bind('keydown.selectBox', function (event) {
                    self.handleKeyDown(event);
                })
                .bind('keypress.selectBox', function (event) {
                    self.handleKeyPress(event);
                })
                .bind('mousedown.selectBox', function (event) {
                    if (1 !== event.which) {
                        return;
                    }
                    if ($(event.target).is('A.selectBox-inline')) {
                        event.preventDefault();
                    }
                    if (!control.hasClass('selectBox-focus')) {
                        control.focus();
                    }
                })
                .insertAfter(select);

            // Auto-height based on size attribute
            if (!select[0].style.height) {
                var size = select.attr('size') ? parseInt(select.attr('size')) : 5;
                // Draw a dummy control off-screen, measure, and remove it
                var tmp = control
                    .clone()
                    .removeAttr('id')
                    .css({
                        position: 'absolute',
                        top: '-9999em'
                    })
                    .show()
                    .appendTo('body');
                tmp.find('.selectBox-options').html('<li><a>\u00A0</a></li>');
                var optionHeight = parseInt(tmp.find('.selectBox-options A:first').html('&nbsp;').outerHeight());
                tmp.remove();
                control.height(optionHeight * size);
            }
            this.disableSelection(control);
        } else {
            // Dropdown controls
            var label = $('<span class="selectBox-label" />'),
                arrow = $('<span class="selectBox-arrow" />');

            // Update label
            label.attr('class', this.getLabelClass()).text(this.getLabelText());
            options = this.getOptions('dropdown');
            options.appendTo('BODY');

            control
                .data('selectBox-options', options)
                .addClass('selectBox-dropdown')
                .append(label)
                .append(arrow)
                .bind('mousedown.selectBox', function (event) {
                    if (1 === event.which) {
                        if (control.hasClass('selectBox-menuShowing')) {
                            self.hideMenus();
                        } else {
                            event.stopPropagation();
                            // Webkit fix to prevent premature selection of options
                            options
                                .data('selectBox-down-at-x', event.screenX)
                                .data('selectBox-down-at-y', event.screenY);
                            self.showMenu();
                        }
                    }
                })
                .bind('keydown.selectBox', function (event) {
                    self.handleKeyDown(event);
                })
                .bind('keypress.selectBox', function (event) {
                    self.handleKeyPress(event);
                })
                .bind('open.selectBox', function (event, triggerData) {
                    if (triggerData && triggerData._selectBox === true) {
                        return;
                    }
                    self.showMenu();
                })
                .bind('close.selectBox', function (event, triggerData) {
                    if (triggerData && triggerData._selectBox === true) {
                        return;
                    }
                    self.hideMenus();
                })
                .insertAfter(select);

            // Set label width
            var labelWidth =
                control.width()
                - arrow.outerWidth()
                - (parseInt(label.css('paddingLeft')) || 0)
                - (parseInt(label.css('paddingRight')) || 0);

            label.width(labelWidth);
            this.disableSelection(control);
        }
        // Store data for later use and show the control
        select
            .addClass('selectBox')
            .data('selectBox-control', control)
            .data('selectBox-settings', settings)
            .hide();
    };

    /**
     * @param {String} type 'inline'|'dropdown'
     * @returns {jQuery}
     */
    SelectBox.prototype.getOptions = function (type) {
        var options;
        var select = $(this.selectElement);
        var self = this;
        // Private function to handle recursion in the getOptions function.
        var _getOptions = function (select, options) {
            // Loop through the set in order of element children.
            select.children('OPTION, OPTGROUP').each(function () {
                // If the element is an option, add it to the list.
                if ($(this).is('OPTION')) {
                    // Check for a value in the option found.
                    if ($(this).length > 0) {
                        // Create an option form the found element.
                        self.generateOptions($(this), options);
                    } else {
                        // No option information found, so add an empty.
                        options.append('<li>\u00A0</li>');
                    }
                } else {
                    // If the element is an option group, add the group and call this function on it.
                    var optgroup = $('<li class="selectBox-optgroup" />');
                    optgroup.text($(this).attr('label'));
                    options.append(optgroup);
                    options = _getOptions($(this), options);
                }
            });
            // Return the built strin
            return options;
        };

        switch (type) {
            case 'inline':
                options = $('<ul class="selectBox-options" />');
                options = _getOptions(select, options);
                options
                    .find('A')
                    .bind('mouseover.selectBox', function (event) {
                        self.addHover($(this).parent());
                    })
                    .bind('mouseout.selectBox', function (event) {
                        self.removeHover($(this).parent());
                    })
                    .bind('mousedown.selectBox', function (event) {
                        if (1 !== event.which) {
                            return
                        }
                        event.preventDefault(); // Prevent options from being "dragged"
                        if (!select.selectBox('control').hasClass('selectBox-active')) {
                            select.selectBox('control').focus();
                        }
                    })
                    .bind('mouseup.selectBox', function (event) {
                        if (1 !== event.which) {
                            return;
                        }
                        self.hideMenus();
                        self.selectOption($(this).parent(), event);
                    });

                this.disableSelection(options);
                return options;
            case 'dropdown':
                options = $('<ul class="selectBox-dropdown-menu selectBox-options" />');
                options = _getOptions(select, options);

                options
                    .data('selectBox-select', select)
                    .css('display', 'none')
                    .appendTo('BODY')
                    .find('A')
                    .bind('mousedown.selectBox', function (event) {
                        if (event.which === 1) {
                            event.preventDefault(); // Prevent options from being "dragged"
                            if (event.screenX === options.data('selectBox-down-at-x') &&
                                event.screenY === options.data('selectBox-down-at-y')) {
                                options.removeData('selectBox-down-at-x').removeData('selectBox-down-at-y');
                                if (/android/i.test(navigator.userAgent.toLowerCase()) &&
                                    /chrome/i.test(navigator.userAgent.toLowerCase())) {
                                    self.selectOption($(this).parent());
                                }
                                self.hideMenus();
                            }
                        }
                    })
                    .bind('mouseup.selectBox', function (event) {
                        if (1 !== event.which) {
                            return;
                        }
                        if (event.screenX === options.data('selectBox-down-at-x') &&
                            event.screenY === options.data('selectBox-down-at-y')) {
                            return;
                        } else {
                            options.removeData('selectBox-down-at-x').removeData('selectBox-down-at-y');
                        }
                        self.selectOption($(this).parent());
                        self.hideMenus();
                    })
                    .bind('mouseover.selectBox', function (event) {
                        self.addHover($(this).parent());
                    })
                    .bind('mouseout.selectBox', function (event) {
                        self.removeHover($(this).parent());
                    });

                // Inherit classes for dropdown menu
                var classes = select.attr('class') || '';
                if ('' !== classes) {
                    classes = classes.split(' ');
                    for (var i = 0; i < classes.length; i++) {
                        options.addClass(classes[i] + '-selectBox-dropdown-menu');
                    }

                }
                this.disableSelection(options);
                return options;
        }
    };

    /**
     * Returns the current class of the selected option.
     *
     * @returns {String}
     */
    SelectBox.prototype.getLabelClass = function () {
        var selected = $(this.selectElement).find('OPTION:selected');
        return ('selectBox-label ' + (selected.attr('class') || '')).replace(/\s+$/, '');
    };

    /**
     * Returns the current label of the selected option.
     *
     * @returns {String}
     */
    SelectBox.prototype.getLabelText = function () {
        var selected = $(this.selectElement).find('OPTION:selected');
        return selected.text() || '\u00A0';
    };

    /**
     * Sets the label.
     * This method uses the getLabelClass() and getLabelText() methods.
     */
    SelectBox.prototype.setLabel = function () {
        var select = $(this.selectElement);
        var control = select.data('selectBox-control');
        if (!control) {
            return;
        }

        control
            .find('.selectBox-label')
            .attr('class', this.getLabelClass())
            .text(this.getLabelText());
    };

    /**
     * Destroys the SelectBox instance and shows the origin select element.
     *
     */
    SelectBox.prototype.destroy = function () {
        var select = $(this.selectElement);
        var control = select.data('selectBox-control');
        if (!control) {
            return;
        }

        var options = control.data('selectBox-options');
        options.remove();
        control.remove();
        select
            .removeClass('selectBox')
            .removeData('selectBox-control')
            .data('selectBox-control', null)
            .removeData('selectBox-settings')
            .data('selectBox-settings', null)
            .show();
    };

    /**
     * Refreshes the option elements.
     */
    SelectBox.prototype.refresh = function () {
        var select = $(this.selectElement)
            , control = select.data('selectBox-control')
            , type = control.hasClass('selectBox-dropdown') ? 'dropdown' : 'inline'
            , options;

        // Remove old options
        control.data('selectBox-options').remove();

        // Generate new options
        options = this.getOptions(type);
        control.data('selectBox-options', options);

        switch (type) {
            case 'inline':
                control.append(options);
                break;
            case 'dropdown':
                // Update label
                this.setLabel();
                $("BODY").append(options);
                break;
        }

        // Restore opened dropdown state (original menu was trashed)
        if ('dropdown' === type && control.hasClass('selectBox-menuShowing')) {
            this.showMenu();
        }
    };

    /**
     * Shows the dropdown menu.
     */
    SelectBox.prototype.showMenu = function () {
        var self = this
            , select = $(this.selectElement)
            , control = select.data('selectBox-control')
            , settings = select.data('selectBox-settings')
            , options = control.data('selectBox-options');

        if (control.hasClass('selectBox-disabled')) {
            return false;
        }

        this.hideMenus();

        // Get top and bottom width of selectBox
        var borderBottomWidth = parseInt(control.css('borderBottomWidth')) || 0;
        var borderTopWidth = parseInt(control.css('borderTopWidth')) || 0;

        // Get proper variables for keeping options in viewport
        var pos = control.offset()
            , topPositionCorrelation = (settings.topPositionCorrelation) ? settings.topPositionCorrelation : 0
            , bottomPositionCorrelation = (settings.bottomPositionCorrelation) ? settings.bottomPositionCorrelation : 0
            , optionsHeight = options.outerHeight()
            , controlHeight = control.outerHeight()
            , maxHeight = parseInt(options.css('max-height'))
            , scrollPos = $(window).scrollTop()
            , heightToTop = pos.top - scrollPos
            , heightToBottom = $(window).height() - (heightToTop + controlHeight)
            ,
            posTop = (heightToTop > heightToBottom) && (settings.keepInViewport == null ? true : settings.keepInViewport)
            , top = posTop
            ? pos.top - optionsHeight + borderTopWidth + topPositionCorrelation
            : pos.top + controlHeight - borderBottomWidth - bottomPositionCorrelation;


        // If the height to top and height to bottom are less than the max-height
        if (heightToTop < maxHeight && heightToBottom < maxHeight) {

            // Set max-height and top
            if (posTop) {
                var maxHeightDiff = maxHeight - (heightToTop - 5);
                options.css({'max-height': maxHeight - maxHeightDiff + 'px'});
                top = top + maxHeightDiff;
            } else {
                var maxHeightDiff = maxHeight - (heightToBottom - 5);
                options.css({'max-height': maxHeight - maxHeightDiff + 'px'});
            }

        }

        // Save if position is top to options data
        options.data('posTop', posTop);


        // Menu position
        options
            .width(control.innerWidth())
            .css({
                top: top,
                left: control.offset().left
            })
            // Add Top and Bottom class based on position
            .addClass('selectBox-options selectBox-options-' + (posTop ? 'top' : 'bottom'));


        if (select.triggerHandler('beforeopen')) {
            return false;
        }

        var dispatchOpenEvent = function () {
            select.triggerHandler('open', {
                _selectBox: true
            });
        };

        // Show menu
        switch (settings.menuTransition) {
            case 'fade':
                options.fadeIn(settings.menuSpeed, dispatchOpenEvent);
                break;
            case 'slide':
                options.slideDown(settings.menuSpeed, dispatchOpenEvent);
                break;
            default:
                options.show(settings.menuSpeed, dispatchOpenEvent);
                break;
        }

        if (!settings.menuSpeed) {
            dispatchOpenEvent();
        }

        // Center on selected option
        var li = options.find('.selectBox-selected:first');
        this.keepOptionInView(li, true);
        this.addHover(li);
        control.addClass('selectBox-menuShowing selectBox-menuShowing-' + (posTop ? 'top' : 'bottom'));

        $(document).bind('mousedown.selectBox', function (event) {
            if (1 === event.which) {
                if ($(event.target).parents().andSelf().hasClass('selectBox-options')) {
                    return;
                }
                self.hideMenus();
            }
        });
    };

    /**
     * Hides the menu of all instances.
     */
    SelectBox.prototype.hideMenus = function () {
        if ($(".selectBox-dropdown-menu:visible").length === 0) {
            return;
        }

        $(document).unbind('mousedown.selectBox');
        $(".selectBox-dropdown-menu").each(function () {
            var options = $(this)
                , select = options.data('selectBox-select')
                , control = select.data('selectBox-control')
                , settings = select.data('selectBox-settings')
                , posTop = options.data('posTop');

            if (select.triggerHandler('beforeclose')) {
                return false;
            }

            var dispatchCloseEvent = function () {
                select.triggerHandler('close', {
                    _selectBox: true
                });
            };
            if (settings) {
                switch (settings.menuTransition) {
                    case 'fade':
                        options.fadeOut(settings.menuSpeed, dispatchCloseEvent);
                        break;
                    case 'slide':
                        options.slideUp(settings.menuSpeed, dispatchCloseEvent);
                        break;
                    default:
                        options.hide(settings.menuSpeed, dispatchCloseEvent);
                        break;
                }
                if (!settings.menuSpeed) {
                    dispatchCloseEvent();
                }
                control.removeClass('selectBox-menuShowing selectBox-menuShowing-' + (posTop ? 'top' : 'bottom'));
            } else {
                $(this).hide();
                $(this).triggerHandler('close', {
                    _selectBox: true
                });
                $(this).removeClass('selectBox-menuShowing selectBox-menuShowing-' + (posTop ? 'top' : 'bottom'));
            }

            options.css('max-height', '');
            //Remove Top or Bottom class based on position
            options.removeClass('selectBox-options-' + (posTop ? 'top' : 'bottom'));
            options.data('posTop', false);
        });
    };

    /**
     * Selects an option.
     *
     * @param {HTMLElement} li
     * @param {DOMEvent}    event
     * @returns {Boolean}
     */
    SelectBox.prototype.selectOption = function (li, event) {
        var select = $(this.selectElement);
        li = $(li);

        var control = select.data('selectBox-control')
            , settings = select.data('selectBox-settings');

        if (control.hasClass('selectBox-disabled')) {
            return false;
        }

        if (0 === li.length || li.hasClass('selectBox-disabled')) {
            return false;
        }

        if (select.attr('multiple')) {
            // If event.shiftKey is true, this will select all options between li and the last li selected
            if (event.shiftKey && control.data('selectBox-last-selected')) {
                li.toggleClass('selectBox-selected');
                var affectedOptions;
                if (li.index() > control.data('selectBox-last-selected').index()) {
                    affectedOptions = li
                        .siblings()
                        .slice(control.data('selectBox-last-selected').index(), li.index());
                } else {
                    affectedOptions = li
                        .siblings()
                        .slice(li.index(), control.data('selectBox-last-selected').index());
                }
                affectedOptions = affectedOptions.not('.selectBox-optgroup, .selectBox-disabled');
                if (li.hasClass('selectBox-selected')) {
                    affectedOptions.addClass('selectBox-selected');
                } else {
                    affectedOptions.removeClass('selectBox-selected');
                }
            } else if ((this.isMac && event.metaKey) || (!this.isMac && event.ctrlKey)) {
                li.toggleClass('selectBox-selected');
            } else {
                li.siblings().removeClass('selectBox-selected');
                li.addClass('selectBox-selected');
            }
        } else {
            li.siblings().removeClass('selectBox-selected');
            li.addClass('selectBox-selected');
        }

        if (control.hasClass('selectBox-dropdown')) {
            control.find('.selectBox-label').text(li.text());
        }

        // Update original control's value
        var i = 0, selection = [];
        if (select.attr('multiple')) {
            control.find('.selectBox-selected A').each(function () {
                selection[i++] = $(this).attr('rel');
            });
        } else {
            selection = li.find('A').attr('rel');
        }

        // Remember most recently selected item
        control.data('selectBox-last-selected', li);

        // Change callback
        if (select.val() !== selection) {
            select.val(selection);
            this.setLabel();
            select.trigger('change');
        }

        return true;
    };

    /**
     * Adds the hover class.
     *
     * @param {HTMLElement} li
     */
    SelectBox.prototype.addHover = function (li) {
        li = $(li);
        var select = $(this.selectElement)
            , control = select.data('selectBox-control')
            , options = control.data('selectBox-options');

        options.find('.selectBox-hover').removeClass('selectBox-hover');
        li.addClass('selectBox-hover');
    };

    /**
     * Returns the original HTML select element.
     *
     * @returns {HTMLElement}
     */
    SelectBox.prototype.getSelectElement = function () {
        return this.selectElement;
    };

    /**
     * Remove the hover class.
     *
     * @param {HTMLElement} li
     */
    SelectBox.prototype.removeHover = function (li) {
        li = $(li);
        var select = $(this.selectElement)
            , control = select.data('selectBox-control')
            , options = control.data('selectBox-options');

        options.find('.selectBox-hover').removeClass('selectBox-hover');
    };

    /**
     * Checks if the widget is in the view.
     *
     * @param {jQuery}      li
     * @param {Boolean}     center
     */
    SelectBox.prototype.keepOptionInView = function (li, center) {
        if (!li || li.length === 0) {
            return;
        }

        var select = $(this.selectElement)
            , control = select.data('selectBox-control')
            , options = control.data('selectBox-options')
            , scrollBox = control.hasClass('selectBox-dropdown') ? options : options.parent()
            , top = parseInt(li.offset().top - scrollBox.position().top)
            , bottom = parseInt(top + li.outerHeight());

        if (center) {
            scrollBox.scrollTop(li.offset().top - scrollBox.offset().top + scrollBox.scrollTop() -
                (scrollBox.height() / 2));
        } else {
            if (top < 0) {
                scrollBox.scrollTop(li.offset().top - scrollBox.offset().top + scrollBox.scrollTop());
            }
            if (bottom > scrollBox.height()) {
                scrollBox.scrollTop((li.offset().top + li.outerHeight()) - scrollBox.offset().top +
                    scrollBox.scrollTop() - scrollBox.height());
            }
        }
    };

    /**
     * Handles the keyDown event.
     * Handles open/close and arrow key functionality
     *
     * @param {DOMEvent}    event
     */
    SelectBox.prototype.handleKeyDown = function (event) {
        var select = $(this.selectElement)
            , control = select.data('selectBox-control')
            , options = control.data('selectBox-options')
            , settings = select.data('selectBox-settings')
            , totalOptions = 0, i = 0;

        if (control.hasClass('selectBox-disabled')) {
            return;
        }

        switch (event.keyCode) {
            case 8:
                // backspace
                event.preventDefault();
                this.typeSearch = '';
                break;
            case 9:
            // tab
            case 27:
                // esc
                this.hideMenus();
                this.removeHover();
                break;
            case 13:
                // enter
                if (control.hasClass('selectBox-menuShowing')) {
                    this.selectOption(options.find('LI.selectBox-hover:first'), event);
                    if (control.hasClass('selectBox-dropdown')) {
                        this.hideMenus();
                    }
                } else {
                    this.showMenu();
                }
                break;
            case 38:
            // up
            case 37:
                // left
                event.preventDefault();
                if (control.hasClass('selectBox-menuShowing')) {
                    var prev = options.find('.selectBox-hover').prev('LI');
                    totalOptions = options.find('LI:not(.selectBox-optgroup)').length;
                    i = 0;
                    while (prev.length === 0 || prev.hasClass('selectBox-disabled') ||
                    prev.hasClass('selectBox-optgroup')) {
                        prev = prev.prev('LI');
                        if (prev.length === 0) {
                            if (settings.loopOptions) {
                                prev = options.find('LI:last');
                            } else {
                                prev = options.find('LI:first');
                            }
                        }
                        if (++i >= totalOptions) {
                            break;
                        }
                    }
                    this.addHover(prev);
                    this.selectOption(prev, event);
                    this.keepOptionInView(prev);
                } else {
                    this.showMenu();
                }
                break;
            case 40:
            // down
            case 39:
                // right
                event.preventDefault();
                if (control.hasClass('selectBox-menuShowing')) {
                    var next = options.find('.selectBox-hover').next('LI');
                    totalOptions = options.find('LI:not(.selectBox-optgroup)').length;
                    i = 0;
                    while (0 === next.length || next.hasClass('selectBox-disabled') ||
                    next.hasClass('selectBox-optgroup')) {
                        next = next.next('LI');
                        if (next.length === 0) {
                            if (settings.loopOptions) {
                                next = options.find('LI:first');
                            } else {
                                next = options.find('LI:last');
                            }
                        }
                        if (++i >= totalOptions) {
                            break;
                        }
                    }
                    this.addHover(next);
                    this.selectOption(next, event);
                    this.keepOptionInView(next);
                } else {
                    this.showMenu();
                }
                break;
        }
    };

    /**
     * Handles the keyPress event.
     * Handles type-to-find functionality
     *
     * @param {DOMEvent}    event
     */
    SelectBox.prototype.handleKeyPress = function (event) {
        var select = $(this.selectElement)
            , control = select.data('selectBox-control')
            , options = control.data('selectBox-options')
            , self = this;

        if (control.hasClass('selectBox-disabled')) {
            return;
        }

        switch (event.keyCode) {
            case 9:
            // tab
            case 27:
            // esc
            case 13:
            // enter
            case 38:
            // up
            case 37:
            // left
            case 40:
            // down
            case 39:
                // right
                // Don't interfere with the keydown event!
                break;
            default:
                // Type to find
                if (!control.hasClass('selectBox-menuShowing')) {
                    this.showMenu();
                }
                event.preventDefault();
                clearTimeout(this.typeTimer);
                this.typeSearch += String.fromCharCode(event.charCode || event.keyCode);
                options.find('A').each(function () {
                    if ($(this).text().substr(0, self.typeSearch.length).toLowerCase() === self.typeSearch.toLowerCase()) {
                        self.addHover($(this).parent());
                        self.selectOption($(this).parent(), event);
                        self.keepOptionInView($(this).parent());
                        return false;
                    }
                });
                // Clear after a brief pause
                this.typeTimer = setTimeout(function () {
                    self.typeSearch = '';
                }, 1000);
                break;
        }
    };

    /**
     * Enables the selectBox.
     */
    SelectBox.prototype.enable = function () {
        var select = $(this.selectElement);
        select.prop('disabled', false);
        var control = select.data('selectBox-control');
        if (!control) {
            return;
        }
        control.removeClass('selectBox-disabled');
    };

    /**
     * Disables the selectBox.
     */
    SelectBox.prototype.disable = function () {
        var select = $(this.selectElement);
        select.prop('disabled', true);
        var control = select.data('selectBox-control');
        if (!control) {
            return;
        }
        control.addClass('selectBox-disabled');
    };

    /**
     * Sets the current value.
     *
     * @param {String}      value
     */
    SelectBox.prototype.setValue = function (value) {
        var select = $(this.selectElement);
        select.val(value);
        value = select.val(); // IE9's select would be null if it was set with a non-exist options value

        if (null === value) { // So check it here and set it with the first option's value if possible
            value = select.children().first().val();
            select.val(value);
        }

        var control = select.data('selectBox-control');
        if (!control) {
            return;
        }

        var settings = select.data('selectBox-settings')
            , options = control.data('selectBox-options');

        // Update label
        this.setLabel();

        // Update control values
        options.find('.selectBox-selected').removeClass('selectBox-selected');
        options.find('A').each(function () {
            if (typeof(value) === 'object') {
                for (var i = 0; i < value.length; i++) {
                    if ($(this).attr('rel') == value[i]) {
                        $(this).parent().addClass('selectBox-selected');
                    }
                }
            } else {
                if ($(this).attr('rel') == value) {
                    $(this).parent().addClass('selectBox-selected');
                }
            }
        });

        if (settings.change) {
            settings.change.call(select);
        }
    };

    /**
     * Sets the option elements.
     *
     * @param {String|Object} options
     */
    SelectBox.prototype.setOptions = function (options) {
        var select = $(this.selectElement)
            , control = select.data('selectBox-control');

        switch (typeof(options)) {
            case 'string':
                select.html(options);
                break;
            case 'object':
                select.html('');
                for (var i in options) {
                    if (options[i] === null) {
                        continue;
                    }
                    if (typeof(options[i]) === 'object') {
                        var optgroup = $('<optgroup label="' + i + '" />');
                        for (var j in options[i]) {
                            optgroup.append('<option value="' + j + '">' + options[i][j] + '</option>');
                        }
                        select.append(optgroup);
                    } else {
                        var option = $('<option value="' + i + '">' + options[i] + '</option>');
                        select.append(option);
                    }
                }
                break;
        }

        if (control) {
            // Refresh the control
            this.refresh();
        }
    };

    /**
     * Disables the selection.
     *
     * @param {*} selector
     */
    SelectBox.prototype.disableSelection = function (selector) {
        $(selector).css('MozUserSelect', 'none').bind('selectstart', function (event) {
            event.preventDefault();
        });
    };

    /**
     * Generates the options.
     *
     * @param {jQuery} self
     * @param {jQuery} options
     */
    SelectBox.prototype.generateOptions = function (self, options) {
        var li = $('<li />'), a = $('<a />');
        li.addClass(self.attr('class'));
        li.data(self.data());
        a.attr('rel', self.val()).text(self.text());
        li.append(a);
        if (self.attr('disabled')) {
            li.addClass('selectBox-disabled');
        }
        if (self.attr('selected')) {
            li.addClass('selectBox-selected');
        }
        options.append(li);
    };

    /**
     * Extends the jQuery.fn object.
     */
    $.extend($.fn, {
        selectBox: function (method, options) {
            var selectBox;

            switch (method) {
                case 'control':
                    return $(this).data('selectBox-control');
                case 'settings':
                    if (!options) {
                        return $(this).data('selectBox-settings');
                    }
                    $(this).each(function () {
                        $(this).data('selectBox-settings', $.extend(true, $(this).data('selectBox-settings'), options));
                    });
                    break;
                case 'options':
                    // Getter
                    if (undefined === options) {
                        return $(this).data('selectBox-control').data('selectBox-options');
                    }
                    // Setter
                    $(this).each(function () {
                        if (selectBox = $(this).data('selectBox')) {
                            selectBox.setOptions(options);
                        }
                    });
                    break;
                case 'value':
                    // Empty string is a valid value
                    if (undefined === options) {
                        return $(this).val();
                    }
                    $(this).each(function () {
                        if (selectBox = $(this).data('selectBox')) {
                            selectBox.setValue(options);
                        }
                    });
                    break;
                case 'refresh':
                    $(this).each(function () {
                        if (selectBox = $(this).data('selectBox')) {
                            selectBox.refresh();
                        }
                    });
                    break;
                case 'enable':
                    $(this).each(function () {
                        if (selectBox = $(this).data('selectBox')) {
                            selectBox.enable(this);
                        }
                    });
                    break;
                case 'disable':
                    $(this).each(function () {
                        if (selectBox = $(this).data('selectBox')) {
                            selectBox.disable();
                        }
                    });
                    break;
                case 'destroy':
                    $(this).each(function () {
                        if (selectBox = $(this).data('selectBox')) {
                            selectBox.destroy();
                            $(this).data('selectBox', null);
                        }
                    });
                    break;
                case 'instance':
                    return $(this).data('selectBox');
                default:
                    $(this).each(function (idx, select) {
                        if (!$(select).data('selectBox')) {
                            $(select).data('selectBox', new SelectBox(select, method));
                        }
                    });
                    break;
            }
            return $(this);
        }
    });
})(jQuery);


/*-----*/


/*
	Masked Input plugin for jQuery
	Copyright (c) 2007-2013 Josh Bush (digitalbush.com)
	Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license)
	Version: 1.3.1
*/
(function ($) {
    function getPasteEvent() {
        var el = document.createElement('input'),
            name = 'onpaste';
        el.setAttribute(name, '');
        return (typeof el[name] === 'function') ? 'paste' : 'input';
    }

    var pasteEventName = getPasteEvent() + ".mask",
        ua = navigator.userAgent,
        iPhone = /iphone/i.test(ua),
        android = /android/i.test(ua),
        caretTimeoutId;

    $.mask = {
        //Predefined character definitions
        definitions: {
            '9': "[0-9]",
            'a': "[A-Za-z]",
            '*': "[A-Za-z0-9]"
        },
        dataName: "rawMaskFn",
        placeholder: '_',
    };

    $.fn.extend({
        //Helper Function for Caret positioning
        caret: function (begin, end) {
            var range;

            if (this.length === 0 || this.is(":hidden")) {
                return;
            }

            if (typeof begin == 'number') {
                end = (typeof end === 'number') ? end : begin;
                return this.each(function () {
                    if (this.setSelectionRange) {
                        this.setSelectionRange(begin, end);
                    } else if (this.createTextRange) {
                        range = this.createTextRange();
                        range.collapse(true);
                        range.moveEnd('character', end);
                        range.moveStart('character', begin);
                        range.select();
                    }
                });
            } else {
                if (this[0].setSelectionRange) {
                    begin = this[0].selectionStart;
                    end = this[0].selectionEnd;
                } else if (document.selection && document.selection.createRange) {
                    range = document.selection.createRange();
                    begin = 0 - range.duplicate().moveStart('character', -100000);
                    end = begin + range.text.length;
                }
                return {begin: begin, end: end};
            }
        },
        unmask: function () {
            return this.trigger("unmask");
        },
        mask: function (mask, settings) {
            var input,
                defs,
                tests,
                partialPosition,
                firstNonMaskPos,
                len;

            if (!mask && this.length > 0) {
                input = $(this[0]);
                return input.data($.mask.dataName)();
            }
            settings = $.extend({
                placeholder: $.mask.placeholder, // Load default placeholder
                completed: null
            }, settings);


            defs = $.mask.definitions;
            tests = [];
            partialPosition = len = mask.length;
            firstNonMaskPos = null;

            $.each(mask.split(""), function (i, c) {
                if (c == '?') {
                    len--;
                    partialPosition = i;
                } else if (defs[c]) {
                    tests.push(new RegExp(defs[c]));
                    if (firstNonMaskPos === null) {
                        firstNonMaskPos = tests.length - 1;
                    }
                } else {
                    tests.push(null);
                }
            });

            return this.trigger("unmask").each(function () {
                var input = $(this),
                    buffer = $.map(
                        mask.split(""),
                        function (c, i) {
                            if (c != '?') {
                                return defs[c] ? settings.placeholder : c;
                            }
                        }),
                    focusText = input.val();

                function seekNext(pos) {
                    while (++pos < len && !tests[pos]) ;
                    return pos;
                }

                function seekPrev(pos) {
                    while (--pos >= 0 && !tests[pos]) ;
                    return pos;
                }

                function shiftL(begin, end) {
                    var i,
                        j;

                    if (begin < 0) {
                        return;
                    }

                    for (i = begin, j = seekNext(end); i < len; i++) {
                        if (tests[i]) {
                            if (j < len && tests[i].test(buffer[j])) {
                                buffer[i] = buffer[j];
                                buffer[j] = settings.placeholder;
                            } else {
                                break;
                            }

                            j = seekNext(j);
                        }
                    }
                    writeBuffer();
                    input.caret(Math.max(firstNonMaskPos, begin));
                }

                function shiftR(pos) {
                    var i,
                        c,
                        j,
                        t;

                    for (i = pos, c = settings.placeholder; i < len; i++) {
                        if (tests[i]) {
                            j = seekNext(i);
                            t = buffer[i];
                            buffer[i] = c;
                            if (j < len && tests[j].test(t)) {
                                c = t;
                            } else {
                                break;
                            }
                        }
                    }
                }

                function keydownEvent(e) {
                    var k = e.which,
                        pos,
                        begin,
                        end;

                    //backspace, delete, and escape get special treatment
                    if (k === 8 || k === 46 || (iPhone && k === 127)) {
                        pos = input.caret();
                        begin = pos.begin;
                        end = pos.end;

                        if (end - begin === 0) {
                            begin = k !== 46 ? seekPrev(begin) : (end = seekNext(begin - 1));
                            end = k === 46 ? seekNext(end) : end;
                        }
                        clearBuffer(begin, end);
                        shiftL(begin, end - 1);

                        e.preventDefault();
                    } else if (k == 27) {//escape
                        input.val(focusText);
                        input.caret(0, checkVal());
                        e.preventDefault();
                    }
                }

                function keypressEvent(e) {
                    var k = e.which,
                        pos = input.caret(),
                        p,
                        c,
                        next;

                    if (e.ctrlKey || e.altKey || e.metaKey || k < 32) {//Ignore
                        return;
                    } else if (k) {
                        if (pos.end - pos.begin !== 0) {
                            clearBuffer(pos.begin, pos.end);
                            shiftL(pos.begin, pos.end - 1);
                        }

                        p = seekNext(pos.begin - 1);
                        if (p < len) {
                            c = String.fromCharCode(k);
                            if (tests[p].test(c)) {
                                shiftR(p);

                                buffer[p] = c;
                                writeBuffer();
                                next = seekNext(p);

                                if (android) {
                                    setTimeout($.proxy($.fn.caret, input, next), 0);
                                } else {
                                    input.caret(next);
                                }

                                if (settings.completed && next >= len) {
                                    settings.completed.call(input);
                                }
                            }
                        }
                        e.preventDefault();
                    }
                }

                function clearBuffer(start, end) {
                    var i;
                    for (i = start; i < end && i < len; i++) {
                        if (tests[i]) {
                            buffer[i] = settings.placeholder;
                        }
                    }
                }

                function writeBuffer() {
                    input.val(buffer.join(''));
                }

                function checkVal(allow) {
                    //try to place characters where they belong
                    var test = input.val(),
                        lastMatch = -1,
                        i,
                        c;

                    for (i = 0, pos = 0; i < len; i++) {
                        if (tests[i]) {
                            buffer[i] = settings.placeholder;
                            while (pos++ < test.length) {
                                c = test.charAt(pos - 1);
                                if (tests[i].test(c)) {
                                    buffer[i] = c;
                                    lastMatch = i;
                                    break;
                                }
                            }
                            if (pos > test.length) {
                                break;
                            }
                        } else if (buffer[i] === test.charAt(pos) && i !== partialPosition) {
                            pos++;
                            lastMatch = i;
                        }
                    }
                    if (allow) {
                        writeBuffer();
                    } else if (lastMatch + 1 < partialPosition) {
                        input.val("");
                        clearBuffer(0, len);
                    } else {
                        writeBuffer();
                        input.val(input.val().substring(0, lastMatch + 1));
                    }
                    return (partialPosition ? i : firstNonMaskPos);
                }

                input.data($.mask.dataName, function () {
                    return $.map(buffer, function (c, i) {
                        return tests[i] && c != settings.placeholder ? c : null;
                    }).join('');
                });

                if (!input.attr("readonly"))
                    input
                        .one("unmask", function () {
                            input
                                .unbind(".mask")
                                .removeData($.mask.dataName);
                        })
                        .bind("focus.mask", function () {
                            clearTimeout(caretTimeoutId);
                            var pos,
                                moveCaret;

                            focusText = input.val();
                            pos = checkVal();

                            caretTimeoutId = setTimeout(function () {
                                writeBuffer();
                                if (pos == mask.length) {
                                    input.caret(0, pos);
                                } else {
                                    input.caret(pos);
                                }
                            }, 10);
                        })
                        .bind("blur.mask", function () {
                            checkVal();
                            if (input.val() != focusText)
                                input.change();
                        })
                        .bind("keydown.mask", keydownEvent)
                        .bind("keypress.mask", keypressEvent)
                        .bind(pasteEventName, function () {
                            setTimeout(function () {
                                var pos = checkVal(true);
                                input.caret(pos);
                                if (settings.completed && pos == input.val().length)
                                    settings.completed.call(input);
                            }, 0);
                        });
                checkVal(); //Perform initial check for existing values
            });
        }
    });


})(jQuery);


/*-----*/


(function ($) {

    $.fn.customScrollbar = function (options, args) {

        var defaultOptions = {
            skin: undefined,
            hScroll: true,
            vScroll: true,
            updateOnWindowResize: false,
            animationSpeed: 300,
            onCustomScroll: undefined,
            swipeSpeed: 1,
            wheelSpeed: 40,
            fixedThumbWidth: undefined,
            fixedThumbHeight: undefined
        }

        var Scrollable = function (element, options) {
            this.$element = $(element);
            this.options = options;
            this.addScrollableClass();
            this.addSkinClass();
            this.addScrollBarComponents();
            if (this.options.vScroll)
                this.vScrollbar = new Scrollbar(this, new VSizing());
            if (this.options.hScroll)
                this.hScrollbar = new Scrollbar(this, new HSizing());
            this.$element.data("scrollable", this);
            this.initKeyboardScrolling();
            this.bindEvents();
        }

        Scrollable.prototype = {

            addScrollableClass: function () {
                if (!this.$element.hasClass("scrollable")) {
                    this.scrollableAdded = true;
                    this.$element.addClass("scrollable");
                }
            },

            removeScrollableClass: function () {
                if (this.scrollableAdded)
                    this.$element.removeClass("scrollable");
            },

            addSkinClass: function () {
                if (typeof(this.options.skin) == "string" && !this.$element.hasClass(this.options.skin)) {
                    this.skinClassAdded = true;
                    this.$element.addClass(this.options.skin);
                }
            },

            removeSkinClass: function () {
                if (this.skinClassAdded)
                    this.$element.removeClass(this.options.skin);
            },

            addScrollBarComponents: function () {
                this.assignViewPort();
                if (this.$viewPort.length == 0) {
                    this.$element.wrapInner("<div class=\"viewport\" />");
                    this.assignViewPort();
                    this.viewPortAdded = true;
                }
                this.assignOverview();
                if (this.$overview.length == 0) {
                    this.$viewPort.wrapInner("<div class=\"overview\" />");
                    this.assignOverview();
                    this.overviewAdded = true;
                }
                this.addScrollBar("vertical", "prepend");
                this.addScrollBar("horizontal", "append");
            },

            removeScrollbarComponents: function () {
                this.removeScrollbar("vertical");
                this.removeScrollbar("horizontal");
                if (this.overviewAdded)
                    this.$element.unwrap();
                if (this.viewPortAdded)
                    this.$element.unwrap();
            },

            removeScrollbar: function (orientation) {
                if (this[orientation + "ScrollbarAdded"])
                    this.$element.find(".scroll-bar." + orientation).remove();
            },

            assignViewPort: function () {
                this.$viewPort = this.$element.find(".viewport");
            },

            assignOverview: function () {
                this.$overview = this.$viewPort.find(".overview");
            },

            addScrollBar: function (orientation, fun) {
                if (this.$element.find(".scroll-bar." + orientation).length == 0) {
                    this.$element[fun]("<div class='scroll-bar " + orientation + "'><div class='thumb'></div></div>")
                    this[orientation + "ScrollbarAdded"] = true;
                }
            },

            resize: function (keepPosition) {
                if (this.vScrollbar)
                    this.vScrollbar.resize(keepPosition);
                if (this.hScrollbar)
                    this.hScrollbar.resize(keepPosition);
            },

            scrollTo: function (element) {
                if (this.vScrollbar)
                    this.vScrollbar.scrollToElement(element);
                if (this.hScrollbar)
                    this.hScrollbar.scrollToElement(element);
            },

            scrollToXY: function (x, y) {
                this.scrollToX(x);
                this.scrollToY(y);
            },

            scrollToX: function (x) {
                if (this.hScrollbar)
                    this.hScrollbar.scrollOverviewTo(x, true);
            },

            scrollToY: function (y) {
                if (this.vScrollbar)
                    this.vScrollbar.scrollOverviewTo(y, true);
            },

            remove: function () {
                this.removeScrollableClass();
                this.removeSkinClass();
                this.removeScrollbarComponents();
                this.$element.data("scrollable", null);
                this.removeKeyboardScrolling();
                if (this.vScrollbar)
                    this.vScrollbar.remove();
                if (this.hScrollbar)
                    this.hScrollbar.remove();
            },

            setAnimationSpeed: function (speed) {
                this.options.animationSpeed = speed;
            },

            isInside: function (element, wrappingElement) {
                var $element = $(element);
                var $wrappingElement = $(wrappingElement);
                var elementOffset = $element.offset();
                var wrappingElementOffset = $wrappingElement.offset();
                return (elementOffset.top >= wrappingElementOffset.top) && (elementOffset.left >= wrappingElementOffset.left) &&
                    (elementOffset.top + $element.height() <= wrappingElementOffset.top + $wrappingElement.height()) &&
                    (elementOffset.left + $element.width() <= wrappingElementOffset.left + $wrappingElement.width())
            },

            initKeyboardScrolling: function () {
                var _this = this;

                this.elementKeydown = function (event) {
                    if (document.activeElement === _this.$element[0]) {
                        if (_this.vScrollbar)
                            _this.vScrollbar.keyScroll(event);
                        if (_this.hScrollbar)
                            _this.hScrollbar.keyScroll(event);
                    }
                }

                this.$element
                    .attr('tabindex', '-1')
                    .keydown(this.elementKeydown);
            },

            removeKeyboardScrolling: function () {
                this.$element
                    .removeAttr('tabindex')
                    .unbind("keydown", this.elementKeydown);
            },

            bindEvents: function () {
                if (this.options.onCustomScroll)
                    this.$element.on("customScroll", this.options.onCustomScroll);
            }

        }

        var Scrollbar = function (scrollable, sizing) {
            this.scrollable = scrollable;
            this.sizing = sizing
            this.$scrollBar = this.sizing.scrollBar(this.scrollable.$element);
            this.$thumb = this.$scrollBar.find(".thumb");
            this.setScrollPosition(0, 0);
            this.resize();
            this.initMouseMoveScrolling();
            this.initMouseWheelScrolling();
            this.initTouchScrolling();
            this.initMouseClickScrolling();
            this.initWindowResize();
        }

        Scrollbar.prototype = {

            resize: function (keepPosition) {
                this.scrollable.$viewPort.height(this.scrollable.$element.height());
                this.sizing.size(this.scrollable.$viewPort, this.sizing.size(this.scrollable.$element));
                this.viewPortSize = this.sizing.size(this.scrollable.$viewPort);
                this.overviewSize = this.sizing.size(this.scrollable.$overview);
                this.ratio = this.viewPortSize / this.overviewSize;
                this.sizing.size(this.$scrollBar, this.viewPortSize);
                this.thumbSize = this.calculateThumbSize();
                this.sizing.size(this.$thumb, this.thumbSize);
                this.maxThumbPosition = this.calculateMaxThumbPosition();
                this.maxOverviewPosition = this.calculateMaxOverviewPosition();
                this.enabled = (this.overviewSize > this.viewPortSize);
                if (this.scrollPercent === undefined)
                    this.scrollPercent = 0.0;
                if (this.enabled)
                    this.rescroll(keepPosition);
                else
                    this.setScrollPosition(0, 0);
                this.$scrollBar.toggle(this.enabled);
            },

            calculateThumbSize: function () {
                var fixedSize = this.sizing.fixedThumbSize(this.scrollable.options)
                var size;
                if (fixedSize)
                    size = fixedSize;
                else
                    size = this.ratio * this.viewPortSize
                return Math.max(size, this.sizing.minSize(this.$thumb));
            },

            initMouseMoveScrolling: function () {
                var _this = this;
                this.$thumb.mousedown(function (event) {
                    if (_this.enabled)
                        _this.startMouseMoveScrolling(event);
                });
                this.documentMouseup = function (event) {
                    _this.stopMouseMoveScrolling(event);
                };
                $(document).mouseup(this.documentMouseup);
                this.documentMousemove = function (event) {
                    _this.mouseMoveScroll(event);
                };
                $(document).mousemove(this.documentMousemove);
                this.$thumb.click(function (event) {
                    event.stopPropagation();
                });
            },

            removeMouseMoveScrolling: function () {
                this.$thumb.unbind();
                $(document).unbind("mouseup", this.documentMouseup);
                $(document).unbind("mousemove", this.documentMousemove);
            },

            initMouseWheelScrolling: function () {
                var _this = this;
                this.scrollable.$element.mousewheel(function (event, delta, deltaX, deltaY) {
                    if (_this.enabled) {
                        if (_this.mouseWheelScroll(deltaX, deltaY)) {
                            event.stopPropagation();
                            event.preventDefault();
                        }
                    }
                });
            },

            removeMouseWheelScrolling: function () {
                this.scrollable.$element.unbind("mousewheel");
            },

            initTouchScrolling: function () {
                if (document.addEventListener) {
                    var _this = this;
                    this.elementTouchstart = function (event) {
                        if (_this.enabled)
                            _this.startTouchScrolling(event);
                    }
                    this.scrollable.$element[0].addEventListener("touchstart", this.elementTouchstart);
                    this.documentTouchmove = function (event) {
                        _this.touchScroll(event);
                    }
                    document.addEventListener("touchmove", this.documentTouchmove);
                    this.elementTouchend = function (event) {
                        _this.stopTouchScrolling(event);
                    }
                    this.scrollable.$element[0].addEventListener("touchend", this.elementTouchend);
                }
            },

            removeTouchScrolling: function () {
                if (document.addEventListener) {
                    this.scrollable.$element[0].removeEventListener("touchstart", this.elementTouchstart);
                    document.removeEventListener("touchmove", this.documentTouchmove);
                    this.scrollable.$element[0].removeEventListener("touchend", this.elementTouchend);
                }
            },

            initMouseClickScrolling: function () {
                var _this = this;
                this.scrollBarClick = function (event) {
                    _this.mouseClickScroll(event);
                };
                this.$scrollBar.click(this.scrollBarClick);
            },

            removeMouseClickScrolling: function () {
                this.$scrollBar.unbind("click", this.scrollBarClick);
            },

            initWindowResize: function () {
                if (this.scrollable.options.updateOnWindowResize) {
                    var _this = this;
                    this.windowResize = function () {
                        _this.resize();
                    };
                    $(window).resize(this.windowResize);
                }
            },

            removeWindowResize: function () {
                $(window).unbind("resize", this.windowResize);
            },

            isKeyScrolling: function (key) {
                return this.keyScrollDelta(key) != null;
            },

            keyScrollDelta: function (key) {
                for (var scrollingKey in this.sizing.scrollingKeys)
                    if (scrollingKey == key)
                        return this.sizing.scrollingKeys[key](this.viewPortSize);
                return null;
            },

            startMouseMoveScrolling: function (event) {
                this.mouseMoveScrolling = true;
                $("html").addClass("not-selectable");
                this.setUnselectable($("html"), "on");
                this.setScrollEvent(event);
            },

            stopMouseMoveScrolling: function (event) {
                this.mouseMoveScrolling = false;
                $("html").removeClass("not-selectable");
                this.setUnselectable($("html"), null);
            },

            setUnselectable: function (element, value) {
                if (element.attr("unselectable") != value) {
                    element.attr("unselectable", value);
                    element.find(':not(input)').attr('unselectable', value);
                }
            },

            mouseMoveScroll: function (event) {
                if (this.mouseMoveScrolling) {
                    var delta = this.sizing.mouseDelta(this.scrollEvent, event);
                    this.scrollThumbBy(delta);
                    this.setScrollEvent(event);
                }
            },

            startTouchScrolling: function (event) {
                if (event.touches && event.touches.length == 1) {
                    this.setScrollEvent(event.touches[0]);
                    this.touchScrolling = true;
                    event.stopPropagation();
                }
            },

            touchScroll: function (event) {
                if (this.touchScrolling && event.touches && event.touches.length == 1) {
                    var delta = -this.sizing.mouseDelta(this.scrollEvent, event.touches[0]) * this.scrollable.options.swipeSpeed;
                    var scrolled = this.scrollOverviewBy(delta);
                    if (scrolled) {
                        event.stopPropagation();
                        event.preventDefault();
                        this.setScrollEvent(event.touches[0]);
                    }
                }
            },

            stopTouchScrolling: function (event) {
                this.touchScrolling = false;
                event.stopPropagation();
            },

            mouseWheelScroll: function (deltaX, deltaY) {
                var delta = -this.sizing.wheelDelta(deltaX, deltaY) * this.scrollable.options.wheelSpeed;
                if (delta != 0)
                    return this.scrollOverviewBy(delta);
            },

            mouseClickScroll: function (event) {
                var delta = this.viewPortSize - 20;
                if (event["page" + this.sizing.scrollAxis()] < this.$thumb.offset()[this.sizing.offsetComponent()])
                // mouse click over thumb
                    delta = -delta;
                this.scrollOverviewBy(delta);
            },

            keyScroll: function (event) {
                var keyDown = event.which;
                if (this.enabled && this.isKeyScrolling(keyDown)) {
                    if (this.scrollOverviewBy(this.keyScrollDelta(keyDown)))
                        event.preventDefault();
                }
            },

            scrollThumbBy: function (delta) {
                var thumbPosition = this.thumbPosition();
                thumbPosition += delta;
                thumbPosition = this.positionOrMax(thumbPosition, this.maxThumbPosition);
                var oldScrollPercent = this.scrollPercent;
                this.scrollPercent = thumbPosition / this.maxThumbPosition;
                var overviewPosition = (thumbPosition * this.maxOverviewPosition) / this.maxThumbPosition;
                this.setScrollPosition(overviewPosition, thumbPosition);
                if (oldScrollPercent != this.scrollPercent) {
                    this.triggerCustomScroll(oldScrollPercent);
                    return true
                }
                else
                    return false;
            },

            thumbPosition: function () {
                return this.$thumb.position()[this.sizing.offsetComponent()];
            },

            scrollOverviewBy: function (delta) {
                var overviewPosition = this.overviewPosition() + delta;
                return this.scrollOverviewTo(overviewPosition, false);
            },

            overviewPosition: function () {
                return -this.scrollable.$overview.position()[this.sizing.offsetComponent()];
            },

            scrollOverviewTo: function (overviewPosition, animate) {
                overviewPosition = this.positionOrMax(overviewPosition, this.maxOverviewPosition);
                var oldScrollPercent = this.scrollPercent;
                this.scrollPercent = overviewPosition / this.maxOverviewPosition;
                var thumbPosition = this.scrollPercent * this.maxThumbPosition;
                if (animate)
                    this.setScrollPositionWithAnimation(overviewPosition, thumbPosition);
                else
                    this.setScrollPosition(overviewPosition, thumbPosition);
                if (oldScrollPercent != this.scrollPercent) {
                    this.triggerCustomScroll(oldScrollPercent);
                    return true;
                }
                else
                    return false;
            },

            positionOrMax: function (p, max) {
                if (p < 0)
                    return 0;
                else if (p > max)
                    return max;
                else
                    return p;
            },

            triggerCustomScroll: function (oldScrollPercent) {
                this.scrollable.$element.trigger("customScroll", {
                        scrollAxis: this.sizing.scrollAxis(),
                        direction: this.sizing.scrollDirection(oldScrollPercent, this.scrollPercent),
                        scrollPercent: this.scrollPercent * 100
                    }
                );
            },

            rescroll: function (keepPosition) {
                if (keepPosition) {
                    var overviewPosition = this.positionOrMax(this.overviewPosition(), this.maxOverviewPosition);
                    this.scrollPercent = overviewPosition / this.maxOverviewPosition;
                    var thumbPosition = this.scrollPercent * this.maxThumbPosition;
                    this.setScrollPosition(overviewPosition, thumbPosition);
                }
                else {
                    var thumbPosition = this.scrollPercent * this.maxThumbPosition;
                    var overviewPosition = this.scrollPercent * this.maxOverviewPosition;
                    this.setScrollPosition(overviewPosition, thumbPosition);
                }
            },

            setScrollPosition: function (overviewPosition, thumbPosition) {
                this.$thumb.css(this.sizing.offsetComponent(), thumbPosition + "px");
                this.scrollable.$overview.css(this.sizing.offsetComponent(), -overviewPosition + "px");
            },

            setScrollPositionWithAnimation: function (overviewPosition, thumbPosition) {
                var thumbAnimationOpts = {};
                var overviewAnimationOpts = {};
                thumbAnimationOpts[this.sizing.offsetComponent()] = thumbPosition + "px";
                this.$thumb.animate(thumbAnimationOpts, this.scrollable.options.animationSpeed);
                overviewAnimationOpts[this.sizing.offsetComponent()] = -overviewPosition + "px";
                this.scrollable.$overview.animate(overviewAnimationOpts, this.scrollable.options.animationSpeed);
            },

            calculateMaxThumbPosition: function () {
                return this.sizing.size(this.$scrollBar) - this.thumbSize;
            },

            calculateMaxOverviewPosition: function () {
                return this.sizing.size(this.scrollable.$overview) - this.sizing.size(this.scrollable.$viewPort);
            },

            setScrollEvent: function (event) {
                var attr = "page" + this.sizing.scrollAxis();
                if (!this.scrollEvent || this.scrollEvent[attr] != event[attr])
                    this.scrollEvent = {pageX: event.pageX, pageY: event.pageY};
            },

            scrollToElement: function (element) {
                var $element = $(element);
                if (this.sizing.isInside($element, this.scrollable.$overview) && !this.sizing.isInside($element, this.scrollable.$viewPort)) {
                    var elementOffset = $element.offset();
                    var overviewOffset = this.scrollable.$overview.offset();
                    var viewPortOffset = this.scrollable.$viewPort.offset();
                    this.scrollOverviewTo(elementOffset[this.sizing.offsetComponent()] - overviewOffset[this.sizing.offsetComponent()], true);
                }
            },

            remove: function () {
                this.removeMouseMoveScrolling();
                this.removeMouseWheelScrolling();
                this.removeTouchScrolling();
                this.removeMouseClickScrolling();
                this.removeWindowResize();
            }

        }

        var HSizing = function () {
        }

        HSizing.prototype = {
            size: function ($el, arg) {
                if (arg)
                    return $el.width(arg);
                else
                    return $el.width();
            },

            minSize: function ($el) {
                return parseInt($el.css("min-width")) || 0;
            },

            fixedThumbSize: function (options) {
                return options.fixedThumbWidth;
            },

            scrollBar: function ($el) {
                return $el.find(".scroll-bar.horizontal");
            },

            mouseDelta: function (event1, event2) {
                return event2.pageX - event1.pageX;
            },

            offsetComponent: function () {
                return "left";
            },

            wheelDelta: function (deltaX, deltaY) {
                return deltaX;
            },

            scrollAxis: function () {
                return "X";
            },

            scrollDirection: function (oldPercent, newPercent) {
                return oldPercent < newPercent ? "right" : "left";
            },

            scrollingKeys: {
                37: function (viewPortSize) {
                    return -10; //arrow left
                },
                39: function (viewPortSize) {
                    return 10; //arrow right
                }
            },

            isInside: function (element, wrappingElement) {
                var $element = $(element);
                var $wrappingElement = $(wrappingElement);
                var elementOffset = $element.offset();
                var wrappingElementOffset = $wrappingElement.offset();
                return (elementOffset.left >= wrappingElementOffset.left) &&
                    (elementOffset.left + $element.width() <= wrappingElementOffset.left + $wrappingElement.width());
            }

        }

        var VSizing = function () {
        }

        VSizing.prototype = {

            size: function ($el, arg) {
                if (arg)
                    return $el.height(arg);
                else
                    return $el.height();
            },

            minSize: function ($el) {
                return parseInt($el.css("min-height")) || 0;
            },

            fixedThumbSize: function (options) {
                return options.fixedThumbHeight;
            },

            scrollBar: function ($el) {
                return $el.find(".scroll-bar.vertical");
            },

            mouseDelta: function (event1, event2) {
                return event2.pageY - event1.pageY;
            },

            offsetComponent: function () {
                return "top";
            },

            wheelDelta: function (deltaX, deltaY) {
                return deltaY;
            },

            scrollAxis: function () {
                return "Y";
            },

            scrollDirection: function (oldPercent, newPercent) {
                return oldPercent < newPercent ? "down" : "up";
            },

            scrollingKeys: {
                38: function (viewPortSize) {
                    return -10; //arrow up
                },
                40: function (viewPortSize) {
                    return 10; //arrow down
                },
                33: function (viewPortSize) {
                    return -(viewPortSize - 20); //page up
                },
                34: function (viewPortSize) {
                    return viewPortSize - 20; //page down
                }
            },

            isInside: function (element, wrappingElement) {
                var $element = $(element);
                var $wrappingElement = $(wrappingElement);
                var elementOffset = $element.offset();
                var wrappingElementOffset = $wrappingElement.offset();
                return (elementOffset.top >= wrappingElementOffset.top) &&
                    (elementOffset.top + $element.height() <= wrappingElementOffset.top + $wrappingElement.height());
            }

        }

        return this.each(function () {
            if (options == undefined)
                options = defaultOptions;
            if (typeof(options) == "string") {
                var scrollable = $(this).data("scrollable");
                if (scrollable)
                    scrollable[options](args);
            }
            else if (typeof(options) == "object") {
                options = $.extend(defaultOptions, options);
                new Scrollable($(this), options);
            }
            else
                throw "Invalid type of options";
        });

    }
    ;

})
(jQuery);

(function ($) {

    var types = ['DOMMouseScroll', 'mousewheel'];

    if ($.event.fixHooks) {
        for (var i = types.length; i;) {
            $.event.fixHooks[types[--i]] = $.event.mouseHooks;
        }
    }

    $.event.special.mousewheel = {
        setup: function () {
            if (this.addEventListener) {
                for (var i = types.length; i;) {
                    this.addEventListener(types[--i], handler, false);
                }
            } else {
                this.onmousewheel = handler;
            }
        },

        teardown: function () {
            if (this.removeEventListener) {
                for (var i = types.length; i;) {
                    this.removeEventListener(types[--i], handler, false);
                }
            } else {
                this.onmousewheel = null;
            }
        }
    };

    $.fn.extend({
        mousewheel: function (fn) {
            return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
        },

        unmousewheel: function (fn) {
            return this.unbind("mousewheel", fn);
        }
    });


    function handler(event) {
        var orgEvent = event || window.event, args = [].slice.call(arguments, 1), delta = 0, returnValue = true,
            deltaX = 0, deltaY = 0;
        event = $.event.fix(orgEvent);
        event.type = "mousewheel";

        // Old school scrollwheel delta
        if (orgEvent.wheelDelta) {
            delta = orgEvent.wheelDelta / 120;
        }
        if (orgEvent.detail) {
            delta = -orgEvent.detail / 3;
        }

        // New school multidimensional scroll (touchpads) deltas
        deltaY = delta;

        // Gecko
        if (orgEvent.axis !== undefined && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) {
            deltaY = 0;
            deltaX = delta;
        }

        // Webkit
        if (orgEvent.wheelDeltaY !== undefined) {
            deltaY = orgEvent.wheelDeltaY / 120;
        }
        if (orgEvent.wheelDeltaX !== undefined) {
            deltaX = orgEvent.wheelDeltaX / 120;
        }

        // Add event and delta to the front of the arguments
        args.unshift(event, delta, deltaX, deltaY);

        return ($.event.dispatch || $.event.handle).apply(this, args);
    }

})(jQuery);


/*-----*/


/*-----*/


/* MAIN JS START */


$(document).ready(function () {

    //check if mobile
    var isMobile = false; //initiate as false
    // device detection
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;
    //-
    if (isMobile) {
        $('body').addClass('mobile');
    }


    $('.fancy').fancybox();

    $('.phone-mask').mask("+7 (999) 999-99-99");

    //form choise toggler
    $('.choise_toggler a').click(function (e) {
        e.preventDefault();
        if ($(this).data('val') == "Мужчина") {
            $(this).siblings('.toggling_unit').removeClass('active');
        } else {
            $(this).siblings('.toggling_unit').addClass('active');
        }

        $(this).closest('.the_form_div').find('input[name="sex"]').val($(this).data('val'));
    });

    $('.toggling_unit').click(function (e) {
        e.preventDefault();

        var tu_offset = $(this).offset();
        var tu_mx = e.pageX - tu_offset.left;
        var tu_my = e.pageY - tu_offset.top;

        if (tu_mx >= (parseInt($(this).width()) / 2)) {
            $(this).addClass('active');
            $(this).closest('.the_form_div').find('input[name="sex"]').val($('.choise_toggler a:last-child').data('val'));
        }
        else {
            {
                $(this).removeClass('active');
                $(this).closest('.the_form_div').find('input[name="sex"]').val($('.choise_toggler a:first-child').data('val'));
            }
        }
    });
    //--

    //add em for adjusting
    $('.the_content_left_column_actions_div .img a').prepend('<em></em>');
    $('.card_page_block_img a').prepend('<em></em>');

    //-


    //header_personal own select

    $('.header_personal_inner > ul > li > a').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).siblings('ul').toggleClass('show');
    });

    /*
    $('.header_personal_inner > ul > li > ul > li > a').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.header_personal_inner > ul > li > a').html($(this).html());
        $('.header_personal_inner > ul > li > a').attr('href',$(this).attr('href'));

        $('.header_personal_inner > ul > li > ul').removeClass('show');
    });*/

    $('body').click(function (e) {
        $('.header_personal_inner > ul > li > ul').removeClass('show');
    });
    //--


    //match_height
    $('.partners_div').matchHeight({
        byRow: true,
        property: 'height',
        target: null,
        remove: false
    });
    $('.news_block_div').matchHeight({
        byRow: true,
        property: 'height',
        target: null,
        remove: false
    });

    $(window).resize(function () {
        $.fn.matchHeight._update();
    });
    //--

    //cat filter
    $('.cat_display_type a').click(function (e) {
        e.preventDefault();
        $('.cat_display_type a').removeClass('active');
        $(this).addClass('active');
        $('.cat_wrap').removeClass('boxed').removeClass('list').addClass($(this).attr('data-mode'));
    });
    //--


    //add hrefs for blocks on activities and news page
    var href_content = "";

    $('.news_block_wrap.activities_block_wrap .news_block_div.activities_block_div').each(function () {
        href_content = $(this).find('p.title a').attr('href');
        $(this).children('.img').children('img').wrap('<a href="' + href_content + '"></a>');
        $('<a class="button more_button" href="' + href_content + '">Подробнее</a>').appendTo($(this).find('div.text'));
    });

    $('.other_news_block_wrap .news_block_div').each(function () {
        href_content = $(this).find('p.title a').attr('href');
        $(this).children('.img').children('img').wrap('<a href="' + href_content + '"></a>');
    });

    //--


    //dynamic stars
    var small_star1 = 22;
    var small_star2 = 42;
    var small_star3 = 65;
    var small_star4 = 88;
    var small_star5 = 110;

    var big_star1 = 28;
    var big_star2 = 56;
    var big_star3 = 84;
    var big_star4 = 112;
    var big_star5 = 140;

    var selected_rating = "";

    function figureOutStars(stars_container, mx1) {
        var calculated_rating = "";

        if ($(stars_container).hasClass('stars_big')) {
            if (mx1 <= big_star5) {
                calculated_rating = "star5";
            }
            if (mx1 <= big_star4) {
                calculated_rating = "star4";
            }
            if (mx1 <= big_star3) {
                calculated_rating = "star3";
            }
            if (mx1 <= big_star2) {
                calculated_rating = "star2";
            }
            if (mx1 <= big_star1) {
                calculated_rating = "star1";
            }
        } else {
            if (mx1 <= small_star5) {
                calculated_rating = "star5";
            }
            if (mx1 <= small_star4) {
                calculated_rating = "star4";
            }
            if (mx1 <= small_star3) {
                calculated_rating = "star3";
            }
            if (mx1 <= small_star2) {
                calculated_rating = "star2";
            }
            if (mx1 <= small_star1) {
                calculated_rating = "star1";
            }
        }

        return calculated_rating;
    }

    //stars mechanics
    $('.stars span').wrap('<div></div>');
    $('.dynamic_stars').click(function (e) {
        var m_offset = $(this).offset();
        var mx = e.pageX - m_offset.left;
        var my = e.pageY - m_offset.top;

        selected_rating = figureOutStars($(this), mx);
        $(this).find('span').html(selected_rating.substr(-1, 1))
    });

    $('.dynamic_stars').mousemove(function (e) {
        var m_offset2 = $(this).offset();
        var mx2 = e.pageX - m_offset2.left;
        var my2 = e.pageY - m_offset2.top;

        if (figureOutStars($(this), mx2) != "") {
            $(this).find('span').removeClass();
            $(this).find('span').addClass(figureOutStars($(this), mx2));
        }

    });

    $('.dynamic_stars').mouseout(function (e) {

        $(this).find('span').removeClass();
        if (selected_rating != "") {
            $(this).find('span').addClass(selected_rating);
        }

    });
    //--
    //--


    //tabs to accordeon
    if ($(window).width() < 980) {


    }
    //-


    $('<div class="mobile_menu_toggler"></div>').prependTo('body > .container');

    $('.mobile_menu_toggler').click(function () {
        $('.header_menu_wrap').toggleClass('active');
        $(this).toggleClass('active');
    });


    //the tabs
    $('.the_tabs_div').not('.the_tabs_div.active').slideUp(200);

    $('.the_tabs_head a').click(function (e) {
        e.preventDefault();
        $('.the_tabs_div').removeClass('active').slideUp();
        $('.the_tabs_div:nth-child(' + (parseInt($(this).index('.the_tabs_head a')) + 1) + ')').addClass('active').slideDown();

        $('.the_tabs_head a').removeClass('active');
        $(this).addClass('active');
    });
    //-

    //club_cat tabs toggler
    if ($(window).width() < 767) {
        $('.club_cat_page_left_search').prependTo('.club_cat_page_block.the_tabs');
        $('.club_cat_page_block.the_tabs .the_tabs_div:nth-last-child(2)').slideUp(1000);
        $('.the_tabs_div').not('.the_tabs_div.active').slideUp(0);
    }
    //--


    $('.qty_div input[type="text"]').keydown(function () {

        return false;
    });


    //qty buttons
    $('.qty_div span').click(function () {
        var qty = parseInt($(this).siblings('input[type="text"]').val());

        if ($(this).hasClass('minus')) {
            if (qty > 1) {
                $(this).siblings('input[type="text"]').val(qty - 1);
            }
        }
        else if ($(this).hasClass('plus')) {
            $(this).siblings('input[type="text"]').val(qty + 1);
        }
    });
    //--


    if ($('input[type="radio"]').length) {
        $('input[type="radio"]').ezMark();
    }


    if ($('input[type="checkbox"]').length) {
        $('input[type="checkbox"]').ezMark();
    }

    if ($('select').length) {
        $('select').selectBox({'keepInViewport': false});
    }


    $('.scrollable').customScrollbar();


    if ($(".top_section_slider").length) {
        $(".top_section_slider").flexslider({
            //itemWidth: 354,
            minItems: 1,
            maxItems: 1,
            directionNav: true,
            controlNav: false,
            pauseOnHover: true,
            animation: "fade",
            slideshow: true,
            move: 1
        });

    }


    if ($(".brands_slider").length) {
        $(".brands_slider").flexslider({
            itemWidth: 195,
            minItems: 1,
            maxItems: 4,
            directionNav: true,
            controlNav: false,
            pauseOnHover: true,
            animation: "slide",
            slideshow: true,
            move: 1
        });

    }

    if ($(".the_card_img_slider").length) {
        $(".the_card_img_slider").flexslider({
            itemWidth: 300,
            minItems: 1,
            maxItems: 4,
            directionNav: true,
            controlNav: false,
            pauseOnHover: true,
            animation: "slide",
            slideshow: true,
            move: 1
        });

    }


    //window scroll

    var fromTop = 0;

    var to_top_show_height = 400;
    var header_offset = $('header').offset().top + 10;

    $(window).scroll(function () {

        fromTop = $(window).scrollTop();

        /*if (fromTop > header_offset) {$('header').addClass('active');}
		if (fromTop < header_offset) {$('header').removeClass('active');}*/

        if (fromTop > to_top_show_height) {
            $('.to_top').addClass('active');
        }
        if (fromTop < to_top_show_height) {
            $('.to_top').removeClass('active');
        }

    });

    //-


    //google map
    if ($('#map1').length) {

        setTimeout(function () {
            var script = document.createElement('script');
            script.onload = function () {

            };
            script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAMj0tzo5dL6q5svRGhyCEYhMwqRcAtve4&callback=initMap";
            script.setAttribute('defer', '');
            script.setAttribute('async', '');
            document.getElementById('map1').appendChild(script);
        }, 999);
    }

//--


//scroll to
    $('.to_top').click(function () {
        $("html, body").animate({
            scrollTop: ($($(this).attr("href")).offset().top) - 0 + "px"
        }, {
            duration: 1100
        });
        return false;
    });
//-


//forms
    /* $(".frm1").validate({  //проверка форм

         rules: {

             name: {
                 required: true,
                 minlength: 2
             },

             mail: {
                 required: true,
                 minlength: 2
             },

             password: {
                 required: true,
                 minlength: 2
             }


         },
         onkeyup: false,
         highlight: function (element, errorClass) {
             $(element).fadeOut(function () {
                 $(element).fadeIn(function () {
                     $(element).fadeOut(function () {
                         $(element).fadeIn();
                     });
                 });
             });


         },

             /*submitHandler: function (form) {

                 $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
                 $.fancybox({href: "#thanks_popup"});

                 //отправка файла на сервер
                 $$f({
                     formid: 'signup-form',//id формы
                     url: '/site/signup'//адрес на серверный скрипт
                 });


             },

             messages: {

                 name: "",
                 mail: "",
                 comment: "",
                 tel: ""

         }
     });*/


    $(".frm2").validate({  //проверка форм

        rules: {

            name: {
                required: true,
                minlength: 2
            },


            tel: {
                required: true,
                minlength: 2
            }


        },
        onkeyup: false,
        highlight: function (element, errorClass) {
            $(element).fadeOut(function () {
                $(element).fadeIn(function () {
                    $(element).fadeOut(function () {
                        $(element).fadeIn();
                    });
                });
            });


        },

        submitHandler: function (form) {

            $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
            $.fancybox({href: "#thanks_popup"});

            //отправка файла на сервер
            $$f({
                formid: 'frm22',//id формы
                url: 'sender.php'//адрес на серверный скрипт
            });


        },

        messages: {

            name: "",
            mail: "",
            comment: "",
            tel: ""

        }
    });

    /*
        $(".frm3").validate({  //проверка форм

            rules: {

                name: {
                    required: true,
                    minlength: 2
                },

                adress: {
                    required: true,
                    minlength: 2
                },

                mail: {
                    required: true,
                    minlength: 2
                },


                tel: {
                    required: true,
                    minlength: 2
                }


            },
            onkeyup: false,
            highlight: function (element, errorClass) {
                $(element).fadeOut(function () {
                    $(element).fadeIn(function () {
                        $(element).fadeOut(function () {
                            $(element).fadeIn();
                        });
                    });
                });


            },

            submitHandler: function (form) {

                $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
                $.fancybox({href: "#thanks_popup"});

                //отправка файла на сервер
                $$f({
                    formid: 'frm22',//id формы
                    url: 'sender.php'//адрес на серверный скрипт
                });


            },

            messages: {

                name: "",
                mail: "",
                adress: "",
                tel: ""

            }
        }); */


    $(".frm01").validate({  //проверка форм

        rules: {

            name: {
                required: true,
                minlength: 2
            },


            tel: {
                required: true,
                minlength: 2
            }


        },
        onkeyup: false,
        highlight: function (element, errorClass) {
            $(element).fadeOut(function () {
                $(element).fadeIn(function () {
                    $(element).fadeOut(function () {
                        $(element).fadeIn();
                    });
                });
            });


        },

        submitHandler: function (form) {

            $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
            $.fancybox({href: "#thanks_popup"});

            //отправка файла на сервер
            $$f({
                formid: 'frm011',//id формы
                url: 'sender.php'//адрес на серверный скрипт
            });


        },

        messages: {

            name: "",
            mail: "",
            comment: "",
            tel: ""

        }
    });


    $(".frm02").validate({  //проверка форм

        rules: {

            name: {
                required: true,
                minlength: 2
            },


            tel: {
                required: true,
                minlength: 2
            }


        },
        onkeyup: false,
        highlight: function (element, errorClass) {
            $(element).fadeOut(function () {
                $(element).fadeIn(function () {
                    $(element).fadeOut(function () {
                        $(element).fadeIn();
                    });
                });
            });


        },

        submitHandler: function (form) {

            $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
            $.fancybox({href: "#thanks_popup"});

            //отправка файла на сервер
            $$f({
                formid: 'frm022',//id формы
                url: 'sender.php'//адрес на серверный скрипт
            });


        },

        messages: {

            name: "",
            mail: "",
            comment: "",
            tel: ""

        }
    });


    $(".frm03").validate({  //проверка форм

        rules: {

            name: {
                required: true,
                minlength: 2
            },


            tel: {
                required: true,
                minlength: 2
            }


        },
        onkeyup: false,
        highlight: function (element, errorClass) {
            $(element).fadeOut(function () {
                $(element).fadeIn(function () {
                    $(element).fadeOut(function () {
                        $(element).fadeIn();
                    });
                });
            });


        },

        submitHandler: function (form) {

            $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
            $.fancybox({href: "#thanks_popup"});

            //отправка файла на сервер
            $$f({
                formid: 'frm033',//id формы
                url: 'sender.php'//адрес на серверный скрипт
            });


        },

        messages: {

            name: "",
            mail: "",
            comment: "",
            tel: ""

        }
    });


})
;

/*
 * acceptReview
 */
function acceptReview(num) {
    $.ajax({
        url: "/rating/accept-review",
        type: "post",
        data: {id: num},
        success: function () {
            Location.reload();
        }
    });

}


$(document).on("pjax:end", function () {
    $(".phone-mask").mask("+7 (999) 999-99-99");
    if ($('input[type="radio"]').length) {
        $('input[type="radio"]').ezMark();
    }


    if ($('input[type="checkbox"]').length) {
        $('input[type="checkbox"]').ezMark();
    }


    if ($('select').length) {
        $('select').selectBox({'keepInViewport': false});
    }

    $('.scrollable').customScrollbar();
});


$('#profileform-location').kladr({
    oneString: true
});
/* MAIN JS END */
