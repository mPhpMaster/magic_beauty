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
   
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    public function collection(Collection $rows)
    {
        $branchId = Branch::where('user_id',$this->user_id)->first()->id;
        $i=0;
        foreach ($rows as $row) 
        {
            if($i >0){
            $category = Category::where(['name' =>  $row[1]])->count();
            if(!$category){
               
                $category = Category::create(['name'=>$row[1],'branch_id'=>$branchId,'category_id'=>0]);
            }
            
            Product::create([
                'category_id'=>$category->id,
                'branch_id'=>$branchId,
                'name' => $row[0],
                'category_name'=> $row[1],
                'description'=>$row[2],
                'price'=>$row[3],
                'quantity'=>$row[4]
            ]);
            }
            $i+=1;
        }
    }
}
