$(document).ready(() => {
    $("#lessonAddContent").on("click", (e) => {
        e.preventDefault();

        $("#lessonNewContent").removeClass("hidden");
    });

    $("#lessonNewContentCloseSVG, #lessonNewContentCloseBtn").on(
        "click",
        (e) => {
            e.preventDefault();

            $("#lessonNewContent").addClass("hidden");
        },
    );

    $("#lessonNewContent").on("click", (e) => {
        e.preventDefault();

        if (!$(e.target).is("#lessonChildContent")) {
            $("#lessonNewContent").toggleClass("hidden");
        }
    });

    $("#lessonChildContent").on("click", (e) => {
        e.stopPropagation();
    });
});
