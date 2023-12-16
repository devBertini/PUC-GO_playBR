<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

use App\Models\Sound;

class SoundController extends Controller
{
    public function index(Request $request) {
        $validator = Validator::make(
            $request->header(),
            [
                'paginate' => 'nullable',
                'userId' => 'nullable',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => 'Erro', 'details' => $validator->errors()->first()], 400);
        }

        $query = Sound::query();

        if (!empty($request->header('userId'))) {
            $query->where('user_id', $request->header('userId'));
        }

        if (!empty($request->header('paginate'))) {
            return $query->paginate(intval($request->header('paginate')));
        }

        try {
            $sounds = $query->with('user')->get();
            return view('sounds', ['sounds' => $sounds]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível listar os áudios existentes.'], 400);
        }
    }

    public function show($id) {
        try {
            $sound = Sound::where('id', $id)->firstOrFail();
            $soundPath = storage_path('app/' . $sound->url);
            return response()->file($soundPath);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível obter informações do áudio selecionado.'], 400);
        }
    }

    public function showView($id) {
        try {
            $sound = Sound::with('user')->findOrFail($id);
            $soundPath = storage_path('app/' . $sound->url);

            // Construir um array com informações do áudio
            $soundData = [
                'id' => $sound->id,
                'title' => $sound->title,
                'description' => $sound->description,
                'author' => $sound->user->name,
                'posted_date' => $sound->created_at->format('d M Y'),
                'url' => url('/api/sounds/' . $id),
            ];

            return view('sound', compact('soundData'));
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível obter o áudio selecionado.'], 400);
        }
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'sound' => 'required|mimetypes:audio/mpeg', // validação para arquivos mp3
        ]);

        $user = Auth::user();
        $path = 'uploads/users/' . $user->id . '/sounds';

        // Salvar o áudio no servidor
        $soundPath = $request->file('sound')->store($path);

        // Criar o registro do áudio no banco de dados
        $sound = new Sound([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'url' => $soundPath,
            'user_id' => $user->id,
        ]);

        try {
            $sound->save();
            return response()->json(['message' => 'Sucesso', 'details' => 'O novo áudio foi enviado com sucesso.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Houve um erro ao enviar o áudio.'], 400);
        }
    }

    public function update(Request $request, $id) {
        $sound = Sound::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $sound->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['message' => 'Sucesso', 'details' => 'O áudio foi atualizado com sucesso.'], 200);
    }

    public function destroy($id) {
        try {
            $sound = Sound::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Você não pode remover um áudio que você não é o autor.'], 400);
        }

        try {
            if (Storage::exists($sound->url)) {
                Storage::delete($sound->url);
            }

            $sound->delete();

            return response()->json(['message' => 'Sucesso', 'details' => 'O Áudio foi removido com sucesso.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível remover o áudio selecionado.'], 400);
        }
    }
}
