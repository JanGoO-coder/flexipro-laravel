<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    function addRating(Request $request)   {
        
        $Rating = new Rating();
        $Rating->user_id = \Request::input('user_id');
        $Rating->company_id  = \Request::input('company_id');
        $Rating->count  = \Request::input('count');
        $Rating->save();
        return response()->json(['message'=>'Add Rating successfully']);
    }

    function updateRating()    {

        $id = \Request::input('id');
        $Rating = Rating::where('id',$id)
            ->update([
                'user_id' => \Request::input('user_id'),
                'company_id' => \Request::input('company_id'),
                'count' => \Request::input('count')
            ]);

        return response()->json(['Message' => 'Rating Updated']);

    }

    public function getRatings()   {

        $Rating = Rating::get();
        return response()->json(['Rating' => $Rating]);

    }

    function deleteRating($id)    {
        
        $Rating = Rating::where('id',$id)->delete();

        return response()->json(['message'=>'Delete Rating successfully']);

    }

    public function getRatingById($id)     {
        
        $Rating = Rating::where('id',$id)->get();

        return response()->json(['Rating' => $Rating]);

    }
}
