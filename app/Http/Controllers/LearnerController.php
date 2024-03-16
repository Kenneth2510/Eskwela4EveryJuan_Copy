<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Learner;
use App\Models\Course;
use App\Models\LearnerCourse;
use App\Models\session_log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;
use Illuminate\Support\Facades\Cache;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;

use App\Http\Controllers\PDFGenerationController;


class LearnerController extends Controller
{
    public function index() {

        if (session()->has('learner')) {
            return redirect('/learner/dashboard');
        
        } else {
        return view('learner.login')
        ->with([
            'title' => 'Learner Login',
            'scripts' => ['instructorLogin.js']
        ]);
        }

        // return view('learner.login')->with('title', 'Learner Login');
    }

    
    public function login_process(Request $request) {
        
        $learnerData = $request->validate([
            "learner_username" =>  ['required'],
            "password" =>  ['required'],
        ], [
            'learner_username.required' => 'Username is required.',
            'password.required' => 'Password is required.',
        ]);

        $username = $request->input('learner_username');
        $password = $request->input('password');
        $remember = $request->has('remember');
    
        if (empty($username) || empty($password)) {
            return back()->withErrors([
                'learner_username' => 'Username is required.',
                'password' => 'Password is required.',
            ])->withInput($request->except('password'));
        }

        // if (auth('learner')->attempt($learnerData)) {
        //     $learner = auth('learner')->user();
        //     $learner = Learner::find($learner->learner_id);
        //     // dd($instructor);

        //     if($learner) {
        //         $request->session()->put("learner", $learner);
        //         // dd(session('instructor'));
        //         $request->session()->put("learner_authenticated", true);
        //         // dd($request->session()->get("instructor_authenticated"));
        //     }
            
        //     // $request->session()->regenerate();
    
        //     return redirect('/learner/authenticate')->with('message', "Welcome Back");
        // }

        $learnerData = DB::table('learner')
        ->where('learner_username', $username)
        ->first();

        if ($learnerData && Hash::check($password, $learnerData->password)) {
            Cache::put('learner_authenticated', $learnerData->learner_id);
            return redirect('/learner/authenticate')->with('message', "Welcome Back");
        }

        
        return back()->withErrors([
            'learner_username_login' => 'Login failed. Please check your credentials and try again.',
            'password_login' => 'Login failed. Please check your credentials and try again.'
        ])->withInput($request->except('password'))->withInput(['learner_username' => $username]);
    }


    public function forgot_password() {
        return view('learner.forgot')
        ->with([
            'title'=> 'Forgot Password',
            'scripts' => [''],
        ]);
    }


    public function reset(Request $request)
    {
        $email = $request->input('email');
    
        // Check if the email exists in the 'instructor' table
        $learner = DB::table('learner')
            ->select(
                'learner_id',
                'learner_username',
                'learner_fname',
                'learner_lname',
                'learner_email',
            )
            ->where('learner_email', '=', $email)
            ->first();
    
        if (!$learner) {
            return response()->json(['message' => 'Learner not found'], 404);
        }
    
        // Generate a random token
        $token = Str::random(60);
    
        // Store the token in the 'password_resets' table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $learner->learner_email],
            ['email' => $learner->learner_email, 'token' => $token, 'created_at' => now()]
        );
    
