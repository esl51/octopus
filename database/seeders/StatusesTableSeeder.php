<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'en' => ['name' => 'Draft'],
                'ru' => ['name' => 'Черновик'],
                'is_published' => false,
                'is_default' => true,
                'variant' => 'warning',
            ],
            [
                'en' => ['name' => 'Published'],
                'ru' => ['name' => 'Опубликовано'],
                'is_published' => true,
                'is_default' => false,
                'variant' => 'success',
            ],
        ];

        foreach ($statuses as $item) {
            $status = Status::create($item);
        }
    }
}
