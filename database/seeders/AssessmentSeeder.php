<?php

namespace Database\Seeders;

use App\Models\Assessment;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $assessments = [
            [
                'slug'              => 'holland',
                'name'              => 'اختبار هولاند (RIASEC)',
                'type'              => 'interests',
                'description'       => 'اكتشف ميولك المهنية وفق نظرية RIASEC',
                'icon'              => 'fa-compass',
                'estimated_minutes' => 20,
                'is_active'         => true,
                'config_json'       => [
                    'scale' => [1=>'لا أوافق بشدة',2=>'لا أوافق',3=>'محايد',4=>'أوافق',5=>'أوافق بشدة'],
                    'dimensions' => ['R'=>'واقعي','I'=>'بحثي','A'=>'فني','S'=>'اجتماعي','E'=>'ريادي','C'=>'تقليدي'],
                    'interpretations' => [],
                ],
            ],
            [
                'slug'              => 'mbti',
                'name'              => 'اختبار MBTI',
                'type'              => 'personality',
                'description'       => 'تحليل نمط شخصيتك من 16 نمطاً',
                'icon'              => 'fa-brain',
                'estimated_minutes' => 15,
                'is_active'         => true,
                'config_json'       => [
                    'scale' => [1=>'لا أوافق بشدة',2=>'لا أوافق',3=>'محايد',4=>'أوافق',5=>'أوافق بشدة'],
                    'dimensions' => ['E'=>'انبساطي','I'=>'انطوائي','S'=>'حسي','N'=>'حدسي','T'=>'تفكيري','F'=>'وجداني','J'=>'قضائي','P'=>'إدراكي'],
                    'interpretations' => [],
                ],
            ],
            [
                'slug'              => 'multiple-intelligences',
                'name'              => 'اختبار الذكاءات المتعددة',
                'type'              => 'intelligence',
                'description'       => 'اكتشف أنواع ذكاءاتك وكيف تستثمرها',
                'icon'              => 'fa-lightbulb',
                'estimated_minutes' => 20,
                'is_active'         => true,
                'config_json'       => [
                    'scale' => [1=>'لا أوافق بشدة',2=>'لا أوافق',3=>'محايد',4=>'أوافق',5=>'أوافق بشدة'],
                    'dimensions' => ['linguistic'=>'لغوي','logical'=>'منطقي','spatial'=>'مكاني','musical'=>'موسيقي','bodily'=>'جسدي-حركي','interpersonal'=>'اجتماعي','intrapersonal'=>'ذاتي','naturalist'=>'طبيعي'],
                    'interpretations' => [],
                ],
            ],
            [
                'slug'              => 'work-values',
                'name'              => 'اختبار القيم المهنية',
                'type'              => 'values',
                'description'       => 'اكتشف قيمك في بيئة العمل',
                'icon'              => 'fa-star',
                'estimated_minutes' => 10,
                'is_active'         => true,
                'config_json'       => [
                    'scale' => [1=>'لا أوافق بشدة',2=>'لا أوافق',3=>'محايد',4=>'أوافق',5=>'أوافق بشدة'],
                    'dimensions' => [],
                    'interpretations' => [],
                ],
            ],
            [
                'slug'              => 'career-fit',
                'name'              => 'اختبار التوافق المهني',
                'type'              => 'career',
                'description'       => 'اكتشف المهن الأنسب لشخصيتك ومهاراتك',
                'icon'              => 'fa-bullseye',
                'estimated_minutes' => 15,
                'is_active'         => true,
                'config_json'       => [
                    'scale' => [1=>'لا أوافق بشدة',2=>'لا أوافق',3=>'محايد',4=>'أوافق',5=>'أوافق بشدة'],
                    'dimensions' => [],
                    'interpretations' => [],
                ],
            ],
        ];

        foreach ($assessments as $data) {
            Assessment::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
