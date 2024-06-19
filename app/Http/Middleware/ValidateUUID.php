<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ValidateUUID
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return ResponseAlias
     */
    public function handle(Request $request, Closure $next): ResponseAlias
    {
        $id = $request->route('developer');

        if ($id && !Uuid::isValid($id)) {
            return response()->json([
                'success' => false,
                'matadata' => [
                    'message' => array('id' => ['Invalid ID format, please provide a valid UUID format.'])
                ],
                'data' => (array())
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
