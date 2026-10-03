<?php

namespace Tests\Feature;

use App\Models\FileReport;
use App\Models\Inspection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InspectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_criar_uma_inspecao()
    {
        // Aqui mantemos o payload puro porque estamos testando a entrada de dados do Frontend.
        // O Controller é quem deve garantir a injeção do "requested_at" e "status" no banco.
        $payload = [
            'name' => 'Nome test',
            'type' => 'active',
            'address' => 'Rua Principal, 123',
            'dengue_breeding_site_spotted' => false,
        ];

        $response = $this->postJson('/api/inspections', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['status' => 'draft']);

        $this->assertDatabaseHas('inspections', ['name' => 'Nome test']);
    }

    public function test_pode_gerar_urls_pre_assinadas()
    {
        Storage::fake('s3');

        // Cria a inspeção magicamente com a Factory, sobrescrevendo só o status
        $inspection = Inspection::factory()->create(['status' => 'draft']);

        $payload = [
            'files' => [
                ['ref_id' => 'temp-1', 'filename' => 'video.mp4', 'content_type' => 'video/mp4']
            ]
        ];

        $response = $this->postJson("/api/inspections/{$inspection->id}/batch-upload-urls", $payload);

        $response->assertOk()
            ->assertJsonStructure(['urls' => [['ref_id', 'upload_url', 'path']]]);
    }

    public function test_pode_enviar_arquivos_para_fila_de_processamento()
    {
        Queue::fake();

        $inspection = Inspection::factory()->create(['status' => 'draft']);

        $payload = [
            'files' => [
                [
                    // Usando o ID real da inspection gerada
                    'path' => "inspections/{$inspection->id}/raw/video.mp4",
                    'name' => 'video.mp4',
                    'mime_type' => 'video/mp4'
                ]
            ]
        ];

        $response = $this->postJson("/api/inspections/{$inspection->id}/process", $payload);

        $response->assertOk();
        $this->assertDatabaseHas('files', ['path' => "inspections/{$inspection->id}/raw/video.mp4"]);
        $this->assertDatabaseHas('inspections', ['id' => $inspection->id, 'status' => 'queued']);
    }

    public function test_can_get_inspection_with_file()
    {
        $report = FileReport::factory()->create();
        $inspection = $report->file->inspection;

        $response = $this->getJson("/api/inspections/{$inspection->id}");

        $response->assertOk();
    }
}
