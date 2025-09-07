<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      if(Auth::user()->admin?->exists()) {
        return true;
      } else {
        return false;
      }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 이름은 있을 때만 유효성 검사
            'name' => ['sometimes', 'string', 'max:255'],
            // 이메일은 있을 때만 유효성 검사하며, 업데이트 시 고유성 검사 예외 처리
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($this->manager->user)],
            // 비밀번호는 있을 때만 유효성 검사 (nullable)
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', Rule::in([0, 1])],
        ];
    }
}
