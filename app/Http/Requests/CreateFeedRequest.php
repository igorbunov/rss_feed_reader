<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
                        ->whereNull('deleted_at');
                }),
            ],
            'is_notify' => 'required|integer|min:0|max:1'
        ];
    }
}
