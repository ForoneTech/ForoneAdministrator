<?php namespace Forone\Requests;

class CreateAdminRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'name' => 'required|max:20',
            'email' => 'required|email|unique:'.config('forone.auth.administrator_table'),
            'password' => 'required|max:20',
        ];
	}

}
