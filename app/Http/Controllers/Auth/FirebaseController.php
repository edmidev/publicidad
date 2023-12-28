<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    public function storeFirebaseToken(Request $request)
    {
        $user = $request->user();
        $user->device_key = $request->token;
        $user->save();

        return response()->json(['Token successfully stored.']);
    }
}
