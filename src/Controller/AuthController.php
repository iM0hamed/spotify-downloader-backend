<?php


namespace App\Controller;

use App\Exception\SpotifyApiRequestException;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    /**
     * @var AuthService
     */
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @Route("/spotify/callback", name="app_spotify_callback")
     */
    public function callback(Request $request)
    {
        $code = $request->get('code', null);

        try {
            $credentials = $this->authService->authenticateCallback($code);
        }
        catch (SpotifyApiRequestException $ex) {
            return new JsonResponse($ex->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($this->item($credentials));
    }
}
