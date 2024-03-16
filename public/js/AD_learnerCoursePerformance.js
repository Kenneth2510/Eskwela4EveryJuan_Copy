$(document).ready(function() {

    var baseUrl = window.location.href

    getLearnerCoursePerformanceData();
    getSyllabusPerformanceData();

    function getLearnerCoursePerformanceData() {

        var url = baseUrl + "/coursePerformance"

        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                console.log(response);

                var averageTimeFormatted = response['averageTimeFormatted']
                var learnerCourseData = response['learnerCourseData']
                var learnerCourseCount = response['learnerCourseCount']
                var learnerCompletedSyllabusCount = response['learnerCompletedSyllabusCount']
                var learnerInProgressSyllabusCount = response['learnerInProgressSyllabusCount']
                var learnerLockedSyllabusCount = response['learnerLockedSyllabusCount']
                var percentageCompleted = response['percentageCompleted']

                $('#learnerPerformancePercent').text(percentageCompleted)
                $('#totalCompletedSyllabus').text(learnerCompletedSyllabusCount)
                $('#totalInProgressSyllabus').text(learnerInProgressSyllabusCount)
                $('#totalLockedSyllabus').text(learnerLockedSyllabusCount)
                $('#averageLearnerProgress').text(averageTimeFormatted)
            },
            error: function(error) {
                console.log(error);
            }
        });   
    }


    function getSyllabusPerformanceData() {

        var url = baseUrl + "/syllabusPerformance"

        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                console.log(response);

                var averageLessonTimeFormatted = response['averageLessonTimeFormatted']
                var averageActivityTimeFormatted = response['averageActivityTimeFormatted']
                var averageQuizTimeFormatted = response['averageQuizTimeFormatted']

                var learnerLessonPerformanceData = response['learnerLessonPerformanceData']
                var learnerLessonCompletedPerformanceData = response['learnerLessonCompletedPerformanceData']

                var learnerActivityPerformanceData = response['learnerActivityPerformanceData']
                var learnerActivityCompletedPerformanceData = response['learnerActivityCompletedPerformanceData']
                var learnerActivityCompletedOutputData = response['learnerActivityCompletedOutputData']
                
                var learnerQuizPerformanceData = response['learnerQuizPerformanceData']
                var learnerQuizCompletedPerformanceData = response['learnerQuizCompletedPerformanceData']
                
                $('#averageLearnerLessonProgress').text(averageLessonTimeFormatted)

                displayLearnerLessonProgressChartTable(learnerLessonPerformanceData)
                displayLearnerLessonProgressChart(learnerLessonPerformanceData)
                displayLearnerLessonProgressLineChart(learnerLessonCompletedPerformanceData)
            
                displayLearnerActivityProgressChart(learnerActivityPerformanceData)
                displayLearnerActivityProgressChartTable(learnerActivityPerformanceData)
                displayLearnerActivityProgressLineChart(learnerActivityCompletedPerformanceData)
                displayLearnerActivityCompletedOutputData(learnerActivityCompletedOutputData)

                displayLearnerQuizProgressChart(learnerQuizPerformanceData)
                displayLearnerQuizProgressChartTable(learnerQuizPerformanceData)
                displayLearnerQuizProgressLineChart(learnerQuizCompletedPerformanceData)
                displayQuizCompletionRemarksBarChart(learnerQuizCompletedPerformanceData)
            },
            error: function(error) {
                console.log(error);
            }
        }); 
    }


    function displayLearnerLessonProgressChartTable(learnerLessonPerformanceData) {
        var learnerLessonProgressChartTableDisp = ``;

        for (let i = 0; i < learnerLessonPerformanceData.length; i++) {
            const learner_lesson_progress_id = learnerLessonPerformanceData[i]['learner_lesson_progress_id'];
            const lesson_id = learnerLessonPerformanceData[i]['lesson_id'];
            const topic_title = learnerLessonPerformanceData[i]['topic_title'];
            const status = learnerLessonPerformanceData[i]['status'];
            const start_period = learnerLessonPerformanceData[i]['start_period'];
            const finish_period = learnerLessonPerformanceData[i]['finish_period'];
            
            learnerLessonProgressChartTableDisp += `
            <tr>
                <td class="py-5">${topic_title}</td>
                <td>${status}</td>
                <td>${start_period}</td>
                <td>${finish_period}</td>
            </tr>
            `;
        }

        $('.learnerLessonProgressRowData').empty();
        $('.learnerLessonProgressRowData').append(learnerLessonProgressChartTableDisp);
    }

    function displayLearnerLessonProgressChart(learnerLessonPerformanceData) {
        const statusCounts = {};
        
        learnerLessonPerformanceData.forEach((learner) => {
            const status = learner.status;
            statusCounts[status] = (statusCounts[status] || 0) + 1;
        });
    
        const labels = Object.keys(statusCounts);
        const dataValues = labels.map((label) => statusCounts[label]);
    
        const dartmouthGreenPalette = [
            '#00693e',
            '#005230',
            '#004224',
        ];
    
        const ctx = $('#learnerLessonProgressChart');
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
    

    function displayLearnerLessonProgressLineChart(learnerLessonCompletedPerformanceData) {
        const labels = learnerLessonCompletedPerformanceData.map((learner) => learner.topic_title);
        const dataValues = learnerLessonCompletedPerformanceData.map((learner) => convertTimeToSeconds(learner.time_difference));
    
        const ctx = $('#learnerLessonProgressLineChart');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Time used',
                    data: dataValues,
                    borderColor: '#00693e',
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Topic Title',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Time Used (seconds)',
                        },
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }
    
    function convertTimeToSeconds(time) {
        const parts = time.split(':');
        return (+parts[0]) * 3600 + (+parts[1]) * 60 + (+parts[2]);
    }
    

    function displayLearnerActivityProgressChartTable(learnerActivityPerformanceData) {
        var learnerActivityProgressChartTableDisp = ``;

        for (let i = 0; i < learnerActivityPerformanceData.length; i++) {
            const learner_activity_progress_id = learnerActivityPerformanceData[i]['learner_activity_progress_id'];
            const course_id = learnerActivityPerformanceData[i]['course_id'];
            const syllabus_id = learnerActivityPerformanceData[i]['syllabus_id'];
            const activity_id = learnerActivityPerformanceData[i]['activity_id'];
            const learner_course_id = learnerActivityPerformanceData[i]['learner_course_id'];
            const topic_title = learnerActivityPerformanceData[i]['topic_title'];
            const topic_id = learnerActivityPerformanceData[i]['topic_id'];
            const status = learnerActivityPerformanceData[i]['status'];
            const start_period = learnerActivityPerformanceData[i]['start_period'];
            const finish_period = learnerActivityPerformanceData[i]['finish_period'];
            
            const redirect_url = `/admin/courseManage/content/${course_id}/${syllabus_id}/activity/${topic_id}/${learner_course_id}/1`
            
            
            learnerActivityProgressChartTableDisp += `
            <tr>
                <td class="py-5">${topic_title}</td>
                <td>${status}</td>
                <td>${start_period}</td>
                <td>${finish_period}</td>
                <td>
                    <a href="${redirect_url}" class="px-3 py-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl">view</a>
                </td>
            </tr>
            `;
        }

        $('.learnerActivityProgressRowData').empty();
        $('.learnerActivityProgressRowData').append(learnerActivityProgressChartTableDisp);
    }

    function displayLearnerActivityProgressChart(learnerActivityPerformanceData) {
        const statusCounts = {};
        
        learnerActivityPerformanceData.forEach((learner) => {
            const status = learner.status;
            statusCounts[status] = (statusCounts[status] || 0) + 1;
        });
    
        const labels = Object.keys(statusCounts);
        const dataValues = labels.map((label) => statusCounts[label]);
    
        const dartmouthGreenPalette = [
            '#00693e',
            '#005230',
            '#004224',
        ];
    
        const ctx = $('#learnerActivityProgressChart');
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


    function displayLearnerActivityProgressLineChart(learnerActivityCompletedPerformanceData) {
        const labels = learnerActivityCompletedPerformanceData.map((learner) => learner.topic_title);
        const dataValues = learnerActivityCompletedPerformanceData.map((learner) => convertTimeToSeconds(learner.time_difference));
    
        const ctx = $('#learnerActivityProgressLineChart');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Time Used',
                    data: dataValues,
                    borderColor: '#00693e',
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Topic Title',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Time Used (seconds)',
                        },
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }

    function displayLearnerActivityCompletedOutputData(learnerActivityCompletedOutputData) {
        // Count the number of PASS and FAIL
        const passCount = learnerActivityCompletedOutputData.filter(quiz => quiz.mark === 'PASS').length;
        const failCount = learnerActivityCompletedOutputData.filter(quiz => quiz.mark === 'FAIL').length;
    
        const labels = ['PASS', 'FAIL'];
        const dataValues = [passCount, failCount];
    
        const ctx = $('#learnerActivityProgressRemarksChart');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const dartmouthGreenPalette = [
            '#00693e',
            '#005230',
            '#004224',
        ];

        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Activity Completion',
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
                            text: 'Remarks',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Activity Attempt',
                        },
                        ticks: {
                            beginAtZero: true,
                        },
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }

    function displayLearnerQuizProgressChart(learnerQuizPerformanceData) {
        const statusCounts = {};
        
        learnerQuizPerformanceData.forEach((learner) => {
            const status = learner.status;
            statusCounts[status] = (statusCounts[status] || 0) + 1;
        });
    
        const labels = Object.keys(statusCounts);
        const dataValues = labels.map((label) => statusCounts[label]);
    
        const dartmouthGreenPalette = [
            '#00693e',
            '#005230',
            '#004224',
        ];
    
        const ctx = $('#learnerQuizProgressChart');
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
    
    function displayLearnerQuizProgressChartTable(learnerQuizPerformanceData) {
        var learnerActivityProgressChartTableDisp = ``;

        for (let i = 0; i < learnerQuizPerformanceData.length; i++) {
            const learner_quiz_progress_id = learnerQuizPerformanceData[i]['learner_quiz_progress_id'];
            const course_id = learnerQuizPerformanceData[i]['course_id'];
            const syllabus_id = learnerQuizPerformanceData[i]['syllabus_id'];
            const quiz_id = learnerQuizPerformanceData[i]['quiz_id'];
            const learner_course_id = learnerQuizPerformanceData[i]['learner_course_id'];
            const topic_title = learnerQuizPerformanceData[i]['topic_title'];
            const topic_id = learnerQuizPerformanceData[i]['topic_id'];
            const status = learnerQuizPerformanceData[i]['status'];
            const score = learnerQuizPerformanceData[i]['score'];
            const attempt = learnerQuizPerformanceData[i]['attempt'];
            const remarks = learnerQuizPerformanceData[i]['remarks'];
            const start_period = learnerQuizPerformanceData[i]['start_period'];
            const finish_period = learnerQuizPerformanceData[i]['finish_period'];
            
            const redirect_url = `/admin/courseManage/content/${course_id}/${syllabus_id}/quiz/${topic_id}/view_learner_output/${learner_quiz_progress_id}`

            
            learnerActivityProgressChartTableDisp += `
            <tr>
                <td class="w-2/12 py-5">${topic_title}</td>
                <td class="w-1/12">${status}</td>
                <td class="w-1/12">${attempt}</td>
                <td class="w-1/12">${score}</td>
                <td class="w-1/12">${remarks}</td>
                <td class="w-2/12">${start_period}</td>
                <td class="w-2/12">${finish_period}</td>
                <td class="w-2/12">
                    <a href="${redirect_url}" class="px-3 py-3 text-white bg-darthmouthgreen hover:bg-green-950 rounded-xl">view</a>
                </td>
            </tr>
            `;
        }

        $('.learnerQuizProgressRowData').empty();
        $('.learnerQuizProgressRowData').append(learnerActivityProgressChartTableDisp);
    }

    function displayLearnerQuizProgressLineChart(learnerQuizCompletedPerformanceData) {
        const labels = learnerQuizCompletedPerformanceData.map((learner) => learner.topic_title);
        const dataValues = learnerQuizCompletedPerformanceData.map((learner) => convertTimeToSeconds(learner.time_difference));
    
        const ctx = $('#learnerQuizProgressLineChart');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Time Used',
                    data: dataValues,
                    borderColor: '#00693e',
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Topic Title',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Time used (seconds)',
                        },
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }

    function displayQuizCompletionRemarksBarChart(learnerQuizCompletedPerformanceData) {
        // Count the number of PASS and FAIL
        const passCount = learnerQuizCompletedPerformanceData.filter(quiz => quiz.remarks === 'PASS').length;
        const failCount = learnerQuizCompletedPerformanceData.filter(quiz => quiz.remarks === 'FAIL').length;
    
        const labels = ['PASS', 'FAIL'];
        const dataValues = [passCount, failCount];
    
        const ctx = $('#learnerQuizProgressRemarksChart');
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const dartmouthGreenPalette = [
            '#00693e',
            '#005230',
            '#004224',
        ];

        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quiz Completion',
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
                            text: 'Remarks',
                        },
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Quizzes',
                        },
                        ticks: {
                            beginAtZero: true,
                        },
                    },
                },
            },
        });
    
        ctx.data('chart', newChart);
    }
})