function fetchVariants(query) {
    return fetch(`/api/product-variants?search=${query}`)
        .then(response => response.json())
        .catch(() => []);
}
