$(document).ready(function() {
    var baseUrl = window.location.href;

    var csrfToken = $('meta[name="csrf-token"]').attr('content');


    $('#course_carousel_right_btn').on('click', function() {
        var container = $('#courseCardContainer');
        var containerWidth = container.outerWidth();
        container.scrollLeft(container.scrollLeft() + containerWidth);
    });
    
    $('#course_carousel_left_btn').on('click', function() {
        var container = $('#courseCardContainer');
        var containerWidth = container.outerWidth();
        container.scrollLeft(container.scrollLeft() - containerWidth);
    });


    getTotalEnrolledCourse();
    function getTotalEnrolledCourse() {
        var url = baseUrl + "/overviewNum";

        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response)

                var learner = response['learner']
                var learnerCourseData = response['learnerCourseData']
                var totalLearnerCourseCount = response['totalLearnerCourseCount']
                var totalLearnerApprovedCourseCount = response['totalLearnerApprovedCourseCount']
                var totalLearnerPendingCourseCount = response['totalLearnerPendingCourseCount']
                var totalLearnerRejectedCourseCount = response['totalLearnerRejectedCourseCount']
                var totalCoursesLessonCount = response['totalCoursesLessonCount']
                var totalCoursesActivityCount = response['totalCoursesActivityCount']
                var totalCoursesQuizCount = response['totalCoursesQuizCount']
                var totalCoursesLessonCompletedCount = response['totalCoursesLessonCompletedCount']
                var totalCoursesActivityCompletedCount = response['totalCoursesActivityCompletedCount']
                var totalCoursesQuizCompletedCount = response['totalCoursesQuizCompletedCount']
                var totalDaysActive = response['totalDaysActive']
                var totalLearnerCourseCompleted = response['totalLearnerCourseCompleted']


                var totalTopicsCompleted = totalCoursesLessonCompletedCount + totalCoursesActivityCompletedCount + totalCoursesQuizCompletedCount

                $('#totalCoursesText').text(totalLearnerCourseCount)
                $('#totalTopicsText').text(totalTopicsCompleted)
                $('#totalDaysActiveText').text(totalDaysActive)

                $('#totalSyllabusCompletedCount').text(totalTopicsCompleted)
                $('#totalLessonsCompletedCount').text(totalCoursesLessonCompletedCount)
                $('#totalActivitiesCompletedCount').text(totalCoursesActivityCompletedCount)
                $('#totalQuizzesCompletedCount').text(totalCoursesQuizCompletedCount)

                $('#completionRate').text(totalLearnerCourseCompleted)

                courseProgressGraph(learnerCourseData);
                init_chatbot(learner)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function courseProgressGraph(learnerCourseData) {

        const statusCounts = {};
        
        learnerCourseData.forEach((learner) => {
            const status = learner.course_progress;
            statusCounts[status] = (statusCounts[status] || 0) + 1;
        });
    
        const labels = Object.keys(statusCounts);
        const dataValues = labels.map((label) => statusCounts[label]);
    
        const dartmouthGreenPalette = [
            '#00693e',
            '#005230',
            '#004224',
        ];
    
        const ctx = $('#courseProgressGraph');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: dartmouthGreenPalette,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Status',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Data Points',
                        },
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }

    sessionDataGraph();
    function sessionDataGraph() {
        var url = baseUrl + "/sessionData";

        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response)

                var totalsPerDay = response['totalsPerDay']

                dispSessionDataGraph(totalsPerDay)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function dispSessionDataGraph(totalsPerDay) {
        // Function to convert seconds to hh:mm:ss format
        function secondsToHMS(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;
    
            const format = (value) => (value < 10 ? `0${value}` : value);
    
            return `${format(hours)}:${format(minutes)}:${format(remainingSeconds)}`;
        }
    
        const labels = totalsPerDay.map(item => item.date);
        const dataValues = totalsPerDay.map(item => parseInt(item.total_seconds));
        const formattedTimeValues = totalsPerDay.map(item => secondsToHMS(parseInt(item.total_seconds)));
    
        const ctx = $('#learnerSessionGraph');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    borderColor: '#00693e',
                    fill: true,
                    lineTension: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Time (hh:mm:ss)',
                        },
                        ticks: {
                            // Customize y-axis ticks to show formatted time
                            callback: function(value) {
                                return secondsToHMS(value);
                            },
                        },
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }






    function init_chatbot(learner) {
        var learner_id = learner['learner_id'];
        var url = `/chatbot/init/${learner_id}`;
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);
    
                add_learner_data(learner_id);
                process_files(learner_id);
    
                $('.submitQuestion').on('click', function(e) {
                    e.preventDefault();
                    submitQuestion();
                });
    
                $('.question_input').on('keydown', function(e) {
                    if (e.keyCode === 13) {
                        e.preventDefault();
                        submitQuestion();
                    }
                });
    
                function submitQuestion() {
                    var question = $('.question_input').val();
                    var course = '';
                    var lesson = '';
    
                    displayUserMessage(question, learner);
                    $('.botloader').removeClass('hidden');
                    var chatData = {
                        question: question,
                        course: course,
                        lesson: lesson,
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
                            $('.question_input').val('')
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
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
                       
                $('.loaderArea').addClass('hidden');
                $('.mainchatbotarea').removeClass('hidden');
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function add_learner_data(learner_id) {
               // console.log(learner);
               var url = `/chatbot/learner/${learner_id}`;
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

    function displayUserMessage(question, learner) {
        var userMessageDisp = ``;
        var profile = learner['profile_picture']
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();

        minutes = minutes < 10 ? '0' + minutes : minutes;

        var timeString = hours + ':' + minutes;
    
        userMessageDisp += `
        
        <div class="mx-3 chat chat-end">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full">
                <img class="bg-red-500" alt="" src="/storage/${profile}" />
                </div>
            </div>
            <div class="mx-3 chat-header">
                You
            </div>
            <div class="whitespace-pre-wrap chat-bubble chat-bubble-primary">${question}</div>
            <div class="opacity-50 chat-footer">
            ${timeString}
            </div>
        </div>
        `;

        $('.chatContainer').append(userMessageDisp);
    }


    function displayBotMessage(response) {

        var message = response['message']

        var botMessageDisp = ``
        botMessageDisp += `
        
        <div class="chat chat-start">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full">
                <img class="bg-white" alt="" src="../../storage/images/chatbot.png" />
                </div>
            </div>
            <div class="chat-bubble ">${message}</div>
        </div>
        `;

        $('.botloader').addClass('hidden')
        $('.chatContainer').append(botMessageDisp);

        var chatContainer = $('.chatContainer')[0];
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    

})