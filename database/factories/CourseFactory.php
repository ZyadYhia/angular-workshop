<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

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
        static $i = 0;
        $i++;

        $courses = [
            ['en' => 'Web Development', 'ar' => 'تطوير الويب'],
            ['en' => 'Mobile Apps', 'ar' => 'تطبيقات الجوال'],
            ['en' => 'Graphic Design', 'ar' => 'التصميم الجرافيكي'],
            ['en' => 'UI/UX Design', 'ar' => 'تصميم واجهات المستخدم'],
            ['en' => 'Digital Marketing', 'ar' => 'التسويق الرقمي'],
            ['en' => 'SEO', 'ar' => 'تحسين محركات البحث'],
            ['en' => 'Content Writing', 'ar' => 'كتابة المحتوى'],
            ['en' => 'Video Editing', 'ar' => 'مونتاج الفيديو'],
            ['en' => 'Photography', 'ar' => 'التصوير الفوتوغرافي'],
            ['en' => 'Data Science', 'ar' => 'علم البيانات'],
            ['en' => 'Machine Learning', 'ar' => 'التعلم الآلي'],
            ['en' => 'Cybersecurity', 'ar' => 'الأمن السيبراني'],
        ];

        $course = $this->faker()->randomElement($courses);

        return [
            'name' => [
                'en' => $course['en'],
                'ar' => $course['ar'],
            ],
            'img' => "courses/$i.png",
            'active' => true,
        ];
    }
}
