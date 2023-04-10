<?php

namespace App\Http\Controllers;

use App\Http\Requests\Items\DeleteItemRequest;
use App\Http\Requests\Items\StoreItemRequest;
use App\Http\Requests\Items\UpdateItemRequest;
use App\Models\Colour;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $userId = $request->user()->id;
        $items = Item::with(['colours'])->where('user_id', $userId)->get();

        return $items;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        //
        $user = $request->user();
        $item = new Item();
        $item->user_id = $user->id;
        $item->title = $request->has('title') ? $request->post('title') : '';
        $item->description = $request->has('description') ? $request->post('description') : '';
        $item->save();

        $colourCodes = $request->input('colours');

        foreach ($colourCodes as $colourCode) {
            $colour = new Colour();
            $colour->colour = $colourCode;
            $colour->item_id = $item->id;

            $colour->save();
        }

        return response('success', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, string $id)
    {
        //
        $item = Item::find($id);
        $item->title = $request->has('title') ? $request->post('title') : '';
        $item->description = $request->has('description') ? $request->post('description') : '';

        $item->save();

        return response('success', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteItemRequest $request, string $id)
    {
        //
        $item = Item::find($id);
        $item->delete();

        return response('success', 200);
    }
}
