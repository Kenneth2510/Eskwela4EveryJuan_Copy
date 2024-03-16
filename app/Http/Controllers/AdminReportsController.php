<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Learner;
use App\Models\Instructor;
use App\Models\Admin;
use App\Models\Course;
use App\Models\LearnerCourse;
use App\Models\Syllabus;
use App\Models\Lessons;
use App\Models\Activities;
use App\Models\ActivityContents;
use App\Models\ActivityContentCriterias;
use App\Models\Quizzes;
use App\Models\LearnerCourseProgress;
use App\Models\LearnerSyllabusProgress;
use App\Models\LearnerLessonProgress;
use App\Models\LearnerActivityProgress;
use App\Models\LearnerQuizProgress;
use App\Models\LearnerActivityOutput;
use App\Models\LearnerActivityCriteriaScore;
use App\Models\LearnerQuizOutputs;
use App\Models\LearnerPreAssessmentProgress;
use App\Models\LearnerPreAssessmentOutput;
use App\Models\LearnerPostAssessmentProgress;
use App\Models\LearnerPostAssessmentOutput;
use App\Models\Message;
use App\Models\MessageContent;
use App\Models\MessageContentFile;
use App\Models\MessageReply;
use App\Models\MessageReplyContent;
use App\Models\MessageReplyContentFile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Response;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminReportsController extends Controller
{
    public function index() {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {

                    $learners = DB::table('learner')
                    ->select(
                        'learner_id',
                        DB::raw('CONCAT(learner_fname, " ", learner_lname) as name')
                    )
                    ->get();

                    $instructors = DB::table('instructor')
                    ->select(
                        'instructor_id',
                        DB::raw('CONCAT(instructor_fname, " ", instructor_lname) as name')
                    )
                    ->get();

                    $approvedCourses = DB::table('course')
                    ->select(
                        'course_name',
                        'course_id',
                    )
                    ->where('course_status', 'Approved')
                    ->get();

                    $data = [
                        'title' => 'Reports',
                        'scripts' => ['AD_reports.js'],
                        'admin' => $adminSession,
                        'learners' => $learners,
                        'instructors' => $instructors,
                        'approvedCourses' => $approvedCourses,
                    ];


                    return view('adminReports.reports')
                    ->with($data);

                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }  else {
                return redirect('/admin');
            }
    }


    public function Users(Request $request) {

                try {
                    $category = $request->input('category');
                    $userCategory = $request->input('userCategory');
                    $userStatus = $request->input('userStatus');
                    $customTime = $request->has('userSelectedDayCheck');
                    $startDate = $request->input('userDateStart');
                    $finishDate = $request->input('userDateFinish');
                    

                    if ($userCategory === 'Learners') {
                        $learner = DB::table('learner')
                        ->select(
                            'learner.learner_id',
                            'learner.status',
                            DB::raw('CONCAT(learner.learner_fname, " ", learner.learner_lname) as name'),
                            'learner.learner_email',
                            'learner.learner_contactno',
                            'learner.learner_gender',
                            'learner.learner_bday',
                            'learner.created_at',
                            'business.business_name',
                        )
                        ->join('business', 'learner.learner_id', 'business.learner_id');

                        if ($userStatus) {
                            $learner->where('learner.status', $userStatus);
                        }

                        if ($customTime) {
                            $startDate = $startDate ?: date('Y-m-d');
                            $finishDate = $finishDate ?: date('Y-m-d');
                            $learner->whereBetween('learner.created_at', [$startDate, $finishDate]);
                        }


                        $learner = $learner->get();

                        // $data = [
                        //     'learnerData' => $learner,
                        //     'category' => $userCategory,
                        // ];

                        
                        $html = view('adminReports.list_user', [
                            'learnerData' => $learner,
                            'category' => $userCategory,
                            ])->render();
                    } else {
                        $instructor = DB::table('instructor')
                        ->select(
                            'instructor.instructor_id',
                            'instructor.status',
                            DB::raw('CONCAT(instructor.instructor_fname, " ", instructor.instructor_lname) as name'),
                            'instructor.instructor_email',
                            'instructor.instructor_contactno',
                            'instructor.instructor_gender',
                            'instructor.instructor_bday',
                            'instructor.created_at',
                        );

                        if ($userStatus) {
                            $instructor->where('instructor.status', $userStatus);
                        }

                        if ($customTime) {
                            $startDate = $startDate ?: date('Y-m-d');
                            $finishDate = $finishDate ?: date('Y-m-d');
                            $instructor->whereBetween('instructor.created_at', [$startDate, $finishDate]);
                        }


                        $instructorData = $instructor->get();
                        // $data = [
                        //     'instructorData' => $instructorData,
                        //     'category' => $userCategory,
                        // ];

                        $html = view('adminReports.list_user', [
                            'instructorData' => $instructorData,
                            'category' => $userCategory,
                            ])->render();
                    }

                    // dd($data);

                // $pdf = PDF::loadView('adminReports.list_user');

                // return $pdf->download();

                
                // Generate HTML content from the view
                // $html = view('adminReports.list_user', [
                //     'data' => $data
                //     ])->render();


                // Generate PDF from HTML
                $pdf = PDF::loadHTML($html)
                ->setOption('zoom', 1.0); // Set the scale factor to 80%
            
                // Return the PDF content as a download
                $filename = $userCategory . "_list.pdf";
                return $pdf->download($filename);

            } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
            }
    }

    public function Session(Request $request) {
        try {
            $category = $request->input('category');
            $userCategory = $request->input('userCategory');
            $customTime = $request->has('userSelectedDayCheck');
            $startDate = $request->input('userDateStart');
            $finishDate = $request->input('userDateFinish');
            
            $sessionData = DB::table('session_logs')
                ->select(
                    'session_logs.session_log_id',
                    'session_logs.session_user_id',
                    'session_logs.session_user_type',
                    'session_logs.session_in',
                    'session_logs.session_out',
                    'session_logs.time_difference'
                )
                ->leftJoin('learner', function ($join) {
                    $join->on('session_logs.session_user_id', '=', 'learner.learner_id')
                        ->where('session_logs.session_user_type', '=', 'LEARNER');
                })
                ->leftJoin('instructor', function ($join) {
                    $join->on('session_logs.session_user_id', '=', 'instructor.instructor_id')
                        ->where('session_logs.session_user_type', '=', 'INSTRUCTOR');
                })
                ->selectRaw('
                    IF(session_logs.session_user_type = "LEARNER", CONCAT(learner.learner_fname, " ", learner.learner_lname), NULL) AS learner_name,
                    IF(session_logs.session_user_type = "INSTRUCTOR", CONCAT(instructor.instructor_fname, " ", instructor.instructor_lname), NULL) AS instructor_name
                ');
    
            if ($customTime) {
                $startDate = $startDate ?: date('Y-m-d');
                $finishDate = $finishDate ?: date('Y-m-d');
                $sessionData->whereBetween('session_logs.session_in', [$startDate, $finishDate]);
            }
    
            if ($userCategory === 'Learners') {
                $sessionData->where('session_logs.session_user_type', 'LEARNER');
            } else if ($userCategory === 'Instructors') {
                $sessionData->where('session_logs.session_user_type', 'INSTRUCTOR');
            }
    
            $sessionData = $sessionData->get();
    
            $html = view('adminReports.session', [
                'sessionData' => $sessionData,
            ])->render();
    
            $pdf = PDF::loadHTML($html)
                ->setOption('zoom', 1.0); // Set the scale factor to 80%
            
            // Return the PDF content as a download                
            $filename = $userCategory . "_sessionData.pdf";
            return $pdf->download($filename);


        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    

    public function UserSession(Request $request) {

                try {
                    $category = $request->input('category');
                    $userSessionCategory = $request->input('userSessionCategory');
                    $userSession = $request->input('userSession');
                    $customTime = $request->has('userSelectedDayCheck');
                    $startDate = $request->input('userDateStart');
                    $finishDate = $request->input('userDateFinish');

                    $sessionData = DB::table('session_logs')
                ->select(
                    'session_logs.session_log_id',
                    'session_logs.session_user_id',
                    'session_logs.session_user_type',
                    'session_logs.session_in',
                    'session_logs.session_out',
                    'session_logs.time_difference'
                )
                ->leftJoin('learner', function ($join) {
                    $join->on('session_logs.session_user_id', '=', 'learner.learner_id')
                        ->where('session_logs.session_user_type', '=', 'LEARNER');
                })
                ->leftJoin('instructor', function ($join) {
                    $join->on('session_logs.session_user_id', '=', 'instructor.instructor_id')
                        ->where('session_logs.session_user_type', '=', 'INSTRUCTOR');
                })
                ->selectRaw('
                    IF(session_logs.session_user_type = "LEARNER", CONCAT(learner.learner_fname, " ", learner.learner_lname), NULL) AS learner_name,
                    IF(session_logs.session_user_type = "INSTRUCTOR", CONCAT(instructor.instructor_fname, " ", instructor.instructor_lname), NULL) AS instructor_name
                ')
                ->where('session_logs.session_user_id', $userSession);
    
            if ($customTime) {
                $startDate = $startDate ?: date('Y-m-d');
                $finishDate = $finishDate ?: date('Y-m-d');
                $sessionData->whereBetween('session_logs.session_in', [$startDate, $finishDate]);
            }
    
            if ($userSessionCategory === 'Learners') {
                $sessionData->where('session_logs.session_user_type', 'LEARNER');
            } else if ($userSessionCategory === 'Instructors') {
                $sessionData->where('session_logs.session_user_type', 'INSTRUCTOR');
            }
    
            $sessionData = $sessionData->get();
    
            $html = view('adminReports.session', [
                'sessionData' => $sessionData,
            ])->render();
    
            $pdf = PDF::loadHTML($html)
                ->setOption('zoom', 1.0); // Set the scale factor to 80%
            
            // Return the PDF content as a download
            $filename = $userSessionCategory . $userSession . "_sessionData.pdf";
            return $pdf->download($filename);

                } catch (\Exception $e) {
                    dd($e->getMessage());
                }

    }

    public function Courses(Request $request) {

                try {
                    $category = $request->input('category');
                    $courseCategory = $request->input('courseCategory');
                    $courseStatus = $request->input('courseStatus');

                    $courseData = DB::table('course')
                    ->select(
                        'course.course_id',
                        'course.course_name',
                        'course.instructor_id',
                        'course.created_at',
                        'course.course_status',
                        'course.course_description',

                        DB::raw('CONCAT(instructor.instructor_fname, " ", instructor.instructor_lname) as name'),
                    )
                    ->join('instructor', 'course.instructor_id', 'instructor.instructor_id');
                    
                    if($courseStatus) {
                        $courseData = $courseData->where('course.course_status', $courseStatus);
                    }

                    $courseData = $courseData->get();

                foreach($courseData as $course) {
                    $course->syllabusData = DB::table('syllabus')
                    ->select(
                        'syllabus_id',
                        'topic_title',
                        'category',
                        )
                    ->where('course_id', $course->course_id)
                    ->groupBy('syllabus_id', 'topic_title', 'category')
                    ->get();

                    $course->learnerCourseData = DB::table('learner_course')
                        ->where('course_id', $course->course_id)
                        ->count();
                }

                $html = view('adminReports.courses', [
                    'courseData' => $courseData,
                    'courseCategory' => $courseCategory,
                ])->render();
        
                $pdf = PDF::loadHTML($html)
                    ->setOption('zoom', 1.0); // Set the scale factor to 80%
                
                // Return the PDF content as a download
            $filename = $courseCategory . "_coursesData.pdf";
            return $pdf->download($filename);

                } catch (\Exception $e) {
                    dd($e->getMessage());
                }

    }

    public function Enrollees(Request $request) {

                try {
                    $category = $request->input('category');
                    $course = $request->input('course');
                    $enrollmentStatus = $request->input('enrollmentStatus');
                    $customTime = $request->has('userSelectedDayCheck');
                    $startDate = $request->input('userDateStart');
                    $finishDate = $request->input('userDateFinish');

                    $courseName = DB::table('course')
                    ->select(
                        'course_name'
                    )
                    ->where('course_id', $course)
                    ->first();

                    $learnerCourseData = DB::table('learner_course')
                    ->select(
                        DB::raw('CONCAT(learner.learner_fname, " ", learner.learner_lname) as name'),
                        'learner_course.status',
                        'learner_course.updated_at',

                        'learner_course_progress.course_progress'
                    )
                    ->join('learner', 'learner_course.learner_id', 'learner.learner_id')
                    ->join('learner_course_progress', 'learner_course.learner_course_id', 'learner_course_progress.learner_course_id')
                    ->where('learner_course.course_id', $course);

                    if($enrollmentStatus) {
                        $learnerCourseData = $learnerCourseData->where('learner_course.status', $enrollmentStatus);
                    }


                    if ($customTime) {
                        $startDate = $startDate ?: date('Y-m-d');
                        $finishDate = $finishDate ?: date('Y-m-d');
                        $learnerCourseData->whereBetween('learner_course.updated_at', [$startDate, $finishDate]);
                    }

                    $learnerCourseData = $learnerCourseData->get();

                    $html = view('adminReports.enrollees', [
                        'learnerCourseData' => $learnerCourseData,
                        'courseName' => $courseName,
                    ])->render();
            
                    $pdf = PDF::loadHTML($html)
                        ->setOption('zoom', 1.0); // Set the scale factor to 80%
                    
                    // Return the PDF content as a download          
                    $filename = $courseName . "_enrolleesData.pdf";
                    return $pdf->download($filename);


                } catch (\Exception $e) {
                    dd($e->getMessage());
                }

    }

    public function CourseGradesheets(Request $request) {

                try {
                    $category = $request->input('category');
                    $course = $request->input('course');

                    $courseData = DB::table('course')
                    ->select(
                        'course_id',
                        'course_name',
                    )
                    ->where('course_id', $course)
                    ->first();

                    $syllabus = DB::table('syllabus')
                    ->select(
                        'syllabus.syllabus_id',
                        'syllabus.course_id',
                        'syllabus.topic_id',
                        'syllabus.topic_title',
                        'syllabus.category',

                    )
                    ->join('course', 'course.course_id', 'syllabus.course_id')
                    ->where('syllabus.course_id', $course)
                    ->orderBy('syllabus.topic_id')
                    ->get();


                    $gradeData = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
                    'learner_course.learner_id',
                    'learner_course.created_at',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',
                    'learner_course_progress.grade',
                    'learner_course_progress.remarks',
                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('learner_course_progress', 'learner_course_progress.learner_course_id', '=', 'learner_course.learner_course_id')
                ->join('learner', 'learner.learner_id', '=', 'learner_course.learner_id')
                ->where('learner_course.course_id', $course);

            $gradeWithActivityData = $gradeData->get();

            foreach ($gradeWithActivityData as $key => $activityData) {
                $activityData->activities = DB::table('learner_activity_output')
                    ->select(
                        'learner_activity_output.activity_id',
                        'learner_activity_output.activity_content_id',
                        'activities.activity_title',
                        DB::raw('COALESCE(ROUND(AVG(IFNULL(attempts.total_score, 0)), 2), 0) as average_score')
                    )
                    ->leftJoin('activities', 'activities.activity_id', '=', 'learner_activity_output.activity_id')
                    ->leftJoin(
                        DB::raw('(SELECT learner_activity_output_id, AVG(total_score) as total_score FROM learner_activity_output GROUP BY learner_activity_output_id) as attempts'),
                        'attempts.learner_activity_output_id',
                        '=',
                        'learner_activity_output.learner_activity_output_id'
                    )
                    ->where('learner_activity_output.course_id', $course)
                    ->where('learner_activity_output.learner_course_id', $activityData->learner_course_id)
                    ->groupBy('learner_activity_output.activity_id', 'learner_activity_output.activity_content_id', 'activities.activity_title')
                    ->get();

                // Retrieve quiz data for the current learner
                $activityData->quizzes = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.quiz_id',
                    'quizzes.quiz_title',
                    DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_quiz_progress.score, 0)), 2), 0) as average_score')
                )
                ->leftJoin('quizzes', 'quizzes.quiz_id', '=', 'learner_quiz_progress.quiz_id')
                ->where('learner_quiz_progress.course_id', $course)
                ->where('learner_quiz_progress.learner_course_id', $activityData->learner_course_id)
                ->groupBy('learner_quiz_progress.quiz_id', 'quizzes.quiz_title')
                ->get();


                $activityData->pre_assessment = DB::table('learner_pre_assessment_progress')
                ->select(
                    'score'
                )
                ->where('course_id', $course)
                ->where('learner_course_id', $activityData->learner_course_id)
                ->first();

                $activityData->post_assessment = DB::table('learner_post_assessment_progress')
                ->select (
                        DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                    )
                    ->where('course_id', $course)
                    ->where('learner_course_id', $activityData->learner_course_id)
                    ->first();

                // Add the updated $activityData back to the main array
                $gradeWithActivityData[$key] = $activityData;
            }

            $activitySyllabusData = DB::table('activities')
            ->select(
                'activities.activity_id',
                'activities.course_id',
                'activities.syllabus_id',
                'activities.topic_id',
                'activities.activity_title',
                'activity_content.total_score',
            )
            ->join('activity_content', 'activities.activity_id', 'activity_content.activity_id')
            ->where('activities.course_id', $course)
            ->orderBy('activities.topic_id',  'asc')
            ->get();

            $quizSyllabusData = DB::table('quizzes')
            ->select(
                'quizzes.quiz_id',
                'quizzes.course_id',
                'quizzes.syllabus_id',
                'quizzes.topic_id',
                'quizzes.quiz_title',
                DB::raw('COUNT(quiz_content.question_id) AS total_score')
            )
            ->join('quiz_content', 'quizzes.quiz_id', 'quiz_content.quiz_id')
            ->where('quizzes.course_id', $course)
            ->groupBy('quizzes.quiz_id')
            ->orderBy('quizzes.topic_id', 'asc')
            ->get();

            $learnerPreAssessmentData = DB::table('learner_pre_assessment_progress')
            ->select(
                'learner_pre_assessment_progress.learner_pre_assessment_progress_id',
                'learner_pre_assessment_progress.course_id',
                'learner_pre_assessment_progress.learner_id',
                'learner_pre_assessment_progress.learner_course_id',
                'learner_pre_assessment_progress.status',
                'learner_pre_assessment_progress.start_period',
                'learner_pre_assessment_progress.finish_period',
                'learner_pre_assessment_progress.score',
                'learner_pre_assessment_progress.remarks',

                'learner.learner_fname',
                'learner.learner_lname',
            )
            ->join('learner', 'learner.learner_id', 'learner_pre_assessment_progress.learner_id')
            ->where('learner_pre_assessment_progress.course_id', $course)
            ->get();
        
        $learnerPostAssessmentData = DB::table('learner_post_assessment_progress')
            ->select(
                'learner_post_assessment_progress.learner_post_assessment_progress_id',
                'learner_post_assessment_progress.course_id',
                'learner_post_assessment_progress.learner_course_id',
                'learner_post_assessment_progress.status',
                'learner_post_assessment_progress.start_period',
                'learner_post_assessment_progress.finish_period',
                'learner_post_assessment_progress.score',
                'learner_post_assessment_progress.remarks',
                'learner_post_assessment_progress.attempt',
                'learner.learner_fname',
                'learner.learner_lname',
            )
            ->join('learner', 'learner.learner_id', 'learner_post_assessment_progress.learner_id')
            ->where('learner_post_assessment_progress.course_id', $course)
            ->get();
    

            $data = [
                'gradeWithActivityData' => $gradeWithActivityData,
                'syllabus' => $syllabus,
                'activitySyllabusData' => $activitySyllabusData,
                'quizSyllabusData' => $quizSyllabusData,
                'learnerPreAssessmentData' => $learnerPreAssessmentData,
                'learnerPostAssessmentData' => $learnerPostAssessmentData,
            ];

            // dd($data);

            $html = view('adminReports.courseGradesheet', $data)->render();
    
            $pdf = PDF::loadHTML($html)
                ->setOption('zoom', 1.0); // Set the scale factor to 80%
            
            // Return the PDF content as a download    
                    $filename = $courseData->course_name . "_gradesheet.pdf";
                    return $pdf->download($filename);

                    
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }

    }

    public function LearnerGradesheets(Request $request) {

                try {
                    $category = $request->input('category');
                    $course = $request->input('learnerCourseCategory');
                    $learnerCourse = $request->input('learnerCourseUser');
                    
                    $syllabus = DB::table('syllabus')
                    ->select(
                        'syllabus.syllabus_id',
                        'syllabus.course_id',
                        'syllabus.topic_id',
                        'syllabus.topic_title',
                        'syllabus.category',

                        'course.course_name',
                    )
                    ->join('course', 'course.course_id', 'syllabus.course_id')
                    ->where('syllabus.course_id', $course)
                    ->orderBy('syllabus.topic_id')
                    ->get();

                    $gradeData = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
                    'learner_course.learner_id',
                    'learner_course.created_at',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',
                    'learner_course_progress.grade',
                    'learner_course_progress.remarks',
                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('learner_course_progress', 'learner_course_progress.learner_course_id', '=', 'learner_course.learner_course_id')
                ->join('learner', 'learner.learner_id', '=', 'learner_course.learner_id')
                ->where('learner_course.course_id', $course)
                ->where('learner_course.learner_course_id', $learnerCourse);

            $gradeWithActivityData = $gradeData->first();
            // foreach ($gradeWithActivityData as $key => $activityData) {
                $gradeWithActivityData->activities = DB::table('learner_activity_output')
                    ->select(
                        'learner_activity_output.activity_id',
                        'learner_activity_output.activity_content_id',
                        'activities.activity_title',
                        DB::raw('COALESCE(ROUND(AVG(IFNULL(attempts.total_score, 0)), 2), 0) as average_score')
                    )
                    ->leftJoin('activities', 'activities.activity_id', '=', 'learner_activity_output.activity_id')
                    ->leftJoin(
                        DB::raw('(SELECT learner_activity_output_id, AVG(total_score) as total_score FROM learner_activity_output GROUP BY learner_activity_output_id) as attempts'),
                        'attempts.learner_activity_output_id',
                        '=',
                        'learner_activity_output.learner_activity_output_id'
                    )
                    ->where('learner_activity_output.course_id', $course)
                    ->where('learner_activity_output.learner_course_id', $gradeWithActivityData->learner_course_id)
                    ->groupBy('learner_activity_output.activity_id', 'learner_activity_output.activity_content_id', 'activities.activity_title')
                    ->get();

                // Retrieve quiz data for the current learner
                $gradeWithActivityData->quizzes = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.quiz_id',
                    'quizzes.quiz_title',
                    DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_quiz_progress.score, 0)), 2), 0) as average_score')
                )
                ->leftJoin('quizzes', 'quizzes.quiz_id', '=', 'learner_quiz_progress.quiz_id')
                ->where('learner_quiz_progress.course_id', $course)
                ->where('learner_quiz_progress.learner_course_id', $gradeWithActivityData->learner_course_id)
                ->groupBy('learner_quiz_progress.quiz_id', 'quizzes.quiz_title')
                ->get();


                $gradeWithActivityData->pre_assessment = DB::table('learner_pre_assessment_progress')
                ->select(
                    'score'
                )
                ->where('course_id', $course)
                ->where('learner_course_id', $gradeWithActivityData->learner_course_id)
                ->first();

                $gradeWithActivityData->post_assessment = DB::table('learner_post_assessment_progress')
                ->select (
                        DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                    )
                    ->where('course_id', $course)
                    ->where('learner_course_id', $gradeWithActivityData->learner_course_id)
                    ->first();

                // Add the updated $activityData back to the main array
                // $gradeWithActivityData[$key] = $activityData;
            // }

            $activitySyllabusData = DB::table('activities')
            ->select(
                'activities.activity_id',
                'activities.course_id',
                'activities.syllabus_id',
                'activities.topic_id',
                'activities.activity_title',
                'activity_content.total_score',
            )
            ->join('activity_content', 'activities.activity_id', 'activity_content.activity_id')
            ->where('activities.course_id', $course)
            ->orderBy('activities.topic_id',  'asc')
            ->get();

            $quizSyllabusData = DB::table('quizzes')
            ->select(
                'quizzes.quiz_id',
                'quizzes.course_id',
                'quizzes.syllabus_id',
                'quizzes.topic_id',
                'quizzes.quiz_title',
                DB::raw('COUNT(quiz_content.question_id) AS total_score')
            )
            ->join('quiz_content', 'quizzes.quiz_id', 'quiz_content.quiz_id')
            ->where('quizzes.course_id', $course)
            ->groupBy('quizzes.quiz_id')
            ->orderBy('quizzes.topic_id', 'asc')
            ->get();

            $learnerPreAssessmentData = DB::table('learner_pre_assessment_progress')
            ->select(
                'learner_pre_assessment_progress.learner_pre_assessment_progress_id',
                'learner_pre_assessment_progress.course_id',
                'learner_pre_assessment_progress.learner_id',
                'learner_pre_assessment_progress.learner_course_id',
                'learner_pre_assessment_progress.status',
                'learner_pre_assessment_progress.start_period',
                'learner_pre_assessment_progress.finish_period',
                'learner_pre_assessment_progress.score',
                'learner_pre_assessment_progress.remarks',

                'learner.learner_fname',
                'learner.learner_lname',
            )
            ->join('learner', 'learner.learner_id', 'learner_pre_assessment_progress.learner_id')
            ->where('learner_pre_assessment_progress.course_id', $course)
            ->where('learner_pre_assessment_progress.learner_id', $gradeWithActivityData->learner_id)
            ->get();
        
        $learnerPostAssessmentData = DB::table('learner_post_assessment_progress')
            ->select(
                'learner_post_assessment_progress.learner_post_assessment_progress_id',
                'learner_post_assessment_progress.course_id',
                'learner_post_assessment_progress.learner_course_id',
                'learner_post_assessment_progress.status',
                'learner_post_assessment_progress.start_period',
                'learner_post_assessment_progress.finish_period',
                'learner_post_assessment_progress.score',
                'learner_post_assessment_progress.remarks',
                'learner_post_assessment_progress.attempt',
                'learner.learner_fname',
                'learner.learner_lname',
            )
            ->join('learner', 'learner.learner_id', 'learner_post_assessment_progress.learner_id')
            ->where('learner_post_assessment_progress.course_id', $course)
            ->where('learner_post_assessment_progress.learner_id', $gradeWithActivityData->learner_id)
            ->get();
    

            $data = [
                'gradeWithActivityData' => $gradeWithActivityData,
                'syllabus' => $syllabus,
                'activitySyllabusData' => $activitySyllabusData,
                'quizSyllabusData' => $quizSyllabusData,
                'learnerPreAssessmentData' => $learnerPreAssessmentData,
                'learnerPostAssessmentData' => $learnerPostAssessmentData,
            ];

            // dd($data);

            $html = view('adminReports.learnerGradesheet', $data)->render();

            $pdf = PDF::loadHTML($html)
                ->setOption('zoom', 1.0); // Set the scale factor to 80%
            
            // Return the PDF content as a download
                    $filename = $gradeWithActivityData->learner_fname . "_" . $gradeWithActivityData->learner_lname + "_" +$syllabus->course_name + "_gradesheet.pdf";
                    return $pdf->download($filename);

                    


                } catch (\Exception $e) {
                    dd($e->getMessage());
                }

    }


    public function learnerCourseData(Course $course) {

        try {
            $learnerCourseData = DB::table('learner_course')
            ->select(
                'learner_course.learner_course_id',
                DB::raw('CONCAT(learner.learner_fname, " ", learner.learner_lname) as name'),
            )
            ->join('learner', 'learner.learner_id', 'learner_course.learner_id')
            ->where('learner_course.course_id' , $course->course_id)
            ->get();

            $data = [
                'learnerCourseData' => $learnerCourseData,
            ];

            return response()->json($data);
                    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
            }
    }
}
