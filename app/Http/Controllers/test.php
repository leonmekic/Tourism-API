<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class test extends Controller
{
    public function __invoke(Request $request)
    {
        $data = [
            [
                'name'    => 'pallazza',
                'address' => 'osijecka 12',
                'rating'  => '4/5'
            ],
            [
                'name'    => 'burek',
                'address' => 'osijecka 55',
                'rating'  => '5/5'
            ]
        ];

        $errors = [
            'message' => 'Validation error',

        ];

        return $this->outWithError($data);

    }
}
