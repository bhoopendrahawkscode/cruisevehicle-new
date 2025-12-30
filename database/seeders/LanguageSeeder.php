<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $langs = [
            "name" => "English",
            'locale' => 'en',
            'status' => 1
        ];
        Language::updateOrCreate(['name' => $langs['name']], $langs);
    }
}
