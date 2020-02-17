$(function(){

  let get = window.location.search.replace('?','').split('&').reduce(
    function (p, e){
      let a = e.split('=');
        p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
      return p;
    },{}
  );

  if (get.id >= 0) {
    if (get.id > 0) {
      $.ajax({
        type: "POST",
        url: "/function.php",
        data: 'page=category&id=' + get.id,
        success: function (data) {
          data = JSON.parse(data);
          $('#categoryName').val(data[0].name);
          $('#categoryTags').val(data[0].keywords);
        }
      });
    }
    $('.btn-save').click(function(){
      $.ajax({
        type: "POST",
        url: "/function.php",
        data: 'page=category&id=' + get.id + '&name=' + $('#categoryName').val() + '&keywords=' + $('#categoryTags').val(),
        success: function () {
          location.assign('/category.php');
        }
      });
    })
  } else {
    $.ajax({
      type: "POST",
      url: "/function.php",
      data: 'page=category',
      success: function (data) {
        data = JSON.parse(data);
        data.forEach(function(item) {
          $('.category').append('<div class="p-v-xs"><a href="/category.php?id=' + item.id + '" class="text-info">' + item.name + '</a><a style="cursor: pointer;" class="remove text-danger m-l-sm" data="' + item.id + '">&#10006;</a></div>');
        });
        $('.remove').click(function () {
          $.post('/function.php', {page: 'category', remove: $(this).attr('data')}, function () {
            location.assign('/category.php');
          });
        })
      }
    });
  }
});
