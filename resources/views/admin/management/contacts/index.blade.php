@extends('admin.layouts.app')

@section('content')
<div class="container-xxl">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Lien he tu khach hang</h4>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-2 mb-3">
                <div class="col-md-5">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tim theo ten, email, noi dung...">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tat ca trang thai</option>
                        <option value="pending" @selected(request('status')==='pending' )>Chua xu ly</option>
                        <option value="processed" @selected(request('status')==='processed' )>Da xu ly</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Loc</button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-light">Dat lai</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Khach hang</th>
                            <th>Email</th>
                            <th>Noi dung</th>
                            <th>Trang thai</th>
                            <th>Gui luc</th>
                            <th class="text-end">Hanh dong</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr>
                            <td>{{ $message->id }}</td>
                            <td>{{ trim($message->first_name . ' ' . $message->last_name) ?: 'N/A' }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($message->message, 90) }}</td>
                            <td>
                                @if($message->processed_at)
                                <span class="badge bg-success-subtle text-success">Da xu ly</span>
                                @else
                                <span class="badge bg-warning-subtle text-warning">Chua xu ly</span>
                                @endif
                            </td>
                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.contacts.show', $message) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chua co lien he nao.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
            <div class="mt-3">
                {{ $messages->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection