<?php

namespace Tests\Feature;

use App\Models\Bukti;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Test Cases untuk fitur Upload File (PBI #18, #19, #20)
 *
 * KAN-16 (PBI #18): UI upload file gambar/dokumen (FE)
 * KAN-17 (PBI #19): Preview file sebelum upload (FE)
 * KAN-18 (PBI #20): Simpan file ke server/database (BE)
 */
class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================================
    // KAN-16 — PBI #18: UI Upload File Gambar/Dokumen (FE)
    // =========================================================================

    /**
     * TC-01: Halaman form lapor dapat diakses dan menampilkan field upload.
     */
    public function test_halaman_lapor_menampilkan_form_upload(): void
    {
        $response = $this->get(route('lapor.create'));

        $response->assertStatus(200);
        // Pastikan form memiliki enctype multipart
        $response->assertSee('enctype="multipart/form-data"', false);
        // Pastikan ada input file dengan name="bukti"
        $response->assertSee('name="bukti"', false);
        // Pastikan ada label upload bukti
        $response->assertSee('Upload Bukti');
    }

    /**
     * TC-02: Form upload hanya menerima tipe file yang diperbolehkan (jpg, jpeg, png, pdf).
     */
    public function test_form_upload_memiliki_accept_attribute(): void
    {
        $response = $this->get(route('lapor.create'));

        $response->assertStatus(200);
        // Pastikan input file memiliki accept attribute untuk membatasi tipe file
        $response->assertSee('accept=".jpg,.jpeg,.png,.pdf"', false);
    }

    /**
     * TC-03: Form menampilkan informasi format dan ukuran file maksimal.
     */
    public function test_form_menampilkan_info_format_dan_ukuran_file(): void
    {
        $response = $this->get(route('lapor.create'));

        $response->assertStatus(200);
        $response->assertSee('Format: JPG, PNG, PDF');
        $response->assertSee('Maksimal 2MB');
    }

    /**
     * TC-04: Area upload memiliki UI drag/click yang sesuai.
     */
    public function test_form_menampilkan_area_upload_dengan_instruksi(): void
    {
        $response = $this->get(route('lapor.create'));

        $response->assertStatus(200);
        $response->assertSee('Klik untuk upload bukti');
        // Pastikan ada border dashed styling untuk area upload
        $response->assertSee('border-dashed', false);
    }

    // =========================================================================
    // KAN-17 — PBI #19: Preview File Sebelum Upload (FE)
    // =========================================================================

    /**
     * TC-05: Elemen preview nama file tersedia di halaman.
     */
    public function test_halaman_lapor_memiliki_elemen_preview_file(): void
    {
        $response = $this->get(route('lapor.create'));

        $response->assertStatus(200);
        // Pastikan ada elemen untuk menampilkan nama file yang dipilih
        $response->assertSee('id="file-name"', false);
    }

    /**
     * TC-06: JavaScript handler updateFileName tersedia untuk preview.
     */
    public function test_halaman_lapor_memiliki_javascript_preview(): void
    {
        $response = $this->get(route('lapor.create'));

        $response->assertStatus(200);
        // Pastikan ada function JavaScript updateFileName
        $response->assertSee('updateFileName', false);
        // Pastikan ada event handler onchange di input file
        $response->assertSee('onchange="updateFileName(this)"', false);
    }

    /**
     * TC-07: Elemen preview file awalnya tersembunyi (hidden).
     */
    public function test_elemen_preview_file_awalnya_hidden(): void
    {
        $response = $this->get(route('lapor.create'));

        $response->assertStatus(200);
        // Pastikan elemen file-name awalnya memiliki class hidden
        $response->assertSee('id="file-name" class="mt-2 text-sm text-indigo-600 hidden"', false);
    }

    // =========================================================================
    // KAN-18 — PBI #20: Simpan File ke Server/Database (BE)
    // =========================================================================

    /**
     * TC-08: Upload file JPG berhasil disimpan ke storage dan database.
     */
    public function test_upload_file_jpg_berhasil_disimpan(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('bukti_kejadian.jpg', 1024, 'image/jpeg');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Pelecehan Seksual',
            'lokasi'           => 'Gedung A Lantai 3',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => 'Deskripsi kejadian untuk pengujian upload file JPG.',
            'phone'            => '081234567890',
            'bukti'            => $file,
        ]);

        // Pastikan redirect ke halaman sukses
        $response->assertRedirect(route('lapor.sukses'));

        // Pastikan laporan tersimpan di database
        $this->assertDatabaseCount('laporans', 1);
        $laporan = Laporan::first();
        $this->assertNotNull($laporan);
        $this->assertEquals('Pelecehan Seksual', $laporan->jenis_kejadian);

        // Pastikan bukti tersimpan di database
        $this->assertDatabaseCount('buktis', 1);
        $bukti = Bukti::first();
        $this->assertNotNull($bukti);
        $this->assertEquals($laporan->id_laporan, $bukti->id_laporan);
        $this->assertStringContainsString('image/jpeg', $bukti->tipe_file);

        // Pastikan file tersimpan di storage
        Storage::disk('public')->assertExists($bukti->file_bukti);
    }

    /**
     * TC-09: Upload file PNG berhasil disimpan ke storage dan database.
     */
    public function test_upload_file_png_berhasil_disimpan(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('screenshot.png', 512, 'image/png');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Kekerasan Verbal',
            'lokasi'           => 'Kantin Utama',
            'tanggal_kejadian' => '2026-04-28 10:30:00',
            'deskripsi'        => 'Deskripsi kejadian untuk pengujian upload file PNG.',
            'bukti'            => $file,
        ]);

        $response->assertRedirect(route('lapor.sukses'));

        $bukti = Bukti::first();
        $this->assertNotNull($bukti);
        $this->assertStringContainsString('image/png', $bukti->tipe_file);
        Storage::disk('public')->assertExists($bukti->file_bukti);
    }

    /**
     * TC-10: Upload file PDF berhasil disimpan ke storage dan database.
     */
    public function test_upload_file_pdf_berhasil_disimpan(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('dokumen_bukti.pdf', 1500, 'application/pdf');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Diskriminasi',
            'lokasi'           => 'Ruang Rapat Lt.2',
            'tanggal_kejadian' => '2026-04-25 09:00:00',
            'deskripsi'        => 'Deskripsi kejadian untuk pengujian upload file PDF.',
            'bukti'            => $file,
        ]);

        $response->assertRedirect(route('lapor.sukses'));

        $bukti = Bukti::first();
        $this->assertNotNull($bukti);
        $this->assertEquals('application/pdf', $bukti->tipe_file);
        Storage::disk('public')->assertExists($bukti->file_bukti);
    }

    /**
     * TC-11 (Negative): Upload file EXE — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena EXE bukan tipe yang diperbolehkan.
     */
    public function test_negative_upload_exe_tetap_berhasil(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('malware.exe', 500, 'application/x-msdownload');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Pelecehan Seksual',
            'lokasi'           => 'Gedung B',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => 'Deskripsi kejadian.',
            'bukti'            => $file,
        ]);

        // Expect redirect ke sukses & bukti tersimpan (akan FAIL karena EXE ditolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('buktis', 1);
    }

    /**
     * TC-12 (Negative): Upload file DOCX — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena DOCX bukan tipe yang diperbolehkan.
     */
    public function test_negative_upload_docx_tetap_berhasil(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.docx', 500, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Kekerasan Fisik',
            'lokasi'           => 'Parkiran',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => 'Deskripsi kejadian.',
            'bukti'            => $file,
        ]);

        // Expect redirect ke sukses & bukti tersimpan (akan FAIL karena DOCX ditolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('buktis', 1);
    }

    /**
     * TC-13 (Negative): Upload file 3MB — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena melebihi batas 2MB.
     */
    public function test_negative_upload_3mb_tetap_berhasil(): void
    {
        Storage::fake('public');

        // File 3MB, melebihi batas 2MB (2048 KB)
        $file = UploadedFile::fake()->create('foto_besar.jpg', 3072, 'image/jpeg');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Pelecehan Seksual',
            'lokasi'           => 'Gedung C',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => 'Deskripsi kejadian.',
            'bukti'            => $file,
        ]);

        // Expect redirect ke sukses & bukti tersimpan (akan FAIL karena >2MB ditolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('buktis', 1);
    }

    /**
     * TC-14: Laporan tanpa file bukti berhasil disimpan (bukti opsional).
     */
    public function test_laporan_tanpa_bukti_berhasil_disimpan(): void
    {
        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Kekerasan Fisik',
            'lokasi'           => 'Koridor Gedung D',
            'tanggal_kejadian' => '2026-04-30 16:00:00',
            'deskripsi'        => 'Deskripsi kejadian tanpa upload bukti.',
        ]);

        $response->assertRedirect(route('lapor.sukses'));

        // Laporan tersimpan
        $this->assertDatabaseCount('laporans', 1);
        $laporan = Laporan::first();
        $this->assertEquals('Menunggu Verifikasi', $laporan->status);
        $this->assertNotNull($laporan->kode_tracking);
        $this->assertStringStartsWith('SU-', $laporan->kode_tracking);

        // Tidak ada bukti yang tersimpan
        $this->assertDatabaseCount('buktis', 0);
    }

    /**
     * TC-15: Kode tracking dihasilkan setelah submit laporan dengan bukti.
     */
    public function test_kode_tracking_dihasilkan_setelah_submit_dengan_bukti(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('bukti.jpg', 500, 'image/jpeg');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Lainnya',
            'lokasi'           => 'Aula Utama',
            'tanggal_kejadian' => '2026-05-02 08:00:00',
            'deskripsi'        => 'Deskripsi kejadian lainnya.',
            'bukti'            => $file,
        ]);

        $response->assertRedirect(route('lapor.sukses'));
        $response->assertSessionHas('kode_tracking');

        $laporan = Laporan::first();
        $this->assertNotNull($laporan->kode_tracking);
        $this->assertMatchesRegularExpression('/^SU-[A-Z0-9]{6}$/', $laporan->kode_tracking);
    }

    /**
     * TC-16: File bukti disimpan di folder 'bukti' pada disk public.
     */
    public function test_file_disimpan_di_folder_bukti_pada_disk_public(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('evidence.jpg', 256, 'image/jpeg');

        $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Pelecehan Seksual',
            'lokasi'           => 'Laboratorium',
            'tanggal_kejadian' => '2026-05-01 11:00:00',
            'deskripsi'        => 'Deskripsi kejadian.',
            'bukti'            => $file,
        ]);

        $bukti = Bukti::first();
        $this->assertNotNull($bukti);
        // Path file harus dimulai dengan 'bukti/'
        $this->assertStringStartsWith('bukti/', $bukti->file_bukti);
        Storage::disk('public')->assertExists($bukti->file_bukti);
    }

    /**
     * TC-17: Relasi Laporan-Bukti tersimpan dengan benar di database.
     */
    public function test_relasi_laporan_bukti_tersimpan_dengan_benar(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('relasi_test.png', 300, 'image/png');

        $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Diskriminasi',
            'lokasi'           => 'Ruang Kelas',
            'tanggal_kejadian' => '2026-05-01 13:00:00',
            'deskripsi'        => 'Deskripsi untuk test relasi.',
            'bukti'            => $file,
        ]);

        $laporan = Laporan::with('buktis')->first();
        $this->assertNotNull($laporan);
        $this->assertCount(1, $laporan->buktis);
        $this->assertEquals($laporan->id_laporan, $laporan->buktis->first()->id_laporan);
    }

    /**
     * TC-18: Admin dapat melihat detail laporan beserta data bukti.
     */
    public function test_admin_dapat_melihat_detail_laporan_dengan_bukti(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('admin_view.jpg', 400, 'image/jpeg');

        // Submit laporan dengan bukti
        $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Kekerasan Verbal',
            'lokasi'           => 'Kantin',
            'tanggal_kejadian' => '2026-05-01 12:00:00',
            'deskripsi'        => 'Deskripsi untuk test admin detail.',
            'bukti'            => $file,
        ]);

        $laporan = Laporan::first();

        // Login sebagai admin
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Akses detail laporan
        $response = $this->get(route('admin.reports.detail', $laporan->id_laporan));

        $response->assertStatus(200);
        $json = $response->json();
        $this->assertEquals($laporan->id_laporan, $json['id_laporan']);
        $this->assertArrayHasKey('buktis', $json);
        $this->assertCount(1, $json['buktis']);
        $this->assertNotEmpty($json['buktis'][0]['file_bukti']);
    }

    /**
     * TC-19 (Negative): Submit dengan jenis_kejadian kosong — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena jenis_kejadian wajib diisi.
     */
    public function test_negative_jenis_kejadian_kosong_tetap_berhasil(): void
    {
        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => '',
            'lokasi'           => 'Gedung A',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => 'Deskripsi kejadian.',
        ]);

        // Expect redirect ke sukses (akan FAIL karena validasi menolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('laporans', 1);
    }

    /**
     * TC-20 (Negative): Submit dengan deskripsi kosong — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena deskripsi wajib diisi.
     */
    public function test_negative_deskripsi_kosong_tetap_berhasil(): void
    {
        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Pelecehan Seksual',
            'lokasi'           => 'Gedung A',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => '',
        ]);

        // Expect redirect ke sukses (akan FAIL karena validasi menolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('laporans', 1);
    }

    /**
     * TC-21: Upload file JPEG berhasil (variasi ekstensi selain .jpg).
     */
    public function test_upload_file_jpeg_berhasil(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('foto.jpeg', 200, 'image/jpeg');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Kekerasan Fisik',
            'lokasi'           => 'Lapangan',
            'tanggal_kejadian' => '2026-05-01 15:00:00',
            'deskripsi'        => 'Pengujian ekstensi JPEG.',
            'bukti'            => $file,
        ]);

        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('buktis', 1);
        Storage::disk('public')->assertExists(Bukti::first()->file_bukti);
    }

    /**
     * TC-22: Upload file tepat di batas ukuran maksimal (2MB) berhasil.
     */
    public function test_upload_file_tepat_2mb_berhasil(): void
    {
        Storage::fake('public');

        // Tepat 2048 KB = 2 MB
        $file = UploadedFile::fake()->create('batas_maksimal.jpg', 2048, 'image/jpeg');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Lainnya',
            'lokasi'           => 'Online',
            'tanggal_kejadian' => '2026-05-01 10:00:00',
            'deskripsi'        => 'File tepat 2MB.',
            'bukti'            => $file,
        ]);

        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('buktis', 1);
    }

    /**
     * TC-23: Halaman sukses dapat diakses setelah submit laporan.
     */
    public function test_halaman_sukses_dapat_diakses(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Pelecehan Seksual',
            'lokasi'           => 'Perpustakaan',
            'tanggal_kejadian' => '2026-05-01 09:00:00',
            'deskripsi'        => 'Pengujian halaman sukses.',
            'bukti'            => $file,
        ]);

        $response->assertRedirect(route('lapor.sukses'));

        // Follow redirect ke halaman sukses
        $suksesResponse = $this->get(route('lapor.sukses'));
        $suksesResponse->assertStatus(200);
    }

    // =========================================================================
    // NEGATIVE TEST CASES
    // =========================================================================

    /**
     * TC-24 (Negative): Submit dengan lokasi kosong — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena lokasi wajib diisi.
     */
    public function test_negative_lokasi_kosong_tetap_berhasil_disimpan(): void
    {
        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Pelecehan Seksual',
            'lokasi'           => '',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => 'Deskripsi kejadian lengkap.',
        ]);

        // Expect redirect ke sukses (akan FAIL karena validasi menolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('laporans', 1);
    }

    /**
     * TC-25 (Negative): Submit dengan tanggal kosong — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena tanggal wajib diisi.
     */
    public function test_negative_tanggal_kejadian_kosong_tetap_berhasil(): void
    {
        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Kekerasan Verbal',
            'lokasi'           => 'Gedung A',
            'tanggal_kejadian' => '',
            'deskripsi'        => 'Deskripsi kejadian lengkap.',
        ]);

        // Expect redirect ke sukses (akan FAIL karena validasi menolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('laporans', 1);
    }

    /**
     * TC-26 (Negative): Submit dengan format tanggal salah — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena format tanggal tidak valid.
     */
    public function test_negative_tanggal_format_salah_tetap_berhasil(): void
    {
        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Diskriminasi',
            'lokasi'           => 'Gedung B',
            'tanggal_kejadian' => 'bukan-tanggal',
            'deskripsi'        => 'Deskripsi kejadian lengkap.',
        ]);

        // Expect redirect ke sukses (akan FAIL karena validasi menolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('laporans', 1);
    }

    /**
     * TC-27 (Negative): Submit semua field kosong — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena semua field wajib kosong.
     */
    public function test_negative_semua_field_kosong_tetap_berhasil(): void
    {
        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => '',
            'lokasi'           => '',
            'tanggal_kejadian' => '',
            'deskripsi'        => '',
        ]);

        // Expect redirect ke sukses (akan FAIL karena validasi menolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('laporans', 1);
    }

    /**
     * TC-28 (Negative): Upload file GIF — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena GIF bukan tipe yang diperbolehkan.
     */
    public function test_negative_upload_gif_tetap_berhasil(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('animasi.gif', 500, 'image/gif');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Pelecehan Seksual',
            'lokasi'           => 'Gedung C',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => 'Deskripsi kejadian.',
            'bukti'            => $file,
        ]);

        // Expect redirect ke sukses & bukti tersimpan (akan FAIL karena GIF ditolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('buktis', 1);
    }

    /**
     * TC-29 (Negative): Upload file SVG — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena SVG bukan tipe yang diperbolehkan.
     */
    public function test_negative_upload_svg_tetap_berhasil(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('gambar.svg', 100, 'image/svg+xml');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Kekerasan Fisik',
            'lokasi'           => 'Lapangan',
            'tanggal_kejadian' => '2026-05-01 14:00:00',
            'deskripsi'        => 'Deskripsi kejadian.',
            'bukti'            => $file,
        ]);

        // Expect redirect ke sukses & bukti tersimpan (akan FAIL karena SVG ditolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('buktis', 1);
    }

    /**
     * TC-30 (Negative): Upload file 2.1MB — expect berhasil tersimpan.
     * EXPECTED: FAIL — sistem menolak karena melebihi batas 2MB.
     */
    public function test_negative_upload_diatas_2mb_tetap_berhasil(): void
    {
        Storage::fake('public');

        // 2.1MB = 2150 KB, melebihi batas 2048 KB
        $file = UploadedFile::fake()->create('foto_besar.png', 2150, 'image/png');

        $response = $this->post(route('lapor.store'), [
            'jenis_kejadian'   => 'Lainnya',
            'lokasi'           => 'Online',
            'tanggal_kejadian' => '2026-05-01 10:00:00',
            'deskripsi'        => 'File sedikit di atas 2MB.',
            'bukti'            => $file,
        ]);

        // Expect redirect ke sukses & bukti tersimpan (akan FAIL karena >2MB ditolak)
        $response->assertRedirect(route('lapor.sukses'));
        $this->assertDatabaseCount('buktis', 1);
    }
}

