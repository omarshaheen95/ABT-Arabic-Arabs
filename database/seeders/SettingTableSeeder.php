<?php

namespace Database\Seeders;

use App\Models\Manager;
use App\Models\Setting;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //['text', 'radio', 'checkbox', 'color', 'file', 'multi_select', 'select', 'number', 'password', 'textarea', 'date']
        $settings = [
            //General
            ['name' => 'Logo', 'key' => 'logo', 'value' => null, 'type' => 'file', 'group' => 'general'],
            ['name' => 'Min Logo', 'key' => 'logo_min', 'value' => null, 'type' => 'file', 'group' => 'general'],
            ['name' => 'Favicon Ico', 'key' => 'icon', 'value' => null, 'type' => 'file', 'group' => 'general'],
            ['name' => 'Arabic Name', 'key' => 'name_ar', 'value' => null, 'type' => 'text', 'group' => 'general'],
            ['name' => 'English Name', 'key' => 'name_en', 'value' => null, 'type' => 'text', 'group' => 'general'],
            ['name' => 'Mobile', 'key' => 'mobile', 'value' => null, 'type' => 'text', 'group' => 'general'],
            ['name' => 'Email', 'key' => 'email', 'value' => null, 'type' => 'text', 'group' => 'general'],

            //Social Links
            ['name' => 'Whatsapp', 'key' => 'whatsapp', 'value' => null, 'type' => 'text', 'group' => 'Social Media Links'],
            ['name' => 'Facebook', 'key' => 'facebook', 'value' => null, 'type' => 'text', 'group' => 'Social Media Links'],
            ['name' => 'Twitter', 'key' => 'twitter', 'value' => null, 'type' => 'text', 'group' => 'Social Media Links'],
            ['name' => 'Instagram', 'key' => 'instagram', 'value' => null, 'type' => 'text', 'group' => 'Social Media Links'],
            ['name' => 'Linkedin', 'key' => 'linkedin', 'value' => null, 'type' => 'text', 'group' => 'Social Media Links'],
            ['name' => 'Youtube', 'key' => 'youtube', 'value' => null, 'type' => 'text', 'group' => 'Social Media Links'],


            //SMTP
            ['name' => 'SMTP MAIL Driver', 'key' => 'smtp_mail_driver', 'value' => 'smtp', 'type' => 'text', 'group' => 'SMTP'],
            ['name' => 'SMTP MAIL HOST', 'key' => 'smtp_mail_host', 'value' => 'smtp.office365.com', 'type' => 'text', 'group' => 'SMTP'],
            ['name' => 'SMTP MAIL PORT', 'key' => 'smtp_mail_port', 'value' => 587, 'type' => 'number', 'group' => 'SMTP'],
            ['name' => 'SMTP MAIL Encryption', 'key' => 'smtp_mail_encryption', 'value' => 'tls', 'type' => 'text', 'group' => 'SMTP'],
            ['name' => 'SMTP MAIL Username', 'key' => 'smtp_mail_username', 'value' => null, 'type' => 'text', 'group' => 'SMTP'],
            ['name' => 'SMTP MAIL Password', 'key' => 'smtp_mail_password', 'value' => null, 'type' => 'password', 'group' => 'SMTP'],
            ['name' => 'SMTP MAIL From Address', 'key' => 'smtp_mail_from_address', 'value' => 'support@abt-assessments.com', 'type' => 'text', 'group' => 'SMTP'],
            ['name' => 'SMTP MAIL From Name', 'key' => 'smtp_mail_from_name', 'value' => 'ABT-Assessments', 'type' => 'text', 'group' => 'SMTP'],
            //Telegram
            ['name' => 'Telegram Bot Token', 'key' => 'telegram_bot_token', 'value' => '7204627160:AAFBry-YE65Ntg0ijk0K4TJl5ghRYO6qrBI', 'type' => 'text', 'group' => 'telegram'],
            ['name' => 'Telegram Channel ID', 'key' => 'telegram_channel_id', 'value' => '-4542490273', 'type' => 'text', 'group' => 'telegram'],
            //Captcha
            ['name' => 'reCAPTCHA secret key', 'key' => 'captcha_secret_key', 'value' => null, 'type' => 'text', 'group' => 'captcha'],
            ['name' => 'reCAPTCHA site key', 'key' => 'captcha_site_key', 'value' => null, 'type' => 'text', 'group' => 'captcha'],

            //XP Award Login Settings
            ['name' => 'Enable XP Award on Login', 'key' => 'enable_xp_award_on_login', 'value' => '0', 'type' => 'radio', 'group' => 'xp_award'],
            ['name' => 'XP Award on Login Points', 'key' => 'xp_award_on_login_points', 'value' => '10', 'type' => 'number', 'group' => 'xp_award'],
            ['name' => 'XP Award on Login Days Count', 'key' => 'xp_award_on_login_days_count', 'value' => '1', 'type' => 'number', 'group' => 'xp_award'],

            //Streak XP Award Settings
            ['name' => 'Enable Weekly Streak XP', 'key' => 'enable_weekly_streak_xp', 'value' => '1', 'type' => 'radio', 'group' => 'streak_xp'],
            ['name' => 'Weekly Streak XP Points', 'key' => 'weekly_streak_xp_points', 'value' => '50', 'type' => 'number', 'group' => 'streak_xp'],
            ['name' => 'Weekly Streak Days Required', 'key' => 'weekly_streak_days_required', 'value' => '7', 'type' => 'number', 'group' => 'streak_xp'],
            ['name' => 'Enable Monthly Streak XP', 'key' => 'enable_monthly_streak_xp', 'value' => '1', 'type' => 'radio', 'group' => 'streak_xp'],
            ['name' => 'Monthly Streak XP Points', 'key' => 'monthly_streak_xp_points', 'value' => '200', 'type' => 'number', 'group' => 'streak_xp'],
            ['name' => 'Monthly Streak Days Required', 'key' => 'monthly_streak_days_required', 'value' => '30', 'type' => 'number', 'group' => 'streak_xp'],


        ];

        $rows = Setting::query()->get();
        foreach ($settings as $setting) {
            $row = $rows->where('key', $setting['key'])->first();
            if (!$row) {
                Setting::query()->create($setting);
            } else {
                $row->update([
                    'name' => $setting['name'],
                    'type' => $setting['type'],
                    'group' => $setting['group']
                ]);
            }
        }
    }
}
