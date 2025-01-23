<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
   /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function passedValidation (){
        $expectedKeys =["name","email","password","password_confirmation"];
        $extraKeys = array_diff(array_keys($this->all()),$expectedKeys);

        if(!empty($extraKeys)){
            abort(400,"se encontraron claves no esperadas: ". implode(", ",$extraKeys));
        }
    }
}
