"use strict";var flg="0";function menu_click(){for(var e=document.querySelectorAll(".pc-navbar li"),t=0;t<e.length;t++)e[t].removeEventListener("click",function(){});for(e=document.querySelectorAll(".pc-navbar li:not(.pc-trigger) .pc-submenu"),t=0;t<e.length;t++)e[t].style.display="none";for(var r=document.querySelectorAll(".pc-navbar > li:not(.pc-caption).pc-hasmenu"),o=0;o<r.length;o++)r[o].addEventListener("click",function(e){e.stopPropagation();var t=e.target;if((t="SPAN"==t.tagName?t.parentNode:t).parentNode.classList.contains("pc-trigger"))t.parentNode.classList.remove("pc-trigger"),slideUp(t.parentNode.children[1],200),window.setTimeout(()=>{t.parentNode.children[1].removeAttribute("style"),t.parentNode.children[1].style.display="none"},200);else{for(var r=document.querySelectorAll("li.pc-trigger"),o=0;o<r.length;o++){var a=r[o];a.classList.remove("pc-trigger"),slideUp(a.children[1],200),window.setTimeout(()=>{a.children[1].removeAttribute("style"),a.children[1].style.display="none"},200)}t.parentNode.classList.add("pc-trigger"),t.children[1]&&slideDown(t.parentNode.children[1],200)}});document.querySelector(".navbar-content")&&new SimpleBar(document.querySelector(".navbar-content"));for(var a=document.querySelectorAll(".pc-navbar > li:not(.pc-caption) li.pc-hasmenu"),o=0;o<a.length;o++)a[o].addEventListener("click",function(e){var t=e.target;if("SPAN"==t.tagName&&(t=t.parentNode),e.stopPropagation(),t.parentNode.classList.contains("pc-trigger"))t.parentNode.classList.remove("pc-trigger"),slideUp(t.parentNode.children[1],200);else{for(var r=t.parentNode.parentNode.children,o=0;o<r.length;o++){var a=r[o];a.classList.remove("pc-trigger"),(a="LI"==a.tagName?a.children[0]:a).parentNode.classList.contains("pc-hasmenu")&&slideUp(a.parentNode.children[1],200)}t.parentNode.classList.add("pc-trigger");e=t.parentNode.children[1];e&&(e.removeAttribute("style"),slideDown(e,200))}})}function setLayout(){var e,t=localStorage.getItem("layout");main_layout_change(t),null!==t&&""!==t&&(e=document.createElement("script"),"horizontal"===t?(document.querySelector(".pc-sidebar").classList.add("d-none"),e.src="assets/js/layout-horizontal.js",document.body.appendChild(e)):"color-header"===t?document.querySelector(".pc-sidebar .m-header .logo-lg")&&document.querySelector(".pc-sidebar .m-header .logo-lg").setAttribute("src","assets/images/logo-white.html"):"compact"===t?(e.src="assets/js/layout-compact.js",document.body.appendChild(e)):"tab"===t&&(e.src="assets/js/layout-tab.js",document.body.appendChild(e))),null===t&&(main_layout_change("vertical"),localStorage.setItem("layout","vertical"))}function add_scroller(){menu_click(),document.querySelector(".navbar-content")&&new SimpleBar(document.querySelector(".navbar-content"))}function rm_menu(){document.querySelector(".pc-sidebar")&&document.querySelector(".pc-sidebar").classList.remove("mob-sidebar-active"),document.querySelector(".topbar")&&document.querySelector(".topbar").classList.remove("mob-sidebar-active"),document.querySelector(".pc-sidebar .pc-menu-overlay").remove(),document.querySelector(".topbar .pc-menu-overlay")&&document.querySelector(".topbar .pc-menu-overlay").remove()}function remove_overlay_menu(){document.querySelector(".pc-sidebar").classList.remove("pc-over-menu-active"),document.querySelector(".topbar")&&document.querySelector(".topbar").classList.remove("mob-sidebar-active"),document.querySelector(".pc-sidebar .pc-menu-overlay").remove(),document.querySelector(".topbar .pc-menu-overlay").remove()}document.addEventListener("DOMContentLoaded",menu_click),document.addEventListener("DOMContentLoaded",function(){feather.replace(),(!document.querySelector("body").hasAttribute("data-pc-layout")||"horizontal"==document.querySelector("body").getAttribute("data-pc-layout")&&window.innerWidth<=1024)&&add_scroller();var e=document.querySelector("#mobile-collapse"),e=(e&&e.addEventListener("click",function(){document.querySelector(".pc-sidebar")&&(document.querySelector(".pc-sidebar").classList.contains("mob-sidebar-active")?rm_menu():(document.querySelector(".pc-sidebar").classList.add("mob-sidebar-active"),document.querySelector(".pc-sidebar").insertAdjacentHTML("beforeend",'<div class="pc-menu-overlay"></div>'),document.querySelector(".pc-menu-overlay").addEventListener("click",function(){rm_menu()})))}),document.querySelector(".header-notification-scroll")&&new SimpleBar(document.querySelector(".header-notification-scroll")),document.querySelector(".profile-notification-scroll")&&new SimpleBar(document.querySelector(".profile-notification-scroll")),document.querySelector(".component-list-card .card-body")&&new SimpleBar(document.querySelector(".component-list-card .card-body")),document.querySelector("#sidebar-hide")),e=(e&&e.addEventListener("click",function(){document.querySelector(".pc-sidebar").classList.contains("pc-sidebar-hide")?document.querySelector(".pc-sidebar").classList.remove("pc-sidebar-hide"):document.querySelector(".pc-sidebar").classList.add("pc-sidebar-hide")}),document.querySelector(".trig-drp-search")&&document.querySelector(".trig-drp-search").addEventListener("shown.bs.dropdown",e=>{document.querySelector(".drp-search input").focus()}),setLayout(),document.querySelectorAll(".theme-main-layout")),t="vertical";e&&document.querySelectorAll(".theme-main-layout > a").forEach(function(e){e.addEventListener("click",function(){location.reload(),document.querySelectorAll(".theme-main-layout > a").forEach(function(e){e.classList.remove("active")}),this.classList.add("active"),t="horizontal"==this.getAttribute("data-value")?"horizontal":"compact"==this.getAttribute("data-value")?"compact":"tab"==this.getAttribute("data-value")?"tab":"color-header"==this.getAttribute("data-value")?"color-header":"vertical",localStorage.setItem("layout",t),setLayout()})})}),window.addEventListener("load",function(){[].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function(e){return new bootstrap.Tooltip(e)}),[].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function(e){return new bootstrap.Popover(e)}),[].slice.call(document.querySelectorAll(".toast")).map(function(e){return new bootstrap.Toast(e)});var e=document.querySelector(".loader-bg");e&&e.remove()});
window.update_active_menu = function() {
    console.log("Updating sidebar active menu...");
    // Clear existing active states and hidden submenus
    var activeItems = document.querySelectorAll(".pc-sidebar .pc-navbar li.active, .pc-sidebar .pc-navbar li.pc-trigger");
    for(var i=0; i<activeItems.length; i++) {
        activeItems[i].classList.remove("active","pc-trigger");
    }
    var subMenus = document.querySelectorAll(".pc-sidebar .pc-navbar .pc-submenu");
    for(var j=0; j<subMenus.length; j++) {
        subMenus[j].style.display="none";
    }

    // Normalized current URL (no trailing slash)
    var currentUrl = window.location.href.split(/[?#]/)[0].replace(/\/$/, "");
    console.log("Current URL:", currentUrl);

    // Apply new active states based on current URL
    var links = document.querySelectorAll(".pc-sidebar .pc-navbar a");
    for(var k=0; k<links.length; k++){
        var linkHref = links[k].href.replace(/\/$/, "");
        if (linkHref == currentUrl && "" != links[k].getAttribute("href")) {
            console.log("Found active link:", linkHref);
            var parentLi = links[k].parentNode;
            parentLi.classList.add("active");
            
            // Highlight parents
            var p1 = parentLi.parentNode.parentNode; // .pc-hasmenu
            if (p1 && p1.classList.contains('pc-hasmenu')) {
                p1.classList.add("pc-trigger", "active");
                var s1 = p1.querySelector('.pc-submenu');
                if (s1) s1.style.display = "block";
                
                var p2 = p1.parentNode.parentNode; // grandparent .pc-hasmenu
                if (p2 && p2.classList.contains('pc-hasmenu')) {
                    p2.classList.add("pc-trigger", "active");
                    var s2 = p2.querySelector('.pc-submenu');
                    if (s2) s2.style.display = "block";
                }
            }
        }
    }
};
window.update_active_menu(); // Call the function to set initial active menu

// Global Checkbox and Bulk Action Handers (Pjax-compatible via delegation)
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'selectAll') {
        var checkboxes = document.querySelectorAll('.product-checkbox');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = e.target.checked;
        }
        if (window.updateBulkActionsVisibility) window.updateBulkActionsVisibility();
    }
    if (e.target && e.target.classList.contains('product-checkbox')) {
        var checkboxes = document.querySelectorAll('.product-checkbox');
        var selectAll = document.getElementById('selectAll');
        if (selectAll) {
            var allChecked = true;
            var someChecked = false;
            for (var j = 0; j < checkboxes.length; j++) {
                if (checkboxes[j].checked) someChecked = true;
                else allChecked = false;
            }
            selectAll.checked = allChecked;
            selectAll.indeterminate = someChecked && !allChecked;
        }
        if (window.updateBulkActionsVisibility) window.updateBulkActionsVisibility();
    }
});

