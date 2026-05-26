@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h3 class="text-2xl font-semibold mb-4">Check-in Tamu</h3>

                @if (isset($message))
                    <p class="mb-4 text-gray-700">{{ $message }}</p>
                @endif

                @if (isset($tamu))
                    <p class="font-semibold">Nama: {{ $tamu->nama_tamu }}</p>
                    <p class="text-sm text-gray-600 mb-4">Asal: {{ $tamu->asal_instansi }}</p>
                @endif

                <a href="{{ route('sekretariat.buku-tamu.index') }}"
                    class="mt-4 inline-block bg-blue-600 text-white py-2 px-4 rounded">Kembali</a>
            </div>
        </div>
    </div>
@endsection
