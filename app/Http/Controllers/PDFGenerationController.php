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

class PDFGenerationController extends Controller
{
public function courseList() {
    try {
        $courseData = DB::table('course')
            ->select(
                'course.course_id',
                'course.course_name',
                'course.instructor_id',
                'course.created_at',
                'course.course_status',
                'course.course_description',
                DB::raw('CONCAT(instructor.instructor_fname, " ", instructor.instructor_lname) as name')
            )
            ->join('instructor', 'course.instructor_id', 'instructor.instructor_id')
            ->where('course.course_status', 'Approved')
            ->get();

        // Load the HTML view
        $html = view('adminReports.simpleCourseList', [
            'courseData' => $courseData,
            'courseCategory' => 'ALL AVAILABLE'
        ])->render();

        // Setup DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save the PDF to the storage
        $filename = "courseList.pdf";
        $folderPath = 'courses/';
        Storage::disk('public')->put($folderPath . '/' . $filename, $dompdf->output());

        return "File Updated";
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}


public function courseDetails(Course $course) {
    try {
        $courseData = DB::table('course')
            ->select(
                'course.course_id',
                'course.course_name',
                'course.instructor_id',
                'course.created_at',
                'course.course_status',
                'course.course_description',
                DB::raw('CONCAT(instructor.instructor_fname, " ", instructor.instructor_lname) as name')
            )
            ->join('instructor', 'course.instructor_id', 'instructor.instructor_id')
            ->where('course.course_id', $course->course_id)
            ->first();

        $syllabusData = DB::table('syllabus')
            ->select(
                'syllabus_id',
                'topic_id',
                'topic_title',
                'category',
            )
            ->where('course_id', $course->course_id)
            ->get();

        // Load the HTML view
        $html = view('adminReports.courseDetails', [
            'courseData' => $courseData,
            'syllabusData' => $syllabusData,
        ])->render();

        // Setup DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save the PDF to the storage
        $name = Str::slug("{$course->course_name}", '_');
        $filename = $name . '_details.pdf';
        $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');
        $folderPath = 'courses/' . $folderName . '/documents';
        Storage::disk('public')->put($folderPath . '/' . $filename, $dompdf->output());

        return "File Updated";
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}



    public function courseEnrollees($course) {
    try {
        $courseData = DB::table('course')
            ->select(
                'course_name',
                'course_id',
            )->where('course_id', $course)
            ->first();

        $learnerCourseData = DB::table('learner_course_progress')
            ->select(
                'learner_course_progress.learner_course_id',
                'learner_course_progress.learner_id',
                DB::raw('CONCAT(learner.learner_fname, " ", learner_lname) as name'),
                'learner_course.status',
                'learner_course_progress.course_progress',
                'learner_course_progress.start_period',
                'learner_course_progress.finish_period',
            )
            ->join('learner' , 'learner_course_progress.learner_id' , 'learner.learner_id')
            ->join('learner_course', 'learner_course_progress.learner_course_id', 'learner_course.learner_course_id')
            ->where('learner_course_progress.course_id', $course)
            ->get();

        // Load the HTML view
        $html = view('adminReports.courseEnrollees', [
            'learnerCourseData' => $learnerCourseData,
            'course' => $courseData,
        ])->render();

        // Setup DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save the PDF to the storage
        $name = Str::slug("{$courseData->course_name}", '_');
        $filename = $name . '_enrollees.pdf';
        $folderName = Str::slug("{$courseData->course_id} {$courseData->course_name}", '_');
        $folderPath = 'courses/' . $folderName . '/documents';
        Storage::disk('public')->put($folderPath . '/' . $filename, $dompdf->output());

        return "File Updated";
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}




    public function courseLessons(Course $course, Syllabus $syllabus, $topic_id, Lessons $lesson) {
    try {
        $courseData = DB::table('course')
            ->select(
                'course.course_id',
                'course.course_name',
                'course.instructor_id',
                DB::raw('CONCAT(instructor.instructor_fname, " ", instructor.instructor_lname) as name')
            )
            ->join('instructor', 'course.instructor_id', 'instructor.instructor_id')
            ->where('course.course_id', $course->course_id)
            ->first();

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
            )
            ->where('lesson_id', $lessonInfo->lesson_id)
            ->orderBy('lesson_content_order', 'ASC')
            ->get();

        $durationInSeconds = $lessonInfo->duration;
        $hours = floor($durationInSeconds / 3600);
        $minutes = floor(($durationInSeconds % 3600) / 60);
        $formattedDuration = sprintf("%02d:%02d", $hours, $minutes);

        // Load the HTML view
        $html = view('adminReports.courseLesson', [
            'courseData' => $courseData,
            'lessonInfo' => $lessonInfo,
            'lessonContent' => $lessonContent,
            'formattedDuration' => $formattedDuration,
        ])->render();

        // Setup DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save the PDF to the storage
        $name = Str::slug("{$course->course_name}", '_');
        $filename = $name . "_" . $lessonInfo->lesson_title . '_lesson.pdf';
        $folderName = Str::slug("{$course->course_id} {$course->course_name}", '_');
        $folderPath = 'courses/' . $folderName . '/documents';
        Storage::disk('public')->put($folderPath . '/' . $filename, $dompdf->output());

        return "File Updated";
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}


public function courseGradeSheet($course) {
    try {
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
                ->select(
                    DB::raw('COALESCE(ROUND(AVG(IFNULL(learner_post_assessment_progress.score, 0)), 2), 0) as average_score')
                )
                ->where('course_id', $course)
                ->where('learner_course_id', $activityData->learner_course_id)
                ->first();

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
            ->orderBy('activities.topic_id', 'asc')
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
            ->groupBy('quizzes.quiz_id', 'quizzes.course_id', 'quizzes.syllabus_id', 'quizzes.topic_id', 'quizzes.quiz_title')
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

        $html = view('adminReports.courseGradesheet', $data)->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = Str::slug("{$courseData->course_name}", '_') . '_gradesheet.pdf';
        $folderName = Str::slug("{$courseData->course_id} {$courseData->course_name}", '_');
        $folderPath = 'courses/' . $folderName . '/documents';

        Storage::disk('public')->put($folderPath . '/' . $filename, $dompdf->output());

        return "File Updated";
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}


    // public function learnerData($learner) {
    //     try {

    //         $learnerData = DB::table('learner')
    //         ->select(
    //             'learner_id',
    //             'learner_fname',
    //             'learner_lname',
    //             'status',
    //             'learner_bday',
    //             'learner_gender',
    //         )
    //         ->where('learner_id', $learner)
    //         ->first();

    //         $businessData = DB::table('business')
    //         ->select(
    //             'business_name',
    //             'business_address',
    //             'business_owner_name',
    //             'business_category',
    //             'business_classification',
    //             'business_description',
    //             'learner_id',
    //         )
    //         ->where('learner_id', $learner)
    //         ->first();


    //         $html = view('adminReports.learner', [
    //             'learnerData' => $learnerData,
    //             'businessData' => $businessData,
    //         ])->render();

    //         $pdf = PDF::loadHTML($html)
    //         ->setOption('zoom', 1.0);


    //             $name = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
    //             $filename = Str::slug("{$name}", '_') . '_details.pdf';

    //             $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
    //             $folderName = Str::slug($folderName, '_');

    //             $folderPath = 'learners/' . $folderName . '/documents';
                
    //             // Get the PDF content as a string
    //             $pdfContent = $pdf->output();
                
    //             // Save the PDF content to the storage
    //             Storage::disk('public')->put($folderPath . '/' . $filename, $pdfContent);
                
    //             return "File Updated";


            
    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //     }
    // }


    public function learnerData($learner) {
        try {
    
            $learnerData = DB::table('learner')
                ->select(
                    'learner_id',
                    'learner_fname',
                    'learner_lname',
                    'status',
                    'learner_bday',
                    'learner_gender',
                )
                ->where('learner_id', $learner)
                ->first();
    
            $businessData = DB::table('business')
                ->select(
                    'business_name',
                    'business_address',
                    'business_owner_name',
                    'business_category',
                    'business_classification',
                    'business_description',
                    'learner_id',
                )
                ->where('learner_id', $learner)
                ->first();
    
            $html = view('adminReports.learner', [
                'learnerData' => $learnerData,
                'businessData' => $businessData,
            ])->render();
    
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $dompdf = new Dompdf($options);
    
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
    
            $output = $dompdf->output();
    
            $name = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
            $filename = Str::slug("{$name}", '_') . '_details.pdf';
    
            $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
            $folderName = Str::slug($folderName, '_');
    
            $folderPath = 'learners/' . $folderName . '/documents';
    
            // Save the PDF content to the storage
            Storage::disk('public')->put($folderPath . '/' . $filename, $output);
    
            return "File Updated";
    
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    public function learnerCourseData($learner) {
    try {
        $learnerData = DB::table('learner')
            ->select(
                'learner_fname', 
                'learner_lname',
            )
            ->where('learner_id', $learner)
            ->first();

        if (!$learnerData) {
            // Handle case when learner data is not found
            return "Learner data not found";
        }

        $learnerCourseData = DB::table('learner_course')
            ->select(
                'learner_course.learner_course_id',
                'learner_course.course_id',
                'course.course_name',
                'learner_course.status',
                'learner_course_progress.course_progress',
                'learner_course_progress.start_period',
                'learner_course_progress.finish_period'
            )
            ->join('course', 'course.course_id', 'learner_course.course_id')
            ->join('learner_course_progress', 'learner_course_progress.learner_course_id', 'learner_course.learner_course_id')
            ->where('learner_course.learner_id', $learner)
            ->get();

        $html = view('adminReports.learnerEnrolledCourse', [
            'learnerCourseData' => $learnerCourseData,
            'learnerData' => $learnerData,
        ])->render();

        // Dompdf options
        $options = new Options();
        $options->set('isPhpEnabled', true); // Allow PHP code in the HTML template

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Set paper size and orientation
        $dompdf->render();

        $output = $dompdf->output();

        $name = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $filename = Str::slug("{$name}", '_') . '_enrolledCourses.pdf';

        $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $folderName = Str::slug($folderName, '_');

        $folderPath = 'learners/' . $folderName . '/documents';

        // Save the PDF content to the storage
        Storage::disk('public')->put($folderPath . '/' . $filename, $output);

        return "File Updated";

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}



      public function learnerCourseGradeSheet($learner, $course, $learnerCourse) {
        try {

            $learnerData = DB::table('learner')
            ->select(
                'learner_fname',
                'learner_lname',
                'learner_id',
            )
            ->where('learner_id', $learner)
            ->first();
            
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
            ->groupBy('quizzes.quiz_id', 'quizzes.course_id', 'quizzes.syllabus_id', 'quizzes.topic_id', 'quizzes.quiz_title')
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


    $html = view('adminReports.learnerGradesheet', $data)->render();

    
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();


        $name = "{$learnerData->learner_lname} {$learnerData->learner_fname} {$courseData->course_name}";
        $filename = Str::slug("{$name}", '_') . '_learnerGradeSheet.pdf';

        $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $folderName = Str::slug($folderName, '_');

        $folderPath = 'learners/' . $folderName . '/documents';
        
        Storage::disk('public')->put($folderPath . '/' . $filename, $dompdf->output());

        
        return "File Updated";
            
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    
    
public function learnerActivityOutput($learner, $course, $learner_course, $syllabus, $attempt) {
    try {
        $learnerData = DB::table('learner')
            ->select(
                'learner_fname',
                'learner_lname',
                'learner_id',
            )
            ->where('learner_id', $learner)
            ->first();


            $courseData = DB::table('course')
            ->select(
                'course_id',
                'course_name',
            )
            ->where('course_id', $course)
            ->first();
            
            
        // Check if learner data is found
        if (!$learnerData) {
            return "Learner data not found";
        }

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
            ->where('activities.course_id', $course)
            ->where('activities.syllabus_id', $syllabus)
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
            ->where('learner_activity_output.learner_course_id', $learner_course)
            ->where('learner_activity_output.course_id', $course)
            ->where('learner_activity_output.syllabus_id', $syllabus)
            ->where('learner_activity_output.activity_id', $activityData->activity_id)
            ->where('learner_activity_output.activity_content_id', $activityData->activity_content_id)
            ->where('learner_activity_output.attempt', $attempt)
            ->first();

        $updatedAttempt = $attempt + 1;
       

        $learnerActivityScoreData = DB::table('learner_activity_criteria_score')
            ->select(
                'learner_activity_criteria_score.learner_activity_criteria_score_id',
                'learner_activity_criteria_score.learner_activity_output_id',
                'learner_activity_criteria_score.activity_content_criteria_id',
                'learner_activity_criteria_score.activity_content_id',
                'learner_activity_criteria_score.score as learner_score',
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

        $html = view('adminReports.learnerActivityOutput', [
            'learnerActivityOutput' => $learnerActivityData,
            'learnerActivityScore' => $learnerActivityScoreData,
            'learnerData' => $learnerData,
            'activityData' => $activityData,
            'attempt' => $attempt,
            'courseData' => $courseData
        ])->render();

        $options = new Options();
        $options->set('isPhpEnabled', true); // Allow PHP code in the HTML template

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        $name = "{$learnerData->learner_lname} {$learnerData->learner_fname} {$courseData->course_name} {$activityData->activity_title} attempt {$attempt}";
        $filename = Str::slug("{$name}", '_') . '_learnerActivityOutput.pdf';

        $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $folderName = Str::slug($folderName, '_');

        $folderPath = 'learners/' . $folderName . '/documents';

        Storage::disk('public')->put($folderPath . '/' . $filename, $output);

        return "File Updated";

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}


public function learnerQuizOutput($learner, $course, $learner_course, $syllabus, $attempt) {
    try {
        $learnerData = DB::table('learner')
            ->select(
                'learner_fname',
                'learner_lname',
                'learner_id',
            )
            ->where('learner_id', $learner)
            ->first();
            

        // Check if learner data is found
        if (!$learnerData) {
            return "Learner data not found";
        }

        $courseData = DB::table('course')
            ->select(
                'course_id',
                'course_name',
            )
            ->where('course_id', $course)
            ->first();

        // Check if course data is found
        if (!$courseData) {
            return "Course data not found";
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
            )
            ->join('quizzes', 'learner_syllabus_progress.syllabus_id', '=', 'quizzes.syllabus_id')
            ->join('course', 'learner_syllabus_progress.course_id', '=', 'course.course_id')
            ->where('learner_syllabus_progress.course_id', $course)
            ->where('learner_syllabus_progress.syllabus_id', $syllabus)
            ->where('learner_syllabus_progress.learner_course_id', $learner_course)
            ->first();

        // Check if learner syllabus progress data is found
        if (!$learnerSyllabusProgressData) {
            return "Learner syllabus progress data not found";
        }

        $quizReferenceData = DB::table('quiz_reference')
            ->select(
                'quiz_reference.quiz_reference_id',
                'quiz_reference.quiz_id',
                'quiz_reference.course_id',
                'quiz_reference.syllabus_id',
                'syllabus.topic_title',
            )
            ->join('syllabus', 'quiz_reference.syllabus_id', '=', 'syllabus.syllabus_id')
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
                'learner_quiz_progress.start_period',
                'learner_quiz_progress.finish_period',
            )
            ->where('learner_quiz_progress.learner_course_id', $learner_course)
            ->where('learner_quiz_progress.course_id', $course)
            ->where('learner_quiz_progress.syllabus_id', $syllabus)
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
    ->where('learner_quiz_output.learner_course_id', $learner_course)
    ->where('quiz_content.quiz_id', $learnerSyllabusProgressData->quiz_id)
    ->where('quiz_content.course_id', $learnerSyllabusProgressData->course_id)
    ->where('quiz_content.syllabus_id', $learnerSyllabusProgressData->syllabus_id)
    ->groupBy(
        'learner_quiz_output.learner_quiz_output_id',
        'learner_quiz_output.quiz_id', // Include this line
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

        $html = view('adminReports.learnerQuizOutput', [
            'learnerSyllabusProgressData' => $learnerSyllabusProgressData,
            'quizReferences' => $quizReferenceData,
            'quizProgressData' => $learnerQuizProgressData,
            'quizLearnerData' => $learnerQuizData,
            'learnerData' => $learnerData,
            'courseData' => $courseData,
            'attempt' => $attempt,
        ])->render();

        $options = new Options();
        $options->set('isPhpEnabled', true); // Allow PHP code in the HTML template

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        $name = "{$learnerData->learner_lname} {$learnerData->learner_fname} {$courseData->course_name} {$learnerSyllabusProgressData->quiz_title} attempt {$attempt}";
        $filename = Str::slug("{$name}", '_') . '_learnerQuizOutput.pdf';

        $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $folderName = Str::slug($folderName, '_');

        $folderPath = 'learners/' . $folderName . '/documents';

        Storage::disk('public')->put($folderPath . '/' . $filename, $output);

        return "File Updated";

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}



public function learnerPreAssessmentOutput($learner, $course, $learner_course) {
    try {
        $learnerData = DB::table('learner')
            ->select(
                'learner_id',
                'learner_fname',
                'learner_lname',
            )
            ->where('learner_id', $learner)
            ->first();

        // Check if learner data is found
        if (!$learnerData) {
            return "Learner data not found";
        }

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
            ->where('learner_course.course_id', $course)
            ->where('learner_course.learner_course_id', $learner_course)
            ->first();

        // Check if course data is found
        if (!$courseData) {
            return "Course data not found";
        }

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
            ->where('learner_course_id', $learner_course)
            ->where('course_id', $course)
            ->first();

        // Check if pre-assessment data is found
        if (!$preAssessmentData) {
            return "Pre-assessment data not found";
        }

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
        'learner_pre_assessment_output.answer',
        'learner_pre_assessment_output.isCorrect',
        'questions.question',
        'questions.category',
        'correct_answers.correct_answer'
    )
    ->get();

        $html = view('adminReports.learnerPreAssessmentOutput', [
            'preAssessmentData' => $preAssessmentData,
            'preAssessmentOutputData' => $preAssessmentOutputData,
            'learnerData' => $learnerData,
            'courseData' => $courseData,
        ])->render();

        $options = new Options();
        $options->set('isPhpEnabled', true); // Allow PHP code in the HTML template

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        $name = "{$learnerData->learner_lname} {$learnerData->learner_fname} {$courseData->course_name}";
        $filename = Str::slug("{$name}", '_') . '_learnerPreAssessment.pdf';

        $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $folderName = Str::slug($folderName, '_');

        $folderPath = 'learners/' . $folderName . '/documents';

        Storage::disk('public')->put($folderPath . '/' . $filename, $output);

        return "File Updated";

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}


public function learnerPostAssessmentOutput($learner, $course, $learner_course, $attempt) {
    try {

        $learnerData = DB::table('learner')
            ->select(
                'learner_id',
                'learner_fname',
                'learner_lname',
            )
            ->where('learner_id', $learner)
            ->first();

        // Check if learner data is found
        if (!$learnerData) {
            return "Learner data not found";
        }

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
            ->where('learner_course.course_id', $course)
            ->first();

        // Check if course data is found
        if (!$courseData) {
            return "Course data not found";
        }

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
            ->where('learner_course_id', $learner_course)
            ->where('course_id', $course)
            ->where('attempt', $attempt)
            ->first();

        // Check if post-assessment data is found
        if (!$postAssessmentData) {
            return "Post-assessment data not found";
        }

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
    'learner_post_assessment_output.attempt',
    'learner_post_assessment_output.answer', // Include 'answer' here
    'learner_post_assessment_output.isCorrect',
    'questions.question',
    'questions.category',
    'correct_answers.correct_answer'
)
    ->get();

        $html = view('adminReports.learnerPostAssessmentOutput', [
            'postAssessmentData' => $postAssessmentData,
            'postAssessmentOutputData' => $postAssessmentOutputData,
            'learnerData' => $learnerData,
            'courseData' => $courseData,
            'attempt' => $attempt,
        ])->render();

        $options = new Options();
        $options->set('isPhpEnabled', true); // Allow PHP code in the HTML template

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        $name = "{$learnerData->learner_lname} {$learnerData->learner_fname} {$courseData->course_name} attempt {$attempt}";
        $filename = Str::slug("{$name}", '_') . '_learnerPostAssessment.pdf';

        $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $folderName = Str::slug($folderName, '_');

        $folderPath = 'learners/' . $folderName . '/documents';

        Storage::disk('public')->put($folderPath . '/' . $filename, $output);

        return "File Updated";

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}


public function learnerSessionData($learner) {
    try {

        $learnerData = DB::table('learner')
            ->select(
                'learner_fname',
                'learner_lname',
                'learner_id',
            )
            ->where('learner_id', $learner)
            ->first();

        // Check if learner data is found
        if (!$learnerData) {
            return "Learner data not found";
        }

        $sessionData = DB::table('session_logs')
            ->select(
                'session_log_id',
                'session_in',
                'session_out',
                'time_difference'
            )
            ->where('session_user_type', 'LEARNER')
            ->where('session_user_id', $learner)
            ->orderBy('session_in', 'ASC')
            ->get();

        $html = view('adminReports.learnerSession', [
            'learnerData' => $learnerData,
            'sessionData' => $sessionData,
        ])->render();

        $options = new Options();
        $options->set('isPhpEnabled', true); // Allow PHP code in the HTML template

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        $name = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $filename = Str::slug("{$name}", '_') . '_sessionData.pdf';

        $folderName = "{$learnerData->learner_lname} {$learnerData->learner_fname}";
        $folderName = Str::slug($folderName, '_');

        $folderPath = 'learners/' . $folderName . '/documents';

        Storage::disk('public')->put($folderPath . '/' . $filename, $output);

        return "File Updated";

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}




}
