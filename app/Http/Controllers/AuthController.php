<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use Exception;

use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request) {

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Erro', 'details' => 'A credencial informada não foi encontrada.'], 401);
        }

        $user = User::where('email', $request->email)->first();

        $user = Auth::user();

        if(!empty($user->tokens()->get()[0])) {
            $user->tokens()->delete();
        }

        try {
            $token = $user->createToken('token-name', ['playBR:'. "User"])->plainTextToken;

            $tokenModel = $user->tokens()->where('name', 'token-name')->first();

            $expiresAt = now()->addHours(9);

            if ($expiresAt->greaterThanOrEqualTo(now()->endOfDay())) {
                $expiresAt = now()->endOfDay();
            }

            $tokenModel->update([
                'name' => $user->name,
                'expires_at' => $expiresAt,
            ]);

            return response()->json([
                'token_type' => "bearer",
                'expires_in' => $tokenModel->expires_at,
                'access_token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'Erro', 'message' => 'Houve um erro ao fazer login.'], 400);
        }
    }

    public function register(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'bail|required|min:2|max:255',
                'email' => 'bail|required|email|unique:users|max:255',
                'password' => 'bail|required|min:4|max:255'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['message' => 'Erro', 'details' => $validator->errors()->first()], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = app('hash')->make($request->password);

        try {
            $user->save();
            return response()->json(['message' => 'Sucesso', 'details' => 'Nova conta cadastrada com sucesso.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'Erro', 'message' => 'Houve um erro ao cadastrar a nova conta.'], 400);
        }
    }

    public function validade(Request $request) {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if ($personalAccessToken) {
            return response()->json(['message' => 'Sucesso', 'details' => 'O token informado é válido.'], 200);
        }

        return response()->json(['message' => 'Erro', 'details' => 'O token informado não é válido.'], 400);
    }

    public function logout(Request $request) {
        $personalAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        if ($personalAccessToken) {
            $personalAccessToken->delete();

            return response()->json(['message' => 'Sucesso', 'details' => 'Logout realizado com sucesso.'], 200);
        }

        return response()->json(['message' => 'Erro', 'details' => 'Houve um erro ao realizar o logout.'], 400);
    }
}
