<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Photo::latest('id')->get();
        return response()->json($products);
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
            'photos'=> 'required',
            'photos.*'=>'file|mimes:jpg,png,jpeg|max:2000',
            'product_id'=> 'required|exists:products,id',
        ]);

        foreach ($request->file('photos') as $photo){
            $newName = $photo->store('public');
            Photo::create([
                'photo'=>$newName,
                'product_id'=>$request->product_id,
            ]);
        }
        return response()->json(['message'=>'photo is uploaded']);
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
        $photo = Photo::find($id);
        if(is_null($photo)){
            return response()->json(['message'=>'photo is not found'], 404);
        }
        Storage::delete($photo);
        $photo->delete();
        return response()->json(['message'=>'photo is deleted'], 204);

    }
}
