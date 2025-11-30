<?php

namespace Database\Factories;

use App\Models\Cat;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cat::class;

    /**
     * Get Faker instance.
     */
    protected function faker(): \Faker\Generator
    {
        return $this->faker ?: app(\Faker\Generator::class);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categories = [
            ['en' => 'Programming', 'ar' => 'البرمجة'],
            ['en' => 'Design', 'ar' => 'التصميم'],
            ['en' => 'Marketing', 'ar' => 'التسويق'],
            ['en' => 'Business', 'ar' => 'الأعمال'],
            ['en' => 'Photography', 'ar' => 'التصوير'],
            ['en' => 'Music', 'ar' => 'الموسيقى'],
            ['en' => 'Health', 'ar' => 'الصحة'],
            ['en' => 'Fitness', 'ar' => 'اللياقة البدنية'],
            ['en' => 'Languages', 'ar' => 'اللغات'],
            ['en' => 'Science', 'ar' => 'العلوم'],
        ];

        $category = $this->faker()->randomElement($categories);

        return [
            'name' => [
                'en' => $category['en'],
                'ar' => $category['ar'],
            ],
            'active' => true,
        ];
    }
}
