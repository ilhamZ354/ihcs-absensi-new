<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute harus diterima.',
    'active_url'           => ':attribute bukan format URL yang benar.',
    'after'                => ':attribute nilainya harus setelah :date.',
    'after_or_equal'       => ':attribute harus tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka atau tanda sambung.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus bertipe array.',
    'before'               => ':attribute nilainya harus sebelum :date.',
    'before_or_equal'      => ':attribute harus tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'file'    => ':attribute harus berukuran :min dan :max kilobyte.',
        'string'  => ':attribute harus berisi antara :min dan :max karakter.',
        'array'   => ':attribute harus berisi antara :min dan :max item.',
    ],
    'boolean'              => ':attribute harus bernilai true ata false.',
    'confirmed'            => 'Konfirmasi :attribute. tidak sama',
    'date'                 => 'Format tanggal :attribute tidak benar.',
    'date_format'          => ':attribute formatnya tidak sesuai dengan :format.',
    'different'            => 'Nilai :attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus bernilai :digits digit.',
    'digits_between'       => ':attribute harus bernilai antara :min dan :max digit.',
    'dimensions'           => 'Dimensi gambar :attribute tidak sesuai.',
    'distinct'             => ':attribute mempunyai nilai ganda.',
    'email'                => 'Format email :attribute tidak benar.',
    'exists'               => ':attribute yang anda pilih tidak sah.',
    'file'                 => ':attribute harus berupa file.',
    'filled'               => ':attribute harus berisi nilai.',
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak benar.',
    'in_array'             => ':attribute tidak terdaftar di :other.',
    'integer'              => ':attribute harus bernilai integer.',
    'ip'                   => ':attribute harus berupa alamat IP.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6.',
    'json'                 => ':attribute harus berupa string JSON.',
    'max'                  => [
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'file'    => ':attribute tidak boleh lebih besar dari :max kilobyte.',
        'string'  => ':attribute tidak boleh lebih besar dari :max karakter.',
        'array'   => ':attribute tidak boleh lebih dari :max item.',
    ],
    'mimes'                => ':attribute format filenynya harus berupa :values.',
    'mimetypes'            => ':attribute format filenynya harus berupa :values.',
    'min'                  => [
        'numeric' => ':attribute minimal bernilai :min.',
        'file'    => ':attribute minimal bernilai :min kilobyte.',
        'string'  => ':attribute minimal bernilai :min karakter.',
        'array'   => ':attribute minimal berisi :min item.',
    ],
    'not_in'               => ':attribute dipilih tidak benar.',
    'numeric'              => ':attribute harus berupa angka.',
    'present'              => ':attribute harus ditampilkan.',
    'regex'                => 'Format :attribute tidak benar.',
    'required'             => ':attribute harus diisi.',
    'required_if'          => ':attribute harus diisi jika :other bernilai :value.',
    'required_unless'      => ':attribute harus diisi kecuali :other berisi nilai :values.',
    'required_with'        => ':attribute harus diisi jika :values berisi nilai.',
    'required_with_all'    => ':attribute harus diisi jika :values berisi nilai.',
    'required_without'     => ':attribute harus diisi jika :values tidak tampil.',
    'required_without_all' => ':attribute harus diisi jika tidak satupun :values yang tampil.',
    'same'                 => ':attribute and :other must match.',
    'size'                 => [
        'numeric' => ':attribute harus berukuran :size.',
        'file'    => ':attribute harus berukuran :size kilobyte.',
        'string'  => ':attribute harus berisi :size karakter.',
        'array'   => ':attribute harus mengandung :size item.',
    ],
    'string'               => ':attribute harus bertipe string.',
    'timezone'             => 'Zona waktu :attribute harus benar.',
    'unique'               => ':attribute telah ada di database.',
    'uploaded'             => ':attribute gagal diupload.',
    'url'                  => 'Format :attribute tidak benar.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
