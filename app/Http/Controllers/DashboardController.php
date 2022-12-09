<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Internship;
use App\Models\School;
use App\Models\User;
use App\Models\UserApplication;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        /**
         * count of department, school, staff and users
         *
         * @var array<string, int> $stat_counts
         */
        $stat_counts = [
            'departments' => count(Department::all()),
            'schools' => count(School::all()),
            'staffs' => count(User::where('is_staff', '1')->get()),
            'interns' => count(UserApplication::where('status', '1')->get())
        ];

        /**
         * week dates
         *
         * @var array<string> $week_dates
         */
        $week_dates = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        /**
         * get current week
         *
         * @var \Carbon\Carbon $current_week
         */
        $current_week = Carbon::now()->setTimezone('Africa/Addis_Ababa');

        /**
         * get last week
         *
         * @var \Carbon\Carbon $last_week
         */
        $last_week = Carbon::now()->subWeek()->setTimezone('Africa/Addis_Ababa');

        /**
         * application counts in this week and last week
         *
         * @var array<int <string, int>> $application_count
         */
        $application_count = [
            'thisWeek' => [],
            'lastWeek' => []
        ];

        for ($i = 0; $i < 7; $i++) {
            if ($current_week->startOfWeek()->addDays($i)->isPast()) {
                $application_count['thisWeek'][$week_dates[$i]] = count(UserApplication::whereDate('created_at', $current_week->startOfWeek()->addDays($i)->format('Y-m-d H:i:s'))->get());
            }
            $application_count['lastWeek'][$week_dates[$i]] = count(UserApplication::whereDate('created_at', $last_week->startOfWeek()->addDays($i)->format('Y-m-d H:i:s'))->get());
        }

        /**
         * calculating Percent increase (or decrease) of applications
         *
         * Percent increase (or decrease) = ((thisWeek - lastWeek)/lastWeek) * 100
         */
        $application_count['percentage'] = (array_sum(array_values($application_count['lastWeek'])) == 0) ? ((array_sum(array_values($application_count['thisWeek'])) == 0) ? 0 : 100) : round(((array_sum(array_values($application_count['thisWeek'])) - array_sum(array_values($application_count['lastWeek']))) / array_sum(array_values($application_count['lastWeek']))) * 100, 2);

        /**
         * all pending applications
         *
         * @var \App\Models\UserApplication $applications
         */
        $applications =  UserApplication::where('status', '0')->get();

        // dd($application_count);
        return view('pages.admin.home', ['applications' => $applications, 'stat_counts' => $stat_counts, 'application_count' => $application_count]);
    }

    /**
     * Display a listing of the resource for school.
     *
     * @return \Illuminate\View\View
     */
    public function schoolIndex(): View
    {
        /**
         * count of department, school, staff and users
         *
         * @var array<string, int> $stat_counts
         */
        $stat_counts = [
            'departments' => count(Department::where('school_id', auth()->user()->school->id)->get()),
            'internships' => count(Internship::whereIn('department_id', function ($query) {
                $query->select('id')
                    ->from('departments')
                    ->where('school_id', auth()->user()->school->id);
            })->get()),
            'interns' => count(UserApplication::whereIn('internship_id', function ($query) {
                $query->select('id')
                    ->from('internships')
                    ->whereIn('department_id', function ($query2) {
                        $query2->select('id')
                            ->from('departments')
                            ->where('school_id', auth()->user()->school->id);
                    });
            })->where('status', '1')->get()),
            'applications' => count(UserApplication::whereIn('internship_id', function ($query) {
                $query->select('id')
                    ->from('internships')
                    ->whereIn('department_id', function ($query2) {
                        $query2->select('id')
                            ->from('departments')
                            ->where('school_id', auth()->user()->school->id);
                    });
            })->get())
        ];

        /**
         * week dates
         *
         * @var array<string> $week_dates
         */
        $week_dates = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        /**
         * get current week
         *
         * @var \Carbon\Carbon $current_week
         */
        $current_week = Carbon::now()->setTimezone('Africa/Addis_Ababa');

        /**
         * get last week
         *
         * @var \Carbon\Carbon $last_week
         */
        $last_week = Carbon::now()->subWeek()->setTimezone('Africa/Addis_Ababa');

        /**
         * application counts in this week and last week
         *
         * @var array<int <string, int>> $application_count
         */
        $application_count = [
            'thisWeek' => [],
            'lastWeek' => []
        ];

        for ($i = 0; $i < 7; $i++) {
            if ($current_week->startOfWeek()->addDays($i)->isPast()) {
                $application_count['thisWeek'][$week_dates[$i]] = count(UserApplication::whereIn('internship_id', function ($query) {
                    $query->select('id')
                        ->from('internships')
                        ->whereIn('department_id', function ($query2) {
                            $query2->select('id')
                                ->from('departments')
                                ->where('school_id', auth()->user()->school->id);
                        });
                })->whereDate('created_at', $current_week->startOfWeek()->addDays($i)->format('Y-m-d H:i:s'))->get());
            }
            $application_count['lastWeek'][$week_dates[$i]] = count(UserApplication::whereIn('internship_id', function ($query) {
                $query->select('id')
                    ->from('internships')
                    ->whereIn('department_id', function ($query2) {
                        $query2->select('id')
                            ->from('departments')
                            ->where('school_id', auth()->user()->school->id);
                    });
            })->whereDate('created_at', $last_week->startOfWeek()->addDays($i)->format('Y-m-d H:i:s'))->get());
        }

        /**
         * calculating Percent increase (or decrease) of applications
         *
         * Percent increase (or decrease) = ((thisWeek - lastWeek)/lastWeek) * 100
         */
        $application_count['percentage'] = (array_sum(array_values($application_count['lastWeek'])) == 0) ? ((array_sum(array_values($application_count['thisWeek'])) == 0) ? 0 : 100) : round(((array_sum(array_values($application_count['thisWeek'])) - array_sum(array_values($application_count['lastWeek']))) / array_sum(array_values($application_count['lastWeek']))) * 100, 2);

        /**
         * all pending applications
         *
         * @var \App\Models\UserApplication $applications
         */
        $applications =  UserApplication::whereIn('internship_id', function ($query) {
            $query->select('id')
                ->from('internships')
                ->whereIn('department_id', function ($query2) {
                    $query2->select('id')
                        ->from('departments')
                        ->where('school_id', auth()->user()->school->id);
                });
        })->where('status', '0')->get();

        // dd($application_count);
        return view('pages.school.home', ['applications' => $applications, 'stat_counts' => $stat_counts, 'application_count' => $application_count]);
    }

    /**
     * Display a listing of the resource for department.
     *
     * @return \Illuminate\View\View
     */
    public function departmentIndex(): View
    {
        /**
         * count of department, school, staff and users
         *
         * @var array<string, int> $stat_counts
         */
        $stat_counts = [
            'internships' => count(Internship::where('department_id', auth()->user()->department->id)->get()),
            'applications' => count(UserApplication::whereIn('internship_id', function ($query) {
                $query->select('id')
                    ->from('internships')
                    ->where('department_id', auth()->user()->department->id);
            })->get()),
            'pending' => count(UserApplication::whereIn('internship_id', function ($query) {
                $query->select('id')
                    ->from('internships')
                    ->where('department_id', auth()->user()->department->id);
            })->where('status', '0')->get()),
            'interns' => count(UserApplication::whereIn('internship_id', function ($query) {
                $query->select('id')
                    ->from('internships')
                    ->where('department_id', auth()->user()->department->id);
            })->where('status', '1')->get())
        ];

        /**
         * week dates
         *
         * @var array<string> $week_dates
         */
        $week_dates = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        /**
         * get current week
         *
         * @var \Carbon\Carbon $current_week
         */
        $current_week = Carbon::now()->setTimezone('Africa/Addis_Ababa');

        /**
         * get last week
         *
         * @var \Carbon\Carbon $last_week
         */
        $last_week = Carbon::now()->subWeek()->setTimezone('Africa/Addis_Ababa');

        /**
         * application counts in this week and last week
         *
         * @var array<int <string, int>> $application_count
         */
        $application_count = [
            'thisWeek' => [],
            'lastWeek' => []
        ];

        for ($i = 0; $i < 7; $i++) {
            if ($current_week->startOfWeek()->addDays($i)->isPast()) {
                $application_count['thisWeek'][$week_dates[$i]] = count(UserApplication::whereIn('internship_id', function ($query) {
                    $query->select('id')
                        ->from('internships')
                        ->where('department_id', auth()->user()->department->id);
                })->whereDate('created_at', $current_week->startOfWeek()->addDays($i)->format('Y-m-d H:i:s'))->get());
            }
            $application_count['lastWeek'][$week_dates[$i]] = count(UserApplication::whereIn('internship_id', function ($query) {
                $query->select('id')
                    ->from('internships')
                    ->where('department_id', auth()->user()->department->id);
            })->whereDate('created_at', $last_week->startOfWeek()->addDays($i)->format('Y-m-d H:i:s'))->get());
        }

        /**
         * calculating Percent increase (or decrease) of applications
         *
         * Percent increase (or decrease) = ((thisWeek - lastWeek)/lastWeek) * 100
         */
        $application_count['percentage'] = (array_sum(array_values($application_count['lastWeek'])) == 0) ? ((array_sum(array_values($application_count['thisWeek'])) == 0) ? 0 : 100) : round(((array_sum(array_values($application_count['thisWeek'])) - array_sum(array_values($application_count['lastWeek']))) / array_sum(array_values($application_count['lastWeek']))) * 100, 2);

        /**
         * all pending applications
         *
         * @var \App\Models\UserApplication $applications
         */
        $applications =  UserApplication::whereIn('internship_id', function ($query) {
            $query->select('id')
                ->from('internships')
                ->where('department_id', auth()->user()->department->id);
        })->where('status', '0')->get();

        // dd($application_count);
        return view('pages.department.home', ['applications' => $applications, 'stat_counts' => $stat_counts, 'application_count' => $application_count]);
    }
}