        // Send password reset email
        $data = [
            'subject' => 'Password Reset Request',
            'body' => "Hello! \n \n
    
    We received a request to reset your password. If you did not make this request, please ignore this email. \n \n
    
    To reset your password, click the link below:\n \n
    
    [Reset Password](https://eskwela4everyjuan.online/learner/reset_password?token=$token) \n \n
    
    If the link doesn't work, copy and paste the following URL into your browser: \n \n
    
    http://127.0.0.1:8000/learner/reset_password?token=$token \n \n
    
    This link will expire in 1 hour for security reasons. \n \n
    
    Thank you for using our platform!",
        ];
    
        try {
            // Create an instance of MailNotify
            $mailNotify = new MailNotify($data);
    
            // Call the to() method on the instance, not statically on the class
            Mail::to($learner->learner_email)->send($mailNotify);
    
            // return response()->json(['message' => 'Password reset email sent successfully']);
            return view('learner.login')
            ->with([
                'title'=> 'Learner Login',
                'scripts' => ['instructorLogin.js'],
                'message' => 'Password reset email sent successfully',
            ]);
    
        } catch (\Exception $th) {
            dd($th);
            return response()->json(['message' => 'Error in sending email']);
        }
    }


    public function reset_password(Request $request) {

        $token = $request->input('token');

        $passwordResetToken = DB::table('password_reset_tokens')
        ->select(
            'created_at',
            'email',
            'token'
        )
        ->where('token', $token)
        ->first();

        $learner = DB::table('learner')
        ->select(
            'learner_id',
            'learner_username',
            'learner_fname',
            'learner_lname',
            'learner_email',
        )
        ->where('learner_email', $passwordResetToken->email)
        ->first();

        return view('learner.reset')
        ->with([
            'title'=> 'Reset Password',
            'scripts' => [''],
            'learner' =>  $learner,
            'token' => $passwordResetToken,

        ]);
    }


    public function reset_password_process($token, Request $request) {
        $passwordResetToken = DB::table('password_reset_tokens')
            ->select('created_at', 'email', 'token')
            ->where('token', $token)
            ->first();
    
        if (!$passwordResetToken) {
            // Token not found or expired
            return redirect()->route('login')->withErrors(['email' => 'Invalid or expired token.']);
        }
    
        $tokenCreatedAt = \Carbon\Carbon::parse($passwordResetToken->created_at);
        $currentDateTime = now();
    
        if ($tokenCreatedAt->diffInMinutes($currentDateTime) > 60) {
            // Token has expired
            return redirect('/learner')->withErrors(['email' => 'The password reset link has expired.']);
        }
    
        // Check if the new password and confirmation match
        $newPassword = $request->input('password');
        $confirmPassword = $request->input('password_confirmation');
    
        if ($newPassword != $confirmPassword) {
            // Passwords don't match
            return redirect('/learner')->withErrors(['password' => 'The new password and confirmation do not match.']);
        }
    
        // Hash the new password before updating
        $hashedPassword = bcrypt($newPassword);
    
        // Update the password for the instructor
        DB::table('learner')
            ->where('learner_email', $passwordResetToken->email)
            ->update(['password' => $hashedPassword]);
    
        // Optionally, you may want to invalidate the token after using it
        DB::table('password_reset_tokens')
            ->where('token', $token)
            ->delete();
    
        // Redirect to a success page or login page
        return redirect('/learner')->with('status', 'Password successfully changed.');
    }

    public function login_authentication(Request $request) {
        if (!Cache::has('learner_authenticated')) {
            return redirect('/learner')->withErrors(['learner_username' => 'Authentication Required']);
        }
        
        // dd($request->session()->get('instructor_authenticated'));
        return view('learner.authenticate')->with('title', 'Learner Login');

    }

