<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            [
                'en' => [
                    'name' => 'Finance',
                ],
                'ru' => [
                    'name' => 'финансы'
                ]

            ],
            [
                'en' => [
                    'name' => 'Social',
                ],
                'ru' => [
                    'name' => 'Общественность'
                ]
            ],
            [
                'en' => [
                    'name' => 'Family',
                ],
                'ru' => [
                    'name' => 'Семья'
                ]
            ],
            [
                'en' => [
                    'name' => 'Business',
                ],
                'ru' => [
                    'name' => 'Бизнес'
                ]
            ],
            [
                'en' => [
                    'name' => 'Others',
                ],
                'ru' => [
                    'name' => 'Другое'
                ]
            ],
        ];

        foreach ($data as $category) {
            Category::create($category);
        }
    }
}
