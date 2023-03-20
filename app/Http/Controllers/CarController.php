<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Car;
use App\Models\Log;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Storage;


class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return Inertia::render('Car/Index', [
            'cars' => Car::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        return Inertia::render('Car/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarRequest $request)
    {

        $extension = $request->file->getClientOriginalExtension();

        //$foo = File::extension($request->file);

        $now = Carbon\Carbon::now();
        $now->toDateTimeString();
        $fileName = $now->format('Y-m-d') . "_" . $now->format('his') . "." . $extension;
        $request->file->storeAs('public/cars/', $fileName);


        Car::create($request->validated() + [
            'file' => $fileName,
        ]);

        return Redirect::route('cars.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $cars = Car::inRandomOrder()->get();

        return Inertia::render('Car/Show', [
            //'cars' => Car::all(),
            'cars' => $cars,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $car = Car::find($id);

        return Inertia::render('Car/Edit', [
            'car' => $car,
        ]);
    }


    public function update(UpdateCarRequest $request, Car $car)
    {

        if ($request->hasFile('image')) {
            $now = Carbon\Carbon::now();
            $now->toDateTimeString();
            $imgName = $now->format('Y-m-d') . "_" . $now->format('his') . ".png";
            $request->image->storeAs('public/images/cars/', $imgName);
        } else {
            $imgName = $car->image;
        }

        if ($request->has('id')) {

            Car::find($request->input('id'))->update($request->validated() + [
                'image' => $imgName,
                'notes' => $request->notes,
            ]);
        }

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->event = "editar locomotora";
        $log->message = Auth::user()->name . " (ID " . Auth::user()->id . ") ha editado locomotora " . $car->name . " (ID " . $car->id . ")";
        $log->save();

        return Redirect::route('cars.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        $car = Car::find($id);
        $car->delete();

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->event = "eliminar locomotora";
        $log->message = Auth::user()->name . " (ID " . Auth::user()->id . ") ha eliminado locomotora " . $car->name . " (ID " . $car->id . ")";
        $log->save();

        return Redirect::route('cars.index');
    }




    public function guessCars()
    {

        $cars = Car::inRandomOrder()->get();

        return Inertia::render('Car/Guess', [
            //'cars' => Car::all(),
            'cars' => $cars,
        ]);
    }


    public function loadCars()
    {

        $files = Storage::files("public/load/cars");
        foreach ($files as $key => $value) {

            try {

                $file = basename($value);
                Storage::move($value, 'public/cars/'  . $file);
                $car = new Car;
                $car->name =  $file;
                $car->file = $file;
                $car->save();
            } catch (\Throwable $th) {
                throw $th;
            }

            // Log::debug($value);
        }
        dd($files);
    }


    public function saveCarInfo(Request $request)
    {

        $car = Car::find($request->params["id"]);
        $car->name = $request->params["name"];
        $car->save();

        return response()->json([
            "ok"
        ]);
    }
}
