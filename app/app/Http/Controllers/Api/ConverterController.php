<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostGisDecodeRequest;
use App\Http\Requests\PostGisEncodeRequest;
use App\Services\ConverterService;
use Illuminate\Http\JsonResponse;

class ConverterController extends Controller
{
    /** @var ConverterService $converterService */
    private ConverterService $converterService;

    /**
     * @param ConverterService $converterService
     */
    public function __construct(ConverterService $converterService)
    {
        $this->converterService = $converterService;
    }

    /**
     * @param PostGisDecodeRequest $request
     * @return JsonResponse
     */
    public function postGisDecode(PostGisDecodeRequest $request): JsonResponse
    {
        return ResponseHelper::response(
            $this->converterService->postGisDecode($request->wkbHex),
            !$this->converterService->isError(),
            $this->converterService->getError(),
        );
    }

    /**
     * @param PostGisEncodeRequest $request
     * @return JsonResponse
     */
    public function postGisEncode(PostGisEncodeRequest $request): JsonResponse
    {
        return ResponseHelper::response(
            $this->converterService->postGisEncode((float)$request->lat, (float)$request->lon),
            !$this->converterService->isError(),
            $this->converterService->getError(),
        );
    }
}
