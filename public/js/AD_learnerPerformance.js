$(document).ready(function() {
    // alert('test')
    var baseUrl = window.location.href;

    getTotalEnrolledCourse()
    getEnrolledCourseData()

    function getTotalEnrolledCourse() {
        var url = baseUrl + "/totalEnrolledCourses";

        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response)

                var learnerCourseData = response['learnerCourseData']
                var totalLearnerCourseCount = response['totalLearnerCourseCount']
                // var totalLearnerCompletedCourseCount = response['totalLearnerCompletedCourseCount']
                // var totalLearnerInProgressCourseCount = response['totalLearnerInProgressCourseCount']
                var totalLearnerApprovedCourseCount = response['totalLearnerApprovedCourseCount']
                var totalLearnerPendingCourseCount = response['totalLearnerPendingCourseCount']
                var totalLearnerRejectedCourseCount = response['totalLearnerRejectedCourseCount']
                var totalCoursesLessonCount = response['totalCoursesLessonCount']
                var totalCoursesActivityCount = response['totalCoursesActivityCount']
                var totalCoursesQuizCount = response['totalCoursesQuizCount']
                var totalCoursesLessonCompletedCount = response['totalCoursesLessonCompletedCount']
                var totalCoursesActivityCompletedCount = response['totalCoursesActivityCompletedCount']
                var totalCoursesQuizCompletedCount = response['totalCoursesQuizCompletedCount']

                $('#totalCourseNum').text(totalLearnerCourseCount)
                $('#totalApprovedCourse').text(totalLearnerApprovedCourseCount)
                $('#totalPendingCourse').text(totalLearnerPendingCourseCount)
                $('#totalRejectedCourse').text(totalLearnerRejectedCourseCount)

                $('#totalSyllabusCompletedCount').text(totalCoursesLessonCount)
                $('#totalLessonsCount').text(totalCoursesLessonCount)
                $('#totalActivitiesCount').text(totalCoursesActivityCount)
                $('#totalQuizzesCount').text(totalCoursesQuizCount)
                $('#totalLessonsCompletedCount').text(totalCoursesLessonCompletedCount)
                $('#totalActivitiesCompletedCount').text(totalCoursesActivityCompletedCount)
                $('#totalQuizzesCompletedCount').text(totalCoursesQuizCompletedCount)

                displayCourseListArea(learnerCourseData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function getEnrolledCourseData() {
        var url = baseUrl + "/enrolledCoursesData";

        var selectedCourse = $('#perCourseSelectArea').val();

        $.ajax({
            type: "GET",
            url: url,
            data: {
                'selectedCourse': selectedCourse
            },
            success: function(response) {
                // console.log(response)

                var courseData = response['courseData']
            
                displayPerCourseInfo(response, courseData, selectedCourse)
                displayCourseDataChart(response, courseData, selectedCourse)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    $('#perCourseSelectArea').on('change', function() {
        getEnrolledCourseData();
    })


    function displayPerCourseInfo(response, courseData, selectedCourse) {
        var courseDataDisp = ``;
        if(selectedCourse === 'ALL') {
            courseDataDisp += `
            <h1 class="text-2xl p-5 font-semibold">All Courses</h1>
            <div class=" my-7 mx-5 h-1/3 text-center item-center flex justify-center">
                        <i class="fa-solid fa-book-open-reader text-darthmouthgreen text-[100px]"></i>
                        <p class="font-bold pt-5 mx-5 text-2xl"><span class="text-darthmouthgreen text-[75px]" id="totalCourseNum">${response['totalLearnerCourseCount']}</span><br>Total Courses</p>
                    </div>
                    <div class="mx-10 mt-10">
                        <div class="flex items-center mx-1">
                            <div class="rounded-full w-3 h-3 mx-1 bg-darthmouthgreen"></div>
                            <p class="font-bold text-md">COMPLETED: <span id="totalApprovedCourse" class="">${response['totalLearnerCompletedCourseCount']}</span></p>
                        </div>

                        <div class="flex items-center mx-1">
                            <div class="rounded-full w-3 h-3 mx-1 bg-yellow-400"></div>
                            <p class="font-bold text-md">IN PROGRESS: <span id="totalPendingCourse" class="">${response['totalLearnerInProgressCourseCount']}</span></p>
                        </div>

                    </div>
            `
        } else {
            courseDataDisp += `
            <div class="w-full text-center text-[100px]"><i class="fa-solid fa-book-open-reader text-[100px] text-darthmouthgreen"></i></div>
            <h1 class="text-2xl h-1/3 text-darthmouthgreen font-semibold">${courseData[0]['course_name']}</h1>
            <p class=""><span class="" id="">${courseData[0]['course_code']}</span></p>
            <p class="">Created at: <span class="" id="">${response['learnerCourseProgressData']['start_period']}</span></p>
           
            <div class="mt-10 flex justify-between">
                <div class="">
                    <div class="flex items-center">
                        <i class="fa-solid fa-file text-darthmouthgreen text-xl mx-3"></i>
                        <p class="font-bold text-md">Lessons: <span id="totalLessonsCount" class="">${response['totalCourseLessonSyllabusCount']}</span></p>
                    </div>
        
                    <div class="flex items-center">
                        <i class="fa-solid fa-clipboard text-darthmouthgreen text-xl mx-3"></i>
                        <p class="font-bold text-md">Activities: <span id="totalActivitiesCount" class="">${response['totalCourseActivitySyllabusCount']}</span></p>
                    </div>
        
                    <div class="flex items-center">
                        <i class="fa-solid fa-pen-to-square text-darthmouthgreen text-xl mx-3"></i>
                        <p class="font-bold text-md">Quizzes: <span id="totalQuizzesCount" class="">${response['totalCourseQuizSyllabusCount']}</span></p>
                    </div>
                </div>

                <div class="">
                    <div class="flex items-center">
                        <i class="fa-solid fa-file text-darthmouthgreen text-xl mx-3"></i>
                        <p class="font-bold text-md">Completed: <span id="totalLessonsCompletedCount" class="">${response['totalCourseLessonCompletedSyllabusCount']}</span></p>
                    </div>

                    <div class="flex items-center">
                        <i class="fa-solid fa-clipboard text-darthmouthgreen text-xl mx-3"></i>
                        <p class="font-bold text-md">Completed: <span id="totalActivitiesCompletedCount" class="">${response['totalCourseActivityCompletedSyllabusCount']}</span></p>
                    </div>

                    <div class="flex items-center">
                        <i class="fa-solid fa-pen-to-square text-darthmouthgreen text-xl mx-3"></i>
                        <p class="font-bold text-md">Completed: <span id="totalQuizzesCompletedCount" class="">${response['totalCourseQuizCompletedSyllabusCount']}</span></p>
                    </div>
                </div>
            </div>
            
            `
        }

        $('#courseInfo').empty();
        $('#courseInfo').append(courseDataDisp);   
    }

    function displayCourseDataChart(response, courseData, selectedCourse) {
        
        if(selectedCourse === 'ALL') {
            const categoryStatusCounts = {};

            // Group data by category and count occurrences of each status
            courseData.forEach(entry => {
                const category = entry.category;
                const status = entry.status || 'UNKNOWN'; // Handle cases where status is undefined
            
                if (!categoryStatusCounts[category]) {
                    categoryStatusCounts[category] = {
                        COMPLETED: 0,
                        IN_PROGRESS: 0,
                        "NOT YET STARTED": 0,
                    };
                }
            
                categoryStatusCounts[category][status]++;
            });
            
            // Separate labels and dataValues for the chart
            const labels = Object.keys(categoryStatusCounts);
            const datasets = Object.keys(categoryStatusCounts[labels[0]]).map((status, index) => {
                return {
                    label: status,
                    data: labels.map(category => categoryStatusCounts[category][status] || 0),
                    borderWidth: 1,
                    backgroundColor: getColor(index),
                    borderColor: getColor(index),
                };
            });
            
            const ctx = $('#courseDataChart');
            
            // Destroy existing Chart instance if it exists
            if (ctx.data('chart')) {
                ctx.data('chart').destroy();
            }
            
            // Create a new Chart instance
            const newChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets,
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            stepSize: 1,
                        },
                    },
                },
            });
            
            // Store the new Chart instance in the container's data for later destruction
            ctx.data('chart', newChart);
        } else {
            const categoryStatusCounts = {};

            // Group data by category and count occurrences of each status
            courseData.forEach(entry => {
                const category = entry.category;
                const status = entry.status || 'UNKNOWN'; // Handle cases where status is undefined
            
                if (!categoryStatusCounts[category]) {
                    categoryStatusCounts[category] = {
                        COMPLETED: 0,
                        IN_PROGRESS: 0,
                        "NOT YET STARTED": 0,
                    };
                }
            
                categoryStatusCounts[category][status]++;
            });
            
            // Separate labels and dataValues for the chart
            const labels = Object.keys(categoryStatusCounts);
            const datasets = Object.keys(categoryStatusCounts[labels[0]]).map((status, index) => {
                return {
                    label: status,
                    data: labels.map(category => categoryStatusCounts[category][status] || 0),
                    borderWidth: 1,
                    backgroundColor: getColor(index),
                    borderColor: getColor(index),
                };
            });
            
            const ctx = $('#courseDataChart');
            
            // Destroy existing Chart instance if it exists
            if (ctx.data('chart')) {
                ctx.data('chart').destroy();
            }
            
            // Create a new Chart instance
            const newChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets,
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            stepSize: 1,
                        },
                    },
                },
            });
            
            // Store the new Chart instance in the container's data for later destruction
            ctx.data('chart', newChart);
        }
    }

    function getColor(index) {
        // You can use shades of Dartmouth Green or create a gradient
        const shades = ['#00693e', '#004d2c', '#00371d']; // Add more shades as needed
        return shades[index % shades.length];
    }


    function displayCourseListArea(learnerCourseData) {
        courseListAreaDisp = ``;

        for (let i = 0; i < learnerCourseData.length; i++) {
            const course_id = learnerCourseData[i]['course_id'];
            const course_name = learnerCourseData[i]['course_name'];
            const course_code = learnerCourseData[i]['course_code'];
            const course_progress = learnerCourseData[i]['course_progress'];
            const start_period = learnerCourseData[i]['start_period'];
            const finish_period = learnerCourseData[i]['finish_period'];
            const learner_course_id = learnerCourseData[i]['learner_course_id'];
            const learner_course_progress_id = learnerCourseData[i]['learner_course_progress_id'];
            const instructor_id = learnerCourseData[i]['instructor_id'];
            const instructor_fname = learnerCourseData[i]['instructor_fname'];
            const instructor_lname = learnerCourseData[i]['instructor_lname'];
         
            const redirect_url = `${baseUrl}/course/${course_id}`
            courseListAreaDisp += `
            <tr class="rowCourseData my-5 text-center">
                <td class="mt-5 py-5">${course_name}</td>
                <td>${course_code}</td>
                <td>${instructor_fname} ${instructor_lname}</td>
                <td>${course_progress}</td>
                <td>${start_period}</td>
                <td>
                    <a href="${redirect_url}" method="GET" class="bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl px-5 py-3">View</a>
                </td>
            </tr>
            `;
        }
        
        $('.rowCourseDataArea').empty();
        $('.rowCourseDataArea').append(courseListAreaDisp)

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
                    fill: false,
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
    
})