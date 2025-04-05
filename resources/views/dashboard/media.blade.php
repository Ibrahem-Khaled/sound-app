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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediaModalLabel">إضافة وسائط</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group mb-4">
                                <label for="mediaType" class="form-label">نوع الوسائط</label>
                                <select class="form-control" id="mediaType" name="mediaType" required>
                                    <option value="audio">صوتي</option>
                                    <option value="video">مرئي</option>
                                </select>
                            </div>

                            <div id="mediaInputSection">
                                <!-- قسم اختيار الملفات المتعددة -->
                                <div class="form-group mb-4" id="multipleAudioSection">
                                    <label for="audioFiles" class="form-label">اختر ملفات صوتية متعددة</label>
                                    <input type="file" class="form-control" id="audioFiles" name="audioFiles[]"
                                        accept="audio/*" multiple>
                                    <small class="text-muted">يمكنك اختيار أكثر من ملف صوتي مرة واحدة</small>
                                </div>

                                <!-- قسم الفيديو (مخفي بشكل افتراضي) -->
                                <div class="form-group mb-4 d-none" id="videoSection">
                                    <label for="videoUrl" class="form-label">رابط الفيديو</label>
                                    <input type="text" class="form-control" id="videoUrl" name="videoUrl"
                                        placeholder="أدخل رابط الفيديو">
                                </div>
                            </div>

                            <div id="mediaFieldsContainer">
                                <!-- سيتم إنشاء الحقول هنا تلقائيا بعد اختيار الملفات -->
                                <div class="alert alert-info">
                                    سيتم إنشاء حقول لكل وسائط بعد الاختيار
                                </div>
                            </div>
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
                        <td>
                            @if ($medium->type == 'audio')
                                <button type="button" class="btn btn-sm btn-info play-audio-btn"
                                    data-audio-src="{{ asset('storage/' . $medium->path) }}">
                                    <i class="fas fa-play"></i> تشغيل
                                </button>
                                <audio class="d-none" id="audioPlayer{{ $medium->id }}">
                                    <source src="{{ asset('storage/' . $medium->path) }}" type="audio/mpeg">
                                </audio>
                            @else
                                {{ $medium->path }}
                            @endif
                        </td>
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
                                            @if ($medium->type == 'audio')
                                                <button type="button" class="btn btn-sm btn-info mt-2 play-audio-btn"
                                                    data-audio-src="{{ asset('storage/' . $medium->path) }}">
                                                    <i class="fas fa-play"></i> تشغيل الملف الحالي
                                                </button>
                                            @endif
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
        document.addEventListener('DOMContentLoaded', function() {
            const mediaTypeSelect = document.getElementById('mediaType');
            const multipleAudioSection = document.getElementById('multipleAudioSection');
            const videoSection = document.getElementById('videoSection');
            const audioFilesInput = document.getElementById('audioFiles');
            const mediaFieldsContainer = document.getElementById('mediaFieldsContainer');

            // تغيير نوع الوسائط
            mediaTypeSelect.addEventListener('change', function() {
                if (this.value === 'audio') {
                    multipleAudioSection.classList.remove('d-none');
                    videoSection.classList.add('d-none');
                } else {
                    multipleAudioSection.classList.add('d-none');
                    videoSection.classList.remove('d-none');
                    mediaFieldsContainer.innerHTML = `
                        <div class="media-input-group mb-4 border p-3 rounded">
                            <h5 class="text-primary">فيديو</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">عنوان الوسائط</label>
                                        <input type="text" class="form-control" name="title[]" placeholder="عنوان الوسائط">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="videoUrl" class="form-label">رابط الفيديو</label>
                                        <input type="text" class="form-control" name="path[]" value="${document.getElementById('videoUrl').value}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="question" class="form-label">السؤال</label>
                                        <input type="text" class="form-control" name="question[]" placeholder="السؤال">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="answer" class="form-label">الإجابة</label>
                                        <textarea class="form-control" name="answer[]" rows="2" placeholder="الإجابة"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="subcategory_id" class="form-label">الفئة الفرعية</label>
                                        <select class="form-control" name="subcategory_id[]">
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            // عند اختيار ملفات صوتية متعددة
            audioFilesInput.addEventListener('change', function(e) {
                const files = e.target.files;
                mediaFieldsContainer.innerHTML = '';

                if (files.length > 0 && mediaTypeSelect.value === 'audio') {
                    Array.from(files).forEach((file, index) => {
                        const audioFieldGroup = document.createElement('div');
                        audioFieldGroup.className = 'media-input-group mb-4 border p-3 rounded';
                        audioFieldGroup.innerHTML = `
                            <h5 class="text-primary">ملف صوتي ${index + 1}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title_${index}" class="form-label">عنوان الوسائط</label>
                                        <input type="text" class="form-control" name="title[]" id="title_${index}" placeholder="عنوان الوسائط">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">معاينة الصوت</label>
                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-sm btn-info play-audio-btn me-2" data-audio-src="${URL.createObjectURL(file)}">
                                                <i class="fas fa-play"></i> تشغيل
                                            </button>
                                            <span>${file.name}</span>
                                        </div>
                                        <input type="hidden" name="audio_filename[]" value="${file.name}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="question_${index}" class="form-label">السؤال</label>
                                        <input type="text" class="form-control" name="question[]" id="question_${index}" placeholder="السؤال">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="answer_${index}" class="form-label">الإجابة</label>
                                        <textarea class="form-control" name="answer[]" id="answer_${index}" rows="2" placeholder="الإجابة"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="subcategory_id_${index}" class="form-label">الفئة الفرعية</label>
                                        <select class="form-control" name="subcategory_id[]" id="subcategory_id_${index}">
                                            @foreach ($subcategories as $subcategory)
                                                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        `;
                        mediaFieldsContainer.appendChild(audioFieldGroup);
                    });
                }
            });

            // تشغيل الصوت عند النقر على زر التشغيل
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('play-audio-btn') || e.target.closest('.play-audio-btn')) {
                    const btn = e.target.classList.contains('play-audio-btn') ? e.target : e.target.closest(
                        '.play-audio-btn');
                    const audioSrc = btn.getAttribute('data-audio-src');
                    const audio = new Audio(audioSrc);
                    audio.play();

                    // تغيير الأيقونة عند التشغيل
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fa-play');
                        icon.classList.add('fa-pause');

                        audio.addEventListener('ended', function() {
                            icon.classList.remove('fa-pause');
                            icon.classList.add('fa-play');
                        });
                    }
                }
            });
        });

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
