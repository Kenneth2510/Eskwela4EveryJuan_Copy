<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LearnerController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\InstructorCourseController;
use App\Http\Controllers\InstructorPerformanceController;
use App\Http\Controllers\InstructorDiscussionController;
use App\Http\Controllers\InstructorMessageController;

use App\Http\Controllers\LearnerCourseController;
use App\Http\Controllers\LearnerPerformanceController;
use App\Http\Controllers\LearnerDiscussionController;
use App\Http\Controllers\LearnerMessageController;

use App\Http\Controllers\MailController;
use App\Http\Controllers\ChatBotController;

use App\Http\Controllers\AdminLearnerController;
use App\Http\Controllers\AdminInstructorController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\AdminCourseManageController;
use App\Http\Controllers\AdminPerformanceController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AdminManagementController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });


Route::controller(UserController::class)->group(function() {
    // Route::get('/', 'index');
    // Route::get('/home', 'home');
});

Route::controller(UserController::class)->group(function() {
    Route::get('/', 'landing');
    Route::get('/terms', 'terms');
    Route::get('/data-privacy', 'data_privacy');
});

Route::controller(LearnerController::class)->group(function() {
    Route::get('/learner', 'index');
    Route::post('/learner/login', 'login_process');
    Route::get('/learner/forgot', 'forgot_password');
    Route::post('/learner/reset', 'reset');
    Route::get('/learner/reset_password', 'reset_password');
    Route::post('/learner/reset_password_process/{token}', 'reset_password_process');
    Route::get('/learner/authenticate', 'login_authentication');
    Route::post('/learner/authenticate', 'authenticate_learner');
    Route::post('/learner/logout', 'logout');
    Route::get('/learner/register', 'register');
    Route::post('/learner/register', 'register_process');
    Route::get('/learner/wait', 'wait');
    Route::get('/learner/dashboard', 'dashboard');
    Route::get('/learner/dashboard/overviewNum', 'overviewNum');
    Route::get('/learner/dashboard/sessionData', 'sessionData');
    // Route::get('/learner/settings', 'settings');

    Route::get('/learner/register1', 'register1');

    // Route::put('/learner/settings', 'update_info');
    // Route::put('/learner/update_profile', 'update_profile');

    Route::get('/learner/profile', 'profile');
    Route::post('/learner/profile/update_user_info', 'update_user_info');
    Route::post('/learner/profile/update_business_info', 'update_business_info');
    Route::post('/learner/profile/update_login_info', 'update_login_info');
    Route::put('/learner/profile/update_profile_photo', 'update_profile_photo');
    
    Route::get('/learner/profile/generate_profile_pdf', 'generate_profile_pdf');

    
    Route::get('/learner/profile/learner/{email}', 'view_other_learner');
    Route::get('/learner/profile/instructor/{email}', 'view_other_instructor');
    
    Route::get('/learner/learnerData', 'learnerData');
});

Route::controller(InstructorController::class)->group(function() {
    Route::get('/instructor', 'index');
    Route::post('/instructor/login', 'login_process');
    Route::get('/instructor/forgot', 'forgot_password');
    Route::post('/instructor/reset', 'reset');
    Route::get('/instructor/reset_password', 'reset_password');
    Route::post('/instructor/reset_password_process/{token}', 'reset_password_process');
    Route::get('/instructor/authenticate', 'login_authentication');
    Route::post('/instructor/authenticate', 'authenticate_instructor');
    Route::post('/instructor/logout', 'logout');
    Route::get('/instructor/register', 'register');
    Route::get('/instructor/wait', 'wait');
    Route::get('/instructor/dashboard', 'dashboard');
    Route::get('/instructor/dashboard/overviewNum', 'overviewNum');
    Route::get('/instructor/register', 'register1');
    Route::post('/instructor/register', 'register_process');
    Route::get('/instructor/settings', 'settings');
    Route::put('/instructor/settings', 'update_info');
    Route::put('/instructor/update_profile', 'update_profile');

    // Route::get('/instructor/activities', 'activity');
    // Route::get('/instructor/quiz', 'quiz');

    Route::get('/instructor/profile', 'profile');
    Route::post('/instructor/profile/update_user_info', 'update_user_info');
    Route::post('/instructor/profile/update_business_info', 'update_business_info');
    Route::post('/instructor/profile/update_login_info', 'update_login_info');
    Route::put('/instructor/profile/update_profile_photo', 'update_profile_photo');
    
    Route::get('/instructor/profile/learner/{email}', 'view_other_learner');
    Route::get('/instructor/profile/instructor/{email}', 'view_other_instructor');
});



