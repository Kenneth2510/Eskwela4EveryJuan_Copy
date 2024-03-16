@include('partials.header')

<section class="flex flex-row w-screen text-sm main-container bg-mainwhitebg md:text-base">
@include('partials.sidebar')




<section class="w-screen px-2 pt-[40px] mx-2 mt-2  overscroll-auto md:overflow-auto">
    <div class="flex justify-between px-10">
        <h1 class="text-6xl font-bold text-darthmouthgreen">Course Enrollees</h1>
        <div class="">
            <p class="text-xl font-semibold text-darthmouthgreen">{{$admin->admin_codename}}</p>
        </div>
    </div>

    <div class="w-full px-3 pb-4 mt-10 rounded-lg shadow-lg b">


            <div class="w-full" id="selectCourseArea">
                <h1 class="text-3xl font-semibold text-darthmouthgreen">Select a Course</h1>
                <select name="selectedCourse" id="selectedCourse" class="w-2/3 px-5 py-3 text-xl border border-darthmouthgreen">
                    @forelse ($courses as $course)
                    <option value="{{$course->course_id}}">{{$course->course_name}}</option>
                    @empty
                    <option value="" disabled>--no approved courses--</option>
                    @endforelse
                </select>
            </div>

            <hr class="my-6 border-t-2 border-gray-300">

            <div class="flex items-center justify-end w-full pr-10 mt-10">
                <div class="mx-2" id="addNewLearnerCourseBtn">
                    
                @if(in_array($admin->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR', 'COURSE_ASST_SUPERVISOR']))
                    <a href="/admin/course/enrollment/addNew" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">Enroll New Learner</a>
                @endif
                </div>
                <div class="mx-2" id="filterLearnerCourse">
                    <select class="px-5 py-3 mx-2 border border-darthmouthgreen rounded-xl" name="filterByStatus" id="filterByStatus">
                        <option value="">Show All</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                    <input class="px-5 py-3 mx-2 border border-darthmouthgreen rounded-xl" type="date" id="filterByDate">
                </div>
                <div class="" id="searchLearnerCourse">
                    <input class="px-5 py-3 mx-2 border border-darthmouthgreen rounded-xl" type="text" placeholder="search by name" id="searchLearner">
                </div>
            </div>


            <div class="w-full mt-10 h-[580px] overflow-y-auto" id="learnerCourseTableArea">
                <table class="w-full text-left ">
                    <thead class="text-xl">
                        <th class="py-2">Enrollee ID</th>
                        <th>Learner ID</th>
                        <th>Enrollee Info</th>
                        <th>Date Enrolled</th>
                        <th>Status</th>
                        <th></th>
                    </thead>
                    <tbody class="" id="learnerCourseTableDispArea">

                        
                    </tbody>
                </table>
            </div>

            
    </div>
</section>
</section>
@include('partials.footer')

<script>
    $(document).ready(function() {

var baseUrl = window.location.href

getLearnerCourseData()

function getLearnerCourseData() {

    var course_id = $('#selectedCourse').val();


    var url = baseUrl + "/learnerCoursesData"

    $.ajax({
        type: "GET",
        url: url,
        data: {
            course_id: course_id
        },
        success: function (response) {
            console.log(response);

            var learnerCourseData = response['learnerCourseData']
            dispLearnerCourseData(learnerCourseData)
        },
        error: function(error) {
            console.log(error);
        }
    });
}

$('#selectedCourse').on('change', function () {
    getLearnerCourseData();
})

function dispLearnerCourseData(learnerCourseData) {
    var learnerCourseDisp = ``;

    for (let i = 0; i < learnerCourseData.length; i++) {
        const name = learnerCourseData[i]['name'];
        const learner_course_id = learnerCourseData[i]['learner_course_id'];
        const learner_id = learnerCourseData[i]['learner_id'];
        const status = learnerCourseData[i]['status'];
        const created_at = learnerCourseData[i]['created_at'];
        const learner_email = learnerCourseData[i]['learner_email'];


        learnerCourseDisp += `
        <tr>
            <td>${learner_course_id}</td>
            <td>${learner_id}</td>
            <td>
                ${name}<br>
                ${learner_email}
            </td>
            <td>${created_at}</td>
            <td>${status}</td>
            <td>
                `
                @if (in_array($admin->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR' , 'COURSE_ASST_SUPERVISOR'])) 
                    learnerCourseDisp +=   `<a href="/admin/course/enrollment/learnerCourse/${learner_course_id}" class="px-5 py-3 text-white bg-darthmouthgreen rounded-xl hover:bg-white hover:text-darthmouthgreen hover:border hover:border-darthmouthgreen">Enter</a>`
                @endif
                
            
                learnerCourseDisp += `</td>
        </tr>
        `;
        
    }

    $('#learnerCourseTableDispArea').empty()
    $('#learnerCourseTableDispArea').append(learnerCourseDisp)
}


$('#filterByStatus, #filterByDate, #searchLearner').on('change keyup', function(e) {
        e.preventDefault()

        var filterStatus = $('#filterByStatus').val()
        var filterDate = $('#filterByDate').val()
        var searchLearner = $('#searchLearner').val()
        var course_id = $('#selectedCourse').val()

        var  url = baseUrl + '/search';

        $.ajax({
        type: "GET",
        url: url,
        data: {
            course_id: course_id,
            searchLearner: searchLearner,
            filterDate: filterDate,
            filterStatus: filterStatus,
        },
        success: function (response) {
            console.log(response);

            var learnerCourseData = response['learnerCourseData']
            dispLearnerCourseData(learnerCourseData)
        },
        error: function(error) {
            console.log(error);
        }
    });
        
    });

})
    </script>