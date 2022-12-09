<?php

namespace App\Http\Controllers;

use App\Models\Configs;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * current route root extractor
     *
     * @var string $current_route
     */
    public ?string $current_route = null;

    public function __construct() {
        $this->current_route = explode('.', Route::currentRouteName())[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function profile(): View
    {
        if($this->current_route == 'admin'){
            $is_node_on = Configs::where('name', 'is_node_on')->first();
            return view('pages.'.$this->current_route.'.profile.base')->with(['detail_page' => true, 'is_node_on' => $is_node_on]);
        }
        return view('pages.'.$this->current_route.'.profile.base')->with(['detail_page' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validating request
        $request->validate([
            'email' => 'email|required',
            'password' => 'string|required',
            'type' => 'integer'
        ]);

        // creating new instance
        $data = new User([
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'type' => $request->get('type') ? $request->get('type') : '0'
        ]);

        // saving new instance in db
        $data->save();

        // returning new instance
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Models\User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordChange(Request $request, User $user): RedirectResponse
    {
        // check for authorization
        if($user->id !== auth()->user()->id) return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        // validating request
        $validator = Validator::make($request->all(),[
            'old_password' => 'string|required',
            'password' => 'string|required|confirmed|min:8',
        ]);

        // check if the validator failed
        if($validator->fails()){
            return redirect()->route($this->current_route.'.profile')->withErrors($validator)->withInput()->with(['password_page' => true]);
        }

        if(Hash::check($request->get('old_password'), $user->password)){
            $user->update([
                'password' => Hash::make($request->get('password'))
            ]);
            return redirect()->route($this->current_route.'.profile')->with(['success'=> 'Password changed successfully.', 'password_page' => true]);
        }else{
            return redirect()->route($this->current_route.'.profile')->with(['error' => 'Old password is incorrect.', 'password_page' => true]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_STAFF, auth()->user()->type, $user, self::ACTION_DELETE)){
            // check and deleting if there is user information
            if($user->information){
                $user->information->delete();
            }

            // delete the instance and return message
            if($user->delete()){
                return redirect()->route($this->current_route.'.staff.list')->with('success', "User has been deleted successfully!");
            }else{
                return redirect()->route($this->current_route.'.staff.list')->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function staffCreate(): View
    {
        return view('pages.admin.staff.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function staffStore(Request $request): RedirectResponse
    {
        // validating request
        $request->validate([
            'email' => 'email|required|unique:\App\Models\User,email',
            'password' => 'string|required|confirmed|min:8',
            'first_name' => 'string|required',
            'middle_name' => 'string|required',
            'last_name' => 'string|nullable'
        ]);


        /**
         * creating user login account
         *
         * @var \Illuminate\Models\User
         */
        $user_login = new User([
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'email_verified_at' => \Carbon\Carbon::now()->timezone('Africa/Addis_Ababa')->format('Y-m-d H:i:s'),
            'type' => '0',
            'is_staff' => '1'
        ]);

        // saving the instance
        if($user_login->save()){
            /**
             * creating user information
             *
             * @var \Illuminate\Models\userInformation
             */
            $user_info = new UserInformation([
                'user_id' => $user_login->id,
                'first_name' => $request->get('first_name'),
                'middle_name' => $request->get('middle_name'),
                'last_name' => $request->get('last_name') ? $request->get('last_name') : ''
            ]);

            // saving the instance
            if($user_info->save()){
                return redirect()->route('admin.staff.add')->with('success', 'Staff has been stored successfully!');
            }else{
                return redirect()->route('admin.staff.add')->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route('admin.staff.add')->with('error', 'Something went wrong, please try again!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function staffIndex(): View
    {
        /**
         * all available staffs
         *
         * @var \App\Models\User $staffs
         */
        $staffs =  User::where('is_staff', 1)->where('type', '<>', '1')->get();

        return view('pages.admin.staff.list', ['staffs'=> $staffs]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function staffShow(User $user): View|RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_STAFF, auth()->user()->type, $user, self::ACTION_VIEW)){
            return view('pages.admin.staff.view', ['staff'=> $user]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View||\Illuminate\Http\RedirectResponse
     */
    public function staffEdit(User $user): View|RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_STAFF, auth()->user()->type, $user, self::ACTION_EDIT)){
            return view('pages.admin.staff.edit', ['staff'=>$user]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function staffUpdate(Request $request, User $user): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_STAFF, auth()->user()->type, $user, self::ACTION_UPDATE)){
            /**
             * check updating flag
             *
             * @var bool $flag
             */
            $flag = false;
            // check if the data is updated
            if($request->get('email')){
                if($request->get('email') != $user->email) $flag = true;
            }
            if($request->get('first_name')){
                if($request->get('first_name') != $user->information->first_name) $flag = true;
            }
            if($request->get('middle_name')){
                if($request->get('middle_name') != $user->information->middle_name) $flag = true;
            }
            if($request->get('last_name')){
                if($request->get('last_name') != $user->information->last_name) $flag = true;
            }

            // check and return
            if(!$flag){
                return redirect()->route('admin.staff.edit', $user->id)->with('success', 'Nothing to update!');
            }

            // validating request
            $request->validate([
                'email' => ['email','nullable', Rule::unique('users', 'email')->ignore($user->id),],
                'first_name' => 'string|nullable',
                'middle_name' => 'string|nullable',
                'last_name' => 'string|nullable'
            ]);
            // update the account instance
            if($user->update([
                'email' => ($request->get('email')) ? $request->get('email') : $user->email,
            ])){
                // update the information instance
                if($user->information->update([
                    'first_name' => ($request->get('first_name')) ? $request->get('first_name') : $user->information->first_name,
                    'middle_name' => ($request->get('middle_name')) ? $request->get('middle_name') : $user->information->middle_name,
                    'last_name' => ($request->get('last_name')) ? $request->get('last_name') : $user->information->last_name
                ])){
                    return redirect()->route('admin.staff.edit', $user->id)->with('success', 'Staff has been updated successfully!');
                }else{
                    return redirect()->route('admin.staff.edit', $user->id)->with('error', 'Something went wrong, please try again!');
                }
            }else{
                return redirect()->route('admin.staff.edit', $user->id)->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }
}
