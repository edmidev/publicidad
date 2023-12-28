<?php

namespace App\Http\Controllers;

use App\Models\DocumentFile;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class DocumentFileController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $document = DocumentFile::all();
        return $this->successResponse($document, 'Lista de documentos');
    }

    public function search(Request $request)
    {
        $document = DocumentFile::orderBy('id', 'desc');

        $textSearched = $request->text;
        $clientId = $request->client_id;

        if ($textSearched) {
            $document->where('name', 'LIKE', '%'.$textSearched.'%');
        }

        if ($clientId) {
            $document->where('client_id', $clientId);
        }

        return $document->paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'client_id' => ['required', 'numeric'],
            'file' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->messages(),
                'message' => 'Revise los campos del formulario'
            ]);
        }
        try{
            $folder = 'public/document/files';
            $microtime = microtime();
            $filename = random_int(1000, 9999).explode(' ', $microtime)[1]. '.'.$request->file->getClientOriginalExtension();
            $path = Storage::putFileAs($folder, $request->file, $filename);

            $documentFile = new DocumentFile();
            $documentFile->client_id = $request->client_id;
            $documentFile->name = $request->name;
            $documentFile->path = $path;

            $documentFile->save();
            return $this->successResponse([
                'data' => $documentFile,
            ], 'Servicio guardardo exitosamente');
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
            $documentFile = DocumentFile::findOrFail($request->id);
            if ($request->hasFile('file')){
                $folder = 'public/document/files';
                $microtime = microtime();
                $filename = random_int(1000, 9999).explode(' ', $microtime)[1]. '.'.$request->file->getClientOriginalExtension();
                $path = Storage::putFileAs($folder, $request->file, $filename);
                $documentFile->path = $path;
            }
            $documentFile->name = $request->name;

            $documentFile->save();
            return $this->successResponse([
                'data' => $documentFile,
            ], 'Servicio actualizado exitosamente');
        } catch (Exception $e){
            return $this->errorResponse('Ocurrio un error, intente mas tarde', 403, $e);
        }
    }

    public function delete(Request $request)
    {
        try{
            $register = DocumentFile::findOrFail($request->id);
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
        $register = DocumentFile::withTrashed()->find($request->id);

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
