<div class="row">
    <div class="mb-3" io-image-input="true">
        <label for="aboutInputImage" class="form-label fw-bold">
            {{__('messages.about_us.image')}}:
            <span class="text-muted fs-6">({{__('messages.about_us.upload_image')}})</span>
            <span data-bs-toggle="tooltip"
                  data-placement="top"
                  data-bs-original-title="{{__('messages.tooltip.home_image')}} - {{__('messages.about_us.recommended_size')}} 800x600, {{__('messages.about_us.max_size')}} 10MB"
            >
                <i class="fas fa-question-circle ml-1 mt-1 general-question-mark text-info"></i>
            </span>
        </label>
        
        <div class="d-block">
            <div class="image-picker position-relative">
                <!-- Image Preview Area -->
                <div class="image previewImage position-relative" id="aboutInputImage_{{ $about->id }}"
                     style="background-image: url('{{ $about->getMedia('aboutUs')->count() > 0 ? $about->about_url : asset('front/images/about-1.png') }}'); 
                            min-height: 400px; 
                            background-size: contain; 
                            background-repeat: no-repeat;
                            background-position: center;
                            border: 3px dashed {{ $about->getMedia('aboutUs')->count() > 0 ? '#8b5cf6' : '#dee2e6' }};
                            border-radius: 16px;
                            cursor: pointer;
                            transition: all 0.4s ease;
                            {{ $about->getMedia('aboutUs')->count() > 0 ? '' : 'background-color: #f8f9fa;' }}">
                    
                    <!-- Upload Overlay -->
                    <div class="upload-overlay d-none position-absolute w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white rounded" 
                         style="top: 0; left: 0;">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                            <div>{{__('messages.about_us.drop_image_here')}}</div>
                        </div>
                    </div>
                    
                    <!-- Placeholder when no image -->
                    @if($about->getMedia('aboutUs')->count() == 0)
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-muted">
                            <i class="fas fa-image fa-3x mb-2 opacity-50"></i>
                            <div class="small">{{__('messages.about_us.no_image_uploaded')}}</div>
                        </div>
                    @endif
                    
                    <!-- Current Image Info - Only show if actual media exists -->
                    @if($about->getMedia('aboutUs')->count() > 0)
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-success modern-badge">{{__('messages.about_us.image_uploaded')}}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Upload Button -->
                <div class="text-center mt-2">
                    <label class="btn btn-outline-primary btn-sm upload-btn" for="aboutInputImage_{{ $about->id }}_file">
                        <i class="fas fa-upload me-1"></i>
                        {{__('messages.about_us.choose_image')}}
                    </label>
                    <input type="file" 
                           id="aboutInputImage_{{ $about->id }}_file"
                           name="image[{{ $about->id }}]" 
                           class="image-upload file-validation d-none" 
                           accept="image/*"
                           data-target="aboutInputImage_{{ $about->id }}"/>
                    
                    <!-- Remove Image Button - Only show if actual media exists -->
                    @if($about->getMedia('aboutUs')->count() > 0)
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm ms-2 remove-image-btn"
                                data-target="aboutInputImage_{{ $about->id }}"
                                data-about-id="{{ $about->id }}">
                            <i class="fas fa-trash me-1"></i>
                            {{__('messages.about_us.remove_image')}}
                        </button>
                    @endif
                </div>
                
                <!-- File Info Display -->
                <div class="file-info mt-2 d-none">
                    <small class="text-muted">
                        <i class="fas fa-file-image me-1"></i>
                        <span class="file-name"></span>
                        <span class="file-size ms-2"></span>
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Helpful Information -->
        <div class="form-text">
            <i class="fas fa-info-circle text-info me-1"></i>
            {{__('messages.about_us.supported_formats')}}: JPG, PNG, GIF, BMP, WebP, SVG | 
            {{__('messages.about_us.max_file_size')}}: 10MB | 
            {{__('messages.about_us.drag_drop_supported')}}
        </div>
    </div>

    <div class="col-lg-12">
        <div class="mb-5">
            {{ Form::label('title', __('messages.about_us.title').':', ['class' => 'form-label required fw-bold']) }}
            <span data-bs-toggle="tooltip"
                  data-placement="top"
                  data-bs-original-title="{{__('messages.tooltip.about_title')}}">
                <i class="fas fa-question-circle ml-1 mt-1 general-question-mark text-info"></i>
            </span>
            {{ Form::text('title['.$about->id.']', $about->title, ['class' => 'form-control', 'placeholder' => __('messages.about_us.title'), 'required', 'maxlength'=>'100']) }}
        </div>
    </div>
    
    <div class="col-lg-12">
        <div class="mb-5">
            {{ Form::label('description', __('messages.about_us.description').':', ['class' => 'form-label required fw-bold']) }}
            <span data-bs-toggle="tooltip"
                  data-placement="top"
                  data-bs-original-title="{{__('messages.tooltip.about_description')}}">
                <i class="fas fa-question-circle ml-1 mt-1 general-question-mark text-info"></i>
            </span>
            {!! Form::textarea('description['.$about->id.']', $about->description, ['class' => 'form-control description-textarea', 'placeholder' => __('messages.about_us.description'), 'required', 'rows' => '4', 'maxlength' => '1000']) !!}
            
            <!-- Character Counter -->
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    {{__('messages.about_us.max_characters')}}: 1000
                </small>
                <small class="text-muted character-counter" data-target="description[{{ $about->id }}]">
                    <span class="current-count">{{ strlen($about->description) }}</span>/1000
                </small>
            </div>
        </div>
    </div>
</div>



