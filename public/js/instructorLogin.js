$(document).ready(function () {
    const showPass = $("#showPwd");
    const hidePass = $("#hidePwd");
    const pwd = $("#password");

    // const loginBtn = $("#loginBtn");
    // const loginForm = $("#loginForm");
    // const securityForm = $("#securityForm");

    const backBtn = $("#backBtn");

    showPass.on("click", function (event) {
        event.preventDefault();

        showPass.toggleClass("hidden");

        hidePass.toggleClass("hidden");
        pwd.prop("type", "text");
    });

    hidePass.on("click", function (event) {
        event.preventDefault();

        showPass.toggleClass("hidden");

        hidePass.toggleClass("hidden");

        pwd.prop("type", "password");
    });

    // loginBtn.on("click", function (event) {
    //     event.preventDefault();

    //     loginForm.addClass("hidden");
    //     securityForm.removeClass("hidden");
    // });

    // backBtn.on("click", function (event) {
    //     event.preventDefault();


        loginForm.removeClass("hidden");
        securityForm.addClass("hidden");
    });

    const imgSlides = $(".slides");
    const carouselBtn = $("#carouselBtn button");
    let crrntSlide = 0;
    let slideInterval;

    function initCarousel() {
        imgSlides.hide();
        imgSlides.eq(crrntSlide).show();
        carouselBtn.removeClass("bg-slate-500");
        carouselBtn.eq(crrntSlide).addClass("bg-slate-500");
    }

    function showSlide(index) {
        imgSlides.hide();
        imgSlides.eq(index).show();
        carouselBtn.removeClass("bg-slate-500");
        carouselBtn.eq(index).addClass("bg-slate-500");
        crrntSlide = index;
    }

    function nextSlide() {
        const nextSlide = (crrntSlide + 1) % imgSlides.length;
        showSlide(nextSlide);
    }

    function startSlideInterval() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function stopSlideInterval() {
        clearInterval(slideInterval);
    }

    // Automatically switch to the next slide every 5 seconds
    startSlideInterval();

    // Event listener for next button
    $("#l-nextBtn").click(function () {
        nextSlide();
        stopSlideInterval();
        startSlideInterval();
    });

    // Event listener for previous button
    $("#l-prevBtn").click(function () {
        const prevSlide =
            (crrntSlide - 1 + imgSlides.length) % imgSlides.length;
        showSlide(prevSlide);
        stopSlideInterval();
        startSlideInterval();
    });

    // Event listener for carousel buttons
    carouselBtn.each(function (index) {
        $(this).click(function () {
            showSlide(index);
            stopSlideInterval();
            startSlideInterval();
        });
    });

    // Initialize the carousel on page load
    initCarousel();
