<script type="text/javascript">
    function sendRequest(url) {
        fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: '{}'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            location.replace('/abtest/admin');
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>



you have {{ count($abTests) }} a/b tests<br/>
<br/>
@if(count($abTests) > 0)
<table>
    <tr>
        <td>test name</td>
        <td>variants</td>
        <td>status</td>
        <td>action</td>
    </tr>
@foreach ($abTests as $abTest)
    <tr>
        <td>{{ $abTest->name }}</td>
        <td>{{ count($abTest->variants) }}</td>
        <td>{{ $abTest->status }}</td>
        <td>@switch(1)
            @case($abTest->isReadyToRun)
                <a href="#" onclick="javascript:sendRequest('/api/v1/abtest/{{ $abTest->id }}/start')">start</a>
                @break
            @case($abTest->isRunning)
                <a href="#" onclick="javascript:sendRequest('/api/v1/abtest/{{ $abTest->id }}/stop')">stop</a>
                @break
            @default
                n/a
        @endswitch</td>
    </tr>
@endforeach
</table>
@endif

