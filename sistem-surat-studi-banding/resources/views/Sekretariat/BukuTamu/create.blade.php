<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Daftarkan Tamu Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form id="formTamu" class="space-y-6">
                        @csrf

                        <!-- Nama Tamu -->
                        <div>
                            <label for="nama_tamu" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Tamu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_tamu" id="nama_tamu"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan nama tamu" required>
                            <span class="text-red-500 text-sm error-nama_tamu"></span>
                        </div>

                        <!-- Asal Instansi -->
                        <div>
                            <label for="asal_instansi" class="block text-sm font-medium text-gray-700 mb-1">
                                Asal Instansi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="asal_instansi" id="asal_instansi"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: PT ABC, Universitas XYZ" required>
                            <span class="text-red-500 text-sm error-asal_instansi"></span>
                        </div>

                        <!-- Tujuan Kunjungan -->
                        <div>
                            <label for="tujuan_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">
                                Tujuan Kunjungan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="tujuan_kunjungan" id="tujuan_kunjungan" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Jelaskan tujuan kunjungan" required></textarea>
                            <span class="text-red-500 text-sm error-tujuan_kunjungan"></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email
                                </label>
                                <input type="email" name="email" id="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="email@example.com">
                                <span class="text-red-500 text-sm error-email"></span>
                            </div>

                            <!-- No. Telepon -->
                            <div>
                                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">
                                    No. Telepon
                                </label>
                                <input type="text" name="no_telepon" id="no_telepon"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="08XXXXXXXXXX">
                                <span class="text-red-500 text-sm error-no_telepon"></span>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit" id="submitBtn"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                📝 Daftarkan Tamu
                            </button>
                            <a href="{{ route('sekretariat.buku-tamu.index') }}"
                                class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded text-center">
                                ❌ Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info QR Code -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>ℹ️ Informasi:</strong> Setelah pendaftaran berhasil, QR Code unik akan di-generate otomatis
                    untuk tamu.
                    QR Code dapat dipindai untuk melakukan check-in.
                </p>
            </div>

            <!-- Result Card -->
            <div id="resultCard" class="hidden mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">✅ Tamu Berhasil Didaftarkan</h3>

                    <!-- QR Code Display -->
                    <div class="flex flex-col items-center mb-6">
                        <p class="text-gray-600 mb-3">QR Code Unik:</p>
                        <div id="qrCodeContainer" class="border-2 border-gray-300 p-4 rounded">
                            <img id="qrImage" src="" alt="QR Code" width="300" height="300">
                        </div>
                        <button onclick="downloadQRCode()"
                            class="mt-3 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            ⬇️ Download QR Code
                        </button>
                    </div>

                    <!-- Guest Info -->
                    <div class="bg-gray-50 p-4 rounded mb-4 space-y-2">
                        <p><strong>Nama:</strong> <span id="resultNama"></span></p>
                        <p><strong>UUID:</strong> <code id="resultUUID" class="bg-gray-200 px-2 py-1 rounded"></code>
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <a href="{{ route('sekretariat.buku-tamu.index') }}"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                            ← Kembali ke Dashboard
                        </a>
                        <button onclick="resetForm()"
                            class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                            + Daftarkan Tamu Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formTamu').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Sedang memproses...';

            const formData = {
                nama_tamu: document.getElementById('nama_tamu').value,
                asal_instansi: document.getElementById('asal_instansi').value,
                tujuan_kunjungan: document.getElementById('tujuan_kunjungan').value,
                email: document.getElementById('email').value,
                no_telepon: document.getElementById('no_telepon').value,
            };

            try {
                const response = await fetch('/api/sekretariat/buku-tamu', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(formData)
                });

                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json') ?
                    await response.json() :
                    {
                        success: false,
                        message: await response.text()
                    };

                if (data.success) {
                    // Tampilkan hasil
                    document.getElementById('formTamu').classList.add('hidden');
                    document.getElementById('resultCard').classList.remove('hidden');
                    document.getElementById('qrImage').src = data.data.qr_image;
                    document.getElementById('resultNama').textContent = data.data.nama_tamu;
                    document.getElementById('resultUUID').textContent = data.data.qr_code;

                    // Simpan QR image untuk download
                    window.qrImageUrl = data.data.qr_image;
                } else {
                    alert('Error: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.textContent = '📝 Daftarkan Tamu';
                }
            } catch (error) {
                alert('Error: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = '📝 Daftarkan Tamu';
            }
        });

        function downloadQRCode() {
            const link = document.createElement('a');
            link.href = window.qrImageUrl;
            link.download = 'qr-code.png';
            link.click();
        }

        function resetForm() {
            document.getElementById('formTamu').reset();
            document.getElementById('formTamu').classList.remove('hidden');
            document.getElementById('resultCard').classList.add('hidden');
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('submitBtn').textContent = '📝 Daftarkan Tamu';
        }
    </script>
</x-app-layout>
