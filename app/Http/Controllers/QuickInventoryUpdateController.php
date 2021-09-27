<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use App\Model\Item;
use App\Model\ReceivingOrder;
use App\Model\WebAdminSetting;
use App\Model\Department;
use App\Model\Category;
use App\Model\Manufacturer;
use App\Model\Unit;
use App\Model\Supplier;
use App\Model\Size;
use App\Model\SubCategory;
use App\Model\ItemGroup;
use App\Model\AgeVerification;
use App\Model\Store;
use App\Model\StoreSettings;
use App\Model\Vendor;


class QuickInventoryUpdateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getForm(){

        return view('quickInventoryUpdate.quick_inventory_update');
    }

}

?>
