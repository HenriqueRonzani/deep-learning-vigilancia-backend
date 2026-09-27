<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\FileReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_ia_pode_criar_relatorios_em_lote()
    {
        // Cria um arquivo real no banco (A factory de File criará a Inspection automaticamente por trás dos panos)
        $file = File::factory()->create();

        $payload = [
            'reports' => [
                [
                    'file_id' => $file->id,
                    'irregularity' => 'open_water_tank',
                    'agent_report' => 'Caixa de água sem tampa encontrada no telhado.'
                ]
            ]
        ];

        $response = $this->postJson('/api/file-report/batch', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('file_reports', [
            'file_id' => $file->id,
            'irregularity' => 'open_water_tank'
        ]);
    }

    public function test_usuario_pode_dar_feedback_no_relatorio()
    {
        // Cria o Report diretamente (e o Laravel cuidará de criar o File e a Inspection pai)
        $report = FileReport::factory()->create([
            'irregularity' => 'abandoned_pool',
            'status' => 'created'
        ]);

        $response = $this->patchJson("/api/file-report/{$report->id}/feedback", [
            'feedback' => 'correct'
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('file_reports', [
            'id' => $report->id,
            'user_feedback' => 'correct'
        ]);
    }
}
