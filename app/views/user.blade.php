@extends('layout')

@section('content')
	<table class="table table-striped">
		<thead> <tr>
			<th>#</th>
			<th>Name</th>
			<th>Email</th>
			</tr>
		</thead>
		<tbody>

		<tr>
		<td>{{ $user->id }}</td>
		<td>{{ $user->name }}</td>
		<td>{{ $user->email }}</td>
		</tbody>
	</table>

@stop
