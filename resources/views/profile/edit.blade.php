@extends('layouts.app')

@section('content')
<style>
    .notif-anim {
        animation: fadeInScale 0.5s;
    }
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .profile-photo-efek {
        border: 3px solid #6366f1;
        box-shadow: 0 4px 16px rgba(99,102,241,0.15);
        transition: box-shadow 0.3s;
    }
    .profile-photo-efek:hover {
        box-shadow: 0 8px 32px rgba(99,102,241,0.25);
    }
    .badge-verif {
        background: #22c55e;
        color: #fff;
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 9999px;
        margin-left: 8px;
        vertical-align: middle;
    }
</style>
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-4">Profil Saya</h2>
                @if (session('status') === 'profile-updated')
                    <div class="mb-4 p-2 bg-green-100 text-green-700 rounded notif-anim">
                        Profil berhasil diperbarui!
                    </div>
                @endif
                @if (session('status') === 'profile-photo-deleted')
                    <div class="mb-4 p-2 bg-green-100 text-green-700 rounded notif-anim">
                        Foto profil berhasil dihapus!
                    </div>
                @endif
                <!-- Foto Profil & Form Hapus (di luar form utama) -->
                <div class="mb-4">
                    <label class="block font-semibold">Foto Profil</label>
                    <div style="position:relative; display:inline-block;">
                        @if ($user->profile_photo)
                            <img id="profile-photo-preview" src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="Foto Profil" class="w-24 h-24 rounded-full mb-2 object-cover profile-photo-efek">
                            <form method="POST" action="{{ route('profile.delete_photo') }}" onsubmit="return confirm('Yakin ingin menghapus foto profil?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 underline text-sm mt-2">Hapus Foto Profil</button>
                            </form>
                        @else
                            <img id="profile-photo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" alt="Default" class="w-24 h-24 rounded-full mb-2 object-cover profile-photo-efek">
                        @endif
                        @if ($user->hasVerifiedEmail())
                            <span class="badge-verif">Terverifikasi</span>
                        @endif
                    </div>
                </div>
                <!-- Form utama edit profil -->
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="file" name="profile_photo" id="profile_photo_input" class="block mt-2" accept="image/*" onchange="previewProfilePhoto(event)">
                    @error('profile_photo')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                    <div class="mb-4">
                        <label class="block font-semibold">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border rounded w-full p-2" required>
                        @error('name')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border rounded w-full p-2" required>
                        @error('email')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold">Nomor HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="border rounded w-full p-2">
                        @error('phone')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold">Alamat</label>
                        <textarea name="address" class="border rounded w-full p-2">{{ old('address', $user->address ?? '') }}</textarea>
                        @error('address')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold">Tanggal Lahir</label>
                        <input type="date" name="birthdate" value="{{ old('birthdate', $user->birthdate ?? '') }}" class="border rounded w-full p-2">
                        @error('birthdate')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold">Jenis Kelamin</label>
                        <select name="gender" class="border rounded w-full p-2">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender', $user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="text-red-600 text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
                </form>
                <script>
                    function previewProfilePhoto(event) {
                        const [file] = event.target.files;
                        if (file) {
                            const preview = document.getElementById('profile-photo-preview');
                            preview.src = URL.createObjectURL(file);
                            preview.style.display = 'block';
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cropper -->
<div id="cropperModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
    <div style="background:#fff; padding:20px; border-radius:8px; max-width:90vw; max-height:90vh;">
        <img id="cropperImage" style="max-width:100%; max-height:60vh;">
        <div class="mt-4 flex justify-end">
            <button type="button" onclick="cropImage()" class="bg-blue-600 text-white px-4 py-2 rounded mr-2">Crop & Pakai</button>
            <button type="button" onclick="closeCropper()" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
        </div>
    </div>
</div>

<!-- Cropper.js CSS & JS via CDN -->
<link  href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
let cropper;
let fileInput = document.getElementById('profile_photo_input');
let cropperModal = document.getElementById('cropperModal');
let cropperImage = document.getElementById('cropperImage');
let preview = document.getElementById('profile-photo-preview');

fileInput.addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        cropperImage.src = URL.createObjectURL(file);
        cropperModal.style.display = 'flex';
        setTimeout(() => {
            cropper = new Cropper(cropperImage, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
            });
        }, 100);
    }
});

function closeCropper() {
    cropper.destroy();
    cropperModal.style.display = 'none';
    fileInput.value = '';
}

function cropImage() {
    cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        imageSmoothingQuality: 'high'
    }).toBlob((blob) => {
        // Update preview
        preview.src = URL.createObjectURL(blob);

        // Create a new File object and set it to the input
        const file = new File([blob], 'cropped.png', { type: 'image/png' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        cropper.destroy();
        cropperModal.style.display = 'none';
    }, 'image/png');
}
</script>

<hr class="my-8">

<h3 class="text-xl font-bold mb-4">Ubah Password</h3>
@if (session('status') === 'password-updated')
    <div class="mb-4 p-2 bg-green-100 text-green-700 rounded notif-anim">
        Password berhasil diubah!
    </div>
@endif
<form method="POST" action="{{ route('profile.update_password') }}">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="block font-semibold">Password Lama</label>
        <input type="password" name="current_password" class="border rounded w-full p-2" required>
        @error('current_password')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-4">
        <label class="block font-semibold">Password Baru</label>
        <input type="password" name="password" class="border rounded w-full p-2" required>
        @error('password')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-4">
        <label class="block font-semibold">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" class="border rounded w-full p-2" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Ubah Password</button>
</form>
@endsection
