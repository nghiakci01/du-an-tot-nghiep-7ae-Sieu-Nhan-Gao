"use strict";

var rtl_flag = !1, dark_flag = !1;

// Track the matchMedia listener for auto mode
var _autoThemeMediaQuery = null;
var _autoThemeListener = null;

// ── Internal helpers ──────────────────────────────────────────────────────────

function _removeAutoListener() {
    if (_autoThemeMediaQuery && _autoThemeListener) {
        _autoThemeMediaQuery.removeEventListener("change", _autoThemeListener);
        _autoThemeMediaQuery = null;
        _autoThemeListener = null;
    }
}

function updateLogo(selector, src) {
    var el = document.querySelector(selector);
    if (el) el.setAttribute("src", src);
}

function updateActiveButton(selector) {
    var active = document.querySelector(".theme-layout .btn.active");
    if (active) active.classList.remove("active");
    var target = document.querySelector(selector);
    if (target) target.classList.add("active");
}

// ── Public theme API ──────────────────────────────────────────────────────────

/**
 * Apply a concrete theme (light|dark) to the DOM.
 * Does NOT touch localStorage — saving is the responsibility of callers who
 * represent intentional user actions (click handlers, restore-from-storage).
 */
function layout_change(mode) {
    _removeAutoListener();

    var body = document.getElementsByTagName("body")[0];
    if (body) body.setAttribute("data-pc-theme", mode);

    // Ensure the auto button is not marked active
    var autoBtn = document.querySelector('.theme-layout .btn[data-value="default"]');
    if (autoBtn) autoBtn.classList.remove("active");

    if ("dark" === mode) {
        dark_flag = !0;
        updateLogo(".pc-sidebar .m-header .logo-lg", "/admin-assets/images/logo-white.svg");
        updateLogo(".navbar-brand .logo-lg", "/admin-assets/images/logo-white.svg");
        updateLogo(".auth-main.v1 .auth-sidefooter img", "/admin-assets/images/logo-white.svg");
        updateLogo(".footer-top .footer-logo", "/admin-assets/images/logo-white.svg");
        updateActiveButton('.theme-layout .btn[data-value="false"]');
    } else {
        dark_flag = !1;
        updateLogo(".pc-sidebar .m-header .logo-lg", "/admin-assets/images/logo-dark.svg");
        updateLogo(".navbar-brand .logo-lg", "/admin-assets/images/logo-dark.svg");
        updateLogo(".auth-main.v1 .auth-sidefooter img", "/admin-assets/images/logo-dark.svg");
        updateLogo(".footer-top .footer-logo", "/admin-assets/images/logo-dark.svg");
        updateActiveButton('.theme-layout .btn[data-value="true"]');
    }
}

/**
 * Activate auto / system-preference mode.
 * Saves "default" to localStorage, applies system theme immediately,
 * and listens for future OS-level changes.
 */
function layout_change_default() {
    _removeAutoListener();

    // Persist preference
    if ("undefined" !== typeof Storage) {
        localStorage.setItem("theme", "default");
    }

    // Mark only the auto button as active
    var allBtns = document.querySelectorAll(".theme-layout .btn");
    allBtns.forEach(function (btn) { btn.classList.remove("active"); });
    var autoBtn = document.querySelector('.theme-layout .btn[data-value="default"]');
    if (autoBtn) autoBtn.classList.add("active");

    // Apply current system preference
    var mq = window.matchMedia("(prefers-color-scheme: dark)");
    var isDark = mq.matches;
    var body = document.getElementsByTagName("body")[0];
    if (body) body.setAttribute("data-pc-theme", isDark ? "dark" : "light");

    if (isDark) {
        dark_flag = !0;
        updateLogo(".pc-sidebar .m-header .logo-lg", "/admin-assets/images/logo-white.svg");
        updateLogo(".navbar-brand .logo-lg", "/admin-assets/images/logo-white.svg");
        updateLogo(".auth-main.v1 .auth-sidefooter img", "/admin-assets/images/logo-white.svg");
        updateLogo(".footer-top .footer-logo", "/admin-assets/images/logo-white.svg");
    } else {
        dark_flag = !1;
        updateLogo(".pc-sidebar .m-header .logo-lg", "/admin-assets/images/logo-dark.svg");
        updateLogo(".navbar-brand .logo-lg", "/admin-assets/images/logo-dark.svg");
        updateLogo(".auth-main.v1 .auth-sidefooter img", "/admin-assets/images/logo-dark.svg");
        updateLogo(".footer-top .footer-logo", "/admin-assets/images/logo-dark.svg");
    }

    // Register listener for future OS-level changes
    _autoThemeMediaQuery = mq;
    _autoThemeListener = function (e) {
        var body = document.getElementsByTagName("body")[0];
        if (body) body.setAttribute("data-pc-theme", e.matches ? "dark" : "light");
        if (e.matches) {
            dark_flag = !0;
            updateLogo(".pc-sidebar .m-header .logo-lg", "/admin-assets/images/logo-white.svg");
            updateLogo(".navbar-brand .logo-lg", "/admin-assets/images/logo-white.svg");
            updateLogo(".auth-main.v1 .auth-sidefooter img", "/admin-assets/images/logo-white.svg");
            updateLogo(".footer-top .footer-logo", "/admin-assets/images/logo-white.svg");
        } else {
            dark_flag = !1;
            updateLogo(".pc-sidebar .m-header .logo-lg", "/admin-assets/images/logo-dark.svg");
            updateLogo(".navbar-brand .logo-lg", "/admin-assets/images/logo-dark.svg");
            updateLogo(".auth-main.v1 .auth-sidefooter img", "/admin-assets/images/logo-dark.svg");
            updateLogo(".footer-top .footer-logo", "/admin-assets/images/logo-dark.svg");
        }
    };
    _autoThemeMediaQuery.addEventListener("change", _autoThemeListener);
}

