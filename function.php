<?php

  include './layouts/connect.php';

  header('Content-Type: text/html; charset=utf-8');
  mb_internal_encoding('utf8');
  date_default_timezone_set('Asia/Almaty');

  if ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
     !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
  {
    $page = isset($_POST['page']) ? $_POST['page'] : '';
    if ($page == 'category')
    {
      echo category();
    }
    else
    {
      $filters = 0;
      if(isset($_POST['type']))
      {
        if (in_array($_POST['type'], array('1','2','3','4','6')))
        {
          $filters = $_POST['type'];
        }
      }
      switch ($filters) {
        case 0:
          echo filter_get_0();
          break;
        case 1:
          echo filter_get_1();
          break;
        case 2:
          echo filter_get_2();
          break;
        case 3:
          echo filter_get_3();
          break;
        case 6:
          echo table_get();
          break;
      };
    }
  } else {
    header('Location: /');
    exit;
  }

  function filter_get_0 () {
    global $pdo;
    $ids = array();
    $labels = array();
    $category = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($category as $item)
    {
      $ids[] = $item['id'];
      $labels[] = $item['name'];
    }

    $dateLeft_parse = explode('.', $_POST['date_left']);
    $dateLeft = new DateTime();
    $dateLeft->setDate($dateLeft_parse[2], $dateLeft_parse[1], $dateLeft_parse[0]);
    $dateLeft = $dateLeft->format('Y-m-d h:i:s');
    $dateRight_parse = explode('.', $_POST['date_right']);
    $dateRight = new DateTime();
    $dateRight->setDate($dateRight_parse[2], $dateRight_parse[1], $dateRight_parse[0]);
    $dateRight = $dateRight->format('Y-m-d h:i:s');

    $categories = [];
    $audios = $pdo->query('SELECT * FROM audios WHERE date >= "'.$dateLeft.'" AND date <= "'.$dateRight.'"')->fetchAll(PDO::FETCH_ASSOC);
    $result = array();
    foreach ($category as $item)
    {
      $count = 0;
      foreach ($audios as $item2)
      {
        $cats = json_decode($item2['categories'], JSON_OBJECT_AS_ARRAY);
        if (in_array($item['id'], $cats))
        {
          $count++;
        }
      }
      $categories[] = array('id' => $item['id'], 'name' => $item['name']);
      $result[] = $count;
    }
    $labelsTemp = [];
    $counts = [];
    $labelsRight = [];
    $resultRight = [];
    $category_param = isset($_POST['category']) ? $_POST['category'] : $categories[0]['id'];
    foreach ($audios as $item) {
      $cats = json_decode($item['categories'], JSON_OBJECT_AS_ARRAY);
      if (in_array($category_param, $cats)) {
        if (array_key_exists(date('d.m.Y', strtotime($item['date'])), $labelsTemp))
        {
          $count = $count + 1;
        }
        else
        {
          $count = 1;
        }
        $labelsTemp[date('d.m.Y', strtotime($item['date']))] = $count;
      }
    }
    foreach ($labelsTemp as $k => $v)
    {
      $labelsRight[] = $k;
      $resultRight[] = $v;
    }

    return json_encode(
      array(
        'left' => array(
          'items' => $labels,
          'datas' => $result,
          'title' => 'График по категориям',
          'chartTitle' => 'Количество взаимодействий по категориям за',
          'color' => 'rgba(54, 162, 235, 1)',
          'label' => 'Количество',
        ),
        'right' => array (
          'items' => $labelsRight,
          'datas' => $resultRight,
          'categories' => $categories,
          'title' => 'График',
          'chartTitle' => 'Категория',
          'color' => 'rgba(54, 162, 235, 1)',
          'label' => 'Количество'
        )
      )
    );
  }

  function filter_get_1 () {
    global $pdo;
    $ids = array();
    $labels = array();
    $operators = $pdo->query('SELECT * FROM operators')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($operators as $item)
    {
      $ids[] = $item['id'];
      $labels[] = $item['name'];
    }

    $dateLeft_parse = explode('.', $_POST['date_left']);
    $dateLeft = new DateTime();
    $dateLeft->setDate($dateLeft_parse[2], $dateLeft_parse[1], $dateLeft_parse[0]);
    $dateLeft = $dateLeft->format('Y-m-d h:i:s');
    $dateRight_parse = explode('.', $_POST['date_right']);
    $dateRight = new DateTime();
    $dateRight->setDate($dateRight_parse[2], $dateRight_parse[1], $dateRight_parse[0]);
    $dateRight = $dateRight->format('Y-m-d h:i:s');

    $audios = $pdo->query('SELECT * FROM audios WHERE operator_id IN (' . implode(',', $ids) . ') AND date >= "'.$dateLeft.'" AND date <= "'.$dateRight.'" AND emotional = 1')->fetchAll(PDO::FETCH_ASSOC);
    $result = array();
    foreach ($operators as $item)
    {
      $count = 0;
      foreach ($audios as $item2)
      {
        if ($item['id'] == $item2['operator_id'])
        {
          $count++;
        }
      }
      $result[] = $count;
    }

    $labels2 = array();
    $categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($categories as $item)
    {
      $ids[] = $item['id'];
      $labels2[] = $item['name'];
    }
    $audios = $pdo->query('SELECT * FROM audios WHERE date >= "'.$dateLeft.'" AND date <= "'.$dateRight.'" AND emotional = 1')->fetchAll(PDO::FETCH_ASSOC);
    $result2 = array();
    foreach ($categories as $item)
    {
      $count = 0;
      foreach ($audios as $item2)
      {
        $cats = json_decode($item2['categories'], JSON_OBJECT_AS_ARRAY);
        if (in_array($item['id'], $cats))
        {
           $count++;
        }
      }
      $result2[] = $count;
    }
    return json_encode(
      array(
        'left' => array(
          'items' => $labels,
          'datas' => $result,
          'title' => 'График взаимодействий',
          'chartTitle' => 'Взаимодействия с повышением голоса на операторов за',
          'color' => 'rgba(138, 30, 148, 1)',
          'label' => 'Количество'
        ),
        'right' => array(
          'items' => $labels2,
          'datas' => $result2,
          'title' => 'График взаимодействий',
          'chartTitle' => 'Взаимодействия с повышением голоса по категориям за',
          'color' => 'rgba(54, 162, 235, 1)',
          'label' => 'Количество'
        )
      )
    );
  }

  function filter_get_2 () {
    global $pdo;
    $ids = array();
    $labels = array();
    $result = array();
    $colors = array();
    $color = '#be2b2b';
    $label = array();
    $operators = $pdo->query('SELECT * FROM operators')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($operators as $item)
    {
      $ids[] = $item['id'];
      $labels[] = $item['name'];
    }

    $dateLeft_parse = explode('.', $_POST['date_left']);
    $dateLeft = new DateTime();
    $dateLeft->setDate($dateLeft_parse[2], $dateLeft_parse[1], $dateLeft_parse[0]);
    $dateLeft = $dateLeft->format('Y-m-d h:i:s');
    $dateRight_parse = explode('.', $_POST['date_right']);
    $dateRight = new DateTime();
    $dateRight->setDate($dateRight_parse[2], $dateRight_parse[1], $dateRight_parse[0]);
    $dateRight = $dateRight->format('Y-m-d h:i:s');

    $audios = $pdo->query('SELECT * FROM audios WHERE operator_id IN (' . implode(',', $ids) . ') AND date >= "'.$dateLeft.'" AND date <= "'.$dateRight.'"')->fetchAll(PDO::FETCH_ASSOC);
    $result = array();
    foreach ($operators as $item)
    {
      $count_10 = 0;
      $count_30 = 0;
      $count_60 = 0;
      $all_count = 0;
      foreach ($audios as $item2)
      {
        if ($item['id'] == $item2['operator_id'])
        {
          $times = json_decode($item2['pauses'], JSON_OBJECT_AS_ARRAY);
          foreach ($times as $time)
          {
            if ($time['duration'] >= 10000 && $time['duration'] < 30000) $count_10++;
            if ($time['duration'] >= 30000 && $time['duration'] < 60000) $count_30++;
            if ($time['duration'] >= 60000) $count_60++;
          }
          $all_count++;
        }
      }
      $percent = $all_count / 100;
      if ($percent > 0)
      {
        $percent_10 = $count_10 / $percent;
        $percent_30 = $count_30 / $percent;
        $percent_60 = $count_60 / $percent;
      }
      $result[] = array($percent_10,$percent_30,$percent_60);
      if ($color == '#be2b2b')
      {
        $colors[] = '#0f8214';
        $color = '#0f8214';
        $label[] = '10 секунд и более (%)';
        continue;
      }
      if ($color == '#0f8214')
      {
        $colors[] = '#959292';
        $color = '#959292';
        $label[] = '30 секунд и более (%)';
        continue;
      }
      if ($color == '#959292')
      {
        $colors[] = '#be2b2b';
        $color = '#be2b2b';
        $label[] = '60 секунд и более (%)';
        continue;
      }
    }

    $labels2 = array();
    $categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($categories as $item)
    {
      $ids[] = $item['id'];
      $labels2[] = $item['name'];
    }

    $audios = $pdo->query('SELECT * FROM audios WHERE date >= "'.$dateLeft.'" AND date <= "'.$dateRight.'"')->fetchAll(PDO::FETCH_ASSOC);
    $result2 = array();
    foreach ($categories as $item)
    {
      $count = 0;
      $all_count = 0;
      foreach ($audios as $item2)
      {
        $cats = json_decode($item2['categories'], JSON_OBJECT_AS_ARRAY);
        if (in_array($item['id'], $cats))
        {
          if ($item2['pauses'] != '')
          {
            $times = json_decode($item2['pauses'], JSON_OBJECT_AS_ARRAY);
            foreach ($times as $time)
            {
              if ($time['duration'] > 60000)
              {
                $count++;
              }
            }
            $all_count++;
          }
        }
      }
      $percent = $all_count / 100;
      if ($percent > 0)
      {
        $percent = $count / $percent;
      }
      $result2[] = $percent;
    }
    return json_encode(
      array(
        'left' => array(
          'items' => $labels,
          'datas' => $result,
          'title' => 'Взаимодействия с долгими паузами в % соотношении',
          'chartTitle' => 'График по операторам за',
          'color' => $colors,
          'label' => $label
        ),
        'right' => array(
          'items' => $labels2,
          'datas' => $result2,
          'title' => 'Кол. пауз в % соотношении длительностью более минуты',
          'chartTitle' => 'График по категориям за',
          'color' => 'rgba(54, 162, 235, 1)',
          'label' => '%'
        )
      )
    );
  }

  function filter_get_3 (){
    global $pdo;
    $ids = array();
    $labels = array();
    $operators = $pdo->query('SELECT * FROM operators')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($operators as $item)
    {
      $ids[] = $item['id'];
      $labels[] = $item['name'];
    }

    $dateLeft_parse = explode('.', $_POST['date_left']);
    $dateLeft = new DateTime();
    $dateLeft->setDate($dateLeft_parse[2], $dateLeft_parse[1], $dateLeft_parse[0]);
    $dateLeft = $dateLeft->format('Y-m-d h:i:s');
    $dateRight_parse = explode('.', $_POST['date_right']);
    $dateRight = new DateTime();
    $dateRight->setDate($dateRight_parse[2], $dateRight_parse[1], $dateRight_parse[0]);
    $dateRight = $dateRight->format('Y-m-d h:i:s');

    $audios = $pdo->query('SELECT operator_id, AVG(duration)as duration FROM audios WHERE operator_id IN (' . implode(',', $ids) . ') AND date >= "'.$dateLeft.'" AND date <= "'.$dateRight.'" GROUP BY operator_id')->fetchAll(PDO::FETCH_ASSOC);
    $result = array();
    foreach ($operators as $item)
    {
      foreach ($audios as $item2)
      {
        if ($item['id'] == $item2['operator_id'])
        {
          $result[] = round($item2['duration'] / 60000, 2);
        }
      }
    }

    $labels2 = array();
    $categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($categories as $item)
    {
      $ids[] = $item['id'];
      $labels2[] = $item['name'];
    }

    $audios = $pdo->query('SELECT * FROM audios WHERE date >= "'.$dateLeft.'" AND date <= "'.$dateRight.'"')->fetchAll(PDO::FETCH_ASSOC);
    $result2 = array();
    $avg_num = array();
    foreach ($categories as $item)
    {
      foreach ($audios as $item2)
      {
        $cats = json_decode($item2['categories'], JSON_OBJECT_AS_ARRAY);
        if (in_array($item['id'], $cats))
        {
          $avg_num[$item['id']][] = $item2['duration'];
        }
      }
      if (isset($avg_num[$item['id']]))
      {
        $result2[] = round(array_sum($avg_num[$item['id']]) / count($avg_num[$item['id']]) / 60000, 2);
      }
      else
      {
        $result2[] = 0;
      }
    }

    return json_encode(
      array(
        'left' => array(
          'items' => $labels,
          'datas' => $result,
          'title' => 'Средняя продолжительность разговора в минутах',
          'chartTitle' => 'График по операторам за',
          'color' => 'rgba(138, 30, 148, 1)',
          'label' => 'Минут'
        ),
        'right' => array(
          'items' => $labels2,
          'datas' => $result2,
          'title' => 'Средняя продолжительность разговора в минутах',
          'chartTitle' => 'График по категориям за',
          'color' => 'rgba(54, 162, 235, 1)',
          'label' => 'Минут'
        )
      )
    );
  }

  function category(){
    global $pdo;
    $category = array();
    if (isset($_POST['remove'])){//action=remove
      $sql = "DELETE FROM categories WHERE id=?";
      $pdo->prepare($sql)->execute(array($_POST['remove']));
      return $_POST['remove'];
    }
    else
    {
      if (isset($_POST['id'])){ //action=get_category
        if (isset($_POST['name']) && isset($_POST['keywords'])){
          if ($_POST['id'] != 0){
            $sql = "UPDATE categories SET name=?, keywords=? WHERE id=?";
            $pdo->prepare($sql)->execute(array($_POST['name'], $_POST['keywords'],  $_POST['id']));
          }
          else
          {
            $sql = 'INSERT INTO categories (name, keywords) VALUES (?,?)';
            $pdo->prepare($sql)->execute(array($_POST['name'], $_POST['keywords']));
          }
        }
        else
        {
          $category = $pdo->query('SELECT * FROM categories WHERE id = ' . $_POST['id'])->fetchAll(PDO::FETCH_ASSOC);
        }
      } else {//action=list_categories
        $category = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
      }
      return json_encode($category);
    }
  }


  function table_get () {
    global $pdo;
    $result = array();

    $categorys = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
    $operators = $pdo->query('SELECT * FROM operators')->fetchAll(PDO::FETCH_ASSOC);

    $dateLeft_parse = explode('.', $_POST['date_left']);
    $dateLeft = new DateTime();
    $dateLeft->setDate($dateLeft_parse[2], $dateLeft_parse[1], $dateLeft_parse[0]);
    $dateLeft = $dateLeft->format('Y-m-d h:i:s');
    $dateRight_parse = explode('.', $_POST['date_right']);
    $dateRight = new DateTime();
    $dateRight->setDate($dateRight_parse[2], $dateRight_parse[1], $dateRight_parse[0]);
    $dateRight = $dateRight->format('Y-m-d h:i:s');

    if (!empty($_POST['filter']) && !empty($_POST['name'])){
      //filter=category
      if ($_POST['filter'] == 'category'){
        $filter = '';
        foreach ($categorys as $item) {
          if ($item['name'] == $_POST['name']) {
            $filter = $item['id'];
            break;
          }
        }
        if ($_POST['param'] == 'emotional') {
          $array = $pdo->query("SELECT * FROM audios WHERE categories LIKE '%" . $filter . "%' AND emotional=1 AND date >= '".$dateLeft."' AND date <= '".$dateRight."'")->fetchAll(PDO::FETCH_ASSOC);
        } else if ($_POST['param'] == 'pauses') {
          $query = $pdo->query("SELECT * FROM audios WHERE categories LIKE '%" . $filter . "%' AND date >= '".$dateLeft."' AND date <= '".$dateRight."'")->fetchAll(PDO::FETCH_ASSOC);
          $array = array();
          foreach ($query as $item) {
            $times = json_decode($item['pauses'], JSON_OBJECT_AS_ARRAY);
            foreach ($times as $time) {
              if ($time['duration'] > 60000) {
                $array[] = $item;
                break;
              }
            }
          }
        } else if ($_POST['param'] == 'duration'){ //BAG TEMP
          $array = $pdo->query("SELECT *, categories, AVG(duration) as duration FROM audios WHERE categories LIKE '%" . $filter . "%' AND date >= '".$dateLeft."' AND date <= '".$dateRight."' GROUP BY categories")->fetchAll(PDO::FETCH_ASSOC);
        } else {
          $array = $pdo->query("SELECT * FROM audios WHERE categories LIKE '%" . $filter . "%' AND date >= '".$dateLeft."' AND date <= '".$dateRight."'")->fetchAll(PDO::FETCH_ASSOC);
        }
      }
      //endOf filter=category

      //filter=user
      if ($_POST['filter'] == 'user') {
        $filter = '';
        foreach ($operators as $item) {
          if ($item['name'] == $_POST['name']) {
            $filter = $item['id'];
            break;
          }
        }
        if ($_POST['param'] == 'emotional'){
          $array = $pdo->query("SELECT * FROM audios WHERE operator_id=".$filter." AND emotional=1 AND date >= '".$dateLeft."' AND date <= '".$dateRight."'")->fetchAll(PDO::FETCH_ASSOC);
        } else if ($_POST['param'] == 'pauses') {
          $query = $pdo->query("SELECT * FROM audios WHERE operator_id=".$filter." AND date >= '".$dateLeft."' AND date <= '".$dateRight."'")->fetchAll(PDO::FETCH_ASSOC);
          $array = array();
          foreach ($query as $item) {
            if ($item['pauses'] != '')
            {
              $times = json_decode($item['pauses'], JSON_OBJECT_AS_ARRAY);
              foreach ($times as $time) {
                if ($time['duration'] > 10000) {
                  $array[] = $item;
                  break;
                }
              }
            }
          }
        } else if ($_POST['param'] == 'duration') { //BAG_TEMP
          $array = $pdo->query("SELECT *, categories, AVG(duration) as duration FROM audios WHERE operator_id=".$filter." AND date >= '".$dateLeft."' AND date <= '".$dateRight."' GROUP BY categories")->fetchAll(PDO::FETCH_ASSOC);
        } else {
          $array = $pdo->query("SELECT * FROM audios WHERE operator_id=".$filter." AND date >= '".$dateLeft."' AND date <= '".$dateRight."'")->fetchAll(PDO::FETCH_ASSOC);
        }
      }
      //endOf filter=user

    } else {
      $array = $pdo->query("SELECT * FROM audios WHERE date >= '".$dateLeft."' AND date <= '".$dateRight."'")->fetchAll(PDO::FETCH_ASSOC);
    }


    foreach ($array as $item) {
      $date = '';
      $emotional = $item['emotional'] == 1 ? 'Да' : 'Нет';
      $categories = '';
      $tags = '';
      $operator = '';
      $date = $item['date'];
      foreach ($categorys as $item2) {
        $cats = json_decode($item['categories'], JSON_OBJECT_AS_ARRAY);
        if (in_array($item2['id'], $cats)) {
          $categories .= $item2['name'] . ', ';
          $tags = $item2['keywords'];
        }
      }
      if ($categories != '')
      {
        $categories = substr($categories, 0, -2);
      }
      foreach ($operators as $item2) {
        if ($item2['id'] == $item['operator_id']) {
          $operator = $item2['name'];
        }
      }
      $item['duration'] = round($item['duration'] / 60000, 2) . ' мин';
      $array2 = array($item['id'], $item['name'], $operator, $date, $item['duration'], $emotional, $categories, $tags);

      if (!empty($item['info'])) $result[] = $array2;
    }


    return json_encode($result);
  }

?>
