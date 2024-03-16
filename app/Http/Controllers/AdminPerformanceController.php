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
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Codedge\Fpdf\Fpdf\Fpdf;


class AdminPerformanceController extends Controller
{
    public function index() {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {

                    $data = [
                        'title' => 'Performance',
                        'scripts' => ['AD_performance.js'],
                        'admin' => $adminSession,
                    ];


                    return view('adminPerformance.performance')
                    ->with($data);

                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }  else {
                return redirect('/admin');
            }
    }

    public function learner_overview() {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {

                    $totalLearnerCount = DB::table('learner')
                    ->count();

                    $learnersPerDay = DB::table('learner')
                    ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as count'))
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();

                // Format the date in words
                $learnersPerDay->transform(function ($item) {
                    $item->day = Carbon::parse($item->day)->format('F j, Y');
                    return $item;
                });

                $learnerStatusCount = DB::table('learner')
                ->select(
                    'status'
                )
                ->get();


                $learnersPerWeek = DB::table('learner')
                ->select(DB::raw('CONCAT("Week ", WEEK(created_at), " of ", YEAR(created_at)) as week'), DB::raw('COUNT(*) as count'))
                ->groupBy('week')
                ->orderBy('week')
                ->get();

                $learnersPerMonth = DB::table('learner')
                ->select(DB::raw('DATE_FORMAT(created_at, "%M %Y") as month'), DB::raw('COUNT(*) as count'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();


                // $hourlyCounts = DB::table('session_logs')
                // ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y %l:00 %p') AS hour_start"), DB::raw('COUNT(*) as session_count'))
                // ->where('session_user_type', 'LEARNER')
                // ->groupBy(DB::raw("DATE(session_in)"), DB::raw("HOUR(session_in)"))
                // ->orderByRaw("DATE(session_in) DESC, HOUR(session_in) DESC")
                // ->get();
            
   $hourlyCounts = DB::table('session_logs')
                ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y %l:00 %p') AS hour_start"), DB::raw('COUNT(*) as session_count'))
                ->where('session_user_type', 'LEARNER')
                ->groupBy('hour_start')
                ->orderBy('hour_start' , 'DESC')
                ->get();

                $dailyCounts = DB::table('session_logs')
                ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y') AS day_start"), DB::raw('COUNT(*) as session_count'))
                ->where('session_user_type', 'LEARNER')
                ->groupBy('day_start')
                ->orderBy('day_start' ,  'DESC')
                ->get();


                $weeklyCounts = DB::table('session_logs')
                ->select(DB::raw("CONCAT('Week ', WEEK(session_in), ', ', YEAR(session_in)) AS week_start"), DB::raw('COUNT(*) as session_count'))
                ->where('session_user_type', 'LEARNER')
                ->groupBy('week_start')
                ->orderBy('week_start'  , 'DESC')
                ->get();

                $monthlyCounts = DB::table('session_logs')
                ->select(DB::raw("DATE_FORMAT(session_in, '%M %Y') AS month_start"), DB::raw('COUNT(*) as session_count'))
                ->where('session_user_type', 'LEARNER')
                ->groupBy('month_start')
                ->orderBy('month_start'  , 'DESC')
                ->get();

                    $data = [
                        'title' => 'Performance',
                        'scripts' => ['AD_performance.js'],
                        'admin' => $adminSession,
                        'totalLearnerCount' => $totalLearnerCount,
                        'learnerStatusData' => $learnerStatusCount,
                        'learnersPerDay' => $learnersPerDay,
                        'learnersPerWeek' => $learnersPerWeek,
                        'learnersPerMonth' => $learnersPerMonth,
                        'hourlyCounts' => $hourlyCounts,
                        'dailyCounts' => $dailyCounts,
                        'weeklyCounts' => $weeklyCounts,
                        'monthlyCounts' => $monthlyCounts,
                    ];


                    // return view('adminPerformance.performance')
                    // ->with($data);

                    return response()->json($data);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }
            }  else {
                return redirect('/admin');
            }
    }


    public function instructor_overview() {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {

                    $totalInstructorCount = DB::table('instructor')
                    ->count();

                    $instructorsPerDay = DB::table('instructor')
                    ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as count'))
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();

                // Format the date in words
                $instructorsPerDay->transform(function ($item) {
                    $item->day = Carbon::parse($item->day)->format('F j, Y');
                    return $item;
                });

                $instructorStatusCount = DB::table('instructor')
                ->select(
                    'status'
                )
                ->get();


                $instructorsPerWeek = DB::table('instructor')
                ->select(DB::raw('CONCAT("Week ", WEEK(created_at), " of ", YEAR(created_at)) as week'), DB::raw('COUNT(*) as count'))
                ->groupBy('week')
                ->orderBy('week')
                ->get();

                $instructorsPerMonth = DB::table('instructor')
                ->select(DB::raw('DATE_FORMAT(created_at, "%M %Y") as month'), DB::raw('COUNT(*) as count'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();


                // $hourlyCounts = DB::table('session_logs')
                // ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y %l:00 %p') AS hour_start"), DB::raw('COUNT(*) as session_count'))
                // ->where('session_user_type', 'LEARNER')
                // ->groupBy(DB::raw("DATE(session_in)"), DB::raw("HOUR(session_in)"))
                // ->orderByRaw("DATE(session_in) DESC, HOUR(session_in) DESC")
                // ->get();
            
   $hourlyCounts = DB::table('session_logs')
                ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y %l:00 %p') AS hour_start"), DB::raw('COUNT(*) as session_count'))
                ->where('session_user_type', 'INSTRUCTOR')
                ->groupBy('hour_start')
                ->orderBy('hour_start' , 'DESC')
                ->get();

                $dailyCounts = DB::table('session_logs')
                ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y') AS day_start"), DB::raw('COUNT(*) as session_count'))
                ->where('session_user_type', 'INSTRUCTOR')
                ->groupBy('day_start')
                ->orderBy('day_start' ,  'DESC')
                ->get();


                $weeklyCounts = DB::table('session_logs')
                ->select(DB::raw("CONCAT('Week ', WEEK(session_in), ', ', YEAR(session_in)) AS week_start"), DB::raw('COUNT(*) as session_count'))
                ->where('session_user_type', 'INSTRUCTOR')
                ->groupBy('week_start')
                ->orderBy('week_start'  , 'DESC')
                ->get();

                $monthlyCounts = DB::table('session_logs')
                ->select(DB::raw("DATE_FORMAT(session_in, '%M %Y') AS month_start"), DB::raw('COUNT(*) as session_count'))
                ->where('session_user_type', 'INSTRUCTOR')
                ->groupBy('month_start')
                ->orderBy('month_start'  , 'DESC')
                ->get();

                    $data = [
                        'title' => 'Performance',
                        'scripts' => ['AD_performance.js'],
                        'admin' => $adminSession,
                        'totalInstructorCount' => $totalInstructorCount,
                        'instructorStatusData' => $instructorStatusCount,
                        'instructorsPerDay' => $instructorsPerDay,
                        'instructorsPerWeek' => $instructorsPerWeek,
                        'instructorsPerMonth' => $instructorsPerMonth,
                        'hourlyCounts' => $hourlyCounts,
                        'dailyCounts' => $dailyCounts,
                        'weeklyCounts' => $weeklyCounts,
                        'monthlyCounts' => $monthlyCounts,
                    ];


                    // return view('adminPerformance.performance')
                    // ->with($data);

                    return response()->json($data);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }
            }  else {
                return redirect('/admin');
            }
    }



    public function course_overview(){
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {

                    $totalCourseCount = DB::table('course')
                    ->count();

                    $coursePerDay = DB::table('course')
                    ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as count'))
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();

                // Format the date in words
                $coursePerDay->transform(function ($item) {
                    $item->day = Carbon::parse($item->day)->format('F j, Y');
                    return $item;
                });

                $courseStatusCount = DB::table('course')
                ->select(
                    'course_status'
                )
                ->get();


                $coursePerWeek = DB::table('course')
                ->select(DB::raw('CONCAT("Week ", WEEK(created_at), " of ", YEAR(created_at)) as week'), DB::raw('COUNT(*) as count'))
                ->groupBy('week')
                ->orderBy('week')
                ->get();

                $coursePerMonth = DB::table('course')
                ->select(DB::raw('DATE_FORMAT(created_at, "%M %Y") as month'), DB::raw('COUNT(*) as count'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();

                $learnerCourseCount = DB::table('learner_course')
                ->select('course.course_name', DB::raw('COUNT(*) as count'))
                ->join('course', 'course.course_id', 'learner_course.course_id')
                ->groupBy('course.course_name')
                ->get();

                // $hourlyCounts = DB::table('session_logs')
                // ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y %l:00 %p') AS hour_start"), DB::raw('COUNT(*) as session_count'))
                // ->where('session_user_type', 'LEARNER')
                // ->groupBy(DB::raw("DATE(session_in)"), DB::raw("HOUR(session_in)"))
                // ->orderByRaw("DATE(session_in) DESC, HOUR(session_in) DESC")
                // ->get();
            
//    $hourlyCounts = DB::table('session_logs')
//                 ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y %l:00 %p') AS hour_start"), DB::raw('COUNT(*) as session_count'))
//                 ->where('session_user_type', 'INSTRUCTOR')
//                 ->groupBy('hour_start')
//                 ->orderBy('hour_start' , 'DESC')
//                 ->get();

//                 $dailyCounts = DB::table('session_logs')
//                 ->select(DB::raw("DATE_FORMAT(session_in, '%W, %M %e, %Y') AS day_start"), DB::raw('COUNT(*) as session_count'))
//                 ->where('session_user_type', 'INSTRUCTOR')
//                 ->groupBy('day_start')
//                 ->orderBy('day_start' ,  'DESC')
//                 ->get();


//                 $weeklyCounts = DB::table('session_logs')
//                 ->select(DB::raw("CONCAT('Week ', WEEK(session_in), ', ', YEAR(session_in)) AS week_start"), DB::raw('COUNT(*) as session_count'))
//                 ->where('session_user_type', 'INSTRUCTOR')
//                 ->groupBy('week_start')
//                 ->orderBy('week_start'  , 'DESC')
//                 ->get();

//                 $monthlyCounts = DB::table('session_logs')
//                 ->select(DB::raw("DATE_FORMAT(session_in, '%M %Y') AS month_start"), DB::raw('COUNT(*) as session_count'))
//                 ->where('session_user_type', 'INSTRUCTOR')
//                 ->groupBy('month_start')
//                 ->orderBy('month_start'  , 'DESC')
//                 ->get();

                    $data = [
                        'title' => 'Performance',
                        'scripts' => ['AD_performance.js'],
                        'admin' => $adminSession,
                        'totalCourseCount' => $totalCourseCount,
                        'courseStatusData' => $courseStatusCount,
                        'coursePerDay' => $coursePerDay,
                        'coursePerWeek' => $coursePerWeek,
                        'coursePerMonth' => $coursePerMonth,
                        'learnerCourseCount' => $learnerCourseCount,
                        // 'hourlyCounts' => $hourlyCounts,
                        // 'dailyCounts' => $dailyCounts,
                        // 'weeklyCounts' => $weeklyCounts,
                        // 'monthlyCounts' => $monthlyCounts,
                    ];


                    // return view('adminPerformance.performance')
                    // ->with($data);

                    return response()->json($data);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }
            }  else {
                return redirect('/admin');
            }        
    }


    public function learners() {
        return $this->search_learner();
    }
    
    public function search_learner() {
        
        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);
            $admin_codename = $admin['admin_codename'];
        } else {
            return redirect('/admin');
        }

        $search_by = request('searchBy');
        $search_val = request('searchVal');

        $filter_date = request('filterDate');
        $filter_status = request('filterStatus');



        try {
            $query = DB::table('learner')
                ->select(
                    'learner.learner_id',
                    'learner.learner_fname',
                    'learner.learner_lname',
                    'learner.learner_contactno',
                    'learner.learner_email',
                    'learner.created_at',
                    'business.business_name',
                    'learner.status'
                )
                ->join('business', 'business.learner_id', '=', 'learner.learner_id')
                ->orderBy('learner.created_at', 'DESC');
    
            if(!empty($filter_date) || !empty($filter_status)) {
                if(!empty($filter_date) && empty($filter_status)){
                    $query->where('learner.created_at', 'LIKE', $filter_date.'%');
                } elseif(empty($filter_date) && !empty($filter_status)){
                    $query->where('learner.status', 'LIKE', $filter_status);
                } else {
                    $query->where(function ($query) use ($filter_date, $filter_status) {
                        $query->where('learner.created_at', 'LIKE', $filter_date.'%')
                            ->where('learner.status', 'LIKE', $filter_status);
                    });
                }
            }

            if (!empty($search_by) && !empty($search_val)) {
                if ($search_by == 'name') {
                    $query->where(function ($query) use ($search_val) {
                        $query->where('learner.learner_fname', 'LIKE', $search_val . '%')
                            ->orWhere('learner.learner_lname', 'LIKE', $search_val . '%');
                    });
                } elseif ($search_by == 'learner_id') {
                    $query->where('learner.learner_id', 'LIKE', $search_val . '%');
                } else {
                    $query->where($search_by, 'LIKE', $search_val . '%');    
                }
            }

 
    
            $learners = $query->paginate(10);
    
            return view('adminPerformance.learners', compact('learners'))
            ->with(['title' => 'Performance', 
                'adminCodeName' => $admin_codename,
                'admin' => $admin,
                'scripts' => []]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    public function view_learner(Learner $learner) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {


                    try {
                        $learnerData = DB::table('learner')
                        ->select(
                            DB::raw('CONCAT(learner_fname, " ", learner_lname) as name'),
                        )
                        ->where('learner_id', $learner->learner_id)
                        ->first();

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
                            'title' => 'Performance',
                            'scripts' => ['AD_learnerPerformance.js'],
                            'courseData' => $learnerCourseData,
                            'admin' => $adminSession,
                            'learner' => $learnerData,
                        ];
                
                        // dd($data);
                        return view('adminPerformance.learnerPerformance')
                        ->with($data);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                    }

                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }  else {
                return redirect('/admin');
            }   
    }




    public function enrolledCoursesPerformances(Learner $learner) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {
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


        }  else {
            return redirect('/admin');
        }  
    }

    public function enrolledCoursesPerformancesData(Learner $learner, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {

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
            return redirect('/admin');
        }
    }


    public function sessionData(Learner $learner) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {

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
            return redirect('/admin');
        }
    }


