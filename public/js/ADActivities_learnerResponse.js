var criteriaData = [];

    function initializeCriteriaData() {
        $('.criteriaScore').each(function () {
            var enteredValue = parseFloat($(this).val()) || 0;
            var activityContentCriteriaID = $(this).data('activity-content-criteria-id');
            var learnerActivityCriteriaScoreID = $(this).data('learner-activity-criteria-score-id');
    
            criteriaData.push({
                activityContentCriteriaID: activityContentCriteriaID,
                learnerActivityCriteriaScoreID: learnerActivityCriteriaScoreID,
                score: enteredValue
            });
        });
        
    }

    function handleCriteriaInput() {
        // Get the entered value as a number
        const enteredValue = parseFloat($(this).val()) || 0;
    
        // Get the max value from the max attribute as a number
        const maxValue = parseFloat($(this).attr('max')) || 0;
    
        // Ensure the entered value does not exceed the max value
        if (enteredValue > maxValue) {
            // Display an error message or take appropriate action
            alert('Entered value cannot exceed the maximum score.');
            // Reset the input value to the max value
            $(this).val(maxValue);
            enteredValue = maxValue;
            return; // Exit the function to prevent further processing
        }
    
        var activityContentCriteriaID = $(this).data('activity-content-criteria-id');
        var learnerActivityCriteriaScoreID = $(this).data('learner-activity-criteria-score-id');
    
        // Check if an entry with the same criteriaId and activityCriteriaScoreId exists
        var existingEntryIndex = criteriaData.findIndex(entry => entry.activityContentCriteriaID === activityContentCriteriaID && entry.learnerActivityCriteriaScoreID === learnerActivityCriteriaScoreID);
    
        if (existingEntryIndex !== -1) {
            // If exists, update the enteredValue only if it is valid
            criteriaData[existingEntryIndex].score = enteredValue; // Update the score
        } else {
            // If not, add a new entry
            criteriaData.push({
                activityContentCriteriaID: activityContentCriteriaID,
                learnerActivityCriteriaScoreID: learnerActivityCriteriaScoreID,
                score: enteredValue
            });
        }
    }




$(document).ready(function() {

    initializeCriteriaData();



    $('#addScoreBtn').on('click', function(e) {
        e.preventDefault();

        $('.criteriaScore').prop('disabled', false).focus();
        $('#remarks').prop('disabled', false);
        // $('#remarks_area').removeClass('hidden');

        $('#returnBtn').addClass('hidden');
        $('#addScoreBtn').addClass('hidden');
        
        $('#cancelScoreBtn').removeClass('hidden');
        $('#submitScoreBtn').removeClass('hidden');
    })

    $('#cancelScoreBtn').on('click', function(e) {
        e.preventDefault();

        $('.criteriaScore').prop('disabled', true);
        $('#remarks').prop('disabled', false);
        // $('#remarks_area').addClass('hidden');

        $('#returnBtn').removeClass('hidden');
        $('#addScoreBtn').removeClass('hidden');
        
        $('#cancelScoreBtn').addClass('hidden');
        $('#submitScoreBtn').addClass('hidden');
    })


    
    

function updateOverallTotalScore() {
    // Recalculate the overall total score
    var overallTotalScore = 0;

    $('.criteriaScore').each(function () {
        overallTotalScore += parseFloat($(this).val()) || 0;
    });

    // Update the overall total score input
    $('#overallScore_input').val(overallTotalScore);
}

$('.criteriaScore').on('input', function (e) {
    handleCriteriaInput.call(this); // Ensure 'this' refers to the input element
    updateOverallTotalScore();
    console.log(criteriaData);
});
    


$('#submitScoreBtn').on('click', function () {
    $('#confirmationModal').removeClass('hidden');
});

// Hide modal when close button or cancel button is clicked
$('#closeModal, #cancelSubmit').on('click', function () {
    $('#confirmationModal').addClass('hidden');
});

// Handle submit action when confirm button is clicked
$('#confirmSubmit').on('click', function (e) {
    e.preventDefault();

    // Retrieve values from the inputs
    var overallScoreInput = $('#overallScore_input').val();
    var criteriaScores = $('.criteriaScore');

    // Validate overall score
    if (overallScoreInput === "") {
        alert("Please enter the overall score.");
        return;
    }

    $('#confirmationModal').addClass('hidden');
    $('#loaderModal').removeClass('hidden');

    // Validate criteria scores
    for (var i = 0; i < criteriaScores.length; i++) {
        var criteriaScore = $(criteriaScores[i]).val();
        if (criteriaScore === "") {
            alert("Please enter all criteria scores.");
            return;
        }
    }

    // If validation passes, proceed with submitting the score
    var learnerActivityOutputID = $(this).data('learner-activity-output-id');
    var learnerCourseID = $(this).data('learner-course-id');
    var activityID = $(this).data('activity-id');
    var activityContentID = $(this).data('activity-content-id');
    var attempt = $(this).data('attempt');

    var remarks = $('#remarks').val();

    var remarksData = {
        remarks: remarks,
        total_score: overallScoreInput
    }

    var url = `/admin/courseManage/content/activity/${learnerActivityOutputID}/${learnerCourseID}/${activityID}/${activityContentID}/${attempt}`;

    // console.log(url);
    // console.log(remarks);
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
    $.ajax({
        type: "POST",
        url: url,
        data: remarksData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            // window.location.reload();
            console.log(response)
            addLearnerOutputCriteriaScore(learnerActivityOutputID, learnerCourseID, activityID, activityContentID, attempt);
            // alert('done');
        },
        error: function(error) {
            console.log(error);
        }
    });
});

function addLearnerOutputCriteriaScore(learnerActivityOutputID, learnerCourseID, activityID, activityContentID, attempt) {
    console.log(criteriaData);
    // console.log('learner_activity_output: ' + learnerActivityOutputID);
    // console.log('learner_course: ' + learnerCourseID);
    // console.log('activity: ' + activityID);
    // console.log('activity_content: ' + activityContentID);
    // Capture the current URL
    var currentUrl = window.location.href;
    console.log("Current URL:", currentUrl);

    // Find the index of "/instructor" in the URL
    var instructorIndex = currentUrl.indexOf("/instructor");

    // Extract the part of the URL after "/instructor"
    var urlSuffix = currentUrl.substring(instructorIndex);
    console.log("URL suffix:", urlSuffix);

    var url = `/admin/courseManage/content/activity/${learnerActivityOutputID}/${learnerCourseID}/${activityID}/${activityContentID}/${attempt}/criteria_score`;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    var promises = [];

    for (let i = 0; i < criteriaData.length; i++) {
        var rowCriteriaData = {
            activity_content_criteria_id: criteriaData[i]['activityContentCriteriaID'],
            learner_activity_criteria_score_id: criteriaData[i]['learnerActivityCriteriaScoreID'],
            score: criteriaData[i]['score'],
            currentUrl: urlSuffix,
        };

        console.log(rowCriteriaData);

        var promise = new Promise(function (resolve, reject) {
            $.ajax({
                type: "POST",
                url: url,
                data: rowCriteriaData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                success: function (response) {
                    console.log(response);
                    resolve(response);
                },
                error: function (error) {
                    console.log(error);
                    reject(error);
                },
            });
        });

        promises.push(promise);
    }

    Promise.all(promises)
        .then(function (responses) {
            // Redirect after all AJAX calls have completed
            if (responses.length > 0 && responses[responses.length - 1].redirect_url) {
                
    $('#loaderModal').addClass('hidden');
                window.location.href = responses[responses.length - 1].redirect_url;
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

})