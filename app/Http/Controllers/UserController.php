<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;

class UserController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Helper();
    }

    public function registration(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [

            'email' =>'required|unique:users|max:255',

            'username' =>'required',

            'password' => 'min:6',

            'password_confirmation' => 'required_with:password|same:password|min:6'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'error' => $validator->errors()
            ], 422);

        }
        
        $request['password'] = bcrypt($request->password);
        // unset($request['password_confirmation']); 
        
        $exceptFieldsArray = ['password_confirmation']; // prepare the data for create in helper
        $this->helper->create($request, 'User', '', '', '', $exceptFieldsArray);
        
        return response()->json([
            'message' => "Successfully user created!"
        ]);
    }

    public function getUser(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [

            'email' =>'required',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'error' => $validator->errors()
            ], 422);

        }

        $data = $this->helper->getUserByEmail('User', $request->email);

        return response()->json([
            'data' => $data
        ]);
    }

}
