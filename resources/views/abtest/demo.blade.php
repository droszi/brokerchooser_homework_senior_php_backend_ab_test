you have {{ count($testVariants) }} test variants assigned<br/>
<br/>
@if(count($testVariants) > 0)
<table>
    <tr>
        <td>test name</td>
        <td>variant name</td>
    </tr>
@foreach ($testVariants as $variant)
    <tr>
        <td>{{ $variant->abTest->name }}</td>
        <td>{{ $variant->name }}</td>
    </tr>
@endforeach
</table>
@endif
