<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChapterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      // 관리자 페이지에 접근 가능한 사용자들 다 허용
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
            'program_id' => 'required',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'week_days' => 'required',
            'week_days.*' => 'in:0,1,2,3,4,5,6',
            'status' => 'required|in:0,1',
        ];
    }
    public function messages()
    {
        return [
            'start.required' => '시작 날짜는 필수 항목입니다.',
            'start.date' => '유효한 날짜 형식이어야 합니다.',
            'end.required' => '종료 날짜는 필수 항목입니다.',
            'end.date' => '유효한 날짜 형식이어야 합니다.',
            'end.after' => '종료 날짜는 시작 날짜 이후여야 합니다.',
            'week_days.required' => '요일 선택은 필수 항목입니다.',
            'week_days.*.in' => '요일은 0(일요일)부터 6(토요일) 사이의 값이어야 합니다.',
            'status.required' => '상태는 필수 항목입니다.',
            'status.in' => '상태는 0(비활성) 또는 1(활성)이어야 합니다.',
        ];
    }
}
