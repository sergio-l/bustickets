<?php

Meta::addJs('custom', asset('/js/admin/script.js'),'admin-default');
Meta::addJs('custom-vue', asset('/js/admin/vue-comp.js'),'admin-default');

//PackageManager::add('stopRefresh')->js('tree',asset('js/script.js'), ['admin-default'], true);