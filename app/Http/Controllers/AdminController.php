<?php
// sample
namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Learner;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Admin;
use App\Models\LearnerCourse;
use App\Models\LearnerCourseProgress;
use App\Models\LearnerSyllabusProgress;
use App\Models\LearnerLessonProgress;
use App\Models\LearnerActivityProgress;
use App\Models\LearnerQuizProgress;
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

class AdminController extends Controller
{
    public function index()
{
    if (auth('admin')->check()) {
        return redirect('/admin/dashboard');
    }

    // Return the login page for administrators
    return view('admin.index')->with('title', 'Eskwela4EveryJuan Admin');
}


public function login_process(Request $request) {
    $adminData = $request->validate([
        "admin_username" => ['required'],
        "password" => ['required']
    ]);

    // Use the "admin" guard to attempt the login
    if (Auth::guard('admin')->attempt($adminData)) {
        $admin = Auth::guard('admin')->user();

        $request->session()->put('admin', $admin);

        $request->session()->regenerate();

        return redirect('/admin/dashboard')->with('message', "Welcome Back");
    }

    return back()->withErrors(['admin_username' => 'Login failed. Please check your credentials and try again.'])->withInput($request->except('password'));
}

    
    public function logout(Request $request) {
        auth('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin')->with('message', 'Logout Successful');
    
    }

    

    public function dashboard() {
        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);



            try {
                $learnerCount = Learner::count();
                $instructorCount = Instructor::count();
                $courseCount = Course::count();

                $courses = DB::table('course')
                ->select(
                    'course_id',
                    'course_name',
                )
                ->get();

                $data = [
                    'title' => 'Admin Dashboard',
                    'scripts' => ['AD_dashboard.js'],
                    'admin' => $admin,
                    'totalLearner' => $learnerCount,
                    'totalInstructor' => $instructorCount,
                    'totalCourse' => $courseCount,
                    'courses' => $courses,
                ];

                // dd($data);
                return view('admin.dashboard')->with($data);

            } catch(\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            return redirect('/admin');
        }
    }


    public function getCountData() {
        if(session()->has('admin')) {
            $admin = session('admin');

             try {

                $learnerData = DB::table('learner')
                ->select(
                    DB::raw("CONCAT(learner_fname, ' ', learner_lname) as name"),
                    'status'
                )
                ->get();

                
                $instructorData = DB::table('instructor')
                ->select(
                    DB::raw("CONCAT(instructor_fname, ' ', instructor_fname) as name"),
                    'status'
                )
                ->get();


                
                $courseData = DB::table('course')
                ->select(
                    'course_name',
                    'course_status',
                )
                ->get();

                $adminData = DB::table('admin')
                ->select(
                    'admin_codename',
                    'role'
                )
                ->get();


                $data = [
                    'title' => 'Admin Dashboard Data',
                    'admin' => $admin,
                    'learners' => $learnerData,
                    'instructors' => $instructorData,
                    'courses' => $courseData,
                    'admins' => $adminData,
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

    public function getCourseProgressData(Request $request) {
        if(session()->has('admin')) {
            $admin = session('admin');

             try {

                $selectedCourse = $request->input('selectedCourse');
                $learnerCourseProgressData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_id',
                    'course_progress'
                )
                ->where('course_id', $selectedCourse)
                ->get();


                $data = [
                    'title' => 'Admin Dashboard Data',
                    'admin' => $admin,
                    'learnerCourseData' => $learnerCourseProgressData,
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

}
