@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="d-flex">
                        <label for="feed_id" style="margin: 5px 10px 0 0;">Feed:</label>

                        <select name="feed_id" id="feed_id" class="form-control">
                            <option value="0">-- all feeds --</option>

                            @foreach ($allFeeds as $feed)
                            <option @if($feed->id == $feedId) selected @endif value="{{ $feed->id }}">{{ $feed->name }}</option>
                            @endforeach
                        </select>

                        <a href="{{ route('feed.create') }}" class="btn btn-primary" style="width: 122px;margin-left:10px;">New Feed</a>
                    </div>

                    <br/>

                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                            </tr>
                            @forelse ($results as $result)
                                <tr @if ($result->is_watched == 0) class="unwatched" @endif>
                                    <td>
                                        @if (!empty($result->prefix))
                                        <a href="{{ $result->link }}" target="__blank"><span style="color: black;font-size:12px;">{{ $result->prefix }}</span> {{ $result->title }}</a>
                                        @else
                                        <a href="{{ $result->link }}" target="__blank">{{ $result->title }}</a>
                                        @endif
                                    </td>
                                    <td>{{ $result->updated_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $results->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#feed_id').on('change', function() {
                if (this.value > 0) {
                    let url = [
                        'page=1',
                        'feed=' + this.value
                    ];

                    window.location.href = "{{ route('home') }}?" + url.join('&');
                } else {
                    window.location.href = "{{ route('home') }}";
                }
            });
        });
    </script>
@endsection
