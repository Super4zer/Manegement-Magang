<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'office_start_time',
                'value' => '08:00',
                'type' => 'time',
                'description' => 'Jam masuk kantor'
            ],
            [
                'key' => 'late_tolerance_time',
                'value' => '08:15',
                'type' => 'time',
                'description' => 'Batas toleransi terlambat'
            ],
            [
                'key' => 'office_end_time',
                'value' => '17:00',
                'type' => 'time',
                'description' => 'Jam pulang kantor'
            ],
            [
                'key' => 'office_latitude',
                'value' => '-7.052683',
                'type' => 'string',
                'description' => 'Latitude lokasi kantor'
            ],
            [
                'key' => 'office_longitude',
                'value' => '110.469375',
                'type' => 'string',
                'description' => 'Longitude lokasi kantor'
            ],
            [
                'key' => 'max_checkin_distance',
                'value' => '100',
                'type' => 'integer',
                'description' => 'Jarak maksimal check-in (meter)'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
