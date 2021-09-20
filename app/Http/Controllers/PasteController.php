<?php

namespace App\Http\Controllers;

use App\Models\Paste;
use http\Env;
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


    public function create(Paste $paste)
    {
        //Validate Request
        try {
            $this->validate(request(), [
                'Paste' => 'required',
                'isPrivate' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['validation'], 400);
        }

        try {
            $newPaste = $paste->create([
                'user_id' => request()->user()->id,
                'slug' => Str::random(8),
                'Paste' => request()->paste,
                'isPrivate' => request()->isPrivate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => 'Issue Creating Paste'
            ], 500);
        }

        // Include new URL in data payload
        $newPaste['url'] = env('APP_URL') . '/Paste/' . $newPaste['slug'];
        $newPaste['api_ref'] = env('APP_URL') . '/api/Paste/' . $newPaste['slug'];

        return response()->json([
            'response' => 'Paste Created Successfully',
            'data' => $newPaste
        ], 200);

    }


    public function show(Paste $paste, $slug)
    {

            $payload = $paste->where('slug', $slug)->first();

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

    public function update(Paste $paste, $slug)
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


    public function destroy(Paste $paste, $slug)
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
