<?php

namespace App\Http\Controllers\User;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StaffStoreRequest;
use App\Http\Requests\User\StaffUpdateRequest;
use App\Staff;
use App\Storage\StaffStorage;
use App\User;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::withTrashed()
            ->with(["user","category"])
            ->where('category_id','!=',1)
            ->orderBy('last_name',"asc")
            ->get();

        return view('user.index',compact('staffs'));
    }

    public function create()
    {
        $categories = Category::where("id", "!=", 1)->get();
        return view('user.create',compact('categories'));
    }

    public function store(StaffStoreRequest $request,StaffStorage $storage)
    {
        $storage->add($request->all());

        session()->flash('success', "Un nouveau Utilisateur a bien été ajouté");

        return redirect()->route('staff.index');

    }

    public function edit(Staff $staff)
    {
        return view('user.edit',compact('staff'));
    }

    public function update(StaffUpdateRequest $request, Staff $staff,StaffStorage $storage)
    {
        $storage->update($staff,$request->all(["last_name", "first_name", "mobile", "cin"]));
        session()->flash('success',  $staff->full_name . ' a bien été modifié');
        return redirect()->route('staff.index');
    }

    public function destroy(Staff $staff,StaffStorage $storage)
    {
        $storage->sub($staff);
        session()->flash('success', $staff->full_name . " a bien été supprimé");
        return redirect()->route('staff.index');
    }

    public function restore(int $id)
    {
        $staff = Staff::withTrashed()->find($id);
        if($staff){
            $staff->restore();
            if($user = User::withTrashed()->where('staff_id',$id)->first()){
                $user->restore();
            }
            session()->flash('success', $staff->full_name . " a bien été activé");
            return redirect()->route('staff.index');
        }
        return abort(404);
    }
}
