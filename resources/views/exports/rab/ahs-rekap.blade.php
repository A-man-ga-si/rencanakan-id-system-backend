<table>
    <thead>
        @if ($company)
            <tr></tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $company->name }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $company->address }}</td>
                <td></td>
            </tr>
            <tr></tr>
            <tr></tr>
        @endif
        <tr></tr>
        <tr>
            <td><b>KEGIATAN</b></td>
            <td><b>{{ $project->activity }}</b></td>
        </tr>
        <tr>
            <td><b>NAMA PEKERJAAN</b></td>
            <td><b>{{ $project->job }}</b></td>
        </tr>
        <tr>
            <td><b>LOKASI PEKERJAAN</b></td>
            <td><b>{{ $project->province->name }}</b></td>
        </tr>
        <tr>
            <td><b>TAHUN ANGGARAN</b></td>
            <td><b>{{ $project->fiscal_year }}</b></td>
        </tr>
        <tr></tr>
        <tr>
            <th>No</th>
            <th>URAIAN</th>
            <th>KODE (AHSP)</th>
            <th>SATUAN</th>
            <th>HARGA SATUAN (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @php $rabSum = 0; @endphp
        {{-- @php logger()->info($rabs) @endphp --}}
        @foreach ($rabs ?? [] as $rab)
            <tr>
                <td><b>{{ numToAlphabet($loop->index) }}</b></td>
                <td>
                    {{ $rab->name }}
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{-- <td>{{ $rab->subtotal }}</td> --}}
            </tr>
            @foreach ($rab->rabItem ?? [] as $rabItem)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $rabItem->name }}</td>
                    <td>{{ $rabItem->customAhs ? $rabItem->customAhs->code : '-' }}</td>
                    <td>{{ $rabItem->unit->name }}</td>
                    <td>{{ $rabItem->customAhs ? $rabItem->customAhs->price : $rabItem->price }}</td>
                </tr>
                @php $rabSum += ($rabItem->customAhs ? $rabItem->customAhs->price : $rabItem->price) * $rabItem->volume @endphp
            @endforeach
            @foreach($rab->rabItemHeader ?? [] as $rabItemHeader)
                <tr>
                    <td><b>{{ numToRoman($loop->iteration) }}</b></td>
                    <td><b>{{ $rabItemHeader->name }}</b></td>
                </tr>
                @foreach ($rabItemHeader->rabItem ?? [] as $rabItem)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $rabItem->name }}</td>
                        <td>{{ $rabItem->customAhs ? $rabItem->customAhs->code : '-' }}</td>
                        <td>{{ $rabItem->unit->name }}</td>
                        <td>{{ $rabItem->customAhs ? $rabItem->customAhs->price : $rabItem->price }}</td>
                        {{-- <td>{{ $rabItem->custom_ahs_id != 'null' ? 'true' : $rabItem->price }}</td>
                        <td>{{ ($rabItem->custom_ahs_id != 'null' ? 'true' : $rabItem->price)}}</td> --}}
                        {{-- <td>{{ $rabItem }}</td>
                        <td>{{ $rabItem }}</td> --}}
                        @php $rabSum += ($rabItem->customAhs ? $rabItem->customAhs->price : $rabItem->price) * $rabItem->volume @endphp
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr></tr>
        {{-- <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>JUMLAH TOTAL A + B</b></td>
            <td>{{ $rabSum }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>PPN {{ $project->ppn }}%</b></td>
            @php $ppn = $project->ppn / 100 * $rabSum @endphp
            <td>{{ $ppn }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>JUMLAH TOTAL DENGAN PPN {{ $project->ppn }}%</b></td>
            <td>{{ $rabSum + $ppn }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>TERBILANG</b></td>
            <td>{{ strtoupper(terbilang($rabSum + $ppn)) }} RUPIAH</td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $project->province->name }}, {{ date('d-m-Y') }}</td>
        </tr> --}}
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $project->province->name }}, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Dibuat Oleh</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $company->name }}</td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $company->director_name }}</td>
        </tr>
    </tbody>
</table>
