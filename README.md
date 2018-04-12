# seleksi

[![Join the chat at https://gitter.im/seleksi/Lobby](https://badges.gitter.im/seleksi/Lobby.svg)](https://gitter.im/seleksi/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bantenprov/seleksi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bantenprov/seleksi/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/bantenprov/seleksi/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bantenprov/seleksi/build-status/master)
[![Latest Stable Version](https://poser.pugx.org/bantenprov/seleksi/v/stable)](https://packagist.org/packages/bantenprov/seleksi)
[![Total Downloads](https://poser.pugx.org/bantenprov/seleksi/downloads)](https://packagist.org/packages/bantenprov/seleksi)
[![Latest Unstable Version](https://poser.pugx.org/bantenprov/seleksi/v/unstable)](https://packagist.org/packages/bantenprov/seleksi)
[![License](https://poser.pugx.org/bantenprov/seleksi/license)](https://packagist.org/packages/bantenprov/seleksi)
[![Monthly Downloads](https://poser.pugx.org/bantenprov/seleksi/d/monthly)](https://packagist.org/packages/bantenprov/seleksi)
[![Daily Downloads](https://poser.pugx.org/bantenprov/seleksi/d/daily)](https://packagist.org/packages/bantenprov/seleksi)

Seleksi

### Install via composer

- Development snapshot

```bash
$ composer require bantenprov/seleksi:dev-master
```

- Latest release:

```bash
$ composer require bantenprov/seleksi
```

### Download via github

```bash
$ git clone https://github.com/bantenprov/seleksi.git
```

#### Edit `config/app.php` :

```php
'providers' => [

    /*
    * Laravel Framework Service Providers...
    */
    Illuminate\Auth\AuthServiceProvider::class,
    Illuminate\Broadcasting\BroadcastServiceProvider::class,
    Illuminate\Bus\BusServiceProvider::class,
    Illuminate\Cache\CacheServiceProvider::class,
    Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
    Illuminate\Cookie\CookieServiceProvider::class,
    //....
    Bantenprov\Seleksi\SeleksiServiceProvider::class,
```

#### Lakukan migrate :

```bash
$ php artisan migrate
```

#### Publish database seeder :

```bash
$ php artisan vendor:publish --tag=seleksi-seeds
```

#### Lakukan auto dump :

```bash
$ composer dump-autoload
```

#### Lakukan seeding :

```bash
$ php artisan db:seed --class=BantenprovSeleksiSeeder
```

#### Lakukan publish component vue :

```bash
$ php artisan vendor:publish --tag=seleksi-assets
$ php artisan vendor:publish --tag=seleksi-public
```
#### Tambahkan route di dalam file : `resources/assets/js/routes.js` :

```javascript
{
    path: '/dashboard',
    redirect: '/dashboard/home',
    component: layout('Default'),
    children: [
        //== ...
        {
         path: '/dashboard/seleksi',
         components: {
            main: resolve => require(['./components/views/bantenprov/seleksi/DashboardSeleksi.vue'], resolve),
            navbar: resolve => require(['./components/Navbar.vue'], resolve),
            sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
          },
          meta: {
            title: "Seleksi"
           }
       },
        //== ...
    ]
},
```

```javascript
{
    path: '/admin',
    redirect: '/admin/dashboard/home',
    component: layout('Default'),
    children: [
        //== ...
        {
            path: '/admin/seleksi',
            components: {
                main: resolve => require(['./components/bantenprov/seleksi/Seleksi.index.vue'], resolve),
                navbar: resolve => require(['./components/Navbar.vue'], resolve),
                sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Seleksi"
            }
        },
        {
            path: '/admin/seleksi/create',
            components: {
                main: resolve => require(['./components/bantenprov/seleksi/Seleksi.add.vue'], resolve),
                navbar: resolve => require(['./components/Navbar.vue'], resolve),
                sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Add Seleksi"
            }
        },
        {
            path: '/admin/seleksi/:id',
            components: {
                main: resolve => require(['./components/bantenprov/seleksi/Seleksi.show.vue'], resolve),
                navbar: resolve => require(['./components/Navbar.vue'], resolve),
                sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "View Seleksi"
            }
        },
        {
            path: '/admin/seleksi/:id/edit',
            components: {
                main: resolve => require(['./components/bantenprov/seleksi/Seleksi.edit.vue'], resolve),
                navbar: resolve => require(['./components/Navbar.vue'], resolve),
                sidebar: resolve => require(['./components/Sidebar.vue'], resolve)
            },
            meta: {
                title: "Edit Seleksi"
            }
        },
        //== ...
    ]
},
```
#### Edit menu `resources/assets/js/menu.js`

```javascript
{
    name: 'Dashboard',
    icon: 'fa fa-dashboard',
    childType: 'collapse',
    childItem: [
        //== ...
        {
        name: 'Seleksi',
        link: '/dashboard/seleksi',
        icon: 'fa fa-angle-double-right'
        },
        //== ...
    ]
},
```

```javascript
{
    name: 'Admin',
    icon: 'fa fa-lock',
    childType: 'collapse',
    childItem: [
        //== ...
        {
        name: 'Seleksi',
        link: '/admin/seleksi',
        icon: 'fa fa-angle-double-right'
        },
        //== ...
    ]
},
```

#### Tambahkan components `resources/assets/js/components.js` :

```javascript
//== Seleksi

import Seleksi from './components/bantenprov/seleksi/Seleksi.chart.vue';
Vue.component('echarts-seleksi', Seleksi);

import SeleksiKota from './components/bantenprov/seleksi/SeleksiKota.chart.vue';
Vue.component('echarts-seleksi-kota', SeleksiKota);

import SeleksiTahun from './components/bantenprov/seleksi/SeleksiTahun.chart.vue';
Vue.component('echarts-seleksi-tahun', SeleksiTahun);

import SeleksiAdminShow from './components/bantenprov/seleksi/SeleksiAdmin.show.vue';
Vue.component('admin-view-seleksi-tahun', SeleksiAdminShow);

//== Echarts Seleksi

import SeleksiBar01 from './components/views/bantenprov/seleksi/SeleksiBar01.vue';
Vue.component('seleksi-bar-01', SeleksiBar01);

import SeleksiBar02 from './components/views/bantenprov/seleksi/SeleksiBar02.vue';
Vue.component('seleksi-bar-02', SeleksiBar02);

//== mini bar charts
import SeleksiBar03 from './components/views/bantenprov/seleksi/SeleksiBar03.vue';
Vue.component('seleksi-bar-03', SeleksiBar03);

import SeleksiPie01 from './components/views/bantenprov/seleksi/SeleksiPie01.vue';
Vue.component('seleksi-pie-01', SeleksiPie01);

import Seleksie02 from './components/views/bantenprov/seleksi/SeleksiPie02.vue';
Vue.component('seleksi-pie-02', Seleksie02);

//== mini pie charts


import SeleksiPie03 from './components/views/bantenprov/seleksi/SeleksiPie03.vue';
Vue.component('seleksi-pie-03', SeleksiPie03);

```

