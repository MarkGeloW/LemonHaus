@if(auth()->check() && auth()->user()->role === 'cashier')
<aside class="w-[285px] min-h-screen bg-white border-r border-[#e5e7eb] flex flex-col justify-between shrink-0">

    <div>
        <!-- Logo -->
        <div class="h-[92px] px-6 flex items-center gap-4 border-b border-[#e5e7eb]">
            <div class="w-10 h-10 rounded-full bg-[#f4c400] flex items-center justify-center text-white font-bold text-xl">
                L
            </div>

            <h1 class="text-[26px] font-extrabold text-[#0f2747]">
                LEMONHAUS
            </h1>
        </div>

        <!-- Menu -->
        <nav class="px-4 py-6 space-y-3">

            <a href="{{ route('cashier.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('cashier.dashboard') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h6v6H4V5Zm10 0h6v6h-6V5ZM4 15h6v6H4v-6Zm10-3h6v9h-6v-9Z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('cashier.orders') }}"
               class="sidebar-link {{ request()->routeIs('cashier.orders') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a4 4 0 1 1 8 0v2m-9 0h10a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z"/>
                </svg>
                <span>Order Management</span>
            </a>

            <a href="{{ route('cashier.kitchen') }}"
               class="sidebar-link {{ request()->routeIs('cashier.kitchen') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 18h6M10 22h4M8 14h8a4 4 0 1 0-1.17-7.83A5 5 0 0 0 5.1 7.5 3.5 3.5 0 0 0 8 14Z"/>
                </svg>
                <span>Kitchen Queue</span>
            </a>

         

        </nav>
    </div>

    <!-- Bottom -->
    <div class="border-t border-[#e5e7eb] px-6 py-7">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full border-2 border-[#cbd5e1] flex items-center justify-center text-[#94a3b8]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0m8-11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                </svg>
            </div>

            <div>
                <h3 class="text-[18px] font-semibold text-[#0f172a]">
                    {{ auth()->user()->name ?? 'Admin User' }}
                </h3>
                <p class="text-sm text-gray-500">
                    {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 text-red-500 font-medium hover:text-red-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0 4-4m-4 4 4 4m6-9h6a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-6"/>
                </svg>
                <span>Sign Out</span>
            </button>
        </form>
    </div>

</aside>
@endif