<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Models\BakeBreadSize;
use App\Models\CommercialTerm;
use App\Models\CycleCount;
use App\Models\DeclinedSupply;
use App\Models\MeasurementUnit;
use App\Models\Order;
use App\Models\PreparedProduct;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Quarantine;
use App\Models\Resource;
use App\Models\SupplierCategory;
use App\Models\Supplier;
use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\SupplyReception;
use App\Models\User;

use App\Observers\BakeBreadSizeObserver;
use App\Observers\CommercialTermObserver;
use App\Observers\CycleCountObserver;
use App\Observers\DeclinedSupplyObserver;
use App\Observers\MeasurementUnitObserver;
use App\Observers\OrderObserver;
use App\Observers\PreparedProductObserver;
use App\Observers\ProductObserver;
use App\Observers\ProductSizeObserver;
use App\Observers\QuarantineObserver;
use App\Observers\ResourceObserver;
use App\Observers\SupplierCategoryObserver;
use App\Observers\SupplierObserver;
use App\Observers\SupplyObserver;
use App\Observers\SupplyOrderObserver;
use App\Observers\SupplyReceptionObserver;
use App\Observers\UserObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        BakeBreadSize::observe(BakeBreadSizeObserver::class);
        CommercialTerm::observe(CommercialTermObserver::class);
        CycleCount::observe(CycleCountObserver::class);
        DeclinedSupply::observe(DeclinedSupplyObserver::class);
        MeasurementUnit::observe(MeasurementUnitObserver::class);
        Order::observe(OrderObserver::class);
        PreparedProduct::observe(PreparedProductObserver::class);
        Product::observe(ProductObserver::class);
        ProductSize::observe(ProductSizeObserver::class);
        Quarantine::observe(QuarantineObserver::class);
        Resource::observe(ResourceObserver::class);
        SupplierCategory::observe(SupplierCategoryObserver::class);
        Supplier::observe(SupplierObserver::class);
        Supply::observe(SupplyObserver::class);
        SupplyOrder::observe(SupplyOrderObserver::class);
        SupplyReception::observe(SupplyReceptionObserver::class);
        User::observe(UserObserver::class);
    }
}
