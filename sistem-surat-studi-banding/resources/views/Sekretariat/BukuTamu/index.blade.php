<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Buku Tamu QR Code
            </h2>
            <a href="{{ route('sekretariat.buku-tamu.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Daftarkan Tamu Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Tamu Hari Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Total Tamu Hari Ini</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_hari_ini'] }}</p>
                            </div>
                            <div class="text-4xl text-blue-500">👥</div>
                        </div>
                    </div>
                </div>

                <!-- Sudah Check-in -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Sudah Check-in</p>
                                <p class="text-3xl font-bold text-green-600">{{ $stats['hadir'] }}</p>
                            </div>
                            <div class="text-4xl text-green-500">✅</div>
                        </div>
                    </div>
                </div>

                <!-- Belum Check-in -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Terdaftar (Belum Check-in)</p>
                                <p class="text-3xl font-bold text-yellow-600">{{ $stats['terdaftar'] }}</p>
                            </div>
                            <div class="text-4xl text-yellow-500">⏳</div>
                        </div>
                    </div>
                </div>

                <!-- Tidak Hadir -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Tidak Hadir</p>
                                <p class="text-3xl font-bold text-red-600">{{ $stats['tidak_hadir'] }}</p>
                            </div>
                            <div class="text-4xl text-red-500">❌</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Tamu -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">📝 Daftar Tamu Hari Ini</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Nama Tamu</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Asal Instansi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Tujuan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Waktu Registrasi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tamu_list as $tamu)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $tamu->nama_tamu }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Str::limit($tamu->asal_instansi, 20) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Str::limit($tamu->tujuan_kunjungan, 25) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if ($tamu->status === 'hadir') bg-green-100 text-green-800
                                            @elseif($tamu->status === 'terdaftar')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-red-100 text-red-800 @endif
                                        ">
                                            {{ ucfirst($tamu->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $tamu->waktu_registrasi->format('H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="viewDetail({{ $tamu->id }})"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            Lihat
                                        </button>
                                        @if ($tamu->status === 'terdaftar')
                                            <button onclick="checkIn('{{ $tamu->qr_code }}')"
                                                class="text-green-600 hover:text-green-900">
                                                Check-in
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada tamu yang terdaftar hari ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4">
                    {{ $tamu_list->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Tamu -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Detail Tamu</h3>
                <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>
            </div>
            <div id="detailContent" class="space-y-3">
                <!-- Konten akan diisi via AJAX -->
            </div>
        </div>
    </div>

    <script>
        function viewDetail(id) {
            fetch(`/api/sekretariat/buku-tamu/${id}`)
                .then(res => res.json())
                .then(data => {
                    const tamu = data.data;
                    const html = `
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-gray-600 text-sm">Nama Tamu</label>
                                <p class="font-semibold text-gray-900">${tamu.nama_tamu}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 text-sm">Email</label>
                                <p class="font-semibold text-gray-900">${tamu.email || '-'}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 text-sm">Asal Instansi</label>
                                <p class="font-semibold text-gray-900">${tamu.asal_instansi}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 text-sm">Telepon</label>
                                <p class="font-semibold text-gray-900">${tamu.no_telepon || '-'}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-gray-600 text-sm">Tujuan Kunjungan</label>
                                <p class="font-semibold text-gray-900">${tamu.tujuan_kunjungan}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 text-sm">Status</label>
                                <p class="font-semibold text-gray-900">${tamu.status}</p>
                            </div>
                            <div>
                                <label class="text-gray-600 text-sm">Waktu Check-in</label>
                                <p class="font-semibold text-gray-900">${tamu.waktu_check_in ? new Date(tamu.waktu_check_in).toLocaleString('id-ID') : '-'}</p>
                            </div>
                        </div>
                    `;
                    document.getElementById('detailContent').innerHTML = html;
                    document.getElementById('detailModal').classList.remove('hidden');
                })
                .catch(err => alert('Error: ' + err.message));
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        function checkIn(qrCode) {
            if (!confirm('Lakukan check-in untuk tamu ini?')) return;

            fetch('/api/sekretariat/buku-tamu/check-in', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        qr_code: qrCode
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) location.reload();
                })
                .catch(err => alert('Error: ' + err.message));
        }
    </script>
</x-app-layout>
