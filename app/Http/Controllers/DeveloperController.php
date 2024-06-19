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
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $developersModel = new DeveloperModel();
        $developers = $developersModel->searchByTerms($request);

        return DeveloperResource::make(
            $developers,
            array('message' => array('retrieved' => ['Developers retrieved successfully'])),
            boolval($developers->count())
        )
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDeveloperRequest $request
     * @return JsonResponse
     */
    public function store(StoreDeveloperRequest $request): JsonResponse
    {

        $developerModel = new DeveloperModel();
        $developer = $developerModel->createDeveloper($request);

        return DeveloperResource::make(
            $developer,
            array('message' => array('created' => ['Developer created successfully'])),
            boolval($developer)
        )
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $developersModel = new DeveloperModel();
        $developer = $developersModel->getDeveloperById($id);

        if ($developer) {
            return DeveloperResource::make(
                $developer,
                array('message' => array('retrieved' => ['Developer retrieved successfully'])),
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_OK);
        } else {
            return DeveloperResource::make(
                $developer,
                array('message' => array('notFound' => ['Developer not found in database'])),
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDeveloperRequest $request
     * @param string $id
     * @return JsonResponse
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
                array('message' => array('updated' => ['Developer updated successfully'])),
                boolval($updatedDeveloper),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_OK);
        } else {
            return DeveloperResource::make(
                $developer,
                array('message' => array('notFound' => ['Developer not found in database'])),
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_NOT_FOUND);
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
        $developersModel = new DeveloperModel();
        $developer = $developersModel->getDeveloperById($id);

        if ($developer) {
            $developersModel->deleteDeveloperById($id);

            return DeveloperResource::make(
                $developer,
                array('message' => array('deleted' => ['Developer deleted successfully'])),
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_OK);
        } else {
            return DeveloperResource::make(
                $developer,
                array('message' => array('notFound' => ['Developer not found in database'])),
                boolval($developer),
            )
                ->response()
                ->setStatusCode(ResponseAlias::HTTP_NOT_FOUND);
        }
    }
}
