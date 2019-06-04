<?php

namespace App\Http\Requests\API\Dashboard;

use App\Models\Dashboard\Tag;
use InfyOm\Generator\Request\APIRequest;

class CreateTagAPIRequest extends APIRequest
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
        return Tag::$rules;
    }
}
