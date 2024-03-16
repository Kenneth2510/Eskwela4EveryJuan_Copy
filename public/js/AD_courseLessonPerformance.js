$(document).ready(function() {

    var baseUrl = window.location.href

    getLearnerSyllabusLessonProgressData();

    function getLearnerSyllabusLessonProgressData() {
        var url = baseUrl + "/lessonData"
    
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                console.log(response);

                var totalLearnerProgressCount = response['totalLearnerLessonProgressCount']
                var totalLearnerLessonCompleteCount = response['totalLearnerLessonCompleteCount']
                var totalLearnerLessonInProgressCount = response['totalLearnerLessonInProgressCount']
                var totalLearnerLessonLockedProgressCount = response['totalLearnerLessonLockedProgressCount']
                var averageTimeDifference = response['averageTimeDifference']
                var learnerLessonProgressData = response['learnerLessonProgressData']

                $('#totalLearnersCount').text(totalLearnerProgressCount)
                $('#totalLearnerSyllabusCompleteStatus').text(totalLearnerLessonCompleteCount)
                $('#totalLearnerSyllabusInProgressStatus').text(totalLearnerLessonInProgressCount)
                $('#totalLearnerSyllabusNotYetStatus').text(totalLearnerLessonLockedProgressCount)
                $('#averageLearnerProgress').text(averageTimeDifference)

                displayLearnerSyllabusStatusChart(learnerLessonProgressData)
                displayLearnerSyllabusStatusTimeChart(learnerLessonProgressData)
                displayLearnerSyllabusProgressTable(learnerLessonProgressData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function displayLearnerSyllabusStatusChart(learnerLessonProgressData) {


        const statusCounts = {};
        learnerLessonProgressData.forEach((learner) => {
            const status = learner.status;
            statusCounts[status] = (statusCounts[status] || 0) + 1;
        });

        const labels = Object.keys(statusCounts);
        const dataValues = Object.values(statusCounts);

        const dartmouthGreenPalette = [
            '#00693e',
            '#005230',
            '#004224',
        ];

        const ctx = $('#learnerSyllabusStatusChart');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }

        const newChart = new Chart(ctx, {
            type: 'doughnut',
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
                    position: 'top',
                },
            },
        });

        ctx.data('chart', newChart);
    }

    function displayLearnerSyllabusStatusTimeChart(learnerLessonProgressData) {
    
            const completionRates = learnerLessonProgressData.map((learner) => {
                const startPeriod = new Date(learner.start_period);
                const finishPeriod = new Date(learner.finish_period);


                const completionRate = finishPeriod - startPeriod;

                return completionRate / 1000;
            });


            const labels = learnerLessonProgressData.map((learner) => `Learner ${learner.learner_lesson_progress_id}`);

            const ctx = $('#learnerSyllabusStatusTimeChart');

            if (ctx.data('chart')) {
                ctx.data('chart').destroy();
            }


            const newChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Completion Rate (seconds)',
                        data: completionRates,
                        backgroundColor: '#00693e',
                        borderColor: '#00693e',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: false, 
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Completion Rate (seconds)',
                            },
                        },
                    },
                },
            });

            ctx.data('chart', newChart);
    }

    function displayLearnerSyllabusProgressTable(learnerLessonProgressData) {
        learnerLessonProgressDisp = ``;
    
        for (let i = 0; i < learnerLessonProgressData.length; i++) {
            const learner_lesson_progress_id = learnerLessonProgressData[i]['learner_lesson_progress_id'];
            const learner_course_id = learnerLessonProgressData[i]['learner_course_id'];
            const learner_fname = learnerLessonProgressData[i]['learner_fname'];
            const learner_lname = learnerLessonProgressData[i]['learner_lname'];
            const course_id = learnerLessonProgressData[i]['course_id'];
            const learner_id = learnerLessonProgressData[i]['learner_id'];
            const status = learnerLessonProgressData[i]['status'];
            const start_period = learnerLessonProgressData[i]['start_period'];
            const finish_period = learnerLessonProgressData[i]['finish_period'];
    
            const baseUrl = window.location.origin;
            const absoluteUrl = `/admin/performance/learners/view/${learner_id}/course/${course_id}`;
            

            learnerLessonProgressDisp += `
            <tr>
                <td class="mt-5 py-5">${learner_fname} ${learner_lname}</td>
                <td>${status}</td>
                <td>${start_period}</td>
                <td>${finish_period}</td>
                <td>
                <a href="${absoluteUrl}" class="px-5 py-3 bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl text-xl">view</a>
                </td>
            </tr>
            `;
        }
    
        $('.learnerSyllabusProgressRowData').empty();
        $('.learnerSyllabusProgressRowData').append(learnerLessonProgressDisp);
    }
    
})