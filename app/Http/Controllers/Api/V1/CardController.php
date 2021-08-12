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
     * @param Card $card
     * @return CardResource
     */
    public function show(Card $card)
    {
        return new CardResource($card);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CardStoreRequest $request
     * @param Card $card
     * @return CardResource
     */
    public function update(CardStoreRequest $request, Card $card): CardResource
    {
        $card->update($request->validated());
        return new CardResource($card);
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
