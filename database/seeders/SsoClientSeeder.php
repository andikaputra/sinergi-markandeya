<?php

namespace Database\Seeders;

use App\Models\SsoClient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SsoClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'name'          => 'LPPM Markandeya',
                'client_id'     => 'lppm-markandeya',
                'client_secret' => Hash::make('lppm_secret_' . env('SSO_KEY_SALT', 'markandeya2026')),
                'redirect_uris' => ['http://localhost:8001/sso/callback', 'https://lppm.markandeyabali.ac.id/sso/callback'],
                'allowed_roles' => ['dosen', 'admin'],
            ],
            [
                'name'          => 'TTE Dosen',
                'client_id'     => 'tte-dosen',
                'client_secret' => Hash::make('tte_secret_' . env('SSO_KEY_SALT', 'markandeya2026')),
                'redirect_uris' => ['http://localhost:8002/sso/callback', 'https://tte-dosen.markandeyabali.ac.id/sso/callback'],
                'allowed_roles' => ['dosen'],
            ],
            [
                'name'          => 'Portal Dosen',
                'client_id'     => 'lecturer-portal',
                'client_secret' => Hash::make('lecturer_secret_' . env('SSO_KEY_SALT', 'markandeya2026')),
                'redirect_uris' => ['http://localhost:8003/sso/callback', 'https://lecturer.markandeyabali.ac.id/sso/callback'],
                'allowed_roles' => ['dosen'],
            ],
            [
                'name'          => 'Portal Mahasiswa',
                'client_id'     => 'student-portal',
                'client_secret' => Hash::make('student_secret_' . env('SSO_KEY_SALT', 'markandeya2026')),
                'redirect_uris' => ['http://localhost:8004/sso/callback', 'https://student.markandeyabali.ac.id/sso/callback'],
                'allowed_roles' => ['mahasiswa'],
            ],
            [
                'name'          => 'E-Learning',
                'client_id'     => 'elearning-markandeya',
                'client_secret' => Hash::make('elearning_secret_' . env('SSO_KEY_SALT', 'markandeya2026')),
                'redirect_uris' => ['http://localhost:8005/sso/callback', 'https://elearning.markandeyabali.ac.id/sso/callback'],
                'allowed_roles' => ['dosen', 'mahasiswa'],
            ],
            [
                'name'          => 'Sinergi Markandeya (Internal)',
                'client_id'     => 'sinergi-internal',
                'client_secret' => Hash::make('sinergi_secret_' . env('SSO_KEY_SALT', 'markandeya2026')),
                'redirect_uris' => ['http://localhost:8000/sso/callback'],
                'allowed_roles' => ['dosen', 'mahasiswa', 'admin'],
            ],
        ];

        foreach ($clients as $client) {
            SsoClient::updateOrCreate(
                ['client_id' => $client['client_id']],
                $client
            );
        }

        $this->command->info('SSO Clients seeded:');
        foreach ($clients as $c) {
            $this->command->line("  ✓ {$c['name']} (client_id: {$c['client_id']})");
        }

        $this->command->warn('');
        $this->command->warn('⚠  Simpan client_secret sebelum di-hash ke .env masing-masing app:');
        $this->command->line("  LPPM_CLIENT_SECRET=lppm_secret_" . env('SSO_KEY_SALT', 'markandeya2026'));
        $this->command->line("  TTE_CLIENT_SECRET=tte_secret_" . env('SSO_KEY_SALT', 'markandeya2026'));
        $this->command->line("  LECTURER_CLIENT_SECRET=lecturer_secret_" . env('SSO_KEY_SALT', 'markandeya2026'));
        $this->command->line("  STUDENT_CLIENT_SECRET=student_secret_" . env('SSO_KEY_SALT', 'markandeya2026'));
        $this->command->line("  ELEARNING_CLIENT_SECRET=elearning_secret_" . env('SSO_KEY_SALT', 'markandeya2026'));
    }
}
