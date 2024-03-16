<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Learner;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\CourseGrading;
use App\Models\ActivityContents;
use App\Models\ActivityContentCriterias;
use App\Models\Admin;
use App\Models\LearnerCourse;
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
use App\Models\Syllabus;
use App\Models\Lessons;
use App\Models\Activities;
use App\Models\Quizzes;
use App\Models\LessonContents;
use App\Models\LearnerPreAssessmentProgress;
use App\Models\LearnerPreAssessmentOutput;
use App\Models\LearnerPostAssessmentProgress;
use App\Models\LearnerPostAssessmentOutput;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\URL;


use App\Http\Controllers\PDFGenerationController;

class AdminCourseManageController extends Controller
{
    public function coursesManage() {
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
    
                return view('admin.coursesManage', compact('courses'))
                    ->with($data);
    
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/admin');
        }
    }


    public function coursesOverview(Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
                try {
                    $course = DB::table('course')
                    ->select(
                        'course.course_id',
                        'course.course_name',
                        'course.course_code',
                        'course.course_description',
                        'course.course_status',
                        'course.course_difficulty',
                        'course.instructor_id',
                        'instructor.instructor_fname',
                        'instructor.instructor_lname',
                        'instructor.profile_picture',
                    )
                    ->join('instructor', 'course.instructor_id', '=',  'instructor.instructor_id')
                    ->where('course_id', $course->course_id)
                    ->first();
    
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
    
    
                    $totalLessonsDuration = $totalLessonsDuration->total_duration ?? 0;
                    $totalActivitiesDuration = $totalActivitiesDuration->total_duration ?? 0;
                    $totalQuizzesDuration = $totalQuizzesDuration->total_duration ?? 0;
    
    
                    $totalCourseTime = $totalLessonsDuration + $totalActivitiesDuration + $totalQuizzesDuration;
    
                    $totalCourseTimeInSeconds = $totalCourseTime / 1000;
    
                    $hours = floor($totalCourseTimeInSeconds / 3600);
                    $minutes = floor(($totalCourseTimeInSeconds % 3600) / 60);
                    $seconds = $totalCourseTimeInSeconds % 60;
    
    
                    $formattedTotalCourseTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    
    
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
    
                    $totalEnrolledCount = DB::table('learner_course')
                    ->where('course_id', $course->course_id)
                    ->count();
    
                    $courseEnrollees = DB::table('learner_course_progress')
                    ->select(
                        'learner_course_progress.learner_course_progress_id',
                        'learner_course_progress.learner_course_id',
                        'learner_course_progress.learner_id',
                        'learner_course_progress.course_id',
                        'learner_course_progress.course_progress',
                        'learner_course_progress.start_period',
                        'learner.learner_fname',
                        'learner.learner_lname',
                        'learner.learner_email',
                        'learner_course.status',
                        'learner_course.created_at',
                    )
                    ->join('learner_course', 'learner_course.learner_course_id', '=', 'learner_course_progress.learner_course_id')
                    ->join('learner', 'learner.learner_id', '=', 'learner_course_progress.learner_id')
                    ->where('learner_course_progress.course_id', $course->course_id)
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
    
                    $gradeWithActivityData[$key] = $activityData;
                }
    
    
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
      
                $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');
    
   
                $directory = "public/courses/$folderName/documents";
    
                $courseFiles = Storage::files($directory);

                $gradingSystem = DB::table('course_grading')
                ->select(
                    'course_grading_id',
                    'course_id',
                    'activity_percent',
                    'quiz_percent',
                    'pre_assessment_percent',
                    'post_assessment_percent',
                )
                ->where('course_id', $course->course_id)
                ->first();
    
                    $data = [
                        'title' => 'Course Overview',
                        'scripts' => ['AD_courseOverview.js'],
                        'admin' => $adminSession,
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
                        'courseEnrollees' => $courseEnrollees,
                        'gradesheet' => $gradeWithActivityData,
                        'activitySyllabus' => $activitySyllabusData,
                        'quizSyllabus' => $quizSyllabusData,
                        'courseFiles' => $courseFiles,
                        'gradingSystem' => $gradingSystem
                    ];
    
                    // dd($data);
    
                    return view('adminCourse.courseOverview', compact('course'))
                    ->with($data);
    
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            
            }  else {
                return view('error.error');
            }
    } else {
        return redirect('/admin');
    }
    
    }


    public function course_content(Course $course) {

            try {
                $course = DB::table('course')
                ->select(
                    "course.course_id",
                    "course.course_name",
                    "course.course_code",
                    "course.course_description",
                    "course.course_status",
                    "course.course_difficulty",
                    "course.instructor_id",
                    "instructor.instructor_fname",
                    "instructor.instructor_lname",
                    "instructor.profile_picture",
                )
                ->join('instructor', 'instructor.instructor_id', '=', 'course.instructor_id')
                ->where('course_id', $course->course_id)
                ->first();

                $syllabus = DB::table('syllabus')
                ->select(
                    "syllabus_id",
                    "topic_id",
                    "topic_title",
                    "category"
                )
                ->where('course_id', $course->course_id)
                ->orderBy('topic_id', 'ASC')
                ->get();

                $lessonCount = 0;
                $quizCount = 0;
                $activityCount = 0;

                foreach($syllabus as $topic) {
                    if($topic->category == 'LESSON') {
                        $lessonCount++;
                    } else if($topic->category == 'ACTIVITY') {
                        $activityCount++;
                    } else {
                        $quizCount++;
                    }
                }


                $data = [
                    'course' => $course,
                    'syllabus' => $syllabus,
                    'lessonCount' => $lessonCount,
                    'quizCount' => $quizCount,
                    'activityCount' => $activityCount,
           
                ];

                return $data;

                // return view('instructor_course.courseContent', compact('instructor', 'course', 'syllabus'))->with([
                //     'title' => 'Course Content',
                //     'scripts' => ['instructor_course_content_syllabus.js'],
                //     'lessonCount' => $lessonCount,
                //     'activityCount' => $activityCount,
                //     'quizCount' => $quizCount,
                // ]);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
        
                return response()->json(['errors' => $errors], 422);
            }
    }


    public function overViewNum(Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
    
            try{

                $enrolleeProgress = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress_id',
                    'learner_course_id',
                    'learner_id',
                    'course_id',
                    'course_progress'
                )
                ->where('course_id', $course->course_id)
                ->get();

                $data = [
                    'title' => 'Performance',    
                    'enrolleeProgress' => $enrolleeProgress,          
                ];
                
        
                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();

                return response()->json(['errors' => $errors], 422);
            }
        } else {
            session()->flash('message', 'You cannot update course data');
            $data = [
                'message' => 'You cannot update course data',
                'redirect_url' => '/admin/courseManage/' . $course->course_id,
            ];
    
            return response()->json($data);
        }
        }  else {
            return redirect('/admin');
        }
    }


    public function editCourseDetails(Course $course, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try{

                    $courseData = $request->validate([
                        'course_name' => ['required'],
                        'course_description' => ['required'],
                    ]);
    
                    $course->update($courseData);
    
                    session()->flash('message', 'Course updated Successfully');
                    return response()->json(['message' => 'Course updated successfully', 'redirect_url' => "/admin/courseManage/$course->course_id"]);
                    
                
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
    
                    return response()->json(['errors' => $errors], 422);
                }
            
            
            } else {
                session()->flash('message', 'You cannot update course data');
                $data = [
                    'message' => 'You cannot update course data',
                    'redirect_url' => '/admin/courseManage/' . $course->course_id,
                ];
        
                return response()->json($data);
            }
            }  else {
                return redirect('/admin');
            }
    }


    public function add_file (Course $course, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                $request->validate([
                    'file' => 'required|mimes:pdf,doc,docx|max:2048',
                ]);
            
                $file = $request->file('file');
                $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');
                $fileName = time() . ' - ' . $course->course_name . ' - ' . $file->getClientOriginalName();
                $folderPath = 'courses/' . $folderName . '/documents';
            
                // Create the course-specific folder if it doesn't exist
                if (!Storage::exists($folderPath)) {
                    Storage::makeDirectory($folderPath);
                }
            
                $filePath = $file->storeAs($folderPath, $fileName, 'public');
        
                if (!$filePath) {
                    // Handle the error, log it, or return a response
                session()->flash('message', 'File could not be uploaded.');
                    return redirect()->back()->with('error', 'File could not be uploaded.');
                }
        
            
                session()->flash('message', 'File Uploaded Successfully');
                return redirect()->back()->with('success', 'File uploaded successfully');

            } else {
                session()->flash('message', 'You cannot upload file');
                $data = [
                    'message' => 'You cannot You cannot upload file',
                    'redirect_url' => '/admin/courseManage/' . $course->course_id,
                ];
        
                return response()->json($data);
            }
            }  else {
                return redirect('/admin');
            }
    }


    public function delete_file(Course $course, Request $request, $fileName) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');
                $filePath = "public/courses/{$folderName}/documents/{$fileName}";
        
                
            
                if (Storage::exists($filePath)) {
                    // Delete the file
                    Storage::delete($filePath);
            
                    session()->flash('message', 'File Deleted Successfully');
                    return redirect()->back()->with('success', 'File deleted successfully');
                } else {
                    return redirect()->back()->with('error', 'File not found');
                }
                
            } else {
                session()->flash('message', 'You cannot upload file');
                $data = [
                    'message' => 'You cannot You cannot upload file',
                    'redirect_url' => '/admin/courseManage/' . $course->course_id,
                ];
        
                return response()->json($data);
            }
            }  else {
                return redirect('/admin');
            }
    }

    public function gradingSystem(Course $course, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {

                try {

                    // dd($request);

                    $gradeData = [
                        'activity_percent' => $request->input('activity_percent'),
                        'quiz_percent' => $request->input('quiz_percent'),
                        'pre_assessment_percent' => $request->input('pre_assessment_percent'),
                        'post_assessment_percent' => $request->input('post_assessment_percent'),    
                    ];


                    // dd($gradeData);

                    DB::table('course_grading')
                    ->where('course_id' , $course->course_id)
                    ->update($gradeData);

                    session()->flash('message', 'Course Grading has been updated');
                    $data = [
                        'message' => 'Course Grading has been updated',
                        'redirect_url' => '/admin/courseManage/' . $course->course_id,
                    ];
            
                    return response()->json($data);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
                    return response()->json(['errors' => $errors], 422);
                }
                
            } else {
                session()->flash('message', 'You do not have privilege to access');
                $data = [
                    'message' => 'You do not have privilege to access',
                    'redirect_url' => '/admin/courseManage/' . $course->course_id,
                ];
        
                return response()->json($data);
            }
            }  else {
                return redirect('/admin');
            }
    }


    public function delete_course (Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {
                    $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');
                    $folderPath = public_path("courses/{$folderName}");
        
                    // Delete files in the 'documents' folder
                    Storage::deleteDirectory("courses/{$folderName}");
        
                    // Delete the course folder
                    if (File::exists($folderPath)) {
                        File::deleteDirectory($folderPath);
                    }
            
                    // Delete the course record
                    $course->delete();
            
                    session()->flash('message', 'Course deleted Successfully');
                    return response()->json(['message' => 'Course deleted successfully', 'redirect_url' => "/admin/courseManages"]);
            
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
                    return response()->json(['errors' => $errors], 422);
                }


            } else {
                session()->flash('message', 'You cannot upload file');
                $data = [
                    'message' => 'You cannot You cannot upload file',
                    'redirect_url' => '/admin/courseManage/' . $course->course_id,
                ];
        
                return response()->json($data);
            }
            }  else {
                return redirect('/admin');
            }
    }

    public function create_syllabus(Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
        $syllabusData = $request->validate([
            'course_id' => ['required'],
            'topic_id' => ['required'],
            'topic_title'=> ['required'],
            'category'=> ['required'],
        ]);

        $syllabus = Syllabus::create($syllabusData);

        if($syllabusData['category'] == 'LESSON') {

            $lessonData = [
                'syllabus_id'=> $syllabus->syllabus_id,
                'course_id' => $syllabus->course_id,
                'topic_id' => $syllabus->topic_id,
                'lesson_title' => $syllabus->topic_title,
            ];

            $lesson = Lessons::create($lessonData);

        } else if($syllabusData['category'] == 'ACTIVITY') {
            $activityData = [
                'syllabus_id'=> $syllabus->syllabus_id,
                'course_id' => $syllabus->course_id,
                'topic_id' => $syllabus->topic_id,
                'activity_title' => $syllabus->topic_title,
            ];

            $activity = Activities::create($activityData);
        } else {
            $quizData = [
                'syllabus_id'=> $syllabus->syllabus_id,
                'course_id' => $syllabus->course_id,
                'topic_id' => $syllabus->topic_id,
                'quiz_title' => $syllabus->topic_title,
            ];

            $quiz = Quizzes::create($quizData);
        }

       
        // $latestSyllabus = DB::table('syllabus')->orderBy('created_at', 'DESC')->first();

        session()->flash('message', 'Syllabus created Successfully');

        $response = [
            'message' => 'Syllabus created successfully',
            'redirect_url' => '/admin/courseManages',
            'syllabus' => $syllabus->syllabus_id,
        ];

        return response()->json($response);
    
        } else {
            session()->flash('message', 'You cannot upload file');
            $data = [
                'message' => 'You cannot You cannot upload file',
                'redirect_url' => '/admin/courseManage/',
            ];

            return response()->json($data);
        }
        }  else {
            return redirect('/admin');
        }
    }

    public function display_course_syllabus_view(Course $course) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {



                    $response = $this->course_content($course);

                    return view('adminCourse.courseContent')->with([
                        'title' => 'Course Content',
                        'scripts' => ['AD_course_content_syllabus.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'admin' => $adminSession,
                        // 'instructor' => $response['instructor'],
                    ]);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }
            
        } else {
            session()->flash('message', 'You cannot upload file');
            $data = [
                'message' => 'You cannot You cannot upload file',
                'redirect_url' => '/admin/courseManage/',
            ];

            return response()->json($data);
        }
        }  else {
            return redirect('/admin');
        }
    }


    public function course_content_json (Course $course) {
 
            try {
                $response = $this->course_content($course);


                $data = [    
                'title' => 'Course Content',
                'scripts' => ['AD_course_content_syllabus.js'],
                'lessonCount' => $response['lessonCount'],
                'activityCount' => $response['activityCount'],
                'quizCount' => $response['quizCount'],
                'course' => $response['course'],
                'syllabus' => $response['syllabus'],
                ];

                return response()->json($data);

            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
        
                return response()->json(['errors' => $errors], 422);
            }

    }

    public function update_syllabus(Course $course, Request $request)
    {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {
                    $syllabusData = $request->validate([
                        'topic_id' => ['required'],
                        'topic_title' => ['required'],
                        'category' => ['required'],
                    ]);
            
                    Syllabus::where('syllabus_id', $request->input('syllabus_id'))
                        ->where('course_id', $course->course_id)
                        ->update($syllabusData);
            
                    $syllabus = Syllabus::where('syllabus_id', $request->input('syllabus_id'))
                        ->where('course_id', $course->course_id)
                        ->first();
            
                    if ($syllabusData['category'] == 'LESSON') {
                        $lessonData = [
                            'syllabus_id' => $syllabus->syllabus_id,
                            'course_id' => $syllabus->course_id,
                            'topic_id' => $syllabus->topic_id,
                            'lesson_title' => $syllabus->topic_title,
                        ];
            
                        Lessons::updateOrCreate(
                            ['syllabus_id' => $syllabus->syllabus_id, 'course_id' => $syllabus->course_id],
                            $lessonData
                        );
                    } elseif ($syllabusData['category'] == 'ACTIVITY') {
                        $activityData = [
                            'syllabus_id' => $syllabus->syllabus_id,
                            'course_id' => $syllabus->course_id,
                            'topic_id' => $syllabus->topic_id,
                            'activity_title' => $syllabus->topic_title,
                        ];
            
                        Activities::updateOrCreate(
                            ['syllabus_id' => $syllabus->syllabus_id, 'course_id' => $syllabus->course_id],
                            $activityData
                        );
                    } else {
                        $quizData = [
                            'syllabus_id' => $syllabus->syllabus_id,
                            'course_id' => $syllabus->course_id,
                            'topic_id' => $syllabus->topic_id,
                            'quiz_title' => $syllabus->topic_title,
                        ];
            
                        Quizzes::updateOrCreate(
                            ['syllabus_id' => $syllabus->syllabus_id, 'course_id' => $syllabus->course_id],
                            $quizData
                        );
                    }
            
                    session()->flash('message', 'Syllabus updated Successfully');
                    return response()->json(['message' => 'Course updated successfully', 'redirect_url' => "/admin/courseManage/content/$course->course_id"]);
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
                    return response()->json(['errors' => $errors], 422);
                }


            } else {
                session()->flash('message', 'You cannot upload file');
                $data = [
                    'message' => 'You cannot You cannot upload file',
                    'redirect_url' => '/admin/courseManage/',
                ];
    
                return response()->json($data);
            }
            }  else {
                return redirect('/admin');
            }
    }


    public function update_syllabus_add_new(Course $course, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {
                    $syllabusData = $request->validate([
                        'topic_id' => ['required'],
                        'topic_title' => ['required'],
                        'category' => ['required'],
                    ]);
            
                    $syllabus = Syllabus::create([
                        'topic_id' => $syllabusData['topic_id'],
                        'topic_title' => $syllabusData['topic_title'],
                        'category' => $syllabusData['category'],
                        'course_id' => $course->course_id,
                    ]);
            
                    if ($syllabusData['category'] == 'LESSON') {
                        Lessons::create([
                            'syllabus_id' => $syllabus->syllabus_id,
                            'course_id' => $syllabus->course_id,
                            'topic_id' => $syllabus->topic_id,
                            'lesson_title' => $syllabus->topic_title,
                        ]);
                    } elseif ($syllabusData['category'] == 'ACTIVITY') {
                        Activities::create([
                            'syllabus_id' => $syllabus->syllabus_id,
                            'course_id' => $syllabus->course_id,
                            'topic_id' => $syllabus->topic_id,
                            'activity_title' => $syllabus->topic_title,
                        ]);
                    } else {
                        Quizzes::create([
                            'syllabus_id' => $syllabus->syllabus_id,
                            'course_id' => $syllabus->course_id,
                            'topic_id' => $syllabus->topic_id,
                            'quiz_title' => $syllabus->topic_title,
                        ]);
                    }
            
                    session()->flash('message', 'Syllabus updated Successfully');
                    return response()->json(['message' => 'Course updated successfully', 'redirect_url' => "/admin/courseManage/content/$course->course_id"]);
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
                    return response()->json(['errors' => $errors], 422);
                }

        } else {
            session()->flash('message', 'You cannot upload file');
            $data = [
                'message' => 'You cannot You cannot upload file',
                'redirect_url' => '/admin/courseManage/',
            ];

            return response()->json($data);
        }
        }  else {
            return redirect('/admin');
        }
        
    }


    public function update_syllabus_delete(Course $course, Request $request) {

        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {
                    $syllabusId = $request->input('fetch_syllabus_id');
                    $syllabus = DB::table('syllabus')
                        ->select(
                            'syllabus_id',
                            'course_id',
                            'topic_id',
                            'topic_title',
                            'category'
                        )
                        ->where('syllabus_id', $syllabusId)
                        // ->where('course_id', $course->course_id)
                        ->first();
            
                    if ($syllabus) {
                        if ($syllabus->category == 'LESSON') {
                            DB::table('lessons')
                                ->where('syllabus_id', $syllabusId)
                                ->where('course_id', $course->course_id)
                                ->delete();
                        } elseif ($syllabus->category == 'ACTIVITY') {
                            DB::table('activities')
                                ->where('syllabus_id', $syllabusId)
                                ->where('course_id', $course->course_id)
                                ->delete();
                        } elseif ($syllabus->category == 'QUIZ') {
                            DB::table('quizzes')
                                ->where('syllabus_id', $syllabusId)
                                ->where('course_id', $course->course_id)
                                ->delete();
                        }
            
                        DB::table('syllabus')
                            ->where('syllabus_id', $syllabusId)
                            ->where('course_id', $course->course_id)
                            ->delete();
            
                        session()->flash('message', 'Topic deleted Successfully');
                        return response()->json(['message' => 'Topic Deleted successfully', 'redirect_url' => "/admin/courseManage/content/$course->course_id"]);
                    } else {
                        return response()->json(['error' => 'Syllabus not found'], 404);
                    }
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
                    return response()->json(['errors' => $errors], 422);
                } 

        } else {
            session()->flash('message', 'You cannot upload file');
            $data = [
                'message' => 'You cannot You cannot upload file',
                'redirect_url' => '/admin/courseManage/',
            ];

            return response()->json($data);
        }
        }  else {
            return redirect('/admin');
        }
    }



    public function view_lesson(Course $course, Syllabus $syllabus, $topic_id) {

        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {

                    $lessonInfo = DB::table('lessons')
                        ->select(
                            'lesson_id',
                            'course_id',
                            'syllabus_id',
                            'topic_id',
                            'lesson_title',
                            'picture',
                            'duration',
                        )
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id', $syllabus->syllabus_id)
                        ->where('topic_id', $topic_id)
                        ->first();
                            // dd($lessonInfo);

                            if ($lessonInfo === null) {
                                // Set $activityContent to null or an empty array if it's appropriate
                                $lessonContent = null; // or $activityContent = [];
                            
                                // You can also provide a message to indicate that no data was found
                                session()->flash('message', 'Please Save the Syllabus First');
                                return redirect("/admin/courseManage/content/$course->course_id");

                            } else {
                                // Fetch $activityContent as you normally would
                                $lessonContent = DB::table('lesson_content')
                            ->select(
                                'lesson_content_id',
                                'lesson_id',
                                'lesson_content_title',
                                'lesson_content',
                                'lesson_content_order',
                                'picture',
                                'video_url'
                            )
                            ->where('lesson_id', $lessonInfo->lesson_id)
                            ->orderBy('lesson_content_order', 'ASC')
                            ->get();

                            $durationInSeconds = $lessonInfo->duration;
                            $hours = floor($durationInSeconds / 3600);
                            $minutes = floor(($durationInSeconds % 3600) / 60);
                            $formattedDuration = sprintf("%02d:%02d", $hours, $minutes);
                            }


                    

                                // dd($lessonContent);

                    $response = $this->course_content($course);

                    session(['lesson_data' => [
                        'lessonInfo' => $lessonInfo,
                        'lessonContent' => $lessonContent,
                        'courseData' => $response,
                        'title' => 'Course Lesson',
                    ]]);

                    return view('adminCourse.courseLesson')->with([
                        'title' => 'Course Lesson',
                        'scripts' => ['AD_lesson_manage.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'lessonInfo' => $lessonInfo,
                        'lessonContent' => $lessonContent,
                        'formattedDuration' => $formattedDuration,
                        'admin' => $adminSession,
                        // 'instructor' => $response['instructor'],
                    ]);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }
            }  else {
                return view('error.error');
            }
        }  else {
            return redirect('/admin');
        }

    }



    public function lesson_content_json (Course $course, Syllabus $syllabus, $topic_id) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {
                    $lessonInfo = DB::table('lessons')
                            ->select(
                                'lesson_id',
                                'course_id',
                                'syllabus_id',
                                'topic_id',
                                'lesson_title',
                                'picture',
                                'duration',
                            )
                            ->where('course_id', $course->course_id)
                            ->where('syllabus_id', $syllabus->syllabus_id)
                            ->where('topic_id', $topic_id)
                            ->first();
    
                    $lessonContent = DB::table('lesson_content')
                            ->select(
                                'lesson_content_id',
                                'lesson_id',
                                'lesson_content_title',
                                'lesson_content',
                                'lesson_content_order',
                                'picture',
                                'video_url',
                            )
                            ->where('lesson_id', $lessonInfo->lesson_id)
                            ->orderBy('lesson_content_order', 'ASC')
                            ->get();
    
                            $durationInSeconds = $lessonInfo->duration;
                            $hours = floor($durationInSeconds / 3600);
                            $minutes = floor(($durationInSeconds % 3600) / 60);
                            $formattedDuration = sprintf("%02d:%02d", $hours, $minutes);
                    $response = $this->course_content($course);
    
    
                    $data = [    
                    'title' => 'Course Content',
                    'scripts' => ['instructor_course_content_syllabus.js'],
                    'lessonCount' => $response['lessonCount'],
                    'activityCount' => $response['activityCount'],
                    'quizCount' => $response['quizCount'],
                    'course' => $response['course'],
                    'syllabus' => $response['syllabus'],
                    'lessonInfo' => $lessonInfo,
                    'lessonContent' => $lessonContent,
                    'formattedDuration' => $formattedDuration,
                    ];
    
                    return response()->json($data);
    
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }

        } else {
            session()->flash('message', 'You cannot upload file');
            $data = [
                'message' => 'You cannot You cannot upload file',
                'redirect_url' => '/admin/courseManage/',
            ];

            return response()->json($data);
        }
        }  else {
            return redirect('/admin');
        }

    }

    public function addCompletionTime(Course $course, Syllabus $syllabus, $topic_id, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            

        try {
            $timeDuration = $request->input('secondsTimeCompletion');

            DB::table('lessons')
            ->where('course_id', $course->course_id)
            ->where('syllabus_id', $syllabus->syllabus_id)
            ->where('topic_id', $topic_id)
            ->update([
                'duration' => $timeDuration
            ]);

            return response()->json(['message' => 'Estimated Time of Completion Added']);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    } else {
        session()->flash('message', 'You cannot upload file');
        $data = [
            'message' => 'You cannot You cannot upload file',
            'redirect_url' => '/admin/courseManage/',
        ];

        return response()->json($data);
    }
    }  else {
        return redirect('/admin');
    }
    }


    public function update_lesson_title(Course $course, Syllabus $syllabus, Request $request, $topic_id, $lesson_id) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
        try {

            // Validate the request...
            $updated_values = $request->validate([
                'lesson_title' => ['required'],
            ]);
            $updated_values2 = $request->validate([
                'topic_title' => ['required'],
            ]);


            DB::table('lessons')
                ->where('lesson_id', $lesson_id)
                ->update($updated_values);

            DB::table('syllabus')
                ->where('syllabus_id', $syllabus->syllabus_id)
                ->where('topic_id', $topic_id)
                ->update($updated_values2);

        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    } else {
        session()->flash('message', 'You cannot update the data');
        $data = [
            'message' => 'You cannot update the data',
            'redirect_url' => '/admin/courseManage/',
        ];

        return response()->json($data);
    }
    }  else {
        return redirect('/admin');
    }
    }

    public function update_lesson_picture(Course $course, Syllabus $syllabus, Request $request, $topic_id, Lessons $lesson) {
        try {

            $lessonData = DB::table('lessons')
            ->select(
                'picture'
            )
            ->where('lesson_id' , $lesson->lesson_id)
            ->first();

            if($lessonData->picture !== null) {
                $relativeFilePath = str_replace('public/', '', $lesson->picture);
                
                if (Storage::disk('public')->exists($relativeFilePath)) {
                    // Storage::disk('public')->delete($relativeFilePath);
                    $specifiedDir = explode('/', $relativeFilePath);
                    array_pop($specifiedDir);

                    $dirPath = implode('/', $specifiedDir);

                    // dd($dirPath);
                    if (Storage::disk('public')->exists($relativeFilePath)) {
                        Storage::disk('public')->delete($relativeFilePath);
                    }
                }
            }
                

            $pictureData = $request->validate([
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            $folderName = "{$course->course_id} {$course->course_name}";
            $folderName = Str::slug($folderName, '_');
            $fileName = time() . ' - '. $course->course_name . ' - ' . $pictureData['picture']->getClientOriginalName() . '.png';
            $fileName = Str::slug($fileName, '-');
            $folderPath = 'courses/' . $folderName . '/pictures';

            $filePath = $pictureData['picture']->storeAs($folderPath, $fileName, 'public');

            Lessons::where('lesson_id' , $lesson->lesson_id)
            ->update(['picture' => $filePath]);

            if(!Storage::exists($folderPath)) { 
            Storage::makeDirectory($folderPath);
        }


        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }


    public function update_lesson_content(Lessons $lesson, LessonContents $lesson_content, Request $request) {
        try {
            $updated_values = $request->validate([
                'lesson_content_title' => ['nullable'],
                'lesson_content' => ['nullable'],
            ]);

            DB::table('lesson_content')
                ->where('lesson_id', $lesson->lesson_id)
                ->where('lesson_content_id', $lesson_content->lesson_content_id)
                ->update($updated_values);

            return response()->json();

        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    } 


    public function delete_lesson_content (Lessons $lesson, LessonContents $lesson_content) {
        try {
            DB::table('lesson_content')
                ->where('lesson_id', $lesson->lesson_id)
                ->where('lesson_content_id', $lesson_content->lesson_content_id)
                ->delete();

        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }

    public function save_lesson_content(Course $course, Syllabus $syllabus, $topic_id, Lessons $lesson, Request $request) {
        try {

            $lessonContentData = $request->validate([
                'lesson_content_id' => ['required'],
                'lesson_content_title' => ['required'],
                'lesson_content' => ['required'],
                'lesson_content_order' => ['required']
            ]);

            LessonContents::where('lesson_id', $lesson->lesson_id)
                        ->where('lesson_content_id', $lessonContentData['lesson_content_id'])
                        ->update($lessonContentData);


                        $reportController = new PDFGenerationController();

                        $reportController->courseLessons($course, $syllabus, $topic_id, $lesson);


            session()->flash('message', 'Lesson Content updated Successfully');
            return response()->json(['message' => 'Lesson Content updated successfully', 'redirect_url' => "/admin/courseManage/content/$course->course_id/$syllabus->syllabus_id/lesson/$topic_id"]);
                        
                    
            } catch (ValidationException $e) {
                // dd($e->getMessage());
                $errors = $e->validator->errors();        
                return response()->json(['errors' => $errors], 422);
            }
    }


    public function save_add_lesson_content(Course $course, Syllabus $syllabus, $topic_id, Lessons $lesson, Request $request) {

        try{
        $lessonContentData = $request->validate([
            'lesson_content_title' => ['nullable'],
            'lesson_content' => ['nullable'],
            'lesson_content_order' => ['required']
        ]);

        $lessonContent = LessonContents::create([
            'lesson_content_title' => $lessonContentData['lesson_content_title'],
            'lesson_content' => $lessonContentData['lesson_content'],
            'lesson_id' => $lesson->lesson_id,
            'lesson_content_order' => $lessonContentData['lesson_content_order']
        ]);

        $reportController = new PDFGenerationController();

        $reportController->courseLessons($course, $syllabus, $topic_id, $lesson);


        session()->flash('message', 'Lesson Content updated Successfully');
        return response()->json(['message' => 'Lesson Content updated successfully', 'redirect_url' => "/admin/courseManage/content/$course->course_id/$syllabus->syllabus_id/lesson/$topic_id"]);
                    
                
        } catch (ValidationException $e) {
            // dd($e->getMessage());
            $errors = $e->validator->errors();        
            return response()->json(['errors' => $errors], 422);
        }
    }

    public function lesson_content_store_file(Course $course, Syllabus $syllabus, $topic_id, Lessons $lesson, LessonContents $lesson_content, Request $request) {
        try {


            $lessonContentData = DB::table('lesson_content')
            ->select(
                'picture'
            )
            ->where('lesson_content_id' , $lesson_content->lesson_content_id)
            ->first();

            if($lessonContentData->picture !== null) {
                $relativeFilePath = str_replace('public/', '', $lesson_content->picture);
                
                if (Storage::disk('public')->exists($relativeFilePath)) {
                    // Storage::disk('public')->delete($relativeFilePath);
                    $specifiedDir = explode('/', $relativeFilePath);
                    array_pop($specifiedDir);

                    $dirPath = implode('/', $specifiedDir);

                    // dd($dirPath);
                    if (Storage::disk('public')->exists($relativeFilePath)) {
                        Storage::disk('public')->delete($relativeFilePath);
                    }
                }

            }

            $pictureData = $request->validate([
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            $folderName = "{$course->course_id} {$course->course_name}";
            $folderName = Str::slug($folderName, '_');
            $pictureName = Str::slug(str_replace(' ', '', $pictureData['picture']->getClientOriginalName()), '-');
            $fileName = time() . ' - '. $course->course_name . ' - ' . $pictureName . '.png';
            $folderPath = 'courses/' . $folderName . '/pictures';

            $filePath = $pictureData['picture']->storeAs($folderPath, $fileName, 'public');

            LessonContents::where('lesson_content_id' , $lesson_content['lesson_content_id'])
            ->update(['picture' => $filePath]);

            if(!Storage::exists($folderPath)) { 
            Storage::makeDirectory($folderPath);
        }


        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }
    
    
    public function lesson_content_delete_file(Course $course, Syllabus $syllabus, $topic_id, Lessons $lesson, LessonContents $lesson_content) {
        try {

            $relativeFilePath = str_replace('public/', '', $lesson_content->picture);
            if (Storage::disk('public')->exists($relativeFilePath)) {
                // Storage::disk('public')->delete($relativeFilePath);
                $specifiedDir = explode('/', $relativeFilePath);
                array_pop($specifiedDir);

                $dirPath = implode('/', $specifiedDir);

                // dd($dirPath);
                if (Storage::disk('public')->exists($relativeFilePath)) {
                    Storage::disk('public')->delete($relativeFilePath);
                }
            }

            $updatedRow = [
                'picture' => null
            ];
                
            DB::table('lesson_content')
                ->where('lesson_id', $lesson->lesson_id)
                ->where('lesson_content_id', $lesson_content->lesson_content_id)
                ->update($updatedRow);

        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }

    public function lesson_content_embed_url(Course $course, Syllabus $syllabus, $topic_id, Lessons $lesson, LessonContents $lesson_content, Request $request) {
        try {

            $embedUrlData = $request->input('video_url');

            LessonContents::where('lesson_content_id' , $lesson_content['lesson_content_id'])
            ->update(['video_url' => $embedUrlData]);


        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }


    public function lesson_content_delete_url (Course $course, Syllabus $syllabus, $topic_id, Lessons $lesson, LessonContents $lesson_content) {
        try {
            $updatedRow = [
                'video_url' => null
            ];
                
            DB::table('lesson_content')
                ->where('lesson_id', $lesson->lesson_id)
                ->where('lesson_content_id', $lesson_content->lesson_content_id)
                ->update($updatedRow);

        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }



    public function view_lesson_pdf(Course $course, Syllabus $syllabus, $topic_id) {

                try {

                    $lessonInfo = DB::table('lessons')
                        ->select(
                            'lesson_id',
                            'course_id',
                            'syllabus_id',
                            'topic_id',
                            'lesson_title',
                            'picture',
                            'duration',
                        )
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id', $syllabus->syllabus_id)
                        ->where('topic_id', $topic_id)
                        ->first();
                            // dd($lessonInfo);

                            if ($lessonInfo === null) {
                                // Set $activityContent to null or an empty array if it's appropriate
                                $lessonContent = null; // or $activityContent = [];
                            
                                // You can also provide a message to indicate that no data was found
                                session()->flash('message', 'Please Save the Syllabus First');
                                return redirect("/admin/courseManage/content/$course->course_id");

                            } else {
                                // Fetch $activityContent as you normally would
                                $lessonContent = DB::table('lesson_content')
                            ->select(
                                'lesson_content_id',
                                'lesson_id',
                                'lesson_content_title',
                                'lesson_content',
                                'lesson_content_order',
                                'picture',
                                'video_url'
                            )
                            ->where('lesson_id', $lessonInfo->lesson_id)
                            ->orderBy('lesson_content_order', 'ASC')
                            ->get();

                            $durationInSeconds = $lessonInfo->duration;
                            $hours = floor($durationInSeconds / 3600);
                            $minutes = floor(($durationInSeconds % 3600) / 60);
                            $formattedDuration = sprintf("%02d:%02d", $hours, $minutes);
                            }


                    

                                // dd($lessonContent);

                    $response = $this->course_content($course);

                    session(['lesson_data' => [
                        'lessonInfo' => $lessonInfo,
                        'lessonContent' => $lessonContent,
                        'courseData' => $response,
                        'title' => 'Course Lesson',
                    ]]);

                    return view('instructor_course.courseLessonPreview', compact('instructor'))->with([
                        'title' => 'Course Lesson',
                        'scripts' => ['instructor_lesson_manage.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'lessonInfo' => $lessonInfo,
                        'lessonContent' => $lessonContent,
                        'formattedDuration' => $formattedDuration,
                        // 'instructor' => $response['instructor'],
                    ]);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }
            

        // return view('instructor_course.courseLesson')->with('title', 'Course Lesson');
    }


    public function lesson_generate_pdf(Course $course, Syllabus $syllabus, $topic_id, Lessons $lesson)
    {
   
            
            // Retrieve the data from the session
            $lessonData = session('lesson_data');

            

            $lessonInfo = DB::table('lessons')
            ->select(
                'lesson_id',
                'course_id',
                'syllabus_id',
                'topic_id',
                'lesson_title',
                'picture',
                'duration',
            )
            ->where('course_id', $course->course_id)
            ->where('syllabus_id', $syllabus->syllabus_id)
            ->where('topic_id', $topic_id)
            ->first();


            $lessonContent = DB::table('lesson_content')
                            ->select(
                                'lesson_content_id',
                                'lesson_id',
                                'lesson_content_title',
                                'lesson_content',
                                'lesson_content_order',
                                'picture',
                                'video_url'
                            )
                            ->where('lesson_id', $lessonInfo->lesson_id)
                            ->orderBy('lesson_content_order', 'ASC')
                            ->get();

                            $durationInSeconds = $lessonInfo->duration;
                            $hours = floor($durationInSeconds / 3600);
                            $minutes = floor(($durationInSeconds % 3600) / 60);
                            $formattedDuration = sprintf("%02d:%02d", $hours, $minutes);


            // if (!$lessonData) {
            //     // Handle the case where the session data is not found
            //     return response('Session data not found', 500);
            // }
        
            $response = $this->course_content($course);
            // Extract the data you need from the session
            $title = 'Course Lesson';
            $scripts = ['instructor_lesson_manage.js'];
            $courseData = $lessonData['courseData'];
    
            $course = $courseData['course'];
            $syllabus = $courseData['syllabus'];
            $lessonCount = $courseData['lessonCount'];
            $activityCount = $courseData['activityCount'];
            $quizCount = $courseData['quizCount'];
    
            // Render the view with the Blade template
            $html = view('adminCourse.courseLessonPreview')
                ->with([
                    'title' => 'Course Lesson',
                        'scripts' => ['instructor_lesson_manage.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'lessonInfo' => $lessonInfo,
                        'lessonContent' => $lessonContent,
                        'formattedDuration' => $formattedDuration,
                ])
                ->render();
    
            // Generate a unique filename for the PDF
            $filename = $course->course_name . '_lesson_' . $lessonInfo->lesson_id . '.pdf';
    
            // Define the folder path based on the course name
            $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');
            $folderPath = 'courses/' . $folderName . '/documents';
    
            // Check if the file already exists in storage and delete it
            if (Storage::disk('public')->exists($folderPath . '/' . $filename)) {
                Storage::disk('public')->delete($folderPath . '/' . $filename);
            }
    
            // Generate the PDF using Snappy PDF
            $pdf = SnappyPdf::loadHTML($html)
            ->setOption('zoom', 0.8) // Set the scale factor to 80%
            ->output();
    
            // Store the new PDF in the public directory within the course-specific folder
            Storage::disk('public')->put($folderPath . '/' . $filename, $pdf);
    
            // Generate the URL to the stored PDF
            $pdfUrl = URL::to('storage/' . $folderPath . '/' . $filename);
    
            // Provide a download link to the user
            session()->flash('message', 'Course Lesson updated');
            return response()->json(['pdf_url' => $pdfUrl]);
    }
    


    
    public function view_activity(Course $course, Syllabus $syllabus, $topic_id) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {

            $activityInfo = DB::table('activities')
            ->select(
                'activity_id',
                'course_id',
                'syllabus_id',
                'topic_id',
                'activity_title',
            )
            ->where('course_id', $course->course_id)
            ->where('syllabus_id', $syllabus->syllabus_id)
            ->where('topic_id', $topic_id)
            ->first();

        if ($activityInfo === null) {
            // Set $activityContent to null or an empty array if it's appropriate
            $activityContent = null; // or $activityContent = [];
            $activityContentCriteria = null;

            // You can also provide a message to indicate that no data was found
            session()->flash('message', 'Please Save the Syllabus First');
            return redirect("/admin/courseManage/content/$course->course_id");
        } else {
            // Fetch $activityContent as you normally would
            $activityContent = DB::table('activity_content')
                ->select(
                    'activity_content_id',
                    'activity_id',
                    'activity_instructions',
                    'total_score',
                )
                ->where('activity_id', $activityInfo->activity_id)
                ->get();

            // Check if $activityContent is empty, and if so, create a new row
            if ($activityContent->isEmpty()) {
                $newActivityContent = [
                    'activity_id' => $activityInfo->activity_id,
                    'activity_instructions' => 'Default Instructions', // You can set default values here
                    'total_score' => 0, // You can set default values here
                ];
                DB::table('activity_content')->insert($newActivityContent);

                // Fetch the newly inserted row
                $activityContent = DB::table('activity_content')
                    ->where('activity_id', $activityInfo->activity_id)
                    ->get();
            }

            // Check if $activityContentCriteria is empty, and if so, create a new row
            if ($activityContent->isNotEmpty()) {
                $activityContentCriteria = DB::table('activity_content_criteria')
                    ->select(
                        'activity_content_criteria_id',
                        'activity_content_id',
                        'criteria_title',
                        'score'
                    )
                    ->whereIn('activity_content_id', $activityContent->pluck('activity_content_id')->toArray())
                    ->get();

                if ($activityContentCriteria->isEmpty()) {
                    $newActivityContentCriteria = [
                        'activity_content_id' => $activityContent[0]->activity_content_id,
                        'criteria_title' => 'Default Criteria', // You can set default values here
                        'score' => 0, // You can set default values here
                    ];
                    DB::table('activity_content_criteria')->insert($newActivityContentCriteria);

                    // Fetch the newly inserted row
                    $activityContentCriteria = DB::table('activity_content_criteria')
                        ->where('activity_content_id', $activityContent[0]->activity_content_id)
                        ->get();
                }
            }
        }


                    $response = $this->course_content($course);

                    session(['activity_data' => [
                        'activityInfo' => $activityInfo,
                        'activityContent' => $activityContent,
                        'activityContentCriteria' => $activityContentCriteria,
                        'courseData' => $response,
                        'title' => 'Course Lesson',
                    ]]);

                    return view('adminCourse.courseActivity')->with([
                        'title' => 'Course Lesson',
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'scripts' => ['ADActivities.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'activityInfo' => $activityInfo,
                        'activityContent' => $activityContent,
                        'activityContentCriteria' => $activityContentCriteria,
                        // 'instructor' => $response['instructor'],
                        'admin' => $adminSession,
                    ]);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }



            }  else {
                return view('error.error');
            }
                }  else {
                    return redirect('/admin');
                }
        
    }


    public function activity_content_json(Course $course, Syllabus $syllabus, $topic_id) { 
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
                try {

                    $activityInfo = DB::table('activities')
                        ->select(
                            'activity_id',
                            'course_id',
                            'syllabus_id',
                            'topic_id',
                            'activity_title',
                        )
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id', $syllabus->syllabus_id)
                        ->where('topic_id', $topic_id)
                        ->first();

                            if ($activityInfo === null) {
                                // Set $activityContent to null or an empty array if it's appropriate
                                $activityContent = null; // or $activityContent = [];
                                $activityContentCriteria = null;
                            
                                // You can also provide a message to indicate that no data was found
                                session()->flash('message', 'Please Save the Syllabus First');
                                return redirect("/admin/courseManage/content/$course->course_id");

                            } else {
                                // Fetch $activityContent as you normally would
                                $activityContent = DB::table('activity_content')
                                    ->select(
                                        'activity_content_id',
                                        'activity_id',
                                        'activity_instructions',
                                        'total_score',
                                    )
                                    ->where('activity_id', $activityInfo->activity_id)
                                    ->get();

                                    if($activityContent === null) {
                                        $activityContentCriteria = null;
                                    } else {
                                        $activityContentCriteria = DB::table('activity_content_criteria')
                                        ->select(
                                            'activity_content_criteria_id',
                                            'activity_content_id',
                                            'criteria_title',
                                            'score'
                                        )
                                        ->whereIn('activity_content_id', $activityContent->pluck('activity_content_id')->toArray()) // Use pluck to get an array of activity_content_id values
                                        ->get();
                                    }
                               
                            }

                                // dd($lessonContent);

                    $response = $this->course_content($course);

                    session(['activity_data' => [
                        'activityInfo' => $activityInfo,
                        'activityContent' => $activityContent,
                        'activityContentCriteria' => $activityContentCriteria,
                        'courseData' => $response,
                        'title' => 'Course Lesson',
                    ]]);

                    
                    $learnerActivityOutput = DB::table('learner_activity_progress')
                    ->select(
                        'learner_activity_progress.learner_activity_progress_id',
                        'learner_activity_progress.learner_course_id',
                        'learner_activity_progress.learner_id',
                        'learner_activity_progress.course_id',
                        'learner_activity_progress.syllabus_id',
                        'learner_activity_progress.activity_id',
                        'learner_activity_progress.status',
                        'learner_activity_progress.created_at',

                        'learner.learner_fname',
                        'learner.learner_lname',

                        'learner_activity_output.total_score',
                        'learner_activity_output.attempt',
                        'learner_activity_output.mark',
                        'learner_activity_output.created_at',
                    )
                    ->join('learner', 'learner.learner_id', '=', 'learner_activity_progress.learner_id')
                    ->join('learner_activity_output', function ($join) {
                        $join->on('learner_activity_output.learner_course_id', '=', 'learner_activity_progress.learner_course_id')
                            ->on('learner_activity_output.syllabus_id', '=', 'learner_activity_progress.syllabus_id')
                            ->on('learner_activity_output.course_id', '=', 'learner_activity_progress.course_id')
                            ->on('learner_activity_output.activity_id', '=', 'learner_activity_progress.activity_id');
                    })
                    ->where('learner_activity_progress.course_id', $course->course_id)
                    ->where('learner_activity_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_activity_progress.activity_id', $activityInfo->activity_id)
                    ->get();

                      $data = [    
                        'title' => 'Course Lesson',
                        'scripts' => ['ADActivities.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'activityInfo' => $activityInfo,
                        'activityContent' => $activityContent,
                        'activityContentCriteria' => $activityContentCriteria,
                        'learnerActivityContent' => $learnerActivityOutput,
                        ];


                    return response()->json($data);

                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }

            }  else {
                return view('error.error');
            }
                }  else {
                    return redirect('/admin');
                }
    }


    public function update_activity_instructions(Course $course, Syllabus $syllabus, $topic_id, Activities $activity, ActivityContents $activity_content, Request $request) {
        try {
            $updated_values = $request->validate([
                'activity_instructions' => ['required'],
            ]);

            DB::table('activity_content')
                ->where('activity_id', $activity->activity_id)
                ->where('activity_content_id', $activity_content->activity_content_id)
                ->update($updated_values);

        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }


    public function update_activity_score(Course $course, Syllabus $syllabus, $topic_id, Activities $activity, ActivityContents $activity_content, Request $request) {
        try {
            $updated_values = $request->validate([
                'total_score' => ['required'],
            ]);

            DB::table('activity_content')
                ->where('activity_id', $activity->activity_id)
                ->where('activity_content_id', $activity_content->activity_content_id)
                ->update($updated_values);

        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }

    public function update_activity_criteria(Course $course, Syllabus $syllabus, $topic_id, Activities $activity, ActivityContents $activity_content, Request $request) {
        try {
            $updated_criterias = $request->validate([
                'activity_content_id' => ['required'],
                'criteria_title' => ['required'],
                'score' => ['required'],
            ]);

            ActivityContentCriterias::where('activity_content_id', $activity_content->activity_content_id)
            ->delete();

            $activityContentCriteria = ActivityContentCriterias::create($updated_criterias);


        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }



    public function add_activity_criteria(Course $course, Syllabus $syllabus, $topic_id, Activities $activity, ActivityContents $activity_content, Request $request) {
        try {
            $updated_criterias = $request->validate([
                'activity_content_id' => ['required'],
                'criteria_title' => ['required'],
                'score' => ['required'],
            ]);

            $activityContentCriteria = ActivityContentCriterias::create($updated_criterias);


        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }



    public function view_learner_activity_response(Course $course, Syllabus $syllabus, $topic_id, LearnerCourse $learner_course, $attempt) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {

                    $activityData = DB::table('activities')
                    ->select(
                        'activities.activity_id',
                        'activities.course_id',
                        'activities.syllabus_id',
                        'activities.topic_id',
                        'activities.activity_title',
    
                        'activity_content.activity_content_id',
                        'activity_content.activity_instructions',
                        'activity_content.total_score'
                    )
                    ->join('activity_content', 'activity_content.activity_id', '=', 'activities.activity_id')
                    ->where('activities.course_id', $course->course_id)
                    ->where('activities.syllabus_id', $syllabus->syllabus_id)
                    ->where('activities.topic_id', $topic_id)
                    ->first();
    
    
                    $learnerActivityData = DB::table('learner_activity_output')
                    ->select(
                        'learner_activity_output.learner_activity_output_id',
                        'learner_activity_output.learner_course_id',
                        'learner_activity_output.syllabus_id',
                        'learner_activity_output.activity_id',
                        'learner_activity_output.activity_content_id',
                        'learner_activity_output.course_id',
                        'learner_activity_output.answer',
                        'learner_activity_output.total_score',
                        'learner_activity_output.max_attempt',
                        'learner_activity_output.attempt',
                        'learner_activity_output.mark',
                        'learner_activity_output.remarks',
                        'learner_activity_output.created_at',
    
                        'learner_course.learner_id',
    
                        'learner.learner_fname',
                        'learner.learner_lname'
                    )
                    ->join('learner_course', 'learner_course.learner_course_id', '=', 'learner_activity_output.learner_course_id')
                    ->join('learner', 'learner.learner_id', '=', 'learner_course.learner_id')
                    ->where('learner_activity_output.learner_course_id', $learner_course->learner_course_id)
                    ->where('learner_activity_output.course_id', $course->course_id)
                    ->where('learner_activity_output.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_activity_output.activity_id', $activityData->activity_id)
                    ->where('learner_activity_output.activity_content_id', $activityData->activity_content_id)
                    ->where('learner_activity_output.attempt', $attempt)
                    ->first();
    
                    $updatedAttempt = $attempt + 1;
                    $learnerActivityData_2nd = DB::table('learner_activity_output')
                    ->select(
                        'learner_activity_output.learner_activity_output_id',
                        'learner_activity_output.learner_course_id',
                        'learner_activity_output.syllabus_id',
                        'learner_activity_output.activity_id',
                        'learner_activity_output.activity_content_id',
                        'learner_activity_output.course_id',
                        'learner_activity_output.answer',
                        'learner_activity_output.total_score',
                        'learner_activity_output.max_attempt',
                        'learner_activity_output.attempt',
                        'learner_activity_output.mark',
                        'learner_activity_output.remarks',
                        'learner_activity_output.created_at',
    
                        'learner_course.learner_id',
    
                        'learner.learner_fname',
                        'learner.learner_lname'
                    )
                    ->join('learner_course', 'learner_course.learner_course_id', '=', 'learner_activity_output.learner_course_id')
                    ->join('learner', 'learner.learner_id', '=', 'learner_course.learner_id')
                    ->where('learner_activity_output.learner_course_id', $learner_course->learner_course_id)
                    ->where('learner_activity_output.course_id', $course->course_id)
                    ->where('learner_activity_output.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_activity_output.activity_id', $activityData->activity_id)
                    ->where('learner_activity_output.activity_content_id', $activityData->activity_content_id)
                    ->where('learner_activity_output.attempt', $updatedAttempt)
                    ->first();
    
                    $learnerActivityScoreData = DB::table('learner_activity_criteria_score')
                    ->select(
                        'learner_activity_criteria_score.learner_activity_criteria_score_id',
                        'learner_activity_criteria_score.learner_activity_output_id',
                        'learner_activity_criteria_score.activity_content_criteria_id',
                        'learner_activity_criteria_score.activity_content_id',
                        'learner_activity_criteria_score.score',
                        'learner_activity_criteria_score.attempt',
    
                        'activity_content_criteria.criteria_title',
                        'activity_content_criteria.score as criteria_score'
                    )
                    ->join('activity_content_criteria', 'activity_content_criteria.activity_content_criteria_id', '=', 'learner_activity_criteria_score.activity_content_criteria_id')
                    ->where('learner_activity_criteria_score.learner_activity_output_id', $learnerActivityData->learner_activity_output_id)
                    ->where('learner_activity_criteria_score.activity_content_id', $learnerActivityData->activity_content_id)
                    ->where('learner_activity_criteria_score.attempt', $attempt)
                    ->orderBy('learner_activity_criteria_score.activity_content_criteria_id', 'ASC')
                    ->get();
                    
                    // dd($learnerActivityScoreData);
                    $response = $this->course_content($course);
    
                   $data = [
                    'title' => 'Activity Output',
                    'scripts' => ['ADActivities_learnerResponse.js'],
                    'mainBackgroundCol' => '#00693e',
                    'darkenedColor' => '#00693e',
                    'activity' => $activityData,
                    'learnerActivityOutput' => $learnerActivityData,
                    'learnerActivityOutput_2nd' => $learnerActivityData_2nd,
                    'learnerActivityScore' => $learnerActivityScoreData,
                    'course' => $response['course'],
                    'admin' => $adminSession,
                   ];
                    // dd($data);
    
                    return view('adminCourse.courseActivity_viewLearnerResponse')->with($data);
    
                } catch(\Exception $e) {
                    dd($e->getMessage());
                }
            }  else {
                return view('error.error');
            }
                }  else {
                    return redirect('/admin');
                }
    }


    public function learnerResponse_overallScore($learner_activity_output, $learner_course, $activity, $activity_content, $attempt, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
                try {
                    $remarks = $request->input('remarks');
                    $totalScore = $request->input('total_score');
    
    
                    $activityData = DB::table('activity_content')
                    ->select(
                        'activity_content_id',
                        'activity_id',
                        'total_score',
                    )
                    ->where('activity_id', $activity)
                    ->where('activity_content_id', $activity_content)
                    ->first();
    
                    $activityMaxScore = $activityData->total_score;
    
    
                        // Check if the total score is below 50% of the activity's maximum score
                    $passingPercentage = 50;
                    $passingScore = ($activityMaxScore * $passingPercentage) / 100;
    
                    if ($totalScore < $passingScore) {
                        // Set the mark as 'fail'
                        $mark = "FAIL";
                    } else {
                        // Set the mark as 'pass'
                        $mark = "PASS";
                    }   
        
        
                    // Update remarks and total score regardless of the current syllabus status
                    DB::table('learner_activity_output')
                    ->where('learner_activity_output_id', $learner_activity_output)
                    ->where('learner_course_id', $learner_course)
                    ->where('activity_id', $activity)
                    ->where('activity_content_id', $activity_content)
                    ->where('attempt', $attempt)
                    ->update([
                        'remarks' => $remarks,
                        'total_score' => $totalScore,
                        'mark' => $mark,
                    ]);
    
                    
    
                    $learnerActivityOutputData = DB::table('learner_activity_output')
                    ->select(
                        'learner_activity_output_id',
                        'learner_course_id',
                        'activity_id',
                        'syllabus_id',
                        'activity_content_id',
                        'course_id',
                        'attempt',
                        'answer',
                        'total_score',
                        'remarks',
                        'mark'
                    )
                    ->where('learner_activity_output_id', $learner_activity_output)
                    ->where('learner_course_id', $learner_course)
                    ->where('activity_id', $activity)
                    ->where('activity_content_id', $activity_content)
                    ->where('attempt', $attempt)
                    ->first();
    
    
                    $now = Carbon::now();
                    $timestampString = $now->toDateTimeString();
    
                DB::table('learner_activity_progress')
                    ->where('learner_course_id', $learnerActivityOutputData->learner_course_id)
                    ->where('course_id', $learnerActivityOutputData->course_id)
                    ->where('syllabus_id', $learnerActivityOutputData->syllabus_id)
                    ->where('activity_id', $learnerActivityOutputData->activity_id)
                    ->update([
                        'status' => "COMPLETED",
                        'finish_period' => $timestampString,
                    ]);
    
                    // dd($learnerActivityOutputData);
                
                    if($attempt <= 2) {
                        $currentSyllabusStatus = DB::table('learner_syllabus_progress')
                        ->where('learner_course_id', $learner_course)
                        ->where('course_id', $learnerActivityOutputData->course_id)
                        ->where('syllabus_id', $learnerActivityOutputData->syllabus_id)
                        ->value('status');
    
                        if ($currentSyllabusStatus !== 'COMPLETED') {
                            DB::table('learner_syllabus_progress')
                                ->where('learner_course_id', $learnerActivityOutputData->learner_course_id)
                                ->where('course_id', $learnerActivityOutputData->course_id)
                                ->where('syllabus_id', $learnerActivityOutputData->syllabus_id)
                                ->update([
                                    'status' => "COMPLETED",
                                ]);
    
                                $now = Carbon::now();
                                $timestampString = $now->toDateTimeString();
    
                            DB::table('learner_activity_progress')
                                ->where('learner_course_id', $learnerActivityOutputData->learner_course_id)
                                ->where('course_id', $learnerActivityOutputData->course_id)
                                ->where('syllabus_id', $learnerActivityOutputData->syllabus_id)
                                ->where('activity_id', $learnerActivityOutputData->activity_id)
                                ->update([
                                    'status' => "COMPLETED",
                                    'finish_period' => $timestampString,
                                ]);
    
                            $learnerSyllabusProgress = DB::table('learner_syllabus_progress')
                                ->select(
                                    'learner_syllabus_progress_id', 
                                    'syllabus_id', 
                                    'category', 
                                    'status',
                                    )
                                ->where('learner_course_id', $learnerActivityOutputData->learner_course_id)
                                ->where('syllabus_id', $learnerActivityOutputData->syllabus_id)
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
                                    ->where('course_id', $learnerActivityOutputData->course_id)
                                    ->update(['status' => 'NOT YET STARTED']);
                                    
                                    session()->flash('message', "You have finished all of the topics! \n Be ready for the Post Assessment to finish this course!");
                                }
    
    
                            }
                        }
                    }
                    
    
                    $response = [
                        'message' => 'Output Scored Successfully',
                    ];
            
                    return response()->json($response);
        
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
    

            } else {
                session()->flash('message', 'You cannot update the data');
                $data = [
                    'message' => 'You cannot update the data',
                    'redirect_url' => '/admin/courseManage/',
                ];
        
                return response()->json($data);
            }
                }  else {
                    return redirect('/admin');
                }

    }


    public function learnerResponse_criteriaScore ($learner_activity_output, $learner_course,  $activity, $activity_content, $attempt, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {
                    $activity_content_criteria_id = $request->input('activity_content_criteria_id');
                    $learner_activity_criteria_score_id = $request->input('learner_activity_criteria_score_id');
                    $score = $request->input('score');
                    $currentUrl = $request->input('currentUrl');
        
                    DB::table('learner_activity_criteria_score')
                        ->where('activity_content_criteria_id', $activity_content_criteria_id)
                        ->where('learner_activity_criteria_score_id', $learner_activity_criteria_score_id)
                        ->where('activity_content_id', $activity_content)
                        ->update([
                            'score' => $score,
                        ]);
    
                        session()->flash('message', 'Output Scored Successfully');
    
                    $response = [
                        'message' => 'Output Scored Successfully',
                        'redirect_url' => $currentUrl,
                    ];
            
                    return response()->json($response);
        
                } catch(\Exception $e) {
                    dd($e->getMessage());
                }

            } else {
                session()->flash('message', 'You cannot update the data');
                $data = [
                    'message' => 'You cannot update the data',
                    'redirect_url' => '/admin/courseManage/',
                ];

                return response()->json($data);
            }
        }  else {
            return redirect('/admin');
        }
    
    
    }


    private function getLearnerActivityOutputData($learner_activity_output, $learner_course, $activity, $activity_content, $attempt) {
        return DB::table('learner_activity_output')
            ->select(
                'learner_activity_output_id',
                'learner_course_id',
                'syllabus_id',
                'activity_id',
                'activity_content_id',
                'course_id',
                'attempt',
                'total_score',
                'mark',
                'max_attempt',
            )
            ->where('learner_activity_output_id', $learner_activity_output)
            ->where('learner_course_id', $learner_course)
            ->where('activity_id', $activity)
            ->where('activity_content_id', $activity_content)
            ->where('attempt', $attempt)
            ->first();
    }


    public function reattempt_activity($learner_activity_output, $learner_course,  $activity, $activity_content, $attempt, Request $request) {
        try {

            $learnerActivityOutputData = $this->getLearnerActivityOutputData($learner_activity_output, $learner_course, $activity, $activity_content, $attempt);
            $learnerActivityOutputData_2nd = $this->getLearnerActivityOutputData($learner_activity_output, $learner_course, $activity, $activity_content, 2);

            $activityCriteria = DB::table('learner_activity_criteria_score')
            ->select(
                'learner_activity_output_id',
                'activity_content_criteria_id',
                'activity_content_id',
            )
            ->where('learner_activity_output_id', $learner_activity_output)
            ->where('activity_content_id', $activity_content)
            ->get();

            // dd($activityCriteria);

            if ($learnerActivityOutputData_2nd) {
                // If a record with attempt 2 already exists, handle accordingly
                session()->flash('message', 'The learner has already taken the second attempt');
            } else {
                // If conditions for a new attempt are met
                if ($learnerActivityOutputData->total_score !== null && $learnerActivityOutputData->mark) {
                    // Check if a record with attempt 2 already exists with the same criteria
                    $existingAttempt2Data = DB::table('learner_activity_output')
                        ->where('learner_activity_output_id', $learner_activity_output)
                        ->where('learner_course_id', $learner_course)
                        ->where('activity_id', $activity)
                        ->where('activity_content_id', $activity_content)
                        ->where('attempt', 2)
                        ->first();

                        
                    if ($existingAttempt2Data) {
                        // If a record with attempt 2 already exists, don't create a new row
                        session()->flash('message', 'The learner has already taken the second attempt');
                    } else {
                        // If conditions for a new attempt are met, create a new row with attempt 2
                        $newAttemptRow = [
                            'learner_course_id' => $learner_course,
                            'syllabus_id' => $learnerActivityOutputData->syllabus_id,
                            'activity_id' => $activity,
                            'activity_content_id' => $activity_content,
                            'course_id' => $learnerActivityOutputData->course_id,
                            'attempt' => 2, // Fixed attempt value for the new row
                        ];
            
                        LearnerActivityOutput::create($newAttemptRow);

                        $learnerActivityOutputNewDataRow = DB::table('learner_activity_output')
                        ->select(
                            'learner_activity_output_id',
                        )
                        ->where('learner_course_id', $learner_course)
                        ->where('activity_id', $activity)
                        ->where('activity_content_id', $activity_content)
                        ->orderBy('learner_activity_output_id', 'DESC')
                        ->first();

                        foreach ($activityCriteria as $criteria) {
                            $rowData = [
                                'learner_activity_output_id' => $learnerActivityOutputNewDataRow->learner_activity_output_id,
                                'activity_content_id' => $activity_content,
                                'activity_content_criteria_id' => $criteria->activity_content_criteria_id, // Use $criteria instead of $activity
                                'attempt' => 2,
                            ];
                        
                            LearnerActivityCriteriaScore::create($rowData);
                        }

                        session()->flash('message', 'Second Attempt was allowed to this learner');
                    }
                } else {
                    session()->flash('message', 'The learner has taken the maximum attempts');
                }
            }
            
            
            

            // dd($newAttemptRow);
            // Redirect back to the previous page
        return back();

        } catch(\Exception $e) {
            dd($e->getMessage());
        }
    }


    
    public function view_quiz(Course $course, Syllabus $syllabus, $topic_id) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {       

                $quizInfo = DB::table('quizzes')
                    ->select(
                        'quiz_id',
                        'course_id',
                        'syllabus_id',
                        'topic_id',
                        'quiz_title',
                        'duration',
                    )
                    ->where('course_id', $course->course_id)
                    ->where('syllabus_id', $syllabus->syllabus_id)
                    ->where('topic_id', $topic_id)
                    ->first();

                    $quizReference = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',

                        'syllabus.topic_title'
                    )
                    ->join('syllabus', 'syllabus.syllabus_id' , '=', 'quiz_reference.syllabus_id')
                    ->where('quiz_reference.quiz_id' , $quizInfo->quiz_id)
                    ->get();

                $response = $this->course_content($course);

                session(['quiz_data' => [
                    'quizInfo' => $quizInfo,
                    'quizReference' => $quizReference,
                    // 'activityContent' => $activityContent,
                    // 'activityContentCriteria' => $activityContentCriteria,
                    'courseData' => $response,
                    'title' => 'Course Quiz',
                ]]);

                    $data = [
                        'title' => 'Course Quiz',
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'scripts' => ['AD_quiz_manage.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'quizInfo' => $quizInfo,
                        'quizReference' => $quizReference,
                        'admin' => $adminSession,
                        // 'instructor' => $response['instructor'],
                    ];

                    // dd($data);
            
                return view('adminCourse.courseQuizOverview')->with($data);

            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
        
                return response()->json(['errors' => $errors], 422);
            }

            }  else {
                return view('error.error');
            }
        }  else {
            return redirect('/admin');
        }

    }


    
    public function quiz_info_json (Course $course, Syllabus $syllabus, $topic_id) {

        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {       


                    $quizInfo = DB::table('quizzes')
                        ->select(
                            'quiz_id',
                            'course_id',
                            'syllabus_id',
                            'topic_id',
                            'quiz_title',
                            'duration',
                        )
                        ->where('course_id', $course->course_id)
                        ->where('syllabus_id', $syllabus->syllabus_id)
                        ->where('topic_id', $topic_id)
                        ->first();
    
                        $quizReference = DB::table('quiz_reference')
                        ->select(
                            'quiz_reference.quiz_reference_id',
                            'quiz_reference.quiz_id',
                            'quiz_reference.course_id',
                            'quiz_reference.syllabus_id',
    
                            'syllabus.syllabus_id',
                            'syllabus.topic_title'
                        )
                        ->join('syllabus', 'syllabus.syllabus_id' , '=', 'quiz_reference.syllabus_id')
                        ->where('quiz_reference.quiz_id' , $quizInfo->quiz_id)
                        ->get();
    
    
                        $syllabusData = DB::table('syllabus')
                        ->select(
                            "syllabus_id",
                            "topic_id",
                            "course_id",
                            "topic_title",
                            "category"
                        )
                        ->where('course_id', $course->course_id)
                        ->where('category', 'LESSON')
                        ->orderBy('topic_id', 'ASC')
                        ->get();
    
                        $learnerQuizOutputs = DB::table('learner_quiz_progress')
                        ->select(
                            'learner_quiz_progress.learner_quiz_progress_id',
                            'learner_quiz_progress.learner_course_id',
                            'learner_quiz_progress.learner_id',
                            'learner_quiz_progress.course_id',
                            'learner_quiz_progress.syllabus_id',
                            'learner_quiz_progress.quiz_id',
                            'learner_quiz_progress.attempt',
                            'learner_quiz_progress.score',
                            'learner_quiz_progress.remarks',
                            'learner_quiz_progress.updated_at',
    
                            'learner.learner_fname',
                            'learner.learner_lname',
    
                            DB::raw('COUNT(quiz_content.question_id) as question_count') // Use COUNT function here
                        )
                        ->join('learner', 'learner_quiz_progress.learner_id', '=', 'learner.learner_id')
                        ->leftJoin('quiz_content', 'quiz_content.quiz_id', '=', 'learner_quiz_progress.quiz_id')
                        ->where('learner_quiz_progress.course_id', $course->course_id)
                        ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                        ->where('learner_quiz_progress.quiz_id', $quizInfo->quiz_id)
                        ->groupBy(
                            'learner_quiz_progress.learner_quiz_progress_id',
                            'learner_quiz_progress.learner_course_id',
                            'learner_quiz_progress.learner_id',
                            'learner_quiz_progress.course_id',
                            'learner_quiz_progress.syllabus_id',
                            'learner_quiz_progress.quiz_id',
                            'learner_quiz_progress.attempt',
                            'learner_quiz_progress.score',
                            'learner_quiz_progress.remarks',
                            'learner_quiz_progress.updated_at',
                            'learner.learner_fname',
                            'learner.learner_lname'
                        )
                        ->get();
    
    
                    $response = $this->course_content($course);
    
                    session(['quiz_data' => [
                        'quizInfo' => $quizInfo,
                        'quizReference' => $quizReference,
                        'syllabusData' => $syllabusData,
                        // 'activityContent' => $activityContent,
                        // 'activityContentCriteria' => $activityContentCriteria,
                        'courseData' => $response,
                        'title' => 'Course Quiz',
                    ]]);
    
                        $data = [
                            'title' => 'Course Quiz',
                            'mainBackgroundCol' => '#00693e',
                            'darkenedColor' => '#00693e',
                            'scripts' => ['instructor_quiz_manage.js'],
                            'lessonCount' => $response['lessonCount'],
                            'activityCount' => $response['activityCount'],
                            'quizCount' => $response['quizCount'],
                            'course' => $response['course'],
                            'syllabus' => $response['syllabus'],
                            'quizInfo' => $quizInfo,
                            'quizReference' => $quizReference,
                            'syllabusData' => $syllabusData,
                            'learnerQuizOutputData' => $learnerQuizOutputs,
                        ];
    
                        // dd($data);
            return response()->json($data);
    
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
    
                return response()->json(['errors' => $errors], 422);
            }

            } else {
                session()->flash('message', 'You cannot update the data');
                $data = [
                    'message' => 'You cannot update the data',
                    'redirect_url' => '/admin/courseManage/',
                ];
        
                return response()->json($data);
            }
                }  else {
                    return redirect('/admin');
                }
    }


    public function manage_add_reference (Course $course, Syllabus $syllabus, $topic_id, Quizzes $quiz, Request $request) {
        try {
            $newReference = $request->validate([
                'quiz_id' => ['required'],
                'course_id' => ['required'],
                'syllabus_id' => ['required'],
            ]);


            $quizReference = QuizReferences::create($newReference);

        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }


    public function manage_update_reference (Course $course, Syllabus $syllabus, $topic_id, Quizzes $quiz, Request $request) {
        try {
            $newReference = $request->validate([
                'quiz_id' => ['required'],
                'course_id' => ['required'],
                'syllabus_id' => ['required'],
            ]);

            QuizReferences::where('quiz_id', $request->quiz_id)
            ->delete();

            $quizReference = QuizReferences::create($newReference);


        }catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }
    }


    public function manage_update_duration(Course $course, Syllabus $syllabus, $topic_id, Quizzes $quiz, Request $request) {
        
        try {

            $duration = $request->input('duration_ms');

            DB::table('quizzes')
            ->where('quiz_id', $quiz->quiz_id)
            ->where('course_id' , $course->course_id)
            ->where('syllabus_id', $syllabus->syllabus_id)
            ->update([
                'duration' => $duration,
            ]);

            $data = [
                'message' => 'quiz duration successfully updated'
            ];

            return response()->json($data);

        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
        
            return response()->json(['errors' => $errors], 422);
        }

    }


    public function quiz_content (Course $course, Syllabus $syllabus, $topic_id, Quizzes $quiz) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {
    
                    $quizInfo = DB::table('quizzes')
                    ->select(
                        'quiz_id',
                        'course_id',
                        'syllabus_id',
                        'topic_id',
                        'quiz_title',
                    )
                    ->where('course_id', $course->course_id)
                    ->where('syllabus_id', $syllabus->syllabus_id)
                    ->where('topic_id', $topic_id)
                    ->first();
    
                    $quizReference = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',
    
                        'syllabus.topic_title'
                    )
                    ->join('syllabus', 'syllabus.syllabus_id' , '=', 'quiz_reference.syllabus_id')
                    ->where('quiz_reference.quiz_id' , $quizInfo->quiz_id)
                    ->get();
    
    
                    $questionsData = DB::table('questions')
                    ->select(
                        'questions.question_id',
                        'questions.syllabus_id',
                        'questions.course_id',
                        'questions.question',
                        'questions.category',
    
                        'question_answer.answer',
                        'question_answer.isCorrect'
                    )
                    ->join('question_answer', 'question_answer.question_id', '=', 'questions.question_id')
                    ->where('questions.course_id', $course->course_id)
                    ->get();
    
                $response = $this->course_content($course);
    
                session(['quiz_data' => [
                    'quizInfo' => $quizInfo,
                    'quizReference' => $quizReference,
                    'courseData' => $response,
                    'title' => 'Course Quiz',
                ]]);
    
                    $data = [
                        'title' => 'Course Quiz',
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'scripts' => ['AD_quiz_builder.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'quizInfo' => $quizInfo,
                        'quizReference' => $quizReference,
                        'questionsData' => $questionsData,
                        // 'instructor' => $response['instructor'],
                        'admin' => $adminSession,
                    ];
    
                
                    return view('adminCourse.courseQuizContent')->with($data);
    
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }

            }  else {
                return view('error.error');
            }
        }  else {
            return redirect('/admin');
        }    
    }


    public function quiz_content_json (Course $course, Syllabus $syllabus, $topic_id, Quizzes $quiz) {
  
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {

                    $quizInfo = DB::table('quizzes')
                    ->select(
                        'quiz_id',
                        'course_id',
                        'syllabus_id',
                        'topic_id',
                        'quiz_title',
                    )
                    ->where('course_id', $course->course_id)
                    ->where('syllabus_id', $syllabus->syllabus_id)
                    ->where('topic_id', $topic_id)
                    ->first();
    
                    $quizReference = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',
    
                        'syllabus.topic_title'
                    )
                    ->join('syllabus', 'syllabus.syllabus_id' , '=', 'quiz_reference.syllabus_id')
                    ->where('quiz_reference.quiz_id' , $quizInfo->quiz_id)
                    ->get();
    
                    $quizContentData = DB::table('quiz_content')
                    ->select(
                        'quiz_content.quiz_content_id',
                        // 'quiz_content.syllabus_id',
                        'quiz_content.course_id',
                        'quiz_content.question_id',
                        'questions.syllabus_id',
                        'questions.question',
                        'questions.category',
                        'syllabus.topic_title',
                        DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers'),
                        DB::raw('JSON_ARRAYAGG(question_answer.isCorrect) as isCorrect')
                    )
                    ->join('questions', 'questions.question_id', '=', 'quiz_content.question_id')
                    ->join('syllabus', 'syllabus.syllabus_id', '=', 'questions.syllabus_id')
                    ->leftJoin('question_answer', 'question_answer.question_id', '=', 'quiz_content.question_id')
                    ->where('quiz_content.course_id', $course->course_id)
                    ->where('quiz_id', $quizInfo->quiz_id)
                    ->groupBy(
                        'quiz_content.quiz_content_id',
                        'quiz_content.syllabus_id',
                        'quiz_content.course_id',
                        'quiz_content.question_id',
                        'questions.question',
                        'questions.category',
                        'syllabus.topic_title'
                    )
                    ->get();
    
    
    
                    $questionsData = DB::table('questions')
                    ->select(
                        'questions.question_id',
                        'questions.syllabus_id',
                        'questions.course_id',
                        'questions.question',
                        'questions.category',
                        'syllabus.topic_title',
                        DB::raw('JSON_ARRAYAGG(question_answer.answer) as answers'),
                        DB::raw('JSON_ARRAYAGG(question_answer.isCorrect) as isCorrect')
                    )
                    ->join('syllabus', 'syllabus.syllabus_id' , '=', 'questions.syllabus_id')
                    ->leftJoin('question_answer', 'question_answer.question_id', '=', 'questions.question_id')
                    ->where('questions.course_id', $course->course_id)
                    ->groupBy('questions.question_id')
                    ->get();
    
    
                $response = $this->course_content($course);
    
                session(['quiz_data' => [
                    'quizInfo' => $quizInfo,
                    'quizReference' => $quizReference,
                    'courseData' => $response,
                    'title' => 'Course Quiz',
                ]]);
    
                    $data = [
                        'title' => 'Course Quiz',
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'scripts' => ['instructor_quiz_builder.js'],
                        'lessonCount' => $response['lessonCount'],
                        'activityCount' => $response['activityCount'],
                        'quizCount' => $response['quizCount'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'quizInfo' => $quizInfo,
                        'quizReference' => $quizReference,
                        'questionsData' => $questionsData,
                        'quizContent' => $quizContentData,
                        // 'instructor' => $response['instructor'],
                    ];
    
    
                        // dd($data);
                        
    
                    return response()->json($data);
                
                    // return view('instructor_course.courseQuizContent', compact('instructor'))->with($data);
    
                } catch (ValidationException $e) {
                    $errors = $e->validator->errors();
            
                    return response()->json(['errors' => $errors], 422);
                }

            } else {
                session()->flash('message', 'You cannot update the data');
                $data = [
                    'message' => 'You cannot update the data',
                    'redirect_url' => '/admin/courseManage/',
                ];

                return response()->json($data);
            }
        }  else {
            return redirect('/admin');
        }

    }
    

    public function empty_quiz_question(Course $course, Syllabus $syllabus, $topic_id, $quiz_id)
    {
        try {
            DB::beginTransaction();

            DB::table('quiz_content')
                ->where('quiz_id', $quiz_id)
                ->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred while emptying quiz content.']);
        }
    }



    public function add_quiz_question(Course $course, Syllabus $syllabus, $topic_id, $quiz_id, Request $request)
    {
        try {
            // DB::beginTransaction();

            $questionData = [
                'syllabus_id' => $request->input('syllabus_id'),
                'course_id' => $request->input('course_id'),
                'question' => $request->input('question'),
                'category' => $request->input('category'),
            ];

            $questions = Questions::create($questionData);

            $answersData = json_decode($request->input('answer'));
            $isCorrectData = json_decode($request->input('isCorrect'));

            foreach ($answersData as $index => $answer) {
                $questionAnswerData = [
                    'question_id' => $questions->question_id,
                    'answer' => $answer,
                    'isCorrect' => $isCorrectData[$index],
                ];

                QuestionAnswers::create($questionAnswerData);
            }

            $quizContentData = [
                'quiz_id' => $quiz_id,
                'syllabus_id' => $syllabus->syllabus_id,
                'course_id' => $course->course_id,
                'question_id' => $questions->question_id,
            ];

            QuizContents::create($quizContentData);

            // DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in add_quiz_question: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'An error occurred while adding quiz question: ' . $e->getMessage()]);
        }
    }


    
    public function update_quiz_question(Course $course, Syllabus $syllabus, $topic_id, $quiz_id, Request $request)
    {
        try {
            // DB::beginTransaction();

            $question_id = $request->input('question_id');

            DB::table('questions')
                ->where('question_id', $question_id)
                ->where('course_id', $course->course_id)
                ->update([
                    'syllabus_id' => $request->input('syllabus_id'),
                    'course_id' => $request->input('course_id'),
                    'question' => $request->input('question'),
                    'category' => $request->input('category'),
                ]);

            DB::table('question_answer')
                ->where('question_id', $question_id)
                ->delete();

            $answersData = json_decode($request->input('answer'));
            $isCorrectData = json_decode($request->input('isCorrect'));

            foreach ($answersData as $index => $answer) {
                $questionAnswerData = [
                    'question_id' => $question_id,
                    'answer' => $answer,
                    'isCorrect' => $isCorrectData[$index],
                ];

                QuestionAnswers::create($questionAnswerData);
            }

            $quizContentData = [
                'quiz_id' => $quiz_id,
                'syllabus_id' => $syllabus->syllabus_id,
                'course_id' => $course->course_id,
                'question_id' => $question_id,
            ];

            QuizContents::create($quizContentData);

            // DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in update_quiz_question: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'An error occurred while updating quiz question: ' . $e->getMessage()]);
        }
    }


    public function view_learner_output(Course $course, Syllabus $syllabus, $topic_id, LearnerQuizProgress $learner_quiz_progress) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {  
   
                    $quizInfo = DB::table('quizzes')
                    ->select(
                        'quiz_id',
                        'course_id',
                        'syllabus_id',
                        'topic_id',
                        'quiz_title',
                        'duration',
                    )
                    ->where('course_id', $course->course_id)
                    ->where('syllabus_id', $syllabus->syllabus_id)
                    ->where('topic_id', $topic_id)
                    ->first();

                    $quizReference = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',

                        'syllabus.topic_title'
                    )
                    ->join('syllabus', 'syllabus.syllabus_id' , '=', 'quiz_reference.syllabus_id')
                    ->where('quiz_reference.quiz_id' , $quizInfo->quiz_id)
                    ->get();

                    $response = $this->course_content($course);

                    $learnerQuizProgressData = DB::table('learner_quiz_progress')
                    ->select(
                        'learner_quiz_progress.learner_quiz_progress_id',
                        'learner_quiz_progress.learner_course_id',
                        'learner_quiz_progress.learner_id',
                        'learner_quiz_progress.syllabus_id',
                        'learner_quiz_progress.quiz_id',
                        'learner_quiz_progress.status',
                        'learner_quiz_progress.max_attempt',
                        'learner_quiz_progress.attempt',
                        'learner_quiz_progress.score',
                        'learner_quiz_progress.remarks',
                        'learner_quiz_progress.updated_at',
                        

                        'learner.learner_fname',
                        'learner.learner_lname',
                    )
                    ->join('learner', 'learner_quiz_progress.learner_id', '=', 'learner.learner_id')
                    ->where('learner_quiz_progress.learner_quiz_progress_id', $learner_quiz_progress->learner_quiz_progress_id)
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_quiz_progress.quiz_id', $quizInfo->quiz_id)
                    ->first();
                    

                    $quizQuestionTotalCount = DB::table('learner_quiz_output')
                    ->select(
                        'learner_quiz_output',
                        'quiz_content_id',
                        'quiz_id',
                        'attempts',
                    )
                    ->where('learner_course_id', $learnerQuizProgressData->learner_course_id)
                    ->where('course_id', $course->course_id)
                    ->where('syllabus_id', $syllabus->syllabus_id)
                    ->where('quiz_id', $quizInfo->quiz_id)
                    ->where('attempts', 1)
                    ->count();



                    $data = [
                        'title' => 'Course Quiz',
                        'mainBackgroundCol' => '#00693e',
                        'darkenedColor' => '#00693e',
                        'scripts' => ['AD_quiz_learnerResponse.js'],
                        'course' => $response['course'],
                        'syllabus' => $response['syllabus'],
                        'quizData' => $quizInfo,
                        'quizReferenceData' => $quizReference,
                        'learnerQuizOutputData' => $learnerQuizProgressData,
                        'quizQuestionTotalCount' => $quizQuestionTotalCount,
                        'admin' => $adminSession,
                    ];

                    // dd($data);
            
                return view('adminCourse.courseQuiz_viewLearnerOutput')->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }


            }  else {
                return view('error.error');
            }
        }  else {
            return redirect('/admin');
        }
        
    }



    
    public function view_learner_output_json(Course $course, Syllabus $syllabus, $topic_id, LearnerQuizProgress $learner_quiz_progress) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'COURSE_SUPERVISOR'])) {
            
                try {  

                    $quizInfo = DB::table('quizzes')
                    ->select(
                        'quiz_id',
                        'course_id',
                        'syllabus_id',
                        'topic_id',
                        'quiz_title',
                        'duration',
                    )
                    ->where('course_id', $course->course_id)
                    ->where('syllabus_id', $syllabus->syllabus_id)
                    ->where('topic_id', $topic_id)
                    ->first();

                    $quizReference = DB::table('quiz_reference')
                    ->select(
                        'quiz_reference.quiz_reference_id',
                        'quiz_reference.quiz_id',
                        'quiz_reference.course_id',
                        'quiz_reference.syllabus_id',

                        'syllabus.topic_title'
                    )
                    ->join('syllabus', 'syllabus.syllabus_id' , '=', 'quiz_reference.syllabus_id')
                    ->where('quiz_reference.quiz_id' , $quizInfo->quiz_id)
                    ->get();

                    $learnerQuizProgressData = DB::table('learner_quiz_progress')
                    ->select(
                        'learner_quiz_progress.learner_quiz_progress_id',
                        'learner_quiz_progress.learner_course_id',
                        'learner_quiz_progress.syllabus_id',
                        'learner_quiz_progress.course_id',
                        'learner_quiz_progress.quiz_id',
                        'learner_quiz_progress.status',
                        'learner_quiz_progress.max_attempt',
                        'learner_quiz_progress.attempt',
                        'learner_quiz_progress.score',
                        'learner_quiz_progress.remarks',
                        'learner_quiz_progress.updated_at',
                    )
                    ->where('learner_quiz_progress.learner_quiz_progress_id', $learner_quiz_progress->learner_quiz_progress_id)
                    ->where('learner_quiz_progress.course_id', $course->course_id)
                    ->where('learner_quiz_progress.syllabus_id', $syllabus->syllabus_id)
                    ->where('learner_quiz_progress.quiz_id', $quizInfo->quiz_id)
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
                    ->where('learner_quiz_output.attempts', $learnerQuizProgressData->attempt)
                    ->where('learner_quiz_output.learner_course_id', $learnerQuizProgressData->learner_course_id)
                    ->where('quiz_content.quiz_id', $learnerQuizProgressData->quiz_id)
                    ->where('quiz_content.course_id', $learnerQuizProgressData->course_id)
                    ->where('quiz_content.syllabus_id', $learnerQuizProgressData->syllabus_id)
                    ->groupBy(
                        'learner_quiz_output.learner_quiz_output_id',
                        'learner_quiz_output.quiz_content_id',
                        'quiz_content.course_id',
                        'quiz_content.question_id',
                        'questions.syllabus_id',
                        'questions.question',
                        'questions.category',
                        'correct_answers.correct_answer'
                    )
                    ->get();



                    $data = [
                        'title' => 'Course Quiz',
                        'scripts' => ['instructor_quiz_learnerResponse.js'],
                        'quizReferenceData' => $quizReference,
                        'learnerQuizData' => $learnerQuizProgressData,
                        'learnerQuizOutputData' => $learnerQuizData,

                    ];

                    // dd($data);

                    return response()->json($data);

            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
        
                return response()->json(['errors' => $errors], 422);
            }

    } else {
        session()->flash('message', 'You cannot update the data');
        $data = [
            'message' => 'You cannot update the data',
            'redirect_url' => '/admin/courseManage/',
        ];

        return response()->json($data);
    }
        }  else {
            return redirect('/admin');
        }
    }

}
