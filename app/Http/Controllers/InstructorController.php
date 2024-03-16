<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Learner;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Admin;
use App\Models\session_log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;
use Illuminate\Support\Facades\Cache;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\URL;

class InstructorController extends Controller
{
    public function index() {
        if (session()->has('instructor')) {
            return redirect('/instructor/dashboard');
        }
    
        return view('instructor.login')->with([
            'title'=> 'Instructor Login',
            'scripts' => ['instructorLogin.js'],
        ]);
    }

    
    public function login_process(Request $request) {
        $request->validate([
            "instructor_username" => ['required', 'string'],
            "password" => ['required', 'string'],
        ], [
            'instructor_username.required' => 'Username is required.',
            'password.required' => 'Password is required.',
        ]);
    
        $username = $request->input('instructor_username');
        $password = $request->input('password');
        $remember = $request->has('remember');
    
        if (empty($username) || empty($password)) {
            return back()->withErrors([
                'instructor_username' => 'Username is required.',
                'password' => 'Password is required.',
            ])->withInput($request->except('password'));
        }
    
        $instructorData = DB::table('instructor')
            ->where('instructor_username', $username)
            ->first();
    
        if ($instructorData && Hash::check($password, $instructorData->password)) {
            Cache::put('instructor_authenticated', $instructorData->instructor_id);
            return redirect('/instructor/authenticate')->with('message', "Welcome Back");
        }
    
        return back()->withErrors([
            'instructor_username_login' => 'Login failed. Please check your credentials and try again.',
            'password_login' => 'Login failed. Please check your credentials and try again.'
        ])->withInput($request->except('password'))->withInput(['instructor_username' => $username]);
    }
    
    public function forgot_password() {
        return view('instructor.forgot')
        ->with([
            'title'=> 'Forgot Password',
            'scripts' => [''],
        ]);
    }

    public function reset(Request $request)
    {
        $email = $request->input('email');
    
        // Check if the email exists in the 'instructor' table
        $instructor = DB::table('instructor')
            ->select(
                'instructor_id',
                'instructor_username',
                'instructor_fname',
                'instructor_lname',
                'instructor_email',
            )
            ->where('instructor_email', '=', $email)
            ->first();
    
        if (!$instructor) {
            return response()->json(['message' => 'Instructor not found'], 404);
        }
    
        // Generate a random token
        $token = Str::random(60);
    
        // Store the token in the 'password_resets' table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $instructor->instructor_email],
            ['email' => $instructor->instructor_email, 'token' => $token, 'created_at' => now()]
        );
    
