<?php

namespace App\Helpers\Commons;

class ApiUrl
{
  protected static $apiUrlKbn = 'https://api-kbn.com/app/api/apikbn.asp';
  protected static $apiUrl = 'http://202.43.163.251:33338/app/api/apikbn.asp';
  protected static $basePath = 'http://202.43.163.251:33338/app/api/apiwms.asp'; // tidak perlu login

  public static function baseUrl()
  {
    return self::$basePath;
  }


  public static function get()
  {
    return self::$apiUrl;
  }

  public static function getWarehouseApiUrl()
  {
    return self::$basePath;
  }

  public static function getWarehouseUrl(): string
  {
    return self::$apiUrl . '?api=warehouse';
  }

  public static function grades()
  {
    return self::$apiUrl . '?api=grade';
  }

  public static function allGrades()
  {
    return self::$apiUrl . '?api=gradeall';
  }

  public static function urbanVillages()
  {
    return self::$apiUrl . '?api=wilayah';
  }

  public static function contacts()
  {
    // return env('API_URL') . '/api/contacts';
    return self::$apiUrl . '?api=contact';
  }

  public static function salesOrderProducts()
  {
    // return env('API_URL') . '/api/contacts';
    // http://202.43.173.27:7172/app/api/apikbn.asp?&sesid=2D847DB512735D34FB0012F5A0A272B93B7D398B1000000003&WAREHOUSE_ID=3&TYPE=UN&CONTACT_ID=126&search=ugstt
    return self::$apiUrl . '?api=so_detail';
  }

  public static function tops()
  {
    // return env('API_URL') . '/api/expeditions';
    return env('API_URL') . '/api/apikbn/tops';
  }

  public static function warehouses()
  {
    // return env('API_URL') . '/api/warehouses';
    // return env('API_URL') . '/api/apikbn/warehouses';
    return self::$apiUrl . '?api=warehouse';
  }

  public static function expeditions()
  {
    // return env('API_URL') . '/api/expeditions';
    // return env('API_URL') . '/api/apikbn/expeditions';
    return self::$apiUrl . '?api=expedition';
  }

  public static function dropPoints()
  {
    // return env('API_URL') . '/api/droppoints';
    // return env('API_URL') . '/api/apikbn/drop-points';
    return self::$apiUrl . '?api=drop-point';
  }



  public static function salesOrderRequests()
  {
    return env('API_URL') . '/api/sales-order-requests';
  }

  // Master API
  public static function products()
  {
    // return self::$apiUrl . '?api=item';
    return env('API_URL') . '/api/products';
  }

  public static function businessCategories()
  {
    // return env('API_URL') . '/api/business-categories';
    // return env('API_URL') . '/api/apikbn/business-categories';
    return self::$apiUrl . '?api=product-category1';
  }

  public static function productCategories()
  {
    // return env('API_URL') . '/api/business-categories';
    // return env('API_URL') . '/api/apikbn/business-categories';
    return self::$apiUrl . '?api=product-category2';
  }

  public static function productBrands()
  {
    // return self::$apiUrl . '?api=kategori4';
    return self::$apiUrl . '?api=product-category4';
  }

  public static function productBrandCategories()
  {
    // return self::$apiUrl . '?api=kategori5';
    return self::$apiUrl . '?api=product-category5';
  }

  // Transaction Api
  public static function stocks()
  {
    return self::$apiUrl . '?api=stok';
  }

  // Report Api
  public static function topProductByRevenues()
  {
    return self::$apiUrl . '?api=topitemomset';
  }

  public static function topProductByQuantities()
  {
    return self::$apiUrl . '?api=topitemqty';
  }

  public static function topBrandByRevenues()
  {
    return self::$apiUrl . '?api=topkategori4omset';
  }

  public static function topBrandByQuantities()
  {
    return self::$apiUrl . '?api=topkategori4qty';
  }
}
