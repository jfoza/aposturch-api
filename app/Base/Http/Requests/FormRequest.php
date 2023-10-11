<?php

namespace App\Base\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

abstract class FormRequest extends LaravelFormRequest
{
    CONST PAGE         = 'page';
    CONST PER_PAGE     = 'perPage';
    CONST COLUMN_ORDER = 'columnOrder';
    CONST COLUMN_NAME  = 'columnName';

    abstract public function rules();

    abstract public function authorize();

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    public function mergePaginationOrderRules(array $filters = [], bool $required = false): array
    {
        $paginationRules = $required ? 'required|integer' : 'nullable|integer';

        $paginationOrderRules = [
            self::PAGE         => $paginationRules,
            self::PER_PAGE     => $paginationRules,
            self::COLUMN_ORDER => 'nullable|string|in:asc,desc',
            self::COLUMN_NAME  => 'nullable|string',
        ];

        return array_merge($paginationOrderRules, $filters);
    }

    public function mergePaginationOrderAttributes(array $attributes = []): array
    {
        $paginationOrderAttributes = [
            self::PAGE         => 'Page',
            self::PER_PAGE     => 'Per Page',
            self::COLUMN_ORDER => 'Column Order',
            self::COLUMN_NAME  => 'Column Name',
        ];

        return array_merge($paginationOrderAttributes, $attributes);
    }
}

