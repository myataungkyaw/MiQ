<?php

namespace App\Http\Requests\API\Dashboard;

use App\Models\Dashboard\Printer;
use InfyOm\Generator\Request\APIRequest;

class CreatePrinterAPIRequest extends APIRequest
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
        return Printer::$rules;
    }
}
