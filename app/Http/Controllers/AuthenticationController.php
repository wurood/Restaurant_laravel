<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request)
    {
        $user = User::where('name', $request->name)->first();

        if ($user) {

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = (array('userId' => $request->id, 'username' => $request->name, 'token' => $token));
                return response($response, 200);
            } else {
                $response = 'Password mismatch';
                return response()->json(['error' => $response], 422);
            }
        } else {
            $response = 'User doesn\'t exist';
            return response()->json(['error' => $response], 422);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:25|unique:users',
            'email' => 'email|string|max:255|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required|min:6|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();$input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->save();
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['username '] = $user->name;
        $success['id'] = $user->id;
        return response()->json(['success' => $success], $this->successStatus);
    }
}
