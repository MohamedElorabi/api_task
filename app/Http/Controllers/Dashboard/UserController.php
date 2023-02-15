<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.users.index', compact('users'));
    }


    public function create()
    {
        return view('dashboard.users.create');
    }


    public function store(UserRequest $request)
    {

        $data = $request->all();
        $user = User::create($data);
        return redirect(route('admin.users.index'))->with('تم إنشاء بيانات المستخدم بنجاح');

    }


    public function edit($id)
    {
        $user = User::find($id);
        return view('dashboard.users.edit', compact('user'));
    }



    public function update(UserRequest $request , $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return redirect(route('admin.users.index'))->with('success' , 'تم التحديث بنجاح');

    }



    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect(route('admin.users.index'))->with('success' , 'تم الحذف بنجاح');

    }
}
