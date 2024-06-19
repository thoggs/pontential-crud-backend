<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeveloperResource extends JsonResource
{
    protected bool $success;
    protected array $metadata;

    public function __construct($resource, array $metadata = [], bool $success = true)
    {
        parent::__construct($resource);
        $this->success = $success;
        $this->metadata = $metadata;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }

    /**
     * Additional data to merge with the resource array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'success' => $this->success,
            'metadata' => $this->metadata
        ];
    }
}