    public function authenticate_learner(Request $request) {

        $learnerId = Cache::get('learner_authenticated');
    
        if (!$learnerId) {
            return redirect('/learner')->withErrors(['learner_username' => 'Authentication Required']);
        }

        $learner = DB::table('learner')
        ->where('learner_id', $learnerId)
        ->first();
            // dd($request);
        $codeNumber = $request->validate([
            "security_code_1" => ['required', 'numeric'],
            "security_code_2" => ['required', 'numeric'],
            "security_code_3" => ['required', 'numeric'],
            "security_code_4" => ['required', 'numeric'],
            "security_code_5" => ['required', 'numeric'],
            "security_code_6" => ['required', 'numeric'],
        ]);

        $securityCodeNumber = implode('', $codeNumber);
        $learnerSecurityCode = $learner->learner_security_code;



        if ($securityCodeNumber === $learnerSecurityCode) {
            // Log the session
            $session_log_data = [
                "session_user_id" => $learner->learner_id,
                "session_user_type" => "LEARNER",
                "session_in" => now()->toDateTimeString(),
            ];
            session_log::create($session_log_data);
    
            // Create the session
            session()->put('learner', $learner);
    
            // Clear unnecessary cache data
            Cache::forget('auth_attempts');
    
            if ($learner->status === 'Approved') {
                return redirect('/learner/dashboard')->with('message', 'Authenticated Successfully');
            } else {
                return redirect('/learner/wait')->with('message', 'Authenticated Successfully');
            }
        } else {
            // Increment the authentication attempts counter
            $attempts = Cache::get('auth_attempts', 0) + 1;
            Cache::put('auth_attempts', $attempts);
    
            $remainingAttempts = 5 - $attempts;
    
            if ($attempts >= 5) {
                // If 5 unsuccessful attempts, destroy the cache
                Cache::forget('learner_authenticated');
                Cache::forget('auth_attempts');
    
                return redirect('/learner/login')->withErrors(['learner_username' => 'Too many incorrect attempts. Session destroyed.']);
            }
    
            return back()->withErrors(['security_code' => 'Invalid Security Code'])->with('remaining_attempts', $remainingAttempts);
        }


    }


    public function wait() {
        return view('learner.wait')
        ->with([
            'title'=> 'Learner Pending',
            'scripts' => [''],
        ]);
    }

    public function logout(Request $request) {

        $learner = session('learner');

        $now = Carbon::now();
        $timestampString = $now->toDateTimeString();
    

        $session_data = DB::table('session_logs')
        ->where('session_user_id', $learner->learner_id)
        ->where('session_user_type' , "LEARNER")
        ->orderBy('session_log_id', 'DESC')
        ->first();

        if ($session_data) {

            DB::table('session_logs')
                ->where('session_log_id', $session_data->session_log_id)
                ->where('session_user_id', $learner->learner_id)
                ->where('session_user_type', "LEARNER")
                ->update([
                    "session_out" => $timestampString,
                ]);

            $sessionIn = Carbon::parse($session_data->session_in);
            $sessionOut = $now;
            $timeDifference = $sessionOut->diffInSeconds($sessionIn);
        
            DB::table('session_logs')
                ->where('session_log_id', $session_data->session_log_id)
                ->update([
                    "time_difference" => $timeDifference,
                ]);
        }


        auth('learner')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/learner')->with('message', 'Logout Successful');

    }

    public function register(){
        return view('learner.register')
        ->with([
            'title' => 'Learner Register',
            'scripts' => ['learnerRegister.js'] ,
        ]);
    }
    // public function register1(){
    //     return view('learner.register1')->with('title', 'Learner Register');
    // }


