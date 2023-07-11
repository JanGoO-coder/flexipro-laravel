<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }

    /**
     * Register New User and Generate JWT Token
     */
    public function register(Request $request)
    {
        try {
            $registeration_input = $request->only('first_name', 'last_name', 'email', 'password', 'c_password', 'user_role');
            $authentication_input = $request->only('email', 'password');

            $validator = Validator::make($registeration_input, [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'c_password' => 'required|same:password',
                'user_role' => 'required|in:company,employee',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            $registeration_input['password'] = bcrypt($registeration_input['password']);
            $user = User::create($registeration_input);

            if (! $token = JWTAuth::attempt($authentication_input)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $success['user'] = $user;
            $success['token'] = $token;

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $validator = Validator::make($credentials, [
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * Get the authenticated User.
     */
    public function profile(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->sendError([], "user not found", 403);
            }
        } catch (JWTException $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }

        return $this->sendResponse($user, "user data retrieved", 200);
    }

    /**
     * Get the authenticated User.
     */
    public function editProfile(Request $request)
    {
        try {
            $profile_fields = $request->only('first_name', 'last_name', 'about', 'gender', 'experience');

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->sendError([], "user not found", 403);
            }

            $validator = Validator::make($profile_fields, [
                'first_name' => 'required',
                'last_name' => 'required',
                'about' => 'required',
                'gender' => 'required|in:male,female',
                'experience' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            User::where('id', $user->id)->update($profile_fields);

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->sendError([], "user not found", 403);
            }
        } catch (JWTException $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }

        return $this->sendResponse($user, "profile updated successfully!", 200);
    }

    /**
     * refresh token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function refresh(Request $request)
    {
        try{
            $token = JWTAuth::getToken();
            if(!$token){
                return $this->sendError([], "token not found", 403);
            }
            $token = JWTAuth::refresh($token);
            $success["token"] = $token;
            $success["message"] = "token refreshed";

            return response(['response'=>$success]);
        } catch (JWTException $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function getAllUsers()
    {
        $user = User::get();
        return response()->json(['Users' => $user]);
    }
}
