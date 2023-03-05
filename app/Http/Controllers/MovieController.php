<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Movie;
use App\Models\Log;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\Storage;


class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        return Inertia::render('Movie/Index', [
            'movies' => Movie::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        return Inertia::render('Movie/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMovieRequest $request)
    {
        
        $extension = $request->file->getClientOriginalExtension();

        //$foo = File::extension($request->file);
        
            $now = Carbon\Carbon::now();
            $now->toDateTimeString();
            $fileName = $now->format('Y-m-d') . "_" . $now->format('his') . "." . $extension;
            $request->file->storeAs('public/movies/', $fileName);
        

        Movie::create($request->validated() + [
            'file' => $fileName,
        ]);

        return Redirect::route('movies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $movies = Movie::inRandomOrder()->get();
        
        return Inertia::render('Movie/Show', [
            //'movies' => Movie::all(),
            'movies' => $movies,
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
        

        $movie = Movie::find($id);

        return Inertia::render('Movie/Edit', [
            'movie' => $movie,
        ]);
    }


    public function update(UpdateMovieRequest $request, Movie $movie)
    {
       
        if ($request->hasFile('image')) {
            $now = Carbon\Carbon::now();
            $now->toDateTimeString();
            $imgName = $now->format('Y-m-d') . "_" . $now->format('his') . ".png";
            $request->image->storeAs('public/images/movies/', $imgName);
        } else {
            $imgName = $movie->image;
        }

        if ($request->has('id')) {

            Movie::find($request->input('id'))->update($request->validated() + [
                'image' => $imgName,
                'notes' => $request->notes,
            ]);
        }

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->event = "editar locomotora";
        $log->message = Auth::user()->name . " (ID " . Auth::user()->id . ") ha editado locomotora " . $movie->name . " (ID " . $movie->id . ")";
        $log->save();

        return Redirect::route('movies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       

        $movie = Movie::find($id);
        $movie->delete();

        $log = new Log;
        $log->user_id = Auth::user()->id;
        $log->event = "eliminar locomotora";
        $log->message = Auth::user()->name . " (ID " . Auth::user()->id . ") ha eliminado locomotora " . $movie->name . " (ID " . $movie->id . ")";
        $log->save();

        return Redirect::route('movies.index');
    }




    public function guessMovies()
    {

        $movies = Movie::inRandomOrder()->get();
        
        return Inertia::render('Movie/Guess', [
            //'movies' => Movie::all(),
            'movies' => $movies,
        ]);
       
    }


    public function loadMovies(){
       
        $files = Storage::files("public/load/movies");
        
        foreach ($files as $key => $value) {
          

            try {
                      
                    $file = basename($value);

                    Storage::move($value, 'public/movies/'  . $file);

                    $movie = new Movie;
                    
                    $movie->name =  "MOVIE"; 
                   
                    $movie->file = $file;
                    $movie->save();
                
            } catch (\Throwable $th) {
                throw $th;
            }

           // Log::debug($value);
        }
        dd($files);
    }


    public function saveMovieInfo(Request $request){

        $movie = Movie::find($request->params["id"]);
        $movie->name = $request->params["name"];       
        $movie->save();

        return response()->json([
            "ok"
        ]);


    }
}





