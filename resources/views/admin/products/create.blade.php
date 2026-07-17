@extends('admin.layout.app')

@section('title', 'Add Product - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Add New Product</h1>
            <p class="text-muted mb-0">Create a new product in the store inventory.</p>
        </div>
        <div class="col-auto">
            <a href="{{ url('/admin/products') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 14px; font-weight: 600;">
                <i class="ti ti-arrow-narrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Error Validation Alert -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3" role="alert" style="background-color: #FDE8E8; color: #9B1C1C;">
            <div class="d-flex align-items-center mb-2">
                <i class="ti ti-alert-circle-filled me-2 fs-4" style="color: #F05252;"></i>
                <div class="fw-bold">Please check the form for errors:</div>
            </div>
            <ul class="mb-0 ps-4" style="font-size: 13.5px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(16%) sepia(85%) saturate(3195%) hue-rotate(345deg) brightness(85%) contrast(98%);"></button>
        </div>
    @endif

    <!-- Form Section -->
    <form action="{{ url('/admin/products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Basic Info & Variations -->
            <div class="col-lg-8">
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">General Information</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Product Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold text-dark fs-6">Product Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control rounded-3 py-2.5 @error('title') is-invalid @enderror" placeholder="e.g.,Khmer dress" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold text-dark fs-6">Slug (URL Keyword)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3" style="font-size: 13.5px; border-color: var(--border-color);">admin/products/</span>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-control py-2.5 rounded-end-3 @error('slug') is-invalid @enderror" placeholder="e.g., khmer dress" style="border-color: var(--border-color); font-size: 14.5px;">
                            </div>
                            <small class="text-muted d-block mt-1">URL-friendly slug. Lowercase letters, numbers, and hyphens only.</small>
                            @error('slug')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-2">
                            <label for="description" class="form-label fw-semibold text-dark fs-6">Description</label>
                            <textarea name="description" id="description" rows="6" class="form-control rounded-3 py-2.5 @error('description') is-invalid @enderror" placeholder="Enter product specifications, features, and details..." style="border-color: var(--border-color); font-size: 14.5px;">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Variations Card -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Product Variations</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Colors Select -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark fs-6 d-block">Available Colors <span class="text-muted" style="font-size:12px; font-weight: normal;">(Select colors to configure individual sizes & images)</span></label>
                            <div class="row g-3">
                                @forelse($colors as $color)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check p-2.5 rounded-3 border d-flex align-items-center gap-2 color-checkbox-wrapper" style="border-color: var(--border-color); background-color: var(--light-bg);">
                                            <input class="form-check-input ms-0 me-2 color-checkbox" type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}" data-color-name="{{ $color->name }}" data-color-code="{{ $color->code }}" {{ is_array(old('colors')) && in_array($color->id, old('colors')) ? 'checked' : '' }}>
                                            <div class="rounded-circle" style="width: 18px; height: 18px; background-color: {{ $color->code }}; border: 1px solid var(--border-color);"></div>
                                            <label class="form-check-label fw-semibold text-dark" for="color_{{ $color->id }}" style="font-size: 13.5px; cursor: pointer;">
                                                {{ $color->name }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted" style="font-size:13.5px; font-style: italic;">
                                        No active colors found. Click <a href="{{ url('/admin/colors/create') }}">here</a> to add colors.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Dynamic Color Details Container -->
                        <div id="variation-details-container">
                            <!-- Injected dynamically via JS -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Classification & Settings -->
            <div class="col-lg-4">
                <!-- Pricing & Stock Settings -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Pricing & Stock</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Price -->
                        <div class="mb-4">
                            <label for="price" class="form-label fw-semibold text-dark fs-6">Unit Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" class="form-control rounded-3 py-2.5 @error('price') is-invalid @enderror" placeholder="0.00" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Level -->
                        <div>
                            <label for="stock" class="form-label fw-semibold text-dark fs-6">Stock Level <span class="text-danger">*</span></label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" class="form-control rounded-3 py-2.5 @error('stock') is-invalid @enderror" placeholder="0" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Classification -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Classification</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Main Category -->
                        <div class="mb-4">
                            <label for="main_category_id" class="form-label fw-semibold text-dark fs-6">Main Category</label>
                            <select id="main_category_id" class="form-select rounded-3 py-2.5" style="border-color: var(--border-color); font-size: 14px;">
                                <option value="">Select Main Category</option>
                                @foreach($mainCategories as $mainCat)
                                    <option value="{{ $mainCat->id }}">{{ $mainCat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-semibold text-dark fs-6">Category</label>
                            <select name="category_id" id="category_id" class="form-select rounded-3 py-2.5 @error('category_id') is-invalid @enderror" style="border-color: var(--border-color); font-size: 14px;">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-parent-id="{{ $category->main_category_id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Product Collections -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark fs-6">Collections</label>
                            <div class="p-3 border rounded-3 bg-light" style="border-color: var(--border-color) !important; max-height: 200px; overflow-y: auto;">
                                @forelse($collections as $collection)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input shadow-sm" type="checkbox" name="collections[]" value="{{ $collection->id }}" id="collection_{{ $collection->id }}" {{ (is_array(old('collections')) && in_array($collection->id, old('collections'))) ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark fw-medium" for="collection_{{ $collection->id }}" style="font-size: 14px;">
                                            {{ $collection->name }}
                                        </label>
                                    </div>
                                @empty
                                    <div class="text-muted" style="font-size: 13px;">No active collections available.</div>
                                @endforelse
                            </div>
                            <small class="text-muted d-block mt-1">Assign product to special groups like "New Arrivals" or "Promotions".</small>
                            @error('collections')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label fw-semibold text-dark fs-6">Status</label>
                            <select name="status" id="status" class="form-select rounded-3 py-2.5 @error('status') is-invalid @enderror" style="border-color: var(--border-color); font-size: 14px;">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <small class="text-muted d-block mt-1">Inactive products will be hidden from customer storefronts.</small>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Settings -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Product Image</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="image_file" class="form-label fw-semibold text-dark fs-6">Upload Image</label>
                            <input type="file" name="image_file" id="image_file" class="form-control rounded-3 py-2 @error('image_file') is-invalid @enderror" style="border-color: var(--border-color); font-size: 13.5px;">
                            <small class="text-muted d-block mt-1">PNG, JPG, JPEG only. Max size 2MB.</small>
                            @error('image_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image URL -->
                        <div>
                            <label for="image" class="form-label fw-semibold text-dark fs-6">Or Image URL</label>
                            <input type="text" name="image" id="image" value="{{ old('image') }}" class="form-control rounded-3 py-2 @error('image') is-invalid @enderror" placeholder="https://example.com/product.jpg" style="border-color: var(--border-color); font-size: 13.5px;">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Area -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning text-white rounded-pill py-2.5 fw-bold" style="background-color: var(--primary); border-color: var(--primary); font-size: 15px;">
                        <i class="ti ti-circle-check me-1"></i> Save Product
                    </button>
                    <a href="{{ url('/admin/products') }}" class="btn btn-light border rounded-pill py-2.5 fw-semibold text-dark" style="font-size: 15px;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('js')
    <script>
        $(document).ready(function() {
            // Auto generate slug from title
            $('#title').on('input', function() {
                var title = $(this).val();
                var slug = title.toLowerCase()
                                .replace(/[^a-z0-9\s-]/g, '') // Remove invalid chars
                                .replace(/[\s_]+/g, '-')      // Replace spaces/underscores with hyphens
                                .replace(/^-+|-+$/g, '');     // Trim leading/trailing hyphens
                $('#slug').val(slug);
            });

            // Dynamic Category Filter by Parent Main Category
            var $categorySelect = $('#category_id');
            var $allOptions = $categorySelect.find('option').clone();

            $('#main_category_id').on('change', function() {
                var mainId = $(this).val();
                
                // Rebuild category select options
                $categorySelect.empty();
                
                $allOptions.each(function() {
                    var $opt = $(this);
                    var parentId = $opt.data('parent-id');
                    
                    if (!$opt.val() || !mainId || parentId == mainId) {
                        $categorySelect.append($opt.clone());
                    }
                });
            });

            // Trigger change if a subcategory is preselected (e.g., from old input)
            var initialSub = $categorySelect.val();
            if (initialSub) {
                var $selectedOpt = $allOptions.filter(function() {
                    return $(this).val() == initialSub;
                });
                var parentId = $selectedOpt.data('parent-id');
                if (parentId) {
                    $('#main_category_id').val(parentId);
                    $('#main_category_id').trigger('change');
                    $categorySelect.val(initialSub);
                }
            }

            // Dynamic variations handling
            const allSizes = @json($sizes);
            const oldVariants = @json(old('variants') ?? []);
            window.variantImages = {};

            function handleGalleryFiles(colorId, files) {
                if (!window.variantImages[colorId]) {
                    window.variantImages[colorId] = [];
                }
                const currentImages = window.variantImages[colorId];
                let addedAny = false;
                
                for (let i = 0; i < files.length; i++) {
                    if (currentImages.length >= 3) {
                        alert('You can only upload up to 3 gallery images per variation.');
                        break;
                    }
                    const file = files[i];
                    const previewUrl = URL.createObjectURL(file);
                    currentImages.push({
                        type: 'file',
                        file: file,
                        previewUrl: previewUrl
                    });
                    addedAny = true;
                }
                
                if (addedAny) {
                    renderGallery(colorId);
                    syncGalleryInputs(colorId);
                }
            }

            function addGalleryUrl(colorId, url) {
                if (!window.variantImages[colorId]) {
                    window.variantImages[colorId] = [];
                }
                const currentImages = window.variantImages[colorId];
                if (currentImages.length >= 3) {
                    alert('You can only add up to 3 gallery images per variation.');
                    return;
                }
                
                currentImages.push({
                    type: 'url',
                    url: url
                });
                
                renderGallery(colorId);
                syncGalleryInputs(colorId);
            }

            window.removeGalleryItem = function(colorId, index) {
                const currentImages = window.variantImages[colorId] || [];
                const removedItem = currentImages[index];
                
                if (removedItem && removedItem.type === 'file' && removedItem.previewUrl) {
                    URL.revokeObjectURL(removedItem.previewUrl);
                }
                
                currentImages.splice(index, 1);
                renderGallery(colorId);
                syncGalleryInputs(colorId);
            };

            function renderGallery(colorId) {
                const currentImages = window.variantImages[colorId] || [];
                const $previewsContainer = $(`#gallery-previews-${colorId}`);
                $previewsContainer.empty();
                
                currentImages.forEach((img, idx) => {
                    const previewSrc = img.type === 'file' ? img.previewUrl : img.url;
                    const sourceText = img.type === 'file' ? `File: ${img.file.name}` : `URL: ${img.url}`;
                    const sourceBadge = img.type === 'file' ? 'Local' : (img.type === 'db' ? 'Saved' : 'URL');
                    
                    const itemHtml = `
                        <div class="gallery-preview-item">
                            <img src="${previewSrc}" alt="Gallery Preview">
                            <span class="item-badge">${sourceBadge}</span>
                            <button type="button" class="item-delete-btn" onclick="removeGalleryItem(${colorId}, ${idx})" title="Remove image">
                                <i class="ti ti-x"></i>
                            </button>
                            <div class="item-source-tag" title="${sourceText}">${sourceText}</div>
                        </div>
                    `;
                    $previewsContainer.append(itemHtml);
                });
                
                const $dropzone = $(`#gallery-dropzone-${colorId}`);
                if (currentImages.length >= 3) {
                    $dropzone.addClass('d-none');
                } else {
                    $dropzone.removeClass('d-none');
                }
            }

            function syncGalleryInputs(colorId) {
                const currentImages = window.variantImages[colorId] || [];
                
                for (let i = 0; i < 3; i++) {
                    const $fileInput = $(`#hidden-file-${colorId}-${i}`);
                    const $urlInput = $(`#hidden-url-${colorId}-${i}`);
                    const $deleteInput = $(`#hidden-delete-${colorId}-${i}`);
                    
                    if (i < currentImages.length) {
                        const img = currentImages[i];
                        
                        if (img.type === 'file') {
                            const dt = new DataTransfer();
                            dt.items.add(img.file);
                            $fileInput[0].files = dt.files;
                            $urlInput.val('');
                            $deleteInput.val('0');
                        } else if (img.type === 'url' || img.type === 'db') {
                            $fileInput.val('');
                            $urlInput.val(img.url);
                            $deleteInput.val('0');
                        }
                    } else {
                        $fileInput.val('');
                        $urlInput.val('');
                        $deleteInput.val('1');
                    }
                }
            }

            function addColorCard(colorId, colorName, colorCode) {
                if ($(`#variant-card-${colorId}`).length > 0) return;

                const oldData = oldVariants[colorId] || {};
                const oldPrice = oldData.price || '';
                const oldSizes = oldData.sizes || [];

                let sizesHtml = '';
                allSizes.forEach(size => {
                    const isChecked = oldSizes.includes(String(size.id)) || oldSizes.includes(Number(size.id)) ? 'checked' : '';
                    sizesHtml += `
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="form-check p-2 rounded-3 border d-flex align-items-center gap-2" style="border-color: var(--border-color); background-color: #fff;">
                                <input class="form-check-input ms-0 me-1" type="checkbox" name="variants[${colorId}][sizes][]" value="${size.id}" id="variant_size_${colorId}_${size.id}" ${isChecked}>
                                <label class="form-check-label fw-bold text-dark" for="variant_size_${colorId}_${size.id}" style="font-size: 12.5px; cursor: pointer;">
                                    ${size.name}
                                </label>
                            </div>
                        </div>
                    `;
                });

                let imagesHtml = `
                    <div class="col-12">
                        <div class="border rounded-3 p-3 bg-light" style="border-color: var(--border-color) !important;">
                            <div class="gallery-upload-wrapper mb-3" id="gallery-wrapper-${colorId}">
                                <div id="gallery-previews-${colorId}" class="d-flex flex-wrap gap-2"></div>
                                
                                <div id="gallery-dropzone-${colorId}" class="gallery-dropzone">
                                    <i class="ti ti-photo-plus"></i>
                                    <span>Upload (Max 3)</span>
                                    <input type="file" id="gallery-file-input-${colorId}" class="d-none" multiple accept="image/*">
                                </div>
                            </div>

                            <div>
                                <label class="form-label text-muted fw-bold mb-1" style="font-size: 11px;">Or Add Image by URL</label>
                                <div class="input-group">
                                    <input type="text" id="gallery-url-input-${colorId}" class="form-control form-control-sm rounded-start-3" placeholder="https://example.com/image.jpg" style="border-color: var(--border-color); font-size: 12px;">
                                    <button class="btn btn-outline-secondary btn-sm rounded-end-3" type="button" id="gallery-add-url-btn-${colorId}" style="font-size: 12px; border-color: var(--border-color);">Add URL</button>
                                </div>
                            </div>

                            <div id="gallery-hidden-inputs-${colorId}" class="d-none">
                                <input type="file" name="variants[${colorId}][detail_image_file_0]" id="hidden-file-${colorId}-0">
                                <input type="file" name="variants[${colorId}][detail_image_file_1]" id="hidden-file-${colorId}-1">
                                <input type="file" name="variants[${colorId}][detail_image_file_2]" id="hidden-file-${colorId}-2">
                                
                                <input type="text" name="variants[${colorId}][detail_image_0]" id="hidden-url-${colorId}-0">
                                <input type="text" name="variants[${colorId}][detail_image_1]" id="hidden-url-${colorId}-1">
                                <input type="text" name="variants[${colorId}][detail_image_2]" id="hidden-url-${colorId}-2">

                                <input type="hidden" name="variants[${colorId}][delete_detail_0]" id="hidden-delete-${colorId}-0" value="0">
                                <input type="hidden" name="variants[${colorId}][delete_detail_1]" id="hidden-delete-${colorId}-1" value="0">
                                <input type="hidden" name="variants[${colorId}][delete_detail_2]" id="hidden-delete-${colorId}-2" value="0">
                            </div>
                        </div>
                    </div>
                `;

                const cardHtml = `
                    <div class="card border mb-3 rounded-3 variant-card" id="variant-card-${colorId}" style="border-color: var(--border-color) !important;">
                        <div class="card-header bg-light d-flex align-items-center justify-content-between py-2.5">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle" style="width: 16px; height: 16px; background-color: ${colorCode}; border: 1px solid var(--border-color);"></div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px;">${colorName} - Variation Details</h6>
                            </div>
                            <button type="button" class="btn-close remove-variant-btn" data-color-id="${colorId}" aria-label="Close" style="font-size: 12px;"></button>
                        </div>
                        <div class="card-body p-3">
                            <!-- Price Override -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark mb-1" style="font-size: 13px;">Price Override ($) <span class="text-muted" style="font-weight: normal; font-size: 11px;">(Optional - defaults to product price)</span></label>
                                <input type="number" step="0.01" name="variants[${colorId}][price]" value="${oldPrice}" class="form-control py-2 rounded-3" placeholder="Leave blank to use default price" style="border-color: var(--border-color); font-size: 13.5px;">
                            </div>

                            <!-- Sizes Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark mb-1 d-block" style="font-size: 13px;">Available Sizes for ${colorName} <span class="text-muted" style="font-weight: normal; font-size: 11.5px;">(Select multiple)</span></label>
                                <div class="row g-2">
                                    ${sizesHtml}
                                </div>
                            </div>

                            <!-- Detail Images -->
                            <div>
                                <label class="form-label fw-semibold text-dark mb-1 d-block" style="font-size: 13px;">Gallery Images for ${colorName} <span class="text-muted" style="font-weight: normal; font-size: 11.5px;">(Max 3 Gallery Images)</span></label>
                                <div class="row g-3">
                                    ${imagesHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#variation-details-container').append(cardHtml);

                // Initialize state array
                window.variantImages[colorId] = [];

                // Handle oldData
                for (let i = 0; i < 3; i++) {
                    const oldUrl = oldData[`detail_image_${i}`] || '';
                    if (oldUrl) {
                        window.variantImages[colorId].push({
                            type: 'url',
                            url: oldUrl
                        });
                    }
                }

                renderGallery(colorId);
                syncGalleryInputs(colorId);

                // Register event listeners
                const $fileInput = $(`#gallery-file-input-${colorId}`);
                $fileInput.on('change', function(e) {
                    const files = e.target.files;
                    if (files.length > 0) {
                        handleGalleryFiles(colorId, files);
                    }
                    $fileInput.val('');
                });

                const $dropzone = $(`#gallery-dropzone-${colorId}`);
                $dropzone.on('click', function(e) {
                    if (e.target.tagName !== 'INPUT') {
                        $fileInput.click();
                    }
                });

                $dropzone.on('dragover dragenter', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).addClass('dragover');
                });
                $dropzone.on('dragleave drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).removeClass('dragover');
                });
                $dropzone.on('drop', function(e) {
                    const files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) {
                        handleGalleryFiles(colorId, files);
                    }
                });

                $(`#gallery-add-url-btn-${colorId}`).on('click', function() {
                    const $urlInput = $(`#gallery-url-input-${colorId}`);
                    const url = $urlInput.val().trim();
                    if (url) {
                        addGalleryUrl(colorId, url);
                        $urlInput.val('');
                    }
                });
            }

            function removeColorCard(colorId) {
                if (window.variantImages[colorId]) {
                    window.variantImages[colorId].forEach(img => {
                        if (img.type === 'file' && img.previewUrl) {
                            URL.revokeObjectURL(img.previewUrl);
                        }
                    });
                    delete window.variantImages[colorId];
                }
                $(`#variant-card-${colorId}`).remove();
            }

            // Listen to checkbox changes
            $('.color-checkbox').on('change', function() {
                const colorId = $(this).val();
                const colorName = $(this).data('color-name');
                const colorCode = $(this).data('color-code');

                if ($(this).is(':checked')) {
                    addColorCard(colorId, colorName, colorCode);
                } else {
                    removeColorCard(colorId);
                }
            });

            // Allow closing a card to uncheck the checkbox
            $(document).on('click', '.remove-variant-btn', function() {
                const colorId = $(this).data('color-id');
                $(`#color_${colorId}`).prop('checked', false).trigger('change');
            });

            // Initialize existing/old selected colors on load
            $('.color-checkbox:checked').each(function() {
                const colorId = $(this).val();
                const colorName = $(this).data('color-name');
                const colorCode = $(this).data('color-code');
                addColorCard(colorId, colorName, colorCode);
            });
        });
    </script>
    @endpush

    @push('css')
    <style>
        .gallery-upload-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }
        .gallery-dropzone {
            width: 120px;
            height: 120px;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            padding: 8px;
            text-align: center;
        }
        .gallery-dropzone:hover, .gallery-dropzone.dragover {
            border-color: #3b82f6;
            background-color: #eff6ff;
            color: #3b82f6;
        }
        .gallery-dropzone i {
            font-size: 24px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .gallery-dropzone:hover i, .gallery-dropzone.dragover i {
            color: #3b82f6;
        }
        .gallery-dropzone span {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
        }
        .gallery-dropzone:hover span, .gallery-dropzone.dragover span {
            color: #3b82f6;
        }
        .gallery-preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .gallery-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gallery-preview-item .item-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background-color: rgba(15, 23, 42, 0.75);
            color: #fff;
            font-size: 9.5px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 6px;
            backdrop-filter: blur(4px);
        }
        .gallery-preview-item .item-delete-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            background-color: #ef4444;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 0;
        }
        .gallery-preview-item .item-delete-btn:hover {
            background-color: #dc2626;
            transform: scale(1.1);
        }
        .gallery-preview-item .item-delete-btn i {
            font-size: 11px;
        }
        .gallery-preview-item .item-source-tag {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(15, 23, 42, 0.8);
            color: #fff;
            font-size: 9px;
            padding: 3px 6px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            backdrop-filter: blur(4px);
        }
    </style>
    @endpush
@endsection
