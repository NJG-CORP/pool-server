/* http://brm.io/jquery-match-height/ */
!function (t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], t) : "undefined" != typeof module && module.exports ? module.exports = t(require("jquery")) : t(jQuery)
}(function (l) {
    var a = -1, n = -1, h = function (t) {
        return parseFloat(t) || 0
    }, c = function (t) {
        var e = l(t), a = null, n = [];
        return e.each(function () {
            var t = l(this), e = t.offset().top - h(t.css("margin-top")), o = 0 < n.length ? n[n.length - 1] : null;
            null === o ? n.push(t) : Math.floor(Math.abs(a - e)) <= 1 ? n[n.length - 1] = o.add(t) : n.push(t), a = e
        }), n
    }, p = function (t) {
        var e = {byRow: !0, property: "height", target: null, remove: !1};
        return "object" == typeof t ? l.extend(e, t) : ("boolean" == typeof t ? e.byRow = t : "remove" === t && (e.remove = !0), e)
    }, u = l.fn.matchHeight = function (t) {
        var e = p(t);
        if (e.remove) {
            var o = this;
            return this.css(e.property, ""), l.each(u._groups, function (t, e) {
                e.elements = e.elements.not(o)
            }), this
        }
        return this.length <= 1 && !e.target || (u._groups.push({elements: this, options: e}), u._apply(this, e)), this
    };
    u.version = "master", u._groups = [], u._throttle = 80, u._maintainScroll = !1, u._beforeUpdate = null, u._afterUpdate = null, u._rows = c, u._parse = h, u._parseOptions = p, u._apply = function (t, e) {
        var i = p(e), o = l(t), a = [o], n = l(window).scrollTop(), r = l("html").outerHeight(!0),
            s = o.parents().filter(":hidden");
        return s.each(function () {
            var t = l(this);
            t.data("style-cache", t.attr("style"))
        }), s.css("display", "block"), i.byRow && !i.target && (o.each(function () {
            var t = l(this), e = t.css("display");
            "inline-block" !== e && "flex" !== e && "inline-flex" !== e && (e = "block"), t.data("style-cache", t.attr("style")), t.css({
                display: e,
                "padding-top": "0",
                "padding-bottom": "0",
                "margin-top": "0",
                "margin-bottom": "0",
                "border-top-width": "0",
                "border-bottom-width": "0",
                height: "100px",
                overflow: "hidden"
            })
        }), a = c(o), o.each(function () {
            var t = l(this);
            t.attr("style", t.data("style-cache") || "")
        })), l.each(a, function (t, e) {
            var o = l(e), n = 0;
            if (i.target) n = i.target.outerHeight(!1); else {
                if (i.byRow && o.length <= 1) return void o.css(i.property, "");
                o.each(function () {
                    var t = l(this), e = t.attr("style"), o = t.css("display");
                    "inline-block" !== o && "flex" !== o && "inline-flex" !== o && (o = "block");
                    var a = {display: o};
                    a[i.property] = "", t.css(a), t.outerHeight(!1) > n && (n = t.outerHeight(!1)), e ? t.attr("style", e) : t.css("display", "")
                })
            }
            o.each(function () {
                var t = l(this), e = 0;
                i.target && t.is(i.target) || ("border-box" !== t.css("box-sizing") && (e += h(t.css("border-top-width")) + h(t.css("border-bottom-width")), e += h(t.css("padding-top")) + h(t.css("padding-bottom"))), t.css(i.property, n - e + "px"))
            })
        }), s.each(function () {
            var t = l(this);
            t.attr("style", t.data("style-cache") || null)
        }), u._maintainScroll && l(window).scrollTop(n / r * l("html").outerHeight(!0)), this
    }, u._applyDataApi = function () {
        var o = {};
        l("[data-match-height], [data-mh]").each(function () {
            var t = l(this), e = t.attr("data-mh") || t.attr("data-match-height");
            o[e] = e in o ? o[e].add(t) : t
        }), l.each(o, function () {
            this.matchHeight(!0)
        })
    };
    var i = function (t) {
        u._beforeUpdate && u._beforeUpdate(t, u._groups), l.each(u._groups, function () {
            u._apply(this.elements, this.options)
        }), u._afterUpdate && u._afterUpdate(t, u._groups)
    };
    u._update = function (t, e) {
        if (e && "resize" === e.type) {
            var o = l(window).width();
            if (o === a) return;
            a = o
        }
        t ? -1 === n && (n = setTimeout(function () {
            i(e), n = -1
        }, u._throttle)) : i(e)
    }, l(u._applyDataApi);
    var t = l.fn.on ? "on" : "bind";
    l(window)[t]("load", function (t) {
        u._update(!1, t)
    }), l(window)[t]("resize orientationchange", function (t) {
        u._update(!0, t)
    })
});

