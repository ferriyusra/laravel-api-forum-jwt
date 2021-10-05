<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,  
            'title' => ucfirst($this->title),
            'body' => $this->body,
            'slug' => $this->slug,
            'category' => $this->category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // dibawah ini mengambil data relasi dari user dan komen user sekaligus
            'user' => $this->user,
            'comment_count' => $this->comments_count,
        ];
    }
}
