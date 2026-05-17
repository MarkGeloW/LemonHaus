@if(auth()->check() && auth()->user()->role === 'cashier')

<!-- Mobile Top Bar -->
<div class="lg:hidden fixed top-0 left-0 right-0 z-[80] h-[72px] bg-white border-b border-[#e5e7eb] px-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-[#f4c400] flex items-center justify-center text-white font-bold text-xl">
            L
        </div>

        <h1 class="text-[22px] font-extrabold text-[#0f2747]">
            LEMONHAUS
        </h1>
    </div>

    <button type="button"
            onclick="openCashierSidebar()"
            class="w-11 h-11 rounded-2xl bg-[#f8fafc] border border-[#e5e7eb] flex items-center justify-center text-[#0f172a]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</div>

<!-- Mobile Overlay -->
<div id="cashier-sidebar-overlay"
     onclick="closeCashierSidebar()"
     class="fixed inset-0 z-[90] hidden bg-[#0f172a]/60 backdrop-blur-sm lg:hidden">
</div>

<!-- Sidebar -->
<aside id="cashier-sidebar"
       class="fixed lg:static inset-y-0 left-0 z-[100] w-[285px] min-h-screen bg-white border-r border-[#e5e7eb] flex flex-col justify-between shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

    <div>
        <div class="h-[92px] px-6 flex items-center justify-between gap-4 border-b border-[#e5e7eb]">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-[#f4c400] flex items-center justify-center text-white font-bold text-xl">
                    L
                </div>

                <h1 class="text-[26px] font-extrabold text-[#0f2747]">
                    LEMONHAUS
                </h1>
            </div>

            <button type="button"
                    onclick="closeCashierSidebar()"
                    class="lg:hidden w-10 h-10 rounded-xl bg-[#f8fafc] border border-[#e5e7eb] flex items-center justify-center text-[#64748b]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="px-4 py-6 space-y-3">
            <a href="{{ route('cashier.dashboard') }}"
               onclick="closeCashierSidebar()"
               class="sidebar-link {{ request()->routeIs('cashier.dashboard') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h6v6H4V5Zm10 0h6v6h-6V5ZM4 15h6v6H4v-6Zm10-3h6v9h-6v-9Z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('cashier.orders.index') }}"
               onclick="closeCashierSidebar()"
               class="sidebar-link {{ request()->routeIs('cashier.orders.index') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a4 4 0 1 1 8 0v2m-9 0h10a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z"/>
                </svg>
                <span>Order Management</span>
            </a>

            <a href="{{ route('cashier.kitchen') }}"
               onclick="closeCashierSidebar()"
               class="sidebar-link {{ request()->routeIs('cashier.kitchen') ? 'active-sidebar' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 18h6M10 22h4M8 14h8a4 4 0 1 0-1.17-7.83A5 5 0 0 0 5.1 7.5 3.5 3.5 0 0 0 8 14Z"/>
                </svg>
                <span>Kitchen Queue</span>
            </a>
        </nav>
    </div>

    <div class="border-t border-[#e5e7eb] px-6 py-7">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full border-2 border-[#cbd5e1] flex items-center justify-center text-[#94a3b8] shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0m8-11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                </svg>
            </div>

            <div class="min-w-0">
                <h3 class="text-[18px] font-semibold text-[#0f172a] truncate">
                    {{ auth()->user()->name ?? 'Cashier' }}
                </h3>

                <p class="text-sm text-gray-500">
                    {{ ucfirst(auth()->user()->role ?? 'Cashier') }}
                </p>
            </div>
        </div>

        <form id="cashier-logout-form" method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="button"
                    onclick="openCashierLogoutModal()"
                    class="flex w-full items-center gap-3 text-red-500 font-medium hover:text-red-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0 4-4m-4 4 4 4m6-9h6a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-6"/>
                </svg>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>
@endif

<!-- Logout Modal -->
<div id="cashier-logout-modal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-[#0f172a]/70 backdrop-blur-sm transition-opacity duration-300 opacity-0 px-4">
    <div id="cashier-logout-modal-content" class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-red-100/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1"/>
            </svg>
        </div>

        <h3 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a] text-center mb-3">
            Sign Out?
        </h3>

        <p class="text-[15px] sm:text-[16px] text-[#64748b] text-center mb-8 px-2 sm:px-4">
            Are you sure you want to securely sign out of your LemonHaus account?
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            <button type="button"
                    onclick="closeCashierLogoutModal()"
                    class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[16px] sm:text-[17px]">
                Cancel
            </button>

            <button type="button"
                    onclick="document.getElementById('cashier-logout-form').submit()"
                    class="flex-1 py-4 rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-md transition text-[16px] sm:text-[17px]">
                Yes, Sign Out
            </button>
        </div>
    </div>
</div>

<script>
    const cashierSidebar = document.getElementById('cashier-sidebar');
    const cashierSidebarOverlay = document.getElementById('cashier-sidebar-overlay');

    function openCashierSidebar() {
        if (!cashierSidebar || !cashierSidebarOverlay) {
            return;
        }

        cashierSidebar.classList.remove('-translate-x-full');
        cashierSidebar.classList.add('translate-x-0');
        cashierSidebarOverlay.classList.remove('hidden');
    }

    function closeCashierSidebar() {
        if (!cashierSidebar || !cashierSidebarOverlay) {
            return;
        }

        cashierSidebar.classList.add('-translate-x-full');
        cashierSidebar.classList.remove('translate-x-0');
        cashierSidebarOverlay.classList.add('hidden');
    }

    const cashierLogoutModal = document.getElementById('cashier-logout-modal');
    const cashierLogoutModalContent = document.getElementById('cashier-logout-modal-content');

    function openCashierLogoutModal() {
        cashierLogoutModal.classList.remove('hidden');

        setTimeout(function () {
            cashierLogoutModal.classList.remove('opacity-0');
            cashierLogoutModal.classList.add('opacity-100');
            cashierLogoutModalContent.classList.remove('scale-95');
            cashierLogoutModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeCashierLogoutModal() {
        cashierLogoutModal.classList.remove('opacity-100');
        cashierLogoutModal.classList.add('opacity-0');
        cashierLogoutModalContent.classList.remove('scale-100');
        cashierLogoutModalContent.classList.add('scale-95');

        setTimeout(function () {
            cashierLogoutModal.classList.add('hidden');
        }, 300);
    }
</script>