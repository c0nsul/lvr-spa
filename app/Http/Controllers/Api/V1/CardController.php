<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardStoreRequest;
use App\Http\Resources\CardResource;
use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Http\Response;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CardStoreRequest $request
     * @return CardResource
     */
    public function store(CardStoreRequest $request): CardResource
    {
        $newDesk = Card::create($request->validated());
        return new CardResource($newDesk);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Card $card
     * @return Response
     */
    public function destroy(Card $card): Response
    {
        $card->delete();
        return \response(null, Response::HTTP_NO_CONTENT);
    }
}
