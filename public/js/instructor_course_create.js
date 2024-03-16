$(document).ready(function () {
    var lessons = [];

    function displayLessons() {
        var newRow = ``;
        $("#lesson_body").empty();

        // Add id to the array
        for (var i = 0; i < lessons.length; i++) {
            if (!lessons[i]) {
                lessons[i] = {};
            }
            lessons[i]["id"] = i + 1;
        }

        for (let i = 0; i < lessons.length; i++) {
            var id = lessons[i]["id"];
            var lesson_name = lessons[i]["title_name"];
            var lesson_category = lessons[i]["category"];

            newRow += `
            <tr class="px-5 h-16 text-center text-l border-b-2 border-black">
                <td>${id}</td>
                <td><input class="item_lessonName border-2 border-black text-l px-3 py-1 rounded-l w-full" value="${lesson_name}" disabled></td>
                <td>
                    <select disabled name="add_category" class="item_category border-2 border-black text-l px-3 py-1 rounded-l w-full">
                        <option value="LESSON" ${
                            lesson_category === "LESSON" ? "selected" : ""
                        }>LESSON</option>
                        <option value="QUIZ" ${
                            lesson_category === "QUIZ" ? "selected" : ""
                        }>QUIZ</option>
                        <option value="ACTIVITY" ${
                            lesson_category === "ACTIVITY" ? "selected" : ""
                        }>ACTIVITY</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="w-full editBtn py-3 px-3 rounded-xl bg-seagreen hover:bg-green-900 hover:text-white">Edit</button>
                    <div class="editButtons hidden">
                        <button type="button" class="w-full editBtn_save py-3 px-3 rounded-xl bg-seagreen hover:bg-green-900 hover:text-white">Save</button>
                        <button type="button" class="w-full editBtn_cancel py-3 px-3 rounded-xl bg-gray-500 hover:bg-gray-900 hover:text-white">Cancel</button>
                        <button type="button" class="w-full deleteBtn py-3 px-3 rounded-xl bg-red-500 hover:bg-red-900 hover:text-white">Delete</button>
                    </div>
                </td>
            </tr>
            `;
        }

        $("#lesson_body").append(newRow);

        // Edit button click handler
        $(".editBtn").click(function () {
            $(this)
                .closest("tr")
                .find(".item_lessonName, .item_category")
                .prop("disabled", false);
            $(this).closest("tr").find(".editButtons").removeClass("hidden");
            $(this).closest("tr").find(".editBtn").prop("disabled", true);
            $(this).closest("tr").find(".editBtn").addClass("hidden");
        });

        // Save button click handler
        $(".editBtn_save").click(function () {
            var row = $(this).closest("tr");
            var lessonIndex = row.index();

            var updatedLesson = {
                id: row.find("td:nth-child(1)").text(),
                lesson_name: row.find(".item_lessonName").val(),
                category: row.find(".item_category").val(),
            };

            lessons[lessonIndex] = updatedLesson;
            // You can save the updated lessons array here if needed.

            row.find(".item_lessonName, .item_category").prop("disabled", true);
            row.find(".editButtons").addClass("hidden");
            row.find(".editBtn").prop("disabled", false);
            row.find(".editBtn").removeClass("hidden");
        });

        // Cancel button click handler
        $(".editBtn_cancel").click(function () {
            var row = $(this).closest("tr");
            row.find(".item_lessonName, .item_category").prop("disabled", true);
            row.find(".editButtons").addClass("hidden");
            row.find(".editBtn").prop("disabled", false);
            row.find(".editBtn").removeClass("hidden");
        });

        // Delete button click handler
        $(".deleteBtn").click(function () {
            var row = $(this).closest("tr");
            var lessonIndex = row.index();
            lessons.splice(lessonIndex, 1); // Remove the lesson from the array

            // Remove the row from the table
            row.remove();
            // You can save the updated lessons array here if needed.
        });
    }

    $("#addLesson_start").on("click", function (e) {
        e.preventDefault();

        $("#selectTypeParent").removeClass("hidden");
    });

    $("#selectTypeCloseBtn").on("click", (e) => {
        e.preventDefault();

        $("#selectTypeParent").addClass("hidden");
    });

    $("#selectTypeParent").on("click", (e) => {
        if (!$(e.target).is("#selectTypeChild")) {
            $("#selectTypeParent").toggleClass("hidden");
        }
    });

    $("#selectTypeChild").on("click", (e) => {
        e.stopPropagation();
    });

    $("#selectTypeConfirmBtn").on("click", function (e) {
        e.preventDefault();
        var add_new_form = ``;
        var chosen_category = "";

        // console.log(category);
        $("#addLesson_start").addClass("hidden");
        chosen_category = $("#modal_add_category").val();

        // console.log(category);
        if (chosen_category !== null) {
            $("#selectTypeParent").addClass("hidden");

            add_new_form = `<tr id="add_newInput" class="hidden border-b-2 border-black w-full">
            <td></td>
            <td>
                <input type="text" id="add_title" class="border-2 border-black py-1 rounded-l w-full">
            </td>
            <td>
                <select name="add_category" id="add_categoryInput" class="border-2 border-black py-1 rounded-l w-full">
                    <option value="LESSON">LESSON</option>
                    <option value="QUIZ">QUIZ</option>
                    <option value="ACTIVITY">ACTIVITY</option>
                </select>
            </td>
            <td>
                <button type="button" id="add_newInputConfirm" class=" py-3 px-3 rounded-xl bg-seagreen hover:bg-green-900 hover:text-white">
                    Confirm
                </button>
                <button type="button" id="add_newInputCancel" class=" py-3 px-3 rounded-xl bg-red-500 hover:bg-red-900 hover:text-white">
                    Cancel
                </button>
            </td>
        </tr>`;
            $("#lesson_body").append(add_new_form);

            // Get the selected category

            $("#add_categoryInput").val(chosen_category);

            $("#add_newInput").removeClass("hidden");
            $("#add_title").focus();
        }

        $("#add_newInputConfirm").on("click", function (e) {
            e.preventDefault();

            var titleName = $("#add_title").val();
            var toAdd_category = $("#add_categoryInput").val();

            // console.log(toAdd_category);
            if (titleName.trim() !== "" && toAdd_category !== null) {
                var newLesson = {
                    title_name: titleName,
                    category: toAdd_category,
                };

                lessons.push(newLesson);
                // console.log(lessons);
                titleName = "";
                $("#add_title").val("");
                $("#add_category").val("");

                $("#addLesson_start").removeClass("hidden");
                $("#add_newInput").addClass("hidden");
                $("#addLesson_start").removeClass("hidden");

                displayLessons();
            } else {
                alert("Please enter a Title");
            }
        });
    });

    $("#nextAddCourse").on("click", function (e) {
        e.preventDefault();

        $("#secondCreateCourse").removeClass("hidden");
        $("#firstCreateCourse").addClass("hidden");
    });

    $("#nextAddCourse2").on("click", function (e) {
        e.preventDefault();

        $("#secondCreateCourse").addClass("hidden");
        $("#firstCreateCourse").addClass("hidden");
        $("#thirdCreateCourse").removeClass("hidden");
    });

    $("#returnTo_first").on("click", function (e) {
        $("#secondCreateCourse").addClass("hidden");
        $("#firstCreateCourse").removeClass("hidden");
    });

    $("#prevAddCourse1").on("click", function () {
        $("#secondCreateCourse").addClass("hidden");
        $("#firstCreateCourse").removeClass("hidden");
    });

    $("#prevAddCourse2").on("click", function () {
        $("#secondCreateCourse").removeClass("hidden");
        $("#firstCreateCourse").addClass("hidden");
        $("#thirdCreateCourse").addClass("hidden");
    });

    function addSyllabus(course) {
        var course_id = course;

        if (lessons.length > 0) {
            for (let i = 0; i < lessons.length; i++) {
                var syllabus_container = {
                    course_id: course_id,
                    topic_id: lessons[i]["id"],
                    topic_title: lessons[i]["title_name"],
                    category: lessons[i]["category"],
                };

                console.log(syllabus_container);

                var csrfToken = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    type: "POST",
                    url: "/instructor/course/create/syllabus/" + course_id,
                    data: syllabus_container,
                    async: false,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {},
                });
            }
        }
    }

    $("#addCourse").submit(function (e) {
        e.preventDefault();

        var course_name = $("#course_name").val();
        var course_description = $("#course_description").val();
        var course_difficulty = $("#course_difficulty").val();

        if (
            course_name === "" ||
            course_description === "" ||
            course_difficulty === ""
        ) {
            alert("Please fill all fields");

            // Handle field validation errors (similar to your existing code)
            // ...
        } else {
            var formData = new FormData(this);
        $('#loaderModal').removeClass('hidden');
            $.ajax({
                type: "POST",
                url: "/instructor/courses/create",
                data: formData,
                contentType: false,
                processData: false,
                async: false,
                success: function (response) {
                    if (
                        response &&
                        response.course_id &&
                        response.redirect_url
                    ) {
                        // After creating the course, add syllabus
                        addSyllabus(response.course_id);

                        // Check if files are selected for upload
                        if ($("#courseFilesUpload")[0].files.length > 0) {
                            // Upload files after adding the syllabus
                            uploadFiles(response.course_id);
                        } else {
                            // If no files selected, redirect to the specified URL
                            
        $('#loaderModal').addClass('hidden');
                            window.location.href = response.redirect_url;
                        }
                    } else {
                        
        $('#loaderModal').addClass('hidden');
                        window.location.href = response.redirect_url;
                    }
                },
            });
        }
    });

    function uploadFiles(courseId) {
        var formData = new FormData();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        // Append each selected file to the FormData object
        var files = $("#courseFilesUpload")[0].files;
        for (var i = 0; i < files.length; i++) {
            formData.append("file", files[i]);
        }

        // Append additional data if needed
        formData.append("course_id", courseId);

        // Make an Ajax request to upload files
        $.ajax({
            type: "POST",
            url: "/instructor/course/upload/files/" + courseId,
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {
                // Handle success, if needed
                console.log("Files uploaded successfully:", response);

                // Redirect to the specified URL after file upload
                window.location.href = response.redirect_url;
            },
            error: function (error) {
                // Handle errors, if needed
                console.error("Error uploading files:", error);
            },
        });
    }
});
