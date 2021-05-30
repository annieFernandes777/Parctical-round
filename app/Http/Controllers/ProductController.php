<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::with('productcategory')->get();

        return response()->json($product);
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
            'name' => 'required|unique:App\Models\Product,name',
            'price' => 'required',
            'category' => 'nullable',
           
        ]);

        if ($validator->fails()) {
            $responseArr = array();
            $responseArr['message'] = $validator->errors();
            $responseArr['token'] = '';
            return response()->json($responseArr);
        } else { 
            $check = Product::create(['name' => $request->name, 'price' => $request->price]);

            $pro_id = $check->id;
            if($request->category != '' ) {
                $category = explode(',', $request->category);

                foreach ($category as $val) {
                    $arr = array('pro_id' => $pro_id, 'cat_id' => $val);    
                    $check1 = ProductCategory::create($arr);
                }
            }
            
            $responseArr = array();
            if($check) {
                $responseArr['message'] = "Sucessfully Added Product.";
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
            'name' => 'required|unique:App\Models\Product,name,'.$id.',id',
            'price' => 'required',
            'category' => 'nullable',
           
        ]);

        if ($validator->fails()) {
            $responseArr = array();
            $responseArr['message'] = $validator->errors();
            $responseArr['token'] = '';
            return response()->json($responseArr);
        } else { 
            $check = Product::where('id', $id)->update(['name' => $request->name, 'price' => $request->price]);

            if($request->category != '' ) {
                $category = explode(',', $request->category);

                $res = ProductCategory::where('pro_id', $id)->delete();
                foreach ($category as $val) {

                    $arr = array('pro_id' => $id, 'cat_id' => $val);    
                        $check1 = ProductCategory::create($arr);
                }
            }
            
            $responseArr = array();
            if($check) {
                $responseArr['message'] = "Sucessfully Updated Product.";
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
            $check = Product::where('id', $id)->delete();

            if($check) {
                $check1 = ProductCategory::where('pro_id', $id)->delete();
                $responseArr['message'] = "Product Sucessfully Deleted";
            } else {
                $responseArr['message'] = "Please try again.";
            }
        } else {
            $responseArr['message'] = "Category Id required.";
        }
        return response()->json($responseArr);
    }
}
