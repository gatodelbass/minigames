<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Card;
use App\Models\Serie;
use App\Models\Log;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use Carbon;
use Illuminate\Support\Facades\Auth;
use App\Utilities\Helper;


class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Auth::user()->userCan("locomotoras ver");

        return Inertia::render('Card/Index', [
            'cards' => Card::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Card/Create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCardRequest $request)
    {

        $imgName = "";

        if ($request->hasFile('image')) {
            $now = Carbon\Carbon::now();
            $now->toDateTimeString();
            $imgName = $now->format('Y-m-d') . "_" . $now->format('his') . ".png";
            $request->image->storeAs('public/images/cards/', $imgName);

            switch ($request->rarity) {
                case '1':
                    $power = rand(100, 499);
                    break;
                case '2':
                    $power = rand(500, 999);
                    break;
                case '3':
                    $power = rand(1000, 4999);
                    break;
                case '4':
                    $power = rand(5000, 9999);
                    break;
                case '5':
                    $power = rand(10000, 49999);
                    break;
                case '6':
                    $power = rand(50000, 99999);
                    break;
                case '7':
                    $power = rand(100000, 499999);
                    break;
                default:
                    $power = 100;
                    break;
            }

            $power = $power * 100;


            Card::create($request->validated() + [
                'image' => $imgName,
                'power' => $power,
            ]);
        }



        return Redirect::route('cards.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //Auth::user()->userCan("locomotoras ver");

        $card = Card::find($id);

        if ($request->wantsJson()) {
            return response()->json([
                'selectedCard' => $card,
            ]);
        }

        return Inertia::render('Card/Show', [
            'card' => $card,
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
        $card = Card::find($id);

        return Inertia::render('Card/Edit', [
            'card' => $card,

        ]);
    }


    public function update(StoreCardRequest $request, Card $card)
    {

        if ($request->hasFile('image')) {
            $now = Carbon\Carbon::now();
            $now->toDateTimeString();
            $imgName = $now->format('Y-m-d') . "_" . $now->format('his') . ".png";
            $request->image->storeAs('public/images/cards/', $imgName);
        } else {
            $imgName = $card->image;
        }

        if ($request->has('id')) {

            switch ($request->rarity) {
                case '1':
                    $power = rand(100, 499);
                    break;
                case '2':
                    $power = rand(500, 999);
                    break;
                case '3':
                    $power = rand(1000, 4999);
                    break;
                case '4':
                    $power = rand(5000, 9999);
                    break;
                case '5':
                    $power = rand(10000, 49999);
                    break;
                case '6':
                    $power = rand(50000, 99999);
                    break;
                case '7':
                    $power = rand(100000, 499999);
                    break;
                default:
                    $power = 100;
                    break;
            }

            $power = $power * 100;



            Card::find($request->input('id'))->update($request->validated() + [
                'image' => $imgName,
                'power' => $power,

            ]);
        }


        return Redirect::route('cards.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Auth::user()->userCan("locomotoras eliminar");

        $card = Card::find($id);
        $card->delete();



        return Redirect::route('cards.index');
    }

    public function setRarity(Request $request)
    {
        //dd($request->request);

        $card = Card::find($request->cardId);

        switch ($request->rarity) {
            case '1':
                $power = rand(100, 499);
                break;
            case '2':
                $power = rand(500, 999);
                break;
            case '3':
                $power = rand(1000, 4999);
                break;
            case '4':
                $power = rand(5000, 9999);
                break;
            case '5':
                $power = rand(10000, 49999);
                break;
            case '6':
                $power = rand(50000, 99999);
                break;
            case '7':
                $power = rand(100000, 499999);
                break;
            default:
                $power = 100;
                break;
        }

        $power = $power * 100;

        $card->rarity = $request->rarity;
        $card->power = $power;
        $card->save();



        return response()->json([
            'cards' => Card::all(),
        ]);
    }

    public function setVisualFilter(Request $request)
    {
        //dd($request->request);

        $card = Card::find($request->cardId);
        $card->visual_filter = $request->filter;
        $card->save();



        return response()->json([
            'cards' => Card::all(),
        ]);
    }



    public function getRandomCards($numberOfPlayers)
    {
        $cards = Card::inRandomOrder()->limit($numberOfPlayers)->get();

        return response()->json([
            'cards' => $cards,
        ]);
    }
}
