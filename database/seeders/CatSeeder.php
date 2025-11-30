<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Seeder;

class CatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['en' => 'Programming', 'ar' => 'البرمجة'],
            ['en' => 'Design', 'ar' => 'التصميم'],
            ['en' => 'Marketing', 'ar' => 'التسويق'],
        ];

        $courses = [
            ['en' => 'Web Development', 'ar' => 'تطوير الويب'],
            ['en' => 'Mobile Apps', 'ar' => 'تطبيقات الجوال'],
            ['en' => 'Graphic Design', 'ar' => 'التصميم الجرافيكي'],
            ['en' => 'UI/UX Design', 'ar' => 'تصميم واجهات المستخدم'],
            ['en' => 'Digital Marketing', 'ar' => 'التسويق الرقمي'],
            ['en' => 'SEO', 'ar' => 'تحسين محركات البحث'],
            ['en' => 'Content Writing', 'ar' => 'كتابة المحتوى'],
            ['en' => 'Video Editing', 'ar' => 'مونتاج الفيديو'],
        ];

        $examNames = [
            ['en' => 'Fundamentals', 'ar' => 'الأساسيات'],
            ['en' => 'Advanced Concepts', 'ar' => 'المفاهيم المتقدمة'],
        ];

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

        $courseIndex = 0;

        foreach ($categories as $categoryData) {
            $cat = Cat::create([
                'name' => [
                    'en' => $categoryData['en'],
                    'ar' => $categoryData['ar'],
                ],
                'active' => true,
            ]);

            // Create 8 courses per category
            for ($c = 0; $c < 8; $c++) {
                $courseData = $courses[$courseIndex % count($courses)];
                $courseIndex++;

                $course = Course::create([
                    'name' => [
                        'en' => $courseData['en'],
                        'ar' => $courseData['ar'],
                    ],
                    'img' => "courses/{$courseIndex}.png",
                    'cat_id' => $cat->id,
                    'active' => true,
                ]);

                // Create 2 exams per course
                for ($e = 0; $e < 2; $e++) {
                    $examNameData = $examNames[$e % count($examNames)];

                    $exam = Exam::create([
                        'name' => [
                            'en' => $examNameData['en'],
                            'ar' => $examNameData['ar'],
                        ],
                        'desc' => [
                            'en' => 'This exam covers important topics and skills. It includes multiple choice questions designed to test your knowledge and understanding. You will be evaluated on your comprehension of key concepts and ability to apply them in practical scenarios. The exam is timed and requires careful attention to detail.',
                            'ar' => 'يغطي هذا الاختبار موضوعات ومهارات مهمة. يتضمن أسئلة متعددة الخيارات مصممة لاختبار معرفتك وفهمك. سيتم تقييمك على فهمك للمفاهيم الأساسية وقدرتك على تطبيقها في سيناريوهات عملية. الاختبار محدد بوقت ويتطلب اهتماماً دقيقاً بالتفاصيل.',
                        ],
                        'img' => "exams/" . (($courseIndex * 2) + $e) . ".png",
                        'questions_no' => 15,
                        'difficulty' => rand(1, 5),
                        'duration_mins' => rand(1, 3) * 30,
                        'course_id' => $course->id,
                        'active' => true,
                    ]);

                    // Create 15 questions per exam
                    for ($q = 0; $q < 15; $q++) {
                        $questionData = $questions[$q % count($questions)];

                        Question::create([
                            'title' => $questionData['title'],
                            'option_1' => $questionData['options'][0],
                            'option_2' => $questionData['options'][1],
                            'option_3' => $questionData['options'][2],
                            'option_4' => $questionData['options'][3],
                            'right_ans' => rand(1, 4),
                            'exam_id' => $exam->id,
                        ]);
                    }
                }
            }
        }
    }
}
