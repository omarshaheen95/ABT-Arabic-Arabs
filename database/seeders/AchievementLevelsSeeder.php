<?php

namespace Database\Seeders;

use App\Models\AchievementLevel;
use Illuminate\Database\Seeder;

class AchievementLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            [
                'name'  => 'البرونزي',
                'required_points' => 100,
                'badge_icon' => 'user_assets/achievement-badges/bronze.svg',
                'description' =>  'المستوى البرونزي - أول خطوة في رحلة الإنجازات',
            ],
            [
                'name' => 'الفضي',
                'required_points' => 250,
                'badge_icon' => 'user_assets/achievement-badges/silver.svg',
                'description' => 'المستوى الفضي - تطور ملحوظ في الأداء',
            ],
            [
                'name' => 'الذهبي',
                'required_points' => 500,
                'badge_icon' => 'user_assets/achievement-badges/gold.svg',
                'description' => 'المستوى الذهبي - تميز واضح في الإنجازات',

            ],
            [
                'name' => 'الياقوتي الأزرق',
                'required_points' => 1000,
                'badge_icon' => 'user_assets/achievement-badges/blue-sapphire.svg',
                'description' => 'المستوى الياقوتي الأزرق - إنجاز مميز ونادر',

            ],
            [
                'name' => 'الياقوتي الأحمر',
                'required_points' => 2000,
                'badge_icon' => 'user_assets/achievement-badges/ruby.svg',
                'description' => 'المستوى الياقوتي الأحمر - مستوى رفيع من التميز',
            ],
            [
                'name' => 'الزمردي',
                'required_points' => 3500,
                'badge_icon' => 'user_assets/achievement-badges/emerald.svg',
                'description' => 'المستوى الزمردي - إبداع وتفوق استثنائي',
                ],
            [
                'name' => 'جوهرة الأرجوان',
                'required_points' => 5000,
                'badge_icon' => 'user_assets/achievement-badges/purple-gem.svg',
                'description' => 'مستوى جوهرة الأرجوان - نخبة المتميزين',
            ],
            [
                'name' => 'جوهر الإتقان',
                'required_points' => 6000,
                'badge_icon' => 'user_assets/achievement-badges/core-mastery.svg',
                'description' => 'مستوى جوهر الإتقان - سيطرة كاملة على المهارات',
            ],
            [
                'name' =>  'اللؤلؤ',
                'required_points' => 7500,
                'badge_icon' => 'user_assets/achievement-badges/pearl.svg',
                'description' => 'مستوى اللؤلؤ - ندرة وجمال الإنجاز',
            ],
            [
                'name' => 'الحجر البركاني',
                'required_points' => 10000,
                'badge_icon' => 'user_assets/achievement-badges/volcanic-stone.svg',
                'description' => 'مستوى الحجر البركاني - قوة وثبات لا مثيل لهما',
            ],
            [
                'name' => 'نجم التفوق',

                'required_points' => 12500,
                'badge_icon' => 'user_assets/achievement-badges/star-of-excellence.svg',
                'description' => 'مستوى نجم التفوق - أداء ثابت وتميّز متقدم',
            ],
            [
                'name' => 'الماسي',
                'required_points' => 15000,
                'badge_icon' => 'user_assets/achievement-badges/diamond.svg',
                'description' => 'المستوى الماسي - قمة الإنجاز والتميز المطلق',
            ],
        ];

        $existingLevels = AchievementLevel::query()->get();

        foreach ($levels as $level) {
            $existingLevel = $existingLevels->where('required_points', $level['required_points'])->first();

            if (!$existingLevel) {
                AchievementLevel::query()->create($level);
            } else {
                $existingLevel->update($level);
            }
        }
    }
}
