<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * @var bool
     */
    protected $single = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'file' => $this->file,
            'url' => $this->url,
        ];

        if ($this->single) {
            $response['enable'] = $this->enable;
        }

        return $response;
    }

    /**
     * Single response
     * 
     * @return self
     */
    public function single(): self {
        $this->single = true;
        return $this;
    }
}
