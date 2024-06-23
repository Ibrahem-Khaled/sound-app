@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#categoryModal">
            إضافة فئة
        </button>

        <!-- Add Category Modal -->
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">إضافة فئة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">اسم الفئة</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="slug">الرمز</label>
                                <input type="text" class="form-control" id="slug" name="slug">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">حفظ الفئة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم التعريف</th>
                    <th>الاسم</th>
                    <th>الرمز</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editCategoryModal{{ $category->id }}">
                                تعديل
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Category Modal -->
                    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1"
                        aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">تعديل الفئة
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name{{ $category->id }}">اسم الفئة</label>
                                            <input type="text" class="form-control" id="name{{ $category->id }}"
                                                name="name" value="{{ $category->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="slug{{ $category->id }}">الرمز</label>
                                            <input type="text" class="form-control" id="slug{{ $category->id }}"
                                                name="slug" value="{{ $category->slug }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                        <button type="submit" class="btn btn-primary">تحديث الفئة</button>
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
