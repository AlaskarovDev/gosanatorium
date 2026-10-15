{{-- ═══════════════════════════════════════════════════════════════
     ROOM GALLERY IMAGES - Multi Upload Component
     Bu kodu otaq yaratma/redaktə formasına thumbnail input-dan SONRA əlavə edin
     
     Backend tələbləri:
     - name="images[]" - yeni şəkillər (multiple file input)
     - name="remove_images[]" - silinəcək mövcud şəkillərin media ID-ləri
     
     Dəyişən:
     - $room - redaktə zamanı mövcud otaq (yaratmada null)
═══════════════════════════════════════════════════════════════ --}}

<div class="crancy__item-form--group mg-top-form-20">
    <label class="crancy__item-label">
        {{ __('translate.Gallery Images') }}
        <small class="text-muted">({{ __('translate.You can upload multiple images') }})</small>
    </label>
    
    <div class="room-gallery-uploader" id="roomGalleryUploader">
        
        {{-- Mövcud şəkillər (redaktə zamanı) --}}
        @if(isset($room) && $room->media)
            <div class="existing-images-grid" id="existingImagesGrid">
                @foreach($room->media->where('media_type', 'image')->where('is_thumbnail', false)->sortBy('display_order') as $media)
                    <div class="gallery-image-item" data-media-id="{{ $media->id }}" id="media-{{ $media->id }}">
                        <div class="gallery-image-preview">
                            <img src="{{ asset($media->file_path) }}" alt="Room image">
                            <div class="gallery-image-overlay">
                                <button type="button" class="gallery-remove-btn" onclick="removeExistingImage({{ $media->id }})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <span class="gallery-image-order">#{{ $loop->iteration }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Yeni şəkillər preview --}}
        <div class="new-images-grid" id="newImagesGrid"></div>

        {{-- Drag & Drop Zone --}}
        <div class="gallery-dropzone" id="galleryDropzone">
            <input type="file" 
                   name="images[]" 
                   id="galleryFileInput" 
                   multiple 
                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                   class="gallery-file-input"
                   onchange="handleGalleryFiles(this.files)">
            <div class="gallery-dropzone-content">
                <div class="gallery-dropzone-icon">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <h5 class="gallery-dropzone-title">{{ __('translate.Drag & drop images here') }}</h5>
                <p class="gallery-dropzone-text">{{ __('translate.or click to browse') }}</p>
                <span class="gallery-dropzone-formats">JPEG, PNG, JPG, GIF, WEBP — max 2MB</span>
            </div>
        </div>

        {{-- Silinəcək şəkillərin hidden input-ları buraya əlavə olunacaq --}}
        <div id="removeImagesContainer"></div>

    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════════════
   ROOM GALLERY UPLOADER STYLES
═══════════════════════════════════════════════════════════════ */
.room-gallery-uploader {
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    background: #f8fafc;
    transition: all 0.3s ease;
}

.room-gallery-uploader.dragover {
    border-color: #667eea;
    background: #f0f4ff;
}

/* Existing Images Grid */
.existing-images-grid,
.new-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.existing-images-grid:empty,
.new-images-grid:empty {
    display: none;
    margin-bottom: 0;
}

/* Image Item */
.gallery-image-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.gallery-image-item:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.gallery-image-item.removing {
    opacity: 0.4;
    transform: scale(0.9);
    border-color: #ef4444;
}

.gallery-image-preview {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
}

.gallery-image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-image-item:hover .gallery-image-preview img {
    transform: scale(1.05);
}

/* Overlay */
.gallery-image-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-image-item:hover .gallery-image-overlay {
    opacity: 1;
}

/* Remove Button */
.gallery-remove-btn {
    width: 36px;
    height: 36px;
    background: #ef4444;
    border: none;
    border-radius: 50%;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-remove-btn:hover {
    background: #dc2626;
    transform: scale(1.1);
}

/* Order Badge */
.gallery-image-order {
    display: block;
    text-align: center;
    padding: 4px;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    background: #f1f5f9;
}

/* Dropzone */
.gallery-dropzone {
    position: relative;
    border: 2px dashed #cbd5e1;
    border-radius: 10px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fff;
}

.gallery-dropzone:hover {
    border-color: #667eea;
    background: #f8faff;
}

.gallery-dropzone.dragover {
    border-color: #667eea;
    background: #eef2ff;
    border-style: solid;
}

.gallery-file-input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    z-index: 10;
}

.gallery-dropzone-icon {
    font-size: 36px;
    color: #94a3b8;
    margin-bottom: 10px;
}

