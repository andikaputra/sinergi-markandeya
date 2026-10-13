<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\DosenPembimbing;
use App\Models\IndividuLuaran;
use App\Models\IndividuProgramKerja;
use App\Models\KelompokProgramKerja;
use App\Models\Mahasiswa;
use App\Models\MahasiswaKegiatan;
use Illuminate\Support\Facades\Storage;
use Tests\Support\WorkflowTestCase;

class ProgramPresentationTest extends WorkflowTestCase
{
    private function participants(): array
    {
        $student = Mahasiswa::create(['nim' => '5001', 'nama' => 'Mahasiswa Satu', 'status' => 'aktif', 'kegiatan' => 'KKN']);
        MahasiswaKegiatan::create(['nim' => $student->nim, 'kegiatan' => 'KKN', 'tahun_akademik' => '2026/2027 Ganjil', 'is_active' => true]);
        $dosen = Dosen::create(['nidn' => '99', 'nama' => 'Dosen Satu']);
        DosenPembimbing::create(['nim' => $student->nim, 'nidn' => $dosen->nidn]);

        return [$student, $dosen];
    }

    public function test_lecturer_program_tabs_render_only_selected_program_type(): void
    {
        [$student, $dosen] = $this->participants();
        IndividuProgramKerja::create(['nim' => $student->nim, 'kategori' => 'KKN', 'judul' => 'Judul Individu Eksklusif', 'status' => 'rencana']);
        KelompokProgramKerja::create(['nim_ketua' => $student->nim, 'kategori' => 'KKN', 'judul' => 'Judul Kelompok Eksklusif', 'status' => 'rencana']);
        $this->actingAs($dosen, 'dosen');
        foreach (['dosen.program-kerja.semua', 'dosen.program-kerja.detail'] as $route) {
            $params = $route === 'dosen.program-kerja.detail' ? ['mahasiswa' => $student->id] : [];
            $this->get(route($route, $params + ['tab' => 'individu']))->assertOk()->assertSee('Judul Individu Eksklusif')->assertDontSee('Judul Kelompok Eksklusif');
            $this->get(route($route, $params + ['tab' => 'kelompok']))->assertOk()->assertSee('Judul Kelompok Eksklusif')->assertDontSee('Judul Individu Eksklusif');
        }
        $this->actingAs($student, 'mahasiswa');
        $this->get(route('program-kerja.index', ['tab' => 'individu']))->assertOk()->assertSee('Judul Individu Eksklusif')->assertDontSee('Judul Kelompok Eksklusif');
        $this->get(route('program-kerja.index', ['tab' => 'kelompok']))->assertOk()->assertSee('Judul Kelompok Eksklusif')->assertDontSee('Judul Individu Eksklusif');
    }

    public function test_luaran_tabs_render_only_selected_type_and_external_download_link(): void
    {
        [$student, $dosen] = $this->participants();
        $individual = IndividuProgramKerja::create(['nim' => $student->nim, 'kategori' => 'KKN', 'judul' => 'Program Individu']);
        $group = KelompokProgramKerja::create(['nim_ketua' => $student->nim, 'kategori' => 'KKN', 'judul' => 'Program Kelompok']);
        IndividuLuaran::create(['individu_program_kerja_id' => $individual->id, 'judul' => 'Artikel Individu Eksklusif', 'file_path' => 'https://drive.google.com/file/d/example/view']);
        \App\Models\KelompokLuaran::create(['kelompok_program_kerja_id' => $group->id, 'judul' => 'Artikel Kelompok Eksklusif']);
        $this->actingAs($dosen, 'dosen');
        $this->get(route('dosen.program-kerja.luaran', ['tab' => 'individu']))->assertOk()->assertSee('Artikel Individu Eksklusif')->assertDontSee('Artikel Kelompok Eksklusif')->assertSee('Buka / unduh berkas');
        $this->get(route('dosen.program-kerja.luaran', ['tab' => 'kelompok']))->assertOk()->assertSee('Artikel Kelompok Eksklusif')->assertDontSee('Artikel Individu Eksklusif');
    }

    public function test_local_article_download_preserves_bytes_and_checks_access(): void
    {
        [$student, $dosen] = $this->participants();
        Storage::fake('public');
        $bytes = "PK\x03\x04original article document";
        Storage::disk('public')->put('luaran/artikel.docx', $bytes);
        $program = IndividuProgramKerja::create(['nim' => $student->nim, 'kategori' => 'KKN', 'judul' => 'Program']);
        $luaran = IndividuLuaran::create(['individu_program_kerja_id' => $program->id, 'judul' => 'Artikel', 'file_path' => 'luaran/artikel.docx']);
        $url = route('luaran.berkas', ['type' => 'individu', 'id' => $luaran->id]);
        $response = $this->actingAs($student, 'mahasiswa')->get($url)->assertOk()->assertDownload('artikel.docx');
        $this->assertSame($bytes, $response->streamedContent());
        $this->actingAs($dosen, 'dosen')->get($url)->assertOk()->assertDownload('artikel.docx');
        $outsider = Mahasiswa::create(['nim' => 'other', 'nama' => 'Other', 'status' => 'aktif']);
        $this->actingAs($outsider, 'mahasiswa')->get($url)->assertForbidden();
        $this->actingAs($student, 'mahasiswa');
        Storage::disk('public')->delete('luaran/artikel.docx');
        $this->get($url)->assertNotFound()->assertHeaderMissing('Content-Disposition');
    }
}
