@extends('admin.layouts.app')

@section('content')
    <style>
        .admin-chat-bubble {
            max-width: 70%;
        }

        .admin-chat-bubble.has-image {
            padding: 6px !important;
            background: #fff !important;
            border: 1px solid #e9ecef !important;
            color: #212529 !important;
        }

        .admin-chat-image {
            display: block;
            max-width: 220px;
            max-height: 220px;
            border-radius: 8px;
            cursor: zoom-in;
            object-fit: cover;
            transition: transform .2s ease;
        }

        .admin-chat-image:hover {
            transform: scale(1.02);
        }

        .admin-chat-text {
            word-wrap: break-word;
        }

        .admin-attachment-preview {
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
            padding: 6px;
            background: #fff;
            border: 1px dashed #ff6565;
            border-radius: 10px;
        }

        .admin-attachment-preview img {
            display: block;
            max-width: 110px;
            max-height: 110px;
            border-radius: 6px;
            object-fit: cover;
        }

        .admin-attachment-remove {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: none;
            background: #ff6565;
            color: #fff;
            font-size: 11px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(255, 101, 101, .4);
        }

        .admin-attachment-remove:hover {
            transform: scale(1.1);
        }

        .admin-chat-lightbox {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .85);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .admin-chat-lightbox img {
            max-width: 90vw;
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, .5);
        }

        .admin-chat-lightbox-close {
            position: absolute;
            top: 20px;
            right: 24px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-chat-lightbox-close:hover {
            background: rgba(255, 255, 255, .25);
        }
    </style>

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
                    <div id="chat-box" class="flex-grow-1 p-3" style="overflow-y: auto;"
                         data-last-date="{{ $lastMessageDate }}"
                         data-last-message-id="{{ data_get(collect($messages)->last(), 'id', 0) }}"
                         data-conversation-id="{{ isset($currentConversation) ? $currentConversation->id : 0 }}">
                        @foreach($messages as $message)
                            @php

                                $senderId = data_get($message, 'participation.messageable_id');
                                $senderType = data_get($message, 'participation.messageable_type');
                                $isSystemMessage = data_get($message, 'data.system_message') === 'admin_busy';

                                // So sánh với user hiện tại, nhưng tin nhắn hệ thống luôn hiển thị phía admin
                                $isMe = $isSystemMessage || ($senderId === auth()->id() && $senderType === get_class(auth()->user()));

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
                                @php
                                    $previousMessageDate = $messageDateKey;
                                @endphp
                            @endif
                            @php
                                $messageId = data_get($message, 'id');
                                $attachments = $attachmentsByMessage->get($messageId) ?? collect([]);
                                $imageAttachments = $attachments->filter(fn($a) => str_starts_with((string) $a->mime_type, 'image/'));
                                $hasImage = $imageAttachments->count() > 0;
                            @endphp
                            <div
                                class="chat-message d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}"
                                data-id="{{ $messageId }}">
                                <div
                                    class="admin-chat-bubble p-3 rounded {{ $isMe ? 'bg-primary text-white' : ' border' }} {{ $hasImage ? 'has-image' : '' }}">
                                    @if($hasImage)
                                        <div class="d-flex flex-wrap gap-2 mb-1">
                                            @foreach($imageAttachments as $att)
                                                <img src="{{ $att->url }}"
                                                     alt="{{ $att->original_name ?? 'image' }}"
                                                     class="admin-chat-image"
                                                     data-full-url="{{ $att->url }}">
                                            @endforeach
                                        </div>
                                    @endif
                                    @if(trim((string) $body) !== '')
                                        <div class="admin-chat-text {{ $hasImage ? 'mt-2' : '' }}">{{ $body }}</div>
                                    @endif
                                    <div class="small {{ $isMe ? 'text-white-50' : 'text-muted' }} mt-1 text-end"
                                         style="font-size: 0.7rem;">
                                        {{ $timeDisplay }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-3 border-top">
                        <div id="admin-attachment-preview" class="admin-attachment-preview" style="display:none;">
                            <div id="admin-attachment-gallery" class="d-flex flex-wrap gap-2"></div>
                            <div class="small text-muted mt-1">
                                <span id="admin-attachment-count">0</span> / 5 ảnh
                            </div>
                        </div>
                        <form id="admin-chat-form" class="d-flex gap-2 align-items-center">
                            <button type="button" id="admin-attach-btn" class="btn btn-outline-secondary"
                                    title="Đính kèm ảnh">
                                <i class="bi bi-paperclip"></i>
                            </button>
                            <input type="file" id="admin-attachment-input"
                                   accept="image/jpeg,image/jpg,image/png,image/webp" multiple hidden>
                            <input type="text" id="message-input" class="form-control"
                                   placeholder="Nhập câu trả lời của bạn..."
                                   autocomplete="off">
                            <button class="btn btn-primary" type="submit" id="send-btn"><i class="bi bi-send"></i>
                            </button>
                        </form>
                    </div>

                    <div id="admin-chat-lightbox" class="admin-chat-lightbox" style="display:none;" role="dialog"
                         aria-modal="true">
                        <button type="button" class="admin-chat-lightbox-close" id="admin-chat-lightbox-close"
                                aria-label="Đóng">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <img id="admin-chat-lightbox-img" src="" alt="">
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

                    const MAX_BYTES = 5 * 1024 * 1024;
                    const MAX_ATTACHMENTS = 5;
                    const ALLOWED = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    let pendingFiles = [];

                    const $attachBtn = $('#admin-attach-btn');
                    const $fileInput = $('#admin-attachment-input');
                    const $preview = $('#admin-attachment-preview');
                    const $gallery = $('#admin-attachment-gallery');
                    const $count = $('#admin-attachment-count');
                    const $lightbox = $('#admin-chat-lightbox');
                    const $lightboxImg = $('#admin-chat-lightbox-img');

                    function escapeHtml(str) {
                        return String(str || '').replace(/[&<>"']/g, function(c) {
                            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
                        });
                    }

                    function renderPendingFiles() {
                        $gallery.empty();
                        pendingFiles.forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const $thumb = $(
                                    `<div class="admin-attachment-thumb position-relative" data-index="${index}" style="width: 92px; height: 92px; border-radius: 10px; overflow: hidden; background: #f8f9fa; border: 1px solid #e9ecef;">
                                        <img src="${escapeHtml(e.target.result)}" alt="${escapeHtml(file.name)}" style="width: 100%; height: 100%; object-fit: cover; cursor: zoom-in;">
                                        <button type="button" class="admin-attachment-remove" aria-label="Xoá ảnh" style="top: 4px; right: 4px;">&times;</button>
                                    </div>`
                                );
                                $thumb.find('img').on('click', function() { openLightbox(e.target.result); });
                                $thumb.find('button').on('click', function(ev) {
                                    ev.preventDefault();
                                    ev.stopPropagation();
                                    removePendingFile(index);
                                });
                                $gallery.append($thumb);
                            };
                            reader.readAsDataURL(file);
                        });

                        $count.text(pendingFiles.length);
                        $preview.toggle(pendingFiles.length > 0);
                    }

                    function addPendingFiles(files) {
                        if (!files || !files.length) return;

                        const remaining = MAX_ATTACHMENTS - pendingFiles.length;
                        if (remaining <= 0) {
                            alert(`Bạn chỉ có thể chọn tối đa ${MAX_ATTACHMENTS} ảnh.`);
                            $fileInput.val('');
                            return;
                        }

                        const incoming = Array.from(files).slice(0, remaining);
                        let added = 0;

                        incoming.forEach(file => {
                            if (ALLOWED.indexOf(file.type) === -1) {
                                alert(`File "${file.name}" không phải là hình ảnh hợp lệ.`);
                                return;
                            }
                            if (file.size > MAX_BYTES) {
                                alert(`File "${file.name}" vượt quá 5MB.`);
                                return;
                            }
                            pendingFiles.push(file);
                            added += 1;
                        });

                        if (added > 0) {
                            renderPendingFiles();
                        }

                        $fileInput.val('');
                    }

                    function removePendingFile(index) {
                        pendingFiles.splice(index, 1);
                        renderPendingFiles();
                    }

                    function clearPendingFiles() {
                        pendingFiles = [];
                        $fileInput.val('');
                        $gallery.empty();
                        $count.text('0');
                        $preview.hide();
                    }

                    $attachBtn.on('click', function() { $fileInput.trigger('click'); });
                    $fileInput.on('change', function(e) { addPendingFiles(e.target.files); });

                    function openLightbox(src) { $lightboxImg.attr('src', src); $lightbox.css('display', 'flex'); }
                    function closeLightbox() { $lightbox.hide(); $lightboxImg.attr('src', ''); }

                    $('#admin-chat-lightbox-close').on('click', closeLightbox);
                    $lightbox.on('click', function(e) { if (e.target === this) closeLightbox(); });
                    $(document).on('keydown', function(e) { if (e.key === 'Escape') closeLightbox(); });

                    chatBox.on('click', '.admin-chat-image', function() {
                        const url = $(this).data('full-url') || $(this).attr('src');
                        if (url) openLightbox(url);
                    });

                    function appendMessage(text, isMe, time, id, createdAtRaw = null, createdAtDate = null, createdAtLabel = '', attachment = null) {
                        const dateValue = createdAtRaw || createdAtDate;
                        if (dateValue) {
                            const dateKey = createdAtDate || dateValue.slice(0, 10);
                            appendDateSeparator(dateKey, createdAtLabel || dateValue);
                        }

                        const justify = isMe ? 'justify-content-end' : 'justify-content-start';
                        const attachments = Array.isArray(attachment) ? attachment.filter(Boolean) : (attachment ? [attachment] : []);
                        const hasImage = attachments.some(item => item && item.is_image && item.url);
                        const hasText = !!(text && String(text).trim().length);
                        const bgClass = hasImage ? 'has-image' : (isMe ? 'bg-primary text-white' : ' border');
                        const timeClass = (isMe && !hasImage) ? 'text-white-50' : 'text-muted';

                        let inner = '';
                        if (hasImage) {
                            inner += `<div class="d-flex flex-wrap gap-2 mb-1">`;
                            attachments.forEach((item) => {
                                if (item && item.is_image && item.url) {
                                    inner += `<img src="${escapeHtml(item.url)}" alt="${escapeHtml(item.original_name || 'image')}" class="admin-chat-image" data-full-url="${escapeHtml(item.url)}">`;
                                }
                            });
                            inner += `</div>`;
                        }
                        if (hasText) {
                            inner += `<div class="admin-chat-text ${hasImage ? 'mt-2' : ''}">${escapeHtml(text)}</div>`;
                        }
                        inner += `<div class="small ${timeClass} mt-1 text-end" style="font-size: 0.7rem;">${escapeHtml(time)}</div>`;

                        const html = `
                        <div class="chat-message d-flex mb-3 ${justify}" data-id="${id}">
                            <div class="admin-chat-bubble p-3 rounded ${bgClass}">${inner}</div>
                        </div>`;
                        chatBox.append(html);
                        chatBox.scrollTop(chatBox[0].scrollHeight);
                        if (id > lastMessageId) lastMessageId = id;
                    }

                    $('#admin-chat-form').on('submit', function(e) {
                        e.preventDefault();
                        const input = $('#message-input');
                        const message = input.val().trim();
                        if (!message && pendingFiles.length === 0) return;
                        $('#send-btn').prop('disabled', true);

                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('message', message);
                        pendingFiles.forEach(function(file) {
                            formData.append('attachments[]', file);
                        });

                        $.ajax({
                            url: `{{ url('admin/chat') }}/${conversationId}/reply`,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                if (response.success) {
                                    input.val('');
                                    clearPendingFiles();
                                    appendMessage(response.message.body, true, response.message.created_at, response.message.id, response.message.created_at_raw, response.message.created_at_date, response.message.created_at_label, response.message.attachments || response.message.attachment || null);
                                } else if (response.message) {
                                    alert(response.message);
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr);
                                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gửi tin nhắn không thành công.';
                                alert(msg);
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
                                    if ($(`.chat-message[data-id="${msg.id}"]`).length === 0) {
                                        appendMessage(msg.body, msg.is_me, msg.created_at, msg.id, msg.created_at_raw, msg.created_at_date, msg.created_at_label, msg.attachments || msg.attachment || null);
                                    }
                                    // Update lastMessageId to prevent duplicate polls
                                    if (msg.id > lastMessageId) {
                                        lastMessageId = msg.id;
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
