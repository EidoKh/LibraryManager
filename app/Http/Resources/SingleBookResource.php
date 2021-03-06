<?php

namespace App\Http\Resources;

use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleBookResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'language' => $this->language,
            'pages' => $this->pages,
            'number_of_copies' => $this->number_of_copies,
            'publication_year' => $this->publication_year,
            'book_image' => $this->book_image,
            'book_thumbnail' => $this->book_thumbnail,
            'category' => Category::find($this->category_id)->category_name,
            'author' => Author::find($this->author_id)->author_name,
        ];
    }
}
