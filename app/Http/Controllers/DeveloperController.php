<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeveloperRequest;
use App\Http\Requests\UpdateDeveloperRequest;
use App\Http\Resources\DeveloperResource;
use App\Models\DeveloperModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $developersModel = new DeveloperModel();
        $developers = $developersModel->searchByTerms($request);

        return DeveloperResource::make(
            $developers,
            ['message' => ['retrieved' => ['Developers retrieved successfully']]],
            boolval($developers->count())
        )
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeveloperRequest $request): JsonResponse
    {

        $developerModel = new DeveloperModel();
        $developer = $developerModel->createDeveloper($request);

        return DeveloperResource::make(
            $developer,
            ['message' => ['created' => ['Developer created successfully']]],
            boolval($developer)
        )
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $developersModel = new DeveloperModel();
        $developer = $developersModel->getDeveloperById($id);

        if ($developer) {
            return DeveloperResource::make(
                $developer,
                ['message' => ['retrieved' => ['Developer retrieved successfully']]],
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_OK);
        } else {
            return DeveloperResource::make(
                $developer,
                ['message' => ['notFound' => ['Developer not found in database']]],
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeveloperRequest $request, string $id): JsonResponse
    {
        $developersModel = new DeveloperModel();
        $developer = $developersModel->getDeveloperById($id);

        if ($developer) {
            $developersModel->updateDeveloper($request, $id);
            $updatedDeveloper = $developersModel->getDeveloperById($id);

            return DeveloperResource::make(
                $updatedDeveloper,
                ['message' => ['updated' => ['Developer updated successfully']]],
                boolval($updatedDeveloper),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_OK);
        } else {
            return DeveloperResource::make(
                $developer,
                ['message' => ['notFound' => ['Developer not found in database']]],
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $developersModel = new DeveloperModel();
        $developer = $developersModel->getDeveloperById($id);

        if ($developer) {
            $developersModel->deleteDeveloperById($id);

            return DeveloperResource::make(
                $developer,
                ['message' => ['deleted' => ['Developer deleted successfully']]],
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_OK);
        } else {
            return DeveloperResource::make(
                $developer,
                ['message' => ['notFound' => ['Developer not found in database']]],
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_NOT_FOUND);
        }
    }
}