        // Send password reset email
        $data = [
            'subject' => 'Password Reset Request',
            'body' => "Hello! \n \n
    
    We received a request to reset your password. If you did not make this request, please ignore this email.\n
    
    To reset your password, click the link below:\n
    
    [Reset Password](https://eskwela4everyjuan.online/instructor/reset_password?token=$token)\n
    
    If the link doesn't work, copy and paste the following URL into your browser:\n
    
    http://127.0.0.1:8000/instructor/reset_password?token=$token\n
    
    This link will expire in 1 hour for security reasons.\n
    
    Thank you for using our platform!",
        ];
    
        try {
            // Create an instance of MailNotify
            $mailNotify = new MailNotify($data);
    
            // Call the to() method on the instance, not statically on the class
            Mail::to($instructor->instructor_email)->send($mailNotify);
    
            // return response()->json(['message' => 'Password reset email sent successfully']);
            return view('instructor.login')
            ->with([
                'title'=> 'Instructor Login',
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

        $instructor = DB::table('instructor')
        ->select(
            'instructor_id',
            'instructor_username',
            'instructor_fname',
            'instructor_lname',
            'instructor_email',
        )
        ->where('instructor_email', $passwordResetToken->email)
        ->first();

        return view('instructor.reset')
        ->with([
            'title'=> 'Reset Password',
            'scripts' => [''],
            'instructor' =>  $instructor,
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
            return redirect('/instructor')->withErrors(['email' => 'The password reset link has expired.']);
        }
    
        // Check if the new password and confirmation match
        $newPassword = $request->input('password');
        $confirmPassword = $request->input('password_confirmation');
    
        if ($newPassword != $confirmPassword) {
            // Passwords don't match
            return redirect('/instructor')->withErrors(['password' => 'The new password and confirmation do not match.']);
        }
    
        // Hash the new password before updating
        $hashedPassword = bcrypt($newPassword);
    
        // Update the password for the instructor
        DB::table('instructor')
            ->where('instructor_email', $passwordResetToken->email)
            ->update(['password' => $hashedPassword]);
    
        // Optionally, you may want to invalidate the token after using it
        DB::table('password_reset_tokens')
            ->where('token', $token)
            ->delete();
    
        // Redirect to a success page or login page
        return redirect('/instructor')->with('status', 'Password successfully changed.');
    }
    

    public function login_authentication(Request $request) {
        if (!Cache::has('instructor_authenticated')) {
            return redirect('/instructor')->withErrors(['instructor_username' => 'Authentication Required']);
        }
    
        return view('instructor.authenticate')->with('title', 'Instructor Login');
    }
    
    
    
    public function authenticate_instructor(Request $request) {
        $instructorId = Cache::get('instructor_authenticated');
    
        if (!$instructorId) {
            return redirect('/instructor')->withErrors(['instructor_username' => 'Authentication Required']);
        }
    
        $instructor = DB::table('instructor')
            ->where('instructor_id', $instructorId)
            ->first();
    
        $codeNumber = $request->validate([
            "security_code_1" => ['required', 'numeric'],
            "security_code_2" => ['required', 'numeric'],
            "security_code_3" => ['required', 'numeric'],
            "security_code_4" => ['required', 'numeric'],
            "security_code_5" => ['required', 'numeric'],
            "security_code_6" => ['required', 'numeric'],
        ]);
    
        $securityCodeNumber = implode('', $codeNumber);
        $instructorSecurityCode = $instructor->instructor_security_code;
    
        // Check if security code is correct
        if ($securityCodeNumber === $instructorSecurityCode) {
            // Log the session
            $session_log_data = [
                "session_user_id" => $instructor->instructor_id,
                "session_user_type" => "INSTRUCTOR",
                "session_in" => now()->toDateTimeString(),
            ];
            session_log::create($session_log_data);
    
            // Create the session
            session()->put('instructor', $instructor);
    
            // Clear unnecessary cache data
            Cache::forget('auth_attempts');
    
            if ($instructor->status === 'Approved') {
                return redirect('/instructor/dashboard')->with('message', 'Authenticated Successfully');
            } else {
                return redirect('/instructor/wait')->with('message', 'Authenticated Successfully');
            }
        } else {
            // Increment the authentication attempts counter
            $attempts = Cache::get('auth_attempts', 0) + 1;
            Cache::put('auth_attempts', $attempts);
    
            $remainingAttempts = 5 - $attempts;
    
            if ($attempts >= 5) {
                // If 5 unsuccessful attempts, destroy the cache
                Cache::forget('instructor_authenticated');
                Cache::forget('auth_attempts');
    
                return redirect('/instructor/login')->withErrors(['instructor_username' => 'Too many incorrect attempts. Session destroyed.']);
            }
    
            return back()->withErrors(['security_code' => 'Invalid Security Code'])->with('remaining_attempts', $remainingAttempts);
        }
    }
    
    

    public function wait() {
        return view('instructor.wait')
        ->with([
            'title'=> 'Instructor Pending',
            'scripts' => [''],
        ]);
    }
    

    public function logout(Request $request) {

        // $instructor = auth('instructor')->user();

        $instructor = session('instructor');


        $now = Carbon::now();
        $timestampString = $now->toDateTimeString();

  
        $session_data = DB::table('session_logs')
        ->where('session_user_id', $instructor->instructor_id)
        ->where('session_user_type' , "INSTRUCTOR")
        ->orderBy('session_log_id', 'DESC')
        ->first();

        if ($session_data) {

            DB::table('session_logs')
                ->where('session_log_id', $session_data->session_log_id)
                ->where('session_user_id', $instructor->instructor_id)
                ->where('session_user_type', "INSTRUCTOR")
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
        Cache::forget('instructor_authenticated');
        Cache::forget('instructor');

        auth('instructor')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/instructor')->with('message', 'Logout Successful');

    }

    // public function register(){
    //     return view('instructor.register')->with('title', 'Instructor Register');
    // }

    public function register1(){
        return view('instructor.register1')
        ->with([
            'title' => 'Instructor Register',
            'scripts' =>  ['instructorRegister.js'],
        ]);
    }

    public function register_process(Request $request) {
        // dd($request);
        $instructorData = $request->validate([
            "instructor_fname" => ['required'],
            "instructor_lname" => ['required'],
            "instructor_bday" => ['required'],
            "instructor_gender" => ['required'],
            "instructor_contactno" => ['required', Rule::unique('instructor', 'instructor_contactno')],
            "instructor_email" => ['required', 'email', Rule::unique('instructor', 'instructor_email')],
            "instructor_username" => ['required', Rule::unique('instructor' , 'instructor_username')],
            "password" => 'required|confirmed',
            // "instructor_security_code" => ['required'],
            "instructor_credentials" => ['required', 'file'],
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
        $instructorData['instructor_security_code'] = $securityCodeNumber;


        $instructorData['instructor_credentials'] = '';
        $instructorData['password'] = bcrypt($instructorData['password']);
    
        $folderName = "{$instructorData['instructor_lname']} {$instructorData['instructor_fname']}";
        $folderName = Str::slug($folderName, '_');
        if($request->hasFile('instructor_credentials')) {
            
            $file = $request->file('instructor_credentials');
            
            try {

           
                $fileName = time() . '-' . $file->getClientOriginalName();
                $folderPath = 'instructors/' . $folderName;
                $filePath = $file->storeAs($folderPath, $fileName, 'public');

                // Copy the default photo to the same directory
                $defaultPhoto = 'public/images/default_profile.png';
                // $isExists = Storage::exists($defaultPhoto);

                $defaultPhoto_path = $folderPath . '/default_profile.png';
                // dd($defaultPhoto_path);

                Storage::copy($defaultPhoto, 'public/' . $defaultPhoto_path);
                // $isExists = Storage::exists($defaultPhoto_path);
                // dd($isExists);
              
                
                // add to database
                $instructorData['profile_picture'] = $defaultPhoto_path;

            $instructorData['instructor_credentials'] = $filePath;
            // dd($instructorData);
            Instructor::create($instructorData);

            //add to storage
            // if(!Storage::exists($folderPath)) {
            //     Storage::makeDirectory($folderPath);
            // }

            // Storage::putFileAs($folderPath, $file, $fileName);


           } catch (\Exception $e) {
            dd($e->getMessage());
           }

           session()->flash('message', 'Instructor Account Created successfully');
           return redirect('/instructor')->with('title', 'Instructor Login')->with('message' , 'Account Successfully created');
        }
    }

    public function dashboard(){
        if (!session()->has('instructor')) {
            return redirect('/instructor');
        }
    
        // Retrieve the instructor data from the session
        $instructor = session('instructor');

        $instructorId = Cache::get('instructor_authenticated');
        $instructor = DB::table('instructor')
            ->where('instructor_id', $instructorId)
            ->first();


        try {
            $coursesCount = Course::where('instructor_id', $instructor->instructor_id)->count();
            $courseApproved = Course::where('course_status', "LIKE", 'Approved%')
            ->where('instructor_id', $instructor->instructor_id)
            ->count();

            // $courses = Course::where('instructor_id', $instructor->instructor_id)->limit(3)->get();

            $courses = DB::table('course')
            ->select(
                'course.course_id',
                'course.course_name',
                'course.course_code',
                'course.course_status',
                'course.course_difficulty',
                'course.instructor_id',

                'instructor.instructor_fname',
                'instructor.instructor_lname',
            )
            ->join('instructor', 'course.instructor_id', '=', 'instructor.instructor_id')
            ->where('course.instructor_id', $instructor->instructor_id)
            ->get();

            // dd($courses);

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
       

        return view('instructor.dashboard', compact('instructor'))
        ->with([
            'title' => 'Instructor Dashboard',
            'scripts' => ['instructor_dashboard.js'],
            'courses' => $courses,
            'coursesCount' => $coursesCount,
            'courseApproved'=>$courseApproved,
        ]);
    }

    public function overviewNum() {
        if (session()->has('instructor')) {
            $instructor = session('instructor');

            try {

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
                    DB::raw('COUNT(learner_course.learner_course_id) as learnerCount'),
                    DB::raw('COUNT(CASE WHEN learner_course.status = "Approved" THEN learner_course.learner_course_id END) as approvedLearnerCount')
                )
                ->join('learner_course', 'learner_course.course_id', '=', 'course.course_id')
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

                return response()->json($data);
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
            }

        } else {
            return redirect('/instructor');
        }


    }

    public function settings(){

        
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            // dd($instructor);

        } else {
            return redirect('/instructor');
        }
        return view('instructor.settings', compact('instructor'))
        ->with([
            'title' => 'Instructor Profile',
            'scripts' => ['instructorSettings.js'],
        ]);
    }

    public function update_info(Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            // dd($instructor);


            
        $updated_instructorData = $request->validate([
            "instructor_fname" => ['required'],
            "instructor_lname" => ['required'],
            "instructor_bday" => ['required'],
            "instructor_gender" => ['required'],
            "instructor_contactno" => ['required'],
        ]);

        $passwordConfirm = $request->input('password_confirmation');

        if (!empty($passwordConfirm)) {
            if (!Hash::check($passwordConfirm, $instructor['password'])) {
                return back()->withErrors(['password_confirmation' => 'Password confirmation does not match.'])->withInput();
            }
        } else {
            return back()->withErrors(['password_confirmation' => 'Password confirmation is required.'])->withInput();
        }

        Instructor::where('instructor_id', $instructor['instructor_id'])
                    ->update($updated_instructorData);
        
        $instructor->instructor_fname = $updated_instructorData['instructor_fname'];
        $instructor->instructor_lname = $updated_instructorData['instructor_lname'];
        $instructor->instructor_bday = $updated_instructorData['instructor_bday'];
        $instructor->instructor_gender = $updated_instructorData['instructor_gender'];
        $instructor->instructor_contactno = $updated_instructorData['instructor_contactno'];
            
        session(['instructor' => $instructor]);

        return redirect('/instructor/settings')->with('message' , 'Profile updated successfully');
        
        } else {
            return redirect('/instructor');
        }  
    }

    public function update_profile(Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
        } else {
            return redirect('/instructor');
        }
    
        $instructorData = $request->validate([
            "profile_picture" => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $folderName = "{$instructor['instructor_lname']} {$instructor['instructor_fname']}";
        $folderName = Str::slug($folderName, '_');
        $fileName = time() . '-' . $instructorData['profile_picture']->getClientOriginalName();
        $folderPath = 'instructors/' . $folderName; // Specify the public directory
        $filePath = $instructorData['profile_picture']->storeAs($folderPath, $fileName, 'public');
    
        try {
            // Update the instructor's profile_picture directly with the correct path
            Instructor::where('instructor_id', $instructor['instructor_id'])
                ->update(['profile_picture' => $filePath]);

                $instructor['profile_picture'] = $filePath;
                session(['instructor' => $instructor]);
    
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    
        return redirect('/instructor/settings')->with('message', 'Profile picture updated successfully');
    }
   

    
    public function profile() {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
            
            try {
            $instructorData = Instructor::where('instructor_id', $instructor->instructor_id)->first();
                
            // $courseData = DB::table('learner_course_progress')
            // ->select(
            //     DB::raw('COUNT(learner_course_progress.learner_course_id) as learner_count'),
            //     'course.course_name'
            // )
            // ->join('course', 'learner_course_progress.course_id', 'course.course_id')
            // ->where('course.instructor_id', $instructorData->instructor_id)
            // ->groupBy(
            //     'course.course_name'
            // )
            // ->get();

            $coursesManaged = DB::table('course')
            ->select(
                'course_id',
                'course_name',
                'course_status'
            )
            ->where('instructor_id', $instructorData->instructor_id)
            ->get();

            // $this->generate_profile_pdf();

            // dd($instructorData);
            $data = [
                'title' => 'Profile',
                'scripts' => ['instructorProfile.js'], 
                'instructor' => $instructorData,
                'courses' => $coursesManaged,
            ];

            return view('instructor.profile')
            ->with($data);

            } catch (\Exception $e) {
                $e->getMessage();
            }
        } else {
            return redirect('/instructor');
        }  
    }


    // public function update_user_info(Request $request) {
    //     if (session()->has('instructor')) {
    //         $instructor= session('instructor');

    //         try {
    //             // dd($request);
    //             $updated_instructorData = $request->validate([
    //                 "instructor_fname" => ['required'],
    //                 "instructor_bday" => ['required'],
    //                 "instructor_lname" => ['required'],
    //                 "instructor_gender" => ['required'],
    //             ]);

    //             Instructor::where('instructor_id', $instructor->instructor_id)
    //             ->update($updated_instructorData);

                
    //             session()->flash('message', 'User Info changed successfully');
                
    //             // return redirect('/learner/profile')->with('message' , 'Profile updated successfully');
        
    
    //             } catch (\Exception $e) {
    //                 $e->getMessage();
    //             }
    //         } else {
    //             return redirect('/instructor');
    //         }  
    // }

    public function update_user_info(Request $request) {
        if (session()->has('instructor')) {
            $instructor = session('instructor');
    
            try {
                $updated_instructorData = $request->validate([
                    "instructor_fname" => ['required'],
                    "instructor_bday" => ['required'],
                    "instructor_lname" => ['required'],
                    "instructor_gender" => ['required'],
                ]);
    
                // Update instructor data
                Instructor::where('instructor_id', $instructor->instructor_id)
                    ->update($updated_instructorData);
    
                // Get the updated instructor data
                $instructor = Instructor::find($instructor->instructor_id);
    
                // Generate new folder name
                $folderName = "{$instructor->instructor_lname} {$instructor->instructor_fname}";
                $folderName = Str::slug($folderName, '_');
                $folderPath = 'instructors/' . $folderName;
    
                // Rename the folder
                $oldFolderName = "{$instructor->original['instructor_lname']} {$instructor->original['instructor_fname']}";
                $oldFolderName = Str::slug($oldFolderName, '_');
                $oldFolderPath = 'instructors/' . $oldFolderName;
    
                if (Storage::exists($oldFolderPath)) {
                    Storage::move($oldFolderPath, $folderPath);
                }
    
                session()->flash('message', 'User Info changed successfully');
    
            } catch (\Exception $e) {
                // Handle exceptions
                $e->getMessage();
            }
        } else {
            return redirect('/instructor');
        }
    }
    

    public function update_login_info(Request $request) {
        if (session()->has('instructor')) {
            $instructor= session('instructor');

            try {
                
                $instructorNewPassword = $request->input('instructorNewPassword');
                $instructorNewPasswordConfirm = $request->input('instructorNewPasswordConfirm');
                $instructor_security_code = $request->input('instructor_security_code');

                 if($instructorNewPassword == $instructorNewPasswordConfirm && $instructor->instructor_security_code == $instructor_security_code) {

                    $hashedPassword = bcrypt($instructorNewPassword);
                    DB::table('instructor')
                    ->where('instructor_id', $instructor->instructor_id)
                    ->update([
                        'password' => $hashedPassword,
                    ]);

                    session()->flash('message', 'User Info changed successfully');
                    $message = 'User Info changed Successfully';
                 } else {
                    session()->flash('message', 'Your Security Code seems to be incorrect');
                    $message = 'Error Occurred';
                 }

                
                // return redirect('/learner/profile')->with('message' , 'Profile updated successfully');
                return response()->json(['message' => $message]);

                
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
                return response()->json(['errors' => $errors], 422);
            }
            } else {
                return redirect('/instructor'); 
            }  
    }


    
    public function update_profile_photo(Request $request) {
        if (session()->has('instructor')) {
            $instructor= session('instructor');
       
        $instructorData = $request->validate([
            "profile_picture" => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $folderName = "{$instructor->instructor_lname} {$instructor->instructor_fname}";
        $folderName = Str::slug($folderName, '_');
        $fileName = time() . '-' . $instructorData['profile_picture']->getClientOriginalName();
        $folderPath = 'learners/' . $folderName; // Specify the public directory
        $filePath = $instructorData['profile_picture']->storeAs($folderPath, $fileName, 'public');
    
        try {
            // Update the instructor's profile_picture directly with the correct path
            Instructor::where('instructor_id', $instructor->instructor_id)
                ->update(['profile_picture' => $filePath]);

                $instructor->profile_picture = $filePath;
                session(['instructor' => $instructor]);

                return redirect('/instructor/profile')->with('message', 'Profile picture updated successfully');
    
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    
        
    } else {
        return redirect('/instructor');
    }

    }


    public function view_other_learner($email) {
        if (session()->has('instructor')) {
            $instructor= session('instructor');

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
                'instructor' => $instructor,
            ];

            return view('instructor.viewLearnerProfile')
            ->with($data);

            } catch (\Exception $e) {
                $e->getMessage();
            }
        } else {
            return redirect('/instructor');
        }  
    }


    public function view_other_instructor($email) {
        if (session()->has('instructor')) {
            $instructor= session('instructor');

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
                'instructor' => $instructor,
                'instructorData' => $instructorData,
                'courses' => $courseData,
            ];

            return view('instructor.viewInstructorProfile')
            ->with($data);

            } catch (\Exception $e) {
                $e->getMessage();
            }
        } else {
            return redirect('/instructor');
        }  
    }

   
}
