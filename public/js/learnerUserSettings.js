$(document).ready(function () {

    
    getLearnerData()


    $("#showLearnerPersonal").on("click", () => {
        $("#learnerPersonal").toggleClass("hidden");
    });
    $("#showLearnerLogin").on("click", () => {
        $("#learnerLogin").toggleClass("hidden");
    });
    $("#showLearnerBusiness").on("click", () => {
        $("#learnerBusiness").toggleClass("hidden");
    });

    const fname = $("#learner_fname");
    const lname = $("#learner_lname");
    const gender = $("#learner_gender");
    const bday = $("#learner_bday");
    const email = $("#learner_email");
    const contactno = $("#learner_contactno");
    const username = $("#learner_username");
    const password = $("#password");
    const password_confirmForm = $("#password_confirmForm");

    const business_name = $("#business_name");
    const bplo_account_number = $("#bplo_account_number");
    const business_address = $("#business_address");
    const business_owner_name = $("#business_owner_name");

    const editBtn = $("#editBtn");
    const updateBtn = $("#updateBtn");
    const cancelBtn = $("#cancelBtn");

    $("#editBtn").on("click", () => {
        $("#learnerPersonal").toggleClass("hidden");
        $("#learnerBusiness").toggleClass("hidden");

        fname.prop("disabled", false);
        fname.attr("autofocus", "autofocus");

        lname.prop("disabled", false);
        bday.prop("disabled", false);
        gender.prop("disabled", false);

        business_name.prop("disabled", false);
        business_address.prop("disabled", false);
        business_owner_name.prop("disabled", false);

        password_confirmForm.removeClass("hidden");

        editBtn.addClass("hidden");
        updateBtn.removeClass("hidden");
        cancelBtn.removeClass("hidden");
    });

    $(cancelBtn).on("click", (e) => {
        window.location.reload();
    });

    $("#updatePictureBtn").click(function () {
        $("#profilePicturePopup").removeClass("hidden");
    });

    $("#closePopup").click(function () {
        $("#profilePicturePopup").addClass("hidden");
    });



    

    function getLearnerData() {
        var url = `/learner/learnerData`;
            $.ajax({
                type: "GET",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    console.log(response);

                    var learner = response['learner']
    
                    process_files(learner)

                    
                    $('.loaderArea').addClass('hidden');
                    $('.mainchatbotarea').removeClass('hidden');

                    $('.submitQuestion').on('click', function(e) {
                        e.preventDefault();
                        submitQuestion();
                    });
        
                    $('.question').on('keydown', function(e) {
                        if (e.keyCode === 13) {
                            e.preventDefault();
                            submitQuestion();
                        }
                    });
        
                    function submitQuestion() {
                        var learner_id = learner['learner_id'];
                        var question = $('.question').val();
                        var course = courseData['course_name'];
                        var lesson = 'ALL';
        
                        displayUserMessage(question, learner);
                        $('.botloader').removeClass('hidden');
                        var chatData = {
                            question: question,
                            course: "ALL",
                            lesson: "ALL",
                        };
        
                        var url = `/chatbot/chat/${learner_id}`;
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: chatData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                console.log(response);
                                displayBotMessage(response);
                                question.val('')
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }

                    $('.loaderArea').addClass('hidden');
                    $('.mainchatbotarea').removeClass('hidden');


                },
                error: function(error) {
                    console.log(error);
                }
            });
    }

    function process_files(session_id) {
        var url = `/chatbot/process/${session_id}`;
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
});
