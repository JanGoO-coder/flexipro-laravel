<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    function addJob(Request $request)   {
        
        $job = new Job();
        $job->company_id = \Request::input('company_id');
        $job->job_title  = \Request::input('job_title');
        $job->job_description = \Request::input('job_description');
        $job->budget  = \Request::input('budget');
        $job->duration_days  = \Request::input('duration_days');
        $job->category_id  = \Request::input('category_id');
        $job->save();
        return response()->json(['message'=>'Add Job successfully']);
    }

    function updateJob()    {

        $id = \Request::input('id');
        $job = Job::where('id',$id)
            ->update([
                'company_id' => \Request::input('company_id'),
                'job_title' => \Request::input('job_title'),
                'job_description' => \Request::input('job_description'),
                'budget' => \Request::input('budget'),
                'duration_days' => \Request::input('duration_days'),
                'category_id' => \Request::input('category_id')
            ]);

        return response()->json(['Message' => 'Job Updated']);

    }

    public function getJobs()   {

        $job = Job::get();
        return response()->json(['jobs' => $job]);
    }

    function deleteJobs($id)    {
        
        $job = Job::where('id',$id)->delete();

        return response()->json(['message'=>'delete job successfully']);

    }

    public function getJobById($id)     {
        
        $job = Job::
        where('id',$id)->get();
        return response()->json(['job' => $job]);

    }
}
