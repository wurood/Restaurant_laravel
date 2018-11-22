<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Reservation;
use App\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Resources\Room as RoomResources;

class RoomsController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|integer|max:100|unique:rooms',
            'hotel_id' => 'required|integer|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        if (Hotel::where('id', '=', Input::get('hotel_id'))->exists()) {
            $input = $request->all();
            $room = Room::create($input);
            $response['id'] = $room->id;
            $response['code'] = $room->code;
            $response['hotel id'] = $room->hotel_id;
            return response($response, 200);

        } else {
            $response = 'Hotel doesn\'t exist';
            return response()->json(['error'=>$response], 422);
        }

    }

    public function show($id)
    {

        $hotel = Hotel::where('id', '=', $id)->exists();
        $status = reservation::where('room_id', '=', $id)->exists();

        $response = DB::table('rooms')
            ->select('rooms.*')
            ->where('hotel_id', $id)
            ->get();

        if ($response->isEmpty()) {
            $response = "no rooms found !";
            return response()->json(['error' => $response], 422);
        }

        if ($hotel) {
            if ($status) {
                $response['status'] = "not available";
            } else {
                $response ['status'] = "available";
            }
        }

        return response()->json(['success' => $response], 200);
    }


}
