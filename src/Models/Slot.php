<?php

namespace Kainotomo\PHMoney\Models;

use App\Models\Setting;
use Illuminate\Support\Carbon;

class Slot extends Base
{

    protected $connection = 'mysql_portfolio';

    public $timestamps = false;

    protected $fillable = [
        'team_id',
        'obj_guid',
        'name',
        'slot_type',
        'int64_val',
        'string_val',
        'double_val',
        'timespec_val',
        'guid_val',
        'numeric_val_num',
        'numeric_val_denom',
        'gdate_val'
    ];

    /**
     * Get options from slots
     *
     * @return array
     */
    public static function getOptions()
    {
        $options = Slot::where('team_id', auth()->user()->current_team_id)->where('name', 'LIKE', 'options%')->orWhere('name', 'LIKE', 'counters%')->orWhere('name', 'LIKE', 'counter_formats%')->get();
        $setting = auth()->user()->currentTeam->settings()->firstOrCreate(
            [
                'team_id' => auth()->user()->current_team_id,
                'type' => "AccountingPeriod"
            ],
            [
                'name' => "Accounting Period",
                'params' => [
                    'date_start' => [
                        'filter_date' => now()->firstOfYear()->toDateString(),
                        'date_type' => "filter_list",
                        'list_date' => [
                            'id' => "start_of_this_year",
                            'name' => "Start of this year"
                        ],
                    ],
                    'date_end' => [
                        'filter_date' => now()->toDateString(),
                        'date_type' => "filter_list",
                        'list_date' => [
                            'id' => "today",
                            'name' => "Today"
                        ],
                    ],
                ],
            ]
        );

        return [
            'guid' => $options->firstWhere('name', 'options')->guid_val ?? null,
            'accounting_period' => [
                'date_start' => $setting->params['date_start'],
                'date_end' => $setting->params['date_end'],
            ],
            'business' => [
                'guid' => $options->firstWhere('name', 'options/Business')->guid_val ?? null,
                'company_name' => $options->firstWhere('name', 'options/Business/Company Name')->string_val ?? null,
                'company_address' => $options->firstWhere('name', 'options/Business/Company Address')->string_val ?? null,
                'company_contact_person' => $options->firstWhere('name', 'options/Business/Company Contact Person')->string_val ?? null,
                'company_phone_number' => $options->firstWhere('name', 'options/Business/Company Phone Number')->string_val ?? null,
                'company_fax_number' => $options->firstWhere('name', 'options/Business/Company Fax Number')->string_val ?? null,
                'company_email_address' => $options->firstWhere('name', 'options/Business/Company Email Address')->string_val ?? null,
                'company_website_url' => $options->firstWhere('name', 'options/Business/Company Website URL')->string_val ?? null,
                'company_id' => $options->firstWhere('name', 'options/Business/Company ID')->string_val ?? null,
                'default_vendor_taxtable' => Taxtable::firstWhere('guid', $options->firstWhere('name', 'options/Business/Default Vendor TaxTable')->guid_val ?? null),
                'default_customer_taxtable' => Taxtable::firstWhere('guid', $options->firstWhere('name', 'options/Business/Default Customer TaxTable')->guid_val ?? null),
            ],
            'counters' => [
                'guid' => $options->firstWhere('name', 'counters')->guid_val ?? null,
                'vendor' => $options->firstWhere('name', 'counters/gncVendor')->int64_val ?? null,
                'invoice' => $options->firstWhere('name', 'counters/gncInvoice')->int64_val ?? null,
                'job' => $options->firstWhere('name', 'counters/gncJob')->int64_val ?? null,
                'employee' => $options->firstWhere('name', 'counters/gncEmployee')->int64_val ?? null,
                'bill' => $options->firstWhere('name', 'counters/gncBill')->int64_val ?? null,
            ],
            'counter_formats' => [
                'guid' => $options->firstWhere('name', 'counter_formats')->guid_val ?? null,
                'vendor' => $options->firstWhere('name', 'counter_formats/gncVendor')->string_val ?? null,
                'invoice' => $options->firstWhere('name', 'counter_formats/gncInvoice')->string_val ?? null,
                'job' => $options->firstWhere('name', 'counter_formats/gncJob')->string_val ?? null,
                'employee' => $options->firstWhere('name', 'counter_formats/gncEmployee')->string_val ?? null,
                'bill' => $options->firstWhere('name', 'counter_formats/gncBill')->string_val ?? null,
            ],
            'tax' => [
                'guid' => $options->firstWhere('name', 'options/Tax')->guid_val ?? null,
                'tax_number' => $options->firstWhere('name', 'options/Tax/Tax Number')->string_val ?? null,
            ],
        ];
    }
}
