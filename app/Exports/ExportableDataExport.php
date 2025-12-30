<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportableDataExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data, $columnsToExclude = [])
    {
        $this->data = $data;
        $this->columnsToExclude = $columnsToExclude;
    }


    public function headings(): array
    {

        $firstItem = $this->data->first();
        $keys = $firstItem ? array_keys($firstItem) : ['message'=>'Data Not Found !'];
        $filteredHeadings = array_filter($keys, function ($key) {
            return !in_array($key, $this->columnsToExclude);
        });
        return array_map(function ($key) {
            return ucwords(str_replace(['_', '-'], ' ', $key));
        }, $filteredHeadings);
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            foreach ($this->columnsToExclude as $column) {
                unset($item[$column]);
            }
            return $item;
        });
    }


}
