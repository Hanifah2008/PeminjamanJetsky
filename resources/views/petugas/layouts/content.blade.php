    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ isset($pageTitle) ? $pageTitle : 'Dashboard' }}</h1>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        @include(isset($content) ? $content : 'petugas.dashboard.index')
      </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
