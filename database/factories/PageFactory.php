<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence();
        $slug = Str::slug($title);

        return [
            'slug' => $slug,
            'title' => $title,
            'headline' => $title,
            'body' => '<p>' . $this->faker->realText() . '</p>',
            'meta_title' => $title,
            'meta_description' => $this->faker->realText(),
            'meta_keywords' => $this->faker->realText(),
            'status_id' => Status::getDefault()->id,
        ];
    }
}
