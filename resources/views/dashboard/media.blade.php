@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#mediaModal">
            إضافة وسائط
        </button>

        <!-- Add Media Modal -->
        <div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediaModalLabel">إضافة وسائط</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">عنوان الوسائط</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div class="form-group">
                                <label for="description">الوصف</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="type">نوع الوسائط</label>
                                <select class="form-control" id="type" name="type" required
                                    onchange="toggleMediaInput(this)">
                                    <option value="audio">صوت</option>
                                    <option value="video">فيديو</option>
                                </select>
                            </div>
                            <div class="form-group" id="audioInput">
                                <label for="path">رفع ملف الصوت</label>
                                <input type="file" class="form-control" id="path" name="path">
                            </div>
                            <div class="form-group d-none" id="videoInput">
                                <label for="path">رابط الفيديو</label>
                                <input type="text" class="form-control" id="path" name="path">
                            </div>
                            <div class="form-group">
                                <label for="question">السؤال</label>
                                <input type="text" class="form-control" id="question" name="question">
                            </div>
                            <div class="form-group">
                                <label for="answer">الإجابة</label>
                                <textarea class="form-control" id="answer" name="answer"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="subcategory_id">الفئة الفرعية</label>
                                <select class="form-control" id="subcategory_id" name="subcategory_id" required>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">حفظ الوسائط</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Media Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم التعريف</th>
                    <th>العنوان</th>
                    <th>الوصف</th>
                    <th>النوع</th>
                    <th>المسار</th>
                    <th>السؤال</th>
                    <th>الإجابة</th>
                    <th>الفئة الفرعية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($media as $medium)
                    <tr>
                        <td>{{ $medium->id }}</td>
                        <td>{{ $medium->title }}</td>
                        <td>{{ $medium->description }}</td>
                        <td>{{ $medium->type }}</td>
                        <td>{{ $medium->path }}</td>
                        <td>{{ $medium->question }}</td>
                        <td>{{ $medium->answer }}</td>
                        <td>{{ $medium->subcategory->name }}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#editMediaModal{{ $medium->id }}">
                                تعديل
                            </button>
                            <form action="{{ route('media.destroy', $medium->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Media Modal -->
                    <div class="modal fade" id="editMediaModal{{ $medium->id }}" tabindex="-1"
                        aria-labelledby="editMediaModalLabel{{ $medium->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editMediaModalLabel{{ $medium->id }}">تعديل الوسائط
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('media.update', $medium->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="title{{ $medium->id }}">عنوان الوسائط</label>
                                            <input type="text" class="form-control" id="title{{ $medium->id }}"
                                                name="title" value="{{ $medium->title }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="description{{ $medium->id }}">الوصف</label>
                                            <textarea class="form-control" id="description{{ $medium->id }}" name="description">{{ $medium->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="type{{ $medium->id }}">نوع الوسائط</label>
                                            <select class="form-control" id="type{{ $medium->id }}" name="type"
                                                required onchange="toggleEditMediaInput({{ $medium->id }}, this)">
                                                <option value="audio" @if ($medium->type == 'audio') selected @endif>
                                                    صوت</option>
                                                <option value="video" @if ($medium->type == 'video') selected @endif>
                                                    فيديو</option>
                                            </select>
                                        </div>
                                        <div class="form-group @if ($medium->type != 'audio') d-none @endif"
                                            id="audioInput{{ $medium->id }}">
                                            <label for="path{{ $medium->id }}">رفع ملف الصوت</label>
                                            <input type="file" class="form-control" id="path{{ $medium->id }}"
                                                name="path">
                                        </div>
                                        <div class="form-group @if ($medium->type != 'video') d-none @endif"
                                            id="videoInput{{ $medium->id }}">
                                            <label for="path{{ $medium->id }}">رابط الفيديو</label>
                                            <input type="text" class="form-control" id="path{{ $medium->id }}"
                                                name="path" value="{{ $medium->path }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="question{{ $medium->id }}">السؤال</label>
                                            <input type="text" class="form-control" id="question{{ $medium->id }}"
                                                name="question" value="{{ $medium->question }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="answer{{ $medium->id }}">الإجابة</label>
                                            <textarea class="form-control" id="answer{{ $medium->id }}" name="answer">{{ $medium->answer }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="subcategory_id{{ $medium->id }}">الفئة الفرعية</label>
                                            <select class="form-control" id="subcategory_id{{ $medium->id }}"
                                                name="subcategory_id" required>
                                                @foreach ($subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}"
                                                        @if ($medium->subcategory_id == $subcategory->id) selected @endif>
                                                        {{ $subcategory->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">إغلاق</button>
                                        <button type="submit" class="btn btn-primary">تحديث الوسائط</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function toggleMediaInput(selectElement) {
            const audioInput = document.getElementById('audioInput');
            const videoInput = document.getElementById('videoInput');
            if (selectElement.value === 'audio') {
                audioInput.classList.remove('d-none');
                videoInput.classList.add('d-none');
            } else {
                audioInput.classList.add('d-none');
                videoInput.classList.remove('d-none');
            }
        }

        function toggleEditMediaInput(id, selectElement) {
            const audioInput = document.getElementById(`audioInput${id}`);
            const videoInput = document.getElementById(`videoInput${id}`);
            if (selectElement.value === 'audio') {
                audioInput.classList.remove('d-none');
                videoInput.classList.add('d-none');
            } else {
                audioInput.classList.add('d-none');
                videoInput.classList.remove('d-none');
            }
        }
    </script>
@stop
