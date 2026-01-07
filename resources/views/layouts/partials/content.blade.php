        <style>
            .chart-container {
                position: relative;
                height: 300px; /* Atur tinggi sesuai kebutuhan */
            }
        </style>
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-content">
            <div class="page-heading">
                <h3>Profile Statistics</h3>
            </div>
            <section class="row">
                <div class="col-12 col-lg-9">
                    <div class="row">
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div
                                            class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon purple mb-2">
                                                <i class="iconly-boldShow"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold mb-1"
                                                style="font-size: 0.8rem">Perlu Diproses</p>
                                            <h4 class="fw-bold mb-0 text-warning">
                                                {{ $stats['pending_orders'] }}
                                            </h4>
                                        </div>
                                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-box-seam text-warning fs-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div
                                            class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon blue mb-2">
                                                <i class="iconly-boldProfile"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold mb-1"
                                                style="font-size: 0.8rem">Stok Menipis</p>
                                            <h4 class="fw-bold mb-0 text-danger">
                                                {{ $stats['low_stock'] }}
                                            </h4>
                                        </div>
                                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-exclamation-triangle text-danger fs-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div
                                            class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon green mb-2">
                                                <i class="iconly-boldAdd-User"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold mb-1"
                                                style="font-size: 0.8rem">Total Produk</p>
                                            <h4 class="fw-bold mb-0 text-primary">
                                                {{ $stats['total_products'] }}
                                            </h4>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-tags text-primary fs-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div
                                            class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon red mb-2">
                                                <i class="iconly-boldBookmark"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-muted text-uppercase fw-semibold mb-1"
                                                style="font-size: 0.8rem">Total Produk</p>
                                            <h4 class="fw-bold mb-0 text-primary">
                                                {{ $stats['total_products'] }}
                                            </h4>
                                        </div>
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-tags text-primary fs-3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-xl-8">
                            {{-- 4. Top Selling Products --}}
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-white py-3">
                                    <h5 class="card-title mb-0">Produk Terlaris</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        @foreach ($topProducts as $product)
                                            <div class="col-6 col-md-2 text-center">
                                                <div class="card h-100 border-0 hover-shadow transition">
                                                    <img src="{{ $product->image_url }}"
                                                        class="card-img-top rounded mb-2"
                                                        style="max-height: 100px; object-fit: cover;">
                                                    <h6 class="card-title text-truncate" style="font-size: 0.9rem">
                                                        {{ $product->name }}</h6>
                                                    <small class="text-muted">{{ $product->sold }} terjual</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- 2. Revenue Chart --}}
                        <div class="col-12 col-xl-8">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="card-title mb-0">Grafik Penjualan (7 Hari)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="revenueChart" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        {{-- 3. Recent Orders --}}
                        <div class="col-lg-12 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="card-title mb-0">Pesanan Terbaru</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        @foreach ($recentOrders as $order)
                                            <div
                                                class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                                <div>
                                                    <div class="fw-bold text-primary">#{{ $order->order_number }}</div>
                                                    <small class="text-muted">{{ $order->user->name }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold">Rp
                                                        {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                                    <span
                                                        class="badge rounded-pill
                                        {{ $order->payment_status == 'paid' ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-footer bg-white text-center py-3">
                                    <a href="{{ route('admin.orders.index') }}" class="text-decoration-none fw-bold">
                                        Lihat Semua Pesanan &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </section>
        </div>


        {{-- Script Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');

            // Data dari Controller (Blade to JS)
            const labels = {!! json_encode($revenueChart->pluck('date')) !!};
            const data = {!! json_encode($revenueChart->pluck('total')) !!};

            new Chart(ctx, {
                type: 'line', // Jenis grafik: Line chart
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data,
                        borderColor: '#0d6efd', // Bootstrap Primary Color
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        tension: 0.3, // Membuat garis sedikit melengkung (smooth)
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Penting agar Chart menyesuaikan container
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    // Format Tooltip jadi Rupiah
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4]
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                        notation: "compact"
                                    }).format(value);
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>
