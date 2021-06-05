@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Feed') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{ route('feed.create') }}" class="btn btn-primary">New Feed</a>

                    <br/>
                    <br/>

                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <th>Url</th>
                                <th>Notify</th>
                                <th style="width: 125px;"></th>
                            </tr>
                            @forelse ($feeds as $feed)
                                <tr>
                                    <td>{{ $feed->name }}</td>
                                    <td>{{ Str::limit($feed->url, 30) }}</td>
                                    <td>{{ $feed->is_notify_text }}</td>
                                    <td>
                                        <a href="{{ route('feed.edit', $feed->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        <form
                                            class="d-inline-flex"
                                            onsubmit="return confirm('Are you sure?');"
                                            method="POST"
                                            action="{{ route('feed.destroy', $feed->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" class="btn btn-sm btn-danger" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No records</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $feeds->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
