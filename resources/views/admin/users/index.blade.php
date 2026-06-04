@extends('layouts.admin')

@section('title', 'Quản lý người dùng')
@section('page-title', 'Quản lý người dùng')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<style>
    /* --- ÉP ĐỘ ƯU TIÊN CSS CỦA DATATABLES ĐỂ LÊN CÙNG 1 HÀNG --- */
    .dataTables_wrapper {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding-bottom: 16px !important;
        gap: 16px !important;
    }

    /* Ép bộ lọc "Hiển thị dòng" sang trái */
    .dataTables_wrapper .dataTables_length {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        color: #64748b !important;
        font-size: 0.875rem !important;
        margin: 0 !important;
        float: none !important;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 6px 24px 6px 12px !important;
        outline: none !important;
        font-size: 0.875rem !important;
        background-color: #fff !important;
    }

    /* Ép ô "Tìm kiếm" sang phải */
    .dataTables_wrapper .dataTables_filter {
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        color: #64748b !important;
        font-size: 0.875rem !important;
        margin: 0 !important;
        float: none !important;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 7px 12px !important;
        outline: none !important;
        width: 240px !important;
        font-size: 0.875rem !important;
        transition: all 0.15s ease !important;
    }

    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #4f46e5 !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }

    /* --- Cấu trúc lại khu vực Bảng dữ liệu --- */
    .dt-table-container {
        width: 100% !important;
        order: 2 !important; /* Đảm bảo bảng luôn nằm dưới thanh tìm kiếm */
        margin-top: 8px !important;
    }

    #users-table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        clear: both !important;
    }

    #users-table thead th {
        background-color: #f8fafc !important;
        color: #64748b !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.05em !important;
        padding: 12px 10px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-top: 1px solid #f1f5f9 !important;
    }

    #users-table tbody td {
        padding: 14px 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155 !important;
        font-size: 0.875rem !important;
    }

    #users-table tbody tr:hover {
        background-color: #f8fafc !important;
    }

    /* --- Khu vực Phân trang đáy bảng --- */
    .dt-footer-container {
        width: 100% !important;
        order: 3 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding-top: 16px !important;
        border-top: 1px solid #f1f5f9 !important;
    }

    .dataTables_wrapper .dataTables_info {
        float: none !important;
        padding: 0 !important;
        color: #64748b !important;
        font-size: 0.875rem !important;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: none !important;
        padding: 0 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        padding: 5px 12px !important;
        margin: 0 2px !important;
        border: 1px solid #e2e8f0 !important;
        background: #fff !important;
        color: #475569 !important;
        font-size: 0.875rem !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f1f5f9 !important;
        color: #1e293b !important;
        border-color: #cbd5e1 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #fff !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f8fafc !important;
        color: #cbd5e1 !important;
        border-color: #e2e8f0 !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">

        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center border border-indigo-100/30 shrink-0">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-slate-900 tracking-tight">
                        Danh sách người dùng
                    </h3>
                    <p class="text-slate-500 text-sm mt-0.5">
                        Quản lý và theo dõi tất cả tài khoản trong hệ thống
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all duration-150 shadow-sm hover:shadow active:scale-[0.98] shrink-0">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Thêm người dùng mới
            </a>
        </div>

        <div class="p-6">
            <table id="users-table" class="w-full display cell-border">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th width="100">Vai trò</th>
                        <th width="130">Trạng thái</th>
                        <th width="140">Ngày tạo</th>
                        <th width="130">Thao tác</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(function () {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.users.datatable') }}",
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true,

        /* Thay đổi cấu trúc DOM: Nhóm bảng và footer vào div riêng để kiểm soát flexbox */
        dom: '<"flex justify-between items-center mb-4"lf><"dt-table-container overflow-x-auto"rt><"dt-footer-container"ip>',

        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            {
                data: 'role_badge',
                name: 'role',
                orderable: false,
                searchable: false
            },
            {
                data: 'status_badge',
                name: 'status',
                orderable: false,
                searchable: false
            },
            { data: 'created_at', name: 'created_at' },
            {
                data: 'actions',
                orderable: false,
                searchable: false
            }
        ],

        language: {
            processing: "Đang tải dữ liệu...",
            search: "",
            lengthMenu: "Hiển thị _MENU_ dòng",
            info: "Hiển thị _START_ đến _END_ của _TOTAL_ người dùng",
            infoEmpty: "Không có dữ liệu",
            infoFiltered: "(lọc từ _MAX_ bản ghi)",
            paginate: {
                first: "Đầu",
                last: "Cuối",
                next: "›",
                previous: "‹"
            },
            zeroRecords: "Không tìm thấy kết quả"
        },

        initComplete: function() {
            /* Đưa placeholder gọn gàng vào trong ô input tìm kiếm */
            $('.dataTables_filter input').attr('placeholder', 'Tìm kiếm tại đây...');
        },

        drawCallback: function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    });
});
</script>
@endpush
