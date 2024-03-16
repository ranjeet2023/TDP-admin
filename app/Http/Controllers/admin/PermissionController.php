<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use DB;

class PermissionController extends Controller
{
    public function Permission()
    {
        return view('admin.permission.permission');
    }

    public function SetPermission(Request $request)
    {
        $title = $request->title;
        $url = $request->url;

        Permission::insert([
            'title' => $title,
            'url' => $url,
        ]);
        return redirect('permission')->with('success','Permission Set');
    }
}
