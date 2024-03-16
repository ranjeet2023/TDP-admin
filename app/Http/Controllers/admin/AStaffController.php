<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\UserHasPermission;

class AStaffController extends Controller
{
    public function AddStaff()
    {
        return  view('admin.staff.add-staff');
    }

    public function PostAddStaff(Request $request)
    {
        $request->validate([
            'firstname' => 'required|min:3|max:32',
            'lastname' => 'required|min:3|max:32',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
            'mobile' => 'required|max:13',
            'email' => "required|email|unique:users,email",
            'account_type' => 'required',
            'skype' => 'required',
            'marketingmail' => 'required'
        ]);

        $staff = array(
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'email' => $request->email,
            'user_type' => $request->account_type,
            'email_verify_code' => Str::random(32),
            'email_verified_at' => date_create(),
            'is_active' => 1,
            'role_id' => 3,
            'created_at' => date_create(),
            'companyname' => $request->email,
        );

        $id = User::insertGetId($staff);
        $staff_detail = array(
            'id' => $id,
            'account_type' => $request->account_type,
            'skypeid' => $request->skype,
            'marketing_mail' => $request->marketingmail,
            'is_delete' => 0
        );
        Admin::insert($staff_detail);

        return redirect('add-staff')->with('update', 'Account Add Successful');
    }

    public function manageStaff()
    {
        $data['emp_count'] = Admin::where('is_delete', 0)->count();
        $data['staff'] = User::whereIn('user_type', array(1,4,5,6))
        ->with('admins')->whereHas('admins',function($query){ $query->where('is_delete',0); })->get();

        return view('admin.staff.staffmanagement')->with($data);
    }

    public function staffEdit(Request $request)
    {
        $id = $request->id;
        $data['staff'] = Admin::with('users')->whereHas('users',function($query)use($id){ $query->where('users.id', $id); })->first();
        $data['managers'] = Admin::with('users')->get();

        return view('admin.staff.edit-staff')->with($data);
    }

    public function deleteStaff(Request $request)
    {
        $id = $request->id;
        Admin::where('id', $id)->update(['is_delete' => 1,]);
        User::where('id', $id)->update(['is_delete' => 1,]);

        return redirect('manage-staff')->with('update', 'Account Delete Successful');
    }

    public function PostStaffEdit(Request $request)
    {
        $request->validate([
            'firstname' => 'string|min:3|max:32',
            'lastname' => 'string|min:2|max:32',
            'mobile' => 'max:14',
            'email' => "required|email",
            'skype' => 'max:32',
            'marketingmail' => 'string',
        ]);

        if($request->staff_type == 1){
            $request->manager = 0;
        }
        $id = $request->id;
        $data['staff'] = Admin::join('users', 'users.id', '=', 'admins.id')->where('users.id', $id)
            ->update([
                'marketing_mail' => $request->marketingmail,
                'manager' => $request->manager,
                'skypeid' => $request->skype,
                'account_type' => $request->account_type,
                'user_type' => $request->account_type,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'mobile' => $request->mobile,
            ]);
        return redirect('manage-staff')->with('update', 'Update Successful !');
    }

    public function ChangeStaffPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'password_confirmation' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $newpassword = $request->password;
        $confirmpassword = $request->password_confirmation;

        $id = $request->user_id;

        $password = Admin::with('users')->whereHas('users',function($query)use($id){ $query->where('id', $id); })->first();
        $staffpassword = $password->users->password;

        Admin::join('users', 'users.id', '=', 'admins.id')->where('users.id', $id)
            ->update(['password' => Hash::make($newpassword)]);
        return redirect('manage-staff')->with('update', 'Password Update Successfully !');
    }

    public function StaffPermission(Request $request)
    {
        $data['user_id'] = $request->id;
        $data['user_name'] = User::select('firstname','lastname')->where('id',$request->id)->first();
        $data['permission'] = Permission::get();
        $data['user_has_permission'] = UserHasPermission::where('user_id', $data['user_id'])->get()->toArray();
        return view('admin.staff.permission-staff')->with($data);
    }

    public function AssignPermission(Request $request)
    {
        $permissions = $request->permission;
        foreach ($permissions as $key=>$permission) {
            $full = 0; $menu    = 0; $edit = 0; $delete = 0;
            if(!empty($permission['full']) && $permission['full'] == 1)
            {
                $full = 1;
            }

            if(!empty($permission['menu']) && $permission['menu'] == 1)
            {
                $menu = 1;
            }

            if(!empty($permission['edit']) && $permission['edit'] == 1)
            {
                $edit = 1;
            }

            if(!empty($permission['delete']) && $permission['delete'] == 1)
            {
                $delete = 1;
            }

            $insert=UserHasPermission::updateOrInsert(
                [
                    'permission_id' => $key,
                    'user_id' => $request->user_id
                ],
                [
                    'user_id' => $request->user_id,
                    'full' => $full,
                    'menu' => $menu,
                    'edit' => $edit,
                    'delete' => $delete
                ]
            );
        }
        return redirect()->back()->with('success','Permission Set');
    }

}
