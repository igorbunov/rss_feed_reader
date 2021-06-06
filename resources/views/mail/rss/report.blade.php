@component('mail::message')
# You have received new {{ $count }} results on your rss feed: {{ $feed->name }}

<table class="table" style="width: 100%;">
    <tbody>
        <tr>
            <th>Title</th>
            <th>Date</th>
        </tr>
        @foreach ($feedResult as $result)
            <tr>
                <td>
                    <a href="{{ $result->link }}" target="__blank">{{ $result->title }}</a>
                </td>
                <td>{{ $result->updated_at->diffForHumans() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@component('mail::button', ['url' => route('home')])
Show report
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
