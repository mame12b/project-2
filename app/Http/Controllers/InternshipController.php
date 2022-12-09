<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\InternshipPrerequisite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use App\Http\Resources\InternshipResource;

class InternshipController extends Controller
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
         * all available internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships =  Internship::all();

        return view('pages.admin.internship.list', ['internships'=> $internships]);
    }

    /**
     * Display a listing of the resource that belong to one department.
     *
     * @return \Illuminate\View\View
     */
    public function departmentIndex(): View
    {
        /**
         * all available internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships =  Internship::where('department_id',auth()->user()->department->id)->get();

        return view('pages.department.internship.list', ['internships'=> $internships]);
    }

    /**
     * Display a listing of the resource that belong to one school.
     *
     * @return \Illuminate\View\View
     */
    public function schoolIndex(): View
    {
        /**
         * all available internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships =  Internship::whereIn('department_id', function($query) {
                                    $query->select('id')
                                    ->from('departments')
                                    ->where('school_id', auth()->user()->school->id);
                                })->get();

        return view('pages.school.internship.list', ['internships'=> $internships]);
    }

    /**
     * send list of all active internship for API
     *
     * @return array
     */
    public function apiIndex(): array
    {
        /**
         * all active internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships = Internship::where('status', '!=' ,'4')->whereDate('deadline', '>=', date('Y-m-d H:i:s'))->orderBy('updated_at', 'desc')->get();

        $data = [];

        foreach($internships as $internship){
            $data[] = new InternshipResource($internship);
        }
        return $data;
    }

    /**
     * send list of matched internships for API
     *
     * @return array
     */
    public function apiSearchIndex(string $query): array
    {
        /**
         * all active internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships = Internship::where('status', '1')
                        ->whereDate('deadline', '>=', date('Y-m-d H:i:s'))
                        ->where('title', 'LIKE', '%'.$query.'%')
                        ->orWhere('description', 'LIKE', '%'.$query.'%')
                        ->get();

        $data = [];

        foreach($internships as $internship){
            $data[] = new InternshipResource($internship);
        }
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('pages.department.internship.add');
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
            'department_id' => 'exists:\App\Models\Department,id|required|integer',
            'title' => 'string|required',
            'description' => 'nullable|string',
            'minimum_cgpa' => 'nullable|numeric|min:0.0|max:4.0',
            'quota' => 'nullable|integer',
            'deadline' => 'date_format:Y-m-d H:i:s|required|after:tomorrow',
            'start_date' => 'required|date_format:Y-m-d H:i:s|after:deadline',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date'
        ]);

        // creating internship
        $internship = new Internship([
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'minimum_cgpa' => $request->minimum_cgpa,
            'quota' => ($request->quota) ? $request->quota : 0,
            'deadline' => $request->deadline,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        // saving internship
        if($internship->save()){
            if($request->prerequisite != null){
                $prerequisite = array_filter($request->prerequisite, function($k){
                    return $k['pre_key'] != '' || $k['pre_key'] != null;
                });
                if(!empty($prerequisite)){
                    // saving prerequisites
                    $internship->prerequisites()->createMany($prerequisite);
                }
            }
            return redirect()->route('department.internship.add')->with('success', 'Internship has been stored successfully!');
        }else{
            return redirect()->route('department.internship.add')->with('error', 'Something went wrong, please try again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Internship $internship
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Internship $internship): View|RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_INTERNSHIP, auth()->user()->type, $internship, self::ACTION_VIEW)){
            // updating status
            $internship->updateStatus();
            return view('pages.'.$this->current_route.'.internship.view', ['internship'=>$internship]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Internship  $internship
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Internship $internship): View|RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_INTERNSHIP, auth()->user()->type, $internship, self::ACTION_EDIT)){
            return view('pages.department.internship.edit', ['internship'=>$internship]);
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Internship $internship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Internship $internship): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_INTERNSHIP, auth()->user()->type, $internship, self::ACTION_UPDATE)){
            // validating request
            $request->validate([
                'department_id' => 'exists:\App\Models\Department,id|integer',
                'title' => 'string',
                'description' => 'nullable|string',
                'minimum_cgpa' => 'nullable|numeric|min:0.0|max:4.0',
                'quota' => 'nullable|integer',
                'deadline' => 'date_format:Y-m-d H:i:s|after:tomorrow',
                'start_date' => 'nullable|date_format:Y-m-d H:i:s|after:deadline',
                'end_date' => 'nullable|date_format:Y-m-d H:i:s|after:start_date'
            ]);

            /**
             * check if the data is updated
             *
             * @var bool $flag
             */
            $flag = $internship->update([
                'department_id' => ($request->department_id) ? $request->department_id : $internship->department_id,
                'title' => ($request->title) ? $request->title : $internship->title,
                'description' => ($request->description) ? $request->description : $internship->description,
                'minimum_cgpa' => ($request->minimum_cgpa) ? $request->minimum_cgpa : $internship->minimum_cgpa,
                'quota' => ($request->quota) ? $request->quota : $internship->quota,
                'deadline' => ($request->deadline) ? $request->deadline : $internship->deadline,
                'start_date' => ($request->start_date) ? $request->start_date : $internship->start_date,
                'end_date' => ($request->end_date) ? $request->end_date : $internship->end_date
            ]);

            if($flag){
                return redirect()->route('department.internship.edit', $internship->id)->with('success', 'Internship has been updated successfully!');
            }else{
                return redirect()->route('department.internship.edit', $internship->id)->with('error', 'Something went wrong, please try again!');
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * Update the specified prerequisite resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Internship $internship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePrerequisite(Request $request, Internship $internship): RedirectResponse
    {
        /**
         * check the status
         *
         * @var bool $flag
         */
        $flag = false;
        foreach($request->prerequisite as $prerequisite){
            if(isset($prerequisite['id'])){
                if(isset($prerequisite['deleted'])){
                    $flag = InternshipPrerequisite::find($prerequisite['id'])->delete();
                }else{
                    $flag = InternshipPrerequisite::find($prerequisite['id'])->update([
                        'pre_key' => $prerequisite['pre_key']
                    ]);
                }
            }else{
                $arr = [ 0 => $prerequisite];
                $flag = $internship->prerequisites()->createMany($arr);
            }
        }
        if($flag){
            return redirect()->route('department.internship.edit', $internship->id)->with('success', 'Internship has been updated successfully!');
        }else{
            return redirect()->route('department.internship.edit', $internship->id)->with('error', 'Something went wrong, please try again!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Internship $internship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Internship $internship): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_INTERNSHIP, auth()->user()->type, $internship, self::ACTION_DELETE)){

            //check status
            if( $internship->isEnded() && $internship->status != '0'){
                if($internship->update(['status' => 0])){
                    return redirect()->route($this->current_route.'.internship.list')->with('success', "internship has been Ended successfully!");
                }else{
                    return redirect()->route($this->current_route.'.internship.list')->with('error', 'Something went wrong, please try again!');
                }
            }if(($internship->status == '2' || $internship->isStarted()) && $internship->status != '0'){
                return redirect()->route($this->current_route.'.internship.list')->with('error', "You cannot delete ongoing internship,End it first!");
            }else{
                // delete the instance and return message
                if($internship->delete()){
                    return redirect()->route($this->current_route.'.internship.list')->with('success', "internship has been deleted successfully!");
                }else{
                    return redirect()->route($this->current_route.'.internship.list')->with('error', 'Something went wrong, please try again!');
                }
            }
        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }

    /**
     * start the internship.
     *
     * @param  \App\Models\Internship $internship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(Internship $internship): RedirectResponse
    {
        // check authorization
        if($this->checkAuthorizations(self::MODEL_INTERNSHIP, auth()->user()->type, $internship, self::ACTION_DELETE)){

            //check status
            if(!$internship->isEnded() && $internship->isStarted() && $internship->status != '2'){
                if(count($internship->getInterns()) > 0){
                    if($internship->update(['status' => '2'])){
                        $is_messaged = sendInternshipStartedMessage($internship);
                        $is_mailed = sendInternshipStartedMail($internship);
                        return redirect()->route($this->current_route.'.internship.list', ['is_messaged' => $is_messaged, 'is_mailed' => $is_mailed])->with('success', "internship has been Started successfully!");
                    }else{
                        return redirect()->route($this->current_route.'.internship.list')->with('error', 'Something went wrong, please try again!');
                    }
                }else{
                    return redirect()->route($this->current_route.'.internship.list')->with('error', 'No available Intern!');
                }
            }else{
            }

        }else{
            return redirect()->route($this->current_route.'.home')->with('error', 'You are not Authorized for this action!');
        }
    }
}
