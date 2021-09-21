<?php

namespace App\Http\Controllers;

use App\Models\Paste;
use http\Env;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function create(Paste $paste): JsonResponse
    {
        //Validate Request
        try {
            $this->validate(request(), [
                'title' => 'required',
                'paste' => 'required',
                'isPrivate' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['validation'], 400);
        }

        try {
            $newPaste = $paste->create([
                'user_id' => request()->user()->id,
                'slug' => Str::random(8),
                'title' => request()->title,
                'paste' => request()->paste,
                'isPrivate' => request()->isPrivate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => 'Issue Creating Paste'
            ], 500);
        }


        return response()->json([
            'response' => 'Paste Created Successfully',
            'data' => $newPaste
        ], 200);

    }


    public function show(Paste $paste, $slug): JsonResponse
    {

        $payload = $paste->where('slug', $slug)->first();

        if (is_null($payload)) {
            return response()->json([
                'response' => 'not found'
            ], 404);
        }

        return response()->json([
            'response' => 'success',
            'data' => $payload
        ], 200);


    }


    public function latest(Paste $paste): JsonResponse
    {

        $payload = $paste->latest()->take(10)->get();

        if(is_null($payload)){
            return response()->json([
                'response' => 'not found'
            ], 404);
        }


        return response()->json([
            'response' => 'success',
            'data' => $payload
        ], 200);



    }


    public function getUsersPastes(Paste $paste): JsonResponse
    {

        $payload = $paste->where('user_id', '=', request()->user()->id)->latest()->get();


        if(is_null($payload)){
            return response()->json([
                'response' => 'not found'
            ], 404);
        }


        return response()->json([
            'response' => 'success',
            'data' => $payload
        ], 200);



    }



    public function update(Paste $paste, $slug): JsonResponse
    {
        // find Paste

        //$payload = $Paste->user()->where('slug', $slug)->firstOrFail()->toSQL();

        $payload = $paste->where('slug', $slug)->first();

        // check is auth user is equal to user_id

        if (request()->user()->id !== $payload['user_id']) {
            return response()->json(['unauthorized'], 401);
        }

        //validate

        try {
            $this->validate(request(), [
                'Paste' => 'required',
                'isPrivate' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['validation'], 400);
        }

        //commit

        $payload['Paste'] = request()->paste;
        $payload['isPrivate'] = request()->isPrivate;

        $payload->save();


        //send response
        return response()->json([
            'response' => 'success',
            'data' => $payload
        ], 200);
    }


    public function destroy(Paste $paste, $slug): JsonResponse
    {
        $payload = $paste->where('slug', $slug)->first();

        // check is auth user is equal to user_id

        if (request()->user()->id !== $payload['user_id']) {
            return response()->json(['unauthorized'], 401);
        }

        $payload->delete();


        return response()->json(['gone'], 410);
    }
}
