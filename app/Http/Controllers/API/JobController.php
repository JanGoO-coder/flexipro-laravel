<?php

namespace App\Http\Controllers\API;

use App\Models\Job;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class JobController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => []]);
    }

    /**
     * Add a new job for a specific company
     */
    function addJob(Request $request)   {
        try {
            $company = JWTAuth::user();
            $company_id = $company->id;

            $attributes = $request->only('job_title', 'job_description', 'budget', 'duration_days', 'category_id');
            $attributes["company_id"] = $company_id;

            $validator = Validator::make($attributes, [
                'job_title' => 'required',
                'job_description' => 'required',
                'budget' => 'required',
                'duration_days' => 'required',
                'category_id' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            $response = Job::create($attributes);
    
            $success["success"] = $response;

            if ($response) {
                $success["message"] = "New Job Added Successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * update a specific job
     */
    function updateJob(Request $request) {
        try {
            $job_id = $request->id;

            $attributes = $request->only('id', 'job_title', 'job_description', 'budget', 'duration_days', 'category_id');

            $validator = Validator::make($attributes, [
                'id' => 'required',
                'job_title' => 'required',
                'job_description' => 'required',
                'budget' => 'required',
                'duration_days' => 'required',
                'category_id' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            unset($attributes["id"]);

            $response = Job::where("id", $job_id)->update($attributes);
    
            if ($response) {
                $success["message"] = "Job Updated Successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * get all jobs for a specific company
     */
    public function getJobs(Request $request) {
        try {
            $company = JWTAuth::user();
            $company_id = $company->id;

            $jobs = Job::where("company_id", $company_id)->get();

            $success["jobs"] = $jobs;

            if (isset($jobs) && count($jobs) > 0) {
                $success["message"] = "Total " . count($jobs) . " Jobs retrieved Successfully!";
            } else {
                $success["message"] = "Your company doesn't posted any jobs yet!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * delete a specific job
     */
    function deleteJobs($id) {
        try {
            $response = Job::where('id',$id)->delete();
    
            $success["success"] = $response;

            if ($response) {
                $success["message"] = "Job deleted Successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * get a specifc job
     */
    public function getJobById($id) {
        try {
            $response = Job::where('id',$id)->get();

            if (isset($response) && count($response) > 0) {
                $success["message"] = "Job data retrieved Successfully!";
            } else {
                $success["message"] = "Job Not Found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }
}
