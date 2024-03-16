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
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\PDFGenerationController;

class AdminLearnerController extends Controller
{
     // -------------------admin learner area------------------------
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
    
            return view('admin.learners', compact('learners'))
            ->with(['title' => 'Learner Management', 
                'adminCodeName' => $admin_codename,
                'admin' => $admin,
                'scripts' => ['AD_learners.js']]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    

    public function add_learner() {
        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);
            if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER') {
            $data = [
                'title' => 'Add New Learner',
                'admin' => $admin
            ];
            
            return view('admin.add_learner')->with($data);
            } else {
                return view('error.error');
            }

        } else {
            return redirect('/admin');
        }

    }
    
    public function store_new_learner(Request $request) {
        // dd($request);
        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);
            if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN' || $admin->role === 'USER_MANAGER') {


                $validator = Validator::make($request->all(), [
                    "learner_fname" => ['required'],
                    "learner_lname" => ['required'],
                    "learner_bday" => ['required'],
                    "learner_gender" => ['required'],
                    "learner_contactno" => ['required', Rule::unique('learner', 'learner_contactno')],
                    "learner_email" => ['required', 'email', Rule::unique('learner', 'learner_email')],
                    "business_name" => ['required'],
                    "business_address" => ['required'],
                    "business_owner_name" => ['required'],
                    "bplo_account_number" => ['required'],
                    "business_category" => ['required'],
                    "business_classification" => ['required'],
                    "business_description" => ['nullable'],
                    "learner_username" => ['required', Rule::unique('learner', 'learner_username')],
                    "password" => ['required'],
                    "learner_security_code" => ['required', 'max:6'],
                ]);
                
                if ($validator->fails()) {
                    $errors = $validator->errors()->all();
                    $data = [
                        'message' => 'Validation failed',
                        'errors' => $errors,
                    ];
                    return response()->json($data, 422);
                }
            
                $validatedData = $validator->validated();
            
                // Check if email, username, or contact number is already taken
                $existingEmail = Learner::where('learner_email', $validatedData['learner_email'])->exists();
                $existingUsername = Learner::where('learner_username', $validatedData['learner_username'])->exists();
                $existingContactNo = Learner::where('learner_contactno', $validatedData['learner_contactno'])->exists();
            
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
                
                $LearnerPersonalData = [
                    "learner_fname" => $validatedData['learner_fname'],
                    "learner_lname" => $validatedData['learner_lname'],
                    "learner_bday" => $validatedData['learner_bday'],
                    "learner_gender" => $validatedData['learner_gender'],
                    "learner_contactno" => $validatedData['learner_contactno'],
                    "learner_email" => $validatedData['learner_email'],
                ];
                
                $businessData = [
                    "business_name" => $validatedData['business_name'],
                    "business_address" => $validatedData['business_address'],
                    "business_owner_name" => $validatedData['business_owner_name'],
                    "bplo_account_number" => $validatedData['bplo_account_number'],
                    "business_category" => $validatedData['business_category'],
                    "business_classification" => $validatedData['business_classification'],
                    "business_description" => $validatedData['business_description'],
                ];
                
                $LearnerLoginData = [
                    "learner_username" => $validatedData['learner_username'],
                    "password" => $validatedData['password'],
                    "learner_security_code" => $validatedData['learner_security_code'],
                ];
                

                $LearnerData = array_merge($LearnerPersonalData , $LearnerLoginData);
                $LearnerData['password'] = bcrypt($LearnerData['password']);

                // $folderName = Str::slug($course->course_name, '_');
                
                $folderName = "{$LearnerData['learner_lname']} {$LearnerData['learner_fname']}";
                $folderName = Str::slug($folderName, '_');
                $folderPath = 'learners/' . $folderName;

                // Copy the default photo to the same directory
                $defaultPhoto = '/public/images/default_profile.png';
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

                // return redirect('/admin/learners')->with('title', 'Learner Management')->with('message' , 'Data was successfully stored');
                session()->flash('message', 'Learner Added successfully');
                $data = [
                    'message' => 'Learner changed successfully',
                    'redirect_url' => '/admin/learners',
                ];

                return response()->json($data);
            } else {
                session()->flash('message', 'You cannot create new learner account');
                $data = [
                    'message' => 'You cannot create new learner account',
                    'redirect_url' => '/admin/learners',
                ];

                return response()->json($data);
            }

        } else {
            return redirect('/admin');
        }

    }

    public function view_learner($id) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            // dd($admin);

            if($adminSession->role === 'IT_DEPT' || $adminSession->role === 'SUPER_ADMIN' || $adminSession->role === 'USER_MANAGER') {

                
                $learnerdata = Learner::findOrFail($id);
                $businessdata = Business::where('learner_id', $id)->first(); 

                $data = [
                    'title' => 'View Learner', 
                    'admin' => $adminSession ,
                    'scripts' => ['AD_learner_manage.js'] ,
                    'learner' => $learnerdata,
                    'business' => $businessdata,
                ];

                // dd($data);

                return view('admin.view_learner')->with($data);


            } else {
                return view('error.error');
            }

        }  else {
            return redirect('/admin');
        }


    }

    public function approveLearner(Learner $learner)
    {

        try {
            $learner->update(['status' => 'Approved']);  

            $data = [
                'subject' => 'Your Learner Account Approval',
                'body' => "Hello! Your learner account has been successfully approved by the admin. You can now log in using the link below: \r \n \r \n
            
                [Learner Login](https://eskwela4everyjuan.online/learner) \r \n \r \n
            
                Thank you for joining our platform!",
            ];
            

            try {
                // Create an instance of MailNotify
                $mailNotify = new MailNotify($data);
    
                // Call the to() method on the instance, not statically on the class
                Mail::to($learner->learner_email)->send($mailNotify);
                
                // return response()->json(['Great! check your mail box']);
    
            } catch (\Exception $th) {
                dd($th);
                return response()->json(['Error in sending email']);
            }

            $reportController = new PDFGenerationController();

            $reportController->learnerData($learner->learner_id);


        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        return redirect()->back()->with('message' , 'Learner Status successfully changed');
    }


    public function rejectLearner(Learner $learner)
    {
        try {
            $learner->update(['status' => 'Rejected']);  
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
        return redirect()->back()->with('message' , 'Learner Status successfully changed');
    }

    public function pendingLearner(Learner $learner)
    {
        try {
            $learner->update(['status' => 'Pending']);  
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
        return redirect()->back()->with('message' , 'Learner Status successfully changed');
    }

    public function update_learner(Request $request, Learner $learner) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            $l_id = $learner->learner_id;
        if($adminSession->role === 'IT_DEPT' || $adminSession->role === 'SUPER_ADMIN' || $adminSession->role === 'USER_MANAGER') {

            $withPass = $request->input('withPass');
 

        if($withPass == 1) {
            $LearnerPersonalData = [
                "learner_fname" =>$request->input('learner_fname'),
                "learner_lname" =>$request->input('learner_lname'),
                "learner_bday" =>$request->input('learner_bday'),
                "learner_gender" =>$request->input('learner_gender'),
                "learner_contactno" =>$request->input('learner_contactno'),
                "learner_email" => $request->input('learner_email'),
                "learner_username" => $request->input('learner_username'),
                "password" => $request->input('password'),
                "learner_security_code" => $request->input('learner_security_code'),
            ];
        } else {
            $LearnerPersonalData = [
                "learner_fname" =>$request->input('learner_fname'),
                "learner_lname" =>$request->input('learner_lname'),
                "learner_bday" =>$request->input('learner_bday'),
                "learner_gender" =>$request->input('learner_gender'),
                "learner_contactno" =>$request->input('learner_contactno'),
                "learner_email" => $request->input('learner_email'),
            ];
        }

 
        $businessData = $request->validate ([
            "business_name" => ['required'],
            "business_address" => ['required'],
            "business_owner_name" => ['required'],
            "bplo_account_number" => ['required'],
            "business_category" => ['required'],
            "business_classification" => ['required'],
            "business_description" => ['required'],
        ]);


        try { 


            $learner->update($LearnerPersonalData);

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

            if ($learner && !empty($businessData)) {

                $learnerBusiness = Business::where('learner_id', $l_id)->first();
                if ($learnerBusiness) {
                    try {
                        $learnerBusiness->update($businessData);
                    } catch (\Exception $e) {
                        dd($e->getMessage());
                    }
                }

            }

        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        
        $reportController = new PDFGenerationController();

        $reportController->learnerData($learner->learner_id);

        session()->flash('message', 'Learner Updated successfully');
        // return back()->with('message' , 'Data was successfully updated'); //add ->with('message') later

        $data = [
            'message' => 'Learner updated successfully',
            'redirect_url' => '/admin/view_learner/' . $l_id,
        ];

        return response()->json($data);
    } else {
        session()->flash('message', 'You cannot create new learner account');
        $data = [
            'message' => 'You cannot create new learner account',
            'redirect_url' => '/admin/view_learner/' . $l_id,
        ];

        return response()->json($data);
    }
}  else {
    return redirect('/admin');
}

    }

    public function destroy_learner(Learner $learner) {
        // dd($learner);
        try {

            $relativeFilePath = str_replace('public/', '', $learner->profile_picture);
            if (Storage::disk('public')->exists($relativeFilePath)) {
                // Storage::disk('public')->delete($relativeFilePath);
                $specifiedDir = explode('/', $relativeFilePath);
                array_pop($specifiedDir);

                $dirPath = implode('/', $specifiedDir);

                // dd($dirPath);
                Storage::disk('public')->deleteDirectory($dirPath);
            
                $learner->delete();
            }
    
    
            session()->flash('message', 'Learner deleted Successfully');
            return response()->json(['message' => 'Learner deleted successfully', 'redirect_url' => "/admin/learners"]);
            
        
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    }

}