Route::controller(AdminController::class)->group(function() {
    Route::get('/admin', 'index');
    Route::post('/admin/login', 'login_process');
    Route::post('/admin/logout', 'logout');
    Route::get('/admin/dashboard', 'dashboard');
    Route::get('/admin/dashboard/getCountData', 'getCountData');
    Route::get('/admin/dashboard/getCourseProgressData', 'getCourseProgressData');

});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/admin/learners', 'AdminLearnerController@learners');
    Route::get('/admin/add_learner', 'AdminLearnerController@add_learner');
    Route::post('/admin/add_learner' ,'AdminLearnerController@store_new_learner');
    Route::get('/admin/view_learner/{learner}', 'AdminLearnerController@view_learner');
    Route::put('/admin/approve_learner/{learner}', 'AdminLearnerController@approveLearner');
    Route::put('/admin/reject_learner/{learner}', 'AdminLearnerController@rejectLearner');
    Route::put('/admin/pending_learner/{learner}', 'AdminLearnerController@pendingLearner');
    Route::put('/admin/view_learner/{learner}' , 'AdminLearnerController@update_learner');
    Route::post('/admin/view_learner/{learner}/delete_learner', 'AdminLearnerController@destroy_learner');
});

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/admin/instructors' , 'AdminInstructorController@instructors');
    Route::get('/admin/add_instructor' , 'AdminInstructorController@add_instructor');
    Route::post('/admin/add_instructor', 'AdminInstructorController@store_new_instructor');
    Route::get('/admin/view_instructor/{instructor}' , 'AdminInstructorController@view_instructor');
    Route::put('/admin/approve_instructor/{instructor}', 'AdminInstructorController@approveInstructor');
    Route::put('/admin/reject_instructor/{instructor}', 'AdminInstructorController@rejectInstructor');
    Route::put('/admin/pending_instructor/{instructor}', 'AdminInstructorController@pendingInstructor');
    Route::post('/admin/view_instructor/{instructor}/update' , 'AdminInstructorController@update_instructor');
    Route::post('/admin/view_instructor/{instructor}/delete_instructor', 'AdminInstructorController@destroy_instructor');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/admin/courses' , 'AdminCourseController@courses');
    Route::get('/admin/add_course', 'AdminCourseController@add_course');
    Route::post('/admin/add_course', 'AdminCourseController@store_new_course');
    Route::get('/admin/view_course/{course}', 'AdminCourseController@view_course');
    Route::post('/admin/view_course/{course}', 'AdminCourseController@update_course');
    Route::post('/admin/view_course/{course}/delete_course', 'AdminCourseController@delete_course');
    Route::put('/admin/approve_course/{course}', 'AdminCourseController@approveCourse');
    Route::put('/admin/reject_course/{course}', 'AdminCourseController@rejectCourse');
    Route::put('/admin/pending_course/{course}', 'AdminCourseController@pendingCourse');

    Route::get('/admin/manage_course/course_overview/{course}' , 'AdminCourseController@manage_course');

    
    Route::get('/admin/course/enrollment' , 'AdminCourseController@course_manage_enrollees');
    Route::get('/admin/course/enrollment/learnerCoursesData' , 'AdminCourseController@getLearnerCourseData');
    Route::get('/admin/course/enrollment/search' , 'AdminCourseController@search');

    Route::get('/admin/course/enrollment/addNew' , 'AdminCourseController@add_new_enrollee');
    Route::get('/admin/course/enrollment/addNew/getData' , 'AdminCourseController@getData');
    Route::post('/admin/course/enrollment/addNew/enrollNew' , 'AdminCourseController@enrollNew');

    Route::get('/admin/course/enrollment/view/{learner_course}' , 'AdminCourseController@view_learner_course');

    Route::get('/admin/course/enrollment/learnerCourse/{learner_course}' , 'AdminCourseController@view_learner_course');



    
    Route::get('/admin/manage_course/enrollees/{course}' , 'AdminCourseController@course_enrollees');
    Route::put('/admin/manage_course/enrollee/approve/{learner_course}', 'AdminCourseController@approve_learner_course');
    Route::put('/admin/manage_course/enrollee/pending/{learner_course}', 'AdminCourseController@pending_learner_course');
    Route::put('/admin/manage_course/enrollee/reject/{learner_course}', 'AdminCourseController@reject_learner_course');


});

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/admin/courseManage', 'AdminCourseManageController@coursesManage');
    Route::get('/admin/courseManage/{course}', 'AdminCourseManageController@coursesOverview');

    Route::get('/admin/courseManage/{course}/overviewNum', 'AdminCourseManageController@overviewNum');
    Route::post('/admin/courseManage/{course}/editCourseDetails', 'AdminCourseManageController@editCourseDetails');
    Route::post('/admin/courseManage/{course}/generate_pdf', 'AdminCourseManageController@generate_pdf');
    Route::post('/admin/courseManage/{course}/add_file', 'AdminCourseManageController@add_file');
    Route::get('/admin/courseManage/{course}/delete_file/{fileName}', 'AdminCourseManageController@delete_file');
    Route::post('/admin/courseManage/{course}/gradingSystem', 'AdminCourseManageController@gradingSystem');

    Route::post('/admin/courseManage/{course}/delete', 'AdminCourseManageController@delete_course');

    Route::post('/admin/courseManage/create/syllabus/{course}', 'AdminCourseManageController@create_syllabus');

    Route::get('/admin/courseManage/content/{course}', 'AdminCourseManageController@display_course_syllabus_view');
    Route::get('/admin/courseManage/content/{course}/json', 'AdminCourseManageController@course_content_json');

    Route::post('/admin/courseManage/content/syllabus/{course}/manage', 'AdminCourseManageController@update_syllabus');
    Route::post('/admin/courseManage/content/syllabus/{course}/manage_add', 'AdminCourseManageController@update_syllabus_add_new');
    Route::post('/admin/courseManage/content/syllabus/{course}/manage_delete', 'AdminCourseManageController@update_syllabus_delete');

    // lesson management
    Route::get('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}', 'AdminCourseManageController@view_lesson');
    Route::get('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/json', 'AdminCourseManageController@lesson_content_json');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/addCompletionTime', 'AdminCourseManageController@addCompletionTime');

    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson_id}', 'AdminCourseManageController@update_lesson_title');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/picture', 'AdminCourseManageController@update_lesson_picture');
    Route::post('/admin/courseManage/content/lesson/{lesson}/title/{lesson_content}', 'AdminCourseManageController@update_lesson_content');
    Route::post('/admin/courseManage/content/lesson/{lesson}/title/{lesson_content}/delete', 'AdminCourseManageController@delete_lesson_content');

    

    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/save', 'AdminCourseManageController@save_lesson_content');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/save_add', 'AdminCourseManageController@save_add_lesson_content');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/store_file/{lesson_content}', 'AdminCourseManageController@lesson_content_store_file');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/delete_file/{lesson_content}', 'AdminCourseManageController@lesson_content_delete_file');
    Route::get('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/pdf_view', 'AdminCourseManageController@view_lesson_pdf');
    Route::get('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/generate_pdf', 'AdminCourseManageController@lesson_generate_pdf');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/store_video_url/{lesson_content}', 'AdminCourseManageController@lesson_content_embed_url');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/delete_url/{lesson_content}', 'AdminCourseManageController@lesson_content_delete_url');
    
    // activity management
    Route::get('/admin/courseManage/content/{course}/{syllabus}/activity/{topic_id}', 'AdminCourseManageController@view_activity');
    Route::get('/admin/courseManage/content/{course}/{syllabus}/activity/{topic_id}/json', 'AdminCourseManageController@activity_content_json');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/activity/{topic_id}/title/{activity}/{activity_content}/instructions', 'AdminCourseManageController@update_activity_instructions');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/activity/{topic_id}/title/{activity}/{activity_content}/score', 'AdminCourseManageController@update_activity_score');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/activity/{topic_id}/title/{activity}/{activity_content}/criteria', 'AdminCourseManageController@update_activity_criteria');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/activity/{topic_id}/title/{activity}/{activity_content}/criteria_add', 'AdminCourseManageController@add_activity_criteria');

    Route::get('/admin/courseManage/content/{course}/{syllabus}/activity/{topic_id}/{learner_course}/{attempt}', 'AdminCourseManageController@view_learner_activity_response');
    Route::post('/admin/courseManage/content/activity/{learner_activity_output}/{learner_course}/{activities}/{activity_content}/{attempt}', 'AdminCourseManageController@learnerResponse_overallScore');
    Route::post('/admin/courseManage/content/activity/{learner_activity_output}/{learner_course}/{activities}/{activity_content}/{attempt}/criteria_score', 'AdminCourseManageController@learnerResponse_criteriaScore');
    Route::get('/admin/courseManage/content/activity/{learner_activity_output}/{learner_course}/{activities}/{activity_content}/{attempt}/reattempt', 'AdminCourseManageController@reattempt_activity');

    // quiz management
    Route::get('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}', 'AdminCourseManageController@view_quiz');
    Route::get('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/json', 'AdminCourseManageController@quiz_info_json');
    Route::get('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/view_learner_output/{learner_quiz_progress}', 'AdminCourseManageController@view_learner_output');
    Route::get('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/view_learner_output/{learner_quiz_progress}/json', 'AdminCourseManageController@view_learner_output_json');
    
    Route::post('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/{quiz}/add', 'AdminCourseManageController@manage_add_reference');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/{quiz}/update', 'AdminCourseManageController@manage_update_reference');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/{quiz}/duration', 'AdminCourseManageController@manage_update_duration');

    Route::get('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content', 'AdminCourseManageController@quiz_content');
    Route::get('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content/json', 'AdminCourseManageController@quiz_content_json');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content/add', 'AdminCourseManageController@add_quiz_question');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content/update', 'AdminCourseManageController@update_quiz_question');
    Route::post('/admin/courseManage/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content/empty', 'AdminCourseManageController@empty_quiz_question');
    
});

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/admin/message', 'AdminMessageController@index');
    Route::get('/admin/message/search_recipient', 'AdminMessageController@search_recipient');
    Route::post('/admin/message/send', 'AdminMessageController@send');
    
    Route::get('/admin/message/getMessages', 'AdminMessageController@getMessages');
    Route::get('/admin/message/getSelectedMessage', 'AdminMessageController@getSelectedMessage');

    Route::post('/admin/message/reply', 'AdminMessageController@reply');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/admin/performance', 'AdminPerformanceController@index');
    Route::get('/admin/performance/learnerOverviewData', 'AdminPerformanceController@learner_overview');
    Route::get('/admin/performance/instructorOverviewData', 'AdminPerformanceController@instructor_overview');
    Route::get('/admin/performance/courseOverviewData', 'AdminPerformanceController@course_overview');

    Route::get('/admin/performance/learners', 'AdminPerformanceController@learners');
    Route::get('/admin/performance/learners/view/{learner}', 'AdminPerformanceController@view_learner');
    Route::get('/admin/performance/learners/view/{learner}/sessionData', 'AdminPerformanceController@sessionData');
    Route::get('/admin/performance/learners/view/{learner}/totalEnrolledCourses', 'AdminPerformanceController@enrolledCoursesPerformances');
    Route::get('/admin/performance/learners/view/{learner}/enrolledCoursesData', 'AdminPerformanceController@enrolledCoursesPerformancesData');
    Route::get('/admin/performance/learners/view/{learner}/course/{course}', 'AdminPerformanceController@coursePerformance');
    Route::get('/admin/performance/learners/view/{learner}/course/{course}/coursePerformance', 'AdminPerformanceController@coursePerformanceData');
    Route::get('/admin/performance/learners/view/{learner}/course/{course}/syllabusPerformance', 'AdminPerformanceController@syllabusPerformanceData');
    Route::get('/admin/performance/learners/view/{course}/{learner_course}/pre_assessment/view_output', 'AdminPerformanceController@view_output_pre_assessment');
    Route::get('/admin/performance/learners/view/{course}/{learner_course}/pre_assessment/view_output/json', 'AdminPerformanceController@view_output_pre_assessment_json');
    Route::get('/admin/performance/learners/view/{course}/{learner_course}/post_assessment/view_output/{attempt}', 'AdminPerformanceController@view_output_post_assessment');
    Route::get('/admin/performance/learners/view/{course}/{learner_course}/post_assessment/view_output/{attempt}/json', 'AdminPerformanceController@view_output_post_assessment_json');

    
    Route::get('/admin/performance/instructors', 'AdminPerformanceController@instructors');
    Route::get('/admin/performance/instructor/view/{instructor}', 'AdminPerformanceController@view_instructor');
    Route::get('/admin/performance/instructor/view/{instructor}/sessionData', 'AdminPerformanceController@i_sessionData');
    Route::get('/admin/performance/instructor/view/{instructor}/totalCourseNum', 'AdminPerformanceController@i_totalCourseNum');
    Route::get('/admin/performance/instructor/view/{instructor}/courseChartData', 'AdminPerformanceController@i_courseChartData');


    Route::get('/admin/performance/courses', 'AdminPerformanceController@courses');
    Route::get('/admin/performance/courses/view/{course}', 'AdminPerformanceController@view_course');
    Route::get('/admin/performance/courses/view/{course}/performanceData', 'AdminPerformanceController@selectedCoursePerformance');
    Route::get('/admin/performance/courses/view/{course}/learnerCourseData', 'AdminPerformanceController@learnerCourseData');
    Route::get('/admin/performance/courses/view/{course}/learnerSyllabusData', 'AdminPerformanceController@learnerSyllabusData');

    Route::get('/admin/performance/courses/view/{course}/syllabus/{syllabus}', 'AdminPerformanceController@courseSyllabusPerformance');
    Route::get('/admin/performance/courses/view/{course}/syllabus/{syllabus}/lessonData', 'AdminPerformanceController@courseSyllabusLessonPerformance');
    Route::get('/admin/performance/courses/view/{course}/syllabus/{syllabus}/activityData', 'AdminPerformanceController@courseSyllabusActivityPerformance');
    Route::get('/admin/performance/courses/view/{course}/syllabus/{syllabus}/activityData/outputs', 'AdminPerformanceController@courseSyllabusActivityScoresPerformance');
    Route::get('/admin/performance/courses/view/{course}/syllabus/{syllabus}/quizData', 'AdminPerformanceController@courseSyllabusQuizPerformance');
    Route::get('/admin/performance/courses/view/{course}/syllabus/{syllabus}/quizData/outputs', 'AdminPerformanceController@courseSyllabusQuizScoresPerformance');
    Route::get('/admin/performance/courses/view/{course}/syllabus/{syllabus}/quizData/contentOutputs', 'AdminPerformanceController@courseSyllabusQuizContentOutputPerformance');
    Route::get('/admin/performance/courses/view/{course}/learner/{learner_course}', 'AdminPerformanceController@learnerCoursePerformance');
    Route::get('/admin/performance/courses/view/{course}/learner/{learner_course}/coursePerformance', 'AdminPerformanceController@learnerCourseOverallPerformance');
    Route::get('/admin/performance/courses/view/{course}/learner/{learner_course}/syllabusPerformance', 'AdminPerformanceController@learnerCourseSyllabusPerformance');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/admin/admins', 'AdminManagementController@index');
    Route::get('/admin/admins/add_admin', 'AdminManagementController@add_new_admin');
    Route::post('/admin/admins/add_admin/submit_new_admin', 'AdminManagementController@submit_new_admin');

    Route::get('/admin/view_admin/{admin}', 'AdminManagementController@view_admin');
    Route::post('/admin/view_admin/{admin}/update', 'AdminManagementController@update_admin');
    Route::post('/admin/view_admin/{admin}/delete', 'AdminManagementController@delete_admin');

    Route::get('/admin/profile', 'AdminManagementController@settings');
    Route::post('/admin/profile/update', 'AdminManagementController@update_settings');
    
});



Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/admin/report', 'AdminReportsController@index');
    Route::get('/admin/report/Users', 'AdminReportsController@Users');
    Route::get('/admin/report/Session', 'AdminReportsController@Session');
    Route::get('/admin/report/UserSession', 'AdminReportsController@UserSession');
    Route::get('/admin/report/Courses', 'AdminReportsController@Courses');
    Route::get('/admin/report/Enrollees', 'AdminReportsController@Enrollees');
    Route::get('/admin/report/CourseGradesheets', 'AdminReportsController@CourseGradesheets');
    Route::get('/admin/report/CoursePerformances', 'AdminReportsController@CoursePerformances');
    Route::get('/admin/report/LearnerGradesheets', 'AdminReportsController@LearnerGradesheets');

    
    Route::get('/admin/report/{course}/learnerCourseData', 'AdminReportsController@learnerCourseData');
});




