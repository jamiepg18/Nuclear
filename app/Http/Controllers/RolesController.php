<?php

namespace Reactor\Http\Controllers;

use Illuminate\Http\Request;
use Reactor\ACL\Role;
use Reactor\Http\Controllers\Traits\ModifiesPermissions;
use Reactor\User;

class RolesController extends ReactorController
{

    use ModifiesPermissions;

    /**
     * Self model path required for ModifiesPermissions
     *
     * @var string
     */
    protected $modelPath = Role::class;
    protected $routeViewPrefix = 'roles';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::sortable()->paginate();

        return view('roles.index')
            ->with(compact('roles'));
    }

    /**
     * Display results of searching the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $roles = Role::search($request->input('q'))->get();

        return view('roles.search')
            ->with(compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('ACCESS_ROLES_CREATE');

        $form = $this->form('Roles\CreateEditForm', [
            'method' => 'POST',
            'url'    => route('reactor.roles.store')
        ]);

        return view('roles.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('ACCESS_ROLES_CREATE');

        $this->validateForm('Roles\CreateEditForm', $request);

        $role = Role::create($request->all());

        flash()->success(trans('users.created_role'));

        return redirect()->route('reactor.roles.edit', $role->getKey());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('ACCESS_ROLES_EDIT');

        $role = Role::findOrFail($id);

        $form = $this->form('Roles\CreateEditForm', [
            'method' => 'PUT',
            'url'    => route('reactor.roles.update', $id),
            'model' => $role
        ]);

        return view('roles.edit', compact('form', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('ACCESS_ROLES_EDIT');

        $role = Role::findOrFail($id);

        $this->validateForm('Roles\CreateEditForm', $request, [
            'name' => ['required', 'max:255',
                'unique:roles,name,' . $role->getKey(),
                'regex:/^([A-Z]+)$/']
        ]);

        $role->update($request->all());

        flash()->success(trans('users.edited_role'));

        return redirect()->route('reactor.roles.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('ACCESS_ROLES_DELETE');

        $role = Role::findOrFail($id);

        $role->delete();

        flash()->success(trans('users.deleted_role'));

        return redirect()->route('reactor.roles.index');
    }

    /**
     * List the specified resource users.
     *
     * @param int $id
     * @return Response
     */
    public function users($id)
    {
        $role = Role::with('users')->findOrFail($id);

        $form = $this->getAddUserForm($id, $role);

        return view('roles.users')
            ->with(compact('role', 'form'));
    }

    /**
     * Creates a form for adding permissions
     *
     * @param int $id
     * @param Role $role
     * @return \Kris\LaravelFormBuilder\Form
     */
    protected function getAddUserForm($id, Role $role)
    {
        $form = $this->form('Users\AddUserForm', [
            'method' => 'PUT',
            'url'    => route('reactor.roles.user.add', $id)
        ]);

        $choices = User::all()
            ->diff($role->users)
            ->lists('first_name', 'id')
            ->toArray();

        $form->modify('user', 'select', [
            'choices' => $choices
        ]);

        return $form;
    }

    /**
     * Add an user to the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function addUser(Request $request, $id)
    {
        $this->validateForm('Users\AddUserForm', $request);

        $role = Role::findOrFail($id);

        $role->associateUser($request->input('user'));

        chronicle()->record($role, 'associated_user_to_role');
        flash()->success(trans('users.added_user'));

        return redirect()->back();
    }

    /**
     * Remove an user from the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function removeUser(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->dissociateUser($request->input('user'));

        chronicle()->record($role, 'dissociated_user_from_role');
        flash()->success(trans('users.unlinked_user'));

        return redirect()->route('reactor.roles.users', $id);
    }

}
