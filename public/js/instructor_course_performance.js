$(document).ready(function(){

    var baseUrl = window.location.href


    var totalLearnerCourseCount;
    var totalApprovedLearnerCourseCount;
    var totalPendingLearnerCourseCount;
    var totalRejectedLearnerCourseCount;

    var totalSyllabusCount;
    var totalLessonsCount;
    var totalActivitiesCount;
    var totalQuizzesCount;

    getCourseData();
    getLearnerCourseProgressData();


    function getCourseData() {

        var url = baseUrl + "/performanceData"
    
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                // console.log(response);

                totalLearnerCourseCount = response['totalLearnerCourseCount']
                totalApprovedLearnerCourseCount = response['totalApprovedLearnerCourseCount']
                totalPendingLearnerCourseCount = response['totalPendingLearnerCourseCount']
                totalRejectedLearnerCourseCount = response['totalRejectedLearnerCourseCount']
                
                totalSyllabusCount = response['totalSyllabusCount']
                totalLessonsCount = response['totalLessonsCount']
                totalActivitiesCount = response['totalActivitiesCount']
                totalQuizzesCount = response['totalQuizzesCount']

                $('#totalLearnerCourseCount').text(totalLearnerCourseCount);
                $('#totalApprovedLearnerCourseCount').text(totalApprovedLearnerCourseCount);
                $('#totalPendingLearnerCourseCount').text(totalPendingLearnerCourseCount);
                $('#totalRejectedLearnerCourseCount').text(totalRejectedLearnerCourseCount);

                $('#totalSyllabusCount').text(totalSyllabusCount);
                $('#totalLessonsCount').text(totalLessonsCount);
                $('#totalActivitiesCount').text(totalActivitiesCount);
                $('#totalQuizzesCount').text(totalQuizzesCount);

            },
            error: function(error) {
                console.log(error);
            }
        });
    }

        function getLearnerCourseProgressData() {
            var url = baseUrl + "/learnerCourseData"
    
            $.ajax({
                type: "GET",
                url: url,
                success: function (response) {
                    // console.log(response);

                    // var learnerCourseData = response[learnerCourseData]
                    var learnerCourseProgressData = response['learnerCourseProgressData']
                    displayLearnerCourseProgressData(learnerCourseProgressData)
                    displayLearnerCourseProgressChart(learnerCourseProgressData)
                },
                error: function(error) {
                    console.log(error);
                }
            });
    }


    function displayLearnerCourseProgressData(learnerCourseProgressData) {
        learnerCourseProgressDataDisp = ``;
        for (let i = 0; i < learnerCourseProgressData.length; i++) {

            const learner_course_progress_id = learnerCourseProgressData[i]['learner_course_progress_id'];
            const learner_course_id = learnerCourseProgressData[i]['learner_course_id'];
            const course_progress = learnerCourseProgressData[i]['course_progress'];
            const learner_fname = learnerCourseProgressData[i]['learner_fname'];
            const learner_lname = learnerCourseProgressData[i]['learner_lname'];
            const start_period = learnerCourseProgressData[i]['start_period'];
            const finish_period = learnerCourseProgressData[i]['finish_period'];
            
            const url = baseUrl + "/learner/" + learner_course_id
            learnerCourseProgressDataDisp += `
            <tr class="text-center">
                <td class="mt-5 py-5">${learner_fname} ${learner_lname}</td>
                <td>${start_period}</td>
                <td>${course_progress}</td>
                <td>
                    <a href="${url}" class="px-5 py-3 bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl">view</a>
                </td>
            </tr>
            `
        }

        $('.learnerCourseRowData').empty();
        $('.learnerCourseRowData').append(learnerCourseProgressDataDisp);
    }

    function displayLearnerCourseProgressChart(learnerCourseProgressData) {


        const progressData = learnerCourseProgressData.map(item => item.course_progress);

        const allProgressCategories = ['IN PROGRESS', 'NOT YET STARTED', 'COMPLETED'];

        const progressCounts = {};
        allProgressCategories.forEach(category => {
            progressCounts[category] = progressData.filter(progress => progress === category).length;
        });

        const labels = Object.keys(progressCounts);
        const dataValues = Object.values(progressCounts);

        const ctx = $('#learnerCourseDataChart');

        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }

        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '# of Enrolled Learners',
                    data: dataValues,
                    borderWidth: 1,
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1,
                    }
                }
            }
        });

        ctx.data('chart', newChart);
    }

    var learnerSyllabusData;
    $('#selectTopic').on('change', function(e) {
        e.preventDefault();

        var syllabus_id = $('#selectTopic').val();

        var viewMoreLink = $('#learnerCourseTopicProgressTable a');
        viewMoreLink.attr("href", baseUrl + "/syllabus/" + syllabus_id);

        var url = baseUrl + "/learnerSyllabusData"
    
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    syllabus_id: syllabus_id
                },
                success: function (response) {
                    console.log(response);

                    learnerSyllabusData = response['learnerSyllabusData'];
                    displayLearnerSyllabusProgressChart(learnerSyllabusData)
                    displayLearnerSyllabusProgressTable(learnerSyllabusData)
                },
                error: function(error) {
                    console.log(error);
                }
            });
    })


    function displayLearnerSyllabusProgressChart(learnerSyllabusData) {

        const statusData = learnerSyllabusData.map(item => item.status);

        const statusCounts = {}
        statusData.forEach(status => {
            statusCounts[status] = (statusCounts[status] || 0) + 1;
        });

        const labels = Object.keys(statusCounts);
        const dataValues = Object.values(statusCounts);

        const ctx = $('#learnerTopicDataChart');

        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }

        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '# of Enrolled Learners',
                    data: dataValues,
                    borderWidth: 1,
                    backgroundColor: '#00693e',
                    borderColor: '#00693e',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1,
                    }
                }
            }
        });

        ctx.data('chart', newChart);
    }


    function displayLearnerSyllabusProgressTable(learnerSyllabusData) {
        var learnerSyllabusProgressDisp =``;

        for (let i = 0; i < learnerSyllabusData.length; i++) {
            const learner_progress_id = learnerSyllabusData[i]['learner_progress_id'];
            const learner_course_id = learnerSyllabusData[i]['learner_course_id'];
            const course_id = learnerSyllabusData[i]['course_id'];
            const syllabus_id = learnerSyllabusData[i]['syllabus_id'];
            const topic_id = learnerSyllabusData[i]['topic_id'];
            const status = learnerSyllabusData[i]['status'];
            const learner_fname = learnerSyllabusData[i]['learner_fname'];
            const learner_lname = learnerSyllabusData[i]['learner_lname'];
            const start_period = learnerSyllabusData[i]['start_period'];
            const finish_period = learnerSyllabusData[i]['finish_period'];
            const created_at = learnerSyllabusData[i]['created_at'];
            
            const url = baseUrl + "/learner/" + learner_course_id
            learnerSyllabusProgressDisp += `
            <tr class="text-center">
                <td class="mt-5 py-5">${learner_fname} ${learner_lname}</td>
                <td>${created_at}</td>
                <td>${status}</td>
                <td>${start_period}</td>
                <td>${finish_period}</td>
                <td>
                    <a href="${url}" class="px-5 py-3 bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl">view</a>
                </td>
            </tr>  
            `;
        }

        $('.learnerSyllabusRowData').empty();
        $('.learnerSyllabusRowData').append(learnerSyllabusProgressDisp);
    }
})