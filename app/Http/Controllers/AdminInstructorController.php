<?php

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

class AdminInstructorController extends Controller
{
    // -----------------------admin instructor------------------------- 
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

            return view('admin.instructors', compact('instructors'))
                ->with(['title' => 'Instructor Management', 'admin' => $admin]);

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function add_instructor() {
        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);
            if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER') {
            $data = [
                'title' => 'Add New Instructor',
                'admin' => $admin
            ];
            
            return view('admin.add_instructor')->with($data);
            } else {
                return view('error.error');
            }

        } else {
            return redirect('/admin');
        }
}

public function store_new_instructor(Request $request) {
    if (auth('admin')->check()) {
        $admin = session('admin');
        // dd($admin);
        if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER') {


        $instructorData = $request->validate([
            "instructor_fname" => ['required'],
            "instructor_lname" => ['required'],
            "instructor_bday" => ['required'],
            "instructor_gender" => ['required'],
            "instructor_contactno" => ['required', Rule::unique('instructor', 'instructor_contactno')],
            "instructor_email" => ['required', 'email', Rule::unique('instructor', 'instructor_email')],
            "instructor_username" => ['required', Rule::unique('instructor', 'instructor_username')],
            "password" => ['required'],
            "instructor_security_code" => ['required', 'min:6'],
            "instructor_credentials" => ['required', 'file'],
        ]);

        // Check if email, username, or contact number is already taken
        $existingEmail = Instructor::where('instructor_email', $instructorData['instructor_email'])->exists();
        $existingUsername = Instructor::where('instructor_username', $instructorData['instructor_username'])->exists();
        $existingContactNo = Instructor::where('instructor_contactno', $instructorData['instructor_contactno'])->exists();

        $alreadyTaken = [];
        if ($existingEmail) {
            $alreadyTaken[] = 'Email';
        }
        if ($existingUsername) {
            $alreadyTaken[] = 'Username';
        }
        if ($existingContactNo) {
            $alreadyTaken[] = 'Contact Number';
        }

        if (!empty($alreadyTaken)) {
            $data = [
                'message' => 'Validation failed',
                'errors' => ['The following fields are already taken: ' . implode(', ', $alreadyTaken)],
            ];
            return response()->json($data, 422, [], JSON_UNESCAPED_UNICODE);
        }

        $instructorData['instructor_credentials'] = '';
        $instructorData['profile_picture'] = '';
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
                    $defaultPhoto_path = $folderPath . '/default_profile.png';

                    Storage::copy($defaultPhoto, 'public/' . $defaultPhoto_path);
                    
                    // add to database
                    $instructorData['profile_picture'] = $defaultPhoto_path;
                    $instructorData['instructor_credentials'] = $filePath;
                    Instructor::create($instructorData);

                    session()->flash('message', 'Instructor Added successfully');
                    $data = [
                        'message' => 'Instructor changed successfully',
                        'redirect_url' => '/admin/instructors',
                    ];

                    return response()->json($data);
                } catch (\Exception $e) {
                    session()->flash('message', 'Failed to create new instructor account');
                    $data = [
                        'message' => 'Failed to create new instructor account',
                        'redirect_url' => '/admin/instructors',
                    ];

                    return response()->json($data);
                }
            } 

        }else {
                session()->flash('message', 'You cannot create new instructor account');
                $data = [
                    'message' => 'You cannot create new instructor account',
                    'redirect_url' => '/admin/instructors',
                ];

                return response()->json($data);
            }
        } else {
            return redirect('/admin');
        }
    }




    public function view_instructor ($id) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            // dd($admin);

            if($adminSession->role === 'IT_DEPT' || $adminSession->role === 'SUPER_ADMIN' || $adminSession->role === 'USER_MANAGER') {

                
                $instructorData = Instructor::findOrFail($id);

                $data = [
                    'title' => 'View Instructor', 
                    'admin' => $adminSession ,
                    // 'scripts' => ['AD_learner_manage.js'] ,
                    'instructor' => $instructorData
                ];

                // dd($data);

                return view('admin.view_instructor')->with($data);


            } else {
                return view('error.error');
            }

        }  else {
            return redirect('/admin');
        }
    }

    public function approveInstructor(Instructor $instructor)
    {

        try {
            $instructor->update(['status' => 'Approved']);  

            $data = [
                'subject' => 'Your Instructor Account Approval',
                'body' => 'Hello! Your instructor account has been successfully approved by the admin. You can now log in using the link below:
            
                [Instructor Login](https://eskwela4everyjuan.online/instructor)
            
                Thank you for joining our platform!',
            ];
            

            try {
                // Create an instance of MailNotify
                $mailNotify = new MailNotify($data);
    
                // Call the to() method on the instance, not statically on the class
                Mail::to($instructor->instructor_email)->send($mailNotify);
                
                // return response()->json(['Great! check your mail box']);
    
            } catch (\Exception $th) {
                dd($th);
                return response()->json(['Error in sending email']);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return redirect()->back()->with('message' , 'Instructor Status successfully changed');
    }

    public function rejectInstructor(Instructor $instructor)
    {
        try {
            $instructor->update(['status' => 'Rejected']);  
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
        return redirect()->back()->with('message' , 'Instructor Status successfully changed');
    }

    public function pendingInstructor(Instructor $instructor)
    {
        try {
            $instructor->update(['status' => 'Pending']);  
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
        return redirect()->back()->with('message' , 'Instructor Status successfully changed');
    }

    public function update_instructor(Instructor $instructor, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
    
            if (in_array($adminSession->role, ['IT_DEPT', 'SUPER_ADMIN', 'USER_MANAGER'])) {


                $withPass = $request->input('withPass');
                $instructorData = [
                    "instructor_fname" => $request->input('instructor_fname'),
                    "instructor_lname" => $request->input('instructor_lname'),
                    "instructor_bday" => $request->input('instructor_bday'),
                    "instructor_gender" => $request->input('instructor_gender'),
                    "instructor_contactno" => $request->input('instructor_contactno'),
                    "instructor_email" => $request->input('instructor_email'),
                    "instructor_username" => $request->input('instructor_username'),
                ];
                // dd($instructorData);
    
                if ($withPass == 1) {
                    $instructorData['password'] = bcrypt($request->input('password'));
                    $instructorData['learner_security_code'] = $request->input('instructor_security_code');
                }
    
                $folderName = Str::slug("{$instructorData['instructor_lname']} {$instructorData['instructor_fname']}", '_');
    
                if ($request->hasFile('instructor_credentials')) {
                    $file = $request->file('instructor_credentials');
                    $fileName = time() . '-' . $file->getClientOriginalName();
                    $folderPath = 'instructors/' . $folderName;
                    $filePath = $file->storeAs($folderPath, $fileName, 'public');
    
                    $instructorData['instructor_credentials'] = $filePath;
                }
    
                $instructor->update($instructorData);

                // Generate new folder name
            $folderName = Str::slug("{$instructorData['instructor_lname']} {$instructorData['instructor_fname']}", '_');
            $folderPath = 'instructors/' . $folderName;

            // Rename the folder if the name has changed
            $oldFolderName = Str::slug("{$instructor->original['instructor_lname']} {$instructor->original['instructor_fname']}", '_');
            $oldFolderPath = 'instructors/' . $oldFolderName;

            if (Storage::exists($oldFolderPath)) {
                Storage::move($oldFolderPath, $folderPath);
            }
    
                session()->flash('message', 'Instructor Updated successfully');
                $data = [
                    'message' => 'Learner updated successfully',
                    'redirect_url' => '/admin/view_instructor/' . $instructor->instructor_id,
                ];
    
                return response()->json($data);
            } else {
                session()->flash('message', 'You cannot create new instructor account');
                $data = [
                    'message' => 'You cannot create new instructor account',
                    'redirect_url' => '/admin/view_instructor/' . $instructor->instructor_id,
                ];
    
                return response()->json($data);
            }
        } else {
            return redirect('/admin');
        }
    }
    

    public function destroy_instructor(Instructor $instructor) {
        
        try {

            $relativeFilePath = str_replace('public/', '', $instructor->instructor_credentials);
            if (Storage::disk('public')->exists($relativeFilePath)) {
                // Storage::disk('public')->delete($relativeFilePath);
                $specifiedDir = explode('/', $relativeFilePath);
                array_pop($specifiedDir);

                $dirPath = implode('/', $specifiedDir);

                // dd($dirPath);
                Storage::disk('public')->deleteDirectory($dirPath);
            }
    
            $instructor->delete();
    
            session()->flash('message', 'Instructor deleted Successfully');
            return response()->json(['message' => 'Instructor deleted successfully', 'redirect_url' => "/admin/instructors"]);
            
        
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
