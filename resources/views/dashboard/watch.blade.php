@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h2 class="mb-4">إدارة الوسائط</h2>

        <!-- زر لفتح مودال الإضافة -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">
            إضافة جديد
        </button>

        <!-- عرض الرسائل -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- جدول عرض بيانات الوسائط -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>العنوان</th>
                    <th>الوصف</th>
                    <th>النوع</th>
                    <th>السؤال</th>
                    <th>الإجابة</th>
                    <th>الفئة الفرعية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($watch as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->question }}</td>
                        <td>{{ $item->answer }}</td>
                        <td>
                            @php
                                $subcategory = $subcategories->firstWhere('id', $item->subcategory_id);
                            @endphp
                            {{ $subcategory ? $subcategory->name : 'غير محدد' }}
                        </td>
                        <td>
                            <!-- زر تعديل -->
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editModal{{ $item->id }}">
                                تعديل
                            </button>
                            <!-- زر حذف -->
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteModal{{ $item->id }}">
                                حذف
                            </button>
                        </td>
                    </tr>

                    <!-- مودال التعديل -->
                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('watch.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $item->id }}">تعديل الوسائط</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>العنوان</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $item->title }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>الوصف</label>
                                            <textarea name="description" class="form-control">{{ $item->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>السؤال</label>
                                            <input type="text" name="question" class="form-control"
                                                value="{{ $item->question }}">
                                        </div>
                                        <div class="form-group">
                                            <label>الإجابة</label>
                                            <textarea name="answer" class="form-control">{{ $item->answer }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>الفئة الفرعية</label>
                                            <select name="subcategory_id" class="form-control" required>
                                                @foreach ($subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}"
                                                        {{ $item->subcategory_id == $subcategory->id ? 'selected' : '' }}>
                                                        {{ $subcategory->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- مودال الحذف -->
                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('watch.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">تأكيد الحذف</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        هل أنت متأكد من حذف الوسائط "{{ $item->title }}"؟
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-danger">حذف</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- مودال الإضافة -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('watch.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">إضافة وسائط جديدة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>العنوان</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>الوصف</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>السؤال</label>
                            <input type="text" name="question" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>الإجابة</label>
                            <textarea name="answer" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>الفئة الفرعية</label>
                            <select name="subcategory_id" class="form-control" required>
                                <option value="" selected disabled>اختر الفئة الفرعية</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- يتم تعيين نوع الوسائط مباشرة كـ "video" في الكنترولر -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