    public function grades($course, $learner_course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            try {

                $courseData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_progress_id',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.course_id',
                    'learner_course_progress.grade',
                    'learner_course_progress.remarks',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',
                    
                    'course.course_name',
                    'course.course_code',
                )
                ->join('course', 'course.course_id', '=', 'learner_course_progress.course_id')
                ->where('learner_course_progress.course_id', $course)
                ->where('learner_course_progress.learner_course_id', $learner_course)
                ->first();

                $learnerLessonsData = DB::table('learner_lesson_progress')
                ->select(
                    'learner_lesson_progress.learner_lesson_progress_id',
                    'learner_lesson_progress.lesson_id',
                    'learner_lesson_progress.start_period',
                    'learner_lesson_progress.finish_period',

                    'lessons.lesson_title',
                )
                ->join('lessons', 'lessons.lesson_id', '=', 'learner_lesson_progress.lesson_id')
                ->where('learner_lesson_progress.learner_course_id', $learner_course)
                ->where('learner_lesson_progress.course_id', $course)
                ->get();

                $learnerActivityScoresData = DB::table('learner_activity_output')
                ->select(
                    'learner_activity_output.activity_id',
                    'learner_activity_output.activity_content_id',
                    'activities.activity_title',
                    DB::raw('COALESCE(ROUND(AVG(attempts.total_score), 2), 0) as average_score')
                )
                ->leftJoin('activities', 'activities.activity_id', '=', 'learner_activity_output.activity_id')
                ->leftJoin(
                    DB::raw('(SELECT learner_activity_output_id, AVG(total_score) as total_score FROM learner_activity_output GROUP BY learner_activity_output_id) as attempts'),
                    'attempts.learner_activity_output_id',
                    '=',
                    'learner_activity_output.learner_activity_output_id'
                )
                ->where('learner_activity_output.course_id', $course)
                ->where('learner_activity_output.learner_course_id', $learner_course)
                ->groupBy('learner_activity_output.activity_id', 'learner_activity_output.activity_content_id', 'activities.activity_title')
                ->get();
            
                $activityLearnerSumScore = 0;
                $activityTotalSum = 0;
    
                $activitiesTotalScore = DB::table('activities')
                ->select(
                    'activities.activity_id',
                    'activities.syllabus_id',
                    'activity_content.total_score',
                ) 
                ->join('activity_content', 'activities.activity_id', '=', 'activity_content.activity_id')
                ->where('activities.course_id', $course)
                ->get();
    
                foreach ($activitiesTotalScore as $activityMain) {
                    $activityTotalSum += $activityMain->total_score;
                }
    
                foreach ($learnerActivityScoresData as $activity) {
                    $activityLearnerSumScore += $activity->average_score;
                }
    
                $learnerQuizScoresData = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.quiz_id',
                    'quizzes.quiz_title',
                    DB::raw('COALESCE(ROUND(AVG(learner_quiz_progress.score), 2), 0) as average_score')
                )
                ->leftJoin('quizzes', 'quizzes.quiz_id', '=', 'learner_quiz_progress.quiz_id')
                ->where('learner_quiz_progress.course_id', $course)
                ->where('learner_quiz_progress.learner_course_id', $learner_course)
                ->groupBy('learner_quiz_progress.quiz_id', 'quizzes.quiz_title')
                ->get();
            
    
                    $quizTotalScore = DB::table('quiz_content')
                    ->where('quiz_content.course_id', $course)
                    ->count();
        
    
                    $quizLearnerSumScore = 0;
                    $quizTotalSum = $quizTotalScore;
        
