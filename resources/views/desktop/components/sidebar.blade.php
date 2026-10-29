<nav id="sidebar">
  <!-- Sidebar Scroll Container -->
  <div id="sidebar-scroll">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
      <!-- Side Header -->
      <div class="content-header content-header-fullrow px-15">
        <!-- Mini Mode -->
        <div class="content-header-section sidebar-mini-visible-b">
          <!-- Logo -->
          <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
            <span class="text-dual-primary-dark">e</span><span class="text-primary">A</span>
          </span>
          <!-- END Logo -->
        </div>
        <!-- END Mini Mode -->

        <!-- Normal Mode -->
        <div class="content-header-section text-center align-parent sidebar-mini-hidden">
          <!-- Close Sidebar, Visible only on mobile screens -->
          <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
          <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
            <i class="fa fa-times text-danger"></i>
          </button>
          <!-- END Close Sidebar -->

          <!-- Logo -->
          <div class="content-header-item">
            <a class="link-effect font-w700" href="{{url('/')}}">
              <i class="si si-fire text-primary"></i>
              <span class="font-size-xl text-dual-primary-dark">e-</span><span class="font-size-xl text-primary">Absensi</span>
            </a>
          </div>
          <!-- END Logo -->
        </div>
        <!-- END Normal Mode -->
      </div>
      <!-- END Side Header -->

      <!-- Side User -->
      <div class="content-side content-side-full content-side-user px-10 align-parent">
        <!-- Visible only in mini mode -->
        <div class="sidebar-mini-visible-b align-v animated fadeIn">
          <img class="img-avatar img-avatar32" data-load="avatar" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="user">
        </div>
        <!-- END Visible only in mini mode -->

                <!-- Visible only in normal mode -->
                <div class="sidebar-mini-hidden-b text-center">
                    <a class="img-link" href="https://ihcs.ptpn4.co.id" target="_blank">
                        <img class="img-avatar" data-load="avatar" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="user">
                    </a>
                    <ul class="list-inline mt-10">
                        <li class="list-inline-item">
                            <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                            <a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
                                <i class="si si-drop"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="link-effect text-dual-primary-dark" href="javascript:;" onclick="doLogout(event)">
                                <i class="si si-logout"></i>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
                <!-- END Visible only in normal mode -->
            </div>
            <!-- END Side User -->
            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <li>
                        <a href="{{url('/')}}" class="{{ Request::is('/') ? 'active' : '' }}"><i class="si si-cup"></i><span class="sidebar-mini-hide">Beranda</span></a>
                    </li>
                    @allow('show', 'transaksi-absensi')
                    <li class="nav-main-heading"><span class="sidebar-mini-visible">TR</span><span class="sidebar-mini-hidden">Transaksi</span></li>
                    <li><a class="{{ Request::is('transaksi-data/transaksi-absensi*') ? 'active' : '' }}" href="{{route('transaksi-data.transaksi-absensi.index')}}"><i class="si si-list"></i><span class="sidebar-mini-hide">Daftar Absensi</span></a></li>
                    @endallow
                    @allow('show', 'formulir-absensi')
                    <li><a class="{{ Request::is('transaksi-data/formulir-absensi*') ? 'active' : '' }}" href="{{route('transaksi-data.formulir-absensi.index')}}"><i class="si si-doc"></i><span class="sidebar-mini-hide">Formulir Absensi</span></a></li>
                    @endallow
                    <li class="nav-main-heading"><span class="sidebar-mini-visible">RP</span><span class="sidebar-mini-hidden">Report</span></li>
                    <li class="{{ Request::is('laporan*') && !Request::is('laporan/kehadiran-harian*') ? 'open' : '' }}">
                        <a class="nav-submenu {{ Request::is('laporan*') && !Request::is('laporan/kehadiran-harian*') ? 'active' : '' }}" data-toggle="nav-submenu" href="#"><i class="si si-layers"></i><span class="sidebar-mini-hide">Laporan</span></a>
                        <ul>
                            <li>
                                <a class="{{ Request::is('laporan/absensi*') ? 'active' : '' }}" href="{{ route('laporan.absensi.index') }}">Absensi</a>
                            </li>
                            @if(is_unit_menggunakan_mesin(Auth::user()->getUser()->kode_unit))
                            <li>
                                <a class="{{ Request::is('laporan/finger*') ? 'active' : '' }}" href="{{ route('laporan.finger.index') }}">Finger Bulanan</a>
                            </li>
                            <li>
                                <a class="{{ Request::is('laporan/uang-makan*') ? 'active' : '' }}" href="{{ route('laporan.uang-makan.index') }}">Uang Makan</a>
                            </li>
                            @if(Auth::user()->getUser()->kode_unit == '00')
                            <li>
                                <a class="{{ Request::is('laporan/wfh*') ? 'active' : '' }}" href="{{ route('laporan.wfh.index') }}">Working From Home</a>
                            </li>
                            @endif
                            @else
                            <li>
                                <a class="{{ Request::is('laporan/bkm*') ? 'active' : '' }}" href="{{ route('laporan.bkm.index') }}">BKM</a>
                            </li>
                            @endif
                            @if(Auth::user()->hasGroup('ADMIN_ABSENSI') || Auth::user()->hasGroup('SUPER_USER'))
                            <li>
                                <a class="{{ Request::is('laporan/hitung-insentif*') ? 'active' : '' }}" href="{{ route('laporan.hitung-insentif.index') }}">Hitung Insentif</a>
                            </li>
                            <li>
                                <a class="{{ Request::is('laporan/lmsw*') ? 'active' : '' }}" href="{{ route('laporan.lsmw.index') }}">LSMW</a>
                            </li>
                            <li>
                                <a class="{{ Request::is('laporan/bkm*') ? 'active' : '' }}" href="{{ route('laporan.bkm.index') }}">BKM</a>
                            </li>
                            <li>
                                <a class="{{ Request::is('laporan/individu-tahunan*') ? 'active' : '' }}" href="{{ route('laporan.kehadiran-individu-tahunan.index') }}">Kehadiran Individu Tahunan</a>
                            </li>
                            <li>
                                <a class="{{ Request::is('laporan/finger-harian*') ? 'active' : '' }}" href="{{ route('laporan.finger-harian.index') }}">Finger Harian</a>
                            </li>
                            <li>
                                <a class="{{ Request::is('laporan/asistensi-gpu*') ? 'active' : '' }}" href="{{ route('laporan.asistensi-gpu.index') }}">Asistensi GPU</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @if(in_array(Auth::user()->getUser()->jenis_user, ['KARYAWAN_PTPN4', 'KARYAWAN_NON_PTPN4']))
                    <li><a class="{{ Request::is('laporan/kehadiran-harian*') ? 'active' : '' }}" href="{{ route('laporan.kehadiran-harian.index') }}"><i class="fa fa-500px"></i><span class="sidebar-mini-hide">Kehadiran Harian</span></a></li>
                    @endif

          <li class="nav-main-heading"><span class="sidebar-mini-visible">MT</span><span class="sidebar-mini-hidden">Master Data</span></li>

          @if(false)
          <li class="{{ Request::is('master-data/ctcp*') ? 'open' : '' }}">
            <a class="nav-submenu {{ Request::is('master-data/ctcp*') ? 'active' : '' }}" data-toggle="nav-submenu" href="#"><i class="si si-tag"></i><span class="sidebar-mini-hide">Cuti Tahunan/Panjang</span></a>
            <ul>
              <li>
                <a class="{{ Request::is('master-data/ctcp/index-download*') ? 'active' : '' }}" href="{{ route('master-data.ctcp.index-download') }}">Download Template</a>
              </li>
              <li>
                <a class="{{ Request::is('master-data/ctcp/upload*') ? 'active' : '' }}" href="{{ route('master-data.ctcp.index-upload') }}">Upload Data</a>
              </li>
              @if(false)
              <li>
                <a class="{{ Request::is('master-data/ctcp/edit*') ? 'active' : '' }}" href="{{ route('master-data.ctcp.index-edit') }}">Edit Data</a>
              </li>
              @endif
            </ul>
          </li>
          @endif

          @allow('show', 'kode-absensi')
          <li><a class="{{ Request::is('master-data/kode-absensi*') ? 'active' : '' }}" href="{{ route('master-data.kode-absensi.index') }}"><i class="si si-list"></i><span class="sidebar-mini-hide">Kode Absensi</span></a></li>
          @endallow

          @allow('show', 'libur-cuti-bersama')
          <li><a class="{{ Request::is('master-data/libur-cuti-bersama*') ? 'active' : '' }}" href="{{ route('master-data.libur-cuti-bersama.index') }}"><i class="si si-pin"></i><span class="sidebar-mini-hide">Libur & Cuti Bersama</span></a></li>
          @endallow

          @allow('show', 'peserta')
          <li><a class="{{ Request::is('master-data/peserta*') ? 'active' : '' }}" href="{{ route('master-data.peserta.index') }}"><i class="si si-users"></i><span class="sidebar-mini-hide">Peserta</span></a></li>
           {{-- <li><a class="{{ Request::is('master-data/n4attendance/*') ? 'active' : '' }}" href="{{route('master-data.n4attendance.index')}}"><i class="si si-screen-tablet"></i>N4-Attendance</a></li> --}}

           <li class="{{ Request::is('master-data/n4attendance/*') ? 'open' : '' }}">
            <a class="nav-submenu {{ Request::is('master-data/n4attendance/*') ? 'active' : '' }}" data-toggle="nav-submenu" href="#"><i class="fa fa-refresh"></i><span class="sidebar-mini-hide">N4-Attendance</span></a>
            <ul>
              <li><a class="{{ Request::is('master-data/n4attendance/peserta*') ? 'active' : '' }}" href="{{route('master-data.n4attendance.peserta.index')}}">Peserta</a></li>
              <li><a class="{{ Request::is('master-data/n4attendance/lokasi*') ? 'active' : '' }}" href="{{route('master-data.n4attendance.lokasi.index')}}">Lokasi</a></li>
              <li><a class="{{ Request::is('master-data/n4attendance/groups*') ? 'active' : '' }}" href="{{route('master-data.n4attendance.groups.index')}}">Employee Group</a></li>
              <li><a class="{{ Request::is('master-data/n4attendance/mock*') ? 'active' : '' }}" href="{{route('master-data.n4attendance.mock.index')}}">Mock Location</a></li>
              <li><a class="{{ Request::is('master-data/n4attendance/kehadiran*') ? 'active' : '' }}" href="{{route('master-data.n4attendance.kehadiran.index')}}">Kehadiran</a></li>
            </ul>
          </li>
          @endallow

          @allow('show', 'data-kehadiran')
          <li><a class="{{ Request::is('master-data/data-kehadiran*') ? 'active' : '' }}" href="{{ route('master-data.data-kehadiran.index') }}"><i class="si si-check"></i><span class="sidebar-mini-hide">Data Kehadiran</span></a></li>
          @endallow

          <!-- allow('show', 'timetable') -->
          <li><a class="{{ Request::is('master-data/timetable*') ? 'active' : '' }}" href="{{ route('master-data.timetable.index') }}"><i class="fa fa-calendar"></i><span class="sidebar-mini-hide">Timetable</span></a></li>
          <!-- endallow -->
          @if(Auth::user()->hasGroup('ADMIN_ABSENSI') || Auth::user()->hasGroup('SUPER_USER'))
          
          <li class="nav-main-heading"><span class="sidebar-mini-visible">AO</span><span class="sidebar-mini-hidden">ADMIN ONLY</span></li>
          <li class="{{ Request::is('admin/normalisasi*') ? 'open' : '' }}">
            <a class="nav-submenu {{ Request::is('admin/normalisasi*') ? 'active' : '' }}" data-toggle="nav-submenu" href="#"><i class="fa fa-refresh"></i><span class="sidebar-mini-hide">Normalisasi</span></a>
            <ul>
              <li><a class="{{ Request::is('admin/normalisasi/absensi*') ? 'active' : '' }}" href="{{route('admin.normalisasi.absensi.index')}}">Absensi</a></li>
              <li><a class="{{ Request::is('admin/normalisasi/insentif*') ? 'active' : '' }}" href="{{route('admin.normalisasi.insentif.index')}}">Insentif</a></li>
            </ul>
          </li>
          <li class="nav-main-heading"><span class="sidebar-mini-visible">ST</span><span class="sidebar-mini-hidden">Setup</span></li>
          <li><a class="{{ Request::is('admin/panel*') ? 'active' : '' }}" href="{{route('admin.panel.index')}}"><i class="fa fa-gears"></i><span class="sidebar-mini-hide">Admin Panel</span></a></li>
          <li><a class="{{ Request::is('admin/backdate*') ? 'active' : '' }}" href="{{route('admin.backdate.index')}}"><i class="fa fa-clock-o"></i><span class="sidebar-mini-hide">Akses Backdate</span></a></li>
          <li><a class="{{ Request::is('admin/permission*') ? 'active' : '' }}" href="{{route('admin.permission.index')}}"><i class="fa fa-lock"></i><span class="sidebar-mini-hide">Group Permission</span></a></li>

          <li class="{{ Request::is('log*') ? 'open' : '' }}">
            <a class="nav-submenu {{ Request::is('log*') ? 'active' : '' }}" data-toggle="nav-submenu" href="#"><i class="fa fa-history"></i><span class="sidebar-mini-hide">Log</span></a>
            <ul>
              <li>
                <a class="{{ Request::is('log/aktifitas*') ? 'active' : '' }}" href="{{ route('log.aktifitas.index') }}">Aktifitas</a>
              </li>
              <li>
                <a class="{{ Request::is('log/sistem*') ? 'active' : '' }}" href="{{ route('log.sistem.index') }}">System Log</a>
              </li>
            </ul>
          </li>
          @endif
        </ul>
      </div>
      <!-- END Side Navigation -->
    </div>
    <!-- Sidebar Content -->
  </div>
  <!-- END Sidebar Scroll Container -->
</nav>