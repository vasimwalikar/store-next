! function(e) {
    e(["jquery"], function(e) {
        return function() {
            function t(e, t, n) {
                return m({
                    type: O.error,
                    iconClass: g().iconClasses.error,
                    message: e,
                    optionsOverride: n,
                    title: t
                })
            }

            function n(t, n) {
                return t || (t = g()), v = e("#" + t.containerId), v.length ? v : (n && (v = l(t)), v)
            }

            function i(e, t, n) {
                return m({
                    type: O.info,
                    iconClass: g().iconClasses.info,
                    message: e,
                    optionsOverride: n,
                    title: t
                })
            }

            function o(e) {
                w = e
            }

            function s(e, t, n) {
                return m({
                    type: O.success,
                    iconClass: g().iconClasses.success,
                    message: e,
                    optionsOverride: n,
                    title: t
                })
            }

            function a(e, t, n) {
                return m({
                    type: O.warning,
                    iconClass: g().iconClasses.warning,
                    message: e,
                    optionsOverride: n,
                    title: t
                })
            }

            function r(e, t) {
                var i = g();
                v || n(i), c(e, i, t) || u(i)
            }

            function d(t) {
                var i = g();
                return v || n(i), t && 0 === e(":focus", t).length ? void h(t) : void(v.children().length && v.remove())
            }

            function u(t) {
                for (var n = v.children(), i = n.length - 1; i >= 0; i--) c(e(n[i]), t)
            }

            function c(t, n, i) {
                var o = i && i.force ? i.force : !1;
                return t && (o || 0 === e(":focus", t).length) ? (t[n.hideMethod]({
                    duration: n.hideDuration,
                    easing: n.hideEasing,
                    complete: function() {
                        h(t)
                    }
                }), !0) : !1
            }

            function l(t) {
                return v = e("<div/>").attr("id", t.containerId).addClass(t.positionClass).attr("aria-live", "polite").attr("role", "alert"), v.appendTo(e(t.target)), v
            }

            function f() {
                return {
                    tapToDismiss: !0,
                    toastClass: "toast",
                    containerId: "toast-container",
                    debug: !1,
                    showMethod: "fadeIn",
                    showDuration: 300,
                    showEasing: "swing",
                    onShown: void 0,
                    hideMethod: "fadeOut",
                    hideDuration: 1e3,
                    hideEasing: "swing",
                    onHidden: void 0,
                    extendedTimeOut: 1e3,
                    iconClasses: {
                        error: "toast-error",
                        info: "toast-info",
                        success: "toast-success",
                        warning: "toast-warning"
                    },
                    iconClass: "toast-info",
                    positionClass: "toast-bottom-right",
                    timeOut: 5e3,
                    titleClass: "toast-title",
                    messageClass: "toast-message",
                    target: "body",
                    closeHtml: '<button type="button">&times;</button>',
                    newestOnTop: !0,
                    preventDuplicates: !1,
                    progressBar: !1
                }
            }

            function p(e) {
                w && w(e)
            }

            function m(t) {
                function i() {
                    a(), d(), u(), c(), l(), r()
                }

                function o() {
                    E.hover(O, w), !D.onclick && D.tapToDismiss && E.click(m), D.closeButton && k && k.click(function(e) {
                        e.stopPropagation ? e.stopPropagation() : void 0 !== e.cancelBubble && e.cancelBubble !== !0 && (e.cancelBubble = !0), m(!0)
                    }), D.onclick && E.click(function() {
                        D.onclick(), m()
                    })
                }

                function s() {
                    E.hide(), E[D.showMethod]({
                        duration: D.showDuration,
                        easing: D.showEasing,
                        complete: D.onShown
                    }), D.timeOut > 0 && (y = setTimeout(m, D.timeOut), M.maxHideTime = parseFloat(D.timeOut), M.hideEta = (new Date).getTime() + M.maxHideTime, D.progressBar && (M.intervalId = setInterval(b, 10)))
                }

                function a() {
                    t.iconClass && E.addClass(D.toastClass).addClass(x)
                }

                function r() {
                    D.newestOnTop ? v.prepend(E) : v.append(E)
                }

                function d() {
                    t.title && (H.append(t.title).addClass(D.titleClass), E.append(H))
                }

                function u() {
                    t.message && (I.append(t.message).addClass(D.messageClass), E.append(I))
                }

                function c() {
                    D.closeButton && (k.addClass("toast-close-button").attr("role", "button"), E.prepend(k))
                }

                function l() {
                    D.progressBar && (B.addClass("toast-progress"), E.prepend(B))
                }

                function f(e, t) {
                    if (e.preventDuplicates) {
                        if (t.message === C) return !0;
                        C = t.message
                    }
                    return !1
                }

                function m(t) {
                    return !e(":focus", E).length || t ? (clearTimeout(M.intervalId), E[D.hideMethod]({
                        duration: D.hideDuration,
                        easing: D.hideEasing,
                        complete: function() {
                            h(E), D.onHidden && "hidden" !== j.state && D.onHidden(), j.state = "hidden", j.endTime = new Date, p(j)
                        }
                    })) : void 0
                }

                function w() {
                    (D.timeOut > 0 || D.extendedTimeOut > 0) && (y = setTimeout(m, D.extendedTimeOut), M.maxHideTime = parseFloat(D.extendedTimeOut), M.hideEta = (new Date).getTime() + M.maxHideTime)
                }

                function O() {
                    clearTimeout(y), M.hideEta = 0, E.stop(!0, !0)[D.showMethod]({
                        duration: D.showDuration,
                        easing: D.showEasing
                    })
                }

                function b() {
                    var e = (M.hideEta - (new Date).getTime()) / M.maxHideTime * 100;
                    B.width(e + "%")
                }
                var D = g(),
                    x = t.iconClass || D.iconClass;
                if ("undefined" != typeof t.optionsOverride && (D = e.extend(D, t.optionsOverride), x = t.optionsOverride.iconClass || x), !f(D, t)) {
                    T++, v = n(D, !0);
                    var y = null,
                        E = e("<div/>"),
                        H = e("<div/>"),
                        I = e("<div/>"),
                        B = e("<div/>"),
                        k = e(D.closeHtml),
                        M = {
                            intervalId: null,
                            hideEta: null,
                            maxHideTime: null
                        },
                        j = {
                            toastId: T,
                            state: "visible",
                            startTime: new Date,
                            options: D,
                            map: t
                        };
                    return i(), s(), o(), p(j), D.debug && console && console.log(j), E
                }
            }

            function g() {
                return e.extend({}, f(), b.options)
            }

            function h(e) {
                v || (v = n()), e.is(":visible") || (e.remove(), e = null, 0 === v.children().length && (v.remove(), C = void 0))
            }
            var v, w, C, T = 0,
                O = {
                    error: "error",
                    info: "info",
                    success: "success",
                    warning: "warning"
                },
                b = {
                    clear: r,
                    remove: d,
                    error: t,
                    getContainer: n,
                    info: i,
                    options: {},
                    subscribe: o,
                    success: s,
                    version: "2.1.1",
                    warning: a
                };
            return b
        }()
    })
}("function" == typeof define && define.amd ? define : function(e, t) {
    "undefined" != typeof module && module.exports ? module.exports = t(require("jquery")) : window.toastr = t(window.jQuery)
});
