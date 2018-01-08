<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use File;
use Flash;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Sentinel;
use Validator;
use View;
use Str;
use Carbon\Carbon;

/**
 * Class UserController.
 *

 */
class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        $users = User::orderBy('created_at', 'DESC');

        $orderby = '';
        if ($request->orderby) {
            $orderby = $request->orderby;
            if ($orderby) {
                foreach ($orderby as $ob):
                    $o = explode('~', $ob);
                    $users = User::orderBy($o[0], $o[1]);
                endforeach;
            }
        }
        $name = '';
        if ($request->name) {
            $name = $request->name;
            if ($name) {
                $users = $users->where(function ($query) use($name) {
                    $query->orWhere('first_name', 'like', '%' . $name . '%');
                    $query->orWhere('last_name', 'like', '%' . $name . '%');
                    $query->orWhere('username', 'like', '%' . $name . '%');
                });
            }
        }
        $email = '';
        if ($request->email) {
            $email = $request->email;
            if ($email)
                $users = $users->where('email', 'like', '%' . $email . '%');
        }
        $phone = '';
        if ($request->phone) {
            $phone = $request->phone;
            if ($phone) {
                $users = $users->where('mobile', 'like', '%' . $email . '%');
            }
        }
        $from = '';
        if ($request->from) {
            $from = $request->from;
            if ($from)
                $users = $users->whereDate('created_at', '>=', $from);
        }

        $to = '';
        if ($request->to) {
            $to = $request->to;
            if ($to)
                $users = $users->whereDate('created_at', '<=', $to);
        }
        $users = $users->paginate(10);
        if ($name) {
            $users = $users->appends(['name' => $name]);
        }
        if ($email) {
            $users = $users->appends(['email' => $email]);
        }
        if ($phone) {
            $users = $users->appends(['phone' => $phone]);
        }
        if ($users) {
            //Get User Roles
            foreach ($users as &$user) {
                $userRoles = $user->getRoles()->lists('name', 'id')->toArray();
                if (count($userRoles) > 0) {
                    $user['roles'] = implode(', ', $userRoles);
                    $user->roles = implode(',<br/>', $userRoles);
                }
            }
        }

        return view('backend.user.index', compact('users', 'user_type', 'from', 'to', 'name', 'phone', 'email'))->with('active', 'user');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $roles = Role::lists('name', 'id');
        $user = null;
        return view('backend.user.create', compact('roles', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        try {
            $rules = array(
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:4',
                'confirm-password' => 'required|same:password',
            );
            
            $input = Input::all();
            $validation = Validator::make($input, $rules);

            if ($validation->fails()) {
                return Redirect::action('Admin\UserController@create')->withErrors($validation)->withInput();
            }
            $user = Sentinel::registerAndActivate($input);
            $user->parent_first_name = $input['parent_first_name'];
            $user->parent_middle_name = $input['parent_middle_name'];
            $user->parent_last_name = $input['parent_last_name'];
            $user->first_name = $input['first_name'];
            $user->middle_name = $input['middle_name'];
            $user->last_name = $input['last_name'];
            $user->mobile = $input['mobile'];
            $user->landline = $input['landline'];
            $user->is_active = 1;
            $user->verify = 'COMPLETED';
            $user->save();
            
            $role = (isset($input['isAdmin']) && $input['isAdmin'] == 1) ? 'superadmin' : 'student';
            $role = Sentinel::findRoleByName($role);
            $role->users()->attach($user);
            
            Flash::message('User was successfully created');

            return Redirect::route('admin.user.index');
        } catch (ValidationException $e) {
            return Redirect::route('admin.user.edit')->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id) {
        $user = Sentinel::findUserById($id);
        $userRoles = $user->getRoles()->lists('name', 'id')->toArray();
        if (count($userRoles) > 0) {
            $user['roles'] = implode(', ', $userRoles);
        }

        return view('backend.users.show', compact('user'))->with('active', 'user');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id) {
        $user = User::find($id);
        return view('backend.user.edit', compact('user'))->with('active', 'user');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id) {
        try {
            $rules = array(
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'min:4',
                'confirm-password' => 'same:password',
            );
            $input = Input::all();
            if (!$input['password'])
                unset($input['password']);
            
            $validation = Validator::make($input, $rules);

            if ($validation->fails()) {
                return Redirect::to(getLang() . '/admin/user/' . $id . '/edit#panel_edit_account')->withErrors($validation)->withInput();
            }
            if (isset($input['password'])){
                $input['password'] = bcrypt($input['password']);
            }
            $user = User::find($id);
            $input['isAdmin'] = ($request->get('isAdmin') ? 1 : 0);
            $input['status'] = ($request->get('status') ? 1 : 0);
            $user->update($input);
            $oldRoles = $user->getRoles()->lists('name', 'id')->toArray();
            foreach ($oldRoles as $id => $role) {
                $roleModel = Sentinel::findRoleByName($role);
                $roleModel->users()->detach($user);
            }
            
            $role = ($input['isAdmin'] == 1) ? 'superadmin' : 'student';
            $role = Sentinel::findRoleByName($role);
            $role->users()->attach($user);
            Flash::message('User was successfully updated');
            return Redirect::route('admin.user.index');
        } catch (ValidationException $e) {
            return Redirect::route('admin.user.edit')->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id) {
        if($id == 1){
            Flash::message('This is superadmin. You can\'t delete this user');
        }else{
            $user = Sentinel::findById($id);
            $user->delete();
            Flash::message('User was successfully deleted');
        }
        return Redirect::route('admin.user.index');
    }

    public function confirmDestroy($id) {
        $user = Sentinel::findById($id);
        return view('backend.user.confirm-destroy', compact('user'))->with('active', 'user');
    }

}
