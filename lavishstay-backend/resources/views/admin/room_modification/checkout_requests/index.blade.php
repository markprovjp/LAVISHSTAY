<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions với enhanced styling -->
        <div class="header-enhanced rounded-2xl p-8 mb-8 text-white shadow-2xl fade-in">
            <div class="sm:flex sm:justify-between sm:items-center">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-3xl md:text-4xl text-white font-bold flex items-center gap-3">
                        {{-- <span class="icon-bounce">🏨</span> --}}
                        Yêu cầu trả phòng sớm/muộn
                    </h1>
                    <p class="text-gray-300 text-lg mt-2">Quản lý các yêu cầu thay đổi thời gian trả phòng</p>
                </div>
                <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                    <a href="{{ route('admin.check_out_requests.create') }}" class="btn-enhanced btn bg-violet-500 hover:bg-violet-600 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Thêm Yêu cầu
                    </a>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages với enhanced styling -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 rounded-xl border-l-4 border-green-500 shadow-lg fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-4 bg-gradient-to-r from-red-100 to-pink-100 text-red-700 rounded-xl border-l-4 border-red-500 shadow-lg fade-in">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Table với enhanced styling -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl dark:border-gray-700 overflow-hidden card-hover fade-in">            <!-- Table Header -->
            <div class="table-header-gradient px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Danh sách yêu cầu
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    ID Đặt phòng
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Loại yêu cầu
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Thời gian trả phòng cũ
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Thời gian trả phòng mới
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    Phí điều chỉnh (VND)
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Trạng thái
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($requests as $request)
                            <tr class="table-row {{ $request->status === 'rejected' ? 'bg-red-600' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100 {{ $request->status === 'rejected' ? 'text-white' : '' }}">{{ $request->booking_id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        {{ $request->type === 'early' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}
                                        {{-- {{ $request->status === 'rejected' ? 'bg-gray-500 text-white' : '' }}"> --}}
                                        <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.414L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ ucfirst($request->type) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-gray-100 {{ $request->status === 'rejected' ? 'text-white' : '' }}">
                                        @if ($request->booking && $request->booking->check_out_date instanceof Carbon\Carbon)
                                            {{ $request->booking->check_out_date->format('d/m/Y H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-gray-100 {{ $request->status === 'rejected' ? 'text-white' : '' }}">
                                        @if ($request->requested_check_out_time instanceof Carbon\Carbon)
                                            {{ $request->requested_check_out_time->format('d/m/Y H:i') }}
                                        @else
                                            {{ $request->requested_check_out_time }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold {{ $request->type === 'early' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $request->type === 'early' ? '+' : '-' }}{{ number_format($request->fee_vnd, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $request->status === 'pending' ? 'status-pending' : 
                                           ($request->status === 'approved' ? 'status-approved' : 'status-rejected') }}
                                        {{ $request->status === 'rejected' ? 'bg-gray-500 text-white' : '' }}">
                                        @if($request->status === 'pending')
                                            <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.414L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($request->status === 'approved')
                                            <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6  text-center py-5">
                                    <div class="mx-auto h-24 w-24  text-gray-400 mb-4">

                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Không có yêu cầu nào</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Chưa có yêu cầu trả phòng sớm/muộn nào được tạo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('a[class*="btn-enhanced"]');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.6);
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>

    <style>
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .status-pending {
            background-color: #fefcbf;
            color: #854d0e;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</x-app-layout>