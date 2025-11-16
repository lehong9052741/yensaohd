@csrf

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Giá gốc</label>
                <input type="number" name="original_price" class="form-control" value="{{ old('original_price', $product->original_price ? intval($product->original_price) : '') }}" placeholder="Giá gốc (nếu có)">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                <input type="number" name="price" class="form-control" value="{{ old('price', isset($product->price) ? intval($product->price) : 0) }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Giá khuyến mãi</label>
                <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price ? intval($product->sale_price) : '') }}" placeholder="Giá sale (nếu có)">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">% Giảm giá</label>
                <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', isset($product->discount_percent) ? intval($product->discount_percent) : 0) }}" min="0" max="100">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', isset($product->quantity) ? intval($product->quantity) : 0) }}" min="0" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Danh mục</label>
                <select name="category" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                    <option value="Yến Thô" {{ old('category', $product->category ?? '') == 'Yến Thô' ? 'selected' : '' }}>Yến Thô</option>
                    <option value="Yến Tinh Chế" {{ old('category', $product->category ?? '') == 'Yến Tinh Chế' ? 'selected' : '' }}>Yến Tinh Chế</option>
                    <option value="Yến Chưng Sẵn" {{ old('category', $product->category ?? '') == 'Yến Chưng Sẵn' ? 'selected' : '' }}>Yến Chưng Sẵn</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" name="is_best_seller" class="form-check-input" id="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_best_seller">
                    Sản phẩm bán chạy
                </label>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Ảnh sản phẩm</label>
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif" id="productImage">
            <small class="text-muted">Dung lượng tối đa: 5MB. Định dạng: JPG, PNG, GIF</small>
            @if(!empty($product->image))
                <div class="mt-3">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="" class="img-fluid rounded" style="max-width:100%" id="imagePreview">
                </div>
            @else
                <div class="mt-3 text-center p-4 border rounded" id="imagePreview">
                    <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-2">Chưa có ảnh</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary" type="submit">
        <i class="bi bi-save"></i> Lưu
    </button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-circle"></i> Hủy
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('productImage');
    const imagePreview = document.getElementById('imagePreview');
    
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File quá lớn! Vui lòng chọn ảnh có dung lượng nhỏ hơn 5MB.');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Vui lòng chọn file ảnh!');
                    this.value = '';
                    return;
                }
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = '<img src="' + e.target.result + '" alt="" class="img-fluid rounded" style="max-width:100%">';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
