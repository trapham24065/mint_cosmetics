@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-0">
            {{-- Sidebar: Danh sách hội thoại --}}
            <div class="col-md-4 border-end bg-white" style="height: calc(100vh - 100px); overflow-y: auto;">
                <div class="p-3 border-bottom bg-light">
                    <h5 class="mb-0">Conversations</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($conversations as $conv)
                        @php
                            $participants = $conv->participants ?? collect([]);
                            $otherParticipant = $participants->first(function($p) {
                                return $p->messageable_id !== auth()->id() || $p->messageable_type !== get_class(auth()->user());
                            });

                            $name = 'Unknown';
                            if ($otherParticipant && $otherParticipant->messageable) {
                                $name = $otherParticipant->messageable->name ??
                                        ($otherParticipant->messageable->full_name ?? 'Guest');
                            } elseif ($otherParticipant) {
                                $name = 'Deleted User';
                            }

                            $isActive = isset($currentConversation) && $currentConversation->id === $conv->id;
                        @endphp
                        <a href="{{ route('admin.chat.index', ['conversation_id' => $conv->id]) }}"
                           class="list-group-item list-group-item-action {{ $isActive ? 'active' : '' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $name }}</h6>
                                <small>{{ $conv->updated_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-truncate d-block text-muted">Click to chat</small>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted">No conversations yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Main: Khung Chat --}}
            <div class="col-md-8 d-flex flex-column" style="height: calc(100vh - 100px);">
                @if(isset($currentConversation))
                    <div class="p-3 border-bottom">
                        <h6 class="mb-0">Chatting with Customer</h6>
                    </div>

                    <div id="chat-box" class="flex-grow-1 p-3" style="overflow-y: auto; ">
                        @foreach($messages as $message)
                            @php

                                $senderId = data_get($message, 'participation.messageable_id');
                                $senderType = data_get($message, 'participation.messageable_type');

                                // So sánh với user hiện tại
                                $isMe = $senderId === auth()->id() && $senderType === get_class(auth()->user());

                                $body = data_get($message, 'body');
                                $createdAt = data_get($message, 'created_at');

                                $timeDisplay = $createdAt instanceof \Carbon\Carbon ? $createdAt->format('H:i') : \Carbon\Carbon::parse($createdAt)->format('H:i');
                            @endphp
                            <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}"
                                 data-id="{{ data_get($message, 'id') }}">
                                <div class="p-3 rounded {{ $isMe ? 'bg-primary text-white' : ' border' }}"
                                >
                                    <div>{{ $body }}</div>
                                    <div class="small {{ $isMe ? 'text-white-50' : 'text-muted' }} mt-1 text-end"
                                         style="font-size: 0.7rem;">
                                        {{ $timeDisplay }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-3 border-top">
                        <form id="admin-chat-form" class="d-flex gap-2">
                            <input type="text" id="message-input" class="form-control" placeholder="Type your reply..."
                                   autocomplete="off" required>
                            <button class="btn btn-primary" type="submit" id="send-btn"><i class="bi bi-send"></i> Send
                            </button>
                        </form>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        <h5>Select a conversation to start chatting</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
<!-- @formatter:off -->
@push('scripts')
    <script>
        (function($) {
            $(document).ready(function() {
                const chatBox = $('#chat-box');

                if (chatBox.length > 0) {
                    chatBox.scrollTop(chatBox[0].scrollHeight);

                    // Lấy ID tin nhắn cuối cùng (An toàn hơn)
                    let lastMessageId = {{ data_get(collect($messages)->last(), 'id', 0) }};
                    const conversationId = {{ isset($currentConversation) ? $currentConversation->id : 0 }};

                    function appendMessage(text, isMe, time, id) {
                        const justify = isMe ? 'justify-content-end' : 'justify-content-start';
                        const bgClass = isMe ? 'bg-primary text-white' : ' border';
                        const timeClass = isMe ? 'text-white-50' : 'text-muted';

                        const html = `
                        <div class="d-flex mb-3 ${justify}" data-id="${id}">
                            <div class="p-3 rounded ${bgClass}" style="max-width: 70%;">
                                <div>${text}</div>
                                <div class="small ${timeClass} mt-1 text-end" style="font-size: 0.7rem;">${time}</div>
                            </div>
                        </div>`;
                        chatBox.append(html);
                        chatBox.scrollTop(chatBox[0].scrollHeight);
                        if (id > lastMessageId) lastMessageId = id;
                    }

                    $('#admin-chat-form').on('submit', function(e) {
                        e.preventDefault();
                        const input = $('#message-input');
                        const message = input.val().trim();
                        if (!message) return;
                        $('#send-btn').prop('disabled', true);

                        $.ajax({
                            url: `{{ url('admin/chat') }}/${conversationId}/reply`,
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}', message: message },
                            success: function(response) {
                                if (response.success) {
                                    input.val('');
                                    appendMessage(response.message.body, true, response.message.created_at, response.message.id);
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr);
                                alert('Failed to send message.');
                            },
                            complete: function() {
                                $('#send-btn').prop('disabled', false);
                                input.focus();
                            }
                        });
                    });

                    setInterval(function() {
                        if (conversationId === 0) return;
                        $.get(`{{ url('admin/chat') }}/${conversationId}/fetch`, { last_id: lastMessageId }, function(response) {
                            if (response.messages && response.messages.length > 0) {
                                response.messages.forEach(msg => {
                                    if ($(`.d-flex[data-id="${msg.id}"]`).length === 0) {
                                        appendMessage(msg.body, msg.is_me, msg.created_at, msg.id);
                                    }
                                });
                            }
                        });
                    }, 3000);
                }
            });
        })(jQuery);
    </script>
@endpush
<!-- @formatter:on -->
