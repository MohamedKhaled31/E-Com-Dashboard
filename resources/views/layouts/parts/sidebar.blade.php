<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Interface
    </div>

    <li class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Categories</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Products</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('suppliers.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Suppliers</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('purchase-invoices.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('purchase-invoices.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Purchase Invoices</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('sale-invoices.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('sale-invoices.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Sale Invoices</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('reports.sales') }}">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Sales Report</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('reports.purchases') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('reports.purchases') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Purchase Report</span>
        </a>
    </li>
</ul>
<!-- End of Sidebar -->
