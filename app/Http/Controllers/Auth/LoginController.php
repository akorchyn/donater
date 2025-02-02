<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public const string RETURN_AFTER_LOGIN = 'RETURN_AFTER_LOGIN';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(): View
    {
        $previous = url()->previous();
        $route = str_contains($previous, config('app.url')) ? $previous : route('my');
        session()->put(LoginController::RETURN_AFTER_LOGIN, $route);

        return view('auth.login', ['loginHash' => app(LoginService::class)->getNewLoginHash()]);
    }

    public function login(Request $request)
    {
        if ('http://localhost' === config('app.url')) {
            $this->fakeLogin();
            return new JsonResponse(['url' => session()->get(self::RETURN_AFTER_LOGIN, route('my'))], Response::HTTP_ACCEPTED);
        }

        $this->validateLogin($request);
        $service  = app(LoginService::class);
        $data = $service->getCachedLoginData($request->get('loginHash'));
        if (empty($data)) {
            return response('Login information not found', Response::HTTP_UNAUTHORIZED);
        }
        $this->guard()->login($service->getOrCreateUser($data));

        return new JsonResponse(['url' => session()->get(self::RETURN_AFTER_LOGIN, route('my'))], Response::HTTP_ACCEPTED);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'loginHash' => 'required|string',
        ]);
    }

    public function fakeLogin(): void
    {
        $this->guard()->login(app(LoginService::class)->getOrCreateUser([
            'id'          => 5609509050,
            'username'    => 'admin',
            'first_name'  => 'Серійний донатер',
            'last_name'   => '',
            'is_premium'  => true,
        ], false));
    }
}
