<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreVacationRequest extends FormRequest
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
            "message_applicant"=> "required|string|max:500|required",
            "message_approver"=> "string|min:10|max:500",
            "start_date"=> "required|date|after:". Carbon::now()->toDateString(),
            "end_date"=> "required|date|after_or_equal:".$this->start_date,
        ];
    }

    public function withValidator($validator){
        $validator->after(function ($validator) {
            $start_date = $this->input('start_date');
            $end_date = $this->input('end_date');

            if ($start_date) {
                $dayOfWeekStart = Carbon::parse($start_date)->dayOfWeek;
                if (in_array($dayOfWeekStart, [0, 6])) { // Domingo (0) o Sábado (6)
                    $validator->errors()->add('start_date', 'Vacation cannot start on a weekend.');
                }
            }

            if ($end_date) {
                $dayOfWeekEnd = Carbon::parse($end_date)->dayOfWeek;
                if (in_array($dayOfWeekEnd, [0, 6])) {
                    $validator->errors()->add('end_date', 'Vacation cannot end on a weekend.');
                }
            }
        });
    }
}
