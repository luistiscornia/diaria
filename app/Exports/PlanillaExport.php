<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;

class PlanillaExport implements FromArray, WithMapping, WithStyles, WithColumnWidths
{

    use Exportable;


    const TITLE_STYLE = [
        'font' => [
            'bold' => true,
            'color' => [ 'rgb' => 'FFFFFF' ],
            'size' => 16,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' =>  [ 'rgb' => '1E569A' ]
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        '___merge' => true,
    ];

    const HEADING_STYLE = [
        'font' => [
            'bold' => true,
            'color' => [ 'rgb' => 'FFFFFF' ]
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' =>  [ 'rgb' => '1E569A' ]
        ],
    ];
    const TIME_STYLE = [
        'font' => [
            'italic' => true,
            'color' => [ 'rgb' => 'FFFFFF' ]
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' =>  [ 'rgb' => '29A1E5' ]
        ],
        '___merge' => true,
    ];

    const TITLE2_STYLE = [
        'font' => [
            'bold' => true,
            'color' => [ 'rgb' => 'FFFFFF' ],
            'size' => 16,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' =>  [ 'rgb' => 'ff9900' ]
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        '___merge' => true,
    ];

    protected $sheets = [1];
    protected $data = [];
    protected $rowStyles = [];
    protected $rowCount = 1;
    protected $agentesTotal;
    protected $sector;
    protected $fechaStr;
    protected $jefeDeTurno;
    protected $agentesConLicencia;



    public function __construct(array $data,$agentesTotal,$sector, $fechaStr, $jefeDeTurno, $agentesConLicencia)
    {
        $this->data = $data;
        $this->agentesTotal = $agentesTotal;
        $this->sector = $sector;
        $this->fechaStr = $fechaStr;
        $this->jefeDeTurno = $jefeDeTurno;
        $this->agentesConLicencia = $agentesConLicencia;
    }

    public function array(): array
    {
        return $this->sheets;
    }

    /**
    * @var Data $data
    */
    public function map($doc): array
    {

        $rows = [];
        $rowCount = 1;


        $rows[] = ["Planilla de $this->sector del $this->fechaStr · Jefe de Turno: ".( $this->jefeDeTurno->nombre_agente ?? 'Sin Jefe de Turno' )."· $this->agentesTotal Agentes" ];
        $this->rowStyles[$rowCount] = self::TITLE_STYLE;
        $rowCount++;


        $rows[] = ["Horario","Sector","Agente","Puesto","Guardia","Día","Reemplaza a:","Detalle","Presente","RT"];
        $this->rowStyles[$rowCount] = self::HEADING_STYLE;
        $rowCount++;


        foreach ($this->data as $ingreso => $items) {

            $count = count($items);
            $rows[] = [ "Horario: $ingreso · $count Agentes" ];
            $this->rowStyles[$rowCount] = self::TIME_STYLE;
            $rowCount++;


            foreach ($items as $item) {

                $rows[] = [
                    $item->ingreso,
                    $item->etiqueta,
                    $item->nombre_agente,
                    $item->nombre_puesto_migratorio,
                    $item->guardia,
                    $item->dia,
                    $item->agente_cobertura,
                    $item->nota,
                    $item->presente,
                    $item->rel_temp,
                ];

                $rowCount++;

            }

        }

        if(count($this->agentesConLicencia) > 0){

            $rows[] = [];
            $rowCount++;

            $rows[] = [ "Agentes con Licencias en el día" ];
            $this->rowStyles[$rowCount] = self::TITLE2_STYLE;
            $rowCount++;

            $rows[] = [null, null, "Agente","Tipo de Licencia","Desde","Hasta"];
            $this->rowStyles[$rowCount] = self::HEADING_STYLE;
            $rowCount++;

            foreach ($this->agentesConLicencia as $item) {

                $rows[] = [
                    null,
                    null,
                    $item->nombre_agente,
                    $item->tipolicencia__nombre,
                    $item->licencia__fecha_desde,
                    $item->licencia__fecha_hasta,
                ];

                $rowCount++;

            }

        }

        $this->rowCount = $rowCount;
        return $rows;
    }


    public function styles(Worksheet $sheet)
    {

        // Merge todos los que tienen ___merge === true
        foreach ($this->rowStyles as $row => $styles) {
            if($styles['___merge'] ?? null === true){
                $sheet->mergeCells("A$row:J$row");
            }
        }

        $sheet->getPageSetup()->setPrintArea("A1:J$this->rowCount");
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(null);
        $sheet->getPageSetup()->setFirstPageNumber(1);
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1,2);
        $sheet->getHeaderFooter()->setOddFooter('&CPage &P of &N');

        return $this->rowStyles;

    }


    public function columnWidths(): array
    {

        return [
            'A' => 10,
            'B' => 10,
            'C' => 25,
            'D' => 20,
            'E' => 10,
            'F' => 10,
            'G' => 25,
            'H' => 20,
            'I' => 10,
            'J' => 10,
        ];


    }

}