Route::get('storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path("app/public/{$folder}/{$filename}");

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('filename', '.*'); 

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/instructor/courses', 'InstructorCourseController@courses');
    Route::get('/instructor/courses/searchCourse', 'InstructorCourseController@searchCourse');
    Route::get('/instructor/courses/create', 'InstructorCourseController@courseCreate');
    Route::post('/instructor/courses/create', 'InstructorCourseController@courseCreate_process');
    Route::post('/instructor/course/upload/files/{course}', 'InstructorCourseController@courseCreateUploadFiles');


    Route::get('/instructor/course/{course}', 'InstructorCourseController@overview');
    Route::get('/instructor/course/{course}/overviewNum', 'InstructorCourseController@overviewNum');
    Route::post('/instructor/course/{course}/editCourseDetails', 'InstructorCourseController@editCourseDetails');
    Route::post('/instructor/course/{course}/generate_pdf', 'InstructorCourseController@generate_pdf');
    Route::post('/instructor/course/{course}/add_file', 'InstructorCourseController@add_file');
    Route::get('/instructor/course/{course}/delete_file/{fileName}', 'InstructorCourseController@delete_file');

    // Route::get('/instructor/course/manage/{course}', 'InstructorCourseController@manage_course');
    // Route::post('/instructor/course/manage/{course}', 'InstructorCourseController@update_course');
    Route::post('/instructor/course/{course}/delete', 'InstructorCourseController@delete_course');

    Route::post('/instructor/course/create/syllabus/{course}', 'InstructorCourseController@create_syllabus');

    Route::get('/instructor/course/content/{course}', 'InstructorCourseController@display_course_syllabus_view');
    Route::get('/instructor/course/content/{course}/json', 'InstructorCourseController@course_content_json');

    Route::post('/instructor/course/content/syllabus/{course}/manage', 'InstructorCourseController@update_syllabus');
    Route::post('/instructor/course/content/syllabus/{course}/manage_add', 'InstructorCourseController@update_syllabus_add_new');
    Route::post('/instructor/course/content/syllabus/{course}/manage_delete', 'InstructorCourseController@update_syllabus_delete');

    // lesson management
    Route::get('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}', 'InstructorCourseController@view_lesson');
    Route::get('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/json', 'InstructorCourseController@lesson_content_json');
    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/addCompletionTime', 'InstructorCourseController@addCompletionTime');

    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson_id}', 'InstructorCourseController@update_lesson_title');
    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/picture', 'InstructorCourseController@update_lesson_picture');
    Route::post('/instructor/course/content/lesson/{lesson}/title/{lesson_content}', 'InstructorCourseController@update_lesson_content');
    Route::post('/instructor/course/content/lesson/{lesson}/title/{lesson_content}/delete', 'InstructorCourseController@delete_lesson_content');

    

    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/save', 'InstructorCourseController@save_lesson_content');
    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/save_add', 'InstructorCourseController@save_add_lesson_content');
    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/store_file/{lesson_content}', 'InstructorCourseController@lesson_content_store_file');
    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/delete_file/{lesson_content}', 'InstructorCourseController@lesson_content_delete_file');
    Route::get('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/pdf_view', 'InstructorCourseController@view_lesson_pdf');
    Route::get('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/generate_pdf', 'InstructorCourseController@lesson_generate_pdf');
    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/store_video_url/{lesson_content}', 'InstructorCourseController@lesson_content_embed_url');
    Route::post('/instructor/course/content/{course}/{syllabus}/lesson/{topic_id}/title/{lesson}/delete_url/{lesson_content}', 'InstructorCourseController@lesson_content_delete_url');
    
    // activity management
    Route::get('/instructor/course/content/{course}/{syllabus}/activity/{topic_id}', 'InstructorCourseController@view_activity');
    Route::get('/instructor/course/content/{course}/{syllabus}/activity/{topic_id}/json', 'InstructorCourseController@activity_content_json');
    Route::post('/instructor/course/content/{course}/{syllabus}/activity/{topic_id}/title/{activity}/{activity_content}/instructions', 'InstructorCourseController@update_activity_instructions');
    Route::post('/instructor/course/content/{course}/{syllabus}/activity/{topic_id}/title/{activity}/{activity_content}/score', 'InstructorCourseController@update_activity_score');
    Route::post('/instructor/course/content/{course}/{syllabus}/activity/{topic_id}/title/{activity}/{activity_content}/criteria', 'InstructorCourseController@update_activity_criteria');
    Route::post('/instructor/course/content/{course}/{syllabus}/activity/{topic_id}/title/{activity}/{activity_content}/criteria_add', 'InstructorCourseController@add_activity_criteria');

    Route::get('/instructor/course/content/{course}/{syllabus}/activity/{topic_id}/{learner_course}/{attempt}', 'InstructorCourseController@view_learner_activity_response');
    Route::post('/instructor/course/content/activity/{learner_activity_output}/{learner_course}/{activities}/{activity_content}/{attempt}', 'InstructorCourseController@learnerResponse_overallScore');
    Route::post('/instructor/course/content/activity/{learner_activity_output}/{learner_course}/{activities}/{activity_content}/{attempt}/criteria_score', 'InstructorCourseController@learnerResponse_criteriaScore');
    Route::get('/instructor/course/content/activity/{learner_activity_output}/{learner_course}/{activities}/{activity_content}/{attempt}/reattempt', 'InstructorCourseController@reattempt_activity');

    // quiz management
    Route::get('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}', 'InstructorCourseController@view_quiz');
    Route::get('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/json', 'InstructorCourseController@quiz_info_json');
    Route::get('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/view_learner_output/{learner_quiz_progress}', 'InstructorCourseController@view_learner_output');
    Route::get('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/view_learner_output/{learner_quiz_progress}/json', 'InstructorCourseController@view_learner_output_json');
    
    Route::post('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/{quiz}/add', 'InstructorCourseController@manage_add_reference');
    Route::post('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/{quiz}/update', 'InstructorCourseController@manage_update_reference');
    Route::post('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/{quiz}/duration', 'InstructorCourseController@manage_update_duration');

    Route::get('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content', 'InstructorCourseController@quiz_content');
    Route::get('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content/json', 'InstructorCourseController@quiz_content_json');
    Route::post('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content/add', 'InstructorCourseController@add_quiz_question');
    Route::post('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content/update', 'InstructorCourseController@update_quiz_question');
    Route::post('/instructor/course/content/{course}/{syllabus}/quiz/{topic_id}/{quiz_id}/content/empty', 'InstructorCourseController@empty_quiz_question');
    

    Route::get('/instructor/course/{course}/certificate', 'InstructorCourseController@generate_certificate');

    // // })->middleware('web');
});

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/learner/courses', 'LearnerCourseController@courses');
    Route::get('/learner/courses/searchCourse', 'LearnerCourseController@searchCourse');
    Route::get('/learner/course/{course}', 'LearnerCourseController@overview');
    Route::post('/learner/course/enroll/{course}', 'LearnerCourseController@enroll_course');
    Route::post('/learner/course/unEnroll/{learnerCourse}', 'LearnerCourseController@unEnroll_course');
    Route::get('/learner/course/manage/{course}', 'LearnerCourseController@manage_course');
    Route::get('/learner/course/manage/{course}/overview', 'LearnerCourseController@course_overview');
    Route::get('/learner/course/manage/{course}/view_syllabus', 'LearnerCourseController@view_syllabus');
    // Route::get('/learner/course/{course}/print_certificate', 'LearnerCourseController@print_certificate');

    Route::get('/learner/course/content/{course}/{learner_course}/pre_assessment', 'LearnerCourseController@pre_assessment');
    Route::get('/learner/course/content/{course}/{learner_course}/pre_assessment/answer', 'LearnerCourseController@answer_pre_assessment');
    Route::get('/learner/course/content/{course}/{learner_course}/pre_assessment/answer/json', 'LearnerCourseController@answer_pre_assessment_json');
    Route::post('/learner/course/content/{course}/{learner_course}/pre_assessment/answer/submit', 'LearnerCourseController@submit_pre_assessment');
    Route::post('/learner/course/content/{course}/{learner_course}/pre_assessment/answer/score', 'LearnerCourseController@score_pre_assessment');
    Route::get('/learner/course/content/{course}/{learner_course}/pre_assessment/view_output', 'LearnerCourseController@view_output_pre_assessment');
    Route::get('/learner/course/content/{course}/{learner_course}/pre_assessment/view_output/json', 'LearnerCourseController@view_output_pre_assessment_json');
    

    Route::get('/learner/course/content/{course}/{learner_course}/lesson/{syllabus}', 'LearnerCourseController@view_lesson');
    Route::post('/learner/course/content/{course}/{learner_course}/lesson/{syllabus}/finish', 'LearnerCourseController@finish_lesson');

    Route::get('/learner/course/content/{course}/{learner_course}/activity/{syllabus}', 'LearnerCourseController@view_activity');
    Route::get('/learner/course/content/{course}/{learner_course}/activity/{syllabus}/answer/{attempt}', 'LearnerCourseController@answer_activity');
    Route::post('/learner/course/content/{course}/{learner_course}/activity/{syllabus}/answer/{attempt}/{activity}/{activity_content}', 'LearnerCourseController@submit_answer');

    Route::get('/learner/course/content/{course}/{learner_course}/quiz/{syllabus}', 'LearnerCourseController@view_quiz');
    Route::get('/learner/course/content/{course}/{learner_course}/quiz/{syllabus}/answer/{attempt}', 'LearnerCourseController@answer_quiz');
    Route::get('/learner/course/content/{course}/{learner_course}/quiz/{syllabus}/answer/{attempt}/json', 'LearnerCourseController@answer_quiz_json');
    Route::post('/learner/course/content/{course}/{learner_course}/quiz/{syllabus}/answer/{attempt}/submit', 'LearnerCourseController@submit_quiz');
    Route::post('/learner/course/content/{course}/{learner_course}/quiz/{syllabus}/answer/{attempt}/score', 'LearnerCourseController@compute_score');

    Route::get('/learner/course/content/{course}/{learner_course}/quiz/{syllabus}/view_output/{attempt}', 'LearnerCourseController@view_output');
    Route::get('/learner/course/content/{course}/{learner_course}/quiz/{syllabus}/view_output/{attempt}/json', 'LearnerCourseController@view_output_json');
    Route::get('/learner/course/content/{course}/{learner_course}/quiz/{syllabus}/reattempt', 'LearnerCourseController@reattempt_answer_quiz');
    

    
    Route::get('/learner/course/content/{course}/{learner_course}/post_assessment', 'LearnerCourseController@post_assessment');
    Route::get('/learner/course/content/{course}/{learner_course}/post_assessment/answer/{attempt}', 'LearnerCourseController@answer_post_assessment');
    Route::get('/learner/course/content/{course}/{learner_course}/post_assessment/answer/{attempt}/json', 'LearnerCourseController@answer_post_assessment_json');
    Route::post('/learner/course/content/{course}/{learner_course}/post_assessment/answer/{attempt}/submit', 'LearnerCourseController@submit_post_assessment');
    Route::post('/learner/course/content/{course}/{learner_course}/post_assessment/answer/{attempt}/score', 'LearnerCourseController@score_post_assessment');
    Route::get('/learner/course/content/{course}/{learner_course}/post_assessment/view_output/{attempt}', 'LearnerCourseController@view_output_post_assessment');
    Route::get('/learner/course/content/{course}/{learner_course}/post_assessment/view_output/{attempt}/json', 'LearnerCourseController@view_output_post_assessment_json');
    Route::get('/learner/course/content/{course}/{learner_course}/post_assessment/reattempt', 'LearnerCourseController@post_assessment_reattempt');

    Route::get('/learner/course/content/{course}/{learner_course}/grades', 'LearnerCourseController@grades');
    Route::get('/learner/course/content/{course}/{learner_course}/gradespdf', 'LearnerCourseController@gradespdf');

    
    Route::get('/learner/course/{course}/{learner_course}/certificate', 'LearnerCourseController@generate_certificate');
    // // })->middleware('web');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/instructor/performances', 'InstructorPerformanceController@performances');
    Route::get('/instructor/performances/sessionData', 'InstructorPerformanceController@sessionData');
    Route::get('/instructor/performances/totalCourseNum', 'InstructorPerformanceController@totalCourseNum');
    Route::get('/instructor/performances/courseChartData', 'InstructorPerformanceController@courseChartData');
    Route::get('/instructor/performances/course/{course}', 'InstructorPerformanceController@coursePerformance');
    Route::get('/instructor/performances/course/{course}/performanceData', 'InstructorPerformanceController@selectedCoursePerformance');
    Route::get('/instructor/performances/course/{course}/learnerCourseData', 'InstructorPerformanceController@learnerCourseData');
    Route::get('/instructor/performances/course/{course}/learnerSyllabusData', 'InstructorPerformanceController@learnerSyllabusData');
    Route::get('/instructor/performances/course/{course}/syllabus/{syllabus}', 'InstructorPerformanceController@courseSyllabusPerformance');
    Route::get('/instructor/performances/course/{course}/syllabus/{syllabus}/lessonData', 'InstructorPerformanceController@courseSyllabusLessonPerformance');
    Route::get('/instructor/performances/course/{course}/syllabus/{syllabus}/activityData', 'InstructorPerformanceController@courseSyllabusActivityPerformance');
    Route::get('/instructor/performances/course/{course}/syllabus/{syllabus}/activityData/outputs', 'InstructorPerformanceController@courseSyllabusActivityScoresPerformance');
    Route::get('/instructor/performances/course/{course}/syllabus/{syllabus}/quizData', 'InstructorPerformanceController@courseSyllabusQuizPerformance');
    Route::get('/instructor/performances/course/{course}/syllabus/{syllabus}/quizData/outputs', 'InstructorPerformanceController@courseSyllabusQuizScoresPerformance');
    Route::get('/instructor/performances/course/{course}/syllabus/{syllabus}/quizData/contentOutputs', 'InstructorPerformanceController@courseSyllabusQuizContentOutputPerformance');
    Route::get('/instructor/performances/course/{course}/learner/{learner_course}', 'InstructorPerformanceController@learnerCoursePerformance');
    Route::get('/instructor/performances/course/{course}/learner/{learner_course}/coursePerformance', 'InstructorPerformanceController@learnerCourseOverallPerformance');
    Route::get('/instructor/performances/course/{course}/learner/{learner_course}/syllabusPerformance', 'InstructorPerformanceController@learnerCourseSyllabusPerformance');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/learner/performances', 'LearnerPerformanceController@performances');
    Route::get('/learner/performances/sessionData', 'LearnerPerformanceController@sessionData');
    Route::get('/learner/performances/totalEnrolledCourses', 'LearnerPerformanceController@enrolledCoursesPerformances');
    Route::get('/learner/performances/enrolledCoursesData', 'LearnerPerformanceController@enrolledCoursesPerformancesData');
    Route::get('/learner/performances/course/{course}', 'LearnerPerformanceController@coursePerformance');
    Route::get('/learner/performances/course/{course}/coursePerformance', 'LearnerPerformanceController@coursePerformanceData');
    Route::get('/learner/performances/course/{course}/syllabusPerformance', 'LearnerPerformanceController@syllabusPerformanceData');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/instructor/discussions', 'InstructorDiscussionController@discussions');
    Route::get('/instructor/discussions/threads', 'InstructorDiscussionController@threadData');
    Route::get('/instructor/discussions/create', 'InstructorDiscussionController@createDiscussion');

    Route::post('/instructor/discussions/create/post', 'InstructorDiscussionController@postDiscussion');
    Route::post('/instructor/discussions/create/post-photo', 'InstructorDiscussionController@postPhotoDiscussion');

    Route::get('/instructor/discussions/thread/{thread}', 'InstructorDiscussionController@viewThread');
    Route::get('/instructor/discussions/thread/{thread}/comments', 'InstructorDiscussionController@viewThreadComments');

    Route::post('/instructor/discussions/thread/{thread}/comment', 'InstructorDiscussionController@postComment');
    Route::post('/instructor/discussions/thread/{thread}/commentReply', 'InstructorDiscussionController@postCommentReply');
    Route::post('/instructor/discussions/thread/{thread}/replyReply', 'InstructorDiscussionController@postReplyReply');

    Route::post('/instructor/discussions/thread/{thread}/upvote', 'InstructorDiscussionController@upvoteThread');
    Route::post('/instructor/discussions/thread/{thread}/downvote', 'InstructorDiscussionController@downvoteThread');
    Route::post('/instructor/discussions/thread/{thread}/comment/{thread_comment}/upvote', 'InstructorDiscussionController@upvoteThreadComment');
    Route::post('/instructor/discussions/thread/{thread}/comment/{thread_comment}/downvote', 'InstructorDiscussionController@downvoteThreadComment');
    Route::post('/instructor/discussions/thread/{thread}/comment/{thread_comment}/reply/{thread_comment_reply}/upvote', 'InstructorDiscussionController@upvoteThreadCommentReply');
    Route::post('/instructor/discussions/thread/{thread}/comment/{thread_comment}/reply/{thread_comment_reply}/downvote', 'InstructorDiscussionController@downvoteThreadCommentReply');
    Route::post('/instructor/discussions/thread/{thread}/comment/{thread_comment}/reply/{thread_comment_reply}/reply/{thread_reply_reply}/upvote', 'InstructorDiscussionController@upvoteThreadReplyReply');
    Route::post('/instructor/discussions/thread/{thread}/comment/{thread_comment}/reply/{thread_comment_reply}/reply/{thread_reply_reply}/downvote', 'InstructorDiscussionController@downvoteThreadReplyReply');
});



Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/learner/discussions', 'LearnerDiscussionController@discussions');
    Route::get('/learner/discussions/threads', 'LearnerDiscussionController@threadData');

    Route::get('/learner/discussions/create', 'LearnerDiscussionController@createDiscussion');
    Route::post('/learner/discussions/create/post', 'LearnerDiscussionController@postDiscussion');
    Route::post('/learner/discussions/create/post-photo', 'LearnerDiscussionController@postPhotoDiscussion');

    Route::get('/learner/discussions/thread/{thread}', 'LearnerDiscussionController@viewThread');
    Route::get('/learner/discussions/thread/{thread}/comments', 'LearnerDiscussionController@viewThreadComments');

    Route::post('/learner/discussions/thread/{thread}/comment', 'LearnerDiscussionController@postComment');
    Route::post('/learner/discussions/thread/{thread}/commentReply', 'LearnerDiscussionController@postCommentReply');
    Route::post('/learner/discussions/thread/{thread}/replyReply', 'LearnerDiscussionController@postReplyReply');

    Route::post('/learner/discussions/thread/{thread}/upvote', 'LearnerDiscussionController@upvoteThread');
    Route::post('/learner/discussions/thread/{thread}/downvote', 'LearnerDiscussionController@downvoteThread');
    Route::post('/learner/discussions/thread/{thread}/comment/{thread_comment}/upvote', 'LearnerDiscussionController@upvoteThreadComment');
    Route::post('/learner/discussions/thread/{thread}/comment/{thread_comment}/downvote', 'LearnerDiscussionController@downvoteThreadComment');
    Route::post('/learner/discussions/thread/{thread}/comment/{thread_comment}/reply/{thread_comment_reply}/upvote', 'LearnerDiscussionController@upvoteThreadCommentReply');
    Route::post('/learner/discussions/thread/{thread}/comment/{thread_comment}/reply/{thread_comment_reply}/downvote', 'LearnerDiscussionController@downvoteThreadCommentReply');
    Route::post('/learner/discussions/thread/{thread}/comment/{thread_comment}/reply/{thread_comment_reply}/reply/{thread_reply_reply}/upvote', 'LearnerDiscussionController@upvoteThreadReplyReply');
    Route::post('/learner/discussions/thread/{thread}/comment/{thread_comment}/reply/{thread_comment_reply}/reply/{thread_reply_reply}/downvote', 'LearnerDiscussionController@downvoteThreadReplyReply');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/send', 'MailController@index');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/learner/message', 'LearnerMessageController@index');
    Route::get('/learner/message/search_recipient', 'LearnerMessageController@search_recipient');
    Route::post('/learner/message/send', 'LearnerMessageController@send');
    
    Route::get('/learner/message/getMessages', 'LearnerMessageController@getMessages');
    Route::get('/learner/message/getSelectedMessage', 'LearnerMessageController@getSelectedMessage');

    Route::post('/learner/message/reply', 'LearnerMessageController@reply');
});


Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/instructor/message', 'InstructorMessageController@index');
    Route::get('/instructor/message/search_recipient', 'InstructorMessageController@search_recipient');
    Route::post('/instructor/message/send', 'InstructorMessageController@send');
    
    Route::get('/instructor/message/getMessages', 'InstructorMessageController@getMessages');
    Route::get('/instructor/message/getSelectedMessage', 'InstructorMessageController@getSelectedMessage');

    Route::post('/instructor/message/reply', 'InstructorMessageController@reply');
});



Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/chatbot/init/{id}', 'ChatBotController@index');
    Route::get('/chatbot/learner/{id}', 'ChatBotController@learner');
    Route::get('/chatbot/learner/{id}/course/{course}', 'ChatBotController@learner_course');
    Route::get('/chatbot/process/{id}', 'ChatBotController@process');
    Route::post('/chatbot/chat/{id}', 'ChatBotController@chat');
    Route::get('/chatbot/reset/{id}', 'ChatBotController@reset');


    Route::get('/chatbot/courseData/{course}', 'ChatBotController@courseData');
    Route::get('/chatbot/syllabusData/{course}/{syllabus}', 'ChatBotController@syllabusData');
});