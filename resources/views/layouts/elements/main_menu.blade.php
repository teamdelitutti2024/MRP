<ul class="navigation">
    <li class="{{ Request::path() == '/' ? 'active' : '' }}"><a href="{{ url('/') }}"><i class="fa fa-laptop"></i> {{ __('Dashboard') }}</a></li>
    <li class="{{ Request::path() == 'users' ? 'active' : '' }}"><a href="{{ url('/users') }}"><i class="fa fa-users"></i> {{ __('Usuarios') }}</a></li>
    <li class="{{ Request::path() == 'suppliers' || Request::path() == 'supplier_categories' ? 'active' : '' }}">
        <a href="#" class="expand" ><i class="fa fa-inbox"></i>{{ __('Proveedores') }}</a>
        <ul>
            <li class="{{ Request::path() == 'suppliers' ? 'active' : '' }}"><a href="{{ url('/suppliers') }}">{{ __('Proveedores') }}</a></li>
            <li class="{{ Request::path() == 'supplier_categories' ? 'active' : '' }}"><a href="{{ url('/supplier_categories') }}">{{ __('Categorías') }}</a></li>
        </ul>
    </li>
    <li class="{{ Request::path() == 'supplies' || Request::path() == 'supply_locations' || Request::path() == 'supply_categories' ? 'active' : '' }}">
        <a href="#" class="expand" ><i class="fa fa-th-list"></i>{{ __('Materias primas') }}</a>
        <ul>
            <li class="{{ Request::path() == 'supplies' ? 'active' : '' }}"><a href="{{ url('/supplies') }}">{{ __('Materias primas') }}</a></li>
            <li class="{{ Request::path() == 'supply_locations' ? 'active' : '' }}"><a href="{{ url('/supply_locations') }}">{{ __('Ubicaciones') }}</a></li>
            <li class="{{ Request::path() == 'supply_categories' ? 'active' : '' }}"><a href="{{ url('/supply_categories') }}">{{ __('Categorías') }}</a></li>
        </ul>
    </li>
    <li class="{{ Request::path() == 'commercial_terms' ? 'active' : '' }}"><a href="{{ url('/commercial_terms') }}"><i class="fa fa-book"></i> {{ __('Condiciones comerciales') }}</a></li>
    <li class="{{ Request::path() == 'measurement_units' ? 'active' : '' }}"><a href="{{ url('/measurement_units') }}"><i class="fa fa-signal"></i> {{ __('Unidades de medida') }}</a></li>
    <li class="{{ Request::path() == 'bake_bread_sizes' ? 'active' : '' }}"><a href="{{ url('/bake_bread_sizes') }}"><i class="fa fa-fire"></i> {{ __('Bases') }}</a></li>
    <li class="{{ Request::path() == 'products' ? 'active' : '' }}"><a href="{{ url('/products') }}"><i class="fa fa-tag"></i> {{ __('Productos') }}</a></li>
    <li class="{{ Request::path() == 'prepared_products' ? 'active' : '' }}"><a href="{{ url('/prepared_products') }}"><i class="fa fa-flask"></i> {{ __('Preparados') }}</a></li>
    <li class="{{ Request::path() == 'resources' ? 'active' : '' }}"><a href="{{ url('/resources') }}"><i class="fa fa-book"></i> {{ __('Recursos') }}</a></li>
    <li class="{{ Request::path() == 'branches' ? 'active' : '' }}"><a href="{{ url('/branches') }}"><i class="fa fa-briefcase"></i> {{ __('Sucursales') }}</a></li>
    <li class="{{ Request::path() == 'orders' ? 'active' : '' }}"><a href="{{ url('/orders') }}"><i class="fa fa-archive"></i> {{ __('Pedidos') }}</a></li>
    <li class="{{ Request::path() == 'products_production' || Request::path() == 'bake_breads_production' ? 'active' : '' }}">
        <a href="#" class="expand" ><i class="fa fa-th-list"></i>{{ __('Producción') }}</a>
        <ul>
            <li class="{{ Request::path() == 'products_production' ? 'active' : '' }}"><a href="{{ url('/products_production') }}">{{ __('Productos') }}</a></li>
            <li class="{{ Request::path() == 'bake_breads_production' ? 'active' : '' }}"><a href="{{ url('/bake_breads_production') }}">{{ __('Bases') }}</a></li>
        </ul>
    </li>
    <li class="{{ Request::path() == 'projection/products' || Request::path() == 'projection/bake_breads' || Request::path() == 'projection/prepared' ? 'active' : '' }}">
        <a href="#" class="expand" ><i class="fa fa-dashboard"></i>{{ __('Proyección') }}</a>
        <ul>
            <li class="{{ Request::path() == 'projection/products' ? 'active' : '' }}"><a href="{{ url('/projection/products') }}">{{ __('Productos') }}</a></li>
            <li class="{{ Request::path() == 'projection/bake_breads' ? 'active' : '' }}"><a href="{{ url('/projection/bake_breads') }}">{{ __('Bases') }}</a></li>
            <li class="{{ Request::path() == 'projection/prepared' ? 'active' : '' }}"><a href="{{ url('/projection/prepared') }}">{{ __('Preparados') }}</a></li>
        </ul>
    </li>
    <li class="{{ Request::path() == 'reports/stock' || Request::path() == 'reports/stock_value' || Request::path() == 'reports/declined_supplies' || Request::path() == 'reports/quarantines' || Request::path() == 'reports/orders' || Request::path() == 'reports/suppliers' || Request::path() == 'reports/supply_orders' || Request::path() == 'reports/supplier_orders' || Request::path() == 'reports/supply_receptions' || Request::path() == 'reports/product_orders' || Request::path() == 'reports/supplies' || Request::path() == 'reports/products' ? 'active' : '' }}">
        <a href="#" class="expand" ><i class="fa fa-tasks"></i>{{ __('Reportes') }}</a>
        <ul>
            <li class="{{ Request::path() == 'reports/stock' ? 'active' : '' }}"><a href="{{ url('/reports/stock') }}">{{ __('Reporte de Cantidades de Inventario') }}</a></li>
            <li class="{{ Request::path() == 'reports/stock_value' ? 'active' : '' }}"><a href="{{ url('/reports/stock_value') }}">{{ __('Reporte de Valoración de Inventario') }}</a></li>
            <li class="{{ Request::path() == 'reports/declined_supplies' ? 'active' : '' }}"><a href="{{ url('/reports/declined_supplies') }}">{{ __('Reporte de Mermas') }}</a></li>
            <li class="{{ Request::path() == 'reports/quarantines' ? 'active' : '' }}"><a href="{{ url('/reports/quarantines') }}">{{ __('Reporte de Cuarentenas') }}</a></li>
            <li class="{{ Request::path() == 'reports/product_orders' ? 'active' : '' }}"><a href="{{ url('/reports/product_orders') }}">{{ __('Reporte de Pedidos') }}</a></li>
            <li class="{{ Request::path() == 'reports/orders' ? 'active' : '' }}"><a href="{{ url('/reports/orders') }}">{{ __('Reporte de Órdenes de Compra') }}</a></li>
            <li class="{{ Request::path() == 'reports/supply_orders' ? 'active' : '' }}"><a href="{{ url('/reports/supply_orders') }}">{{ __('Reporte de Órdenes de Compra por Materia Prima') }}</a></li>
            <li class="{{ Request::path() == 'reports/supplier_orders' ? 'active' : '' }}"><a href="{{ url('/reports/supplier_orders') }}">{{ __('Reporte de Órdenes de Compra por Proveedor') }}</a></li>
            <li class="{{ Request::path() == 'reports/supply_receptions' ? 'active' : '' }}"><a href="{{ url('/reports/supply_receptions') }}">{{ __('Reporte de Recepciones de Materia Prima') }}</a></li>
            <li class="{{ Request::path() == 'reports/supplies' ? 'active' : '' }}"><a href="{{ url('/reports/supplies') }}">{{ __('Reporte de Materias Primas') }}</a></li>
            <li class="{{ Request::path() == 'reports/products' ? 'active' : '' }}"><a href="{{ url('/reports/products') }}">{{ __('Reporte de Productos') }}</a></li>
            <li class="{{ Request::path() == 'reports/suppliers' ? 'active' : '' }}"><a href="{{ url('/reports/suppliers') }}">{{ __('Reporte de Proveedores') }}</a></li>
        </ul>
    </li>
    <li class="{{ Request::path() == 'supply_orders' ? 'active' : '' }}"><a href="{{ url('/supply_orders') }}"><i class="fa fa-list-alt"></i> {{ __('Pedidos de materia prima') }}</a></li>
    <li class="{{ Request::path() == 'supply_receptions' ? 'active' : '' }}"><a href="{{ url('/supply_receptions') }}"><i class="fa fa-list-alt"></i> {{ __('Recepciones de materia prima') }}</a></li>
    <li class="{{ Request::path() == 'supply_transfers' ? 'active' : '' }}"><a href="{{ url('/supply_transfers') }}"><i class="fa fa-refresh"></i> {{ __('Transferencias de mta. prima') }}</a></li>
    <li class="{{ Request::path() == 'declined_supplies' || Request::path() == 'declined_products' || Request::path() == 'declined_bake_breads' || Request::path() == 'declined_prepared_products' ? 'active' : '' }}">
        <a href="#" class="expand" ><i class="fa fa-tasks"></i>{{ __('Mermas') }}</a>
        <ul>
            <li class="{{ Request::path() == 'declined_supplies' ? 'active' : '' }}"><a href="{{ url('/declined_supplies') }}">{{ __('Materias primas') }}</a></li>
            <li class="{{ Request::path() == 'declined_products' ? 'active' : '' }}"><a href="{{ url('/declined_products') }}">{{ __('Productos') }}</a></li>
            <li class="{{ Request::path() == 'declined_bake_breads' ? 'active' : '' }}"><a href="{{ url('/declined_bake_breads') }}">{{ __('Bases') }}</a></li>
            <li class="{{ Request::path() == 'declined_prepared_products' ? 'active' : '' }}"><a href="{{ url('/declined_prepared_products') }}">{{ __('Preparados') }}</a></li>
        </ul>
    </li>
    <li class="{{ Request::path() == 'quarantines' ? 'active' : '' }}"><a href="{{ url('/quarantines') }}"><i class="fa fa-warning"></i> {{ __('Cuarentenas') }}</a></li>
    <li class="{{ Request::path() == 'general_stock' || Request::path() == 'stock/level_1' || Request::path() == 'stock/level_2' ? 'active' : '' }}">
        <a href="#" class="expand" ><i class="fa fa-tasks"></i>{{ __('Inventario') }}</a>
        <ul>
            <li class="{{ Request::path() == 'general_stock' ? 'active' : '' }}"><a href="{{ url('/general_stock') }}">{{ __('Inventario general') }}</a></li>
            <li class="{{ Request::path() == 'stock/upload_stock' ? 'active' : '' }}"><a href="{{ url('/stock/upload_stock') }}">{{ __('Actualizar inventario') }}</a></li>
            <li class="{{ Request::path() == 'stock/level_1' ? 'active' : '' }}"><a href="{{ url('/stock/level_1') }}">{{ __('Inventario nivel 1') }}</a></li>
            <li class="{{ Request::path() == 'stock/level_2' ? 'active' : '' }}"><a href="{{ url('/stock/level_2') }}">{{ __('Inventario nivel 2') }}</a></li>
        </ul>
    </li>
    <li class="{{ Request::path() == 'cycle_counts' ? 'active' : '' }}"><a href="{{ url('/cycle_counts') }}"><i class="fa fa-repeat"></i> {{ __('Conteos') }}</a></li>
    <li class="{{ Request::path() == 'departure_shipments' ? 'active' : '' }}"><a href="{{ url('/departure_shipments') }}"><i class="fa fa-level-up"></i> {{ __('Embarques de salida') }}</a></li>
    <li class="{{ Request::path() == 'inbound_shipments' ? 'active' : '' }}"><a href="{{ url('/inbound_shipments') }}"><i class="fa fa-level-down"></i> {{ __('Embarques de entrada') }}</a></li>
    <li class="{{ Request::path() == 'changes_log' ? 'active' : '' }}"><a href="{{ url('/changes_log') }}"><i class="fa fa-exclamation-circle"></i> {{ __('Log de cambios') }}</a></li>
</ul>