<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class DepartmentController extends Controller
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
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        /**
         * all available departments
         *
         * @var \App\Models\Department $departments
         */
        $departments =  Department::all();

        return view('pages.admin.department.list', ['departments'=> $departments]);
    }

    /**
     * Display a listing of the resource which belongs to one School
     *
     * @return \Illuminate\View\View
     */
    public function schoolIndex(): View
    {
        /**
         * all available departments
         *
         * @var \App\Models\Department $departments
         */
        $departments =  Department::where('school_id',Auth::user()->school->id)->get();

        return view('pages.school.department.list', ['departments'=> $departments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        /**
         * available staffs which can be department head
         *
         * @var \App\Models\User department_head_list
         */
        $department_head_list = User::where('is_staff', '1')->where('type', '0')->get();

        /**
         * available schools
         *
         * @var \App\Models\School $school_list
         */
        $school_list = School::all();

        return view('pages.'.$this->current_route.'.department.add', ['department_head_list'=>$department_head_list, 'school_list'=>$school_list]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // validating request
        $request->validate([
            'school_id' => 'exists:\App\Models\School,id|integer',
            'head_id' => 'nullable|exists:\App\Models\User,id|integer',
            'name' => 'string|required',
            'description' => 'nullable|string'
        ]);

        /**
         * create new instance of Department
         *
         * @var \App\Models\Department $data
         */
        $data = new Department($request->all());

        // saving new instance in db and returning message
        if($data->save()){
            // updating head user
            if($request->get('head_id')){
                User::find($request->get('head_id'))->update(['type' => '3']);
            }
            return redirect()->route($this->current_route.'.department.add')->with('success', 'Department has been stored successfully!');
        }else{
            return redirect()->route($this->current_route.'.department.add')->with('error', 'Something went wrong, please try again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department $department
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Department $department): View|RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_DEPARTMENT, auth()->user()->type, $department, self::ACTION_VIEW)){
            return view('pages.'.$this->current_route.'.department.view', ['department'=> $department]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Department $department): View|RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_DEPARTMENT, auth()->user()->type, $department, self::ACTION_EDIT)){
            /**
             * available staffs which can be department head
             *
             * @var \App\Models\User department_head_list
             */
            $department_head_list = User::where('is_staff', '1')->where('type', '0')->get();

            /**
             * available schools
             *
             * @var \App\Models\School $school_list
             */
            $school_list = School::all();

            return view('pages.'.$this->current_route.'.department.edit', ['department_head_list'=>$department_head_list, 'department'=>$department, 'school_list'=>$school_list]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Department $department): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_DEPARTMENT, auth()->user()->type, $department, self::ACTION_UPDATE)){
            /**
             * get all request parameters
             *
             * @var array $checker
            */
            $checker = $request->all();
            // delete _token param
            unset($checker['_token']);
            // check send data and stored data are the same
            if(array_intersect_assoc($department->attributesToArray(),$request->all()) == $checker){
                return redirect()->route($this->current_route.'.department.edit', $department->id)->with('success', 'Nothing to update!');
            }
            // validating request
            $request->validate([
                'school_id' => 'exists:\App\Models\School,id|integer|nullable',
                'head_id' => 'exists:\App\Models\User,id|integer|nullable',
                'name' => 'string|nullable',
                'description' => 'string|nullable'
            ]);
            // updating head user
            if($request->get('head_id')){
                User::find($request->get('head_id'))->update(['type' => '3']);
            }else{
                if($department->head) $department->head->update([ 'type' => '0']);
            }
            // update the instance and return message
            if($department->update($request->all())){
                return redirect()->route($this->current_route.'.department.edit', $department->id)->with('success', 'Department has been updated successfully!');
            }else{
                return redirect()->route($this->current_route.'.department.edit', $department->id)->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Department $department): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_DEPARTMENT, auth()->user()->type, $department, self::ACTION_DELETE)){

            // delete the instance and return message
            if($department->delete()){
                return redirect()->route($this->current_route.'.department.list')->with('success', "Department has been deleted successfully!");
            }else{
                return redirect()->route($this->current_route.'.department.list')->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }
}
