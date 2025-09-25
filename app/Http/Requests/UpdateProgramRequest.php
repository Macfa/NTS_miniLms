<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      // return auth()->user()->role == 'admin' ? true : false;
      // 정책이랑 혼선 우려
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $rules = [
            'category' => 'required|min:2|max:10',
            'name' => 'required|min:1|max:20',
            'description' => 'required|min:1|max:20',
            'limit_count' => 'required|integer',
            'manager_id' => 'required|exists:managers,id',
            'total_week' => 'nullable|integer',
            'total_price' => 'nullable|integer',
            'status' => 'required|in:0,1',
        ];

        if ($user && $user->role === 'admin') {
            $rules['approval_status'] = 'required|in:1,2,3';
        } else {
            $rules['approval_status'] = 'nullable|in:1,2,3';
        }

        return $rules;
    }

    // 챕터 관련 전처리 제거

    protected function prepareForValidation(): void
    {
        $user = $this->user();
        if ($user && $user->role === 'manager') {
            $managerId = optional($user->manager)->id;
            if ($managerId) {
                $this->merge(['manager_id' => $managerId]);
            }
        }
    }
}
