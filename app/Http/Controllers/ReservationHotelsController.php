<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Room;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class reservationHotelsController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'integer|max:100',
            'room_id' => 'required|integer|max:100',
            'from_date'=> 'date',
            'to_date'  => 'date',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
            $room_in= Reservation::where('room_id', Input::get('room_id'))->first();
        if(!$room_in) {
            $user = User::where('id', '=', Input::get('user_id'))->exists();
            if (Room::where('id', '=', Input::get('room_id'))->exists()) {
                if ($user) {
                    $input = $request->all();
                    $booking = Reservation::create($input);
                    $response['id'] = $booking->id;
                    $response['user id'] = $booking->user_id;
                    $response['room id'] = $booking->room_id;
                    $response['from_date'] = date('Y-m-d H:i:s');
                    return response()->json(['success' => $response], 200);
                } else {
                    $response = 'User doesn\'t exist';
                    return response()->json(['error' => $response], 422);
                }
            } else {
                $response = 'Room doesn\'t exist';
                return response()->json(['error' => $response], 422);
            }
        }else {
            $response = 'Room is reserved';
            return response()->json(['error' => $response], 422);

        }
    }

    public  function  release(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'integer|max:100',
            'room_id' => 'required|integer|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user=User::where('id', '=', Input::get('user_id'))->exists();
        $input_user= Reservation::where('user_id', Input::get('user_id'))->first();
        if (Room::where('id', '=', Input::get('room_id'))->exists()) {
            if($user ==$input_user ) {
                $input = $request->all();
                $booking = Reservation::create($input);
                $response['id'] = $booking->id;
                $response['user id'] = $booking->user_id;
                $response['room id'] = $booking->room_id;
                $response['to_date'] =  date('Y-m-d H:i:s');
                return response()->json(['success' => $response], 200);
            }
            else {
                $response = 'User doesn\'t exist';
                return response()->json(['error' => $response], 422);
            }
        }
        else {
            $response = 'User reserve Room with different number ,';
            return response()->json(['error' => $response], 422);
        }
    }
}
