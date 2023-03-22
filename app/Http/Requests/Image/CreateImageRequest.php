<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;

class CreateImageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Get data from request
     * 
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return [
            'name' => $this->input('name'),
            'file' => $this->file('file'),
        ];
    }
}
