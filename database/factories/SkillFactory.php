<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Skill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $i = 0;
        $i++;

        $skills = [
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

        $skill = $this->faker->randomElement($skills);

        return [
            'name' => [
                'en' => $skill['en'],
                'ar' => $skill['ar'],
            ],
            'img' => "skills/$i.png",
            'active' => true,
        ];
    }
}