.gallery-dropzone:hover .gallery-dropzone-icon {
    color: #667eea;
}

.gallery-dropzone-title {
    font-size: 16px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 4px;
}

.gallery-dropzone-text {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 6px;
}

.gallery-dropzone-formats {
    font-size: 12px;
    color: #94a3b8;
    display: inline-block;
    padding: 4px 12px;
    background: #f1f5f9;
    border-radius: 20px;
}

/* New Image Preview (seçilmiş amma hələ yüklənməmiş) */
.new-image-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    border: 2px solid #22c55e;
}

.new-image-item .gallery-image-preview {
    aspect-ratio: 4/3;
}

.new-image-item .gallery-image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.new-image-badge {
    display: block;
    text-align: center;
    padding: 4px;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
    background: #22c55e;
}

/* Responsive */
@media (max-width: 575px) {
    .existing-images-grid,
    .new-images-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 8px;
    }
    
    .gallery-dropzone { padding: 20px 15px; }
    .gallery-dropzone-icon { font-size: 28px; }
    .gallery-dropzone-title { font-size: 14px; }
}
</style>

<script>
(function() {
    // DataTransfer to manage files in the file input
    let selectedFiles = new DataTransfer();
    
    const dropzone = document.getElementById('galleryDropzone');
    const fileInput = document.getElementById('galleryFileInput');
    const newGrid = document.getElementById('newImagesGrid');
    const uploader = document.getElementById('roomGalleryUploader');
    
    if (!dropzone) return;

    // Drag & Drop Events
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('dragover');
            uploader.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('dragover');
            uploader.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        handleGalleryFiles(files);
    });

    // Handle file selection
    window.handleGalleryFiles = function(files) {
        if (!files || files.length === 0) return;
        
        Array.from(files).forEach(file => {
            // Validate
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                toastr?.error?.(`${file.name}: Yalnız şəkil faylları qəbul edilir`) || alert(`${file.name}: Invalid file type`);
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                toastr?.error?.(`${file.name}: Fayl 2MB-dan böyükdür`) || alert(`${file.name}: File too large`);
                return;
            }
            
            // Add to DataTransfer
            selectedFiles.items.add(file);
            
            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const index = selectedFiles.files.length - 1;
                const div = document.createElement('div');
                div.className = 'gallery-image-item new-image-item';
                div.dataset.fileIndex = index;
                div.innerHTML = `
                    <div class="gallery-image-preview">
                        <img src="${e.target.result}" alt="New image">
                        <div class="gallery-image-overlay">
                            <button type="button" class="gallery-remove-btn" onclick="removeNewImage(this, ${index})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <span class="new-image-badge"><i class="fa-solid fa-plus"></i> Yeni</span>
                `;
                newGrid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        
        // Update file input
        fileInput.files = selectedFiles.files;
    };

    // Remove new (not yet uploaded) image
    window.removeNewImage = function(btn, fileIndex) {
        const item = btn.closest('.gallery-image-item');
        
        // Rebuild DataTransfer without this file
        const newDT = new DataTransfer();
        let currentIndex = 0;
        
        Array.from(selectedFiles.files).forEach((file, i) => {
            if (i !== fileIndex) {
                newDT.items.add(file);
            }
        });
        
        selectedFiles = newDT;
        fileInput.files = selectedFiles.files;
        
        // Remove preview with animation
        item.style.transition = 'all 0.3s ease';
        item.style.opacity = '0';
        item.style.transform = 'scale(0.8)';
        setTimeout(() => {
            item.remove();
            // Re-index remaining items
            document.querySelectorAll('#newImagesGrid .new-image-item').forEach((el, i) => {
                el.dataset.fileIndex = i;
                const removeBtn = el.querySelector('.gallery-remove-btn');
                if (removeBtn) {
                    removeBtn.setAttribute('onclick', `removeNewImage(this, ${i})`);
                }
            });
        }, 300);
    };

    // Remove existing (already saved) image
    window.removeExistingImage = function(mediaId) {
        const item = document.getElementById('media-' + mediaId);
        if (!item) return;
        
        // Toggle
        if (item.classList.contains('removing')) {
            // Undo remove
            item.classList.remove('removing');
            const hiddenInput = document.querySelector(`input[name="remove_images[]"][value="${mediaId}"]`);
            if (hiddenInput) hiddenInput.remove();
        } else {
            // Mark for removal
            item.classList.add('removing');
            const container = document.getElementById('removeImagesContainer');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_images[]';
            input.value = mediaId;
            container.appendChild(input);
        }
    };
})();
</script>