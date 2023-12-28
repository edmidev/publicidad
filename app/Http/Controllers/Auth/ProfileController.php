<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            /** Validacion de datos */
            $validator = Validator::make($request->all(), [
                'email' => $request->email != $user->email ? ['required', 'string', 'max:255', 'unique:users'] : ''
            ]);

            if ($validator->fails()) {
                $messages = implode('<br/>', $validator->messages()->all());
                return response()->json([
                    'status' => 0,
                    'errors' => $validator->messages(),
                    'message' => $messages
                ]);
            }

            $canUpdatePassword = $this->canUpdatePassword($request);

            if ($canUpdatePassword === true){
                $user->password = Hash::make($request->password);

            } else if($canUpdatePassword == 'error') {
                return response()->json([
                    'status' => 0,
                    'message' => 'Datos invalidos para actualizar tu contraseña'
                ]);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->phone = $request->phone;

            $user->save();
            $user->roles;

            return response()->json([
                'status' => 1,
                'data' => $user,
                'message' => 'Usuario actualizado exitosamente'
            ]);
        } catch (Exception $e) {
            info($e);
            return response()->json([
                'status' => 0,
                'message' => 'Ha ocurrido un error, intente mas tarde.'
            ]);
        }
    }

    public function updateAvatar(Request $request)
    {
        $user = $request->user();
        $file = $request->file('avatar');

        try{
            if($file){
                if(Storage::disk('public')->exists('avatar/' . $user->avatar))
                    Storage::disk('public')->delete('avatar/' . $user->avatar);

                // $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $extension = $file->extension();
                $filename = time() . Str::random(5) . '.' . $extension;
                Storage::disk('public')->put('avatar/' . $filename, file_get_contents($file));
                $user->avatar = $filename;
                $user->save();
                return response()->json([
                    'status' => 1,
                    'message' => 'Imagen actualizada exitosamente',
                    'avatar' => $filename
                ]);
            }else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Imagen invalida o inexistente',
                ]);
            }
        } catch(Exception $e){
            info($e);
            return response()->json([
                'status' => 0,
                'message' => 'Ocurrio un error, intente mas tarde por favor',
            ]);
        }
    }

    private function canUpdatePassword(Request $request)
    {
        if ($request->old_password && $request->password && $request->repeat_password){
            if (!Auth::guard('web')->attempt(['email' => $request->user()->email, 'password' => $request->old_password])) {
                return 'error';
            }

            if($request->password != $request->repeat_password || strlen($request->password) < 6){
                return 'error';
            }

            return true;
        } else{
            return false;
        }
    }
}
