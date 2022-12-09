<?php

namespace App\Jobs;

use App\Models\AtsReport;
use App\Models\Internship;
use App\Models\UserApplication;
use App\Models\UserPrerequisiteResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeneratePdfReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private AtsReport $ats_report)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * getting all recourses between the given dates
         *
         * @var \App\Modes\Internship $internships
         */
        $internships = null;

        /**
         * getting all recourses between the given dates
         *
         * @var \App\Modes\UserApplication $user_applications
         */
        $user_applications = null;

        // for admin
        if($this->ats_report->owner_type == 'admin'){

            $internships = Internship::whereBetween('created_at', [$this->ats_report->start_date, $this->ats_report->end_date])->get();

            $user_applications = UserApplication::whereBetween('created_at', [$this->ats_report->start_date, $this->ats_report->end_date])->get();
        }
        // for school
        else if($this->ats_report->owner_type == 'school'){
            $internships = Internship::whereIn('department_id', function($query) {
                $query->select('id')
                ->from('departments')
                ->where('school_id', $this->ats_report->owner);
            })->whereBetween('created_at', [$this->ats_report->start_date, $this->ats_report->end_date])->get();

            $user_applications = UserApplication::whereIn('internship_id', function($query) {
                $query->select('id')
                ->from('internships')
                ->whereIn('department_id', function($query2){
                    $query2->select('id')
                    ->from('departments')
                    ->where('school_id', $this->ats_report->owner);
                });
            })->whereBetween('created_at', [$this->ats_report->start_date, $this->ats_report->end_date])->get();
        }
        // for department
        else if($this->ats_report->owner_type == 'department'){
            $internships = Internship::where('department_id', $this->ats_report->owner)->whereBetween('created_at', [$this->ats_report->start_date, $this->ats_report->end_date])->get();

            $user_applications = UserApplication::whereIn('internship_id', function($query) {
                $query->select('id')
                ->from('internships')
                ->where('department_id', $this->ats_report->owner);
            })->whereBetween('created_at', [$this->ats_report->start_date, $this->ats_report->end_date])->get();
        }

        $pdf = Pdf::loadView('reports.'.$this->ats_report->owner_type, ['internships'=>$internships, 'user_applications'=>$user_applications, 'ats_report'=>$this->ats_report])->setPaper('a4', 'landscape');

        if(Storage::disk('public_storage')->put('ats_r_'.$this->ats_report->id.'.pdf', $pdf->output())){
            $this->ats_report->update(['file_path' => 'ats_r_'.$this->ats_report->id.'.pdf']);
        }

    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        $this->ats_report->update(['file_path' => 'generate_error']);
        Log::error($exception->getMessage());
    }
}
