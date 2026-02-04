@extends('admin.layouts.app')

@section('content')
    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-8">
                <form method="POST" action="{{ route('admin.chatbot-replies.update', $reply) }}">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Edit Reply</h4>
                            <div>
                                <a href="{{ route('admin.chatbot-replies.index') }}" class="btn btn-outline-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('admin.management.chatbot.replies._form')
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Associated Keywords</h4></div>
                    <div class="card-body">
                        <p class="text-muted">Add or remove keywords that will trigger this reply.</p>

                        {{-- Form to add new keywords --}}
                        <form action="{{ route('admin.chatbot-replies.keywords.store', $reply) }}" method="POST"
                              class="d-flex gap-2 mb-3">
                            @csrf
                            <input type="text" name="keyword" class="form-control form-control-sm"
                                   placeholder="Add a new keyword..." required>
                            <button type="submit" class="btn btn-sm btn-primary">Add</button>
                        </form>

                        {{-- List of existing keywords --}}
                        <div>
                            @forelse($reply->keywords as $keyword)
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <span>{{ $keyword->keyword }}</span>
                                    <form action="{{ route('admin.chatbot-keywords.destroy', $keyword) }}" method="POST"
                                          onsubmit="return confirm('Delete this keyword?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-1 lh-1">&times;
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p>No keywords added yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
