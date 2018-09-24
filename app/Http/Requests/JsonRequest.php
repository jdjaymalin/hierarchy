<?php namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class JsonRequest
 * @package App\Http\Requests
 *
 * @property string data
 */
class JsonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'required|string'
        ];
    }

    /**
     * @return array
     * @throws HttpResponseException
     */
    public function getEntitiesData() : array
    {
        $dataArray = json_decode($this->data, true);

        if ($dataArray === null) {
            $this->errorResponse(['JSON input is not valid']);
        }

        return $dataArray;
    }

    /**
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator) : void
    {
        $this->errorResponse([$validator->errors()->toArray()]);
    }

    /**
     * @param array $error
     * @throws HttpResponseException
     */
    private function errorResponse(array $error) : void
    {
        throw new HttpResponseException(response()->json(['errors' => $error], 422));
    }
}
