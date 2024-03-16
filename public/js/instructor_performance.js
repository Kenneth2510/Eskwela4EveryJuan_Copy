$(document).ready(function() {
    var baseUrl = window.location.href;
    var totalCourseNum;
    var totalPendingCourseNum;
    var totalApprovedCourseNum;
    var totalPendingCourseNum;
    
    var totalLearnersCount;
    var totalApprovedLearnersCount;
    var totalPendingLearnersCount;
    var totalRejectedLearnersCount;

    var totalSyllabusCount;
    var totalLessonsCount;
    var totalActivitiesCount;
    var totalQuizzesCount;

    var allInstructorCourses;

    getTotalCoursesNum();
    getCourseChartData();

    function getTotalCoursesNum() {

        var url = baseUrl + "/totalCourseNum";

        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                // console.log(response)

                totalCourseNum = response['totalCourseNum']
                totalPendingCourseNum = response['totalPendingCourseNum']
                totalApprovedCourseNum = response['totalApprovedCourseNum']
                totalRejectedCourseNum = response['totalRejectedCourseNum']

                totalLearnersCount = response['totalLearnersCount']
                totalPendingLearnersCount = response['totalPendingLearnersCount']
                totalApprovedLearnersCount = response['totalApprovedLearnersCount']
                totalRejectedLearnersCount = response['totalRejectedLearnersCount']

                totalSyllabusCount = response['totalSyllabusCount']
                totalLessonsCount = response['totalLessonsCount']
                totalActivitiesCount = response['totalActivitiesCount']
                totalQuizzesCount = response['totalQuizzesCount']

                $('#totalCourseNum').text(totalCourseNum)
                $('#totalPendingCourse').text(totalPendingCourseNum)
                $('#totalApprovedCourse').text(totalApprovedCourseNum)
                $('#totalRejectedCourse').text(totalRejectedCourseNum)

                $('#totalLearnersCount').text(totalLearnersCount)
                $('#totalPendingLearnersCount').text(totalPendingLearnersCount)
                $('#totalApprovedLearnersCount').text(totalApprovedLearnersCount)
                $('#totalRejectedLearnersCount').text(totalRejectedLearnersCount)

                $('#totalSyllabusCount').text(totalSyllabusCount)
                $('#totalLessonsCount').text(totalLessonsCount)
                $('#totalActivitiesCount').text(totalActivitiesCount)
                $('#totalQuizzesCount').text(totalQuizzesCount)
            
                allInstructorCourses = response['allInstructorCourses'];
                
                allInstructorCoursesTable(allInstructorCourses);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function getCourseChartData() {
        var selectedCourse = $('#perCourseSelectArea').val();

        var url = baseUrl + "/courseChartData";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                'selectedCourse': selectedCourse
            },
            success: function(response) {
                console.log(response)

                courseData = response['courseData']
                displayCourseData(response, courseData, selectedCourse)
                $('#courseDataChart').empty();
                displayCourseChartData(response, courseData, selectedCourse)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    $('#perCourseSelectArea').on('change', function() {
        getCourseChartData();
    })

    function displayCourseData(response, courseData, selectedCourse) {
        var courseDataDisp = ``;
        if(selectedCourse === 'ALL') {
            courseDataDisp += `
            <h1 class="text-2xl p-5 font-semibold">All Courses</h1>
            <div class=" my-7 mx-5 h-1/3 text-center item-center flex justify-center">
                        <i class="fa-solid fa-book-open-reader text-darthmouthgreen text-[100px]"></i>
                        <p class="font-bold pt-5 mx-5 text-2xl"><span class="text-darthmouthgreen text-[75px]" id="totalCourseNum">${response['totalCourseNum']}</span><br>Total Courses</p>
                    </div>
                    <div class="mx-10 mt-5">
                        <div class="flex items-center mx-1">
                            <div class="rounded-full w-3 h-3 mx-1 bg-darthmouthgreen"></div>
                            <p class="font-bold text-md">Approved: <span id="totalApprovedCourse" class="">${response['totalApprovedCourseNum']}</span></p>
                        </div>

                        <div class="flex items-center mx-1">
                            <div class="rounded-full w-3 h-3 mx-1 bg-yellow-400"></div>
                            <p class="font-bold text-md">Pending: <span id="totalPendingCourse" class="">${response['totalPendingCourseNum']}</span></p>
                        </div>

                        <div class="flex items-center mx-1">
                            <div class="rounded-full w-3 h-3 mx-1 bg-red-700"></div>
                            <p class="font-bold text-md">Rejected: <span id="totalRejectedCourse" class="">${response['totalRejectedCourseNum']}</span></p>
                        </div>
                    </div>
            `
        } else {
            courseDataDisp += `
            <div class="w-full text-center text-[100px]"><i class="fa-solid fa-book-open-reader text-[100px] text-darthmouthgreen"></i></div>
            <h1 class="text-2xl h-1/3 text-darthmouthgreen font-semibold">${courseData[0]['course_name']}</h1>
            <p class=""><span class="" id="">${courseData[0]['course_code']}</span></p>
            <p class="">Created at: <span class="" id="">${courseData[0]['created_at']}</span></p>
            <p class="">Updated at: <span class="" id="">${courseData[0]['updated_at']}</span></p>
            <div class="">
                <div class="flex items-center">
                    <i class="fa-solid fa-file text-darthmouthgreen text-xl mx-3"></i>
                    <p class="font-bold text-md">Lessons: <span id="totalLessonsCount" class="">${response['totalLessonsCount']}</span></p>
                </div>
    
                <div class="flex items-center">
                    <i class="fa-solid fa-clipboard text-darthmouthgreen text-xl mx-3"></i>
                    <p class="font-bold text-md">Activities: <span id="totalActivitiesCount" class="">${response['totalActivitiesCount']}</span></p>
                </div>
    
                <div class="flex items-center">
                    <i class="fa-solid fa-pen-to-square text-darthmouthgreen text-xl mx-3"></i>
                    <p class="font-bold text-md">Quizzes: <span id="totalQuizzesCount" class="">${response['totalQuizzesCount']}</span></p>
                </div>
            </div>
            `
        }

        $('#courseInfo').empty();
        $('#courseInfo').append(courseDataDisp);   
    }


    function displayCourseChartData(response, courseData, selectedCourse) {

        if(selectedCourse === 'ALL') {
            const learnersByCourse = courseData.reduce((acc, entry) => {
                const courseId = entry.course_id;
                const courseName = entry.course_name;
    
                if (!acc[courseId]) {
                    acc[courseId] = {
                        label: courseName,
                        count: 1,
                    };
                } else {
                    acc[courseId].count += 1;
                }
    
                return acc;
            }, {});
    
            const labels = Object.values(learnersByCourse).map(course => course.label);
            const dataValues = Object.values(learnersByCourse).map(course => course.count);
    
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
    
            // Store the new Chart instance in the container's data for later destruction
            ctx.data('chart', newChart);

        } else {
            const enrollmentByMonth = courseData.reduce((acc, entry) => {
                const monthKey = `${entry.year}-${entry.month}`;
                acc[monthKey] = (acc[monthKey] || 0) + 1;
                return acc;
            }, {});
    
            const labels = Object.keys(enrollmentByMonth);
            const dataValues = Object.values(enrollmentByMonth);
    
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
    
            // Store the new Chart instance in the container's data for later destruction
            ctx.data('chart', newChart);

        
        }
    }

    function allInstructorCoursesTable(allInstructorCourses) {
        allInstructorCoursesTableDisp = ``;
        
        for (let i = 0; i < allInstructorCourses.length; i++) {
            const course_id = allInstructorCourses[i]['course_id'];
            const course_name = allInstructorCourses[i]['course_name'];
            const course_code = allInstructorCourses[i]['course_code'];
            const created_at = allInstructorCourses[i]['created_at'];
            const course_status = allInstructorCourses[i]['course_status'];
            const learnerCount = allInstructorCourses[i]['learnerCount'];
            const approvedLearnerCount = allInstructorCourses[i]['approvedLearnerCount'];

            allInstructorCoursesTableDisp += `
            <tr class="rowCourseData my-5 text-center">
                <td class="mt-5 py-5">${course_name}</td>
                <td>${course_code}</td>
                <td>${learnerCount} / ${approvedLearnerCount}</td>
                <td>${created_at}</td>
                <td>${course_status}</td>
                <td>
                    <a href="/instructor/performances/course/${course_id}" method="GET" class="bg-darthmouthgreen hover:bg-green-950 text-white rounded-xl px-5 py-3">View</a>
                </td>
            </tr>
            `
            
        }

        $('.rowCourseDataArea').empty();
        $('.rowCourseDataArea').append(allInstructorCoursesTableDisp)
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
    
        const ctx = $('#instructorSessionGraph');
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