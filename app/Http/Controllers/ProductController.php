<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;

class ProductController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Helper();
    }

    public function showProduct(Request $request)
    {
        $relationship = ['user', 'category'];
        $data = $this->helper->show("Product", $relationship, '', $request->page);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function showSingleProduct(Request $request)
    {
        $relationship = ['user', 'category'];
        $data = $this->helper->show("Product", $relationship, $request->id, '', true);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function createProduct(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [

            'name' => 'required',

            'price' => 'required|numeric',

            'user_id' => 'required|exists:users,id',

            'category_id' => 'required|exists:product_categories,id',

            // 'image_url' => 'mimes:jpeg,jpg,png,gif|sometimes|max:10000'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        if($request->hasFile("image_url")){
            $this->helper->create($request, 'Product', true, ['image_url'], './storage/uploads',['image_url']);
        }else{
            $this->helper->create($request, 'Product', '', '', '', ['image_url']);
        }

        return response()->json([
            'message' => "Successfully created product!"
        ]);
    }


    public function showCategories(Request $request)
    {
        $data = $this->helper->show("ProductCategory", '', '', $request->page, true);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function createProductCategories(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [

            'name' => 'required|unique:product_categories',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $this->helper->create($request, 'ProductCategory');

        return response()->json([
            'message' => "Successfully created product category!"
        ]);
    }


    public function delete(Request $request)
    {
        $message = $this->helper->delete($request->id, "Product");

        return response()->json([
            'message' => $message
        ]);
    }

    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'id' => 'required',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }
        if($request->hasFile("image_url")){
            $this->helper->update($request, $request->id, "Product", ['image_url'], true, ['image_url'], './storage/uploads');
        }else{
            $this->helper->update($request, $request->id, "Product", ['image_url']);
        }

        return response()->json([
            'message' => "Successfully Updated the product!"
        ]);
    }

    public function testUpload()
    {
        return true;
    }
}
