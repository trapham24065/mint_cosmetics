<!--== Start Aside Search Form ==-->
<aside class="aside-search-box-wrapper offcanvas offcanvas-top" tabindex="-1" id="AsideOffcanvasSearch"
       aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="d-none" id="offcanvasTopLabel">Aside Search</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <div class="container pt--0 pb--0">
            <div class="search-box-form-wrap">
                <div class="search-note">
                    <p>Start typing and press Enter to search</p>
                </div>
                <form action="{{ route('shop') }}" method="get"
                      onsubmit="return !!document.getElementById('SearchInput').value.trim();">
                    <div class="aside-search-form position-relative">
                        <label for="SearchInput" class="visually-hidden">Search</label>
                        <input id="SearchInput" type="search" class="form-control"
                               name="search"
                               placeholder="Search entire store…"
                               value="{{ request('search') }}">
                        <button class="search-button" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div id="aside-search-results" class="container mt-3 flex-grow-1" style="overflow-y: auto;">
        </div>
    </div>
</aside>
<!--== End Aside Search Form ==-->

<style>
    #AsideOffcanvasSearch .offcanvas-body {
        max-height: 80vh;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('SearchInput');
        const resultsContainer = document.getElementById('aside-search-results');

        if (!searchInput) {
            return;
        }

        let debounceTimer;
        searchInput.addEventListener('keyup', function() {
            clearTimeout(debounceTimer);
            const query = this.value;

            if (query.length < 2) {
                resultsContainer.innerHTML = '';
                return;
            }

            debounceTimer = setTimeout(() => {
                resultsContainer.innerHTML = '<p class="text-center p-3">Searching...</p>';

                fetch(`/api/products/search?query=${query}`).then(response => response.json()).then(products => {
                    resultsContainer.innerHTML = '';

                    if (products.length > 0) {
                        products.forEach(product => {
                            const firstVariant = product.variants && product.variants.length > 0
                                ? product.variants[0]
                                : null;
                            const price = firstVariant
                                ? parseFloat(firstVariant.price).toLocaleString('vi-VN') + ' VNĐ'
                                : 'N/A';
                            const imageUrl = product.image
                                ? `/storage/${product.image}`
                                : `{{ asset('assets/storefront/images/shop/1.webp') }}`;
                            const productUrl = `/products/${product.slug}`;

                            const resultHtml = `
                                    <a href="${productUrl}" class="d-flex align-items-center gap-3 p-2 border-bottom text-decoration-none text-dark">
                                        <img src="${imageUrl}" alt="${product.name}" width="50" class="img-thumbnail">
                                        <div>
                                            <h6 class="mb-0">${product.name}</h6>
                                            <p class="mb-0 text-muted">${price}</p>
                                        </div>
                                    </a>
                                `;
                            resultsContainer.insertAdjacentHTML('beforeend', resultHtml);
                        });
                    } else {
                        resultsContainer.innerHTML = '<p class="text-center p-3">No products found.</p>';
                    }
                }).catch(error => {
                    console.error('Search error:', error);
                    resultsContainer.innerHTML = '<p class="text-center p-3 text-danger">An error occurred.</p>';
                });
            }, 300);
        });
    });
</script>

