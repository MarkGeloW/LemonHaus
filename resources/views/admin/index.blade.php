@extends('layouts.app')

@section('content')
@php
    $tab = request('tab', 'users');
@endphp

<div class="min-h-screen flex bg-[#f6f7fb]">

    @include('admin.partials.sidebar')

    <main class="flex-1 px-9 pt-10 pb-10">
        <div class="max-w-[1340px]">

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
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-[38px] font-extrabold leading-none text-[#0f172a]">Admin Console</h1>
                    <p class="text-[18px] text-[#64748b] mt-3">Manage users and view system activity</p>
                </div>
            </div>

            <div class="border-b border-[#e5e7eb] mb-7">
                <nav class="flex items-center gap-12">
                    <a href="{{ route('admin.index', ['tab' => 'users']) }}"
                       class="flex items-center gap-3 pb-4 text-[17px] font-semibold border-b-2 {{ $tab === 'users' ? 'border-[#eab308] text-[#d08a00]' : 'border-transparent text-[#64748b]' }}">
                        User Management
                    </a>

                    <a href="{{ route('admin.index', ['tab' => 'logs']) }}"
                       class="flex items-center gap-3 pb-4 text-[17px] font-semibold border-b-2 {{ $tab === 'logs' ? 'border-[#eab308] text-[#d08a00]' : 'border-transparent text-[#64748b]' }}">
                        Audit Logs
                    </a>
                </nav>
            </div>

            @if($tab === 'users')
                <div class="grid grid-cols-1 xl:grid-cols-[1.3fr_0.7fr] gap-6">

                    <div class="overflow-hidden rounded-[24px] border border-[#e9edf3] bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                            <thead class="bg-[#f8fafc] text-[#64748b]">
                                <tr>
                                    <th class="px-7 py-6 text-[16px] font-semibold">Name</th>
                                    <th class="px-7 py-6 text-[16px] font-semibold">Email</th>
                                    <th class="px-7 py-6 text-[16px] font-semibold">Role</th>
                                    <th class="px-7 py-6 text-[16px] font-semibold">Status</th>
                                    <th class="px-7 py-6 text-[16px] font-semibold text-right">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-[#eef2f7]">
                                @forelse($users as $user)
                                    @php
                                        $roleClasses = match($user->role) {
                                            'admin' => 'bg-[#f3e8ff] text-[#9333ea]',
                                            'cashier' => 'bg-[#dcfce7] text-[#16a34a]',
                                            'kitchen' => 'bg-[#ffedd5] text-[#ea580c]',
                                            'inventory' => 'bg-[#dbeafe] text-[#2563eb]',
                                            default => 'bg-gray-100 text-gray-600',
                                        };

                                        $roleLabel = match($user->role) {
                                            'admin' => 'Admin',
                                            'cashier' => 'Cashier',
                                            'kitchen' => 'Kitchen',
                                            'inventory' => 'Inventory',
                                            default => ucfirst($user->role),
                                        };
                                    @endphp

                                    <tr>
                                        <td class="px-7 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full bg-[#f3f4f6] flex items-center justify-center text-[18px] font-semibold text-[#334155]">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="text-[18px] font-semibold text-[#0f172a]">{{ $user->name }}</span>
                                            </div>
                                        </td>

                                        <td class="px-7 py-5 text-[16px] text-[#475569]">{{ $user->email }}</td>

                                        <td class="px-7 py-5">
                                            <span class="inline-flex rounded-full px-4 py-1 text-[14px] font-semibold {{ $roleClasses }}">
                                                {{ $roleLabel }}
                                            </span>
                                        </td>

                                        <td class="px-7 py-5">
                                            <span class="inline-flex items-center gap-2 text-[16px] font-medium text-[#16a34a]">
                                                <span class="w-2 h-2 rounded-full bg-[#16a34a]"></span>
                                                Active
                                            </span>
                                        </td>

                                        <td class="px-7 py-5 text-right">
                                            @if(auth()->id() !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-[#94a3b8] hover:text-red-500 transition">
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

                    <div class="rounded-[24px] border border-[#e9edf3] bg-white shadow-sm p-7">
                        <h2 class="text-[24px] font-bold text-[#0f172a] mb-6">Add User</h2>

                        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Enter full name" required>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">Username</label>
                                <input type="text" name="username" value="{{ old('username') }}"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Enter username" required>
                            </div>
                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Enter email" required>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">Role</label>
                                <select name="role"
                                        class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]" required>
                                    <option value="">Select role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                    <option value="kitchen" {{ old('role') == 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                                    <option value="inventory" {{ old('role') == 'inventory' ? 'selected' : '' }}>Inventory</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">Password</label>
                                <input type="password" name="password"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Enter password" required>
                            </div>

                            <div>
                                <label class="block text-[15px] font-medium text-[#334155] mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full rounded-2xl border border-[#dbe3ec] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#f4b400]"
                                       placeholder="Confirm password" required>
                            </div>

                            <button type="submit"
                                    class="w-full rounded-2xl bg-[#0f172a] px-5 py-3 text-white font-semibold hover:bg-[#1e293b] transition">
                                Create User
                            </button>
                        </form>
                    </div>

                </div>
            @endif

            @if($tab === 'logs')
                <div div class="overflow-x-auto">
                        <class="overflow-hidden rounded-[24px] border border-[#e9edf3] bg-white shadow-sm">
                    <table class="w-full text-left">
                        <thead class="bg-[#f8fafc] text-[#64748b]">
                            <tr>
                                <th class="px-7 py-6 text-[16px] font-semibold">Timestamp</th>
                                <th class="px-7 py-6 text-[16px] font-semibold">User</th>
                                <th class="px-7 py-6 text-[16px] font-semibold">Action</th>
                                <th class="px-7 py-6 text-[16px] font-semibold">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#eef2f7]">
                            <tr>
                                <td class="px-7 py-5 text-[16px] text-[#475569]">Apr 19, 2026 8:41 PM</td>
                                <td class="px-7 py-5 text-[16px] text-[#0f172a] font-medium">Admin User</td>
                                <td class="px-7 py-5 text-[16px] text-[#0f172a]">Created User</td>
                                <td class="px-7 py-5 text-[16px] text-[#475569]">Added Sarah Cashier account</td>
                            </tr>
                        </table>
                    </divbody>
                    </table>
                </div>
            @endif

        </div>
    </main>
</div>
@endsection