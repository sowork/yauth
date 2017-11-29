<?php

namespace Sowork\YAuth\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemPost extends FormRequest
{
    /**
     * 如何自定义返回内容？有下面几种方式
     * 1. 重写本类的 formatErrors() 方法
     * 2. 重写本类的 response() 方法  #该方法其实只是将formatErrors的内容直接返回，并对ajax和http两种请求返回格式做了封装
     * 3. 在全局异常类handler中捕捉 ValidationException 返回自定义内容
     *
     * 建议：如果返回内容格式相同，可以采用handler类捕捉异常的方式，如果返回格式不同，可以在自定义formrequest类中重写formatErrors（）方法
     */

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
            //
            '*.item_name' => 'required|max:191|unique:yauth_items,item_name',
            '*.item_type' => 'required|integer|in:1,2',
            '*.item_desc' => 'max:191'
        ];
    }

    public function formatErrors(Validator $validator)
    {
        $response['data'] = $validator->getMessageBag()->toArray();
        $response['msg'] = '数据校验失败';
        $response['isError'] = true;

        return $response;
    }

}