window.updateBulkActionsVisibility = function() {
    var checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
    var btnBulkDelete = document.querySelector('.btn-bulk-delete');
    if (btnBulkDelete) {
        btnBulkDelete.disabled = checkedCount === 0;
    }
};

document.addEventListener('click', function(e) {
    var bulkDeleteBtn = e.target.closest('.btn-bulk-delete');
    if (bulkDeleteBtn) {
        var checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        if (checkedBoxes.length === 0) return;

        if (window.Swal) {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Bạn đang chọn xóa ' + checkedBoxes.length + ' mục. Hành động này không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Vâng, xóa chúng!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.getElementById('bulk-delete-form');
                    var inputsContainer = document.getElementById('bulk-delete-inputs');
                    if (form && inputsContainer) {
                        inputsContainer.innerHTML = '';
                        checkedBoxes.forEach(function(cb) {
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = cb.value;
                            inputsContainer.appendChild(input);
                        });
                        form.submit();
                    }
                }
            });
        }
    }

    var deleteAllBtn = e.target.closest('.btn-delete-all');
    if (deleteAllBtn) {
        if (window.Swal) {
            Swal.fire({
                title: 'CẢNH BÁO NGUY HIỂM!',
                text: 'Bạn đang chọn XÓA TOÀN BỘ trong hệ thống. Hành động này KHÔNG THỂ HOÀN TÁC. Bạn có chắc chắn không?',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'XÓA TẤT CẢ',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.getElementById('delete-all-form');
                    if (form) form.submit();
                }
            });
        }
    }
});

