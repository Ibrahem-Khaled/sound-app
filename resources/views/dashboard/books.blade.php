@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#bookModal">
            إضافة كتاب
        </button>

        <!-- Add Book Modal -->
        <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookModalLabel">إضافة كتاب</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">اسم الكتاب</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="slug">الرمز</label>
                                <input type="text" class="form-control" id="slug" name="slug">
                            </div>
                            <div class="form-group">
                                <label for="image">صورة الكتاب</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">حفظ الكتاب</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Books Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم التعريف</th>
                    <th>الاسم</th>
                    <th>الرمز</th>
                    <th>الصورة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td>{{ $book->name }}</td>
                        <td>{{ $book->slug }}</td>
                        <td>
                            @if ($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->name }}" width="50">
                            @else
                                لا توجد صورة
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editBookModal{{ $book->id }}">
                                تعديل
                            </button>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Book Modal -->
                    <div class="modal fade" id="editBookModal{{ $book->id }}" tabindex="-1"
                        aria-labelledby="editBookModalLabel{{ $book->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editBookModalLabel{{ $book->id }}">تعديل الكتاب</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('books.update', $book->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name{{ $book->id }}">اسم الكتاب</label>
                                            <input type="text" class="form-control" id="name{{ $book->id }}"
                                                name="name" value="{{ $book->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="slug{{ $book->id }}">الرمز</label>
                                            <input type="text" class="form-control" id="slug{{ $book->id }}"
                                                name="slug" value="{{ $book->slug }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="image{{ $book->id }}">صورة الكتاب</label>
                                            <input type="file" class="form-control" id="image{{ $book->id }}"
                                                name="image">
                                            @if ($book->image)
                                                <img src="{{ asset('storage/' . $book->image) }}"
                                                    alt="{{ $book->name }}" width="50">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">إغلاق</button>
                                        <button type="submit" class="btn btn-primary">تحديث الكتاب</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
