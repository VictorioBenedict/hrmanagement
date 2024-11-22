<!-- resources/views/Frontend/pages/contactUs/receive.blade.php -->

@extends('layouts.app') <!-- Adjust layout as needed -->

@section('content')
<div class="container">
    <h1>Received Messages</h1>
    @if($contacts->isEmpty())
        <p>No messages found.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <tr>
                        <td>{{ $contacts->name }}</td>
                        <td>{{ $contacts->email }}</td>
                        <td>{{ $contacts->phone }}</td>
                        <td>{{ $contacts->subject }}</td>
                        <td>{{ $contacts->message }}</td>
                        <td>
                            <form action="{{ route('contacts.delete', $contact->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
