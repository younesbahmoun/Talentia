<x-master title="All User Profiles">
    @section('style')
        <style>
            .profile-img {
                width: 120px;
                height: 120px;
                object-fit: cover;
                border: 3px solid #0d6efd;
            }
            .avatar-fallback {
                width: 120px;
                height: 120px;
                background: linear-gradient(45deg, #0d6efd, #6610f2);
                color: white;
                font-size: 3rem;
                font-weight: bold;
            }
            .card-hover {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
            .specialty-badge {
                font-size: 0.85rem;
                padding: 0.35em 0.65em;
            }
        </style>
    @endsection

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-5 fw-bold">
                    <i class="bi bi-people text-primary"></i> All User Profiles
                </h1>
                <p class="text-muted">Browse through our community members and their specializations</p>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Search by name..." style="max-width: 300px;">
                        <select class="form-select" style="max-width: 200px;">
                            <option>All Specialities</option>
                            <option>Web Development</option>
                            <option>Design</option>
                            <option>Marketing</option>
                        </select>
                    </div>
                    <span class="badge bg-info fs-6">12 Profiles</span>
                </div>
            </div>
        </div>

        <!-- Profiles Grid -->
        <div class="row g-4">
            @foreach($profiles as $profile)   
                <!-- Profile Card 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card card-hover h-100 border-0 shadow-sm">
                        <div class="card-body text-center pt-4">
                            <!-- Profile Photo -->
                            <div class="mb-3">
                                <img src="{{ $profile->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($profile->name . ' ' . $profile->prenom) . '&background=0D8ABC&color=fff&size=120' }}" 
                                    alt="John Doe" 
                                    class="rounded-circle profile-img">
                            </div>
                            
                            <!-- Name -->
                            <h4 class="card-title mb-2">{{ $profile->prenom }} {{ $profile->name }}</h4>
                            
                            <!-- Speciality -->
                            <div class="mb-3">
                                <span class="badge bg-primary specialty-badge">
                                    {{-- <i class="bi bi-code-slash"></i>  --}}
                                    {{ $profile->specialite }}
                                </span>
                            </div>
                            
                            <!-- Bio -->
                            @unless(!$profile->prenom)
                            <p class="card-text text-muted mb-4">{{ Str::limit($profile->bio, 30) }}</p>
                            @endunless
                            
                            <!-- Actions -->
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('profile.show') }}">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye"></i> View Profile
                                    </button>
                                </a>
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-person-plus me-1"></i> Connect
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $profiles->links() }}

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    @section('footer')
        @include('components.footer')
    @endsection
    @section('scripts')
        <script>
            // Simple example JavaScript for interactivity
            // document.addEventListener('DOMContentLoaded', function() {
            //     // Add click event to all "View Profile" buttons
            //     document.querySelectorAll('.btn-primary').forEach(button => {
            //         button.addEventListener('click', function() {
            //             const card = this.closest('.card');
            //             const name = card.querySelector('.card-title').textContent;
            //             alert(`Viewing profile of: ${name}`);
            //         });
            //     });
                
            //     // Add click event to all "Message" buttons
            //     document.querySelectorAll('.btn-outline-secondary').forEach(button => {
            //         button.addEventListener('click', function() {
            //             const card = this.closest('.card');
            //             const name = card.querySelector('.card-title').textContent;
            //             alert(`Opening chat with: ${name}`);
            //         });
            //     });
            // });
        </script>
    @endsection
    @section('footer')
        @include('components.footer')
    @endsection
</x-master>