// ── Restore saved preference on page load ─────────────────────────────────────
// DOMContentLoaded fires AFTER inline <script> tags at the bottom of <body>,
// so this always runs last and correctly overrides the server-side default.
document.addEventListener("DOMContentLoaded", function () {
    if ("undefined" !== typeof Storage) {
        var saved = localStorage.getItem("theme");
        if (saved === "default") {
            layout_change_default();
        } else if (saved === "dark" || saved === "light") {
            layout_change(saved);
        }
        // If nothing is saved, keep the server-side default (already applied by inline script)
    } else {
        console.warn("Web Storage API is not supported in this browser.");
    }
});

// ── Click handlers for theme switcher buttons ─────────────────────────────────
// These run at parse time; the loop body executes synchronously so any buttons
// already in the DOM will be wired up. Buttons that arrive later (e.g. via
// the offcanvas) also fire the handler because we listen on the button itself.
document.addEventListener("DOMContentLoaded", function () {
    var buttons = document.querySelectorAll(".theme-layout .btn");
    buttons.forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.stopPropagation();

            var target = e.target;
            while (target && !target.hasAttribute("data-value")) {
                target = target.parentNode;
            }
            if (!target) return;

            var val = target.getAttribute("data-value");
            if (val === "default") {
                layout_change_default(); // saves "default" to localStorage inside
            } else if (val === "true") {
                layout_change("light");
                localStorage.setItem("theme", "light");
            } else {
                layout_change("dark");
                localStorage.setItem("theme", "dark");
            }
        });
    });
});

// ── Other layout helpers ──────────────────────────────────────────────────────

function layout_theme_contrast_change(e) {
    var body = document.getElementsByTagName("body")[0];
    var active = document.querySelector(".theme-contrast .btn.active");
    var target = document.querySelector(".theme-contrast .btn[data-value='" + e + "']");
    if (body) body.setAttribute("data-pc-theme_contrast", e);
    if (active) active.classList.remove("active");
    if (target) target.classList.add("active");
}

function layout_caption_change(e) {
    var body = document.getElementsByTagName("body")[0];
    if (body) body.setAttribute("data-pc-sidebar-caption", e);
    var activeBtn = document.querySelector(".theme-nav-caption .btn.active");
    var targetBtn = document.querySelector(".theme-nav-caption .btn[data-value='" + e + "']");
    if (activeBtn) activeBtn.classList.remove("active");
    if (targetBtn) targetBtn.classList.add("active");
}

function preset_change(e) {
    var body = document.getElementsByTagName("body")[0];
    if (body) body.setAttribute("data-pc-preset", e);
    var activePreset = document.querySelector(".preset-color > a.active");
    var targetPreset = document.querySelector(".preset-color > a[data-value='" + e + "']");
    if (activePreset) activePreset.classList.remove("active");
    if (targetPreset) targetPreset.classList.add("active");
}

function layout_rtl_change(e) {
    var body = document.getElementsByTagName("body")[0];
    var html = document.getElementsByTagName("html")[0];
    var activeBtn = document.querySelector(".theme-direction .btn.active");
    if ("true" === e) {
        rtl_flag = !0;
        if (body) body.setAttribute("data-pc-direction", "rtl");
        if (html) { html.setAttribute("dir", "rtl"); html.setAttribute("lang", "ar"); }
        if (activeBtn) activeBtn.classList.remove("active");
        var t = document.querySelector(".theme-direction .btn[data-value='true']");
        if (t) t.classList.add("active");
    } else {
        rtl_flag = !1;
        if (body) body.setAttribute("data-pc-direction", "ltr");
        if (html) { html.removeAttribute("dir"); html.removeAttribute("lang"); }
        if (activeBtn) activeBtn.classList.remove("active");
        var t = document.querySelector(".theme-direction .btn[data-value='false']");
        if (t) t.classList.add("active");
    }
}

function change_box_container(e) {
    var content = document.querySelector(".pc-content");
    var footer = document.querySelector(".footer-wrapper");
    if (!content || !footer) return;
    var active = document.querySelector(".theme-container .btn.active");
    if ("true" === e) {
        content.classList.add("container");
        footer.classList.add("container");
        footer.classList.remove("container-fluid");
        if (active) active.classList.remove("active");
        var t = document.querySelector('.theme-container .btn[data-value="true"]');
        if (t) t.classList.add("active");
    } else {
        content.classList.remove("container");
        footer.classList.remove("container");
        footer.classList.add("container-fluid");
        if (active) active.classList.remove("active");
        var t = document.querySelector('.theme-container .btn[data-value="false"]');
        if (t) t.classList.add("active");
    }
}

function main_layout_change(e) {
    var body = document.getElementsByTagName("body")[0];
    if (body) body.setAttribute("data-pc-layout", e);
}

// ── DOMContentLoaded: preset colors, SimpleBar, layout reset ─────────────────

document.addEventListener("DOMContentLoaded", function () {
    // Preset color pickers
    var presets = document.querySelectorAll(".preset-color");
    presets.forEach(function (preset) {
        preset.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", function (e) {
                var target = e.target;
                while (target && !target.hasAttribute("data-value")) {
                    target = target.parentNode;
                }
                if (target) preset_change(target.getAttribute("data-value"));
            });
        });
    });

    // SimpleBar for customizer body
    if (document.querySelector(".pct-body") && typeof SimpleBar !== "undefined") {
        new SimpleBar(document.querySelector(".pct-body"));
    }

    // Layout reset button — clears all prefs then reloads
    var resetBtn = document.querySelector("#layoutreset");
    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            localStorage.clear();
            localStorage.setItem("layout", "vertical");
            location.reload();
        });
    }
});