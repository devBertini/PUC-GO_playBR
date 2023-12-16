<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

use App\Models\Sound;
use App\Models\Video;

class HomeController extends Controller
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

        $queryVideo = Video::query();

        if (!empty($request->header('userId'))) {
            $queryVideo->where('user_id', $request->header('userId'));
        }

        if (!empty($request->header('paginate'))) {
            return $queryVideo->paginate(intval($request->header('paginate')));
        }

        $querySound = Sound::query();

        if (!empty($request->header('userId'))) {
            $querySound->where('user_id', $request->header('userId'));
        }

        if (!empty($request->header('paginate'))) {
            return $querySound->paginate(intval($request->header('paginate')));
        }

        
        try {
            $videos = $queryVideo->with('user')->get();
            $sound = $querySound->with('user')->get();

            return view('videos', ['videos' => $videos, 'audios' => $sound]);
            // return view('videos', ['videos' => $videos]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Erro', 'details' => 'Não foi possível listar os vídeos existentes.'], 400);
        }
    }
}