/* http://fancyapps.com/fancybox/ 2.1.5 */
!function (o, i, H, h) {
    "use strict";
    var a = H("html"), r = H(o), c = H(i), M = H.fancybox = function () {
        M.open.apply(this, arguments)
    }, s = navigator.userAgent.match(/msie/i), l = null, d = i.createTouch !== h, f = function (e) {
        return e && e.hasOwnProperty && e instanceof H
    }, u = function (e) {
        return e && "string" === H.type(e)
    }, A = function (e) {
        return u(e) && 0 < e.indexOf("%")
    }, I = function (e, t) {
        var i = parseInt(e, 10) || 0;
        return t && A(e) && (i = M.getViewport()[t] / 100 * i), Math.ceil(i)
    }, D = function (e, t) {
        return I(e, t) + "px"
    };
    H.extend(M, {
        version: "2.1.5",
        defaults: {
            padding: 15,
            margin: 20,
            width: 800,
            height: 600,
            minWidth: 100,
            minHeight: 100,
            maxWidth: 9999,
            maxHeight: 9999,
            pixelRatio: 1,
            autoSize: !0,
            autoHeight: !1,
            autoWidth: !1,
            autoResize: !0,
            autoCenter: !d,
            fitToView: !0,
            aspectRatio: !1,
            topRatio: .5,
            leftRatio: .5,
            scrolling: "auto",
            wrapCSS: "",
            arrows: !0,
            closeBtn: !0,
            closeClick: !1,
            nextClick: !1,
            mouseWheel: !0,
            autoPlay: !1,
            playSpeed: 3e3,
            preload: 3,
            modal: !1,
            loop: !0,
            ajax: {dataType: "html", headers: {"X-fancyBox": !0}},
            iframe: {scrolling: "auto", preload: !0},
            swf: {wmode: "transparent", allowfullscreen: "true", allowscriptaccess: "always"},
            keys: {
                next: {13: "left", 34: "up", 39: "left", 40: "up"},
                prev: {8: "right", 33: "down", 37: "right", 38: "down"},
                close: [27],
                play: [32],
                toggle: [70]
            },
            direction: {next: "left", prev: "right"},
            scrollOutside: !0,
            index: 0,
            type: null,
            href: null,
            content: null,
            title: null,
            tpl: {
                wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
                image: '<img class="fancybox-image" src="{href}" alt="" />',
                iframe: '<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen' + (s ? ' allowtransparency="true"' : "") + "></iframe>",
                error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
                closeBtn: '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
                next: '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
                prev: '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
            },
            openEffect: "fade",
            openSpeed: 250,
            openEasing: "swing",
            openOpacity: !0,
            openMethod: "zoomIn",
            closeEffect: "fade",
            closeSpeed: 250,
            closeEasing: "swing",
            closeOpacity: !0,
            closeMethod: "zoomOut",
            nextEffect: "elastic",
            nextSpeed: 250,
            nextEasing: "swing",
            nextMethod: "changeIn",
            prevEffect: "elastic",
            prevSpeed: 250,
            prevEasing: "swing",
            prevMethod: "changeOut",
            helpers: {overlay: !0, title: !0},
            onCancel: H.noop,
            beforeLoad: H.noop,
            afterLoad: H.noop,
            beforeShow: H.noop,
            afterShow: H.noop,
            beforeChange: H.noop,
            beforeClose: H.noop,
            afterClose: H.noop
        },
        group: {},
        opts: {},
        previous: null,
        coming: null,
        current: null,
        isActive: !1,
        isOpen: !1,
        isOpened: !1,
        wrap: null,
        skin: null,
        outer: null,
        inner: null,
        player: {timer: null, isActive: !1},
        ajaxLoad: null,
        imgPreload: null,
        transitions: {},
        helpers: {},
        open: function (d, p) {
            if (d && (H.isPlainObject(p) || (p = {}), !1 !== M.close(!0))) return H.isArray(d) || (d = f(d) ? H(d).get() : [d]), H.each(d, function (e, t) {
                var i, n, o, a, r, s, l, c = {};
                "object" === H.type(t) && (t.nodeType && (t = H(t)), f(t) ? (c = {
                    href: t.data("fancybox-href") || t.attr("href"),
                    title: t.data("fancybox-title") || t.attr("title"),
                    isDom: !0,
                    element: t
                }, H.metadata && H.extend(!0, c, t.metadata())) : c = t), i = p.href || c.href || (u(t) ? t : null), n = p.title !== h ? p.title : c.title || "", !(a = (o = p.content || c.content) ? "html" : p.type || c.type) && c.isDom && ((a = t.data("fancybox-type")) || (a = (r = t.prop("class").match(/fancybox\.(\w+)/)) ? r[1] : null)), u(i) && (a || (M.isImage(i) ? a = "image" : M.isSWF(i) ? a = "swf" : "#" === i.charAt(0) ? a = "inline" : u(t) && (a = "html", o = t)), "ajax" === a && (i = (s = i.split(/\s+/, 2)).shift(), l = s.shift())), o || ("inline" === a ? i ? o = H(u(i) ? i.replace(/.*(?=#[^\s]+$)/, "") : i) : c.isDom && (o = t) : "html" === a ? o = i : a || i || !c.isDom || (a = "inline", o = t)), H.extend(c, {
                    href: i,
                    type: a,
                    content: o,
                    title: n,
                    selector: l
                }), d[e] = c
            }), M.opts = H.extend(!0, {}, M.defaults, p), p.keys !== h && (M.opts.keys = !!p.keys && H.extend({}, M.defaults.keys, p.keys)), M.group = d, M._start(M.opts.index)
        },
        cancel: function () {
            var e = M.coming;
            e && !1 !== M.trigger("onCancel") && (M.hideLoading(), M.ajaxLoad && M.ajaxLoad.abort(), M.ajaxLoad = null, M.imgPreload && (M.imgPreload.onload = M.imgPreload.onerror = null), e.wrap && e.wrap.stop(!0, !0).trigger("onReset").remove(), M.coming = null, M.current || M._afterZoomOut(e))
        },
        close: function (e) {
            M.cancel(), !1 !== M.trigger("beforeClose") && (M.unbindEvents(), M.isActive && (M.isOpen && !0 !== e ? (M.isOpen = M.isOpened = !1, M.isClosing = !0, H(".fancybox-item, .fancybox-nav").remove(), M.wrap.stop(!0, !0).removeClass("fancybox-opened"), M.transitions[M.current.closeMethod]()) : (H(".fancybox-wrap").stop(!0).trigger("onReset").remove(), M._afterZoomOut())))
        },
        play: function (e) {
            var t = function () {
                clearTimeout(M.player.timer)
            }, i = function () {
                t(), M.current && M.player.isActive && (M.player.timer = setTimeout(M.next, M.current.playSpeed))
            }, n = function () {
                t(), c.unbind(".player"), M.player.isActive = !1, M.trigger("onPlayEnd")
            };
            !0 === e || !M.player.isActive && !1 !== e ? M.current && (M.current.loop || M.current.index < M.group.length - 1) && (M.player.isActive = !0, c.bind({
                "onCancel.player beforeClose.player": n,
                "onUpdate.player": i,
                "beforeLoad.player": t
            }), i(), M.trigger("onPlayStart")) : n()
        },
        next: function (e) {
            var t = M.current;
            t && (u(e) || (e = t.direction.next), M.jumpto(t.index + 1, e, "next"))
        },
        prev: function (e) {
            var t = M.current;
            t && (u(e) || (e = t.direction.prev), M.jumpto(t.index - 1, e, "prev"))
        },
        jumpto: function (e, t, i) {
            var n = M.current;
            n && (e = I(e), M.direction = t || n.direction[e >= n.index ? "next" : "prev"], M.router = i || "jumpto", n.loop && (e < 0 && (e = n.group.length + e % n.group.length), e %= n.group.length), n.group[e] !== h && (M.cancel(), M._start(e)))
        },
        reposition: function (e, t) {
            var i, n = M.current, o = n ? n.wrap : null;
            o && (i = M._getPosition(t), e && "scroll" === e.type ? (delete i.position, o.stop(!0, !0).animate(i, 200)) : (o.css(i), n.pos = H.extend({}, n.dim, i)))
        },
        update: function (t) {
            var i = t && t.type, n = !i || "orientationchange" === i;
            n && (clearTimeout(l), l = null), M.isOpen && !l && (l = setTimeout(function () {
                var e = M.current;
                e && !M.isClosing && (M.wrap.removeClass("fancybox-tmp"), (n || "load" === i || "resize" === i && e.autoResize) && M._setDimension(), "scroll" === i && e.canShrink || M.reposition(t), M.trigger("onUpdate"), l = null)
            }, n && !d ? 0 : 300))
        },
        toggle: function (e) {
            M.isOpen && (M.current.fitToView = "boolean" === H.type(e) ? e : !M.current.fitToView, d && (M.wrap.removeAttr("style").addClass("fancybox-tmp"), M.trigger("onUpdate")), M.update())
        },
        hideLoading: function () {
            c.unbind(".loading"), H("#fancybox-loading").remove()
        },
        showLoading: function () {
            var e, t;
            M.hideLoading(), e = H('<div id="fancybox-loading"><div></div></div>').click(M.cancel).appendTo("body"), c.bind("keydown.loading", function (e) {
                27 === (e.which || e.keyCode) && (e.preventDefault(), M.cancel())
            }), M.defaults.fixed || (t = M.getViewport(), e.css({
                position: "absolute",
                top: .5 * t.h + t.y,
                left: .5 * t.w + t.x
            }))
        },
        getViewport: function () {
            var e = M.current && M.current.locked || !1, t = {x: r.scrollLeft(), y: r.scrollTop()};
            return t.h = e ? (t.w = e[0].clientWidth, e[0].clientHeight) : (t.w = d && o.innerWidth ? o.innerWidth : r.width(), d && o.innerHeight ? o.innerHeight : r.height()), t
        },
        unbindEvents: function () {
            M.wrap && f(M.wrap) && M.wrap.unbind(".fb"), c.unbind(".fb"), r.unbind(".fb")
        },
        bindEvents: function () {
            var t, l = M.current;
            l && (r.bind("orientationchange.fb" + (d ? "" : " resize.fb") + (l.autoCenter && !l.locked ? " scroll.fb" : ""), M.update), (t = l.keys) && c.bind("keydown.fb", function (i) {
                var n = i.which || i.keyCode, e = i.target || i.srcElement;
                if (27 === n && M.coming) return !1;
                i.ctrlKey || i.altKey || i.shiftKey || i.metaKey || e && (e.type || H(e).is("[contenteditable]")) || H.each(t, function (e, t) {
                    return 1 < l.group.length && t[n] !== h ? (M[e](t[n]), i.preventDefault(), !1) : -1 < H.inArray(n, t) ? (M[e](), i.preventDefault(), !1) : void 0
                })
            }), H.fn.mousewheel && l.mouseWheel && M.wrap.bind("mousewheel.fb", function (e, t, i, n) {
                for (var o, a = e.target || null, r = H(a), s = !1; r.length && !(s || r.is(".fancybox-skin") || r.is(".fancybox-wrap"));) s = (o = r[0]) && !(o.style.overflow && "hidden" === o.style.overflow) && (o.clientWidth && o.scrollWidth > o.clientWidth || o.clientHeight && o.scrollHeight > o.clientHeight), r = H(r).parent();
                0 === t || s || 1 < M.group.length && !l.canShrink && (0 < n || 0 < i ? M.prev(0 < n ? "down" : "left") : (n < 0 || i < 0) && M.next(n < 0 ? "up" : "right"), e.preventDefault())
            }))
        },
        trigger: function (i, e) {
            var t, n = e || M.coming || M.current;
            if (n) {
                if (H.isFunction(n[i]) && (t = n[i].apply(n, Array.prototype.slice.call(arguments, 1))), !1 === t) return !1;
                n.helpers && H.each(n.helpers, function (e, t) {
                    t && M.helpers[e] && H.isFunction(M.helpers[e][i]) && M.helpers[e][i](H.extend(!0, {}, M.helpers[e].defaults, t), n)
                }), c.trigger(i)
            }
        },
        isImage: function (e) {
            return u(e) && e.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg)((\?|#).*)?$)/i)
        },
        isSWF: function (e) {
            return u(e) && e.match(/\.(swf)((\?|#).*)?$/i)
        },
        _start: function (e) {
            var t, i, n, o, a, r = {};
            if (e = I(e), !(t = M.group[e] || null)) return !1;
            if (o = (r = H.extend(!0, {}, M.opts, t)).margin, a = r.padding, "number" === H.type(o) && (r.margin = [o, o, o, o]), "number" === H.type(a) && (r.padding = [a, a, a, a]), r.modal && H.extend(!0, r, {
                closeBtn: !1,
                closeClick: !1,
                nextClick: !1,
                arrows: !1,
                mouseWheel: !1,
                keys: null,
                helpers: {overlay: {closeClick: !1}}
            }), r.autoSize && (r.autoWidth = r.autoHeight = !0), "auto" === r.width && (r.autoWidth = !0), "auto" === r.height && (r.autoHeight = !0), r.group = M.group, r.index = e, M.coming = r, !1 !== M.trigger("beforeLoad")) {
                if (n = r.type, i = r.href, !n) return M.coming = null, !(!M.current || !M.router || "jumpto" === M.router) && (M.current.index = e, M[M.router](M.direction));
                if (M.isActive = !0, "image" !== n && "swf" !== n || (r.autoHeight = r.autoWidth = !1, r.scrolling = "visible"), "image" === n && (r.aspectRatio = !0), "iframe" === n && d && (r.scrolling = "scroll"), r.wrap = H(r.tpl.wrap).addClass("fancybox-" + (d ? "mobile" : "desktop") + " fancybox-type-" + n + " fancybox-tmp " + r.wrapCSS).appendTo(r.parent || "body"), H.extend(r, {
                    skin: H(".fancybox-skin", r.wrap),
                    outer: H(".fancybox-outer", r.wrap),
                    inner: H(".fancybox-inner", r.wrap)
                }), H.each(["Top", "Right", "Bottom", "Left"], function (e, t) {
                    r.skin.css("padding" + t, D(r.padding[e]))
                }), M.trigger("onReady"), "inline" === n || "html" === n) {
                    if (!r.content || !r.content.length) return M._error("content")
                } else if (!i) return M._error("href");
                "image" === n ? M._loadImage() : "ajax" === n ? M._loadAjax() : "iframe" === n ? M._loadIframe() : M._afterLoad()
            } else M.coming = null
        },
        _error: function (e) {
            H.extend(M.coming, {
                type: "html",
                autoWidth: !0,
                autoHeight: !0,
                minWidth: 0,
                minHeight: 0,
                scrolling: "no",
                hasError: e,
                content: M.coming.tpl.error
            }), M._afterLoad()
        },
        _loadImage: function () {
            var e = M.imgPreload = new Image;
            e.onload = function () {
                this.onload = this.onerror = null, M.coming.width = this.width / M.opts.pixelRatio, M.coming.height = this.height / M.opts.pixelRatio, M._afterLoad()
            }, e.onerror = function () {
                this.onload = this.onerror = null, M._error("image")
            }, e.src = M.coming.href, !0 !== e.complete && M.showLoading()
        },
        _loadAjax: function () {
            var i = M.coming;
            M.showLoading(), M.ajaxLoad = H.ajax(H.extend({}, i.ajax, {
                url: i.href, error: function (e, t) {
                    M.coming && "abort" !== t ? M._error("ajax", e) : M.hideLoading()
                }, success: function (e, t) {
                    "success" === t && (i.content = e, M._afterLoad())
                }
            }))
        },
        _loadIframe: function () {
            var e = M.coming,
                t = H(e.tpl.iframe.replace(/\{rnd\}/g, (new Date).getTime())).attr("scrolling", d ? "auto" : e.iframe.scrolling).attr("src", e.href);
            H(e.wrap).bind("onReset", function () {
                try {
                    H(this).find("iframe").hide().attr("src", "//about:blank").end().empty()
                } catch (e) {
                }
            }), e.iframe.preload && (M.showLoading(), t.one("load", function () {
                H(this).data("ready", 1), d || H(this).bind("load.fb", M.update), H(this).parents(".fancybox-wrap").width("100%").removeClass("fancybox-tmp").show(), M._afterLoad()
            })), e.content = t.appendTo(e.inner), e.iframe.preload || M._afterLoad()
        },
        _preloadImages: function () {
            var e, t, i = M.group, n = M.current, o = i.length, a = n.preload ? Math.min(n.preload, o - 1) : 0;
            for (t = 1; t <= a; t += 1) "image" === (e = i[(n.index + t) % o]).type && e.href && ((new Image).src = e.href)
        },
        _afterLoad: function () {
            var e, i, t, n, o, a, r = M.coming, s = M.current, l = "fancybox-placeholder";
            if (M.hideLoading(), r && !1 !== M.isActive) {
                if (!1 === M.trigger("afterLoad", r, s)) return r.wrap.stop(!0).trigger("onReset").remove(), void(M.coming = null);
                switch (s && (M.trigger("beforeChange", s), s.wrap.stop(!0).removeClass("fancybox-opened").find(".fancybox-item, .fancybox-nav").remove()), M.unbindEvents(), i = (e = r).content, t = r.type, n = r.scrolling, H.extend(M, {
                    wrap: e.wrap,
                    skin: e.skin,
                    outer: e.outer,
                    inner: e.inner,
                    current: e,
                    previous: s
                }), o = e.href, t) {
                    case"inline":
                    case"ajax":
                    case"html":
                        e.selector ? i = H("<div>").html(i).find(e.selector) : f(i) && (i.data(l) || i.data(l, H('<div class="' + l + '"></div>').insertAfter(i).hide()), i = i.show().detach(), e.wrap.bind("onReset", function () {
                            H(this).find(i).length && i.hide().replaceAll(i.data(l)).data(l, !1)
                        }));
                        break;
                    case"image":
                        i = e.tpl.image.replace("{href}", o);
                        break;
                    case"swf":
                        i = '<object id="fancybox-swf" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="movie" value="' + o + '"></param>', a = "", H.each(e.swf, function (e, t) {
                            i += '<param name="' + e + '" value="' + t + '"></param>', a += " " + e + '="' + t + '"'
                        }), i += '<embed src="' + o + '" type="application/x-shockwave-flash" width="100%" height="100%"' + a + "></embed></object>"
                }
                f(i) && i.parent().is(e.inner) || e.inner.append(i), M.trigger("beforeShow"), e.inner.css("overflow", "yes" === n ? "scroll" : "no" === n ? "hidden" : n), M._setDimension(), M.reposition(), M.isOpen = !1, M.coming = null, M.bindEvents(), M.isOpened ? s.prevMethod && M.transitions[s.prevMethod]() : H(".fancybox-wrap").not(e.wrap).stop(!0).trigger("onReset").remove(), M.transitions[M.isOpened ? e.nextMethod : e.openMethod](), M._preloadImages()
            }
        },
        _setDimension: function () {
            var e, t, i, n, o, a, r, s, l, c, d, p, h, f, u, g, m, y = M.getViewport(), x = 0, v = M.wrap, w = M.skin,
                b = M.inner, k = M.current, C = k.width, O = k.height, W = k.minWidth, _ = k.minHeight, S = k.maxWidth,
                T = k.maxHeight, L = k.scrolling, E = k.scrollOutside ? k.scrollbarWidth : 0, R = k.margin,
                j = I(R[1] + R[3]), P = I(R[0] + R[2]);
            if (v.add(w).add(b).width("auto").height("auto").removeClass("fancybox-tmp"), o = j + (i = I(w.outerWidth(!0) - w.width())), a = P + (n = I(w.outerHeight(!0) - w.height())), r = A(C) ? (y.w - o) * I(C) / 100 : C, s = A(O) ? (y.h - a) * I(O) / 100 : O, "iframe" === k.type) {
                if (g = k.content, k.autoHeight && 1 === g.data("ready")) try {
                    g[0].contentWindow.document.location && (b.width(r).height(9999), m = g.contents().find("body"), E && m.css("overflow-x", "hidden"), s = m.outerHeight(!0))
                } catch (e) {
                }
            } else (k.autoWidth || k.autoHeight) && (b.addClass("fancybox-tmp"), k.autoWidth || b.width(r), k.autoHeight || b.height(s), k.autoWidth && (r = b.width()), k.autoHeight && (s = b.height()), b.removeClass("fancybox-tmp"));
            if (C = I(r), O = I(s), d = r / s, W = I(A(W) ? I(W, "w") - o : W), S = I(A(S) ? I(S, "w") - o : S), _ = I(A(_) ? I(_, "h") - a : _), l = S, c = T = I(A(T) ? I(T, "h") - a : T), k.fitToView && (S = Math.min(y.w - o, S), T = Math.min(y.h - a, T)), f = y.w - j, u = y.h - P, k.aspectRatio ? (S < C && (O = I((C = S) / d)), T < O && (C = I((O = T) * d)), C < W && (O = I((C = W) / d)), O < _ && (C = I((O = _) * d))) : (C = Math.max(W, Math.min(C, S)), k.autoHeight && "iframe" !== k.type && (b.width(C), O = b.height()), O = Math.max(_, Math.min(O, T))), k.fitToView) if (b.width(C).height(O), v.width(C + i), p = v.width(), h = v.height(), k.aspectRatio) for (; (f < p || u < h) && W < C && _ < O && !(19 < x++);) O = Math.max(_, Math.min(T, O - 10)), (C = I(O * d)) < W && (O = I((C = W) / d)), S < C && (O = I((C = S) / d)), b.width(C).height(O), v.width(C + i), p = v.width(), h = v.height(); else C = Math.max(W, Math.min(C, C - (p - f))), O = Math.max(_, Math.min(O, O - (h - u)));
            E && "auto" === L && O < s && C + i + E < f && (C += E), b.width(C).height(O), v.width(C + i), p = v.width(), h = v.height(), e = (f < p || u < h) && W < C && _ < O, t = k.aspectRatio ? C < l && O < c && C < r && O < s : (C < l || O < c) && (C < r || O < s), H.extend(k, {
                dim: {
                    width: D(p),
                    height: D(h)
                },
                origWidth: r,
                origHeight: s,
                canShrink: e,
                canExpand: t,
                wPadding: i,
                hPadding: n,
                wrapSpace: h - w.outerHeight(!0),
                skinSpace: w.height() - O
            }), !g && k.autoHeight && _ < O && O < T && !t && b.height("auto")
        },
        _getPosition: function (e) {
            var t = M.current, i = M.getViewport(), n = t.margin, o = M.wrap.width() + n[1] + n[3],
                a = M.wrap.height() + n[0] + n[2], r = {position: "absolute", top: n[0], left: n[3]};
            return t.autoCenter && t.fixed && !e && a <= i.h && o <= i.w ? r.position = "fixed" : t.locked || (r.top += i.y, r.left += i.x), r.top = D(Math.max(r.top, r.top + (i.h - a) * t.topRatio)), r.left = D(Math.max(r.left, r.left + (i.w - o) * t.leftRatio)), r
        },
        _afterZoomIn: function () {
            var t = M.current;
            t && (M.isOpen = M.isOpened = !0, M.wrap.css("overflow", "visible").addClass("fancybox-opened"), M.update(), (t.closeClick || t.nextClick && 1 < M.group.length) && M.inner.css("cursor", "pointer").bind("click.fb", function (e) {
                H(e.target).is("a") || H(e.target).parent().is("a") || (e.preventDefault(), M[t.closeClick ? "close" : "next"]())
            }), t.closeBtn && H(t.tpl.closeBtn).appendTo(M.skin).bind("click.fb", function (e) {
                e.preventDefault(), M.close()
            }), t.arrows && 1 < M.group.length && ((t.loop || 0 < t.index) && H(t.tpl.prev).appendTo(M.outer).bind("click.fb", M.prev), (t.loop || t.index < M.group.length - 1) && H(t.tpl.next).appendTo(M.outer).bind("click.fb", M.next)), M.trigger("afterShow"), t.loop || t.index !== t.group.length - 1 ? M.opts.autoPlay && !M.player.isActive && (M.opts.autoPlay = !1, M.play()) : M.play(!1))
        },
        _afterZoomOut: function (e) {
            e = e || M.current, H(".fancybox-wrap").trigger("onReset").remove(), H.extend(M, {
                group: {},
                opts: {},
                router: !1,
                current: null,
                isActive: !1,
                isOpened: !1,
                isOpen: !1,
                isClosing: !1,
                wrap: null,
                skin: null,
                outer: null,
                inner: null
            }), M.trigger("afterClose", e)
        }
    }), M.transitions = {
        getOrigPosition: function () {
            var e = M.current, t = e.element, i = e.orig, n = {}, o = 50, a = 50, r = e.hPadding, s = e.wPadding,
                l = M.getViewport();
            return !i && e.isDom && t.is(":visible") && ((i = t.find("img:first")).length || (i = t)), f(i) ? (n = i.offset(), i.is("img") && (o = i.outerWidth(), a = i.outerHeight())) : (n.top = l.y + (l.h - a) * e.topRatio, n.left = l.x + (l.w - o) * e.leftRatio), ("fixed" === M.wrap.css("position") || e.locked) && (n.top -= l.y, n.left -= l.x), n = {
                top: D(n.top - r * e.topRatio),
                left: D(n.left - s * e.leftRatio),
                width: D(o + s),
                height: D(a + r)
            }
        }, step: function (e, t) {
            var i, n, o = t.prop, a = M.current, r = a.wrapSpace, s = a.skinSpace;
            "width" !== o && "height" !== o || (i = t.end === t.start ? 1 : (e - t.start) / (t.end - t.start), M.isClosing && (i = 1 - i), n = e - ("width" === o ? a.wPadding : a.hPadding), M.skin[o](I("width" === o ? n : n - r * i)), M.inner[o](I("width" === o ? n : n - r * i - s * i)))
        }, zoomIn: function () {
            var e = M.current, t = e.pos, i = e.openEffect, n = "elastic" === i, o = H.extend({opacity: 1}, t);
            delete o.position, n ? (t = this.getOrigPosition(), e.openOpacity && (t.opacity = .1)) : "fade" === i && (t.opacity = .1), M.wrap.css(t).animate(o, {
                duration: "none" === i ? 0 : e.openSpeed,
                easing: e.openEasing,
                step: n ? this.step : null,
                complete: M._afterZoomIn
            })
        }, zoomOut: function () {
            var e = M.current, t = e.closeEffect, i = "elastic" === t, n = {opacity: .1};
            i && (n = this.getOrigPosition(), e.closeOpacity && (n.opacity = .1)), M.wrap.animate(n, {
                duration: "none" === t ? 0 : e.closeSpeed,
                easing: e.closeEasing,
                step: i ? this.step : null,
                complete: M._afterZoomOut
            })
        }, changeIn: function () {
            var e, t = M.current, i = t.nextEffect, n = t.pos, o = {opacity: 1}, a = M.direction;
            n.opacity = .1, "elastic" === i && (o[e = "down" === a || "up" === a ? "top" : "left"] = "down" === a || "right" === a ? (n[e] = D(I(n[e]) - 200), "+=200px") : (n[e] = D(I(n[e]) + 200), "-=200px")), "none" === i ? M._afterZoomIn() : M.wrap.css(n).animate(o, {
                duration: t.nextSpeed,
                easing: t.nextEasing,
                complete: M._afterZoomIn
            })
        }, changeOut: function () {
            var e = M.previous, t = e.prevEffect, i = {opacity: .1}, n = M.direction;
            "elastic" === t && (i["down" === n || "up" === n ? "top" : "left"] = ("up" === n || "left" === n ? "-" : "+") + "=200px"), e.wrap.animate(i, {
                duration: "none" === t ? 0 : e.prevSpeed,
                easing: e.prevEasing,
                complete: function () {
                    H(this).trigger("onReset").remove()
                }
            })
        }
    }, M.helpers.overlay = {
        defaults: {closeClick: !0, speedOut: 200, showEarly: !0, css: {}, locked: !d, fixed: !0},
        overlay: null,
        fixed: !1,
        el: H("html"),
        create: function (e) {
            e = H.extend({}, this.defaults, e), this.overlay && this.close(), this.overlay = H('<div class="fancybox-overlay"></div>').appendTo(M.coming ? M.coming.parent : e.parent), this.fixed = !1, e.fixed && M.defaults.fixed && (this.overlay.addClass("fancybox-overlay-fixed"), this.fixed = !0)
        },
        open: function (e) {
            var t = this;
            e = H.extend({}, this.defaults, e), this.overlay ? this.overlay.unbind(".overlay").width("auto").height("auto") : this.create(e), this.fixed || (r.bind("resize.overlay", H.proxy(this.update, this)), this.update()), e.closeClick && this.overlay.bind("click.overlay", function (e) {
                if (H(e.target).hasClass("fancybox-overlay")) return M.isActive ? M.close() : t.close(), !1
            }), this.overlay.css(e.css).show()
        },
        close: function () {
            var e, t;
            r.unbind("resize.overlay"), this.el.hasClass("fancybox-lock") && (H(".fancybox-margin").removeClass("fancybox-margin"), e = r.scrollTop(), t = r.scrollLeft(), this.el.removeClass("fancybox-lock"), r.scrollTop(e).scrollLeft(t)), H(".fancybox-overlay").remove().hide(), H.extend(this, {
                overlay: null,
                fixed: !1
            })
        },
        update: function () {
            var e, t = "100%";
            this.overlay.width(t).height("100%"), s ? (e = Math.max(i.documentElement.offsetWidth, i.body.offsetWidth), c.width() > e && (t = c.width())) : c.width() > r.width() && (t = c.width()), this.overlay.width(t).height(c.height())
        },
        onReady: function (e, t) {
            var i = this.overlay;
            H(".fancybox-overlay").stop(!0, !0), i || this.create(e), e.locked && this.fixed && t.fixed && (i || (this.margin = c.height() > r.height() && H("html").css("margin-right").replace("px", "")), t.locked = this.overlay.append(t.wrap), t.fixed = !1), !0 === e.showEarly && this.beforeShow.apply(this, arguments)
        },
        beforeShow: function (e, t) {
            var i, n;
            t.locked && (!1 !== this.margin && (H("*").filter(function () {
                return "fixed" === H(this).css("position") && !H(this).hasClass("fancybox-overlay") && !H(this).hasClass("fancybox-wrap")
            }).addClass("fancybox-margin"), this.el.addClass("fancybox-margin")), i = r.scrollTop(), n = r.scrollLeft(), this.el.addClass("fancybox-lock"), r.scrollTop(i).scrollLeft(n)), this.open(e)
        },
        onUpdate: function () {
            this.fixed || this.update()
        },
        afterClose: function (e) {
            this.overlay && !M.coming && this.overlay.fadeOut(e.speedOut, H.proxy(this.close, this))
        }
    }, M.helpers.title = {
        defaults: {type: "float", position: "bottom"}, beforeShow: function (e) {
            var t, i, n = M.current, o = n.title, a = e.type;
            if (H.isFunction(o) && (o = o.call(n.element, n)), u(o) && "" !== H.trim(o)) {
                switch (t = H('<div class="fancybox-title fancybox-title-' + a + '-wrap">' + o + "</div>"), a) {
                    case"inside":
                        i = M.skin;
                        break;
                    case"outside":
                        i = M.wrap;
                        break;
                    case"over":
                        i = M.inner;
                        break;
                    default:
                        i = M.skin, t.appendTo("body"), s && t.width(t.width()), t.wrapInner('<span class="child"></span>'), M.current.margin[2] += Math.abs(I(t.css("margin-bottom")))
                }
                t["top" === e.position ? "prependTo" : "appendTo"](i)
            }
        }
    }, H.fn.fancybox = function (a) {
        var r, s = H(this), l = this.selector || "", e = function (e) {
            var t, i, n = H(this).blur(), o = r;
            e.ctrlKey || e.altKey || e.shiftKey || e.metaKey || n.is(".fancybox-wrap") || (t = a.groupAttr || "data-fancybox-group", (i = n.attr(t)) || (t = "rel", i = n.get(0)[t]), i && "" !== i && "nofollow" !== i && (o = (n = (n = l.length ? H(l) : s).filter("[" + t + '="' + i + '"]')).index(this)), a.index = o, !1 !== M.open(n, a) && e.preventDefault())
        };
        return r = (a = a || {}).index || 0, l && !1 !== a.live ? c.undelegate(l, "click.fb-start").delegate(l + ":not('.fancybox-item, .fancybox-nav')", "click.fb-start", e) : s.unbind("click.fb-start").bind("click.fb-start", e), this.filter("[data-fancybox-start=1]").trigger("click"), this
    }, c.ready(function () {
        var e, t, i, n;
        H.scrollbarWidth === h && (H.scrollbarWidth = function () {
            var e = H('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo("body"),
                t = e.children(), i = t.innerWidth() - t.height(99).innerWidth();
            return e.remove(), i
        }), H.support.fixedPosition === h && (H.support.fixedPosition = (i = H('<div style="position:fixed;top:20px;"></div>').appendTo("body"), n = 20 === i[0].offsetTop || 15 === i[0].offsetTop, i.remove(), n)), H.extend(M.defaults, {
            scrollbarWidth: H.scrollbarWidth(),
            fixed: H.support.fixedPosition,
            parent: H("body")
        }), e = H(o).width(), a.addClass("fancybox-lock-test"), t = H(o).width(), a.removeClass("fancybox-lock-test"), H("<style type='text/css'>.fancybox-margin{margin-right:" + (t - e) + "px;}</style>").appendTo("head")
    })
}(window, document, jQuery);

/* jQuery FlexSlider v2.1 */
!function (d) {
    d.flexslider = function (u, e) {
        var p = d(u), m = d.extend({}, d.flexslider.defaults, e), s = m.namespace,
            r = "ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch,
            a = r ? "touchend" : "click", v = "vertical" === m.direction, f = m.reverse, g = 0 < m.itemWidth,
            h = "fade" === m.animation, l = "" !== m.asNavFor, c = {};
        d.data(u, "flexslider", p), c = {
            init: function () {
                p.animating = !1, p.currentSlide = m.startAt, p.animatingTo = p.currentSlide, p.atEnd = 0 === p.currentSlide || p.currentSlide === p.last, p.containerSelector = m.selector.substr(0, m.selector.search(" ")), p.slides = d(m.selector, p), p.container = d(p.containerSelector, p), p.count = p.slides.length, p.syncExists = 0 < d(m.sync).length, "slide" === m.animation && (m.animation = "swing"), p.prop = v ? "top" : "marginLeft", p.args = {}, p.manualPause = !1;
                var e, t = p;
                if ((e = !m.video) && (e = !h) && (e = m.useCSS)) e:{
                    e = document.createElement("div");
                    var n,
                        a = ["perspectiveProperty", "WebkitPerspective", "MozPerspective", "OPerspective", "msPerspective"];
                    for (n in a) if (void 0 !== e.style[a[n]]) {
                        p.pfx = a[n].replace("Perspective", "").toLowerCase(), p.prop = "-" + p.pfx + "-transform", e = !0;
                        break e
                    }
                    e = !1
                }
                t.transitions = e, "" !== m.controlsContainer && (p.controlsContainer = 0 < d(m.controlsContainer).length && d(m.controlsContainer)), "" !== m.manualControls && (p.manualControls = 0 < d(m.manualControls).length && d(m.manualControls)), m.randomize && (p.slides.sort(function () {
                    return Math.round(Math.random()) - .5
                }), p.container.empty().append(p.slides)), p.doMath(), l && c.asNav.setup(), p.setup("init"), m.controlNav && c.controlNav.setup(), m.directionNav && c.directionNav.setup(), m.keyboard && (1 === d(p.containerSelector).length || m.multipleKeyboard) && d(document).bind("keyup", function (e) {
                    e = e.keyCode, p.animating || 39 !== e && 37 !== e || (e = 39 === e ? p.getTarget("next") : 37 === e && p.getTarget("prev"), p.flexAnimate(e, m.pauseOnAction))
                }), m.mousewheel && p.bind("mousewheel", function (e, t) {
                    e.preventDefault();
                    var n = t < 0 ? p.getTarget("next") : p.getTarget("prev");
                    p.flexAnimate(n, m.pauseOnAction)
                }), m.pausePlay && c.pausePlay.setup(), m.slideshow && (m.pauseOnHover && p.hover(function () {
                    !p.manualPlay && !p.manualPause && p.pause()
                }, function () {
                    !p.manualPause && !p.manualPlay && p.play()
                }), 0 < m.initDelay ? setTimeout(p.play, m.initDelay) : p.play()), r && m.touch && c.touch(), (!h || h && m.smoothHeight) && d(window).bind("resize focus", c.resize), setTimeout(function () {
                    m.start(p)
                }, 200)
            }, asNav: {
                setup: function () {
                    p.asNav = !0, p.animatingTo = Math.floor(p.currentSlide / p.move), p.currentItem = p.currentSlide, p.slides.removeClass(s + "active-slide").eq(p.currentItem).addClass(s + "active-slide"), p.slides.click(function (e) {
                        e.preventDefault();
                        var t = (e = d(this)).index();
                        !d(m.asNavFor).data("flexslider").animating && !e.hasClass("active") && (p.direction = p.currentItem < t ? "next" : "prev", p.flexAnimate(t, m.pauseOnAction, !1, !0, !0))
                    })
                }
            }, controlNav: {
                setup: function () {
                    p.manualControls ? c.controlNav.setupManual() : c.controlNav.setupPaging()
                }, setupPaging: function () {
                    var e, t = 1;
                    if (p.controlNavScaffold = d('<ol class="' + s + "control-nav " + s + ("thumbnails" === m.controlNav ? "control-thumbs" : "control-paging") + '"></ol>'), 1 < p.pagingCount) for (var n = 0; n < p.pagingCount; n++) e = "thumbnails" === m.controlNav ? '<img src="' + p.slides.eq(n).attr("data-thumb") + '"/>' : "<a>" + t + "</a>", p.controlNavScaffold.append("<li>" + e + "</li>"), t++;
                    p.controlsContainer ? d(p.controlsContainer).append(p.controlNavScaffold) : p.append(p.controlNavScaffold), c.controlNav.set(), c.controlNav.active(), p.controlNavScaffold.delegate("a, img", a, function (e) {
                        e.preventDefault();
                        e = d(this);
                        var t = p.controlNav.index(e);
                        e.hasClass(s + "active") || (p.direction = t > p.currentSlide ? "next" : "prev", p.flexAnimate(t, m.pauseOnAction))
                    }), r && p.controlNavScaffold.delegate("a", "click touchstart", function (e) {
                        e.preventDefault()
                    })
                }, setupManual: function () {
                    p.controlNav = p.manualControls, c.controlNav.active(), p.controlNav.live(a, function (e) {
                        e.preventDefault();
                        e = d(this);
                        var t = p.controlNav.index(e);
                        e.hasClass(s + "active") || (t > p.currentSlide ? p.direction = "next" : p.direction = "prev", p.flexAnimate(t, m.pauseOnAction))
                    }), r && p.controlNav.live("click touchstart", function (e) {
                        e.preventDefault()
                    })
                }, set: function () {
                    p.controlNav = d("." + s + "control-nav li " + ("thumbnails" === m.controlNav ? "img" : "a"), p.controlsContainer ? p.controlsContainer : p)
                }, active: function () {
                    p.controlNav.removeClass(s + "active").eq(p.animatingTo).addClass(s + "active")
                }, update: function (e, t) {
                    1 < p.pagingCount && "add" === e ? p.controlNavScaffold.append(d("<li><a>" + p.count + "</a></li>")) : 1 === p.pagingCount ? p.controlNavScaffold.find("li").remove() : p.controlNav.eq(t).closest("li").remove(), c.controlNav.set(), 1 < p.pagingCount && p.pagingCount !== p.controlNav.length ? p.update(t, e) : c.controlNav.active()
                }
            }, directionNav: {
                setup: function () {
                    var e = d('<ul class="' + s + 'direction-nav"><li><a class="' + s + 'prev" href="#">' + m.prevText + '</a></li><li><a class="' + s + 'next" href="#">' + m.nextText + "</a></li></ul>");
                    p.controlsContainer ? (d(p.controlsContainer).append(e), p.directionNav = d("." + s + "direction-nav li a", p.controlsContainer)) : (p.append(e), p.directionNav = d("." + s + "direction-nav li a", p)), c.directionNav.update(), p.directionNav.bind(a, function (e) {
                        e.preventDefault(), e = d(this).hasClass(s + "next") ? p.getTarget("next") : p.getTarget("prev"), p.flexAnimate(e, m.pauseOnAction)
                    }), r && p.directionNav.bind("click touchstart", function (e) {
                        e.preventDefault()
                    })
                }, update: function () {
                    var e = s + "disabled";
                    1 === p.pagingCount ? p.directionNav.addClass(e) : m.animationLoop ? p.directionNav.removeClass(e) : 0 === p.animatingTo ? p.directionNav.removeClass(e).filter("." + s + "prev").addClass(e) : p.animatingTo === p.last ? p.directionNav.removeClass(e).filter("." + s + "next").addClass(e) : p.directionNav.removeClass(e)
                }
            }, pausePlay: {
                setup: function () {
                    var e = d('<div class="' + s + 'pauseplay"><a></a></div>');
                    p.controlsContainer ? (p.controlsContainer.append(e), p.pausePlay = d("." + s + "pauseplay a", p.controlsContainer)) : (p.append(e), p.pausePlay = d("." + s + "pauseplay a", p)), c.pausePlay.update(m.slideshow ? s + "pause" : s + "play"), p.pausePlay.bind(a, function (e) {
                        e.preventDefault(), d(this).hasClass(s + "pause") ? (p.manualPause = !0, p.manualPlay = !1, p.pause()) : (p.manualPause = !1, p.manualPlay = !0, p.play())
                    }), r && p.pausePlay.bind("click touchstart", function (e) {
                        e.preventDefault()
                    })
                }, update: function (e) {
                    "play" === e ? p.pausePlay.removeClass(s + "pause").addClass(s + "play").text(m.playText) : p.pausePlay.removeClass(s + "play").addClass(s + "pause").text(m.pauseText)
                }
            }, touch: function () {
                function n(e) {
                    l = v ? i - e.touches[0].pageY : i - e.touches[0].pageX, (!(d = v ? Math.abs(l) < Math.abs(e.touches[0].pageX - o) : Math.abs(l) < Math.abs(e.touches[0].pageY - o)) || 500 < Number(new Date) - c) && (e.preventDefault(), !h && p.transitions && (m.animationLoop || (l /= 0 === p.currentSlide && l < 0 || p.currentSlide === p.last && 0 < l ? Math.abs(l) / r + 2 : 1), p.setProps(s + l, "setTouch")))
                }

                function a() {
                    if (u.removeEventListener("touchmove", n, !1), p.animatingTo === p.currentSlide && !d && null !== l) {
                        var e = f ? -l : l, t = 0 < e ? p.getTarget("next") : p.getTarget("prev");
                        p.canAdvance(t) && (Number(new Date) - c < 550 && 50 < Math.abs(e) || Math.abs(e) > r / 2) ? p.flexAnimate(t, m.pauseOnAction) : h || p.flexAnimate(p.currentSlide, m.pauseOnAction, !0)
                    }
                    u.removeEventListener("touchend", a, !1), s = l = o = i = null
                }

                var i, o, s, r, l, c, d = !1;
                u.addEventListener("touchstart", function (e) {
                    p.animating ? e.preventDefault() : 1 === e.touches.length && (p.pause(), r = v ? p.h : p.w, c = Number(new Date), s = g && f && p.animatingTo === p.last ? 0 : g && f ? p.limit - (p.itemW + m.itemMargin) * p.move * p.animatingTo : g && p.currentSlide === p.last ? p.limit : g ? (p.itemW + m.itemMargin) * p.move * p.currentSlide : f ? (p.last - p.currentSlide + p.cloneOffset) * r : (p.currentSlide + p.cloneOffset) * r, i = v ? e.touches[0].pageY : e.touches[0].pageX, o = v ? e.touches[0].pageX : e.touches[0].pageY, u.addEventListener("touchmove", n, !1), u.addEventListener("touchend", a, !1))
                }, !1)
            }, resize: function () {
                !p.animating && p.is(":visible") && (g || p.doMath(), h ? c.smoothHeight() : g ? (p.slides.width(p.computedW), p.update(p.pagingCount), p.setProps()) : v ? (p.viewport.height(p.h), p.setProps(p.h, "setTotal")) : (m.smoothHeight && c.smoothHeight(), p.newSlides.width(p.computedW), p.setProps(p.computedW, "setTotal")))
            }, smoothHeight: function (e) {
                if (!v || h) {
                    var t = h ? p : p.viewport;
                    e ? t.animate({height: p.slides.eq(p.animatingTo).height()}, e) : t.height(p.slides.eq(p.animatingTo).height())
                }
            }, sync: function (e) {
                var t = d(m.sync).data("flexslider"), n = p.animatingTo;
                switch (e) {
                    case"animate":
                        t.flexAnimate(n, m.pauseOnAction, !1, !0);
                        break;
                    case"play":
                        !t.playing && !t.asNav && t.play();
                        break;
                    case"pause":
                        t.pause()
                }
            }
        }, p.flexAnimate = function (e, t, n, a, i) {
            if (l && 1 === p.pagingCount && (p.direction = p.currentItem < e ? "next" : "prev"), !p.animating && (p.canAdvance(e, i) || n) && p.is(":visible")) {
                if (l && a) {
                    if (n = d(m.asNavFor).data("flexslider"), p.atEnd = 0 === e || e === p.count - 1, n.flexAnimate(e, !0, !1, !0, i), p.direction = p.currentItem < e ? "next" : "prev", n.direction = p.direction, Math.ceil((e + 1) / p.visible) - 1 === p.currentSlide || 0 === e) return p.currentItem = e, p.slides.removeClass(s + "active-slide").eq(e).addClass(s + "active-slide"), !1;
                    p.currentItem = e, p.slides.removeClass(s + "active-slide").eq(e).addClass(s + "active-slide"), e = Math.floor(e / p.visible)
                }
                if (p.animating = !0, p.animatingTo = e, m.before(p), t && p.pause(), p.syncExists && !i && c.sync("animate"), m.controlNav && c.controlNav.active(), g || p.slides.removeClass(s + "active-slide").eq(e).addClass(s + "active-slide"), p.atEnd = 0 === e || e === p.last, m.directionNav && c.directionNav.update(), e === p.last && (m.end(p), m.animationLoop || p.pause()), h) r ? (p.slides.eq(p.currentSlide).css({
                    opacity: 0,
                    zIndex: 1
                }), p.slides.eq(e).css({
                    opacity: 1,
                    zIndex: 2
                }), p.slides.unbind("webkitTransitionEnd transitionend"), p.slides.eq(p.currentSlide).bind("webkitTransitionEnd transitionend", function () {
                    m.after(p)
                }), p.animating = !1, p.currentSlide = p.animatingTo) : (p.slides.eq(p.currentSlide).fadeOut(m.animationSpeed, m.easing), p.slides.eq(e).fadeIn(m.animationSpeed, m.easing, p.wrapup)); else {
                    var o = v ? p.slides.filter(":first").height() : p.computedW;
                    e = g ? (e = m.itemWidth > p.w ? 2 * m.itemMargin : m.itemMargin, (e = (p.itemW + e) * p.move * p.animatingTo) > p.limit && 1 !== p.visible ? p.limit : e) : 0 === p.currentSlide && e === p.count - 1 && m.animationLoop && "next" !== p.direction ? f ? (p.count + p.cloneOffset) * o : 0 : p.currentSlide === p.last && 0 === e && m.animationLoop && "prev" !== p.direction ? f ? 0 : (p.count + 1) * o : f ? (p.count - 1 - e + p.cloneOffset) * o : (e + p.cloneOffset) * o, p.setProps(e, "", m.animationSpeed), p.transitions ? (m.animationLoop && p.atEnd || (p.animating = !1, p.currentSlide = p.animatingTo), p.container.unbind("webkitTransitionEnd transitionend"), p.container.bind("webkitTransitionEnd transitionend", function () {
                        p.wrapup(o)
                    })) : p.container.animate(p.args, m.animationSpeed, m.easing, function () {
                        p.wrapup(o)
                    })
                }
                m.smoothHeight && c.smoothHeight(m.animationSpeed)
            }
        }, p.wrapup = function (e) {
            !h && !g && (0 === p.currentSlide && p.animatingTo === p.last && m.animationLoop ? p.setProps(e, "jumpEnd") : p.currentSlide === p.last && 0 === p.animatingTo && m.animationLoop && p.setProps(e, "jumpStart")), p.animating = !1, p.currentSlide = p.animatingTo, m.after(p)
        }, p.animateSlides = function () {
            p.animating || p.flexAnimate(p.getTarget("next"))
        }, p.pause = function () {
            clearInterval(p.animatedSlides), p.playing = !1, m.pausePlay && c.pausePlay.update("play"), p.syncExists && c.sync("pause")
        }, p.play = function () {
            p.animatedSlides = setInterval(p.animateSlides, m.slideshowSpeed), p.playing = !0, m.pausePlay && c.pausePlay.update("pause"), p.syncExists && c.sync("play")
        }, p.canAdvance = function (e, t) {
            var n = l ? p.pagingCount - 1 : p.last;
            return !!t || (!(!l || p.currentItem !== p.count - 1 || 0 !== e || "prev" !== p.direction) || (!l || 0 !== p.currentItem || e !== p.pagingCount - 1 || "next" === p.direction) && (!(e === p.currentSlide && !l) && (!!m.animationLoop || (!p.atEnd || 0 !== p.currentSlide || e !== n || "next" === p.direction) && (!p.atEnd || p.currentSlide !== n || 0 !== e || "next" !== p.direction))))
        }, p.getTarget = function (e) {
            return "next" === (p.direction = e) ? p.currentSlide === p.last ? 0 : p.currentSlide + 1 : 0 === p.currentSlide ? p.last : p.currentSlide - 1
        }, p.setProps = function (e, t, n) {
            var a, i = e || (p.itemW + m.itemMargin) * p.move * p.animatingTo;
            a = -1 * function () {
                if (g) return "setTouch" === t ? e : f && p.animatingTo === p.last ? 0 : f ? p.limit - (p.itemW + m.itemMargin) * p.move * p.animatingTo : p.animatingTo === p.last ? p.limit : i;
                switch (t) {
                    case"setTotal":
                        return f ? (p.count - 1 - p.currentSlide + p.cloneOffset) * e : (p.currentSlide + p.cloneOffset) * e;
                    case"setTouch":
                        return e;
                    case"jumpEnd":
                        return f ? e : p.count * e;
                    case"jumpStart":
                        return f ? p.count * e : e;
                    default:
                        return e
                }
            }() + "px", p.transitions && (a = v ? "translate3d(0," + a + ",0)" : "translate3d(" + a + ",0,0)", n = void 0 !== n ? n / 1e3 + "s" : "0s", p.container.css("-" + p.pfx + "-transition-duration", n)), p.args[p.prop] = a, (p.transitions || void 0 === n) && p.container.css(p.args)
        }, p.setup = function (e) {
            var t, n;
            h ? (p.slides.css({
                width: "100%",
                float: "left",
                marginRight: "-100%",
                position: "relative"
            }), "init" === e && (r ? p.slides.css({
                opacity: 0,
                display: "block",
                webkitTransition: "opacity " + m.animationSpeed / 1e3 + "s ease",
                zIndex: 1
            }).eq(p.currentSlide).css({
                opacity: 1,
                zIndex: 2
            }) : p.slides.eq(p.currentSlide).fadeIn(m.animationSpeed, m.easing)), m.smoothHeight && c.smoothHeight()) : ("init" === e && (p.viewport = d('<div class="' + s + 'viewport"></div>').css({
                overflow: "hidden",
                position: "relative"
            }).appendTo(p).append(p.container), p.cloneCount = 0, p.cloneOffset = 0, f && (n = d.makeArray(p.slides).reverse(), p.slides = d(n), p.container.empty().append(p.slides))), m.animationLoop && !g && (p.cloneCount = 2, p.cloneOffset = 1, "init" !== e && p.container.find(".clone").remove(), p.container.append(p.slides.first().clone().addClass("clone")).prepend(p.slides.last().clone().addClass("clone"))), p.newSlides = d(m.selector, p), t = f ? p.count - 1 - p.currentSlide + p.cloneOffset : p.currentSlide + p.cloneOffset, v && !g ? (p.container.height(200 * (p.count + p.cloneCount) + "%").css("position", "absolute").width("100%"), setTimeout(function () {
                p.newSlides.css({display: "block"}), p.doMath(), p.viewport.height(p.h), p.setProps(t * p.h, "init")
            }, "init" === e ? 100 : 0)) : (p.container.width(200 * (p.count + p.cloneCount) + "%"), p.setProps(t * p.computedW, "init"), setTimeout(function () {
                p.doMath(), p.newSlides.css({
                    width: p.computedW,
                    float: "left",
                    display: "block"
                }), m.smoothHeight && c.smoothHeight()
            }, "init" === e ? 100 : 0)));
            g || p.slides.removeClass(s + "active-slide").eq(p.currentSlide).addClass(s + "active-slide")
        }, p.doMath = function () {
            var e = p.slides.first(), t = m.itemMargin, n = m.minItems, a = m.maxItems;
            p.w = p.width(), p.h = e.height(), p.boxPadding = e.outerWidth() - e.width(), g ? (p.itemT = m.itemWidth + t, p.minW = n ? n * p.itemT : p.w, p.maxW = a ? a * p.itemT : p.w, p.itemW = p.minW > p.w ? (p.w - t * n) / n : p.maxW < p.w ? (p.w - t * a) / a : m.itemWidth > p.w ? p.w : m.itemWidth, p.visible = Math.floor(p.w / (p.itemW + t)), p.move = 0 < m.move && m.move < p.visible ? m.move : p.visible, p.pagingCount = Math.ceil((p.count - p.visible) / p.move + 1), p.last = p.pagingCount - 1, p.limit = 1 === p.pagingCount ? 0 : m.itemWidth > p.w ? (p.itemW + 2 * t) * p.count - p.w - t : (p.itemW + t) * p.count - p.w - t) : (p.itemW = p.w, p.pagingCount = p.count, p.last = p.count - 1), p.computedW = p.itemW - p.boxPadding
        }, p.update = function (e, t) {
            p.doMath(), g || (e < p.currentSlide ? p.currentSlide += 1 : e <= p.currentSlide && 0 !== e && (p.currentSlide -= 1), p.animatingTo = p.currentSlide), m.controlNav && !p.manualControls && ("add" === t && !g || p.pagingCount > p.controlNav.length ? c.controlNav.update("add") : ("remove" === t && !g || p.pagingCount < p.controlNav.length) && (g && p.currentSlide > p.last && (p.currentSlide -= 1, p.animatingTo -= 1), c.controlNav.update("remove", p.last))), m.directionNav && c.directionNav.update()
        }, p.addSlide = function (e, t) {
            var n = d(e);
            p.count += 1, p.last = p.count - 1, v && f ? void 0 !== t ? p.slides.eq(p.count - t).after(n) : p.container.prepend(n) : void 0 !== t ? p.slides.eq(t).before(n) : p.container.append(n), p.update(t, "add"), p.slides = d(m.selector + ":not(.clone)", p), p.setup(), m.added(p)
        }, p.removeSlide = function (e) {
            var t = isNaN(e) ? p.slides.index(d(e)) : e;
            p.count -= 1, p.last = p.count - 1, isNaN(e) ? d(e, p.slides).remove() : v && f ? p.slides.eq(p.last).remove() : p.slides.eq(e).remove(), p.doMath(), p.update(t, "remove"), p.slides = d(m.selector + ":not(.clone)", p), p.setup(), m.removed(p)
        }, c.init()
    }, d.flexslider.defaults = {
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
        slideshowSpeed: 7e3,
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
    }, d.fn.flexslider = function (n) {
        if (void 0 === n && (n = {}), "object" == typeof n) return this.each(function () {
            var e = d(this), t = e.find(n.selector ? n.selector : ".slides > li");
            1 === t.length ? (t.fadeIn(400), n.start && n.start(e)) : null == e.data("flexslider") && new d.flexslider(this, n)
        });
        var e = d(this).data("flexslider");
        switch (n) {
            case"play":
                e.play();
                break;
            case"pause":
                e.pause();
                break;
            case"next":
                e.flexAnimate(e.getTarget("next"), !0);
                break;
            case"prev":
            case"previous":
                e.flexAnimate(e.getTarget("prev"), !0);
                break;
            default:
                "number" == typeof n && e.flexAnimate(n, !0)
        }
    }
}(jQuery);

/* http://bassistance.de/jquery-plugins/jquery-plugin-validation/ 1.11.1 */
!function (l) {
    l.extend(l.fn, {
        validate: function (t) {
            if (this.length) {
                var i = l.data(this[0], "validator");
                return i || (this.attr("novalidate", "novalidate"), i = new l.validator(t, this[0]), l.data(this[0], "validator", i), i.settings.onsubmit && (this.validateDelegate(":submit", "click", function (t) {
                    i.settings.submitHandler && (i.submitButton = t.target), l(t.target).hasClass("cancel") && (i.cancelSubmit = !0), void 0 !== l(t.target).attr("formnovalidate") && (i.cancelSubmit = !0)
                }), this.submit(function (e) {
                    function t() {
                        var t;
                        return !i.settings.submitHandler || (i.submitButton && (t = l("<input type='hidden'/>").attr("name", i.submitButton.name).val(l(i.submitButton).val()).appendTo(i.currentForm)), i.settings.submitHandler.call(i, i.currentForm, e), i.submitButton && t.remove(), !1)
                    }

                    return i.settings.debug && e.preventDefault(), i.cancelSubmit ? (i.cancelSubmit = !1, t()) : i.form() ? i.pendingRequest ? !(i.formSubmitted = !0) : t() : (i.focusInvalid(), !1)
                })), i)
            }
            t && t.debug && window.console && console.warn("Nothing selected, can't validate, returning nothing.")
        }, valid: function () {
            if (l(this[0]).is("form")) return this.validate().form();
            var t = !0, e = l(this[0].form).validate();
            return this.each(function () {
                t = t && e.element(this)
            }), t
        }, removeAttrs: function (t) {
            var i = {}, s = this;
            return l.each(t.split(/\s/), function (t, e) {
                i[e] = s.attr(e), s.removeAttr(e)
            }), i
        }, rules: function (t, e) {
            var i = this[0];
            if (t) {
                var s = l.data(i.form, "validator").settings, r = s.rules, n = l.validator.staticRules(i);
                switch (t) {
                    case"add":
                        l.extend(n, l.validator.normalizeRule(e)), delete n.messages, r[i.name] = n, e.messages && (s.messages[i.name] = l.extend(s.messages[i.name], e.messages));
                        break;
                    case"remove":
                        if (!e) return delete r[i.name], n;
                        var a = {};
                        return l.each(e.split(/\s/), function (t, e) {
                            a[e] = n[e], delete n[e]
                        }), a
                }
            }
            var u = l.validator.normalizeRules(l.extend({}, l.validator.classRules(i), l.validator.attributeRules(i), l.validator.dataRules(i), l.validator.staticRules(i)), i);
            if (u.required) {
                var o = u.required;
                delete u.required, u = l.extend({required: o}, u)
            }
            return u
        }
    }), l.extend(l.expr[":"], {
        blank: function (t) {
            return !l.trim("" + l(t).val())
        }, filled: function (t) {
            return !!l.trim("" + l(t).val())
        }, unchecked: function (t) {
            return !l(t).prop("checked")
        }
    }), l.validator = function (t, e) {
        this.settings = l.extend(!0, {}, l.validator.defaults, t), this.currentForm = e, this.init()
    }, l.validator.format = function (i, t) {
        return 1 === arguments.length ? function () {
            var t = l.makeArray(arguments);
            return t.unshift(i), l.validator.format.apply(this, t)
        } : (2 < arguments.length && t.constructor !== Array && (t = l.makeArray(arguments).slice(1)), t.constructor !== Array && (t = [t]), l.each(t, function (t, e) {
            i = i.replace(new RegExp("\\{" + t + "\\}", "g"), function () {
                return e
            })
        }), i)
    }, l.extend(l.validator, {
        defaults: {
            messages: {},
            groups: {},
            rules: {},
            errorClass: "error",
            validClass: "valid",
            errorElement: "label",
            focusInvalid: !0,
            errorContainer: l([]),
            errorLabelContainer: l([]),
            onsubmit: !0,
            ignore: ":hidden",
            ignoreTitle: !1,
            onfocusin: function (t, e) {
                this.lastActive = t, this.settings.focusCleanup && !this.blockFocusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, t, this.settings.errorClass, this.settings.validClass), this.addWrapper(this.errorsFor(t)).hide())
            },
            onfocusout: function (t, e) {
                this.checkable(t) || !(t.name in this.submitted) && this.optional(t) || this.element(t)
            },
            onkeyup: function (t, e) {
                9 === e.which && "" === this.elementValue(t) || (t.name in this.submitted || t === this.lastElement) && this.element(t)
            },
            onclick: function (t, e) {
                t.name in this.submitted ? this.element(t) : t.parentNode.name in this.submitted && this.element(t.parentNode)
            },
            highlight: function (t, e, i) {
                "radio" === t.type ? this.findByName(t.name).addClass(e).removeClass(i) : l(t).addClass(e).removeClass(i)
            },
            unhighlight: function (t, e, i) {
                "radio" === t.type ? this.findByName(t.name).removeClass(e).addClass(i) : l(t).removeClass(e).addClass(i)
            }
        },
        setDefaults: function (t) {
            l.extend(l.validator.defaults, t)
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
            maxlength: l.validator.format("Please enter no more than {0} characters."),
            minlength: l.validator.format("Please enter at least {0} characters."),
            rangelength: l.validator.format("Please enter a value between {0} and {1} characters long."),
            range: l.validator.format("Please enter a value between {0} and {1}."),
            max: l.validator.format("Please enter a value less than or equal to {0}."),
            min: l.validator.format("Please enter a value greater than or equal to {0}.")
        },
        autoCreateRanges: !1,
        prototype: {
            init: function () {
                this.labelContainer = l(this.settings.errorLabelContainer), this.errorContext = this.labelContainer.length && this.labelContainer || l(this.currentForm), this.containers = l(this.settings.errorContainer).add(this.settings.errorLabelContainer), this.submitted = {}, this.valueCache = {}, this.pendingRequest = 0, this.pending = {}, this.invalid = {}, this.reset();
                var s = this.groups = {};
                l.each(this.settings.groups, function (i, t) {
                    "string" == typeof t && (t = t.split(/\s/)), l.each(t, function (t, e) {
                        s[e] = i
                    })
                });
                var i = this.settings.rules;

                function t(t) {
                    var e = l.data(this[0].form, "validator"), i = "on" + t.type.replace(/^validate/, "");
                    e.settings[i] && e.settings[i].call(e, this[0], t)
                }

                l.each(i, function (t, e) {
                    i[t] = l.validator.normalizeRule(e)
                }), l(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'] ", "focusin focusout keyup", t).validateDelegate("[type='radio'], [type='checkbox'], select, option", "click", t), this.settings.invalidHandler && l(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler)
            }, form: function () {
                return this.checkForm(), l.extend(this.submitted, this.errorMap), this.invalid = l.extend({}, this.errorMap), this.valid() || l(this.currentForm).triggerHandler("invalid-form", [this]), this.showErrors(), this.valid()
            }, checkForm: function () {
                this.prepareForm();
                for (var t = 0, e = this.currentElements = this.elements(); e[t]; t++) this.check(e[t]);
                return this.valid()
            }, element: function (t) {
                t = this.validationTargetFor(this.clean(t)), this.lastElement = t, this.prepareElement(t), this.currentElements = l(t);
                var e = !1 !== this.check(t);
                return e ? delete this.invalid[t.name] : this.invalid[t.name] = !0, this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers)), this.showErrors(), e
            }, showErrors: function (e) {
                if (e) {
                    for (var t in l.extend(this.errorMap, e), this.errorList = [], e) this.errorList.push({
                        message: e[t],
                        element: this.findByName(t)[0]
                    });
                    this.successList = l.grep(this.successList, function (t) {
                        return !(t.name in e)
                    })
                }
                this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
            }, resetForm: function () {
                l.fn.resetForm && l(this.currentForm).resetForm(), this.submitted = {}, this.lastElement = null, this.prepareForm(), this.hideErrors(), this.elements().removeClass(this.settings.errorClass).removeData("previousValue")
            }, numberOfInvalids: function () {
                return this.objectLength(this.invalid)
            }, objectLength: function (t) {
                var e = 0;
                for (var i in t) e++;
                return e
            }, hideErrors: function () {
                this.addWrapper(this.toHide).hide()
            }, valid: function () {
                return 0 === this.size()
            }, size: function () {
                return this.errorList.length
            }, focusInvalid: function () {
                if (this.settings.focusInvalid) try {
                    l(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin")
                } catch (t) {
                }
            }, findLastActive: function () {
                var e = this.lastActive;
                return e && 1 === l.grep(this.errorList, function (t) {
                    return t.element.name === e.name
                }).length && e
            }, elements: function () {
                var t = this, e = {};
                return l(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function () {
                    return !this.name && t.settings.debug && window.console && console.error("%o has no name assigned", this), !(this.name in e || !t.objectLength(l(this).rules())) && (e[this.name] = !0)
                })
            }, clean: function (t) {
                return l(t)[0]
            }, errors: function () {
                var t = this.settings.errorClass.replace(" ", ".");
                return l(this.settings.errorElement + "." + t, this.errorContext)
            }, reset: function () {
                this.successList = [], this.errorList = [], this.errorMap = {}, this.toShow = l([]), this.toHide = l([]), this.currentElements = l([])
            }, prepareForm: function () {
                this.reset(), this.toHide = this.errors().add(this.containers)
            }, prepareElement: function (t) {
                this.reset(), this.toHide = this.errorsFor(t)
            }, elementValue: function (t) {
                var e = l(t).attr("type"), i = l(t).val();
                return "radio" === e || "checkbox" === e ? l("input[name='" + l(t).attr("name") + "']:checked").val() : "string" == typeof i ? i.replace(/\r/g, "") : i
            }, check: function (e) {
                e = this.validationTargetFor(this.clean(e));
                var t, i = l(e).rules(), s = !1, r = this.elementValue(e);
                for (var n in i) {
                    var a = {method: n, parameters: i[n]};
                    try {
                        if ("dependency-mismatch" === (t = l.validator.methods[n].call(this, r, e, a.parameters))) {
                            s = !0;
                            continue
                        }
                        if (s = !1, "pending" === t) return void(this.toHide = this.toHide.not(this.errorsFor(e)));
                        if (!t) return this.formatAndAdd(e, a), !1
                    } catch (t) {
                        throw this.settings.debug && window.console && console.log("Exception occurred when checking element " + e.id + ", check the '" + a.method + "' method.", t), t
                    }
                }
                if (!s) return this.objectLength(i) && this.successList.push(e), !0
            }, customDataMessage: function (t, e) {
                return l(t).data("msg-" + e.toLowerCase()) || t.attributes && l(t).attr("data-msg-" + e.toLowerCase())
            }, customMessage: function (t, e) {
                var i = this.settings.messages[t];
                return i && (i.constructor === String ? i : i[e])
            }, findDefined: function () {
                for (var t = 0; t < arguments.length; t++) if (void 0 !== arguments[t]) return arguments[t]
            }, defaultMessage: function (t, e) {
                return this.findDefined(this.customMessage(t.name, e), this.customDataMessage(t, e), !this.settings.ignoreTitle && t.title || void 0, l.validator.messages[e], "<strong>Warning: No message defined for " + t.name + "</strong>")
            }, formatAndAdd: function (t, e) {
                var i = this.defaultMessage(t, e.method), s = /\$?\{(\d+)\}/g;
                "function" == typeof i ? i = i.call(this, e.parameters, t) : s.test(i) && (i = l.validator.format(i.replace(s, "{$1}"), e.parameters)), this.errorList.push({
                    message: i,
                    element: t
                }), this.errorMap[t.name] = i, this.submitted[t.name] = i
            }, addWrapper: function (t) {
                return this.settings.wrapper && (t = t.add(t.parent(this.settings.wrapper))), t
            }, defaultShowErrors: function () {
                var t, e;
                for (t = 0; this.errorList[t]; t++) {
                    var i = this.errorList[t];
                    this.settings.highlight && this.settings.highlight.call(this, i.element, this.settings.errorClass, this.settings.validClass), this.showLabel(i.element, i.message)
                }
                if (this.errorList.length && (this.toShow = this.toShow.add(this.containers)), this.settings.success) for (t = 0; this.successList[t]; t++) this.showLabel(this.successList[t]);
                if (this.settings.unhighlight) for (t = 0, e = this.validElements(); e[t]; t++) this.settings.unhighlight.call(this, e[t], this.settings.errorClass, this.settings.validClass);
                this.toHide = this.toHide.not(this.toShow), this.hideErrors(), this.addWrapper(this.toShow).show()
            }, validElements: function () {
                return this.currentElements.not(this.invalidElements())
            }, invalidElements: function () {
                return l(this.errorList).map(function () {
                    return this.element
                })
            }, showLabel: function (t, e) {
                var i = this.errorsFor(t);
                i.length ? (i.removeClass(this.settings.validClass).addClass(this.settings.errorClass), i.html(e)) : (i = l("<" + this.settings.errorElement + ">").attr("for", this.idOrName(t)).addClass(this.settings.errorClass).html(e || ""), this.settings.wrapper && (i = i.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()), this.labelContainer.append(i).length || (this.settings.errorPlacement ? this.settings.errorPlacement(i, l(t)) : i.insertAfter(t))), !e && this.settings.success && (i.text(""), "string" == typeof this.settings.success ? i.addClass(this.settings.success) : this.settings.success(i, t)), this.toShow = this.toShow.add(i)
            }, errorsFor: function (t) {
                var e = this.idOrName(t);
                return this.errors().filter(function () {
                    return l(this).attr("for") === e
                })
            }, idOrName: function (t) {
                return this.groups[t.name] || (this.checkable(t) ? t.name : t.id || t.name)
            }, validationTargetFor: function (t) {
                return this.checkable(t) && (t = this.findByName(t.name).not(this.settings.ignore)[0]), t
            }, checkable: function (t) {
                return /radio|checkbox/i.test(t.type)
            }, findByName: function (t) {
                return l(this.currentForm).find("[name='" + t + "']")
            }, getLength: function (t, e) {
                switch (e.nodeName.toLowerCase()) {
                    case"select":
                        return l("option:selected", e).length;
                    case"input":
                        if (this.checkable(e)) return this.findByName(e.name).filter(":checked").length
                }
                return t.length
            }, depend: function (t, e) {
                return !this.dependTypes[typeof t] || this.dependTypes[typeof t](t, e)
            }, dependTypes: {
                boolean: function (t, e) {
                    return t
                }, string: function (t, e) {
                    return !!l(t, e.form).length
                }, function: function (t, e) {
                    return t(e)
                }
            }, optional: function (t) {
                var e = this.elementValue(t);
                return !l.validator.methods.required.call(this, e, t) && "dependency-mismatch"
            }, startRequest: function (t) {
                this.pending[t.name] || (this.pendingRequest++, this.pending[t.name] = !0)
            }, stopRequest: function (t, e) {
                this.pendingRequest--, this.pendingRequest < 0 && (this.pendingRequest = 0), delete this.pending[t.name], e && 0 === this.pendingRequest && this.formSubmitted && this.form() ? (l(this.currentForm).submit(), this.formSubmitted = !1) : !e && 0 === this.pendingRequest && this.formSubmitted && (l(this.currentForm).triggerHandler("invalid-form", [this]), this.formSubmitted = !1)
            }, previousValue: function (t) {
                return l.data(t, "previousValue") || l.data(t, "previousValue", {
                    old: null,
                    valid: !0,
                    message: this.defaultMessage(t, "remote")
                })
            }
        },
        classRuleSettings: {
            required: {required: !0},
            email: {email: !0},
            url: {url: !0},
            date: {date: !0},
            dateISO: {dateISO: !0},
            number: {number: !0},
            digits: {digits: !0},
            creditcard: {creditcard: !0}
        },
        addClassRules: function (t, e) {
            t.constructor === String ? this.classRuleSettings[t] = e : l.extend(this.classRuleSettings, t)
        },
        classRules: function (t) {
            var e = {}, i = l(t).attr("class");
            return i && l.each(i.split(" "), function () {
                this in l.validator.classRuleSettings && l.extend(e, l.validator.classRuleSettings[this])
            }), e
        },
        attributeRules: function (t) {
            var e = {}, i = l(t), s = i[0].getAttribute("type");
            for (var r in l.validator.methods) {
                var n;
                n = "required" === r ? ("" === (n = i.get(0).getAttribute(r)) && (n = !0), !!n) : i.attr(r), /min|max/.test(r) && (null === s || /number|range|text/.test(s)) && (n = Number(n)), n ? e[r] = n : s === r && "range" !== s && (e[r] = !0)
            }
            return e.maxlength && /-1|2147483647|524288/.test(e.maxlength) && delete e.maxlength, e
        },
        dataRules: function (t) {
            var e, i, s = {}, r = l(t);
            for (e in l.validator.methods) void 0 !== (i = r.data("rule-" + e.toLowerCase())) && (s[e] = i);
            return s
        },
        staticRules: function (t) {
            var e = {}, i = l.data(t.form, "validator");
            return i.settings.rules && (e = l.validator.normalizeRule(i.settings.rules[t.name]) || {}), e
        },
        normalizeRules: function (s, r) {
            return l.each(s, function (t, e) {
                if (!1 !== e) {
                    if (e.param || e.depends) {
                        var i = !0;
                        switch (typeof e.depends) {
                            case"string":
                                i = !!l(e.depends, r.form).length;
                                break;
                            case"function":
                                i = e.depends.call(r, r)
                        }
                        i ? s[t] = void 0 === e.param || e.param : delete s[t]
                    }
                } else delete s[t]
            }), l.each(s, function (t, e) {
                s[t] = l.isFunction(e) ? e(r) : e
            }), l.each(["minlength", "maxlength"], function () {
                s[this] && (s[this] = Number(s[this]))
            }), l.each(["rangelength", "range"], function () {
                var t;
                s[this] && (l.isArray(s[this]) ? s[this] = [Number(s[this][0]), Number(s[this][1])] : "string" == typeof s[this] && (t = s[this].split(/[\s,]+/), s[this] = [Number(t[0]), Number(t[1])]))
            }), l.validator.autoCreateRanges && (s.min && s.max && (s.range = [s.min, s.max], delete s.min, delete s.max), s.minlength && s.maxlength && (s.rangelength = [s.minlength, s.maxlength], delete s.minlength, delete s.maxlength)), s
        },
        normalizeRule: function (t) {
            if ("string" == typeof t) {
                var e = {};
                l.each(t.split(/\s/), function () {
                    e[this] = !0
                }), t = e
            }
            return t
        },
        addMethod: function (t, e, i) {
            l.validator.methods[t] = e, l.validator.messages[t] = void 0 !== i ? i : l.validator.messages[t], e.length < 3 && l.validator.addClassRules(t, l.validator.normalizeRule(t))
        },
        methods: {
            required: function (t, e, i) {
                if (!this.depend(i, e)) return "dependency-mismatch";
                if ("select" !== e.nodeName.toLowerCase()) return this.checkable(e) ? 0 < this.getLength(t, e) : 0 < l.trim(t).length;
                var s = l(e).val();
                return s && 0 < s.length
            }, email: function (t, e) {
                return this.optional(e) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(t)
            }, url: function (t, e) {
                return this.optional(e) || /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(t)
            }, date: function (t, e) {
                return this.optional(e) || !/Invalid|NaN/.test(new Date(t).toString())
            }, dateISO: function (t, e) {
                return this.optional(e) || /^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/.test(t)
            }, number: function (t, e) {
                return this.optional(e) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(t)
            }, digits: function (t, e) {
                return this.optional(e) || /^\d+$/.test(t)
            }, creditcard: function (t, e) {
                if (this.optional(e)) return "dependency-mismatch";
                if (/[^0-9 \-]+/.test(t)) return !1;
                for (var i = 0, s = 0, r = !1, n = (t = t.replace(/\D/g, "")).length - 1; 0 <= n; n--) {
                    var a = t.charAt(n);
                    s = parseInt(a, 10), r && 9 < (s *= 2) && (s -= 9), i += s, r = !r
                }
                return i % 10 == 0
            }, minlength: function (t, e, i) {
                var s = l.isArray(t) ? t.length : this.getLength(l.trim(t), e);
                return this.optional(e) || i <= s
            }, maxlength: function (t, e, i) {
                var s = l.isArray(t) ? t.length : this.getLength(l.trim(t), e);
                return this.optional(e) || s <= i
            }, rangelength: function (t, e, i) {
                var s = l.isArray(t) ? t.length : this.getLength(l.trim(t), e);
                return this.optional(e) || s >= i[0] && s <= i[1]
            }, min: function (t, e, i) {
                return this.optional(e) || i <= t
            }, max: function (t, e, i) {
                return this.optional(e) || t <= i
            }, range: function (t, e, i) {
                return this.optional(e) || t >= i[0] && t <= i[1]
            }, equalTo: function (t, e, i) {
                var s = l(i);
                return this.settings.onfocusout && s.unbind(".validate-equalTo").bind("blur.validate-equalTo", function () {
                    l(e).valid()
                }), t === s.val()
            }, remote: function (n, a, t) {
                if (this.optional(a)) return "dependency-mismatch";
                var u = this.previousValue(a);
                if (this.settings.messages[a.name] || (this.settings.messages[a.name] = {}), u.originalMessage = this.settings.messages[a.name].remote, this.settings.messages[a.name].remote = u.message, t = "string" == typeof t && {url: t} || t, u.old === n) return u.valid;
                u.old = n;
                var o = this;
                this.startRequest(a);
                var e = {};
                return e[a.name] = n, l.ajax(l.extend(!0, {
                    url: t,
                    mode: "abort",
                    port: "validate" + a.name,
                    dataType: "json",
                    data: e,
                    success: function (t) {
                        o.settings.messages[a.name].remote = u.originalMessage;
                        var e = !0 === t || "true" === t;
                        if (e) {
                            var i = o.formSubmitted;
                            o.prepareElement(a), o.formSubmitted = i, o.successList.push(a), delete o.invalid[a.name], o.showErrors()
                        } else {
                            var s = {}, r = t || o.defaultMessage(a, "remote");
                            s[a.name] = u.message = l.isFunction(r) ? r(n) : r, o.invalid[a.name] = !0, o.showErrors(s)
                        }
                        u.valid = e, o.stopRequest(a, e)
                    }
                }, t)), "pending"
            }
        }
    }), l.format = l.validator.format
}(jQuery), function (s) {
    var r = {};
    if (s.ajaxPrefilter) s.ajaxPrefilter(function (t, e, i) {
        var s = t.port;
        "abort" === t.mode && (r[s] && r[s].abort(), r[s] = i)
    }); else {
        var n = s.ajax;
        s.ajax = function (t) {
            var e = ("mode" in t ? t : s.ajaxSettings).mode, i = ("port" in t ? t : s.ajaxSettings).port;
            return "abort" === e ? (r[i] && r[i].abort(), r[i] = n.apply(this, arguments), r[i]) : n.apply(this, arguments)
        }
    }
}(jQuery), function (r) {
    r.extend(r.fn, {
        validateDelegate: function (i, t, s) {
            return this.bind(t, function (t) {
                var e = r(t.target);
                if (e.is(i)) return s.apply(e, arguments)
            })
        }
    })
}(jQuery);

/*------*/
var _$$ = {
        version: 20120603, $: function () {
            var e;
            switch (arguments.length) {
                case 0:
                    return document;
                case 2:
                    _$$.$(arguments[0]).innerHTML = arguments[1];
                    break;
                case 3:
                    _$$.$(arguments[0]).style[arguments[1]] = arguments[2]
            }
            return 0 < arguments.length && (e = "string" == typeof arguments[0] ? _$$.$().getElementById(arguments[0]) : arguments[0]), null != e && (e.$$ = function () {
                switch (arguments.length) {
                    case 0:
                        this.$$ = function () {
                            return _$$.$.apply(null, arguments)
                        };
                        break;
                    case 1:
                        e.innerHTML = arguments[0];
                        break;
                    case 2:
                        e.style[arguments[0]] = arguments[1]
                }
                return e
            }, e.$$after = function (e) {
                for (var t = this, n = _$$.$(e); (n = n.nextSibling) && 1 != n.nodeType;) ;
                return null != n ? n != t && n.parentNode.insertBefore(t, n) : e != t && e.parentNode.appendChild(t), _$$.$(t)
            }, e.$$before = function (e) {
                for (var t = this, n = _$$.$(e); (n = n.previousSibling) && 1 != n.nodeType;) ;
                var o = n;
                if (null != o) {
                    for (; (o = o.nextSibling) && 1 != o.nodeType;) ;
                    n != t && n.parentNode.insertBefore(t, o)
                } else e != t && e.parentNode.insertBefore(t, e);
                return _$$.$(t)
            }, e.$$prev = function () {
                for (var e = this; (e = e.previousSibling) && 1 != e.nodeType;) ;
                return null != e ? _$$.$(e) : e
            }, e.$$next = function () {
                for (var e = this; (e = e.nextSibling) && 1 != e.nodeType;) ;
                return null != e ? _$$.$(e) : e
            }, e.$$first = function () {
                var e = this;
                return null != (e = (e = e.firstChild) && 1 != e.nodeType ? e.nextSibling : e) ? _$$.$(e) : e
            }, e.$$last = function () {
                var e = this;
                return null != (e = (e = e.lastChild) && 1 != e.nodeType ? e.previousSibling : e) ? _$$.$(e) : e
            }, e.$$parent = function (e) {
                var t = this;
                e = e || 1;
                for (var n = 0; n < e; n++) null != t && (t = t.parentNode);
                return null != t ? _$$.$(t) : t
            }, e.$$html = function () {
                return _$$.$(this).innerHTML
            }, e)
        }, $a: function (e) {
            e = e || {};
            var t = {
                type: "get",
                url: "",
                data: {},
                response: "text",
                header: {"Content-Type": "application/x-www-form-urlencoded; charset=windows-1251", Referer: location.href},
                async: !0,
                username: "",
                password: "",
                errrep: !1,
                error: function (e) {
                    alert(["Your browser does not support Ajax", "Request failed", "Address does not exist", "The waiting time left"][e])
                },
                status: function (e) {
                },
                endstatus: function (e) {
                },
                success: function (e) {
                },
                timeout: 0
            };
            for (var n in t) void 0 === e[n] && (e[n] = t[n]);
            var o = e.type, r = e.url, i = e.data, s = e.response, $ = e.header, a = e.async, l = e.username,
                c = e.password, d = e.errrep, u = e.error, f = e.status, h = e.endstatus, p = e.success, m = e.timeout,
                v = function (e) {
                    var t = [];
                    if (e instanceof Object) {
                        for (var n in e) t.push(encodeURIComponent(n) + "=" + encodeURIComponent(e[n]));
                        return t.join("&")
                    }
                    return encodeURIComponent(e)
                }, w = function (e) {
                    for (var t = {}, n = /^\s*/, o = /\s*$/, r = e.split("\n"), i = 0; i < r.length; i++) {
                        var s = r[i];
                        if (0 != s.length) {
                            var $ = s.indexOf(":"), a = s.substring(0, $).replace(n, "").replace(o, ""),
                                l = s.substring($ + 1).replace(n, "").replace(o, "");
                            t[a] = l
                        }
                    }
                    return t
                }, _ = function () {
                    var t = null;
                    if (window.XMLHttpRequest) try {
                        t = new XMLHttpRequest
                    } catch (e) {
                    } else if (window.ActiveXObject) try {
                        t = new ActiveXObject("Msxml2.XMLHTTP")
                    } catch (e) {
                        try {
                            t = new ActiveXObject("Microsoft.XMLHTTP")
                        } catch (e) {
                        }
                    }
                    return t
                }();
            if (null !== _) {
                var g = r + "?" + v(i);
                if ("get" == o ? "" == l && "" == c ? _.open("GET", g, a) : _.open("GET", g, a, l, c) : "post" == o ? "" == l && "" == c ? _.open("POST", r, a) : _.open("POST", r, a, l, c) : "head" == o && ("" == l && "" == c ? _.open("HEAD", g, a) : _.open("HEAD", g, a, l, c)), $ instanceof Object) for (var y in $) _.setRequestHeader(y, $[y]);
                if (a) {
                    if (_.onreadystatechange = function () {
                        f(_.readyState), 4 == _.readyState && (b && clearTimeout(b), h(_.status), 200 == _.status ? "get" == o || "post" == o ? "text" == s ? p(_.responseText) : "xml" == s && p(_.responseXML) : "head" == o && p(w(_.getAllResponseHeaders())) : 404 == _.status ? d && u(2) : d && u(1))
                    }, "get" == o || "head" == o ? _.send(null) : "post" == o && _.send(v(i)), 0 < m) var b = setTimeout(function () {
                        4 != _.readyState && (_.abort(), d && u(3))
                    }, m)
                } else "get" == o || "head" == o ? _.send(null) : "post" == o && _.send(v(i)), h(_.status), 200 == _.status ? "get" == o || "post" == o ? "text" == s ? p(_.responseText) : "xml" == s && p(_.responseXML) : "head" == o && p(w(_.getAllResponseHeaders())) : 404 == _.status ? d && u(2) : d && u(1)
            } else d && u(0)
        }, $c: {
            set: function (e, t, n, o, r, i) {
                if (void 0 !== e) {
                    n = n || 0;
                    var s = new Date;
                    s.setTime(s.getTime() + 1e3 * n), _$$.$().cookie = e + "=" + encodeURIComponent(t) + "; " + (void 0 === n ? "" : "expires=" + s.toGMTString() + "; ") + (void 0 === o ? "path=/;" : "path=" + o + "; ") + (void 0 === r ? "" : "domain=" + r + "; ") + (!0 === i ? "secure; " : "")
                }
            }, get: function (e) {
                var t = _$$.$().cookie, n = t.length;
                if (n) {
                    var o = t.indexOf(e + "=");
                    if (-1 != o) {
                        var r = t.indexOf(";", o);
                        return -1 == r && (r = n), o += e.length + 1, decodeURIComponent(t.substring(o, r))
                    }
                }
            }, erase: function (e) {
                this.set(e, "", -1)
            }, test: function () {
                this.set("test_cookie", "test", 10);
                var e = "test" === this.get("test_cookie");
                return this.erase("test_cookie"), e
            }
        }, $e: {
            add: function (o, r, e) {
                if (null == o.event_list && (o.event_list = {}), null == o.event_list[r]) {
                    o.event_list[r] = [];
                    var t = function (e) {
                        e = e || window.event;
                        var t = o.event_list[r];
                        for (var n in t) t[n](e)
                    };
                    o.addEventListener ? o.addEventListener(r, t, !1) : o.attachEvent ? o.attachEvent("on" + r, t) : o["on" + r] = t
                }
                var n = o.event_list[r], i = !1;
                for (var s in n) n[s] == e && (i = !0);
                i || o.event_list[r].push(e)
            }, remove: function (e, t, n) {
                if (null == e.event_list) return !1;
                if (null == e.event_list[t]) return !1;
                var o = e.event_list[t];
                for (var r in o) if (o[r] == n) return o = o.splice(r, 1), !0;
                return !1
            }
        }, $f: function (e) {
            e = e || {};
            var t = {
                formid: "", url: "", onstart: function () {
                }, onsend: function () {
                }
            };
            for (var n in t) void 0 === e[n] && (e[n] = t[n]);
            var o = e.formid, r = e.url, i = e.onsend, s = e.onstart, $ = "f" + _$$.$s.randnum(0, 1e6),
                a = _$$.$i({create: "div", attribute: {}, insert: _$$.$().body});
            return a.innerHTML = '<iframe style="width:250px;height:200px;" src="about:blank" id="' + $ + '" name="' + $ + '" onload="if(this.onsendcomplete) {if(this.contentDocument) {var d = this.contentDocument;}else if(this.contentWindow) {var d = this.contentWindow.document;}else {var d = window.frames[this.id].document;}if(d.location.href != \'about:blank\') {this.onsendcomplete();}}"></iframe>', a.style.display = "none", _$$.$($).onsendcomplete = function () {
                setTimeout(function () {
                    _$$.$().body.removeChild(a)
                }, 6e4), i()
            }, _$$.$(o).setAttribute("target", $), _$$.$(o).setAttribute("action", r), _$$.$(o).submit(), s(), a
        }, $i: function (e) {
            e = e || {};
            var t = {
                create: "script", attribute: {type: "text/javascript"}, insert: _$$.$().body, onready: function () {
                }
            };
            for (var n in t) void 0 === e[n] && (e[n] = t[n]);
            var o = e.create, r = e.attribute, i = e.insert, s = e.onready, $ = _$$.$().createElement(o);
            for (var a in r) $.setAttribute(a, r[a]);
            if ("script" == o && void 0 !== r.src && ($.readyState ? $.onreadystatechange = function () {
                "loaded" != $.readyState && "complete" != $.readyState || ($.onreadystatechange = null, s())
            } : $.onload = function () {
                s()
            }), i.appendChild($), "script" == o && void 0 === r.src) $.$$ = function () {
                $.text = arguments[0]
            }; else {
                if ("style" != o) return _$$.$($);
                $.$$ = function () {
                    $.styleSheet ? $.styleSheet.cssText = _$$.$().createTextNode(arguments[0]).nodeValue : $.appendChild(_$$.$().createTextNode(arguments[0]))
                }
            }
            return $
        }, $r: {
            rl: [], or: function (e) {
                _$$.$r.rl.length || _$$.$r.br(function () {
                    for (var e = 0; e < _$$.$r.rl.length; e++) _$$.$r.rl[e]()
                }), _$$.$r.rl.push(e)
            }, br: function (e) {
                var t = !1, n = function () {
                    t || (t = !0, e())
                };
                if (document.addEventListener) document.addEventListener("DOMContentLoaded", function () {
                    document.removeEventListener("DOMContentLoaded", arguments.callee, !1), n()
                }, !1); else if (document.attachEvent) {
                    if (document.documentElement.doScroll && window == window.top) {
                        var o = function () {
                            if (!t) try {
                                document.documentElement.doScroll("left"), n()
                            } catch (e) {
                                setTimeout(o, 10)
                            }
                        };
                        o()
                    }
                    document.attachEvent("onreadystatechange", function () {
                        "complete" === document.readyState && (document.detachEvent("onreadystatechange", arguments.callee), n())
                    })
                }
                window.addEventListener ? window.addEventListener("load", n, !1) : window.attachEvent ? window.attachEvent("onload", n) : window.onload = n
            }
        }, $s: {
            screensize: function () {
                return {w: screen.width, h: screen.height}
            }, windowpos: function () {
                return window.screenLeft || window.screenTop ? {
                    l: window.screenLeft,
                    t: window.screenTop
                } : window.screenX || window.screenY ? {l: window.screenX, t: window.screenY} : {l: 0, t: 0}
            }, clientsize: function () {
                return window.innerWidth || window.innerHeight ? {
                    w: window.innerWidth,
                    h: window.innerHeight
                } : _$$.$().documentElement && (_$$.$().documentElement.clientWidth || _$$.$().documentElement.clientHeight) ? {
                    w: _$$.$().documentElement.clientWidth,
                    h: _$$.$().documentElement.clientHeight
                } : _$$.$().body.clientWidth || _$$.$().body.clientHeight ? {
                    w: _$$.$().body.clientWidth,
                    h: _$$.$().body.clientHeight
                } : {w: 0, h: 0}
            }, scrollpos: function () {
                return window.innerWidth || window.innerHeight ? {
                    l: window.pageXOffset,
                    t: window.pageYOffset
                } : _$$.$().documentElement && (_$$.$().documentElement.clientWidth || _$$.$().documentElement.clientHeight) ? {
                    l: _$$.$().documentElement.scrollLeft,
                    t: _$$.$().documentElement.scrollTop
                } : _$$.$().body.clientWidth || _$$.$().body.clientHeight ? {
                    l: _$$.$().body.scrollLeft,
                    t: _$$.$().body.scrollTop
                } : {l: 0, t: 0}
            }, scrollsize: function () {
                return _$$.$().documentElement && (_$$.$().documentElement.scrollWidth || _$$.$().documentElement.scrollHeight) ? {
                    w: _$$.$().documentElement.scrollWidth,
                    h: _$$.$().documentElement.scrollHeight
                } : _$$.$().body.scrollWidth || _$$.$().body.scrollHeight ? {
                    w: _$$.$().body.scrollWidth,
                    h: _$$.$().body.scrollHeight
                } : {w: 0, h: 0}
            }, mousepos: function (e) {
                var t = 0, n = 0;
                return e || (e = window.event), e.pageX || e.pageY ? (t = e.pageX, n = e.pageY) : (e.clientX || e.clientY) && (t = e.clientX + this.scrollpos().l - _$$.$().documentElement.clientLeft, n = e.clientY + this.scrollpos().t - _$$.$().documentElement.clientTop), {
                    x: t,
                    y: n
                }
            }, mouseelpos: function (e) {
                var t = 0, n = 0;
                return e || (e = window.event), e.layerX || e.layerY ? (t = e.layerX, n = e.layerY) : (e.offsetX || e.offsetY) && (t = e.offsetX, n = e.offsetY), {
                    x: t,
                    y: n
                }
            }, elementpos: function (e) {
                for (var t = 0, n = 0; e;) t += e.offsetLeft, n += e.offsetTop, e = e.offsetParent;
                return {l: t, t: n}
            }, getelbyclass: function (e, t, n) {
                t = t || "*";
                var o, r = [];
                o = (n = n || _$$.$()).getElementsByTagName(t);
                for (var i = 0; i < o.length; i++) o[i].className == e && r.push(o[i]);
                return r
            }, getelbytag: function (e, t) {
                e = e || "*";
                var n, o = [];
                n = (t = t || _$$.$()).getElementsByTagName(e);
                for (var r = 0; r < n.length; r++) o.push(n[r]);
                return o
            }, getelbyname: function (e, t, n) {
                t = t || "*";
                var o = [], r = [];
                o = (n = n || _$$.$()).getElementsByTagName(t);
                for (var i = 0; i < o.length; i++) o[i].getAttribute("name") == e && r.push(o[i]);
                return r
            }, geteventtype: function (e) {
                return e || (e = window.event), e.type
            }, mousebutton: function (e) {
                return null == e.which ? e.button < 2 ? "left" : 4 == e.button ? "middle" : "right" : e.which < 2 ? "left" : 2 == e.which ? "middle" : "right"
            }, randnum: function (e, t) {
                return Math.floor(Math.random() * (t - e + 1)) + e
            }, browsername: function () {
                var e, t = navigator.userAgent;
                return -1 != t.indexOf("MSIE") ? e = "MSIE" : -1 != t.indexOf("Safari") ? e = -1 != t.indexOf("Chrome") ? "Chrome" : "Safari" : -1 != t.indexOf("Gecko") ? e = -1 != t.indexOf("Chrome") ? "Chrome" : "Mozilla" : -1 != t.indexOf("Mozilla") ? e = "Old Netscape or Mozilla" : -1 != t.indexOf("Opera") && (e = "Opera"), e
            }
        }
    }, $ver = _$$.version, $$ = _$$.$, $$a = _$$.$a, $$c = _$$.$c, $$e = _$$.$e, $$f = _$$.$f, $$i = _$$.$i,
    $$r = _$$.$r.or, $$s = _$$.$s;

/* ezMark 1.0 */
!function (a) {
    a.fn.ezMark = function (e) {
        var c = {
            checkboxCls: (e = e || {}).checkboxCls || "ez-checkbox",
            radioCls: e.radioCls || "ez-radio",
            checkedCls: e.checkedCls || "ez-checked",
            selectedCls: e.selectedCls || "ez-selected",
            hideCls: "ez-hide"
        };
        return this.each(function () {
            var e = a(this),
                s = "checkbox" == e.attr("type") ? '<div class="' + c.checkboxCls + '">' : '<div class="' + c.radioCls + '">';
            "checkbox" == e.attr("type") ? (e.addClass(c.hideCls).wrap(s).change(function () {
                a(this).is(":checked") ? a(this).parent().addClass(c.checkedCls) : a(this).parent().removeClass(c.checkedCls)
            }), e.is(":checked") && e.parent().addClass(c.checkedCls)) : "radio" == e.attr("type") && (e.addClass(c.hideCls).wrap(s).change(function () {
                a('input[name="' + a(this).attr("name") + '"]').each(function () {
                    a(this).is(":checked") ? a(this).parent().addClass(c.selectedCls) : a(this).parent().removeClass(c.selectedCls)
                })
            }), e.is(":checked") && e.parent().addClass(c.selectedCls))
        })
    }
}(jQuery);

/* https://github.com/marcj/jquery-selectBox 1.2.0 */
!function (w) {
    var o = this.SelectBox = function (e, t) {
        if (e instanceof jQuery) {
            if (!(0 < e.length)) return;
            e = e[0]
        }
        return this.typeTimer = null, this.typeSearch = "", this.isMac = navigator.platform.match(/mac/i), t = "object" == typeof t ? t : {}, this.selectElement = e, !(!t.mobile && navigator.userAgent.match(/iPad|iPhone|Android|IEMobile|BlackBerry/i)) && ("select" === e.tagName.toLowerCase() && void this.init(t))
    };
    o.prototype.version = "1.2.0", o.prototype.init = function (t) {
        var e = w(this.selectElement);
        if (e.data("selectBox-control")) return !1;
        var s = w('<a class="selectBox" />'), o = e.attr("multiple") || 1 < parseInt(e.attr("size")), a = t || {},
            n = parseInt(e.prop("tabindex")) || 0, l = this;
        if (s.width(e.outerWidth()).addClass(e.attr("class")).attr("title", e.attr("title") || "").attr("tabindex", n).css("display", "inline-block").bind("focus.selectBox", function () {
            this !== document.activeElement && document.body !== document.activeElement && w(document.activeElement).blur(), s.hasClass("selectBox-active") || (s.addClass("selectBox-active"), e.trigger("focus"))
        }).bind("blur.selectBox", function () {
            s.hasClass("selectBox-active") && (s.removeClass("selectBox-active"), e.trigger("blur"))
        }), w(window).data("selectBox-bindings") || w(window).data("selectBox-bindings", !0).bind("scroll.selectBox", a.hideOnWindowScroll ? this.hideMenus : w.noop).bind("resize.selectBox", this.hideMenus), e.attr("disabled") && s.addClass("selectBox-disabled"), e.bind("click.selectBox", function (e) {
            s.focus(), e.preventDefault()
        }), o) {
            if (t = this.getOptions("inline"), s.append(t).data("selectBox-options", t).addClass("selectBox-inline selectBox-menuShowing").bind("keydown.selectBox", function (e) {
                l.handleKeyDown(e)
            }).bind("keypress.selectBox", function (e) {
                l.handleKeyPress(e)
            }).bind("mousedown.selectBox", function (e) {
                1 === e.which && (w(e.target).is("A.selectBox-inline") && e.preventDefault(), s.hasClass("selectBox-focus") || s.focus())
            }).insertAfter(e), !e[0].style.height) {
                var i = e.attr("size") ? parseInt(e.attr("size")) : 5,
                    c = s.clone().removeAttr("id").css({position: "absolute", top: "-9999em"}).show().appendTo("body");
                c.find(".selectBox-options").html("<li><a> </a></li>");
                var r = parseInt(c.find(".selectBox-options A:first").html("&nbsp;").outerHeight());
                c.remove(), s.height(r * i)
            }
            this.disableSelection(s)
        } else {
            var d = w('<span class="selectBox-label" />'), h = w('<span class="selectBox-arrow" />');
            d.attr("class", this.getLabelClass()).text(this.getLabelText()), (t = this.getOptions("dropdown")).appendTo("BODY"), s.data("selectBox-options", t).addClass("selectBox-dropdown").append(d).append(h).bind("mousedown.selectBox", function (e) {
                1 === e.which && (s.hasClass("selectBox-menuShowing") ? l.hideMenus() : (e.stopPropagation(), t.data("selectBox-down-at-x", e.screenX).data("selectBox-down-at-y", e.screenY), l.showMenu()))
            }).bind("keydown.selectBox", function (e) {
                l.handleKeyDown(e)
            }).bind("keypress.selectBox", function (e) {
                l.handleKeyPress(e)
            }).bind("open.selectBox", function (e, t) {
                t && !0 === t._selectBox || l.showMenu()
            }).bind("close.selectBox", function (e, t) {
                t && !0 === t._selectBox || l.hideMenus()
            }).insertAfter(e);
            var p = s.width() - h.outerWidth() - (parseInt(d.css("paddingLeft")) || 0) - (parseInt(d.css("paddingRight")) || 0);
            d.width(p), this.disableSelection(s)
        }
        e.addClass("selectBox").data("selectBox-control", s).data("selectBox-settings", a).hide()
    }, o.prototype.getOptions = function (e) {
        var t, s = w(this.selectElement), o = this, a = function (e, t) {
            return e.children("OPTION, OPTGROUP").each(function () {
                if (w(this).is("OPTION")) 0 < w(this).length ? o.generateOptions(w(this), t) : t.append("<li> </li>"); else {
                    var e = w('<li class="selectBox-optgroup" />');
                    e.text(w(this).attr("label")), t.append(e), t = a(w(this), t)
                }
            }), t
        };
        switch (e) {
            case"inline":
                return t = w('<ul class="selectBox-options" />'), (t = a(s, t)).find("A").bind("mouseover.selectBox", function (e) {
                    o.addHover(w(this).parent())
                }).bind("mouseout.selectBox", function (e) {
                    o.removeHover(w(this).parent())
                }).bind("mousedown.selectBox", function (e) {
                    1 === e.which && (e.preventDefault(), s.selectBox("control").hasClass("selectBox-active") || s.selectBox("control").focus())
                }).bind("mouseup.selectBox", function (e) {
                    1 === e.which && (o.hideMenus(), o.selectOption(w(this).parent(), e))
                }), this.disableSelection(t), t;
            case"dropdown":
                t = w('<ul class="selectBox-dropdown-menu selectBox-options" />'), (t = a(s, t)).data("selectBox-select", s).css("display", "none").appendTo("BODY").find("A").bind("mousedown.selectBox", function (e) {
                    1 === e.which && (e.preventDefault(), e.screenX === t.data("selectBox-down-at-x") && e.screenY === t.data("selectBox-down-at-y") && (t.removeData("selectBox-down-at-x").removeData("selectBox-down-at-y"), /android/i.test(navigator.userAgent.toLowerCase()) && /chrome/i.test(navigator.userAgent.toLowerCase()) && o.selectOption(w(this).parent()), o.hideMenus()))
                }).bind("mouseup.selectBox", function (e) {
                    1 === e.which && (e.screenX === t.data("selectBox-down-at-x") && e.screenY === t.data("selectBox-down-at-y") || (t.removeData("selectBox-down-at-x").removeData("selectBox-down-at-y"), o.selectOption(w(this).parent()), o.hideMenus()))
                }).bind("mouseover.selectBox", function (e) {
                    o.addHover(w(this).parent())
                }).bind("mouseout.selectBox", function (e) {
                    o.removeHover(w(this).parent())
                });
                var n = s.attr("class") || "";
                if ("" !== n) {
                    n = n.split(" ");
                    for (var l = 0; l < n.length; l++) t.addClass(n[l] + "-selectBox-dropdown-menu")
                }
                return this.disableSelection(t), t
        }
    }, o.prototype.getLabelClass = function () {
        return ("selectBox-label " + (w(this.selectElement).find("OPTION:selected").attr("class") || "")).replace(/\s+$/, "")
    }, o.prototype.getLabelText = function () {
        return w(this.selectElement).find("OPTION:selected").text() || " "
    }, o.prototype.setLabel = function () {
        var e = w(this.selectElement).data("selectBox-control");
        e && e.find(".selectBox-label").attr("class", this.getLabelClass()).text(this.getLabelText())
    }, o.prototype.destroy = function () {
        var e = w(this.selectElement), t = e.data("selectBox-control");
        t && (t.data("selectBox-options").remove(), t.remove(), e.removeClass("selectBox").removeData("selectBox-control").data("selectBox-control", null).removeData("selectBox-settings").data("selectBox-settings", null).show())
    }, o.prototype.refresh = function () {
        var e, t = w(this.selectElement).data("selectBox-control"),
            s = t.hasClass("selectBox-dropdown") ? "dropdown" : "inline";
        switch (t.data("selectBox-options").remove(), e = this.getOptions(s), t.data("selectBox-options", e), s) {
            case"inline":
                t.append(e);
                break;
            case"dropdown":
                this.setLabel(), w("BODY").append(e)
        }
        "dropdown" === s && t.hasClass("selectBox-menuShowing") && this.showMenu()
    }, o.prototype.showMenu = function () {
        var t = this, e = w(this.selectElement), s = e.data("selectBox-control"), o = e.data("selectBox-settings"),
            a = s.data("selectBox-options");
        if (s.hasClass("selectBox-disabled")) return !1;
        this.hideMenus();
        var n = parseInt(s.css("borderBottomWidth")) || 0, l = parseInt(s.css("borderTopWidth")) || 0, i = s.offset(),
            c = o.topPositionCorrelation ? o.topPositionCorrelation : 0,
            r = o.bottomPositionCorrelation ? o.bottomPositionCorrelation : 0, d = a.outerHeight(), h = s.outerHeight(),
            p = parseInt(a.css("max-height")), x = w(window).scrollTop(), u = i.top - x,
            B = w(window).height() - (u + h), f = B < u && (null == o.keepInViewport || o.keepInViewport),
            v = f ? i.top - d + l + c : i.top + h - n - r;
        if (u < p && B < p) if (f) {
            var m = p - (u - 5);
            a.css({"max-height": p - m + "px"}), v += m
        } else {
            m = p - (B - 5);
            a.css({"max-height": p - m + "px"})
        }
        if (a.data("posTop", f), a.width(s.innerWidth()).css({
            top: v,
            left: s.offset().left
        }).addClass("selectBox-options selectBox-options-" + (f ? "top" : "bottom")), e.triggerHandler("beforeopen")) return !1;
        var b = function () {
            e.triggerHandler("open", {_selectBox: !0})
        };
        switch (o.menuTransition) {
            case"fade":
                a.fadeIn(o.menuSpeed, b);
                break;
            case"slide":
                a.slideDown(o.menuSpeed, b);
                break;
            default:
                a.show(o.menuSpeed, b)
        }
        o.menuSpeed || b();
        var g = a.find(".selectBox-selected:first");
        this.keepOptionInView(g, !0), this.addHover(g), s.addClass("selectBox-menuShowing selectBox-menuShowing-" + (f ? "top" : "bottom")), w(document).bind("mousedown.selectBox", function (e) {
            if (1 === e.which) {
                if (w(e.target).parents().andSelf().hasClass("selectBox-options")) return;
                t.hideMenus()
            }
        })
    }, o.prototype.hideMenus = function () {
        0 !== w(".selectBox-dropdown-menu:visible").length && (w(document).unbind("mousedown.selectBox"), w(".selectBox-dropdown-menu").each(function () {
            var e = w(this), t = e.data("selectBox-select"), s = t.data("selectBox-control"),
                o = t.data("selectBox-settings"), a = e.data("posTop");
            if (t.triggerHandler("beforeclose")) return !1;
            var n = function () {
                t.triggerHandler("close", {_selectBox: !0})
            };
            if (o) {
                switch (o.menuTransition) {
                    case"fade":
                        e.fadeOut(o.menuSpeed, n);
                        break;
                    case"slide":
                        e.slideUp(o.menuSpeed, n);
                        break;
                    default:
                        e.hide(o.menuSpeed, n)
                }
                o.menuSpeed || n(), s.removeClass("selectBox-menuShowing selectBox-menuShowing-" + (a ? "top" : "bottom"))
            } else w(this).hide(), w(this).triggerHandler("close", {_selectBox: !0}), w(this).removeClass("selectBox-menuShowing selectBox-menuShowing-" + (a ? "top" : "bottom"));
            e.css("max-height", ""), e.removeClass("selectBox-options-" + (a ? "top" : "bottom")), e.data("posTop", !1)
        }))
    }, o.prototype.selectOption = function (e, t) {
        var s = w(this.selectElement);
        e = w(e);
        var o, a = s.data("selectBox-control");
        s.data("selectBox-settings");
        if (a.hasClass("selectBox-disabled")) return !1;
        if (0 === e.length || e.hasClass("selectBox-disabled")) return !1;
        s.attr("multiple") ? t.shiftKey && a.data("selectBox-last-selected") ? (e.toggleClass("selectBox-selected"), o = (o = e.index() > a.data("selectBox-last-selected").index() ? e.siblings().slice(a.data("selectBox-last-selected").index(), e.index()) : e.siblings().slice(e.index(), a.data("selectBox-last-selected").index())).not(".selectBox-optgroup, .selectBox-disabled"), e.hasClass("selectBox-selected") ? o.addClass("selectBox-selected") : o.removeClass("selectBox-selected")) : this.isMac && t.metaKey || !this.isMac && t.ctrlKey ? e.toggleClass("selectBox-selected") : (e.siblings().removeClass("selectBox-selected"), e.addClass("selectBox-selected")) : (e.siblings().removeClass("selectBox-selected"), e.addClass("selectBox-selected"));
        a.hasClass("selectBox-dropdown") && a.find(".selectBox-label").text(e.text());
        var n = 0, l = [];
        return s.attr("multiple") ? a.find(".selectBox-selected A").each(function () {
            l[n++] = w(this).attr("rel")
        }) : l = e.find("A").attr("rel"), a.data("selectBox-last-selected", e), s.val() !== l && (s.val(l), this.setLabel(), s.trigger("change")), !0
    }, o.prototype.addHover = function (e) {
        e = w(e), w(this.selectElement).data("selectBox-control").data("selectBox-options").find(".selectBox-hover").removeClass("selectBox-hover"), e.addClass("selectBox-hover")
    }, o.prototype.getSelectElement = function () {
        return this.selectElement
    }, o.prototype.removeHover = function (e) {
        e = w(e), w(this.selectElement).data("selectBox-control").data("selectBox-options").find(".selectBox-hover").removeClass("selectBox-hover")
    }, o.prototype.keepOptionInView = function (e, t) {
        if (e && 0 !== e.length) {
            var s = w(this.selectElement).data("selectBox-control"), o = s.data("selectBox-options"),
                a = s.hasClass("selectBox-dropdown") ? o : o.parent(), n = parseInt(e.offset().top - a.position().top),
                l = parseInt(n + e.outerHeight());
            t ? a.scrollTop(e.offset().top - a.offset().top + a.scrollTop() - a.height() / 2) : (n < 0 && a.scrollTop(e.offset().top - a.offset().top + a.scrollTop()), l > a.height() && a.scrollTop(e.offset().top + e.outerHeight() - a.offset().top + a.scrollTop() - a.height()))
        }
    }, o.prototype.handleKeyDown = function (e) {
        var t = w(this.selectElement), s = t.data("selectBox-control"), o = s.data("selectBox-options"),
            a = t.data("selectBox-settings"), n = 0, l = 0;
        if (!s.hasClass("selectBox-disabled")) switch (e.keyCode) {
            case 8:
                e.preventDefault(), this.typeSearch = "";
                break;
            case 9:
            case 27:
                this.hideMenus(), this.removeHover();
                break;
            case 13:
                s.hasClass("selectBox-menuShowing") ? (this.selectOption(o.find("LI.selectBox-hover:first"), e), s.hasClass("selectBox-dropdown") && this.hideMenus()) : this.showMenu();
                break;
            case 38:
            case 37:
                if (e.preventDefault(), s.hasClass("selectBox-menuShowing")) {
                    var i = o.find(".selectBox-hover").prev("LI");
                    for (n = o.find("LI:not(.selectBox-optgroup)").length, l = 0; (0 === i.length || i.hasClass("selectBox-disabled") || i.hasClass("selectBox-optgroup")) && (0 === (i = i.prev("LI")).length && (i = a.loopOptions ? o.find("LI:last") : o.find("LI:first")), !(++l >= n));) ;
                    this.addHover(i), this.selectOption(i, e), this.keepOptionInView(i)
                } else this.showMenu();
                break;
            case 40:
            case 39:
                if (e.preventDefault(), s.hasClass("selectBox-menuShowing")) {
                    var c = o.find(".selectBox-hover").next("LI");
                    for (n = o.find("LI:not(.selectBox-optgroup)").length, l = 0; (0 === c.length || c.hasClass("selectBox-disabled") || c.hasClass("selectBox-optgroup")) && (0 === (c = c.next("LI")).length && (c = a.loopOptions ? o.find("LI:first") : o.find("LI:last")), !(++l >= n));) ;
                    this.addHover(c), this.selectOption(c, e), this.keepOptionInView(c)
                } else this.showMenu()
        }
    }, o.prototype.handleKeyPress = function (e) {
        var t = w(this.selectElement).data("selectBox-control"), s = t.data("selectBox-options"), o = this;
        if (!t.hasClass("selectBox-disabled")) switch (e.keyCode) {
            case 9:
            case 27:
            case 13:
            case 38:
            case 37:
            case 40:
            case 39:
                break;
            default:
                t.hasClass("selectBox-menuShowing") || this.showMenu(), e.preventDefault(), clearTimeout(this.typeTimer), this.typeSearch += String.fromCharCode(e.charCode || e.keyCode), s.find("A").each(function () {
                    if (w(this).text().substr(0, o.typeSearch.length).toLowerCase() === o.typeSearch.toLowerCase()) return o.addHover(w(this).parent()), o.selectOption(w(this).parent(), e), o.keepOptionInView(w(this).parent()), !1
                }), this.typeTimer = setTimeout(function () {
                    o.typeSearch = ""
                }, 1e3)
        }
    }, o.prototype.enable = function () {
        var e = w(this.selectElement);
        e.prop("disabled", !1);
        var t = e.data("selectBox-control");
        t && t.removeClass("selectBox-disabled")
    }, o.prototype.disable = function () {
        var e = w(this.selectElement);
        e.prop("disabled", !0);
        var t = e.data("selectBox-control");
        t && t.addClass("selectBox-disabled")
    }, o.prototype.setValue = function (t) {
        var e = w(this.selectElement);
        e.val(t), null === (t = e.val()) && (t = e.children().first().val(), e.val(t));
        var s = e.data("selectBox-control");
        if (s) {
            var o = e.data("selectBox-settings"), a = s.data("selectBox-options");
            this.setLabel(), a.find(".selectBox-selected").removeClass("selectBox-selected"), a.find("A").each(function () {
                if ("object" == typeof t) for (var e = 0; e < t.length; e++) w(this).attr("rel") == t[e] && w(this).parent().addClass("selectBox-selected"); else w(this).attr("rel") == t && w(this).parent().addClass("selectBox-selected")
            }), o.change && o.change.call(e)
        }
    }, o.prototype.setOptions = function (e) {
        var t = w(this.selectElement), s = t.data("selectBox-control");
        switch (typeof e) {
            case"string":
                t.html(e);
                break;
            case"object":
                for (var o in t.html(""), e) if (null !== e[o]) if ("object" == typeof e[o]) {
                    var a = w('<optgroup label="' + o + '" />');
                    for (var n in e[o]) a.append('<option value="' + n + '">' + e[o][n] + "</option>");
                    t.append(a)
                } else {
                    var l = w('<option value="' + o + '">' + e[o] + "</option>");
                    t.append(l)
                }
        }
        s && this.refresh()
    }, o.prototype.disableSelection = function (e) {
        w(e).css("MozUserSelect", "none").bind("selectstart", function (e) {
            e.preventDefault()
        })
    }, o.prototype.generateOptions = function (e, t) {
        var s = w("<li />"), o = w("<a />");
        s.addClass(e.attr("class")), s.data(e.data()), o.attr("rel", e.val()).text(e.text()), s.append(o), e.attr("disabled") && s.addClass("selectBox-disabled"), e.attr("selected") && s.addClass("selectBox-selected"), t.append(s)
    }, w.extend(w.fn, {
        selectBox: function (s, e) {
            var t;
            switch (s) {
                case"control":
                    return w(this).data("selectBox-control");
                case"settings":
                    if (!e) return w(this).data("selectBox-settings");
                    w(this).each(function () {
                        w(this).data("selectBox-settings", w.extend(!0, w(this).data("selectBox-settings"), e))
                    });
                    break;
                case"options":
                    if (void 0 === e) return w(this).data("selectBox-control").data("selectBox-options");
                    w(this).each(function () {
                        (t = w(this).data("selectBox")) && t.setOptions(e)
                    });
                    break;
                case"value":
                    if (void 0 === e) return w(this).val();
                    w(this).each(function () {
                        (t = w(this).data("selectBox")) && t.setValue(e)
                    });
                    break;
                case"refresh":
                    w(this).each(function () {
                        (t = w(this).data("selectBox")) && t.refresh()
                    });
                    break;
                case"enable":
                    w(this).each(function () {
                        (t = w(this).data("selectBox")) && t.enable(this)
                    });
                    break;
                case"disable":
                    w(this).each(function () {
                        (t = w(this).data("selectBox")) && t.disable()
                    });
                    break;
                case"destroy":
                    w(this).each(function () {
                        (t = w(this).data("selectBox")) && (t.destroy(), w(this).data("selectBox", null))
                    });
                    break;
                case"instance":
                    return w(this).data("selectBox");
                default:
                    w(this).each(function (e, t) {
                        w(t).data("selectBox") || w(t).data("selectBox", new o(t, s))
                    })
            }
            return w(this)
        }
    })
}(jQuery);

/* http://digitalbush.com/projects/masked-input-plugin/ 1.3.1 */
!function (k) {
    var a, e, t,
        r = (e = document.createElement("input"), t = "onpaste", e.setAttribute(t, ""), ("function" == typeof e[t] ? "paste" : "input") + ".mask"),
        n = navigator.userAgent, b = /iphone/i.test(n), y = /android/i.test(n);
    k.mask = {
        definitions: {9: "[0-9]", a: "[A-Za-z]", "*": "[A-Za-z0-9]"},
        dataName: "rawMaskFn",
        placeholder: "_"
    }, k.fn.extend({
        caret: function (e, t) {
            var n;
            if (0 !== this.length && !this.is(":hidden")) return "number" == typeof e ? (t = "number" == typeof t ? t : e, this.each(function () {
                this.setSelectionRange ? this.setSelectionRange(e, t) : this.createTextRange && ((n = this.createTextRange()).collapse(!0), n.moveEnd("character", t), n.moveStart("character", e), n.select())
            })) : (this[0].setSelectionRange ? (e = this[0].selectionStart, t = this[0].selectionEnd) : document.selection && document.selection.createRange && (n = document.selection.createRange(), e = 0 - n.duplicate().moveStart("character", -1e5), t = e + n.text.length), {
                begin: e,
                end: t
            })
        }, unmask: function () {
            return this.trigger("unmask")
        }, mask: function (t, d) {
            var n, m, p, g, v;
            return !t && 0 < this.length ? k(this[0]).data(k.mask.dataName)() : (d = k.extend({
                placeholder: k.mask.placeholder,
                completed: null
            }, d), n = k.mask.definitions, m = [], p = v = t.length, g = null, k.each(t.split(""), function (e, t) {
                "?" == t ? (v--, p = e) : n[t] ? (m.push(new RegExp(n[t])), null === g && (g = m.length - 1)) : m.push(null)
            }), this.trigger("unmask").each(function () {
                var o = k(this), c = k.map(t.split(""), function (e, t) {
                    if ("?" != e) return n[e] ? d.placeholder : e
                }), i = o.val();

                function l(e) {
                    for (; ++e < v && !m[e];) ;
                    return e
                }

                function s(e, t) {
                    var n, a;
                    if (!(e < 0)) {
                        for (n = e, a = l(t); n < v; n++) if (m[n]) {
                            if (!(a < v && m[n].test(c[a]))) break;
                            c[n] = c[a], c[a] = d.placeholder, a = l(a)
                        }
                        f(), o.caret(Math.max(g, e))
                    }
                }

                function u(e, t) {
                    var n;
                    for (n = e; n < t && n < v; n++) m[n] && (c[n] = d.placeholder)
                }

                function f() {
                    o.val(c.join(""))
                }

                function h(e) {
                    var t, n, a = o.val(), r = -1;
                    for (t = 0, pos = 0; t < v; t++) if (m[t]) {
                        for (c[t] = d.placeholder; pos++ < a.length;) if (n = a.charAt(pos - 1), m[t].test(n)) {
                            c[t] = n, r = t;
                            break
                        }
                        if (pos > a.length) break
                    } else c[t] === a.charAt(pos) && t !== p && (pos++, r = t);
                    return e ? f() : r + 1 < p ? (o.val(""), u(0, v)) : (f(), o.val(o.val().substring(0, r + 1))), p ? t : g
                }

                o.data(k.mask.dataName, function () {
                    return k.map(c, function (e, t) {
                        return m[t] && e != d.placeholder ? e : null
                    }).join("")
                }), o.attr("readonly") || o.one("unmask", function () {
                    o.unbind(".mask").removeData(k.mask.dataName)
                }).bind("focus.mask", function () {
                    var e;
                    clearTimeout(a), i = o.val(), e = h(), a = setTimeout(function () {
                        f(), e == t.length ? o.caret(0, e) : o.caret(e)
                    }, 10)
                }).bind("blur.mask", function () {
                    h(), o.val() != i && o.change()
                }).bind("keydown.mask", function (e) {
                    var t, n, a, r = e.which;
                    8 === r || 46 === r || b && 127 === r ? (n = (t = o.caret()).begin, (a = t.end) - n == 0 && (n = 46 !== r ? function (e) {
                        for (; 0 <= --e && !m[e];) ;
                        return e
                    }(n) : a = l(n - 1), a = 46 === r ? l(a) : a), u(n, a), s(n, a - 1), e.preventDefault()) : 27 == r && (o.val(i), o.caret(0, h()), e.preventDefault())
                }).bind("keypress.mask", function (e) {
                    var t, n, a, r = e.which, i = o.caret();
                    e.ctrlKey || e.altKey || e.metaKey || r < 32 || r && (i.end - i.begin != 0 && (u(i.begin, i.end), s(i.begin, i.end - 1)), (t = l(i.begin - 1)) < v && (n = String.fromCharCode(r), m[t].test(n) && (function (e) {
                        var t, n, a, r;
                        for (t = e, n = d.placeholder; t < v; t++) if (m[t]) {
                            if (a = l(t), r = c[t], c[t] = n, !(a < v && m[a].test(r))) break;
                            n = r
                        }
                    }(t), c[t] = n, f(), a = l(t), y ? setTimeout(k.proxy(k.fn.caret, o, a), 0) : o.caret(a), d.completed && v <= a && d.completed.call(o))), e.preventDefault())
                }).bind(r, function () {
                    setTimeout(function () {
                        var e = h(!0);
                        o.caret(e), d.completed && e == o.val().length && d.completed.call(o)
                    }, 0)
                }), h()
            }))
        }
    })
}(jQuery);

/* customScrollbar */
!function (h) {
    h.fn.customScrollbar = function (i, t) {
        var o = {
            skin: void 0,
            hScroll: !0,
            vScroll: !0,
            updateOnWindowResize: !1,
            animationSpeed: 300,
            onCustomScroll: void 0,
            swipeSpeed: 1,
            wheelSpeed: 40,
            fixedThumbWidth: void 0,
            fixedThumbHeight: void 0
        }, s = function (e, i) {
            this.$element = h(e), this.options = i, this.addScrollableClass(), this.addSkinClass(), this.addScrollBarComponents(), this.options.vScroll && (this.vScrollbar = new n(this, new r)), this.options.hScroll && (this.hScrollbar = new n(this, new l)), this.$element.data("scrollable", this), this.initKeyboardScrolling(), this.bindEvents()
        };
        s.prototype = {
            addScrollableClass: function () {
                this.$element.hasClass("scrollable") || (this.scrollableAdded = !0, this.$element.addClass("scrollable"))
            }, removeScrollableClass: function () {
                this.scrollableAdded && this.$element.removeClass("scrollable")
            }, addSkinClass: function () {
                "string" != typeof this.options.skin || this.$element.hasClass(this.options.skin) || (this.skinClassAdded = !0, this.$element.addClass(this.options.skin))
            }, removeSkinClass: function () {
                this.skinClassAdded && this.$element.removeClass(this.options.skin)
            }, addScrollBarComponents: function () {
                this.assignViewPort(), 0 == this.$viewPort.length && (this.$element.wrapInner('<div class="viewport" />'), this.assignViewPort(), this.viewPortAdded = !0), this.assignOverview(), 0 == this.$overview.length && (this.$viewPort.wrapInner('<div class="overview" />'), this.assignOverview(), this.overviewAdded = !0), this.addScrollBar("vertical", "prepend"), this.addScrollBar("horizontal", "append")
            }, removeScrollbarComponents: function () {
                this.removeScrollbar("vertical"), this.removeScrollbar("horizontal"), this.overviewAdded && this.$element.unwrap(), this.viewPortAdded && this.$element.unwrap()
            }, removeScrollbar: function (e) {
                this[e + "ScrollbarAdded"] && this.$element.find(".scroll-bar." + e).remove()
            }, assignViewPort: function () {
                this.$viewPort = this.$element.find(".viewport")
            }, assignOverview: function () {
                this.$overview = this.$viewPort.find(".overview")
            }, addScrollBar: function (e, i) {
                0 == this.$element.find(".scroll-bar." + e).length && (this.$element[i]("<div class='scroll-bar " + e + "'><div class='thumb'></div></div>"), this[e + "ScrollbarAdded"] = !0)
            }, resize: function (e) {
                this.vScrollbar && this.vScrollbar.resize(e), this.hScrollbar && this.hScrollbar.resize(e)
            }, scrollTo: function (e) {
                this.vScrollbar && this.vScrollbar.scrollToElement(e), this.hScrollbar && this.hScrollbar.scrollToElement(e)
            }, scrollToXY: function (e, i) {
                this.scrollToX(e), this.scrollToY(i)
            }, scrollToX: function (e) {
                this.hScrollbar && this.hScrollbar.scrollOverviewTo(e, !0)
            }, scrollToY: function (e) {
                this.vScrollbar && this.vScrollbar.scrollOverviewTo(e, !0)
            }, remove: function () {
                this.removeScrollableClass(), this.removeSkinClass(), this.removeScrollbarComponents(), this.$element.data("scrollable", null), this.removeKeyboardScrolling(), this.vScrollbar && this.vScrollbar.remove(), this.hScrollbar && this.hScrollbar.remove()
            }, setAnimationSpeed: function (e) {
                this.options.animationSpeed = e
            }, isInside: function (e, i) {
                var t = h(e), o = h(i), s = t.offset(), n = o.offset();
                return s.top >= n.top && s.left >= n.left && s.top + t.height() <= n.top + o.height() && s.left + t.width() <= n.left + o.width()
            }, initKeyboardScrolling: function () {
                var i = this;
                this.elementKeydown = function (e) {
                    document.activeElement === i.$element[0] && (i.vScrollbar && i.vScrollbar.keyScroll(e), i.hScrollbar && i.hScrollbar.keyScroll(e))
                }, this.$element.attr("tabindex", "-1").keydown(this.elementKeydown)
            }, removeKeyboardScrolling: function () {
                this.$element.removeAttr("tabindex").unbind("keydown", this.elementKeydown)
            }, bindEvents: function () {
                this.options.onCustomScroll && this.$element.on("customScroll", this.options.onCustomScroll)
            }
        };
        var n = function (e, i) {
            this.scrollable = e, this.sizing = i, this.$scrollBar = this.sizing.scrollBar(this.scrollable.$element), this.$thumb = this.$scrollBar.find(".thumb"), this.setScrollPosition(0, 0), this.resize(), this.initMouseMoveScrolling(), this.initMouseWheelScrolling(), this.initTouchScrolling(), this.initMouseClickScrolling(), this.initWindowResize()
        };
        n.prototype = {
            resize: function (e) {
                this.scrollable.$viewPort.height(this.scrollable.$element.height()), this.sizing.size(this.scrollable.$viewPort, this.sizing.size(this.scrollable.$element)), this.viewPortSize = this.sizing.size(this.scrollable.$viewPort), this.overviewSize = this.sizing.size(this.scrollable.$overview), this.ratio = this.viewPortSize / this.overviewSize, this.sizing.size(this.$scrollBar, this.viewPortSize), this.thumbSize = this.calculateThumbSize(), this.sizing.size(this.$thumb, this.thumbSize), this.maxThumbPosition = this.calculateMaxThumbPosition(), this.maxOverviewPosition = this.calculateMaxOverviewPosition(), this.enabled = this.overviewSize > this.viewPortSize, void 0 === this.scrollPercent && (this.scrollPercent = 0), this.enabled ? this.rescroll(e) : this.setScrollPosition(0, 0), this.$scrollBar.toggle(this.enabled)
            }, calculateThumbSize: function () {
                var e, i = this.sizing.fixedThumbSize(this.scrollable.options);
                return e = i || this.ratio * this.viewPortSize, Math.max(e, this.sizing.minSize(this.$thumb))
            }, initMouseMoveScrolling: function () {
                var i = this;
                this.$thumb.mousedown(function (e) {
                    i.enabled && i.startMouseMoveScrolling(e)
                }), this.documentMouseup = function (e) {
                    i.stopMouseMoveScrolling(e)
                }, h(document).mouseup(this.documentMouseup), this.documentMousemove = function (e) {
                    i.mouseMoveScroll(e)
                }, h(document).mousemove(this.documentMousemove), this.$thumb.click(function (e) {
                    e.stopPropagation()
                })
            }, removeMouseMoveScrolling: function () {
                this.$thumb.unbind(), h(document).unbind("mouseup", this.documentMouseup), h(document).unbind("mousemove", this.documentMousemove)
            }, initMouseWheelScrolling: function () {
                var s = this;
                this.scrollable.$element.mousewheel(function (e, i, t, o) {
                    s.enabled && s.mouseWheelScroll(t, o) && (e.stopPropagation(), e.preventDefault())
                })
            }, removeMouseWheelScrolling: function () {
                this.scrollable.$element.unbind("mousewheel")
            }, initTouchScrolling: function () {
                if (document.addEventListener) {
                    var i = this;
                    this.elementTouchstart = function (e) {
                        i.enabled && i.startTouchScrolling(e)
                    }, this.scrollable.$element[0].addEventListener("touchstart", this.elementTouchstart), this.documentTouchmove = function (e) {
                        i.touchScroll(e)
                    }, document.addEventListener("touchmove", this.documentTouchmove), this.elementTouchend = function (e) {
                        i.stopTouchScrolling(e)
                    }, this.scrollable.$element[0].addEventListener("touchend", this.elementTouchend)
                }
            }, removeTouchScrolling: function () {
                document.addEventListener && (this.scrollable.$element[0].removeEventListener("touchstart", this.elementTouchstart), document.removeEventListener("touchmove", this.documentTouchmove), this.scrollable.$element[0].removeEventListener("touchend", this.elementTouchend))
            }, initMouseClickScrolling: function () {
                var i = this;
                this.scrollBarClick = function (e) {
                    i.mouseClickScroll(e)
                }, this.$scrollBar.click(this.scrollBarClick)
            }, removeMouseClickScrolling: function () {
                this.$scrollBar.unbind("click", this.scrollBarClick)
            }, initWindowResize: function () {
                if (this.scrollable.options.updateOnWindowResize) {
                    var e = this;
                    this.windowResize = function () {
                        e.resize()
                    }, h(window).resize(this.windowResize)
                }
            }, removeWindowResize: function () {
                h(window).unbind("resize", this.windowResize)
            }, isKeyScrolling: function (e) {
                return null != this.keyScrollDelta(e)
            }, keyScrollDelta: function (e) {
                for (var i in this.sizing.scrollingKeys) if (i == e) return this.sizing.scrollingKeys[e](this.viewPortSize);
                return null
            }, startMouseMoveScrolling: function (e) {
                this.mouseMoveScrolling = !0, h("html").addClass("not-selectable"), this.setUnselectable(h("html"), "on"), this.setScrollEvent(e)
            }, stopMouseMoveScrolling: function (e) {
                this.mouseMoveScrolling = !1, h("html").removeClass("not-selectable"), this.setUnselectable(h("html"), null)
            }, setUnselectable: function (e, i) {
                e.attr("unselectable") != i && (e.attr("unselectable", i), e.find(":not(input)").attr("unselectable", i))
            }, mouseMoveScroll: function (e) {
                if (this.mouseMoveScrolling) {
                    var i = this.sizing.mouseDelta(this.scrollEvent, e);
                    this.scrollThumbBy(i), this.setScrollEvent(e)
                }
            }, startTouchScrolling: function (e) {
                e.touches && 1 == e.touches.length && (this.setScrollEvent(e.touches[0]), this.touchScrolling = !0, e.stopPropagation())
            }, touchScroll: function (e) {
                if (this.touchScrolling && e.touches && 1 == e.touches.length) {
                    var i = -this.sizing.mouseDelta(this.scrollEvent, e.touches[0]) * this.scrollable.options.swipeSpeed;
                    this.scrollOverviewBy(i) && (e.stopPropagation(), e.preventDefault(), this.setScrollEvent(e.touches[0]))
                }
            }, stopTouchScrolling: function (e) {
                this.touchScrolling = !1, e.stopPropagation()
            }, mouseWheelScroll: function (e, i) {
                var t = -this.sizing.wheelDelta(e, i) * this.scrollable.options.wheelSpeed;
                if (0 != t) return this.scrollOverviewBy(t)
            }, mouseClickScroll: function (e) {
                var i = this.viewPortSize - 20;
                e["page" + this.sizing.scrollAxis()] < this.$thumb.offset()[this.sizing.offsetComponent()] && (i = -i), this.scrollOverviewBy(i)
            }, keyScroll: function (e) {
                var i = e.which;
                this.enabled && this.isKeyScrolling(i) && this.scrollOverviewBy(this.keyScrollDelta(i)) && e.preventDefault()
            }, scrollThumbBy: function (e) {
                var i = this.thumbPosition();
                i += e, i = this.positionOrMax(i, this.maxThumbPosition);
                var t = this.scrollPercent;
                this.scrollPercent = i / this.maxThumbPosition;
                var o = i * this.maxOverviewPosition / this.maxThumbPosition;
                return this.setScrollPosition(o, i), t != this.scrollPercent && (this.triggerCustomScroll(t), !0)
            }, thumbPosition: function () {
                return this.$thumb.position()[this.sizing.offsetComponent()]
            }, scrollOverviewBy: function (e) {
                var i = this.overviewPosition() + e;
                return this.scrollOverviewTo(i, !1)
            }, overviewPosition: function () {
                return -this.scrollable.$overview.position()[this.sizing.offsetComponent()]
            }, scrollOverviewTo: function (e, i) {
                e = this.positionOrMax(e, this.maxOverviewPosition);
                var t = this.scrollPercent;
                this.scrollPercent = e / this.maxOverviewPosition;
                var o = this.scrollPercent * this.maxThumbPosition;
                return i ? this.setScrollPositionWithAnimation(e, o) : this.setScrollPosition(e, o), t != this.scrollPercent && (this.triggerCustomScroll(t), !0)
            }, positionOrMax: function (e, i) {
                return e < 0 ? 0 : i < e ? i : e
            }, triggerCustomScroll: function (e) {
                this.scrollable.$element.trigger("customScroll", {
                    scrollAxis: this.sizing.scrollAxis(),
                    direction: this.sizing.scrollDirection(e, this.scrollPercent),
                    scrollPercent: 100 * this.scrollPercent
                })
            }, rescroll: function (e) {
                if (e) {
                    var i = this.positionOrMax(this.overviewPosition(), this.maxOverviewPosition);
                    this.scrollPercent = i / this.maxOverviewPosition;
                    var t = this.scrollPercent * this.maxThumbPosition;
                    this.setScrollPosition(i, t)
                } else {
                    t = this.scrollPercent * this.maxThumbPosition, i = this.scrollPercent * this.maxOverviewPosition;
                    this.setScrollPosition(i, t)
                }
            }, setScrollPosition: function (e, i) {
                this.$thumb.css(this.sizing.offsetComponent(), i + "px"), this.scrollable.$overview.css(this.sizing.offsetComponent(), -e + "px")
            }, setScrollPositionWithAnimation: function (e, i) {
                var t = {}, o = {};
                t[this.sizing.offsetComponent()] = i + "px", this.$thumb.animate(t, this.scrollable.options.animationSpeed), o[this.sizing.offsetComponent()] = -e + "px", this.scrollable.$overview.animate(o, this.scrollable.options.animationSpeed)
            }, calculateMaxThumbPosition: function () {
                return this.sizing.size(this.$scrollBar) - this.thumbSize
            }, calculateMaxOverviewPosition: function () {
                return this.sizing.size(this.scrollable.$overview) - this.sizing.size(this.scrollable.$viewPort)
            }, setScrollEvent: function (e) {
                var i = "page" + this.sizing.scrollAxis();
                this.scrollEvent && this.scrollEvent[i] == e[i] || (this.scrollEvent = {pageX: e.pageX, pageY: e.pageY})
            }, scrollToElement: function (e) {
                var i = h(e);
                if (this.sizing.isInside(i, this.scrollable.$overview) && !this.sizing.isInside(i, this.scrollable.$viewPort)) {
                    var t = i.offset(), o = this.scrollable.$overview.offset();
                    this.scrollable.$viewPort.offset();
                    this.scrollOverviewTo(t[this.sizing.offsetComponent()] - o[this.sizing.offsetComponent()], !0)
                }
            }, remove: function () {
                this.removeMouseMoveScrolling(), this.removeMouseWheelScrolling(), this.removeTouchScrolling(), this.removeMouseClickScrolling(), this.removeWindowResize()
            }
        };
        var l = function () {
        };
        l.prototype = {
            size: function (e, i) {
                return i ? e.width(i) : e.width()
            }, minSize: function (e) {
                return parseInt(e.css("min-width")) || 0
            }, fixedThumbSize: function (e) {
                return e.fixedThumbWidth
            }, scrollBar: function (e) {
                return e.find(".scroll-bar.horizontal")
            }, mouseDelta: function (e, i) {
                return i.pageX - e.pageX
            }, offsetComponent: function () {
                return "left"
            }, wheelDelta: function (e, i) {
                return e
            }, scrollAxis: function () {
                return "X"
            }, scrollDirection: function (e, i) {
                return e < i ? "right" : "left"
            }, scrollingKeys: {
                37: function (e) {
                    return -10
                }, 39: function (e) {
                    return 10
                }
            }, isInside: function (e, i) {
                var t = h(e), o = h(i), s = t.offset(), n = o.offset();
                return s.left >= n.left && s.left + t.width() <= n.left + o.width()
            }
        };
        var r = function () {
        };
        return r.prototype = {
            size: function (e, i) {
                return i ? e.height(i) : e.height()
            }, minSize: function (e) {
                return parseInt(e.css("min-height")) || 0
            }, fixedThumbSize: function (e) {
                return e.fixedThumbHeight
            }, scrollBar: function (e) {
                return e.find(".scroll-bar.vertical")
            }, mouseDelta: function (e, i) {
                return i.pageY - e.pageY
            }, offsetComponent: function () {
                return "top"
            }, wheelDelta: function (e, i) {
                return i
            }, scrollAxis: function () {
                return "Y"
            }, scrollDirection: function (e, i) {
                return e < i ? "down" : "up"
            }, scrollingKeys: {
                38: function (e) {
                    return -10
                }, 40: function (e) {
                    return 10
                }, 33: function (e) {
                    return -(e - 20)
                }, 34: function (e) {
                    return e - 20
                }
            }, isInside: function (e, i) {
                var t = h(e), o = h(i), s = t.offset(), n = o.offset();
                return s.top >= n.top && s.top + t.height() <= n.top + o.height()
            }
        }, this.each(function () {
            if (null == i && (i = o), "string" == typeof i) {
                var e = h(this).data("scrollable");
                e && e[i](t)
            } else {
                if ("object" != typeof i) throw"Invalid type of options";
                i = h.extend(o, i), new s(h(this), i)
            }
        })
    }
}(jQuery), function (l) {
    var i = ["DOMMouseScroll", "mousewheel"];
    if (l.event.fixHooks) for (var e = i.length; e;) l.event.fixHooks[i[--e]] = l.event.mouseHooks;

    function t(e) {
        var i = e || window.event, t = [].slice.call(arguments, 1), o = 0, s = 0, n = 0;
        return (e = l.event.fix(i)).type = "mousewheel", i.wheelDelta && (o = i.wheelDelta / 120), i.detail && (o = -i.detail / 3), n = o, void 0 !== i.axis && i.axis === i.HORIZONTAL_AXIS && (n = 0, s = o), void 0 !== i.wheelDeltaY && (n = i.wheelDeltaY / 120), void 0 !== i.wheelDeltaX && (s = i.wheelDeltaX / 120), t.unshift(e, o, s, n), (l.event.dispatch || l.event.handle).apply(this, t)
    }

    l.event.special.mousewheel = {
        setup: function () {
            if (this.addEventListener) for (var e = i.length; e;) this.addEventListener(i[--e], t, !1); else this.onmousewheel = t
        }, teardown: function () {
            if (this.removeEventListener) for (var e = i.length; e;) this.removeEventListener(i[--e], t, !1); else this.onmousewheel = null
        }
    }, l.fn.extend({
        mousewheel: function (e) {
            return e ? this.bind("mousewheel", e) : this.trigger("mousewheel")
        }, unmousewheel: function (e) {
            return this.unbind("mousewheel", e)
        }
    })
}(jQuery);

/* https://github.com/nobleclem/jQuery-MultiSelect 2.4.15 */
!function (w) {
    var s = {
        columns: 1,
        search: !1,
        searchOptions: {
            delay: 250, showOptGroups: !1, searchText: !0, searchValue: !1, onSearch: function (t) {
            }
        },
        texts: {
            placeholder: "Select options",
            search: "Search",
            selectedOptions: " selected",
            selectAll: "Select all",
            unselectAll: "Unselect all",
            noneSelected: "None Selected"
        },
        selectAll: !1,
        selectGroup: !1,
        minHeight: 200,
        maxHeight: null,
        maxWidth: null,
        maxPlaceholderWidth: null,
        maxPlaceholderOpts: 10,
        showCheckbox: !0,
        checkboxAutoFit: !1,
        optionAttributes: [],
        onLoad: function (t) {
        },
        onOptionClick: function (t, e) {
        },
        onControlClose: function (t) {
        },
        onSelectAll: function (t, e) {
        }
    }, o = 1, n = 1;

    function i(t, e) {
        if (this.element = t, this.options = w.extend(!0, {}, s, e), this.updateSelectAll = !0, this.updatePlaceholder = !0, this.listNumber = o, o += 1, !w(this.element).attr("multiple")) throw new Error("[jQuery-MultiSelect] Select list must be a multiselect list in order to use this plugin");
        if (this.options.search && !this.options.searchOptions.searchText && !this.options.searchOptions.searchValue) throw new Error("[jQuery-MultiSelect] Either searchText or searchValue should be true.");
        "placeholder" in this.options && (this.options.texts.placeholder = this.options.placeholder, delete this.options.placeholder), "default" in this.options.searchOptions && (this.options.texts.search = this.options.searchOptions.default, delete this.options.searchOptions.default), this.load()
    }

    "function" != typeof Array.prototype.map && (Array.prototype.map = function (t, e) {
        return void 0 === e && (e = this), w.isArray(e) ? w.map(e, t) : []
    }), "function" != typeof String.prototype.trim && (String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, "")
    }), i.prototype = {
        load: function () {
            var i = this;
            if ("SELECT" != i.element.nodeName || w(i.element).hasClass("jqmsLoaded")) return !0;
            w(i.element).addClass("jqmsLoaded ms-list-" + i.listNumber).data("plugin_multiselect-instance", i), w(i.element).after('<div id="ms-list-' + i.listNumber + '" class="ms-options-wrap"><button type="button"><span>None Selected</span></button><div class="ms-options"><ul></ul></div></div>');
            var s = w(i.element).siblings("#ms-list-" + i.listNumber + ".ms-options-wrap").find("> button:first-child"),
                n = w(i.element).siblings("#ms-list-" + i.listNumber + ".ms-options-wrap").find("> .ms-options"),
                l = n.find("> ul");
            if (i.options.showCheckbox ? i.options.checkboxAutoFit && n.addClass("checkbox-autofit") : n.addClass("hide-checkbox"), w(i.element).prop("disabled") && s.prop("disabled", !0), i.options.maxPlaceholderWidth && s.css("maxWidth", i.options.maxPlaceholderWidth), i.options.maxHeight) var t = i.options.maxHeight; else t = w(window).height() - n.offset().top + w(window).scrollTop() - 20;
            if (t = t < i.options.minHeight ? i.options.minHeight : t, n.css({
                maxWidth: i.options.maxWidth,
                minHeight: i.options.minHeight,
                maxHeight: t
            }), n.on("touchmove mousewheel DOMMouseScroll", function (t) {
                if (w(this).outerHeight() < w(this)[0].scrollHeight) {
                    var e = t.originalEvent, s = e.wheelDelta || -e.detail;
                    w(this).outerHeight() + w(this)[0].scrollTop > w(this)[0].scrollHeight && (t.preventDefault(), this.scrollTop += s < 0 ? 1 : -1)
                }
            }), w(document).off("click.ms-hideopts").on("click.ms-hideopts", function (t) {
                w(t.target).closest(".ms-options-wrap").length || w(".ms-options-wrap.ms-active > .ms-options").each(function () {
                    w(this).closest(".ms-options-wrap").removeClass("ms-active");
                    var t = w(this).closest(".ms-options-wrap").attr("id"),
                        e = w(this).parent().siblings("." + t + ".jqmsLoaded").data("plugin_multiselect-instance");
                    "function" == typeof e.options.onControlClose && e.options.onControlClose(e.element)
                })
            }).on("keydown", function (t) {
                27 == (t.keyCode || t.which) && w(this).trigger("click.ms-hideopts")
            }), s.on("keydown", function (t) {
                var e = t.keyCode || t.which;
                13 != e && 32 != e || s.trigger("mousedown")
            }), s.on("mousedown", function (t) {
                if (t.which && 1 != t.which) return !0;
                if (w(".ms-options-wrap.ms-active").each(function () {
                    if (w(this).siblings("." + w(this).attr("id") + ".jqmsLoaded")[0] != n.parent().siblings(".ms-list-" + i.listNumber + ".jqmsLoaded")[0]) {
                        w(this).removeClass("ms-active");
                        var t = w(this).siblings("." + w(this).attr("id") + ".jqmsLoaded").data("plugin_multiselect-instance");
                        "function" == typeof t.options.onControlClose && t.options.onControlClose(t.element)
                    }
                }), n.closest(".ms-options-wrap").toggleClass("ms-active"), n.closest(".ms-options-wrap").hasClass("ms-active")) {
                    if (n.css("maxHeight", ""), i.options.maxHeight) var e = i.options.maxHeight; else e = w(window).height() - n.offset().top + w(window).scrollTop() - 20;
                    e && (e = e < i.options.minHeight ? i.options.minHeight : e, n.css("maxHeight", e))
                } else "function" == typeof i.options.onControlClose && i.options.onControlClose(i.element)
            }).click(function (t) {
                t.preventDefault()
            }), i.options.texts.placeholder && s.find("span").text(i.options.texts.placeholder), i.options.search) {
                l.before('<div class="ms-search"><input type="text" value="" placeholder="' + i.options.texts.search + '" /></div>');
                var o = n.find(".ms-search input");
                o.on("keyup", function () {
                    if (w(this).data("lastsearch") == w(this).val()) return !0;
                    w(this).data("searchTimeout") && clearTimeout(w(this).data("searchTimeout"));
                    var e = w(this);
                    w(this).data("searchTimeout", setTimeout(function () {
                        e.data("lastsearch", e.val()), "function" == typeof i.options.searchOptions.onSearch && i.options.searchOptions.onSearch(i.element);
                        var t = w.trim(o.val().toLowerCase());
                        t ? (l.find('li[data-search-term*="' + t + '"]:not(.optgroup)').removeClass("ms-hidden"), l.find('li:not([data-search-term*="' + t + '"], .optgroup)').addClass("ms-hidden")) : l.find(".ms-hidden").removeClass("ms-hidden"), i.options.searchOptions.showOptGroups || l.find(".optgroup").each(function () {
                            w(this).find("li:not(.ms-hidden)").length ? w(this).show() : w(this).hide()
                        }), i._updateSelectAllText()
                    }, i.options.searchOptions.delay))
                })
            }
            i.options.selectAll && l.before('<a href="#" class="ms-selectall global">' + i.options.texts.selectAll + "</a>"), n.on("click", ".ms-selectall", function (t) {
                t.preventDefault(), i.updateSelectAll = !1, i.updatePlaceholder = !1;
                var e = n.parent().siblings(".ms-list-" + i.listNumber + ".jqmsLoaded");
                if (w(this).hasClass("global")) l.find("li:not(.optgroup, .selected, .ms-hidden)").length ? (l.find("li:not(.optgroup, .selected, .ms-hidden)").addClass("selected"), l.find('li.selected input[type="checkbox"]:not(:disabled)').prop("checked", !0)) : (l.find("li:not(.optgroup, .ms-hidden).selected").removeClass("selected"), l.find('li:not(.optgroup, .ms-hidden, .selected) input[type="checkbox"]:not(:disabled)').prop("checked", !1)); else if (w(this).closest("li").hasClass("optgroup")) {
                    var s = w(this).closest("li.optgroup");
                    s.find("li:not(.selected, .ms-hidden)").length ? (s.find("li:not(.selected, .ms-hidden)").addClass("selected"), s.find('li.selected input[type="checkbox"]:not(:disabled)').prop("checked", !0)) : (s.find("li:not(.ms-hidden).selected").removeClass("selected"), s.find('li:not(.ms-hidden, .selected) input[type="checkbox"]:not(:disabled)').prop("checked", !1))
                }
                var o = [];
                l.find('li.selected input[type="checkbox"]').each(function () {
                    o.push(w(this).val())
                }), e.val(o).trigger("change"), i.updateSelectAll = !0, i.updatePlaceholder = !0, "function" == typeof i.options.onSelectAll && i.options.onSelectAll(i.element, o.length), i._updateSelectAllText(), i._updatePlaceholderText()
            });
            var a = [];
            w(i.element).children().each(function () {
                if ("OPTGROUP" == this.nodeName) {
                    var o = [];
                    w(this).children("option").each(function () {
                        for (var t = {}, e = 0; e < i.options.optionAttributes.length; e++) {
                            var s = i.options.optionAttributes[e];
                            void 0 !== w(this).attr(s) && (t[s] = w(this).attr(s))
                        }
                        o.push({
                            name: w(this).text(),
                            value: w(this).val(),
                            checked: w(this).prop("selected"),
                            attributes: t
                        })
                    }), a.push({label: w(this).attr("label"), options: o})
                } else {
                    if ("OPTION" != this.nodeName) return !0;
                    for (var t = {}, e = 0; e < i.options.optionAttributes.length; e++) {
                        var s = i.options.optionAttributes[e];
                        void 0 !== w(this).attr(s) && (t[s] = w(this).attr(s))
                    }
                    a.push({
                        name: w(this).text(),
                        value: w(this).val(),
                        checked: w(this).prop("selected"),
                        attributes: t
                    })
                }
            }), i.loadOptions(a, !0, !1), n.on("click", 'input[type="checkbox"]', function () {
                w(this).closest("li").toggleClass("selected"), n.parent().siblings(".ms-list-" + i.listNumber + ".jqmsLoaded").find('option[value="' + w(this).val() + '"]').prop("selected", w(this).is(":checked")).closest("select").trigger("change"), "function" == typeof i.options.onOptionClick && i.options.onOptionClick(i.element, this), i._updateSelectAllText(), i._updatePlaceholderText()
            }), n.on("focusin", 'input[type="checkbox"]', function () {
                w(this).closest("label").addClass("focused")
            }).on("focusout", 'input[type="checkbox"]', function () {
                w(this).closest("label").removeClass("focused")
            }), "function" == typeof i.options.onLoad && i.options.onLoad(i.element), w(i.element).hide()
        }, loadOptions: function (t, e, s) {
            e = "boolean" != typeof e || e, s = "boolean" != typeof s || s;
            var o = this, i = w(o.element),
                n = i.siblings("#ms-list-" + o.listNumber + ".ms-options-wrap").find("> .ms-options > ul"),
                l = i.siblings("#ms-list-" + o.listNumber + ".ms-options-wrap").find("> .ms-options");
            e && (n.find("> li").remove(), s && i.find("> *").remove());
            var a = [];
            for (var p in t) if (t.hasOwnProperty(p)) {
                var c = t[p], r = w("<li/>"), h = !0;
                if (c.hasOwnProperty("value")) {
                    if (o.options.showCheckbox && o.options.checkboxAutoFit && r.addClass("ms-reflow"), o._addOption(r, c), s) {
                        var d = w('<option value="' + c.value + '">' + c.name + "</option>");
                        c.hasOwnProperty("attributes") && Object.keys(c.attributes).length && d.attr(c.attributes), c.checked && d.prop("selected", !0), i.append(d)
                    }
                } else {
                    if (!c.hasOwnProperty("options")) continue;
                    var u = w('<optgroup label="' + c.label + '"></optgroup>');
                    for (var m in n.find("> li.optgroup > span.label").each(function () {
                        w(this).text() == c.label && (r = w(this).closest(".optgroup"), h = !1)
                    }), s && (i.find('optgroup[label="' + c.label + '"]').length ? u = i.find('optgroup[label="' + c.label + '"]') : i.append(u)), h && (r.addClass("optgroup"), r.append('<span class="label">' + c.label + "</span>"), r.find("> .label").css({clear: "both"}), o.options.selectGroup && r.append('<a href="#" class="ms-selectall">' + o.options.texts.selectAll + "</a>"), r.append("<ul/>")), c.options) if (c.options.hasOwnProperty(m)) {
                        var f = c.options[m], b = w("<li/>");
                        if (o.options.showCheckbox && o.options.checkboxAutoFit && b.addClass("ms-reflow"), f.hasOwnProperty("value") && (o._addOption(b, f), r.find("> ul").append(b), s)) {
                            d = w('<option value="' + f.value + '">' + f.name + "</option>");
                            f.hasOwnProperty("attributes") && Object.keys(f.attributes).length && d.attr(f.attributes), f.checked && d.prop("selected", !0), u.append(d)
                        }
                    }
                }
                h && a.push(r)
            }
            if (n.append(a), o.options.checkboxAutoFit && o.options.showCheckbox && !l.hasClass("hide-checkbox")) {
                var g = n.find('.ms-reflow:eq(0) input[type="checkbox"]');
                if (g.length) {
                    var v = g.outerWidth();
                    v = v || 15, n.find(".ms-reflow label").css("padding-left", 2 * parseInt(g.closest("label").css("padding-left")) + v), n.find(".ms-reflow").removeClass("ms-reflow")
                }
            }
            o._updatePlaceholderText(), l.find("ul").css({
                "column-count": "",
                "column-gap": "",
                "-webkit-column-count": "",
                "-webkit-column-gap": "",
                "-moz-column-count": "",
                "-moz-column-gap": ""
            }), i.find("optgroup").length ? (n.find("> li:not(.optgroup)").css({
                float: "left",
                width: 100 / o.options.columns + "%"
            }), n.find("li.optgroup").css({clear: "both"}).find("> ul").css({
                "column-count": o.options.columns,
                "column-gap": 0,
                "-webkit-column-count": o.options.columns,
                "-webkit-column-gap": 0,
                "-moz-column-count": o.options.columns,
                "-moz-column-gap": 0
            }), this._ieVersion() && this._ieVersion() < 10 && n.find("li.optgroup > ul > li").css({
                float: "left",
                width: 100 / o.options.columns + "%"
            })) : (n.css({
                "column-count": o.options.columns,
                "column-gap": 0,
                "-webkit-column-count": o.options.columns,
                "-webkit-column-gap": 0,
                "-moz-column-count": o.options.columns,
                "-moz-column-gap": 0
            }), this._ieVersion() && this._ieVersion() < 10 && n.find("> li").css({
                float: "left",
                width: 100 / o.options.columns + "%"
            })), o._updateSelectAllText()
        }, settings: function (t) {
            this.options = w.extend(!0, {}, this.options, t), this.reload()
        }, unload: function () {
            w(this.element).siblings("#ms-list-" + this.listNumber + ".ms-options-wrap").remove(), w(this.element).show(function () {
                w(this).css("display", "").removeClass("jqmsLoaded")
            })
        }, reload: function () {
            w(this.element).siblings("#ms-list-" + this.listNumber + ".ms-options-wrap").remove(), w(this.element).removeClass("jqmsLoaded"), this.load()
        }, reset: function () {
            var t = [];
            w(this.element).find("option").each(function () {
                w(this).prop("defaultSelected") && t.push(w(this).val())
            }), w(this.element).val(t), this.reload()
        }, disable: function (t) {
            t = "boolean" != typeof t || t, w(this.element).prop("disabled", t), w(this.element).siblings("#ms-list-" + this.listNumber + ".ms-options-wrap").find("button:first-child").prop("disabled", t)
        }, _updateSelectAllText: function () {
            if (this.updateSelectAll) {
                var e = this;
                if (e.options.selectAll || e.options.selectGroup) w(e.element).siblings("#ms-list-" + e.listNumber + ".ms-options-wrap").find("> .ms-options").find(".ms-selectall").each(function () {
                    var t = w(this).parent().find("li:not(.optgroup,.selected,.ms-hidden)");
                    w(this).text(t.length ? e.options.texts.selectAll : e.options.texts.unselectAll)
                })
            }
        }, _updatePlaceholderText: function () {
            if (this.updatePlaceholder) {
                var t = this, e = w(t.element), s = e.val() ? e.val() : [],
                    o = e.siblings("#ms-list-" + t.listNumber + ".ms-options-wrap").find("> button:first-child"),
                    i = o.find("span"),
                    n = e.siblings("#ms-list-" + t.listNumber + ".ms-options-wrap").find("> .ms-options");
                e.find("option:selected:disabled").length && (s = [], e.find("option:selected").each(function () {
                    s.push(w(this).val())
                }));
                var l = [];
                for (var a in s) if (s.hasOwnProperty(a) && (l.push(w.trim(e.find('option[value="' + s[a] + '"]').text())), l.length >= t.options.maxPlaceholderOpts)) break;
                i.text(l.join(", ")), l.length ? n.closest(".ms-options-wrap").addClass("ms-has-selections") : n.closest(".ms-options-wrap").removeClass("ms-has-selections"), l.length ? (i.width() > o.width() || l.length != s.length) && i.text(s.length + t.options.texts.selectedOptions) : i.text(t.options.texts.placeholder)
            }
        }, _addOption: function (t, e) {
            var s = w("<label/>", {for: "ms-opt-" + n, text: e.name}),
                o = w("<input>", {type: "checkbox", title: e.name, id: "ms-opt-" + n, value: e.value});
            e.hasOwnProperty("attributes") && Object.keys(e.attributes).length && o.attr(e.attributes), e.checked && (t.addClass("default selected"), o.prop("checked", !0)), s.prepend(o);
            var i = "";
            this.options.searchOptions.searchText && (i += " " + e.name.toLowerCase()), this.options.searchOptions.searchValue && (i += " " + e.value.toLowerCase()), t.attr("data-search-term", w.trim(i)).prepend(s), n += 1
        }, _ieVersion: function () {
            var t = navigator.userAgent.toLowerCase();
            return -1 != t.indexOf("msie") && parseInt(t.split("msie")[1])
        }
    }, w.fn.multiselect = function (e) {
        if (this.length) {
            var s, o = arguments;
            return void 0 === e || "object" == typeof e ? this.each(function () {
                w.data(this, "plugin_multiselect") || w.data(this, "plugin_multiselect", new i(this, e))
            }) : "string" == typeof e && "_" !== e[0] && "init" !== e ? (this.each(function () {
                var t = w.data(this, "plugin_multiselect");
                t instanceof i && "function" == typeof t[e] && (s = t[e].apply(t, Array.prototype.slice.call(o, 1))), "unload" === e && w.data(this, "plugin_multiselect", null)
            }), s) : void 0
        }
    }
}(jQuery);

/* https://github.com/harvesthq/chosen/ 1.8.7 */
(function () {
    var t, e, s, i, n = function (t, e) {
        return function () {
            return t.apply(e, arguments)
        }
    }, r = function (t, e) {
        function s() {
            this.constructor = t
        }

        for (var i in e) o.call(e, i) && (t[i] = e[i]);
        return s.prototype = e.prototype, t.prototype = new s, t.__super__ = e.prototype, t
    }, o = {}.hasOwnProperty;
    (i = function () {
        function t() {
            this.options_index = 0, this.parsed = []
        }

        return t.prototype.add_node = function (t) {
            return "OPTGROUP" === t.nodeName.toUpperCase() ? this.add_group(t) : this.add_option(t)
        }, t.prototype.add_group = function (t) {
            var e, s, i, n, r, o;
            for (e = this.parsed.length, this.parsed.push({
                array_index: e,
                group: !0,
                label: t.label,
                title: t.title ? t.title : void 0,
                children: 0,
                disabled: t.disabled,
                classes: t.className
            }), o = [], s = 0, i = (r = t.childNodes).length; s < i; s++) n = r[s], o.push(this.add_option(n, e, t.disabled));
            return o
        }, t.prototype.add_option = function (t, e, s) {
            if ("OPTION" === t.nodeName.toUpperCase()) return "" !== t.text ? (null != e && (this.parsed[e].children += 1), this.parsed.push({
                array_index: this.parsed.length,
                options_index: this.options_index,
                value: t.value,
                text: t.text,
                html: t.innerHTML,
                title: t.title ? t.title : void 0,
                selected: t.selected,
                disabled: !0 === s ? s : t.disabled,
                group_array_index: e,
                group_label: null != e ? this.parsed[e].label : null,
                classes: t.className,
                style: t.style.cssText
            })) : this.parsed.push({
                array_index: this.parsed.length,
                options_index: this.options_index,
                empty: !0
            }), this.options_index += 1
        }, t
    }()).select_to_array = function (t) {
        var e, s, n, r, o;
        for (r = new i, s = 0, n = (o = t.childNodes).length; s < n; s++) e = o[s], r.add_node(e);
        return r.parsed
    }, e = function () {
        function t(e, s) {
            this.form_field = e, this.options = null != s ? s : {}, this.label_click_handler = n(this.label_click_handler, this), t.browser_is_supported() && (this.is_multiple = this.form_field.multiple, this.set_default_text(), this.set_default_values(), this.setup(), this.set_up_html(), this.register_observers(), this.on_ready())
        }

        return t.prototype.set_default_values = function () {
            return this.click_test_action = function (t) {
                return function (e) {
                    return t.test_active_click(e)
                }
            }(this), this.activate_action = function (t) {
                return function (e) {
                    return t.activate_field(e)
                }
            }(this), this.active_field = !1, this.mouse_on_container = !1, this.results_showing = !1, this.result_highlighted = null, this.is_rtl = this.options.rtl || /\bchosen-rtl\b/.test(this.form_field.className), this.allow_single_deselect = null != this.options.allow_single_deselect && null != this.form_field.options[0] && "" === this.form_field.options[0].text && this.options.allow_single_deselect, this.disable_search_threshold = this.options.disable_search_threshold || 0, this.disable_search = this.options.disable_search || !1, this.enable_split_word_search = null == this.options.enable_split_word_search || this.options.enable_split_word_search, this.group_search = null == this.options.group_search || this.options.group_search, this.search_contains = this.options.search_contains || !1, this.single_backstroke_delete = null == this.options.single_backstroke_delete || this.options.single_backstroke_delete, this.max_selected_options = this.options.max_selected_options || Infinity, this.inherit_select_classes = this.options.inherit_select_classes || !1, this.display_selected_options = null == this.options.display_selected_options || this.options.display_selected_options, this.display_disabled_options = null == this.options.display_disabled_options || this.options.display_disabled_options, this.include_group_label_in_selected = this.options.include_group_label_in_selected || !1, this.max_shown_results = this.options.max_shown_results || Number.POSITIVE_INFINITY, this.case_sensitive_search = this.options.case_sensitive_search || !1, this.hide_results_on_select = null == this.options.hide_results_on_select || this.options.hide_results_on_select
        }, t.prototype.set_default_text = function () {
            return this.form_field.getAttribute("data-placeholder") ? this.default_text = this.form_field.getAttribute("data-placeholder") : this.is_multiple ? this.default_text = this.options.placeholder_text_multiple || this.options.placeholder_text || t.default_multiple_text : this.default_text = this.options.placeholder_text_single || this.options.placeholder_text || t.default_single_text, this.default_text = this.escape_html(this.default_text), this.results_none_found = this.form_field.getAttribute("data-no_results_text") || this.options.no_results_text || t.default_no_result_text
        }, t.prototype.choice_label = function (t) {
            return this.include_group_label_in_selected && null != t.group_label ? "<b class='group-name'>" + this.escape_html(t.group_label) + "</b>" + t.html : t.html
        }, t.prototype.mouse_enter = function () {
            return this.mouse_on_container = !0
        }, t.prototype.mouse_leave = function () {
            return this.mouse_on_container = !1
        }, t.prototype.input_focus = function (t) {
            if (this.is_multiple) {
                if (!this.active_field) return setTimeout(function (t) {
                    return function () {
                        return t.container_mousedown()
                    }
                }(this), 50)
            } else if (!this.active_field) return this.activate_field()
        }, t.prototype.input_blur = function (t) {
            if (!this.mouse_on_container) return this.active_field = !1, setTimeout(function (t) {
                return function () {
                    return t.blur_test()
                }
            }(this), 100)
        }, t.prototype.label_click_handler = function (t) {
            return this.is_multiple ? this.container_mousedown(t) : this.activate_field()
        }, t.prototype.results_option_build = function (t) {
            var e, s, i, n, r, o, h;
            for (e = "", h = 0, n = 0, r = (o = this.results_data).length; n < r && (s = o[n], i = "", "" !== (i = s.group ? this.result_add_group(s) : this.result_add_option(s)) && (h++, e += i), (null != t ? t.first : void 0) && (s.selected && this.is_multiple ? this.choice_build(s) : s.selected && !this.is_multiple && this.single_set_selected_text(this.choice_label(s))), !(h >= this.max_shown_results)); n++) ;
            return e
        }, t.prototype.result_add_option = function (t) {
            var e, s;
            return t.search_match && this.include_option_in_results(t) ? (e = [], t.disabled || t.selected && this.is_multiple || e.push("active-result"), !t.disabled || t.selected && this.is_multiple || e.push("disabled-result"), t.selected && e.push("result-selected"), null != t.group_array_index && e.push("group-option"), "" !== t.classes && e.push(t.classes), s = document.createElement("li"), s.className = e.join(" "), t.style && (s.style.cssText = t.style), s.setAttribute("data-option-array-index", t.array_index), s.innerHTML = t.highlighted_html || t.html, t.title && (s.title = t.title), this.outerHTML(s)) : ""
        }, t.prototype.result_add_group = function (t) {
            var e, s;
            return (t.search_match || t.group_match) && t.active_options > 0 ? ((e = []).push("group-result"), t.classes && e.push(t.classes), s = document.createElement("li"), s.className = e.join(" "), s.innerHTML = t.highlighted_html || this.escape_html(t.label), t.title && (s.title = t.title), this.outerHTML(s)) : ""
        }, t.prototype.results_update_field = function () {
            if (this.set_default_text(), this.is_multiple || this.results_reset_cleanup(), this.result_clear_highlight(), this.results_build(), this.results_showing) return this.winnow_results()
        }, t.prototype.reset_single_select_options = function () {
            var t, e, s, i, n;
            for (n = [], t = 0, e = (s = this.results_data).length; t < e; t++) (i = s[t]).selected ? n.push(i.selected = !1) : n.push(void 0);
            return n
        }, t.prototype.results_toggle = function () {
            return this.results_showing ? this.results_hide() : this.results_show()
        }, t.prototype.results_search = function (t) {
            return this.results_showing ? this.winnow_results() : this.results_show()
        }, t.prototype.winnow_results = function (t) {
            var e, s, i, n, r, o, h, l, c, _, a, u, d, p, f;
            for (this.no_results_clear(), _ = 0, e = (h = this.get_search_text()).replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), c = this.get_search_regex(e), i = 0, n = (l = this.results_data).length; i < n; i++) (r = l[i]).search_match = !1, a = null, u = null, r.highlighted_html = "", this.include_option_in_results(r) && (r.group && (r.group_match = !1, r.active_options = 0), null != r.group_array_index && this.results_data[r.group_array_index] && (0 === (a = this.results_data[r.group_array_index]).active_options && a.search_match && (_ += 1), a.active_options += 1), f = r.group ? r.label : r.text, r.group && !this.group_search || (u = this.search_string_match(f, c), r.search_match = null != u, r.search_match && !r.group && (_ += 1), r.search_match ? (h.length && (d = u.index, o = f.slice(0, d), s = f.slice(d, d + h.length), p = f.slice(d + h.length), r.highlighted_html = this.escape_html(o) + "<em>" + this.escape_html(s) + "</em>" + this.escape_html(p)), null != a && (a.group_match = !0)) : null != r.group_array_index && this.results_data[r.group_array_index].search_match && (r.search_match = !0)));
            return this.result_clear_highlight(), _ < 1 && h.length ? (this.update_results_content(""), this.no_results(h)) : (this.update_results_content(this.results_option_build()), (null != t ? t.skip_highlight : void 0) ? void 0 : this.winnow_results_set_highlight())
        }, t.prototype.get_search_regex = function (t) {
            var e, s;
            return s = this.search_contains ? t : "(^|\\s|\\b)" + t + "[^\\s]*", this.enable_split_word_search || this.search_contains || (s = "^" + s), e = this.case_sensitive_search ? "" : "i", new RegExp(s, e)
        }, t.prototype.search_string_match = function (t, e) {
            var s;
            return s = e.exec(t), !this.search_contains && (null != s ? s[1] : void 0) && (s.index += 1), s
        }, t.prototype.choices_count = function () {
            var t, e, s;
            if (null != this.selected_option_count) return this.selected_option_count;
            for (this.selected_option_count = 0, t = 0, e = (s = this.form_field.options).length; t < e; t++) s[t].selected && (this.selected_option_count += 1);
            return this.selected_option_count
        }, t.prototype.choices_click = function (t) {
            if (t.preventDefault(), this.activate_field(), !this.results_showing && !this.is_disabled) return this.results_show()
        }, t.prototype.keydown_checker = function (t) {
            var e, s;
            switch (s = null != (e = t.which) ? e : t.keyCode, this.search_field_scale(), 8 !== s && this.pending_backstroke && this.clear_backstroke(), s) {
                case 8:
                    this.backstroke_length = this.get_search_field_value().length;
                    break;
                case 9:
                    this.results_showing && !this.is_multiple && this.result_select(t), this.mouse_on_container = !1;
                    break;
                case 13:
                case 27:
                    this.results_showing && t.preventDefault();
                    break;
                case 32:
                    this.disable_search && t.preventDefault();
                    break;
                case 38:
                    t.preventDefault(), this.keyup_arrow();
                    break;
                case 40:
                    t.preventDefault(), this.keydown_arrow()
            }
        }, t.prototype.keyup_checker = function (t) {
            var e, s;
            switch (s = null != (e = t.which) ? e : t.keyCode, this.search_field_scale(), s) {
                case 8:
                    this.is_multiple && this.backstroke_length < 1 && this.choices_count() > 0 ? this.keydown_backstroke() : this.pending_backstroke || (this.result_clear_highlight(), this.results_search());
                    break;
                case 13:
                    t.preventDefault(), this.results_showing && this.result_select(t);
                    break;
                case 27:
                    this.results_showing && this.results_hide();
                    break;
                case 9:
                case 16:
                case 17:
                case 18:
                case 38:
                case 40:
                case 91:
                    break;
                default:
                    this.results_search()
            }
        }, t.prototype.clipboard_event_checker = function (t) {
            if (!this.is_disabled) return setTimeout(function (t) {
                return function () {
                    return t.results_search()
                }
            }(this), 50)
        }, t.prototype.container_width = function () {
            return null != this.options.width ? this.options.width : this.form_field.offsetWidth + "px"
        }, t.prototype.include_option_in_results = function (t) {
            return !(this.is_multiple && !this.display_selected_options && t.selected) && (!(!this.display_disabled_options && t.disabled) && !t.empty)
        }, t.prototype.search_results_touchstart = function (t) {
            return this.touch_started = !0, this.search_results_mouseover(t)
        }, t.prototype.search_results_touchmove = function (t) {
            return this.touch_started = !1, this.search_results_mouseout(t)
        }, t.prototype.search_results_touchend = function (t) {
            if (this.touch_started) return this.search_results_mouseup(t)
        }, t.prototype.outerHTML = function (t) {
            var e;
            return t.outerHTML ? t.outerHTML : ((e = document.createElement("div")).appendChild(t), e.innerHTML)
        }, t.prototype.get_single_html = function () {
            return '<a class="chosen-single chosen-default">\n  <span>' + this.default_text + '</span>\n  <div><b></b></div>\n</a>\n<div class="chosen-drop">\n  <div class="chosen-search">\n	<input class="chosen-search-input" type="text" autocomplete="off" />\n  </div>\n  <ul class="chosen-results"></ul>\n</div>'
        }, t.prototype.get_multi_html = function () {
            return '<ul class="chosen-choices">\n  <li class="search-field">\n	<input class="chosen-search-input" type="text" autocomplete="off" value="' + this.default_text + '" />\n  </li>\n</ul>\n<div class="chosen-drop">\n  <ul class="chosen-results"></ul>\n</div>'
        }, t.prototype.get_no_results_html = function (t) {
            return '<li class="no-results">\n  ' + this.results_none_found + " <span>" + this.escape_html(t) + "</span>\n</li>"
        }, t.browser_is_supported = function () {
            return "Microsoft Internet Explorer" === window.navigator.appName ? document.documentMode >= 8 : !(/iP(od|hone)/i.test(window.navigator.userAgent) || /IEMobile/i.test(window.navigator.userAgent) || /Windows Phone/i.test(window.navigator.userAgent) || /BlackBerry/i.test(window.navigator.userAgent) || /BB10/i.test(window.navigator.userAgent) || /Android.*Mobile/i.test(window.navigator.userAgent))
        }, t.default_multiple_text = "Select Some Options", t.default_single_text = "Select an Option", t.default_no_result_text = "No results match", t
    }(), (t = jQuery).fn.extend({
        chosen: function (i) {
            return e.browser_is_supported() ? this.each(function (e) {
                var n, r;
                r = (n = t(this)).data("chosen"), "destroy" !== i ? r instanceof s || n.data("chosen", new s(this, i)) : r instanceof s && r.destroy()
            }) : this
        }
    }), s = function (s) {
        function n() {
            return n.__super__.constructor.apply(this, arguments)
        }

        return r(n, e), n.prototype.setup = function () {
            return this.form_field_jq = t(this.form_field), this.current_selectedIndex = this.form_field.selectedIndex
        }, n.prototype.set_up_html = function () {
            var e, s;
            return (e = ["chosen-container"]).push("chosen-container-" + (this.is_multiple ? "multi" : "single")), this.inherit_select_classes && this.form_field.className && e.push(this.form_field.className), this.is_rtl && e.push("chosen-rtl"), s = {
                "class": e.join(" "),
                title: this.form_field.title
            }, this.form_field.id.length && (s.id = this.form_field.id.replace(/[^\w]/g, "_") + "_chosen"), this.container = t("<div />", s), this.container.width(this.container_width()), this.is_multiple ? this.container.html(this.get_multi_html()) : this.container.html(this.get_single_html()), this.form_field_jq.hide().after(this.container), this.dropdown = this.container.find("div.chosen-drop").first(), this.search_field = this.container.find("input").first(), this.search_results = this.container.find("ul.chosen-results").first(), this.search_field_scale(), this.search_no_results = this.container.find("li.no-results").first(), this.is_multiple ? (this.search_choices = this.container.find("ul.chosen-choices").first(), this.search_container = this.container.find("li.search-field").first()) : (this.search_container = this.container.find("div.chosen-search").first(), this.selected_item = this.container.find(".chosen-single").first()), this.results_build(), this.set_tab_index(), this.set_label_behavior()
        }, n.prototype.on_ready = function () {
            return this.form_field_jq.trigger("chosen:ready", {chosen: this})
        }, n.prototype.register_observers = function () {
            return this.container.on("touchstart.chosen", function (t) {
                return function (e) {
                    t.container_mousedown(e)
                }
            }(this)), this.container.on("touchend.chosen", function (t) {
                return function (e) {
                    t.container_mouseup(e)
                }
            }(this)), this.container.on("mousedown.chosen", function (t) {
                return function (e) {
                    t.container_mousedown(e)
                }
            }(this)), this.container.on("mouseup.chosen", function (t) {
                return function (e) {
                    t.container_mouseup(e)
                }
            }(this)), this.container.on("mouseenter.chosen", function (t) {
                return function (e) {
                    t.mouse_enter(e)
                }
            }(this)), this.container.on("mouseleave.chosen", function (t) {
                return function (e) {
                    t.mouse_leave(e)
                }
            }(this)), this.search_results.on("mouseup.chosen", function (t) {
                return function (e) {
                    t.search_results_mouseup(e)
                }
            }(this)), this.search_results.on("mouseover.chosen", function (t) {
                return function (e) {
                    t.search_results_mouseover(e)
                }
            }(this)), this.search_results.on("mouseout.chosen", function (t) {
                return function (e) {
                    t.search_results_mouseout(e)
                }
            }(this)), this.search_results.on("mousewheel.chosen DOMMouseScroll.chosen", function (t) {
                return function (e) {
                    t.search_results_mousewheel(e)
                }
            }(this)), this.search_results.on("touchstart.chosen", function (t) {
                return function (e) {
                    t.search_results_touchstart(e)
                }
            }(this)), this.search_results.on("touchmove.chosen", function (t) {
                return function (e) {
                    t.search_results_touchmove(e)
                }
            }(this)), this.search_results.on("touchend.chosen", function (t) {
                return function (e) {
                    t.search_results_touchend(e)
                }
            }(this)), this.form_field_jq.on("chosen:updated.chosen", function (t) {
                return function (e) {
                    t.results_update_field(e)
                }
            }(this)), this.form_field_jq.on("chosen:activate.chosen", function (t) {
                return function (e) {
                    t.activate_field(e)
                }
            }(this)), this.form_field_jq.on("chosen:open.chosen", function (t) {
                return function (e) {
                    t.container_mousedown(e)
                }
            }(this)), this.form_field_jq.on("chosen:close.chosen", function (t) {
                return function (e) {
                    t.close_field(e)
                }
            }(this)), this.search_field.on("blur.chosen", function (t) {
                return function (e) {
                    t.input_blur(e)
                }
            }(this)), this.search_field.on("keyup.chosen", function (t) {
                return function (e) {
                    t.keyup_checker(e)
                }
            }(this)), this.search_field.on("keydown.chosen", function (t) {
                return function (e) {
                    t.keydown_checker(e)
                }
            }(this)), this.search_field.on("focus.chosen", function (t) {
                return function (e) {
                    t.input_focus(e)
                }
            }(this)), this.search_field.on("cut.chosen", function (t) {
                return function (e) {
                    t.clipboard_event_checker(e)
                }
            }(this)), this.search_field.on("paste.chosen", function (t) {
                return function (e) {
                    t.clipboard_event_checker(e)
                }
            }(this)), this.is_multiple ? this.search_choices.on("click.chosen", function (t) {
                return function (e) {
                    t.choices_click(e)
                }
            }(this)) : this.container.on("click.chosen", function (t) {
                t.preventDefault()
            })
        }, n.prototype.destroy = function () {
            return t(this.container[0].ownerDocument).off("click.chosen", this.click_test_action), this.form_field_label.length > 0 && this.form_field_label.off("click.chosen"), this.search_field[0].tabIndex && (this.form_field_jq[0].tabIndex = this.search_field[0].tabIndex), this.container.remove(), this.form_field_jq.removeData("chosen"), this.form_field_jq.show()
        }, n.prototype.search_field_disabled = function () {
            return this.is_disabled = this.form_field.disabled || this.form_field_jq.parents("fieldset").is(":disabled"), this.container.toggleClass("chosen-disabled", this.is_disabled), this.search_field[0].disabled = this.is_disabled, this.is_multiple || this.selected_item.off("focus.chosen", this.activate_field), this.is_disabled ? this.close_field() : this.is_multiple ? void 0 : this.selected_item.on("focus.chosen", this.activate_field)
        }, n.prototype.container_mousedown = function (e) {
            var s;
            if (!this.is_disabled) return !e || "mousedown" !== (s = e.type) && "touchstart" !== s || this.results_showing || e.preventDefault(), null != e && t(e.target).hasClass("search-choice-close") ? void 0 : (this.active_field ? this.is_multiple || !e || t(e.target)[0] !== this.selected_item[0] && !t(e.target).parents("a.chosen-single").length || (e.preventDefault(), this.results_toggle()) : (this.is_multiple && this.search_field.val(""), t(this.container[0].ownerDocument).on("click.chosen", this.click_test_action), this.results_show()), this.activate_field())
        }, n.prototype.container_mouseup = function (t) {
            if ("ABBR" === t.target.nodeName && !this.is_disabled) return this.results_reset(t)
        }, n.prototype.search_results_mousewheel = function (t) {
            var e;
            if (t.originalEvent && (e = t.originalEvent.deltaY || -t.originalEvent.wheelDelta || t.originalEvent.detail), null != e) return t.preventDefault(), "DOMMouseScroll" === t.type && (e *= 40), this.search_results.scrollTop(e + this.search_results.scrollTop())
        }, n.prototype.blur_test = function (t) {
            if (!this.active_field && this.container.hasClass("chosen-container-active")) return this.close_field()
        }, n.prototype.close_field = function () {
            return t(this.container[0].ownerDocument).off("click.chosen", this.click_test_action), this.active_field = !1, this.results_hide(), this.container.removeClass("chosen-container-active"), this.clear_backstroke(), this.show_search_field_default(), this.search_field_scale(), this.search_field.blur()
        }, n.prototype.activate_field = function () {
            if (!this.is_disabled) return this.container.addClass("chosen-container-active"), this.active_field = !0, this.search_field.val(this.search_field.val()), this.search_field.focus()
        }, n.prototype.test_active_click = function (e) {
            var s;
            return (s = t(e.target).closest(".chosen-container")).length && this.container[0] === s[0] ? this.active_field = !0 : this.close_field()
        }, n.prototype.results_build = function () {
            return this.parsing = !0, this.selected_option_count = null, this.results_data = i.select_to_array(this.form_field), this.is_multiple ? this.search_choices.find("li.search-choice").remove() : (this.single_set_selected_text(), this.disable_search || this.form_field.options.length <= this.disable_search_threshold ? (this.search_field[0].readOnly = !0, this.container.addClass("chosen-container-single-nosearch")) : (this.search_field[0].readOnly = !1, this.container.removeClass("chosen-container-single-nosearch"))), this.update_results_content(this.results_option_build({first: !0})), this.search_field_disabled(), this.show_search_field_default(), this.search_field_scale(), this.parsing = !1
        }, n.prototype.result_do_highlight = function (t) {
            var e, s, i, n, r;
            if (t.length) {
                if (this.result_clear_highlight(), this.result_highlight = t, this.result_highlight.addClass("highlighted"), i = parseInt(this.search_results.css("maxHeight"), 10), r = this.search_results.scrollTop(), n = i + r, s = this.result_highlight.position().top + this.search_results.scrollTop(), (e = s + this.result_highlight.outerHeight()) >= n) return this.search_results.scrollTop(e - i > 0 ? e - i : 0);
                if (s < r) return this.search_results.scrollTop(s)
            }
        }, n.prototype.result_clear_highlight = function () {
            return this.result_highlight && this.result_highlight.removeClass("highlighted"), this.result_highlight = null
        }, n.prototype.results_show = function () {
            return this.is_multiple && this.max_selected_options <= this.choices_count() ? (this.form_field_jq.trigger("chosen:maxselected", {chosen: this}), !1) : (this.container.addClass("chosen-with-drop"), this.results_showing = !0, this.search_field.focus(), this.search_field.val(this.get_search_field_value()), this.winnow_results(), this.form_field_jq.trigger("chosen:showing_dropdown", {chosen: this}))
        }, n.prototype.update_results_content = function (t) {
            return this.search_results.html(t)
        }, n.prototype.results_hide = function () {
            return this.results_showing && (this.result_clear_highlight(), this.container.removeClass("chosen-with-drop"), this.form_field_jq.trigger("chosen:hiding_dropdown", {chosen: this})), this.results_showing = !1
        }, n.prototype.set_tab_index = function (t) {
            var e;
            if (this.form_field.tabIndex) return e = this.form_field.tabIndex, this.form_field.tabIndex = -1, this.search_field[0].tabIndex = e
        }, n.prototype.set_label_behavior = function () {
            if (this.form_field_label = this.form_field_jq.parents("label"), !this.form_field_label.length && this.form_field.id.length && (this.form_field_label = t("label[for='" + this.form_field.id + "']")), this.form_field_label.length > 0) return this.form_field_label.on("click.chosen", this.label_click_handler)
        }, n.prototype.show_search_field_default = function () {
            return this.is_multiple && this.choices_count() < 1 && !this.active_field ? (this.search_field.val(this.default_text), this.search_field.addClass("default")) : (this.search_field.val(""), this.search_field.removeClass("default"))
        }, n.prototype.search_results_mouseup = function (e) {
            var s;
            if ((s = t(e.target).hasClass("active-result") ? t(e.target) : t(e.target).parents(".active-result").first()).length) return this.result_highlight = s, this.result_select(e), this.search_field.focus()
        }, n.prototype.search_results_mouseover = function (e) {
            var s;
            if (s = t(e.target).hasClass("active-result") ? t(e.target) : t(e.target).parents(".active-result").first()) return this.result_do_highlight(s)
        }, n.prototype.search_results_mouseout = function (e) {
            if (t(e.target).hasClass("active-result") || t(e.target).parents(".active-result").first()) return this.result_clear_highlight()
        }, n.prototype.choice_build = function (e) {
            var s, i;
            return s = t("<li />", {"class": "search-choice"}).html("<span>" + this.choice_label(e) + "</span>"), e.disabled ? s.addClass("search-choice-disabled") : ((i = t("<a />", {
                "class": "search-choice-close",
                "data-option-array-index": e.array_index
            })).on("click.chosen", function (t) {
                return function (e) {
                    return t.choice_destroy_link_click(e)
                }
            }(this)), s.append(i)), this.search_container.before(s)
        }, n.prototype.choice_destroy_link_click = function (e) {
            if (e.preventDefault(), e.stopPropagation(), !this.is_disabled) return this.choice_destroy(t(e.target))
        }, n.prototype.choice_destroy = function (t) {
            if (this.result_deselect(t[0].getAttribute("data-option-array-index"))) return this.active_field ? this.search_field.focus() : this.show_search_field_default(), this.is_multiple && this.choices_count() > 0 && this.get_search_field_value().length < 1 && this.results_hide(), t.parents("li").first().remove(), this.search_field_scale()
        }, n.prototype.results_reset = function () {
            if (this.reset_single_select_options(), this.form_field.options[0].selected = !0, this.single_set_selected_text(), this.show_search_field_default(), this.results_reset_cleanup(), this.trigger_form_field_change(), this.active_field) return this.results_hide()
        }, n.prototype.results_reset_cleanup = function () {
            return this.current_selectedIndex = this.form_field.selectedIndex, this.selected_item.find("abbr").remove()
        }, n.prototype.result_select = function (t) {
            var e, s;
            if (this.result_highlight) return e = this.result_highlight, this.result_clear_highlight(), this.is_multiple && this.max_selected_options <= this.choices_count() ? (this.form_field_jq.trigger("chosen:maxselected", {chosen: this}), !1) : (this.is_multiple ? e.removeClass("active-result") : this.reset_single_select_options(), e.addClass("result-selected"), s = this.results_data[e[0].getAttribute("data-option-array-index")], s.selected = !0, this.form_field.options[s.options_index].selected = !0, this.selected_option_count = null, this.is_multiple ? this.choice_build(s) : this.single_set_selected_text(this.choice_label(s)), this.is_multiple && (!this.hide_results_on_select || t.metaKey || t.ctrlKey) ? t.metaKey || t.ctrlKey ? this.winnow_results({skip_highlight: !0}) : (this.search_field.val(""), this.winnow_results()) : (this.results_hide(), this.show_search_field_default()), (this.is_multiple || this.form_field.selectedIndex !== this.current_selectedIndex) && this.trigger_form_field_change({selected: this.form_field.options[s.options_index].value}), this.current_selectedIndex = this.form_field.selectedIndex, t.preventDefault(), this.search_field_scale())
        }, n.prototype.single_set_selected_text = function (t) {
            return null == t && (t = this.default_text), t === this.default_text ? this.selected_item.addClass("chosen-default") : (this.single_deselect_control_build(), this.selected_item.removeClass("chosen-default")), this.selected_item.find("span").html(t)
        }, n.prototype.result_deselect = function (t) {
            var e;
            return e = this.results_data[t], !this.form_field.options[e.options_index].disabled && (e.selected = !1, this.form_field.options[e.options_index].selected = !1, this.selected_option_count = null, this.result_clear_highlight(), this.results_showing && this.winnow_results(), this.trigger_form_field_change({deselected: this.form_field.options[e.options_index].value}), this.search_field_scale(), !0)
        }, n.prototype.single_deselect_control_build = function () {
            if (this.allow_single_deselect) return this.selected_item.find("abbr").length || this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>'), this.selected_item.addClass("chosen-single-with-deselect")
        }, n.prototype.get_search_field_value = function () {
            return this.search_field.val()
        }, n.prototype.get_search_text = function () {
            return t.trim(this.get_search_field_value())
        }, n.prototype.escape_html = function (e) {
            return t("<div/>").text(e).html()
        }, n.prototype.winnow_results_set_highlight = function () {
            var t, e;
            if (e = this.is_multiple ? [] : this.search_results.find(".result-selected.active-result"), null != (t = e.length ? e.first() : this.search_results.find(".active-result").first())) return this.result_do_highlight(t)
        }, n.prototype.no_results = function (t) {
            var e;
            return e = this.get_no_results_html(t), this.search_results.append(e), this.form_field_jq.trigger("chosen:no_results", {chosen: this})
        }, n.prototype.no_results_clear = function () {
            return this.search_results.find(".no-results").remove()
        }, n.prototype.keydown_arrow = function () {
            var t;
            return this.results_showing && this.result_highlight ? (t = this.result_highlight.nextAll("li.active-result").first()) ? this.result_do_highlight(t) : void 0 : this.results_show()
        }, n.prototype.keyup_arrow = function () {
            var t;
            return this.results_showing || this.is_multiple ? this.result_highlight ? (t = this.result_highlight.prevAll("li.active-result")).length ? this.result_do_highlight(t.first()) : (this.choices_count() > 0 && this.results_hide(), this.result_clear_highlight()) : void 0 : this.results_show()
        }, n.prototype.keydown_backstroke = function () {
            var t;
            return this.pending_backstroke ? (this.choice_destroy(this.pending_backstroke.find("a").first()), this.clear_backstroke()) : (t = this.search_container.siblings("li.search-choice").last()).length && !t.hasClass("search-choice-disabled") ? (this.pending_backstroke = t, this.single_backstroke_delete ? this.keydown_backstroke() : this.pending_backstroke.addClass("search-choice-focus")) : void 0
        }, n.prototype.clear_backstroke = function () {
            return this.pending_backstroke && this.pending_backstroke.removeClass("search-choice-focus"), this.pending_backstroke = null
        }, n.prototype.search_field_scale = function () {
            var e, s, i, n, r, o, h;
            if (this.is_multiple) {
                for (r = {
                    position: "absolute",
                    left: "-1000px",
                    top: "-1000px",
                    display: "none",
                    whiteSpace: "pre"
                }, s = 0, i = (o = ["fontSize", "fontStyle", "fontWeight", "fontFamily", "lineHeight", "textTransform", "letterSpacing"]).length; s < i; s++) r[n = o[s]] = this.search_field.css(n);
                return (e = t("<div />").css(r)).text(this.get_search_field_value()), t("body").append(e), h = e.width() + 25, e.remove(), this.container.is(":visible") && (h = Math.min(this.container.outerWidth() - 10, h)), this.search_field.width(h)
            }
        }, n.prototype.trigger_form_field_change = function (t) {
            return this.form_field_jq.trigger("input", t), this.form_field_jq.trigger("change", t)
        }, n
    }()
}).call(this);

/* jquery ui datepicker ru locale */
!function (e) {
    "function" == typeof define && define.amd ? define(["../widgets/datepicker"], e) : e(jQuery.datepicker)
}(function (e) {
    return e.regional.ru = {
        closeText: "Закрыть",
        prevText: "&#x3C;Пред",
        nextText: "След&#x3E;",
        currentText: "Сегодня",
        monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
        monthNamesShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
        dayNames: ["воскресенье", "понедельник", "вторник", "среда", "четверг", "пятница", "суббота"],
        dayNamesShort: ["вск", "пнд", "втр", "срд", "чтв", "птн", "сбт"],
        dayNamesMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        weekHeader: "Нед",
        dateFormat: "dd.mm.yy",
        firstDay: 1,
        isRTL: !1,
        showMonthAfterYear: !1,
        yearSuffix: ""
    }, e.setDefaults(e.regional.ru), e.regional.ru
});

/*-----*/

/* MAIN JS START */
$(document).ready(function () {
    var isMobile = false;
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;
    if (isMobile) {
        $('body').addClass('mobile');
    }
    $('.fancy').fancybox();
    if ($('.invite_button').length > 0) {
        $('.invite_button').each(function () {
            $(this).fancybox({
                scrolling: "no",
                helpers: {
                    overlay: {
                        locked: true // try changing to true and scrolling around the page
                    }
                },
                beforeShow: function () {
                    $('#invite_popup select').not('.multiselect').each(function () {
                        $(this).selectBox({'keepInViewport': false, 'mobile': true});
                    });
                    $('#invite_place').on('change', function () {
                        var text = $(this).children("option:selected").attr('data-snippet');
                        $('.legend').text(text);
                    });
                    $('#invite_popup input[name=invite-date]').datepicker({minDate: 0, regional: "ru"});


                    $('#invite_popup input[name=invite-city]').on('change', function () {
                        $('#invite_place').selectBox('destroy');
                        $('#invite_place option[data-snippet]').remove();
                        $('.legend').text("");
                        $('#invite_place').selectBox({'keepInViewport': false, 'mobile': true});
                        $('#invite_place').selectBox('disable');
                        $.ajax({
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            cache: false,
                            url: 'clubs/list/' + encodeURIComponent($('#invite_popup input[name=invite-city]').val()),
                            success: function (result) {
                                if (result.length > 0) {
                                    $('#invite_place').selectBox('destroy');
                                    result.forEach(function (item, index) {
                                        $('#invite_place').append('<option value="' + item.club_id + '" data-snippet="' + item.address + '">' + item.title + '</option>');
                                    });
                                    $('#invite_place').selectBox({'keepInViewport': false, 'mobile': true});
                                }
                            },
                            error: function () {
                            }
                        });
                    });
                }
            });
        });
    }
    if ($('.book_table_button').length > 0) {
        $('.book_table_button').each(function () {
            $(this).fancybox({
                beforeShow: function () {
                    $('#booking_popup input[name=book-date]').datepicker({minDate: 0, regional: "ru"});
                }
            });
        });
    }
    $('input[name="tel"]').mask("+7 (999) 999-99-99");
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
        });
    */
    $('body').click(function (e) {
        $('.header_personal_inner > ul > li > ul').removeClass('show');
    });
    //--
    //match_height
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
    //stars mechanics
    $('.stars span').wrap('<div></div>');
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

    $('.dynamic_stars').click(function (e) {
        var m_offset = $(this).offset();
        var mx = e.pageX - m_offset.left;
        var my = e.pageY - m_offset.top;
        selected_rating = figureOutStars($(this), mx);
        $(this).find('span').html(selected_rating.substr(-1, 1));
        $(this).parent().find('input[name="rating"]').val(selected_rating.substr(-1, 1))
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
    //elements rearrangement on mobile
    function mobileRearrangement() {
        if ($(window).width() < 767) {
            $('.chat_window_search').prependTo('.chat_window_head');
            $('<div class="chat_window_menu"></div>').insertAfter('.chat_window_head .chat_window_search');
        }
        else {
            $('.chat_window_search').prependTo('.chat_window_content_left');
        }
        if ($(window).width() < 640) {
            $('.news_block_div.partners_block_div .img a.button').each(function () {
                $(this).appendTo($(this).closest('.news_block_div.partners_block_div').children('.text'));
            });
            $('.bottom_buttons a.button.reject_button').each(function () {
                $(this).appendTo($(this).closest('.news_block_div.partners_block_div').children('.bottom_buttons'));
            });
            if ($('.inner_page_main.inner_page_main404').length) {
                $('.mobile_menu_toggler').hide();
            }
        }
    }
    mobileRearrangement();
    $(window).resize(function () {
        mobileRearrangement();
        $('.scrollable').customScrollbar();
    });
    //--
    $('.scrollable').customScrollbar();
    //chat_menu on mobile click
    $('body').on('click', '.chat_window_menu', function () {
        $('.chat_window').toggleClass('active');
        $('.chat_window_content_right').toggleClass('active');
        $(this).toggleClass('active');
    });
    //--
    //the tabs
    $('.the_tabs_div').not('.the_tabs_div.active').slideUp(200);
    $('.the_tabs_head a').click(function (e) {
        e.preventDefault();
        $('.the_tabs_div').removeClass('active').slideUp();
        $('.the_tabs_div:nth-child(' + (parseInt($(this).index('.the_tabs_head a')) + 1) + ')').addClass('active').slideDown();
        $('.the_tabs_head a').removeClass('active');
        $(this).addClass('active');
    });
    $('.forgot_password').click(function (e) {
        e.preventDefault();
        $('.the_tabs_div').removeClass('active').slideUp();
        $('.the_tabs_div:nth-child(3)').addClass('active').slideDown();
        $('.the_tabs_head a').removeClass('active');
        return false;
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
    //UI Range slider
    if ($('#slider-range-time').length) {
        $("input[name='time']").click(function (e) {
            e.stopPropagation();
            $("#slider-range-time-wrap").toggleClass('active');
        });
        $("body").click(function () {
            $("#slider-range-time-wrap").removeClass('active');
        });

        $("#slider-range-time").slider({
            range: true,
            min: 0,
            max: 24,
            values: [parseInt($('input[name="game_time_from"]').val()), parseInt($('input[name="game_time_to"]').val())],
            slide: function (event, ui) {
                $("input[name='time']").val("с " + ui.values[0] + " до " + ui.values[1] + " часов");
                $(this).parent().parent().find('input[name="game_time_from"]').val(ui.values[0]);
                $(this).parent().parent().find('input[name="game_time_to"]').val(ui.values[1]);
            }
        });
    }
    //UI Range slider
    if ($('#slider-range-time-2').length) {
        $("input[name='time2']").click(function (e) {
            e.stopPropagation();
            $("#slider-range-time-wrap-2").toggleClass('active');
        });
        $("body").click(function () {
            $("#slider-range-time-wrap-2").removeClass('active');
        });
        $("#slider-range-time-2").slider({
            range: true,
            min: 0,
            max: 24,
            values: [parseInt($('input[name="game_time_from"]').val()), parseInt($('input[name="game_time_to"]').val())],
            slide: function (event, ui) {
                $("input[name='time2']").val("с " + ui.values[0] + " до " + ui.values[1] + " часов");
                $(this).parent().parent().find('input[name="game_time_from"]').val(ui.values[0]);
                $(this).parent().parent().find('input[name="game_time_to"]').val(ui.values[1]);
            }
        });
    }
    //--
    if ($('div:not(.switcher) > input[type="radio"]').length) {
        $('input[type="radio"]').ezMark();
    }
    if ($('input[type="checkbox"]').length) {
        $('input[type="checkbox"]').ezMark();
    }
    if ($('select').length) {
        $('select').not('.multiselect').each(function () {
            if (!$(this).closest('.the_form').parent().is('.popup')) {
                $(this).selectBox({'keepInViewport': false, 'mobile': true});
            }
        });
    }
    if ($('.multiselect').length) {
        //$('.multiselect').chosen({disable_search_threshold: 15});
        $('.multiselect').each(function () {
            $(this).multiselect({
                search: false,
                columns: 1,
                texts: {
                    placeholder: $(this).data('placeholder'), // text to use in dummy input
                    search: 'Поиск',		 // search input placeholder text
                    selectedOptions: ' выбрано',	  // selected suffix text
                    selectAll: 'Select all',	 // select all text
                    unselectAll: 'Unselect all',   // unselect all text
                    noneSelected: 'не выбрано'   // None selected text
                },
                minHeight: 0,
            });
        });
    }
    //autocomplete
    // if ($('.city-autocomplete').length > 0) {
    //     function fillInAddress(object, autocomplete) {
    //         var place = autocomplete.getPlace();
    //         for (var i = 0; i < place.address_components.length; i++) {
    //             var addressType = place.address_components[i].types[0];
    //             switch (addressType) {
    //                 case "locality":
    //                     object.val(place.address_components[i].long_name);
    //                     if ($(object).parents('#invite_popup').length > 0) {
    //                         $(object).trigger('change');
    //                     }
    //                     break;
    //             }
    //         }
    //     }
    //
    //     function initAutocomplete() {
    //         $('.city-autocomplete').each(function () {
    //             var autocomplete;
    //             autocomplete = new google.maps.places.Autocomplete(($(this)), {types: ['geocode']});
    //             autocomplete.addListener('place_changed', fillInAddress($(this), autocomplete));
    //         });
    //     }
    //
    //     maps = document.createElement('script');
    //     maps.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAMj0tzo5dL6q5svRGhyCEYhMwqRcAtve4&amp;libraries=places&amp;callback=initAutocomplete';
    //     maps.async = true;
    //     document.getElementsByTagName('body')[0].appendChild(maps);
    // }

    //--
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
    var header_offset = ($('header').length > 0) ? ($('header').offset().top + 10) : 0;
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

    // //forms
    // $(".frm1").validate({  //проверка форм
    //     rules: {
    //         name: {
    //             required: true,
    //             minlength: 2
    //         },
    //         tel: {
    //             required: true,
    //             minlength: 2
    //         }
    //     },
    //     onkeyup: false,
    //     highlight: function (element, errorClass) {
    //         $(element).fadeOut(function () {
    //             $(element).fadeIn(function () {
    //                 $(element).fadeOut(function () {
    //                     $(element).fadeIn();
    //                 });
    //             });
    //         });
    //     },
    //     submitHandler: function (form) {
    //         $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
    //         $.fancybox({href: "#thanks_popup"});
    //         //отправка файла на сервер
    //         $$f({
    //             formid: 'frm11',//id формы
    //             url: 'sender.php'//адрес на серверный скрипт
    //         });
    //     },
    //     messages: {
    //         name: "",
    //         mail: "",
    //         comment: "",
    //         tel: ""
    //     }
    // });
    //
    // $(".frm2").validate({  //проверка форм
    //     rules: {
    //         name: {
    //             required: true,
    //             minlength: 2
    //         },
    //         tel: {
    //             required: true,
    //             minlength: 2
    //         }
    //     },
    //     onkeyup: false,
    //     highlight: function (element, errorClass) {
    //         $(element).fadeOut(function () {
    //             $(element).fadeIn(function () {
    //                 $(element).fadeOut(function () {
    //                     $(element).fadeIn();
    //                 });
    //             });
    //         });
    //     },
    //     submitHandler: function (form) {
    //         $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
    //         $.fancybox({href: "#thanks_popup"});
    //         //отправка файла на сервер
    //         $$f({
    //             formid: 'frm22',//id формы
    //             url: 'sender.php'//адрес на серверный скрипт
    //         });
    //     },
    //     messages: {
    //         name: "",
    //         mail: "",
    //         comment: "",
    //         tel: ""
    //     }
    // });
    //
    // $(".frm3").validate({  //проверка форм
    //     rules: {
    //         name: {
    //             required: true,
    //             minlength: 2
    //         },
    //         adress: {
    //             required: true,
    //             minlength: 2
    //         },
    //         mail: {
    //             required: true,
    //             minlength: 2
    //         },
    //         tel: {
    //             required: true,
    //             minlength: 2
    //         }
    //     },
    //     onkeyup: false,
    //     highlight: function (element, errorClass) {
    //         $(element).fadeOut(function () {
    //             $(element).fadeIn(function () {
    //                 $(element).fadeOut(function () {
    //                     $(element).fadeIn();
    //                 });
    //             });
    //         });
    //     },
    //     submitHandler: function (form) {
    //         $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
    //         $.fancybox({href: "#thanks_popup"});
    //         //отправка файла на сервер
    //         $$f({
    //             formid: 'frm22',//id формы
    //             url: 'sender.php'//адрес на серверный скрипт
    //         });
    //     },
    //     messages: {
    //         name: "",
    //         mail: "",
    //         adress: "",
    //         tel: ""
    //     }
    // });
    //
    // $(".frm01").validate({  //проверка форм
    //     rules: {
    //         name: {
    //             required: true,
    //             minlength: 2
    //         },
    //         tel: {
    //             required: true,
    //             minlength: 2
    //         }
    //     },
    //     onkeyup: false,
    //     highlight: function (element, errorClass) {
    //         $(element).fadeOut(function () {
    //             $(element).fadeIn(function () {
    //                 $(element).fadeOut(function () {
    //                     $(element).fadeIn();
    //                 });
    //             });
    //         });
    //     },
    //     submitHandler: function (form) {
    //         $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
    //         $.fancybox({href: "#thanks_popup"});
    //         //отправка файла на сервер
    //         $$f({
    //             formid: 'frm011',//id формы
    //             url: 'sender.php'//адрес на серверный скрипт
    //         });
    //     },
    //     messages: {
    //         name: "",
    //         mail: "",
    //         comment: "",
    //         tel: ""
    //     }
    // });
    //
    // $(".frm02").validate({  //проверка форм
    //     rules: {
    //         name: {
    //             required: true,
    //             minlength: 2
    //         },
    //         tel: {
    //             required: true,
    //             minlength: 2
    //         }
    //     },
    //     onkeyup: false,
    //     highlight: function (element, errorClass) {
    //         $(element).fadeOut(function () {
    //             $(element).fadeIn(function () {
    //                 $(element).fadeOut(function () {
    //                     $(element).fadeIn();
    //                 });
    //             });
    //         });
    //     },
    //     submitHandler: function (form) {
    //         $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
    //         $.fancybox({href: "#thanks_popup"});
    //         //отправка файла на сервер
    //         $$f({
    //             formid: 'frm022',//id формы
    //             url: 'sender.php'//адрес на серверный скрипт
    //         });
    //     },
    //     messages: {
    //         name: "",
    //         mail: "",
    //         comment: "",
    //         tel: ""
    //     }
    // });
    //
    // $(".frm03").validate({  //проверка форм
    //     rules: {
    //         name: {
    //             required: true,
    //             minlength: 2
    //         },
    //         tel: {
    //             required: true,
    //             minlength: 2
    //         }
    //     },
    //     onkeyup: false,
    //     highlight: function (element, errorClass) {
    //         $(element).fadeOut(function () {
    //             $(element).fadeIn(function () {
    //                 $(element).fadeOut(function () {
    //                     $(element).fadeIn();
    //                 });
    //             });
    //         });
    //     },
    //     submitHandler: function (form) {
    //         $(form).find('input[type="submit"]').addClass('done').attr('disabled', 'disabled');
    //         $.fancybox({href: "#thanks_popup"});
    //         //отправка файла на сервер
    //         $$f({
    //             formid: 'frm033',//id формы
    //             url: 'sender.php'//адрес на серверный скрипт
    //         });
    //     },
    //     messages: {
    //         name: "",
    //         mail: "",
    //         comment: "",
    //         tel: ""
    //     }
    // });

});

/* MAIN JS END */