<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ValidationService
{
    /**
     * field Validations
     *
     * @param array $params
     * @return array
     */
    public function validateRequiredFields(array &$params): array
    {
        Log::info("validateRequiredFields " . json_encode($params));

            $validator = Validator::make($params, [
                'email' =>  'required|email',
                'phone_number' =>  'numeric|digits:10',
                'name'    =>  'string|required|max:255',
                'comment'    =>  'string|required|max:1000',
            ]);

        if ($validator->fails()) {
            return $validator->errors()->getMessages();
        }

        return $validator->errors()->getMessages();
    }
}
