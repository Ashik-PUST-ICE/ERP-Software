document.addEventListener("DOMContentLoaded", function () {

    const toggle = document.querySelector(".menu-toggle");
    const menu = document.querySelector(".nav-menu");
    const closeButton = document.querySelector(".close-button");

    toggle.addEventListener("click", () => {
        menu.classList.toggle("active");
        toggle.classList.toggle("active");
    });

    // Close menu when clicking on close button
    closeButton.addEventListener("click", () => {
        menu.classList.remove("active");
    });

    // Handle dropdown toggle on mobile
    const dropdownParents = document.querySelectorAll('.nav-menu .has-dropdown');
    
    dropdownParents.forEach(dropdown => {
        const dropdownLink = dropdown.querySelector('a');
        const dropdownMenu = dropdown.querySelector('.dropdown');
        
        if (dropdownLink && dropdownMenu) {
            dropdownLink.addEventListener('click', function(e) {
                // Only handle click on mobile (when menu is active)
                if (window.innerWidth <= 992 && menu.classList.contains('active')) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Toggle the dropdown
                    dropdown.classList.toggle('dropdown-open');
                }
            });
        }
    });

    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', function (e) {
            // Get the parent li element
            const parentLi = this.closest('li');
            
            // Don't close menu if clicking on dropdown parent (it toggles dropdown instead)
            if (parentLi && parentLi.classList.contains('has-dropdown')) {
                // Let the dropdown toggle handler handle this
                return;
            }

            // Close menu when clicking on a nav link
            menu.classList.remove("active");

            if (this.hash) {
                const target = document.querySelector(this.hash);
                if (target) {
                    e.preventDefault();
                    if (typeof ScrollSmoother !== "undefined" && ScrollSmoother.get()) {
                        ScrollSmoother.get().scrollTo(target, true, "top top");
                    } else if (typeof gsap !== "undefined" && gsap.to) {
                        gsap.to(window, {
                            scrollTo: { y: target, offsetY: 10 },
                            duration: 0.8,
                            ease: "power2.out"
                        });
                    } else {
                        target.scrollIntoView({ behavior: "smooth" });
                    }
                }
            }

        });
    });

    // Footer links: scroll to section when on landing page
    document.querySelectorAll('.footer-links a[href*="#"]').forEach(link => {
        link.addEventListener('click', function (e) {
            var hash = this.hash;
            if (hash) {
                var target = document.querySelector(hash);
                if (target) {
                    e.preventDefault();
                    if (typeof ScrollSmoother !== "undefined" && ScrollSmoother.get()) {
                        ScrollSmoother.get().scrollTo(target, true, "top top");
                    } else if (typeof gsap !== "undefined" && gsap.to) {
                        gsap.to(window, {
                            scrollTo: { y: target, offsetY: 10 },
                            duration: 0.8,
                            ease: "power2.out"
                        });
                    } else {
                        target.scrollIntoView({ behavior: "smooth" });
                    }
                }
            }
        });
    });


    /* STICKY HEADER */
    let lastScrollTop = 0;
    const header = document.querySelector(".lp-header");

    window.addEventListener("scroll", () => {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        let width = window.innerWidth;

        if (width > 768) {
            if (scrollTop > lastScrollTop) {
                header.classList.add("is-sticky");
            } else if (scrollTop <= 48) {
                header.classList.remove("is-sticky");
            }
        } else {
            if (scrollTop > lastScrollTop) {
                header.style.top = "-92px";
                header.classList.add("is-sticky");
            } else {
                header.style.top = "0";
                if (scrollTop <= 48) {
                    header.style.top = "0px";
                    header.classList.remove("is-sticky");
                }
            }
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }, { passive: true });
    /* STICKY HEADER END */

    // Testimonial Slider Start
    var swiper = new Swiper(".testimonialSwiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        speed: 1000,
        delay: 2000,
        // if we use the loop true that could be problem with 3.4 counting 
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        // autoplay: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: false,
        },
        breakpoints: {
            // When window width is >= 768px (Tablet)
            768: {
                slidesPerView: 2,
            },
            // When window width is >= 1024px (Desktop)
            1024: {
                slidesPerView: 2.4,
            },
            1200: {
                slidesPerView: 3.4,
            },
        }
    });
    // Testimonial Slider End 

    /* START CAMPAING CALENDER IMAGE ANIMATION */

    gsap.registerPlugin(ScrollTrigger);
    gsap.registerPlugin(ScrollToPlugin);

    // Only run animation on desktop (not mobile/tablet)
    let mm = gsap.matchMedia();

    mm.add("(min-width: 992px)", function () {

        let images = gsap.utils.toArray(".image-box");

        if (images.length <= 1) return;

        // Initial stacked positions
        images.slice(1).forEach((img, i) => {
            gsap.set(img, { y: 900 + (i * 100) });
        });

        let tl = gsap.timeline({
            scrollTrigger: {
                trigger: ".campaing-calender",
                start: "top+=200 top",
                end: "+=1000",
                scrub: true,
                pin: true
            }
        });

        // Animate all images dynamically
        images.slice(1).forEach((img, i) => {
            tl.to(img, {
                y: 85 + (i * 80),
                duration: 1
            });
        });

        ScrollTrigger.create({
            trigger: ".campaing-calender",
            start: "bottom bottom",
            onEnter: () => AOS.refresh(),
            onLeaveBack: () => AOS.refresh()
        });

    });
    /* START CAMPAING CALENDER IMAGE ANIMATION */

    /* AOS INITIALIZATION START */
    AOS.init({
        once: true,
        easing: "ease-out"
    });
    /* AOS INITIALIZATION END */


    /* SMOOTH SCROLLER START (GSAP) START */
    if (typeof ScrollSmoother !== "undefined") {
        const smoother = ScrollSmoother.create({
            wrapper: "#smooth-wrapper",
            content: "#smooth-content",
            smooth: 4,
            effects: false,
            normalizeScroll: true,
            smoothTouch: 0.1,
            ignoreMobileResize: true,
            speed: 0.5,
            onEnter: () => AOS.refresh(),
            onLeaveBack: () => AOS.refresh()
        });
    }
    /* SMOOTH SCROLLER START (GSAP) END */

    // Pin the banner content
    ScrollTrigger.create({
        trigger: ".lp-banner-area", // The section that holds everything
        start: "top top",           // Start pinning when the section hits the top
        end: "+=100%",              // Stay pinned for the duration of one viewport height
        pin: ".fixed-content-area",  // The specific element to lock
        pinSpacing: false,          // Crucial: allows other elements to scroll OVER it
        onUpdate: (self) => {
            // Optional: Fade out the content as you scroll for a smoother transition
            gsap.set(".lp-banner-content", { opacity: 1 - self.progress });
            // gsap.set(".bgimg img", { y: self.progress * 0 });
        }
    });
})  