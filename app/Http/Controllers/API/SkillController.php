<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SkillController extends Controller
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
     * Get Skills of a specific user account
     */
    public function getUserSkills(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $user_id = $user->id;

            $skills = Skill::where('user_id', $user_id)->get();

            if (isset($skills) && count($skills) > 0) {
                $success["skills"] = $skills;
                $success["message"] = "Total " . count($skills) . " Skills Found!";
            } else {
                $success["skills"] = [];
                $success["message"] = "No Skills Found Yet!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * Add new skill of a specific user account
     */
    public function addUserSkill(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $user_id = $user->id;

            $response = Skill::create([
                "name" => $request->name,
                "user_id" => $user_id
            ]);

            $success["success"] = $response;

            if ($response) {
                $success["message"] = "New Skill added successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * remove specific skill of a specific user account
     */
    public function removeUserSkill(Request $request)
    {
        try {
            $user = JWTAuth::user();
            $user_id = $user->id;

            $response = Skill::where('id', $request->id)->delete();

            $success["success"] = $response;

            if ($response) {
                $success["message"] = "Skill removed successfully!";
            } else {
                $success["message"] = "Skill not found! please send valid skill id";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }
}
