<?php

return [

    'accepted'             => ':attribute phải được chấp nhận.',
    'active_url'           => ':attribute không phải là URL hợp lệ.',
    'after'                => ':attribute phải là ngày sau :date.',
    'after_or_equal'       => ':attribute phải là ngày sau hoặc bằng :date.',
    'alpha'                => ':attribute chỉ được chứa chữ cái.',
    'alpha_dash'           => ':attribute chỉ được chứa chữ cái, số, dấu gạch ngang và gạch dưới.',
    'alpha_num'            => ':attribute chỉ được chứa chữ cái và số.',
    'array'                => ':attribute phải là một mảng.',
    'before'               => ':attribute phải là ngày trước :date.',
    'before_or_equal'      => ':attribute phải là ngày trước hoặc bằng :date.',
    'between'              => [
        'numeric' => ':attribute phải nằm giữa :min và :max.',
        'file'    => ':attribute phải có dung lượng từ :min đến :max KB.',
        'string'  => ':attribute phải có độ dài từ :min đến :max ký tự.',
        'array'   => ':attribute phải có từ :min đến :max phần tử.',
    ],
    'boolean'              => ':attribute phải là true hoặc false.',
    'confirmed'            => ':attribute xác nhận không khớp.',
    'date'                 => ':attribute không phải là ngày hợp lệ.',
    'date_equals'          => ':attribute phải là ngày bằng :date.',
    'date_format'          => ':attribute không đúng định dạng :format.',
    'different'            => ':attribute phải khác :other.',
    'digits'               => ':attribute phải gồm :digits chữ số.',
    'digits_between'       => ':attribute phải có từ :min đến :max chữ số.',
    'email'                => ':attribute phải là địa chỉ email hợp lệ.',
    'exists'               => ':attribute không tồn tại.',
    'file'                 => ':attribute phải là một tập tin.',
    'filled'               => ':attribute không được để trống.',
    'gt'                   => [
        'numeric' => ':attribute phải lớn hơn :value.',
        'file'    => ':attribute phải lớn hơn :value KB.',
        'string'  => ':attribute phải dài hơn :value ký tự.',
        'array'   => ':attribute phải có nhiều hơn :value phần tử.',
    ],
    'gte'                  => [
        'numeric' => ':attribute phải lớn hơn hoặc bằng :value.',
        'file'    => ':attribute phải lớn hơn hoặc bằng :value KB.',
        'string'  => ':attribute phải dài hơn hoặc bằng :value ký tự.',
        'array'   => ':attribute phải có ít nhất :value phần tử.',
    ],
    'image'                => ':attribute phải là hình ảnh.',
    'in'                   => ':attribute không hợp lệ.',
    'integer'              => ':attribute phải là số nguyên.',
    'ip'                   => ':attribute phải là địa chỉ IP hợp lệ.',
    'max'                  => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'file'    => ':attribute không được lớn hơn :max KB.',
        'string'  => ':attribute không được dài quá :max ký tự.',
        'array'   => ':attribute không được có quá :max phần tử.',
    ],
    'mimes'                => ':attribute phải có định dạng: :values.',
    'min'                  => [
        'numeric' => ':attribute phải ít nhất là :min.',
        'file'    => ':attribute phải có dung lượng tối thiểu :min KB.',
        'string'  => ':attribute phải có ít nhất :min ký tự.',
        'array'   => ':attribute phải có ít nhất :min phần tử.',
    ],
    'not_in'               => ':attribute không hợp lệ.',
    'numeric'              => ':attribute phải là số.',
    'regex'                => ':attribute không đúng định dạng.',
    'required'             => ':attribute không được để trống.',
    'required_if'          => ':attribute là bắt buộc.',
    'required_unless'      => ':attribute là bắt buộc.',
    'required_with'        => ':attribute là bắt buộc.',
    'required_with_all'    => ':attribute là bắt buộc.',
    'required_without'     => ':attribute là bắt buộc.',
    'required_without_all' => ':attribute là bắt buộc.',
    'same'                 => ':attribute phải giống :other.',
    'size'                 => [
        'numeric' => ':attribute phải bằng :size.',
        'file'    => ':attribute phải có dung lượng :size KB.',
        'string'  => ':attribute phải dài :size ký tự.',
        'array'   => ':attribute phải chứa :size phần tử.',
    ],
    'string'               => ':attribute phải là chuỗi ký tự.',
    'timezone'             => ':attribute không phải múi giờ hợp lệ.',
    'unique'               => ':attribute đã tồn tại.',
    'url'                  => ':attribute không đúng định dạng URL.',

    /*
    |--------------------------------------------------------------------------
    | Custom Attributes
    |--------------------------------------------------------------------------
    */
    'attributes'           => [
        'name'                  => 'Tên',
        'email'                 => 'Email',
        'password'              => 'Mật khẩu',
        'password_confirmation' => 'Xác nhận mật khẩu',
        'current_password'      => 'Mật khẩu hiện tại',
        'phone'                 => 'Số điện thoại',
        'avatar'                => 'Ảnh đại diện',
    ],

];