for(var tc=document.querySelectorAll(".prod-likes .form-check-input"),t=0;t<tc.length;t++){var prod_like=tc[t];prod_like.addEventListener("change",function(e){var t=e.currentTarget;if(t.checked)t.parentNode.insertAdjacentHTML("beforeend",'<div class="pc-like"><div class="like-wrapper"><span><span class="pc-group"><span class="pc-dots"></span><span class="pc-dots"></span><span class="pc-dots"></span><span class="pc-dots"></span></span></span></div></div>'),t.parentNode.querySelector(".pc-like").classList.add("pc-like-animate"),setTimeout(function(){try{t.parentNode.querySelector(".pc-like").remove()}catch(e){console.error("Error removing like animation:",e)}},3e3);else try{t.parentNode.querySelector(".pc-like").remove()}catch(e){console.error("Error removing like animation:",e)}})}for(tc=document.querySelectorAll(".auth-main.v2 .img-brand"),t=0;t<tc.length;t++)tc[t].setAttribute("src","assets/images/logo-white.html");function main_layout_change(e){var t;document.getElementsByTagName("body")[0].setAttribute("data-pc-layout",e),document.querySelector(".pct-offcanvas")&&((t=document.querySelector(".theme-main-layout > a.active"))&&t.classList.remove("active"),t=document.querySelector(".theme-main-layout > a[data-value='"+e+"']"))&&t.classList.add("active")}function removeClassByPrefix(t,r){for(let e=0;e<t.classList.length;e++){var o=t.classList[e];o.startsWith(r)&&t.classList.remove(o)}}let slideUp=(e,t=0)=>{e.style.transitionProperty="height, margin, padding",e.style.transitionDuration=t+"ms",e.style.boxSizing="border-box",e.style.height=e.offsetHeight+"px",e.offsetHeight,e.style.overflow="hidden",e.style.height=0,e.style.paddingTop=0,e.style.paddingBottom=0,e.style.marginTop=0,e.style.marginBottom=0},slideDown=(e,t=0)=>{e.style.removeProperty("display");let r=window.getComputedStyle(e).display;"none"===r&&(r="block"),e.style.display=r;var o=e.offsetHeight;e.style.overflow="hidden",e.style.height=0,e.style.paddingTop=0,e.style.paddingBottom=0,e.style.marginTop=0,e.style.marginBottom=0,e.offsetHeight,e.style.boxSizing="border-box",e.style.transitionProperty="height, margin, padding",e.style.transitionDuration=t+"ms",e.style.height=o+"px",e.style.removeProperty("padding-top"),e.style.removeProperty("padding-bottom"),e.style.removeProperty("margin-top"),e.style.removeProperty("margin-bottom"),window.setTimeout(()=>{e.style.removeProperty("height"),e.style.removeProperty("overflow"),e.style.removeProperty("transition-duration"),e.style.removeProperty("transition-property")},t)};var slideToggle=(e,t=0)=>("none"===window.getComputedStyle(e).display?slideDown:slideUp)(e,t);