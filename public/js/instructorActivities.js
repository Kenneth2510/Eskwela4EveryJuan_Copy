$(document).ready(() => {
    $("#viewResponseActivity").on("click", (e) => {
        e.preventDefault();
        
        // $("#selectTypeParent").removeClass("hidden");
        $('#responsesModal').removeClass('hidden');
        activityOutputData();
    });

    $(".exitResponsesModalBtn").on("click", () => {
        $("#responsesModal").addClass("hidden");
    });

    let isAppended = false;

    function activityOutputData() {
        var courseID = $('#studentsList').data('course-id')
        var syllabusID = $('#studentsList').data('syllabus-id')
        var topicID = $('#studentsList').data('topic-id')
        

        // console.log(courseID)
        // console.log(syllabusID);
        // console.log(topicID);

        var url = "/instructor/course/content/"+ courseID +"/"+ syllabusID +"/activity/"+ topicID +"/json";

        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            success: function (response){
                console.log(response)
                activityData = response['activityContent']
                // activityCriteriaData = response['activityContentCriteria']
                // console.log(activityCriteriaData)
                // reDisplayActivity(activityData, activityCriteriaData);
                learnerActivityContent = response['learnerActivityContent'];
                appendStudentsList(learnerActivityContent, activityData);
            },
            error: function(error) {
                console.log(error);
            }
        })
    }

    function appendStudentsList(learnerActivityContent, activityData) {
        var courseID = $('#studentsList').data('course-id')
        var syllabusID = $('#studentsList').data('syllabus-id')
        var topicID = $('#studentsList').data('topic-id')
        // console.log(isAppended);
        // if (!isAppended) {
        //     const studentsList = $(
        //         `<button class="flex flex-row items-center" id="backToDefault">
        //                 <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M560-240 320-480l240-240 56 56-184 184 184 184-56 56Z"/></svg>
        //                 <p>Go back</p>
        //             </button>
        //             <h1 class="mt-4 mb-2 text-xl font-medium">Learner Responses</h1>
        //                     <table class="w-full text-center table-fixed">
        //                        <thead>
        //                            <tr>
        //                                <th class="w-1/12">Enrollee ID</th>
        //                                <th class="w-1/12">Learner ID</th>
        //                                <th class="w-2/12">Name</th>
        //                                <th class="w-1/12">Attempt</th>
        //                                <th class="w-2/12">Attempt Taken</th>
        //                                <th class="w-1/12">Score</th>
        //                                <th class="w-2/12">Status</th>
        //                                <th class="w-1/12">Mark</th>
        //                                <th class="w-1/12"></th>
        //                            </tr>
        //                        </thead>
        //                        <tbody id="learnerActivityData">
                                   
        //                        </tbody>
        //                    </table>`,
        //     );
        //     // studentsList
        //     //     .find('td[class="float-right"]')
        //     //     .html(
        //     //         '<button class="flex flex-row items-center justify-center p-4 m-2 rounded-lg shadow-lg bg-amber-400 hover:bg-amber-500 md:h-12 py-2" type="button" id=""><h1>visit</h1></button>',
        //     //     );

        //     $("#studentsList").append(studentsList);

        //     let criteria_total_score = activityData[0]['total_score']

        //     isAppended = true;

            learnerRowData = ``;

            if(learnerActivityContent.length === 0) {
                learnerRowData = `
                <tr>
                    <td colspan="9" class="text-center">No data available</td>
                </tr>
            `;
            } else {
                for (let i = 0; i < learnerActivityContent.length; i++) {
                    const enrollee_id = learnerActivityContent[i]['learner_course_id'];
                    const learner_id = learnerActivityContent[i]['learner_id'];
                    const learner_fname = learnerActivityContent[i]['learner_fname'];
                    const learner_lname = learnerActivityContent[i]['learner_lname'];
                    let total_score = learnerActivityContent[i]['total_score'];
                    const status = learnerActivityContent[i]['status'];
                    const attempt = learnerActivityContent[i]['attempt'];
                    const mark = learnerActivityContent[i]['mark'];
                    const created_at = learnerActivityContent[i]['created_at'];
    
                    if(status == "NOT YET STARTED") {
                        dispStatus = "NOT YET STARTED"
                    } else if (status == "IN PROGRESS") {
                        dispStatus = "TO BE CHECKED";
                    } else {
                        dispStatus = "COMPLETED";
                    }
    
                    if(total_score == null) {
                        total_score = "no score yet";
                    }
    
                    learnerRowData += `
                        <tr>
                            <td>${enrollee_id}</td>
                            <td>${learner_id}</td>
                            <td>${learner_fname} ${learner_lname}</td>
                            <td>${attempt}</td>
                            <td>${created_at}</td>
                            <td>${total_score}</td>
                            <td>${dispStatus}</td>
                            <td>${mark}</td>
                            <td class="float-right">
                                <a href="/instructor/course/content/${courseID}/${syllabusID}/activity/${topicID}/${enrollee_id}/${attempt}" 
                                class="flex flex-row items-center justify-center p-4 m-2 rounded-lg shadow-lg bg-darthmouthgreen hover:bg-green-650 md:h-12 py-2"  
                                data-learner-course-id="${enrollee_id}">
                                    <h1>visit</h1>
                                </a>
                            </td>
                        </tr>
                    `;
                }
            }
      

            $('#responsesRowDataArea').empty();
            $('#responsesRowDataArea').append(learnerRowData);


                              

        //     $(document).on("click", "#backToDefault", () => {
        //         $("#defaultView").removeClass("hidden");
        //         studentsList.remove();
        //         isAppended = false;
        //     });
        // }
    }

    $("#viewStudents").on("click", () => {
        appendStudentsList();
        $("#defaultView").addClass("hidden");
        $("#selectTypeParent").addClass("hidden");
    });

    $("#emptyActivity").on("click", "button", function () {
        $("#defaultView").toggleClass("hidden");
        $(this).remove();
    });

    window.handleModal();



    var activityData = {};
    var activityCriteriaData = {};



    // overall edit activity
    $('#editActivityBtn').on('click', function(e) {
        e.preventDefault();

        $('#editInstructionsBtn').removeClass('hidden');
        $('#editCriteriaBtn').removeClass('hidden');
        $('#editTotalScore').removeClass('hidden');

        $('#editActivity_clickedBtns').removeClass('hidden');
        $('#editActivityBtn').addClass('hidden');   
        
        var courseID = $(this).data('course-id');
        var syllabusID = $(this).data('syllabus-id');
        var topicID = $(this).data('topic_id');

        $('#loaderModal').removeClass('hidden');

        var url = "/instructor/course/content/"+ courseID +"/"+ syllabusID +"/activity/"+ topicID +"/json";

        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            success: function (response){
                console.log(response)
                activityData = response['activityContent']
                activityCriteriaData = response['activityContentCriteria']
                // console.log(activityCriteriaData)
                
        $('#loaderModal').addClass('hidden');
                reDisplayActivity(activityData, activityCriteriaData);
            },
            error: function(error) {
                console.log(error);
            }
        })
    })


    function reDisplayActivity(activityData, activityCriteriaData) {
        console.log(activityCriteriaData)
        var activityContent_disp =``;
        
        for (let i = 0; i < activityData.length; i++) {
            const activity_content_id = activityData[i]['activity_content_id'];
            const activity_id = activityData[i]['activity_id'];
            const activity_instructions = activityData[i]['activity_instructions'];
            const total_score = activityData[i]['total_score'];

            
            if(activityData !== null) {
                activityContent_disp += `
                <div class="flex flex-row items-center">
                        <h3 class="my-2 text-xl font-medium">Instructions:</h3>
                        <button id="" class="editInstructionsBtn hidden">
                            <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                        </button>
                        
                    </div>
          
                    
                    <textarea name="activity_instructions" class="w-full max-w-full min-w-full activity_instructions h-[200px]" disabled>${activity_instructions}</textarea>
                    <div class="hidden mt-3 editInstructions_clickedBtn">
                        <button data-activity-id="${activity_id}" data-activity-content-id="${activity_content_id}" class="px-3 py-3 text-white bg-green-600 saveInstructionsBtn hover:bg-green-900 rounded-xl">Save</button>
                        <button class="px-3 py-3 text-white bg-red-600 cancelInstructionsBtn hover:bg-red-900 rounded-xl">Cancel</button>
                    </div>

                    <div class="flex flex-row items-center mt-5">
                        <h3 class="my-2 text-xl font-medium">Criteria:</h3>
                        <button id="" class="editCriteriaBtn hidden">
                            <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                        </button>
                        
                    </div>
                    <table class="rounded-xl">
                        <thead class="text-xl text-white bg-green-700 rounded-xl">
                            <th class="w-2/5">Criteria</th>
                            <th class="w-1/5">Score</th>
                            <th class="w-1/5"></th>
                        </thead>
                        <tbody>`;
                        if(activityCriteriaData !== null) {
          
                            for (let x = 0; x < activityCriteriaData.length; x++) {
                                const activity_content_criteria_id = activityCriteriaData[x]['activity_content_criteria_id'];
                                const activity_content_id = activityCriteriaData[x]['activity_content_id'];
                                const criteria_title = activityCriteriaData[x]['criteria_title'];
                                const score = activityCriteriaData[x]['score'];

                                
                                    activityContent_disp += `
                                    <tr>
                                        <td>
                                            <input type="text" class="criteriaTitle_input" value="${criteria_title}" disabled>
                                        </td>
                                        <td class="flex justify-end">
                                            <input type="text" class="criteriaScore_input flex text-center" value="${score}" disabled></td>
                                        <td>
                                            <button class="hidden px-3 py-1 mx-2 font-semibold text-white bg-green-600 rounded-xl editRowCriteriaBtn hover:bg-green-900">Edit</button>
                                            <div class="hidden flex edit_btns">
                                            
                                                <button data-activity-content-criteria-id="${activity_content_criteria_id}" data-activity-content-id="${activity_content_id}" class="px-3 py-1 mx-2 font-semibold text-white bg-darthmouthgreen rounded-xl saveRowCriteriaBtn hover:bg-green-900">Save</button>

                                                <button data-activity-content-criteria-id="${activity_content_criteria_id}" data-activity-content-id="${activity_content_id}" class="px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl deleteRowCriteriaBtn hover:bg-red-900">Delete</button>

                                                <button class=" px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl cancelRowCriteriaBtn hover:bg-red-900">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                    `
                                }
                            } else {
                                activityContent_disp += `
                                <tr>
                                    <td rowspan="3">No Criterias Found</td>
                                </tr>
                                `
                            }
                            
                        
                activityContent_disp += `
                </tbody>
                    </table>
                    <button id="" data-activity-id="${activity_id}" data-activity-content-id="${activity_content_id}" class="addNewCriteria hidden px-3 py-1 mx-2 font-semibold text-white bg-darthmouthgreen rounded-xl hover:bg-green-900">Add Criteria</button>
                    <div class="editCriteria_clickedBtn hidden mt-3" id=""> 
                        <button id="" data-activity-id="${activity_id}" data-activity-content-id="${activity_content_id}" class="saveCriteriaBtn text-2xl px-7 py-5 text-white rounded-xl bg-darthmouthgreen hover:bg-green-900">Save Criteria</button>
                        <button id="" class="cancelCriteriaBtn text-2xl px-7 py-5 text-white bg-red-600 hover:bg-red-900 rounded-xl">Cancel</button>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="">
  
                        <p class="text-2xl font-semibold">Overall Total Score: </p>
                        <input type="number" id="" class="overallScore_input w-full text-4xl" value="${total_score}" disabled>
                        
                        <p class="px-10 text-4xl">/ ${total_score}</p>

                    </div>
                `           
            } else {
                // activityContent_disp += `
                // <p>No instructions given</p>
                // `

                activityContent_disp += `
                <div class="flex flex-row items-center">
                        <h3 class="my-2 text-xl font-medium">Instructions:</h3>
                        <button id="" class="editInstructionsBtn hidden">
                            <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                        </button>
                        
                    </div>
          
                    
                    <textarea name="activity_instructions" class="w-full max-w-full min-w-full activity_instructions h-[200px]" disabled></textarea>
                    <div class="hidden mt-3 editInstructions_clickedBtn">
                        <button data-activity-id="${activity_id}" data-activity-content-id="${activity_content_id}" class="px-3 py-3 text-white bg-green-600 saveInstructionsBtn hover:bg-green-900 rounded-xl">Save</button>
                        <button class="px-3 py-3 text-white bg-red-600 cancelInstructionsBtn hover:bg-red-900 rounded-xl">Cancel</button>
                    </div>

                    <div class="flex flex-row items-center mt-5">
                        <h3 class="my-2 text-xl font-medium">Criteria:</h3>
                        <button id="" class="editCriteriaBtn hidden">
                            <svg class="mx-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M80 0v-160h800V0H80Zm80-240v-150l362-362 150 150-362 362H160Zm80-80h36l284-282-38-38-282 284v36Zm477-326L567-796l72-72q11-12 28-11.5t28 11.5l94 94q11 11 11 27.5T789-718l-72 72ZM240-320Z"/></svg>
                        </button>
                        
                    </div>
                    <table class="rounded-xl">
                        <thead class="text-xl text-white bg-green-700 rounded-xl">
                            <th class="w-2/5">Criteria</th>
                            <th class="w-1/5">Score</th>
                            <th class="w-1/5"></th>
                        </thead>
                        <tbody>`;
                        if(activityCriteriaData !== null) {
          
                            for (let x = 0; x < activityCriteriaData.length; x++) {
                                const activity_content_criteria_id = activityCriteriaData[x]['activity_content_criteria_id'];
                                const activity_content_id = activityCriteriaData[x]['activity_content_id'];
                                const criteria_title = activityCriteriaData[x]['criteria_title'];
                                const score = activityCriteriaData[x]['score'];

                                
                                    activityContent_disp += `
                                    <tr>
                                        <td>
                                            <input type="text" class="criteriaTitle_input" value="${criteria_title}" disabled>
                                        </td>
                                        <td class="flex justify-end">
                                            <input type="text" class="criteriaScore_input flex text-center" value="${score}" disabled></td>
                                        <td>
                                            <button class="hidden px-3 py-1 mx-2 font-semibold text-white bg-green-600 rounded-xl editRowCriteriaBtn hover:bg-green-900">Edit</button>
                                            <div class="hidden flex edit_btns">
                                            
                                                <button data-activity-content-criteria-id="${activity_content_criteria_id}" data-activity-content-id="${activity_content_id}" class="px-3 py-1 mx-2 font-semibold text-white bg-darthmouthgreen rounded-xl saveRowCriteriaBtn hover:bg-green-900">Save</button>

                                                <button data-activity-content-criteria-id="${activity_content_criteria_id}" data-activity-content-id="${activity_content_id}" class="px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl deleteRowCriteriaBtn hover:bg-red-900">Delete</button>

                                                <button class=" px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl cancelRowCriteriaBtn hover:bg-red-900">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                    `
                                }
                            } else {
                                activityContent_disp += `
                                <tr>
                                    <td rowspan="3">No Criterias Found</td>
                                </tr>
                                `
                            }
                            
                        
                activityContent_disp += `
                </tbody>
                    </table>
                    <button id="" data-activity-id="${activity_id}" data-activity-content-id="${activity_content_id}" class="addNewCriteria hidden px-3 py-1 mx-2 font-semibold text-white bg-darthmouthgreen rounded-xl hover:bg-green-900">Add Criteria</button>
                    <div class="editCriteria_clickedBtn hidden mt-3" id=""> 
                        <button id="" data-activity-id="${activity_id}" data-activity-content-id="${activity_content_id}" class="saveCriteriaBtn text-2xl px-7 py-5 text-white rounded-xl bg-darthmouthgreen hover:bg-green-900">Save Criteria</button>
                        <button id="" class="cancelCriteriaBtn text-2xl px-7 py-5 text-white bg-red-600 hover:bg-red-900 rounded-xl">Cancel</button>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="">
          
                        <p class="text-2xl font-semibold">Overall Total Score: </p>
                        <input type="number" id="" class="overallScore_input w-full text-4xl" value="${total_score}" disabled>
                        
                        <p class="px-10 text-4xl">/ ${total_score}</p>
                    </div>
                `           
            }
            
            
        }

        $('#defaultView').empty();
        $('#defaultView').append(activityContent_disp);

        $('.editInstructionsBtn').removeClass('hidden');
        $('.editCriteriaBtn').removeClass('hidden');
        $('.editTotalScore').removeClass('hidden');

        $('.editActivity_clickedBtns').removeClass('hidden');
        $('.editActivityBtn').addClass('hidden');



        
    // edit instructions
    $('#insertLessonContent').keydown(function(event) {
        if (event.key === 'Enter') {
          event.preventDefault(); // Prevent the default behavior (line break)

          var textarea = $(this);
          var start = textarea[0].selectionStart;
          var end = textarea[0].selectionEnd;
          var value = textarea.val();

          // Insert a newline character at the cursor position
          var updatedValue = value.substring(0, start) + '\n' + value.substring(end);

          // Update the textarea's value and cursor position
          textarea.val(updatedValue);
          textarea[0].setSelectionRange(start + 1, start + 1);
        }
      });


    var pElements = $('.activity_instructions_input');
        
    // Use the filter function to select only those with newline characters
    var pElementsWithNewlines = pElements.filter(function() {
        return $(this).text().includes('\n');
    });

    pElementsWithNewlines.css('white-space', 'pre');

    $('.editInstructionsBtn').on('click', function(e) {
        e.preventDefault();
        $('.activity_instructions').prop('disabled', false).focus();

        $('.editInstructions_clickedBtn').removeClass('hidden');
        $('.editInstructionsBtn').addClass('hidden');
    })

    $('.cancelInstructionsBtn').on('click', function(e) {
        e.preventDefault();

        $('.activity_instructions').prop('disabled', true);

        $('.editInstructions_clickedBtn').addClass('hidden');
        $('.editInstructionsBtn').removeClass('hidden');
    })

    $('.saveInstructionsBtn').on('click', function(e) {
        $('#loaderModal').removeClass('hidden');
        const activityContentID = $(this).data('activity-content-id');
        const activityID = $(this).data('activity-id');
        const activity_instructions = $(this).closest('.editInstructions_clickedBtn').siblings('textarea.activity_instructions').val();

        const udpatedActivityInstructions = {
            'activity_instructions': activity_instructions,
        };

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // console.log(activityContentID, activity_instructions)
        var baseUrl = window.location.href;
        // console.log(baseUrl);
        var url = baseUrl + "/title/"+ activityID +"/" + activityContentID +"/instructions";
        // console.log(url)

        $.ajax({
            type: "POST",
            url: url,
            data: udpatedActivityInstructions,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                
        $('#loaderModal').addClass('hidden');
                window.location.reload();
            },
            error: function(error) {
                console.log(error);
            }
        });
    })


    $('.editCriteriaBtn').on('click', function(e) {
        e.preventDefault();

        $('.addNewCriteria').removeClass('hidden');
        $('.editCriteria_clickedBtn').removeClass('hidden');
        $('.editRowCriteriaBtn').removeClass('hidden');
    })

        $('.editRowCriteriaBtn').on('click', function(e) {
            e.preventDefault();
            var tr = $(this).closest('tr');

            const criteriaTitle = tr.find('.criteriaTitle_input').val().trim();
            const criteriaScore = tr.find('.criteriaScore_input').val().trim();

            console.log('criteriaTitle:', criteriaTitle);
            console.log('criteriaScore:', criteriaScore);

            // Find the index of the object in activityCriteriaData
            const indexToSave = activityCriteriaData.findIndex(item => item.criteria_title === criteriaTitle && item.score == criteriaScore);

            tr.find('.deleteRowCriteriaBtn').data('array-criteria-index', indexToSave);
            tr.find('.saveRowCriteriaBtn').data('array-criteria-index', indexToSave);

            tr.find('.edit_btns').removeClass('hidden');
            $(this).addClass('hidden');

            tr.find('.criteriaTitle_input').prop('disabled', false).focus;
            tr.find('.criteriaScore_input').prop('disabled', false);
        })

        $('.cancelRowCriteriaBtn').on('click', function(e) {
            e.preventDefault();

            var tr = $(this).closest('tr');
            tr.find('.editRowCriteriaBtn').removeClass('hidden');
            tr.find('.edit_btns').addClass('hidden');

            tr.find('.criteriaTitle_input').prop('disabled', true);
            tr.find('.criteriaScore_input').prop('disabled', true);
        })

        $('.deleteRowCriteriaBtn').on('click', function(e) {
            e.preventDefault();
            // alert('test')

            var tr = $(this).closest('tr');
            const criteriaTitle = tr.find('.criteriaTitle_input').val().trim();
            const criteriaScore = tr.find('.criteriaScore_input').val().trim();

            console.log('criteriaTitle:', criteriaTitle);
            console.log('criteriaScore:', criteriaScore);

            // Find the index of the object in activityCriteriaData
            // const indexToRemove = activityCriteriaData.findIndex(item => item.criteria_title === criteriaTitle && item.score == criteriaScore);

            // console.log('indexToRemove:', indexToRemove);

            const criteria_id = $(this).data('array-criteria-index')

            console.log(criteria_id)

          
            if (criteria_id !== -1) {
                // Remove the object from the array
                activityCriteriaData.splice(criteria_id, 1);
                console.log(activityCriteriaData);
        
                // Update the display or perform any other necessary actions
                reDisplayActivity(activityData, activityCriteriaData);
                $('.addNewCriteria').removeClass('hidden');
                $('.editCriteria_clickedBtn').removeClass('hidden');
                $('.editRowCriteriaBtn').removeClass('hidden');
            }

        })

        $('.saveRowCriteriaBtn').on('click', function(e) {
            e.preventDefault();

            var tr = $(this).closest('tr');
            const criteriaTitle = tr.find('.criteriaTitle_input').val().trim();
            const criteriaScore = tr.find('.criteriaScore_input').val().trim();

            console.log('criteriaTitle:', criteriaTitle);
            console.log('criteriaScore:', criteriaScore);

            // // Find the index of the object in activityCriteriaData
            // const indexToSave = activityCriteriaData.findIndex(item => item.criteria_title === criteriaTitle && item.score == criteriaScore);
        
            const criteria_id = $(this).data('array-criteria-index')
            console.log(criteria_id);
            console.log(activityCriteriaData[criteria_id])


            if (criteria_id !== -1) {
                activityCriteriaData[criteria_id]['criteria_title'] = criteriaTitle;
                activityCriteriaData[criteria_id]['score'] = criteriaScore;
                // Update the display or perform any other necessary actions
                reDisplayActivity(activityData, activityCriteriaData);
                $('.addNewCriteria').removeClass('hidden');
                $('.editCriteria_clickedBtn').removeClass('hidden');
                $('.editRowCriteriaBtn').removeClass('hidden');
            }
                
        })



    $('.cancelCriteriaBtn').on('click', function(e) {
        e.preventDefault();

        $('.addNewCriteria').addClass('hidden');
        $('.editCriteria_clickedBtn').addClass('hidden');
        $('.editt_btns').addClass('hidden');
    })

    $('.saveCriteriaBtn').on('click', function(e) {

        e.preventDefault();
        
        var criteriaCounter = 0;
        
        $('#loaderModal').removeClass('hidden');

        const activityContentID = $(this).data('activity-content-id');
        const activityID = $(this).data('activity-id');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        var baseUrl = window.location.href;


       
        var totalCriteriaScore = 0;
        for (let i = 0; i < activityCriteriaData.length; i++) {
            const activity_content_id = activityCriteriaData[i]['activity_content_id'];
            const criteria_title = activityCriteriaData[i]['criteria_title'];
            const score = activityCriteriaData[i]['score'];

            totalCriteriaScore += parseInt(score, 10);
            const rowCriteria = {
                'activity_content_id': activity_content_id,
                'criteria_title' : criteria_title,
                'score' : parseInt(score, 10)
            }
            console.log(rowCriteria);

            $('#loaderModal').removeClass('hidden');
            if (criteriaCounter === 0) {
                
            var url = baseUrl + "/title/"+ activityID +"/" + activityContentID+"/criteria";
                // alert('1')
                $.ajax({
                    type: "POST",
                    url: url,
                    data: rowCriteria,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if(activityCriteriaData.length === criteriaCounter++) {
                            
        $('#loaderModal').addClass('hidden');
                            window.location.reload();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

                criteriaCounter++;
            }else {
                var url = baseUrl + "/title/"+ activityID +"/" + activityContentID+"/criteria_add";
                // alert('2')
                $.ajax({
                    type: "POST",
                    url: url,
                    data: rowCriteria,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        
        $('#loaderModal').addClass('hidden');
                        window.location.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
                criteriaCounter++;
        } 
        }
        // console.log(totalCriteriaScore);

        var url2 = baseUrl + "/title/"+ activityID +"/" + activityContentID+"/score";
        // console.log(url)

        const udpatedActivityTotalScore = {
            'total_score': totalCriteriaScore,
        };

        $.ajax({
            type: "POST",
            url: url2,
            data: udpatedActivityTotalScore,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                
        $('#loaderModal').addClass('hidden');
                window.location.reload();
            },
            error: function(error) {
                console.log(error);
            }
        });


    })

    var none_count = 0;
    $('.addNewCriteria').on('click', function(e) {
        e.preventDefault();
        
        $('.editCriteria_clickedBtn').addClass('hidden');

        const activityID = $(this).data('activity-id');
        const activityContentID = $(this).data('activity-content-id');

        // console.log(activityCriteriaData)
        var newRow = $('<tr>');

        // Create the first cell (td) with an input field for criteria_title
        var criteriaTitleInput = $('<input type="text" class="addNewCriteriaTitle flex text-center">');
        newRow.append($('<td>').append(criteriaTitleInput));

        // Create the second cell (td) with an input field for score
        var scoreInput = $('<input type="number" class="addNewCriteriaScore flex text-center">');
        newRow.append($('<td>').append(scoreInput));

        // Create the third cell (td) with Save and Delete buttons
        var saveButton = $('<button class="px-3 py-1 mx-2 font-semibold text-white bg-green-600 rounded-xl saveNewCriteriaBtn hover:bg-green-900">Save</button>');
        var deleteButton = $('<button class="px-3 py-1 mx-2 font-semibold text-white bg-red-600 rounded-xl deleteNewCriteriaBtn hover:bg-red-900">Delete</button>');

        newRow.append($('<td>').append(saveButton).append(deleteButton));

        // Append the new row to the table
        $('table tbody').append(newRow);

        // Enable the input fields for criteria and score
        criteriaTitleInput.prop('disabled', false).focus();;
        scoreInput.prop('disabled', false);
        $('.addNewCriteria').addClass('hidden');
        // Add event handlers for the new Save and Delete buttons
        $('.saveNewCriteriaBtn').on('click', function() {
            const criteriaTitle = $(this).closest('tr').find('.addNewCriteriaTitle').val();
            const criteriaScore = $(this).closest('tr').find('.addNewCriteriaScore').val();

            if(criteriaTitle !== null && criteriaTitle !== '' && criteriaScore !== null && criteriaScore !== '') {
// Handle saving the criteria and score to your array (activityCriteriaData)
            // You can use $(this) to access the specific Save button clicked
            // Add your code to save the data in the array
            const newCriteria = {
                activity_content_criteria_id: 'none' + none_count++,
                activity_content_id: activityContentID,
                criteria_title: criteriaTitle,
                score: criteriaScore,
            }

            console.log(newCriteria)

            activityCriteriaData.push(newCriteria);
            reDisplayActivity(activityData, activityCriteriaData);
            $('.addNewCriteria').removeClass('hidden');
            $('.editCriteria_clickedBtn').removeClass('hidden');
            $('.editRowCriteriaBtn').removeClass('hidden');
            } else {
                alert('Please Enter the criteria and score');
                $(this).closest('tr').find('.addNewCriteriaTitle').focus();
            }
            
            $('.editCriteria_clickedBtn').removeClass('hidden');
        });

        $('.deleteNewCriteriaBtn').on('click', function() {
            // Handle deleting the new row if needed
            newRow.remove();
            
            $('.editCriteria_clickedBtn').removeClass('hidden');
        });
    })


    // edit total score
    $('.editTotalScore').on('click', function(e) {
        e.preventDefault();

        $('.editTotalScore_clickedBtn').removeClass('hidden');
        $('.overallScore_input').prop('disabled', false).focus();
    })

    $('.cancelTotalScoreBtn').on('click', function(e) {
        e.preventDefault();

        $('.editTotalScore_clickedBtn').addClass('hidden');
        $('.overallScore_input').prop('disabled', true);
    })

    $('.saveTotalScoreBtn').on('click', function(e) {
        e.preventDefault();
        e.preventDefault();

        const activityContentID = $(this).data('activity-content-id');
        const activityID = $(this).data('activity-id');
        const activity_totalScore = $(this).closest('.editTotalScore_clickedBtn').siblings('input.overallScore_input').val();

        const udpatedActivityTotalScore = {
            'total_score': activity_totalScore,
        };

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // console.log(activityContentID, activity_instructions)
        var baseUrl = window.location.href;
        // console.log(baseUrl);
        var url = baseUrl + "/title/"+ activityID +"/" + activityContentID+"/score";
        // console.log(url)
        
        $('#loaderModal').removeClass('hidden');

        $.ajax({
            type: "POST",
            url: url,
            data: udpatedActivityTotalScore,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                
        $('#loaderModal').addClass('hidden');
                window.location.reload();
            },
            error: function(error) {
                console.log(error);
            }
        });
    })
}

    $('#cancelActivityBtn').on('click', function(e){
        e.preventDefault();

        $('.editInstructionsBtn').addClass('hidden');
        $('.editCriteriaBtn').addClass('hidden');
        $('.editTotalScore').addClass('hidden');

        $('#editActivity_clickedBtns').addClass('hidden');
        $('#editActivityBtn').removeClass('hidden'); 
    })

    $('#saveActivityBtn').on('click', function(e) {
        e.preventDefault();

        $('.editInstructionsBtn').addClass('hidden');
        $('.editCriteriaBtn').addClass('hidden');
        $('.editTotalScore').addClass('hidden');

        $('#editActivity_clickedBtns').addClass('hidden');
        $('#editActivityBtn').removeClass('hidden'); 
    })
});