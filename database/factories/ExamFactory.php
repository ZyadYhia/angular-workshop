<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exam::class;

    /**
     * Get Faker instance.
     */
    protected function faker(): \Faker\Generator
    {
        return $this->faker ?: \Faker\Factory::create('en_US');
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

        $examNames = [
            ['en' => 'Fundamentals', 'ar' => 'الأساسيات'],
            ['en' => 'Advanced Concepts', 'ar' => 'المفاهيم المتقدمة'],
            ['en' => 'Practical Applications', 'ar' => 'التطبيقات العملية'],
            ['en' => 'Best Practices', 'ar' => 'أفضل الممارسات'],
            ['en' => 'Project Development', 'ar' => 'تطوير المشاريع'],
            ['en' => 'Professional Techniques', 'ar' => 'التقنيات الاحترافية'],
        ];

        $name = $this->faker()->randomElement($examNames);

        return [
            'name' => [
                'en' => $name['en'],
                'ar' => $name['ar'],
            ],
            'desc' => [
                'en' => 'This exam covers important topics and skills. It includes multiple choice questions designed to test your knowledge and understanding. You will be evaluated on your comprehension of key concepts and ability to apply them in practical scenarios. The exam is timed and requires careful attention to detail.',
                'ar' => 'يغطي هذا الاختبار موضوعات ومهارات مهمة. يتضمن أسئلة متعددة الخيارات مصممة لاختبار معرفتك وفهمك. سيتم تقييمك على فهمك للمفاهيم الأساسية وقدرتك على تطبيقها في سيناريوهات عملية. الاختبار محدد بوقت ويتطلب اهتماماً دقيقاً بالتفاصيل.',
            ],
            'img' => "exams/$i.png",
            'questions_no' => 15,
            'difficulty' => $this->faker()->numberBetween(1, 5),
            'duration_mins' => $this->faker()->numberBetween(1, 3) * 30,
            'active' => true,
        ];
    }
}
