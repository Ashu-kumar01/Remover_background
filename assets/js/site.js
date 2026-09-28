// Site-wide chrome: scroll-reveal animations, the before/after compare
// slider, and closing the mobile nav after a link is tapped. Kept separate
// from app.js, which only handles the background-removal tool itself.

(function scrollReveal() {
    const targets = document.querySelectorAll(".reveal");
    if (!targets.length) return;

    if (!("IntersectionObserver" in window)) {
        targets.forEach((el) => el.classList.add("in-view"));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("in-view");
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15, rootMargin: "0px 0px -40px 0px" }
    );

    targets.forEach((el) => observer.observe(el));
})();

(function compareSlider() {
    const slider = document.getElementById("compareSlider");
    if (!slider) return;

    const beforeImg = slider.querySelector(".compare-before");
    const afterImg = slider.querySelector(".compare-after");
    const handle = slider.querySelector(".compare-handle");
    let dragging = false;

    function setPosition(clientX) {
        const rect = slider.getBoundingClientRect();
        let pct = ((clientX - rect.left) / rect.width) * 100;
        pct = Math.max(0, Math.min(100, pct));
        // Both images are clipped to their own side of the divider so the
        // opaque "before" photo never sits behind the transparent parts of
        // "after" — otherwise it shows through instead of the checkerboard.
        // Before stays on the left, after on the right.
        beforeImg.style.clipPath = `inset(0 ${100 - pct}% 0 0)`;
        afterImg.style.clipPath = `inset(0 0 0 ${pct}%)`;
        handle.style.left = pct + "%";
        handle.setAttribute("aria-valuenow", Math.round(pct));
    }

    function onPointerMove(e) {
        if (!dragging) return;
        const x = e.touches ? e.touches[0].clientX : e.clientX;
        setPosition(x);
    }

    function stopDragging() {
        dragging = false;
    }

    handle.addEventListener("pointerdown", (e) => {
        dragging = true;
        handle.setPointerCapture(e.pointerId);
    });
    slider.addEventListener("pointerdown", (e) => {
        dragging = true;
        setPosition(e.clientX);
    });
    window.addEventListener("pointermove", onPointerMove);
    window.addEventListener("pointerup", stopDragging);
    window.addEventListener("pointercancel", stopDragging);

    handle.addEventListener("keydown", (e) => {
        const current = parseFloat(handle.style.left) || 50;
        if (e.key === "ArrowLeft") {
            e.preventDefault();
            setPosition(slider.getBoundingClientRect().left + (slider.getBoundingClientRect().width * Math.max(0, current - 5)) / 100);
        } else if (e.key === "ArrowRight") {
            e.preventDefault();
            setPosition(slider.getBoundingClientRect().left + (slider.getBoundingClientRect().width * Math.min(100, current + 5)) / 100);
        }
    });
})();

(function autoCloseMobileNav() {
    const collapseEl = document.getElementById("siteNavCollapse");
    if (!collapseEl || typeof bootstrap === "undefined") return;

    collapseEl.querySelectorAll(".nav-link, .btn-nav").forEach((link) => {
        link.addEventListener("click", () => {
            const instance = bootstrap.Collapse.getInstance(collapseEl);
            if (instance) instance.hide();
        });
    });
})();
