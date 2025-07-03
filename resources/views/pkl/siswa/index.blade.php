@extends('layouts.app')

@section('title', 'Daftar Siswa PKL')

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Siswa PKL</h2>
            <button class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors">
                Tambah Siswa
            </button>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <div class="flex gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm" placeholder="Cari siswa...">
                    </div>
                </div>
                <div>
                    <select name="status" class="block w-full py-2 px-3 border border-gray-200 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-sm">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Persetujuan</option>
                        <option value="approved">Disetujui</option>
                        <option value="ongoing">Sedang Berlangsung</option>
                        <option value="completed">Selesai</option>
                        <option value="rejected">Ditolak</option>
                        <option value="none">Belum Mendaftar</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIS
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Siswa
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status Akun
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status PKL
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Perusahaan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $student->nis ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full overflow-hidden">
                                    @if ($student->avatar)
                                        <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-xs font-medium text-gray-600">
                                            {{ $student->initials }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($student->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $student->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($student->pkls->count() > 0)
                                @php $pkl = $student->pkls->sortByDesc('created_at')->first(); @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $pkl->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                       ($pkl->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($pkl->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                         ($pkl->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                    {{ $pkl->getStatusLabel() }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    Belum Mendaftar
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if ($student->pkls->count() > 0 && $student->pkls->sortByDesc('created_at')->first()->company)
                                {{ $student->pkls->sortByDesc('created_at')->first()->company->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('pkl.siswa.show', $student->id) }}" class="text-gray-500 hover:text-gray-700" title="Lihat Detail">
                                    <x-icon name="eye" size="sm" />
                                </a>
                                <a href="{{ route('pkl.siswa.edit', $student->id) }}" class="text-gray-500 hover:text-gray-700" title="Edit Siswa">
                                    <x-icon name="pencil" size="sm" />
                                </a>
                                @if ($student->pkls->count() > 0)
                                    <a href="{{ route('pkl.pendaftaran.show', $student->pkls->sortByDesc('created_at')->first()->id) }}" class="text-gray-500 hover:text-gray-700" title="Detail PKL">
                                        <x-icon name="document-text" size="sm" />
                                    </a>
                                @else
                                    <a href="{{ route('pkl.pendaftaran.create', ['student_id' => $student->id]) }}" class="text-gray-500 hover:text-gray-700" title="Daftarkan PKL">
                                        <x-icon name="plus-circle" size="sm" />
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data siswa yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection 