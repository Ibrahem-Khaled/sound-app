@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#subCategoryModal">
            إضافة فئة فرعية
        </button>

        <!-- Add SubCategory Modal -->
        <div class="modal fade" id="subCategoryModal" tabindex="-1" aria-labelledby="subCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subCategoryModalLabel">إضافة فئة فرعية</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('subcategories.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">اسم الفئة الفرعية</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="slug">الرمز</label>
                                <input type="text" class="form-control" id="slug" name="slug">
                            </div>
                            <div class="form-group">
                                <label for="category_id">الفئة الرئيسية</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">حفظ الفئة الفرعية</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SubCategories Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم التعريف</th>
                    <th>الاسم</th>
                    <th>الرمز</th>
                    <th>الفئة الرئيسية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subcategories as $subcategory)
                    <tr>
                        <td>{{ $subcategory->id }}</td>
                        <td>{{ $subcategory->name }}</td>
                        <td>{{ $subcategory->slug }}</td>
                        <td>{{ $subcategory->category->name }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editSubCategoryModal{{ $subcategory->id }}">
                                تعديل
                            </button>
                            <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit SubCategory Modal -->
                    <div class="modal fade" id="editSubCategoryModal{{ $subcategory->id }}" tabindex="-1"
                        aria-labelledby="editSubCategoryModalLabel{{ $subcategory->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSubCategoryModalLabel{{ $subcategory->id }}">تعديل
                                        الفئة الفرعية
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('subcategories.update', $subcategory->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name{{ $subcategory->id }}">اسم الفئة الفرعية</label>
                                            <input type="text" class="form-control" id="name{{ $subcategory->id }}"
                                                name="name" value="{{ $subcategory->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="slug{{ $subcategory->id }}">الرمز</label>
                                            <input type="text" class="form-control" id="slug{{ $subcategory->id }}"
                                                name="slug" value="{{ $subcategory->slug }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="category_id{{ $subcategory->id }}">الفئة الرئيسية</label>
                                            <select class="form-control" id="category_id{{ $subcategory->id }}"
                                                name="category_id" required>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        @if ($subcategory->category_id == $category->id) selected @endif>
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                        <button type="submit" class="btn btn-primary">تحديث الفئة الفرعية</button>
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
