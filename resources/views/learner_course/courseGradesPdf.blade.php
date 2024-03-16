<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title !== "" ? $title : 'Eskwela4EveryJuan'}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Scrollbar styles */
        ::-webkit-scrollbar {
            width: 15px;
        }

        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #00693e;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #004026;
        }

        /* General styles */
        html, body {
            width: 100%;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page_content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.5rem;
            background-color: #fff;
            overflow: auto;
            width: 100%;
        }

        .flex {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .flex-row {
            display: flex;
            align-items: center;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        label {
            font-size: 1rem;
            margin-right: 0.5rem;
        }

        hr {
            margin: 1.5rem 0;
            border-top: 0.125rem solid #e2e8f0;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        input[type="text"] {
            width: 100%;
            font-size: 2rem;
            font-weight: bold;
            border: none;
            padding: 0;
        }

        /* Custom styles */
        .bg-mainwhitebg {
            background-color: #f7f7f7;
        }

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        .w-full {
            width: 100%;
        }

        .w-3/4 {
            width: 75%;
        }

        .w-9/12 {
            width: 75%;
        }

        .md:overflow-auto {
            overflow: auto;
        }

        .md:w-3/4 {
            width: 75%;
        }

        .lg:w-9/12 {
            width: 75%;
        }

        .pt-[100px] {
            padding-top: 100px;
        }

        .mx-2 {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }

        .mt-10 {
            margin-top: 2.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .pb-4 {
            padding-bottom: 1rem;
        }

        .rounded-xl {
            border-radius: 1rem;
        }

        .text-white {
            color: #fff;
        }

        .text-gray-600 {
            color: #718096;
        }

        .text-2xl {
            font-size: 1.25rem;
        }

        .text-4xl {
            font-size: 2rem;
        }

        .text-5xl {
            font-size: 2.5rem;
        }

        .font-bold {
            font-weight: bold;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .py-5 {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }

        .mx-16 {
            margin-left: 4rem;
            margin-right: 4rem;
        }

        .w-[700px] {
            width: 700px;
        }

        .border-b-2 {
            border-bottom-width: 0.125rem;
        }

        .fill-mainwhitebg {
            background-color: #f7f7f7;
        }

        .text-green-600 {
            color: #38a169;
        }

        .text-black {
            color: #000;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body class="min-h-full bg-mainwhitebg font-poppins">
    <section id="start" class="page_content">
        <section class="w-full px-2 pt-[100px] mx-2 mt-2 md:overflow-auto md:w-3/4 lg:w-9/12">
            <div class="p-3 pb-4 overflow-auto bg-white rounded-lg shadow-lg overscroll-auto">
                <div style="background-color:{{$mainBackgroundCol}};" class="p-2 text-white fill-white rounded-xl">
                    <h1 class="w-1/2 py-4 text-5xl font-bold"><span class="">{{ $courseData->course_name }}</span></h1>
                    <div class="flex flex-col justify-between fill-mainwhitebg">
                        <h1 class="w-1/2 py-4 text-4xl font-bold"><span class="">COURSE GRADESHEET</span></h1>
                    </div>
                </div> 
        
                <div class="mx-2">
                    <div class="mt-1 text-gray-600 text-l">
                        <a href="{{ url('/learner/courses') }}" class="">course></a>
                        <a href="{{ url("/learner/course/$courseData->course_id") }}">{{$courseData->course_name}}></a>
                        <a href="{{ url("/learner/course/manage/$courseData->course_id/overview") }}">content></a>
                        <a href="">Grades</a>
                    </div>
                    <div style="margin-top: 10px;" class="">
                        <h3>About the Learner</h3>

                        <h4>Name: <span>{{$learner->learner_fname}} {{$learner->learner_lname}}</span></h4>
                        <h4>Business Name: <span>{{$businessData->business_name}}</span></h4>
                        <h4>Business Category: <span>{{$businessData->business_category}}</span></h4>
                        <h4>Business Classification: <span>{{$businessData->business_classification}}</span></h4>
                        <h4>Business Description:</h4>
                        <p>{{ $businessData->business_description }}</p>

                    </div>
                    <div class="flex justify-between py-4 mt-10 border-b-2">
                        <div class="flex flex-row items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M12 29a1 1 0 0 1-.92-.62L6.33 17H2v-2h5a1 1 0 0 1 .92.62L12 25.28l8.06-21.63A1 1 0 0 1 21 3a1 1 0 0 1 .93.68L25.72 15H30v2h-5a1 1 0 0 1-.95-.68L21 7l-8.06 21.35A1 1 0 0 1 12 29Z"/></svg>
                            <h1 class="mx-2 text-2xl font-semibold">Gradesheet</h1>
                        </div>
                        <h1 class="mx-2 text-2xl font-semibold">
                            @if ($courseData->course_progress === "NOT YET STARTED")
                            <span class="">STATUS: NOT YET STARTED</span>
                            @elseif ($courseData->course_progress === "COMPLETED")
                            <span class="">STATUS: COMPLETED</span>
                            @else
                            <span class="">STATUS: IN PROGRESS</span>
                            @endif
                        </h1>
                    </div>
                </div>
        
                <div class="mt-10">
                    <h1 class="mx-2 text-2xl font-semibold">Pre Assessment</h1>
                    <h1 class="py-5 mx-16 text-4xl font-bold text-green-600">{{$preAssessmentLearnerSumScore}} <span class="text-2xl font-bold text-black"> / {{$totalScoreCount_pre_assessment}}</span></h1>
                </div>
        
                <div class="mt-10">
                    <h1 class="mx-2 text-2xl font-semibold">Lessons</h1>
                    <table class="text-center py-5 mx-16 w-[700px]">
                        <thead>
                            <th>Lesson Title</th>
                            <th>Start Date</th>
                            <th>Finish Date</th>
                        </thead>
                        <tbody>
                            @foreach ($learnerLessonsData as $lesson)
                                <tr>
                                    <td>{{ $lesson->lesson_title }}</td>
                                    <td>{{ $lesson->start_period }}</td>
                                    <td>{{ $lesson->finish_period }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        
                <div class="mt-10">
                    <h1 class="mx-2 text-2xl font-semibold">Activities</h1>
                    <table class="text-center py-5 mx-16 w-[700px]">
                        <thead>
                            <th>Activity Title</th>
                            <th>Score</th>
                        </thead>
                        <tbody>
                            @foreach ($activityScoresData as $activity)
                                <tr>
                                    <td>{{ $activity->activity_title }}</td>
                                    <td>{{ $activity->average_score }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        
                <div class="mt-10">
                    <h1 class="mx-2 text-2xl font-semibold">Quizzes</h1>
                    <table class="text-center py-5 mx-16 w-[700px]">
                        <thead>
                            <th>Quiz Title</th>
                            <th>Score</th>
                        </thead>
                        <tbody>
                            @foreach ($quizScoresData as $quiz)
                                <tr>
                                    <td>{{ $quiz->quiz_title }}</td>
                                    <td>{{ $quiz->average_score }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        
                <div class="mt-10">
                    <h1 class="mx-2 text-2xl font-semibold">Post Assessment</h1>
                    <h1 class="py-5 mx-16 text-4xl font-bold text-green-600">{{$postAssessmentLearnerSumScore}} <span class="text-2xl font-bold text-black"> / {{$totalScoreCount_post_assessment}}</span></h1>
                </div>
        

                @if ($courseData->course_progress === 'COMPLETED')
                <hr class="my-6 border-t-2 border-gray-300">
                <h1 class="mx-2 text-2xl font-semibold">Computation of Grades</h1>
                <div class="px-10 mt-3">
                    <h1 class="text-xl font-bold">Activities</h1>
                    <p class="py-5 mx-16 text-xl font-bold">[[ {{ $activityLearnerSumScore }}  / {{ $activityTotalSum }} ] x 100 ] x 35% = {{ $activityGrade }}%</p>
        
                    <h1 class="text-xl font-bold">Quizzes</h1>
                    <p class="py-5 mx-16 text-xl font-bold">[[ {{ $quizLearnerSumScore }}  / {{ $quizTotalSum }} ] x 100 ] x 35% = {{ $quizGrade }}%</p>
        
                    <h1 class="text-xl font-bold">Post Assessment</h1>
                    <p class="py-5 mx-16 text-xl font-bold">[[ {{ $postAssessmentLearnerSumScore }}  / {{ $totalScoreCount_post_assessment }} ] x 100 ] x 30% = {{ $postAssessmentScoreGrade }}%</p>
        
                    <h1 class="text-xl font-bold text-green-600">Overall Grade</h1>
                    <p class="py-5 mx-16 text-xl font-bold text-green-600">{{ $activityGrade }} + {{ $quizGrade }} + {{$postAssessmentScoreGrade}} = {{ $totalGrade }}%</p>
        
                    <hr class="my-6 border-t-2 border-gray-300">
                    <h1 class="text-2xl font-bold">Final Grade: <span class="text-green-600">{{$totalGrade}}%</span></h1>
                    <h1 class="text-2xl font-bold">Remarks: <span class="text-green-600">{{$remarks}}</span></h1>
                </div>
                @endif
            </div>
        </section>
    </section>
</body>
</html>
