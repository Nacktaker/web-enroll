@extends('layouts.app')

@section('content')
<div class="container">
	<h1>Edit User</h1>

	@if($errors->any())
		<div style="background:#fff5f5;border:1px solid #fbd5d5;padding:8px;margin-bottom:12px;">
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<form method="POST" action="{{ route('users.update', $user->id) }}">
		@csrf
		@method('PUT')

		<div>
			<label for="name">Name</label>
			<input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required />
		</div>

		<div>
			<label for="email">Email</label>
			<input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
		</div>

		<div>
			<label for="password">Password (leave blank to keep current)</label>
			<input id="password" name="password" type="password" />
		</div>

		<div>
			<label for="password_confirmation">Confirm Password</label>
			<input id="password_confirmation" name="password_confirmation" type="password" />
		</div>

		<div style="margin-top:12px;">
			<button type="submit">Save</button>
			<a href="{{ route('users.view', $user->id) }}">Cancel</a>
		</div>
	</form>
</div>
@endsection
