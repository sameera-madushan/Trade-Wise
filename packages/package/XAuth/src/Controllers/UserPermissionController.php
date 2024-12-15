<?php

namespace Package\XAuth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Package\XAuth\Models\Permission;
use Package\XAuth\Resources\PermissionResource;

class UserPermissionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/user-permissions",
     *      operationId="get_user_permissions",
     *      tags={"Permissions"},
     *      summary="Get user permissions",
     *      description="Get user permissions",
     *      security={{"sanctum": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Success Responce",
     *          @OA\JsonContent(
     *              @OA\Property(property="status",description="Responce status",type="string",example="success"),
     *               @OA\Property(property="message",description="Success message",type="string",example=""),
     *               @OA\Property(property="data",description="Responce data",type="object",example={})
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="message",type="string",example="Unauthenticated")
     *          )
     *      )
     * )
     */
    public function index(): JsonResponse
    {
        $permissions = Auth::user()->getAllPermissions()->pluck('name');
        return apiResponse('success','', $permissions);
    }
}
