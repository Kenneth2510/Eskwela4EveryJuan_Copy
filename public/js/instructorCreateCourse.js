$(document).ready(function () {
    $("#addNewCourse").on("click", function () {
        window.location.replace("/instructor/courses/create");
    });

    $("#nextAddCourse").on("click", function (event) {
        event.preventDefault();

        $("#firstCreateCourse").addClass("hidden");
        $("#secondCreateCourse").removeClass("hidden");
    });

    $("#backCreateCourse").on("click", function (event) {
        event.preventDefault();

        if ($("#firstCreateCourse").hasClass("hidden")) {
            $("#firstCreateCourse").removeClass("hidden");
            $("#secondCreateCourse").addClass("hidden");
        } else {
            window.location.replace("/instructor/courses");
        }
    });

    $("#fileInput").on("change", function () {
        var files = $("#fileInput").prop("files");
        let filename = $.map(files, (val) => {
            return "<li class='border-b-2'>" + val.name + "</li>";
        });

        console.log("Filename:");
        console.log(filename);

        $("#uploadedFileName").append(filename);
    });
});
