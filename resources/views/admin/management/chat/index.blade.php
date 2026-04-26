@extends('admin.layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        {{-- Sidebar: Danh sách hội thoại --}}
        <div class="col-md-4 border-end " style="height: calc(100vh - 100px); overflow-y: auto;">
            <div class="p-3 border-bottom bg-light">
                <h5 class="mb-0">Cuộc trò chuyện</h5>
            </div>
            <div class="list-group list-group-flush">
                @forelse($conversations as $conv)
                @php
                $participants = $conv->participants ?? collect([]);
                $otherParticipant = $participants->first(function($p) {
                return $p->messageable_id !== auth()->id() || $p->messageable_type !== get_class(auth()->user());
                });

                $name = 'Không rõ';
                if ($otherParticipant && $otherParticipant->messageable) {
                $name = $otherParticipant->messageable->name ??
                ($otherParticipant->messageable->full_name ?? 'Guest');
                } elseif ($otherParticipant) {
                $name = 'Người dùng đã bị xóa';
                }

                $isActive = isset($currentConversation) && $currentConversation->id === $conv->id;
                @endphp
                <a href="{{ route('admin.chat.index', ['conversation_id' => $conv->id]) }}"
                    class="list-group-item list-group-item-action {{ $isActive ? 'active' : '' }}">
                    <div class="d-flex w-100 justify-content-between">
                        <small class="mb-1">{{ $name }}</small>
                        <small>{{ $conv->updated_at->diffForHumans() }}</small>
                    </div>
                    <small class="text-truncate d-block">Nhấp vào để trò chuyện</small>
                </a>
                @empty
                <div class="p-4 text-center text-muted">Chưa có cuộc trò chuyện nào.</div>
                @endforelse
            </div>
        </div>

        {{-- Main: Khung Chat --}}
        <div class="col-md-8 d-flex flex-column" style="height: calc(100vh - 100px);">
            @if(isset($currentConversation))
            <div class="p-3 border-bottom">
                <h6 class="mb-0">Trò chuyện với khách hàng</h6>
            </div>

            @php
            $lastMessageDate = data_get(collect($messages)->last(), 'created_at');
            $lastMessageDate = $lastMessageDate instanceof \Carbon\Carbon
            ? $lastMessageDate->format('Y-m-d')
            : ($lastMessageDate ? \Carbon\Carbon::parse($lastMessageDate)->format('Y-m-d') : '');
            $previousMessageDate = null;
            @endphp
            <div id="chat-box" class="flex-grow-1 p-3" style="overflow-y: auto;" data-last-date="{{ $lastMessageDate }}" data-last-message-id="{{ data_get(collect($messages)->last(), 'id', 0) }}" data-conversation-id="{{ isset($currentConversation) ? $currentConversation->id : 0 }}">
                @foreach($messages as $message)
                @php

                $senderId = data_get($message, 'participation.messageable_id');
                $senderType = data_get($message, 'participation.messageable_type');

                // So sánh với user hiện tại
                $isMe = $senderId === auth()->id() && $senderType === get_class(auth()->user());

                $body = data_get($message, 'body');
                $createdAt = data_get($message, 'created_at');
                $messageDateKey = $createdAt instanceof \Carbon\Carbon ? $createdAt->format('Y-m-d') : \Carbon\Carbon::parse($createdAt)->format('Y-m-d');
                $messageDateLabel = $createdAt instanceof \Carbon\Carbon
                ? ($createdAt->isToday() ? 'Hôm nay' : ($createdAt->isYesterday() ? 'Hôm qua' : $createdAt->format('d/m/Y')))
                : \Carbon\Carbon::parse($createdAt)->format('d/m/Y');

                $timeDisplay = $createdAt instanceof \Carbon\Carbon ? $createdAt->format('H:i') : \Carbon\Carbon::parse($createdAt)->format('H:i');
                @endphp
                @if($previousMessageDate !== $messageDateKey)
                <div class="text-center my-3">
                    <span class="badge rounded-pill bg-light text-muted border px-3 py-2" style="font-size: 0.8rem;">
                        {{ $messageDateLabel }}
                    </span>
                </div>
                @php($previousMessageDate = $messageDateKey)
                @endif
                <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}"
                    data-id="{{ data_get($message, 'id') }}">
                    <div class="p-3 rounded {{ $isMe ? 'bg-primary text-white' : ' border' }}">
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
                    <input type="text" id="message-input" class="form-control"
                        placeholder="Nhập câu trả lời của bạn..."
                        autocomplete="off" required>
                    <button class="btn btn-primary" type="submit" id="send-btn"><i class="bi bi-send"></i> Gửi
                    </button>
                </form>
            </div>
            @else
            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                <h5>Chọn một cuộc trò chuyện để bắt đầu.</h5>
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
                    let lastMessageId = parseInt(chatBox.attr('data-last-message-id') || '0', 10);
                    const conversationId = parseInt(chatBox.attr('data-conversation-id') || '0', 10);
                    let lastRenderedDateKey = chatBox.attr('data-last-date') || '';

                    function appendDateSeparator(dateKey, label) {
                        if (!dateKey || dateKey === lastRenderedDateKey) {
                            return;
                        }

                        const separator = `
                            <div class="text-center my-3" data-date-separator="${dateKey}">
                                <span class="badge rounded-pill bg-light text-muted border px-3 py-2" style="font-size: 0.8rem;">
                                    ${label}
                                </span>
                            </div>`;

                        chatBox.append(separator);
                        lastRenderedDateKey = dateKey;
                    }

                    function appendMessage(text, isMe, time, id, createdAtRaw = null, createdAtDate = null, createdAtLabel = '') {
                        const dateValue = createdAtRaw || createdAtDate;
                        if (dateValue) {
                            const dateKey = createdAtDate || dateValue.slice(0, 10);
                            appendDateSeparator(dateKey, createdAtLabel || dateValue);
                        }

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
                                    appendMessage(response.message.body, true, response.message.created_at, response.message.id, response.message.created_at_raw, response.message.created_at_date, response.message.created_at_label);
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr);
                                alert('Gửi tin nhắn không thành công.');
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
                                        appendMessage(msg.body, msg.is_me, msg.created_at, msg.id, msg.created_at_raw, msg.created_at_date, msg.created_at_label);
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