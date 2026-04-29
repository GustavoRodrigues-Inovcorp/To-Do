<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:2000',
            'priority'      => 'required|in:low,medium,high',
            'due_date'      => 'nullable|date',
            'status'        => 'sometimes|required|in:pending,in_progress,completed',
        ];
    }

    public function messages(): array
    {
        return [
        'title.required'    => 'O título é obrigatório.',
        'priority.in'       => 'A prioridade deve ser alta, média ou baixa.',
        ];
    }
}
