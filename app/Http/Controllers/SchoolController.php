<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class SchoolController extends Controller
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
         * all available school
         *
         * @var \App\Models\School $schools
         */
        $schools =  School::all();

        return view('pages.admin.school.list', ['schools'=> $schools]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        /**
         * available staffs which can be school head
         *
         * @var \App\Models\User $school_head_list
         */
        $school_head_list = User::where('is_staff', '1')->where('type', '0')->get();

        return view('pages.admin.school.add', ['school_head_list'=>$school_head_list]);
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
            'head_id' => 'nullable|exists:\App\Models\User,id|integer',
            'name' => 'string|required',
            'description' => 'nullable|string'
        ]);

        /**
         * create new instance of School
         *
         * @var \App\Models\School $data
         */
        $data = new School($request->all());

        // saving new instance in db and returning message
        if($data->save()){
            // updating head user
            if($request->get('head_id')){
                User::find($request->get('head_id'))->update(['type' => '2']);
            }
            return redirect()->route('admin.school.add')->with('success', 'School has been stored successfully!');
        }else{
            return redirect()->route('admin.school.add')->with('error', 'Something went wrong, please try again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\School $school
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(School $school): View|RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_SCHOOL, auth()->user()->type, $school, self::ACTION_VIEW)){
            return view('pages.admin.school.view', ['school'=> $school]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\School  $school
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(School $school): View|RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_SCHOOL, auth()->user()->type, $school, self::ACTION_EDIT)){
            /**
             * available staffs which can be school head
             *
             * @var \App\Models\User $school_head_list
             */
            $school_head_list = User::where('is_staff', '1')->where('type', '0')->get();

            return view('pages.admin.school.edit', ['school_head_list'=>$school_head_list,'school'=>$school]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, School $school): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_SCHOOL, auth()->user()->type, $school, self::ACTION_UPDATE)){
            /**
             * get all request parameters
             *
             * @var array $checker
            */
            $checker = $request->all();
            // delete _token param
            unset($checker['_token']);
            // check send data and stored data are the same
            if(array_intersect_assoc($school->attributesToArray(),$request->all()) == $checker){
                return redirect()->route('admin.school.edit', $school->id)->with('success', 'Nothing to update!');
            }
            // validating request
            $request->validate([
                'head_id' => 'exists:\App\Models\User,id|integer|nullable',
                'name' => 'string|nullable',
                'description' => 'string|nullable'
            ]);
            // updating head user
            if($request->get('head_id')){
                User::find($request->get('head_id'))->update(['type' => '2']);
            }else{
                if($school->head) $school->head->update([ 'type' => '0']);
            }
            // update the instance and return message
            if($school->update($request->all())){
                return redirect()->route('admin.school.edit', $school->id)->with('success', 'School has been updated successfully!');
            }else{
                return redirect()->route('admin.school.edit', $school->id)->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\School $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(School $school): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_SCHOOL, auth()->user()->type, $school, self::ACTION_DELETE)){

            // delete the instance and return message
            if($school->delete()){
                return redirect()->route('admin.school.list')->with('success', "School has been deleted successfully!");
            }else{
                return redirect()->route('admin.school.list')->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }
}
