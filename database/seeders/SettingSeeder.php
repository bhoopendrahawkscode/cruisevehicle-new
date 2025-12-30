<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

                'websiteTitle' => 'Octal software Pvt Ltd',
                'companyEmail' => 'koginip@mailinator.com',
                'companyPhone' => '9785539620',
                'companyAddress' => 'koginip@mailinator.com',
                'dateFormat' => 'dd-mm-yyyy',
                'googleAnalytics' => 'Commodi sint impedit',
                'thirdPartyJs' => 'https://google.com',
                'customCss' => 'Mollit eveniet cons',
                'primaryColor' => '#8964ce',
                'secondaryColor' => '#381c55',
                'header' => '<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #000000;" width="100%">
                <tbody>
                    <tr>
                        <td>
                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fff;" width="100%">
                            <tbody>
                                <tr>
                                    <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px; margin: 0 auto;" width="650">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:15px;padding-top:15px;width:100%;padding-right:0px;padding-left:0px;">
                                                            <div align="center" class="alignment" style="line-height:10px">
                                                            <div style="max-width: 195px;"><img alt="your logo" src="http://2gthr-dev.octallab.com/assets/images/logo.png" style="display: block; border: 0px; width: 100%;" title="your logo" /></div>
                                                            </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </td>
                    </tr>
                </tbody>
            </table>',

                'footer' => '<table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #000000;" width="100%">
            <tbody>
                <tr>
                    <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fff;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 650px; margin: 0 auto;" width="650">
                                    <tbody>
                                        <tr>
                                            <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                            <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td class="pad" style="padding-bottom:15px;padding-top:15px;width:100%;padding-right:0px;padding-left:0px;">
                                                        <div align="center" class="alignment" style="line-height:10px">
                                                        <div style="max-width: 195px;"><img alt="your logo" src="http://2gthr-dev.octallab.com/assets/images/logo.png" style="display: block; border: 0px; width: 192px; height: 192px;" title="your logo" /></div>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>',
                'pageLimit' => 50,
                'UploadServerType' => 'Local',
                'isMaintenanceMode' => 'Up',

        ];


        Setting::updateOrCreate(['websiteTitle' => $data['websiteTitle'], 'companyEmail' => $data['companyEmail'], 'companyPhone' => $data['companyPhone']], $data);
    }
}
