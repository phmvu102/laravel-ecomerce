<div class="flex items-center gap-2">

    <a href="{{ route('admin.users.show',$user) }}"
       class="px-3 py-1.5 text-xs bg-sky-100 text-sky-600 rounded-lg hover:bg-sky-200">
        Xem
    </a>

    <a href="{{ route('admin.users.edit',$user) }}"
       class="px-3 py-1.5 text-xs bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200">
        Sửa
    </a>

    <form action="{{ route('admin.users.destroy',$user) }}"
          method="POST"
          onsubmit="return confirm('Xóa người dùng này?')">

        @csrf
        @method('DELETE')

        <button
            class="px-3 py-1.5 text-xs bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
            Xóa
        </button>

    </form>

</div>
