<?php

namespace App\Http\Controllers;

use App\Models\Concessionaria;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\SearchRequest;
use App\Services\ConcessionariaService;
use App\Http\Resources\ConcessionariaResource;
use App\Http\Requests\ConcessionariaStoreRequest;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ConcessionariaController extends BaseController
{
    /**
     * Create a new Controller instance.
     */
    public function __construct(
        private ConcessionariaService $service,
    ) {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request, Pipeline $pipeline): ResourceCollection
    {
        return ConcessionariaResource::collection($this->service->index($request()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConcessionariaStoreRequest $request): JsonResponse
    {
        return (new ConcessionariaResource($this->service->store($request->validated())))
                    ->response()
                    ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Concessionaria $concessionaria): ConcessionariaResource
    {
        return new ConcessionariaResource($concessionaria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConcessionariaStoreRequest $request, Concessionaria $concessionaria): ConcessionariaResource
    {
        return new ConcessionariaResource($this->service->update($request->validated(), $concessionaria));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Concessionaria $concessionaria): JsonResponse
    {
        $this->service->delete($concessionaria);

        return $this->respond(statusCode: JsonResponse::HTTP_NO_CONTENT);
    }
}
