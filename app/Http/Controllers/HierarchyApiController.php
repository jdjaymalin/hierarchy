<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\ChildToParentFormatter;
use App\Services\Exceptions\HierarchyLoopException;
use App\Services\HierarchyService;
use Illuminate\Http\JsonResponse;

class HierarchyApiController
{
    /** @var HierarchyService */
    private $hierarchyService;

    /**
     * HierarchyApiController constructor.
     * @param HierarchyService $hierarchyService
     */
    public function __construct(HierarchyService $hierarchyService)
    {
        $this->hierarchyService = $hierarchyService;
    }

    /**
     * @param Requests\JsonRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function getHierarchy(Requests\JsonRequest $request) : JsonResponse
    {
        try {
            $input = $request->getEntitiesData();
            $hierarchyMap = $this->hierarchyService->generateHierarchyMap(
                (new ChildToParentFormatter())
                    ->getEntitiesFromInput($input)
            );
        } catch (HierarchyLoopException $e) {
            return response()->json(['errors' => [$e->getMessage()]], 422);
        }

        return response()->json(['result' => $hierarchyMap]);
    }
}
