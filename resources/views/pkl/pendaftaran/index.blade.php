@extends('layouts.app')

@section('title', 'Daftar Pendaftaran PKL')

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Pendaftaran PKL</h2>
            <a href="{{ route('pkl.pendaftaran.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-transparent rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Tambah Pendaftaran
            </a>
        </div>

        <!-- Filter dan Pencarian -->
        <div class="mb-6">
            <form action="{{ route('pkl.pendaftaran') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-icon name="search" size="sm" class="text-gray-400" />
                        </div>
                        <input type="text" name="search" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Cari nama siswa atau perusahaan..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="w-full md:w-40">
                    <label for="status" class="sr-only">Status</label>
                    <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                
                <div class="w-full md:w-40">
                    <label for="period" class="sr-only">Periode</label>
                    <select name="period" id="period" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" onchange="this.form.submit()">
                        <option value="">Semua Periode</option>
                        <option value="current" {{ request('period') == 'current' ? 'selected' : '' }}>Periode Saat Ini</option>
                        <option value="past" {{ request('period') == 'past' ? 'selected' : '' }}>Periode Lalu</option>
                        <option value="future" {{ request('period') == 'future' ? 'selected' : '' }}>Periode Mendatang</option>
                    </select>
                </div>
                
                <button type="submit" class="sr-only">Cari</button>
            </form>
        </div>

        <!-- Tabel Pendaftaran PKL -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pkls ?? [] as $pkl)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <x-icon name="user" size="sm" class="text-gray-600" />
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $pkl->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $pkl->user->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $pkl->company->name }}</div>
                                <div class="text-xs text-gray-500">{{ $pkl->position }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($pkl->start_date)->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($pkl->end_date)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $pkl->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($pkl->status === 'approved' ? 'bg-blue-100 text-blue-800' : 
                                        ($pkl->status === 'ongoing' ? 'bg-green-100 text-green-800' : 
                                         ($pkl->status === 'completed' ? 'bg-gray-100 text-gray-800' : 
                                          'bg-red-100 text-red-800'))) }}">
                                    {{ $pkl->getStatusLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $pkl->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('pkl.pendaftaran.show', $pkl->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                @if($pkl->status === 'pending' && Auth::user()->id === $pkl->user_id)
                                    <a href="#" class="text-red-600 hover:text-red-900">Batal</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                Tidak ada data pendaftaran PKL.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($pkls) && $pkls->hasPages())
            <div class="mt-4">
                {{ $pkls->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 