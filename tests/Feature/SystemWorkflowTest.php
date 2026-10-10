<?php

namespace Tests\Feature;

use App\Mail\ResetPasswordMahasiswa;
use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\DosenPembimbing;
use App\Models\Mahasiswa;
use App\Models\MahasiswaKegiatan;
use App\Models\PenempatanKkn;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\Support\WorkflowTestCase;

class SystemWorkflowTest extends WorkflowTestCase
{
    private function student(string $activity = 'KKN'): Mahasiswa
    {
        $student = Mahasiswa::create([
            'nim' => '12345', 'nama' => 'Mahasiswa Test', 'email' => 'student@example.test',
            'password' => 'old-password', 'status' => 'aktif', 'kegiatan' => $activity,
            'tahun_akademik' => '2025/2026 Ganjil',
        ]);
        MahasiswaKegiatan::create([
            'nim' => $student->nim, 'kegiatan' => $activity, 'tahun_akademik' => '2025/2026 Ganjil',
            'is_active' => true, 'status_kegiatan' => 'aktif',
        ]);

        return $student;
    }

    private function admin(): User
    {
        return User::create(['name' => 'Admin Test', 'email' => 'admin@example.test',
            'password' => 'password', 'role' => 'superadmin']);
    }

    public function test_all_monitoring_pages_keep_admin_navigation_and_logout(): void
    {
        $this->actingAs($this->admin(), 'web');
        $student = $this->student();
        Dosen::create(['nidn' => '987', 'nama' => 'Dosen Test', 'password' => 'password']);
        DB::table('mahasiswas')->update(['last_login' => now()->subDays(31)]);
        DB::table('dosens')->update(['last_login' => now()->subDays(31)]);
        $assignment = DosenPembimbing::create(['nim' => $student->nim, 'nidn' => '987']);
        foreach (['belum_direview', 'perlu_revisi', 'disetujui'] as $status) {
            Bimbingan::create(['nim' => $student->nim, 'dosen_pembimbing_id' => $assignment->id,
                'status' => $status, 'topik' => 'Topik Monitoring', 'tanggal_bimbingan' => now()]);
        }
        $program = DB::table('individu_program_kerjas')->insertGetId([
            'nim' => $student->nim, 'status' => 'rencana', 'kategori' => 'kkn', 'judul' => 'Program Monitoring',
            'tanggal_mulai' => now(), 'tanggal_selesai' => now()->addDay(), 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('individu_luarans')->insert(['individu_program_kerja_id' => $program,
            'judul' => 'Luaran Monitoring', 'status' => 'selesai', 'created_at' => now(), 'updated_at' => now()]);
        $routes = [
            'admin.bimbingan.dashboard', 'admin.bimbingan.belum-bimbingan',
            'admin.bimbingan.belum-direview', 'admin.bimbingan.perlu-revisi',
            'admin.bimbingan.dosen-performa', 'admin.bimbingan.laporan',
            'admin.login-activity.dashboard', 'admin.login-activity.mahasiswa-belum-login',
            'admin.login-activity.mahasiswa-tidak-aktif', 'admin.login-activity.dosen-belum-login',
            'admin.login-activity.dosen-tidak-aktif', 'admin.login-activity.laporan',
            'admin.program-kerja.dashboard', 'admin.program-kerja.mahasiswa-tanpa-program',
            'admin.program-kerja.semua-program', 'admin.program-kerja.semua-luaran',
        ];
        foreach ($routes as $name) {
            $this->get(route($name))->assertOk()->assertSee('id="sidebar"', false)
                ->assertSee('action="'.route('logoutadmin').'"', false)
                ->assertSee('aria-current="page"', false)->assertDontSee('Panel Mahasiswa');
        }
        $this->get(route('admin.program-kerja.detail-mahasiswa', $student->nim))
            ->assertOk()->assertSee('action="'.route('logoutadmin').'"', false);
    }

    public function test_monitoring_displays_actual_topics_and_aggregates_each_dosen_once(): void
    {
        $student = $this->student();
        Dosen::create(['nidn' => '987', 'nama' => 'Dosen Test', 'password' => 'password']);
        $first = DosenPembimbing::create(['nim' => $student->nim, 'nidn' => '987']);
        DosenPembimbing::create(['nim' => '67890', 'nidn' => '987']);
        Bimbingan::create(['nim' => $student->nim, 'dosen_pembimbing_id' => $first->id,
            'topik' => 'Topik Asli', 'status' => 'belum_direview', 'tanggal_bimbingan' => now()]);
        $this->actingAs($this->admin(), 'web');
        $this->get(route('admin.bimbingan.belum-direview'))->assertOk()->assertSee('Topik Asli');
        $this->get(route('admin.bimbingan.laporan'))->assertOk()->assertSee('Topik Asli');
        $this->get(route('admin.bimbingan.dosen-performa'))->assertOk()
            ->assertViewHas('dosenList', fn ($rows) => $rows->count() === 1 && $rows[0]['total_mahasiswa'] === 2);
    }

    public function test_admin_dashboard_matches_account_status_and_activity_permissions(): void
    {
        $this->student('PKL');
        $inactive = Mahasiswa::create(['nim' => '88888', 'nama' => 'Akun Nonaktif',
            'email' => 'inactive@example.test', 'password' => 'password', 'status' => 'nonaktif']);
        Mahasiswa::create(['nim' => '99999', 'nama' => 'Akun Pending',
            'email' => 'pending@example.test', 'password' => 'password', 'status' => 'pending']);
        $admin = User::create(['name' => 'Admin KKN', 'email' => 'kkn@example.test', 'password' => 'password',
            'role' => 'admin', 'kegiatan' => ['KKN']]);
        $response = $this->actingAs($admin, 'web')->get(route('admindashboard'));
        $response->assertOk()->assertSee('Dashboard Admin')->assertSee('Akun Nonaktif')
            ->assertDontSee('Peserta PKL')->assertViewHas('jumlahPending', 1)
            ->assertViewHas('pendingTerbaru', fn ($rows) => $rows->count() === 1 && $rows->first()->id === $inactive->id)
            ->assertViewHas('pendaftaranTerbaru', fn ($rows) => $rows->isEmpty());
    }

    public function test_dosen_dashboard_keeps_current_period_assignments_and_reviews(): void
    {
        $student = $this->student();
        \App\Models\TahunAkademik::create(['tahun' => '2025/2026', 'semester' => 'Ganjil', 'is_active' => true]);
        $other = Mahasiswa::create(['nim' => '88888', 'nama' => 'Periode Lain',
            'email' => 'other@example.test', 'password' => 'password', 'status' => 'aktif',
            'kegiatan' => 'KKN', 'tahun_akademik' => '2024/2025 Ganjil']);
        MahasiswaKegiatan::create(['nim' => $other->nim, 'kegiatan' => 'KKN',
            'tahun_akademik' => '2024/2025 Ganjil', 'is_active' => true, 'status_kegiatan' => 'aktif']);
        $dosen = Dosen::create(['nidn' => '987', 'nip' => '654', 'nama' => 'Dosen Test', 'password' => 'password']);
        $assignment = DosenPembimbing::create(['nim' => $student->nim, 'nidn' => '654', 'nilai' => 0]);
        $otherAssignment = DosenPembimbing::create(['nim' => $other->nim, 'nidn' => '987']);
        foreach ([$assignment, $otherAssignment] as $item) {
            Bimbingan::create(['nim' => $item->nim, 'dosen_pembimbing_id' => $item->id,
                'topik' => 'Review Dashboard', 'status' => 'belum_direview', 'tanggal_bimbingan' => now()]);
        }
        $this->actingAs($dosen, 'dosen')->get(route('dosen.dashboard'))->assertOk()
            ->assertSee('Dashboard Dosen')->assertSee('Review Dashboard')->assertDontSee('Periode Lain')
            ->assertViewHas('totalBimbingan', 1)->assertViewHas('sudahDinilai', 1)
            ->assertViewHas('belumDinilai', 0)->assertViewHas('bimbinganBelumDireview', 1)
            ->assertViewHas('mahasiswaTerbaru', fn ($rows) => $rows->count() === 1 && $rows->first()->nim === $student->nim);
    }

    public function test_attachment_download_preserves_bytes_and_checks_access_and_missing_file(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $student = $this->student();
        $dosen = Dosen::create(['nidn' => '987', 'nip' => '654', 'password' => 'password']);
        $assignment = DosenPembimbing::create(['nim' => $student->nim, 'nidn' => '654']);
        $review = Bimbingan::create(['nim' => $student->nim, 'dosen_pembimbing_id' => $assignment->id,
            'topik' => 'Lampiran Word', 'status' => 'belum_direview', 'materi_terlampir' => 'lampiran.docx']);
        foreach (['doc', 'docx'] as $extension) {
            $filename = 'lampiran.'.$extension;
            $review->update(['materi_terlampir' => $filename]);
            $bytes = ($extension === 'doc' ? "\xD0\xCF\x11\xE0" : "PK\x03\x04")."original-document-bytes\x00";
            \Illuminate\Support\Facades\Storage::disk('public')->put('bimbingan/'.$filename, $bytes);
            $response = $this->actingAs($student, 'mahasiswa')->get(route('bimbingan.berkas', $review));
            $response->assertOk()->assertDownload($filename);
            $this->assertSame($bytes, $response->streamedContent());
        }
        $other = Mahasiswa::create(['nim' => 'other', 'nama' => 'Lain', 'password' => 'password']);
        $this->actingAs($other, 'mahasiswa')->get(route('bimbingan.berkas', $review))->assertForbidden();
        auth('mahasiswa')->logout();
        $this->actingAs($dosen, 'dosen')->get(route('bimbingan.berkas', $review))->assertOk()->assertDownload('lampiran.docx');
        \Illuminate\Support\Facades\Storage::disk('public')->delete('bimbingan/lampiran.docx');
        $this->get(route('bimbingan.berkas', $review))->assertNotFound()->assertHeaderMissing('Content-Disposition');
    }

    public function test_printed_participant_report_uses_selected_academic_period(): void
    {
        $this->student();
        $this->actingAs($this->admin(), 'web')->get(route('admin.print.kkn', ['ta' => '2025/2026 Ganjil']))
            ->assertOk()->assertSee('2025/2026 Ganjil')->assertSee('Mahasiswa Test')->assertSee('Cetak / Simpan PDF')
            ->assertDontSee('id="sidebar"', false)->assertSee('Belum tersedia');
    }

    public function test_bimbingan_print_matches_status_date_and_activity_filters(): void
    {
        $student = $this->student();
        $assignment = DosenPembimbing::create(['nim' => $student->nim, 'nidn' => '987']);
        foreach ([['Topik Disetujui', 'disetujui'], ['Topik Menunggu', 'belum_direview']] as [$title, $status]) {
            Bimbingan::create(['nim' => $student->nim, 'dosen_pembimbing_id' => $assignment->id,
                'topik' => $title, 'status' => $status, 'tanggal_bimbingan' => now()]);
        }
        $query = ['cetak'=>1, 'kegiatan'=>'KKN', 'status'=>'disetujui', 'ta'=>'2025/2026 Ganjil',
            'tanggal_mulai'=>now()->toDateString(), 'tanggal_selesai'=>now()->toDateString()];
        $this->actingAs($this->admin(), 'web')->get(route('admin.bimbingan.laporan', $query))->assertOk()
            ->assertSee('Topik Disetujui')->assertDontSee('Topik Menunggu')->assertViewHas('bimbingans', fn ($rows) => $rows->count() === 1);
        $admin = User::create(['name'=>'Admin KKN', 'email'=>'scoped@example.test', 'password'=>'password', 'role'=>'admin', 'kegiatan'=>['KKN']]);
        $query['kegiatan'] = 'PKL';
        $this->actingAs($admin, 'web')->get(route('admin.bimbingan.laporan', $query))->assertForbidden();
    }

    public function test_login_print_contains_all_records_beyond_pagination(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            DB::table('mahasiswas')->insert(['nim'=>'nim'.$i, 'nama'=>'Mahasiswa '.$i, 'last_login'=>now()]);
            DB::table('dosens')->insert(['nidn'=>'nidn'.$i, 'nama'=>'Dosen '.$i, 'last_login'=>now()]);
        }
        $this->actingAs($this->admin(), 'web')->get(route('admin.login-activity.laporan', ['cetak'=>1]))
            ->assertOk()->assertViewHas('mahasiswas', fn ($rows) => $rows->count() === 25)
            ->assertViewHas('dosens', fn ($rows) => $rows->count() === 25)->assertSee('Dosen 25');
    }

    public function test_student_and_program_prints_render_without_dashboard_chrome(): void
    {
        $student = $this->student();
        $this->actingAs($student, 'mahasiswa')->get(route('jurnal.cetak'))->assertOk()->assertSee('2025/2026 Ganjil');
        $this->get(route('bimbingan.cetak'))->assertOk()->assertDontSee('id="sidebar"', false);
        auth('mahasiswa')->logout();
        $this->actingAs($this->admin(), 'web');
        foreach (['admin.program-kerja.semua-program', 'admin.program-kerja.semua-luaran'] as $route) {
            $this->get(route($route, ['cetak'=>1]))->assertOk()->assertSee('Cetak / Simpan PDF')->assertDontSee('id="sidebar"', false);
        }
    }

    public function test_successful_web_login_records_dosen_before_redirect_and_failed_login_does_not(): void
    {
        $this->mock(\App\Services\AisService::class, function ($mock) {
            $mock->shouldReceive('loginMahasiswa')->andReturn(null);
            $mock->shouldReceive('loginDosen')->andReturn(null);
        });
        $dosen = Dosen::create(['nidn'=>'987','nip'=>'654','nama'=>'Dosen Login','password'=>'secret-password']);
        $this->post(route('login.submit'), ['email'=>'654','password'=>'wrong-password'])->assertSessionHasErrors('email');
        $this->assertNull($dosen->fresh()->last_login);
        $this->post(route('login.submit'), ['email'=>'654','password'=>'secret-password'])->assertRedirect(route('dosen.dashboard'));
        $this->assertNotNull($dosen->fresh()->last_login);
        auth('dosen')->logout();
        $this->actingAs($this->admin(), 'web')->get(route('admin.login-activity.dosen-belum-login', ['cetak'=>1]))
            ->assertOk()->assertDontSee('Dosen Login');
    }

    public function test_api_login_records_dosen_activity_and_returns_positive_token_lifetime(): void
    {
        $dosen = Dosen::create(['nidn'=>'987','nama'=>'Dosen API','password'=>'secret-password']);
        $this->postJson('/api/v1/login/dosen', ['username'=>'987','password'=>'secret-password'])
            ->assertOk()->assertJsonPath('user_type','dosen')->assertJson(fn ($json) => $json->where('expires_in', fn ($value) => $value > 0)->etc());
        $this->assertNotNull($dosen->fresh()->last_login);
    }

    public function test_monev_monitoring_resolves_aliases_and_does_not_duplicate_mirrored_stages(): void
    {
        $first = Dosen::create(['nidn'=>'111','nip'=>'old-111','nama'=>'Belum Monev','password'=>'password']);
        $second = Dosen::create(['nidn'=>'222','nama'=>'Sudah Mulai','password'=>'password']);
        Dosen::create(['nidn'=>'333','nama'=>'Monev Lengkap','password'=>'password']);
        Dosen::create(['nidn'=>'444','nama'=>'Tanpa Penugasan','password'=>'password']);
        $completed = \App\Models\DosenMonev::create(['nidn'=>'333','monev_type'=>'individu','kegiatan'=>'kkn','nim'=>'unknown']);
        foreach ([1,2,3] as $stage) \App\Models\DosenMonevTahap::create(['dosen_monev_id'=>$completed->id,'tahap_ke'=>$stage,'catatan'=>'Evaluasi tercatat','tanggal_monev'=>now()]);
        $empty = \App\Models\DosenMonev::create(['nidn'=>'old-111','monev_type'=>'individu','kegiatan'=>'kkn','nim'=>'unknown','catatan'=>'   ','foto_monev'=>[]]);
        $started = \App\Models\DosenMonev::create(['nidn'=>'222','monev_type'=>'individu','kegiatan'=>'kkn','nim'=>'unknown','tanggal_monev'=>now(),'catatan'=>'Data tersalin dari tahap 2']);
        \App\Models\DosenMonevTahap::create(['dosen_monev_id'=>$started->id,'tahap_ke'=>2,'nilai'=>0,'tanggal_monev'=>now()]);
        $this->assertSame([1=>false,2=>true,3=>false], $started->fresh()->load('tahaps')->recordedStages());
        $this->actingAs($this->admin(), 'web')->get(route('admin.monev.monitoring'))
            ->assertOk()->assertSee('Belum Monev')->assertDontSee('Sudah Mulai')->assertDontSee('Monev Lengkap')->assertDontSee('Tanpa Penugasan')
            ->assertViewHas('summary', fn ($summary) => $summary['belum_mulai'] === 1 && $summary['berjalan'] === 1 && $summary['lengkap'] === 1 && $summary['total'] === 3);
        $this->get(route('admin.monev.monitoring',['status'=>'semua','cetak'=>1]))
            ->assertOk()->assertSee('Belum Monev')->assertSee('Sudah Mulai')->assertSee('Monev Lengkap')->assertDontSee('id="sidebar"', false);
        auth('web')->logout();
        $this->actingAs($second,'dosen')->get(route('dosen.program-kerja.monev-dashboard'))
            ->assertOk()->assertSee('1/3 tahap')->assertViewHas('totalBelum',0)->assertViewHas('totalLengkap',0);
    }

    public function test_monev_monitoring_scopes_activities_and_counts_legacy_date_only_records(): void
    {
        Dosen::create(['nidn'=>'111','nama'=>'Pemonev','password'=>'password']);
        $legacy = \App\Models\DosenMonev::create(['nidn'=>'111','monev_type'=>'kelompok','kegiatan'=>'kkn','tanggal_monev'=>now()]);
        \App\Models\DosenMonev::create(['nidn'=>'111','monev_type'=>'kelompok','kegiatan'=>'ppl']);
        $this->assertSame(1,$legacy->load('tahaps')->monev_selesai_count);
        $admin = User::create(['name'=>'KKN','email'=>'only-kkn@example.test','password'=>'password','role'=>'admin','kegiatan'=>['KKN']]);
        $this->actingAs($admin,'web')->get(route('admin.monev.monitoring',['status'=>'semua']))->assertOk()
            ->assertViewHas('lecturers', fn ($rows) => $rows->first()['total'] === 1 && $rows->first()['berjalan'] === 1);
        $this->get(route('admin.monev.monitoring',['kegiatan'=>'ppl']))->assertForbidden();
    }

    public function test_print_of_dosen_without_login_contains_all_filtered_records(): void
    {
        for ($i=1;$i<=30;$i++) Dosen::create(['nidn'=>'never-'.$i,'nama'=>'Belum Tercatat '.$i,'password'=>'password']);
        $logged = Dosen::create(['nidn'=>'logged','nama'=>'Sudah Tercatat','password'=>'password']);
        $logged->forceFill(['last_login'=>now()])->save();
        $this->actingAs($this->admin(),'web')->get(route('admin.login-activity.dosen-belum-login',['cetak'=>1]))
            ->assertOk()->assertViewHas('dosen', fn ($rows) => $rows->count() === 30)->assertDontSee('Sudah Tercatat');
        $this->get(route('admin.login-activity.dosen-belum-login',['q'=>'never-30','cetak'=>1]))
            ->assertOk()->assertViewHas('dosen', fn ($rows) => $rows->count() === 1)->assertSee('Belum Tercatat 30');
    }

    public function test_monev_monitoring_accepts_legacy_encoded_admin_permissions(): void
    {
        $admin = User::create(['name'=>'Admin Legacy','email'=>'legacy@example.test','password'=>'password',
            'role'=>'admin','kegiatan'=>json_encode(['KKN'])]);
        $this->assertSame(['KKN'],$admin->getAllowedKegiatan());
        $this->assertTrue($admin->canManage('KKN'));
        $this->assertFalse($admin->canManage('PPL'));
        $this->actingAs($admin,'web')->get(route('admin.monev.monitoring'))->assertOk();
    }

    public function test_monitoring_requires_admin_authentication(): void
    {
        foreach (['admin.bimbingan.dashboard', 'admin.login-activity.dashboard', 'admin.program-kerja.dashboard'] as $name) {
            $this->get(route($name))->assertRedirect(route('login'));
        }
    }

    public function test_activity_records_dosen_and_mahasiswa_without_writing_to_admin(): void
    {
        $student = $this->student();
        $dosen = Dosen::create(['nidn' => '987', 'password' => 'password']);
        $this->actingAs($student, 'mahasiswa')->actingAs($dosen, 'dosen');
        $this->get('/')->assertOk();
        $this->assertNotNull($student->fresh()->last_login);
        $this->assertNotNull($dosen->fresh()->last_login);
        $this->assertInstanceOf(\DateTimeInterface::class, $dosen->fresh()->last_login);
        $this->actingAs($this->admin(), 'web')->get(route('admin.login-activity.dashboard'))->assertOk();
    }

    public function test_reset_link_is_sent_to_owner_and_is_not_returned_to_requester(): void
    {
        Mail::fake();
        $student = $this->student();
        $response = $this->from(route('lupa-password'))->post(route('lupa-password.submit'), [
            'nim' => $student->nim, 'email' => $student->email,
        ]);
        $response->assertRedirect(route('lupa-password'))->assertSessionHas('success');
        $token = null;
        Mail::assertSent(ResetPasswordMahasiswa::class, function ($mail) use ($student, &$token) {
            $token = $mail->token;

            return $mail->hasTo($student->email);
        });
        $response->assertDontSee($token);
        $this->assertSame(hash('sha256', $token), $student->fresh()->reset_token);
        $this->post(route('reset-password.submit'), ['token' => $token, 'password' => 'new-password',
            'password_confirmation' => 'new-password'])->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('new-password', $student->fresh()->password));
        $this->post(route('reset-password.submit'), ['token' => $token, 'password' => 'another-password',
            'password_confirmation' => 'another-password'])->assertSessionHasErrors('token');
    }

