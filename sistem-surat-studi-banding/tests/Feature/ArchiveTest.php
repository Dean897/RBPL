<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Archive;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArchiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_upload_archive_pdf()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->post(route('archives.store'), [
            'title' => 'Contoh Arsip',
            'category' => 'SK',
            'description' => 'Deskripsi contoh',
            'file' => $file,
            'archived_at' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('archives.index'));

        $this->assertDatabaseHas('archives', [
            'title' => 'Contoh Arsip',
            'category' => 'SK',
        ]);

        $archive = Archive::first();

        Storage::disk('public')->assertExists($archive->file_path);
    }
}