    public function register_process(Request $request) {
        $LearnerPersonalData = $request->validate ([
            "learner_fname" => ['required'],
            "learner_lname" => ['required'],
            "learner_bday" => ['required'],
            "learner_gender" => ['required'],
            "learner_contactno" => ['required' , Rule::unique('learner' , 'learner_contactno')],
            "learner_email" => ['required' , 'email' , Rule::unique('learner' , 'learner_email')],
        ]);

        $businessData = $request->validate ([
            "business_name" => ['required'],
            "business_address" => ['required'],
            "business_owner_name" => ['required'],
            "bplo_account_number" => ['required'],
            "business_category" => ['required'],
            "business_classification" => ['required'],
            "business_description" => ['required'],
        ]);

        $LearnerLoginData = $request->validate([
            "learner_username" => ['required', Rule::unique('learner' , 'learner_username')],
            "password" => 'required|confirmed',
        ]);

        $codeNumber = $request->validate([
            "security_code_1" => ['required', 'alpha_num'],
            "security_code_2" => ['required', 'alpha_num'],
            "security_code_3" => ['required', 'alpha_num'],
            "security_code_4" => ['required', 'alpha_num'],
            "security_code_5" => ['required', 'alpha_num'],
            "security_code_6" => ['required', 'alpha_num'],
        ]);


        $securityCodeNumber = implode('', $codeNumber);
        // dd($securityCodeNumber);


        

        $LearnerData = array_merge($LearnerPersonalData , $LearnerLoginData , ["learner_security_code" => $securityCodeNumber]);
        $LearnerData['password'] = bcrypt($LearnerData['password']);
        
        $LearnerData['learner_security_code'] = $securityCodeNumber;

        $folderName = "{$LearnerData['learner_lname']} {$LearnerData['learner_fname']}";
        $folderName = Str::slug($folderName, '_');
        $folderPath = 'learners/' . $folderName;

        // Copy the default photo to the same directory
        $defaultPhoto = 'public/images/default_profile.png';
        // $isExists = Storage::exists($defaultPhoto);

        $defaultPhoto_path = $folderPath . '/default_profile.png';
        // dd($defaultPhoto_path);

        $LearnerData['profile_picture'] = $defaultPhoto_path;
        Storage::copy($defaultPhoto, 'public/' . $defaultPhoto_path);
        // $isExists = Storage::exists($defaultPhoto_path);
        // dd($isExists);

        $newCreatedLearner = Learner::firstOrCreate($LearnerData);

        $latestStudent = DB::table('learner')->orderBy('created_at', 'DESC')->first();

        $latestStudentId = $latestStudent->learner_id;

        $businessData['learner_id'] = $latestStudentId;

        Business::firstOrCreate($businessData);

        $folderName = "{$LearnerData['learner_lname']} {$LearnerData['learner_fname']}";
        $folderName = Str::slug($folderName, '_');
        
        // $fileName = time() . '-' . $file->getClientOriginalName();
        $folderPath = '/public/learners/' . $folderName;
        // $filePath = $file->storeAs($folderPath, $fileName, 'public');

        if(!Storage::exists($folderPath)) { 
            Storage::makeDirectory($folderPath);
        }

        $reportController = new PDFGenerationController();

        $reportController->learnerData($newCreatedLearner->learner_id);

        session()->flash('message', 'Learner Account Created successfully');
        return redirect('/learner')->with('title', 'Learner Management')->with('message' , 'Data was successfully stored');
    
    }

