<?php

namespace App\Http\Requests\API\Dashboard;

use App\Models\Dashboard\Line;
use InfyOm\Generator\Request\APIRequest;

class CreateLineAPIRequest extends APIRequest
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
        return Line::$rules;
    }
}
