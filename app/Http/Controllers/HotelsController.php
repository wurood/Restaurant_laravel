<?php

namespace App\Http\Controllers;

use App\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Hotel as HotelResources;

class HotelsController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:25|unique:hotels',
            'rate' => 'required|integer|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $hotel = Hotel::create($input);
        $response['id'] = $hotel->id;
        $response['hotel_name'] = $hotel->name;
        $response['rate'] = $hotel->rate;
        return response()->json(['success' =>$response],200);
    }


    public function list()
    {
        //retuern all hotel
        $hotels = Hotel::all();
        return HotelResources::collection($hotels);
    }

    public function show($id)
    {
        //Get Hotel
        $hotels = Hotel::findOrFail($id);
        if(!(Hotel::where('id', '=',$id)->exists())){
            $response = 'Hotel doesn\'t exist';
            return response()->json(['error' => $response], 422);
        }
        $hotels_out= new HotelResources($hotels);
        $rooms = DB::table('rooms')
            ->select('rooms.*')
            ->where('hotel_id', $id)
            ->get();
        $response=array($rooms,$hotels_out);
        return response($response);
    }
}
