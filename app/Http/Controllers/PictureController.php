<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Picture;
use App\Http\Requests\StorePictureRequest;
use Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Log;

class PictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Picture/Index', [
            'pictures' => Picture::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Picture/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePictureRequest $request)
    {
        $extension = $request->file->getClientOriginalExtension();

        //$foo = File::extension($request->file);

        $now = Carbon\Carbon::now();
        $now->toDateTimeString();
        $fileName = $now->format('Y-m-d') . "_" . $now->format('his') . "." . $extension;
        $request->file->storeAs('public/pictures/', $fileName);


        Picture::create($request->validated() + [
            'file' => $fileName,
        ]);

        return Redirect::route('pictures.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function guessPictures()
    {

        $pictures = Picture::inRandomOrder()->get();

        return Inertia::render('Picture/Guess', [
            'pictures' => $pictures,
        ]);
    }


    public function load()
    {

        $type = "Object";

        $files = Storage::files("public/load");

        $i = 0;

        foreach ($files as $key => $value) {
            $i++;

            try {

                $extension = pathinfo($value, PATHINFO_EXTENSION);

                if ($extension == "jpeg" || $extension == "jpg" || $extension == "png" || $extension == "webp") {
                    $file = basename($value);

                    Storage::move($value, 'public/pictures/'  . $file);

                    $picture = new Picture;

                    $picture->name = $file;
                    $picture->type = $type;
                    $picture->file = $file;
                    $picture->save();
                }
            } catch (\Throwable $th) {
                //throw $th;
            }

            Log::debug($value);
        }
        dd($files);
    }
}
