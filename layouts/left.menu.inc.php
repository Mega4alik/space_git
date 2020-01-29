<div class="fixed left-0  flex-none bg-gray-100 overflow-hidden" v-bind:class="{'w-1/5': menuOpen, 'w-12': !menuOpen}">
  <div class="w-full" v-if="menuOpen">
    <div class="w-full uppercase font-bold text-base text-blue-600 pt-8 px-8">
      Speech Analytics <i class="icon icon-radio-unchecked ml-4 mt-1"></i>
    </div>
    <div class="w-full text-base pt-8 px-8 flex">
      <div class="w-6 text-blue-600">
        <i class="icon icon-phone cursor-pointer"></i>
      </div>
      <div class="w-auto pl-4">
        <a href="/" class="text-blue-500 hover:text-blue-800 cursor-pointer">
          Записи разговоров
        </a>
      </div>
    </div>
    <div class="w-full text-base pt-8 px-8 flex">
      <div class="w-6 text-blue-600">
        <i class="menu-icon icon-format_list_bulleted"></i>
      </div>
      <div class="w-auto pl-4">
        <a href="/category.php" class="text-blue-500 hover:text-blue-800 cursor-pointer">
          Категории
        </a>
      </div>
    </div>
  </div>
  <div class="w-full" v-if="!menuOpen">
    <div class="w-full text-base text-blue-600 pt-8 text-center">
      <i class="icon icon-radio-unchecked"></i>
    </div>
    <div class="w-full text-base text-blue-600 pt-8 text-center">
      <a href="/"><i class="icon icon-phone cursor-pointer text-blue-600 hover:text-blue-800"></i></a>
    </div>
    <div class="w-full text-base pt-8 text-center">
      <a href="/category.php"><i class="icon icon-list cursor-pointer text-blue-600 hover:text-blue-800"></i></a>
    </div>
  </div>
</div>
