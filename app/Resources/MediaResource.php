<?php

namespace App\Resources;

use Domus\Sections\Traits\SectionTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'collection_name' => $this->collection_name,
            'url' => $this->url,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'file_name' => $this->file_name,
            'data' => $this->data,
            'conversions' => [
                'thumb' => $this->getUrl('thumbnail'),
                'medium' => $this->getUrl('medium'),
                'large' => $this->getUrl('large'),
            ],
        ];
    }
}
