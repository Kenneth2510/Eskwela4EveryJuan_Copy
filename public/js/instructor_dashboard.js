$(document).ready(function() {
    var baseUrl = window.location.href;

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


    getOverviewTotalNumbers();

    function getOverviewTotalNumbers() {
        var url = baseUrl + "/overviewNum";

        $.ajax({
            type: "GET",
            url: url,
            success: function(response) {
                console.log(response);


                allInstructorCourses = response['allInstructorCourses']
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


                $('#totalCoursesText').text(totalCourseNum)
                $('#totalLearnersCountText').text(totalLearnersCount)
                $('#totalSyllabusCountText').text(totalSyllabusCount)

                dispEnrolledLearnersCount(allInstructorCourses, totalLearnersCount)
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    function dispEnrolledLearnersCount(allInstructorCourses, totalLearnersCount) {
        enrolledLearnersDisp = ``;

        for (let i = 0; i < allInstructorCourses.length; i++) {
            const course_name = allInstructorCourses[i]['course_name'];
            const learnerCount = allInstructorCourses[i]['approvedLearnerCount'];
        
            const percent = ((learnerCount / totalLearnersCount) * 100).toFixed(2);

            enrolledLearnersDisp += `
                <tr class="my-5">
                    <td class="py-3 px-5 text-lg">${course_name}</td>
                    <td>
                        <div class="h-7 rounded-xl" style="background: #9DB0A3" id="skill_bar">
                            <div class="h-7 relative bg-darthmouthgreen rounded-xl text-white text-center py-1" id="skill_per" per="${percent}%" style="max-width: ${percent}%">${percent}%</div>
                        </div>
                    </td>
                </tr>
            `
        }

        $('#enrollePercentArea').empty();
        $('#enrollePercentArea').append(enrolledLearnersDisp);
        
    }

    
})