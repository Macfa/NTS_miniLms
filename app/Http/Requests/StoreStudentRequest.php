<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      // dd(auth()->user()->admin);
      // 관리자만 학생 생성 가능
        if(auth()->user()->admin?->exists()) {
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
            'name' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:2|confirmed',
            'status' => 'required|in:0,1',
        ];
    }
}
