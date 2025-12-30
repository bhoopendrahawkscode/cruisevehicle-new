<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\CarModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CarModelsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $brand = Brand::firstOrCreate(['name' => trim($row['make'])]);

        // Check if the car model already exists for this brand
        $existingModel = CarModel::where('brand_id', $brand->id)
            ->where('name', trim($row['model']))
            ->first();

        if ($existingModel) {
            return null; 
        }

        return new CarModel([
            'brand_id' => $brand->id,
            'name' => trim($row['model']),
        ]);
    }
}
