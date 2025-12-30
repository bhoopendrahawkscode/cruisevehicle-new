<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\InsuranceRenewal;
use App\Models\CompanyInsuranceRenewal;
use App\Constants\Constant;

class InsuranceRenewalsExport implements FromCollection, WithHeadings
{
    protected $searchData;
    protected $zeroZero;
    protected $fullFull;

    public function __construct($searchData)
    { 
        $this->searchData = $searchData;
        $this->zeroZero = Constant::ZERO_ZERO;
        $this->fullFull = Constant::FULL_FULL;
    }

    public function collection()
    {
        $insuranceCompanyId = isset($this->searchData['company']) ? $this->searchData['company'] : '';
        
       return CompanyInsuranceRenewal::where($this->getCompanyQuery($this->searchData))
        ->join('insurance_renewal', 'company_insurance_renewals.insurance_renewal_id', '=', 'insurance_renewal.id')
        ->leftJoin('users', 'company_insurance_renewals.company_id', '=', 'users.id') 
        ->leftJoin('models', 'insurance_renewal.car_model', '=', 'models.id')
        ->whereIn('company_insurance_renewals.status', [3, 4])
        ->select('insurance_renewal.full_name',
                'insurance_renewal.year_of_manufacturer',
                'insurance_renewal.vehicle_registration_mark',
                'insurance_renewal.value',
                'insurance_renewal.sum_to_be_insured',
                'models.name as car_model_name',
                'users.full_name as company_name') // Select specific columns if needed
        ->get();
      
    }

    private function getCompanyQuery($searchData)
    {
        $query = [];
        if (isset($searchData['status']) && $searchData['status'] != '') {
            $query[] = ['status', '=', $searchData['status']];
        }
        if (isset($searchData['from']) && $searchData['from'] != '') {
            $query[] = ['insurance_renewal.created_at', '>=', $searchData['from'] . $this->zeroZero];
        }
        if (isset($searchData['to']) && $searchData['to'] != '') {
            $query[] = ['insurance_renewal.created_at', '<=', $searchData['to'] . $this->fullFull];
        }
        return $query;
    }

    public function headings(): array
    {
        return [
            'Vehicle owner name', 
            'Year of Manufacturer',
            'Vehicle Registration Mark',
            'Vehicle Value',
            'Sum to be insured',
            'Car Model',
            'Insurance Company Name',
           
        ];
    }

    
}
