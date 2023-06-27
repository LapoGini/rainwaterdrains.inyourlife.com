<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Street;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Utils\Functions;

use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{

    public function getAll() 
    {
        $items = Item::with('street', 'street.city', 'tags', 'user')->orderBy('id', 'DESC')->get();
        
        return Functions::setResponse($items, 'Operazioni non trovate');
    }

    public function getByCityId(int $city_id) 
    {
        $items = Street::with('street', 'street.city', 'tags', 'user')->whereHas('street.city', function (Builder $query) use ($city_id) {
            $query->where('id', '=', $city_id);
        })->get();
        
        return Functions::setResponse($items, 'Strade non trovate');
    }

    public function getByStreetId(int $street_id) 
    {
        $items = Street::with('street', 'street.city', 'tags', 'user')->whereHas('street', function (Builder $query) use ($street_id) {
            $query->where('id', '=', $street_id);
        })->get();
        
        return Functions::setResponse($items, 'Strade non trovate');
    }

    public function getByUserId(int $user_id) 
    {
        $items = Street::with('street', 'street.city', 'tags', 'user')->whereHas('user', function (Builder $query) use ($user_id) {
            $query->where('id', '=', $user_id);
        })->get();
        
        return Functions::setResponse($items, 'Strade non trovate');
    }

    public function getByTagId(int $tag_id) 
    {
        $items = Street::with('street', 'street.city', 'tags', 'user')->whereHas('tag', function (Builder $query) use ($tag_id) {
            $query->where('id', '=', $tag_id);
        })->get();
        
        return Functions::setResponse($items, 'Strade non trovate');
    }


    public function set(Request $request) 
    {
        $user = Auth::guard('api')->user();
        $data = $request->all();
        
        $validator = Validator::make($data,[
            'latitude'=> 'required|numeric',
            'longitude'=> 'required|numeric',
            'altitude'=> 'required|numeric',
            'accuracy'=> 'required|numeric',
            'pic' => 'string|nullable',
            'note' => 'string|nullable',
            'height'=> 'required|numeric',
            'width'=> 'required|numeric',
            'depth'=> 'required|numeric',
            'street_id'=> 'required|numeric',
            'tagsIds' => 'array'
        ]);

        if($validator->fails()){
            return response()->json(['validation_errors' => $validator->messages()],201);
        }
        
       
        $street = Street::find($data['street_id']); 
        
        if($street) {
            $item = Item::make($data);
            $item->street()->associate($street);
            $item->user()->associate($user);
            $item->save();
        }
        if(isset($data['tagsIds'])){
            $item->tags()->sync($data['tagsIds']);
        }

        return response()->json(['success' => $item], 200);
    }
}
