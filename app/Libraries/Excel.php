<?php

namespace App\Libraries;

use App\Models\IntegrantesModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel {

    public function save() {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('archivos/hello world.xlsx');
    }

    public function convert($encabezado, $detalles, $archivo) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $array[] = $encabezado;
        foreach ($detalles as $detalle) {
            $array[] = $detalle;
        }
        $sheet->fromArray($array);
        $writer = new Xlsx($spreadsheet);
        $writer->save($archivo);
    }

    public function read($file) {
        $inputFileType = IOFactory::identify($file);
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file);
        return $spreadsheet->getActiveSheet()->toArray();
    }
}

?>