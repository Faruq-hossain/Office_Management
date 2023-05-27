<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Actions\Role\CreateRole;
use App\Actions\Role\UpdateRole;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\RoleFormRequest;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:role list'])->only(['index']);
        $this->middleware(['permission:create role'])->only(['create']);
        $this->middleware(['permission:edit role'])->only(['edit']);
        $this->middleware(['permission:delete role'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    // send permission index page with this code
    {
        $roles = Role::with('permissions')->latest()->get();
        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        // $permission_groups = User::getPermissionGroup();

        // permission variable take compact kore front end e set kore dibo
        return view('role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  data validate korte hobe
    // unique korar jonno database ki name e data ace seta dekhle hobe tai

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        //validation korar por akhane create korar code
        //array return korbe.

        // $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        // ata korar karon holo there is no permission woth id 4 guard sanctum
        //karon akha role jokon create korteci tokon guard name web e pass hobe..

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        // role permission korar jonno code
        //permission syne korar jonno sync method use
        $role->syncPermissions($request->permissions);

        session()->flash('success', 'Role Created!');
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions = Permission::all();
        $role = Role::with('permissions')->find($id);
        $data = $role->permissions()->pluck('id')->toArray();
// atar maddhome data frontend e pathate pari edit blade.php te
        return view('role.edit', compact(['permissions', 'role', 'data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        // abort_if(!userCan('role.update'), 403);
        $request->validate([
            'name' => "required|unique:roles,name, $role->id"
        ]);

        // array pathabo and array key dibo konta update korte cai

        $role->update([ 'name' => $request->name]);
        $role->syncPermissions($request->permissions);

        session()->flash('success', 'Role has been updated successfully!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        // abort_if(!userCan('role.delete'), 403);

        $role->delete();
        session()->flash('success', 'Role has been deleted successfully deleted!');
        return back();
    }
}
