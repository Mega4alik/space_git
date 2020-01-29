<div class="w-full p-8 pt-4">
  <div class="w-full text-xl text-gray-700">Взаимодействия</div>
  <select class="w-full border border-gray-500 p-2 text-gray-700 rounded mt-6 outline-none text-base" v-model="filter" @change="filter_set">
    <option value="0" default>График по категориям</option>
    <option value="1">Повышение голоса, эмоциональные взаимодействия</option>
    <option value="2">Длительные паузы</option>
    <option value="3">Продолжительные разговоры</option>
  </select>
  <div class="w-full flex flex wrap mt-6">
    <div class="w-full sm:w-full md:w-full lg:w-1/2 xl:w-1/2 pr-2">
      <div class="w-full border border-gray-400 rounded p-4">
        <div class="w-full text-base text-gray-600 pb-4" v-text="lChartTitle"></div>
        <select class="w-full p-2 border border-gray-400 text-gray-700 rounded outline-none mb-6">
          <option>Месяц</option>
          <option>Неделю</option>
          <option>День</option>
        </select>
        <div class="w-full" id="lChart" style="height: 20rem"></div>
      </div>
    </div>
    <div class="w-full sm:w-full md:w-full lg:w-1/2 xl:w-1/2 pl-2">
      <div class="w-full border border-gray-400 rounded p-4">
        <div class="w-full text-base text-gray-600 pb-4" v-text="rChartTitle"></div>
        <select class="w-full p-2 border border-gray-400 text-gray-700 rounded outline-none mb-6" v-if="filter == 0" v-model="category" @change="filter_set_1()">
          <option v-for="item in categories" :value="item.id" v-text="item.name"></option>
        </select>
        <select class="w-full p-2 border border-gray-400 text-gray-700 rounded outline-none mb-6" v-if="filter != 0">
          <option>Месяц</option>
          <option>Неделю</option>
          <option>День</option>
        </select>
        <div class="w-full" id="rChart" style="height: 20rem"></div>
      </div>
    </div>
  </div>
  <div class="w-full mt-6 border border-gray-400 rounded p-4">
    <div class="w-full flex">
      <div class="w-1/2 text-gray-700">
        Показывать по
        <select class="w-20 p-2 border border-gray-400 text-gray-700 rounded outline-none mx-2" v-model="counts">
          <option>10</option>
          <option>25</option>
          <option>50</option>
          <option>100</option>
        </select>
        записей
      </div>
      <div class="w-1/2 text-gray-700 flex flex-grow-0">
        <input type="text" class="flex-grow p-2 px-4 border border-gray-400 text-gray-700 rounded outline-none mr-4" placeholder="Поиск..." />
        <div class="w-12 bg-blue-500 rounded py-2 text-center text-gray-100 cursor-pointer hover:bg-blue-600">
          <i class="icon icon-search"></i>
        </div>
      </div>
    </div>
    <div class="w-full flex flex-grow-0 pt-4">
      <table class="w-full">
        <tr class="bg-gray-200">
          <td align="left" class="p-2 text-sm">#</td>
          <td align="left" class="p-2 text-sm">Файл</td>
          <td align="left" class="p-2 text-sm">Сотрудник</td>
          <td align="left" class="p-2 text-sm">Время загрузки аудио</td>
          <td align="left" class="p-2 text-sm">Продолжительность</td>
          <td align="left" class="p-2 text-sm">Эмоции</td>
          <td align="left" class="p-2 text-sm">Категории</td>
          <td align="left" class="p-2 text-sm">Найденные ключевые слова</td>
        </tr>
        <tr v-for="item in tables">
          <td align="left" class="p-2 text-sm" v-text="item.id"></td>
          <td align="left" class="p-2 text-sm" v-text="item.name"></td>
          <td align="left" class="p-2 text-sm" v-text="item.operator_id"></td>
          <td align="left" class="p-2 text-sm" v-text="item.date"></td>
          <td align="left" class="p-2 text-sm" v-text="item.date"></td>
          <td align="left" class="p-2 text-sm" v-text="item.emotional == 1 ? 'Да' : 'Нет'"></td>
        </tr>
    </div>
  </div>
</div>

<script src="./assets/js/calls.js"></script>
