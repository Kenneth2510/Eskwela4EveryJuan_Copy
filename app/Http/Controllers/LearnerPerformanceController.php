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

class LearnerPerformanceController extends Controller
{
    public function performances() {
        if (session()->has('learner')) {
            $learner= session('learner');


            try {

                $learnerCourseData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_progress_id',
                    'learner_course_progress.learner_course_id',
                    'learner_course_progress.course_id',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',

                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.instructor_id',

                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                )
                ->join('course', 'learner_course_progress.course_id', '=', 'course.course_id')
                ->join('instructor', 'course.instructor_id', '=', 'instructor.instructor_id')
                ->where('learner_course_progress.learner_id', $learner->learner_id)
                ->get();

                
                $data = [
                    'title' => 'Course Performance',
                    'scripts' => ['learner_performance.js'],
                    'courseData' => $learnerCourseData,
                ];
        
                // dd($data);
                return view('learner_performance.learnerPerformance' , compact('learner'))
                ->with($data);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/learner');
        }
    }

    public function enrolledCoursesPerformances() {
        if (session()->has('learner')) {
            $learner= session('learner');

            try{
                $learnerCourseData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_progress_id',
                    'learner_course_progress.learner_course_id',
                    'learner_course_progress.course_id',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',

                    'course.course_name',
                    'course.course_code',
                    'course.instructor_id',

                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                )
                ->join('course', 'learner_course_progress.course_id', '=', 'course.course_id')
                ->join('instructor', 'course.instructor_id', '=', 'instructor.instructor_id')
                ->where('learner_course_progress.learner_id', $learner->learner_id)
                ->get();

                $totalLearnerCourseCount = DB::table('learner_course_progress')
                ->where('learner_course_progress.learner_id', $learner->learner_id)
                ->count();

                $totalLearnerApprovedCourseCount = DB::table('learner_course')
                ->where('learner_course.learner_id', $learner->learner_id)
                ->where('learner_course.status', 'Approved')
                ->count();

                $totalLearnerPendingCourseCount = DB::table('learner_course')
                ->where('learner_course.learner_id', $learner->learner_id)
                ->where('learner_course.status', 'Pending')
                ->count();

                $totalLearnerRejectedCourseCount = DB::table('learner_course')
                ->where('learner_course.learner_id', $learner->learner_id)
                ->where('learner_course.status', 'Rejected')
                ->count();


                $totalCoursesLessonCount = 0;
                $totalCoursesActivityCount = 0;
                $totalCoursesQuizCount = 0;

                $totalCoursesLessonCompletedCount = 0;
                $totalCoursesActivityCompletedCount = 0;
                $totalCoursesQuizCompletedCount = 0;

                foreach ($learnerCourseData as $course) {

                    $totalCoursesLessonCount += DB::table('learner_syllabus_progress')
                    ->where('learner_id', $learner->learner_id)
                    ->where('learner_course_id', $course->learner_course_id)
                    ->where('course_id', $course->course_id)
                    ->where('category', 'LESSON')
                    ->count();

                    $totalCoursesActivityCount += DB::table('learner_syllabus_progress')
                    ->where('learner_id', $learner->learner_id)
                    ->where('learner_course_id', $course->learner_course_id)
                    ->where('course_id', $course->course_id)
                    ->where('category', 'ACTIVITY')
                    ->count();

                    $totalCoursesQuizCount += DB::table('learner_syllabus_progress')
                    ->where('learner_id', $learner->learner_id)
                    ->where('learner_course_id', $course->learner_course_id)
                    ->where('course_id', $course->course_id)
                    ->where('category', 'QUIZ')
                    ->count();


                    $totalCoursesLessonCompletedCount += DB::table('learner_syllabus_progress')
                    ->where('learner_id', $learner->learner_id)
                    ->where('learner_course_id', $course->learner_course_id)
                    ->where('course_id', $course->course_id)
                    ->where('category', 'LESSON')
                    ->where('status', 'COMPLETED')
                    ->count();

                    $totalCoursesActivityCompletedCount += DB::table('learner_syllabus_progress')
                    ->where('learner_id', $learner->learner_id)
                    ->where('learner_course_id', $course->learner_course_id)
                    ->where('course_id', $course->course_id)
                    ->where('category', 'ACTIVITY')
                    ->where('status', 'COMPLETED')
                    ->count();

                    $totalCoursesQuizCompletedCount += DB::table('learner_syllabus_progress')
                    ->where('learner_id', $learner->learner_id)
                    ->where('learner_course_id', $course->learner_course_id)
                    ->where('course_id', $course->course_id)
                    ->where('category', 'QUIZ')
                    ->where('status', 'COMPLETED')
                    ->count();
                }

                $data = [
                    'title' => 'Performance',
                    'learnerCourseData' => $learnerCourseData,
                    'totalLearnerCourseCount' => $totalLearnerCourseCount,
                    // 'totalLearnerCompletedCourseCount' => $totalLearnerCompletedCourseCount,
                    // 'totalLearnerInProgressCourseCount' => $totalLearnerInProgressCourseCount,
                    'totalLearnerApprovedCourseCount' => $totalLearnerApprovedCourseCount,
                    'totalLearnerPendingCourseCount' => $totalLearnerPendingCourseCount,
                    'totalLearnerRejectedCourseCount' => $totalLearnerRejectedCourseCount,
                    'totalCoursesLessonCount' => $totalCoursesLessonCount,
                    'totalCoursesActivityCount' => $totalCoursesActivityCount,
                    'totalCoursesQuizCount' => $totalCoursesQuizCount,
                    'totalCoursesLessonCompletedCount' => $totalCoursesLessonCompletedCount,
                    'totalCoursesActivityCompletedCount' => $totalCoursesActivityCompletedCount,
                    'totalCoursesQuizCompletedCount' => $totalCoursesQuizCompletedCount,
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

    public function enrolledCoursesPerformancesData(Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner');
            
            try{
                $selectedCourse = $request->input('selectedCourse');

                
                $totalLearnerCourseCount = DB::table('learner_course_progress')
                ->where('learner_course_progress.learner_id', $learner->learner_id)
                ->count();

                $totalLearnerCompletedCourseCount = DB::table('learner_course_progress')
                ->where('learner_course_progress.learner_id', $learner->learner_id)
                ->where('learner_course_progress.course_progress', 'COMPLETED')
                ->count();

                $totalLearnerInProgressCourseCount = DB::table('learner_course_progress')
                ->where('learner_course_progress.learner_id', $learner->learner_id)
                ->where('learner_course_progress.course_progress', 'IN PROGRESS')
                ->count();

                

                if($selectedCourse === "ALL") {
                    $courseData = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress.learner_syllabus_progress_id',
                        'learner_syllabus_progress.learner_course_id',
                        'learner_syllabus_progress.syllabus_id',
                        'learner_syllabus_progress.course_id',
                        'learner_syllabus_progress.category',
                        'learner_syllabus_progress.status',
                        
                        'course.course_name',
                        'course.course_code',
                    )
                    ->join('course', 'learner_syllabus_progress.course_id', '=', 'course.course_id')
                    ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                    ->get();

                    $data['courseData'] = $courseData;
                } else {
                    $courseData = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress.learner_syllabus_progress_id',
                        'learner_syllabus_progress.learner_course_id',
                        'learner_syllabus_progress.syllabus_id',
                        'learner_syllabus_progress.course_id',
                        'learner_syllabus_progress.category',
                        'learner_syllabus_progress.status',
                  
                        'course.course_name',
                        'course.course_code',
                        
                    )
                    ->join('course', 'learner_syllabus_progress.course_id', '=', 'course.course_id')
                    ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                    ->where('learner_syllabus_progress.course_id', $selectedCourse)
                    ->get();

                    $courseData_first = $courseData->first();

                    $learnerCourseProgressData = DB::table('learner_course_progress')
                    ->select(
                        'learner_course_progress_id',
                        'learner_course_id',
                        'course_progress',
                        'start_period',
                        'finish_period',
                    )
                    ->where('learner_course_id', $courseData_first->learner_course_id)
                    ->where('learner_id', $learner->learner_id)
                    ->where('course_id', $selectedCourse)
                    ->first();


                    $totalCourseSyllabusCount = DB::table('learner_syllabus_progress')
                    ->where('learner_course_id', $courseData_first->learner_course_id)
                    ->where('learner_id', $learner->learner_id)
                    ->where('course_id', $selectedCourse)
                    ->count();

                    $totalCourseLessonSyllabusCount = DB::table('learner_syllabus_progress')
                    ->where('learner_course_id', $courseData_first->learner_course_id)
                    ->where('learner_id', $learner->learner_id)
                    ->where('course_id', $selectedCourse)
                    ->where('category', 'LESSON')
                    ->count();

                    $totalCourseActivitySyllabusCount = DB::table('learner_syllabus_progress')
                    ->where('learner_course_id', $courseData_first->learner_course_id)
                    ->where('learner_id', $learner->learner_id)
                    ->where('course_id', $selectedCourse)
                    ->where('category', 'ACTIVITY')
                    ->count();

                    $totalCourseQuizSyllabusCount = DB::table('learner_syllabus_progress')
                    ->where('learner_course_id', $courseData_first->learner_course_id)
                    ->where('learner_id', $learner->learner_id)
                    ->where('course_id', $selectedCourse)
                    ->where('category', 'QUIZ')
                    ->count();

                    $totalCourseLessonCompletedSyllabusCount = DB::table('learner_syllabus_progress')
                    ->where('learner_course_id', $courseData_first->learner_course_id)
                    ->where('learner_id', $learner->learner_id)
                    ->where('course_id', $selectedCourse)
                    ->where('category', 'LESSON')
                    ->where('status', 'COMPLETED')
                    ->count();

                    $totalCourseActivityCompletedSyllabusCount = DB::table('learner_syllabus_progress')
                    ->where('learner_course_id', $courseData_first->learner_course_id)
                    ->where('learner_id', $learner->learner_id)
                    ->where('course_id', $selectedCourse)
                    ->where('category', 'ACTIVITY')
                    ->where('status', 'COMPLETED')
                    ->count();

                    $totalCourseQuizCompletedSyllabusCount = DB::table('learner_syllabus_progress')
                    ->where('learner_course_id', $courseData_first->learner_course_id)
                    ->where('learner_id', $learner->learner_id)
                    ->where('course_id', $selectedCourse)
                    ->where('category', 'QUIZ')
                    ->where('status', 'COMPLETED')
                    ->count();


                    $data['courseData'] = $courseData;
                    $data['learnerCourseProgressData'] = $learnerCourseProgressData;
                    $data['totalCourseSyllabusCount'] = $totalCourseSyllabusCount;
                    $data['totalCourseLessonSyllabusCount'] = $totalCourseLessonSyllabusCount;
                    $data['totalCourseActivitySyllabusCount'] = $totalCourseActivitySyllabusCount;
                    $data['totalCourseQuizSyllabusCount'] = $totalCourseQuizSyllabusCount;
                    $data['totalCourseLessonCompletedSyllabusCount'] = $totalCourseLessonCompletedSyllabusCount;
                    $data['totalCourseActivityCompletedSyllabusCount'] = $totalCourseActivityCompletedSyllabusCount;
                    $data['totalCourseQuizCompletedSyllabusCount'] = $totalCourseQuizCompletedSyllabusCount;

                }

                $data['title'] = 'Course Performance';
                $data['totalLearnerCourseCount'] = $totalLearnerCourseCount;
                $data['totalLearnerCompletedCourseCount'] = $totalLearnerCompletedCourseCount;
                $data['totalLearnerInProgressCourseCount'] = $totalLearnerInProgressCourseCount;

                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/learner');
        }
    }


    public function sessionData() {
        if (session()->has('learner')) {
            $learner= session('learner');

            try{
                $totalsPerDay = DB::table('session_logs')
                ->select(DB::raw('DATE(session_in) as date'), DB::raw('SUM(time_difference) as total_seconds'))
                ->where('session_user_id', $learner->learner_id)
                ->where('session_user_type', 'LEARNER')
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


    public function coursePerformance(Course $course) {
        if (session()->has('learner')) {
            $learner= session('learner');


            try {
                $learnerCourseData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_progress_id',
                    'learner_course_progress.learner_course_id',
                    'learner_course_progress.course_id',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',

                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.instructor_id',

                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                )
                ->join('course', 'learner_course_progress.course_id', '=', 'course.course_id')
                ->join('instructor', 'course.instructor_id', '=', 'instructor.instructor_id')
                ->where('learner_course_progress.learner_id', $learner->learner_id)
                ->where('learner_course_progress.course_id', $course->course_id)
                ->first();

                $learnerSyllabusData = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress.learner_syllabus_progress_id',
                    'learner_syllabus_progress.learner_course_id',
                    'learner_syllabus_progress.syllabus_id',
                    'learner_syllabus_progress.category',
                    'learner_syllabus_progress.status',

                    'syllabus.topic_id',
                    'syllabus.topic_title',
                )
                ->join('syllabus', 'learner_syllabus_progress.syllabus_id', '=', 'syllabus.syllabus_id')
                ->where('learner_syllabus_progress.learner_course_id', $learnerCourseData->learner_course_id)
                ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                ->where('learner_syllabus_progress.course_id', $course->course_id)
                ->get();

                $data = [
                    'title' => 'Course Performance',
                    'scripts' => ['learner_course_performance.js'],
                    'learnerCourseData' => $learnerCourseData,
                    'learner' =>$learner,
                    'learnerSyllabusData' => $learnerSyllabusData,
                ];
        
                // dd($data);
                return view('learner_performance.learnerCoursePerformance' , compact('learner'))
                ->with($data);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/learner');
        }
    }

    public function coursePerformanceData(Course $course) {
        if (session()->has('learner')) {
            $learner= session('learner');
            
            try {

                $learnercourse = DB::table('learner_course')
                ->select(
                    'learner_course_id',
                    'learner_id',
                    'course_id',
                )
                ->where('learner_id', $learner->learner_id)
                ->where('course_id', $course->course_id)
                ->first();

                $learnerCourseData = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress_id',
                    'learner_course_id',
                    'syllabus_id',
                    'category',
                    'status',
                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learnercourse->learner_course_id)
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
                ->where('learner_course_id', $learnercourse->learner_course_id)
                ->count();

                $learnerCompletedSyllabusCount = DB::table('learner_syllabus_progress')
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learnercourse->learner_course_id)
                ->where('status', 'COMPLETED')
                ->count();

                $learnerInProgressSyllabusCount = DB::table('learner_syllabus_progress')
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learnercourse->learner_course_id)
                ->where('status', 'IN PROGRESS')
                ->count();

                $learnerLockedSyllabusCount = DB::table('learner_syllabus_progress')
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learnercourse->learner_course_id)
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
                ->where('learner_course_id', $learnercourse->learner_course_id)
                ->where('status', 'COMPLETED')
                ->get();

                $learnerActivityCompletedData = DB::table('learner_activity_progress')
                ->select(
                    'start_period',
                    'finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_activity_progress.finish_period, learner_activity_progress.start_period), "%H:%i:%s") as time_difference')

                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learnercourse->learner_course_id)
                ->where('status', 'COMPLETED')
                ->get();

                $learnerQuizCompletedData = DB::table('learner_quiz_progress')
                ->select(
                    'start_period',
                    'finish_period',
                    DB::raw('TIME_FORMAT(TIMEDIFF(learner_quiz_progress.finish_period, learner_quiz_progress.start_period), "%H:%i:%s") as time_difference')

                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learnercourse->learner_course_id)
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
                'course' => $course,
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

    public function syllabusPerformanceData(Course $course) {
        if (session()->has('learner')) {
            $learner= session('learner');
            
            try {
                $learnercourse = DB::table('learner_course')
                ->select(
                    'learner_course_id',
                    'learner_id',
                    'course_id',
                )
                ->where('learner_id', $learner->learner_id)
                ->where('course_id', $course->course_id)
                ->first();


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
                ->where('learner_lesson_progress.learner_course_id', $learnercourse->learner_course_id)
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
                ->where('learner_lesson_progress.learner_course_id', $learnercourse->learner_course_id)
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
                ->where('learner_activity_progress.learner_course_id', $learnercourse->learner_course_id)
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
                ->where('learner_activity_progress.learner_course_id', $learnercourse->learner_course_id)
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
                ->where('learner_activity_output.learner_course_id', $learnercourse->learner_course_id)
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
                ->where('learner_quiz_progress.learner_course_id', $learnercourse->learner_course_id)
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
                ->where('learner_quiz_progress.learner_course_id', $learnercourse->learner_course_id)
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
