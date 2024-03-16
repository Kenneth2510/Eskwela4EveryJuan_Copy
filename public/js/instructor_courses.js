$(document).ready(function() {
    var baseUrl = window.location.href;

    $('#searchCourse').on('input', function() {

        var courseVal = $('#searchCourse').val()


        var url = baseUrl + "/searchCourse";


        $.ajax({
            type: "GET",
            data: {
                courseVal: courseVal
            },
            url: url,
            success: function(response) {
                console.log(response);

                var courseData = response['courses']
                updateCourseDisp(courseData)
            },
            error: function(error) {
                console.log(error);
            }
        });
    })

    function updateCourseDisp(courseData) {
        courseDisp = ``

        for (let i = 0; i < courseData.length; i++) {
            const course_id = courseData[i]['course_id'];
            const course_name = courseData[i]['course_name'];
            const course_code = courseData[i]['course_code'];
            const instructor_lname = courseData[i]['instructor_lname'];
            const instructor_fname = courseData[i]['instructor_fname'];
            const profile_picture = courseData[i]['profile_picture'];
            

            courseDisp += `
            <div style="background-color: #00693e" class="px-3 py-2 relative m-4 rounded-lg shadow-lg h-72 w-52">
                <div style="background-color: #9DB0A3" class="relative h-32 mx-auto my-4 rounded w-44">
                    <img class="absolute w-16 h-16 bg-yellow-500 rounded-full right-3 -bottom-4" src="../storage/app/public/${profile_picture}" alt="">
                </div>
                
                <div class="px-4">
                    <h1 class="mb-2 overflow-hidden text-lg font-bold text-white whitespace-no-wrap">${course_name}</h1>

                    <div class="text-sm text-gray-100 ">
                        <p>${course_code}</p>
                        <h3>${instructor_fname} ${instructor_lname}</h3>
                    </div>
                </div>
                
                <a href="/instructor/course/${course_id}" style="background-color: #00693e; right:0; bottom: 0;" class="absolute float-right mx-4 mb-3 rounded">
                    <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z"/></svg>
                </a>
            </div>
            `
        }

        $('#courses').empty();
        $('#courses').append(courseDisp);
    }
})