<?php

namespace App\Http\Controllers;

use App\Models\FailedJob;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FailedJobController extends Controller
{
    function addFailedJob(Request $request)     {

        $Fjob = new FailedJob();
        $Fjob->connection = \Request::input('connection');
        $Fjob->queue  = \Request::input('queue');
        $Fjob->payload = \Request::input('payload');
        $Fjob->exception  = \Request::input('exception');
        $Fjob->save();

        return response()->json(['message'=>'Add Failed Job successfully']);

    }

    function updateFailedJob()     {
        $id = \Request::input('id');
        $Fjob = FailedJob::where('id',$id)
            ->update([
                'connection' => \Request::input('connection'),
                'queue' => \Request::input('queue'),
                'payload' => \Request::input('payload'),
                'exception' => \Request::input('exception')
            ]);

        return response()->json(['Message' => 'Failed Job Updated']);

    }

    public function getFailedJobs()    {

        $Fjob = FailedJob::get();
        return response()->json(['FailedJobs' => $Fjob]);

    }

    function deleteFailedJobs($id)     {
        
        $Fjob = FailedJob::where('id',$id)->delete();

        return response()->json(['message'=>'delete Failed Job successfully']);

    }

    public function getFailedJobsById($id)    {

        $Fjob = FailedJob::
        where('id',$id)->get();
        
        return response()->json(['FailedJobs' => $Fjob]);

    }
}
