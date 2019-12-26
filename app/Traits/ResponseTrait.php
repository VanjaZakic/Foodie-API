<?php

namespace App\Traits;

use Prettus\Repository\Transformer\ModelTransformer as Transformer;
use Spatie\Fractal\Fractal;
use Illuminate\Http\JsonResponse;
use ReflectionClass;
use Illuminate\Support\Facades\Request;

/**
 * Trait ResponseTrait
 * @package App\Traits
 */
trait ResponseTrait
{
    protected $metaData = [];

    /**
     * @param       $data
     * @param null  $transformerName The transformer (e.g., Transformer::class or new Transformer()) to be applied
     * @param array $includes        additional resources to be included
     * @param array $meta            additional meta information to be applied
     * @param null  $resourceKey     the resource key to be set for the TOP LEVEL resource
     *
     * @return array
     */

    public function transform(
        $data,
        $transformerName = null,
        array $includes = [],
        array $meta = [],
        $resourceKey = null
    )
    {
        $transformer = new $transformerName();
        if ($transformerName instanceof Transformer) {
            $transformer = $transformerName;
        }

        $includes = array_unique(array_merge($transformer->getDefaultIncludes(), $includes));

        $transformer->setDefaultIncludes($includes);

        $this->metaData = [
            'include' => $transformer->getAvailableIncludes(),
            'custom'  => $meta,
        ];

        $fractal = Fractal::create($data, $transformer)
            ->withResourceName($resourceKey)
            ->addMeta($this->metaData);

        $request = Request::instance();
        if ($requestIncludes = $request->get('include')) {
            $fractal->parseIncludes($requestIncludes);
        }

        if ($requestFilters = $request->get('filter')) {
            return $this->filterResponse($fractal->toArray(), explode(';', $requestFilters));
        }

        return $fractal->toArray();
    }

    /**
     * @param $data
     *
     * @return  $this
     */
    public function withMeta($data)
    {
        $this->metaData = $data;

        return $this;
    }

    /**
     * @param       $message
     * @param int   $status
     * @param array $headers
     * @param int   $options
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function json($message, $status = 200, array $headers = [], $options = 0)
    {
        return new JsonResponse($message, $status, $headers, $options);
    }

    /**
     * @param null  $message
     * @param int   $status
     * @param array $headers
     * @param int   $options
     *
     * @return JsonResponse
     */
    public function created($message = null, $status = 201, array $headers = [], $options = 0)
    {
        return new JsonResponse($message, $status, $headers, $options);
    }

    /**
     * @param null  array or string $message
     * @param int   $status
     * @param array $headers
     * @param int   $options
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function accepted($message = null, $status = 202, array $headers = [], $options = 0)
    {
        return new JsonResponse($message, $status, $headers, $options);
    }

    /**
     * @param null $responseArray
     *
     * @return JsonResponse
     * @throws \ReflectionException
     */
    public function deleted($responseArray = null)
    {
        if (!$responseArray) {
            return $this->accepted();
        }

        $id        = $responseArray->getHashedKey();
        $className = (new ReflectionClass($responseArray))->getShortName();

        return $this->accepted([
            'message' => "$className ($id) Deleted Successfully.",
        ]);
    }

    /**
     * @param int $status
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function noContent($status = 204)
    {
        return new JsonResponse(null, $status);
    }

    /**
     * @param array $responseArray
     * @param array $filters
     *
     * @return array
     */
    private function filterResponse(array $responseArray, array $filters)
    {
        foreach ($responseArray as $k => $v) {
            if (in_array($k, $filters, true)) {
                continue;
            }

            if (is_array($v)) {
                $v = $this->filterResponse($v, $filters);
                if (empty($v)) {
                    unset($responseArray[$k]);
                } else {
                    $responseArray[$k] = $v;
                }
                continue;
            }

            if (!in_array($k, $filters)) {
                unset($responseArray[$k]);
                continue;
            }
        }

        return $responseArray;
    }
}
