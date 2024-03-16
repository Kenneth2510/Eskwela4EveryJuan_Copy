$(document).ready(function(e) {
    
    var baseUrl = window.location.href

    getLearnerSyllabusActivityProgressData()
    getActivityOutputScoreData()

    function getLearnerSyllabusActivityProgressData() {
        var url = baseUrl + "/activityData"
    
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                console.log(response);

                var totalLearnerActivityProgressCount = response['totalLearnerActivityProgressCount']
                var totalLearnerActivityCompleteCount = response['totalLearnerActivityCompleteCount']
                var totalLearnerActivityInProgressCount = response['totalLearnerActivityInProgressCount']
                var totalLearnerActivityLockedProgressCount = response['totalLearnerActivityLockedProgressCount']
                var averageTimeDifference = response['averageTimeDifference']
                var learnerActivityProgressData = response['learnerActivityProgressData']
                var attemptCount = response['attemptCount']

                $('#totalLearnersCount').text(totalLearnerActivityProgressCount)
                $('#totalLearnerSyllabusCompleteStatus').text(totalLearnerActivityCompleteCount)
                $('#totalLearnerSyllabusInProgressStatus').text(totalLearnerActivityInProgressCount)
                $('#totalLearnerSyllabusNotYetStatus').text(totalLearnerActivityLockedProgressCount)
                $('#averageLearnerProgress').text(averageTimeDifference)

                displayLearnerSyllabusStatusChart(learnerActivityProgressData)
                displayLearnerSyllabusStatusTimeChart(learnerActivityProgressData)
                displayLearnerSyllabusAttemptNumberChart(attemptCount)
                displayLearnerSyllabusProgressTableArea(learnerActivityProgressData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function displayLearnerSyllabusStatusChart(learnerActivityProgressData) {
        const statusCounts = {};
        learnerActivityProgressData.forEach((learner) => {
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


    function displayLearnerSyllabusStatusTimeChart(learnerActivityProgressData) {
        const completionRates = learnerActivityProgressData.map((learner) => {
            const startPeriod = new Date(learner.start_period);
            const finishPeriod = new Date(learner.finish_period);


            const completionRate = finishPeriod - startPeriod;

            return completionRate / 1000;
        });


        const labels = learnerActivityProgressData.map((learner) => `Learner ${learner.learner_lesson_progress_id}`);

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


    function displayLearnerSyllabusAttemptNumberChart(attemptCount) {
        const ctx = $('#learnerSyllabusAttemptNumberChart');

        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }

        const oneAttemptCount = attemptCount.OneAttempt || 0;
        const reAttemptsCount = attemptCount.ReAttempts || 0;

        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['One Attempt', 'Reattempts'],
                datasets: [{
                    label: 'Number of Attempts',
                    data: [oneAttemptCount, reAttemptsCount],
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
                        display: true,
                        title: {
                            display: true,
                            text: 'Attempts',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Attempts',
                        },
                        stepSize: 1,
                    },
                },
            },
        });

        ctx.data('chart', newChart);
    }

    function getActivityOutputScoreData() {
        var url = baseUrl + "/activityData/outputs"
    
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                console.log(response);

                var learnerActivityOutputOverallScoreData = response['learnerActivityOutputOverallScoreData']
                var learnerActivityOutputCriteriaScoreData = response['learnerActivityOutputCriteriaScoreData']
            
                displayLearnerSyllabusOverallScoreChart(learnerActivityOutputOverallScoreData)
                displayLearnerSyllabusCriteriaScoreChart(learnerActivityOutputCriteriaScoreData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function displayLearnerSyllabusOverallScoreChart(learnerActivityOutputOverallScoreData) {
        const ctx = $('#learnerSyllabusOverallScoreChart');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const learnerData = learnerActivityOutputOverallScoreData.map(data => ({
            label: `${data.learner_fname} ${data.learner_lname}`,
            score: data.total_score,
            attempt: data.attempt,
        }));
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: learnerData.map(data => data.label),
                datasets: [{
                    label: 'Total Scores',
                    data: learnerData.map(data => data.score),
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
                        display: true,
                        title: {
                            display: true,
                            text: 'Learners',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Scores',
                        },
                        stepSize: 1,
                    },
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const data = learnerData[context.dataIndex];
                                return `${data.label}, Attempt ${data.attempt}: ${data.score} points`;
                            },
                        },
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }

    function displayLearnerSyllabusCriteriaScoreChart(learnerActivityOutputCriteriaScoreData) {
        const ctx = $('#learnerSyllabusCriteriaScoreChart');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const learnerIds = Object.keys(learnerActivityOutputCriteriaScoreData);
        const criteriaTitles = Array.from(new Set(
            learnerIds.flatMap(id => learnerActivityOutputCriteriaScoreData[id].map(item => item.criteria_title))
        ));
    
        const scores = Array.from(new Set(
            learnerIds.flatMap(id => learnerActivityOutputCriteriaScoreData[id].map(item => item.score))
        ));
    
        // Define more contrasting colors for Dartmouth Green
        const dartmouthGreenContrasting = ['#00693e', '#00441e', '#002300', '#005738', '#003023'];
    
        const datasets = scores.map((score, index) => {
            const data = criteriaTitles.map(title => {
                const count = learnerIds.reduce((acc, id) => {
                    const criteria = learnerActivityOutputCriteriaScoreData[id].find(item => item.criteria_title === title && item.score === score);
                    return acc + (criteria ? 1 : 0);
                }, 0);
    
                return count;
            });
    
            return {
                label: `Score ${score}`,
                data: data,
                backgroundColor: dartmouthGreenContrasting[index % dartmouthGreenContrasting.length],
                borderColor: dartmouthGreenContrasting[index % dartmouthGreenContrasting.length],
                borderWidth: 1,
            };
        });
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: criteriaTitles,
                datasets: datasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Criteria',
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count',
                        },
                        stepSize: 1,
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }
    


    function displayLearnerSyllabusProgressTableArea(learnerActivityProgressData) {
        var learnerSyllabusProgressTableDisp = ``;

        for (let i = 0; i < learnerActivityProgressData.length; i++) {
            const learner_activity_progress_id = learnerActivityProgressData[i]['learner_activity_progress_id'];
            const learner_course_id = learnerActivityProgressData[i]['learner_course_id'];
            const activity_id = learnerActivityProgressData[i]['activity_id'];
            const course_id = learnerActivityProgressData[i]['course_id'];
            const syllabus_id = learnerActivityProgressData[i]['syllabus_id'];
            const topic_id = learnerActivityProgressData[i]['topic_id'];
            const learner_fname = learnerActivityProgressData[i]['learner_fname'];
            const learner_lname = learnerActivityProgressData[i]['learner_lname'];
            const status = learnerActivityProgressData[i]['status'];
            const start_period = learnerActivityProgressData[i]['start_period'];
            const finish_period = learnerActivityProgressData[i]['finish_period'];
            
            const absoluteUrl = `/instructor/course/content/${course_id}/${syllabus_id}/activity/${topic_id}/${learner_course_id}/1`;

            learnerSyllabusProgressTableDisp += `
            <tr>
                <td class="mt-5 py-5">${learner_fname} ${learner_lname}</td>
                <td>${status}</td>
                <td>${start_period}</td>
                <td>${finish_period}</td>
                <td>
                <a href="${absoluteUrl}" class="px-5 py-3 bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl text-xl">view</a>
                </td>
            </tr>
            `
        }

        $('.learnerSyllabusProgressRowData').empty();
        $('.learnerSyllabusProgressRowData').append(learnerSyllabusProgressTableDisp);
    }
    
})

