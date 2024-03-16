<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\LearnerCourse;
use App\Models\LessonContents;
use App\Models\ActivityContents;
use App\Models\ActivityContentCriterias;
use App\Models\Syllabus;
use App\Models\Lessons;
use App\Models\Activities;
use App\Models\Quizzes;
use App\Models\LearnerCourseProgress;
use App\Models\LearnerSyllabusProgress;
use App\Models\LearnerLessonProgress;
use App\Models\LearnerActivityProgress;
use App\Models\LearnerQuizProgress;
use App\Models\LearnerActivityOutput;
use App\Models\LearnerActivityCriteriaScore;
use App\Models\QuizContents;
use App\Models\QuizReferences;
use App\Models\Questions;
use App\Models\QuestionAnswers;
use App\Models\LearnerQuizOutputs;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\URL;
use Dompdf\Dompdf;
use App\Http\Controllers\DateTime;

class InstructorPerformanceController extends Controller
{
    

    public function performances() {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {
                $courses = DB::table('course')
                    ->select(
                        "course.course_id",
                        "course.course_name",
                        "course.course_code",
                        "instructor.instructor_lname",
                        "instructor.instructor_fname",
                        "instructor.profile_picture"
                    )
                ->where('course.instructor_id', '=', $instructor->instructor_id)
                ->join('instructor', 'instructor.instructor_id', '=', 'course.instructor_id')
                ->orderBy("course.created_at", "ASC")
                ->get();

                $data = [
                    'title' => 'Performance',
                    'scripts' => ['instructor_performance.js'],
                    'courses' => $courses,
                ];
        
                // dd($data);
                return view('instructor_performance.instructorPerformance' , compact('instructor', 'courses'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/instructor');
        }


    }

    public function sessionData() {
        if (session()->has('instructor')) {
            $instructor = session('instructor');

            try{
                $totalsPerDay = DB::table('session_logs')
                ->select(DB::raw('DATE(session_in) as date'), DB::raw('SUM(time_difference) as total_seconds'))
                ->where('session_user_id', $instructor->instructor_id)
                ->where('session_user_type', 'INSTRUCTOR')
                ->groupBy(DB::raw('DATE(session_in)'))
                ->get();

                $data = [
                    'title' => 'Performance',
                    'totalsPerDay' => $totalsPerDay,
                ];
                
        
                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/learner');
        }
    }


    public function totalCourseNum () {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try{

                $totalCourseNum = DB::table('course')
                ->where('instructor_id', $instructor->instructor_id)
                ->count();

                $totalPendingCourseNum = DB::table('course')
                ->where('instructor_id', $instructor->instructor_id)
                ->where('course_status', 'Pending')
                ->count();

                $totalApprovedCourseNum = DB::table('course')
                ->where('instructor_id', $instructor->instructor_id)
                ->where('course_status', 'Approved')
                ->count();

                $totalRejectedCourseNum = DB::table('course')
                ->where('instructor_id', $instructor->instructor_id)
                ->where('course_status', 'Rejected')
                ->count();

                $allInstructorCourses = DB::table('course')
                ->select(
                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.course_description',
                    'course.course_status',
                    'course.course_difficulty',
                    'course.created_at',
                    'course.updated_at',
                    DB::raw('COALESCE(COUNT(learner_course.learner_course_id), 0) as learnerCount'),
                    DB::raw('COALESCE(COUNT(CASE WHEN learner_course.status = "Approved" THEN learner_course.learner_course_id END), 0) as approvedLearnerCount')
                )
                ->leftJoin('learner_course', 'learner_course.course_id', '=', 'course.course_id')
                ->where('instructor_id', $instructor->instructor_id)
                ->groupBy(
                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.course_description',
                    'course.course_status',
                    'course.course_difficulty',
                    'course.created_at',
                    'course.updated_at'
                )
                ->get();
            
            // dd($allInstructorCourses);

                $totalLearnersCount = 0;
                $totalPendingLearnersCount = 0;
                $totalApprovedLearnersCount = 0;
                $totalRejectedLearnersCount = 0;

                $totalSyllabusCount = 0;
                $totalLessonsCount = 0;
                $totalActivitiesCount = 0;
                $totalQuizzesCount = 0;

                foreach ($allInstructorCourses as $course) {

                    $totalLearnersCount += DB::table('learner_course')
                    ->where('course_id', $course->course_id)
                    ->count();

                    $totalPendingLearnersCount += DB::table('learner_course')
                    ->where('course_id', $course->course_id)
                    ->where('status', 'Pending')
                    ->count();

                    $totalApprovedLearnersCount += DB::table('learner_course')
                    ->where('course_id', $course->course_id)
                    ->where('status', 'Approved')
                    ->count();

                    $totalRejectedLearnersCount += DB::table('learner_course')
                    ->where('course_id', $course->course_id)
                    ->where('status', 'Rejected')
                    ->count();

                    $totalSyllabusCount += DB::table('syllabus')
                    ->where('course_id', $course->course_id)
                    ->count();

                    $totalLessonsCount += DB::table('syllabus')
                    ->where('course_id', $course->course_id)
                    ->where('category', 'LESSON')
                    ->count();

                    $totalActivitiesCount += DB::table('syllabus')
                    ->where('course_id', $course->course_id)
                    ->where('category', 'ACTIVITY')
                    ->count();

                    $totalQuizzesCount += DB::table('syllabus')
                    ->where('course_id', $course->course_id)
                    ->where('category', 'QUIZ')
                    ->count();
                }

                $data = [
                    'title' => 'Performance',
                    'scripts' => ['instructor_performance.js'],
                    'totalCourseNum' => $totalCourseNum,
                    'allInstructorCourses' => $allInstructorCourses,
                    'totalLearnersCount' => $totalLearnersCount,
                    'totalPendingLearnersCount' => $totalPendingLearnersCount,
                    'totalApprovedLearnersCount' => $totalApprovedLearnersCount,
                    'totalRejectedLearnersCount' => $totalRejectedLearnersCount,
                    'totalSyllabusCount' => $totalSyllabusCount,
                    'totalLessonsCount' => $totalLessonsCount,
                    'totalActivitiesCount' => $totalActivitiesCount,
                    'totalQuizzesCount' => $totalQuizzesCount,
                    'totalPendingCourseNum' => $totalPendingCourseNum,
                    'totalApprovedCourseNum' => $totalApprovedCourseNum,
                    'totalRejectedCourseNum' => $totalRejectedCourseNum,
                ];

                // dd($data);
        
                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }


    public function courseChartData(Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try{
                
                $selectedCourse = $request->input('selectedCourse');

                $totalCourseNum = DB::table('course')
                ->where('instructor_id', $instructor->instructor_id)
                ->count();

                $totalPendingCourseNum = DB::table('course')
                ->where('instructor_id', $instructor->instructor_id)
                ->where('course_status', 'Pending')
                ->count();

                $totalApprovedCourseNum = DB::table('course')
                ->where('instructor_id', $instructor->instructor_id)
                ->where('course_status', 'Approved')
                ->count();

                $totalRejectedCourseNum = DB::table('course')
                ->where('instructor_id', $instructor->instructor_id)
                ->where('course_status', 'Rejected')
                ->count();

                if ($selectedCourse === "ALL") {
                    $courseData = DB::table('learner_course')
                    ->select(
                        'learner_course.learner_course_id',
                        'learner_course.status',
                        DB::raw('YEAR(learner_course.created_at) as year'), // Extract year
                        DB::raw('MONTH(learner_course.created_at) as month'), // Extract month
                        DB::raw('DAY(learner_course.created_at) as day'), // Extract day
                        DB::raw('TIME(learner_course.created_at) as time'), // Extract time
                        'course.course_name',
                        'learner_course.course_id',
                    )
                    ->join('course', 'learner_course.course_id', '=', 'course.course_id')
                    ->where('course.instructor_id', $instructor->instructor_id)
                    ->get();

                    $data = [
                        'title' => 'Performance',
                        'scripts' => ['instructor_performance.js'],
                        'courseData' => $courseData,
                        'totalCourseNum' => $totalCourseNum,
                        'totalPendingCourseNum' => $totalPendingCourseNum,
                        'totalApprovedCourseNum' => $totalApprovedCourseNum,
                        'totalRejectedCourseNum' => $totalRejectedCourseNum
                        ];
                } else {
                    $courseData = DB::table('learner_course')
                    ->select(
                        'learner_course.learner_course_id',
                        'learner_course.status',
                        DB::raw('YEAR(learner_course.created_at) as year'), // Extract year
                        DB::raw('MONTH(learner_course.created_at) as month'), // Extract month
                        DB::raw('DAY(learner_course.created_at) as day'), // Extract day
                        DB::raw('TIME(learner_course.created_at) as time'), // Extract time
                        'learner_course.course_id',

                        'course.course_name',
                        'course.course_status',
                        'course.course_code',
                        'course.created_at',
                        'course.updated_at',
                    )
                    ->join('course', 'learner_course.course_id', '=', 'course.course_id')
                    ->where('course.instructor_id', $instructor->instructor_id)
                    ->where('learner_course.course_id', $selectedCourse)
                    ->get();

                    $totalLearnersCount = 0;
                    $totalPendingLearnersCount = 0;
                    $totalApprovedLearnersCount = 0;
                    $totalRejectedLearnersCount = 0;
    
                    $totalSyllabusCount = 0;
                    $totalLessonsCount = 0;
                    $totalActivitiesCount = 0;
                    $totalQuizzesCount = 0;

                    if ($courseData->isNotEmpty()) {
                        $course = $courseData->first();

                        $totalLearnersCount += DB::table('learner_course')
                        ->where('course_id', $course->course_id)
                        ->count();
    
                        $totalPendingLearnersCount += DB::table('learner_course')
                        ->where('course_id', $course->course_id)
                        ->where('status', 'Pending')
                        ->count();
    
                        $totalApprovedLearnersCount += DB::table('learner_course')
                        ->where('course_id', $course->course_id)
                        ->where('status', 'Approved')
                        ->count();
    
                        $totalRejectedLearnersCount += DB::table('learner_course')
                        ->where('course_id', $course->course_id)
                        ->where('status', 'Rejected')
                        ->count();
    
                        $totalSyllabusCount += DB::table('syllabus')
                        ->where('course_id', $course->course_id)
                        ->count();
    
                        $totalLessonsCount += DB::table('syllabus')
                        ->where('course_id', $course->course_id)
                        ->where('category', 'LESSON')
                        ->count();
    
                        $totalActivitiesCount += DB::table('syllabus')
                        ->where('course_id', $course->course_id)
                        ->where('category', 'ACTIVITY')
                        ->count();
    
                        $totalQuizzesCount += DB::table('syllabus')
                        ->where('course_id', $course->course_id)
                        ->where('category', 'QUIZ')
                        ->count();


                        $data = [
                            'title' => 'Performance',
                            'scripts' => ['instructor_performance.js'],
                            'courseData' => $courseData,
                            'totalLearnersCount' => $totalLearnersCount,
                            'totalPendingLearnersCount' => $totalPendingLearnersCount,
                            'totalApprovedLearnersCount' => $totalApprovedLearnersCount,
                            'totalRejectedLearnersCount' => $totalRejectedLearnersCount,
                            'totalSyllabusCount' => $totalSyllabusCount,
                            'totalLessonsCount' => $totalLessonsCount,
                            'totalActivitiesCount' => $totalActivitiesCount,
                            'totalQuizzesCount' => $totalQuizzesCount,'totalCourseNum' => $totalCourseNum,
                            'totalPendingCourseNum' => $totalPendingCourseNum,
                            'totalApprovedCourseNum' => $totalApprovedCourseNum,
                            'totalRejectedCourseNum' => $totalRejectedCourseNum
                            ];
                    } else {
                        $data = [
                            'title' => 'Performance',
                            'scripts' => ['instructor_performance.js'],
                            'courseData' => $courseData,
                            'totalLearnersCount' => $totalLearnersCount,
                            'totalPendingLearnersCount' => $totalPendingLearnersCount,
                            'totalApprovedLearnersCount' => $totalApprovedLearnersCount,
                            'totalRejectedLearnersCount' => $totalRejectedLearnersCount,
                            'totalSyllabusCount' => $totalSyllabusCount,
                            'totalLessonsCount' => $totalLessonsCount,
                            'totalActivitiesCount' => $totalActivitiesCount,
                            'totalQuizzesCount' => $totalQuizzesCount,'totalCourseNum' => $totalCourseNum,
                            'totalPendingCourseNum' => $totalPendingCourseNum,
                            'totalApprovedCourseNum' => $totalApprovedCourseNum,
                            'totalRejectedCourseNum' => $totalRejectedCourseNum
                            ];
                    }
                    

                    
                }

    
            return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }

    public function coursePerformance(Course $course) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {
                $course = DB::table('course')
                    ->select(
                        "course.course_id",
                        "course.course_name",
                        "course.course_code",
                        "instructor.instructor_lname",
                        "instructor.instructor_fname",
                        "instructor.profile_picture"
                    )
                ->where('course.instructor_id', '=', $instructor->instructor_id)
                ->join('instructor', 'instructor.instructor_id', '=', 'course.instructor_id')
                ->orderBy("course.created_at", "ASC")
                ->where('course.course_id', $course->course_id)
                ->first();

                $syllabus = DB::table('syllabus')
                ->select(
                    'syllabus_id',
                    'topic_id',
                    'topic_title',
                    'category',
                )
                ->where('course_id', $course->course_id)
                ->orderBy('topic_id', 'ASC')
                ->get();

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/instructor');
        }

        $data = [
            'title' => 'Course Performance',
            'scripts' => ['instructor_course_performance.js'],
            'course' => $course,
            'syllabus' => $syllabus
        ];

        // dd($data);
        return view('instructor_performance.instructorCoursePerformance' , compact('instructor', 'course'))
        ->with($data);
    }

    public function selectedCoursePerformance(Course $course) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {
                $totalLearnerCourseCount = DB::table('learner_course')
                ->where('course_id', $course->course_id)
                ->count();
                
                $totalApprovedLearnerCourseCount = DB::table('learner_course')
                ->where('course_id', $course->course_id)
                ->where('status', 'Approved')
                ->count();

                $totalPendingLearnerCourseCount = DB::table('learner_course')
                ->where('course_id', $course->course_id)
                ->where('status', 'Pending')
                ->count();

                $totalRejectedLearnerCourseCount = DB::table('learner_course')
                ->where('course_id', $course->course_id)
                ->where('status', 'Rejected')
                ->count();


                $totalSyllabusCount = DB::table('syllabus')
                ->where('course_id', $course->course_id)
                ->count();

                $totalLessonsCount = DB::table('syllabus')
                ->where('course_id', $course->course_id)
                ->where('category', 'LESSON')
                ->count();

                $totalActivitiesCount = DB::table('syllabus')
                ->where('course_id', $course->course_id)
                ->where('category', 'ACTIVITY')
                ->count();

                $totalQuizzesCount = DB::table('syllabus')
                ->where('course_id', $course->course_id)
                ->where('category', 'QUIZ')
                ->count();

                $data = [
                    'title' => 'Performance',
                    'totalLearnerCourseCount' => $totalLearnerCourseCount,
                    'totalApprovedLearnerCourseCount' => $totalApprovedLearnerCourseCount,
                    'totalPendingLearnerCourseCount' => $totalPendingLearnerCourseCount,
                    'totalRejectedLearnerCourseCount' => $totalRejectedLearnerCourseCount,
                    'totalSyllabusCount' => $totalSyllabusCount,
                    'totalLessonsCount' => $totalLessonsCount,
                    'totalActivitiesCount' => $totalActivitiesCount,
                    'totalQuizzesCount' => $totalQuizzesCount,
                    ];

                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }

    public function learnerCourseData(Course $course) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {
                // $learnerCourseData = DB::table('learner_course')
                // ->select(
                //     'learner_course.learner_course_id',
                //     'learner_course.learner_id',
                //     'learner_course.status',

                //     'learner.learner_fname',
                //     'learner.learner_lname',
                // )
                // ->join('learner', 'learner.learner_id', '=', 'learner_course.learner_id')
                // ->where('learner_course.course_id', $course->course_id)
                // ->get();

                $learnerCourseProgressData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_progress_id',
                    'learner_course_progress.learner_course_id',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',

                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('learner', 'learner.learner_id', '=', 'learner_course_progress.learner_id')
                ->where('learner_course_progress.course_id', $course->course_id)
                ->get();

                $data = [
                    'title' => 'Performance',
                    // 'learnerCourseData' => $learnerCourseData,
                    'learnerCourseProgressData' => $learnerCourseProgressData,
                    ];

                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }

    public function learnerSyllabusData(Course $course, Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {

                $syllabus_id = $request->input('syllabus_id');

                $syllabusData = DB::table('syllabus')
                ->select(
                    'syllabus_id',
                    'course_id',
                    'topic_id',
                    'topic_title',
                    'category',
                )
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus_id)
                ->first();

                if($syllabusData->category == 'LESSON') {
                    $learnerSyllabusData = DB::table('learner_lesson_progress')
                    ->select(
                        'learner_lesson_progress.learner_lesson_progress_id AS learner_progress_id',
                        'learner_lesson_progress.learner_course_id',
                        'learner_lesson_progress.course_id',
                        'learner_lesson_progress.syllabus_id',
                        'learner_lesson_progress.lesson_id AS topic_id',
                        'learner_lesson_progress.status',
                        'learner_lesson_progress.start_period',
                        'learner_lesson_progress.finish_period',

                        'learner.learner_fname',
                        'learner.learner_lname',

                        'learner_course.created_at',
                    )
                    ->join('learner', 'learner_lesson_progress.learner_id', '=', 'learner.learner_id')
                    ->join('learner_course', 'learner.learner_id', '=', 'learner_course.learner_id')
                    ->where('learner_lesson_progress.course_id', $course->course_id)
                    ->where('learner_lesson_progress.syllabus_id', $syllabus_id)
                    ->get();
                } else if ($syllabusData->category == 'ACTIVITY') {
                    $learnerSyllabusData = DB::table('learner_activity_progress')
                    ->select(
                        'learner_activity_progress.learner_activity_progress_id AS learner_progress_id',
                        'learner_activity_progress.learner_course_id',
                        'learner_activity_progress.course_id',
                        'learner_activity_progress.syllabus_id',
                        'learner_activity_progress.activity_id AS topic_id',
                        'learner_activity_progress.status',
                        'learner_activity_progress.start_period',
                        'learner_activity_progress.finish_period',

                        
                        'learner.learner_fname',
                        'learner.learner_lname',

                        'learner_course.created_at',
                    )
                    ->join('learner', 'learner_activity_progress.learner_id', '=', 'learner.learner_id')
                    ->join('learner_course', 'learner.learner_id', '=', 'learner_course.learner_id')
                    ->where('learner_activity_progress.course_id', $course->course_id)
                    ->where('learner_activity_progress.syllabus_id', $syllabus_id)
                    ->get();
                } else {
                    $learnerSyllabusData = DB::table('learner_quiz_progress')
                    ->select(
                        'learner_quiz_progress.learner_quiz_progress_id AS learner_progress_id',
                        'learner_quiz_progress.learner_course_id',
                        'learner_quiz_progress.course_id',
                        'learner_quiz_progress.syllabus_id',
                        'learner_quiz_progress.quiz_id AS topic_id',
                        'learner_quiz_progress.status',
                        'learner_quiz_progress.start_period',
                        'learner_quiz_progress.finish_period',
                        'learner_quiz_progress.attempt',
                      
                        'learner.learner_fname',
                        'learner.learner_lname',

                        'learner_course.created_at',
                    )
                    ->join('learner', 'learner_quiz_progress.learner_id', '=', 'learner.learner_id')
                    ->join('learner_course', 'learner.learner_id', '=', 'learner_course.learner_id')
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus_id)
                    ->get();
                }

                $data = [
                    'title' => 'Performance',
                    'syllabusData' => $syllabusData,
                    'learnerSyllabusData' => $learnerSyllabusData,
                    ];

                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }

    public function courseSyllabusPerformance(Course $course, Syllabus $syllabus) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {
                $courseData = DB::table('course')
                ->select(
                    'course_id',
                    'course_name',
                    'course_code',
                    'course_description',
                    'course_status',
                )
                ->where('course_id', $course->course_id)
                ->first();

                $syllabusData = DB::table('syllabus')
                ->select(
                    'syllabus_id',
                    'course_id',
                    'topic_id',
                    'topic_title',
                    'category',
                )
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->first();

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/instructor');
        }

        
        if($syllabusData->category === 'LESSON') {

            $data = [
                'title' => 'Course Performance',
                'scripts' => ['instructor_syllabus_lesson_performance.js'],
                'courseData' => $courseData,
                'syllabusData' => $syllabusData,
            ];
    
            // dd($data);

            return view('instructor_performance.instructorSyllabusLessonPerformance' , compact('instructor'))
        ->with($data);
        } else if($syllabusData->category === 'ACTIVITY') {
            $data = [
                'title' => 'Course Performance',
                'scripts' => ['instructor_syllabus_activity_performance.js'],
                'courseData' => $courseData,
                'syllabusData' => $syllabusData,
            ];
    
            // dd($data);

            return view('instructor_performance.instructorSyllabusActivityPerformance' , compact('instructor'))
        ->with($data);
        } else {
            $data = [
                'title' => 'Course Performance',
                'scripts' => ['instructor_syllabus_quiz_performance.js'],
                'courseData' => $courseData,
                'syllabusData' => $syllabusData,
            ];
    
            // dd($data);

            return view('instructor_performance.instructorSyllabusQuizPerformance' , compact('instructor'))
        ->with($data);
        }

    }

    public function courseSyllabusLessonPerformance(Course $course, Syllabus $syllabus) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {
                $learnerLessonProgressData = DB::table('learner_lesson_progress')
                ->select(
                    'learner_lesson_progress.learner_lesson_progress_id',
                    'learner_lesson_progress.learner_course_id',
                    'learner_lesson_progress.course_id',
                    'learner_lesson_progress.status',
                    'learner_lesson_progress.start_period',
                    'learner_lesson_progress.finish_period',

                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('learner', 'learner_lesson_progress.learner_id', '=', 'learner.learner_id')
                ->where('learner_lesson_progress.course_id', $course->course_id)
                ->where('learner_lesson_progress.syllabus_id', $syllabus->syllabus_id)
                ->get();

                $totalTimeDifference = 0;
                $numberOfRows = count($learnerLessonProgressData);

                foreach ($learnerLessonProgressData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                   
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    
                    $totalTimeDifference += $timeDifference;
                }

                $averageTimeDifference = ($numberOfRows > 0) ? ($totalTimeDifference / $numberOfRows) : 0;
                $averageTimeDifferenceInSeconds = $averageTimeDifference;
                $averageTimeFormatted = gmdate("H:i:s", $averageTimeDifferenceInSeconds);
                

                $totalLearnerLessonProgressCount = DB::table('learner_lesson_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->count();

                $totalLearnerLessonCompleteCount = DB::table('learner_lesson_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'COMPLETED')
                ->count();

                $totalLearnerLessonLockedCount = DB::table('learner_lesson_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'LOCKED')
                ->count();


                $totalLearnerLessonInProgressCount = DB::table('learner_lesson_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'IN PROGRESS')
                ->count();

                $data = [
                    'title' => 'Performance',
                    'learnerLessonProgressData' => $learnerLessonProgressData,
                    'totalLearnerLessonProgressCount' => $totalLearnerLessonProgressCount,
                    'totalLearnerLessonCompleteCount' => $totalLearnerLessonCompleteCount,
                    'totalLearnerLessonLockedCount' => $totalLearnerLessonLockedCount,
                    'totalLearnerLessonInProgressCount' => $totalLearnerLessonInProgressCount,
                    'averageTimeDifference' => $averageTimeFormatted,
                    ];

                // dd($data);
                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }


    public function courseSyllabusActivityPerformance(Course $course, Syllabus $syllabus) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {
                $learnerActivityProgressData = DB::table('learner_activity_progress')
                ->select(
                    'learner_activity_progress.learner_activity_progress_id',
                    'learner_activity_progress.learner_course_id',
                    'learner_activity_progress.course_id',
                    'learner_activity_progress.status',
                    'learner_activity_progress.activity_id',
                    'learner_activity_progress.syllabus_id',
                    'learner_activity_progress.start_period',
                    'learner_activity_progress.finish_period',

                    'learner.learner_fname',
                    'learner.learner_lname',

                    'syllabus.topic_id',
                )
                ->join('syllabus', 'learner_activity_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->join('learner', 'learner_activity_progress.learner_id', '=', 'learner.learner_id')
                ->where('learner_activity_progress.course_id', $course->course_id)
                ->where('learner_activity_progress.syllabus_id', $syllabus->syllabus_id)
                ->get();

                $learnerActivityProgressData_first = $learnerActivityProgressData->first();

                $totalTimeDifference = 0;
                $numberOfRows = count($learnerActivityProgressData);

                foreach ($learnerActivityProgressData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                    // Calculate time difference in seconds
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    // Calculate time difference in days
                    $daysDifference = $finishPeriod->diff($startPeriod)->days;

                    // Convert days to seconds and add to total time difference
                    $totalTimeDifference += ($daysDifference * 24 * 60 * 60) + $timeDifference;
                }

                // Calculate average time difference in seconds
                $averageTimeDifference = ($numberOfRows > 0) ? ($totalTimeDifference / $numberOfRows) : 0;

                // Format the average time difference
                $averageTimeFormatted = gmdate("H:i:s", $averageTimeDifference);

                $totalLearnerActivityProgressCount = DB::table('learner_activity_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->count();

                $totalLearnerActivityCompleteCount = DB::table('learner_activity_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'COMPLETED')
                ->count();

                $totalLearnerActivityLockedCount = DB::table('learner_activity_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'LOCKED')
                ->count();


                $totalLearnerActivityInProgressCount = DB::table('learner_activity_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'IN PROGRESS')
                ->count();

                $learnerActivityOutputData = DB::table('learner_activity_output')
                ->select(
                    'learner_activity_output_id',
                    'learner_course_id',
                    'attempt',
                )
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('activity_id', $learnerActivityProgressData_first->activity_id)
                ->get();

                $learnerActivityOutputData_firstAttemptOnly = DB::table('learner_activity_output')
                ->select(
                    'learner_activity_output_id',
                    'learner_course_id',
                    'attempt',
                )
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('activity_id', $learnerActivityProgressData_first->activity_id)
                ->where('attempt', 1)
                ->count();

                $learnerActivityOutputData_withSecondAttempt = DB::table('learner_activity_output')
                ->select(
                    'learner_activity_output_id',
                    'learner_course_id',
                    'attempt',
                )
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('activity_id', $learnerActivityProgressData_first->activity_id)
                ->where('attempt', 2)
                ->count();

                $attemptCount = [
                    'OneAttempt' => ($learnerActivityOutputData_firstAttemptOnly - $learnerActivityOutputData_withSecondAttempt),
                    'ReAttempts' => $learnerActivityOutputData_withSecondAttempt,
                ];

                $data = [
                    'title' => 'Performance',
                    'learnerActivityProgressData' => $learnerActivityProgressData,
                    'totalLearnerActivityProgressCount' => $totalLearnerActivityProgressCount,
                    'totalLearnerActivityCompleteCount' => $totalLearnerActivityCompleteCount,
                    'totalLearnerActivityLockedCount' => $totalLearnerActivityLockedCount,
                    'totalLearnerActivityInProgressCount' => $totalLearnerActivityInProgressCount,
                    'averageTimeDifference' => $averageTimeFormatted,
                    'learnerActivityOutputData' => $learnerActivityOutputData,
                    'attemptCount' => $attemptCount
                    ];

                // dd($data);
                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }

    public function courseSyllabusActivityScoresPerformance(Course $course, Syllabus $syllabus) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {
                $learnerActivityOutputOverallScoreData = DB::table('learner_activity_output')
                ->select(
                    'learner_activity_output.learner_activity_output_id',
                    'learner_activity_output.learner_course_id',
                    'learner_activity_output.activity_id',
                    'learner_activity_output.total_score',
                    'learner_activity_output.attempt',
                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('learner_course', 'learner_activity_output.learner_course_id', '=', 'learner_course.learner_course_id')
                ->join('learner', 'learner_course.learner_id', '=', 'learner.learner_id')
                ->where('learner_activity_output.course_id', $course->course_id)
                ->where('learner_activity_output.syllabus_id', $syllabus->syllabus_id)
                ->groupBy('learner_activity_output.learner_activity_output_id')
                ->get();

            $learnerActivityOutputCriteriaScoreData = [];

            foreach ($learnerActivityOutputOverallScoreData as $learnerOutput) {
                $criteriaScoreData = DB::table('learner_activity_criteria_score')
                    ->select(
                        'learner_activity_criteria_score.learner_activity_criteria_score_id',
                        'learner_activity_criteria_score.learner_activity_output_id',
                        'learner_activity_criteria_score.activity_content_criteria_id',
                        'learner_activity_criteria_score.attempt',
                        'learner_activity_criteria_score.score',
                        'activity_content_criteria.criteria_title'
                    )
                    ->join('activity_content_criteria', 'learner_activity_criteria_score.activity_content_criteria_id', '=', 'activity_content_criteria.activity_content_criteria_id')
                    ->where('learner_activity_criteria_score.learner_activity_output_id', $learnerOutput->learner_activity_output_id)
                    ->get();

                // Add the criteria score data to the result array
                $learnerActivityOutputCriteriaScoreData[$learnerOutput->learner_activity_output_id] = $criteriaScoreData;
            }

            $data = [
                'title' => 'Performance',
                'learnerActivityOutputOverallScoreData' => $learnerActivityOutputOverallScoreData,
                'learnerActivityOutputCriteriaScoreData' => $learnerActivityOutputCriteriaScoreData,
            ];

            return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }


    public function courseSyllabusQuizPerformance(Course $course, Syllabus $syllabus) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {
                $learnerQuizProgressData = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.learner_quiz_progress_id',
                    'learner_quiz_progress.learner_course_id',
                    'learner_quiz_progress.course_id',
                    'learner_quiz_progress.status',
                    'learner_quiz_progress.quiz_id',
                    'learner_quiz_progress.syllabus_id',
                    'learner_quiz_progress.start_period',
                    'learner_quiz_progress.finish_period',
                    'learner_quiz_progress.attempt',
                    'learner_quiz_progress.score',
                    'learner_quiz_progress.remarks',

                    'learner.learner_fname',
                    'learner.learner_lname',

                    'syllabus.topic_id',
                )
                ->join('syllabus', 'learner_quiz_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->join('learner', 'learner_quiz_progress.learner_id', '=', 'learner.learner_id')
                ->where('learner_quiz_progress.course_id', $course->course_id)
                ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                ->get();

                $learnerQuizProgressData_first = $learnerQuizProgressData->first();

                $totalTimeDifference = 0;
                $numberOfRows = count($learnerQuizProgressData);

                foreach ($learnerQuizProgressData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                    // Calculate time difference in seconds
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    // Calculate time difference in days
                    $daysDifference = $finishPeriod->diff($startPeriod)->days;

                    // Convert days to seconds and add to total time difference
                    $totalTimeDifference += ($daysDifference * 24 * 60 * 60) + $timeDifference;
                }

                // Calculate average time difference in seconds
                $averageTimeDifference = ($numberOfRows > 0) ? ($totalTimeDifference / $numberOfRows) : 0;

                // Format the average time difference
                $averageTimeFormatted = gmdate("H:i:s", $averageTimeDifference);

                $totalLearnerQuizProgressCount = DB::table('learner_quiz_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->count();

                $totalLearnerQuizCompleteCount = DB::table('learner_quiz_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'COMPLETED')
                ->count();

                $totalLearnerQuizLockedCount = DB::table('learner_quiz_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'LOCKED')
                ->count();


                $totalLearnerQuizInProgressCount = DB::table('learner_quiz_progress')
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('status', 'IN PROGRESS')
                ->count();

               

                $learnerQuizOutputData_firstAttemptOnly = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress_id',
                    'learner_course_id',
                    'attempt',
                )
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('quiz_id', $learnerQuizProgressData_first->quiz_id)
                ->where('attempt', 1)
                ->count();

                $learnerQuizOutputData_withSecondAttempt = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress_id',
                    'learner_course_id',
                    'attempt',
                )
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('quiz_id', $learnerQuizProgressData_first->quiz_id)
                ->where('attempt', 2)
                ->count();

                $attemptCount = [
                    'OneAttempt' => ($learnerQuizOutputData_firstAttemptOnly - $learnerQuizOutputData_withSecondAttempt),
                    'ReAttempts' => $learnerQuizOutputData_withSecondAttempt,
                ];

                $data = [
                    'title' => 'Performance',
                    'learnerQuizProgressData' => $learnerQuizProgressData,
                    'totalLearnerQuizProgressCount' => $totalLearnerQuizProgressCount,
                    'totalLearnerQuizCompleteCount' => $totalLearnerQuizCompleteCount,
                    'totalLearnerQuizLockedCount' => $totalLearnerQuizLockedCount,
                    'totalLearnerQuizInProgressCount' => $totalLearnerQuizInProgressCount,
                    'averageTimeDifference' => $averageTimeFormatted,
                    'attemptCount' => $attemptCount
                    ];

                // dd($data);
                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }


    public function courseSyllabusQuizScoresPerformance(Course $course, Syllabus $syllabus) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {
                $learnerQuizOutputOverallScoreData = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.learner_quiz_progress_id',
                    'learner_quiz_progress.learner_course_id',
                    'learner_quiz_progress.quiz_id',
                    'learner_quiz_progress.score',
                    'learner_quiz_progress.attempt',
                    'learner_quiz_progress.remarks',
                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('learner_course', 'learner_quiz_progress.learner_course_id', '=', 'learner_course.learner_course_id')
                ->join('learner', 'learner_course.learner_id', '=', 'learner.learner_id')
                ->where('learner_quiz_progress.course_id', $course->course_id)
                ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                ->get();

           

            $data = [
                'title' => 'Performance',
                'learnerQuizOutputOverallScoreData' => $learnerQuizOutputOverallScoreData,
            ];

            return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }

    public function courseSyllabusQuizContentOutputPerformance(Course $course, Syllabus $syllabus) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {
                $learnerQuizOutputOverallScoreData = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.learner_quiz_progress_id',
                    'learner_quiz_progress.learner_course_id',
                    'learner_quiz_progress.quiz_id',
                    'learner_quiz_progress.score',
                    'learner_quiz_progress.attempt',
                    'learner_quiz_progress.remarks',
                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('learner', 'learner_quiz_progress.learner_id', '=', 'learner.learner_id')
                ->where('learner_quiz_progress.course_id', $course->course_id)
                ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                ->get();

                $learnerQuizOutputOverallScoreData_firstRow = $learnerQuizOutputOverallScoreData->first();

                $quizData = DB::table('quiz_content')
                ->select(
                    'quiz_content.quiz_content_id',
                    'quiz_content.quiz_id',
                    'quiz_content.syllabus_id',
                    'quiz_content.question_id',

                    'questions.question',
                    'questions.category',

                    'question_answer.question_answer_id',
                    'question_answer.answer',
                    'question_answer.isCorrect',
                )
                ->join('questions', 'quiz_content.question_id', '=', 'questions.question_id')
                ->join('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
                ->where('quiz_content.quiz_id', $learnerQuizOutputOverallScoreData_firstRow->quiz_id)
                ->get();

            $learnerQuizOutputData = DB::table('learner_quiz_output')
            ->select(
                'learner_quiz_output.learner_quiz_output_id',
                'learner_quiz_output.learner_course_id',
                'learner_quiz_output.quiz_id',
                'learner_quiz_output.quiz_content_id',
                'learner_quiz_output.answer',
                'learner_quiz_output.isCorrect',
            
                'quiz_content.question_id',
                'questions.question',
            )
            ->join('quiz_content', 'learner_quiz_output.quiz_content_id', '=' , 'quiz_content.quiz_content_id')
            ->join('questions', 'quiz_content.question_id', '=' , 'questions.question_id')
            ->where('learner_quiz_output.course_id', $course->course_id)
            ->where('learner_quiz_output.syllabus_id', $syllabus->syllabus_id)
            ->where('learner_quiz_output.quiz_id', $learnerQuizOutputOverallScoreData_firstRow->quiz_id)
            ->get();

            $data = [
                'title' => 'Performance',
                'learnerQuizOutputOverallScoreData' => $learnerQuizOutputOverallScoreData,
                'quizData' => $quizData,
                'learnerQuizOutputData' => $learnerQuizOutputData,
            ];

            return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }

    public function learnerCoursePerformance(Course $course, LearnerCourse $learner_course) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            

            try {
                $course = DB::table('course')
                    ->select(
                        "course.course_id",
                        "course.course_name",
                        "course.course_code",
                        "instructor.instructor_lname",
                        "instructor.instructor_fname",
                        "instructor.profile_picture"
                    )
                ->where('course.instructor_id', '=', $instructor->instructor_id)
                ->join('instructor', 'instructor.instructor_id', '=', 'course.instructor_id')
                ->orderBy("course.created_at", "ASC")
                ->where('course.course_id', $course->course_id)
                ->first();

                $syllabus = DB::table('syllabus')
                ->select(
                    'syllabus_id',
                    'topic_id',
                    'topic_title',
                    'category',
                )
                ->where('course_id', $course->course_id)
                ->orderBy('topic_id', 'ASC')
                ->get();

                $learner_course_data = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress.learner_syllabus_progress_id',
                    'learner_syllabus_progress.learner_course_id',
                    'learner_syllabus_progress.category',
                    'learner_syllabus_progress.syllabus_id',
                    'learner_syllabus_progress.status',

                    'syllabus.topic_id',
                    'syllabus.topic_title',
                    'syllabus.category',
                )
                ->join('syllabus', 'learner_syllabus_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                ->where('learner_syllabus_progress.course_id', $course->course_id)
                ->get();

                $learner_data = DB::table('learner_course')
                ->select(
                    'learner_course.*',

                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('learner', 'learner_course.learner_id', '=', 'learner.learner_id')
                ->where('learner_course.learner_course_id', $learner_course->learner_course_id)
                ->first();


                $data = [
                    'title' => 'Course Performance',
                    'scripts' => ['instructor_view_learner_performance.js'],
                    'course' => $course,
                    'syllabus' => $syllabus,
                    'learnerCourse' => $learner_course_data,
                    'learner' => $learner_data,
                ];
        
                // dd($data);
                return view('instructor_performance.instructorViewLearnerPerformance' , compact('instructor'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/instructor');
        }
    }

    public function learnerCourseOverallPerformance(Course $course, LearnerCourse $learner_course) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {

                $learnerCourseData = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress_id',
                    'learner_course_id',
                    'syllabus_id',
                    'category',
                    'status',
                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->get();

                $learnerCourseCount = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress_id',
                    'learner_course_id',
                    'syllabus_id',
                    'category',
                    'status',
                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->count();

                $learnerCompletedSyllabusCount = DB::table('learner_syllabus_progress')
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('status', 'COMPLETED')
                ->count();

                $learnerInProgressSyllabusCount = DB::table('learner_syllabus_progress')
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('status', 'IN PROGRESS')
                ->count();

                $learnerLockedSyllabusCount = DB::table('learner_syllabus_progress')
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where(function ($query) {
                    $query->where('status', 'LOCKED')
                          ->orWhere('status', 'NOT YET STARTED');
                })
                ->count();

                $percentageCompleted = ($learnerCompletedSyllabusCount / $learnerCourseCount) * 100;

                $learnerLessonCompletedData = DB::table('learner_lesson_progress')
                ->select(
                    'start_period',
                    'finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_lesson_progress.finish_period, learner_lesson_progress.start_period), "%H:%i:%s") as time_difference')

                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('status', 'COMPLETED')
                ->get();

                $learnerActivityCompletedData = DB::table('learner_activity_progress')
                ->select(
                    'start_period',
                    'finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_activity_progress.finish_period, learner_activity_progress.start_period), "%H:%i:%s") as time_difference')

                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('status', 'COMPLETED')
                ->get();

                $learnerQuizCompletedData = DB::table('learner_quiz_progress')
                ->select(
                    'start_period',
                    'finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_quiz_progress.finish_period, learner_quiz_progress.start_period), "%H:%i:%s") as time_difference')

                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('status', 'COMPLETED')
                ->get();

                $totalLessonTimeDifference = 0;
                $totalActivityTimeDifference = 0;
                $totalQuizTimeDifference = 0;

                $numberOfLessonRows = count($learnerLessonCompletedData);
                $numberOfActivityRows = count($learnerActivityCompletedData);
                $numberOfQuizRows = count($learnerQuizCompletedData);

                foreach ($learnerLessonCompletedData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                    // Calculate time difference in seconds
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    // Calculate time difference in days
                    $daysDifference = $finishPeriod->diff($startPeriod)->days;

                    // Convert days to seconds and add to total time difference
                    $totalLessonTimeDifference += ($daysDifference * 24 * 60 * 60) + $timeDifference;
                }

                foreach ($learnerActivityCompletedData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                    // Calculate time difference in seconds
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    // Calculate time difference in days
                    $daysDifference = $finishPeriod->diff($startPeriod)->days;

                    // Convert days to seconds and add to total time difference
                    $totalActivityTimeDifference += ($daysDifference * 24 * 60 * 60) + $timeDifference;
                }

                foreach ($learnerQuizCompletedData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                    // Calculate time difference in seconds
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    // Calculate time difference in days
                    $daysDifference = $finishPeriod->diff($startPeriod)->days;

                    // Convert days to seconds and add to total time difference
                    $totalQuizTimeDifference += ($daysDifference * 24 * 60 * 60) + $timeDifference;
                }


                $totalNumberOfRows = ($numberOfLessonRows + $numberOfActivityRows + $numberOfQuizRows);
                $totalTimeDifference = ($totalLessonTimeDifference + $totalActivityTimeDifference + $totalQuizTimeDifference);

                // Calculate average time difference in seconds
                $averageTimeDifference = ($totalNumberOfRows > 0) ? ($totalTimeDifference / $totalNumberOfRows) : 0;

                // Format the average time difference
                $averageTimeFormatted = gmdate("H:i:s", $averageTimeDifference);

                $data = [
                'title' => 'Performance',
                'learnerCourseData' => $learnerCourseData,
                'learnerCourseCount' => $learnerCourseCount,
                'learnerCompletedSyllabusCount' => $learnerCompletedSyllabusCount,
                'learnerInProgressSyllabusCount' => $learnerInProgressSyllabusCount,
                'learnerLockedSyllabusCount' => $learnerLockedSyllabusCount,
                'percentageCompleted' => $percentageCompleted,
                'averageTimeFormatted' => $averageTimeFormatted,
                'learnerLessonCompletedData' => $learnerLessonCompletedData,
                'learnerActivityCompletedData' => $learnerActivityCompletedData,
                'learnerQuizCompletedData' => $learnerQuizCompletedData,
            ];

            return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }

    public function learnerCourseSyllabusPerformance(Course $course, LearnerCourse $learner_course) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {

                $learnerLessonPerformanceData = DB::table('learner_lesson_progress')
                ->select(
                    'learner_lesson_progress.learner_lesson_progress_id',
                    'learner_lesson_progress.lesson_id',
                    'learner_lesson_progress.status',
                    'learner_lesson_progress.start_period',
                    'learner_lesson_progress.finish_period',
                    DB::raw('TIMEDIFF(learner_lesson_progress.finish_period, learner_lesson_progress.start_period) as time_difference'),

                    'syllabus.topic_title',
                )
                ->join('syllabus', 'learner_lesson_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->where('learner_lesson_progress.course_id', $course->course_id)
                ->where('learner_lesson_progress.learner_course_id', $learner_course->learner_course_id)
                ->get();

                $learnerLessonCompletedPerformanceData = DB::table('learner_lesson_progress')
                ->select(
                    'learner_lesson_progress.learner_lesson_progress_id',
                    'learner_lesson_progress.lesson_id',
                    'learner_lesson_progress.status',
                    'learner_lesson_progress.start_period',
                    'learner_lesson_progress.finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_lesson_progress.finish_period, learner_lesson_progress.start_period), "%H:%i:%s") as time_difference'),

                
                    'syllabus.topic_title',
                )
                ->join('syllabus', 'learner_lesson_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->where('learner_lesson_progress.course_id', $course->course_id)
                ->where('learner_lesson_progress.learner_course_id', $learner_course->learner_course_id)
                ->where('learner_lesson_progress.status', 'COMPLETED')
                ->get();

                $totalLessonTimeDifference = 0;
                $numberOfLessonRows = count($learnerLessonCompletedPerformanceData);

                foreach ($learnerLessonCompletedPerformanceData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                    // Calculate time difference in seconds
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    // Calculate time difference in days
                    $daysDifference = $finishPeriod->diff($startPeriod)->days;

                    // Convert days to seconds and add to total time difference
                    $totalLessonTimeDifference += ($daysDifference * 24 * 60 * 60) + $timeDifference;
                }

                // Calculate average time difference in seconds
                $averageLessonTimeDifference = ($numberOfLessonRows > 0) ? ($totalLessonTimeDifference / $numberOfLessonRows) : 0;

                // Format the average time difference
                $averageLessonTimeFormatted = gmdate("H:i:s", $averageLessonTimeDifference);



                $learnerActivityPerformanceData = DB::table('learner_activity_progress')
                ->select(
                    'learner_activity_progress.learner_activity_progress_id',
                    'learner_activity_progress.course_id',
                    'learner_activity_progress.syllabus_id',
                    'learner_activity_progress.activity_id',
                    'learner_activity_progress.learner_course_id',
                    'learner_activity_progress.status',
                    'learner_activity_progress.start_period',
                    'learner_activity_progress.finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_activity_progress.finish_period, learner_activity_progress.start_period), "%H:%i:%s") as time_difference'),

                    'syllabus.topic_title',
                    'syllabus.topic_id',
                )
                ->join('syllabus', 'learner_activity_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->where('learner_activity_progress.course_id', $course->course_id)
                ->where('learner_activity_progress.learner_course_id', $learner_course->learner_course_id)
                ->get();

                $learnerActivityCompletedPerformanceData = DB::table('learner_activity_progress')
                ->select(
                    'learner_activity_progress.learner_activity_progress_id',
                    'learner_activity_progress.course_id',
                    'learner_activity_progress.syllabus_id',
                    'learner_activity_progress.activity_id',
                    'learner_activity_progress.learner_course_id',
                    'learner_activity_progress.status',
                    'learner_activity_progress.start_period',
                    'learner_activity_progress.finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_activity_progress.finish_period, learner_activity_progress.start_period), "%H:%i:%s") as time_difference'),

                    'syllabus.topic_title',
                    'syllabus.topic_id',
                )
                ->join('syllabus', 'learner_activity_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->where('learner_activity_progress.course_id', $course->course_id)
                ->where('learner_activity_progress.learner_course_id', $learner_course->learner_course_id)
                ->where('learner_activity_progress.status', 'COMPLETED')
                ->get();

                $learnerActivityCompletedOutputData = DB::table('learner_activity_output')
                ->select(
                    'learner_activity_output_id',
                    'learner_course_id',
                    'syllabus_id',
                    'activity_id',
                    'activity_content_id',
                    'total_score',
                    'attempt',
                    'mark',
                )
                ->where('learner_activity_output.course_id', $course->course_id)
                ->where('learner_activity_output.learner_course_id', $learner_course->learner_course_id)
                ->get();

                $totalActivityTimeDifference = 0;
                $numberOfActivityRows = count($learnerActivityCompletedPerformanceData);

                foreach ($learnerActivityCompletedPerformanceData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                    // Calculate time difference in seconds
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    // Calculate time difference in days
                    $daysDifference = $finishPeriod->diff($startPeriod)->days;

                    // Convert days to seconds and add to total time difference
                    $totalActivityTimeDifference += ($daysDifference * 24 * 60 * 60) + $timeDifference;
                }

                // Calculate average time difference in seconds
                $averageActivityTimeDifference = ($numberOfActivityRows > 0) ? ($totalActivityTimeDifference / $numberOfActivityRows) : 0;

                // Format the average time difference
                $averageActivityTimeFormatted = gmdate("H:i:s", $averageActivityTimeDifference);



                
                $learnerQuizPerformanceData = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.learner_quiz_progress_id',
                    'learner_quiz_progress.course_id',
                    'learner_quiz_progress.syllabus_id',
                    'learner_quiz_progress.quiz_id',
                    'learner_quiz_progress.learner_course_id',
                    'learner_quiz_progress.status',
                    'learner_quiz_progress.attempt',
                    'learner_quiz_progress.score',
                    'learner_quiz_progress.remarks',
                    'learner_quiz_progress.start_period',
                    'learner_quiz_progress.finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_quiz_progress.finish_period, learner_quiz_progress.start_period), "%H:%i:%s") as time_difference'),

                    'syllabus.topic_title',
                    'syllabus.topic_id',
                )
                ->join('syllabus', 'learner_quiz_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->where('learner_quiz_progress.course_id', $course->course_id)
                ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                ->get();

                $learnerQuizCompletedPerformanceData = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.learner_quiz_progress_id',
                    'learner_quiz_progress.course_id',
                    'learner_quiz_progress.syllabus_id',
                    'learner_quiz_progress.quiz_id',
                    'learner_quiz_progress.learner_course_id',
                    'learner_quiz_progress.status',
                    'learner_quiz_progress.attempt',
                    'learner_quiz_progress.score',
                    'learner_quiz_progress.remarks',
                    'learner_quiz_progress.start_period',
                    'learner_quiz_progress.finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_quiz_progress.finish_period, learner_quiz_progress.start_period), "%H:%i:%s") as time_difference'),

                    'syllabus.topic_title',
                    'syllabus.topic_id',
                )
                ->join('syllabus', 'learner_quiz_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->where('learner_quiz_progress.course_id', $course->course_id)
                ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                ->where('learner_quiz_progress.status', 'COMPLETED')
                ->get();

                $totalQuizTimeDifference = 0;
                $numberOfQuizRows = count($learnerQuizCompletedPerformanceData);

                foreach ($learnerQuizCompletedPerformanceData as $row) {
                    
                    $startPeriod = new \DateTime($row->start_period);
                    $finishPeriod = new \DateTime($row->finish_period);

                    // Calculate time difference in seconds
                    $timeDifference = $finishPeriod->getTimestamp() - $startPeriod->getTimestamp();

                    // Calculate time difference in days
                    $daysDifference = $finishPeriod->diff($startPeriod)->days;

                    // Convert days to seconds and add to total time difference
                    $totalQuizTimeDifference += ($daysDifference * 24 * 60 * 60) + $timeDifference;
                }

                // Calculate average time difference in seconds
                $averageQuizTimeDifference = ($numberOfQuizRows > 0) ? ($totalQuizTimeDifference / $numberOfQuizRows) : 0;

                // Format the average time difference
                $averageQuizTimeFormatted = gmdate("H:i:s", $averageQuizTimeDifference);



                $data = [
                'title' => 'Performance',
                'learnerLessonPerformanceData' => $learnerLessonPerformanceData,
                'learnerLessonCompletedPerformanceData' => $learnerLessonCompletedPerformanceData,
                'averageLessonTimeFormatted' => $averageLessonTimeFormatted,
                'learnerActivityPerformanceData' => $learnerActivityPerformanceData,
                'learnerActivityCompletedPerformanceData' => $learnerActivityCompletedPerformanceData,
                'averageActivityTimeFormatted' => $averageActivityTimeFormatted,
                'learnerActivityCompletedOutputData' => $learnerActivityCompletedOutputData,
                'learnerQuizPerformanceData' => $learnerQuizPerformanceData,
                'learnerQuizCompletedPerformanceData' => $learnerQuizCompletedPerformanceData,
                'averageQuizTimeFormatted' => $averageQuizTimeFormatted,
                ];

            return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/instructor');
        }
    }
}
