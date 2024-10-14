<?php

namespace App\Http\Controllers\Api;

use App\Facades\AuthFacade;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends ApiController
{
    public function __construct(private AuthFacade $authFacade) {}

    /**
     * Criar uma nova conta
     *
     * Cria uma nova conta de usuário.
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $this->authFacade->register($data);

        return $this->success(data: null, message: 'Conta criada com sucesso.', statusCode: 201);
    }

    /**
     * Login
     *
     * Obtém o access token a partir das credenciais do usuário.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $tokenData = $this->authFacade->getAccessToken($credentials);

        if (! $tokenData) {
            return $this->failed(message: 'Credenciais inválidas.', error: 'invalid_credentials', statusCode: 401);
        }

        return $this->success(data: $tokenData, message: 'Login realizado com sucesso.');
    }

    /**
     * Atualizar access token
     *
     * Atualiza o access token do usuário autenticado.
     */
    public function refresh()
    {
        $tokenData = $this->authFacade->refresh();

        return $this->success(data: $tokenData, message: 'Token atualizado com sucesso.');
    }

    /**
     * Logout
     *
     * Invalida o access token do usuário autenticado.
     */
    public function logout()
    {
        $this->authFacade->logout();

        return $this->success(data: null, message: 'Logout realizado com sucesso.');
    }
}
