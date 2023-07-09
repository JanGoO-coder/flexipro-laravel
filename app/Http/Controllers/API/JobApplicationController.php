<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JobApplicationController extends Controller
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
     * Get all job applications sent by a user against a particular user
     */
    public function getUserJobApplications(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $user_id = $user->id;

            $jobApplications = JobApplication::where("user_id", $user_id)->get();
            
            $success["applications"] = $jobApplications;
            
            if (isset($jobApplications) && count($jobApplications) > 0) {
                $success["message"] = "Total " . count($jobApplications) . " Job Applications data retrieved successfully!";
            } else {
                $success["message"] = "No Job Applications Found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * Get all job applications recieved by company against a particular user
     */
    public function getCompanyJobApplications(Request $request)
    {
        try {
            $company = JWTAuth::user();
            $company_id = $company->id;

            $jobApplications = JobApplication::where("company_id", $company_id)->get();
            
            $success["applications"] = $jobApplications;
            
            if (isset($jobApplications) && count($jobApplications) > 0) {
                $success["message"] = "Total " . count($jobApplications) . " Job Applications data retrieved successfully!";
            } else {
                $success["message"] = "No Job Applications Found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * Send application job to a company
     */
    public function sendJobApplication(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $user_id = $user->id;

            $attributes = $request->only('description', 'job_id', 'company_id');

            $validator = Validator::make($attributes, [
                'description' => 'required',
                'job_id' => 'required',
                'company_id' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            $attributes["user_id"] = $user_id;
            $attributes["status"] = "pending";

            $response = JobApplication::create($attributes);

            if ($response) {
                $success["message"] = "Job Application Sent Successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * update status of job application
     */
    public function updateJopApplicationStatus(Request $request)
    {
        try {
            $attributes = $request->only('status');
            
            $validator = Validator::make($attributes, [
                'status' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            $response = JobApplication::where('id', $request->id)->update($attributes);

            if ($response) {
                $success["message"] = "Job Application got " . $request->status;
            } else {
                $success["message"] = "Job Application not found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * remove job application
     */
    public function removeJopApplication(Request $request)
    {
        try {
            $response = JobApplication::where('id', $request->id)->delete();

            if ($response) {
                $success["message"] = "Job Application deleted successfully!";
            } else {
                $success["message"] = "Job Application not found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }
}
