<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Track;
use App\Models\Log;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreTrackRequest;
use App\Http\Requests\UpdateTrackRequest;
use Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\File;


class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        return Inertia::render('Track/Index', [
            'tracks' => Track::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        return Inertia::render('Track/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrackRequest $request)
    {
        
        $extension = $request->file->getClientOriginalExtension();

        //$foo = File::extension($request->file);
        
            $now = Carbon\Carbon::now();
            $now->toDateTimeString();
            $fileName = $now->format('Y-m-d') . "_" . $now->format('his') . "." . $extension;
            $request->file->storeAs('public/tracks/', $fileName);
        

        Track::create($request->validated() + [
            'file' => $fileName,
        ]);

        return Redirect::route('tracks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $tracks = Track::inRandomOrder()->get();
        
        return Inertia::render('Track/Show', [
            //'tracks' => Track::all(),
            'tracks' => $tracks,
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
        

        $track = Track::find($id);

        return Inertia::render('Track/Edit', [
            'track' => $track,
        ]);
    }


    public function update(UpdateTrackRequest $request, Track $track)
    {
       
        if ($request->hasFile('image')) {
            $now = Carbon\Carbon::now();
            $now->toDateTimeString();
            $imgName = $now->format('Y-m-d') . "_" . $now->format('his') . ".png";
            $request->image->storeAs('public/images/tracks/', $imgName);
        } else {
            $imgName = $track->image;
        }

        if ($request->has('id')) {

            Track::find($request->input('id'))->update($request->validated() + [
                'image' => $imgName,
                'notes' => $request->notes,
            ]);
        }

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->event = "editar locomotora";
        $log->message = Auth::user()->name . " (ID " . Auth::user()->id . ") ha editado locomotora " . $track->name . " (ID " . $track->id . ")";
        $log->save();

        return Redirect::route('tracks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       

        $track = Track::find($id);
        $track->delete();

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->event = "eliminar locomotora";
        $log->message = Auth::user()->name . " (ID " . Auth::user()->id . ") ha eliminado locomotora " . $track->name . " (ID " . $track->id . ")";
        $log->save();

        return Redirect::route('tracks.index');
    }




    public function guessSongs()
    {

        $tracks = Track::inRandomOrder()->get();
        
        return Inertia::render('Track/Guess', [
            //'tracks' => Track::all(),
            'tracks' => $tracks,
        ]);
       
    }
}
