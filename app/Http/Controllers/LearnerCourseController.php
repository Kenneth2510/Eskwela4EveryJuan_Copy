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
use App\Models\Certificates;
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
use Dompdf\Dompdf;
use Dompdf\Options;


use App\Http\Controllers\PDFGenerationController;

class LearnerCourseController extends Controller
{
    public function courses (){
        if (session()->has('learner')) {
            $learner= session('learner');
            // dd($instructor);

            try {
                $query = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
                    'learner_course.course_id',
                    'learner_course.status',
                    'learner_course.created_at',
                    'course.course_name',
                    'course.course_code',
                    'course.course_status',
                    'course.course_difficulty',
                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                    'instructor.profile_picture'
                )
                ->join('course','learner_course.course_id','=','course.course_id')
                ->join('instructor' , 'course.instructor_id', '=', 'instructor.instructor_id')
                ->where('learner_course.learner_id', '=', $learner->learner_id);

                $learnerCourse = $query->get();

                $allCourses = DB::table('course')
                ->select(
                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.course_status',
                    'course.course_difficulty',
                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                    'instructor.profile_picture'
                )
                ->join('instructor' , 'course.instructor_id', '=', 'instructor.instructor_id')
                ->where('course.course_status', '=', "APPROVED")
                ->get();

                // dd($allCourses);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/learner');
        }

        // return view('instructor_course.courses' , compact('instructor'))->with('title', 'Instructor Courses');
        return view('learner_course.courses', compact('learner', 'learnerCourse','allCourses'))
        ->with([
            'title' => 'My Courses',
            'scripts' => ['learner_courses.js'],
        ]);
    }


