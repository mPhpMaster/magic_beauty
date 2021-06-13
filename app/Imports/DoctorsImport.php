<?php

namespace App\Imports;

use App\Interfaces\IRoleConst;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DoctorsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $head = [
            "name" => 0,
            "email" => 1,
            "mobile" => 2,
            "password" => 3,
            "location" => 4,
        ];

        $rows->shift();
        $rows->map(function ($row, $index) use ($head) {
            $attributes = [
                'name' => $row[ $head['name'] ],
                'email' => $row[ $head['email'] ] ?: "d{$index}@mail.com",
                'mobile' => parseMobile($row[ $head['mobile'] ]),
                'location' => $row[ $head['location'] ] ?? "",
                'password' => \Hash::make($row[ $head['password'] ] ?: $row[ $head['mobile'] ]),
            ];

            return User::create($attributes)->assignRole(IRoleConst::DOCTOR_ROLE);
        });
    }
}
