$(document).ready(function() {

    var baseUrl = window.location.href;


    getLearnerOverviewData();
    getInstructorOverviewData();
    getCourseOverviewData();

    function getLearnerOverviewData() {
    var url = baseUrl + "/learnerOverviewData";
        
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                // console.log(response)

                var totalLearnerCount = response['totalLearnerCount']
                var learnerStatusData = response['learnerStatusData']
                var learnersPerDay = response['learnersPerDay']
                var learnersPerMonth = response['learnersPerMonth']
                var learnersPerWeek = response['learnersPerWeek']
                var hourlyCounts = response['hourlyCounts']
                var dailyCounts = response['dailyCounts']
                var monthlyCounts = response['monthlyCounts']
                var weeklyCounts = response['weeklyCounts']

                $('#totalLearners').text(totalLearnerCount)
                dateRegisteredData_day(learnersPerDay)
                dateRegisteredData_week(learnersPerWeek)
                dateRegisteredData_month(learnersPerMonth)
                
                AvgSessionData_hour(hourlyCounts)
                AvgSessionData_day(dailyCounts)
                AvgSessionData_week(weeklyCounts)
                AvgSessionData_month(monthlyCounts)

                LearnerStatusData(learnerStatusData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function dateRegisteredData_day(learnersPerDay) {
        const days = learnersPerDay.map(item => item.day);
        const counts = learnersPerDay.map(item => item.count);

        const ctx = $('#dateRegisteredData_day');

        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }

        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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


    function dateRegisteredData_week(learnersPerWeek) {
        const weeks = learnersPerWeek.map(item => item.week);
        const counts = learnersPerWeek.map(item => item.count);
    
        const ctx = $('#dateRegisteredData_week');
    
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: weeks,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

    function dateRegisteredData_month(learnersPerMonth) {
        const months = learnersPerMonth.map(item => item.month);
        const counts = learnersPerMonth.map(item => item.count);
    
        const ctx = $('#dateRegisteredData_month');
    
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    


    $('#dateRegisteredDataFilter').on('change', function() {
        var val = $(this).val();
    
        if (val === 'daily') {
            $('#dateRegisteredData_dayArea').removeClass('hidden');
            $('#dateRegisteredData_weekArea').addClass('hidden');
            $('#dateRegisteredData_monthArea').addClass('hidden');
        } else if (val === 'weekly') {
            $('#dateRegisteredData_dayArea').addClass('hidden');
            $('#dateRegisteredData_weekArea').removeClass('hidden');
            $('#dateRegisteredData_monthArea').addClass('hidden');
        } else {
            $('#dateRegisteredData_dayArea').addClass('hidden');
            $('#dateRegisteredData_weekArea').addClass('hidden');
            $('#dateRegisteredData_monthArea').removeClass('hidden');
        }
    });



    function AvgSessionData_hour(hourlyCounts) {
        const groupedCounts = hourlyCounts.reduce((acc, curr) => {
            const hour = new Date(curr.hour_start).toLocaleString('en-US', { hour: 'numeric', hour12: true });
            acc[hour] = (acc[hour] || 0) + curr.session_count;
            return acc;
        }, {});
    
        const hours = Object.keys(groupedCounts).sort((a, b) => parseInt(a) - parseInt(b));
        const counts = hours.map(hour => groupedCounts[hour]);
    
        const ctx = $('#AvgSessionData_hour');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [{
                    label: 'Session Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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


    function AvgSessionData_day(dailyCounts) {
        const groupedCounts = dailyCounts.reduce((acc, curr) => {
            const day = new Date(curr.day_start).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            acc[day] = (acc[day] || 0) + curr.session_count;
            return acc;
        }, {});
    
        const days = Object.keys(groupedCounts).sort((a, b) => new Date(a) - new Date(b));
        const counts = days.map(day => groupedCounts[day]);
    
        const ctx = $('#AvgSessionData_day');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Session Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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


    function AvgSessionData_week(weeklyCounts) {
        const groupedCounts = weeklyCounts.reduce((acc, curr) => {
            acc[curr.week_start] = curr.session_count;
            return acc;
        }, {});
    
        const weeks = Object.keys(groupedCounts).sort((a, b) => {
            const weekA = parseInt(a.match(/\d+/)[0]);
            const weekB = parseInt(b.match(/\d+/)[0]);
            return weekA - weekB;
        });
        const counts = weeks.map(week => groupedCounts[week]);
    
        const ctx = $('#AvgSessionData_week');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: weeks,
                datasets: [{
                    label: 'Session Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    
    

    function AvgSessionData_month(monthlyCounts) {
        const groupedCounts = monthlyCounts.reduce((acc, curr) => {
            const month = new Date(curr.month_start + ' 1').toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            acc[month] = curr.session_count;
            return acc;
        }, {});
    
        const months = Object.keys(groupedCounts).sort((a, b) => {
            const dateA = new Date('01 ' + a);
            const dateB = new Date('01 ' + b);
            return dateA - dateB;
        });
        const counts = months.map(month => groupedCounts[month]);
    
        const ctx = $('#AvgSessionData_month');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Session Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    


    $('#AvgSessionDataFilter').on('change', function() {
        var val = $(this).val();
    
        if (val === 'daily') {
            $('#AvgSessionData_dayArea').removeClass('hidden');
            $('#AvgSessionData_weekArea').addClass('hidden');
            $('#AvgSessionData_monthArea').addClass('hidden');
        } else if (val === 'weekly') {
            $('#AvgSessionData_dayArea').addClass('hidden');
            $('#AvgSessionData_weekArea').removeClass('hidden');
            $('#AvgSessionData_monthArea').addClass('hidden');
        } else {
            $('#AvgSessionData_dayArea').addClass('hidden');
            $('#AvgSessionData_weekArea').addClass('hidden');
            $('#AvgSessionData_monthArea').removeClass('hidden');
        }
    });


    function LearnerStatusData(learnerStatusData) {
        const statuses = learnerStatusData.map(data => data.status);
        const counts = statuses.reduce((acc, status) => {
            acc[status] = (acc[status] || 0) + 1;
            return acc;
        }, {});
    
        const labels = Object.keys(counts);
        const data = Object.values(counts);
    
        const ctx = $('#totalLearnerStatus');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Status',
                    data: data,
                    backgroundColor: [
                        'rgba(0, 150, 50, 0.5)',  
                        'rgba(0, 100, 30, 0.5)',   
                        'rgba(0, 50, 10, 0.5)',    
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    
        ctx.data('chart', newChart);
    }
    



    function getInstructorOverviewData() {


    var url = baseUrl + "/instructorOverviewData";
        
        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                // console.log(response)

                var totalInstructorCount = response['totalInstructorCount']
                var instructorStatusData = response['instructorStatusData']
                var instructorsPerDay = response['instructorsPerDay']
                var instructorsPerMonth = response['instructorsPerMonth']
                var instructorsPerWeek = response['instructorsPerWeek']
                var hourlyCounts = response['hourlyCounts']
                var dailyCounts = response['dailyCounts']
                var monthlyCounts = response['monthlyCounts']
                var weeklyCounts = response['weeklyCounts']

                $('#totalInstructors').text(totalInstructorCount)
                i_dateRegisteredData_day(instructorsPerDay)
                i_dateRegisteredData_week(instructorsPerWeek)
                i_dateRegisteredData_month(instructorsPerMonth)
                
                i_AvgSessionData_hour(hourlyCounts)
                i_AvgSessionData_day(dailyCounts)
                i_AvgSessionData_week(weeklyCounts)
                i_AvgSessionData_month(monthlyCounts)
                totalInstructorStatus(instructorStatusData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }



    function i_dateRegisteredData_day(instructorsPerDay) {
        const days = instructorsPerDay.map(item => item.day);
        const counts = instructorsPerDay.map(item => item.count);

        const ctx = $('#i_dateRegisteredData_day');

        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }

        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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


    function i_dateRegisteredData_week(instructorsPerWeek) {
        const weeks = instructorsPerWeek.map(item => item.week);
        const counts = instructorsPerWeek.map(item => item.count);
    
        const ctx = $('#i_dateRegisteredData_week');
    
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: weeks,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

    function i_dateRegisteredData_month(instructorsPerMonth) {
        const months = instructorsPerMonth.map(item => item.month);
        const counts = instructorsPerMonth.map(item => item.count);
    
        const ctx = $('#i_dateRegisteredData_month');
    
        // Destroy existing Chart instance if it exists
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        // Create a new Chart instance
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    


    $('#i_dateRegisteredDataFilter').on('change', function() {
        var val = $(this).val();
    
        if (val === 'daily') {
            $('#i_dateRegisteredData_dayArea').removeClass('hidden');
            $('#i_dateRegisteredData_weekArea').addClass('hidden');
            $('#i_dateRegisteredData_monthArea').addClass('hidden');
        } else if (val === 'weekly') {
            $('#i_dateRegisteredData_dayArea').addClass('hidden');
            $('#i_dateRegisteredData_weekArea').removeClass('hidden');
            $('#i_dateRegisteredData_monthArea').addClass('hidden');
        } else {
            $('#i_dateRegisteredData_dayArea').addClass('hidden');
            $('#i_dateRegisteredData_weekArea').addClass('hidden');
            $('#i_dateRegisteredData_monthArea').removeClass('hidden');
        }
    });



    function i_AvgSessionData_hour(hourlyCounts) {
        const groupedCounts = hourlyCounts.reduce((acc, curr) => {
            const hour = new Date(curr.hour_start).toLocaleString('en-US', { hour: 'numeric', hour12: true });
            acc[hour] = (acc[hour] || 0) + curr.session_count;
            return acc;
        }, {});
    
        const hours = Object.keys(groupedCounts).sort((a, b) => parseInt(a) - parseInt(b));
        const counts = hours.map(hour => groupedCounts[hour]);
    
        const ctx = $('#i_AvgSessionData_hour');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: hours,
                datasets: [{
                    label: 'Session Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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


    function i_AvgSessionData_day(dailyCounts) {
        const groupedCounts = dailyCounts.reduce((acc, curr) => {
            const day = new Date(curr.day_start).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            acc[day] = (acc[day] || 0) + curr.session_count;
            return acc;
        }, {});
    
        const days = Object.keys(groupedCounts).sort((a, b) => new Date(a) - new Date(b));
        const counts = days.map(day => groupedCounts[day]);
    
        const ctx = $('#i_AvgSessionData_day');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Session Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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


    function i_AvgSessionData_week(weeklyCounts) {
        const groupedCounts = weeklyCounts.reduce((acc, curr) => {
            acc[curr.week_start] = curr.session_count;
            return acc;
        }, {});
    
        const weeks = Object.keys(groupedCounts).sort((a, b) => {
            const weekA = parseInt(a.match(/\d+/)[0]);
            const weekB = parseInt(b.match(/\d+/)[0]);
            return weekA - weekB;
        });
        const counts = weeks.map(week => groupedCounts[week]);
    
        const ctx = $('#i_AvgSessionData_week');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: weeks,
                datasets: [{
                    label: 'Session Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    
    

    function i_AvgSessionData_month(monthlyCounts) {
        const groupedCounts = monthlyCounts.reduce((acc, curr) => {
            const month = new Date(curr.month_start + ' 1').toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            acc[month] = curr.session_count;
            return acc;
        }, {});
    
        const months = Object.keys(groupedCounts).sort((a, b) => {
            const dateA = new Date('01 ' + a);
            const dateB = new Date('01 ' + b);
            return dateA - dateB;
        });
        const counts = months.map(month => groupedCounts[month]);
    
        const ctx = $('#i_AvgSessionData_month');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Session Count',
                    data: counts,
                    backgroundColor: 'rgba(0, 150, 50, 0.5)',
                    borderColor: 'rgba(0, 150, 50, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    


    $('#i_AvgSessionDataFilter').on('change', function() {
        var val = $(this).val();
    
        if (val === 'daily') {
            $('#i_AvgSessionData_dayArea').removeClass('hidden');
            $('#i_AvgSessionData_weekArea').addClass('hidden');
            $('#i_AvgSessionData_monthArea').addClass('hidden');
        } else if (val === 'weekly') {
            $('#i_AvgSessionData_dayArea').addClass('hidden');
            $('#i_AvgSessionData_weekArea').removeClass('hidden');
            $('#i_AvgSessionData_monthArea').addClass('hidden');
        } else {
            $('#i_AvgSessionData_dayArea').addClass('hidden');
            $('#i_AvgSessionData_weekArea').addClass('hidden');
            $('#i_AvgSessionData_monthArea').removeClass('hidden');
        }
    });


    function totalInstructorStatus(instructorStatusData) {
        const statuses = instructorStatusData.map(data => data.status);
        const counts = statuses.reduce((acc, status) => {
            acc[status] = (acc[status] || 0) + 1;
            return acc;
        }, {});
    
        const labels = Object.keys(counts);
        const data = Object.values(counts);
    
        const ctx = $('#totalInstructorStatus');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Status',
                    data: data,
                    backgroundColor: [
                        'rgba(0, 150, 50, 0.5)',  
                        'rgba(0, 100, 30, 0.5)',   
                        'rgba(0, 50, 10, 0.5)',    
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    
        ctx.data('chart', newChart);
    }
    
    

    function getCourseOverviewData() {


        var url = baseUrl + "/courseOverviewData";
            
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    console.log(response)
    
                    var totalCourseCount = response['totalCourseCount']
                    var courseStatusData = response['courseStatusData']
                    var coursePerDay = response['coursePerDay']
                    var coursePerMonth = response['coursePerMonth']
                    var coursePerWeek = response['coursePerWeek']
                    var learnerCourseCount = response['learnerCourseCount']
    
                    $('#totalCourse').text(totalCourseCount)
                    c_dateRegisteredData_day(coursePerDay)
                    c_dateRegisteredData_week(coursePerWeek)
                    c_dateRegisteredData_month(coursePerMonth)
                    
                    totalCourseStatus(courseStatusData)
                    LearnerCourseCount(learnerCourseCount)
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    

        function c_dateRegisteredData_day(coursePerDay) {
            const days = coursePerDay.map(item => item.day);
            const counts = coursePerDay.map(item => item.count);
    
            const ctx = $('#c_dateRegisteredData_day');
    
            // Destroy existing Chart instance if it exists
            if (ctx.data('chart')) {
                ctx.data('chart').destroy();
            }
    
            // Create a new Chart instance
            const newChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Count',
                        data: counts,
                        backgroundColor: 'rgba(0, 150, 50, 0.5)',
                        borderColor: 'rgba(0, 150, 50, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
    
    
        function c_dateRegisteredData_week(coursePerWeek) {
            const weeks = coursePerWeek.map(item => item.week);
            const counts = coursePerWeek.map(item => item.count);
        
            const ctx = $('#c_dateRegisteredData_week');
        
            // Destroy existing Chart instance if it exists
            if (ctx.data('chart')) {
                ctx.data('chart').destroy();
            }
        
            // Create a new Chart instance
            const newChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: weeks,
                    datasets: [{
                        label: 'Count',
                        data: counts,
                        backgroundColor: 'rgba(0, 150, 50, 0.5)',
                        borderColor: 'rgba(0, 150, 50, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
    
        function c_dateRegisteredData_month(coursePerMonth) {
            const months = coursePerMonth.map(item => item.month);
            const counts = coursePerMonth.map(item => item.count);
        
            const ctx = $('#c_dateRegisteredData_month');
        
            // Destroy existing Chart instance if it exists
            if (ctx.data('chart')) {
                ctx.data('chart').destroy();
            }
        
            // Create a new Chart instance
            const newChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Count',
                        data: counts,
                        backgroundColor: 'rgba(0, 150, 50, 0.5)',
                        borderColor: 'rgba(0, 150, 50, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
        
    
    
        $('#c_dateRegisteredDataFilter').on('change', function() {
            var val = $(this).val();
        
            if (val === 'daily') {
                $('#c_dateRegisteredData_dayArea').removeClass('hidden');
                $('#c_dateRegisteredData_weekArea').addClass('hidden');
                $('#c_dateRegisteredData_monthArea').addClass('hidden');
            } else if (val === 'weekly') {
                $('#c_dateRegisteredData_dayArea').addClass('hidden');
                $('#c_dateRegisteredData_weekArea').removeClass('hidden');
                $('#c_dateRegisteredData_monthArea').addClass('hidden');
            } else {
                $('#c_dateRegisteredData_dayArea').addClass('hidden');
                $('#c_dateRegisteredData_weekArea').addClass('hidden');
                $('#c_dateRegisteredData_monthArea').removeClass('hidden');
            }
        });


        
    function totalCourseStatus(courseStatusData) {
        const statuses = courseStatusData.map(data => data.course_status);
        const counts = statuses.reduce((acc, status) => {
            acc[status] = (acc[status] || 0) + 1;
            return acc;
        }, {});
    
        const labels = Object.keys(counts);
        const data = Object.values(counts);
    
        const ctx = $('#totalCourseStatus');
    
        if (ctx.data('chart')) {
            ctx.data('chart').destroy();
        }
    
        const newChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Status',
                    data: data,
                    backgroundColor: [
                        'rgba(0, 150, 50, 0.5)',  
                        'rgba(0, 100, 30, 0.5)',   
                        'rgba(0, 50, 10, 0.5)',    
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    
        ctx.data('chart', newChart);
    }


    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }


    
function LearnerCourseCount(learnerCourseCount) {
    const courseData = learnerCourseCount.map(data => ({ courseName: data.course_name, count: data.count }));

    const ctx = $('#enrolleeNumbers');

    if (ctx.data('chart')) {
        ctx.data('chart').destroy();
    }

    const newChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: courseData.map(data => data.courseName),
            datasets: [{
                label: 'Course Count',
                data: courseData.map(data => data.count),
                backgroundColor: courseData.map(() => getRandomColor()),
                borderColor: '#ffffff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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