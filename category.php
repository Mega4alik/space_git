<!DOCTYPE html>
<html lang="ru">

  <head>

    <!-- Head CSS & JS -->
    <?php include './layouts/head.inc.php'; ?>
    <!-- /Head CSS & JS -->

  </head>

  <body style="overflow-x: hidden;">

      <!-- Page Container -->
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
                <h3 class="breadcrumb-header">Категории</h3>
              </div>
            </div>
            <?php if (isset($_GET['id'])) { ?>
              <div class="row m-b-lg">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label for="categoryName">Наименование категории</label>
                    <input type="text" class="form-control" id="categoryName" style="width: 50%;" />
                  </div>
                  <div class="form-group">
                    <label class="categoryTags">Ключевые фразы через запятую</label>
                    <input type="text" class="form-control" id="categoryTags" placeholder="Например: забронировать билет, бронирование, бронь" />
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 m-t-lg">
                  <a href="/category.php" class="btn btn-default btn-addon">Отменить</a>
                  <a class="btn btn-success btn-addon btn-save m-l-md">Сохранить</a>
                </div>
              </div>
            <?php } else { ?>
              <div class="row m-b-lg">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 category"></div>
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 m-t-lg">
                  <a href="/category.php?id=0" class="btn btn-success btn-addon">Добавить новую категорию</a>
                </div>
              </div>
            <?php } ?>
            <?php include './layouts/footer.inc.php'; ?>
          </div>
          <!-- /Page Inner -->

        </div>
        <!-- /Page Content -->

      </div>
      <!-- /Page Container -->
      <script src="./assets/js/category.js"></script>
  </body>
</html>
