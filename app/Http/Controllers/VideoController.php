<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

use App\Models\Video;

class VideoController extends Controller
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

        $query = Video::query();

        if (!empty($request->header('userId'))) {
            $query->where('user_id', $request->header('userId'));
        }

        if (!empty($request->header('paginate'))) {
            return $query->paginate(intval($request->header('paginate')));
        }

        try {
            $videos = $query->with('user')->get();
            return view('videos', ['videos' => $videos]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível listar os vídeos existentes.'], 400);
        }
    }

    public function show($id) {
        try {
            $video = Video::where('id', $id)->firstOrFail();
            $videoPath = storage_path('app/' . $video->url);
            return response()->file($videoPath);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível obter informações do vídeo selecionado.'], 400);
        }
    }

    public function showView($id) {
        try {
            $video = Video::with('user')->findOrFail($id);
            $videoPath = storage_path('app/' . $video->url);

            // Construir um array com informações do vídeo e a URL do vídeo
            $videoData = [
                'id' => $video->id,
                'title' => $video->title,
                'description' => $video->description,
                'author' => $video->user->name,
                'thumbnail' => $video->thumbnail,
                'posted_date' => $video->created_at->format('d M Y'),
                'video_url' => url('/api/videos/' . $id),
            ];

            return view('video', compact('videoData'));
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível obter o vídeo selecionado.'], 400);
        }
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'video' => 'required|mimetypes:video/mp4',
            'thumbnail' => 'sometimes|image|mimes:jpeg,png,jpg',
        ]);

        // Obter o usuário autenticado
        $user = Auth::user();
        $path = 'uploads/users/' . $user->id . '/videos';

        // Salvar o vídeo no servidor
        $videoPath = $request->file('video')->store($path);

        $thumbnailPath = '';

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('uploads/users/' . $user->id . '/videos/thumbs', 'public');
        } else {
            $thumbnailPath = null;
        }

        // Criar o registro do vídeo no banco de dados
        $video = new Video([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'thumbnail' => $thumbnailPath,
            'url' => $videoPath,
            'user_id' => $user->id,
        ]);

        try {
            $video->save();
            return response()->json(['message' => 'Sucesso', 'details' => 'O novo vídeo foi enviado com sucesso.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Houve um erro ao enviar o vídeo.'], 400);
        }
    }

    public function update(Request $request, $id) {
        // Encontrar e verificar se o vídeo pertence ao usuário autenticado
        $video = Video::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validar a requisição
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        // Atualizar informações do vídeo
        $video->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['message' => 'Sucesso', 'details' => 'O vídeo foi atualizado com sucesso.'], 200);
    }

    public function destroy($id) {
        // Encontrar e verificar se o vídeo pertence ao usuário autenticado
        try {
            $video = Video::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Você não pode remover um vídeo que você não é o autor.'], 400);
        }

        try {
            // Verifica se o arquivo de vídeo existe e remove
            if (Storage::exists($video->url)) {
                Storage::delete($video->url);
            }

            // Verifica se a thumbnail existe e remove
            if (Storage::exists($video->thumbnail)) {
                Storage::delete($video->thumbnail);
            }

            // Deletar o registro do vídeo no banco de dados
            $video->delete();

            return response()->json(['message' => 'Sucesso', 'details' => 'O Vídeo fo iremovido com sucesso.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível remover o vídeo selecionado.'], 400);
        }
    }
}
