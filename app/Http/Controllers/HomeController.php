<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Internship;
use App\Models\User;
use App\Models\UserApplication;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('welcome');
    }

    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function usersHome(): View
    {
        /**
         * user statistic data
         *
         * @var array $stats
         */
        $stats = [0,0,0];
        foreach(auth()->user()->applications as $application){
            if($application != null){
                $stats[(int)$application->status]++;
            }
        }
        return view('pages.user.home', ['stats'=>$stats]);
    }

    /**
     * Show the main dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function welcome(): View
    {
        /**
         * user statistic data
         *
         * @var array $stats
         */
        $stats = [
            'applications' => UserApplication::all()->count(),
            'applicants' => User::where('is_staff', '0')->count(),
            'internships' => Internship::all()->count(),
            'departments' => Department::all()->count()
        ];

        return view('welcome', ['stats'=>$stats]);
    }
}