    public function searchCourse(Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner');

            $courseVal = $request->input('courseVal');

            $searchCourse = DB::table('learner_course')
            ->select(
                'learner_course.learner_course_id',
                'learner_course.course_id',
                'learner_course.status',
                'learner_course.created_at',
                'course.course_name',
                'course.course_code',
                'course.course_status',
                'course.course_difficulty',
                'instructor.instructor_fname',
                'instructor.instructor_lname',
                'instructor.profile_picture'
            )
            ->join('course','learner_course.course_id','=','course.course_id')
            ->join('instructor' , 'course.instructor_id', '=', 'instructor.instructor_id')
            ->where('learner_course.learner_id', '=', $learner->learner_id)
            ->where('course.course_name', 'like', '%' . $courseVal . '%')
            ->get();

            $data = [
                'courses' => $searchCourse,
            ];

            return response()->json($data);
        } else {
            return redirect('/instructor');
        }
    }

    public function overview(Course $course) {
 
        if (session()->has('learner')) {
            $learner= session('learner');
            // dd($instructor);

            try {
                $course = DB::table('course')
                ->select(
                    'course.course_id',
                    'course.course_name',
                    'course.course_code',
                    'course.course_description',
                    'course.course_status',
                    'course.course_difficulty',
                    'instructor.instructor_fname',
                    'instructor.instructor_lname',
                    'instructor.profile_picture',
                    'instructor.instructor_email'
                )
                ->join('instructor', 'course.instructor_id', '=',  'instructor.instructor_id')
                ->where('course_id', $course->course_id)
                ->first();

                $enrollees = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
                    'learner_course.learner_id',
                    'learner_course.status',
                    'learner_course.created_at',
                    'learner_course_progress.course_progress',
                    'learner.learner_fname',
                    'learner.learner_lname',
                    'learner.learner_email',
                )
                ->join('learner_course_progress', 'learner_course_progress.learner_course_id', '=' , 'learner_course.learner_course_id')
                ->join('learner', 'learner.learner_id',  '=', 'learner_course.learner_id')
                ->where('learner_course.course_id' , $course->course_id)
                ->get();

                $syllabus = DB::table('syllabus')
                ->select(
                    'syllabus_id',
                    'course_id',
                    'topic_id',
                    'topic_title',
                    'category',
                )
                ->where('course_id', $course->course_id)
                ->orderBy('topic_id')
                ->get();

                $totalSyllabusCount = DB::table('syllabus')
                ->where('course_id', $course->course_id)
                ->count();

                $totalLessonsCount = DB::table('lessons')
                ->where('course_id', $course->course_id)
                ->count();

                $totalActivitiesCount = DB::table('activities')
                ->where('course_id', $course->course_id)
                ->count();

                $totalQuizzesCount = DB::table('quizzes')
                ->where('course_id', $course->course_id)
                ->count();


                $totalLessonsDuration = DB::table('lessons')
                ->select(
                    DB::raw('SUM(duration) as total_duration')
                )
                ->where('course_id', $course->course_id)
                ->first();

                $totalActivitiesDuration = DB::table('activities')
                ->select(
                    DB::raw('SUM(duration) as total_duration')
                )
                ->where('course_id', $course->course_id)
                ->first();

                $totalQuizzesDuration = DB::table('quizzes')
                ->select(
                    DB::raw('SUM(duration) as total_duration')
                )
                ->where('course_id', $course->course_id)
                ->first();

                $totalEnrolledCount = DB::table('learner_course')
                ->where('course_id', $course->course_id)
                ->count();

                $learnerTotalLessonProgressDuration = DB::table('learner_lesson_progress')
                ->select(
                    DB::raw('SUM(TIMEDIFF(finish_period, start_period)) as total_time')
                )
                ->where('learner_id', $learner->learner_id)
                ->where('course_id', $course->course_id)
                ->first();

                $learnerTotalActivityProgressDuration = DB::table('learner_activity_progress')
                ->select(
                    DB::raw('SUM(TIMEDIFF(finish_period, start_period)) as total_time')
                )
                ->where('learner_id', $learner->learner_id)
                ->where('course_id', $course->course_id)
                ->first();

                $learnerTotalQuizProgressDuration = DB::table('learner_quiz_progress')
                ->select(
                    DB::raw('SUM(TIMEDIFF(finish_period, start_period)) as total_time')
                )
                ->where('learner_id', $learner->learner_id)
                ->where('course_id', $course->course_id)
                ->first();

                
                $learnerTotalLessonProgressDuration = $learnerTotalLessonProgressDuration->total_time ?? 0;
                $learnerTotalActivityProgressDuration = $learnerTotalActivityProgressDuration->total_time ?? 0;
                $learnerTotalQuizProgressDuration = $learnerTotalQuizProgressDuration->total_time ?? 0;

                $learnerTotalTime = $learnerTotalLessonProgressDuration + $learnerTotalLessonProgressDuration + $learnerTotalLessonProgressDuration;

                $learnerTotalTimeinSeconds = $learnerTotalTime / 1000;

                $learnerhours = floor($learnerTotalTimeinSeconds / 3600);
                $learnerminutes = floor(($learnerTotalTimeinSeconds % 3600) / 60);
                $learnerseconds = $learnerTotalTimeinSeconds % 60;


                $formattedTotalLearnerCourseTime = sprintf('%02d:%02d:%02d', $learnerhours, $learnerminutes, $learnerseconds);


                $isEnrolled = DB::table('learner_course')
                ->select(  
                'learner_course.learner_course_id',
                'learner_course.course_id',
                'learner_course.status',
                'learner_course.created_at',
                // 'learner_course_progress.course_progress',
                )
                // ->join('learner_course_progress' , 'learner_course_progress.learner_course_id' , '=' , 'learner_course.learner_course_id')
                ->join('course', 'learner_course.course_id', '=', 'course.course_id')
                ->where('learner_course.learner_id', '=', $learner->learner_id)
                ->where('learner_course.course_id', '=', $course->course_id)
                ->first();
                $totalLessonsDuration = $totalLessonsDuration->total_duration ?? 0;
                $totalActivitiesDuration = $totalActivitiesDuration->total_duration ?? 0;
                $totalQuizzesDuration = $totalQuizzesDuration->total_duration ?? 0;
                    // dd($isEnrolled);
                if($isEnrolled) {
                    $courseProgress = DB::table('learner_course_progress')
                    ->select(
                        'learner_course_progress_id',
                        'learner_course_id',
                        'course_progress',
                        'grade',
                        'remarks',
                        'start_period',
                        'finish_period',
                    )
                    ->where('learner_course_id', $isEnrolled->learner_course_id)
                    ->first();
                    // dd($courseProgress);

                    $learnerPreAssessmentGrade = DB::table('learner_pre_assessment_progress')
                    ->select(
                        'score'
                    )
                    ->where('course_id', $course->course_id)
                    ->where('learner_course_id', $isEnrolled->learner_course_id)
                    ->first();
        
                    $learnerPostAssessmentGrade = DB::table('learner_post_assessment_progress')
                    ->select (
                            DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                        )
                        ->where('course_id', $course->course_id)
                        ->where('learner_course_id', $isEnrolled->learner_course_id)
                        ->first();


                        $gradeData = DB::table('learner_course')
                        ->select(
                            'learner_course.learner_course_id',
                            'learner_course.learner_id',
                            'learner_course.created_at',
                            'learner_course_progress.course_progress',
                            'learner_course_progress.start_period',
                            'learner_course_progress.finish_period',
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
                    
                }
         
                $totalCourseTimeInSeconds = $totalLessonsDuration + $totalActivitiesDuration + $totalQuizzesDuration;
                $totalCourseTimeInSeconds = ($totalCourseTimeInSeconds) / 1000; // Convert milliseconds to seconds

                $hours = floor($totalCourseTimeInSeconds / 3600);
                $minutes = floor(($totalCourseTimeInSeconds % 3600) / 60);
                $seconds = $totalCourseTimeInSeconds % 60;
                
                $formattedTotalCourseTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                
                
                // dd($formattedTotalCourseTime);
                


                $syllabusProgress = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress.syllabus_id',
                    'learner_syllabus_progress.category',
                    'learner_syllabus_progress.status',
                    'syllabus.topic_title'
                )
                ->join('syllabus', 'syllabus.syllabus_id', '=', 'learner_syllabus_progress.syllabus_id')
                ->where('learner_syllabus_progress.learner_id' , $learner->learner_id)
                ->where('learner_syllabus_progress.course_id', $course->course_id)
                ->get();
                    // dd($syllabusProgress);
                $syllabusProgressCompleted = DB::table('learner_syllabus_progress')
                ->where('learner_id' , $learner->learner_id)
                ->where('course_id', $course->course_id)
                ->where('status', 'COMPLETED')
                ->count();

                $progressPercent = ($syllabusProgressCompleted / $totalSyllabusCount) * 100;


   



            $activitySyllabusData = DB::table('activities')
            ->select(
                'activity_id',
                'course_id',
                'syllabus_id',
                'topic_id',
                'activity_title'
            )
            ->where('course_id', $course->course_id)
            ->orderBy('topic_id',  'asc')
            ->get();

            $quizSyllabusData = DB::table('quizzes')
            ->select(
                'quiz_id',
                'course_id',
                'syllabus_id',
                'topic_id',
                'quiz_title'
            )
            ->where('course_id', $course->course_id)
            ->orderBy('topic_id',  'asc')
            ->get();

            // dd($gradeWithQuizData);

                            // // $folderName = "{$course->course_id} {$course->course_name}";
                            // $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');

                            // // $directoryPath = "/public/courses/{$folderName}/lesson_1.pdf";
                
                            // // // $courseFiles = Storage::disk('public')->files($folderName);
                
                            // // $courseFiles = Storage::files($directoryPath);
                            // // $courseFiles = Storage::allFiles($directoryPath);
                
                            // $directory = "public/courses/$folderName/documents";
                            
                
                            // // Get all files in the specified directory
                            // $courseFiles = Storage::files($directory);


                            $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');
                            $directory = "public/courses/$folderName/documents";

                            // Get all files in the specified directory
                            $allFiles = glob(storage_path("app/$directory/*.pdf"), GLOB_BRACE);

                            // Filter out files containing the keywords
                            $filteredFiles = array_filter($allFiles, function($file) {
                                return strpos($file, 'gradesheet') === false && strpos($file, 'enrollees') === false;
                            });



                if($isEnrolled) {
                    $data = [
                        'title' => 'Course Overview',
                        'scripts' => ['learner_courseOverview.js'],
                        'totalLessonsDuration' => $totalLessonsDuration,
                        'totalActivitiesDuration' => $totalActivitiesDuration,
                        'totalQuizzesDuration' => $totalQuizzesDuration,
                        'totalCourseTime' => $formattedTotalCourseTime,
                        'totalSyllabusCount' => $totalSyllabusCount,
                        'totalLessonsCount' => $totalLessonsCount,
                        'totalActivitiesCount' => $totalActivitiesCount,
                        'totalQuizzesCount' => $totalQuizzesCount,
                        'syllabus' => $syllabus,
                        'totalEnrolledCount' => $totalEnrolledCount,
                        'totalLearnerTime' => $formattedTotalLearnerCourseTime,
                        'syllabusProgress' => $syllabusProgress,
                        'syllabusProgressCompleted' => $syllabusProgressCompleted,
                        'progressPercent' => round($progressPercent, 2),
                        'enrollees' => $enrollees,
                        'gradesheet' => $gradeWithQuizData,
                        'activitySyllabus' => $activitySyllabusData,
                        'quizSyllabus' => $quizSyllabusData,
                        'courseFiles' => $filteredFiles,
                        'courseProgress' => $courseProgress,
                        'preAssessmentGrade' => $learnerPreAssessmentGrade,
                        'postAssessmentGrade' => $learnerPostAssessmentGrade->average_score,
                    ];
                } else {
                    $data = [
                        'title' => 'Course Overview',
                        'scripts' => ['learner_courseOverview.js'],
                        'totalLessonsDuration' => $totalLessonsDuration,
                        'totalActivitiesDuration' => $totalActivitiesDuration,
                        'totalQuizzesDuration' => $totalQuizzesDuration,
                        'totalCourseTime' => $formattedTotalCourseTime,
                        'totalSyllabusCount' => $totalSyllabusCount,
                        'totalLessonsCount' => $totalLessonsCount,
                        'totalActivitiesCount' => $totalActivitiesCount,
                        'totalQuizzesCount' => $totalQuizzesCount,
                        'syllabus' => $syllabus,
                        'totalEnrolledCount' => $totalEnrolledCount,
                        'totalLearnerTime' => $formattedTotalLearnerCourseTime,
                        'syllabusProgress' => null,
                        'syllabusProgressCompleted' => $syllabusProgressCompleted,
                        'progressPercent' => round($progressPercent, 2),
                        'enrollees' => $enrollees,
                        'gradesheet' => null,
                        'activitySyllabus' => $activitySyllabusData,
                        'quizSyllabus' => $quizSyllabusData,
                        'courseFiles' => null,
                    ];
                }

                // dd($data);
                return view('learner_course.courseOverview', compact('course', 'learner', 'isEnrolled'))
                ->with($data);
    

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/learner');
        }

        
    }

    public function enroll_course(Course $course) {
        if (session()->has('learner')) {
            $learner= session('learner');

            if($learner->status !== 'Approved') {
                session()->flash('message', 'Account is not yet Approved');
                return response()->json(['message' => 'Account is not yet Approved', 'redirect_url' => "/learner/course/$course->course_id"]);
            } else {
                try {
                    $courseEnrollData = ([
                        "learner_id" => $learner->learner_id,
                        "course_id" => $course->course_id,
                    ]);

                    LearnerCourse::firstOrCreate($courseEnrollData);

                    $reportController = new PDFGenerationController();

                    $reportController->courseEnrollees($course->course_id);
                    $reportController->learnerCourseData($learner->learner_id);

                    session()->flash('message', 'Course enrolled Successfully');
                    return response()->json(['message' => 'Course enrolled successfully', 'redirect_url' => '/learner/courses']);
                

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
        
                    return response()->json(['errors' => $errors], 422); 
                }
            }
        } else {
            return redirect('/learner');
        }
    }

    public function unEnroll_course(LearnerCourse $learnerCourse) {
        // dd($learnerCourse);
        if (session()->has('learner')) {
            $learner= session('learner');

            try {

                $learnerActivityOutput = DB::table('learner_activity_output')
                ->select(
                    'learner_activity_output_id'
                )
                ->where('learner_course_id', $learnerCourse->learner_course_id)
                ->get();

                foreach($learnerActivityOutput as $activityOutput) {
                    DB::table('learner_activity_criteria_score')
                    ->where('learner_activity_output_id' , $activityOutput->learner_activity_output_id)
                    ->delete();
                }

                DB::table('learner_activity_output')
                ->where('learner_course_id', $learnerCourse->learner_course_id)
                ->delete();

                DB::table('learner_activity_progress')
                ->where('learner_course_id', $learnerCourse->learner_course_id)
                ->delete();
                
                
                DB::table('learner_quiz_progress')
                ->where('learner_course_id', $learnerCourse->learner_course_id)
                ->delete();

                DB::table('learner_quiz_output')
                ->where('learner_course_id', $learnerCourse->learner_course_id)
                ->delete();

                DB::table('learner_lesson_progress')
                ->where('learner_course_id', $learnerCourse->learner_course_id)
                ->delete();

                DB::table('learner_syllabus_progress')
                ->where('learner_course_id', $learnerCourse->learner_course_id)
                ->delete();

                DB::table('learner_course_progress')
                ->where('learner_course_id', $learnerCourse->learner_course_id)
                ->delete();

                $learnerCourse->delete();

                session()->flash('message', 'Course unenrolled Successfully');
                return response()->json(['message' => 'Course unenrolled successfully', 'redirect_url' => "/learner/courses"]);
                
            
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();        
                return response()->json(['errors' => $errors], 422);
            }
        } else {
            return redirect('/learner');
        }
    }

    
    public function gradespdf($course, $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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

                if($courseData){ 
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
    
                    if($courseData->course_progress === 'COMPLETED') {
                        // compute now the grades
                        $activityGrade = 0;
                        $quizGrade = 0;
                        $postAssessmentGrade = 0;
                        $preAssessmentGrade = 0;
                        $totalGrade = 0;
    
                        // activity
                        $activityGrade = (($activityLearnerSumScore / $activityTotalSum) * 100) * 0.35;
                        $quizGrade = (($quizLearnerSumScore / $quizTotalSum) * 100) * 0.35;
                        $postAssessmentGrade = (($postAssessmentLearnerSumScore / $totalScoreCount_post_assessment) * 100) * 0.30;
                        $preAssessmentGrade = (($preAssessmentLearnerSumScore / $totalScoreCount_pre_assessment) * 100) * 0.30;
    
    
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
    
                    }
                      
                        $learnerBusinessData = DB::table('business')
                        ->select(
                            'business_name',
                            'business_address',
                            'business_owner_name',
                            'bplo_account_number',
                            'business_category',
                            'business_classification',
                            'business_description',
                        )
                        ->where('learner_id', $learner->learner_id)
                        ->first();
    
                    
                    if($courseData->course_progress === 'COMPLETED') {
                        $data = [
                            'title' => 'Course Gradesheet',
                            'scripts' => ['/learner_post_assessment.js'],
                            'mainBackgroundCol' => '#00693e',
                            'businessData' => $learnerBusinessData,
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
                    } else {
                        $data = [
                            'title' => 'Course Gradesheet',
                            'scripts' => ['/learner_post_assessment.js'],
                            'mainBackgroundCol' => '#00693e',
                            'businessData' => $learnerBusinessData,
                            'courseData' => $courseData,
                            'activityScoresData' => $learnerActivityScoresData,
                            'quizScoresData' => $learnerQuizScoresData,
                            'preAssessmentData' => $learnerPreAssessmentGrade,
                            'postAssessmentGrade' => $learnerPostAssessmentGrade,
                            'postAssessmentData' => $learnerPostAssessmentData,
        
                            'learnerLessonsData' => $learnerLessonsData,
        
                            'activityLearnerSumScore' => $activityLearnerSumScore,
                            'activityTotalSum' => $activityTotalSum,
        
                            'quizLearnerSumScore' => $quizLearnerSumScore,
                            'quizTotalSum' => $quizTotalSum,
    
                            'postAssessmentLearnerSumScore' => $postAssessmentLearnerSumScore,
                            'totalScoreCount_post_assessment' => $totalScoreCount_post_assessment,
    
                            'preAssessmentLearnerSumScore' => $preAssessmentLearnerSumScore,
                            'totalScoreCount_pre_assessment' => $totalScoreCount_pre_assessment,
                        ];
                    }
    
    
                    
                $html = view('learner_course.courseGradesPdf', compact('learner'))
    ->with($data)
    ->render();

// Generate a unique filename for the PDF
$filename = $learner->learner_id . '_' . $learner->learner_fname . '_' . $learner->learner_lname . '_' . $courseData->course_name . '.pdf';

// Define the folder path based on the course name
$folderName = Str::slug("{$learner->learner_lname} {$learner->learner_fname}", '_');
$folderPath = 'learners/' . $folderName . '/documents';

// Check if the file already exists in storage and delete it
if (Storage::disk('public')->exists($folderPath . '/' . $filename)) {
    Storage::disk('public')->delete($folderPath . '/' . $filename);
}

// Create an instance of the Dompdf class
$dompdf = new Dompdf();

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF
$output = $dompdf->output();

// Store the new PDF in the public directory within the course-specific folder
Storage::disk('public')->put($folderPath . '/' . $filename, $output);

// Generate the URL to the stored PDF
$pdfUrl = URL::to('storage/' . $folderPath . '/' . $filename);
    
                }

                
                return null;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }


    public function manage_course(Request $request, Course $course) {
        if (session()->has('learner')) {
            $learner= session('learner');
            // dd($instructor);

            try {

                $search_by = $request->input('searchBy');
                $search_val = $request->input('searchVal');

                $filter_date = $request->input('filterDate');
                $filter_status = $request->input('filterStatus');

      
                $course = DB::table('course')
                ->select(
                    "course.course_id",
                    "course.course_name",
                    "course.course_code",
                    "course.course_status",
                    "course.course_difficulty",
                    "course.course_description",
                    "course.created_at",
                    "course.updated_at",
                    "instructor.instructor_lname",
                    "instructor.instructor_fname",
                )
            ->where('course.course_id', $course->course_id)
            ->join('instructor', 'instructor.instructor_id', '=', 'course.instructor_id')
            ->first();

            
            $isEnrolled = DB::table('learner_course')
            ->select(  
            'learner_course.learner_course_id',
            'learner_course.course_id',
            'learner_course.status',
            'learner_course.created_at')
            ->join('course', 'learner_course.course_id', '=', 'course.course_id')
            ->where('learner_course.learner_id', '=', $learner->learner_id)
            ->where('learner_course.course_id', '=', $course->course_id)
            ->first();

            $enrolleesQuery = DB::table('learner_course')
            ->select(
                'learner_course.learner_course_id',
                'learner_course.learner_id',
                'learner_course.status',
                'learner_course.created_at',
                'learner.learner_fname',
                'learner.learner_lname',
                'learner.learner_email'
            )
            ->join('learner', 'learner_course.learner_id', '=', 'learner.learner_id')
            ->orderBy('learner_course.created_at','DESC')
            ->where('learner_course.course_id', '=', $course->course_id);

            if(!empty($filter_date) || !empty($filter_status)) {
                if(!empty($filter_date) && empty($filter_date)) {
                    $enrolleesQuery->where('learner_course.created_at', 'LIKE', $filter_date.'%');
                } elseif (empty($filter_date) && !empty($filter_status)) {
                    $enrolleesQuery->where('learner_course.status', 'LIKE', $filter_status.'%');
                } else {
                    $enrolleesQuery->where('learner_course.created_at', 'LIKE', $filter_date.'%')
                        ->where('learner_course.status', 'LIKE', $filter_status.'%');
                }
            }

            if(!empty($search_by) && !empty($search_val)) {
                if($search_by == 'name') {
                    $enrolleesQuery->where(function ($enrolleesQuery) use ($search_val) {
                        $enrolleesQuery->where('learner.learner_fname', 'LIKE', $search_val.'%')
                            ->orWhere('learner.learner_lname', 'LIKE', $search_val.'%');
                    });
                } else if ($search_by == 'learner_course_id') {
                    $enrolleesQuery->where('learner_course.'.$search_by, 'LIKE', $search_val.'%');
                } else {
                    $enrolleesQuery->where('learner.'.$search_by, 'LIKE', $search_val. '%');
                }
            }

            $enrollees = $enrolleesQuery->get();

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        } else {
            return redirect('/learner');
        }

        $response = [
            'course' => $course,
            'enrollees' => $enrollees,
            'isEnrolled' => $isEnrolled,
            'filterDate' => $filter_date,
            'filterStatus' => $filter_status,
            'searchBy' => $search_by,
            'searchVal' => $search_val,
        ];

        return response()->json($response);
    }

    public function course_overview(Course $course) {
        if (session()->has('learner')) {
            $learner= session('learner');
            // dd($learner);


            try {
                $learnerCourseData = DB::table('learner_course')
                ->select(
                    'learner_course_id',
                    'learner_id',
                    'course_id',
                    'status'
                )
                ->where('learner_id', $learner->learner_id)
                ->where('course_id' , $course->course_id)
                ->first();

                if ($learnerCourseData->status !== 'Approved') {
                    
                    session()->flash('message', 'Your Enrollment is not yet Approved');
                    return redirect()->back();
                };

                $courseData = DB::table('course')
                ->select(
                    'course_id',
                    'course_name',
                    'course_code',
                    'course_description',
                    'course_status',
                    'course_difficulty',
                    'instructor_id',
                )
                ->where('course_id', $course->course_id)
                ->first();

                
                   
                $learnerCourseProgressData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress_id',
                    'learner_course_id',
                    'learner_id',
                    'course_id',
                    'course_progress'
                )
                ->where('learner_course_id', $learnerCourseData->learner_course_id)
                ->first();

                if($learnerCourseProgressData->course_progress !== "IN PROGRESS" && $learnerCourseProgressData->course_progress !== "COMPLETED") {
                    DB::table('learner_course_progress')
                    ->where('learner_course_id', $learnerCourseData->learner_course_id)
                    ->update(['course_progress' => 'IN PROGRESS']);
                    // dd($learnerCourseProgressData);
                }

                $learnerCourseProgressData2 = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress_id',
                    'learner_course_id',
                    'learner_id',
                    'course_id',
                    'course_progress'
                )
                ->where('learner_course_id', $learnerCourseData->learner_course_id)
                ->first();
                

                $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress.learner_syllabus_progress_id',
                    'learner_syllabus_progress.learner_course_id',
                    'learner_syllabus_progress.syllabus_id',
                    'learner_syllabus_progress.category',
                    'learner_syllabus_progress.status',
                    'syllabus.course_id',
                    'syllabus.topic_id',
                    'syllabus.topic_title',
                    'syllabus.category'
                    )
                ->join('syllabus','learner_syllabus_progress.syllabus_id','=','syllabus.syllabus_id')
                ->where('learner_course_id', $learnerCourseData->learner_course_id)
                ->orderBy('syllabus.topic_id', 'ASC')
                ->get();

                // dd($learnerSyllabusProgressData);


                $lessonCount = 0;
                $quizCount = 0;
                $activityCount = 0;

                foreach($learnerSyllabusProgressData as $topic) {
                    if($topic->category == 'LESSON') {
                        $lessonCount++;
                    } else if($topic->category == 'ACTIVITY') {
                        $activityCount++;
                    } else {
                        $quizCount++;
                    }
                }

                $preAssessmentStatus = DB::table('learner_pre_assessment_progress')
                ->select(
                    'learner_pre_assessment_progress_id',
                    'status'
                )
                ->where('learner_course_id', $learnerCourseData->learner_course_id)
                ->first();


                $postAssessmentStatus = DB::table('learner_post_assessment_progress')
                ->select(
                    'learner_post_assessment_progress_id',
                    'status'
                )
                ->where('learner_course_id', $learnerCourseData->learner_course_id)
                ->first();

                
            $this->gradespdf($learnerCourseData->course_id, $learnerCourseData->learner_course_id);

            
        
        return view('learner_course.courseSyllabus', compact('learner'))
        ->with([
            'title' => 'Course Overview',
            'scripts' => ['/L_course_syllabus_overview.js'],
            'course' => $courseData,
            'learnerCourse' => $learnerCourseData,
            'leanerCourseProgress' => $learnerCourseProgressData2,
            'learnerSyllabusData' => $learnerSyllabusProgressData,
            'lessonCount' => $lessonCount,
            'activityCount' => $activityCount,
            'quizCount' => $quizCount,
            'preAssessmentData' => $preAssessmentStatus,
            'postAssessmentData' => $postAssessmentStatus,
        ]);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }

    }

    public function view_syllabus(Course $course) {

        try {
            $syllabusData = DB::table('syllabus')
            ->select(
                'syllabus_id',
                'course_id',
                'topic_id',
                'topic_title',
                'category'
            )
            ->where('course_id', $course->course_id)
            ->orderBy('topic_id', 'ASC')
            ->get();

        } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
        
                    return response()->json(['errors' => $errors], 422); 
        }

        $response = [
            'syllabus' => $syllabusData
        ];

        return response()->json($response);

    }


    public function pre_assessment(Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {

                $courseData = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
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
                ->where('learner_course.learner_id', $learner->learner_id)
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
                ->where('learner_course_id', $courseData->learner_course_id)
                ->where('course_id', $course->course_id)
                ->first();

                $totalNumofQuestions = DB::table('learner_pre_assessment_output')
                ->where('learner_course_id', $courseData->learner_course_id)
                ->where('course_id', $course->course_id)
                ->count();

                $data = [
                    'title' => 'Course Lesson',
                    'scripts' => ['/learner_pre_assessment.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'preAssessmentData' => $preAssessmentData,
                    'questionsCount' => $totalNumofQuestions,
                ];
                // dd($data);

                return view('learner_course.coursePreAssessment', compact('learner'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    public function answer_pre_assessment (Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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
                ->where('learner_course.learner_id', $learner->learner_id)
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
                ->where('learner_course_id', $courseData->learner_course_id)
                ->where('course_id', $course->course_id)
                ->first();


                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();

                DB::table('learner_pre_assessment_progress')
                ->where('learner_pre_assessment_progress_id', $preAssessmentData->learner_pre_assessment_progress_id)
                ->update([
                    'start_period' => $timestampString,
                    'status' => 'IN PROGRESS',
                ]);


                    // if($preAssessmentData->status === 'COMPLETED') {
                    //     session()->flash('message', 'You have already finished your Pre Assessment');
                    //     return redirect('/learner/course/content/'.$course->id.'/'.$learner_course->id.'/pre_assessment')->with('error', 'You have already finished your Pre Assessment');

                    // } else {

                        $learnerPreAssessmentOutputData = DB::table('learner_pre_assessment_output')
                        ->select(
                            'learner_pre_assessment_output_id',
                            'question_id',
                            'syllabus_id',
                        )
                        ->where('learner_course_id', $courseData->learner_course_id)
                        ->where('course_id', $course->course_id)
                        ->get();

                        $questionsCount = DB::table('questions')
                        ->where('course_id', $course->course_id)
                        ->count();

                        if($learnerPreAssessmentOutputData->isEmpty()) {
                            $assessmentQuestions = [];

                            if($questionsCount < 25) {

                                $questions = DB::table('questions')
                                ->select(
                                 'question_id',
                                 'syllabus_id',
                                 'question',
                                 'category'
                                 )
                                 ->where('course_id', $course->course_id)
                                 ->inRandomOrder()
                                 ->get();

                                 $assessmentQuestions = array_merge($assessmentQuestions, $questions->toArray());
                            
                                } else {

                                $questionNumbersPerLesson = DB::table('lessons')
                                ->select(
                                    'lesson_title',
                                    'duration',
                                    'syllabus_id',
                                    DB::raw('(SELECT SUM(duration) FROM lessons WHERE course_id = ' . $course->course_id . ') AS total_duration'),
                                    DB::raw('((duration / (SELECT SUM(duration) FROM lessons WHERE course_id = ' . $course->course_id . ')) * 100) AS percentage'),
                                    DB::raw('ROUND(25 * (duration / (SELECT SUM(duration) FROM lessons WHERE course_id = ' . $course->course_id . ')), 0) AS item_number')
                                )
                                ->where('course_id', $course->course_id)
                                ->get();
                            
                                foreach ($questionNumbersPerLesson as $questionsPerLesson) {
    
                                    $questions = DB::table('questions')
                                       ->select(
                                        'question_id',
                                        'syllabus_id',
                                        'question',
                                        'category'
                                        )
                                        ->where('course_id', $course->course_id)
                                        ->where('syllabus_id', $questionsPerLesson->syllabus_id)
                                        ->inRandomOrder()
                                        ->limit($questionsPerLesson->item_number)
                                        ->get();
    
                                        $assessmentQuestions = array_merge($assessmentQuestions, $questions->toArray());
                                }
                            }

                               // dd($assessmentQuestions);
                               foreach($assessmentQuestions as $content) {
                    
                                $outputData = [
                                    'learner_course_id' => $courseData->learner_course_id,
                                    'learner_id' => $courseData->learner_id,
                                    'course_id' => $courseData->course_id,
                                    'question_id' => $content->question_id,
                                    'syllabus_id' => $content->syllabus_id
                                ];
                    
                                LearnerPreAssessmentOutput::firstOrCreate($outputData);
                            }

                        $preAssessmentOutputData = DB::table('learner_pre_assessment_output')
                        ->select(
                            'learner_pre_assessment_output.learner_pre_assessment_output_id',
                            'learner_pre_assessment_output.learner_course_id',
                            'learner_pre_assessment_output.course_id',
                            'learner_pre_assessment_output.question_id',
                            'learner_pre_assessment_output.syllabus_id',
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
                    
                        
                    } else {
                        $preAssessmentOutputData = DB::table('learner_pre_assessment_output')
                        ->select(
                            'learner_pre_assessment_output.learner_pre_assessment_output_id',
                            'learner_pre_assessment_output.learner_course_id',
                            'learner_pre_assessment_output.course_id',
                            'learner_pre_assessment_output.question_id',
                            'learner_pre_assessment_output.syllabus_id',
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

                    }
                // }
    

                $data = [
                    'title' => 'Course Lesson',
                    'scripts' => ['/learner_pre_assessment.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'preAssessmentData' => $preAssessmentData,
                    'preAssessmentOutputData' => $preAssessmentOutputData,
                ];

                // dd($data);

                return view('learner_course.coursePreAssessmentAnswer', compact('learner'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    public function answer_pre_assessment_json(Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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


                        $preAssessmentOutputData = DB::table('learner_pre_assessment_output')
                        ->select(
                            'learner_pre_assessment_output.learner_pre_assessment_output_id',
                            'learner_pre_assessment_output.learner_course_id',
                            'learner_pre_assessment_output.course_id',
                            'learner_pre_assessment_output.question_id',
                            'learner_pre_assessment_output.syllabus_id',
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
                    'title' => 'Course Lesson',
                    'scripts' => ['/learner_pre_assessment.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'preAssessmentData' => $preAssessmentData,
                    'preAssessmentOutputData' => $preAssessmentOutputData,
                ];

                // dd($data);

                // return view('learner_course.coursePreAssessmentAnswer', compact('learner'))
                // ->with($data);

                return response()->json($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    public function submit_pre_assessment(Course $course, LearnerCourse $learner_course, Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
                // dd($request);
            $learner_pre_assessment_output_id = $request->input('learner_pre_assessment_output_id');
            $question_id = $request->input('question_id');

            $answer = $request->input('answer');

            DB::table('learner_pre_assessment_output')
            ->where('learner_pre_assessment_output_id', $learner_pre_assessment_output_id)
            ->where('question_id', $question_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->update([
                'answer' => $answer
            ]);


            $this->check_pre_assessment_answer($learner_pre_assessment_output_id, $question_id, $answer);


            $firstTopic = DB::table('learner_syllabus_progress')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->orderBy('learner_syllabus_progress_id', 'ASC')
            ->first();
            // dd($firstTopic);
            DB::table('learner_syllabus_progress')
            ->where('learner_syllabus_progress_id', $firstTopic->learner_syllabus_progress_id)
            ->update([
                'STATUS' => "NOT YET STARTED"
            ]);


            // if($firstTopic->category === 'LESSON') {

            // } else if ($firstTopic->category ==='ACTIVITY') {

            // } else {

            // };

            // Return the counts in the response
            $data = [
            'message' => 'Learner Quiz Output submitted successfully',
            ];


            return response()->json($data);

            } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
        
                return response()->json(['errors' => $errors], 422);
            }
        }
    }
    

    public function check_pre_assessment_answer($learner_pre_assessment_output_id, $question_id, $answer) {
        try {
            // If $answer is null, set isCorrect to 0
            $answerValue = $answer !== null
                ? DB::table('question_answer')
                    ->select('isCorrect')
                    ->where('question_id', $question_id)
                    ->where('answer', $answer)
                    ->first()
                : (object) ['isCorrect' => 0];

                $isCorrect = $answerValue !== null ? $answerValue->isCorrect : 0;
    
                DB::table('learner_pre_assessment_output')
                ->where('learner_pre_assessment_output_id', $learner_pre_assessment_output_id)
                ->where('question_id', $question_id)
                ->update([
                    'isCorrect' => $isCorrect
                ]);
    
            // Return the correctness status
            return $answerValue !== null ? $answerValue->isCorrect : 0;

    
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    

    public function score_pre_assessment (Course $course, LearnerCourse $learner_course, Request $request) {
        
        try {
            $learner_pre_assessment_output_id = $request->input('learner_pre_assessment_output_id');
            $question_id = $request->input('question_id');

            // total items of the quiz
            $totalCount = DB::table('learner_pre_assessment_output')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->count();

            // score of the learner
            $scoreCount = DB::table('learner_pre_assessment_output')
            ->where('isCorrect', 1)
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->count();
            
            $scorePercentage = ($scoreCount / $totalCount) * 100;

            $now = Carbon::now();
            $timestampString = $now->toDateTimeString();
            // update the score and status
            DB::table('learner_pre_assessment_progress')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->update([
                'status' => "COMPLETED",
                'score' => $scoreCount,
                'remarks' => $scorePercentage >= 90 ? 'Excellent' : ($scorePercentage >= 80 ? 'Very Good' : ($scorePercentage >= 70 ? 'Good' : ($scorePercentage >= 60 ? 'Satisfactory' : 'Needs Improvement'))),
                'finish_period' => $timestampString,

            ]);

            $reportController = new PDFGenerationController();

            $reportController->courseGradeSheet($course->course_id);
            $reportController->learnerCourseGradeSheet($learner_course->learner_id, $course->course_id, $learner_course->learner_course_id);
            $reportController->learnerPreAssessmentOutput($learner_course->learner_id, $course->course_id, $learner_course->learner_course_id);
            
            
          $this->overallGrade($course, $learner_course);
            
            session()->flash('message', 'Learner Pre Assessment Scored successfully');

            $data = [
                'message' => 'Learner Pre Assessment Scored successfully',
                'redirect_url' => "/learner/course/content/$course->course_id/$learner_course->learner_course_id/pre_assessment",
                ];
    
    
                return response()->json($data);
    

        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }   

    
    public function view_output_pre_assessment(Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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
        'questions.question_id',
        'learner_pre_assessment_output.answer',
        'learner_pre_assessment_output.isCorrect'
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

                // dd($data);

                return view('learner_course.coursePreAssessmentOutput', compact('learner'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    public function view_output_pre_assessment_json(Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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
        DB::raw('(SELECT JSON_ARRAYAGG(answer) FROM question_answer WHERE question_answer.question_id = questions.question_id AND question_answer.isCorrect = 1) as correct_answer')
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
        'learner_pre_assessment_output.answer',
        'learner_pre_assessment_output.isCorrect',
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
            return redirect('/learner');
        }
    }

    public function view_lesson(Course $course, LearnerCourse $learner_course, Syllabus $syllabus) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {

                $preAssessmentData = DB::table('learner_pre_assessment_progress')
                ->select(
                    'learner_pre_assessment_progress_id',
                    'status'
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->first();

                if($preAssessmentData->status == 'NOT YET STARTED') {
                    session()->flash('message', 'Please Accomplish the Pre Assessment first');
                    return back()->withInput()->withErrors('Please Accomplish the Pre Assessment first');

                }

                $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress.learner_syllabus_progress_id',
                    'learner_syllabus_progress.learner_course_id',
                    'learner_syllabus_progress.learner_id',
                    'learner_syllabus_progress.course_id',
                    'learner_syllabus_progress.syllabus_id',
                    'learner_syllabus_progress.category',
                    'learner_syllabus_progress.status', 
                    'course.course_name',
                    
                    'lessons.lesson_id',
                    'lessons.lesson_title',
                    'lessons.picture',
                )
                ->join('lessons', 'learner_syllabus_progress.syllabus_id', '=', 'lessons.syllabus_id')
                ->join('course','learner_syllabus_progress.course_id','=','course.course_id')
                ->where('learner_syllabus_progress.course_id', $course->course_id)
                ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                ->first();

                // dd($learnerSyllabusProgressData);

                $learnerLessonProgressData = DB::table('learner_lesson_progress')
                ->select(
                    'learner_lesson_progress.learner_lesson_progress_id',
                    'learner_lesson_progress.learner_course_id',
                    'learner_lesson_progress.syllabus_id',
                    'learner_lesson_progress.lesson_id',

                    'lesson_content.lesson_content_id',
                    'lesson_content.lesson_content_title',
                    'lesson_content.lesson_content',
                    'lesson_content.lesson_content_order',
                    'lesson_content.picture',
                    'lesson_content.video_url',
                )
                ->join('lesson_content', 'learner_lesson_progress.lesson_id', '=', 'lesson_content.lesson_id')
                ->where('learner_lesson_progress.course_id', $course->course_id)
                ->where('learner_lesson_progress.syllabus_id', $syllabus->syllabus_id)
                ->where('learner_lesson_progress.learner_course_id' , $learnerSyllabusProgressData->learner_course_id)
                ->orderBy('lesson_content.lesson_content_order', 'ASC')
                ->get();

                if($learnerSyllabusProgressData->status !== "COMPLETED" && $learnerSyllabusProgressData->status !== "IN PROGRESS") {
                    DB::table('learner_syllabus_progress')
                    ->where('learner_course_id', $learnerSyllabusProgressData->learner_course_id)
                    ->where('syllabus_id' , $syllabus->syllabus_id)
                    ->update(['status' => 'IN PROGRESS']);
                    // ->first();
                    // dd($a);
                    
                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();

                    DB::table('learner_lesson_progress')
                    ->where('lesson_id', $learnerSyllabusProgressData->lesson_id)
                    ->where('learner_course_id', $learnerSyllabusProgressData->learner_course_id)
                    ->update([
                        'status' => 'IN PROGRESS',
                        'start_period' => $timestampString,
                    ]);
                    // ->first();
                    // dd($b);
    
                    // dd($learnerLessonProgressData);
                }
                
                
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
        return view('learner_course.courseLesson', compact('learner'))
        ->with([
            'title' => 'Course Lesson',
            'scripts' => ['/L_course_lesson.js'],
            'syllabus' => $learnerSyllabusProgressData,
            'lessons' => $learnerLessonProgressData,
            'mainBackgroundCol' => '#00693e',
            'darkenedColor' => '#00693e',
        ]);
    }

    public function finish_lesson(Course $course, LearnerCourse $learner_course, Syllabus $syllabus) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
                $currentLessonStatus = DB::table('learner_lesson_progress')
                ->select('status', 'learner_lesson_progress_id')
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->first();
            
    
                // Check if the current lesson is not already completed
                if ($currentLessonStatus->status !== 'COMPLETED') {
                    // Update the status of the current lesson to 'COMPLETED'
                    DB::table('learner_syllabus_progress')
                        ->where('learner_course_id' , $learner_course->learner_course_id)
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id' , $syllabus->syllabus_id)
                        ->update(['status' => 'COMPLETED']);
    
                        $now = Carbon::now();
                        $timestampString = $now->toDateTimeString();
                    
                    // Update the status of the current lesson to 'COMPLETED'
                    DB::table('learner_lesson_progress')
                        ->where('learner_course_id' , $learner_course->learner_course_id)
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id' , $syllabus->syllabus_id)
                        ->update([
                            'status' => 'COMPLETED',
                            'finish_period' => $timestampString,
                        ]);

                        $learnerSyllabusProgress = DB::table('learner_syllabus_progress') 
                            ->select(
                                'learner_syllabus_progress_id',
                                'learner_course_id',
                                'course_id',
                                'syllabus_id',
                                'status',
                            )
                            ->where('course_id', $course->course_id)
                            ->where('syllabus_id', $syllabus->syllabus_id)
                            ->where('learner_course_id', $learner_course->learner_course_id)
                            ->first();
                        
                    
                    // Find the next lesson that is still 'LOCKED' and update its status to 'NOT YET STARTED'
                    $nextSyllabusProgress = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress_id', 
                        'syllabus_id', 
                        'category', 
                        'status',
                        )
                    ->where('learner_syllabus_progress_id', '>', $learnerSyllabusProgress->learner_syllabus_progress_id)
                    ->orderBy('learner_syllabus_progress_id', 'ASC')
                    ->limit(1)
                    ->first();
    
                    if($nextSyllabusProgress) {
                        DB::table('learner_syllabus_progress')
                        ->where('learner_syllabus_progress_id', '>', $learnerSyllabusProgress->learner_syllabus_progress_id)
                        ->orderBy('learner_syllabus_progress_id', 'ASC')
                        ->limit(1)
                        ->update(['status' => 'NOT YET STARTED']);
                    } else {
                        DB::table('learner_post_assessment_progress')
                        ->where('learner_course_id', $learnerSyllabusProgress->learner_course_id)
                        ->where('course_id', $learnerSyllabusProgress->course_id)
                        ->update(['status' => 'NOT YET STARTED']);
                        
                        session()->flash('message', "You have finished all of the topics! \n Be ready for the Post Assessment to finish this course!");
                    }
                }
    
                session()->flash('message', 'Lesson Completed Successfully');
    
                $response = [
                    'message' => 'Lesson Completed successfully',
                    'redirect_url' => "/learner/course/manage/$course->course_id/overview",
                    'course_id' => $course->course_id,
                ];
    
                return response()->json($response);
    
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
    
                return response()->json(['errors' => $errors], 422);
            }
        } else {
            return redirect('/learner');
        }
    }
    

    public function view_activity(Course $course, LearnerCourse $learner_course, Syllabus $syllabus) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
        

                $preAssessmentData = DB::table('learner_pre_assessment_progress')
                ->select(
                    'learner_pre_assessment_progress_id',
                    'status'
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->first();

                if($preAssessmentData->status == 'NOT YET STARTED') {
                    session()->flash('message', 'Please Accomplish the Pre Assessment first');
                    return back()->withInput()->withErrors('Please Accomplish the Pre Assessment first');

                }
                
                $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress.learner_syllabus_progress_id',
                    'learner_syllabus_progress.learner_course_id',
                    'learner_syllabus_progress.learner_id',
                    'learner_syllabus_progress.course_id',
                    'learner_syllabus_progress.syllabus_id',
                    'learner_syllabus_progress.category',
                    'learner_syllabus_progress.status', 
                    'course.course_name',
                    
                    'activities.activity_id',
                    'activities.activity_title',
                )
                ->join('activities', 'learner_syllabus_progress.syllabus_id', '=', 'activities.syllabus_id')
                ->join('course','learner_syllabus_progress.course_id','=','course.course_id')
                ->where('learner_syllabus_progress.course_id', $course->course_id)
                ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                ->first();

           

                $learnerActivityProgressData = DB::table('learner_activity_progress')
                ->select(
                    'learner_activity_progress.learner_activity_progress_id',
                    'learner_activity_progress.learner_course_id',
                    'learner_activity_progress.learner_id',
                    'learner_activity_progress.course_id',
                    'learner_activity_progress.syllabus_id',
                    'learner_activity_progress.activity_id',
                    'learner_activity_progress.status',

                    'activity_content.activity_content_id',
                    'activity_content.activity_instructions',
                    'activity_content.total_score',
                )
                ->join('activity_content', 'learner_activity_progress.activity_id', '=', 'activity_content.activity_id')
                // ->join('activity_content_criteria', 'activity_content.activity_content_id', '=', 'activity_content_criteria.activity_content_id')
                ->where('learner_activity_progress.course_id', $course->course_id)
                ->where('learner_activity_progress.syllabus_id', $syllabus->syllabus_id)
                ->where('learner_activity_progress.learner_course_id' , $learnerSyllabusProgressData->learner_course_id)
                ->first();

                // dd($learnerActivity  rogressData);

                $activityContentCriteriaData = DB::table('activity_content_criteria')
                ->select(
                    'activity_content_criteria_id',
                    'activity_content_id',
                    'criteria_title',
                    'score'
                )
                ->where('activity_content_id', $learnerActivityProgressData->activity_content_id)
                ->get();
                $totalScores = 0;
                // foreach ($activityContentCriteriaData as $criteria) {
                //     $totalScore += $criteria->score;
                // }


                // if ($learnerActivityProgressData->status === "COMPLETED" || $learnerActivityProgressData->status === "IN PROGRESS") {
                    $activityOutputData1st = DB::table('learner_activity_output')
                        ->select(
                            'learner_activity_output_id',
                            'learner_course_id',
                            'syllabus_id',
                            'activity_id',
                            'activity_content_id',
                            'course_id',
                            'answer',
                            'attempt',
                            'mark',
                            'total_score',
                            'remarks',
                            'created_at',
                        )
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id', $syllabus->syllabus_id)
                        ->where('learner_course_id', $learnerSyllabusProgressData->learner_course_id)
                        ->first();

                        $activityOutputData = DB::table('learner_activity_output')
                        ->select(
                            'learner_activity_output_id',
                            'learner_course_id',
                            'syllabus_id',
                            'activity_id',
                            'activity_content_id',
                            'course_id',
                            'answer',
                            'attempt',
                            'mark',
                            'total_score',
                            'remarks',
                            'updated_at',
                        )
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id', $syllabus->syllabus_id)
                        ->where('learner_course_id', $learnerSyllabusProgressData->learner_course_id)
                        ->get();

                    //    dd($activityOutputData);
                        $activityScoreData = [];
                    if(!empty($activityOutputData1st)) {
                        $activityScoreData = DB::table('learner_activity_criteria_score')
                        ->select(
                            'learner_activity_criteria_score_id',
                            'learner_activity_output_id',
                            'activity_content_criteria_id',
                            'activity_content_id',
                            'attempt',
                            'score'
                        )
                        ->where('learner_activity_output_id', $activityOutputData1st->learner_activity_output_id)
                        ->where('activity_content_id', $activityOutputData1st->activity_content_id)
                        ->orderBy('learner_activity_criteria_score_id', 'ASC')
                        ->get();

                       
                    }
                    


                    foreach ($activityContentCriteriaData as $score) {
                        $totalScores += $score->score;
                    }
                
                    $data = [
                        'title' => 'Course Lesson',
                        'scripts' => ['/L_course_activity.js'],
                        'syllabus' => $learnerSyllabusProgressData,
                        'activity' => $learnerActivityProgressData,
                        'activityCriteria' => $activityContentCriteriaData,
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'activityOutput' => $activityOutputData,
                        'activityScore' => $activityScoreData,
                        'totalScores' => $totalScores,
                    ];
//
                    //  dd($data);
                // } else {
                //     $data = [
                //         'title' => 'Course Lesson',
                //         'scripts' => ['/L_course_activity.js'],
                //         'syllabus' => $learnerSyllabusProgressData,
                //         'activity' => $learnerActivityProgressData,
                //         'activityCriteria' => $activityContentCriteriaData,
                //         'mainBackgroundCol' => '#00693e',
                //         'darkenedColor' => '#00693e',
                //         'activityOutput' => null,
                //         'activityScore' => null,
                //         'totalScores' => $totalScores,
                //     ];
                // }

                // dd($data);


            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }


        return view('learner_course.courseActivity', compact('learner'))
        ->with($data);
    }

    public function answer_activity(Course $course, LearnerCourse $learner_course, Syllabus $syllabus, $attempt) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {

                
                $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress.learner_syllabus_progress_id',
                    'learner_syllabus_progress.learner_course_id',
                    'learner_syllabus_progress.learner_id',
                    'learner_syllabus_progress.course_id',
                    'learner_syllabus_progress.syllabus_id',
                    'learner_syllabus_progress.category',
                    'learner_syllabus_progress.status', 
                    'course.course_name',
                    
                    'activities.activity_id',
                    'activities.activity_title',
                )
                ->join('activities', 'learner_syllabus_progress.syllabus_id', '=', 'activities.syllabus_id')
                ->join('course','learner_syllabus_progress.course_id','=','course.course_id')
                ->where('learner_syllabus_progress.course_id', $course->course_id)
                ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                ->first();

           

                $learnerActivityProgressData = DB::table('learner_activity_progress')
                ->select(
                    'learner_activity_progress.learner_activity_progress_id',
                    'learner_activity_progress.learner_course_id',
                    'learner_activity_progress.learner_id',
                    'learner_activity_progress.course_id',
                    'learner_activity_progress.syllabus_id',
                    'learner_activity_progress.activity_id',
                    'learner_activity_progress.status',

                    'activity_content.activity_content_id',
                    'activity_content.activity_instructions',
                    'activity_content.total_score',
                )
                ->join('activity_content', 'learner_activity_progress.activity_id', '=', 'activity_content.activity_id')
                // ->join('activity_content_criteria', 'activity_content.activity_content_id', '=', 'activity_content_criteria.activity_content_id')
                ->where('learner_activity_progress.course_id', $course->course_id)
                ->where('learner_activity_progress.syllabus_id', $syllabus->syllabus_id)
                ->where('learner_activity_progress.learner_course_id' , $learnerSyllabusProgressData->learner_course_id)
                ->first();

                $now = Carbon::now();
                $timestampString = $now->toDateTimeString();

                DB::table('learner_activity_progress')
                ->where('learner_activity_progress.course_id', $course->course_id)
                ->where('learner_activity_progress.syllabus_id', $syllabus->syllabus_id)
                ->where('learner_activity_progress.learner_course_id' , $learnerSyllabusProgressData->learner_course_id)
                ->update([
                    'start_period' => $timestampString,
                ]);

                $activityContentCriteriaData = DB::table('activity_content_criteria')
                ->select(
                    'activity_content_criteria_id',
                    'activity_content_id',
                    'criteria_title',
                    'score'
                )
                ->where('activity_content_id', $learnerActivityProgressData->activity_content_id)
                ->get();

    

        

                
                if ($learnerActivityProgressData->status === "COMPLETED" || $learnerActivityProgressData->status === "IN PROGRESS") {
                    $activityOutputData = DB::table('learner_activity_output')
                        ->select(
                            'learner_activity_output_id',
                            'learner_course_id',
                            'syllabus_id',
                            'activity_id',
                            'activity_content_id',
                            'course_id',
                            'answer',
                            'total_score',
                            'remarks',
                            'attempt',
                            'updated_at',
                        )
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id', $syllabus->syllabus_id)
                        ->where('learner_course_id', $learnerSyllabusProgressData->learner_course_id)
                        ->where('attempt', $attempt)
                        ->first();

                       
                
                    $activityScoreData = DB::table('learner_activity_criteria_score')
                        ->select(
                            'learner_activity_criteria_score_id',
                            'learner_activity_output_id',
                            'activity_content_criteria_id',
                            'activity_content_id',
                            'score'
                        )
                        ->where('learner_activity_output_id', $activityOutputData->learner_activity_output_id)
                        ->where('activity_content_id', $activityOutputData->activity_content_id)
                        ->orderBy('learner_activity_criteria_score_id', 'ASC')
                        ->get();

                     
        
                
                    $data = [
                        'title' => 'Course Lesson',
                        'scripts' => ['/L_course_activity.js'],
                        'syllabus' => $learnerSyllabusProgressData,
                        'activity' => $learnerActivityProgressData,
                        'activityCriteria' => $activityContentCriteriaData,
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'activityOutput' => $activityOutputData,
                        'activityScore' => $activityScoreData,
                    ];

                    //  dd($data);
                } else {

                    $activityData =([
                        'learner_course_id' => $learner_course->learner_course_id,
                        'syllabus_id' => $syllabus->syllabus_id,
                        'activity_id' => $learnerActivityProgressData->activity_id,
                        'activity_content_id' => $learnerActivityProgressData->activity_content_id,
                        'course_id' => $course->course_id,
                        'attempt' => $attempt
                    ]);
    
                    LearnerActivityOutput::firstOrCreate($activityData);

                    $activityOutputData = DB::table('learner_activity_output')
                        ->select(
                            'learner_activity_output_id',
                            'learner_course_id',
                            'syllabus_id',
                            'activity_id',
                            'activity_content_id',
                            'course_id',
                            'answer',
                            'total_score',
                            'remarks',
                            'attempt',
                            'updated_at',
                        )
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id', $syllabus->syllabus_id)
                        ->where('learner_course_id', $learnerSyllabusProgressData->learner_course_id)
                        ->where('attempt', $attempt)
                        ->first();

                        // dd($activityOutputData);
                        $activityCriteria = DB::table('activity_content_criteria')
                        ->select(
                            'activity_content_criteria_id',
                            'activity_content_id',
                        )
                        ->where('activity_content_id', $activityOutputData->activity_content_id)
                        ->get();

                        foreach($activityCriteria as $criteria) {
                            $newRowData = ([
                                'learner_activity_output_id' => $activityOutputData->learner_activity_output_id,
                                'activity_content_criteria_id' => $criteria->activity_content_criteria_id,
                                'activity_content_id' =>$criteria->activity_content_id,
                            ]);
        
                            LearnerActivityCriteriaScore::firstOrCreate($newRowData);
                        };
                        

                        $activityScoreData = DB::table('learner_activity_criteria_score')
                        ->select(
                            'learner_activity_criteria_score_id',
                            'learner_activity_output_id',
                            'activity_content_criteria_id',
                            'activity_content_id',
                            'attempt',
                            'score'
                        )
                        ->where('learner_activity_output_id', $activityOutputData->learner_activity_output_id)
                        ->where('activity_content_id', $criteria->activity_content_id)
                        ->orderBy('learner_activity_criteria_score_id', 'ASC')
                        ->get();

                    $data = [
                        'title' => 'Course Lesson',
                        'scripts' => ['/L_course_activity.js'],
                        'syllabus' => $learnerSyllabusProgressData,
                        'activity' => $learnerActivityProgressData,
                        'activityCriteria' => $activityContentCriteriaData,
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'activityOutput' => $activityOutputData,
                        'activityScore' => $activityScoreData,
                    ];
                }


                
                // dd($data);


            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }


        return view('learner_course.courseActivityAnswer', compact('learner'))
        ->with($data);
    }

    public function submit_answer(Course $course, LearnerCourse $learner_course, Syllabus $syllabus,$attempt, Activities $activity, ActivityContents $activity_content , Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
                $activityData =([
                    // 'learner_course_id' => $learner_course->learner_course_id,
                    // 'syllabus_id' => $syllabus->syllabus_id,
                    // 'activity_id' => $activity->activity_id,
                    // 'activity_content_id' => $activity_content->activity_content_id,
                    // 'course_id' => $course->course_id,
                    'answer' => $request->answer,
                ]);

                DB::table('learner_activity_output')
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('activity_id', $activity->activity_id)
                ->where('activity_content_id', $activity_content->activity_content_id)
                ->where('course_id', $course->course_id)
                ->where('attempt', $attempt)
                ->update($activityData);

                $learnerActivityData = DB::table('learner_activity_output')
                ->select(
                    'learner_activity_output_id',
                    'learner_course_id',
                    'syllabus_id',
                    'activity_id',
                    'activity_content_id',
                    'course_id',
                    'answer',
                    'total_score'
                )
                ->orderBy('learner_activity_output_id', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->first();

                $activityCriteria = DB::table('activity_content_criteria')
                ->select(
                    'activity_content_criteria_id',
                    'activity_content_id',
                )
                ->where('activity_content_id', $activity_content->activity_content_id)
                ->get();

                // updating the status of the learner progress

                $now = Carbon::now();
                $timestampString = $now->toDateTimeString();

                DB::table('learner_activity_progress')
                ->where('learner_course_id' , $learner_course->learner_course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('course_id', $course->course_id)
                ->where('activity_id', $activity->activity_id)
                ->update([
                    'status' => 'COMPLETED',
                    'finish_period' => $timestampString,
                ]);

                DB::table('learner_syllabus_progress')
                ->where('learner_course_id' , $learner_course->learner_course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('course_id', $course->course_id)
                ->update(['status' => 'COMPLETED']);

                
                $learnerSyllabusProgress = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress_id', 
                    'syllabus_id', 
                    'category', 
                    'status',
                    )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->orderBy('learner_syllabus_progress_id', 'ASC')
                ->first();

            if ($learnerSyllabusProgress) {
                $nextSyllabusProgress = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress_id', 
                    'syllabus_id', 
                    'category', 
                    'status',
                    )
                ->where('learner_syllabus_progress_id', '>', $learnerSyllabusProgress->learner_syllabus_progress_id)
                ->orderBy('learner_syllabus_progress_id', 'ASC')
                ->limit(1)
                ->first();

                if($nextSyllabusProgress) {
                    DB::table('learner_syllabus_progress')
                    ->where('learner_syllabus_progress_id', '>', $learnerSyllabusProgress->learner_syllabus_progress_id)
                    ->orderBy('learner_syllabus_progress_id', 'ASC')
                    ->limit(1)
                    ->update(['status' => 'NOT YET STARTED']);
                } else {
                    DB::table('learner_post_assessment_progress')
                    ->where('learner_course_id', $learner_course->learner_course_id)
                    ->where('course_id', $course->course_id)
                    ->update(['status' => 'NOT YET STARTED']);
                    
                    session()->flash('message', "You have finished all of the topics! \n Be ready for the Post Assessment to finish this course!");
                }
            }



                // foreach($activityCriteria as $criteria) {
                //     $newRowData = ([
                //         'learner_activity_output_id' => $learnerActivityData->learner_activity_output_id,
                //         'activity_content_criteria_id' => $criteria->activity_content_criteria_id,
                //         'activity_content_id' =>$criteria->activity_content_id,
                //     ]);

                //     LearnerActivityCriteriaScore::create($newRowData);
                // };



                

                session()->flash('message', 'Activity Finished Successfully');
                return response()->json(['message' => 'Course updated successfully',
                 'redirect_url' => "/learner/course/content/$course->course_id/$learner_course->learner_course_id/activity/$syllabus->syllabus_id"]);
            

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
                    return response()->json(['errors' => $errors], 422);
                }
        } else {
            return redirect('/learner');
        }
    }


    public function view_quiz(Course $course, LearnerCourse $learner_course, Syllabus $syllabus) {

        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
                // if (!function_exists('getRandomColor')) {
                //     function getRandomColor() {
                //     return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                //     }
                // }
                
                // // Generate a random color for mainBackgroundCol
                // $mainBackgroundCol = getRandomColor();
    
                // // Darken the mainBackgroundCol
                // $mainColorRGB = sscanf($mainBackgroundCol, "#%02x%02x%02x");
                // $mainBackgroundCol = sprintf("#%02x%02x%02x", $mainColorRGB[0] * 0.6, $mainColorRGB[1] * 0.6, $mainColorRGB[2] * 0.6);
    
                // // Darken the mainBackgroundCol further for darkenedColor
                // $darkenedColor = sprintf("#%02x%02x%02x", $mainColorRGB[0] * 0.4, $mainColorRGB[1] * 0.4, $mainColorRGB[2] * 0.4);


                $preAssessmentData = DB::table('learner_pre_assessment_progress')
                ->select(
                    'learner_pre_assessment_progress_id',
                    'status'
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->first();

                if($preAssessmentData->status == 'NOT YET STARTED') {
                    session()->flash('message', 'Please Accomplish the Pre Assessment first');
                    return back()->withInput()->withErrors('Please Accomplish the Pre Assessment first');

                }


                $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                ->select(
                    'learner_syllabus_progress.learner_syllabus_progress_id',
                    'learner_syllabus_progress.learner_course_id',
                    'learner_syllabus_progress.learner_id',
                    'learner_syllabus_progress.course_id',
                    'learner_syllabus_progress.syllabus_id',
                    'learner_syllabus_progress.category',
                    'learner_syllabus_progress.status', 
                    'course.course_name',
                    
                    'quizzes.quiz_id',
                    'quizzes.quiz_title',
                    'quizzes.duration'
                )
                ->join('quizzes', 'learner_syllabus_progress.syllabus_id', '=', 'quizzes.syllabus_id')
                ->join('course','learner_syllabus_progress.course_id','=','course.course_id')

                ->where('learner_syllabus_progress.course_id', $course->course_id)
                ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                ->first();


                $quizReferenceData = DB::table('quiz_reference')
                ->select(
                    'quiz_reference.quiz_reference_id',
                    'quiz_reference.quiz_id',
                    'quiz_reference.course_id',
                    'quiz_reference.syllabus_id',
                    'syllabus.topic_title',
                )
                ->join('syllabus', 'quiz_reference.syllabus_id', '=', 'syllabus.syllabus_id' )
                ->where('quiz_reference.quiz_id', $learnerSyllabusProgressData->quiz_id)
                ->get();

                $learnerQuizProgressData = DB::table('learner_quiz_progress')
                ->select(
                    'learner_quiz_progress.learner_quiz_progress_id',
                    'learner_quiz_progress.learner_course_id',
                    'learner_quiz_progress.syllabus_id',
                    'learner_quiz_progress.quiz_id',
                    'learner_quiz_progress.course_id',
                    'learner_quiz_progress.status',
                    'learner_quiz_progress.attempt',
                    'learner_quiz_progress.max_attempt',
                    'learner_quiz_progress.score',
                    'learner_quiz_progress.remarks',
                    'learner_quiz_progress.updated_at',
                )
                ->where('quiz_id', $learnerSyllabusProgressData->quiz_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->get();

                $totalCount = DB::table('learner_quiz_output')
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('quiz_id', $learnerSyllabusProgressData->quiz_id)
                ->where('attempts', 1)
                ->count();


            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }

        $data = [
            'title' => 'Course Lesson',
            'scripts' => ['/L_course_quiz.js'],
            'mainBackgroundCol' => '#00693e',
            'darkenedColor' => '#00693e',
            'learnerSyllabusProgressData' => $learnerSyllabusProgressData,
            'learnerQuizProgressData' => $learnerQuizProgressData,
            'quizReferenceData' => $quizReferenceData,
            'totalQuestionCount' => $totalCount,
        ];

        // dd($data);

        return view('learner_course.courseQuiz', compact('learner'))
        ->with($data);

    }


    public function answer_quiz(Course $course, LearnerCourse $learner_course, Syllabus $syllabus, $attempt) {

        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
    
                $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress.learner_syllabus_progress_id',
                        'learner_syllabus_progress.learner_course_id',
                        'learner_syllabus_progress.learner_id',
                        'learner_syllabus_progress.course_id',
                        'learner_syllabus_progress.syllabus_id',
                        'learner_syllabus_progress.category',
                        'learner_syllabus_progress.status', 
                        'course.course_name',
                        'quizzes.quiz_id',
                        'quizzes.quiz_title',
                        'quizzes.duration',
                    )
                    ->join('quizzes', 'learner_syllabus_progress.syllabus_id', '=', 'quizzes.syllabus_id')
                    ->join('course','learner_syllabus_progress.course_id','=','course.course_id')
                    ->where('learner_syllabus_progress.course_id', $course->course_id)
                    ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                    ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                    ->first();

                    $quizReferenceData = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',
                        'syllabus.topic_title',
                    )
                    ->join('syllabus', 'quiz_reference.syllabus_id', '=', 'syllabus.syllabus_id' )
                    ->where('quiz_reference.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->get();


                    $learnerQuizProgressData = DB::table('learner_quiz_progress')
                    ->select(
                        'learner_quiz_progress.learner_quiz_progress_id',
                        'learner_quiz_progress.learner_course_id',
                        'learner_quiz_progress.syllabus_id',
                        'learner_quiz_progress.quiz_id',
                        'learner_quiz_progress.status',
                        'learner_quiz_progress.max_attempt',
                        'learner_quiz_progress.attempt',
                        'learner_quiz_progress.score',
                    )
                    ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_quiz_progress.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->orderBy('learner_quiz_progress.learner_quiz_progress_id', 'DESC')
                    ->first();

                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();

                    DB::table('learner_quiz_progress')
                    ->where('learner_quiz_progress_id', $learnerQuizProgressData->learner_quiz_progress_id)
                    ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_quiz_progress.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->update([
                        'start_period' => $timestampString,
                    ]);


                    if($learnerQuizProgressData->status === 'COMPLETED' || $learnerSyllabusProgressData === 'COMPLETED') {
                        session()->flash('message', 'Maximum number of Attempts taken.');
                        return redirect()->route('view_quiz', [
                            'course' => $learnerSyllabusProgressData->course_id,
                            'learner_course' => $learnerSyllabusProgressData->learner_course_id,
                            'syllabus' => $learnerSyllabusProgressData->syllabus_id,
                        ])->with('error', 'Maximum number of Attempts taken.');
                    } else {

                        $learnerQuizOutputData = DB::table('learner_quiz_output')
                        ->select(
                            'learner_quiz_output.learner_quiz_output_id',
                            'learner_quiz_output.quiz_id',
                            'learner_quiz_output.quiz_content_id',
                            'learner_quiz_output.attempts',
                            'learner_quiz_output.answer',
                            'learner_quiz_output.isCorrect',
                        )
                        ->where('learner_quiz_output.learner_course_id', $learner_course->learner_course_id)
                        ->where('learner_quiz_output.course_id', $course->course_id)
                        ->where('learner_quiz_output.syllabus_id', $syllabus->syllabus_id)
                        ->where('learner_quiz_output.quiz_id', $learnerSyllabusProgressData->quiz_id)
                        ->where('learner_quiz_output.attempts', '=', $learnerQuizProgressData->attempt)
                        ->get();


                        if($learnerQuizOutputData->isEmpty()) {
                            $quizContentData = DB::table('quiz_content')
                                ->select(
                                    'quiz_content.quiz_content_id',
                                    'quiz_content.quiz_id',
                                    'quiz_content.course_id',
                                    'quiz_content.syllabus_id',
                                    'quiz_content.question_id',
                                )
                                ->where('quiz_content.quiz_id', $learnerSyllabusProgressData->quiz_id)
                                ->where('quiz_content.course_id', $learnerSyllabusProgressData->course_id)
                                ->where('quiz_content.syllabus_id', $learnerSyllabusProgressData->syllabus_id)
                                ->inRandomOrder()
                                ->get();

                                foreach($quizContentData as $question) {
                                    $questionRowData = [
                                        'learner_course_id' => $learner_course->learner_course_id,
                                        'learner_id' => $learnerSyllabusProgressData->learner_id,
                                        'course_id' => $question->course_id,
                                        'syllabus_id' => $question->syllabus_id,
                                        'quiz_id' => $question->quiz_id,
                                        'quiz_content_id' => $question->quiz_content_id,
                                        'attempts' => $learnerQuizProgressData->attempt,
                                    ];

                                    
                                    LearnerQuizOutputs::firstOrCreate($questionRowData);
                        }
$learnerQuizData = DB::table('learner_quiz_output')
    ->select(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id',
        'learner_quiz_output.quiz_content_id',
        'quiz_content.course_id',
        'quiz_content.question_id',
        'questions.syllabus_id',
        'questions.question',
        'questions.category',
        DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers'),
    )
    ->join('quiz_content', 'learner_quiz_output.quiz_content_id', '=', 'quiz_content.quiz_content_id')
    ->join('questions', 'quiz_content.question_id', '=', 'questions.question_id')
    ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
    ->where('learner_quiz_output.attempts', $learnerQuizProgressData->attempt)
    ->where('quiz_content.quiz_id', $learnerSyllabusProgressData->quiz_id)
    ->where('quiz_content.course_id', $learnerSyllabusProgressData->course_id)
    ->where('quiz_content.syllabus_id', $learnerSyllabusProgressData->syllabus_id)
    ->groupBy(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id', // Include this line
        'learner_quiz_output.quiz_content_id',
        'quiz_content.course_id',
        'quiz_content.question_id',
        'questions.syllabus_id',
        'questions.question',
        'questions.category'
    )
    ->get();

                        
                    } else {
$learnerQuizData = DB::table('learner_quiz_output')
    ->select(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id',
        'learner_quiz_output.quiz_content_id',
        'quiz_content.course_id',
        'quiz_content.question_id',
        'questions.syllabus_id',
        'questions.question',
        'questions.category',
        DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers'),
    )
    ->join('quiz_content', 'learner_quiz_output.quiz_content_id', '=', 'quiz_content.quiz_content_id')
    ->join('questions', 'quiz_content.question_id', '=', 'questions.question_id')
    ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
    ->where('learner_quiz_output.attempts', $learnerQuizProgressData->attempt)
    ->where('quiz_content.quiz_id', $learnerSyllabusProgressData->quiz_id)
    ->where('quiz_content.course_id', $learnerSyllabusProgressData->course_id)
    ->where('quiz_content.syllabus_id', $learnerSyllabusProgressData->syllabus_id)
    ->groupBy(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id', // Include this line
        'learner_quiz_output.quiz_content_id',
        'quiz_content.course_id',
        'quiz_content.question_id',
        'questions.syllabus_id',
        'questions.question',
        'questions.category'
    )
    ->get();

                    }
                }
    
                

                    $data = [
                        'title' => 'Quiz',
                        'scripts' => ['/L_course_quiz.js'],
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'learnerSyllabusProgressData' => $learnerSyllabusProgressData,
                        'quizReferences' => $quizReferenceData,
                        'quizProgressData' => $learnerQuizProgressData,
                        'quizLearnerData' => $learnerQuizData,
                    ];

                    // dd($data);
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    
        return view('learner_course.courseQuizAnswer', compact('learner'))
            ->with($data);
    }


    public function answer_quiz_json (Course $course, LearnerCourse $learner_course, Syllabus $syllabus , $attempt) {


        try {

            $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress.learner_syllabus_progress_id',
                        'learner_syllabus_progress.learner_course_id',
                        'learner_syllabus_progress.learner_id',
                        'learner_syllabus_progress.course_id',
                        'learner_syllabus_progress.syllabus_id',
                        'learner_syllabus_progress.category',
                        'learner_syllabus_progress.status', 
                        'course.course_name',
                        'quizzes.quiz_id',
                        'quizzes.quiz_title',
                        'quizzes.duration',
                    )
                    ->join('quizzes', 'learner_syllabus_progress.syllabus_id', '=', 'quizzes.syllabus_id')
                    ->join('course','learner_syllabus_progress.course_id','=','course.course_id')
                    ->where('learner_syllabus_progress.course_id', $course->course_id)
                    ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                    ->first();

                
                    $quizReferenceData = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',
                        'syllabus.topic_title',
                    )
                    ->join('syllabus', 'quiz_reference.syllabus_id', '=', 'syllabus.syllabus_id' )
                    ->where('quiz_reference.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->get();


                    $learnerQuizProgressData = DB::table('learner_quiz_progress')
                    ->select(
                        'learner_quiz_progress.learner_quiz_progress_id',
                        'learner_quiz_progress.learner_course_id',
                        'learner_quiz_progress.syllabus_id',
                        'learner_quiz_progress.quiz_id',
                        'learner_quiz_progress.status',
                        'learner_quiz_progress.max_attempt',
                        'learner_quiz_progress.attempt',
                        'learner_quiz_progress.score',
                    )
                    ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                    ->where('learner_quiz_progress.attempt', $attempt)
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_quiz_progress.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->orderBy('learner_quiz_progress.learner_quiz_progress_id', 'DESC')
                    ->first();

// $learnerQuizData = DB::table('learner_quiz_output')
//    ->select(
//        'learner_quiz_output.learner_quiz_output_id',
//        'learner_quiz_output.quiz_id',
//        'learner_quiz_output.quiz_content_id',
//        'quiz_content.course_id',
//        'quiz_content.question_id',
//        'questions.syllabus_id',
//        'questions.question',
//        'questions.category',
//        DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers'),
//    )
//    ->join('quiz_content', 'learner_quiz_output.quiz_content_id', '=', 'quiz_content.quiz_content_id')
//    ->join('questions', 'quiz_content.question_id', '=', 'questions.question_id')
//    ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
//    ->where('learner_quiz_output.attempts', $learnerQuizProgressData->attempt)
//    ->where('quiz_content.quiz_id', $learnerSyllabusProgressData->quiz_id)
//    ->where('quiz_content.course_id', $learnerSyllabusProgressData->course_id)
//    ->where('quiz_content.syllabus_id', $learnerSyllabusProgressData->syllabus_id)
//    ->groupBy(
//        'learner_quiz_output.learner_quiz_output_id',
//        'learner_quiz_output.quiz_id',
//        'learner_quiz_output.quiz_content_id',
//        'quiz_content.course_id',
//        'quiz_content.question_id',
//        'questions.syllabus_id',
//        'questions.question',
//        'questions.category'
//    )
//    ->get();
    
    $learnerQuizData = DB::table('learner_quiz_output')
    ->select(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id',
        'learner_quiz_output.quiz_content_id',
        'learner_quiz_output.attempts',
        'quiz_content.course_id',
        'quiz_content.question_id',
        'questions.syllabus_id',
        'questions.question',
        'questions.category',
        DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers'),
 
    )
    ->join('quiz_content', 'learner_quiz_output.quiz_content_id', '=', 'quiz_content.quiz_content_id')
    ->join('questions', 'quiz_content.question_id', '=', 'questions.question_id')
    ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
    ->where('learner_quiz_output.attempts', $attempt)
    ->where('learner_quiz_output.learner_course_id', $learner_course->learner_course_id)
    ->where('quiz_content.quiz_id', $learnerSyllabusProgressData->quiz_id)
    ->where('quiz_content.course_id', $learnerSyllabusProgressData->course_id)
    ->where('quiz_content.syllabus_id', $learnerSyllabusProgressData->syllabus_id)
    ->groupBy(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id',
        'learner_quiz_output.quiz_content_id',
        'learner_quiz_output.attempts',
        'quiz_content.course_id',
        'quiz_content.question_id',
        'questions.syllabus_id',
        'questions.question',
        'questions.category',
    )
    ->get();
    
    
    
                    $data = [
                        'learnerSyllabusProgressData' => $learnerSyllabusProgressData,
                        'quizReferences' => $quizReferenceData,
                        'quizProgressData' => $learnerQuizProgressData,
                        'quizLearnerData' => $learnerQuizData,
                    ];


                    return response()->json($data);

        } catch (ValidationException $e) {
                $errors = $e->validator->errors();
        
                return response()->json(['errors' => $errors], 422);
            }
    }


    public function submit_quiz (Course $course, LearnerCourse $learner_course, Syllabus $syllabus, $attempt , Request $request) {

        try {
            
            $learner_quiz_output_id = $request->input('learner_quiz_output_id');
            $quiz_id = $request->input('quiz_id');
            $quiz_content_id = $request->input('quiz_content_id');
            $question_id = $request->input('question_id');

            $answer = $request->input('answer');

            DB::table('learner_quiz_output')
            ->where('learner_quiz_output_id', $learner_quiz_output_id)
            ->where('quiz_id', $quiz_id)
            ->where('quiz_content_id', $quiz_content_id)
            ->where('attempts', $attempt)
            ->update([
                'answer' => $answer
            ]);


            $this->check_answer($learner_quiz_output_id, $quiz_id, $quiz_content_id, $question_id, $answer, $attempt);



            // Return the counts in the response
            $data = [
            'message' => 'Learner Quiz Output submitted successfully',
            ];


            return response()->json($data);

        } catch (ValidationException $e) {
                $errors = $e->validator->errors();
        
                return response()->json(['errors' => $errors], 422);
            }

    }

    public function check_answer($learner_quiz_output_id, $quiz_id, $quiz_content_id, $question_id, $answer, $attempt) {
        try {
            // If $answer is null, set isCorrect to 0
            $answerValue = $answer !== null
                ? DB::table('question_answer')
                    ->select('isCorrect')
                    ->where('question_id', $question_id)
                    ->where('answer', $answer)
                    ->first()
                : (object) ['isCorrect' => 0];

                $isCorrect = $answerValue !== null ? $answerValue->isCorrect : 0;
    
            DB::table('learner_quiz_output')
                ->where('learner_quiz_output_id', $learner_quiz_output_id)
                ->where('quiz_id', $quiz_id)
                ->where('quiz_content_id', $quiz_content_id)
                ->where('answer', $answer)
                ->where('attempts', $attempt)
                ->update([
                    'isCorrect' => $isCorrect
                ]);
    
            // Return the correctness status
            return $answerValue !== null ? $answerValue->isCorrect : 0;

    
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    

    public function compute_score (Course $course, LearnerCourse $learner_course, Syllabus $syllabus, $attempt, Request $request) {
        
        try {
            $learner_quiz_output_id = $request->input('learner_quiz_output_id');
            $quiz_id = $request->input('quiz_id');
            $quiz_content_id = $request->input('quiz_content_id');
            $question_id = $request->input('question_id');

            //fetch the learner_course_id, quiz_id, and attemp number for count later
            $learnerQuizOutputData = DB::table('learner_quiz_output')
            ->select(
                'learner_quiz_output_id',
                'learner_course_id',
                'course_id',
                'syllabus_id',
                'quiz_id',
                'quiz_content_id',
                'attempts',
            )
            ->where('learner_quiz_output_id', $learner_quiz_output_id)
            ->where('attempts', $attempt)
            ->first();

            // update syllabus_progress
            DB::table('learner_syllabus_progress')
            ->where('course_id', $learnerQuizOutputData->course_id)
            ->where('syllabus_id', $learnerQuizOutputData->syllabus_id)
            ->where('learner_course_id', $learnerQuizOutputData->learner_course_id)
            ->update([
                'status' => 'IN PROGRESS'
            ]);


            // total items of the quiz
            $totalCount = DB::table('learner_quiz_output')
            ->where('quiz_id', $quiz_id)
            ->where('course_id', $learnerQuizOutputData->course_id)
            ->where('syllabus_id', $learnerQuizOutputData->syllabus_id)
            ->where('learner_course_id', $learnerQuizOutputData->learner_course_id)
            ->where('attempts', $learnerQuizOutputData->attempts)
            ->count();

            // score of the learner
            $scoreCount = DB::table('learner_quiz_output')
            ->where('isCorrect', 1)
            ->where('quiz_id', $quiz_id)
            ->where('course_id', $learnerQuizOutputData->course_id)
            ->where('syllabus_id', $learnerQuizOutputData->syllabus_id)
            ->where('learner_course_id', $learnerQuizOutputData->learner_course_id)
            ->where('attempts', $learnerQuizOutputData->attempts)
            ->count();
            
            $now = Carbon::now();
            $timestampString = $now->toDateTimeString();
            // update the score and status
            DB::table('learner_quiz_progress')
            ->where('quiz_id', $quiz_id)
            ->where('course_id', $learnerQuizOutputData->course_id)
            ->where('syllabus_id', $learnerQuizOutputData->syllabus_id)
            ->where('learner_course_id', $learnerQuizOutputData->learner_course_id)
            ->where('attempt', $learnerQuizOutputData->attempts)
            ->update([
                'score' => $scoreCount,
                'remarks' => ($scoreCount >= $totalCount / 2) ? 'PASS' : 'FAIL',
                'finish_period' => $timestampString,
            ]);

            $learnerQuizProgress = DB::table('learner_quiz_progress')
            ->select(
                'learner_quiz_progress_id',
                'learner_course_id',
                'course_id',
                'syllabus_id',
                'quiz_id',
                'status',
                'attempt',
                'score',
                'remarks',
            )
            ->where('quiz_id', $quiz_id)
            ->where('course_id', $learnerQuizOutputData->course_id)
            ->where('syllabus_id', $learnerQuizOutputData->syllabus_id)
            ->where('learner_course_id', $learnerQuizOutputData->learner_course_id)
            ->where('attempt', $learnerQuizOutputData->attempts)
            ->first();


            if($learnerQuizProgress !== 'COMPLETED') {

                       // update the score and status
                   DB::table('learner_quiz_progress')
                   ->where('quiz_id', $quiz_id)
                   ->where('course_id', $learnerQuizOutputData->course_id)
                   ->where('syllabus_id', $learnerQuizOutputData->syllabus_id)
                   ->where('learner_course_id', $learnerQuizOutputData->learner_course_id)
                   ->where('attempt', $learnerQuizOutputData->attempts)
                   ->update([
                       'status' => 'COMPLETED',
                   ]);

                if($scoreCount >= $totalCount / 2) {

                    // update syllabus_progress
                    DB::table('learner_syllabus_progress')
                    ->where('course_id', $learnerQuizOutputData->course_id)
                    ->where('syllabus_id', $learnerQuizOutputData->syllabus_id)
                    ->where('learner_course_id', $learnerQuizOutputData->learner_course_id)
                    ->update([
                        'status' => 'COMPLETED'
                    ]);


                    // // Find the next lesson that is still 'LOCKED' and update its status to 'NOT YET STARTED'
                    // $nextLesson = DB::table('learner_syllabus_progress')
                    // ->where('learner_quiz_progress_id', '>', $learnerQuizProgress->learner_quiz_progress_id)
                    // ->where('learner_course_id' , $learnerQuizOutputData->learner_course_id)
                    // ->where('course_id', $learnerQuizOutputData->course_id)
                    // ->orderBy('learner_syllabus_progress_id', 'ASC')
                    // ->limit(1)
                    // ->first();

                    // if ($nextLesson) {
                    //     DB::table('learner_syllabus_progress')
                    //         ->where('learner_syllabus_progress_id', $nextLesson->learner_syllabus_progress_id)
                    //         ->update(['status' => 'NOT YET STARTED']);
                    // } else {
                    //     DB::table('learner_post_assessment_progress')
                    //     ->where('learner_course_id', $learner_course->learner_course_id)
                    //     ->where('course_id', $course->course_id)
                    //     ->update(['status' => 'NOT YET STARTED']);
                    //     session()->flash('message', "Great! You have finished all the Topics. \n Be ready for the Post Assessment for final grading!");
                    // }

                    $learnerSyllabusProgress = DB::table('learner_syllabus_progress') 
                    ->select(
                        'learner_syllabus_progress_id',
                        'learner_course_id',
                        'course_id',
                        'syllabus_id',
                        'status',
                    )
                    ->where('course_id', $course->course_id)
                    ->where('syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_course_id', $learner_course->learner_course_id)
                    ->first();
                
            
                    // Find the next lesson that is still 'LOCKED' and update its status to 'NOT YET STARTED'
                    $nextSyllabusProgress = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress_id', 
                        'syllabus_id', 
                        'category', 
                        'status',
                        )
                    ->where('learner_syllabus_progress_id', '>', $learnerSyllabusProgress->learner_syllabus_progress_id)
                    ->orderBy('learner_syllabus_progress_id', 'ASC')
                    ->limit(1)
                    ->first();

                    if($nextSyllabusProgress) {
                        DB::table('learner_syllabus_progress')
                        ->where('learner_syllabus_progress_id', '>', $learnerSyllabusProgress->learner_syllabus_progress_id)
                        ->orderBy('learner_syllabus_progress_id', 'ASC')
                        ->limit(1)
                        ->update(['status' => 'NOT YET STARTED']);
                    } else {
                        DB::table('learner_post_assessment_progress')
                        ->where('learner_course_id', $learnerSyllabusProgress->learner_course_id)
                        ->where('course_id', $learnerSyllabusProgress->course_id)
                        ->update(['status' => 'NOT YET STARTED']);
                        
                        session()->flash('message', "You have finished all of the topics! \n Be ready for the Post Assessment to finish this course!");
                    }
                }

            }
            
            
          $this->overallGrade($course, $learner_course);

            $reportController = new PDFGenerationController();

            $reportController->courseGradeSheet($learnerQuizProgress->course_id);

            $reportController->learnerCourseGradeSheet($learner_course->learner_id, $course->course_id, $learner_course->learner_course_id);

            $reportController->learnerQuizOutput($learner_course->learner_id, $course->course_id, $learner_course->learner_course_id, $syllabus->syllabus_id, $attempt);


            $data = [
                'message' => 'Learner Quiz Scored successfully',
                ];
    
    
                return response()->json($data);
    

        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }   


    public function view_output (Course $course, LearnerCourse $learner_course, Syllabus $syllabus, $attempt) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {

                $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress.learner_syllabus_progress_id',
                        'learner_syllabus_progress.learner_course_id',
                        'learner_syllabus_progress.learner_id',
                        'learner_syllabus_progress.course_id',
                        'learner_syllabus_progress.syllabus_id',
                        'learner_syllabus_progress.category',
                        'learner_syllabus_progress.status', 
                        'course.course_name',
                        'quizzes.quiz_id',
                        'quizzes.quiz_title',
                    )
                    ->join('quizzes', 'learner_syllabus_progress.syllabus_id', '=', 'quizzes.syllabus_id')
                    ->join('course','learner_syllabus_progress.course_id','=','course.course_id')
                    ->where('learner_syllabus_progress.course_id', $course->course_id)
                    ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                    ->first();

                
                    $quizReferenceData = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',
                        'syllabus.topic_title',
                    )
                    ->join('syllabus', 'quiz_reference.syllabus_id', '=', 'syllabus.syllabus_id' )
                    ->where('quiz_reference.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->get();


                    $learnerQuizProgressData = DB::table('learner_quiz_progress')
                    ->select(
                        'learner_quiz_progress.learner_quiz_progress_id',
                        'learner_quiz_progress.learner_course_id',
                        'learner_quiz_progress.syllabus_id',
                        'learner_quiz_progress.quiz_id',
                        'learner_quiz_progress.status',
                        'learner_quiz_progress.max_attempt',
                        'learner_quiz_progress.attempt',
                        'learner_quiz_progress.score',
                    )
                    ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_quiz_progress.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->where('learner_quiz_progress.attempt', $attempt)
                    ->orderBy('learner_quiz_progress.learner_quiz_progress_id', 'DESC')
                    ->first();


                    $data = [
                        'title' => 'Quiz',
                        'scripts' => ['/L_course_quiz_output.js'],
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'learnerSyllabusProgressData' => $learnerSyllabusProgressData,
                        'quizReferences' => $quizReferenceData,
                        'quizProgressData' => $learnerQuizProgressData,
                        // 'quizLearnerData' => $learnerQuizData,
                    ];

                    // dd($data);

            return view('learner_course.courseQuizOutput', compact('learner'))
            ->with($data);


            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    public function view_output_json (Course $course, LearnerCourse $learner_course, Syllabus $syllabus, $attempt) {

        try {

            $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress.learner_syllabus_progress_id',
                        'learner_syllabus_progress.learner_course_id',
                        'learner_syllabus_progress.learner_id',
                        'learner_syllabus_progress.course_id',
                        'learner_syllabus_progress.syllabus_id',
                        'learner_syllabus_progress.category',
                        'learner_syllabus_progress.status', 
                        'course.course_name',
                        'quizzes.quiz_id',
                        'quizzes.quiz_title',
                    )
                    ->join('quizzes', 'learner_syllabus_progress.syllabus_id', '=', 'quizzes.syllabus_id')
                    ->join('course','learner_syllabus_progress.course_id','=','course.course_id')
                    ->where('learner_syllabus_progress.course_id', $course->course_id)
                    ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                    ->first();

                
                    $quizReferenceData = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',
                        'syllabus.topic_title',
                    )
                    ->join('syllabus', 'quiz_reference.syllabus_id', '=', 'syllabus.syllabus_id' )
                    ->where('quiz_reference.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->get();


                    $learnerQuizProgressData = DB::table('learner_quiz_progress')
                    ->select(
                        'learner_quiz_progress.learner_quiz_progress_id',
                        'learner_quiz_progress.learner_course_id',
                        'learner_quiz_progress.syllabus_id',
                        'learner_quiz_progress.quiz_id',
                        'learner_quiz_progress.status',
                        'learner_quiz_progress.max_attempt',
                        'learner_quiz_progress.attempt',
                        'learner_quiz_progress.score',
                    )
                    ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_quiz_progress.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->where('learner_quiz_progress.attempt', $attempt)
                    ->orderBy('learner_quiz_progress.learner_quiz_progress_id', 'DESC')
                    ->first();


                    $correctAnswerSubquery = DB::table('question_answer')
                        ->select('question_id', DB::raw('JSON_ARRAYAGG(answer) as correct_answer'))
                        ->where('isCorrect', 1)
                        ->groupBy('question_id');

$learnerQuizData = DB::table('learner_quiz_output')
    ->select(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id',
        'learner_quiz_output.quiz_content_id',
        'learner_quiz_output.attempts',
        'learner_quiz_output.answer',
        'learner_quiz_output.isCorrect',
        'quiz_content.course_id',
        'quiz_content.question_id',
        'questions.syllabus_id',
        'questions.question',
        'questions.category',
        DB::raw('JSON_ARRAYAGG(question_answer.answer) as all_choices'),
        DB::raw('correct_answers.correct_answer')
    )
    ->join('quiz_content', 'learner_quiz_output.quiz_content_id', '=', 'quiz_content.quiz_content_id')
    ->join('questions', 'quiz_content.question_id', '=', 'questions.question_id')
    ->leftJoinSub($correctAnswerSubquery, 'correct_answers', function ($join) {
        $join->on('questions.question_id', '=', 'correct_answers.question_id');
    })
    ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
    ->where('learner_quiz_output.attempts', $attempt)
    ->where('learner_quiz_output.learner_course_id', $learner_course->learner_course_id)
    ->where('quiz_content.quiz_id', $learnerSyllabusProgressData->quiz_id)
    ->where('quiz_content.course_id', $learnerSyllabusProgressData->course_id)
    ->where('quiz_content.syllabus_id', $learnerSyllabusProgressData->syllabus_id)
    ->groupBy(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id',
        'learner_quiz_output.quiz_content_id',
        'learner_quiz_output.attempts',
        'learner_quiz_output.answer',
        'learner_quiz_output.isCorrect',
        'quiz_content.course_id',
        'quiz_content.question_id',
        'questions.syllabus_id',
        'questions.question',
        'questions.category',
        'correct_answers.correct_answer'
    )
    ->get();



                    $data = [
                        'learnerSyllabusProgressData' => $learnerSyllabusProgressData,
                        'quizReferences' => $quizReferenceData,
                        'quizProgressData' => $learnerQuizProgressData,
                        'quizLearnerData' => $learnerQuizData,
                    ];

                    return response()->json($data);

        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }

    }




    public function reattempt_answer_quiz (Course $course, LearnerCourse $learner_course, Syllabus $syllabus) {

        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
                
                $learnerSyllabusProgressData = DB::table('learner_syllabus_progress')
                    ->select(
                        'learner_syllabus_progress.learner_syllabus_progress_id',
                        'learner_syllabus_progress.learner_course_id',
                        'learner_syllabus_progress.learner_id',
                        'learner_syllabus_progress.course_id',
                        'learner_syllabus_progress.syllabus_id',
                        'learner_syllabus_progress.category',
                        'learner_syllabus_progress.status', 
                        'course.course_name',
                        'quizzes.quiz_id',
                        'quizzes.quiz_title',
                        'quizzes.duration',
                    )
                    ->join('quizzes', 'learner_syllabus_progress.syllabus_id', '=', 'quizzes.syllabus_id')
                    ->join('course', 'learner_syllabus_progress.course_id', '=', 'course.course_id')
                    ->where('learner_syllabus_progress.course_id', $course->course_id)
                    ->where('learner_syllabus_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_syllabus_progress.learner_id', $learner->learner_id)
                    ->where('learner_syllabus_progress.learner_course_id', $learner_course->learner_course_id)
                    ->first();

                $existing_learnerQuizProgressData = DB::table('learner_quiz_progress')
                    ->select(
                        'learner_quiz_progress.learner_quiz_progress_id',
                        'learner_quiz_progress.learner_course_id',
                        'learner_quiz_progress.learner_id',
                        'learner_quiz_progress.course_id',
                        'learner_quiz_progress.syllabus_id',
                        'learner_quiz_progress.quiz_id',
                        'learner_quiz_progress.status',
                        'learner_quiz_progress.max_attempt',
                        'learner_quiz_progress.attempt',
                        'learner_quiz_progress.score',
                        'learner_quiz_progress.remarks',
                    )
                    ->join('learner_syllabus_progress', 'learner_quiz_progress.syllabus_id', '=', 'learner_syllabus_progress.syllabus_id')
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_quiz_progress.learner_id', $learner->learner_id)
                    ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                    ->where('learner_quiz_progress.quiz_id', $learnerSyllabusProgressData->quiz_id)
                    ->orderBy('learner_quiz_progress.learner_quiz_progress_id', 'DESC')
                    ->first();


                $newRowData = [
                    'learner_course_id' => $existing_learnerQuizProgressData->learner_course_id,
                    'learner_id' => $existing_learnerQuizProgressData->learner_id,
                    'course_id' => $existing_learnerQuizProgressData->course_id,
                    'syllabus_id' => $existing_learnerQuizProgressData->syllabus_id,
                    'quiz_id' => $existing_learnerQuizProgressData->quiz_id,
                    'status' => 'NOT YET STARTED',
                    'attempt' => $existing_learnerQuizProgressData->attempt + 1,
                ];

                LearnerQuizProgress::create($newRowData);


                


                session()->flash('message', 'You may now reattempt the quiz');
                return back();
            } catch (\Exception $e) {
                            dd($e->getMessage());
                        }
        } else {
            return redirect('/learner');
        }

    }



    

    public function post_assessment(Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {

                $courseData = DB::table('learner_course')
                ->select(
                    'learner_course.learner_course_id',
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
                ->where('learner_course.learner_course_id', $learner_course->learner_course_id)
                ->first();

                $postAssessmentData_recent = DB::table('learner_post_assessment_progress')
                ->select(
                    'learner_post_assessment_progress_id',
                    'status',
                    'max_duration',
                    'score',
                    'remarks',
                    'start_period',
                    'finish_period',
                    'attempt',
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->orderBy('attempt', 'DESC')
                ->first();

                $attemptCount = DB::table('learner_post_assessment_progress')
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('attempt', 1)
                ->count();


                $postAssessmentData = DB::table('learner_post_assessment_progress')
                ->select(
                    'learner_post_assessment_progress_id',
                    'status',
                    'max_duration',
                    'score',
                    'remarks',
                    'start_period',
                    'finish_period',
                    'attempt',
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->get();

$postAssessmentDataWithQuestions = [];
foreach ($postAssessmentData as $postAssessment) {
    $questionsDataForPostAssessment = DB::table('learner_post_assessment_output')
        ->select(
            'learner_post_assessment_output.syllabus_id',
            'learner_post_assessment_output.attempt',
            DB::raw('COUNT(learner_post_assessment_output.question_id) AS total_lesson_question'),
            DB::raw('SUM(CASE WHEN learner_post_assessment_output.isCorrect = 1 THEN 1 ELSE 0 END) AS correct_answers_per_lesson'),
            'syllabus.topic_title',
        )
        ->join('syllabus', 'learner_post_assessment_output.syllabus_id', 'syllabus.syllabus_id')
        ->where('learner_post_assessment_output.learner_course_id', $learner_course->learner_course_id)
        ->where('learner_post_assessment_output.course_id', $course->course_id)
        ->where('learner_post_assessment_output.attempt', $postAssessment->attempt)
        ->groupBy('learner_post_assessment_output.syllabus_id', 'learner_post_assessment_output.attempt', 'syllabus.topic_title') // Include topic_title in the GROUP BY clause
        ->get();

    $postAssessment->questionsData = $questionsDataForPostAssessment;
    $postAssessmentDataWithQuestions[] = $postAssessment;
}


                // $questionsData = DB::table('learner_post_assessment_output')
                // ->select(
                //     'learner_post_assessment_output.syllabus_id',
                //     'learner_post_assessment_output.attempt',
                //     DB::raw('COUNT(learner_post_assessment_output.question_id) AS total_lesson_question'),
                //     DB::raw('SUM(CASE WHEN learner_post_assessment_output.isCorrect = 1 THEN 1 ELSE 0 END) AS correct_answers_per_lesson'),
                //     'syllabus.topic_title',
                // )
                // ->join('syllabus', 'learner_post_assessment_output.syllabus_id', 'syllabus.syllabus_id')
                // ->where('learner_post_assessment_output.learner_course_id', $learner_course->learner_course_id)
                // ->where('learner_post_assessment_output.course_id', $course->course_id)
                // ->groupBy('learner_post_assessment_output.syllabus_id', 'learner_post_assessment_output.attempt')
                // ->get();
            
                // $groupedQuestionsData = $questionsData->groupBy('attempt');

            // dd($groupedQuestionsData);
            

            
                $totalNumofQuestions = DB::table('learner_post_assessment_output')
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('attempt', 1)
                ->count();

                $data = [
                    'title' => 'Course Post Assessment',
                    'scripts' => ['/learner_post_assessment.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'postAssessmentData_recent' => $postAssessmentData_recent,
                    'postAssessmentData' => $postAssessmentDataWithQuestions,
                    'questionsCount' => $totalNumofQuestions,
                    'attemptCount' => $attemptCount,
                    // 'questionsData' => $groupedQuestionsData,
                ];
                // dd($data);
                return view('learner_course.coursePostAssessment', compact('learner'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }


    public function answer_post_assessment (Course $course, LearnerCourse $learner_course, $attempt) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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
                ->where('learner_course.learner_course_id', $learner_course->learner_course_id)
                ->first();

                $postAssessmentData = DB::table('learner_post_assessment_progress')
                ->select(
                    'learner_post_assessment_progress_id',
                    'status',
                    'max_duration',
                    'score',
                    'remarks',
                    'start_period',
                    'finish_period',
                    'attempt'
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('attempt', $attempt)
                ->first();


                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();

                DB::table('learner_post_assessment_progress')
                ->where('learner_post_assessment_progress_id', $postAssessmentData->learner_post_assessment_progress_id)
                ->where('attempt', $attempt)
                ->update([
                    'start_period' => $timestampString,
                    'status' => 'IN PROGRESS',
                ]);


                    // if($preAssessmentData->status === 'COMPLETED') {
                    //     session()->flash('message', 'You have already finished your Pre Assessment');
                    //     return redirect('/learner/course/content/'.$course->id.'/'.$learner_course->id.'/pre_assessment')->with('error', 'You have already finished your Pre Assessment');

                    // } else {

                        $learnerPostAssessmentOutputData = DB::table('learner_post_assessment_output')
                        ->select(
                            'learner_post_assessment_output_id',
                            'question_id',
                            'syllabus_id',
                        )
                        ->where('learner_course_id', $learner_course->learner_course_id)
                        ->where('course_id', $course->course_id)
                        ->where('attempt', $attempt)
                        ->get();

                        $questionsCount = DB::table('questions')
                        ->where('course_id', $course->course_id)
                        ->count();

                        if($learnerPostAssessmentOutputData->isEmpty()) {
                          
                            $assessmentQuestions = [];

                            if ($questionsCount < 50) {
                                $questions = DB::table('questions')
                                ->select(
                                 'question_id',
                                 'syllabus_id',
                                 'question',
                                 'category'
                                 )
                                 ->where('course_id', $course->course_id)
                                 ->inRandomOrder()
                                 ->get();

                                 $assessmentQuestions = array_merge($assessmentQuestions, $questions->toArray());
                            } else {
                                $questionNumbersPerLesson = DB::table('lessons')
                                ->select(
                                    'lesson_title',
                                    'duration',
                                    'syllabus_id',
                                    DB::raw('(SELECT SUM(duration) FROM lessons WHERE course_id = ' . $course->course_id . ') AS total_duration'),
                                    DB::raw('((duration / (SELECT SUM(duration) FROM lessons WHERE course_id = ' . $course->course_id . ')) * 100) AS percentage'),
                                    DB::raw('ROUND(50 * (duration / (SELECT SUM(duration) FROM lessons WHERE course_id = ' . $course->course_id . ')), 0) AS item_number')
                                )
                                ->where('course_id', $course->course_id)
                                ->get();
                            
                                foreach ($questionNumbersPerLesson as $questionsPerLesson) {
    
                                    $questions = DB::table('questions')
                                       ->select(
                                        'question_id',
                                        'syllabus_id',
                                        'question',
                                        'category'
                                        )
                                        ->where('course_id', $course->course_id)
                                        ->where('syllabus_id', $questionsPerLesson->syllabus_id)
                                        ->inRandomOrder()
                                        ->limit($questionsPerLesson->item_number)
                                        ->get();
    
                                        $assessmentQuestions = array_merge($assessmentQuestions, $questions->toArray());
                                }
                            }

                            // dd($assessmentQuestions);
                        foreach($assessmentQuestions as $content) {
                            
                            $outputData = [
                                'learner_course_id' => $courseData->learner_course_id,
                                'learner_id' => $courseData->learner_id,
                                'course_id' => $courseData->course_id,
                                'question_id' => $content->question_id,
                                'syllabus_id' => $content->syllabus_id,
                                'attempt' => $attempt
                            ];

                            LearnerPostAssessmentOutput::firstOrCreate($outputData);
                        }
                            

                        $postAssessmentOutputData = DB::table('learner_post_assessment_output')
                        ->select(
                            'learner_post_assessment_output.learner_post_assessment_output_id',
                            'learner_post_assessment_output.learner_course_id',
                            'learner_post_assessment_output.course_id',
                            'learner_post_assessment_output.question_id',
                            'learner_post_assessment_output.syllabus_id',
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
                    
                        
                    } else {
                        $postAssessmentOutputData = DB::table('learner_post_assessment_output')
                        ->select(
                            'learner_post_assessment_output.learner_post_assessment_output_id',
                            'learner_post_assessment_output.learner_course_id',
                            'learner_post_assessment_output.course_id',
                            'learner_post_assessment_output.question_id',
                            'learner_post_assessment_output.syllabus_id',
                            'questions.question',
                            'questions.category',
                            DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers')
                        )
                        ->join('questions', 'learner_post_assessment_output.question_id', '=', 'questions.question_id')
                        ->leftJoin('question_answer', 'questions.question_id', '=', 'question_answer.question_id')
                        ->where('learner_post_assessment_output.course_id', $courseData->course_id)
                        ->where('learner_post_assessment_output.learner_course_id', $courseData->learner_course_id)
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

                    }
                // }
    

                $data = [
                    'title' => 'Course Lesson',
                    'scripts' => ['/learner_post_assessment.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'postAssessmentData' => $postAssessmentData,
                    'postAssessmentOutputData' => $postAssessmentOutputData,
                ];

                // dd($data);

                return view('learner_course.coursePostAssessmentAnswer', compact('learner'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    
    public function answer_post_assessment_json(Course $course, LearnerCourse $learner_course, $attempt) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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
                ->where('learner_course.learner_course_id', $learner_course->learner_course_id)
                ->first();

                $postAssessmentData = DB::table('learner_post_assessment_progress')
                ->select(
                    'learner_post_assessment_progress_id',
                    'status',
                    'max_duration',
                    'score',
                    'remarks',
                    'start_period',
                    'finish_period',
                    'attempt'
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->where('attempt', $attempt)
                ->first();


$postAssessmentOutputData = DB::table('learner_post_assessment_output')
    ->select(
        'learner_post_assessment_output.learner_post_assessment_output_id',
        'learner_post_assessment_output.learner_course_id',
        'learner_post_assessment_output.course_id',
        'learner_post_assessment_output.attempt',
        'learner_post_assessment_output.question_id',
        'learner_post_assessment_output.syllabus_id',
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
        'learner_post_assessment_output.attempt', // Include 'attempt' here
        'learner_post_assessment_output.question_id',
        'learner_post_assessment_output.syllabus_id',
        'questions.question',
        'questions.category',
        'questions.question_id'
    )
    ->get();
    

                $data = [
                    'title' => 'Course Lesson',
                    'scripts' => ['/learner_pre_assessment.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'postAssessmentData' => $postAssessmentData,
                    'postAssessmentOutputData' => $postAssessmentOutputData,
                ];

                // dd($data);

                // return view('learner_course.coursePreAssessmentAnswer', compact('learner'))
                // ->with($data);

                return response()->json($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    public function submit_post_assessment(Course $course, LearnerCourse $learner_course, $attempt, Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {

            $learner_post_assessment_output_id = $request->input('learner_post_assessment_output_id');
            $question_id = $request->input('question_id');

            $answer = $request->input('answer');

            DB::table('learner_post_assessment_output')
            ->where('learner_post_assessment_output_id', $learner_post_assessment_output_id)
            ->where('question_id', $question_id)
            ->where('attempt', $attempt)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->update([
                'answer' => $answer
            ]);


            $this->check_post_assessment_answer($learner_post_assessment_output_id, $question_id, $answer, $attempt);



            // Return the counts in the response
            $data = [
            'message' => 'Learner Post Assessment submitted successfully',
            ];


            return response()->json($data);

            } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
        
                return response()->json(['errors' => $errors], 422);
            }
        }
    }
    

    public function check_post_assessment_answer($learner_post_assessment_output_id, $question_id, $answer, $attempt) {
        try {
            // If $answer is null, set isCorrect to 0
            $answerValue = $answer !== null
                ? DB::table('question_answer')
                    ->select('isCorrect')
                    ->where('question_id', $question_id)
                    ->where('answer', $answer)
                    ->first()
                : (object) ['isCorrect' => 0];

                $isCorrect = $answerValue !== null ? $answerValue->isCorrect : 0;
    
                DB::table('learner_post_assessment_output')
                ->where('learner_post_assessment_output_id', $learner_post_assessment_output_id)
                ->where('question_id', $question_id)
                ->where('attempt', $attempt)
                ->update([
                    'isCorrect' => $isCorrect
                ]);
    
            // Return the correctness status
            return $answerValue !== null ? $answerValue->isCorrect : 0;

    
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    

    public function score_post_assessment (Course $course, LearnerCourse $learner_course, $attempt, Request $request) {
        
        try {
            $learner_post_assessment_output_id = $request->input('learner_post_assessment_output_id');
            $question_id = $request->input('question_id');

            // total items of the quiz
            $totalCount = DB::table('learner_post_assessment_output')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->where('attempt', $attempt)
            ->count();

            // score of the learner
            $scoreCount = DB::table('learner_post_assessment_output')
            ->where('isCorrect', 1)
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->where('attempt', $attempt)
            ->count();
            
            $scorePercentage = ($scoreCount / $totalCount) * 100;

            $now = Carbon::now();
            $timestampString = $now->toDateTimeString();
            // update the score and status
            DB::table('learner_post_assessment_progress')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->where('attempt', $attempt)
            ->update([
                'status' => "COMPLETED",
                'score' => $scoreCount,
                'remarks' => $scorePercentage >= 90 ? 'Excellent' : ($scorePercentage >= 80 ? 'Very Good' : ($scorePercentage >= 70 ? 'Good' : ($scorePercentage > 50 ? 'Satisfactory' : 'Needs Improvement'))),
                'finish_period' => $timestampString,

            ]);

          $this->overallGrade($course, $learner_course);

          $reportController = new PDFGenerationController();

          $reportController->courseGradeSheet($course->course_id);
          $reportController->learnerCourseGradeSheet($learner_course->learner_id, $course->course_id, $learner_course->learner_course_id);
          $reportController->learnerPostAssessmentOutput($learner_course->learner_id, $course->course_id, $learner_course->learner_course_id, $attempt);
          
            
            session()->flash('message', 'Learner Post Assessment Scored successfully');

            $data = [
                'message' => 'Learner Post Assessment Scored successfully',
                'redirect_url' => "/learner/course/content/$course->course_id/$learner_course->learner_course_id/post_assessment",
                ];
    
    
                return response()->json($data);
    

        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }   

    
    public function view_output_post_assessment(Course $course, LearnerCourse $learner_course, $attempt) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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
                        'learner_post_assessment_output.attempt',
                        'learner_post_assessment_output.question_id',
                        'learner_post_assessment_output.syllabus_id',
                        'learner_post_assessment_output.answer',
                        'learner_post_assessment_output.isCorrect',
                        'questions.question',
                        'questions.category',
                        'correct_answers.correct_answer'
                    )
                    ->get();

                    

                $data = [
                    'title' => 'Course Post Assessment',
                    'scripts' => ['/learner_post_assessment_output.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'learnerCourseData' => $courseData,
                    'postAssessmentData' => $postAssessmentData,
                    'postAssessmentOutputData' => $postAssessmentOutputData,
                ];

                // dd($data);

                return view('learner_course.coursePostAssessmentOutput', compact('learner'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    public function view_output_post_assessment_json(Course $course, LearnerCourse $learner_course, $attempt) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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
                        'learner_post_assessment_output.attempt',
                        'learner_post_assessment_output.question_id',
                        'learner_post_assessment_output.answer',
                        'learner_post_assessment_output.isCorrect',
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
            return redirect('/learner');
        }
    }

    public function post_assessment_reattempt(Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
            
                $lastLearnerPostAssessmentData = DB::table('learner_post_assessment_progress')
                ->select(
                    'learner_post_assessment_progress_id',
                    'attempt',
                )
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->where('course_id', $course->course_id)
                ->orderBy('attempt', 'DESC')
                ->first();

                $newAttempt = $lastLearnerPostAssessmentData->attempt + 1;

                $newLearnerPostAssessmentData = [
                    'learner_course_id' => $learner_course->learner_course_id,
                    'learner_id' => $learner->learner_id,
                    'course_id' => $course->course_id,
                    'status' => "NOT YET STARTED",
                    'attempt' => $newAttempt,
                ];

                LearnerPostAssessmentProgress::create($newLearnerPostAssessmentData);
                
                session()->flash('message', 'You may now reattempt the Post Assessment');
                return back();

                // return response()->json($data);
    

            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
            
                return response()->json(['errors' => $errors], 422);
            }
        } else {
            return redirect('/learner');
        }
    }


    public function overallGrade(Course $course, LearnerCourse $learner_course) {
        try {
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
            ->where('learner_activity_output.course_id', $course->course_id)
            ->where('learner_activity_output.learner_course_id', $learner_course->learner_course_id)
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
            ->where('activities.course_id', $course->course_id)
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
            ->where('learner_quiz_progress.course_id', $course->course_id)
            ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
            ->groupBy('learner_quiz_progress.quiz_id', 'quizzes.quiz_title')
            ->get();
        

                $quizTotalScore = DB::table('quiz_content')
                ->where('quiz_content.course_id', $course->course_id)
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
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->get();

            $totalScoreCount_post_assessment = DB::table('learner_post_assessment_output')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->where('attempt', 1)
            ->count();


            $postAssessmentLearnerSumScore = 0;


            foreach ($learnerPostAssessmentScoresData as $post_assessment) {
                $postAssessmentLearnerSumScore += $post_assessment->average_score;
            }

            $learnerPreAssessmentScoresData = DB::table('learner_pre_assessment_progress')
            ->select(
                'score'
            )
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->get();

            $totalScoreCount_pre_assessment = DB::table('learner_pre_assessment_output')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->count();

            $preAssessmentLearnerSumScore = 0;


            foreach ($learnerPreAssessmentScoresData as $pre_assessment) {
                $preAssessmentLearnerSumScore += $pre_assessment->score;
            }

            $courseGrading = DB::table('course_grading')
            ->select(
                'activity_percent',
                'quiz_percent',
                'pre_assessment_percent',
                'post_assessment_percent',
            )
            ->where('course_id', $course->course_id)
            ->first();

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

            $now = Carbon::now();
            $timestampString = $now->toDateTimeString();

            DB::table('learner_course_progress')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->update([
                'course_progress' => "COMPLETED",
                'grade' => $totalGrade,
                'remarks' => $remarks,
                'finish_period' => $timestampString,
            ]);

            $reportController = new PDFGenerationController();

            $reportController->courseEnrollees($course->course_id);



        } catch (\Exception $e) { 
            dd($e->getMessage());
        }
    }


    public function grades(Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
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
                ->where('learner_course_progress.course_id', $course->course_id)
                ->where('learner_course_progress.learner_course_id', $learner_course->learner_course_id)
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
                ->where('learner_lesson_progress.learner_course_id', $learner_course->learner_course_id)
                ->where('learner_lesson_progress.course_id', $course->course_id)
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
                ->where('learner_activity_output.course_id', $course->course_id)
                ->where('learner_activity_output.learner_course_id', $learner_course->learner_course_id)
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
                ->where('activities.course_id', $course->course_id)
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
                ->where('learner_quiz_progress.course_id', $course->course_id)
                ->where('learner_quiz_progress.learner_course_id', $learner_course->learner_course_id)
                ->groupBy('learner_quiz_progress.quiz_id', 'quizzes.quiz_title')
                ->get();
            
    
                    $quizTotalScore = DB::table('quiz_content')
                    ->where('quiz_content.course_id', $course->course_id)
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
                    ->where('course_id', $course->course_id)
                    ->where('learner_course_id', $learner_course->learner_course_id)
                    ->get();
    
                $totalScoreCount_post_assessment = DB::table('learner_post_assessment_output')
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
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
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->first();

            $learnerPreAssessmentScoresData = DB::table('learner_pre_assessment_progress')
            ->select(
                'score'
            )
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->get();

            $totalScoreCount_pre_assessment = DB::table('learner_pre_assessment_output')
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->count();

            $preAssessmentLearnerSumScore = 0;


            foreach ($learnerPreAssessmentScoresData as $pre_assessment) {
                $preAssessmentLearnerSumScore += $pre_assessment->score;
            }


            $learnerPostAssessmentGrade = DB::table('learner_post_assessment_progress')
            ->select (
                    DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                )
                ->where('course_id', $course->course_id)
                ->where('learner_course_id', $learner_course->learner_course_id)
                ->first();

            $learnerPostAssessmentData = DB::table('learner_post_assessment_progress') 
            ->select(
                'start_period',
                'finish_period'
            )
            ->where('course_id', $course->course_id)
            ->where('learner_course_id', $learner_course->learner_course_id)
            ->orderBy('attempt', 'DESC')
            ->first();

            $courseGrading = DB::table('course_grading')
            ->select(
                'activity_percent',
                'quiz_percent',
                'pre_assessment_percent',
                'post_assessment_percent',
            )
            ->where('course_id', $course->course_id)
            ->first();
            $activityGrade = 0;
            $quizGrade = 0;
            $postAssessmentGrade = 0;
            $preAssessmentGrade = 0;
            $totalGrade = 0;
            $remarks = '';


            if($courseData->course_progress === 'COMPLETED') {
                      // compute now the grades

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
                    }
                $data = [
                    'title' => 'Course Gradesheet',
                    'scripts' => ['/learner_gradesheet.js'],
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
    


                return view('learner_course.courseGrades', compact('learner'))
                ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }

    protected $fpdf;
 
    public function __construct()
    {
        $this->fpdf = new Fpdf;
    }
    public function generate_certificate(Course $course, LearnerCourse $learner_course) {
        if (session()->has('learner')) {
            $learner= session('learner'); 
            try {
                //get the data needed

                $learnerCourseProgressData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_progress_id',
                    'learner_course_progress.course_id',
                    'learner_course_progress.finish_period',
                    'course.course_name',
                    'course.course_code',
                )
                ->join('course', 'learner_course_progress.course_id', 'course.course_id')
                ->where('learner_course_progress.learner_course_id', $learner_course->learner_course_id)
                ->where('learner_course_progress.course_id', $course->course_id)
                ->first();

                $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $learnerCourseProgressData->finish_period)->format('F d, Y');



                $certData = DB::table('certificates')
                ->select(
                    'certificate_id',
                    'reference_id',
                    'user_type',
                    'user_id',
                    'course_id'
                )
                ->where('user_type', 'LEARNER')
                ->where('user_id', $learner->learner_id)
                ->where('course_id', $course->course_id)
                ->first();

                if($certData) {
                    $referenceNumber = $certData->reference_id;
                } else {
                    $datePart = Carbon::now()->format('Ymd');
                    do {
                        $randomPart = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                        $referenceNumber = $datePart . $randomPart;
                    } while (Certificates::where('reference_id', $referenceNumber)->exists());

                    Certificates::create([
                        'reference_id' => $referenceNumber,
                        'user_type' => 'LEARNER',
                        'user_id' => $learner->learner_id,
                        'course_id' => $course->course_id
                    ]);


                }
                // generate the pdf
                $filename = storage_path('app/public/images/cert_3.png');
    
                $this->fpdf->AddPage("L");
    


    
                // Set the background image with low opacity
                $this->fpdf->Image($filename, 0, 0, $this->fpdf->GetPageWidth(), $this->fpdf->GetPageHeight(), '', '', 0, false, 300);
                
                // Set the font size for the large text
                // $this->fpdf->SetFont('GreatVibes', 'B', 36);
                $this->fpdf->SetFont('arial', 'B', 40);
                $text = "$learner->learner_fname $learner->learner_lname";
                // Get the width of the text
                $textWidth = $this->fpdf->GetStringWidth($text);
                // Calculate the X coordinate to center the text
                $x = ($this->fpdf->GetPageWidth() - $textWidth) / 2;
                // Set the X coordinate and draw the text
                $this->fpdf->SetXY($x, 50);
                $this->fpdf->Cell($textWidth, 110, $text, 0, 0, 'C');
                
                $this->fpdf->SetFont('Arial', 'B', 14);
                $text3 = "LEARNER - Course: $learnerCourseProgressData->course_name";
                // Get the width of the text
                $text3Width = $this->fpdf->GetStringWidth($text);
                // Calculate the X coordinate to center the text
                $x = ($this->fpdf->GetPageWidth() - $text3Width) / 2;
                // Set the X coordinate and draw the text
                $this->fpdf->SetXY($x, 50);
                $this->fpdf->Cell($text3Width, 130, $text3, 0, 0, 'C');


                // Set the font size for the large text
                // $this->fpdf->SetFont('GreatVibes', 'B', 36);
                $this->fpdf->SetFont('Arial', 'i', 12);
                $text2 = "This is to certify that $learner->learner_fname $learner->learner_lname has completed $learnerCourseProgressData->course_name with dedication\n
                dedication and skill, demonstrating a commendable commitment to learning and personal\n
                development. She has effectively fulfilled the requirements for this program.\n
                Awarded on $formattedDate.";

                $x = 10; // Set X-axis position
                $this->fpdf->SetXY($x, 126);
                $this->fpdf->MultiCell($this->fpdf->GetPageWidth() - ($x * 2), 3, $text2, 0, 'C');



                $this->fpdf->SetFont('Arial', 'B', 11);
                $text4 = "Reference Number: $referenceNumber";

                $this->fpdf->SetXY(10, $this->fpdf->GetPageHeight() - 39);
                $this->fpdf->Cell(0, 10, $text4, 0, 0, 'L');
                


                $this->fpdf->Output();
                
    
                exit;
    
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/learner');
        }
    }
    
}