                    foreach ($learnerQuizScoresData as $quiz) {
                        $quizLearnerSumScore += $quiz->average_score;
                    }
    
    
                $learnerPostAssessmentScoresData = DB::table('learner_post_assessment_progress')
                ->select (
                        DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                    )
                    ->where('course_id', $course)
                    ->where('learner_course_id', $learner_course)
                    ->get();
    
                $totalScoreCount_post_assessment = DB::table('learner_post_assessment_output')
                ->where('course_id', $course)
                ->where('learner_course_id', $learner_course)
                ->where('attempt', 1)
                ->count();
    
    
                $postAssessmentLearnerSumScore = 0;
    
    
                foreach ($learnerPostAssessmentScoresData as $post_assessment) {
                    $postAssessmentLearnerSumScore += $post_assessment->average_score;
                }
            
            $learnerPreAssessmentGrade = DB::table('learner_pre_assessment_progress')
            ->select(
                'score',
                'start_period',
                'finish_period'
            )
            ->where('course_id', $course)
            ->where('learner_course_id', $learner_course)
            ->first();

            $learnerPreAssessmentScoresData = DB::table('learner_pre_assessment_progress')
            ->select(
                'score'
            )
            ->where('course_id', $course)
            ->where('learner_course_id', $learner_course)
            ->get();

