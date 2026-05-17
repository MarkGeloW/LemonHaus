@extends('layouts.app')

@section('content')
@php
    $tab = request('tab', 'users');
@endphp

<div class="min-h-screen flex bg-[#f6f7fb]">

    @include('admin.partials.sidebar')

    <main class="flex-1 w-full px-4 sm:px-6 lg:px-9 pt-[96px] lg:pt-10 pb-10 overflow-x-hidden relative">
        <div class="max-w-[1340px] mx-auto">

            @if(session('success'))
                <div class="mb-5 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-[30px] sm:text-[38px] font-extrabold leading-tight text-[#0f172a]">
                        Admin Console
                    </h1>

                    <p class="text-[15px] sm:text-[18px] text-[#64748b] mt-3">
                        Manage users and view system activity
                    </p>
                </div>
            </div>

            <div class="border-b border-[#e5e7eb] mb-7 overflow-x-auto">
                <nav class="flex items-center gap-8 sm:gap-12 min-w-max">
                    <a href="{{ route('admin.index', ['tab' => 'users']) }}"
                       class="flex items-center gap-3 pb-4 text-[15px] sm:text-[17px] font-semibold border-b-2 {{ $tab === 'users' ? 'border-[#eab308] text-[#d08a00]' : 'border-transparent text-[#64748b]' }}">
                        User Management
                    </a>

                    <a href="{{ route('admin.index', ['tab' => 'logs']) }}"
                       class="flex items-center gap-3 pb-4 text-[15px] sm:text-[17px] font-semibold border-b-2 {{ $tab === 'logs' ? 'border-[#eab308] text-[#d08a00]' : 'border-transparent text-[#64748b]' }}">
                        Audit Logs
                    </a>
                </nav>
            </div>

            @if($tab === 'users')
                <div class="grid grid-cols-1 xl:grid-cols-[1.3fr_0.7fr] gap-6">

                    <div class="overflow-hidden rounded-[24px] border border-[#e9edf3] bg-white shadow-sm">
                        <div class="px-5 sm:px-7 py-5 border-b border-[#eef2f7]">
                            <h2 class="text-[21px] sm:text-[24px] font-bold text-[#0f172a]">
                                Users
                            </h2>

                            <p class="text-[14px] sm:text-[16px] text-[#64748b] mt-1">
                                Scroll sideways on small screens to view all columns.
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[850px] text-left">
                                <thead class="bg-[#f8fafc] text-[#64748b]">
                                    <tr>
                                        <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold">Name</th>
                                        <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold">Email</th>
                                        <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold">Role</th>
                                        <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold">Status</th>
                                        <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-[#eef2f7]">
                                    @forelse($users as $user)
                                        @php
                                            $roleClasses = match($user->role) {
                                                'admin' => 'bg-[#f3e8ff] text-[#9333ea]',
                                                'cashier' => 'bg-[#dcfce7] text-[#16a34a]',
                                                'kitchen' => 'bg-[#ffedd5] text-[#ea580c]',
                                                default => 'bg-gray-100 text-gray-600',
                                            };

                                            $roleLabel = match($user->role) {
                                                'admin' => 'Admin',
                                                'cashier' => 'Cashier',
                                                'kitchen' => 'Kitchen',
                                                default => ucfirst($user->role),
                                            };
                                        @endphp

                                        <tr class="hover:bg-[#f8fafc] transition">
                                            <td class="px-6 sm:px-7 py-5">
                                                <div class="flex items-center gap-4 min-w-0">
                                                    <div class="w-10 h-10 rounded-full bg-[#f3f4f6] flex items-center justify-center text-[18px] font-semibold text-[#334155] shrink-0">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>

                                                    <span class="text-[16px] sm:text-[18px] font-semibold text-[#0f172a] whitespace-nowrap">
                                                        {{ $user->name }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-6 sm:px-7 py-5 text-[15px] sm:text-[16px] text-[#475569] whitespace-nowrap">
                                                {{ $user->email }}
                                            </td>

                                            <td class="px-6 sm:px-7 py-5 whitespace-nowrap">
                                                <span class="inline-flex rounded-full px-4 py-1 text-[14px] font-semibold {{ $roleClasses }}">
                                                    {{ $roleLabel }}
                                                </span>
                                            </td>

                                            <td class="px-6 sm:px-7 py-5 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-2 text-[15px] sm:text-[16px] font-medium text-[#16a34a]">
                                                    <span class="w-2 h-2 rounded-full bg-[#16a34a]"></span>
                                                    Active
                                                </span>
                                            </td>

                                            <td class="px-6 sm:px-7 py-5 text-right whitespace-nowrap">
                                                @if(auth()->id() !== $user->id)
                                                    <form id="delete-form-{{ $user->id }}"
                                                          action="{{ route('admin.users.destroy', $user) }}"
                                                          method="POST"
                                                          class="inline-block">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button"
                                                                onclick="openDeleteModal('{{ $user->id }}', @js($user->name))"
                                                                class="text-[#94a3b8] hover:text-red-500 transition p-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3m-6 0h8"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-7 py-8 text-center text-[#64748b]">
                                                No users found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-[#e9edf3] bg-white shadow-sm p-5 sm:p-7">
                        <h2 class="text-[21px] sm:text-[24px] font-bold text-[#0f172a] mb-6">
                            Add User
                        </h2>

                        <form id="add-user-form" action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">
                                    Full Name
                                </label>

                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Enter full name"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">
                                    Username
                                </label>

                                <input type="text"
                                       name="username"
                                       value="{{ old('username') }}"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Enter username"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Enter email"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">
                                    Role
                                </label>

                                <select name="role"
                                        class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                        required>
                                    <option value="">Select role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                    <option value="kitchen" {{ old('role') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">
                                    Password
                                </label>

                                <input type="password"
                                       name="password"
                                       id="user-password"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Enter password"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">
                                    Confirm Password
                                </label>

                                <input type="password"
                                       name="password_confirmation"
                                       id="user-password-confirm"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Confirm password"
                                       required>
                            </div>

                            <button type="button"
                                    onclick="openAddModal()"
                                    class="w-full rounded-2xl bg-[#0f172a] px-5 py-3 text-white font-semibold hover:bg-[#1e293b] transition">
                                Create User
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($tab === 'logs')
                <div class="overflow-hidden rounded-[24px] border border-[#e9edf3] bg-white shadow-sm">
                    <div class="px-5 sm:px-7 py-5 border-b border-[#eef2f7]">
                        <h2 class="text-[21px] sm:text-[24px] font-bold text-[#0f172a]">
                            Audit Logs
                        </h2>

                        <p class="text-[14px] sm:text-[16px] text-[#64748b] mt-1">
                            Scroll sideways on small screens to view all details.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[900px] text-left">
                            <thead class="bg-[#f8fafc] text-[#64748b]">
                                <tr>
                                    <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold">Timestamp</th>
                                    <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold">User</th>
                                    <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold">Action</th>
                                    <th class="px-6 sm:px-7 py-5 sm:py-6 text-[15px] sm:text-[16px] font-semibold">Details</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-[#eef2f7]">
                                @forelse($auditLogs as $log)
                                    <tr class="hover:bg-[#f8fafc] transition">
                                        <td class="px-6 sm:px-7 py-5 text-[15px] sm:text-[16px] text-[#475569] whitespace-nowrap">
                                            {{ $log->created_at->format('M d, Y h:i A') }}
                                        </td>

                                        <td class="px-6 sm:px-7 py-5 text-[15px] sm:text-[16px] text-[#0f172a] font-medium whitespace-nowrap">
                                            {{ $log->user_name ?? 'System' }}
                                        </td>

                                        <td class="px-6 sm:px-7 py-5 whitespace-nowrap">
                                            <span class="inline-flex rounded-full px-4 py-1 text-[14px] font-semibold
                                                @if(str_contains(strtolower($log->action), 'created'))
                                                    bg-green-100 text-green-700
                                                @elseif(str_contains(strtolower($log->action), 'deleted'))
                                                    bg-red-100 text-red-700
                                                @elseif(str_contains(strtolower($log->action), 'updated'))
                                                    bg-blue-100 text-blue-700
                                                @else
                                                    bg-gray-100 text-gray-700
                                                @endif
                                            ">
                                                {{ $log->action }}
                                            </span>
                                        </td>

                                        <td class="px-6 sm:px-7 py-5 text-[15px] sm:text-[16px] text-[#475569] min-w-[280px]">
                                            {{ $log->details }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-7 py-10 text-center text-[#64748b]">
                                            No audit logs yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </main>

    <div id="add-modal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-[#0f172a]/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 px-4">
        <div id="add-modal-content" class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-yellow-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-yellow-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM3 20a6 6 0 0 1 12 0v1H3v-1z"/>
                </svg>
            </div>

            <h3 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a] text-center mb-3">
                Confirm New User
            </h3>

            <p class="text-[15px] sm:text-[16px] text-[#64748b] text-center mb-8 px-2 sm:px-4">
                Are you sure you want to create this user account?
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="button"
                        onclick="closeAddModal()"
                        class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[16px] sm:text-[17px]">
                    Cancel
                </button>

                <button type="button"
                        onclick="submitAddForm()"
                        class="flex-1 py-4 rounded-2xl font-bold text-slate-900 bg-[#f4c400] hover:bg-[#eab308] shadow-md transition text-[16px] sm:text-[17px]">
                    Yes, Create
                </button>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-[#0f172a]/70 backdrop-blur-sm transition-opacity duration-300 opacity-0 px-4">
        <div id="delete-modal-content" class="bg-white rounded-[28px] sm:rounded-[32px] p-6 sm:p-8 max-w-md w-full shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-6 border-[8px] border-red-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-10 sm:h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3m-6 0h8"/>
                </svg>
            </div>

            <h3 class="text-[22px] sm:text-[26px] font-extrabold text-[#0f172a] text-center mb-3">
                Delete User?
            </h3>

            <p class="text-[15px] sm:text-[16px] text-[#64748b] text-center mb-8 px-2 sm:px-4">
                Are you sure you want to delete
                <span id="delete-user-name" class="font-bold text-slate-800"></span>?
                This action is permanent.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="flex-1 py-4 rounded-2xl font-bold text-[#64748b] bg-slate-100 hover:bg-slate-200 transition text-[16px] sm:text-[17px]">
                    Cancel
                </button>

                <button type="button"
                        onclick="submitDeleteForm()"
                        class="flex-1 py-4 rounded-2xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-md transition text-[16px] sm:text-[17px]">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    const addModal = document.getElementById('add-modal');
    const addModalContent = document.getElementById('add-modal-content');
    const addForm = document.getElementById('add-user-form');

    function openAddModal() {
        if (!addForm || !addForm.checkValidity()) {
            if (addForm) {
                addForm.reportValidity();
            }

            return;
        }

        const pass = document.getElementById('user-password').value;
        const conf = document.getElementById('user-password-confirm').value;

        if (pass !== conf) {
            alert('Passwords do not match.');
            return;
        }

        addModal.classList.remove('hidden');

        setTimeout(function () {
            addModal.classList.remove('opacity-0');
            addModal.classList.add('opacity-100');
            addModalContent.classList.remove('scale-95');
            addModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeAddModal() {
        addModal.classList.remove('opacity-100');
        addModal.classList.add('opacity-0');
        addModalContent.classList.remove('scale-100');
        addModalContent.classList.add('scale-95');

        setTimeout(function () {
            addModal.classList.add('hidden');
        }, 300);
    }

    function submitAddForm() {
        if (addForm) {
            addForm.submit();
        }
    }

    const deleteModal = document.getElementById('delete-modal');
    const deleteModalContent = document.getElementById('delete-modal-content');
    let currentDeleteFormId = null;

    function openDeleteModal(userId, userName) {
        currentDeleteFormId = 'delete-form-' + userId;
        document.getElementById('delete-user-name').innerText = userName;

        deleteModal.classList.remove('hidden');

        setTimeout(function () {
            deleteModal.classList.remove('opacity-0');
            deleteModal.classList.add('opacity-100');
            deleteModalContent.classList.remove('scale-95');
            deleteModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeDeleteModal() {
        deleteModal.classList.remove('opacity-100');
        deleteModal.classList.add('opacity-0');
        deleteModalContent.classList.remove('scale-100');
        deleteModalContent.classList.add('scale-95');

        setTimeout(function () {
            deleteModal.classList.add('hidden');
        }, 300);

        currentDeleteFormId = null;
    }

    function submitDeleteForm() {
        if (currentDeleteFormId) {
            document.getElementById(currentDeleteFormId).submit();
        }
    }
</script>
@endsection