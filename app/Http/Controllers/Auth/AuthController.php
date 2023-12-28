<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponser;

    public function authenticate(Request $request)
    {
        $userData = $request->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:5'
        ]);

        if (!Auth::attempt($userData)) {
            return response()->json([
                'status' => 0,
                'message' => 'Las credenciales son incorrectas'
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken(time())->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => [
                'email' => $user->email,
                'position' => $user->position,
                'name' => $user->name,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'roles' => $user->roles
            ],
        ], 'Has iniciado sesion exitosamente');

    }

    public function generatePasswordResentLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users',
        ]);

        if ($validator->fails()) {
            $messages = implode('<br/>', $validator->messages()->all());
            return response()->json([
                'status' => 0,
                'errors' => $validator->messages(),
                'message' => $messages
            ]);
        }

        try{
            // Delete existing tokens
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            $token = Str::random(64);
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            $data = [
                'token' => $token
            ];

            Mail::to($request->email)->send(new ResetPasswordMail($data));

            return response()->json([
                'status' => 1,
                'message' => 'Correo electrónico enviado con exito'
            ]);
        } catch (Exception $e) {
            info($e);
            return response()->json([
                'status' => 0,
                'message' => 'Ocurrio un error, intente mas tarde'
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string'],
            'email' => ['required','string','email','max:255'],
            'password' => ['required', 'string'],
            'password_confirmation' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            $messages = implode('<br/>', $validator->messages()->all());
            return response()->json([
                'status' => 0,
                'errors' => $validator->messages(),
                'message' => $messages
            ]);
        }

        $token = $request->token;
        $maxTime = Carbon::now()->subHour();
        $valid = DB::table('password_reset_tokens')
                ->where('token', $token)
                ->where('email', $request->email)
                ->where('created_at', '>', $maxTime)
                ->first();

        if (!$valid) {
            return response()->json([
                'status' => 0,
                'message' => 'Token invalido o ya expiro. debes solicitar uno nuevo'
            ]);
        }

        if ($request->password != $request->password_confirmation) {
            return response()->json([
                'status' => 0,
                'message' => 'Las contraseñas no coinciden',
                'errors' => [
                    'password' => ['Las contraseñas no coinciden'],
                    'password_confirmation' => ['Las contraseñas no coinciden'],
                ]
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')
                ->where('token', $token)
                ->delete();

        return response()->json([
            'status' => 1,
            'message' => 'contraseña actualizada exitosamente'
        ]);
    }
}
