<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->load('user', 'comments');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'author' => [
                'username' => $this->user->username,
                'email' => $this->user->email
            ], 
            'news_content' => $this->news_content,
            'created_ad' =>\date_format($this->created_at,"d-m-Y"),
            'comments' => $this->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'username' => $comment->user->username,
                    'coment_content' => $comment->comments_content
                ];
            }),
        ];
    }
}
