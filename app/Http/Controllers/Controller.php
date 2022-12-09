<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Internship;
use App\Models\User;
use App\Models\UserApplication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public const MODEL_DEPARTMENT = 'department';
    public const MODEL_USER = 'user';
    public const MODEL_INTERNSHIP = 'internship';
    public const MODEL_SCHOOL = 'school';
    public const MODEL_APPLICATIONS = 'applications';
    public const MODEL_INTERNS = 'interns';
    public const MODEL_STAFF = 'staff';

    public const ACTION_VIEW = 'view';
    public const ACTION_EDIT = 'edit';
    public const ACTION_UPDATE = 'update';
    public const ACTION_CREATE = 'create';
    public const ACTION_STORE = 'store';
    public const ACTION_DELETE = 'delete';
    public const ACTION_ACCEPT_APPLICATION = 'accept_application';
    public const ACTION_REJECT_APPLICATION = 'reject_application';
    public const ACTION_RESET_APPLICATION = 'reset_application';
    public const ACTION_APPLY = 'apply';


    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * check authorizations for various actions
     *
     * @param string $model
     * @param string $actor
     * @param string $action
     * @param mixed $data
     * @return bool
     */
    public function checkAuthorizations(string $model, string $actor, mixed $data, string $action):bool
    {
        // check for required fields
        if($model == null || $actor == null || $data == null) return false;
        if($model == '' || $actor == '' || $data == '') return false;
        if($actor == 'admin'){
            return $this->checkAdminAuthorizations($model, $data, $action,);
        }else if($actor == 'user'){
            return $this->checkUserAuthorizations($model, $data, $action,);
        }else if($actor == 'department'){
            return $this->checkDepartmentAuthorizations($model, $data, $action);
        }else if($actor == 'school'){
            return $this->checkSchoolAuthorizations($model, $data, $action,);
        }else{
            // unknown actor
            return false;
        }
    }

    /**
     * check school authorizations
     *
     * @param string $model
     * @param string $action
     * @param mixed $data
     * @return bool
     */
    public function checkAdminAuthorizations(string $model, mixed $data, string $action = null): bool
    {
        // check if the user is head of department
        if(auth()->user()->type != 'admin') return false;
        // dd($data);

        // check for school actions
        if($model === self::MODEL_SCHOOL){
            return true;
        }
        // check for department
        else if($model === self::MODEL_DEPARTMENT){
            return true;
        }
        // check for staffs actions
        else if($model === self::MODEL_STAFF){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof User)) return false;

            // check if the user is staff
            if($data->is_staff == '1'){
                return true;
            }else{
                return false;
            }
        }
        // check for internship actions
        else if($model === self::MODEL_INTERNSHIP){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof Internship)) return false;

            // check the action
            if($action === self::ACTION_VIEW || $action === self::ACTION_DELETE){
                return true;
            }else{
                return false;
            }
        }
        // check for application actions
        else if($model === self::MODEL_APPLICATIONS){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof UserApplication)) return false;

            // check the action
            if($action === self::ACTION_DELETE){
                return true;
            }else{
                return false;
            }
        }
        // check for interns actions
        else if($model === self::MODEL_INTERNS){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof UserApplication)) return false;

            // check the action
            if($action === self::ACTION_VIEW){
                return true;
            }else{
                return false;
            }
        }
        // if other models comes it doesn't have authorization
        else{
            return false;
        }
        return true;
    }

    /**
     * check school authorizations
     *
     * @param string $model
     * @param string $action
     * @param mixed $data
     * @return bool
     */
    public function checkSchoolAuthorizations(string $model, mixed $data, string $action = null): bool
    {
        // check if the user is head of school
        if(auth()->user()->school === null) return false;

        // check for department actions
        if($model === self::MODEL_DEPARTMENT){
            // check the instance
            if(!($data instanceof Department)) return false;


            // check if the user owns the department
            if($data->school_id === auth()->user()->school->id){
                return true;
            }else{
                return false;
            }
        }
        // check for internship actions
        else if($model === self::MODEL_INTERNSHIP){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof Internship)) return false;

            // check if the user owns the internship
            if($data->department->school->id === auth()->user()->school->id){
                // check the action
                if($action === self::ACTION_VIEW || $action === self::ACTION_DELETE){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        // check for application actions
        else if($model === self::MODEL_APPLICATIONS){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof UserApplication)) return false;

            // check if the user owns the application
            if($data->internship->department->school->id === auth()->user()->school->id){
                // check the action
                if($action === self::ACTION_DELETE){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        // check for interns actions
        else if($model === self::MODEL_INTERNS){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof UserApplication)) return false;

            // check if the user owns the interns
            if($data->internship->department->school->id === auth()->user()->school->id){
                // check the action
                if($action === self::ACTION_VIEW){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        // if other models comes it doesn't have authorization
        else{
            return false;
        }
    }

    /**
     * check department authorizations
     *
     * @param string $model
     * @param string $action
     * @param mixed $data
     * @return bool
     */
    public function checkDepartmentAuthorizations(string $model, mixed $data, string $action = null): bool
    {
        // check if the user is head of department
        if(auth()->user()->department === null) return false;

        // check for department actions
        if($model === self::MODEL_INTERNSHIP){
            // check the instance
            if(!($data instanceof Internship)) return false;


            // check if the user owns the department
            if($data->department_id === auth()->user()->department->id){
                return true;
            }else{
                return false;
            }
        }
        // check for application actions
        else if($model === self::MODEL_APPLICATIONS){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof UserApplication)) return false;

            // check if the user owns the application
            if($data->internship->department->id === auth()->user()->department->id){
                // check the action
                if($action === self::ACTION_DELETE || $action === self::ACTION_VIEW || $action === self::ACTION_ACCEPT_APPLICATION || $action === self::ACTION_REJECT_APPLICATION || $action === self::ACTION_RESET_APPLICATION){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        // check for interns actions
        else if($model === self::MODEL_INTERNS){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof UserApplication)) return false;

            // check if the user owns the interns
            if($data->internship->department->id === auth()->user()->department->id){
                // check the action
                if($action === self::ACTION_VIEW || $action === self::ACTION_DELETE){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        // if other models comes it doesn't have authorization
        else{
            return false;
        }
    }

    /**
     * check user authorizations
     *
     * @param string $model
     * @param string $action
     * @param mixed $data
     * @return bool
     */
    public function checkUserAuthorizations(string $model, mixed $data, String $action = null): bool
    {
        // check if the user is not staff
        if(auth()->user()->is_staff == '1') return false;

        // check for internship actions
        if($model === self::MODEL_INTERNSHIP){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof Internship)) return false;

            // check the action
            if($action === self::ACTION_VIEW || $action === self::ACTION_APPLY){
                return true;
            }else{
                return false;
            }
        }
        // check for application actions
        else if($model === self::MODEL_APPLICATIONS){
            // action is required
            if($action === null) return false;

            // check the instance
            if(!($data instanceof UserApplication)) return false;

            // check if the user owns the application
            if($data->user_id === auth()->user()->id){
                // check the action
                if($action === self::ACTION_DELETE){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
        // if other models comes it doesn't have authorization
        else{
            return false;
        }
    }
}
