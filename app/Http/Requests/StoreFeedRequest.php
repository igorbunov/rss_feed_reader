<?php

namespace App\Http\Requests;

use App\Models\Feed;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->feed->user_id == auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:4|max:100',
            'url' => [
                'required',
                'url',
                Rule::unique('feeds')->where(function ($query) {
                    return $query->where('url', $this->url)
                        ->where('user_id', auth()->id())
                        ->where('id', '!=', $this->feed->id)
                        ->whereNull('deleted_at');
                }),
            ],
            'is_notify' => 'required|integer|min:0|max:1'
        ];
    }
}
