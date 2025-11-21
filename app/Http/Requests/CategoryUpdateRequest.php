<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Enums\CategoryType;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
    $category_id_from_url = $this->route('category'); 

    $category = Category::find($category_id_from_url);

    return $category && $category->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes','string','max:255'],
            'type' => ['sometimes',new Enum(CategoryType::class)],
        ];
    }
}
