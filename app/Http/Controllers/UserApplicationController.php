<?php

namespace App\Http\Controllers;

use App\Models\UserApplication;
use App\Models\Internship;
use App\Models\User;
use App\Models\UserPrerequisiteResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class UserApplicationController extends Controller
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
        * all available applications
        *
        * @var \App\Models\UserApplication $applications
        */
       $applications =  UserApplication::all();

        return view('pages.admin.application.list', ['applications'=>$applications]);
    }

    /**
     * Display a listing of the resource by school.
     *
     * @return \Illuminate\View\View
     */
    public function schoolIndex(): View
    {
       /**
        * all available applications in the school
        *
        * @var \App\Models\UserApplication $applications
        */
        $applications =  UserApplication::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->whereIn('department_id', function($query2){
                $query2->select('id')
                ->from('departments')
                ->where('school_id', auth()->user()->school->id);
            });
        })->get();

        return view('pages.school.application.list', ['applications'=>$applications]);
    }

    /**
     * Display a listing of the resource by department.
     *
     * @return \Illuminate\View\View
     */
    public function departmentIndex(): View
    {
       /**
        * all available applications in the department
        *
        * @var \App\Models\UserApplication $applications
        */
        $applications =  UserApplication::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->where('department_id', auth()->user()->department->id);
        })->get();

        return view('pages.department.application.list', ['applications'=>$applications]);
    }

    /**
     * Display a listing of the resource by department and alow functionalities to filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function filter(Request $request): View
    {
        /**
         * all available internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships =  Internship::where('department_id',auth()->user()->department->id)->get();

       /**
        * all available applications in the department
        *
        * @var \App\Models\UserApplication $applications
        */
        $applications =  UserApplication::whereIn('internship_id', function($query) {
            $query->select('id')
            ->from('internships')
            ->where('department_id', auth()->user()->department->id);
        });

        /**
         * filters array
         *
         * @var array $filters
         */
        $filters = ($request->all())?$request->all():[];

        $request->validate([
            'status' => 'nullable|in:0,1,2',
            'cgpa_action' => 'nullable|in:>=,<=,=',
            'cgpa_value' => 'nullable|numeric|max:4.0|min:0.0',
            'internship' => 'nullable|exists:\App\Models\Internship,id',
            'date' => 'nullable|in:desc,asc',
            'cgpa_list' => 'nullable|in:desc,asc'
        ]);

        // add filter for status
        if($request->status != null){
            $applications = $applications->where('status', $request->status);
        }

        // add filter for internship
        if($request->internship != null){
            $applications = $applications->where('internship_id', $request->internship);
        }

        // add filter for custom cgpa
        if($request->cgpa_action != null && $request->cgpa_value != null){
            if($request->cgpa_list == null){
                $applications = $applications->select('user_applications.*')->join('user_information', function($join) use($request){
                    $join->on('user_applications.user_id', '=', 'user_information.user_id')
                         ->where('cgpa', $request->cgpa_action, $request->cgpa_value);
                });
            }else{
                $applications = $applications->select('user_applications.*')->join('user_information', function($join) use($request){
                    $join->on('user_applications.user_id', '=', 'user_information.user_id')
                         ->where('cgpa', $request->cgpa_action, $request->cgpa_value);
                })->orderBy('cgpa', $request->cgpa_list );
            }
        }

        // add filter for cgpa order
        if($request->cgpa_list != null &&  $request->cgpa_value == null){
            $applications = $applications->select('user_applications.*')->join('user_information', function($join) use($request){
                $join->on('user_applications.user_id', '=', 'user_information.user_id');
            })->orderBy('cgpa', $request->cgpa_list );
        }

        // add filter for date order
        if($request->date != null){
            $applications = $applications->orderBy('created_at', $request->date );
        }

        // get filtered or unfiltered object
        $applications = $applications->get();

        /**
         * check if filter is applied
         *
         * @var bool $isFilterActivated
         */
        $isFilterActivated = false;

        if(count($filters) > 0){
            foreach($filters as $key => $filter){
                if($filter != null && $key != 'cgpa_action') $isFilterActivated = true;
            }
        }

        return view('pages.department.application.filter', ['isFilterActivated'=>$isFilterActivated,'filters'=>$filters, 'internships' => $internships,'applications'=>$applications]);
    }

    /**
     * Display a listing of the resource by user id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userIndex(Request $request)
    {
        /**
        * all available applications of the user
        *
        * @var \App\Models\UserApplication $applications
        */
        $applications =  UserApplication::where('user_id', auth()->user()->id);

        /**
         * filters array
         *
         * @var array $filters
         */
        $filters = ($request->all())?$request->all():[];

        $request->validate([
            'status' => 'nullable|in:0,1,2',
            'date' => 'nullable|in:desc,asc'
        ]);

        // add filter for status
        if($request->status != null){
            $applications = $applications->where('status', $request->status);
        }

        // add filter for date order
        if($request->date != null){
            $applications = $applications->orderBy('created_at', $request->date );
        }

        /**
         * check if filter is applied
         *
         * @var bool $isFilterActivated
         */
        $isFilterActivated = false;

        if(count($filters) > 0){
            foreach($filters as $key => $filter){
                if($filter != null && $key != 'cgpa_action') $isFilterActivated = true;
            }
        }

        // get filtered or unfiltered object
        $applications = $applications->get();

        return view('pages.user.application.list', ['isFilterActivated'=>$isFilterActivated,'filters'=>$filters, 'applications'=>$applications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Internship $internship): View
    {
        return view('pages.user.internship.apply', ['internship'=>$internship]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Internship  $internship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Internship $internship): RedirectResponse
    {
        /**
         * user data
         *
         * @var \App\Models\User $user_data
         */
        $user_data = User::find($request->get('user_id'));

        //check if the user have internship
        if($user_data->haveInternship()){
            return redirect()->route('user.internship.apply', $internship->id)->with('error', 'You have Internship in progress!');
        }

        //check if the user already applied
        if($user_data->alreadyApplied($internship)){
            return redirect()->route('user.internship.apply', $internship->id)->with('error', 'You have already applied to this internship!');
        }

        //check if the deadline is passed
        if($user_data->alreadyApplied($internship)){
            return redirect()->route('user.internship.apply', $internship->id)->with('error', 'You have already applied to this internship!');
        }

        /**
         * rules for validation
         *
         * @var array $rules
         */
        $rules = [
            'user_id' => 'exists:\App\Models\User,id|integer|required',
            'internship_id' => 'exists:\App\Models\Internship,id|integer|required',
        ];

        foreach ($internship->prerequisites as $prerequisite) {
            $rules['r_'.$prerequisite->id] = 'required';
        }

        // validating request
        $request->validate($rules);

        /**
         * new application data
         *
         * @var \App\Model\UserApplication $data
         */
        $data = new UserApplication($request->all());

        // saving new instance in db
        if($data->save()){
            // checking if the internship have prerequisite
            if($internship->prerequisites){

                /**
                 * flag for checking insertions
                 *
                 * @var bool $flag
                 */
                $flag = true;

                foreach($internship->prerequisites as $prerequisite){

                    $pre_data = new UserPrerequisiteResponse([
                        'user_application_id' => $data->id,
                        'prerequisite_id' => $prerequisite->id,
                        'response' => $request->get('r_'.$prerequisite->id)
                    ]);

                    if(!$pre_data->save()) $flag = false;
                }

                if($flag){
                    return redirect()->route('user.internship.apply', $internship->id)->with('success', 'Successfully applied!');
                }else{
                    return redirect()->route('user.internship.apply', $internship->id)->with('error', 'Something went wrong, please try again!');
                }

            }else{
                return redirect()->route('user.internship.apply', $internship->id)->with('success', 'Successfully applied!');
            }
        }else{
            return redirect()->route('user.internship.apply', $internship->id)->with('error', 'Something went wrong, please try again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $user_application
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(int $user_application): View|RedirectResponse
    {
        /**
         * getting user application from db
         *
         * @var \App\Models\UserApplication $user_application
         */
        $user_application = UserApplication::find($user_application);
        // check authorization
        if($this->checkAuthorizations(self::MODEL_APPLICATIONS, auth()->user()->type, $user_application, self::ACTION_VIEW)){
            /**
             * statics for better decisions
             *
             * @var array<string, int> $statistics
             */
            $statistics = [];

            $statistics[] = $user_application->internship->statistics();

            $statistics[1]['rankByDate'] = $user_application->rankByDate();
            $statistics[1]['rankByCGPA'] = $user_application->rankByCGPA();
            $statistics[1]['completeness'] = $user_application->user->profileCompleteness();

            return view('pages.department.application.view', ['statistics' => $statistics,'user_application' => $user_application, 'internship' => $user_application->internship, 'user' => $user_application->user, 'prerequisite_responses' => $user_application->prerequisiteResponses, 'interview' => $user_application->haveInterview()]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Change the application status to accepted
     *
     * @param  int $user_application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptApplication(int $user_application): RedirectResponse
    {
        /**
         * getting user application from db
         *
         * @var \App\Models\UserApplication $user_application
         */
        $user_application = UserApplication::find($user_application);
        // check authorization
        if($this->checkAuthorizations(self::MODEL_APPLICATIONS, auth()->user()->type, $user_application, self::ACTION_ACCEPT_APPLICATION)){
            if($user_application->user->haveInternship()){
                return redirect()->route('department.application.view', $user_application->id)->with('error', 'User have already enrolled in another Internship!');
            }else{
                if($user_application->internship->isQuotaFull()){
                    return redirect()->route('department.application.view', $user_application->id)->with('error', 'Internship Quota full, no available spot!');
                }else{
                    if($user_application->internship->isEnded()){
                        return redirect()->route('department.application.view', $user_application->id)->with('error', 'Cannot make change to this application,Internship ended!');
                    }else{
                        if($user_application->internship->isStarted()){
                            return redirect()->route('department.application.view', $user_application->id)->with('error', 'Cannot make change to this application,Internship started!');
                        }else{
                            if($user_application->update(['status' => 1])){
                                if(!$user_application->haveInterview()){
                                    $user_application->initInterview();
                                }
                                $is_messaged = sendApplicationAcceptedMessage($user_application->haveInterview(), $user_application->internship);
                                $is_mailed = sendApplicationAcceptedMail($user_application->user);
                                return redirect()->route('department.application.view', [$user_application->id, 'is_messaged' => $is_messaged, 'is_mailed' => $is_mailed])->with('success', 'Application status successfully changed to Accepted!');
                            }else{
                                return redirect()->route('department.application.view', $user_application->id)->with('error', 'Something went wrong, please try again!');
                            }
                        }
                    }
                }
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }


    }

    /**
     * Change the application status to rejected
     *
     * @param  int $user_application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectApplication(int $user_application): RedirectResponse
    {
        /**
         * getting user application from db
         *
         * @var \App\Models\UserApplication $user_application
         */
        $user_application = UserApplication::find($user_application);

        // check authorization
        if($this->checkAuthorizations(self::MODEL_APPLICATIONS, auth()->user()->type, $user_application, self::ACTION_REJECT_APPLICATION)){
            if($user_application->internship->isEnded()){
                return redirect()->route('department.application.view', $user_application->id)->with('error', 'Cannot make change to this application,Internship ended!');
            }else{
                if($user_application->internship->isStarted()){
                    return redirect()->route('department.application.view', $user_application->id)->with('error', 'Cannot make change to this application,Internship started!');
                }else{
                    if($user_application->update(['status' => 2])){
                        return redirect()->route('department.application.view', $user_application->id)->with('success', 'Application status successfully changed to Rejected!');
                    }else{
                        return redirect()->route('department.application.view', $user_application->id)->with('error', 'Something went wrong, please try again!');
                    }
                }
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }

    }

    /**
     * reset application status
     *
     * @param  int $user_application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetApplication(int $user_application): RedirectResponse
    {
        /**
         * getting user application from db
         *
         * @var \App\Models\UserApplication $user_application
         */
        $user_application = UserApplication::find($user_application);
        // check authorization
        if($this->checkAuthorizations(self::MODEL_APPLICATIONS, auth()->user()->type, $user_application, self::ACTION_RESET_APPLICATION)){
            if($user_application->internship->isEnded()){
                return redirect()->route('department.application.view', $user_application->id)->with('error', 'Cannot make change to this application,Internship ended!');
            }else{
                if($user_application->internship->isStarted()){
                    return redirect()->route('department.application.view', $user_application->id)->with('error', 'Cannot make change to this application,Internship started!');
                }else{
                    if($user_application->update(['status' => 0])){
                        if($user_application->haveInterview()){
                            $user_application->deleteInterview();
                        }
                        return redirect()->route('department.application.view', $user_application->id)->with('success', 'Application status successfully rested!');
                    }else{
                        return redirect()->route('department.application.view', $user_application->id)->with('error', 'Something went wrong, please try again!');
                    }
                }
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $user_application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $user_application): RedirectResponse
    {
        /**
         * getting user application from db
         *
         * @var \App\Models\UserApplication $user_application
         */
        $user_application = UserApplication::find($user_application);
        // check authorization
        if($this->checkAuthorizations(self::MODEL_APPLICATIONS, auth()->user()->type, $user_application, self::ACTION_DELETE)){
            if($user_application->status == 1){
                return redirect()->route($this->current_route.'.application.list')->with('error', 'This Application status is accepted!');
            }else{
                // delete the instance and return message
                if($user_application->delete()){
                    return redirect()->route($this->current_route.'.application.list')->with('success', "Application has been deleted successfully!");
                }else{
                    return redirect()->route($this->current_route.'.application.list')->with('error', 'Something went wrong, please try again!');
                }
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Remove the specified resource from storage for users.
     *
     * @param  int $user_application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revoke(int $user_application): RedirectResponse
    {
        /**
         * getting user application from db
         *
         * @var \App\Models\UserApplication $user_application
         */
        $user_application = UserApplication::find($user_application);

        // check authorization
        if($this->checkAuthorizations(self::MODEL_APPLICATIONS, auth()->user()->type, $user_application, self::ACTION_DELETE)){
            if($user_application->status == 1){
                return redirect()->route('user.application.list')->with('error', 'You are enrolled in this internship!');
            }else if($user_application->status == 2){
                return redirect()->route('user.application.list')->with('error', 'You are rejected from this internship!');
            }else{
                // delete the instance and return message
                if($user_application->delete()){
                    return redirect()->route('user.application.list')->with('success', "Application has been revoked successfully!");
                }else{
                    return redirect()->route('user.application.list')->with('error', 'Something went wrong, please try again!');
                }
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }
}
