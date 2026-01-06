<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Intern;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\Attendance;
use App\Models\Report;
use App\Models\Assessment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@magang.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Pembimbing
        $pembimbing = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.pembimbing@magang.com',
            'password' => Hash::make('password'),
            'role' => 'pembimbing',
        ]);

        $pembimbing2 = User::create([
            'name' => 'Siti Rahayu',
            'email' => 'siti.pembimbing@magang.com',
            'password' => Hash::make('password'),
            'role' => 'pembimbing',
        ]);

        // Create Interns
        $internUsers = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@siswa.com'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@siswa.com'],
            ['name' => 'Rizky Pratama', 'email' => 'rizky@siswa.com'],
            ['name' => 'Maya Sari', 'email' => 'maya@siswa.com'],
            ['name' => 'Dimas Nugroho', 'email' => 'dimas@siswa.com'],
        ];

        $schools = ['SMK Negeri 1 Jakarta', 'SMK Negeri 2 Bandung', 'SMK Telkom Surabaya', 'SMK Negeri 4 Semarang'];
        $departments = ['Rekayasa Perangkat Lunak', 'Teknik Komputer Jaringan', 'Multimedia', 'Sistem Informasi'];

        $interns = [];
        foreach ($internUsers as $index => $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'role' => 'intern',
            ]);

            $intern = Intern::create([
                'user_id' => $user->id,
                'nis' => 'NIS' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'school' => $schools[array_rand($schools)],
                'department' => $departments[array_rand($departments)],
                'phone' => '08' . rand(1000000000, 9999999999),
                'address' => 'Jl. Contoh No. ' . ($index + 1) . ', Jakarta',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(4),
                'status' => 'active',
                'supervisor_id' => $index < 3 ? $pembimbing->id : $pembimbing2->id,
            ]);

            $interns[] = $intern;
        }

        // Create Tasks with on-time and late submissions
        $taskTitles = [
            'Membuat halaman login',
            'Desain database',
            'Implementasi API REST',
            'Testing aplikasi',
            'Dokumentasi sistem',
            'Bug fixing homepage',
            'Optimisasi performa',
            'Membuat laporan progress',
            'Design UI Dashboard',
            'Integrasi Payment Gateway',
        ];

        $priorities = ['low', 'medium', 'high'];

        foreach ($interns as $intern) {
            $numTasks = rand(5, 8);
            for ($i = 0; $i < $numTasks; $i++) {
                $isCompleted = rand(0, 100) > 30; // 70% chance completed
                $isLate = $isCompleted && rand(0, 100) > 60; // 40% of completed are late
                
                $deadline = Carbon::now()->subDays(rand(1, 10));
                $deadlineTime = sprintf('%02d:%02d', rand(14, 23), rand(0, 59));
                
                $startedAt = null;
                $completedAt = null;
                $submittedAt = null;
                $status = 'pending';

                if ($isCompleted) {
                    $status = 'completed';
                    $startedAt = $deadline->copy()->subDays(rand(3, 7));
                    
                    if ($isLate) {
                        // Submitted after deadline
                        $completedAt = $deadline->copy()->addDays(rand(1, 3));
                    } else {
                        // Submitted before deadline
                        $completedAt = $deadline->copy()->subDays(rand(0, 2));
                    }
                    $submittedAt = $completedAt;
                } elseif (rand(0, 1)) {
                    $status = 'in_progress';
                    $startedAt = Carbon::now()->subDays(rand(1, 3));
                    $deadline = Carbon::now()->addDays(rand(1, 7)); // Future deadline
                }

                Task::create([
                    'title' => $taskTitles[array_rand($taskTitles)],
                    'description' => 'Deskripsi detail untuk tugas ini. Mohon dikerjakan dengan baik dan sesuai requirement.',
                    'intern_id' => $intern->id,
                    'assigned_by' => $intern->supervisor_id ?? $admin->id,
                    'priority' => $priorities[array_rand($priorities)],
                    'status' => $status,
                    'deadline' => $deadline,
                    'deadline_time' => $deadlineTime,
                    'started_at' => $startedAt,
                    'completed_at' => $completedAt,
                    'submitted_at' => $submittedAt,
                    'is_late' => $isLate,
                    'estimated_hours' => rand(4, 24),
                ]);
            }
        }

        // Create Attendances for last 14 days
        $attendanceStatuses = ['present', 'present', 'present', 'present', 'late', 'absent', 'sick', 'permission'];
        
        foreach ($interns as $intern) {
            for ($day = 13; $day >= 0; $day--) {
                $date = Carbon::now()->subDays($day);
                
                // Skip weekends
                if ($date->isWeekend()) continue;

                $status = $attendanceStatuses[array_rand($attendanceStatuses)];
                $checkIn = null;
                $checkOut = null;

                if (in_array($status, ['present', 'late'])) {
                    $checkIn = $status === 'late' 
                        ? Carbon::createFromTime(8, rand(15, 59)) 
                        : Carbon::createFromTime(7, rand(30, 59));
                    $checkOut = Carbon::createFromTime(16, rand(0, 30));
                }

                Attendance::create([
                    'intern_id' => $intern->id,
                    'date' => $date,
                    'check_in' => $checkIn?->format('H:i'),
                    'check_out' => $checkOut?->format('H:i'),
                    'status' => $status,
                    'notes' => $status === 'sick' ? 'Sakit flu' : ($status === 'permission' ? 'Ada keperluan keluarga' : null),
                ]);
            }
        }

        // Create Reports
        foreach ($interns as $intern) {
            Report::create([
                'intern_id' => $intern->id,
                'created_by' => $intern->supervisor_id ?? $admin->id,
                'title' => 'Laporan Mingguan - Minggu 1',
                'content' => 'Ringkasan kegiatan selama minggu pertama magang. Siswa telah menyelesaikan orientasi dan mulai mengerjakan tugas-tugas yang diberikan dengan baik.',
                'type' => 'weekly',
                'period_start' => Carbon::now()->subWeeks(2)->startOfWeek(),
                'period_end' => Carbon::now()->subWeeks(2)->endOfWeek(),
                'status' => 'reviewed',
                'feedback' => 'Progres baik, tingkatkan inisiatif dalam bertanya dan eksplorasi.',
            ]);

            Report::create([
                'intern_id' => $intern->id,
                'created_by' => $intern->supervisor_id ?? $admin->id,
                'title' => 'Laporan Mingguan - Minggu 2',
                'content' => 'Minggu kedua berjalan dengan baik. Mulai terbiasa dengan workflow dan tools yang digunakan. Sudah bisa bekerja mandiri.',
                'type' => 'weekly',
                'period_start' => Carbon::now()->subWeek()->startOfWeek(),
                'period_end' => Carbon::now()->subWeek()->endOfWeek(),
                'status' => 'submitted',
            ]);
        }

        // Create Assessments
        foreach ($interns as $intern) {
            Assessment::create([
                'intern_id' => $intern->id,
                'assessed_by' => $intern->supervisor_id ?? $admin->id,
                'quality_score' => rand(65, 95),
                'speed_score' => rand(60, 90),
                'initiative_score' => rand(55, 85),
                'teamwork_score' => rand(70, 95),
                'communication_score' => rand(60, 90),
                'strengths' => 'Rajin, teliti, dan mau belajar. Menunjukkan dedikasi yang tinggi.',
                'improvements' => 'Tingkatkan kemampuan komunikasi dan presentasi. Lebih proaktif dalam bertanya.',
                'comments' => 'Terus pertahankan semangat belajar! Potensi sangat baik.',
            ]);
        }

        $this->command->info('');
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('📋 Login Credentials:');
        $this->command->info('   Admin: admin@magang.com / password');
        $this->command->info('   Pembimbing: budi.pembimbing@magang.com / password');
        $this->command->info('   Intern: ahmad@siswa.com / password');
        $this->command->info('');
    }
}
