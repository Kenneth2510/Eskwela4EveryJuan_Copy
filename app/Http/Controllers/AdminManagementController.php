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

class AdminManagementController extends Controller
{
    public function index() {
        return $this->search_admin();
    }
    
    public function search_admin() {
        
        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);
    

        $search_by = request('searchBy');
        $search_val = request('searchVal');




        try {
            $query = DB::table('admin')
                ->select(
                    'admin_id',
                    'admin_username',
                    'admin_codename',
                    'role',
                )
                ->where('role', '!=', 'SUPER_ADMIN');

                if(!empty($search_by) && !empty($search_val)) {
                $query->where($search_by, 'LIKE', '%' . $search_val . '%');
                }

     
            $adminData = $query->paginate(10);
    
            $data = [
                'title' => 'Admin Management',
                "admin" => $admin,
                "adminData" => $adminData,
                'scripts' => ['AD_learners.js'],
            ];

            return view('admin.adminManage')
            ->with($data);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }
            
        }  else {
            return redirect('/admin');
        }

    }

    public function add_new_admin() {
        if (auth('admin')->check()) {
            $admin = session('admin');
            // dd($admin);

            if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN') {
                $data = [
                    'title' => 'Add Admin',
                    // 'scripts' => ['AD_add_new_admin.js'],
                    'admin' => $admin,
                ];

                return view('admin.add_admin')
                ->with($data);
            } else {
                return view('error.error');
            }



    
        }  else {
            return redirect('/admin');
        }
    }

    public function submit_new_admin(Request $request) {
        if (auth('admin')->check()) {
            $admin = session('admin');
            try {
                if($admin->role === 'IT_DEPT' || $admin->role === 'SUPER_ADMIN') {
                $adminData = $request->validate([
                    'admin_codename' => ['required'],
                    'admin_username' => ['required'],
                    'role' => ['required'],
                    'password' => ['required'],
                ]);
                
                $adminData['password'] = bcrypt($adminData['password']);

                Admin::create($adminData);


                session()->flash('message', 'Admin Added successfully');
                $data = [
                    'message' => 'User Info changed successfully',
                    'redirect_url' => '/admin/admins',
                ];

                return response()->json($data);
            } else {
                // return view('error.error');
                
                dd($admin);
                session()->flash('message', 'You cannot create new admin account');
                $data = [
                    'message' => 'You cannot create new admin account',
                    'redirect_url' => '/admin/admins',
                ];

                return response()->json($data);
            }

                
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
                return response()->json(['errors' => $errors], 422);
            }
        }  else {
            return redirect('/admin');
        }
    }


    public function view_admin(Admin $admin) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            // dd($admin);

            if($adminSession->role === 'IT_DEPT' || $adminSession->role === 'SUPER_ADMIN') {

                $adminData = DB::table('admin')
                ->select(
                    'admin_id',
                    'admin_codename',
                    'role',
                    'admin_username',
                )
                ->where('admin_id', $admin->admin_id)
                ->where('role', '!=', 'SUPER_ADMIN')
                ->first();

                $data = [
                    'title' => 'View Admin',
                    'adminData' => $adminData,
                    // 'scripts' => ['AD_add_new_admin.js'],
                    'admin' => $adminSession,
                ];

                if(!$adminData) {
                    return view('error.error');
                }

                return view('admin.view_admin')
                ->with($data);
            } else {
                return view('error.error');
            }

        }  else {
            return redirect('/admin');
        }
    }

    public function update_admin (Admin $admin, Request $request) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            try {

                if($adminSession->role === 'IT_DEPT' || $adminSession->role === 'SUPER_ADMIN') {

                $withPass = $request->input('withPass');

                if($withPass == 1) {
                    $adminData = [
                        'admin_codename' => $request->input('admin_codename'),
                        'admin_username' => $request->input('admin_username'),
                        'role' => $request->input('role'),
                        'password' => bcrypt($request->input('newPassword')),
                    ];
                } else {
                    $adminData = [
                        'admin_codename' => $request->input('admin_codename'),
                        'admin_username' => $request->input('admin_username'),
                        'role' => $request->input('role'),
                    ];
                }

                DB::table('admin')
                ->where('admin_id', $admin->admin_id)
                ->where('role', '!=', 'SUPER_ADMIN')
                ->update($adminData);



                session()->flash('message', 'Admin Updated successfully');
                $data = [
                    'message' => 'User Info changed successfully',
                    'redirect_url' => '/admin/view_admin/'. $admin->admin_id,
                ];

                return response()->json($data);
            } else {
                // return view('error.error');
                
                session()->flash('message', 'You cannot create new admin account');
                $data = [
                    'message' => 'You cannot create new admin account',
                    'redirect_url' => '/admin/view_admin/'. $admin->admin_id,
                ];

                return response()->json($data);
            }

                
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
                return response()->json(['errors' => $errors], 422);
            }
        }  else {
            return redirect('/admin');
        }
    }

    public function delete_admin(Admin $admin) {
        if (auth('admin')->check()) {
            $adminSession = session('admin');
            try {

                if($adminSession->role === 'IT_DEPT' || $adminSession->role === 'SUPER_ADMIN') {

               
                DB::table('admin')
                ->where('admin_id', $admin->admin_id)
                ->where('role', '!=', 'SUPER_ADMIN')
                ->delete();

                session()->flash('message', 'Admin Deleted successfully');
                $data = [
                    'message' => 'User Info changed successfully',
                    'redirect_url' => '/admin/admins',
                ];

                return response()->json($data);
            } else {
                // return view('error.error');
                
                session()->flash('message', 'You cannot delete admin account');
                $data = [
                    'message' => 'You cannot delete admin account',
                    'redirect_url' => '/admin/admin',
                ];

                return response()->json($data);
            }

                
            } catch (ValidationException $e) {
                $errors = $e->validator->errors();
                return response()->json(['errors' => $errors], 422);
            }
        }  else {
            return redirect('/admin');
        }
    }



    public function settings(Request $request) {
        if (auth('admin')->check()) {
            $admin = session('admin');

            $adminData = DB::table('admin')
            ->select(
                'admin_id',
                'admin_codename',
                'role',
                'admin_username',
            )
            ->where('admin_id', $admin->admin_id)
            ->first();

            $data = [
                'title' => 'View Profile',
                // 'scripts' => ['AD_add_new_admin.js'],
                'admin' => $admin,
                'adminData' => $adminData,
            ];

            return view('admin.adminSettings')
            ->with($data);
    
        }  else {
            return redirect('/admin');
        }
    }


    public function update_Settings(Request $request) {
        if (auth('admin')->check()) {
            $admin = session('admin');
            try {
                $withPass = $request->input('withPass');



                if($withPass == 1) {
                    $adminData = [
                        'admin_codename' => $request->input('admin_codename'),
                        'admin_username' => $request->input('admin_username'),
                        'password' => bcrypt($request->input('password')),
                    ];
                } else {
                    $adminData = [
                        'admin_codename' => $request->input('admin_codename'),
                        'admin_username' => $request->input('admin_username'),
                    ];
                }

                DB::table('admin')
                ->where('admin_id', $admin->admin_id)
                ->update($adminData);


            session()->flash('message', 'You have successfully updated your account');
            $data = [
                'message' => 'You have successfully updated your account',
                'redirect_url' => '/admin/profile',
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
}
