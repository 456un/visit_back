<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Converter\Dto\ShaHashDto;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostGisDecodeRequest;
use App\Http\Requests\PostGisEncodeRequest;
use App\Http\Requests\ShaHashRequest;
use App\Services\ConverterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    /**
     * @param ShaHashRequest $request
     * @return JsonResponse
     */
    public function shaHash(ShaHashRequest $request): JsonResponse
    {
        $isHmac = filter_var($request->isHmac, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (is_null($isHmac)) {
            return ResponseHelper::response([], false, 'Параметр isHmac не валиден');
        }

        $shaHashDto = (new ShaHashDto())
            ->setAlgo($request->algo)
            ->setData($request->data)
            ->setIsHmac($isHmac)
            ->setTypeHmac($request->typeHmac)
            ->setHmac($request->hmac)
            ->setFileHmac($request->fileHmac);

        return ResponseHelper::response(
            $this->converterService->shaHash($shaHashDto),
            !$this->converterService->isError(),
            $this->converterService->getError(),
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function argon2Hash(Request $request): JsonResponse
    {
        return ResponseHelper::response(
            $this->converterService->argon2Hash(),
            !$this->converterService->isError(),
            $this->converterService->getError(),
        );
    }
}
