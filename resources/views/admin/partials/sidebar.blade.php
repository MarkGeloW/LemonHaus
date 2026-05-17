@if(auth()->check() && auth()->user()->role === 'admin')
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
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h6v6H4V5Zm10 0h6v6h-6V5ZM4 15h6v6H4v-6Zm10-3h6v9h-6v-9Z"/>
                </svg>
                <span>Dashboard</span>
            </a>

           <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.index') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a4 4 0 1 1 8 0v2m-9 0h10a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z"/>
                </svg>
                <span>Order Management</span>
            </a>

<a href="{{ url('/admin/kitchen') }}" class="sidebar-link {{ request()->is('admin/kitchen') ? 'active-sidebar' : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18h6M10 22h4M8 14h8a4 4 0 1 0-1.17-7.83A5 5 0 0 0 5.1 7.5 3.5 3.5 0 0 0 8 14Z"/>
    </svg>
    <span>Kitchen Queue</span>
</a>
          <a href="{{ route('admin.inventory') }}" class="sidebar-link {{ request()->routeIs('admin.inventory') ? 'active-sidebar' : '' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 4.5 9 5.25v10.5l-9-5.25V4.5Zm0 0L3 7.125v10.5l4.5-2.625m9-5.25L12 12.375m0 0L3 7.125"/>
    </svg>
    <span>Inventory</span>
</a>

            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.index') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m5 14V9m5 10V3m5 16v-6"/>
                </svg>
                <span>Reports</span>
            </a>

            <a href="{{ route('admin.index') }}" class="sidebar-link {{ request()->routeIs('admin.index') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4Z"/>
                </svg>
                <span>Admin & Audit</span>
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

        <!-- Custom Sign Out Trigger -->
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="openLogoutModal()" class="flex w-full items-center gap-3 text-red-500 font-medium hover:text-red-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0 4-4m-4 4 4 4m6-9h6a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-6"/>
                </svg>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>
@endif

<!-- GLOBAL LOGOUT MODAL -->
<div id="logout-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-[#0f172a]/70 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div id="logout-modal-content" class="bg-white rounded-[32px] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-red-100/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </div>
        <h3 class="text-[26px] font-extrabold text-[#0f172a] text-center mb-3">Sign Out?</h3>
        <p class="text-[16px] text-[#64748b] text-center mb-8 px-4">
            Are you sure you want to securely sign out of your LemonHaus account?
        </p>
        <div class="flex gap-4">
            <button type="button" onclick="closeLogoutModal()" class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[17px]">
                Cancel
            </button>
            <button type="button" onclick="document.getElementById('logout-form').submit()" class="flex-1 py-4 rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-md transition text-[17px]">
                Yes, Sign Out
            </button>
        </div>
    </div>
</div>

<script>
    const logoutModal = document.getElementById('logout-modal');
    const logoutModalContent = document.getElementById('logout-modal-content');

    function openLogoutModal() {
        logoutModal.classList.remove('hidden');
        setTimeout(() => {
            logoutModal.classList.remove('opacity-0');
            logoutModal.classList.add('opacity-100');
            logoutModalContent.classList.remove('scale-95');
            logoutModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeLogoutModal() {
        logoutModal.classList.remove('opacity-100');
        logoutModal.classList.add('opacity-0');
        logoutModalContent.classList.remove('scale-100');
        logoutModalContent.classList.add('scale-95');
        setTimeout(() => { logoutModal.classList.add('hidden'); }, 300);
    }
</script>