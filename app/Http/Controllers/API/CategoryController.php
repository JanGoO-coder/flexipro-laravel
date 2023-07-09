<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryController extends Controller
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
     * Add a new category
     */
    function addCategory(Request $request)   {
        try {
            $attributes = $request->only('name', 'description');

            $validator = Validator::make($attributes, [
                'name' => 'required',
                'description' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            $response = Category::create($attributes);
    
            $success["success"] = $response;

            if ($response) {
                $success["message"] = "New Category Added Successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * update a specific category
     */
    function updateCategory(Request $request) {
        try {
            $category_id = $request->id;

            $attributes = $request->only('id', 'name', 'description');

            $validator = Validator::make($attributes, [
                'id' => 'required',
                'name' => 'required',
                'description' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(), 'Validation Error', 422);
            }

            unset($attributes["id"]);

            $response = Category::where("id", $category_id)->update($attributes);
    
            if ($response) {
                $success["message"] = "Category Updated Successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * get all categories
     */
    public function getCategories(Request $request) {
        try {
            $categories = Category::get();

            $success["categories"] = $categories;

            if (isset($categories) && count($categories) > 0) {
                $success["message"] = "Total " . count($categories) . " categories retrieved Successfully!";
            } else {
                $success["message"] = "No categories found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * delete a specific category
     */
    function deleteCategory($id) {
        try {
            $response = Category::where('id',$id)->delete();
    
            $success["success"] = $response;

            if ($response) {
                $success["message"] = "Category deleted Successfully!";
            } else {
                $success["message"] = "Something went wrong!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }

    /**
     * get a specifc category
     */
    public function getCategoryById($id) {
        try {
            $response = Category::where('id',$id)->get();

            if (isset($response) && count($response) > 0) {
                $success["message"] = "Category data retrieved Successfully!";
            } else {
                $success["message"] = "Category Not Found!";
            }

            return response(['response'=>$success]);
        } catch (Exception $ex) {
            return $this->sendError([], $ex->getMessage(), 500);
        }
    }
}
