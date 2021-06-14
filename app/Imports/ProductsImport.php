<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{
    protected $user_id;

    public function __construct($user_id = 0)
    {
        $this->user_id = $user_id;
    }

    public function collection(Collection $rows)
    {
        $head = [
            "name" => 0,
            "category_name" => 1,
            "description" => 2,
            "price" => 3,
            "quantity" => 4,
            "name_ar" => 5,
            "description_ar" => 6,
            "category_name_ar" => 7,
        ];

        $branch = $this->user_id ? Branch::where('user_id', $this->user_id)->first() : Branch::first();
        $branchId = $branch ? $branch->id : 0;

        foreach ($rows as $index => $row) {
            if ( trim($index) === "0" ) {
                continue;
            }

            $category = Category::firstOrCreate([
                'name_en' => trim($row[ $head["category_name"] ]),
                'name_ar' => trim($row[ $head["category_name_ar"] ]),
            ], [
                'category_id' => 0,
            ]);
            try {
                $product = Product::create([
                    'category_id' => $category ? $category->id : 0,
//                    'branch_id' => $branchId,
                    'name_en' => trim($row[ $head["name"] ]),
                    'name_ar' => trim($row[ $head["name_ar"] ]),
                    'description' => trim($row[ $head["description"] ]),
                    'price' => $row[ $head["price"] ],
//                    'qty' => $row[ $head["quantity"] ]
                ]);

                if ( file_exists($image_path = base_path("products_images/" . ($index + 1) . ".png")) ) {
                    $product->addImage($image_path, true);
                }
                $product->updateQty($branchId, (double)$row[ $head["quantity"] ]);
            } catch (\Exception $exception) {
                dd($exception);
            }
        }
    }
}
