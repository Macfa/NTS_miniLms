<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      return auth()->user()->role == 'admin' ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|min:2|max:10',
            'name' => 'required|min:1|max:20',
            'description' => 'required|min:1|max:20',
            'limit_count' => 'required',
            'manager_id' => 'required',
            'total_week' => 'nullable|integer',
            'total_price' => 'nullable|integer',
            'status' => 'required|in:0,1',
            'approval_status' => 'required|in:1,2,3',
            'chapters' => 'required|array|min:1',
            'chapters.*.start' => 'required|date',
            'chapters.*.end' => 'required|date',
            'chapters.*.week_days' => 'required|string',
            'chapters.*.status' => 'required|in:0,1',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('chapters') && is_string($this->chapters)) {
            $this->merge([
                'chapters' => json_decode($this->chapters, true)
            ]);
        }
    }
}
