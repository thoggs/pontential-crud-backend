<?php

namespace App\Http\Controllers;

use App\Models\DeveloperModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $developers = new DeveloperModel();

        return response()->json([
            'status' => 'success',
            'message' => 'Developers retrieved successfully',
            'model' => $developers->searchByTerms($request)
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $requestValidator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'age' => 'required|integer|min:1|max:999',
            'hobby' => 'required|string|max:100',
            'birthDate' => 'required|date|date_format:Y-m-d'
        ]);

        if ($requestValidator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $requestValidator->errors()->toArray(),
                'model' => []
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $developerModel = new DeveloperModel();
        $developer = $developerModel->createDeveloper($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Developer created successfully.',
            'model' => $developer->toArray()
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $developers = new DeveloperModel();

        if ($developers->getDeveloperById($id)) {
            return response()->json([
                'status' => 'success',
                'message' => (array()),
                'model' => $developers->getDeveloperById($id)
            ], ResponseAlias::HTTP_OK);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => [
                    'errors' => 'Developer not found in database'
                ],
                'model' => (array())
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $requestValidator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'age' => 'required|integer|min:1|max:999',
            'hobby' => 'required|string|max:100',
            'birthDate' => 'required|date|date_format:Y-m-d'
        ]);

        if ($requestValidator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $requestValidator->errors()->toArray(),
                'model' => (array())
            ], ResponseAlias::HTTP_BAD_REQUEST);
        } else {
            $developers = new DeveloperModel();

            if ($developers->getDeveloperById($id)) {
                $developers->updateDeveloper($request, $id);

                return response()->json([
                    'status' => 'success',
                    'message' => (array()),
                    'model' => $developers->getDeveloperById($id)
                ], ResponseAlias::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID not found in database',
                    'model' => (array())
                ], ResponseAlias::HTTP_NOT_FOUND);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $developers = new DeveloperModel();

        if ($developers->getDeveloperById($id)) {
            $developers->deleteDeveloperById($id);
            return response()->json([], ResponseAlias::HTTP_NO_CONTENT);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => [
                    'errors' => 'ID not found in database'
                ],
                'model' => (array())
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
    }
}
