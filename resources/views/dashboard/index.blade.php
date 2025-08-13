<x-app-layout>

    {{-- Script Tambahan --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    {{-- Judul Halaman --}}
    <x-slot name="title">Dashboard</x-slot>

    {{-- Statistik Pengguna --}}
    <section>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-cards.status-card :count="$totalUsers" title="Total Pengguna" subtitle="Total pengguna" color="red" />
            <x-cards.status-card :count="$adminUsers" title="Admin" subtitle="Pegawai admin" color="green" />
            <x-cards.status-card :count="$notaryUsers" title="Notulis" subtitle="Pegawai notulis" color="blue" />
        </div>
    </section>

    {{-- Statistik Notulen --}}
    <section class="mt-5 bg-white rounded-xl p-5">
        <canvas id="minutesChart" height="80"></canvas>
    </section>

    <script>
        const ctx = document.getElementById('minutesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ],
                datasets: [{
                    label: 'Total Notulen per Bulan',
                    data: @json($minutes),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</x-app-layout>
