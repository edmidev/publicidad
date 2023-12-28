<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $ad = Ad::all();
        return $this->successResponse($ad, 'Lista de publicidades');
    }

    public function search(Request $request)
    {
        $ads = Ad::orderBy('id', 'desc');

        $textSearched = $request->text;
        $clientId = $request->client_id;

        if ($textSearched) {
            $ads->where('name', 'LIKE', '%'.$textSearched.'%');
        }

        if ($clientId) {
            $ads->where('client_id', $clientId);
        }

        return $ads->paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'url' => ['required', 'url'],
            'position' => ['required', 'string'],
            'image' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->messages(),
                'message' => 'Revise los campos del formulario'
            ]);
        }
        try{
            $folder = 'public/images';
            $microtime = microtime();
            $filename = random_int(1000, 9999).explode(' ', $microtime)[1]. '.'.$request->image->getClientOriginalExtension();
            $path = Storage::putFileAs($folder, $request->image, $filename);

            $ad = new Ad();
            $ad->name = $request->name;
            $ad->url = $request->url;
            $ad->position = $request->position;
            $ad->image = $path;

            $ad->save();
            return $this->successResponse([
                'data' => $ad,
            ], 'Pubicidad guardarda exitosamente');
        } catch (Exception $e){
            return $this->errorResponse('Ocurrio un error, intente mas tarde', 403, $e);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->messages(),
                'message' => 'Revise los campos del formulario'
            ]);
        }

        try{
            $ad = Ad::findOrFail($request->id);
            if ($request->hasFile('file')){
                $folder = 'public/document/files';
                $microtime = microtime();
                $filename = random_int(1000, 9999).explode(' ', $microtime)[1]. '.'.$request->file->getClientOriginalExtension();
                $path = Storage::putFileAs($folder, $request->file, $filename);
                $ad->path = $path;
            }
            $ad->name = $request->name;

            $ad->save();
            return $this->successResponse([
                'data' => $ad,
            ], 'Servicio actualizado exitosamente');
        } catch (Exception $e){
            return $this->errorResponse('Ocurrio un error, intente mas tarde', 403, $e);
        }
    }

    public function delete(Request $request)
    {
        try{
            $register = Ad::findOrFail($request->id);
            if (!$register)
                return $this->errorResponse('No se ha podido deshabilitar el registro', 403);

            Storage::delete($register->path);
            $register->delete();

            return $this->successResponse([], 'Archivo eliminado exitosamente');
        } catch (Exception $e){
            return $this->errorResponse('Ocurrio un error, intente mas tarde', 403, $e);
        }
    }

    public function toggle(Request $request)
    {
        $register = Ad::withTrashed()->find($request->id);

        if (!$register)
            return $this->errorResponse('No se ha podido actualizar el registro', 403);

        if($register->deleted_at){
            $register->restore();
        } else {
            $register->delete();
        }
        return $this->successResponse(null, 'Registro actualizar exitosamente');
    }
}
