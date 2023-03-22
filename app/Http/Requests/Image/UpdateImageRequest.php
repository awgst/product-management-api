<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'enable' => 'boolean',
        ];
    }

    /**
     * Get data from request
     * 
     * @return array<string, mixed>
     */
    public function data(): array
    {
        $data = [];
        if ($this->has('name')) {
            $data['name'] = $this->input('name');
        }
        if ($this->has('file')) {
            $data['file'] = $this->file('file');
        }
        if ($this->has('enable')) {
            $data['enable'] = $this->input('enable');
        }

        return $data;
    }
}
