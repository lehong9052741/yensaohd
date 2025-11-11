@csrf

<div class="mb-3">
    <label class="form-label">Tên</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Giá</label>
    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? 0) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Danh mục</label>
    <input type="text" name="category" class="form-control" value="{{ old('category', $product->category ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Ảnh</label>
    <input type="file" name="image" class="form-control">
    @if(!empty($product->image))
        <div class="mt-2">
            <img src="{{ asset('storage/' . $product->image) }}" alt="" style="max-width:200px">
        </div>
    @endif
</div>

<div>
    <button class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
</div>
