@extends('layouts.app')

@section('content')
<div id="pos-app" class="h-100">
    <div class="row g-4 h-100">
        <!-- Left side - Products -->
        <div class="col-lg-8 d-flex flex-column mb-3 h-100">
            <div class="card flex-grow-1 d-flex flex-column m-0 h-100">
                <div class="card-header">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input 
                                    type="text" 
                                    v-model="searchQuery" 
                                    class="form-control" 
                                    placeholder="Search products by name or code..."
                                >
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2">
                                <button 
                                    @click="filterByCategory(null)"
                                    :class="['btn', selectedCategory === null ? 'btn-primary' : 'btn-outline-primary']"
                                >
                                    <i class="fas fa-th-large me-2"></i>
                                    All
                                </button>
                                <button 
                                    v-for="category in categories" 
                                    :key="category.id"
                                    @click="filterByCategory(category)"
                                    :class="['btn', selectedCategory?.id === category.id ? 'btn-primary' : 'btn-outline-primary']"
                                >
                                    <i class="fas fa-tag me-2"></i>
                                    @{{ category.name }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 flex-grow-1 overflow-auto">
                    <div v-if="filteredProducts.length === 0" class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No products found</h5>
                    </div>
                    <div v-else class="product-grid">
                        <div v-for="product in filteredProducts" 
                             :key="product.id" 
                             class="product-card"
                             @click="addToCart(product)">
                            <img v-if="!product.image" src="{{asset('/no-image.png')}}" class="img-fluid" style="object-fit: cover;">
                            <img v-else :src="'/images/'+product.image" class="img-fluid" :alt="product.name">
                            <h6>@{{ product.name }}</h6>
                            <p class="mb-0">$@{{ product.price }}</p>
                            <small class="text-muted">Stock: @{{ product.stock }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Cart -->
        <div class="col-lg-4 d-flex flex-column h-100">
            <div class="card flex-grow-1 d-flex flex-column h-100">
                <!-- Cart Header -->
                <div class="card-header border-bottom">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="search-box">
                                <i class="fas fa-user"></i>
                                <input 
                                    type="text" 
                                    v-model="customerName" 
                                    class="form-control" 
                                    placeholder="Customer name..."
                                >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-danger" :class="{disabled: cart.length === 0}" @click="resetCart" style="min-width: max-content;height: 100%;">
                                <i class="fas fa-trash"></i>
                                Clear All
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cart Items - Scrollable Area -->
                <div class="flex-grow-1 d-flex flex-column" style="min-height: 0;">
                    <div class="overflow-auto h-100" style="height: 265px !important;">
                        <div v-if="cart.length === 0" class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Your cart is empty</h5>
                            <p class="text-muted">Add products by clicking on them</p>
                        </div>
                        <div v-else class="p-3">
                            <table class="table table-sm mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in cart" :key="item.id">
                                        <td>
                                            <div class="fw-semibold">@{{ item.name }}</div>
                                            <small class="text-muted">@{{ item.code }}</small>
                                        </td>
                                        <td style="width: 120px;">
                                            <div class="input-group input-group-sm">
                                                <button @click="decrementQuantity(item)" class="btn btn-outline-secondary">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" v-model="item.quantity" class="form-control text-center">
                                                <button @click="incrementQuantity(item)" class="btn btn-outline-secondary">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>$@{{ Number(item.price * item.quantity).toFixed(2) }}</td>
                                        <td>
                                            <button @click="removeFromCart(item)" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Cart Footer -->
                <div class="card-footer border-top">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="bg-light rounded p-3">
                                <div class="row mb-2">
                                    <div class="col-6">Subtotal:</div>
                                    <div class="col-6 text-end">$@{{ subtotal.toFixed(2) }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Tax (10%):</div>
                                    <div class="col-6 text-end">$@{{ tax.toFixed(2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6"><strong>Total:</strong></div>
                                    <div class="col-6 text-end"><strong>$@{{ total.toFixed(2) }}</strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <select v-model="paymentMethod" class="form-select">
                                <option value="cash">💵 Cash Payment</option>
                                <option value="card">💳 Card Payment</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button 
                                @click="processSale" 
                                class="btn btn-success w-100" 
                                :disabled="cart.length === 0"
                            >
                                <i class="fas fa-check-circle me-2"></i>
                                Complete Sale
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Regular Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Sale Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="invoice-content">
                        <!-- Company Info -->
                        <div class="text-center mb-4">
                            <h4>{{ auth()->user()->store->name }}</h4>
                            <p class="mb-1">{{ auth()->user()->store->address }}</p>
                            <p class="mb-1">Phone: (123) 456-7890</p>
                        </div>

                        <!-- Invoice Details -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <h6>Invoice to:</h6>
                                <p class="mb-1">@{{ completedSale ? completedSale.customer_name || 'Walk-in Customer' : 'Walk-in Customer' }}</p>
                                <p class="mb-1">Invoice #: @{{ currentSale?.id }}</p>
                                <p>Date: @{{ new Date().toLocaleDateString() }}</p>
                            </div>
                            <div class="col-6 text-end">
                                <h6>Payment Method:</h6>
                                <p>@{{ completedSale?.payment_method === 'cash' ? '💵 Cash Payment' : '💳 Card Payment' }}</p>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in completedSale?.items" :key="item.id">
                                    <td>@{{ item.name }}</td>
                                    <td class="text-center">@{{ item.quantity }}</td>
                                    <td class="text-end">$@{{ Number(item.price).toFixed(2) }}</td>
                                    <td class="text-end">$@{{ Number(item.price * item.quantity).toFixed(2) }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal:</td>
                                    <td class="text-end">$@{{ Number(completedSale?.subtotal || 0).toFixed(2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">Tax (10%):</td>
                                    <td class="text-end">$@{{ Number(completedSale?.tax || 0).toFixed(2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>$@{{ Number(completedSale?.total || 0).toFixed(2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <p class="mb-1">Thank you for supporting us!</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" @click="printInvoice">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Make the app take full height */
    html, body {
        height: 100%;
    }
    
    #app {
        min-height: 100%;
        display: flex;
        flex-direction: column;
    }

    main {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
    }

    /* Product grid styles */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        padding: 1rem;
    }

    .product-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .product-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 0.375rem;
        margin-bottom: 0.75rem;
    }

    .product-card h6 {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    /* Search box styles */
    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
    }

    .search-box input {
        padding-left: 2.5rem;
        height: 45px;
        border-radius: 0.5rem;
    }

    /* Cart table styles */
    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        padding: 0.75rem;
    }

    .table td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    /* Card styles */
    .card {
        border: none;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border-radius: 0.5rem;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-footer {
        background: white;
        border-top: 1px solid #e5e7eb;
    }

    /* Button styles */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }

    .btn-group .btn {
        border-radius: 0.5rem;
    }

    /* Form control styles */
    .form-control, .form-select {
        border-color: #e5e7eb;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Scrollbar styles */
    .overflow-auto::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .overflow-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .overflow-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Cart specific styles */
    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 1;
        background: white;
    }

    .table-light {
        --bs-table-bg: #f8f9fa;
    }

    .card-header.border-bottom {
        border-bottom: 1px solid #e5e7eb !important;
    }

    .card-footer.border-top {
        border-top: 1px solid #e5e7eb !important;
    }

    /* Ensure the cart content scrolls properly */
    .overflow-auto {
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 #f1f1f1;
    }

    .overflow-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .overflow-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@section('scripts')
<script>
const { createApp } = Vue

createApp({
    data() {
        return {
            products: [],
            cart: [],
            searchQuery: '',
            selectedCategory: null,
            categories: [],
            customerName: '',
            loyaltyPoints: 0,
            paymentMethod: 'cash',
            currentSale: null,
            invoiceModal: null,
            completedSale: null,
        }
    },
    computed: {
        filteredProducts() {
            let filtered = this.products
            
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase()
                filtered = filtered.filter(product => 
                    product.name.toLowerCase().includes(query) ||
                    product.code.toLowerCase().includes(query)
                )
            }
            
            if (this.selectedCategory) {
                filtered = filtered.filter(product => 
                    product.category_id === this.selectedCategory.id
                )
            }
            
            return filtered
        },
        subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)
        },
        tax() {
            return this.subtotal * 0.1
        },
        total() {
            return this.subtotal + this.tax
        }
    },
    methods: {
        loadCategories() {
            axios.get('/api/categories')
                .then(response => {
                    if(response.status == 200) {
                        this.categories = response.data
                    }
                })
                .catch(error => {
                    console.error('Error loading categories:', error)
                })
        },
        filterByCategory(category) {
            this.selectedCategory = category
        },
        loadProducts() {
            axios.get("{{route('api.products')}}")
            .then(response => {
                console.log(response);
                if(response.status == 200){
                    console.log(response.data);

                    this.products = response.data
                }
            })
            .catch(error => {
                console.error('Error loading products:', error)
            })
        },
        addToCart(product) {
            if (product.stock === 0) {
                this.showError('This product is out of stock')
                return
            }

            const existingItem = this.cart.find(item => item.id === product.id)
            
            if (existingItem) {
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity++
                } else {
                    this.showError('Not enough stock available')
                }
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    code: product.code,
                    price: product.price,
                    quantity: 1
                })
            }
        },
        removeFromCart(item) {
            const index = this.cart.indexOf(item)
            if (index > -1) {
                this.cart.splice(index, 1)
            }
        },
        incrementQuantity(item) {
            const product = this.products.find(p => p.id === item.id)
            if (item.quantity < product.stock) {
                item.quantity++
            } else {
                this.showError('Not enough stock available')
            }
        },
        decrementQuantity(item) {
            if (item.quantity > 1) {
                item.quantity--
            } else {
                this.removeFromCart(item)
            }
        },
        showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            })
        },
        processSale() {
            const saleData = {
                customer_name: this.customerName,
                payment_method: this.paymentMethod,
                total_amount: this.total,
                tax_amount: this.tax,
                net_amount: this.subtotal,
                items: this.cart.map(item => ({
                    id: item.id,
                    quantity: item.quantity,
                    price: item.price
                }))
            }

            axios.post('{{ route('api.sales.store') }}', saleData)
                .then(response => {
                    if (response.data.sale) {
                        this.currentSale = response.data.sale
                        this.completedSale = {
                            items: [...this.cart],
                            subtotal: this.subtotal,
                            tax: this.tax,
                            total: this.total,
                            customer_name: this.customerName,
                            payment_method: this.paymentMethod
                        }
                        this.showInvoice()
                    }
                })
                .catch(error => {
                    console.error('Error processing sale:', error)
                    this.showError(error.response?.data?.message || 'Error processing sale')
                })
        },
        showInvoice() {
            const modalElement = document.getElementById('invoiceModal')
            modalElement.addEventListener('hidden.bs.modal', () => {
                this.resetCart()
            }, { once: true })
            
            this.invoiceModal.show()
        },
        printInvoice() {
            // Create the invoice HTML
            const invoiceHtml = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Invoice #${this.currentSale.id}</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            padding: 20px;
                            background: white;
                        }
                        .invoice-box {
                            max-width: 800px;
                            margin: auto;
                            padding: 30px;
                            font-size: 14px;
                            line-height: 24px;
                        }
                        .table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        .table th, .table td {
                            padding: 12px;
                            border: 1px solid #dee2e6;
                        }
                        .text-end { text-align: right; }
                        .text-center { text-align: center; }
                        @media print {
                            @page { margin: 0.5cm; }
                            body { padding: 0; }
                        }
                    </style>
                </head>
                <body onload="window.print()">
                    <div class="invoice-box">
                        <!-- Company Info -->
                        <div class="text-center mb-4">
                            <h4>{{ auth()->user()->store->name }}</h4>
                            <p class="mb-1">{{ auth()->user()->store->address }}</p>
                            <p class="mb-1">Phone: (123) 456-7890</p>
                        </div>

                        <!-- Invoice Details -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <h6>Invoice to:</h6>
                                <p class="mb-1">${this.completedSale.customer_name || 'Walk-in Customer'}</p>
                                <p class="mb-1">Invoice #: ${this.currentSale.id}</p>
                                <p>Date: ${new Date().toLocaleDateString()}</p>
                            </div>
                            <div class="col-6 text-end">
                                <h6>Payment Method:</h6>
                                <p>${this.completedSale.payment_method === 'cash' ? '💵 Cash Payment' : '💳 Card Payment'}</p>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${this.completedSale.items.map(item => `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td class="text-center">${item.quantity}</td>
                                        <td class="text-end">$${Number(item.price).toFixed(2)}</td>
                                        <td class="text-end">$${Number(item.price * item.quantity).toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal:</td>
                                    <td class="text-end">$${Number(this.completedSale.subtotal).toFixed(2)}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">Tax (10%):</td>
                                    <td class="text-end">$${Number(this.completedSale.tax).toFixed(2)}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong>$${Number(this.completedSale.total).toFixed(2)}</strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <p class="mb-1">Thank you for supporting us!</p>
                        </div>
                    </div>
                </body>
                </html>
            `;

            // Calculate center position
            const width = 800;
            const height = 600;
            const left = (window.screen.width - width) / 2;
            const top = (window.screen.height - height) / 2;

            // Open new window with centered position
            const printWindow = window.open('', 'PrintWindow', 
                `width=${width},height=${height},top=${top},left=${left},screenX=${left},screenY=${top}`
            );
            printWindow.document.write(invoiceHtml);
            printWindow.document.close();
        },
        resetCart() {
            this.cart = []
            this.customerName = ''
            this.paymentMethod = 'cash'
            this.completedSale = null
            this.loadProducts()
        }
    },
    mounted() {
        this.loadProducts()
        this.loadCategories()
        this.invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'))
    }
}).mount('#pos-app')
</script>
@endsection