    public function dashboard() {
        if (session()->has('learner')) {
            $learner= session('learner');
    
            try {
                // Get the courses the learner is enrolled in
                $enrolledCoursesCheck = DB::table('learner_course')
                    ->select('learner_course.course_id')
                    ->where('learner_course.learner_id', '=', $learner->learner_id)
                    ->get()
                    ->pluck('course_id'); // Get an array of course_ids
    
                // Query for approved courses not in the enrolledCourses list
                $query = DB::table('course')
                    ->select(
                        "course.course_id",
                        "course.course_name",
                        "course.course_code",
                        "instructor.instructor_lname",
                        "instructor.instructor_fname",
                        "instructor.profile_picture"
                    )
                    ->where('course.course_status', '=', 'Approved')
                    ->whereNotIn('course.course_id', $enrolledCoursesCheck)
                    ->join('instructor', 'instructor.instructor_id', '=', 'course.instructor_id')
                    ->orderBy("course.course_name", "ASC");
    
                $courses = $query->get();

                      // Get the courses the learner is enrolled in
                $enrolledCourses = DB::table('learner_course')
                    ->select(
                        'learner_course.course_id',
                        'learner_course.status',
                        'learner_course.created_at',
                        'course.course_name',
                        'course.course_code',
                        'course.course_difficulty',
                        'course.instructor_id',
                        'instructor.instructor_fname',
                        'instructor.instructor_lname',
                        'instructor.profile_picture'
                    )
                    ->join('course', 'learner_course.course_id', '=', 'course.course_id')
                    ->join('instructor', 'course.instructor_id', '=', 'instructor.instructor_id')
                    ->where('learner_course.learner_id', '=', $learner->learner_id)
                    ->where('learner_course.status', '=', 'Approved')
                    ->get();

                // dd($enrolledCourses);

                $reportController = new PDFGenerationController();

                $reportController->learnerSessionData($learner->learner_id);

    
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
    
            return view('learner.dashboard', compact('learner', 'courses', 'enrolledCourses'))
            ->with([
                'title' => 'Learner Dashboard',
                'scripts' => ['learner_dashboard.js'],
            ]);
    
        } else {
            return redirect('/learner');
        }
    }

    public function overviewNum() {
        if (session()->has('learner')) {
            $learner= session('learner');

            try{
                $learnerCourseData = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_progress_id',
                    'learner_course_progress.learner_course_id',
                    'learner_course_progress.learner_id',
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

                $totalLearnerCourseCompleted = DB::table('learner_course_progress')
                ->where('learner_course_progress.learner_id', $learner->learner_id)
                ->where('learner_course_progress.course_progress', "COMPLETED")
                ->count();

                $totalDaysActive = DB::table('session_logs')
                ->select(DB::raw('DATE(session_in) as date'))
                ->where('session_user_id', $learner->learner_id)
                ->where('session_user_type', 'LEARNER')
                ->groupBy(DB::raw('DATE(session_in)'))
                ->get()
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
                    'learner' => $learner,
                    'learnerCourseData' => $learnerCourseData,
                    'totalLearnerCourseCount' => $totalLearnerCourseCount,
                    'totalLearnerApprovedCourseCount' => $totalLearnerApprovedCourseCount,
                    'totalLearnerPendingCourseCount' => $totalLearnerPendingCourseCount,
                    'totalLearnerRejectedCourseCount' => $totalLearnerRejectedCourseCount,
                    'totalCoursesLessonCount' => $totalCoursesLessonCount,
                    'totalCoursesActivityCount' => $totalCoursesActivityCount,
                    'totalCoursesQuizCount' => $totalCoursesQuizCount,
                    'totalCoursesLessonCompletedCount' => $totalCoursesLessonCompletedCount,
                    'totalCoursesActivityCompletedCount' => $totalCoursesActivityCompletedCount,
                    'totalCoursesQuizCompletedCount' => $totalCoursesQuizCompletedCount,
                    'totalDaysActive' => $totalDaysActive,
                    'totalLearnerCourseCompleted' => $totalLearnerCourseCompleted,
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

    public function settings() {

        if (session()->has('learner')) {
            $learner= session('learner');
            // dd($learner);

            $business = Business::where('learner_id', $learner->learner_id)->first();
            // dd($business);
        } else {
            return redirect('/learner');
        }
        return view('learner.settings', compact('learner', 'business'))
        ->with([
            'title' => 'Learner Settings',
            'scripts' => ['learnerUserSettings.js'],
        ]);
    }

    public function update_info(Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner');
            // dd($learner);

            // dd($request);
            
        $updated_learnerData = $request->validate([
            "learner_fname" => ['required'],
            "learner_lname" => ['required'],
            "learner_bday" => ['required'],
            "learner_gender" => ['required'],
        ]);

        $updated_businessData = $request->validate([
            "business_name" => ['required'],
            "business_address" => ['required'],
            "business_owner_name" => ['required'],
            'business_category' => ['required'],
            'business_classification' => ['required'],
            'business_description' => ['required'],
        ]);

        $passwordConfirm = $request->input('password_confirmation');

        if (!empty($passwordConfirm)) {
            if (!Hash::check($passwordConfirm, $learner['password'])) {
                return back()->withErrors(['password_confirmation' => 'Password confirmation does not match.'])->withInput();
            }
        } else {
            return back()->withErrors(['password_confirmation' => 'Password confirmation is required.'])->withInput();
        }

        Learner::where('learner_id', $learner['learner_id'])
                    ->update($updated_learnerData);
        

                    $reportController = new PDFGenerationController();

                    $reportController->learnerData($learner->learner_id);
        
        if ($learner && !empty($businessData)) {

            $learnerBusiness = Business::where('learner_id', $learner['learner_id'])->first();
            if ($learnerBusiness) {
                try {
                    $learnerBusiness->update($updated_businessData);
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }
        }
        

                    
        $learner->learner_fname = $updated_learnerData['learner_fname'];
        $learner->learner_lname = $updated_learnerData['learner_lname'];
        $learner->learner_bday = $updated_learnerData['learner_bday'];
        $learner->learner_gender = $updated_learnerData['learner_gender'];


        $reportController = new PDFGenerationController();

        $reportController->learnerData($learner->learner_id);
            
        session(['learner' => $learner]);

        return redirect('/learner/settings')->with('message' , 'Profile updated successfully');
        
        } else {
            return redirect('/learner');
        }  
    }

    
    public function profile() {
        if (session()->has('learner')) {
            $learner= session('learner');

            try {
            $learnerData = Learner::where('learner_id', $learner->learner_id)->first();
                
            $business = Business::where('learner_id', $learner->learner_id)->first();

            $this->generate_profile_pdf();

            
            $data = [
                'title' => 'Profile',
                'scripts' => ['learnerProfile.js'], 
                'learner' => $learnerData,
                'business' => $business,
            ];

            return view('learner.profile')
            ->with($data);

            } catch (\Exception $e) {
                $e->getMessage();
            }
        } else {
            return redirect('/learner');
        }  
    }


    // public function update_user_info(Request $request) {
    //     if (session()->has('learner')) {
    //         $learner = session('learner');
    
    //         try {
    //             $updated_learnerData = $request->validate([
    //                 "learner_fname" => ['required'],
    //                 "learner_lname" => ['required'],
    //                 "learner_bday" => ['required'],
    //                 "learner_gender" => ['required'],
    //             ]);
    
    //             $oldFolderName = "{$learner->learner_lname} {$learner->learner_fname}";
                
    //             Learner::where('learner_id', $learner->learner_id)
    //                 ->update($updated_learnerData);
    
    //             $newFolderName = "{$updated_learnerData['learner_lname']} {$updated_learnerData['learner_fname']}";
    
    //             Storage::move("path_to_your_storage/{$oldFolderName}", "path_to_your_storage/{$newFolderName}");
    
    //             $reportController = new PDFGenerationController();
    //             $reportController->learnerData($learner->learner_id);
    
    //             session()->flash('message', 'User Info changed successfully');
    
    //         } catch (\Exception $e) {
    //             // Handle the exception
    //             $e->getMessage();
    //         }
    //     } else {
    //         return redirect('/learner');
    //     }  
    // }

    public function update_user_info(Request $request) {
        if (session()->has('learner')) {
            $learner = session('learner');
    
            try {
                $updated_learnerData = $request->validate([
                    "learner_fname" => ['required'],
                    "learner_lname" => ['required'],
                    "learner_bday" => ['required'],
                    "learner_gender" => ['required'],
                ]);
    
                // Update learner data
                Learner::where('learner_id', $learner->learner_id)
                    ->update($updated_learnerData);
    
                // Get the updated learner data
                $learner = Learner::find($learner->learner_id);
    
                // Generate new folder name
                $folderName = "{$learner->learner_lname} {$learner->learner_fname}";
                $folderName = Str::slug($folderName, '_');
                $folderPath = 'learners/' . $folderName;
    
                // Rename the folder
                $oldFolderName = "{$learner->original['learner_lname']} {$learner->original['learner_fname']}";
                $oldFolderName = Str::slug($oldFolderName, '_');
                $oldFolderPath = 'learners/' . $oldFolderName;
    
                if (Storage::exists($oldFolderPath)) {
                    Storage::move($oldFolderPath, $folderPath);
                }
    
                session()->flash('message', 'User Info changed successfully');
    
            } catch (\Exception $e) {
                // Handle exceptions
                $e->getMessage();
            }
        } else {
            return redirect('/learner');
        }
    }
    

    public function update_business_info(Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner');

            try {

                
                $business_name = $request->input('business_name');
                $business_address = $request->input('business_address');
                $business_owner_name = $request->input('business_owner_name');
                $business_category = $request->input('business_category');
                $business_classification = $request->input('business_classification');
                $business_description = $request->input('business_description');

                DB::table('business')
                ->where('learner_id', $learner->learner_id)
                ->update([
                    "business_name" => $business_name,
                    "business_address" => $business_address,
                    "business_owner_name" => $business_owner_name,
                    "business_category" => $business_category,
                    "business_classification" => $business_classification,
                    "business_description" => $business_description,
                ]);
                

                $reportController = new PDFGenerationController();

                $reportController->learnerData($learner->learner_id);


                session()->flash('message', 'User Info changed successfully');
                
                // return redirect('/learner/profile')->with('message' , 'Profile updated successfully');
                return response()->json(['message' => 'User Info changed successfully']);

                
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
                return response()->json(['errors' => $errors], 422);
            }
            } else {
                return redirect('/learner');
            }  
    }

    public function update_login_info(Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner');

            try {
                
                $learnerNewPassword = $request->input('learnerNewPassword');
                $learnerNewPasswordConfirm = $request->input('learnerNewPasswordConfirm');
                $learner_security_code = $request->input('learner_security_code');

                 if($learnerNewPassword == $learnerNewPasswordConfirm && $learner->learner_security_code == $learner_security_code) {

                    $hashedPassword = bcrypt($learnerNewPassword);
                    DB::table('learner')
                    ->where('learner_id', $learner->learner_id)
                    ->update([
                        'password' => $hashedPassword,
                    ]);

                    session()->flash('message', 'User Info changed successfully');
                    $message = 'User Info changed Successfully';
                 } else {
                    session()->flash('message', 'Error Occurred');
                    $message = 'Error Occurred';
                 }

                
                // return redirect('/learner/profile')->with('message' , 'Profile updated successfully');
                return response()->json(['message' => $message]);

                
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
                return response()->json(['errors' => $errors], 422);
            }
            } else {
                return redirect('/learner');
            }  
    }


    
    public function update_profile_photo(Request $request) {
        if (session()->has('learner')) {
            $learner= session('learner');
       
        $learnerData = $request->validate([
            "profile_picture" => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $folderName = "{$learner->learner_lname} {$learner->learner_fname}";
        $folderName = Str::slug($folderName, '_');
        $fileName = time() . '-' . $learnerData['profile_picture']->getClientOriginalName();
        $folderPath = 'learners/' . $folderName; // Specify the public directory
        $filePath = $learnerData['profile_picture']->storeAs($folderPath, $fileName, 'public');
    
        try {
            // Update the instructor's profile_picture directly with the correct path
            Learner::where('learner_id', $learner->learner_id)
                ->update(['profile_picture' => $filePath]);

                $learner->profile_picture = $filePath;
                session(['learner' => $learner]);

                return redirect('/learner/profile')->with('message', 'Profile picture updated successfully');
    
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    
        
    } else {
        return redirect('/learner');
    }

    }


    public function view_other_learner($email) {
        if (session()->has('learner')) {
            $learner= session('learner');

            try {
                
                $learnerData = DB::table('learner')
                ->where('learner_email', $email)
                ->first();

                $businessData = DB::table('business')
                ->where('learner_id', $learnerData->learner_id)
                ->first();

                $courseProgress = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_id',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',
                    'learner_course_progress.course_id',
                    'course.course_name',
                )
                ->join('course', 'learner_course_progress.course_id', 'course.course_id')
                ->where('learner_course_progress.learner_id', $learnerData->learner_id)
                ->get();
   
            $data = [
                'title' => 'Profile',
                'scripts' => ['userProfile.js'], 
                'learner' => $learnerData,
                'business' => $businessData,
                'courses' => $courseProgress,
            ];

            return view('learner.viewLearnerProfile')
            ->with($data);

            } catch (\Exception $e) {
                $e->getMessage();
            }
        } else {
            return redirect('/learner');
        }  
    }


    public function view_other_instructor($email) {
        if (session()->has('learner')) {
            $learner= session('learner');

            try {
                
                $instructorData = DB::table('instructor')
                ->where('instructor_email', $email)
                ->first();

                $courseData = DB::table('learner_course_progress')
                ->select(
                    DB::raw('COUNT(learner_course_progress.learner_course_id) as learner_count'),
                    'course.course_name'
                )
                ->join('course', 'learner_course_progress.course_id', 'course.course_id')
                ->where('course.instructor_id', $instructorData->instructor_id)
                ->groupBy(
                    'course.course_name'
                )
                ->get();
            

            $data = [
                'title' => 'Profile',
                'scripts' => ['userProfile.js'], 
                'learner' => $learner,
                'instructor' => $instructorData,
                'courses' => $courseData,
            ];

            return view('learner.viewInstructorProfile')
            ->with($data);

            } catch (\Exception $e) {
                $e->getMessage();
            }
        } else {
            return redirect('/learner');
        }  
    }

public function generate_profile_pdf() {
    if (session()->has('learner')) {
        $learner= session('learner');

        try {
            $learnerData = Learner::where('learner_id', $learner->learner_id)->first();
            $business = Business::where('learner_id', $learner->learner_id)->first();
            $courseProgress = DB::table('learner_course_progress')
                ->select(
                    'learner_course_progress.learner_course_id',
                    'learner_course_progress.course_progress',
                    'learner_course_progress.start_period',
                    'learner_course_progress.finish_period',
                    'learner_course_progress.course_id',
                    'course.course_name',
                )
                ->join('course', 'learner_course_progress.course_id', 'course.course_id')
                ->where('learner_course_progress.learner_id', $learnerData->learner_id)
                ->get();

            $data = [
                'title' => 'Profile',
                'scripts' => ['userProfile.js'],
                'learner' => $learnerData,
                'business' => $business,
                'courses' => $courseProgress
            ];

            // Render the view with the Blade template
            $html = view('learner.previewProfile', compact('learner'))->with($data)->render();

            // Generate a unique filename for the PDF
            $filename = $learner->learner_id . '_' . $learner->learner_fname . '_' . $learner->learner_lname .'.pdf';

            // Initialize Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the PDF
            $dompdf->render();

            // Store the new PDF in the public directory within the course-specific folder
            $folderName = Str::slug("{$learner->learner_lname} {$learner->learner_fname}", '_');
            $folderPath = 'learners/' . $folderName . '/documents';
            $pdf = $dompdf->output();
            Storage::disk('public')->put($folderPath . '/' . $filename, $pdf);

            // Generate the URL to the stored PDF
            $pdfUrl = URL::to('storage/' . $folderPath . '/' . $filename);

            return null;

        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to generate PDF: ' . $e->getMessage());

            // Optionally, return an error response or redirect the user
            return response()->json(['success' => false, 'message' => 'Failed to generate PDF']);
        }
    } else {
        return redirect('/learner');
    }
}

    public function learnerData() {
        if (session()->has('learner')) {
            $learner= session('learner');

            return response()->json(['learner' => $learner]);
        } else {
            return redirect('/learner');
        }  
    }
}
