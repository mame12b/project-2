<?php

namespace App\Http\Controllers;

use App\Models\UserApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class InternController extends Controller
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
         * all available interns
         *
         * @var \App\Models\UserApplication $interns
         */
        $interns =  UserApplication::where('status', '1')->get();

        return view('pages.admin.intern.list', ['interns'=> $interns]);
    }

    /**
     * Display a listing of the resource for school.
     *
     * @return \Illuminate\View\View
     */
    public function schoolIndex(): View
    {
        /**
         * all available interns
         *
         * @var \App\Models\UserApplication $interns
         */
        $interns =  UserApplication::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->whereIn('department_id', function($query2){
                $query2->select('id')
                ->from('departments')
                ->where('school_id', auth()->user()->school->id);
            });
        })->where('status', '1')->get();

        return view('pages.school.intern.list', ['interns'=> $interns]);
    }

    /**
     * Display a listing of the resource for department.
     *
     * @return \Illuminate\View\View
     */
    public function departmentIndex(): View
    {
        /**
         * all available interns
         *
         * @var \App\Models\UserApplication $interns
         */
        $interns =  UserApplication::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->where('department_id', auth()->user()->department->id);
        })->where('status', '1')->get();

        return view('pages.department.intern.list', ['interns'=> $interns]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $school
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(int $intern): View|RedirectResponse
    {
        /**
         * getting user application from db
         *
         * @var \App\Models\UserApplication $user_application
         */
        $user_application = UserApplication::find($intern);
        // check authorization
        if($this->checkAuthorizations(self::MODEL_INTERNS, auth()->user()->type, $user_application, self::ACTION_VIEW)){
            if($user_application->status == '1'){
                return view('pages.'.$this->current_route.'.intern.view', ['user_application' => $user_application, 'internship' => $user_application->internship, 'user' => $user_application->user, 'prerequisite_responses' => $user_application->prerequisiteResponses]);
            }else{
                return redirect()->route($this->current_route.'.home')->with('error', 'Requested resource not found!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $intern
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $intern): RedirectResponse
    {
        /**
         * getting user application from db
         *
         * @var \App\Models\UserApplication $user_application
         */
        $user_application = UserApplication::find($intern);
        // check authorization
        if($this->checkAuthorizations(self::MODEL_INTERNS, auth()->user()->type, $user_application, self::ACTION_DELETE)){
            // delete the instance and return message
            if($user_application->delete()){
                return redirect()->route($this->current_route.'.intern.list')->with('success', "Intern has been deleted successfully!");
            }else{
                return redirect()->route($this->current_route.'.intern.list')->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }
}
