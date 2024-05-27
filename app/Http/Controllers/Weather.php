<?php

namespace App\Http\Controllers;

use App\Models\weather as ModelsWeather;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Weather extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['script']       = "weather.script.index";
        return $this->renderTo("weather.index");
    }

    public function get_weather_data(Request $request)
    {
        $request->validate([
            'longitude'     => ['required'],
            'latitude'     => ['required']
        ]);
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=$request->latitude&lon=$request->longitude&units=metric&appid=" . env('OPENWEATHER_APPID');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($curl, CURLOPT_HTTPGET, true); // Use GET method
        $response = curl_exec($curl);
        curl_close($curl);
        if ($response === false) {
            $error = curl_error($curl);
            return response()->json([
                'status'        => 'Failed',
                'message'       => $error
            ], 404);
        } else {
            $response = json_decode($response);

            $weather_data = (object) [];

            if (key_exists('coord', (array) $response)) {
                $weather_data->location_name = $response->name;
                $weather_data->coord = $response->coord;

                $timezone = $response->timezone / 3600;
                if ($timezone < 0)
                    $timezone = "UTC" . $timezone;
                else
                    $timezone = "UTC+" . $timezone;
                $weather_data->timezone = $timezone;
                $weather_data->humidity = $response->main->humidity;
                $weather_data->pressure = $response->main->pressure;
                $weather_data->wind_speed = $response->wind->speed;

                return response()->json([
                    'status'        => 'Success',
                    'message'       => 'Successfully retrieve data',
                    'data'          => [
                        'weather_data' => $weather_data
                    ]
                ], 201);
            } else {
                return response()->json([
                    'status'        => 'Failed',
                    'message'       => $response->message,
                ], 401);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_name'         => ['required', 'max:255'],
            'latitude'              => ['required', 'max:255'],
            'longitude'             => ['required', 'max:255'],
            'timezone'              => ['required', 'max:255'],
            'pressure'              => ['required', 'numeric', 'between:0,9999999999.99'],
            'humidity'              => ['required', 'numeric', 'between:0,9999999999.99'],
            'wind_speed'            => ['required', 'numeric', 'between:0,9999999999.99'],
        ]);

        DB::beginTransaction();
        try {
            $weather        = ModelsWeather::where('latitude', $request->latitude)->where('longitude', $request->longitude)->first();
            if ($weather !== null) {
                ModelsWeather::where('latitude', $request->latitude)->where('longitude', $request->longitude)
                    ->update([
                        "timezone"      => $request->timezone,
                        "pressure"      => $request->pressure,
                        "humidity"      => $request->humidity,
                        "wind_speed"    => $request->wind_speed,
                    ]);
                DB::commit();

                return response()->json([
                    'status' => 'Success',
                    'message'   => "Successfully update weather data"
                ], 200);
            } else {
                ModelsWeather::create([
                    "location_name"     => $request->location_name,
                    "latitude"          =>  $request->latitude,
                    "longitude"         => $request->longitude,
                    "timezone"          =>  $request->timezone,
                    "pressure"          =>  $request->pressure,
                    "humidity"          =>  $request->humidity,
                    "wind_speed"        =>    $request->wind_speed,
                ]);
                DB::commit();
                return response()->json([
                    'status' => 'Success',
                    'message'   => "Successfully insert weather data"
                ], 201);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'Failed',
                'message'   => "Transaction failed: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $query          = ModelsWeather::select("*");
        return DataTables::of($query)
            ->addColumn('latlong', function ($query) {
                return '<a target="_blank"
                href="https://www.google.com/maps/search/?api=1&query=' . $query->latitude . '%2C' . $query->longitude . '"><i
                    class="fa-solid fa-up-right-from-square"></i> ' . $query->latitude . ', ' . $query->longitude . '</a>';
            })
            ->addColumn('action', function ($query) {
                return '
                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                    <button onclick="deleteWeather(' . $query->id . ')" class="btn btn-outline-danger">Delete</button>
                </div>
                ';
            })
            ->editColumn('updated_at', function ($query) {
                return date('d M Y H:i:s', strtotime($query->created_at));
            })
            ->editColumn('courier_name', function ($query) {
                return
                    '<div class="text-center">
                ' . $query->courier_name . ' <br>
                <a href="tel:' . $query->courier_phone . '" target="_blank">' . $query->courier_phone . '</a>
                </div>';
            })
            ->rawColumns(['latlong', 'action'])
            ->removeColumn(['id'])
            ->make(true);
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
        //
    }

    public function destroy(Request $request)
    {
        $weather        = ModelsWeather::find($request->id);
        if ($weather === null) {
            return response()->json([
                'status' => 'Failed',
                'message'   => "No data found"
            ], 404);
        }
        DB::beginTransaction();
        try {

            ModelsWeather::where('id', $request->id)->delete();
            DB::commit();
            return response()->json([
                'status' => 'Success',
                'message'   => "Successfully delete weather data"
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'Failed',
                'message'   => "Transaction failed: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function api_destroy($id)
    {
        $weather        = ModelsWeather::find($id);
        if ($weather === null) {
            return response()->json([
                'status' => 'Failed',
                'message'   => "No data found"
            ], 404);
        }
        DB::beginTransaction();
        try {

            ModelsWeather::where('id', $id)->delete();
            DB::commit();
            return response()->json([
                'status' => 'Success',
                'message'   => "Successfully delete weather data"
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'Failed',
                'message'   => "Transaction failed: " . $e->getMessage()
            ], 500);
        }
    }

    public function api_show()
    {
        $weathers           = ModelsWeather::all();
        if ($weathers->isEmpty()) {
            return response()->json([
                'status'            => 'Failed',
                'message'           => 'Weathers data not found',
            ], 404);
        }

        return response()->json([
            'status'            => 'Success',
            'message'           => 'Weather data retrieved',
            'data'              => [
                'weathers'      => ModelsWeather::all()
            ]
        ]);
    }
}
