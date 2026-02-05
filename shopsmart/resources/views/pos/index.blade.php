@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Point of Sale</h1>
            <p class="text-gray-600 mt-1">Process sales and generate receipts</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Selection -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Search -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <input type="text" id="productSearch" placeholder="Search products by name, SKU, or barcode..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>

            <!-- Products Grid -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="productsGrid">
                    <!-- Products will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Cart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Cart</h2>
            
            <div class="space-y-4 mb-4">
                <div id="cartItems" class="space-y-2 max-h-96 overflow-y-auto">
                    <p class="text-sm text-gray-500 text-center py-4">Cart is empty</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold" id="subtotal">$0.00</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tax:</span>
                    <span class="font-semibold" id="tax">$0.00</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                    <span>Total:</span>
                    <span id="total">$0.00</span>
                </div>

                <div class="space-y-2 mt-4">
                    <select id="paymentMethod" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                    
                    <button id="completeSale" class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold">
                        Complete Sale
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let cart = [];
    const taxRate = 0.10; // 10% tax

    // Load products
    fetch('{{ url("/api/products") }}')
        .then(res => res.json())
        .then(products => {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = products.map(product => `
                <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md cursor-pointer" onclick="addToCart(${JSON.stringify(product).replace(/"/g, '&quot;')})">
                    <div class="text-sm font-medium text-gray-900">${product.name}</div>
                    <div class="text-xs text-gray-500 mt-1">Stock: ${product.stock_quantity}</div>
                    <div class="text-sm font-semibold text-purple-600 mt-2">$${parseFloat(product.selling_price).toFixed(2)}</div>
                </div>
            `).join('');
        });

    function addToCart(product) {
        const existing = cart.find(item => item.id === product.id);
        if (existing) {
            existing.quantity++;
        } else {
            cart.push({...product, quantity: 1});
        }
        updateCart();
    }

    function updateCart() {
        const cartItems = document.getElementById('cartItems');
        const subtotal = cart.reduce((sum, item) => sum + (item.selling_price * item.quantity), 0);
        const tax = subtotal * taxRate;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;

        cartItems.innerHTML = cart.length ? cart.map((item, index) => `
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                <div class="flex-1">
                    <div class="text-sm font-medium">${item.name}</div>
                    <div class="text-xs text-gray-500">$${parseFloat(item.selling_price).toFixed(2)} x ${item.quantity}</div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateQuantity(${index}, -1)" class="px-2 py-1 bg-gray-200 rounded">-</button>
                    <span class="text-sm font-medium">${item.quantity}</span>
                    <button onclick="updateQuantity(${index}, 1)" class="px-2 py-1 bg-gray-200 rounded">+</button>
                    <button onclick="removeFromCart(${index})" class="ml-2 text-red-600">×</button>
                </div>
            </div>
        `).join('') : '<p class="text-sm text-gray-500 text-center py-4">Cart is empty</p>';
    }

    function updateQuantity(index, change) {
        cart[index].quantity = Math.max(1, cart[index].quantity + change);
        updateCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCart();
    }

    document.getElementById('completeSale').addEventListener('click', function() {
        if (cart.length === 0) {
            alert('Cart is empty');
            return;
        }

        const paymentMethod = document.getElementById('paymentMethod').value;
        
        fetch('/pos/complete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                items: cart,
                payment_method: paymentMethod
            })
        })
        .then(res => res.json())
        .then(data => {
            alert('Sale completed successfully!');
            cart = [];
            updateCart();
        })
        .catch(err => {
            alert('Error completing sale');
            console.error(err);
        });
    });
</script>
@endsection
@endsection