            $totalScoreCount_pre_assessment = DB::table('learner_pre_assessment_output')
            ->where('course_id', $course)
            ->where('learner_course_id', $learner_course)
            ->count();

            $preAssessmentLearnerSumScore = 0;


            foreach ($learnerPreAssessmentScoresData as $pre_assessment) {
                $preAssessmentLearnerSumScore += $pre_assessment->score;
            }


            $learnerPostAssessmentGrade = DB::table('learner_post_assessment_progress')
            ->select (
                    DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                )
                ->where('course_id', $course)
                ->where('learner_course_id', $learner_course)
                ->first();

            $learnerPostAssessmentData = DB::table('learner_post_assessment_progress') 
            ->select(
                'start_period',
                'finish_period'
            )
            ->where('course_id', $course)
            ->where('learner_course_id', $learner_course)
            ->orderBy('attempt', 'DESC')
            ->first();

            $courseGrading = DB::table('course_grading')
            ->select(
                'activity_percent',
                'quiz_percent',
                'pre_assessment_percent',
                'post_assessment_percent',
            )
            ->where('course_id', $course)
            ->first();


            if($courseData->course_progress === 'COMPLETED') {
                      // compute now the grades
                      $activityGrade = 0;
                      $quizGrade = 0;
                      $postAssessmentGrade = 0;
                      $preAssessmentGrade = 0;
                      $totalGrade = 0;
          
                      // activity
                      $activityGrade = (($activityLearnerSumScore / $activityTotalSum) * 100) * $courseGrading->activity_percent;
                      $quizGrade = (($quizLearnerSumScore / $quizTotalSum) * 100) * $courseGrading->quiz_percent;
                      $postAssessmentGrade = (($postAssessmentLearnerSumScore / $totalScoreCount_post_assessment) * 100) * $courseGrading->pre_assessment_percent;
                      $preAssessmentGrade = (($preAssessmentLearnerSumScore / $totalScoreCount_pre_assessment) * 100) * $courseGrading->post_assessment_percent;
          
          
                      $totalGrade = $activityGrade + $quizGrade + $postAssessmentGrade;
          
                       
                      if ($totalGrade >= 90) {
                          $remarks = 'Excellent';
                      } elseif ($totalGrade >= 80) {
                          $remarks = 'Very Good';
                      } elseif ($totalGrade >= 70) {
                          $remarks = 'Good';
                      } elseif ($totalGrade > 50) {
                          $remarks = 'Satisfactory';
                      } else {
                          $remarks = 'Needs Improvement';
                      }

            } else {
                $activityGrade = null;
                $quizGrade = null;
                $preAssessmentGrade = null;
                $postAssessmentGrade = null;
                $totalGrade = null;
                $remarks = null;
            }


                $data = [
                    'title' => 'Course Gradesheet',
                    'scripts' => ['/learner_post_assessment.js'],
                    'mainBackgroundCol' => '#00693e',
                    'courseData' => $courseData,
                    'activityScoresData' => $learnerActivityScoresData,
                    'quizScoresData' => $learnerQuizScoresData,
                    'preAssessmentData' => $learnerPreAssessmentGrade,
                    'postAssessmentGrade' => $learnerPostAssessmentGrade,
                    'postAssessmentData' => $learnerPostAssessmentData,

                    'learnerLessonsData' => $learnerLessonsData,

                    'activityLearnerSumScore' => $activityLearnerSumScore,
                    'activityTotalSum' => $activityTotalSum,
                    'activityGrade' => $activityGrade,

                    'quizLearnerSumScore' => $quizLearnerSumScore,
                    'quizTotalSum' => $quizTotalSum,
                    'quizGrade' => $quizGrade,

                    'postAssessmentLearnerSumScore' => $postAssessmentLearnerSumScore,
                    'totalScoreCount_post_assessment' => $totalScoreCount_post_assessment,
                    'postAssessmentScoreGrade' => $postAssessmentGrade,

                    'preAssessmentGradeData' => $preAssessmentGrade,
                    'preAssessmentLearnerSumScore' => $preAssessmentLearnerSumScore,
                    'totalScoreCount_pre_assessment' => $totalScoreCount_pre_assessment,

                    'totalGrade' => $totalGrade,
                    'remarks' => $remarks,
                ];


