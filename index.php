<!DOCTYPE html>
<html lang="ru">

  <head>

    <!-- Head CSS & JS -->
    <?php include './layouts/head.inc.php'; ?>
    <!-- /Head CSS & JS -->

  </head>

  <body>
    <?php /*
    <div class="w-full h-full absolute flex flex-grow-0 overflow-y-auto" id="app">
      <?php include './layouts/left.menu.inc.php'; ?>
      <div class="flex-grow">
        <div class="w-full flex pl-4 pt-4">
          <div class="p-4 text-base">
            <i class="icon icon-menu cursor-pointer text-gray-600 hover:text-gray-800" @click="menuOpen = !menuOpen"></i>
          </div>
          <div class="p-4 text-xs pt-5">
            <i class="icon icon-enlarge2 cursor-pointer text-gray-600 hover:text-gray-800" @click="fullSize"></i>
          </div>
        </div>
        <div class="w-full relative">
          <?php include './layouts/calls.inc.php'; ?>
        </div>
      </div>
    </div>
    */ ?>
      <div class="page-container">

        <!-- Page Sidebar -->
        <?php include './layouts/page_sidebar.inc.php'; ?>
        <!-- /Page Sidebar -->

        <!-- Page Content -->
        <div class="page-content">

          <!-- Page Header -->
          <?php include './layouts/page_header.inc.php'; ?>
          <!-- /Page Header -->

          <!-- Page Inner -->
          <div class="page-inner">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 page-title">
                <h3 class="breadcrumb-header">Взаимодействия</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" style="z-index: 9999999999!important">
                <select class="form-control filter">
                  <option value="0" default>График по категориям</option>
                  <option value="1" >Повышение голоса, эмоциональные взаимодействия</option>
                  <option value="2" >Длительные паузы</option>
                  <option value="3" >Продолжительные разговоры</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 p-v-sm">
                <div class="row">
                  <div class="col-sm-2 col-md-2 col-lg-2 col-xl-2" style="padding-top: 0.3rem;">
                    Даты фильтрации
                  </div>
                  <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10">
                    <input type="text" style="width: 100%; padding: 0.2rem 1rem;" class="date_min_max" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 p-v-lg">
                <div class="panel">
                  <div class="chartTitleLeft">Количество взаимодействий по категориям за: </div>
                  <div class="p-v-md"></div>
                  <div class="chartBarLeft" style="height: 25rem"></div>
                </div>
              </div>
              <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 p-v-lg">
                <div class="panel">
                  <div class="chartTitleRight">Количество взаимодействий по категориям за: </div>
                  <div class="p-v-md">
                    <select class="form-control m-v-sm" id="select_cat"></select>
                  </div>
                  <div class="chartBarRight" style="height: 25rem"></div>
                </div>
              </div>
            </div>
            <div class="panel">
               <table id="example" class="display" width="100%"></table>
             </div>
            <?php include './layouts/footer.inc.php'; ?>
          </div>
          <!-- /Page Inner -->

        </div>
        <!-- /Page Content -->

      </div>
      <!-- /Page Container -->
      <script src="./assets/js/dashboard.js"></script>

  </body>
</html>
