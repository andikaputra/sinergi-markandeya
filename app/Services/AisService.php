<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class AisService
{
    private const BASE    = 'https://ais.markandeyabali.ac.id/api/v1';
    private const TIMEOUT = 10;

    // ── Login ──────────────────────────────────────────────────────────────

    public function loginMahasiswa(string $username, string $password): ?array
    {
        $data = $this->loginRequest('mahasiswa', $username, $password);
        // tipe_login 2 = mahasiswa, 1 = dosen
        if ($data && ($data['_tipe_login'] ?? null) != 2) return null;
        return $data;
    }

    public function loginDosen(string $username, string $password): ?array
    {
        $data = $this->loginRequest('dosen', $username, $password);
        // tipe_login 1 = dosen
        if ($data && ($data['_tipe_login'] ?? null) != 1) return null;
        return $data;
    }

    private function sanitizeFotoUrl(?string $url): ?string
    {
        if (!$url) return null;
        $parsed = parse_url($url);
        if (!in_array($parsed['scheme'] ?? '', ['http', 'https'])) return null;
        return $url;
    }

    private function loginRequest(string $type, string $username, string $password): ?array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->get(self::BASE . "/login/{$type}", [
                    'username' => $username,
                    'password' => $password,
                ]);

            if (!$response->successful()) return null;

            $results = $response->json('responseData.results');
            if (!$results || ($results[0]['status'] ?? 0) != 1) return null;

            $userData = $results[1][0] ?? null;
            if (!$userData) return null;

            $userData['_token']      = $results[0]['token']      ?? null;
            $userData['_tipe_login'] = $results[0]['tipe_login'] ?? null;
            $userData['_tahun']      = $results[2][0]            ?? null;
            return $userData;
        } catch (\Exception $e) {
            return null;
        }
    }

    // ── Profile ────────────────────────────────────────────────────────────

    public function profilMahasiswa(string $token): ?array
    {
        return $this->profileRequest('profil_mahasiswa', $token);
    }

    public function profilDosen(string $token): ?array
    {
        return $this->profileRequest('profil_dosen', $token);
    }

    private function profileRequest(string $endpoint, string $token): ?array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->get(self::BASE . "/{$endpoint}", ['token' => $token]);

            if (!$response->successful()) return null;

            $results = $response->json('responseData.results');
            if (!$results || ($results[0]['status'] ?? 0) != 1) return null;

            return $results[1][0] ?? $results[1] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    // ── Sync helpers ───────────────────────────────────────────────────────

    public function syncMahasiswa(array $data, string $plainPassword): Mahasiswa
    {
        // Field mapping: NPM → nim, NamaProdi → prodi, urlFoto → foto
        $nim      = $data['NPM'] ?? $data['NIM'] ?? $data['Username'] ?? null;
        $existing = Mahasiswa::where('nim', $nim)->first();

        $fields = [
            'nama'      => $data['Nama']                                          ?? null,
            'prodi'     => $data['NamaProdi'] ?? $data['Prodi']                  ?? ($existing->prodi  ?? null),
            'kampus'    => $data['NamaKampus'] ?? $data['Kampus']                ?? ($existing->kampus ?? null),
            'email'     => $data['Email']                                         ?? ($existing->email  ?? null),
            'foto'      => $this->sanitizeFotoUrl($data['urlFoto'] ?? $data['Foto'] ?? null) ?? ($existing->foto ?? null),
            'ais_token' => $data['_token'],
            'password'  => $plainPassword,
        ];

        if ($existing) {
            $existing->update($fields);
            return $existing->fresh();
        }

        return Mahasiswa::create(array_merge($fields, [
            'nim'    => $nim,
            'status' => 'aktif',
        ]));
    }

    public function syncDosen(array $data, string $plainPassword): Dosen
    {
        $nidn     = $data['NIDN'] ?? $data['Username'] ?? null;
        $existing = Dosen::where('nidn', $nidn)->first();

        $fields = [
            'nama'      => $data['Nama']                          ?? null,
            'nip'       => $data['NIP']                           ?? ($existing->nip  ?? null),
            'foto'      => $this->sanitizeFotoUrl($data['urlFoto'] ?? $data['Foto'] ?? null) ?? ($existing->foto ?? null),
            'ais_token' => $data['_token'],
            'password'  => $plainPassword,
        ];

        if ($existing) {
            $existing->update($fields);
            return $existing->fresh();
        }

        return Dosen::create(array_merge($fields, ['nidn' => $nidn]));
    }

    public function syncMahasiswaFromProfil(Mahasiswa $mhs): Mahasiswa
    {
        if (!$mhs->ais_token) return $mhs;

        $data = $this->profilMahasiswa($mhs->ais_token);
        if (!$data) return $mhs;

        $mhs->update([
            'nama'  => $data['Nama']                              ?? $mhs->nama,
            'email' => $data['Email']                             ?? $mhs->email,
            'prodi' => $data['NamaProdi'] ?? $data['Prodi']      ?? $mhs->prodi,
            'foto'  => $this->sanitizeFotoUrl($data['urlFoto'] ?? $data['Foto'] ?? null) ?? $mhs->foto,
        ]);

        return $mhs->fresh();
    }

    public function syncDosenFromProfil(Dosen $dosen): Dosen
    {
        if (!$dosen->ais_token) return $dosen;

        $data = $this->profilDosen($dosen->ais_token);
        if (!$data) return $dosen;

        $dosen->update([
            'nama' => $data['Nama']                           ?? $dosen->nama,
            'nip'  => $data['NIP']                            ?? $dosen->nip,
            'foto' => $this->sanitizeFotoUrl($data['urlFoto'] ?? $data['Foto'] ?? null) ?? $dosen->foto,
        ]);

        return $dosen->fresh();
    }
}
