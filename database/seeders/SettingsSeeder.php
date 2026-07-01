<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'school_name'       => 'আল-আমিন স্কুল ও মাদ্রাসা',
            'school_name_en'    => 'Al-Amin School & Madrasa',
            'school_address'    => 'গ্রাম: ------, উপজেলা: ------, জেলা: ------',
            'school_phone'      => '01XXXXXXXXX',
            'school_email'      => 'info@school.com',
            'school_website'    => '',
            'school_logo'       => '',
            'school_type'       => 'both',
            'language'          => 'bn',
            'font_size'         => 'medium',
            'font_family'       => 'hind',
            'currency_symbol'   => '৳',
            'date_format'       => 'd/m/Y',
            'academic_year'     => '2024-2025',
            'result_system'     => 'gpa',
            'pass_marks'        => '33',
            'sms_enabled'       => '0',
            'sms_api_key'       => '',
            'footer_text'       => 'কম্পিউটার প্রিন্টেড — স্বাক্ষর প্রয়োজন নেই',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->command->info('Settings seeded!');
    }
}
