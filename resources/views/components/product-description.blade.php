@php
    $id = 'product-desc-' . uniqid('', true);
@endphp

<div class="product-description-wrapper" id="{{ $id }}">
    <div class="product-description-content collapsed">
        {!! $slot !!}
    </div>

    <div class="description-fade"></div>

    <div class="description-btn-wrapper">
        <button class="description-toggle-btn">
            Xem thêm
        </button>
    </div>
</div>
<style>
    .product-description-wrapper {
        position: relative;
    }

    .product-description-content {
        overflow: hidden;
        transition: max-height 0.4s ease;
    }

    .product-description-content.collapsed {
        max-height: 220px;
    }

    .product-description-content img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }

    /* fade effect */
    .description-fade {
        position: absolute;
        bottom: 60px;
        left: 0;
        width: 100%;
        height: 80px;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
    }

    /* button wrapper */
    .description-btn-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 15px;
    }

    /* button style */
    .description-toggle-btn {
        background: #ff6565;
        border: 1px solid #ff6565;
        border-radius: 30px;
        padding: 8px 22px;
        font-size: 14px;
        font-weight: 500;
        color: white;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    /* hover effect */
    .description-toggle-btn:hover {
        background: #364958;
        border-color: #364958;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const wrapper = document.getElementById("{{ $id }}");
        if (!wrapper) {
            return;
        }

        const content = wrapper.querySelector('.product-description-content');
        const button = wrapper.querySelector('.description-toggle-btn');
        const fade = wrapper.querySelector('.description-fade');

        const collapsedHeight = 220;

        // tự detect chiều cao
        if (content.scrollHeight <= collapsedHeight) {
            button.style.display = 'none';
            fade.style.display = 'none';
        }

        button.addEventListener('click', function() {

            if (content.classList.contains('collapsed')) {
                content.classList.remove('collapsed');
                content.style.maxHeight = content.scrollHeight + 'px';

                button.innerText = 'Thu gọn';
                fade.style.display = 'none';

            } else {

                content.classList.add('collapsed');
                content.style.maxHeight = collapsedHeight + 'px';

                button.innerText = 'Xem thêm';
                fade.style.display = 'block';
            }

        });

    });
</script>
