<?php

namespace App\Helpers\Traits;

use Livewire\WithPagination;
use Mary\Traits\Toast;

trait BaseComponentTrait
{
    use WithPagination,
        Toast,
        HandlesAuthentication,
        HandlesMasterUpdates,
        HasMasterOptions,
        HasTableHelpers,
        QuickCreateTrait,
        ManualActivityLog,
        HandlesPriceCalculations,
        LocalizationTrait,
        AccessPolicyHelper;

    #[\Livewire\Attributes\Url(except: '')]
    public ?string $search = '';

    #[\Livewire\Attributes\Url(as: 'filter', except: '')]
    public array $filters = [
        'start_date' => null,
        'end_date' => null,
    ];

    #[\Livewire\Attributes\Url(except: '')]
    public ?string $lang = 'en';

    #[\Livewire\Attributes\Url(as: 'filter-detail', except: '')]
    public array $filter_details = [
        'start_date' => null,
        'end_date' => null,
    ];

    #[\Livewire\Attributes\Url(except: '', as: 'tab_id')]
    public ?string $tabId = null;

    public array $statusOptions = [];
    public array $statusApproveOptions = [];
    public array $statusPriorityOptions = [];
    public array $msEmployeeOptions = [];
    public array $msBranchOptions = [];
    public array $msSupplierOptions = [];
    public array $msCustomerOptions = [];
    public array $msProductOptions = [];
    public array $msWarehouseOptions = [];
    public array $currencyOptions = [];
    public array $msCustomerAddressOptions = [];
    public array $opsiAccessRight = [];
    public array $opsiAccessRightGroup = [];
    public array $opsiAccessRightGroupCategory = [];
    public array $opsiGroundRulesCategory = [];
    public array $opsiPengajuanCategory = [];
    public array $opsiChannelCategory = [];
    public array $opsiSpecialCaseCategory = [];

    public string $modelAccessRight = \App\Models\AccessRight::class;
    public string $modelAccessRightGroup = \App\Models\AccessRightGroup::class;
    public string $modelEmployeeSession = \App\Models\MsEmployeeSession::class;
    public string $modelEmployee = \App\Models\MsEmployee::class;
    public string $modelEmployeeAccount = \App\Models\MsEmployeeAccount::class;

    public bool $updateCurrencyModalMsProductPricePurchaseQuotation = false;

    #[\Livewire\Attributes\Locked]
    public ?string $id = null;

    #[\Livewire\Attributes\Url(keep: true)]
    public ?string $sesid = null;

    public bool $drawer = false;
    public bool $isDrawer = false;
    public bool $drawerDetail = false;
    public bool $isDrawerDetail = false;

    public array $sortBy = [
        'column' => 'name',
        'direction' => 'desc',
    ];

    public int $perPage = 20;
    public array $checkboxes = [];
    public ?string $employeeLoginName = null;
    public ?string $employeeLoginPosition = null;
    public ?string $employeeLoginWarehouse = null;
    public ?string $employeeLoginWarehouseBranch = null;
    public ?string $employeeLoginId = null;

    public $activityLogs = [];
    public bool $isLogModalOpen = false;
}
