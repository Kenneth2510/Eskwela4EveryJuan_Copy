// const maxW = document.querySelector("#max-sidebar"),
//     minW = document.querySelector("#min-sidebar"),
//     hamb = document.querySelector("#hamb-but");

// hamb.addEventListener("click", () => {

// });
$('#instructor_profile').on('click', (e)=> {
    e.preventDefault();
    $("#profile").toggleClass("hidden");
})

$("#hamb-but").click(function () {
    $("#max-sidebar").toggleClass("hidden");
    $("#min-sidebar").toggleClass("block");

    $("#min-sidebar").toggleClass("hidden");

    $("#main-sidebar").toggleClass("w-60");

    $(".list-inside").children("li").toggleClass("px-2");
    $(".list-inside").children("li").toggleClass("px-10");
});

$("#prof-btn").click(function () {
    $("#profile").toggleClass("hidden");
});

$("#prof-open-btn").click(function () {
    $("#profile").toggleClass("hidden");
});

