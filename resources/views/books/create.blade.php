@extends('layouts.app')

@section('content')
<div class="container mt-4">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<h3>Add Book</h3>

			@if(session('error'))
				<div class="alert alert-danger">{{ session('error') }}</div>
			@endif

			<form method="POST" action="{{ route('books.store') }}">
				@csrf

				<div class="mb-3">
					<label class="form-label">Title</label>
					<input name="title" class="form-control" value="{{ old('title') }}" required>
					@error('title')<div class="text-danger">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">Author</label>
					<div class="row">
						<div class="col-md-6">
							<select name="author_id" class="form-select">
								<option value="">Select existing author</option>
								@foreach($authors as $a)
									<option value="{{ $a->id }}" {{ old('author_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<input name="new_author" class="form-control" placeholder="Or type new author name" value="{{ old('new_author') }}">
						</div>
					</div>
					@error('author_id')<div class="text-danger">{{ $message }}</div>@enderror
					@error('new_author')<div class="text-danger">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">Category</label>
					<div class="row">
						<div class="col-md-6">
							<select name="category_id" class="form-select">
								<option value="">Select existing category</option>
								@foreach($categories as $c)
									<option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<input name="new_category" class="form-control" placeholder="Or type new category name" value="{{ old('new_category') }}">
						</div>
					</div>
					@error('category_id')<div class="text-danger">{{ $message }}</div>@enderror
					@error('new_category')<div class="text-danger">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">Genre</label>
					<input name="genre" class="form-control" value="{{ old('genre') }}">
					@error('genre')<div class="text-danger">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">Description</label>
					<textarea name="description" class="form-control">{{ old('description') }}</textarea>
					@error('description')<div class="text-danger">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">Location (Shelf)</label>
					<input name="location" class="form-control" value="{{ old('location') }}">
					@error('location')<div class="text-danger">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label class="form-label">Quantity</label>
					<input name="quantity" type="number" min="1" class="form-control" value="{{ old('quantity', 1) }}" required>
					@error('quantity')<div class="text-danger">{{ $message }}</div>@enderror
				</div>

				<button class="btn btn-primary">Add Book</button>
			</form>
		</div>
	</div>
</div>
@endsection
