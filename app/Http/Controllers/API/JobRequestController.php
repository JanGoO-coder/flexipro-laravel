<?php

namespace App\Http\Controllers\API;

use App\Models\JobRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class JobRequestController extends Controller
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
     * Get all job Requests sent by a user against a particular user
     */
    public function getUserJobRequest(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $user_id = $user->id;

            $jobRequests = JobRequest::where("user_id", $user_id)->get();
            
            $success["requests"] = $jobRequests;
            
            if (isset($jobRequests) && count($jobRequests) > 0) {
                $success["message"] = "Total " . count($jobRequests) . " Job Requests data retrieved successfully!";
            } else {
                $success["message"] = "No Job Requests Found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * Get all job Requests recieved by company against a particular user
     */
    public function getCompanyJobRequests(Request $request)
    {
        try {
            $company = JWTAuth::user();
            $company_id = $company->id;

            $jobRequests = JobRequest::where("company_id", $company_id)->get();
            
            $success["Requests"] = $jobRequests;
            
            if (isset($jobRequests) && count($jobRequests) > 0) {
                $success["message"] = "Total " . count($jobRequests) . " Job Requests data retrieved successfully!";
            } else {
                $success["message"] = "No Job Requests Found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * Send Request job to a company
     */
    public function sendJobRequest(Request $request)
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

            $response = JobRequest::create($attributes);

            if ($response) {
                $success["message"] = "Job Request Sent Successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * update status of job Request
     */
    public function updateJopRequestStatus(Request $request)
    {
        try {
            $attributes = $request->only('id', 'status');
            
            $validator = Validator::make($attributes, [
                'id' => 'required',
                'status' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            uset($attributes["id"]);

            $response = JobRequest::where('id', $request->id)->update($attributes);

            if ($response) {
                $success["message"] = "Job Request got " . $request->status;
            } else {
                $success["message"] = "Job Request not found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * remove job Request
     */
    public function removeJopRequest(Request $request)
    {
        try {
            $attributes = $request->only('id');
            
            $validator = Validator::make($attributes, [
                'id' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            $response = JobRequest::where('id', $request->id)->delete();

            if ($response) {
                $success["message"] = "Job Request deleted successfully!";
            } else {
                $success["message"] = "Job Request not found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }
}
