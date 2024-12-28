@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#mediaModal">
            إضافة وسائط
        </button>

        @if ($errors->any())
            <div class="alert alert-danger w-50">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Media Modal -->
        <div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- تم تكبير الحجم لجعل العناصر مرتبة بشكل أفضل -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediaModalLabel">إضافة وسائط</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div id="audioInputsContainer">
                                <div class="audio-input-group mb-4 border p-3 rounded">
                                    <h5 class="text-primary">ملف صوتي</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="title[]" class="form-label">عنوان الوسائط</label>
                                                <input type="text" class="form-control" name="title[]"
                                                    placeholder="عنوان الوسائط">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="type" class="form-label">نوع الوسائط</label>
                                            <select class="form-control" name="type[]" onchange="toggleMediaInput(this)"
                                                required>
                                                <option value="audio">صوتي</option>
                                                <option value="video">مرئي</option>
                                            </select>
                                        </div>
                                        <div class="form-group" id="audioInput">
                                            <label for="path[]" class="form-label">رفع ملف الصوت</label>
                                            <input type="file" class="form-control" name="path[]" accept="audio/*">
                                        </div>
                                        <div class="form-group d-none" id="videoInput">
                                            <label for="video_path[]" class="form-label">رابط الفيديو</label>
                                            <input type="text" class="form-control" name="path[]"
                                                placeholder="أدخل رابط الفيديو">
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="question[]" class="form-label">السؤال</label>
                                                <input type="text" class="form-control" name="question[]"
                                                    placeholder="السؤال">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="answer[]" class="form-label">الإجابة</label>
                                                <textarea class="form-control" name="answer[]" rows="2" placeholder="الإجابة"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="subcategory_id[]" class="form-label">الفئة الفرعية</label>
                                                <select class="form-control" name="subcategory_id[]">
                                                    @foreach ($subcategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm mt-2 w-100"
                                                onclick="removeAudioInput(this)">إزالة</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success mt-3 w-100" onclick="addAudioInput()">إضافة ملف
                                صوتي آخر</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
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
                        <td>{{ $medium?->subcategory->name }}</td>
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
            const audioInput = selectElement.closest('.audio-input-group').querySelector('#audioInput');
            const videoInput = selectElement.closest('.audio-input-group').querySelector('#videoInput');

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

        function addAudioInput() {
            const container = document.getElementById('audioInputsContainer');
            const newGroup = document.querySelector('.audio-input-group').cloneNode(true);
            newGroup.querySelectorAll('input, textarea, select').forEach(input => input.value = '');
            container.appendChild(newGroup);
        }

        function removeAudioInput(button) {
            const container = document.getElementById('audioInputsContainer');
            if (container.children.length > 1) {
                button.closest('.audio-input-group').remove();
            }
        }
    </script>
@stop
