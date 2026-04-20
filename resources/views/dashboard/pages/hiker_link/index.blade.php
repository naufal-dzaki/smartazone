@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Link Pendaki dengan Jam Tangan</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#linkModal">+ Link Baru</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">Daftar Link Pendaki</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Pendaki</th>
                            <th>NIK</th>
                            <th>Telepon</th>
                            <th>Device</th>
                            <th>Battery</th>
                            <th>Status</th>
                            <th>Waktu Mulai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($linked as $l)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $l->hiker_name }}</td>
                                <td>{{ $l->hiker_nik }}</td>
                                <td>{{ $l->hiker_phone }}</td>
                                <td>Device #{{ $l->device_id }}</td>
                                <td>{{ $l->battery_level ?? 'N/A' }}%</td>
                                <td><span class="badge bg-success">{{ $l->status }}</span></td>
                                <td>{{ $l->started_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="linkModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('hiker.link.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tautkan Pendaki & Jam Tangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Booking</label>
                        <select id="bookingSelect" name="booking_id" class="form-select" required>
                            <option value="">Pilih Booking</option>
                            @foreach ($bookings as $b)
                                <option value="{{ $b->id }}" data-members='@json($b->members)'>
                                    {{ $b->leader_name }} (Leader)
                                    | {{ count($b->members) }} orang
                                    | {{ $b->qr_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Member</label>
                        <select id="memberSelect" name="member_name" class="form-select" required>
                            <option value="">Pilih Member</option>
                        </select>
                    </div>

                    <input type="hidden" name="member_nik" id="memberNik">
                    <input type="hidden" name="member_phone" id="memberPhone">

                    <div class="mb-3">
                        <label>Device</label>
                        <select name="device_id" class="form-select" required>
                            <option value="">Pilih Device</option>
                            @foreach ($devices as $d)
                                <option value="{{ $d->id }}">Device #{{ $d->id }} (Battery
                                    {{ $d->battery_level ?? 'N/A' }}%)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            // $('#bookingSelect').on('change', function() {
            //     const members = $(this).find(':selected').data('members');
            //     const $memberSelect = $('#memberSelect');

            //     $memberSelect.empty().append('<option value="">Pilih Member</option>');

            //     if (Array.isArray(members)) {
            //         members.forEach(m => {
            //             $memberSelect.append(
            //                 $('<option>', {
            //                     value: m,
            //                     text: m,
            //                     'data-nik': '',
            //                     'data-phone': ''
            //                 })
            //             );
            //         });
            //     }
            // });

            $('#bookingSelect').on('change', function() {
                const members = $(this).find(':selected').data('members');
                const $memberSelect = $('#memberSelect');

                $memberSelect.empty().append('<option value="">Pilih Member</option>');

                if (Array.isArray(members)) {
                    members.forEach(m => {
                        const isLeader = m.is_leader ? ' (Leader)' : '';

                        $memberSelect.append(
                            $('<option>', {
                                value: m.name,
                                text: `${m.name}${isLeader} - ${m.phone ?? '-'}`,
                                'data-nik': m.nik ?? '',
                                'data-phone': m.phone ?? ''
                            })
                        );
                    });
                }
            });


            // $('#memberSelect').on('change', function() {
            //     $('#memberNik').val('');
            //     $('#memberPhone').val('');
            // });

            $('#memberSelect').on('change', function() {
                const nik = $(this).find(':selected').data('nik');
                const phone = $(this).find(':selected').data('phone');

                $('#memberNik').val(nik);
                $('#memberPhone').val(phone);
            });


        });
    </script>
@endpush
