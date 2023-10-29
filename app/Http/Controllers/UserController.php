<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /** @var UserService */
    protected $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * List all users.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users,  Response::HTTP_OK);
    }

    /**
     * Store a new user.
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        
        try {
            $this->userService->createUser($validatedData);
            return response()->json(['message' => 'Usuário e conta criados com sucesso!'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified user by ID.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->findUserById($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user,  Response::HTTP_OK);
    }

    /**
     * Display the specified user by email.
     * @param string $email
     * @return JsonResponse
     */
    public function showByEmail(string $email): JsonResponse
    {
        $user = $this->userService->findUserByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'],  Response::HTTP_NOT_FOUND);
        }

        return response()->json($user,  Response::HTTP_OK);
    }

    /**
     * Display the most recently created user.
     * @return JsonResponse
     */
    public function showLatest(): JsonResponse
    {
        $user = $this->userService->findLatestUser();

        if (!$user) {
            return response()->json(['message' => 'Nenhum usuário foi encontrado.'],  Response::HTTP_NOT_FOUND);
        }

        return response()->json($user, 200);
    }
}
