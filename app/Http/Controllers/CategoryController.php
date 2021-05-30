<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductCategory;
use Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::with('children')->where('parent_id', 0)->get();

        return response()->json($category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:App\Models\Category,name',
            'parent' => 'nullable|numeric',
           
        ]);

        if ($validator->fails()) {
            $responseArr = array();
            $responseArr['message'] = $validator->errors();
            $responseArr['token'] = '';
            return response()->json($responseArr);
        } else {
            $parent_id = 0;
            if($request->parent != '') {
                $parent_id = $request->parent;
            } 
            $check = Category::create(['name' => $request->name, 'parent_id' => $parent_id]);

            $responseArr = array();
            if($check) {
                $responseArr['message'] = "Sucessfully Added Category.";
            } else {
                $responseArr['message'] = "Please try again.";
            }

            return response()->json($responseArr);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:App\Models\Category,name,'.$id.',id',
            'parent' => 'nullable|numeric',
           
        ]);

        if ($validator->fails()) {
            $responseArr = array();
            $responseArr['message'] = $validator->errors();
            $responseArr['token'] = '';
            return response()->json($responseArr);
        } else {
            $parent_id = 0;
            if($request->parent != '') {
                $parent_id = $request->parent;
            } 
            $check = Category::where('id', $id)->update(['name' => $request->name, 'parent_id' => $parent_id]);

            $responseArr = array();
            if($check) {
                $responseArr['message'] = "Category Sucessfully Updated";
            } else {
                $responseArr['message'] = "Please try again.";
            }

            return response()->json($responseArr);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $responseArr = array();
        if($id != '') {
            $check = Category::where('id', $id)->delete();
            if($check) {
                $check1 = ProductController::where('cat_id', $id)->delete();
                $responseArr['message'] = "Category Sucessfully Deleted";
            } else {
                $responseArr['message'] = "Please try again.";
            }
        } else {
            $responseArr['message'] = "Category Id required.";
        }
        return response()->json($responseArr);
    }
}