                // dd($data);
                // return view('learner_course.courseGrades', compact('learner'))
                // ->with($data);
                return $data;

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/admin');
        }
    }


    public function coursePerformance(Learner $learner, Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
                try {

                $learnerCourseData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_progress_id',
                    'learner_course_progress.learner_course_id',
                    'learner_course_progress.learner_id',
                    'learner_course_progress.course_id',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',
                    'learner_course_progress.grade',
                    'learner_course_progress.remarks',

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

                $learnerPreAssessmentGrade = DB::table('learner_pre_assessment_progress')
                ->select(
                    'score'
                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learnerCourseData->learner_course_id)
                ->first();
    
                $learnerPostAssessmentGrade = DB::table('learner_post_assessment_progress')
                ->select (
                        DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                    )
                    ->where('course_id', $course->course_id)
                    ->where('learner_course_id', $learnerCourseData->learner_course_id)
                    ->first();


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
                    ->where('learner_course.course_id', $course->course_id)
                    ->where('learner_course.learner_id', $learner->learner_id);
                
                    $gradeWithActivityData = $gradeData->get();
                    
                    foreach ($gradeWithActivityData as $activityData) {
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
                            ->where('learner_activity_output.course_id', $course->course_id)
                            ->where('learner_activity_output.learner_course_id', $activityData->learner_course_id)
                            ->groupBy('learner_activity_output.activity_id', 'learner_activity_output.activity_content_id', 'activities.activity_title')
                            ->get();
                    }
                    
                    $gradeWithQuizData = $gradeWithActivityData;
                    
                    foreach ($gradeWithQuizData as $quizData) {
                        $quizData->quizzes = DB::table('learner_quiz_progress')
                            ->select(
                                'learner_quiz_progress.quiz_id',
                                'quizzes.quiz_title',
                                DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_quiz_progress.score, 0)), 2), 0) as average_score')
                            )
                            ->leftJoin('quizzes', 'quizzes.quiz_id', '=', 'learner_quiz_progress.quiz_id')
                            ->where('learner_quiz_progress.course_id', $course->course_id)
                            ->where('learner_quiz_progress.learner_course_id', $quizData->learner_course_id)
                            ->groupBy('learner_quiz_progress.quiz_id', 'quizzes.quiz_title')
                            ->get();
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
                ->where('activities.course_id', $course->course_id)
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
                ->where('quizzes.course_id', $course->course_id)
                ->groupBy('quizzes.quiz_id')
                ->orderBy('quizzes.topic_id', 'asc')
                ->get();
            
                
                    $gradeComputation = $this->grades($course->course_id, $learnerCourseData->learner_course_id);

                    $learnerPreAssessmentData = DB::table('learner_pre_assessment_progress')
                    ->select(
                        'learner_pre_assessment_progress.learner_pre_assessment_progress_id',
                        'learner_pre_assessment_progress.status',
                        'learner_pre_assessment_progress.start_period',
                        'learner_pre_assessment_progress.finish_period',
                        'learner_pre_assessment_progress.score',
                        'learner_pre_assessment_progress.remarks',
                    )
                    ->join('learner_pre_assessment_output', 'learner_pre_assessment_progress.learner_course_id', 'learner_pre_assessment_output.learner_course_id')
                    ->where('learner_pre_assessment_progress.course_id', $course->course_id)
                    ->where('learner_pre_assessment_progress.learner_id', $learner->learner_id)
                    ->first();
                
                $learnerPostAssessmentData = DB::table('learner_post_assessment_progress')
                    ->select(
                        'learner_post_assessment_progress.learner_post_assessment_progress_id',
                        'learner_post_assessment_progress.status',
                        'learner_post_assessment_progress.start_period',
                        'learner_post_assessment_progress.finish_period',
                        'learner_post_assessment_progress.score',
                        'learner_post_assessment_progress.remarks',
                        'learner_post_assessment_progress.attempt',
                    )
                    ->where('learner_post_assessment_progress.course_id', $course->course_id)
                    ->where('learner_post_assessment_progress.learner_id', $learner->learner_id)
                    ->get();
                
                
                $data = [
                    'title' => 'Course Performance',
                    'scripts' => ['AD_learnerCoursePerformance.js'],
                    'learnerCourseData' => $learnerCourseData,
                    'learner' =>$learner,
                    'learnerSyllabusData' => $learnerSyllabusData,
                    'admin' => $adminSession,
                    'gradesheet' => $gradeWithQuizData,
                    'learnerPreAssessmentGrade' => $learnerPreAssessmentGrade,
                    'learnerPostAssessmentGrade' => $learnerPostAssessmentGrade,
                    'gradeComputation' => $gradeComputation,
                    'activitySyllabus' => $activitySyllabusData,
                    'quizSyllabus' => $quizSyllabusData,
                    'learnerPreAssessmentData' => $learnerPreAssessmentData,
                    'learnerPostAssessmentData' => $learnerPostAssessmentData,
                ];
        
          
                // dd($data);
                return view('adminPerformance.learnerCoursePerformance' , compact('learner'))
                ->with($data);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/admin');
        }
    }


    public function view_output_post_assessment(Course $course, LearnerCourse $learner_course, $attempt) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            try {

           
                $courseData = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
                    'learner_course.learner_id',
                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.instructor_id',
                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('course', 'learner_course.course_id', 'course.course_id')
                ->join('learner', 'learner_course.learner_id', 'learner.learner_id')
                ->join('instructor', 'course.instructor_id', 'instructor.instructor_id')
                ->where('learner_course.course_id', $course->course_id)
                ->where('learner_course.learner_course_id', $learner_course->learner_course_id)
                ->first();

                $postAssessmentData = DB::table('learner_post_assessment_progress')
                ->select(
                    'learner_post_assessment_progress_id',
                    'status',
                    'max_duration',
                    'score',
                    'remarks',
                    'attempt',
                    'start_period',
                    'finish_period',
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('attempt', $attempt)
                ->first();


                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();

        
                        $postAssessmentOutputData = DB::table('learner_post_assessment_output')
                        ->select(
                            'learner_post_assessment_output.learner_post_assessment_output_id',
                            'learner_post_assessment_output.learner_course_id',
                            'learner_post_assessment_output.course_id',
                            'learner_post_assessment_output.attempt',
                            'learner_post_assessment_output.question_id',
                            'learner_post_assessment_output.syllabus_id',
                            'learner_post_assessment_output.answer',
                            'learner_post_assessment_output.isCorrect',
                            'questions.question',
                            'questions.category',
                            DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers')
                        )
                        ->join('questions', 'learner_post_assessment_output.question_id', '=', 'questions.question_id')
                        ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
                        ->where('learner_post_assessment_output.course_id', $courseData->course_id)
                        ->where('learner_post_assessment_output.learner_course_id', $courseData->learner_course_id)
                        ->where('attempt', $attempt)
                        ->groupBy(
                            'learner_post_assessment_output.learner_post_assessment_output_id',
                            'learner_post_assessment_output.learner_course_id',
                            'learner_post_assessment_output.course_id',
                            'learner_post_assessment_output.question_id',
                            'learner_post_assessment_output.syllabus_id',
                            'questions.question',
                            'questions.category',
                            'questions.question_id'
                        )
                        ->get();

                    

                $data = [
                    'title' => 'Course Post Assessment',
                    'scripts' => ['/learner_post_assessment_output.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'admin' => $adminSession,
                    'learnerCourseData' => $courseData,
                    'postAssessmentData' => $postAssessmentData,
                    'postAssessmentOutputData' => $postAssessmentOutputData,
                    'courseData' => $courseData
                ];

                // dd($data);

                return view('adminPerformance.learner_post_assessment_output')
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/admin');
        }
    }

    public function view_output_post_assessment_json(Course $course, LearnerCourse $learner_course, $attempt) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            try {

           
                $courseData = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
                    'learner_course.learner_id',
                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.instructor_id',
                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                )
                ->join('course', 'learner_course.course_id', 'course.course_id')
                ->join('instructor', 'course.instructor_id', 'instructor.instructor_id')
                ->where('learner_course.course_id', $course->course_id)
                ->first();

                $postAssessmentData = DB::table('learner_post_assessment_progress')
                ->select(
                    'learner_post_assessment_progress_id',
                    'status',
                    'max_duration',
                    'score',
                    'remarks',
                    'attempt',
                    'start_period',
                    'finish_period',
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('attempt', $attempt)
                ->first();


                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();

                    $correctAnswerSubquery = DB::table('question_answer')
                    ->select('question_id', DB::raw('JSON_ARRAYAGG(answer) as correct_answer'))
                    ->where('isCorrect', 1)
                    ->groupBy('question_id');
        
                    $postAssessmentOutputData = DB::table('learner_post_assessment_output')
                    ->select(
                        'learner_post_assessment_output.learner_post_assessment_output_id',
                        'learner_post_assessment_output.learner_course_id',
                        'learner_post_assessment_output.course_id',
                        'learner_post_assessment_output.question_id',
                        'learner_post_assessment_output.syllabus_id',
                        'learner_post_assessment_output.attempt',
                        'learner_post_assessment_output.answer',
                        'learner_post_assessment_output.isCorrect',
                        'questions.question',
                        'questions.category',
                        DB::raw('JSON_ARRAYAGG(question_answer.answer) as all_choices'),
                        DB::raw('correct_answers.correct_answer')
                    )
                    ->join('questions', 'learner_post_assessment_output.question_id', '=', 'questions.question_id')
                    ->leftJoinSub($correctAnswerSubquery, 'correct_answers', function ($join) {
                        $join->on('questions.question_id', '=', 'correct_answers.question_id');
                    })
                    ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
                    ->where('learner_post_assessment_output.course_id', $courseData->course_id)
                    ->where('learner_post_assessment_output.learner_course_id', $courseData->learner_course_id)
                    ->where('attempt', $attempt)
                    ->groupBy(
                        'learner_post_assessment_output.learner_post_assessment_output_id',
                        'learner_post_assessment_output.learner_course_id',
                        'learner_post_assessment_output.course_id',
                        'learner_post_assessment_output.question_id',
                        'learner_post_assessment_output.syllabus_id',
                        'questions.question',
                        'questions.category',
                        'correct_answers.correct_answer'
                    )
                    ->get();


                    

                $data = [
                    'title' => 'Course Pre Assessment',
                    'scripts' => ['/learner_pre_assessment_output.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'postAssessmentData' => $postAssessmentData,
                    'postAssessmentOutputData' => $postAssessmentOutputData,
                ];

                // // dd($data);

                // return view('learner_course.coursePreAssessmentOutput', compact('learner'))
                // ->with($data);
                return response()->json($data);
    

            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
            
                return response()->json(['errors' => $errors], 422);
            }
        } else {
            return redirect('/admin');
        }
    }



    public function view_output_pre_assessment(Course $course, LearnerCourse $learner_course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            try {

           
                $courseData = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
                    'learner_course.learner_id',
                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.instructor_id',
                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                    'learner.learner_fname',
                    'learner.learner_lname',
                )
                ->join('course', 'learner_course.course_id', 'course.course_id')
                ->join('learner', 'learner_course.learner_id', 'learner.learner_id')
                ->join('instructor', 'course.instructor_id', 'instructor.instructor_id')
                ->where('learner_course.course_id', $course->course_id)
                ->where('learner_course.learner_course_id', $learner_course->learner_course_id)
                ->first();

                $preAssessmentData = DB::table('learner_pre_assessment_progress')
                ->select(
                    'learner_pre_assessment_progress_id',
                    'status',
                    'max_duration',
                    'score',
                    'remarks',
                    'start_period',
                    'finish_period',
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->first();


                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();

        
                        $preAssessmentOutputData = DB::table('learner_pre_assessment_output')
                        ->select(
                            'learner_pre_assessment_output.learner_pre_assessment_output_id',
                            'learner_pre_assessment_output.learner_course_id',
                            'learner_pre_assessment_output.course_id',
                            'learner_pre_assessment_output.question_id',
                            'learner_pre_assessment_output.syllabus_id',
                            'learner_pre_assessment_output.answer',
                            'learner_pre_assessment_output.isCorrect',
                            'questions.question',
                            'questions.category',
                            DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers')
                        )
                        ->join('questions', 'learner_pre_assessment_output.question_id', '=', 'questions.question_id')
                        ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
                        ->where('learner_pre_assessment_output.course_id', $courseData->course_id)
                        ->where('learner_pre_assessment_output.learner_course_id', $courseData->learner_course_id)
                        ->groupBy(
                            'learner_pre_assessment_output.learner_pre_assessment_output_id',
                            'learner_pre_assessment_output.learner_course_id',
                            'learner_pre_assessment_output.course_id',
                            'learner_pre_assessment_output.question_id',
                            'learner_pre_assessment_output.syllabus_id',
                            'questions.question',
                            'questions.category',
                            'questions.question_id'
                        )
                        ->get();

                    

                $data = [
                    'title' => 'Course Pre Assessment',
                    'scripts' => ['/learner_pre_assessment_output.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'preAssessmentData' => $preAssessmentData,
                    'admin' => $adminSession,
                    'preAssessmentOutputData' => $preAssessmentOutputData,
                    'courseData' => $courseData,
                ];

                // dd($data);

                return view('adminPerformance.learner_pre_assessment_output')
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/admin');
        }
    }

    public function view_output_pre_assessment_json(Course $course, LearnerCourse $learner_course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            try {

           
                $courseData = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
                    'learner_course.learner_id',
                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.instructor_id',
                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                )
                ->join('course', 'learner_course.course_id', 'course.course_id')
                ->join('instructor', 'course.instructor_id', 'instructor.instructor_id')
                ->where('learner_course.course_id', $course->course_id)
                ->first();

                $preAssessmentData = DB::table('learner_pre_assessment_progress')
                ->select(
                    'learner_pre_assessment_progress_id',
                    'status',
                    'max_duration',
                    'score',
                    'remarks',
                    'start_period',
                    'finish_period',
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->first();


                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();

                    $correctAnswerSubquery = DB::table('question_answer')
                    ->select('question_id', DB::raw('JSON_ARRAYAGG(answer) as correct_answer'))
                    ->where('isCorrect', 1)
                    ->groupBy('question_id');
        
                    $preAssessmentOutputData = DB::table('learner_pre_assessment_output')
                    ->select(
                        'learner_pre_assessment_output.learner_pre_assessment_output_id',
                        'learner_pre_assessment_output.learner_course_id',
                        'learner_pre_assessment_output.course_id',
                        'learner_pre_assessment_output.question_id',
                        'learner_pre_assessment_output.syllabus_id',
                        'learner_pre_assessment_output.answer',
                        'learner_pre_assessment_output.isCorrect',
                        'questions.question',
                        'questions.category',
                        DB::raw('JSON_ARRAYAGG(question_answer.answer) as all_choices'),
                        DB::raw('correct_answers.correct_answer')
                    )
                    ->join('questions', 'learner_pre_assessment_output.question_id', '=', 'questions.question_id')
                    ->leftJoinSub($correctAnswerSubquery, 'correct_answers', function ($join) {
                        $join->on('questions.question_id', '=', 'correct_answers.question_id');
                    })
                    ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
                    ->where('learner_pre_assessment_output.course_id', $courseData->course_id)
                    ->where('learner_pre_assessment_output.learner_course_id', $courseData->learner_course_id)
                    ->groupBy(
                        'learner_pre_assessment_output.learner_pre_assessment_output_id',
                        'learner_pre_assessment_output.learner_course_id',
                        'learner_pre_assessment_output.course_id',
                        'learner_pre_assessment_output.question_id',
                        'learner_pre_assessment_output.syllabus_id',
                        'questions.question',
                        'questions.category',
                        'correct_answers.correct_answer'
                    )
                    ->get();


                    

                $data = [
                    'title' => 'Course Pre Assessment',
                    'scripts' => ['/learner_pre_assessment_output.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'preAssessmentData' => $preAssessmentData,
                    'preAssessmentOutputData' => $preAssessmentOutputData,
                ];

                // // dd($data);

                // return view('learner_course.coursePreAssessmentOutput', compact('learner'))
                // ->with($data);
                return response()->json($data);
    

            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
            
                return response()->json(['errors' => $errors], 422);
            }
        } else {
            return redirect('/admin');
        }
    }


    public function coursePerformanceData(Learner $learner, Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
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
            ];

            return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }


        } else {
            return redirect('/admin');
        }
    }

    public function syllabusPerformanceData(Learner $learner, Course $course) {

        if (auth('admin')->check()) {
            $adminSession = session('admin');
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
            return redirect('/admin');
        }
    }








    public function instructors() {
        return $this->search_instructor();
    }

    public function search_instructor() {

        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);
            $admin_codename = $admin['admin_codename'];
        } else {
            return redirect('/admin');
        }

        $search_by = request('searchBy');
        $search_val = request('searchVal');
        
        $filter_date = request('filterDate');
        $filter_status = request('filterStatus');


        try {
            $query = DB::table('instructor')
                ->orderBy('created_at', 'DESC');

            if(!empty($filter_date) || !empty($filter_status)) {
                if(!empty($filter_date) && empty($filter_date)) {
                    $query->where('created_at', 'LIKE', $filter_date.'%');
                } elseif (empty($filter_date) && !empty($filter_status)) {
                    $query->where('status', 'LIKE', $filter_status.'%');
                } else {
                    $query->where('created_at', 'LIKE', $filter_date.'%')
                        ->where('status', 'LIKE', $filter_status.'%');
                }
            }

            if(!empty($search_by) && !empty($search_val)) {
                if($search_by == 'name') {
                    $query->where(function ($query) use ($search_val) {
                        $query->where('instructor_fname', 'LIKE', $search_val.'%')
                            ->orWhere('instructor_lname', 'LIKE', $search_val.'%');
                    });
                } else {
                    $query->where($search_by, 'LIKE', $search_val.'%');
                }
            }


            $instructors = $query->paginate(10);

            return view('adminPerformance.instructors', compact('instructors'))
                ->with(['title' => 'Instructor Management', 'admin' => $admin]);

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }



    public function view_instructor(Instructor $instructor) {
        
        if (auth('admin')->check()) {
            $adminSession = session('admin');

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

                $instructorData = DB::table('instructor')
                ->select(
                    DB::raw('CONCAT(instructor_fname, " ", instructor_lname) as name'),
                )
                ->where('instructor_id', $instructor->instructor_id)
                ->first();

                $data = [
                    'title' => 'Performance',
                    'scripts' => ['AD_instructor_performance.js'],
                    'courses' => $courses,
                    'instructor' => $instructorData,
                    'admin' => $adminSession,
                ];
        
                // dd($data);
                return view('adminPerformance.instructorPerformance')
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }


        } else {
            return redirect('/admin');
        }
    }



    public function i_sessionData(Instructor $instructor) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');

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
            return redirect('/admin');
        }
    }


    public function i_totalCourseNum (Instructor $instructor) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            
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
            return redirect('/admin');
        }
    }


    public function i_courseChartData(Instructor $instructor, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            
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
            return redirect('/admin');
        }
    }




    public function courses() {
        return $this->search_course();
    }
    
    public function search_course() {
    
        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);
    
    
            $search_by = request('searchBy');
            $search_val = request('searchVal');
            
            $filter_date = request('filterDate');
            $filter_status = request('filterStatus');
    
    
            try {
                $query = DB::table('course')
                    ->select(
                        'course.course_id',
                        'course.course_name',
                        'course.course_code',
                        'course.course_status',
                        'course.course_difficulty',
                        'course.course_description',
                        'instructor.instructor_lname',
                        'instructor.instructor_fname',
                        'course.created_at',
                    )
                    ->join('instructor', 'instructor.instructor_id', '=', 'course.instructor_id')
                    ->orderBy('course.created_at', 'DESC');
    
                if(!empty($filter_date) || !empty($filter_status)) {
                    if(!empty($filter_date) && empty($filter_date)) {
                        $query->where('course.created_at', 'LIKE', $filter_date.'%');
                    } elseif (empty($filter_date) && !empty($filter_status)) {
                        $query->where('course.course_status', 'LIKE', $filter_status.'%');
                    } else {
                        $query->where('course.created_at', 'LIKE', $filter_date.'%')
                            ->where('course.course_status', 'LIKE', $filter_status.'%');
                    }
                }
    
                if(!empty($search_by) && !empty($search_val)) {
                    if($search_by == 'instructor') {
                        $query->where(function ($query) use ($search_val) {
                            $query->where('instructor_fname', 'LIKE', $search_val.'%')
                                ->orWhere('instructor_lname', 'LIKE', $search_val.'%');
                        });
                    } else {
                        $query->where('course.'.$search_by, 'LIKE', $search_val.'%');
                    }
                }
    
    
                $courses = $query->paginate(10);
    
                $data = ['title' => 'Course Management',
                     'admin' => $admin
    
                ];
    
                return view('adminPerformance.courses', compact('courses'))
                    ->with($data);
    
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/admin');
        }
    }



    public function view_course(Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
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
                ->where('learner_course.course_id', $course->course_id);

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
                    ->where('learner_activity_output.course_id', $course->course_id)
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
                ->where('learner_quiz_progress.course_id', $course->course_id)
                ->where('learner_quiz_progress.learner_course_id', $activityData->learner_course_id)
                ->groupBy('learner_quiz_progress.quiz_id', 'quizzes.quiz_title')
                ->get();


                $activityData->pre_assessment = DB::table('learner_pre_assessment_progress')
                ->select(
                    'score'
                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $activityData->learner_course_id)
                ->first();

                $activityData->post_assessment = DB::table('learner_post_assessment_progress')
                ->select (
                        DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                    )
                    ->where('course_id', $course->course_id)
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
            ->where('activities.course_id', $course->course_id)
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
            ->where('quizzes.course_id', $course->course_id)
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
                    ->where('learner_pre_assessment_progress.course_id', $course->course_id)
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
                    ->where('learner_post_assessment_progress.course_id', $course->course_id)
                    ->get();
            
            
                $data = [
                    'title' => 'Course Performance',
                    'scripts' => ['AD_course_performance.js'],
                    'course' => $course,
                    'syllabus' => $syllabus,
                    'admin' => $adminSession,
                    'gradesheet' => $gradeWithActivityData,
                    'activitySyllabus' => $activitySyllabusData,
                    'quizSyllabus' => $quizSyllabusData,
                    'learnerPreAssessmentData' => $learnerPreAssessmentData,
                    'learnerPostAssessmentData' => $learnerPostAssessmentData,
                ];

                // dd($data);
                return view('adminPerformance.coursePerformance')
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }  else {
            return redirect('/admin');
        } 
    }


    public function selectedCoursePerformance(Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            

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
            return redirect('/admin');
        }
    }

    public function learnerCourseData(Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            

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
                    'learner_course_progress.learner_id',
                    'learner_course_progress.course_id',
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
            return redirect('/admin');
        }
    }

    public function learnerSyllabusData(Course $course, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            
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
                        'learner_lesson_progress.learner_id',
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
                        'learner_activity_progress.learner_id',
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
                        'learner_quiz_progress.learner_id',
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
            return redirect('/admin');
        }
    }



    public function courseSyllabusPerformance(Course $course, Syllabus $syllabus) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            

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

        
        if($syllabusData->category === 'LESSON') {

            $data = [
                'title' => 'Course Performance',
                'scripts' => ['AD_courseLessonPerformance.js'],
                'courseData' => $courseData,
                'syllabusData' => $syllabusData,
                'admin' => $adminSession
            ];
    
            // dd($data);

            return view('adminPerformance.courseLessonPerformance')
        ->with($data);
        } else if($syllabusData->category === 'ACTIVITY') {
            $data = [
                'title' => 'Course Performance',
                'scripts' => ['AD_courseActivityPerformance.js'],
                'courseData' => $courseData,
                'syllabusData' => $syllabusData,
                'admin' => $adminSession
            ];
    
            // dd($data);

            return view('adminPerformance.courseActivityPerformance')
        ->with($data);
        } else {
            $data = [
                'title' => 'Course Performance',
                'scripts' => ['AD_courseQuizPerformance.js'],
                'courseData' => $courseData,
                'syllabusData' => $syllabusData,
                'admin' => $adminSession
            ];
    
            // dd($data);

            return view('adminPerformance.courseQuizPerformance')
        ->with($data);
        }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    } else {
        return redirect('/admin');
    }

    }



    public function courseSyllabusLessonPerformance(Course $course, Syllabus $syllabus) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            
            try {
                $learnerLessonProgressData = DB::table('learner_lesson_progress')
                ->select(
                    'learner_lesson_progress.learner_lesson_progress_id',
                    'learner_lesson_progress.learner_course_id',
                    'learner_lesson_progress.course_id',
                    'learner_lesson_progress.learner_id',
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
            return redirect('/admin');
        }
    }


    public function courseSyllabusActivityPerformance(Course $course, Syllabus $syllabus) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            
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
            return redirect('/admin');
        }
    }

    public function courseSyllabusActivityScoresPerformance(Course $course, Syllabus $syllabus) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            
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
            return redirect('/admin');
        }
    }



    public function courseSyllabusQuizPerformance(Course $course, Syllabus $syllabus) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            
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
            return redirect('/admin');
        }
    }


    public function courseSyllabusQuizScoresPerformance(Course $course, Syllabus $syllabus) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
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
            return redirect('/admin');
        }
    }

    public function courseSyllabusQuizContentOutputPerformance(Course $course, Syllabus $syllabus) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
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
            return redirect('/admin');
        }
    }
}
