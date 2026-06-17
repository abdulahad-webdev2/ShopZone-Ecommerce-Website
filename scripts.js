function openMenu() {
    const sideMenu = document.getElementById("sideMenu");
    const overlay = document.getElementById("menuOverlay");

    if (sideMenu) {
        sideMenu.classList.add("active");
    }

    if (overlay) {
        overlay.classList.add("active");
    }

    document.body.classList.add("menu-open");
}

function closeMenu() {
    const sideMenu = document.getElementById("sideMenu");
    const overlay = document.getElementById("menuOverlay");

    if (sideMenu) {
        sideMenu.classList.remove("active");
    }

    if (overlay) {
        overlay.classList.remove("active");
    }

    document.body.classList.remove("menu-open");
}

function toggleSearchBox() {
    const searchBox = document.getElementById("searchBarBox");

    if (!searchBox) return;

    searchBox.classList.toggle("active");

    const input = searchBox.querySelector("input");

    if (searchBox.classList.contains("active") && input) {
        setTimeout(function () {
            input.focus();
        }, 100);
    }
}

document.addEventListener("click", function (event) {
    const searchBox = document.getElementById("searchBarBox");
    const searchBtn = document.querySelector(".search-toggle-btn");

    if (!searchBox || !searchBtn) return;

    const clickedInsideSearch = searchBox.contains(event.target);
    const clickedSearchButton = searchBtn.contains(event.target);

    if (!clickedInsideSearch && !clickedSearchButton) {
        searchBox.classList.remove("active");
    }
});

document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeMenu();

        const searchBox = document.getElementById("searchBarBox");
        const footerInfoBox = document.getElementById("footerInfoBox");

        if (searchBox) {
            searchBox.classList.remove("active");
        }

        if (footerInfoBox) {
            footerInfoBox.classList.remove("active");
        }
    }
});

/* FOOTER INFO BUTTONS */

function showFooterInfo(type) {
    const box = document.getElementById("footerInfoBox");
    const title = document.getElementById("footerInfoTitle");
    const text = document.getElementById("footerInfoText");

    if (!box || !title || !text) return;

    const info = {
        shipping: {
            title: "Shipping Charges",
            text: "ShopZone offers reliable delivery across Pakistan. Shipping charges start from Rs. 130 and may vary depending on your location, product weight, and order size."
        },

        track: {
            title: "Track Your Order",
            text: "To track your order, contact our support team on WhatsApp at +92 343 5247548. Please share your name, order details, or product information for quick assistance."
        },

        stores: {
            title: "Find Stores",
            text: "ShopZone currently works as an online shopping platform. You can explore our complete collection online and contact us anytime for product details and order support."
        },

        help: {
            title: "Need Help?",
            text: "Our support team is here to help you with orders, product details, delivery updates, returns, and general questions. Contact us on WhatsApp: +92 343 5247548 or email: kabdulahad576@gmail.com."
        },

        faqs: {
            title: "Frequently Asked Questions",
            text: "You can place an order by selecting a product and adding it to your cart. For order confirmation, delivery updates, product questions, or support, contact ShopZone through WhatsApp or email."
        },

        terms: {
            title: "Terms & Conditions",
            text: "By using ShopZone, you agree to provide correct order information and follow our shopping policies. Product availability, prices, discounts, and offers may change without prior notice."
        },

        privacy: {
            title: "Privacy Policy",
            text: "ShopZone respects your privacy. Customer information such as name, contact number, email, and order details is used only for order processing, delivery, and customer support."
        },

        disclaimer: {
            title: "Disclaimer",
            text: "Product colors, sizes, and designs may slightly vary due to lighting, screen settings, or photography. ShopZone tries its best to provide accurate product information and images."
        },

        contact: {
            title: "Contact Us",
            text: "WhatsApp: +92 343 5247548 | Email: kabdulahad576@gmail.com | Instagram: @a_ahad2428. You can contact us anytime for product information, order details, and customer support."
        },

        about: {
            title: "About ShopZone",
            text: "Welcome to ShopZone, your trusted destination for quality products at unbeatable prices. Founded by Abdul Ahad, ShopZone is dedicated to bringing you a smooth, secure, and enjoyable online shopping experience. From fashion and electronics to daily essentials, we offer everything you need in one place. Our mission is to provide top-quality products, fast delivery, and excellent customer service to make every shopping experience convenient and satisfying."
        },

        blogs: {
            title: "Blogs",
            text: "Explore fashion tips, shopping guides, seasonal trends, outfit ideas, product care tips, and online shopping guidance through ShopZone blogs. New content will be added soon."
        },

        cloth: {
            title: "Cloth Care",
            text: "To keep your clothes fresh and long-lasting, wash with mild detergent, avoid harsh bleach, dry in shade, and iron according to the fabric type. Always read the care label before washing."
        },

        newsletter: {
            title: "Newsletter",
            text: "Thank you for your interest in ShopZone updates. You will receive news about latest products, discounts, seasonal collections, and special offers."
        }
    };

    if (!info[type]) return;

    title.textContent = info[type].title;
    text.textContent = info[type].text;

    box.classList.add("active");

    box.scrollIntoView({
        behavior: "smooth",
        block: "center"
    });
}

function closeFooterInfo() {
    const box = document.getElementById("footerInfoBox");

    if (box) {
        box.classList.remove("active");
    }
}

/* ACTIVE NAV + BACK TO TOP */

document.addEventListener("DOMContentLoaded", function () {
    const currentPage = window.location.pathname.split("/").pop();
    const currentUrl = window.location.href;

    document.querySelectorAll(".category-nav a, .side-menu a").forEach(function (link) {
        const linkHref = link.href;
        const linkPage = link.getAttribute("href");

        if (linkHref === currentUrl || linkPage === currentPage) {
            link.classList.add("active");
        }
    });

    const backToTopBtn = document.querySelector(".k-top-float");

    if (backToTopBtn) {
        backToTopBtn.addEventListener("click", function (e) {
            e.preventDefault();

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }
});

/* OLD HOME SLIDER SUPPORT */

let slideIndex = 0;
let slides = [];
let dots = [];
let sliderTimer = null;

function showSlide(index) {
    if (slides.length === 0) return;

    slides.forEach(function (slide) {
        slide.classList.remove("active");
    });

    dots.forEach(function (dot) {
        dot.classList.remove("active");
    });

    if (index >= slides.length) {
        slideIndex = 0;
    }

    if (index < 0) {
        slideIndex = slides.length - 1;
    }

    slides[slideIndex].classList.add("active");

    if (dots[slideIndex]) {
        dots[slideIndex].classList.add("active");
    }
}

function changeSlide(step) {
    slideIndex += step;
    showSlide(slideIndex);
    restartSlider();
}

function currentSlide(index) {
    slideIndex = index;
    showSlide(slideIndex);
    restartSlider();
}

function startSlider() {
    if (slides.length === 0) return;

    sliderTimer = setInterval(function () {
        slideIndex++;
        showSlide(slideIndex);
    }, 4500);
}

function restartSlider() {
    if (sliderTimer) {
        clearInterval(sliderTimer);
    }

    startSlider();
}

document.addEventListener("DOMContentLoaded", function () {
    slides = document.querySelectorAll(".home-slider .slide");
    dots = document.querySelectorAll(".home-slider .dot");

    if (slides.length > 0) {
        showSlide(slideIndex);
        startSlider();
    }
});