@extends('layouts.app')

@section('title', 'Dashboard - SMKN 4 BOGOR')

@section('content')
        <!-- Hero Section Fullscreen -->
        <div class="hero-section-fullscreen">
            <div class="hero-slider">
                <div class="hero-slide active" style="background-image: url('{{ asset('images/foto 1.jpeg') }}')"></div>
                <div class="hero-slide" style="background-image: url('{{ asset('images/foto 2.jpeg') }}')"></div>
                <div class="hero-slide" style="background-image: url('{{ asset('images/foto 3.jpeg') }}')"></div>
            </div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Selamat Datang di SMKN 4 BOGOR</h1>
                <p>Temukan berbagai kegiatan, prestasi, dan informasi terkini tentang sekolah kami</p>
                <div class="hero-actions">
                    <a href="#jurusan" class="btn-cta">Lihat Jurusan</a>
                    <a href="#lokasi" class="btn-cta btn-ghost">Lokasi & Kontak</a>
                </div>
            </div>
        </div>

        <!-- Tentang SMKN 4 Bogor -->
        <section id="tentang" class="section-fullscreen mb-4 section-alt py-3">
            <div class="container pb-4 section-soft accented decor-gradient-top">
                <div class="row align-items-stretch g-4">
                    <div class="col-12 text-center mb-2">
                        <h2 class="vm-title-center">Tentang SMKN 4 BOGOR</h2>
                        <p class="vm-subtitle">Profil singkat sekolah kami</p>
                    </div>
                    <div class="col-lg-7 d-flex">
                        <div class="dashboard-card vm-glow h-100 w-100">
                            <div class="d-flex align-items-center mb-3">
                                <span class="vm-chip me-2"><i class="fas fa-school"></i></span>
                                <strong>SMKN 4 Bogor</strong>
                            </div>
                            <p class="mb-0 text-rich" style="text-align: justify; line-height: 1.8;">
                                SMKN 4 Bogor adalah Sekolah Menengah Kejuruan Negeri yang berlokasi di Jl. Raya Tajur, Kp. Buntar, Muarasari, Bogor Selatan. Berdiri sejak 2009 dan meraih akreditasi A pada 2018, sekolah ini dipimpin oleh <strong>Drs. Mulya Mulprihartono, M.Si</strong> sejak Juli 2020 dengan berbagai inovasi, seperti penggunaan <em>Learning Management System (LMS)</em>, kerjasama dengan dunia industri (IDUKA), serta penguatan praktik belajar. Berada di lahan seluas <strong>12.724 m²</strong>, SMKN 4 Bogor memiliki <strong>54 guru</strong> dan <strong>22 staf TU</strong>, serta fasilitas lengkap seperti laboratorium praktik, ruang kelas, aula, lapangan upacara, taman, akses internet satelit, dan listrik dari PLN & Diesel. Dengan sistem lima hari sekolah penuh, SMKN 4 Bogor berkomitmen mencetak lulusan yang kompeten, siap kerja, dan mampu bersaing di dunia industri.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-5 d-flex">
                        <div class="dashboard-card h-100 w-100">
                            <div class="tiles-grid">
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-user-graduate"></i></div>
                                    <div class="tile-value">54</div>
                                    <div class="tile-label">Guru</div>
                                </div>
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-users"></i></div>
                                    <div class="tile-value">22</div>
                                    <div class="tile-label">Staf TU</div>
                                </div>
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-award"></i></div>
                                    <div class="tile-value">Akreditasi A</div>
                                    <div class="tile-label">Sejak 2018</div>
                                </div>
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-map-marked-alt"></i></div>
                                    <div class="tile-value">12.724 m²</div>
                                    <div class="tile-label">Luas Lahan</div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a class="btn-cta alt" href="{{ route('guru-staf') }}">
                                    <i class="fas fa-compass"></i> Jelajahi Guru & Staf
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>

        
        
        <section id="visi-misi" class="section-fullscreen mb-4 section-alt py-3">
            <div class="container section-soft accented decor-gradient-top">
            <div class="row">
                <div class="col-12 text-center mb-3">
                    <h2 class="vm-title-center">Visi & Misi SMKN 4 BOGOR</h2>
                    <p class="vm-subtitle">Mewujudkan lulusan berkarakter, kompeten, dan siap kerja di era industri</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-card vm-card">
                        <div class="vm-badge"><i class="fas fa-bullseye"></i> Visi</div>
                        <p class="vm-quote">Menjadi SMK unggulan yang menghasilkan lulusan berkualitas, berkarakter, dan siap kerja dalam bidang teknologi dan industri.</p>
                        <div class="vm-divider"></div>
                        <div class="vm-badge badge-outline"><i class="fas fa-list-check"></i> Misi</div>
                        <ul class="mb-0 vm-list vm-list-readable">
                            <li><span class="vm-chip"><i class="fas fa-check"></i></span>Menyelenggarakan pendidikan kejuruan yang berkualitas dan relevan dengan kebutuhan industri</li>
                            <li><span class="vm-chip"><i class="fas fa-check"></i></span>Mengembangkan karakter siswa yang beriman, berakhlak mulia, dan berwawasan global</li>
                            <li><span class="vm-chip"><i class="fas fa-check"></i></span>Meningkatkan kompetensi guru dan tenaga kependidikan secara berkelanjutan</li>
                            <li><span class="vm-chip"><i class="fas fa-check"></i></span>Membangun kerjasama dengan dunia usaha dan industri untuk pengembangan pembelajaran</li>
                            <li><span class="vm-chip"><i class="fas fa-check"></i></span>Menyediakan sarana dan prasarana yang memadai untuk mendukung proses pembelajaran</li>
                        </ul>
                    </div>
                    </div>
                </div>
            </div>
        </section>

        
        <!-- Fasilitas Sekolah -->
        <section id="fasilitas" class="section-fullscreen mb-4 section-alt py-3">
            <div class="container section-soft accented decor-gradient-top">
                <div class="row">
                    <div class="col-12 text-center mb-3">
                        <h2 class="vm-title-center">Fasilitas SMKN 4 BOGOR</h2>
                        <p class="vm-subtitle">Fasilitas lengkap penunjang proses belajar dan kegiatan sekolah</p>
                    </div>
                </div>
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-7 d-flex">
                        <div class="dashboard-card vm-card w-100 h-100">
                            <div class="vm-badge"><i class="fas fa-toolbox"></i> Fasilitas Unggulan</div>
                            <div class="vm-divider"></div>
                            <div class="tiles-grid">
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-desktop"></i></div>
                                    <div class="tile-label fw-semibold">Lab Komputer Modern</div>
                                    <div class="tile-desc text-muted small">Lab komputer nyaman untuk belajar coding dan desain.</div>
                                </div>
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-screwdriver-wrench"></i></div>
                                    <div class="tile-label fw-semibold">Bengkel Praktik</div>
                                    <div class="tile-desc text-muted small">Bengkel lengkap untuk praktik jurusan teknik.</div>
                                </div>
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-wifi"></i></div>
                                    <div class="tile-label fw-semibold">WiFi Gratis</div>
                                    <div class="tile-desc text-muted small">WiFi gratis untuk kegiatan belajar.</div>
                                </div>
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-book"></i></div>
                                    <div class="tile-label fw-semibold">Perpustakaan</div>
                                    <div class="tile-desc text-muted small">Perpustakaan nyaman dengan koleksi buku yang memadai.</div>
                                </div>
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-shield-alt"></i></div>
                                    <div class="tile-label fw-semibold">Keamanan 24 Jam</div>
                                    <div class="tile-desc text-muted small">Keamanan terjaga 24 jam.</div>
                                </div>
                                <div class="tile text-center">
                                    <div class="tile-icon"><i class="fas fa-trophy"></i></div>
                                    <div class="tile-label fw-semibold">Lapangan Olahraga</div>
                                    <div class="tile-desc text-muted small">Lapangan untuk aktivitas olahraga dan ekskul.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 d-flex">
                        <div class="dashboard-card w-100 h-100">
                            <!-- Video 1 -->
                            <div class="map-responsive" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;">
                                <iframe src="https://www.youtube.com/embed/N6cmqCbQllo" title="Fasilitas - Video 1" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                            <!-- Video 2 -->
                            <div class="map-responsive mt-3" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;">
                                <iframe src="https://www.youtube.com/embed/auya1s3yif4" title="Fasilitas - Video 2" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="jurusan" class="section-fullscreen mb-4 section-alt py-3">
            <div class="container section-soft decor-gradient-top">
            <div class="row">
                <div class="col-12 text-center mb-3">
                    <h2 class="vm-title-center">Jurusan SMKN 4 BOGOR</h2>
                    <p class="vm-subtitle"><strong>Jelajahi Jurusan</strong> – klik salah satu kartu untuk melihat deskripsi singkat setiap jurusan.</p>
                </div>
                    </div>
            <div class="row g-3 align-items-stretch">
				<div class="col-sm-6 col-lg-3 d-flex">
					<div class="jur-card w-100">
						<img class="jur-logo" src="{{ asset('images/pplg.jpeg') }}" alt="Logo PPLG">
						<h5>Pengembangan Perangkat Lunak dan Gim (PPLG)</h5>
						<p>Pengembangan aplikasi web/mobile, UI/UX, database, dan gim dasar.</p>
						<div class="mt-2 cta-wrap">
							<a class="btn-cta alt major-trigger" data-bs-toggle="modal" data-bs-target="#majorModal"
							   data-title="Pengembangan Perangkat Lunak dan Gim (PPLG)"
							   data-desc="Jurusan PPLG mempelajari cara merancang, membuat, dan mengembangkan perangkat lunak serta gim. Siswa dibekali keterampilan coding, desain UI/UX, basis data, hingga manajemen proyek perangkat lunak. Selain itu, juga diajarkan membuat aplikasi berbasis web, mobile, maupun desktop, serta mengembangkan gim dengan teknologi terkini. Lulusan PPLG mampu bekerja sebagai programmer, game developer, software engineer, web developer, mobile developer, atau melanjutkan studi di bidang informatika dan teknologi."
							   data-img="{{ asset('images/pplg.jpeg') }}">
								<i class="fas fa-compass"></i> Jelajahi Jurusan
							</a>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="jur-card">
						<img class="jur-logo" src="{{ asset('images/tkj.jpeg') }}" alt="Logo TKJ">
						<h5>Teknik Komputer & Jaringan (TKJ)</h5>
						<p>Instalasi jaringan kabel/wifi, administrasi server, keamanan, dan troubleshooting.</p>
						<div class="mt-2 cta-wrap">
							<a class="btn-cta alt major-trigger" data-bs-toggle="modal" data-bs-target="#majorModal"
							   data-title="Teknik Komputer & Jaringan (TKJ)"
							   data-desc="Jurusan TKJ berfokus pada instalasi, perakitan, serta perawatan komputer dan jaringan. Siswa belajar mengenai perangkat keras, sistem operasi, administrasi server, keamanan jaringan, hingga manajemen data. Praktik utama meliputi merancang jaringan LAN/WAN, konfigurasi router dan switch, hingga cloud computing. Lulusan TKJ siap bekerja sebagai network administrator, teknisi komputer, IT support, system administrator, atau berwirausaha di bidang jasa komputer & jaringan."
							   data-img="{{ asset('images/tkj.jpeg') }}">
								<i class="fas fa-compass"></i> Jelajahi Jurusan
							</a>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="jur-card">
						<img class="jur-logo" src="{{ asset('images/to.jpeg') }}" alt="Logo TO">
						<h5>Teknik Otomotif (TO)</h5>
						<p>Perawatan dan perbaikan kendaraan modern, mesin, kelistrikan, dan EFI.</p>
						<div class="mt-2 cta-wrap">
							<a class="btn-cta alt major-trigger" data-bs-toggle="modal" data-bs-target="#majorModal"
							   data-title="Teknik Otomotif (TO)"
							   data-desc="Jurusan TO mempelajari perawatan, perbaikan, dan rekayasa kendaraan bermotor, baik roda dua maupun roda empat. Siswa diajarkan tentang mesin, sistem kelistrikan, chasis, transmisi, hingga teknologi otomotif modern seperti kendaraan listrik dan injeksi. Selain keterampilan mekanik, juga dilatih menganalisis kerusakan, melakukan servis berkala, serta modifikasi kendaraan. Lulusan TO dapat berkarier sebagai mekanik, teknisi otomotif, konsultan otomotif, service advisor, atau membuka bengkel mandiri."
							   data-img="{{ asset('images/to.jpeg') }}">
								<i class="fas fa-compass"></i> Jelajahi Jurusan
							</a>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="jur-card">
						<img class="jur-logo" src="{{ asset('images/tpfl.jpeg') }}" alt="Logo TPFL">
						<h5>Teknik Pengelasan & Fabrikasi Logam (TPFL)</h5>
						<p>Fabrikasi dan pengelasan logam sesuai standar industri serta K3.</p>
						<div class="mt-2 cta-wrap">
							<a class="btn-cta alt major-trigger" data-bs-toggle="modal" data-bs-target="#majorModal"
							   data-title="Teknik Pengelasan &amp; Fabrikasi Logam (TPFL)"
							   data-desc="Jurusan TPFL mendalami keterampilan mengelas, memotong, dan membentuk logam untuk kebutuhan konstruksi maupun industri. Siswa dilatih menguasai berbagai teknik pengelasan (SMAW, GMAW, GTAW, dsb.), menggambar teknik, membaca blueprint, serta menggunakan peralatan fabrikasi. Bidang ini sangat dibutuhkan di dunia industri manufaktur, perkapalan, otomotif, dan konstruksi. Lulusan TPFL dapat bekerja sebagai welder, teknisi fabrikasi, quality control logam, atau teknisi konstruksi baja baik di dalam maupun luar negeri."
							   data-img="{{ asset('images/tpfl.jpeg') }}">
								<i class="fas fa-compass"></i> Jelajahi Jurusan
							</a>
						</div>
					</div>
				</div>
            </div>
        </div>
        </section>

        
        <section id="lokasi" class="section-fullscreen section-vh mb-4 section-alt py-3">
            <div class="container section-soft decor-gradient-top">
        <div class="row">
                <div class="col-12 text-center mb-3">
                    <h2 class="vm-title-center">Peta Sekolah SMKN 4 Bogor</h2>
                    <p class="vm-subtitle">Jalan Raya Tajur, Kp. Buntar, Kel. Muarasari, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16137</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                <div class="dashboard-card">
                        <div class="map-responsive map-fullheight">
                            <iframe
                                src="https://www.google.com/maps?q=Jalan%20Raya%20Tajur%2C%20Kp.%20Buntar%2C%20Kelurahan%20Muarasari%2C%20Kecamatan%20Bogor%20Selatan%2C%20Kota%20Bogor%2C%20Jawa%20Barat%2016137&output=embed"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <h6>Alamat</h6>
                        <p class="mb-0">
                            <a class="contact-link" href="https://www.google.com/maps?q=Jalan+Raya+Tajur,+Kp.+Buntar,+Muarasari,+Bogor+Selatan,+Kota+Bogor,+Jawa+Barat+16137" target="_blank" rel="noopener">
                                Jl. Raya Tajur, Kp. Buntar RT.02/RW.08<br>Kel. Muarasari, Kec. Bogor Selatan<br>Kota Bogor, Jawa Barat 16137
                            </a>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-phone"></i></div>
                        <h6>Telepon</h6>
                        <p class="mb-0"><a class="contact-link" href="tel:+622511234567">(0251) 1234567</a></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <h6>Email</h6>
                        <p class="mb-0"><a class="contact-link" href="mailto:info@smkn4bogor.sch.id">info@smkn4bogor.sch.id</a></p>
                    </div>
                </div>
            </div>
        </div>
        </section>

        <!-- Main Content Container -->
        <div class="container main-content">

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const slides = document.querySelectorAll('.hero-slide');
                let idx = 0;
                if (slides.length > 1) {
                    setInterval(() => {
                        slides[idx].classList.remove('active');
                        idx = (idx + 1) % slides.length;
                        slides[idx].classList.add('active');
                    }, 5000);
                }

                // Smooth scroll for same-page anchors
                document.querySelectorAll('a[href^="#"]').forEach(link => {
                    link.addEventListener('click', function (e) {
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth' });
                        }
                    });
                });

                // Modal Jurusan
                const majorModal = document.getElementById('majorModal');
                if (majorModal) {
                    const titleEl = majorModal.querySelector('.modal-title');
                    const bodyDesc = majorModal.querySelector('.major-desc');
                    const imgEl = majorModal.querySelector('.major-img');
                    document.querySelectorAll('.major-trigger').forEach(el => {
                        el.addEventListener('click', () => {
                            const title = el.getAttribute('data-title') || 'Jurusan';
                            const desc = el.getAttribute('data-desc') || '';
                            const img = el.getAttribute('data-img') || '';
                            titleEl.textContent = title;
                            bodyDesc.textContent = desc;
                            if (img) imgEl.src = img;
                        });
                        // juga dukung Enter untuk aksesibilitas
                        el.addEventListener('keydown', (ev) => {
                            if (ev.key === 'Enter' || ev.key === ' ') {
                                ev.preventDefault();
                                el.click();
                            }
                        });
                    });
                }
            });
        </script>
        </section>

        <!-- Modal Informasi Jurusan -->
        <div class="modal fade" id="majorModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row g-3 align-items-center">
                  <div class="col-md-5">
                    <img src="" alt="logo jurusan" class="img-fluid rounded shadow-sm major-img">
                  </div>
                  <div class="col-md-7">
                    <p class="mb-0 major-desc"></p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
        @endsection