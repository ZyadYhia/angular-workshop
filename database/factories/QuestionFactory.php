<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Get Faker instance.
     */
    protected function faker(): \Faker\Generator
    {
        if ($this->faker) {
            return $this->faker;
        }

        if (app()->bound(\Faker\Generator::class)) {
            return app(\Faker\Generator::class);
        }

        return \Faker\Factory::create(config('app.faker_locale', 'en_US'));
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $questions = [
            [
                'title' => ['en' => 'What is the correct syntax for this concept?', 'ar' => 'ما هو التركيب الصحيح لهذا المفهوم؟'],
                'options' => [
                    ['en' => 'Option A', 'ar' => 'الخيار أ'],
                    ['en' => 'Option B', 'ar' => 'الخيار ب'],
                    ['en' => 'Option C', 'ar' => 'الخيار ج'],
                    ['en' => 'Option D', 'ar' => 'الخيار د'],
                ],
            ],
            [
                'title' => ['en' => 'Which statement best describes this process?', 'ar' => 'أي عبارة تصف هذه العملية بشكل أفضل؟'],
                'options' => [
                    ['en' => 'First choice', 'ar' => 'الاختيار الأول'],
                    ['en' => 'Second choice', 'ar' => 'الاختيار الثاني'],
                    ['en' => 'Third choice', 'ar' => 'الاختيار الثالث'],
                    ['en' => 'Fourth choice', 'ar' => 'الاختيار الرابع'],
                ],
            ],
            [
                'title' => ['en' => 'How would you implement this feature?', 'ar' => 'كيف ستقوم بتنفيذ هذه الميزة؟'],
                'options' => [
                    ['en' => 'Method one', 'ar' => 'الطريقة الأولى'],
                    ['en' => 'Method two', 'ar' => 'الطريقة الثانية'],
                    ['en' => 'Method three', 'ar' => 'الطريقة الثالثة'],
                    ['en' => 'Method four', 'ar' => 'الطريقة الرابعة'],
                ],
            ],
        ];

        $question = $this->faker()->randomElement($questions);

        return [
            'title' => $question['title'],
            'option_1' => $question['options'][0],
            'option_2' => $question['options'][1],
            'option_3' => $question['options'][2],
            'option_4' => $question['options'][3],
            'right_ans' => $this->faker()->numberBetween(1, 4),
        ];
    }
}
