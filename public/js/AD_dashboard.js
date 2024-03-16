$(document).ready(function() {

    var baseUrl = window.location.href;

    getCountChartData();
    getLearnerCourseProgressData();

    function getCountChartData() {
        var url = baseUrl + "/getCountData";

        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);
                
                var learners = response['learners']
                var instructors = response['instructors']
                var courses = response['courses']
                var admins = response['admins']

                dispLearnerChart(learners)
                dispInstructorChart(instructors)
                dispCourseChart(courses)
                dispAdminChart(admins)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function dispInstructorChart(instructors) {
        const statuses = ['Pending', 'Approved', 'Rejected']; // Assuming these are the possible statuses
    
        // Count the number of learners for each status
        const counts = statuses.map(status => {
            return instructors.filter(instructor => instructor.status === status).length;
        });
    
        const ctx = $('#instructorData');
        
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: statuses,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: [
                        'rgba(0, 150, 50, 0.5)', // Change color for 'Pending'
                        'rgba(0, 100, 30, 0.5)', // Change color for 'Approved'
                        'rgba(0, 50, 10, 0.5)',  // Change color for 'Rejected'
                    ],
                    borderColor: [
                        'rgba(0, 150, 50, 1)',  // Change border color for 'Pending'
                        'rgba(0, 100, 30, 1)',  // Change border color for 'Approved'
                        'rgba(0, 50, 10, 1)',   // Change border color for 'Rejected'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    
        ctx.data('chart', newChart);
    }
    
    function dispLearnerChart(learners) {
        const statuses = ['Pending', 'Approved', 'Rejected']; // Assuming these are the possible statuses
    
        // Count the number of learners for each status
        const counts = statuses.map(status => {
            return learners.filter(learner => learner.status === status).length;
        });
    
        const ctx = $('#learnerData');
        
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: statuses,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: [
                        'rgba(0, 150, 50, 0.5)', // Change color for 'Pending'
                        'rgba(0, 100, 30, 0.5)', // Change color for 'Approved'
                        'rgba(0, 50, 10, 0.5)',  // Change color for 'Rejected'
                    ],
                    borderColor: [
                        'rgba(0, 150, 50, 1)',  // Change border color for 'Pending'
                        'rgba(0, 100, 30, 1)',  // Change border color for 'Approved'
                        'rgba(0, 50, 10, 1)',   // Change border color for 'Rejected'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    
        ctx.data('chart', newChart);
    }

    function dispCourseChart(courses) {
        const statuses = ['Pending', 'Approved', 'Rejected']; // Assuming these are the possible statuses
    
        // Count the number of learners for each status
        const counts = statuses.map(status => {
            return courses.filter(course => course.course_status === status).length;
        });
    
        const ctx = $('#courseData');
        
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: statuses,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: [
                        'rgba(0, 150, 50, 0.5)', // Change color for 'Pending'
                        'rgba(0, 100, 30, 0.5)', // Change color for 'Approved'
                        'rgba(0, 50, 10, 0.5)',  // Change color for 'Rejected'
                    ],
                    borderColor: [
                        'rgba(0, 150, 50, 1)',  // Change border color for 'Pending'
                        'rgba(0, 100, 30, 1)',  // Change border color for 'Approved'
                        'rgba(0, 50, 10, 1)',   // Change border color for 'Rejected'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    
        ctx.data('chart', newChart);
    }


    function dispAdminChart(admins) {
        const roles = ['SUPER_ADMIN', 'IT_DEPT', 'COURSE_SUPERVISOR', 'COURSE_ASST_SUPERVISOR', 'USER_MANAGER', 'CLERK']; // Assuming these are the possible statuses
    
        // Count the number of learners for each status
        const counts = roles.map(role => {
            return admins.filter(admin => admin.role === role).length;
        });
    
        const ctx = $('#adminData');
        
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: roles,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: [
                        'rgba(0, 150, 50, 0.5)',   // Change color for 'SUPER_ADMIN'
                        'rgba(0, 100, 30, 0.5)',   // Change color for 'IT_DEPT'
                        'rgba(0, 50, 10, 0.5)',    // Change color for 'COURSE_SUPERVISOR'
                        'rgba(0, 0, 0, 0.5)',      // Change color for 'COURSE_ASST_SUPERVISOR'
                        'rgba(50, 0, 0, 0.5)',     // Change color for 'USER_MANAGER'
                        'rgba(100, 0, 0, 0.5)',    // Change color for 'CLERK'
                    ],
                    borderColor: [
                        'rgba(0, 150, 50, 1)',     // Change border color for 'SUPER_ADMIN'
                        'rgba(0, 100, 30, 1)',     // Change border color for 'IT_DEPT'
                        'rgba(0, 50, 10, 1)',      // Change border color for 'COURSE_SUPERVISOR'
                        'rgba(0, 0, 0, 1)',        // Change border color for 'COURSE_ASST_SUPERVISOR'
                        'rgba(50, 0, 0, 1)',       // Change border color for 'USER_MANAGER'
                        'rgba(100, 0, 0, 1)',      // Change border color for 'CLERK'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    
        ctx.data('chart', newChart);
    }


    function getLearnerCourseProgressData() {
        var selectedCourse = $('#selectedCourse').val();

        var url = baseUrl + "/getCourseProgressData";

        $.ajax({
            type: "GET",
            url: url,
            data: {
                'selectedCourse': selectedCourse
            },
            success: function(response) {
                console.log(response)

                var learnerCourseData = response['learnerCourseData']
                dispLearnerCourseProgressChart(learnerCourseData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    $('#selectedCourse').on('change', function() {
        getLearnerCourseProgressData();
    })


    function dispLearnerCourseProgressChart(learnerCourseData) {
        console.log(learnerCourseData);
        const statuses = ['COMPLETED', 'IN PROGRESS', 'NOT YET STARTED']; // Assuming these are the possible statuses
    
        // Count the number of learners for each status
        const counts = statuses.map(status => {
            return learnerCourseData.filter(course => course.course_progress === status).length;
        });
    
        const ctx = $('#courseProgressChart');
    
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: statuses,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: [
                        'rgba(0, 150, 50, 0.5)', // Change color for 'Pending'
                        'rgba(0, 100, 30, 0.5)', // Change color for 'Approved'
                        'rgba(0, 50, 10, 0.5)',  // Change color for 'Rejected'
                    ],
                    borderColor: [
                        'rgba(0, 150, 50, 1)',  // Change border color for 'Pending'
                        'rgba(0, 100, 30, 1)',  // Change border color for 'Approved'
                        'rgba(0, 50, 10, 1)',   // Change border color for 'Rejected'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    
        ctx.data('chart', newChart);
    }
})