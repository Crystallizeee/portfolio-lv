<?php

namespace Tests\Feature;

use App\Livewire\Admin\CvGenerator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CvGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_cv_generator_page_renders_successfully()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.cv-generator'))
            ->assertSuccessful()
            ->assertSeeLivewire(CvGenerator::class);
    }

    public function test_can_generate_pdf()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'phone' => '123456',
            'address' => 'Test Address',
            'linkedin' => 'lnkdn',
            'website' => 'web',
            'summary' => 'sum'
        ]);

        $component = Livewire::actingAs($user)
            ->test(CvGenerator::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com');

        $component->call('generatePdf')
            ->assertFileDownloaded('cv-john-doe.pdf');
    }
    public function test_can_generate_pdf_with_manual_data()
    {
        $user = User::factory()->create(['name' => 'Jane Doe']);

        $component = Livewire::actingAs($user)
            ->test(CvGenerator::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('useDbExperiences', false)
            ->set('useDbSkills', false)
            ->call('addManualExperience')
            ->set('manualExperiences.0.company', 'Tech Corp')
            ->set('manualExperiences.0.role', 'Developer')
            ->call('addManualSkill')
            ->set('manualSkills.0.name', 'Laravel');

        $component->call('generatePdf')
            ->assertFileDownloaded('cv-jane-doe.pdf');
    }
    public function test_can_generate_pdf_with_db_data()
    {
        // Seed data
        \App\Models\Experience::create([
            'company' => 'Db Corp',
            'role' => 'Db Dev',
            'type' => 'Fulltime',
            'date_range' => '2020-2021',
            'description' => 'Test',
            'sort_order' => 1
        ]);

        $user = User::factory()->create(['name' => 'Db User']);

        $component = Livewire::actingAs($user)
            ->test(CvGenerator::class)
            ->set('name', 'Db User')
            ->set('email', 'db@example.com')
            ->set('useDbExperiences', true)
            ->set('useDbSkills', true);

        $component->call('generatePdf')
            ->assertFileDownloaded('cv-db-user.pdf');
    }
}

