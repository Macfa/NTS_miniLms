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
            'description' => 'required|min:1',
            'limit_count' => 'required|integer',
            // 관리자에겐 필수, 실제로는 prepareForValidation에서 강사에 대해 서버 주입
            'manager_id' => 'required|exists:managers,id',
            'total_week' => 'nullable|integer',
            'total_price' => 'nullable|integer',
            'status' => 'required|in:0,1',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'file|mimes:jpg,jpeg,png',
        ];

        // 승인 상태는 관리자일 때만 필수, 강사는 미전송 가능
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
      // $user = auth()->user();
        $user = $this->user();
        // 강사는 선택 UI 없이 자신 소속 manager_id를 서버에서 강제 주입
        if ($user && $user->role === 'manager') {
            $managerId = optional($user->manager)->id;
            if ($managerId) {
                $this->merge(['manager_id' => $managerId]);
            }
        }
    }
}
