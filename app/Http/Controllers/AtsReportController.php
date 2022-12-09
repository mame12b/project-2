<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\UserApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AtsReportController extends Controller
{
    /**
     * application listing for department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function departmentApplication(Request $request): View
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
            'start_date' => 'nullable|date_format:Y-m-d|before:end_date',
            'end_date' => 'nullable|date_format:Y-m-d|after:start_date',
            'internship' => 'nullable|exists:\App\Models\Internship,id',
            'date' => 'nullable|in:desc,asc',
        ]);

        // add filter for status
        if($request->status != null){
            $applications = $applications->where('status', $request->status);
        }

        // add filter for internship
        if($request->internship != null){
            $applications = $applications->where('internship_id', $request->internship);
        }

        // add filter for start and end date
        if($request->start_date != null && $request->end_date != null){
            $applications = $applications->whereBetween('created_at', [$request->start_date, $request->end_date]);
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
                if($filter != null) $isFilterActivated = true;
            }
        }

        if(!$isFilterActivated){
            $applications = [];
        }

        return view('pages.department.reports.application', ['isFilterActivated'=>$isFilterActivated,'filters'=>$filters, 'internships' => $internships,'applications'=>$applications]);

    }

    /**
     * internship listing for department
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function departmentInternship(Request $request): View
    {
        /**
         * all available internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships =  Internship::where('department_id',auth()->user()->department->id);

        /**
         * filters array
         *
         * @var array $filters
         */
        $filters = ($request->all())?$request->all():[];

        $request->validate([
            'status' => 'nullable|in:0,1,2,3,4',
            'start_date' => 'nullable|date_format:Y-m-d|before:end_date',
            'end_date' => 'nullable|date_format:Y-m-d|after:start_date',
            'date' => 'nullable|in:desc,asc'
        ]);

        // add filter for status
        if($request->status != null){
            $internships = $internships->where('status', $request->status);
        }


        // add filter for start and end date
        if($request->start_date != null && $request->end_date != null){
            $internships = $internships->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // add filter for date order
        if($request->date != null){
            $internships = $internships->orderBy('created_at', $request->date );
        }

        // get filtered or unfiltered object
        $internships = $internships->get();

        /**
         * check if filter is applied
         *
         * @var bool $isFilterActivated
         */
        $isFilterActivated = false;

        if(count($filters) > 0){
            foreach($filters as $key => $filter){
                if($filter != null) $isFilterActivated = true;
            }
        }

        if(!$isFilterActivated){
            $internships = [];
        }

        return view('pages.department.reports.internship', ['isFilterActivated'=>$isFilterActivated,'filters'=>$filters, 'internships' => $internships]);

    }

    /**
     * application listing for school
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function schoolApplication(Request $request): View
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

       /**
        * all available applications in the department
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
        });

        /**
         * filters array
         *
         * @var array $filters
         */
        $filters = ($request->all())?$request->all():[];

        $request->validate([
            'status' => 'nullable|in:0,1,2',
            'start_date' => 'nullable|date_format:Y-m-d|before:end_date',
            'end_date' => 'nullable|date_format:Y-m-d|after:start_date',
            'internship' => 'nullable|exists:\App\Models\Internship,id',
            'date' => 'nullable|in:desc,asc',
        ]);

        // add filter for status
        if($request->status != null){
            $applications = $applications->where('status', $request->status);
        }

        // add filter for internship
        if($request->internship != null){
            $applications = $applications->where('internship_id', $request->internship);
        }

        // add filter for start and end date
        if($request->start_date != null && $request->end_date != null){
            $applications = $applications->whereBetween('created_at', [$request->start_date, $request->end_date]);
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
                if($filter != null) $isFilterActivated = true;
            }
        }

        if(!$isFilterActivated){
            $applications = [];
        }

        return view('pages.school.reports.application', ['isFilterActivated'=>$isFilterActivated,'filters'=>$filters, 'internships' => $internships,'applications'=>$applications]);

    }

    /**
     * internship listing for school
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function schoolInternship(Request $request): View
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
        });

        /**
         * filters array
         *
         * @var array $filters
         */
        $filters = ($request->all())?$request->all():[];

        $request->validate([
            'status' => 'nullable|in:0,1,2,3,4',
            'start_date' => 'nullable|date_format:Y-m-d|before:end_date',
            'end_date' => 'nullable|date_format:Y-m-d|after:start_date',
            'date' => 'nullable|in:desc,asc'
        ]);

        // add filter for status
        if($request->status != null){
            $internships = $internships->where('status', $request->status);
        }


        // add filter for start and end date
        if($request->start_date != null && $request->end_date != null){
            $internships = $internships->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // add filter for date order
        if($request->date != null){
            $internships = $internships->orderBy('created_at', $request->date );
        }

        // get filtered or unfiltered object
        $internships = $internships->get();

        /**
         * check if filter is applied
         *
         * @var bool $isFilterActivated
         */
        $isFilterActivated = false;

        if(count($filters) > 0){
            foreach($filters as $key => $filter){
                if($filter != null) $isFilterActivated = true;
            }
        }

        if(!$isFilterActivated){
            $internships = [];
        }

        return view('pages.school.reports.internship', ['isFilterActivated'=>$isFilterActivated,'filters'=>$filters, 'internships' => $internships]);

    }

    /**
     * application listing for admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function adminApplication(Request $request): View
    {
        /**
         * all available internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships =  Internship::all();

       /**
        * all available applications in the department
        *
        * @var \App\Models\UserApplication $applications
        */
        $applications =  UserApplication::where('id', '!=', null);

        /**
         * filters array
         *
         * @var array $filters
         */
        $filters = ($request->all())?$request->all():[];

        $request->validate([
            'status' => 'nullable|in:0,1,2',
            'start_date' => 'nullable|date_format:Y-m-d|before:end_date',
            'end_date' => 'nullable|date_format:Y-m-d|after:start_date',
            'internship' => 'nullable|exists:\App\Models\Internship,id',
            'date' => 'nullable|in:desc,asc',
        ]);

        // add filter for status
        if($request->status != null){
            $applications = $applications->where('status', $request->status);
        }

        // add filter for internship
        if($request->internship != null){
            $applications = $applications->where('internship_id', $request->internship);
        }

        // add filter for start and end date
        if($request->start_date != null && $request->end_date != null){
            $applications = $applications->whereBetween('created_at', [$request->start_date, $request->end_date]);
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
                if($filter != null) $isFilterActivated = true;
            }
        }

        if(!$isFilterActivated){
            $applications = [];
        }

        return view('pages.admin.reports.application', ['isFilterActivated'=>$isFilterActivated,'filters'=>$filters, 'internships' => $internships,'applications'=>$applications]);

    }

    /**
     * internship listing for admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function adminInternship(Request $request): View
    {
        /**
         * all available internships
         *
         * @var \App\Models\Internship $internships
         */
        $internships =  Internship::where('id', '!=', null);

        /**
         * filters array
         *
         * @var array $filters
         */
        $filters = ($request->all())?$request->all():[];

        $request->validate([
            'status' => 'nullable|in:0,1,2,3,4',
            'start_date' => 'nullable|date_format:Y-m-d|before:end_date',
            'end_date' => 'nullable|date_format:Y-m-d|after:start_date',
            'date' => 'nullable|in:desc,asc'
        ]);

        // add filter for status
        if($request->status != null){
            $internships = $internships->where('status', $request->status);
        }


        // add filter for start and end date
        if($request->start_date != null && $request->end_date != null){
            $internships = $internships->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // add filter for date order
        if($request->date != null){
            $internships = $internships->orderBy('created_at', $request->date );
        }

        // get filtered or unfiltered object
        $internships = $internships->get();

        /**
         * check if filter is applied
         *
         * @var bool $isFilterActivated
         */
        $isFilterActivated = false;

        if(count($filters) > 0){
            foreach($filters as $key => $filter){
                if($filter != null) $isFilterActivated = true;
            }
        }

        if(!$isFilterActivated){
            $internships = [];
        }

        return view('pages.admin.reports.internship', ['isFilterActivated'=>$isFilterActivated,'filters'=>$filters, 'internships' => $internships]);

    }
}
