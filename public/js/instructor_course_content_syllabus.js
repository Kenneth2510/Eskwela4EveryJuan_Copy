$(document).ready(function () {
    var syllabusData = {};
    var edit_prior = 0;

    $("#showSyllabusBtn").click(function () {
        // Toggle the visibility of the modal
        var courseID = $(this).data("course-id");
        $("#syllabusModal").toggleClass("hidden");
        getSyllabusData(courseID);
    });

    // Click event for the close button within the modal
    $("#syllabusModal .close").click(function () {
        // Toggle the visibility of the modal
        $("#syllabusModal").toggleClass("hidden");
    });

    function getSyllabusData(courseID) {
        // alert(courseID);

        $.ajax({
            type: "GET",
            url: "/instructor/course/content/" + courseID + "/json",
            dataType: "json",
            success: function (response) {
                console.log(response);
                // displaySyllabus(response['syllabus'])
                syllabusData = response["syllabus"];
                // console.log(syllabusData)
                reDisplaySyllabus(syllabusData);
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    // Update the displaySyllabus function to include the "Edit" button and "Save" and "Cancel" buttons
    // function displaySyllabus(syllabus) {
    //     var syllabusRowDisp = ``;

    //     for (let i = 0; i < syllabus.length; i++) {
    //         const syllabus_id = syllabus[i]['syllabus_id'];
    //         const topic_id = syllabus[i]['topic_id'];
    //         const topic_title = syllabus[i]['topic_title'];
    //         const category = syllabus[i]['category'];

    //         syllabusData.push(syllabus[i])

    //         syllabusRowDisp += `
    //         <tr>
    //             <td><input type="text" class="rowtopic_id" value="${topic_id}" disabled></td>
    //             <td><input type="text" class="rowtopic_title" value="${topic_title}" disabled></td>
    //             <td>
    //                 <select class="rowTopicType block w-full px-4 py-2 mt-2 rounded-md border border-gray-300 focus:ring focus:ring-seagreen focus:ring-opacity-50" disabled>
    //                     <option value="LESSON" ${category === 'LESSON' ? 'selected' : ''}>LESSON</option>
    //                     <option value="ACTIVITY" ${category === 'ACTIVITY' ? 'selected' : ''}>ACTIVITY</option>
    //                     <option value="QUIZ" ${category === 'QUIZ' ? 'selected' : ''}>QUIZ</option>
    //                 </select>
    //             </td>
    //             <td>
    //                 <button data-syllabus-id="${syllabus_id}" class="row_editBtn hidden mx-1 py-1 px-3 rounded-lg bg-seagreen hover:bg-green-700">Edit</button>
    //                 <button data-syllabus-id="${syllabus_id}" class="row_saveBtn hidden mx-1 py-1 px-3 rounded-lg bg-seagreen hover:bg-green-700">Save</button>
    //                 <button data-syllabus-id="${syllabus_id}" class="row_cancelBtn hidden mx-1 py-1 px-3 rounded-lg bg-red-500 hover:bg-red-700">Cancel</button>
    //                 <button data-syllabus-id="${syllabus_id}" class="row_deleteBtn hidden mx-1 py-1 px-3 rounded-lg bg-red-500 hover:bg-red-700">Delete</button>
    //             </td>
    //         </tr>
    //         `;

    //         // $('.rowTopicType').val(category);
    //     }

    //     $('#syllabusTableBody').empty();
    //     $('#syllabusTableBody').append(syllabusRowDisp);

    // }

    function reDisplaySyllabus(syllabusData) {
        var syllabusRowDisp = ``;
        var location_selection_main = ``;
        var location_selection_sub = ``;
        // console.log(syllabusData);
        for (let i = 0; i < syllabusData.length; i++) {
            const syllabus_id = syllabusData[i]["syllabus_id"];
            const topic_title = syllabusData[i]["topic_title"];
            const category = syllabusData[i]["category"];
            console.log(syllabusData[i]);

            syllabusRowDisp +=
                `
            <tr>
            <td><input type="text" class="rowtopic_id w-full" value="` +
                i +
                `" disabled></td>
            <td><input type="text" class="rowtopic_title w-full" value="${topic_title}" disabled></td>
            <td>
                <select class="rowTopicType block w-full px-4 py-2 mt-2 rounded-md border border-gray-300 focus:ring focus:ring-seagreen focus:ring-opacity-50" disabled>
                    <option value="LESSON" ${
                        category === "LESSON" ? "selected" : ""
                    }>LESSON</option>
                    <option value="ACTIVITY" ${
                        category === "ACTIVITY" ? "selected" : ""
                    }>ACTIVITY</option>
                    <option value="QUIZ" ${
                        category === "QUIZ" ? "selected" : ""
                    }>QUIZ</option>
                </select>
            </td>
            <td>
                <button data-syllabus-id="${syllabus_id}" class="row_editBtn ${
                    edit_prior === 1 ? "" : "hidden"
                } mx-1 py-1 px-3 rounded-lg bg-seagreen hover:bg-green-700">Edit</button>
                <button data-syllabus-id="${syllabus_id}" class="row_saveBtn hidden mx-1 py-1 px-3 rounded-lg bg-seagreen hover:bg-green-700">Save</button>
                <button data-syllabus-id="${syllabus_id}" class="row_cancelBtn hidden mx-1 py-1 px-3 rounded-lg bg-red-500 hover:bg-red-700">Cancel</button>
                <button data-syllabus-id="${syllabus_id}" class="row_deleteBtn hidden mx-1 py-1 px-3 rounded-lg bg-red-500 hover:bg-red-700">Delete</button>
            </td>
        </tr>
            `;

            location_selection_sub +=
                `
            <option value="` +
                topic_title +
                `">AFTER ` +
                topic_title +
                `</option>
            `;

            location_selection_main =
                `
                <option value="START">At the Beginning</option>
            ` +
                location_selection_sub +
                `
                <option value="END">In the End</option>
            `;
        }

        if (edit_prior === 1) {
            $(".row_editBtn").removeClass("hidden");
        }

        $("#syllabusTableBody").empty();
        $("#syllabusTableBody").append(syllabusRowDisp);

        $("#insertLocation").empty();
        $("#insertLocation").append(location_selection_main);

        $(".row_editBtn").on("click", function (e) {
            e.preventDefault();
            const row = $(this).closest("tr");

            const saveBtn = row.find(".row_saveBtn");
            const cancelBtn = row.find(".row_cancelBtn");
            const deleteBtn = row.find(".row_deleteBtn");

            $(this)
                .closest("tr")
                .find(".rowtopic_title, .rowTopicType")
                .prop("disabled", false);
            $(this).closest("tr").find(".rowtopic_title").focus();

            saveBtn.removeClass("hidden");
            cancelBtn.removeClass("hidden");
            deleteBtn.removeClass("hidden");
            row.find(".row_editBtn").addClass("hidden");
        });

        $(".row_cancelBtn").on("click", function (e) {
            e.preventDefault();
            const row = $(this).closest("tr");

            const saveBtn = row.find(".row_saveBtn");
            const cancelBtn = row.find(".row_cancelBtn");
            const deleteBtn = row.find(".row_deleteBtn");

            $(this)
                .closest("tr")
                .find(".rowtopic_title, .rowTopicType")
                .prop("disabled", true);

            saveBtn.addClass("hidden");
            cancelBtn.addClass("hidden");
            deleteBtn.addClass("hidden");
            row.find(".row_editBtn").removeClass("hidden");
        });

        $(".row_saveBtn").on("click", function (e) {
            e.preventDefault();
            const row = $(this).closest("tr");
            const syllabus_id = row.find(".row_saveBtn").data("syllabus-id");
            const updated_topic_title = row.find(".rowtopic_title").val();
            const updated_category = row.find(".rowTopicType").val();

            // Find the index of the corresponding syllabusData item
            const index = syllabusData.findIndex(
                (item) => item.syllabus_id === syllabus_id,
            );

            if (index !== -1) {
                // Update the values in the syllabusData object
                syllabusData[index]["topic_title"] = updated_topic_title;
                syllabusData[index]["category"] = updated_category;
            }

            // Disable input fields and hide buttons
            row.find(".rowtopic_title, .rowTopicType").prop("disabled", true);
            row.find(".row_saveBtn, .row_cancelBtn, .row_deleteBtn").addClass(
                "hidden",
            );
            row.find(".row_editBtn").removeClass("hidden");
        });

        let rowToDelete;
        $(".row_deleteBtn")
            .off()
            .on("click", function (e) {
                e.preventDefault();
                const row = $(this).closest("tr");
                const syllabus_id = row
                    .find(".row_deleteBtn")
                    .data("syllabus-id");

                rowToDelete = row;

                $("#confirmDelete").data("syllabus-id", syllabus_id);
                $("#deleteCourseModal").removeClass("hidden");
            });

        $("#cancelDelete").on("click", function (e) {
            $("#confirmDelete").data("syllabus-id", null);
            $("#deleteCourseModal").addClass("hidden");
        });

        $("#confirmDelete")
            .off()
            .on("click", function (e) {
                e.preventDefault();
                var courseID = $(this).data("course-id");
                const fetch_syllabus_id = $(this).data("syllabus-id");
                // console.log(fetch_syllabus_id)

                if (isNaN(fetch_syllabus_id)) {
                } else {
                    edit_prior = 1;
                    // console.log(fetch_syllabus_id)
                    var csrfToken = $('meta[name="csrf-token"]').attr(
                        "content",
                    );

                    var data = { fetch_syllabus_id: fetch_syllabus_id };

                    $.ajax({
                        type: "POST",
                        url:
                            "/instructor/course/content/syllabus/" +
                            courseID +
                            "/manage_delete",
                        data: data,
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        success: function (response) {
                            // Handle success if needed
                            // if (response && response.redirect_url ) {
                            //     window.location.href = response.redirect_url;
                            // }

                            $("#deleteCourseModal").addClass("hidden");
                            reDisplaySyllabus(syllabusData);
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    });
                }

                // Find the index of the corresponding syllabusData item
                const index = syllabusData.findIndex(
                    (item) => item.syllabus_id === fetch_syllabus_id,
                );

                if (index !== -1) {
                    // Remove the item from the syllabusData array
                    syllabusData.splice(index, 1);
                }

                if (rowToDelete) {
                    rowToDelete.remove(); // Remove the row
                }

                $("#deleteCourseModal").addClass("hidden");
                reDisplaySyllabus(syllabusData);
            });
    }

    $("#editSyllabusBtn").on("click", function (e) {
        e.preventDefault();

        $("#addChangesBtn").removeClass("hidden");
        $("#saveChangesBtn").removeClass("hidden");
        $("#cancelChangesBtn").removeClass("hidden");

        $(".row_editBtn").removeClass("hidden");

        $("#editSyllabusBtn").addClass("hidden");
    });

    $("#cancelChangesBtn").on("click", function (e) {
        e.preventDefault();

        $("#addChangesBtn").addClass("hidden");
        $("#saveChangesBtn").addClass("hidden");
        $("#cancelChangesBtn").addClass("hidden");

        $(".row_editBtn").addClass("hidden");

        $("#editSyllabusBtn").removeClass("hidden");
    });

    $("#addChangesBtn").on("click", function (e) {
        e.preventDefault();

        $("#addTopicModal").removeClass("hidden");
    });

    $("#cancelAddTopicBtn").on("click", function (e) {
        e.preventDefault();

        $("#addTopicModal").addClass("hidden");
    });

    $("#saveChangesBtn").on("click", function (e) {
        e.preventDefault();
        // var topic_id_counter = 1;
        // console.log(syllabusData)
        var courseID = $(this).data("course-id");

        // to rearrange the topic id
        for (let i = 0; i < syllabusData.length; i++) {
            syllabusData[i]["topic_id"] = i + 1;
            // topic_id_counter++;

            // console.log(syllabusData[i])
        }

        for (let i = 0; i < syllabusData.length; i++) {
            var rowSyllabusData = {
                syllabus_id: syllabusData[i]["syllabus_id"],
                topic_id: syllabusData[i]["topic_id"],
                topic_title: syllabusData[i]["topic_title"],
                category: syllabusData[i]["category"],
            };
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            if (!/^none\d+$/.test(rowSyllabusData["syllabus_id"])) {
                // AJAX for updating the values
                $.ajax({
                    type: "POST",
                    url:
                        "/instructor/course/content/syllabus/" +
                        courseID +
                        "/manage",
                    data: rowSyllabusData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    async: false,
                    success: function (response) {
                        // Handle success if needed
                        if (i + 1 == syllabusData.length) {
                            if (response && response.redirect_url) {
                                window.location.href = response.redirect_url;
                            }
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    },
                });
            } else {
                // AJAX for creating new syllabus
                rowSyllabusData["syllabus_id"] = "";
                $.ajax({
                    type: "POST",
                    url:
                        "/instructor/course/content/syllabus/" +
                        courseID +
                        "/manage_add",
                    data: rowSyllabusData,
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    async: false,
                    success: function (response) {
                        // Handle success if needed
                        if (i + 1 == syllabusData.length) {
                            if (response && response.redirect_url) {
                                window.location.href = response.redirect_url;
                            }
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    },
                });
            }
        }
    });

    var none_count = 0;

    $("#confirmAddTopicBtn").on("click", function (e) {
        e.preventDefault();

        var chosen_category = $("#topicType").val();
        var chosen_location = $("#insertLocation").val();
        var chosen_titleName = $("#topicName").val();

        const newTopic = {
            syllabus_id: "none" + none_count++,
            topic_id: "",
            topic_title: chosen_titleName,
            category: chosen_category,
        };

        if (syllabusData.length > 0) {
            if (chosen_location == "START") {
                syllabusData.unshift(newTopic);
            } else if (chosen_location == "END") {
                syllabusData.push(newTopic);
            } else {
                const insertIndex = syllabusData.findIndex(
                    (topic) => topic.topic_title === chosen_location,
                );

                // Insert the new topic at the specified index
                if (insertIndex !== -1) {
                    syllabusData.splice(insertIndex + 1, 0, newTopic);
                } else {
                    // Handle the case where the insertLocation is not found, you may choose to append it at the end.
                    syllabusData.push(newTopic);
                }
            }
        } else {
            syllabusData.push(newTopic);
        }

        $("#addTopicModal").addClass("hidden");
        reDisplaySyllabus(syllabusData);
        $(".row_editBtn").removeClass("hidden");
        // console.log(syllabusData)
    });

    $("#removeModalBtn").on("click", function (e) {
        e.preventDefault();

        $("#syllabusModal").addClass("hidden");
    });
});
