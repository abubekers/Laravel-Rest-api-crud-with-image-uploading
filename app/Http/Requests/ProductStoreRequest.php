<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class ProductStoreRequest extends FormRequest
{
    public function authorize()
    {
        //return false;
        return true;
    }
 
    public function rules()
    {
        if(request()->isMethod('post')) {
            return [
                'name' => 'required|string|max:50',
                'description' => 'required|string|max:250',
                'type'=>'required|between:0,4',
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ];
        } else {
            return [
                'name' => 'required|string|max:258',
                'description' => 'required|string',
                'type'=>'required|digits_between:0,4',
                'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ];
        }
    }
 
    public function messages()
    {
        if(request()->isMethod('post')) {
            return [
                'name' => 'required|string|max:258',
                'description' => 'required|string',
                'type'=>'required|digits_between:0,4',
                'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ];
        } else {
            return [
                'name.required' => 'Name is required!',
                'description.required' => 'Descritpion is required!'
            ];   
        }
    }
}