    public function test_unknown_reset_account_does_not_reveal_account_existence(): void
    {
        Mail::fake();
        $this->post(route('lupa-password.submit'), ['nim' => 'unknown', 'email' => 'unknown@example.test'])
            ->assertSessionHas('success');
        Mail::assertNothingSent();
    }

    public function test_cancelled_activity_cannot_be_reactivated(): void
    {
        $student = $this->student();
        $cancelled = MahasiswaKegiatan::create(['nim' => $student->nim, 'kegiatan' => 'Magang',
            'tahun_akademik' => '2025/2026 Ganjil', 'is_active' => false, 'status_kegiatan' => 'dibatalkan']);
        $this->actingAs($student, 'mahasiswa')->post(route('mahasiswa.switch-kegiatan'), ['kegiatan_id' => $cancelled->id])
            ->assertSessionHas('error');
        $this->assertFalse($cancelled->fresh()->is_active);
        $this->assertSame('KKN', $student->fresh()->kegiatan);
    }

    public function test_new_registration_preserves_old_placement_and_switch_restores_period(): void
    {
        $student = $this->student();
        $oldRegistration = $student->activeKegiatan;
        DB::table('lokasi_kkn')->insert([['id' => 1, 'desa' => 'Lama'], ['id' => 2, 'desa' => 'Baru']]);
        $oldPlacement = PenempatanKkn::create(['nim' => $student->nim, 'lokasi_kkn_id' => 1]);
        $ta = DB::table('tahun_akademiks')->insertGetId(['tahun' => '2026/2027', 'semester' => 'Ganjil',
            'tanggal_mulai_daftar' => now()->subDay(), 'tanggal_selesai_daftar' => now()->addDay()]);
        $this->actingAs($student, 'mahasiswa')->post(route('mahasiswa.daftar-kegiatan'), [
            'kegiatan' => 'KKN', 'tahun_akademik_id' => $ta, 'preferensi_lokasi_id' => 2,
            'link_dokumen_1' => 'https://example.test/1', 'link_dokumen_2' => 'https://example.test/2',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame(2, PenempatanKkn::withoutGlobalScopes()->count());
        $this->assertEquals(1, PenempatanKkn::withoutGlobalScopes()->find($oldPlacement->id)->lokasi_kkn_id);
        $this->assertEquals(2, $student->fresh()->penempatankkn->lokasi_kkn_id);
        $this->post(route('mahasiswa.switch-kegiatan'), ['kegiatan_id' => $oldRegistration->id])->assertSessionHas('success');
        $this->assertEquals(1, $student->fresh()->penempatankkn->lokasi_kkn_id);
        $this->assertSame(1, Mahasiswa::whereHas('penempatankkn')->count());
    }

    public function test_invalid_location_does_not_create_registration(): void
    {
        $student = $this->student();
        $ta = DB::table('tahun_akademiks')->insertGetId(['tahun' => '2026/2027', 'semester' => 'Ganjil']);
        $this->actingAs($student, 'mahasiswa')->post(route('mahasiswa.daftar-kegiatan'), [
            'kegiatan' => 'KKN', 'tahun_akademik_id' => $ta, 'preferensi_lokasi_id' => 999,
            'link_dokumen_1' => 'https://example.test/1', 'link_dokumen_2' => 'https://example.test/2',
        ])->assertSessionHasErrors('preferensi_lokasi_id');
        $this->assertSame(1, MahasiswaKegiatan::count());
    }

    public function test_admin_without_activity_permission_cannot_approve_or_assign(): void
    {
        $admin = User::create(['name' => 'Admin KKN', 'email' => 'kkn@example.test', 'password' => 'password',
            'role' => 'admin', 'kegiatan' => ['KKN']]);
        $this->actingAs($admin, 'web');
        $this->post(route('pengajuanpkl.approve', 1))->assertForbidden();
        $this->post(route('pengajuanmagang.approve', 1))->assertForbidden();
        $this->post(route('assign.lokasipkl.store'))->assertForbidden();
        $this->get(route('admin.run-migrate'))->assertForbidden();
        $this->get(route('admin.clear-cache'))->assertForbidden();
    }

    public function test_shared_plotting_endpoint_uses_student_activity_permissions(): void
    {
        $student = $this->student('PKL');
        $dosen = Dosen::create(['nidn' => '987', 'password' => 'password']);
        $admin = User::create(['name' => 'Admin KKN', 'email' => 'kkn@example.test', 'password' => 'password',
            'role' => 'admin', 'kegiatan' => ['KKN']]);
        $this->actingAs($admin, 'web')->post(route('assign.dosen.store'), ['nims' => [$student->nim], 'nidn' => $dosen->nidn])
            ->assertForbidden();
        $this->assertDatabaseMissing('dosen_pembimbings', ['nim' => $student->nim]);
        $admin->update(['kegiatan' => ['PKL']]);
        $this->actingAs($admin->fresh(), 'web')->post(route('assign.dosen.store'), ['nims' => [$student->nim], 'nidn' => $dosen->nidn])
            ->assertSessionHas('success');
        $this->assertDatabaseHas('dosen_pembimbings', ['nim' => $student->nim, 'nidn' => $dosen->nidn]);
    }

    public function test_cancelling_an_old_period_does_not_change_current_selection(): void
    {
        $student = $this->student();
        $old = $student->activeKegiatan;
        $old->update(['is_active' => false]);
        $current = MahasiswaKegiatan::create(['nim' => $student->nim, 'kegiatan' => 'KKN',
            'tahun_akademik' => '2026/2027 Ganjil', 'is_active' => true, 'status_kegiatan' => 'aktif']);
        $student->update(['tahun_akademik' => '2026/2027 Ganjil']);
        $this->actingAs($student, 'mahasiswa')->post(route('mahasiswa.batalkan-kegiatan', $old->id))
            ->assertSessionHas('success');
        $this->assertTrue($current->fresh()->is_active);
        $this->assertSame('2026/2027 Ganjil', $student->fresh()->getRawOriginal('tahun_akademik'));
    }

    public function test_approvals_update_placement_and_do_not_allow_reprocessing(): void
    {
        foreach ([['PKL', 'pkl', 'pkls', 'lokasi_pkl_id', 'pengajuanpkl'],
            ['Magang', 'magang', 'magangs', 'lokasi_magang_id', 'pengajuanmagang']] as [$activity, $suffix, $plural, $field, $route]) {
            if ($activity === 'PKL') {
                $student = $this->student($activity);
            } else {
                $student->mahasiswaKegiatan()->update(['kegiatan' => $activity]);
                $student->update(['kegiatan' => $activity]);
            }
            $oldLocation = DB::table('lokasi_'.$plural)->insertGetId(['nama_instansi' => 'Lama', 'alamat' => 'Lama']);
            DB::table('penempatan_'.$plural)->insert(['nim' => $student->nim, $field => $oldLocation, 'tahun_akademik' => '2025/2026 Ganjil']);
            $application = DB::table('pengajuan_lokasi_'.$suffix)->insertGetId(['nim' => $student->nim,
                'nama_instansi' => 'Baru', 'alamat' => 'Baru', 'status' => 'pending', 'tahun_akademik' => '2025/2026 Ganjil']);
            $this->actingAs($this->adminIfNeeded(), 'web')->post(route($route.'.approve', $application))
                ->assertSessionHasNoErrors()->assertSessionHas('success');
            $this->assertDatabaseHas('pengajuan_lokasi_'.$suffix, ['id' => $application, 'status' => 'approved']);
            $newLocation = DB::table('lokasi_'.$plural)->where('nama_instansi', 'Baru')->value('id');
            $this->assertDatabaseHas('penempatan_'.$plural, ['nim' => $student->nim, $field => $newLocation]);
            $this->post(route($route.'.reject', $application))->assertSessionHasErrors('pengajuan');
            $this->assertDatabaseHas('pengajuan_lokasi_'.$suffix, ['id' => $application, 'status' => 'approved']);
        }
    }

    private function adminIfNeeded(): User
    {
        return User::first() ?? $this->admin();
    }

    public function test_full_location_leaves_application_and_existing_placement_unchanged(): void
    {
        $student = $this->student('PKL');
        $location = DB::table('lokasi_pkls')->insertGetId(['nama_instansi' => 'Penuh', 'alamat' => 'Alamat', 'maks_peserta' => 1]);
        DB::table('penempatan_pkls')->insert(['nim' => 'other', 'lokasi_pkl_id' => $location, 'tahun_akademik' => '2025/2026 Ganjil']);
        $application = DB::table('pengajuan_lokasi_pkl')->insertGetId(['nim' => $student->nim,
            'nama_instansi' => 'Penuh', 'alamat' => 'Alamat', 'status' => 'pending', 'tahun_akademik' => '2025/2026 Ganjil']);
        $this->actingAs($this->admin(), 'web')->post(route('pengajuanpkl.approve', $application))->assertSessionHasErrors('pengajuan');
        $this->assertDatabaseHas('pengajuan_lokasi_pkl', ['id' => $application, 'status' => 'pending']);
        $this->assertDatabaseMissing('penempatan_pkls', ['nim' => $student->nim]);
    }

    public function test_dosen_can_review_assignment_using_nip_alias(): void
    {
        $student = $this->student();
        $dosen = Dosen::create(['nidn' => '987', 'nip' => '654', 'password' => 'password']);
        $assignment = DosenPembimbing::create(['nim' => $student->nim, 'nidn' => '654']);
        $review = Bimbingan::create(['nim' => $student->nim, 'dosen_pembimbing_id' => $assignment->id,
            'topik' => 'Test', 'status' => 'belum_direview']);
        $this->actingAs($dosen, 'dosen')->post(route('dosen.bimbingan.status', $review->id), ['status' => 'disetujui'])
            ->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertSame('disetujui', $review->fresh()->status);
        $other = Dosen::create(['nidn' => 'other', 'password' => 'password']);
        $this->actingAs($other, 'dosen')->post(route('dosen.bimbingan.status', $review->id), ['status' => 'perlu_revisi'])->assertForbidden();
    }

    public function test_period_migration_backfills_and_refuses_lossy_rollback(): void
    {
        $this->periodMigration()->down();
        $student = $this->student();
        DB::table('pembagian_lokasi_kkn')->insert(['nim' => $student->nim, 'lokasi_kkn_id' => 1]);
        $this->periodMigration()->up();
        $this->assertDatabaseHas('pembagian_lokasi_kkn', ['nim' => $student->nim, 'tahun_akademik' => '2025/2026 Ganjil']);
        DB::table('pembagian_lokasi_kkn')->insert(['nim' => $student->nim, 'lokasi_kkn_id' => 2, 'tahun_akademik' => '2026/2027 Ganjil']);
        $this->expectException(\RuntimeException::class);
        $this->periodMigration()->down();
    }
}
